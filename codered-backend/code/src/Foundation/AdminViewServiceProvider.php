<?php
namespace App\Foundation;

use App\Domains\Admin\Http\View\Composers\ProfileComposer;
use App\Foundation\Http\View\Admin\Composers\LayoutComposer;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use View;

class AdminViewServiceProvider extends BaseServiceProvider
{
    public function register()
    {
       //
    }

    public function boot()
    {
        // Using class based composers...
        View::composer(
            ['admin.layouts.topbar', 'admin.layouts.main','errors::404', 'errors.404'],
            ProfileComposer::class
        );

        View::composer(
            ['*'],
            LayoutComposer::class
        );

    }
}
