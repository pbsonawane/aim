<?php
/*
Author: Vishal Chaudhari
Description: This library load the configuration from database and make configuration available to whole codeigniter
 */
/**
 * Use:
 *     $this->load->library('enconfig');
 *
 *
 **/
namespace App\Services\eNsysconfig;
use  App\Services\Restapi;
use  App\Services\RemoteApi;

class Enconfig
{
    /**
     * @param $ci
     */
    public function __construct($ci = true)
    {
        $this->item = '';
        $this->ensysconfig = 'ensysconfig';
        $this->item = $this->load_config("all", $ci); 
    }

    /**
     * @param $from
     * @param $config_item
     * @return mixed
     */
    public function load_config($config_item = "", $codeigniter = true)
    {
        global $system_settings, $restapiobj;
        try {
            if ($codeigniter)
            {
                //$this->ci = &get_instance();
                //$this->ci->load->library('restapi');
                $restapi = new Restapi;
                $from = config('app.en_sysconfig_config');
            }
            else
            {
                $from = $system_settings['en_sysconfig_config'];
            }

            $from = $from != '' ? $from : 'api';
            $config_ = $codeigniter ? config('enconfig') : $system_settings;
            $restapi = $codeigniter ? $restapi : $restapiobj;
            if ($from == "api")
            {
                apilog("+++++API++++++++");
               //                $url = $config_['en_sysconfig_api_url'].'/config/get?config_item='.$config_item;
                $url = config('app.en_sysconfig_api_url').'/config/get?config_item='.$config_item;
                $this->item = $restapi->apicall('get', $url);
                $this->item = $this->process_config($this->item);
            }
            else if ($from == "local")
            {
                apilog("+++++LOCAL++++++++");
                if (!is_dir(config('app.en_sysconfig_config_path')))
                {
                    if (!@mkdir(config('app.en_sysconfig_config_path'), 0755, true))
                    {
                        die("Unable to create system directory");
                    }
                }
                if (file_exists(config('app.en_sysconfig_config_path')."/".$this->ensysconfig))
                {
                    $data = file_get_contents(config('app.en_sysconfig_config_path')."/".$this->ensysconfig);
                    if (json_decode($data, true))
                    {
                        $this->item = json_decode($data, true);
                        return $this->item;
                    }
                }
                else
                {
                    // write file
                    $url = config('app.en_sysconfig_api_url').'/config/get?config_item='.$config_item;
                    $this->item = $restapi->apicall('get', $url);
                    $this->item = $this->process_config($this->item);
                    $config_key_value = false; // set false for json , set true for key value
                    $config_str = '';
                    if ($config_key_value)
                    {
                        if (is_array($this->item))
                        {
                            foreach ($this->item as $key => $val)
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
                        $config_str = json_encode($this->item);
                    }
                    if (!is_writable(config('app.en_sysconfig_config_path')))
                    {
                        die(trans('messages.155')." => ".config('app.en_sysconfig_config_path')."/".$this->ensysconfig);
                    }
                    $write = file_put_contents(config('app.en_sysconfig_config_path')."/".$this->ensysconfig, $config_str);
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
           // echo response()->json($data);
            die();
        }
        catch (Error $e)
        {
            $data['data'] = '';
            $data['message']['error'] = $e->message();
            $data['status'] = 'error';
           // echo response()->json($data);
            die();
        }
        if ($codeigniter)
        {
            //$this->ci->config->set_item('language', _isset($this->item, 'language'));
           config('app.language', _isset($this->item, 'language'));

        }
        return $this->item;
    }
    /**
     * @param $response
     * @return mixed
     */
    public function process_config($response)
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
}