<?php

namespace App\Domains\UserActivity\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class UserActivityInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user-activity:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will publish config file and run a migration for user log activity';

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
        $migrationFile = "2020_11_20_100001_create_log_table.php";
        //config
        $this->publishConfig();

        $this->line('-----------------------------');
        if (!Schema::hasTable('user_activity_logs')) {
            $this->call('migrate');
        } else {
            $this->error('logs table already exist in your database. migration not run successfully');
        }

    }

    private function publishConfig()
    {
        $this->call('vendor:publish', [
            '--provider' => "App\Domains\UserActivity\Providers\UserActivityServiceProvider",
            '--tag'      => 'config',
            '--force'    => true
        ]);
    }


}
