<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use wataridori\ChatworkSDK\ChatworkSDK;
use wataridori\ChatworkSDK\ChatworkRoom;

class ReportCVNotify extends Command
{
    protected $message = '[To:3501599] Đếm CV đi nào bạn êiiiii (goodduck)';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:cv-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify report CV';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            ChatworkSDK::setApiKey(env('CHATWORK_API_KEY'));
            $room = new ChatworkRoom(env('MY_SOI_CW_ID'));
            $room->sendMessage($this->message);
        } catch (\Exception $e) {
            logger($e);
            return false;
        }
    }
}
