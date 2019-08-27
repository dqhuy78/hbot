<?php

namespace App\Services\Types\Target;

use App\Models\User;

abstract class AbstractTargetService
{
    /**
     * Extract user id from message
     *
     * @param string $msg
     *
     * @return string
     */
    protected function extractTargetId($msg)
    {
        $msgSegment = explode(':', $msg);
        $targetUserId = substr($msgSegment[2], 0, strpos($msgSegment[2], ']'));

        return $targetUserId;
    }

    /**
     * Chekc if target user is except list
     *
     * @param int $accountId
     *
     * @return boolean
     */
    protected function exceptTarget($accountId)
    {
        return in_array($accountId, [env('ADMIN_CW_ID'), env('HBOT_CW_ID'), env('SUB_ADMIN_CW_ID')]);
    }

    /**
     * Generate response message
     *
     * @param int $fromId
     *
     * @return string
     */
    protected function getCounterResponse($fromId)
    {
        return '[To:' . $fromId . ']' . PHP_EOL
            . ' (nonono)' . PHP_EOL
            . ' (tat2)(tat2)(tat2)';
    }
}
