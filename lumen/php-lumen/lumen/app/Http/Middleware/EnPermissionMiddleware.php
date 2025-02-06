<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use DB;
use App;
use Illuminate\Http\Response;

class EnPermissionMiddleware 
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function __construct()
    {
        
    }

    public function handle($request, Closure $next)
    {
    	//Error Messages for triggering Error
    	$data['data'] 				= null;
        $data['message']['error']   = trans('messages.900');
        $data['statuscode']         = '403';
        $data['status']             = 'error';
        $inputdata    = $request->all();
        $isSuperadmin  = isset($inputdata['ENMASTERADMIN']) ? $inputdata['ENMASTERADMIN'] :'';
        $access_rights = isset($inputdata['ACCESSRIGHTS']) ? $inputdata['ACCESSRIGHTS'] :'';
        if ($isSuperadmin !="" && $isSuperadmin == 'y')
        {
            return $next($request);
        }
        else
        {
            if (!empty($access_rights))
            {
                $access_rights_res = $request->input('ACCESSRIGHTS');
                $permission  =  strtoupper($request->segment(1));
                $accessright =  $request->segment(2);
                
                if($permission == 'PURCHASEORDERS') $permission = 'PURCHASEORDER';

                //for url with UUID at second segment
                $UUIDv4 = '/[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}/';
                if(preg_match($UUIDv4, $accessright) || $accessright == "0") $accessright = "";

                if(is_array($access_rights_res['ITAM']) && isset($access_rights_res['ITAM'][$permission]))
                {
                    $access_rights = json_decode($access_rights_res['ITAM'][$permission], TRUE);    
                     if(count($access_rights) > 0 && in_array('r', $access_rights))
                    {
                        if ($accessright !="")
                        {
                            if ($accessright == "list" ||  $accessright == "mainlist" ||  $accessright == "details") 
                            {
                                if(in_array('r', $access_rights))
                                {
                                   return $next($request);                   
                                }
                                else
                                {

                                    return response()->json($data);
                                }
                            }
                            elseif($accessright == "add" || $accessright == "addsubmit" ||  $accessright == "save" ) 
                            {
                                if(in_array('c', $access_rights))
                                {
                                   return $next($request);                    
                                }
                                else
                                {
                                    return response()->json($data);
                                }
                            }
                            elseif($accessright == "edit" || $accessright == "editsubmit" || $accessright == "update") 
                            {
                                if(in_array('u', $access_rights))
                                {
                                   return $next($request);                   
                                }
                                else
                                {
                                   return response()->json($data);
                                }   
                            }
                            elseif($accessright=="delete") 
                            {
                                if(in_array('d', $access_rights))
                                {
                                    return $next($request);                   
                                }
                                else
                                {
                                    return response()->json($data);
                                }    
                            }
                            else
                            {
                              return response()->json($data);
                            }
                        }
                        else
                        {
                           return $next($request);
                        }
                    }
                    else
                    {
                        if (count($access_rights) > 0 && in_array('a', $access_rights ))
                        {
                           return $next($request);    
                        }
                        else
                        {
                           return response()->json($data);
                        }
                    }
                }
                else
                {
                    return response()->json($data);
                }
            }
            else
            {
               return response()->json($data);
            } 
        }
        return $next($request);
    }
}
