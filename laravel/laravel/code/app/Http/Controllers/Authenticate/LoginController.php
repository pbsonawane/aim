<?php

namespace App\Http\Controllers\Authenticate;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Redirect;
use Illuminate\Support\Facades\Input;
use App\Services\IAM\IamAppService;
use Session;

class LoginController extends Controller
{
    public function __construct(IamAppService $iamapi,Request $request)
    {
		$this->iamapi = $iamapi;
		$this->request = $request;
        $this->request_params = $this->request->all();
		$this->grant_password = "password";
		$this->grant_reset = "reset";
	}
	
	public function logout()
    {
		//Destroy Session
		deleteSession();
		$redirect_base_url = config('enconfig.iamapp_url');
		return Redirect::to($redirect_base_url.'/logout');	
	}
	
	
	/**
     * This function is to start session and set access token to session
     * @author Vishal Chaudhari
     * @access public
     * @package Authenticate
     * @return string
     */
    public function setaccess()
    {
		$inputdata = $this->request->all();
		$access_token = $this->request->input('access_token',null);
		$access_token = trim($access_token);
		// pending to check referrer
		$domainkey = 'itam';
		saveAccessTokenToSession($access_token, $domainkey);
	}

	/**
     * This function is to clear session
     * @author Vishal Chaudhari
     * @access public
     * @package Authenticate
     * @return string
     */
	function clearsession()
	{
		deleteSession();
	}

}