<?php

namespace App\Services\Types;

trait ServiceAuthorization
{
    protected function authorize($accountId)
    {
        return in_array($accountId, [env('ADMIN_CW_ID'), env('HBOT_CW_ID'), env('SUB_ADMIN_CW_ID')]);
    }
}
