<?php
namespace App\Services;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use Illuminate\Http\Request;

class RemoteApi
{
    /**
     * You can specify the query string parameters using the query request option as an array.
     * $response = $client->request('GET', 'http://httpbin.org?foo=bar');
     * $client->request('GET', 'http://httpbin.org', [
     *'query' => ['foo' => 'bar']
     * ]);
     * $client->request('GET', 'http://httpbin.org', ['query' => 'foo=bar']);
     */
    public function apicall($method, $base_uri, $api, $options = array())
    {
        $options['http_errors'] = false;
        $request = new Request();
     		$headeroptions = array("POST" => array("Content-Type" => "application/json"));
        //$request = request();
       
        if(isset($options['headeroptions']))
        {  
            $headeroptions = array("POST" => array("Content-Type" => $options['headeroptions'])); 
            unset($options['headeroptions']);
        }
         
        $method = $method == "" ? "GET" : $method;
        @$options['responseType'] = !isset($options['responseType']) ? "array" : $options['responseType'];
		$apiresp = isset($options['apiresp']) && isset($options['apiresp']) ? true : false;
		$donotprocess = isset($options['donotprocess']) && isset($options['donotprocess']) ? true : false;
        $basicAuth = isset($options['basicAuth']) && isset($options['basicAuth']) ? true : false;
        
	   $token =  isset($options['token']) ? $options['token'] : '';

       if (!isset($options['headers']) && $token !="")
        {
            $header_opt['headers'] = [
                'Authorization' => $token
            ];
        }
		// Set Request information
		$header_opt['headers']['remoteip'] = (string)$request->ip();
		$header_opt['headers']['method'] = (string)$request->method(); //$request->method() == "" ? $request->method(): (string)$method;
		$header_opt['headers']['fullurl'] = (string)$request->fullUrl();
        $header_opt['headers']['agent'] = (string)$request->header('user-agent');
        $header_opt['headers']['root'] = (string)$request->root();
        //$header_opt['headers']['servertype'] = (string)"server";
		// integrate kong authentication key
		//$options['form_params']['360permit'] = '9ac9a78e11e9G4Su';
		$gatewaykey = config('enconfig.gateway_status') ? "?".config('enconfig.gateway_key')."=".config('enconfig.gateway_value') : '';
		$base_uri = config('enconfig.gateway_status') ? config('enconfig.gateway_url') : $base_uri;
		
        @$options['timeout'] = 0;
        $options['headers'] = $header_opt['headers'];
        if($method == "POST")
        {
            //$options['headers'] = array_merge($options['headers'], isset($headeroptions[$method]) ? $headeroptions[$method] : array());
			//$options['headers'] = $headeroptions[$method];
        }      
        #echo $method; #echo $url; #echo $api; #
        //print_r($options);
        try
        {
			//if($method == "POST" || $method == "GET" )
			{
				$basic_auth = $basicAuth ? ['auth' => [$options['basicAuthUser'], $options['basicAuthPwd']]] : array();
				$client = new Client($basic_auth);
				apilog($method." => ".$base_uri."/".$api." options => ".json_encode($options));
				$res = $client->request($method, $base_uri."/".$api.$gatewaykey, $options); 
			}
			/*else if($method == "PUT")
			{
				apilog($method." => ".$base_uri."/".$api." options => ".json_encode($options));
				$basic_auth = $basicAuth ? ['auth' => [$options['basicAuthUser'], $options['basicAuthPwd']]] : array();
				$client = new Client($basic_auth);
				apilog($method." => ".$base_uri."/".$api." options => ".json_encode($options));
				$res = $client->request($method, $base_uri."/".$api.$gatewaykey, $options); 
			}*/
			$statuscode = $res->getStatusCode();
            apilog($statuscode);
			$resp = false;
            apilog($method." => ".$base_uri."/".$api." options => ".json_encode($options));
            if ($statuscode == "200")
            {
                $data = $res->getBody()->getContents();
                apilog("Repsonse => ".$data);
				if($donotprocess)
					$resp = $data;	
				else if($apiresp)
					$resp = $this->apioutput($data, $options);	
				else						
					$resp = $this->sendoutput($data, $options);
			}
            else
            {
                $data = $res->getBody()->getContents();
                apilog("Repsonse => ".$data);
                $resp = false;
            }
            return $resp;
        }
		catch (GuzzleHttp\Exception\ClientException $e)
		{
			$response = $e->getResponse();
			$responseBodyAsString = $response->getBody()->getContents();
			return false;
		}
        catch (RequestException $e)
        {
			$response = $this->StatusCodeHandling($e);
			return false;
            
        }
    }
	 function removeBomUtf8($s){
       if(substr($s,0,3)==chr(hexdec('EF')).chr(hexdec('BB')).chr(hexdec('BF'))){
            return substr($s,3);
        }else{
            return $s;
        }
    }
    public function sendoutput($data_response, $options,$statuscode='')
    {
        $responsedata = null;
        $data_req = $this->removeBomUtf8($data_response);
        if(!json_decode((string) $data_req))
        {   
             apilog("Applied data filter");     
             $data_req =  preg_replace('/[^A-Za-z0-9\-\{\}\:\[\]\-_\"\\\\ \/,\#\?\$\@\&\!\%\(\)\+\-\.]/', '', $data_req);   
        }
        
        if (json_decode((string) $data_req))
        {
            $is_error = false;
            $msg = '';
            $json_data = json_decode((string) $data_req,true);
            
            if ($json_data['status'] == "error")
            {
                $responsedata   = $json_data['data'];
                $is_error       = true;
                $message        = _isset($json_data, 'message');
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
                        $msg = $headererrors;  // Updated By Vikas as error messgae was not displayed for single error.
                    }
                }
                else
                {
                    $msg[] = trans('messages.161');
                }
            }
            else
            {
                $responsedata = $json_data['data'];
                $is_error = false;
                $msg = _isset($json_data['message'],$json_data['status']);
            }
        }
        else
        {
            $responsedata = null;
            $is_error = true;
            $msg = "Unable to process request";
        }

        $response['content'] = $responsedata;
        $response['is_error'] = $is_error;
        $response['msg'] = $msg;
        return $response;
    }
    public function apioutput($data, $options)
    {
        $responsedata = null;
        if (json_decode($data))
        {
            $is_error = false;
            $msg = '';
            $json_data = json_decode($data, true);
			if (_isset($json_data,'error'))
            {
                $is_error = true;
                $message = _isset($json_data, 'message');
                if (isset($message))
                {
                    $msg = "[".$json_data['error']."] ".$message;
                }
                else
                {
                    $msg[] = trans('messages.161');
                }
            }
            else
            {
                $responsedata = $json_data['data'];
                $is_error = false;
                $msg = '';
            }
        }
        else
        {
            $responsedata = null;
            $is_error = true;
            $msg = trans('messages.161');
        }
        $response['content'] = $responsedata;
        $response['is_error'] = $is_error;
        $response['msg'] = $msg;
        return $response;
    }
}
