<?php

namespace Framework\Console\Commands;

use App\Domains\User\Models\User;
use DB;
use Illuminate\Console\Command;


class ChangeImagesUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'change-images-url';

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

        // https://eccommonstorage.blob.core.windows.net/

        return DB::table('uploads')
            ->where('full_url', 'LIKE', '%https://eccommonstorage.blob.core.windows.net/%')
            ->update([
                'full_url' => DB::raw("REPLACE(full_url, 'https://eccommonstorage.blob.core.windows.net/', 'replace-url-with-this/')")
            ]);

    }



}
