<?php

namespace App\Http\Controllers;

use App\Services\ServiceEntry;
use Illuminate\Http\Request;
use wataridori\ChatworkSDK\ChatworkRoom;
use wataridori\ChatworkSDK\ChatworkSDK;

class ChatworkHookController extends Controller
{
    private $command = [
        // '{random}' => '\App\Services\Types\User\RandomService',
        // '{sync}' => '\App\Services\Types\User\SyncService',
        '{msg:' => '\App\Services\Types\User\ToService',
        '{weather}' => '\App\Services\Types\Command\Weather\WeatherCommandService',
        '{map}' => '\App\Services\Types\Command\Map\MapCommandService',
        '{welcome:' => '\App\Services\Types\Target\WelcomeService',
        '{tát:' => '\App\Services\Types\Target\SlapTargetService',
        '{liếm:' => '\App\Services\Types\Target\LickTargetService',
        '{noel}' => '\App\Services\Types\Command\Noel\NoelCommandService',

    ];

    private $chatEmo = [
        '(tat' => '\App\Services\Types\Emo\SlapEmoService',
        '(ngu' => '\App\Services\Types\Emo\SlapEmoService',
        ' điên ' => '\App\Services\Types\Emo\SlapEmoService',
        '(dam' => '\App\Services\Types\Emo\PunchEmoService',
        '(boxing' => '\App\Services\Types\Emo\PunchEmoService',
        '(songphi' => '\App\Services\Types\Emo\KickEmoService',
        '(da' => '\App\Services\Types\Emo\KickEmoService',
        '(danhnhau' => '\App\Services\Types\Emo\KillEmoService',
        '(kill' => '\App\Services\Types\Emo\KillEmoService',
        ' giet' => '\App\Services\Types\Emo\KillEmoService',
        '(hi)' => '\App\Services\Types\Emo\HiEmoService',
        '(hello)' => '\App\Services\Types\Emo\HiEmoService',
        'hey' => '\App\Services\Types\Emo\HiEmoService',
        'haha' => '\App\Services\Types\Emo\SmileEmoService',
        'hihi' => '\App\Services\Types\Emo\SmileEmoService',
        'hoho' => '\App\Services\Types\Emo\SmileEmoService',
        'hehe' => '\App\Services\Types\Emo\SmileEmoService',
        'ahaha' => '\App\Services\Types\Emo\SmileEmoService',
        'ahihi' => '\App\Services\Types\Emo\SmileEmoService',
        'clap' => '\App\Services\Types\Emo\ClapEmoService',
        'vỗ  tay' => '\App\Services\Types\Emo\ClapEmoService',
        ' votay' => '\App\Services\Types\Emo\ClapEmoService',
        'dance' => '\App\Services\Types\Emo\DanceEmoService',
        ' nhay' => '\App\Services\Types\Emo\DanceEmoService',
        'nhảy' => '\App\Services\Types\Emo\DanceEmoService',
        'party' => '\App\Services\Types\Emo\DanceEmoService',
        'love' => '\App\Services\Types\Emo\LoveEmoService',
        ' yeu' => '\App\Services\Types\Emo\LoveEmoService',
        ' yêu' => '\App\Services\Types\Emo\LoveEmoService',
        ' thuong' => '\App\Services\Types\Emo\LoveEmoService',
        'thương' => '\App\Services\Types\Emo\LoveEmoService',
        ' hon' => '\App\Services\Types\Emo\LoveEmoService',
        ' hôn' => '\App\Services\Types\Emo\LoveEmoService',
        'đẹp trai' => '\App\Services\Types\Emo\BeautyEmoService',
        'xinh' => '\App\Services\Types\Emo\BeautyEmoService',
        '?' => '\App\Services\Types\Emo\ConfuseEmoService',
    ];

    public function handleEvent(Request $request)
    {
        // Extract data
        $event = $request->input('webhook_event');
        $data = [
            'roomId' => $event['room_id'],
            'fromId' => $event['from_account_id'],
            'msgId' => $event['message_id'],
            'msg' => $this->extractMainContent($event['body']),
        ];

        // Pick service
        $service = $this->getService($data['msg']);

        // Generate message
        $response = ServiceEntry::service($service)
            ->createResponse($data);
        if ($service === '\App\Services\Types\User\ToService') {
            $data['roomId'] = env('TEAM_AN_TRUA_FS');
        }

        // Send message
        $this->sendResponse($response, $data['roomId']);
    }

    private function sendResponse($response, $roomId)
    {
        try {
            ChatworkSDK::setApiKey(env('CHATWORK_API_KEY'));
            $room = new ChatworkRoom($roomId);
            $room->sendMessage($response);
        } catch (\Exception $e) {
            logger($e);

            return false;
        }
    }

    private function getService($message)
    {
        $serviceName = $this->getExistsService($message, $this->command);
        if ($serviceName) {
            return $serviceName;
        }

        $serviceName = $this->getExistsService($message, $this->chatEmo);
        if ($serviceName) {
            return $serviceName;
        }

        return '@';
    }

    private function extractMainContent($message)
    {
        return trim(substr($message, strpos($message, ']')));
    }

    private function getExistsService($message, $validOption)
    {
        foreach ($validOption as $key => $value) {
            if (strpos($message, $key)) {
                return $value;
            }
        }

        return null;
    }
}
