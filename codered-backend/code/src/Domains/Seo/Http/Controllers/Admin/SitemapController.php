<?php

namespace App\Domains\Seo\Http\Controllers\Admin;

use App\Domains\Course\Models\Course;
use App\Domains\Payments\Models\LearnPathInfo;
use App\Foundation\Traits\HasAuthorization;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use INTCore\OneARTFoundation\Http\Controller;

class SitemapController extends Controller
{

    /**
     * Display a listing of the resource.
     *
//     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $base_url = config('user.user_website');
        $urls = [
            ["url" => $base_url, "date" =>  Carbon::now()->subHour(2)->format('Y-m-d\TH:i\+00:00')],
            ["url" => "$base_url/enterprise", "date" => Carbon::now()->subHour(3)->format('Y-m-d\TH:i\+00:00')],
            ["url" => "$base_url/login", "date" => Carbon::now()->subHour(4)->format('Y-m-d\TH:i\+00:00')],
            ["url" => "$base_url/register", "date" => Carbon::now()->subHour(5)->format('Y-m-d\TH:i\+00:00')],
            ["url" => "$base_url/terms-and-conditions", "date" => Carbon::now()->subHour(6)->format('Y-m-d\TH:i\+00:00')],
            ["url" => "$base_url/pro", "date" => Carbon::now()->subHour(7)->format('Y-m-d\TH:i\+00:00')],
            ["url" => "$base_url/support", "date" => Carbon::now()->subHour(8)->format('Y-m-d\TH:i\+00:00')],
            ["url" => "$base_url/instructors", "date" => Carbon::now()->subHour(9)->format('Y-m-d\TH:i\+00:00')],
            ["url" => "$base_url/privacy-policy", "date" => Carbon::now()->subHour(10)->format('Y-m-d\TH:i\+00:00')],
            ["url" => "$base_url/course-library", "date" => Carbon::now()->subHour(11)->format('Y-m-d\TH:i\+00:00')],
            ["url" => "$base_url/forgetPassword", "date" => Carbon::now()->subHour(11)->format('Y-m-d\TH:i\+00:00')],
        ];

        foreach (LearnPathInfo::active()->pluck('slug_url', 'updated_at') as $date => $learn_path_slug){
            array_push($urls, ["url" =>  env('USER_URL')."/learning-path/$learn_path_slug", "date" => Carbon::parse($date)->format('Y-m-d\TH:i\+00:00')]);
        }

        foreach (Course::active()->pluck('slug_url', 'updated_at') as $date => $course_slug){
            array_push($urls, ["url" =>  env('USER_URL')."/course/$course_slug", "date" => Carbon::parse($date)->format('Y-m-d\TH:i\+00:00')]);
        }

        $today = Carbon::now()->format('Y-m-d');

        \Storage::put("sitemap/sitemap-{$today}.xml", view('seo::admin.sitemap.index')->with(compact('urls'))->render());

        return \Storage::download("sitemap/sitemap-{$today}.xml");
    }
}
