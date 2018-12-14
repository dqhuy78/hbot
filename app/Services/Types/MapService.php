<?php

namespace App\Services\Types;

use Cmfcmf\OpenWeatherMap;

class MapService
{
    /**
     * Create response message for weather service
     */
    public function createResponse($data)
    {
        extract($data);

        return "[rp aid=$userId to=$roomId-$messId]\n"
            . 'Keangnam Office Map:' . PHP_EOL
            . ' - 13F (Fizz + Buzz): https://goo.gl/tv3Gz5' . PHP_EOL
            . ' - 18F: https://goo.gl/U5FwQw' . PHP_EOL;
    }
}
