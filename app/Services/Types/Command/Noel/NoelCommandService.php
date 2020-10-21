<?php

namespace App\Services\Types\Command\Noel;

use Illuminate\Support\Facades\Artisan;

class NoelCommandService
{
    /**
     * Create response message for weather service
     */
    public function createResponse($data)
    {
        logger($data);
        if (in_array($data['fromId'], [env('ADMIN_CW_ID'), env('HBOT_CW_ID'), env('SUB_ADMIN_CW_ID')])) {
            Artisan::call('weather:today');
        }
    }
}
