<?php
namespace App\Services;
use App\Services\Restapi;
use App\Services\Configsettings;
//use Illuminate\Support\Traits\Macroable;

	/*
	Author: Namrata Thakur
	Description: This library load the configuration from database and make configuration available to whole codeigniter
	*/
	
	/**
	 * Use:
	 *     use App\Services\Enmessages
	 *
	 **/	
class Enmessages
{
    /**
     * @param $ci
     */
    public function __construct($ci = true)
    {
        $this->message = '';
        $this->enmessages = 'enmessages';
        $this->message = $this->load_message("all", $ci);
            
    }

    /**
     * @param $from
     * @param $message_type
     * @return mixed
     */
    public function load_message($message_type = "", $codeigniter = true)
    {
        global $system_settings, $restapiobj;
        $configsettings = new Configsettings;
        $restapi = new Restapi;  

        try {
            if ($codeigniter)
            {
                //$this->ci = &get_instance();
                //$this->ci->load->library('restapi');                
                $from = config('app.en_sysconfig_config');
            }
            else
            {
                $from = $system_settings['en_sysconfig_config'];
            }

            $from = $from != '' ? $from : 'api';
            if($codeigniter)
            {
                 $config_['en_sysconfig_api_url'] = config('app.en_sysconfig_api_url');
                 $config_['en_sysconfig_config_path'] = config('app.en_sysconfig_config_path');
            }
            else
            {
                $config_ = $system_settings;
            }
           
            $restapi = $codeigniter ? $restapi: $restapiobj;
            if ($from == "api")
            {
                $url = $config_['en_sysconfig_api_url'].'/message/get?message_type='.$message_type;
                $this->message = $restapi->apicall('get', $url);
                $this->message = $this->process_message($this->message);
            }
            else if ($from == "local")
            {
                if (!is_dir($config_['en_sysconfig_config_path']))
                {
                    if (!@mkdir($config_['en_sysconfig_config_path'], 0755, true))
                    {
                        die(trans('messages.153'));
                    }
                }
                if (file_exists($config_['en_sysconfig_config_path']."/".$this->enmessages))
                {
                    $data = file_get_contents($config_['en_sysconfig_config_path']."/".$this->enmessages);
                    if (json_decode($data, true))
                    {
                        $this->message = json_decode($data, true);
                        return $this->message;
                    }
                }
                else
                {
                    // write file
                    $url = $config_['en_sysconfig_api_url'].'/message/get?message_type='.$message_type;
                    $this->message = $restapi->apicall('get', $url);
                    $this->message = $this->process_message($this->message);
                    $config_key_value = true; // set false for json , set true for key value
                    $config_str = '';
                    if ($keyvalue)
                    {
                        if (is_array($this->message))
                        {
                            foreach ($this->message as $key => $val)
                            {
                                $config_str .= $key."=".$val."\n";
                            }
                        }
                        if ($config_str == '')
                        {
                            die(trans('messages.159'));
                        }
                    }
                    else
                    {
                        $config_str = json_encode($this->message);
                    }
                    if (!is_writable($config_['en_sysconfig_config_path']))
                    {
                        die(trans('messages.155')." => ".$config_['en_sysconfig_config_path']."/".$this->enmessages);
                    }
                    $write = file_put_contents($config_['en_sysconfig_config_path']."/".$this->enmessages, $config_str);
                    if (!$write)
                    {
                        die(trans('messages.157'));
                    }
                }
            }
        }
        catch (Exception $e)
        {
            $data['data'] = '';
            $data['message']['error'] = $e->message();
            $data['status'] = 'error';
            echo response()->json($data);
            die();
        }
        catch (Error $e)
        {
            $data['data'] = '';
            $data['message']['error'] = $e->message();
            $data['status'] = 'error';
            echo response()->json($data);
            die();
        }
        return $this->message;
    }
    /**
     * @param $response
     * @return mixed
     */
    public function process_message($response)
    {
        if (is_array($response) && isset($response['is_error']) && $response['is_error'])
        {
            die($response['msg']);
        }
        else
        {
            $content = _isset($response, 'content');
        }
        return $content;
    }
    /**
     * @param $key
     * @return mixed
     */
    public function show($key)
    {
        $msg = array('code' => '', 'msg' => $key);
        if (isset($this->message[$key]))
        {
            $msg = $this->message[$key];
            if($msg['msg'] == '')
            {
                $msg = array('code' => '', 'msg' => $key);        
            }           
        }
        return $msg;
    }
}