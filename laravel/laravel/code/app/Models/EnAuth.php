<?php

namespace App\Models;

use Exception;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Illuminate\Database\Eloquent\Model;

//use Illuminate\Http\Request;

class EnAuth extends Model
{
    /*
     * This is model funtion used for token parse, validate and expiry check.
     * @author Vishal Chaudhari
     * @access public
     * @param token
     * @param_type string
     * @return any
     */
    protected function validateToken($tokenString, $site = NULL)
    {
        $request = request();
        $token = (string) $tokenString;
        $prefix_token = 'encoded ';
        if($site == NULL)
        {  
            if (!$token || $token == '')
            {
                // Unauthorized response if token not there
                $data['data'] = null;
                $data['message']['error'] = showmessage('113');
                $data['status'] = 'error';
			}
			else
			{

				$token = explode($prefix_token, $token); 

				if (count($token) != 2)
				{
					$data['data'] = null;
					 $data['message']['error'] = showmessage('113');
					$data['status'] = 'error';
				}
			}				
            
        }
        $token = (string) $tokenString;
        //dd($token);
        if($site == NULL)
        { 
            list($token) = sscanf($token, $prefix_token . '%s');
        }
        if (!$token || $token == '')
        {
            // Unauthorized response if token not there
            $data['data'] = null;
            $data['message']['error'] = showmessage('113');                    
            $data['status'] = 'error';
        }
        else
        {
            try {
                $credentials = JWT::decode($token, env('JWT_KEY'), ['HS256']);
               
                if ($credentials->expireon < time() && 0)
                {
                    $data['data'] = 'expired';
                    $data['message']['error'] = showmessage('115');
                    $data['status'] = 'error';
                    $data['credentials'] = $credentials;
                    $data['subject'] = $credentials->subject;
                    $data['valid'] = 1;
                }
               /* else
                {
                    $data['data'] = null;
                    $data['message']['success'] = showmessage('132');
                    $data['status'] = 'success';
                }*/
                /*else if ($credentials->ipaddress != $request->ip() && 0)
                {
                    $data['data'] = 'ipchange';
                    $data['message']['error'] = showmessage('500');
                    $data['status'] = 'error';
                }*/
            }
            catch (ExpiredException $e)
            {
                $data['data'] = null;
                $data['message']['error'] = showmessage('115');
                $data['status'] = 'error';
            }
            catch (Exception $e)
            {
                $data['data'] = null;
                $data['message']['error'] = showmessage('117');
                $data['status'] = 'error';
            }
        }     
        //print_r($data); exit;
        if (isset($data['status']) && $data['status'] == "error")
        {
            return $data;
        }
        else
        {
            $data['valid'] = 1;
            return $credentials;
        }

    }
}
