<?php
namespace App\Foundation;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        // Register the service providers of your Services here.
        // $this->app->register('full namespace here')
        $this->app->register('App\Domains\User\Providers\UserServiceProvider');
        $this->app->register('App\Domains\Admin\Providers\AdminServiceProvider');

        $this->app->register('App\Domains\Uploads\Providers\UploadsServiceProvider');

        $this->app->register('App\Domains\ContactUs\Providers\ContactUsServiceProvider');
        $this->app->register('App\Domains\Faq\Providers\FaqServiceProvider');
        $this->app->register('App\Domains\Favourite\Providers\FavouriteServiceProvider');
        $this->app->register('App\Domains\Notification\Providers\NotificationServiceProvider');
        $this->app->register('App\Domains\Geography\Providers\GeographyServiceProvider');

        $this->app->register('App\Domains\Course\Providers\CourseServiceProvider');
        $this->app->register('App\Domains\Brightcove\BrightcoveServiceProvider');

        $this->app->register('App\Domains\Configuration\Providers\ConfigurationServiceProvider');

        $this->app->register('App\Domains\Voucher\Providers\VoucherServiceProvider');

        $this->app->register('App\Domains\UserActivity\Providers\UserActivityServiceProvider');
        $this->app->register('App\Domains\Blog\Providers\BlogServiceProvider');

        $this->app->register('App\Domains\Workshop\Providers\WorkshopServiceProvider');

        $this->app->register('App\Domains\OpenApi\Providers\OpenApiServiceProvider');

        $this->app->register('App\Domains\Bundles\Providers\BundlesServiceProvider');

        $this->app->register('App\Domains\Enterprise\Providers\EnterpriseServiceProvider');
        $this->app->register('App\Domains\Reports\Providers\ReportsServiceProvider');

        $this->app->register('App\Domains\Partner\Providers\PartnerServiceProvider');
        $this->app->register('App\Domains\Cms\Providers\CmsServiceProvider');


        $this->app->register('App\Domains\Payments\Providers\PaymentsServiceProvider');

        $this->app->register('App\Domains\Comment\Providers\CommentServiceProvider');

        $this->app->register('App\Domains\Seo\Providers\SeoServiceProvider');
        
        $this->app->register('App\Domains\Challenge\Providers\ChallengeServiceProvider');

    }

}

