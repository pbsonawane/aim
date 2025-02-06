<?php
namespace App\Services\IAM;

use App\Services\RemoteApi;

class IamAppService
{
    public function __construct()
    {
        $this->remoteapi = new RemoteApi;
        $this->url = config('enconfig.iamapp_url');
    }
	/**
     * Function is used to verify session token set this domain using IAM api(Laravel)
     * @author Vishal Chaudhari
     * @access public
     * @package Authenticate
     * @param array $options
     */
    public function verifysesstoken($options)
    {
        //$data = $this->remoteapi->apicall("POST", $this->url, 'api/verifysesstoken', $options);
        $data = $this->remoteapi->apicall("POST", trim($this->url,"/"), 'api/shared_session/verifysesstoken', $options);
        return $data;
    }
    
    /**
     * Function is used to fetch logged in session details using session token
     * @author Vishal Chaudhari
     * @access public
     * @package Authenticate
     * @param domainkey string
     * @param accesstoken string
     */
    public function sessdetails($options)
    {
        $data = $this->remoteapi->apicall("POST", trim($this->url,"/"), 'api/shared_session/gettokendata', $options);
        return $data;
    }
    
    /**
     * Function is used to acknowldge auth domain session has been set for current domain. 
     * @author Vishal Chaudhari
     * @access public
     * @package Authenticate
     * @param domainkey string
     * @param accesstoken string
     */
    public function setsessionack($options)
    {
        $data = $this->remoteapi->apicall("POST", trim($this->url,"/"), 'api/shared_session/setsessionack', $options);
        return $data;
    }
    
	
}

