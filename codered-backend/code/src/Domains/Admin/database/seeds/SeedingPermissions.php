<?php

namespace App\Domains\Admin\database\seeds;

use App\Domains\Admin\Models\Role;
use App\Domains\Admin\Models\Ability;
use App\Domains\Admin\Models\Module;
use App\Domains\Blog\Rules\ArticleCategoryPermission;
use App\Domains\Blog\Rules\ArticlePermission;
use App\Domains\Blog\Rules\QuotePermission;
use App\Domains\Bundles\Rules\CourseBundlePermission;
use App\Domains\Bundles\Rules\PromoCodePermission;
use App\Domains\Enterprise\Rules\EnterprisePermission;
use App\Domains\Payments\Rules\PackageSubscriptionPermission;
use App\Domains\Payments\Rules\PaymentIntegrationPermission;
use App\Domains\Course\Rules\LessonObjectivePermission;
use App\Domains\Partner\Rules\PartnerPermissions;
use App\Domains\User\Rules\GoalPermission;
use App\Domains\User\Rules\InstructorPermission;
use App\Domains\User\Rules\PayoutPermission;
use App\Domains\Payments\Rules\SubscriptionCancellationPermission;
use App\Domains\User\Rules\UserTagPermission;
use App\Domains\UserActivity\Rules\UserActivityPermission;
use App\Domains\Voucher\Rules\VoucherPermission;
use Illuminate\Database\Seeder;
use App\Domains\Faq\Rules\FaqPermission;
use App\Domains\User\Rules\UserPermission;
use App\Domains\Course\Rules\CoursePermission;
use App\Domains\Course\Rules\CourseTagPermission;
use App\Domains\Admin\Rules\RolesPermission;
use App\Domains\Admin\Rules\AdminPermission;
use App\Domains\Challenge\Rules\ChallengePermission;
use App\Domains\Geography\Rules\CityPermission;
use App\Domains\Geography\Rules\AreaPermission;
use App\Domains\Geography\Rules\CountryPermission;
use App\Domains\ContactUs\Rules\ContactUsPermission;
use App\Domains\Course\Rules\CourseCategoryPermission;
use App\Domains\ContactUs\Rules\ContactUsSubjectsPermission;
use App\Domains\Course\Rules\CourseCertificationPermission;
use App\Domains\Payments\Rules\LearnPathPermission;
use App\Domains\Reports\Rules\LessonReportPermission;
use App\Domains\Reports\Rules\GlobalKnowledgeReportPermission;
use App\Domains\Reports\Rules\SummaryReportPermission;


class SeedingPermissions extends Seeder
{
    public function run(): bool
    {

        $modules = $this->getPermissions();
        foreach ($modules as $module_abilities) {
            foreach ($module_abilities as $key => $ability) {
                if ($key == "MODULE") {
                    $db_module = Module::firstOrCreate(["name" => $ability]);
                    continue;
                }

                if (!isset($db_module) || is_null($db_module) || empty($db_module)) {
                    die($module_abilities['MODULE'] . 'Couldn\'t be created');
                }

                Ability::firstOrCreate([
                    "module_id" => $db_module->id,
                    "name"      => $ability['name'],
                    "ability"   => $ability['ability'],
                ]);
            }
        }

        $this->createSuperRole();

        return true;
    }

    private function getPermissions(): array
    {
        return [
            AdminPermission::getConstants(),
            RolesPermission::getConstants(),

            // domain: Course
            CourseCategoryPermission::getConstants(),
            CourseTagPermission::getConstants(),
            CoursePermission::getConstants(),
            CourseCertificationPermission::getConstants(),

            // domain: User
            UserPermission::getConstants(),
            PayoutPermission::getConstants(),
            UserTagPermission::getConstants(),
            GoalPermission::getConstants(),
            InstructorPermission::getConstants(),
            SubscriptionCancellationPermission::getConstants(),

            // domain: Configuration
            /*PackageSubscriptionPermission::getConstants(),
            PaymentIntegrationPermission::getConstants(),*/

            // domain: Voucher
            VoucherPermission::getConstants(),

            // CountryPermission::getConstants(),
            // CityPermission::getConstants(),
            // AreaPermission::getConstants(),

            ArticleCategoryPermission::getConstants(),
            ArticlePermission::getConstants(),
            QuotePermission::getConstants(),

            FaqPermission::getConstants(),
            ChallengePermission::getConstants(),

            ContactUsPermission::getConstants(),
            UserActivityPermission::getConstants(),
//            ContactUsSubjectsPermission::getConstants(),

            CourseBundlePermission::getConstants(),
            PromoCodePermission::getConstants(),

            PartnerPermissions::getConstants(),
//            LessonObjectivePermission::getConstants()

            //Domain Reports
             LessonReportPermission::getConstants(),
             GlobalKnowledgeReportPermission::getConstants(),
             SummaryReportPermission::getConstants(),


              // domain: Payments
            PackageSubscriptionPermission::getConstants(),
            PaymentIntegrationPermission::getConstants(),
            LearnPathPermission::getConstants(),
            EnterprisePermission::getConstants()



        ];
    }

    private function createSuperRole()
    {
        if ($super_admin = Role::where('is_super_admin', 1)->first()) {
            $super_admin = Role::where('is_super_admin', 1)->first();
        } else {
            $super_admin = Role::firstOrCreate(["name" => "Super Admin", 'is_super_admin' => 1]);
        }

        $guaranteed_abilities = Ability::all();
        $super_admin->allow($guaranteed_abilities);
    }
}
