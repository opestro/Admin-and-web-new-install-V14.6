<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class FilePermissionProcess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return void
     */
    public function handle(): void
    {
        try {
            chmod(public_path('sitemap.xml'), 0777);
            chmod(base_path('sitemap.xml'), 0777);
            chmod(public_path('robots.txt'), 0777);
            chmod(base_path('robots.txt'), 0777);
        } catch (\Exception $exception) {
        }
    }
}
