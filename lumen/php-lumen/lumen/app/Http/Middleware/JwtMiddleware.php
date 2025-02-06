<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Models\EnAuth;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use DB;
use App;
use App\Services\RemoteApi;


class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $token = (string)$request->header('Authorization');
        $lang = (string)$request->header('Content-Language');

        if($token=="")
        {
            $data['data'] = NULL;             
            $data['message']['error'] = "Token Field is required.";
            $data['status'] = 'error'; 
            return response()->json($data);
        }

        $remote_api = new RemoteApi();
        $auth_service_url = config('enconfig.auth_service_url');
        $options = array('form_params' => array('token' => $token,'callfrom' => 'ITAM'));
        $data   = $remote_api->apicall('post', $auth_service_url, 'auth/validate_auth_token', $options);

        if (isset($data['is_error']) && $data['is_error'] == false && isset($data['content']) && is_array($data['content']) && sizeof($data['content']) > 0)
        {
            $user_data  = $data['content'];

            if (isset($user_data['user_id'])) 
            {
                $request->request->add(['parent_id' => $user_data['user_id']]);
                $request->request->add(['loggedinuserid' => $user_data['user_id']]);
            }

            if (isset($user_data['username'])) 
            {
                $request->request->add(['ENUSERNAME' => $user_data['username']]);
            }

            if (isset($user_data['userfullname'])) 
            {
                $request->request->add(['ENFULLNAME' => $user_data['userfullname']]);
            }

            if (isset($user_data['profile_photo'])) 
            {
                $request->request->add(['ENPROFILEPHOTO' => $user_data['profile_photo']]);
            }

            if (isset($user_data['role_id'])) 
            {
                $request->request->add(['ENROLES' => $user_data['role_id']]);
            }

            if (isset($user_data['masteradmin'])) 
            {
                $request->request->add(['ENMASTERADMIN' => $user_data['masteradmin']]);
            }
            else
            {
                $request->request->add(['ENMASTERADMIN' => 'n']);
            }

            if (isset($user_data['role'])) 
            {
                $request->request->add(['ENROLES' => $user_data['role']]);
            }
            if (isset($user_data['accessrights'])) 
            {
                $request->request->add(['ACCESSRIGHTS' => $user_data['accessrights']]);
            }
            app('translator')->setLocale($lang);

            return $next($request);    
        }
        else
        {
            $data['data'] = 'TOKEN_EXPIRED';
            $data['message']['error'] = isset($data['msg']) ? $data['msg'] : 'Token Expired';
            $data['status'] = 'error'; 
            return response()->json($data);
        }
    }

}
