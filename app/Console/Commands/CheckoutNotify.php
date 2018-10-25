<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use wataridori\ChatworkSDK\ChatworkSDK;
use wataridori\ChatworkSDK\ChatworkRoom;

class CheckoutNotify extends Command
{
    protected $message = '[toall] Bây giờ là 17h00 mọi người hãy nhớ checkout trước khi ra về (facepalm)';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:checkout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'remember to checkout';

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
            $room = new ChatworkRoom(97377164);
            $room->sendMessage($this->message);
        } catch (\Exception $e) {
            logger($e);
            return false;
        }
    }
}
