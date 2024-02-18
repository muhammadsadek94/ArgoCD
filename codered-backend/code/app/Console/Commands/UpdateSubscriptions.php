<?php

namespace Framework\Console\Commands;

use App\Domains\Payments\Enum\AccessType;
use App\Domains\User\Enum\SubscribeStatus;
use App\Domains\User\Models\UserSubscription;
use Illuminate\Console\Command;

class UpdateSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:subscriptions-dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Subscriptions Dates (for temporary use for campaign of 1Jan 22)';
//    protected $description = 'Update Subscriptions Dates (for temporary use for campaign of 3Nov 21)';

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
        $subscriptions =  UserSubscription::whereHas('package', function ($query) {
            $query->where('access_type','!=', AccessType::PRO);
        })->whereDate('created_at','>', '2022-01-01')->get();

        echo(count($subscriptions));
        echo "\n";
        foreach ($subscriptions as $subscription) {
            $subscription->update(['expired_at' => \Carbon\Carbon::parse($subscription->created_at)->addYear(), 'status' => SubscribeStatus::ACTIVE]);
        }


        return count($subscriptions);
    }
}
