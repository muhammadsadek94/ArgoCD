<?php

namespace Framework\Console\Commands;

use App\Domains\Course\Jobs\Api\V1\User\GenerateCertificateJob;
use App\Domains\Course\Models\CompletedCourses;
use Illuminate\Console\Command;
use DB;

class RegenerateDuplicatedCertificate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'certificates:re-generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 're-generate duplicated certificates';

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
        //        $query = "SELECT cc.*
        //                    FROM completed_courses cc
        //                    JOIN (SELECT *, COUNT(*)
        //                    FROM completed_courses
        //                    GROUP BY certificate_number
        //                    HAVING count(*) > 1 ) cc2
        //                    ON cc.certificate_number= cc2.certificate_number
        //                   ";
        //
        //        $total = count(DB::select($query));
        //        dd($total);

        /*     $results = CompletedCourses::where('certificate_number', '>', 147653)
                 ->get();

             $results_certificates = [];
             foreach ($results as $key => $result) {
                 $result->update([
                     'certificate_number'=> (($key+1) + 147653)
                 ]);
                 $results_certificates[] = dispatch_now(new GenerateCertificateJob($result->user, $result));
             }*/

//        $query = "SELECT cc.*
//                            FROM completed_courses cc
//                            JOIN (SELECT *, COUNT(*)
//                            FROM completed_courses
//                            GROUP BY certificate_number
//                            HAVING count(*) > 1 ) cc2
//                            ON cc.certificate_number= cc2.certificate_number
//                           ";
//
//        $results = DB::select($query);

        $results = CompletedCourses::where('certificate_number', '>', '193145')->orderBy('certificate_number')->get();

        foreach ($results as $result) {
            $max_certificate = DB::table('completed_courses')->where('certificate_number', '<', '1486651')
                ->select(DB::raw('MAX(CAST(certificate_number AS UNSIGNED)) as max'))->get()[0];
            $most_certificate_number = $max_certificate->max + 1;


            $certificate = CompletedCourses::find($result->id);
            if(empty($certificate->course) || empty($certificate->user)) continue;

            $certificate->update([
                'certificate_number' => $most_certificate_number
            ]);

            dispatch_now(new GenerateCertificateJob($certificate->user, $certificate));
        }

        $query = "SELECT cc.*
                            FROM completed_courses cc
                            JOIN (SELECT *, COUNT(*)
                            FROM completed_courses
                            GROUP BY certificate_number
                            HAVING count(*) > 1 ) cc2
                            ON cc.certificate_number= cc2.certificate_number
                           ";

        $total = count(DB::select($query));
        dd($total);

        //        dd($results_certificates);

        /*$query = "SELECT cc.*
                    FROM completed_courses cc
                    JOIN (SELECT *, COUNT(*)
                    FROM completed_courses
                    GROUP BY certificate_number
                    HAVING count(*) > 1 ) cc2
                    ON cc.certificate_number= cc2.certificate_number
                   ";


        $max = 500;
        $total = count(DB::select($query));

        $pages = ceil($total / $max);
        for ($i = 1 ; $i < ($pages + 1) ; $i++) {
            $offset = (($i - 1) * $max);
            $start = ($offset == 0 ? 0 : ($offset + 1));
            $qualified_query = "{$query}  limit {$max} offset {$start}";
            $certificates = DB::select($qualified_query);

            echo (count($certificates)) ."\n";
        }*/

        return 0;
    }
}
