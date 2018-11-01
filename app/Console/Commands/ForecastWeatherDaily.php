<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Cmfcmf\OpenWeatherMap;
use Carbon\Carbon;
use wataridori\ChatworkSDK\ChatworkSDK;
use wataridori\ChatworkSDK\ChatworkRoom;

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
            // Get daily weather
            $weatherApi = new OpenWeatherMap(env('OPEN_WEATHER_API_KEY'));
            $forecastWeather = $weatherApi->getRawHourlyForecastData('Hanoi', 'metric', 'vi', '', 'json');
            $forecastWeather = $this->extractTodayWeather($forecastWeather);

            // Get current weather
            $weather = $weatherApi->getWeather('Hanoi', 'metric', 'vi');
            $currentWeather = $this->extractCurrentWeather($weather);



            // Send message
            ChatworkSDK::setApiKey(env('CHATWORK_API_KEY'));
            $room = new ChatworkRoom(env('TEAM_AN_TRUA_FS'));
            $room->sendMessage($this->constructMessage($forecastWeather, $currentWeather));
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
        $result = [];
        $weathers = json_decode($weathers, true);
        foreach ($weathers['list'] as $weather) {
            $day = Carbon::parse($weather['dt_txt']);
            if ($day->isToday()) {
                $result['min_temp'][] = $weather['main']['temp_min'];
                $result['max_temp'][] = $weather['main']['temp_max'];
                $result['desc'][] = $weather['weather'][0]['description'];
            } else {
                break;
            }
        }

        return [
            'min_temp' => round(min($result['min_temp']), 2),
            'max_temp' => round(max($result['max_temp']), 2),
            'desc' => implode(', ', array_unique($result['desc'])),
        ];
    }

    /**
     * Extract data for only current weather
     *
     * @param json $weathers
     *
     * @return array
     */
    protected function extractCurrentWeather($weather)
    {
        return [
            'temperature' => $weather->temperature->getValue(),
            'desc' => ucfirst(strtolower($weather->clouds->getDescription())),
        ];
    }

    /**
     * Construct response message for chatwork
     *
     * @param array $forecastWeather
     *
     * @return string
     */
    protected function constructMessage($today, $current)
    {
        return '[toall] Dự báo thời tiết ngày ' . Carbon::today()->format('d/m/Y') . ':' . PHP_EOL
            . '- Nhiệt độ hiện tại: ' . $current['temperature'] . ' độ C - ' . $current['desc'] . PHP_EOL
            . '- Nhiệt độ trong ngày: ' . $today['min_temp'] . ' đến ' . $today['max_temp'] . ' độ C' . PHP_EOL
            . '- Thời tiết: ' . $today['desc'];
    }
}
