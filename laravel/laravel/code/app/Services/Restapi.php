<?php
namespace App\Services;
/*
Author: Namrata Thakur
Description: This library used to call REST API's
 */
/**
 * Use:
 *     use App\Services\Restapi
 *
 **/
class Restapi
{
    /**
     * @var string
     */
    protected $_useragent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1';
    /**
     * @var mixed
     */
    protected $_url;
    /**
     * @var mixed
     */
    protected $_followlocation;
    /**
     * @var mixed
     */
    protected $_cookieFileLocation;
    /**
     * @var mixed
     */
    protected $_timeout = 0;
    /**
     * @var mixed
     */
    protected $_maxRedirects;

    /**
     * @var mixed
     */
    protected $_post;
    /**
     * @var mixed
     */
    protected $_postFields;
    /**
     * @var string
     */
    protected $_referer = "";

    /**
     * @var mixed
     */
    protected $_session;
    /**
     * @var mixed
     */
    protected $_webpage;
    /**
     * @var mixed
     */
    protected $_includeHeader;
    /**
     * @var mixed
     */
    protected $_noBody;
    /**
     * @var mixed
     */
    protected $_status;
    /**
     * @var mixed
     */
    protected $_binaryTransfer;
    /**
     * @var int
     */
    public $authentication = 0;
    /**
     * @var string
     */
    public $auth_name = '';
    /**
     * @var string
     */
    public $auth_pass = '';
    /**
     * @var mixed
     */
    public $CURLOPT_AUTOREFERER = true;

    /**
     * @param $url
     * @param $followlocation
     * @param true $timeOut
     * @param $maxRedirecs
     * @param $binaryTransfer
     * @param false $includeHeader
     * @param false $noBody
     */
    public function __construct($url = "", $followlocation = true, $timeOut = 1, $maxRedirecs = 4, $binaryTransfer = false, $includeHeader = false, $noBody = false)
    {
        $this->_url = $url;
        $this->_followlocation = $followlocation;
        $this->_timeout = 0;//$timeOut;
        $this->_maxRedirects = $maxRedirecs;
        $this->_noBody = $noBody;
        $this->_includeHeader = $includeHeader;
        $this->_binaryTransfer = $binaryTransfer;
    }

    /**
     * @param $referer
     */
    public function setReferer($referer)
    {
        $this->_referer = $referer;
    }

    /**
     * @param $path
     */
    public function setCookiFileLocation($path)
    {
        $this->_cookieFileLocation = $path;
    }

    /**
     * @param $postFields
     */
    public function setPost($postFields)
    {
        $this->_post = true;
        $this->_postFields = $postFields;
    }

    /**
     * @param $userAgent
     */
    public function setUserAgent($userAgent)
    {
        $this->_useragent = $userAgent;
    }

    /**
     * @param $url
     */
    public function apicall($method, $base_uri, $api = "", $options = [], $request = [])
    {
        try
        {
            $is_error = true;
            $output = '';
            $msg = trans('messages.163');
            $resp = [];
            $method = $method == "" ? "GET" : $method;
            $this->_post = strtolower($method) == "post" ? true : false;
            $this->_url = $base_uri;
            $s = curl_init();

            curl_setopt($s, CURLOPT_URL, $this->_url);
            curl_setopt($s, CURLOPT_HTTPHEADER, ['Expect:']);
            curl_setopt($s, CURLOPT_TIMEOUT, $this->_timeout);
            curl_setopt($s, CURLOPT_MAXREDIRS, $this->_maxRedirects);
            curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($s, CURLOPT_FOLLOWLOCATION, $this->_followlocation);
            curl_setopt($s, CURLOPT_COOKIEJAR, $this->_cookieFileLocation);
            curl_setopt($s, CURLOPT_COOKIEFILE, $this->_cookieFileLocation);
            curl_setopt($s, CURLOPT_AUTOREFERER, $this->CURLOPT_AUTOREFERER);
            curl_setopt($s, CURLOPT_CONNECTTIMEOUT, $this->_timeout);
            curl_setopt($s, CURLOPT_SSL_VERIFYPEER, false);

            if ($this->authentication == 1)
            {
                curl_setopt($s, CURLOPT_USERPWD, $this->auth_name.':'.$this->auth_pass);
            }
            if ($this->_post)
            {
                $this->_postFields = _isset($options, 'form_params');
                curl_setopt($s, CURLOPT_POST, true);
                curl_setopt($s, CURLOPT_POSTFIELDS, $this->_postFields);
            }

            if ($this->_includeHeader)
            {
                curl_setopt($s, CURLOPT_HEADER, true);
            }

            if ($this->_noBody)
            {
                curl_setopt($s, CURLOPT_NOBODY, true);
            }
            /*
            if($this->_binary)
            {
            curl_setopt($s,CURLOPT_BINARYTRANSFER,true);
            }
             */

            curl_setopt($s, CURLOPT_USERAGENT, $this->_useragent);
            curl_setopt($s, CURLOPT_REFERER, $this->_referer);

            $response = curl_exec($s);
          //  echo "************************************";
           // print_r($response);
			
			$curlinfo	= curl_getinfo($s);
			$statuscode	= _isset($curlinfo, 'http_code');
			
            if (!curl_errno($s))
            {
                //error message
                $is_error = false;
                $curlinfo = curl_getinfo($s);
            }
            else
            {
                $is_error = true;
                $msg = curl_error($s);
            }
            if (!$is_error)
            {
                if (_isset($curlinfo, 'http_code') == "200")
                {
                    $resp = $this->sendoutput($response, $options,$statuscode);
                }
                else
                {
                    $is_error = true;
                    $msg = $curlinfo['http_code'];
                    $output = $response;
					
					$formparams['url'] = $base_uri."/".$api;
					save_errlog($api,"Rest API call from IAM",$formparams,"HTTP Status code - ".$statuscode);
                }
            }
        }
        catch (Exception $e)
        {
            $is_error = true;
            $curl_error = $e->getMessage();
        }
        finally
        {
            curl_close($s);
            if ($is_error)
            {
                $resp['is_error']	= $is_error;
                $resp['content']	= $output;
                $resp['msg']		= $msg;
				$resp['http_code']	= $statuscode;
            }

            return $resp;
        }
    }

