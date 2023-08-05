<?php

namespace Innsite19\CrudGenerator;

use Illuminate\Support\ServiceProvider;
use Innsite19\CrudGenerator\App\Console\Commands\GenerateCrud;

class CrudGeneratorServiceProvider extends ServiceProvider
{

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateCrud::class
            ]);
        }
    }
    public function register()
    {
    }
}
