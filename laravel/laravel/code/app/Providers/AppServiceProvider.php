<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Configsettings;
use App\Services\eNsysconfig\Enconfig;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $configArr = (array) new Configsettings;        
        $itemsArr = (array) new Enconfig;  
        $items = isset($itemsArr['item']) ? $itemsArr['item'] : [];        
        if(count($items) > 0)
        {
            foreach($items as $k => $item)
            {
            //Config::set("enconfig.".$k, $item); //Laravel
                config(['enconfig.'.$k => $item]); //Lumen
            }                   
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
