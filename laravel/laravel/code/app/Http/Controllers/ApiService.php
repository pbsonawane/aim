<?php

namespace App\Http\Controllers;

class ApiService extends Controller
{
    public function apiCurlRequest($actionUrl, $data, $userName, $password)
    {
        $curl = curl_init($actionUrl);

        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($curl, CURLOPT_USERPWD, "$userName:$password");
        $headers = array('Authorization: Basic ' . base64_encode("$userName:$password"));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        echo "O/P: " . $result = curl_exec($curl);
        return $result;
    }

    public function fetchDataFromApi()
    {
        $wsdl = "http://115.124.127.130/~crmesdsdev/uat/sku_api_rest.php?wsdl";
        $request = [];

        $json_data = $this->apiCurlRequest($wsdl, $request, 'crmiapiclient', '6AG?xR$s4;P9$??!K');
       // print_r($json_data);
        //die();

        // Rest of your code
    }
}