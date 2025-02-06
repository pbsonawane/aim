<?php
use App\Http\Controllers\Controller;
use App\Services\eNsysconfig\Enlog;

function _isset($data, $index = "", $default = "")
{
    if (is_array($data) && isset($data[$index]))
    {
        if (is_array($data[$index]))
        {
            return $data[$index];
        }

        if (trim($data[$index]) != '')
        {
            return $data[$index];
        }
        else if (trim($data[$index]) == '' && trim($default) != '')
        {
            return $default;
        }

        return '';
    }
    else
    {
        return $default;
    }

}
function get_idarr_from_objectarr($obj_arr_ids, $field)
{
    $arr = array();

    if (!$obj_arr_ids->isEmpty())
    {

        foreach ($obj_arr_ids as $id)
        {
            if (isset($id->$field))
            {
                $arr[] = $id->$field;
            }
            else
            {
                return '';
            }
        }
        return $arr;
    }
    else
    {
        return '';
    }
}
function _unset($data, $keys)
{
    if (is_array($data) && $keys != '')
    {
        $keys = explode(",", $keys);
        foreach ($keys as $key)
        {
            unset($data[trim($key)]);
        }
        return $data;
    }
    else
    {
        return $data;
    }

}
function keytoarray($data, $key)
{
    $key2array = array();
    if (is_array($data) && count($data) > 0)
    {
        foreach ($data as $val)
        {
            $key2array[$val[$key]] = $val;
        }
    }
    return $key2array;
}
function arraycontainotherarray($search_this, $all)
{
    return count(array_intersect($search_this, $all)) == count($search_this) ? true : false;
}
function json_to_array($json)
{
    $result = array();
    if ($json !== '')
    {
        $result = isjson($json) ? json_decode($json, true) : $result;
    }
    return $result;
}
function isjson($string)
{
    return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
}
function converttojson($string, $fromtype = "string")
{
    if ($fromtype === 'string')
    {
        return $string !== '' ? json_encode(explode(",", $string)) : "[]";
    }
    else if ($fromtype === 'array')
    {
        return $string !== '' ? json_encode($string) : "[]";
    }

}
function save_errlog($function="",$functionality="",$parameters=array(),$errormsg="",$index='error'){
    //Maintain Error Log
    $enlog = new Enlog();
    
    $error_data['function']		 = $function; 
    $error_data['functionality'] = $functionality;
    $error_data['error']		 = $errormsg;
    
    if(is_array($parameters) && count($parameters) > 0){
        $error_data['parameters'] = json_encode($parameters);
    }
    
    $res = $enlog->$index($error_data);
    return $res;
}
function apilog($msg)
{
    $api_logs_path = config('enconfig.api_log_path');
    $logs_enable = config('enconfig.api_log_enable');
    if($logs_enable)
    {
        tracklog($api_logs_path,$msg);
    }
}

function tracklog($log_path,$msg)
{
    if($log_path != '')
    {
        $log_file = $log_path;
        $logmsg = date('d/m/Y H:i:s')." : ".$msg."\n";            
        @file_put_contents($log_file, $logmsg, FILE_APPEND);
    }

}
function limitoffset($limit="", $page="")
{
    $limit = $limit != '' ? $limit : config('enconfig.def_limit');
    $page = $page != '' ? $page : config('enconfig.page');
    
    //$offset = is_numeric($limit) ? $limit * $page : 0;

    $offset = ($page - 1) * $limit;

    return array("limit" => $limit, "page" => $page, "offset" => $offset);
}

if (!function_exists('urlGenerator')) {

    /**

     * @return \Laravel\Lumen\Routing\UrlGenerator

     */

    function urlGenerator() {

        return new \Laravel\Lumen\Routing\UrlGenerator(app());

    }

}



if (!function_exists('asset')) {

    /**

     * @param $path

     * @param bool $secured

     *

     * @return string

     */

    function asset($path, $secured = false) {

        return urlGenerator()->asset($path, $secured);

    }

}

function validation_composite_unique_without_status_for_json_data($parameters){
    // remove first parameter and assume it is the table name
    $table         = array_shift($parameters);
    $parameters[3] = isset($parameters[3]) ? $parameters[3] : '';
    $parameters[4] = isset($parameters[4]) ? $parameters[4] : '';
    
    $valid_uuid = (bool) preg_match("/[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}/", $parameters[4]);

    $query = DB::table($table)
        ->select(trim($parameters[0]))
        //->whereRaw('JSON_EXTRACT('.$parameters[0].', "$.'.$parameters[1].'") = ?', [ strtolower($parameters[2]) ]);
        ->whereRaw('LOWER('.$parameters[0].'->"$.'.$parameters[1].'") = JSON_QUOTE(lower("'.$parameters[2].'"))');
    if (isset($parameters[3]) != "" && isset($parameters[4]) != "" && $valid_uuid === true)
    {
        $query->where(trim($parameters[3]), '!=', DB::raw('UUID_TO_BIN("'.$parameters[4].'")'));
    }

    $data = $query->get();
    return count($data) ? false : true;
}


/*
Below function is to decrypt data from CRM - contact details.
*/
function crm_encrypt_decrypt_data($string, $action = 'dc')
{
$result_string = '';
if ($string != '') {
//$password = '3sc3RLrpd17'; //may be changed in future
$password = 'bEN3bVdHQ04vM1ZySGJsMXFPTlJBdz09';
// CBC has an IV and thus needs randomness every time a message is encrypted
$method = 'aes-256-cbc';
// Must be exact 32 chars (256 bit)
$key = substr(hash('sha256', $password, true), 0, 32);
// IV must be exact 16 chars (128 bit)
$iv = chr(0x44) . chr(0x41) . chr(0x42) . chr(0x43) . chr(0x44) . chr(0x46) . chr(0x48) . chr(0x49) . chr(0x50) . chr(0x51) . chr(0x52) . chr(0x53) . chr(0x54) . chr(0x55) . chr(0x56) . chr(0x57);



$result_string = openssl_decrypt(base64_decode($string), $method, $key, OPENSSL_RAW_DATA, $iv);
if ($action == 'ec') {
$result_string = base64_encode(openssl_encrypt($string, $method, $key, OPENSSL_RAW_DATA, $iv));
}
}
return $result_string;
}