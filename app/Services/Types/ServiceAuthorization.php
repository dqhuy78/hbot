<?php

namespace App\Services\Types;

use App\Models\User;

trait ServiceAuthorization
{
    protected function authorize($accountId)
    {
        return in_array($accountId, [env('ADMIN_CW_ID'), env('HBOT_CW_ID'), env('SUB_ADMIN_CW_ID')]);
    }
}
