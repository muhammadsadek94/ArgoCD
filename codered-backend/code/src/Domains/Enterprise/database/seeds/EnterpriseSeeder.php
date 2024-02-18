<?php

namespace App\Domains\Enterprise\database\seeds;

use App\Domains\Course\Models\CompletedCoursePercentage;
use App\Domains\Course\Models\CompletedCourses;
use App\Domains\Course\Models\Course;
use App\Domains\Course\Models\CourseEnrollment;
use App\Domains\Course\Models\CourseReview;
use App\Domains\Course\Models\WatchHistoryTime;
use App\Domains\Enterprise\Enum\LicneseStatusType;
use App\Domains\Enterprise\Enum\LicneseType;
use App\Domains\Enterprise\Models\CourseWeight;
use App\Domains\Enterprise\Models\EnterpriseLearnPath;
use App\Domains\Enterprise\Models\License;
use App\Domains\Payments\Models\PackageSubscription;
use App\Domains\User\Enum\UserActivation;
use App\Domains\User\Enum\UserType;
use App\Domains\User\Models\User;
use App\Domains\User\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EnterpriseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //courses

        // for ($i = 0; $i < 5; $i++) {
        echo "creating enterprise \r\n";
        $enterprise = User::create([
            "first_name" => fake()->firstName,
            "last_name" => fake()->lastName,
            "company_name" => fake()->company,
            "phone" => fake()->phoneNumber,
            "email" => fake()->email,
            "password" => '@Aa123123',
            "activation" => UserActivation::ACTIVE,
            "type" => UserType::PRO_ENTERPRISE_ADMIN,
        ]);



        echo "created enterprise \r\n";
        echo "----------------- \r\n";
        echo "Email: {$enterprise->email} \n";
        echo "Pass : @Aa123123 \n";
        echo "----------------- \r\n";
        echo "creating licenses \r\n";

        $this->createLicense($enterprise, 50, LicneseType::PREMIUM);
        echo "created licenses \r\n";
        echo "creating learn path \r\n";
        $learn_path = $this->createLearnPath($enterprise);
        echo "created learn path \r\n";

        $sub_accounts_ids = [];

        $user_names = [
            "Software Engineering Team",
            "Penetration Testing team",
            "Data Analyst",
            "Data Science Team",
            "DevOps Team",
        ];


        for ($i = 0; $i < 5; $i++) {

            $user = User::create([
                "first_name" => $user_names[$i],
                "last_name" => null,
                "company_name" => $user_names[$i],
                "phone" => fake()->phoneNumber,
                "email" => fake()->email,
                "password" => '@Aa123123',
                "activation" => UserActivation::ACTIVE,
                "type" => UserType::PRO_ENTERPRISE_SUBACCOUNT,
                "enterprise_id" => $enterprise->id,
            ]);

            $sub_accounts_ids[] = $user->id;
        }

        for ($j = 0; $j < 15; $j++) {
            echo "creating enterprise {$i} user {$j} \r\n";
            $user = User::create([
                "first_name" => fake()->firstName,
                "last_name" => fake()->lastName,
                "company_name" => fake()->company,
                "phone" => fake()->phoneNumber,
                "email" => fake()->email,
                "password" => '@Aa123123',
                "activation" => UserActivation::ACTIVE,
                "type" => UserType::USER,
                "enterprise_id" => $enterprise->id,
                "subaccount_id" => $sub_accounts_ids[array_rand($sub_accounts_ids)]
            ]);

            $user->created_at = Carbon::now()->subDays(rand(1, 10));
            $user->save();

            echo "created enterprise {$i} user {$j} \r\n";

            foreach ($learn_path as $package) {
                echo "creating enterprise {$i} user subscription {$j} \r\n";
                UserSubscription::create([
                    "status"          => 1,
                    "expired_at"      => now()->addYear(),
                    "user_id"             => $user->id,
                    "package_id"          => $package->id
                ]);
                echo "created enterprise {$i} user subscription {$j} \r\n";
                foreach ($package->courses as $course) {
                    echo "creating enterprise {$i} user history {$j} \r\n";
                    $watch_history = WatchHistoryTime::create([
                        "user_id" => $user->id,
                        "course_id" => $course->id,
                        "watched_time" => fake()->numberBetween(600, 9000),
                        "lesson_id" => $course->lessons->first()?->id,
                        "status" => 1,
                    ]);

                    $review = CourseReview::create([
                        'user_id' => $user->id,
                        "course_id" => $course->id,
                        'rate' => fake()->numberBetween(4, 5),
                        'activation' => 1,
                    ]);

                    $review->created_at = Carbon::now()->subDays(rand(1, 10));
                    $review->save();

                    $watch_history->created_at = Carbon::now()->subDays(rand(1, 60));
                    $watch_history->updated_at = Carbon::now()->subDays(rand(1, 60));
                    $watch_history->save();

                    echo "created enterprise {$i} user history {$j} \r\n";

                    echo "creating enterprise {$i} user completed courses {$j} \r\n";
                    $completed_users = CompletedCourses::create([
                        "user_id" => $user->id,
                        "course_id" => $course->id,
                        "degree" => fake()->numberBetween(1, 100)
                    ]);

                    $completed_users->created_at = Carbon::now()->subDays(rand(1, 60));
                    $completed_users->save();

                    echo "created enterprise {$i} user completed courses {$j} \r\n";

                    echo "creating enterprise {$i} user completed courses percentages {$j} \r\n";
                    $completed_users_percentage = CompletedCoursePercentage::create([
                        "user_id" => $user->id,
                        "course_id" => $course->id,
                        "completed_percentage" => fake()->numberBetween(1, 100),
                        "is_finished" => 0,
                    ]);

                    $completed_users_percentage->created_at = Carbon::now()->subDays(rand(1, 60));
                    $completed_users_percentage->save();

                    echo "created enterprise {$i} user completed courses percentages {$j} \r\n";

                    echo "creating enterprise {$i} user courses enrollments {$j} \r\n";
                    $enrollment = CourseEnrollment::create([
                        "course_id" => $course->id,
                        "user_id" => $user->id,
                    ]);

                    $enrollment->created_at = Carbon::now()->subDays(rand(1, 60));
                    $enrollment->save();

                    echo "created enterprise {$i} user courses enrollments {$j} \r\n";
                }
            }
        }
        // }
    }

    private function getRandomString($length = 27, $prefix = '', $enterpiseName = '')
    {
        $prefix = $prefix . '-' . $enterpiseName . '-';
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return "{$prefix}{$randomString}";
    }

    private function createLicense(User $user, $License_number, $type)
    {
        for ($i = 0; $i < $License_number; $i++) {
            $licence = $this->getRandomString(27, 'EP', $user->first_name);
            $data = [
                'license' => $licence,
                'expired_at' => Carbon::now()->addDays(365),
                'duration' => '365',
                'status' => LicneseStatusType::PENDING,
                'license_type' => $type,
                'enterprise_id' => $user->id,
                'activation' => true

            ];
            $create = License::create($data);
        }
    }

    private function createLearnPath(User $user, $length = 1)
    {

        $packages = $this->createPackages($user);

        foreach ($packages as $package) {
            $create = EnterpriseLearnPath::create([
                'package_id' => $package->id,
                'enterprise_id' => $user->id,
                'activation' => true
            ]);
        }

        return $packages;
    }

    private function createPackages($user)
    {
        $packages = collect();

        $courses = Course::take(40)->active()->course()->inRandomOrder()->get()->pluck('id')->toArray();

        for ($i = 0; $i < 5; $i++) {
            $packages_names = [
                'Cisco DevNet Associate (DEVASC 200-901)',
                'Practical Artificial Intelligence for Professionals',
                'Integrating Industrial Cybersecurity into Power Sector',
                'Transforming Business Decisions with Data Analytics - Advanced',
                'Building Secure Java Applications',
            ];

            $package = PackageSubscription::create([
                "name" => $packages_names[$i],
                'amount' => fake()->numberBetween(10, 500),
                "sku"         => fake()->text(15),
                'type' => 6,
                'enterprise_id' => $user->id,
                'access_type' => rand(5, 6),
                'access_id' => json_encode($courses),
                'deadline_type' => rand(0, 1),
                'activation' => 1,
                'access_permission' => 2,
            ]);

            for ($j = 0; $j < 8; $j++) {
                CourseWeight::create([
                    'package_subscription_id' => $package->id->toString(),
                    'course_id' => $courses[array_rand($courses)],
                    'sort' => $j + 1,
                    'weight' => ceil(count($courses) / 100)
                ]);
            }

            $packages->add($package);
        }

        return $packages;
    }
}
