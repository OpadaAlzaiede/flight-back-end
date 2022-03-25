<?php

namespace App\Console\Commands;

use App\Mail\SupportMail;
use Carbon\Carbon;
use App\Models\Trip;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckSupportEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'support-email:check';

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
     * @return int
     */
    public function handle()
    {
        $trips = Trip::getArrivedTrips();

        foreach($trips as $trip) {

            $unCheckedUsers = $trip->users()->where('is_arrived', 0)->get();
            if(Carbon::now()->diffInMinutes($trip->arrives_at) > 15)
                foreach($unCheckedUsers as $user) {
                    if($user->supportEmail)
                        Mail::send(new SupportMail($trip), [], function($message) use ($user){
                            $message->to($user);
                            $message->subject('Support Email');
                        });
                }
        }

        return Command::SUCCESS;
    }
}
