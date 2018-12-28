<?php

namespace App\Services\Types\User;

use App\Models\User;
use App\Services\Types\ServiceAuthorization;

class RandomService
{
    use ServiceAuthorization;

    /**
     * Create response message for permission service
     */
    public function createResponse($data)
    {
        extract($data);
        if (!$this->authorize($fromId)) {
            return '[To:' . $fromId . ']' . PHP_EOL
                . ' (nonono)';
        }

        $choosenMemberAccId = $this->chooseRandomMember();
        $msg = $this->sendCongratulations($choosenMemberAccId);

        return $msg;
    }

    /**
     * Choose a random user's account id from database
     *
     * @return int
     */
    private function chooseRandomMember()
    {
        $members = User::where([
            ['role', 'member'],
            ['priority', '>', '0'],
        ])
            ->get();
        $random = $this->transformMemberData($members);
        shuffle($random);
        $choosenMemberAccId = $random[array_rand($random)];

        return $choosenMemberAccId;
    }

    /**
     * Generate random account id array from member list
     *
     * @param array $members
     *
     * @return array
     */
    private function transformMemberData($members)
    {
        $random = [];
        foreach ($members as $member) {
            if ($member['priority'] == 1) {
                array_push($random, (int) $member['account_id']);
            } else {
                $tmp = array_fill(0, (int) $member['priority'], (int) $member['account_id']);
                $random = array_merge($random, $tmp);
            }
        }

        return $random;
    }

    /**
     * Generate response message
     *
     * @param int $memberId
     *
     * @return string
     */
    private function sendCongratulations($memberId)
    {
        return '[To:' . $memberId . '] ' . PHP_EOL
            . 'Chúc mừng bạn đã là người may mắn được lựa chọn (tangqua)';
    }
}
