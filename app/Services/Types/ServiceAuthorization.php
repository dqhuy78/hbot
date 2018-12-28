<?php

namespace App\Services\Types;

use App\Models\User;

trait ServiceAuthorization
{
    protected function authorize($accountId)
    {
        $user = User::where('account_id', $accountId)->first();

        return $user->isAdmin();
    }
}
