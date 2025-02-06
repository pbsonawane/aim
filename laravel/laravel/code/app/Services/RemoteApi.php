<?php
namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Lang;
use App\Libraries\Clicklog;
use Session;

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
    public function apicall($method, $base_uri, $api, $options = [])
    {
        $headeroptions = ["POST" => ['Content-Type: application/json; charset=utf-8']];
        $request = request();
        $method = $method == "" ? "GET" : $method;
        $options['responseType'] = !isset($options['responseType']) ? "array" : $options['responseType'];
        $token = getTokenFromSession();
        if (!isset($options['headers']))
        {
            $header_opt['headers'] = [
                //'Authorization' => 'encoded '.$token
                'Authorization' => $token
            ];
        }
        // Set Request information
        $header_opt['headers']['remoteip'] = (string)$request->ip();
        $header_opt['headers']['method'] = (string)$request->method();
        $header_opt['headers']['fullurl'] = (string)$request->fullUrl();
        $header_opt['headers']['agent'] = (string)$request->header('user-agent');
        $header_opt['headers']['root'] = (string)$request->root();
        $header_opt['headers']['servertype'] = (string)"server";
        // integrate kong authentication key
        //$options['form_params']['360permit'] = '9ac9a78e11e9G4Su';
        $gatewaykey = config('enconfig.gateway_status') ? "?".config('enconfig.gateway_key')."=".config('enconfig.gateway_value') : '';
        $base_uri = config('enconfig.gateway_status') ? config('enconfig.gateway_url') : $base_uri;
        $header_opt['headers']['Content-Language'] = (string)Lang::locale();
        //$header_opt['headers']['Access-Control-Allow-Headers'] = "*";
        $options['timeout'] = 10000000000;
        $options['headers'] = $header_opt['headers'];
        //$options['headers'] = array_merge($options['headers'], isset($headeroptions[$method]) ? $headeroptions[$method] : array());
        #echo $method; #echo $url; #echo $api; #
        try
        {   
            //$this->clicklog = new Clicklog();
            //$this->clicklog->logrecords($method,'',$base_uri."/".$api.$gatewaykey);
            //save_errlog($method,$base_uri."/".$api.$gatewaykey,$options,"","click");
            $client = new Client();
            $res = $client->request($method, $base_uri."/".$api.$gatewaykey, $options);
            $statuscode = $res->getStatusCode();
            // "200"
            #print_r($res->getHeader('content-type'));
            // 'application/json; charset=utf8'
            #$data = (string)$res->getBody();
            $resp = false;
            apilog($method." => ".$base_uri."/".$api." options => ".json_encode($options));
            if ($statuscode == "200")
            {
                $data = (string)$res->getBody()->getContents();
                apilog("Repsonse => ".$data);
                $resp = $this->sendoutput(trim($data), $options,$statuscode);
            }
            else
            {
                $data = $res->getBody()->getContents();
                apilog("Repsonse => ".$data);
                //$resp = false;
                $resp = $this->sendoutput(trim($data), $options,$statuscode);
                
                $formparams['url'] = $base_uri."/".$api;
                save_errlog($api,"Remote API call from ITAM",$formparams,"HTTP Status code - ".$statuscode);
            }
            return $resp;
        }
        catch (GuzzleHttp\Exception\ClientException $e)
        {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            apilog("Repsonse => ".$responseBodyAsString);
            return false;
        }
        catch (RequestException $e)
        {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            apilog("Repsonse => ".$responseBodyAsString);
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
      
		/*
       		$res123 =  json_decode(trim(stripcslashes( $data_req)));
        switch (json_last_error()) {
         case JSON_ERROR_NONE:
             apilog(' - No errors');
         break;
         case JSON_ERROR_DEPTH:
             apilog( ' - Maximum stack depth exceeded');
         break;
         case JSON_ERROR_STATE_MISMATCH:
             apilog( ' - Underflow or the modes mismatch');
         break;
         case JSON_ERROR_CTRL_CHAR:
             apilog( ' - Unexpected control character found');
         break;
         case JSON_ERROR_SYNTAX:
             apilog( ' - Syntax error, malformed JSON');
         break;
         case JSON_ERROR_UTF8:
             apilog( ' - Malformed UTF-8 characters, possibly incorrectly encoded');
         break;
         default:
             apilog( ' - Unknown error');
         break;
        }   */
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
                $is_error     = false;
                $msg          = _isset($json_data['message'],$json_data['status']);
            }
        }
        else
        {
            $responsedata = null;
            $is_error = true;
            $msg = trans('messages.161');
        }
        
        $response['content']    = $responsedata;
        $response['is_error']   = $is_error;
        $response['msg']        = $msg;
        $response['http_code']  = $statuscode;
        
        
        $response = set_http_code_errmsg($response);
        
        return $response;
    }
}
