<?php

namespace App\Console\Commands;

use App\Utils\Helpers;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Madnest\Madzipper\Facades\Madzipper;

class InstallablePackage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prepare:installable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an installable package.';

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
        Helpers::remove_dir('.idea');
        Artisan::call('debugbar:clear');

        Helpers::remove_dir('storage/app/public');
        Madzipper::make('installation/backup/public.zip')->extractTo('storage/app');

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

        $dot_env = base_path('.env');
        $new_env = base_path('.env.example');
        copy($new_env, $dot_env);

        $routes = base_path('app/Providers/RouteServiceProvider.php');
        $new_routes = base_path('installation/activate_install_routes.txt');
        copy($new_routes, $routes);
    }
}
