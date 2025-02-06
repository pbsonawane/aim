<?php
/*
Author: Vishal Chaudhari
Description: This library load the log error, response, click, sqlerrors to elastic search database
 */
/**
 * Use:
 *      $this->load->library('enlog');
 *              or
 *      $enlog = new Enlog(false);
 *
 *      $CI->enlog->error($error_data);             or $enlog->error($error_data);
 *      $CI->enlog->sqlerror($error_data);
 *      $CI->enlog->click($error_data);
 *      $CI->enlog->response($error_data);

 *
 *
 **/

namespace App\Services\eNsysconfig;
use App\Services\eNsysconfig\Enconfig;
use App\Services\Restapi;

class Enlog
{
    /**
     * @param $ci
     */
    public function __construct($ci = true)
    {
        $this->ci = $ci;
         
        /*if ($ci)
        {
            $this->ci = &get_instance();
            $this->ci->load->library('restapi');
        }*/
    }

    /**
     * @param $options
     * @return mixed
     */
    /**
     * @param $options
     */
    public function error($dataarray)
    {
        global $system_settings, $restapiobj, $enconfig;
       /*
        Commentd - Namrata - to avoid multiple call to "load_config" 
        $enconfig_s = new Enconfig;
        $config_ = $this->ci ? config('enconfig') : $system_settings;
        $enconfig_ = $this->ci ? $enconfig_s->item : $enconfig->item;
        */
        try {
            //if (isset($enconfig_['error_reporting']) && !$enconfig_['error_reporting'])
            if(!config('enconfig.error_reporting'))
            {
                return false;
            }

            if ($this->ci)
            {
               // $data['user_id'] = $this->ci->session->userdata('EMUSERID');
                //$data['username'] = $this->ci->session->userdata('EMUSERNAME');
                $data['user_id'] = (string)getSessionItem('user_id');
                $data['username'] = (string)getSessionItem('username');
            }
            else
            {
                $data['user_id'] = _isset($dataarray, 'user_id');
                $data['username'] = _isset($dataarray, 'username');
            }

            $data['function'] = _isset($dataarray, 'function');
            $data['functionality'] = _isset($dataarray, 'functionality');
            $data['command'] = _isset($dataarray, 'command');
            $data['parameters'] = _isset($dataarray, 'parameters'); // parameters
            $data['error'] = _isset($dataarray, 'error'); // Error
            $options['method'] = 'POST';
            //$options['url'] = $config_['en_sysconfig_api_url'].'/log/error';
            $options['url'] = config('app.en_sysconfig_api_url').'/log/error';
          
            return $this->writelog($options, $data);
        }
        catch (Exception $e)
        {
            $data['data'] = '';
            $data['message']['error'] = $e->message();
            $data['status'] = 'error';
            //echo response()->json($data);
            die();
        }
        catch (Error $e)
        {
            $data['data'] = '';
            $data['message']['error'] = $e->message();
            $data['status'] = 'error';
            //echo response()->json($data);
            die();
        }
        return $this->message;
    }

    public function debug($dataarray)
    {
        global $system_settings, $restapiobj, $enconfig;
       /*
        Commentd - Namrata - to avoid multiple call to "load_config" 
        $enconfig_s = new Enconfig;
        $config_ = $this->ci ? config('enconfig') : $system_settings;
        $enconfig_ = $this->ci ? $enconfig_s->item : $enconfig->item;
        */
        try {
            //if (!$enconfig_['error_reporting'])
            if(!config('enconfig.error_reporting'))
            {
                return false;
            }

            if ($this->ci)
            {
                //$data['user_id'] = $this->ci->session->userdata('EMUSERID');
                //$data['username'] = $this->ci->session->userdata('EMUSERNAME');
                $data['user_id'] = (string)getSessionItem('user_id');
                $data['username'] = (string)getSessionItem('username');
            }
            else
            {
                $data['user_id'] = _isset($dataarray, 'user_id');
                $data['username'] = _isset($dataarray, 'username');
            }

            $data['function'] = _isset($dataarray, 'function_name');
            $data['functionality'] = _isset($dataarray, 'functionality');
            $data['command'] = _isset($dataarray, 'command');
            $data['parameters'] = _isset($dataarray, 'parameters'); // parameters
            $data['error'] = _isset($dataarray, 'error'); // Error
            $options['method'] = 'POST';
            //$options['url'] = $config_['en_sysconfig_api_url'].'/log/debug';
            $options['url'] = config('app.en_sysconfig_api_url').'/log/debug';
            
            return $this->writelog($options, $data);
        }
        catch (Exception $e)
        {
            $data['data'] = '';
            $data['message']['error'] = $e->message();
            $data['status'] = 'error';
            //echo response()->json($data);
            die();
        }
        catch (Error $e)
        {
            $data['data'] = '';
            $data['message']['error'] = $e->message();
            $data['status'] = 'error';
            //echo response()->json($data);
            die();
        }
        return $this->message;
    }
    /**
     * @param $dataarray
     * @return mixed
     */
    public function sqlerror($dataarray)
    {
        global $system_settings, $restapiobj, $enconfig;
        /*$enconfig_s = new Enconfig;

        $config_ = $this->ci ? config('enconfig') : $system_settings;
        $enconfig_ = $this->ci ? $enconfig_s->item : $enconfig->item;*/
        try {
            //if (!$enconfig_['error_reporting'])
            if(!config('enconfig.error_reporting'))
            {
                return false;
            }

            if ($this->ci)
            {
                //$data['user_id'] = $this->ci->session->userdata('EMUSERID');
                //$data['username'] = $this->ci->session->userdata('EMUSERNAME');
                $data['user_id'] = (string)getSessionItem('user_id');
                $data['username'] = (string)getSessionItem('username');
            }
            else
            {
                $data['user_id'] = _isset($dataarray, 'user_id');
                $data['username'] = _isset($dataarray, 'username');
            }

            $data['functionality'] = _isset($dataarray, 'functionality');
            $data['command'] = _isset($dataarray, 'command');
            $data['parameters'] = _isset($dataarray, 'parameters'); // parameters
            $data['error'] = _isset($dataarray, 'error'); // Error
            $options['method'] = 'POST';
            //$options['url'] = $config_['en_sysconfig_api_url'].'/log/sqlerror';
            $options['url'] = config('app.en_sysconfig_api_url').'/log/sqlerror';
            
            return $this->writelog($options, $data);
        }
        catch (Exception $e)
        {
            $data['data'] = '';
            $data['message']['error'] = $e->message();
            $data['status'] = 'error';
            //echo response()->json($data);
            die();
        }
        catch (Error $e)
        {
            $data['data'] = '';
            $data['message']['error'] = $e->message();
            $data['status'] = 'error';
            //echo response()->json($data);
            die();
        }
        return $this->message;
    }

