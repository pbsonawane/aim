<?php
namespace App\Services;

use App\Services\IAM\IamAppService;
use Lang;
use App;

class DomainAccessService
{
    public function __construct()
    {
        $this->iamapi = new IamAppService();
    }


    public function setdomainacceess($redirect_url=null)
    {
        $access_token = (string)getSessionItem('access_token');
        $domainkey = (string)getSessionItem('domainkey');

        $msg = 'Token not found';

        if($access_token != '')
        {
            $form_params['domainkey'] = $domainkey;
            $form_params['access_token'] = $access_token;
            $options = [
                'form_params' => $form_params];
            $resp = $this->iamapi->verifysesstoken($options);
            if($resp['is_error'])
            {
                $is_error = $resp['is_error'];
                $msg = $resp['msg'];
            }
            else
            {

                if(_isset($resp,'content',false))
                {   
                    $sessiondata = $this->iamapi->sessdetails($options);

                    if($sessiondata['is_error'])
                    {
                        $is_error = $resp['is_error'];
                        $msg = $resp['msg'];
                    }
                    else
                    {
                        // set session
                        $acksession = $this->iamapi->setsessionack($options);
                        if($acksession['is_error'])
                        {
                            $is_error = $resp['is_error'];
                            $msg = $resp['msg'];
                        }
                        else
                        {
                            $content  = _isset($sessiondata,'content');
                            $content  = json_to_array($content);
                            apilog('--------------- content ----------------');
                            apilog(json_encode($content));
                            $user_id  = _isset($content,'user_id');
                            $token    = _isset($content,'token');
                            $username = _isset($content,'username');
                            $displayname = _isset($content,'displayname');
                            $displayname = trim($displayname) == "" ? "XXX" : $displayname;
                            $masteradmin = _isset($content,'issuperadmin');
                            $userfullname= _isset($content,'userfullname');
                            
                            $issuperadmin = false;
                            if ($masteradmin == 'y')
                            {
                                $issuperadmin = true;
                            }
                            $role_id          = _isset($content,'role_id');
                            $accessrights     = _isset($content,'accessrights');
                            $locale           = _isset($content,'content-language');
                             $locale  = isset($locale) ? $locale : config('app.locale');
                            if( $user_id != '' && $username != '' && $displayname != '' && $token != '')
                            {
                                $data = array("displayname" => $displayname,"user_id" => $user_id,"username" => $username,"token" => $token,"issuperadmin"=>$issuperadmin,"userfullname"=>$userfullname);
                                //save token
                                if ($role_id !="") 
                                {
                                    $data['role_id'] = $role_id;
                                }
                                if ($accessrights !="") 
                                {
                                    $data['accessrights'] = $accessrights;
                                }
                                if ($locale !="") 
                                {
                                    $data['locale'] = $locale;
                                }
                                saveSession($data);
                                App::setLocale($locale);
                               return true;
                            }
                            else
                            {
                                $is_error = "error";
                                $msg = 'Invalid Token.';
                            }
                        }
                    }
                }
                else
                {
                    $is_error = "error";
                    $msg = 'Invalid Token.';
                }
            }
        }
        else
        {
            $is_error = "error";
            $msg = 'Token not found';
        }
        
        return false;
    }

}
