<?php

namespace App\Services\Types\Target;

class SlapTargetService extends AbstractTargetService
{
    protected $emo = [
        '(sucvat)',
        '(xien)',
        '(2tat)',
        '(tat2)',
        '(tat3)',
        '(tat4)',
        '(bopco)',
    ];

    protected $intro = [
        '(cakhiavuivl)',
        '(choino)',
        '(tranhra)',
        '(laiday3)',
    ];

    /**
     * Create response message for weather service
     */
    public function createResponse($data)
    {
        extract($data);
        $targetUserId = $this->extractTargetId($msg);

        if (!$targetUserId) {
            return '[To:'.$fromId.']'.PHP_EOL.' (kidding?)';
        }

        if (!$this->exceptTarget($targetUserId)) {
            $intro = $this->intro[array_rand($this->intro)];
            $emotionNo1 = $this->emo[array_rand($this->emo)];
            $emotionNo2 = $this->emo[array_rand($this->emo)];
            $emotionNo3 = $this->emo[array_rand($this->emo)];

            return '[To:'.$targetUserId.']'.PHP_EOL.$intro.PHP_EOL.PHP_EOL.$emotionNo1.' '.$emotionNo2.' '.$emotionNo3;
        }

        return $this->getCounterResponse($fromId);
    }
}
