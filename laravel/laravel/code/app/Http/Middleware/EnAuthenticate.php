<?php

namespace App\Http\Middleware;

use Closure;
use Redirect;
use App\Services\DomainAccessService;
use Session;

class EnAuthenticate
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
        $this->domain_access = new DomainAccessService();
        $token = (string)getSessionItem('token');
        
        $url = $request->url();
        if($token=="")
        {
            $access_token = (string)getSessionItem('access_token');
            if($access_token != '')
            {
                $is_valid_call =  $this->domain_access->setdomainacceess();
                
                if ($is_valid_call) 
                {
                    return $next($request);    
                }
                else
                {
                    if($request->ajax())
                    {
                        $response = [];
                        $response["html"]     = "";
                        $response["is_error"] = true;
                        $response["msg"]      = 'Service token expired.';
                        echo json_encode($response);
                        exit();
                    }
                    else
                    {
                        if($request->ajax())
                        {
                            $response = [];
                            $response["html"]     = "";
                            $response["is_error"] = true;
                            $response["msg"]      = 'Service token expired.';
                            echo json_encode($response);
                            exit();
                        }
                        else
                        {
                            return Redirect::to('logout');
                        }

                    }
                }
                //return Redirect::to('checkaccess/'.$url);
            }
            else
            {
                return Redirect::to('logout');
            }
        }
        else
        {
            return $next($request);    
        }
    }
}
