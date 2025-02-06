<?php
namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Session;
use Lang;
use Storage;


class RemoteDownloadApi
{
    
    public function apicall($method, $base_uri, $api, $options = [])
    {
        $headeroptions = ["POST" => ['Content-Type: application/json; charset=utf-8']];
        $request = request();
        $method = $method == "" ? "GET" : $method;
        $options['responseType'] = !isset($options['responseType']) ? "array" : $options['responseType'];
        $token = getTokenFromSession();
        apilog("token in remote".$token);
        if (!isset($options['headers']))
        {
            $header_opt['headers'] = [
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

        $gatewaykey = config('enconfig.gateway_status') ? "?".config('enconfig.gateway_key')."=".config('enconfig.gateway_value') : '';
        $base_uri = config('enconfig.gateway_status') ? config('enconfig.gateway_url') : $base_uri;
        $header_opt['headers']['Content-Language'] = (string)Lang::locale();
        $options['timeout'] = 10000000000;
        $options['headers'] = $header_opt['headers'];
        try
        {   
            $client = new Client();
            $res = $client->request($method, $base_uri."/".$api.$gatewaykey, $options);
            $statuscode = $res->getStatusCode();
            $resp = false;
            apilog($method." => ".$base_uri."/".$api." options => ".json_encode($options));
            if ($statuscode == "200")
            {
                $data = (string)$res->getBody()->getContents();
                apilog("Repsonse => ".$data);
                //$resp = $this->sendoutput(trim($data), $options,$statuscode);
            }
            else
            {
                $data = $res->getBody()->getContents();
                apilog("Repsonse => ".$data);
            }
            return $data;
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
    
}
