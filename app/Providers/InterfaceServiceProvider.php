<?php

namespace App\Providers;

use App\Contracts\ControllerInterface;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class InterfaceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->bindInterfaceWithRepository();
    }

    private function bindInterfaceWithRepository():void
    {
        $this->app->bind(ControllerInterface::class, BaseController::class);
        $repositoriesPath = app_path('Repositories');
        $contractsPath = app_path('Contracts/Repositories');
        $repositoryFiles = File::files($repositoriesPath);
        foreach ($repositoryFiles as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $interfaceName = $filename . 'Interface';
            $interfacePath = $contractsPath . DIRECTORY_SEPARATOR . $interfaceName . '.php';
            if (File::exists($interfacePath)) {
                $interface = 'App\Contracts\Repositories\\' . $interfaceName;
                $repository = 'App\Repositories\\' . $filename;
                $this->app->bind($interface, $repository);
            }
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
