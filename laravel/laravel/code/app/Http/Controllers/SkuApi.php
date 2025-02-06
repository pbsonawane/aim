<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
class SkuApi extends Controller {

   public function getskus() {

        ini_set('max_execution_time', '3000'); //300 seconds = 5 minutes
        ini_set('max_execution_time', '0'); // for infinite time of execution 
    try 
    {

       return "dadsadsadgsajhgd jsahg djhsad";
    } 
    catch (\Exception $e) 
    {
        dd($e);
    }
    catch (\Error $e) 
    {
        dd($e);
    }
      
   }



}