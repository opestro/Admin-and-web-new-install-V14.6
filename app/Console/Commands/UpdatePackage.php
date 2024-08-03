<?php

namespace App\Console\Commands;

use App\CentralLogics\Helpers;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class UpdatePackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prepare:updatable';

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
     * @return int
     */
    public function handle()
    {
        Artisan::call('debugbar:clear');

        $folder = base_path('resources/themes');
        $directories = glob($folder . '/*', GLOB_ONLYDIR);
        foreach ($directories as $directory) {
            $array = explode('/', $directory);
            if (File::isDirectory($directory) && !in_array(end($array), ["default", "theme_aster"])) {
                File::deleteDirectory($directory);
            }
        }

        $add_on_folder = base_path('Modules');
        $add_on_directories = glob($add_on_folder . '/*', GLOB_ONLYDIR);
        foreach ($add_on_directories as $directory) {
            $array = explode('/', $directory);
            if (File::isDirectory($directory)) {
                File::deleteDirectory($directory);
            }
        }

        $routes = base_path('app/Providers/RouteServiceProvider.php');
        $new_routes = base_path('installation/activate_update_routes.txt');
        copy($new_routes, $routes);
        return 0;
    }

}
