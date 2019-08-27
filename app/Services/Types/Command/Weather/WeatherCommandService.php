<?php

namespace App\Services\Types\Command\Weather;

use Cmfcmf\OpenWeatherMap;
use GuzzleHttp\Client;

class WeatherCommandService
{
    /**
     * Create response message for weather service
     */
    public function createResponse($data)
    {
        try {
            $apiUrl = 'http://dataservice.accuweather.com/currentconditions/v1/353412';
            $client = new Client;
            $response = $client->request('GET', $apiUrl, [
                'query' => [
                    'language' => 'vi',
                    'apikey' => env('ACCU_WEAHTER_API_KEY'),
                ],
            ]);
            $content = json_decode($response->getBody()->getContents())[0];
            $temperature = $content->Temperature->Metric->Value;
            $description = $content->WeatherText;

            return "[rp aid=$fromId to=$roomId-$msgId]\n"
                . "Nhiệt độ hiện tại: $temperature độ C - $description";
        } catch (\Exception $e) {
            logger($e);
        }
    }
}
