<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use wataridori\ChatworkSDK\ChatworkRoom;
use wataridori\ChatworkSDK\ChatworkSDK;

class LunchPrepareNotify extends Command
{
    protected $message = '[toall] Quay cơm đê mọi người êiiiiiii (dithoi)';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:prepare-lunch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepare lunch notification';

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
            $room = new ChatworkRoom(env('TEAM_AN_TRUA_FS'));
            $room->sendMessage($this->message);
        } catch (\Exception $e) {
            logger($e);

            return false;
        }
    }
}
