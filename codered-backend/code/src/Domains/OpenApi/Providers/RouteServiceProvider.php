<?php

namespace App\Domains\OpenApi\Providers;

use App\Domains\OpenApi\Enum\AccessScope;
use Illuminate\Routing\Router;
use Laravel\Passport\Passport;
use INTCore\OneARTFoundation\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Read the routes from the "api.php" and "web.php" files of this Service
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function map(Router $router)
    {
        $namespace = 'App\Domains\OpenApi\Http\Controllers';
        $pathApi = __DIR__.'/../routes/api.php';
        $pathWeb = __DIR__.'/../routes/web.php';

        $this->loadRoutesFiles($router, $namespace, $pathApi, $pathWeb);

        Passport::ignoreRoutes();

        Passport::tokensCan([
            AccessScope::UPDATE_SUBSCRIPTIONS => "Ability to update user's subscriptions",
            AccessScope::REVOKE_SUBSCRIPTIONS => "Ability to cancel user's subscriptions immediately ",
        ]);

    }
}
