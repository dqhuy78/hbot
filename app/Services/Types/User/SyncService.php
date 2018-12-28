<?php

namespace App\Services\Types\User;

use App\Models\User;
use App\Services\Types\ServiceAuthorization;
use wataridori\ChatworkSDK\ChatworkRoom;
use wataridori\ChatworkSDK\ChatworkSDK;

class SyncService
{
    use ServiceAuthorization;

    /**
     * Create response message for weather service
     */
    public function createResponse($data)
    {
        extract($data);
        if (!$this->authorize($fromId)) {
            return '[To:' . $fromId . ']' . PHP_EOL
                . ' (nonono)';
        }

        $members = $this->getMemberList();
        $users = $this->normalize($members);
        $newUsersAccId = $this->checkForNewUser($users);
        $newUsers = $this->getNewUser($users, $newUsersAccId);
        User::insert($newUsers);

        return $this->generateReport($newUsers);
    }

    /**
     * Get member list from chatwork room
     */
    private function getMemberList()
    {
        ChatworkSDK::setApiKey(env('CHATWORK_API_KEY'));
        $room = new ChatworkRoom(env('TEAM_AN_TRUA_FS'));
        $members = $room->getMembers();

        return $members;
    }

    /**
     * Transfrom response data to array type
     *
     * @param wataridori\ChatworkSDK\ChatworkUser $member
     *
     * @return array
     */
    private function normalize($members)
    {
        $normalizeData = [];
        foreach ($members as $member) {
            $data['account_id'] = $member->account_id;
            $data['name'] = $member->name;
            $data['role'] = $member->role;
            array_push($normalizeData, $data);
        }

        return $normalizeData;
    }

    /**
     * Check for new member account id
     *
     * @param array $users
     *
     * @return array
     */
    private function checkForNewUser($users)
    {
        $usersAccId = array_column($users, 'account_id');
        $existsUsers = User::whereIn('account_id', $usersAccId)->get();
        $existsUsersAccId = $existsUsers->pluck('account_id')->all();
        $newUsersAccId = array_diff($usersAccId, $existsUsersAccId);

        return $newUsersAccId;
    }

    /**
     * Get new members data base on given accout id list
     *
     * @param array $users
     * @param array $newUsersAccId
     *
     * @return array
     */
    private function getNewUser($users, $newUsersAccId)
    {
        $result = [];
        foreach ($users as $user) {
            if (in_array($user['account_id'], $newUsersAccId)) {
                $result[] = $user;
            }
        }

        return $result;
    }

    /**
     * Transfrom user array to string report messsage
     *
     * @param array $users
     *
     * @return string
     */
    private function transformUserToString($users)
    {
        $result = '';
        foreach ($users as $user) {
            $result .= '      [To:' . $user['account_id'] . '] '
                . $user['name'] . PHP_EOL;
        }

        return $result;
    }

    /**
     * Generate report message
     *
     * @param array $newUsers
     *
     * @return string
     */
    private function generateReport($newUsers)
    {
        if (count($newUsers)) {
            return '[To:' . env('ADMIN_CW_ID') . ']' . PHP_EOL
            . 'Sync member complete! ' . count($newUsers) . ' new members found:' . PHP_EOL
            . $this->transformUserToString($newUsers);
        } else {
            return '[To:' . env('ADMIN_CW_ID') . ']' . PHP_EOL
                . 'Sync member complete! No new member found.';
        }
    }
}
