<?php

namespace App\Services\Types\Command\Map;

class MapCommandService
{
    /**
     * Create response message for weather service
     */
    public function createResponse(array $data)
    {
        extract($data);

        return "[rp aid=$fromId to=$roomId-$msgId]\n".'Keangnam Office Map:'.PHP_EOL.' - 13F (Fizz + Buzz): https://goo.gl/tv3Gz5'.PHP_EOL.' - 18F: https://goo.gl/U5FwQw'.PHP_EOL;
    }
}
