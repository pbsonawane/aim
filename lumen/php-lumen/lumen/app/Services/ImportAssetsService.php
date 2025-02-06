<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\EnImportNotifications;

class ImportAssetsService
{
    public function __construct()
    {

    }

    public function importasset($notification_id=null)
    {
        if ($notification_id!=null) 
        {
            $notification_id_bin = DB::raw('UUID_TO_BIN("' . $notification_id . '")');
        	$res = EnImportNotifications::select(DB::raw('BIN_TO_UUID(user_id) AS user_id'),'importdata','filename')->where('notification_id', $notification_id_bin)->first();
        	if($res)
        	{
        		$farray = json_decode($res->importdata,true);
        		$filename = $res->filename;
        		$user_id = $res->user_id;
        		$request_params['farray']             = $farray;
        		$request_params['filename']           = $filename;
        		$request_params['ciuser_id']          = $user_id;
        		$request_params['notification_id']    = $notification_id;
        		$request = new \Illuminate\Http\Request();
                $request->replace($request_params);
        		$result = app('App\Http\Controllers\asset\AssetController')->importprocess($request);
        	}
        	 
        }
           
    }
}


?>