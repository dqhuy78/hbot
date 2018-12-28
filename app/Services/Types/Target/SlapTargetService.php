<?php

namespace App\Services\Types\Target;

use App\Services\Types\Target\AbstractTargetService;

class SlapTargetService extends AbstractTargetService
{
    protected $emo = [
        '(songphi2)',
        '(sucvat)',
        '(phonglon)',
        '(xien)',
        '(2tat)',
        '(tat2)',
        '(tat3)',
        '(tat4)',
    ];

    /**
     * Create response message for weather service
     */
    public function createResponse($data)
    {
        extract($data);
        $targetUserId = $this->extractTargetId($msg);

        if (!$targetUserId) {
            return '[To:' . $fromId . ']' . PHP_EOL
                . ' (kidding?)';
        }

        if (!$this->exceptTarget($targetUserId)) {
            $emotionNo1 = $this->emo[array_rand($this->emo)];
            $emotionNo2 = $this->emo[array_rand($this->emo)];
            $emotionNo3 = $this->emo[array_rand($this->emo)];

            return '[To:' . $targetUserId . ']' . PHP_EOL
                . '(laiday3) ' . PHP_EOL
                . PHP_EOL
                . $emotionNo1 . ' ' . $emotionNo2 . ' ' . $emotionNo3;
        } else {
            return $this->getCounterResponse($fromId);
        }
    }
}
