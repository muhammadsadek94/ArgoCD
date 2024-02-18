<?php

namespace App\Domains\Admin\Http\View\Composers;
use Auth;
use Illuminate\View\View;


class ProfileComposer
{

    /**
     * Create a new profile composer.
     *
     */
    public function __construct()
    {
        // Dependencies automatically resolved by service container...
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $auth = auth()->guard('admin')->user();
        $view->with('auth', $auth);
    }

}
