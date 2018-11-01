<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ServiceEntry;
use wataridori\ChatworkSDK\ChatworkSDK;
use wataridori\ChatworkSDK\ChatworkRoom;

class WebhookController extends Controller
{

    protected $supportName = [
        '(tat' => 'slap',
        '(ngu' => 'slap',
        ' điên ' => 'slap',
        '(dam' => 'punch',
        '(boxing' => 'punch',
        '(songphi' => 'kick',
        '(danhnhau' => 'kill',
        '(da' => 'kick',
        '(hi)' => 'hi',
        '(hello)' => 'hi',
        'hey' => 'hi',
        '(kill' => 'kill',
        'giet' => 'kill',
        '?' => 'confuse',
        'haha' => 'smile',
        'hihi' => 'smile',
        'hoho' => 'smile',
        'hehe' => 'smile',
        'ahaha' => 'smile',
        'ahihi' => 'smile',
        'clap' => 'clap',
        ' vỗ  tay ' => 'clap',
        'votay' => 'clap',
        'dance' => 'dance',
        'nhay' => 'dance',
        ' nhảy ' => 'dance',
        'party' => 'dance',
        'love' => 'love',
        ' yeu ' => 'love',
        ' yêu ' => 'love',
        ' thuong ' => 'love',
        ' thương ' => 'love',
        ' hon ' => 'love',
        ' hôn ' => 'love',
        ' đẹp trai ' => 'beauty',
        ' xinh ' => 'beauty',
        'handsome' => 'beauty',
        '{weather}' => 'weather',
        '{map}' => 'map',
    ];

    /**
     * Handle webhook event from chatwork
     */
    public function handleEvent(Request $request)
    {
        // Get evnet infomation
        $webhookEvent = $request->input('webhook_event');
        $roomId = $webhookEvent['room_id'];
        $fromId = $webhookEvent['from_account_id'];
        $messageId = $webhookEvent['message_id'];

        // Generate response
        $message = $this->extractContent($webhookEvent['body']);
        $name = $this->getServiceName($message);
        $response = ServiceEntry::service($name)
            ->createResponse([
                'roomId' => $roomId,
                'userId' => $fromId,
                'messId' => $messageId,
            ]);

        // Send response
        $this->sendResponse($response, $roomId);
    }

    /**
     * Send response message to chatwork
     *
     * @param string $response
     * @param string $roomId
     *
     * @return void
     */
    protected function sendResponse($response, $roomId)
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

    /**
     * Get service type to create rseponse message
     *
     * @param string $messsage
     *
     * @return string
     */
    protected function getServiceName($message)
    {
        $name = '?';
        foreach ($this->supportName as $key => $value) {
            if (strpos($message, $key)) {
                $name = $value;
                break;
            }
        }

        return $name;
    }

    /**
     * Remove the [To:xxxxx] part from message
     *
     * @param string $message
     *
     * @return string
     */
    protected function extractContent($message)
    {
        return trim(substr($message, strpos($message, ']')));
    }
}
