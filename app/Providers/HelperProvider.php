<?php

namespace App\Providers;

use App\Helpers\Helper;
use App\Helpers\ReponseHelper;
use Illuminate\Support\ServiceProvider;

class HelperProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Helper',function(){
            return new ResponseHelper();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
