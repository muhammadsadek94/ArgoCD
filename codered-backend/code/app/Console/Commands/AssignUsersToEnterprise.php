<?php

namespace Framework\Console\Commands;

use App\Domains\User\Models\User;
use Illuminate\Console\Command;


class AssignUsersToEnterprise extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enterprise:create-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create report for payout all instructors';

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

        return User::where('email', 'LIKE', '@jainuniversity.ac.in')
            ->update([
                'enterprise_id' => '300f14e9-b6da-417e-8706-392a91b90840'
            ]);

    }



}
