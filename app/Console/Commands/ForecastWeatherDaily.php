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
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Forecast today weather';

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
            $apiUrl = 'http://dataservice.accuweather.com/forecasts/v1/hourly/12hour/353412';
            $client = new Client;
            $response = $client->request('GET', $apiUrl, [
                'query' => [
                    'language' => 'vi',
                    'apikey' => env('ACCU_WEAHTER_API_KEY'),
                    'metric' => 'true',
                ],
            ]);
            $content = json_decode($response->getBody()->getContents());
            $forecastWeather = $this->extractTodayWeather($content);

            // Send message
            ChatworkSDK::setApiKey(env('CHATWORK_API_KEY'));
            $room = new ChatworkRoom(env('TEAM_AN_TRUA_FS'));
            $room->sendMessage($this->constructMessage($forecastWeather));
        } catch (\Exception $e) {
            logger($e);
        }
    }

    /**
     * Extract data for only today weather
     *
     * @param json $weathers
     *
     * @return array
     */
    protected function extractTodayWeather($weathers)
    {
        $result = [
            'min_temp' => 99,
            'max_temp' => 0,
            'desc' => [],
        ];
        foreach ($weathers as $weather) {
            if ($result['min_temp'] > $weather->Temperature->Value) {
                $result['min_temp'] = $weather->Temperature->Value;
            }
            if ($result['max_temp'] < $weather->Temperature->Value) {
                $result['max_temp'] = $weather->Temperature->Value;
            }
            $result['desc'][] = $weather->IconPhrase;
        }

        $result['desc'] = implode(', ', array_unique($result['desc']));

        return $result;
    }

    /**
     * Construct response message for chatwork
     *
     * @param array $forecastWeather
     *
     * @return string
     */
    protected function constructMessage($forecastWeather)
    {
        return '[toall] Dự báo thời tiết ngày ' . Carbon::today()->format('d/m/Y') . ':' . PHP_EOL
            . '- Nhiệt độ: ' . $forecastWeather['min_temp'] . ' đến ' . $forecastWeather['max_temp'] . ' độ C' . PHP_EOL
            . '- Thời tiết trong 12h tới: ' . $forecastWeather['desc'];
    }
}
