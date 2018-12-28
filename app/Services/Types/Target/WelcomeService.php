<?php

namespace App\Services\Types\Target;

use App\Services\Types\ServiceAuthorization;
use App\Services\Types\Target\AbstractTargetService;

class WelcomeService extends AbstractTargetService
{
    use ServiceAuthorization;
    /**
     * Create response message for weather service
     */
    public function createResponse($data)
    {
        extract($data);
        $targetUserId = $this->extractTargetId($msg);

        if ($this->authorize($fromId)) {
            return '[To:' . $targetUserId . ']' . PHP_EOL
                . 'Chào mừng bạn đến với box team ăn nhau FS.'
                . ' Bạn hãy giới thiệu về tên tuổi, ngày sinh, sở thích, ...'
                . '  để mọi người cùng biết nhé :D';
        }
    }
}
