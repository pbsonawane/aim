<?php
/**
 * Author: Shadab Khan
 * Description: This Middleware is used to set the languages to the current env 
 * variable from session
 * 
 */ 

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    

    public function handle($request, Closure $next)
    {

        if (Session::has('locale') AND in_array(Session::get('locale'), Config::get('app.languages'))) 
            {
                App::setLocale(Session::get('locale'));
            }
            else 
            { 
                App::setLocale(Config::get('app.fallback_locale'));
            }

            return $next($request);
    }
}
