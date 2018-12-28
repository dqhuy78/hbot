<?php

namespace App\Services\Types\Command\Weather;

use Cmfcmf\OpenWeatherMap;

class WeatherCommandService
{
    /**
     * Create response message for weather service
     */
    public function createResponse($data)
    {
        $weather = $this->getCurrentWeather();
        extract($weather);
        extract($data);

        return "[rp aid=$fromId to=$roomId-$msgId]\n"
            . "Nhiệt độ hiện tại: $temperature độ C - $desc";
    }

    /**
     * Get current time weather
     *
     * @return array
     */
    public function getCurrentWeather()
    {
        try {
            $weatherApi = new OpenWeatherMap(env('OPEN_WEATHER_API_KEY'));
            $weather = $weatherApi->getWeather('Hanoi', 'metric', 'vi');

            return [
                'temperature' => $weather->temperature->getValue(),
                'desc' => ucfirst(strtolower($weather->clouds->getDescription())),
            ];
        } catch (\Exception $e) {
            logger($e);
        }
    }
}
