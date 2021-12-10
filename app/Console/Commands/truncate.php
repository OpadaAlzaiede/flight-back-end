<?php

namespace App\Console\Commands;

use App\Models\Trip;
use App\Models\User;
use App\Models\Comment;
use App\Models\Attachment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class truncate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:truncate';

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
        User::truncate();
        Trip::truncate();
        Comment::truncate();
        Attachment::truncate();
        DB::table('trip_user')->truncate();
    }
}
