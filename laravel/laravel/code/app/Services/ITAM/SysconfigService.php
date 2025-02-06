<?php
namespace App\Services\ITAM;

use App\Services\RemoteApi;

class SysconfigService
{
    public function __construct()
    {
        $this->remoteapi = new RemoteApi;
        $this->url = config('app.en_sysconfig_api_url');
        apilog("Rebranding Url....................");
        apilog($this->url);
    }
    /**
     * Function is used to display rebranding data
     * @author Kavita Daware
     * @access public
     * @package rebranding
     * @param array $options
     */    
    public function rebranding($options = array())
    {
       $data = $this->remoteapi->apicall("GET", $this->url, 'rebranding', $options);
       return $data;
    }
   
    public function getlogo($options = array())
    {
       $data = $this->remoteapi->apicall("POST", $this->url, 'getlogo', $options);
       return $data;
    }

}

