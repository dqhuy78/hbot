<?php

namespace App\Services\Types\Target;

use App\Services\Types\Target\AbstractTargetService;

class LickTargetService extends AbstractTargetService
{
    protected $emo = [
        '(liemmanhinh3)',
        '(liemliemliem)',
        '(liemmanhinh5)',
        '(liemmanhinh6)',
        '(liemliemliem)',
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
                . ' (ngu)';
        }

        if (!$this->exceptTarget($targetUserId)) {
            $emotionNo1 = $emotionNo2 = $emotionNo3 = $this->emo[array_rand($this->emo)];

            return '[To:' . $targetUserId . ']' . PHP_EOL
                . '(aigo2) ' . PHP_EOL
                . PHP_EOL
                . $emotionNo1 . ' ' . $emotionNo2 . ' ' . $emotionNo3;
        } else {
            return $this->getCounterResponse($fromId);
        }
    }
}
