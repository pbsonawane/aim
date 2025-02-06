<?php
namespace App\Services;

use App\Services\Restapi;
use Session;


/*
| Auther : Sagar Sainkar
| Comment: Service for file check sum
*/

class FileChecksumService
{
    public function __construct()
    {
        
        $this->apiurl   = config('app.en_sysconfig_api_url');//config('enconfig.sysconfigservice_url');
        $this->module   = 'emagic';
        $this->Restapi  = new Restapi();
    }

    public function checksum()
    {
        try
        {
            apilog($this->apiurl."/checkchange");
            $url        = $this->apiurl."/checkchange";
            $response   = $this->Restapi->apicall('POST', $url);

            return $response;
        }
        catch(\Exception $e)
        {
            //$e->getMessage()
            return false;
        }
        catch(\Error $e)
        {
            //$e->getMessage()
            return false;
        }
    }


   /* public function checksumfilechange()
    {

        try
        {

            $segment2_array = array('set_timer', 'invalid_access', 'filechange');
            $segment3_array = array('login', 'logout', 'ssoauth');
            
            $interval       = (int) config('enconfig.checksum_interval') * 60; // convert mins to seconds
            $currenttime    = time();        
            $checksumtime   =  Session::get('EMCHECKSUMTIME');

            if ($currenttime >= $checksumtime)
            {
                $data = $this->checksum();

                if (is_array($data) && count($data) > 0)
                {
                    if ($data['is_error'] == 1)
                    {
                        $url_segment_one =  Request::segment(1);
                        $url_segment_two =  Request::segment(2);

                        if (!in_array($url_segment_one, $segment2_array) && !in_array($url_segment_two, $segment3_array))
                        {
                            $headers            = apache_request_headers();
                            $nextchecksumtime   = time();
                            
                            Session::put('EMCHECKSUMTIME',$nextchecksumtime);

                            if ($headers['Content-Length'] > 0 || isset($_SERVER['HTTP_X_REQUESTED_WITH']))
                            {
                                //echo 'FileDBChange';
                                return 'FileDBChange';
                            }
                            else
                            {
                               //redirect(site_url()."/manage/filechange/");
                              // dd('Redirect to file change');
                               return 'FileChange';

                            }
                        }
                        else
                        {
                            return true;
                        }
                    }
                    else
                    {
                        $nextchecksumtime = time() + $interval;
                        Session::put('EMCHECKSUMTIME',$nextchecksumtime);
                        return true;
                    }
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return true;
            }

        }
        catch(\Exception $e)
        {
            //$e->getMessage()
            return false;
        }
        catch(\Error $e)
        {
            //$e->getMessage()
            return false;
        }
    }*/
        public function checksumfilechange()
    {

        try
        {

            $segment2_array = ['set_timer', 'invalid_access', 'filechange'];
            $segment3_array = ['login', 'logout', 'ssoauth'];
            
            $interval       = (int) 60;//config('enconfig.checksum_interval') * 60; // convert mins to seconds

            $currenttime    = time();        
            $checksumtime   =  Session::get('EMCHECKSUMTIME');
            apilog("checksumtime------------------".$checksumtime);
            apilog("curent_time---".$currenttime);
            apilog($checksumtime - $currenttime);
            if ($currenttime >= $checksumtime)
            {
                $data = $this->checksum();
                apilog(json_encode($data));
                if (is_array($data) && count($data) > 0)
                {
                    if ($data['is_error'] == 1)
                    {
                        $url_segment_one =  request()->segment(1) !== null ? request()->segment(1) : "";//Request::segment(1);
                        $url_segment_two =  request()->segment(2)  !== null ? request()->segment(2) : ""; //Request::segment(2);
                        apilog("+++++++++in req - url_segment_one=====$url_segment_one====url_segment_two - ==$url_segment_two====");

                        if (!in_array($url_segment_one, $segment2_array) && !in_array($url_segment_two, $segment3_array))
                        {
                            apilog($url_segment_one);
                            apilog($url_segment_two);
                            $headers            = $_SERVER; //apache_request_headers();
                            //apilog(json_encode($headers));
                            $nextchecksumtime   = time();
                            
                            Session::put('EMCHECKSUMTIME',$nextchecksumtime);
                            //if ($headers['Content-Length'] > 0 || isset($_SERVER['HTTP_X_REQUESTED_WITH']))
                            if ($headers['CONTENT_LENGTH'] > 0 || isset($_SERVER['HTTP_X_REQUESTED_WITH']))
                            {
                                apilog("FileDBChange") ;
                                //echo 'FileDBChange';
                                return 'FileDBChange';
                            }
                            else
                            {
                                apilog("FileChange") ;
                               /*redirect(site_url()."/manage/filechange/");
                               dd('Redirect to file change');*/
                               return 'FileChange';

                            }

                        }
                        else
                        {
                            apilog("TRUE ") ;
                            return true;
                        }
                    }
                    else
                    {
                        $nextchecksumtime = time() + $interval;
                        Session::put('EMCHECKSUMTIME',$nextchecksumtime);
                        return true;
                    }
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return true;
            }

        }
        catch(\Exception $e)
        {
            //$e->getMessage()
            return false;
        }
        catch(\Error $e)
        {
            //$e->getMessage()
            return false;
        }
    }

}