    /**
     * @param $dataarray
     * @return mixed
     */
    public function response($dataarray)
    {
        global $system_settings, $restapiobj, $enconfig;
        /*$enconfig_s = new Enconfig;

        $config_ = $this->ci ? config('enconfig') : $system_settings;
        $enconfig_ = $this->ci ? $enconfig_s->item : $enconfig->item;*/

        try {
            //if (!$enconfig_['error_reporting'])
            if(!config('enconfig.error_reporting'))
            {
                return false;
            }

            if ($this->ci)
            {
                //$data['user_id'] = $this->ci->session->userdata('EMUSERID');
                //$data['username'] = $this->ci->session->userdata('EMUSERNAME');
                $data['user_id'] = (string)getSessionItem('user_id');
                $data['username'] = (string)getSessionItem('username');
            }
            else
            {
                $data['user_id'] = _isset($dataarray, 'user_id');
                $data['username'] = _isset($dataarray, 'username');
            }

            $data['function'] = _isset($dataarray, 'function_name');
            $data['functionality'] = _isset($dataarray, 'functionality');
            $data['command'] = _isset($dataarray, 'command');
            $data['parameters'] = _isset($dataarray, 'parameters'); // parameters
            $data['response'] = _isset($dataarray, 'response'); // Error
            $options['method'] = 'POST';
            //$options['url'] = $config_['en_sysconfig_api_url'].'/log/response';
            $options['url'] = config('app.en_sysconfig_api_url').'/log/response';
            
            return $this->writelog($options, $data);
        }
        catch (Exception $e)
        {
            $data['data'] = '';
            $data['message']['error'] = $e->message();
            $data['status'] = 'error';
            //echo response()->json($data);
            die();
        }
        catch (Error $e)
        {
            $data['data'] = '';
            $data['message']['error'] = $e->message();
            $data['status'] = 'error';
            //echo response()->json($data);
            die();
        }
        return $this->message;
    }
    /**
     * @param $dataarray
     * @return mixed
     */
    public function click($dataarray)
    {	
        global $system_settings, $restapiobj, $enconfig;
        /*$enconfig_s = new Enconfig;

        $config_ = $this->ci ? config('enconfig') : $system_settings;
        $enconfig_ = $this->ci ? $enconfig_s->item : $enconfig->item;*/

        try {
            //if (!$enconfig_['error_reporting'])
            if(!config('enconfig.error_reporting'))
            {
                return false;
            }

            if ($this->ci)
            {
                //$data['user_id'] = $this->ci->session->userdata('EMUSERID');
                //$data['username'] = $this->ci->session->userdata('EMUSERNAME');
                $data['user_id'] = (string)getSessionItem('user_id');
                $data['username'] = (string)getSessionItem('username');
            }
            else
            {
                $data['user_id'] = _isset($dataarray, 'user_id');
                $data['username'] = _isset($dataarray, 'username');
            }

            $data['function'] = _isset($dataarray, 'function');
            $data['portal_key'] = _isset($dataarray, 'enlight');
            $data['url'] = _isset($dataarray, 'url');
            $data['msg'] = _isset($dataarray, 'msg');
            $options['method'] = 'POST';
            //$options['url'] = $config_['en_sysconfig_api_url'].'/log/click';
            $options['url'] = config('app.en_sysconfig_api_url').'/log/click';
            return $this->writelog($options, $data);
           
        }
        catch (Exception $e)
        {
            $data['data'] = '';
            $data['message']['error'] = $e->message();
            $data['status'] = 'error';
            //echo response()->json($data);
            die();
        }
        catch (Error $e)
        {
            $data['data'] = '';
            $data['message']['error'] = $e->message();
            $data['status'] = 'error';
            //echo response()->json($data);
            die();
        }
        return $this->message;
    }
    /**
     * @param $options
     */
    public function writelog($options, $data)
    {
        global $system_settings, $restapiobj;
        $restapi = new Restapi;

        //$config_ = $this->ci ? config('enconfig') : $system_settings;
        $restapi = $this->ci ? $restapi : $restapiobj;

        $url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        if (strpos($url, 'emtimer') !== false)
        {
            return false;
        }
        $agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $ref_url = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '';
        $ipaddress = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';

        $data['url'] = $url;
        $data['ref_url'] = $ref_url;
        $data['ipaddress'] = $ipaddress;
        $data['agent'] = $agent;
        $url = _isset($options, 'url');
        $options['form_params'] = $data;
        return $resp = $restapi->apicall(_isset($options, 'method'), $url, '', $options);
        
    }
}