    /**
     * @return mixed
     */
    public function getHttpStatus()
    {
        return $this->_status;
    }

    /**
     * @return mixed
     */
    public function __tostring()
    {
        return $this->_webpage;
    }
    /**
     * @param $use
     */
    public function useAuth($use)
    {
        $this->authentication = 0;
        if ($use == true)
        {
            $this->authentication = 1;
        }

    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->auth_name = $name;
    }
    /**
     * @param $pass
     */
    public function setPass($pass)
    {
        $this->auth_pass = $pass;
    }
	function removeBomUtf8($s){
	   if(substr($s,0,3)==chr(hexdec('EF')).chr(hexdec('BB')).chr(hexdec('BF'))){
			return substr($s,3);
		}else{
			return $s;
		}
	}

    /**
     * @param $data
     * @param $options
     * @return mixed
     */
    public function sendoutput($data, $options,$statuscode)
    {
        $responsedata = null;
      //  print_r($data); 
        $data = trim($data);
		$data = $this->removeBomUtf8($data);
        if (json_decode($data))
        {
            $is_error = false;
            $msg = '';
            $json_data = json_decode($data, true);
            if ($json_data['status'] == "error")
            {
                $is_error = true;
                $message = _isset($json_data, 'message');
                if (isset($message))
                {
                    $headererrors = $message["error"];
                    if (is_array($headererrors))
                    {
                        $msg = $headererrors;
                        foreach ($headererrors as $index => $error)
                        {
                            if ($index == '115')
                            {
                                //refresh token
                                //refreshInProgress()
                            }
                            else if ($index == '113' || $index == '117')
                            {
                                //redirect to logout url
                            }
                            else if ($index == '143')
                            {
                                //redirect to logout
                            }
                        }
                    }
                    else
                    {
                        $msg = $headererrors; // Updated By Vikas as error messgae was not displayed for single error.
                    }
                }
                else
                {
                    $msg[] = trans('messages.161');
                }
            }
            else
            {
                /*if($options['responseType'] == "array")
                return $json_data['data'];
                else if($options['responseType'] == "object")
                return json_decode($data);
                else if($options['responseType'] == "json")
                return $data;
                else
                return $data;
                 */
                $responsedata = $json_data['data'];
                $is_error = false;
                $msg = _isset($json_data['message'], $json_data['status']);
            }
        }
        else
        {
            $responsedata = null;
            $is_error = true;
            $msg = trans('messages.161');
        }
        $response['content']	= $responsedata;
        $response['is_error']	= $is_error;
        $response['msg']		= $msg;
		$response['http_code']	= $statuscode;
		
		$response = set_http_code_errmsg($response);
        return $response;
    }
    /**
     * @return mixed
     */
    private function request_ip()
    {
        return $_SERVER['HTTP_X_FORWARDED_FOR'] != "" ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
    }
    /**
     * @return mixed
     */
    private function request_method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    /**
     * @return mixed
     */
    private function request_referrer()
    {
        return $_SERVER['HTTP_REFERER'];
    }
    /**
     * @return mixed
     */
    private function request_agent()
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }
    /**
     * @return mixed
     */
    private function request_fullurl()
    {
        return $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")."://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
    }
}
