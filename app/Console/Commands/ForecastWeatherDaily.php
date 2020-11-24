<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use wataridori\ChatworkSDK\ChatworkRoom;
use wataridori\ChatworkSDK\ChatworkSDK;

class ForecastWeatherDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:today';

    /**
     * The console command desceiption.
     *
     * @var string
     */
    protected $desceiption = 'Forecast today weather';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            // Get weather
            $apiUrl = 'http://dataservice.accuweather.com/currentconditions/v1/353412';
            $client = new Client();
            $response = $client->request('GET', $apiUrl, [
                'query' => [
                    'language' => 'vi',
                    'apikey' => env('ACCU_WEAHTER_API_KEY'),
                ],
            ]);
            $content = json_decode($response->getBody()->getContents())[0];
            $temperature = $content->Temperature->Metric->Value;
            $des = $content->WeatherText;

            // Send message
            ChatworkSDK::setApiKey(env('CHATWORK_API_KEY'));
            $room = new ChatworkRoom(env('TEAM_AN_TRUA_FS'));
            $room->sendMessage($this->constructMessage($temperature, $des));
        } catch (\Exception $e) {
            logger($e->getLine());
            logger($e->getMessage());
            logger($e);
        }
    }

    protected function constructMessage($temperature, $des)
    {
        $dayLeftToNoel = (int) round((strtotime('2020-12-24') - time()) / (60 * 60 * 24));
        $dayLeftToNewYear = (int) round((strtotime('2021-01-01') - time()) / (60 * 60 * 24));
        $dayLeftToNewYear2 = (int) round((strtotime('2021-02-12') - time()) / (60 * 60 * 24));
        $date = Carbon::today()->format('d/m/Y');

        return '[toall][title]Chào buổi sáng![/title]'
            . '(*) Dự báo thời tiết ngày ' . $date . ': Nhiệt độ: ' . $temperature . ' độ C, ' . $des . PHP_EOL . PHP_EOL
            . '(cracker) Còn ' . $dayLeftToNoel . ' ngày nữa là Noel (https://dqhuy78.github.io/countdown/)' . PHP_EOL
            . '(cracker) Còn ' . $dayLeftToNewYear . ' ngày nữa là tết dương (Comming soon...)' . PHP_EOL
            . '(cracker) Và ' . $dayLeftToNewYear2 . ' ngày nữa là tết âm lịch (Comming soon...)'
            . $this->getSalaryMessage()
            . '[/info]';
    }

    protected function getSalaryMessage()
    {
        $salaryDate = date("Y-m-d", strtotime("last day of this month"));
        $salaryDay = date('w', strtotime($salaryDate));
        if ($salaryDay === '0') {
            $salaryDate =  date('Y-m-d', strtotime('-2 day', strtotime($salaryDate)));
        } elseif ($salaryDay === '6') {
            $salaryDate = date('Y-m-d', strtotime('-1 day', strtotime($salaryDate)));
        }

        $dateDiff = date_diff(date_create(date('Y-m-d')), date_create(date('Y-m-d', strtotime($salaryDate))))
            ->format('%a');
        if ($dateDiff <= 7) {
            return PHP_EOL . PHP_EOL
                . $dateDiff . ' ngày nữa là có lương về rồi mọi người ơi (100diem)(100diem)(100diem)';
        }
        return '';
    }
}
