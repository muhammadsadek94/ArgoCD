<?php

namespace Framework\Console\Commands;

use App\Domains\Payments\Models\PaymentIntegration;
use App\Domains\Voucher\Models\Voucher;
use Illuminate\Console\Command;

class UpdatePaymentIntegrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:payable-type';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update url path in Payable Type column as per Payment Architecture';

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
        $integrations =  PaymentIntegration::where('payable_type','LIKE', 'App\\\\Domains\\\\Configuration\\\\Models\\\\PackageSubscription')->get();
        echo(count($integrations));
        echo "\n";
        foreach ($integrations as $integration) {
            $integration->update(['payable_type' => 'App\Domains\Payments\Models\PackageSubscription']);
        }


        $vouchers =  Voucher::where('payable_type','LIKE', 'App\\\\Domains\\\\Configuration\\\\Models\\\\PackageSubscription')->get();
        echo(count($vouchers));
        echo "\n";
        foreach ($vouchers as $voucher) {
            $voucher->update(['payable_type' => 'App\Domains\Payments\Models\PackageSubscription']);
        }


        return count($integrations);
    }
}
