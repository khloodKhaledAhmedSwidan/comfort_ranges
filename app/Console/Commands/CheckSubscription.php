<?php

namespace App\Console\Commands;

use App\User;
use App\UserDevice;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckSubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkSubscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $users = User::where('subscription','1')->get();
        $now = Carbon::now();
        foreach ($users as $user){
            $end_at = $user->subscription_date->addMinute();
            if($now >= $end_at){
                $user->update([
                    'subscription' => '0',
                    'subscription_date' => null
                ]);

                $devicesTokens = UserDevice::where('user_id', $user->id)
                    ->get()
                    ->pluck('device_token')
                    ->toArray();
                $title = 'الاشتراك';
                $body = 'تم انتهاء مدة الاشتراك الخاص بك في تطبيق كشوف';
                if ($devicesTokens) {
                    sendMultiNotification($title,$body,$devicesTokens);
                }

            }
        }
    }
}
