<?php
/*
Author: Shadab Khan
Description: This controller is used to set the languages
 
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Lang;
use App;
use Cache;

class LangController extends Controller
{
    public function index($locale)
    {
    	App::setLocale($locale);
    	session()->put('locale', $locale);
        return redirect()->back();
    }

    /*
      Author: Shadab Khan
      Description: This function is used to set the languages messages 
                   in javascript file (its just a run time genrated file)
     *
     */
    public  function lang_trans_js()
    {
        Cache::forget('lang.js');
        
        $strings = Cache::rememberForever('lang.js', function () {
            
            $lang    = config('app.locale');
            
            $files   = glob(resource_path('lang/' . $lang . '/*.php'));
            $strings = [];
            
            
            foreach ($files as $file) 
            {
                $name           = basename($file, '.php');
                $strings[$name] = require $file;
            }
            return $strings;
        });
        
        // header('Content-Type: text/javascript');
        
        //    echo('window.lang_trans_js = ' . json_encode($strings) . ';');
        echo json_encode($strings);

        exit();
    }
}
