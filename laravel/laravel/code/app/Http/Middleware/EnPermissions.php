<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Session;


class EnPermissions 
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function __construct()
    {

    }

    public function handle($request, Closure $next)
    {

        $url =  $request->segment(1);
        if ($url=="js" || $url=="viewprofile" || $url=="language") 
        {
            return $next($request);
        }
        if (Session::has('accessrights') && !Session::has('issuperadmin')) 
        {
            $accessrights = Session::get('accessrights');
            $permission   =  strtoupper($request->segment(1));
            $accessright  =  $request->segment(2);

            //---TODO : for PO route, need to check-----
            if($permission == 'PURCHASEORDERS') $permission = 'PURCHASEORDER';
            //-----------------------------------------

            //for url with UUID at second segment
            $UUIDv4     = '/[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}/';
            if(preg_match($UUIDv4, $accessright) || $accessright == "0") $accessright = "";


            if (isset($accessrights['ITAM'][$permission]) && $accessrights['ITAM'][$permission] !="") 
            {
                $access_rights = json_decode($accessrights['ITAM'][$permission], TRUE);
                if(count($access_rights) > 0 && in_array('r', $access_rights))
                {
                    if ($accessright !="")
                    {
                        if ($accessright == "list" ||  $accessright == "mainlist" ||  $accessright == "details") 
                        {
                            if(in_array('r', $access_rights))
                            {
                               return $next($request);                   
                            }
                            else
                            {
                                return new response(abort(403));
                            }
                        }
                        elseif($accessright == "add" || $accessright == "addsubmit" ||  $accessright == "save") 
                        {
                            if(in_array('c', $access_rights))
                            {
                               return $next($request);                    
                            }
                            else
                            {
                                return new response(abort(403));
                            }
                        }
                        elseif($accessright == "edit" || $accessright == "editsubmit" || $accessright == "update") 
                        {
                            if(in_array('u', $access_rights))
                            {
                               return $next($request);                   
                            }
                            else
                            {
                               return new response(abort(403));
                            }   
                        }
                        elseif($accessright=="delete") 
                        {
                            if(in_array('d', $access_rights))
                            {
                                return $next($request);                   
                            }
                            else
                            {
                               return new response(abort(403));
                            }    
                        }
                        else
                        {
                            return new response(abort(403));
                        }
                    }
                    else
                    {
                       return $next($request);
                    }
                }
                else
                {
                    if (count($access_rights) > 0 && in_array('a', $access_rights ))
                    {
                        return $next($request);    
                    }
                    else
                    {
                       return new response(abort(403));
                    }
                }
            }
            else
            {
                // //for advanced permissions, which has 1 permission key for multiple paths
                // if(isset($permission) && $permission != ''){
                //     $access_rights = array();
                //     switch ($permission) {
                //        case 'CONTRACTRENEW':
                //        case 'CONTRACTRENEWSUBMIT':
                //        case 'RENEWDETAILS':
                //             if(isset($accessrights['ITAM']['RENEW_CONTRACT'])) $access_rights = json_decode($accessrights['ITAM']['RENEW_CONTRACT'], TRUE);
                //             break;
                //        case 'CONTRACTUPDATEASSOCIATECHILD':
                //        case 'CHILDCONTRACT':
                //        case 'ASSOCIATECHILDCONTRACT':
                //             if(isset($accessrights['ITAM']['ASSOCIATE_CHILD_CONTRACT'])) $access_rights = json_decode($accessrights['ITAM']['ASSOCIATE_CHILD_CONTRACT'], TRUE);
                //             break;
                //        case 'NOTIFY_OWNER_CONTRACT':
                //             if(isset($accessrights['ITAM']['NOTIFY_OWNER_EMAIL'])) $access_rights = json_decode($accessrights['ITAM']['NOTIFY_OWNER_EMAIL'], TRUE);
                //             break;
                //        case 'NOTIFY_VENDOR_CONTRACT':
                //             if(isset($accessrights['ITAM']['NOTIFY_VENDOR_EMAIL'])) $access_rights = json_decode($accessrights['ITAM']['NOTIFY_VENDOR_EMAIL'], TRUE);
                //             break;
                //        case 'CONTRACTHISTORYLOG':
                //        case 'GETSWHISTORY':
                //             if(isset($accessrights['ITAM']['VIEW_HISTORY'])) $access_rights = json_decode($accessrights['ITAM']['VIEW_HISTORY'], TRUE);
                //             break;
                              
                //        case 'EMAILQUOTEADD':
                //        case 'EMAILTEMPLATESTATUSUPDATE':
                //        case 'EMAILQUOTES':
                //        case 'EMAILTEMPLATECATEGORY':
                //        case 'EMAILQUOTEADDSUBMIT':
                //        case 'EMAILTEMPLATECHANGESTATUS':
                //             if(isset($accessrights['ITAM']['EMAILTEMPLATE'])) $access_rights = json_decode($accessrights['ITAM']['EMAILTEMPLATE'], TRUE);
                //             if(count($access_rights) > 0 && in_array('u', $access_rights))
                //             {
                //                return $next($request);
                //             }
                //             break;

                //        case 'SWADDASSET':
                //        case 'GETCITEMPIDSOFTWARE':
                //        case 'SWADDLISENSE':
                //        case 'SWADDLICENSESUBMIT':
                //        case 'SOFTWARELICENSEEDIT':
                //        case 'SOFTWARELICENSEEDITSUBMIT':
                //        case 'SOFTWARELICENSELLOCATE':
                //        case 'SWALLOCATEASSETREMOVE':
                //        case 'SWDEALLOCATEUNINSTALL':
                //        case 'GETCITEMPIDSW':
                //        case 'SWATTACHASSETSAVE':
                //        case 'SWASSETREMOVE':
                //        case 'GETSWINSTALLATION':
                //             if(isset($accessrights['ITAM']['SOFTWARE'])) $access_rights = json_decode($accessrights['ITAM']['SOFTWARE'], TRUE);
                //             if(count($access_rights) > 0 && in_array('u', $access_rights))
                //             {
                //                return $next($request);
                //             }
                //             break;
                //        case 'SOFTWARELISTDETAILS':
                //        case 'ASSETWITHSTATUS':
                //             if(isset($accessrights['ITAM']['SOFTWARE'])) $access_rights = json_decode($accessrights['ITAM']['SOFTWARE'], TRUE);
                //             if(count($access_rights) > 0 && in_array('r', $access_rights))
                //             {
                //                return $next($request);
                //             }
                //             break;
                            
                //        case 'ADDATTRIBUTES':
                //        case 'EDITATTRIBUTE':
                //        case 'UPDATEATTRIBUTE':
                //             if(isset($accessrights['ITAM']['CITEMPLATES'])) $access_rights = json_decode($accessrights['ITAM']['CITEMPLATES'], TRUE);
                //             if(count($access_rights) > 0 && in_array('r', $access_rights))
                //             {
                //                return $next($request);
                //             }
                //             break;

                //        case 'ASSETS':
                //        case 'ASSETTREE':
                //        case 'ASSETDASHBOARDPARENT':
                //        case 'ASSETDASHBOARD':
                //        case 'ASSETWITHSTATUS':
                //        case 'ASSETCONTRACT':
                //        case 'ASSETSOFCITYPE':
                //             if(isset($accessrights['ITAM']['ASSET'])) $access_rights = json_decode($accessrights['ITAM']['ASSET'], TRUE);
                //             if(count($access_rights) > 0 && in_array('r', $access_rights))
                //             {
                //                return $next($request);
                //             }
                //             break;
                //        case 'STATUSCHANGE':
                //        case 'STATUSCHANGESUBMIT':
                //             if(isset($accessrights['ITAM']['ASSET'])) $access_rights = json_decode($accessrights['ITAM']['ASSET'], TRUE);
                //             if(count($access_rights) > 0 && in_array('u', $access_rights))
                //             {
                //                return $next($request);
                //             }
                //             break;
                //        case 'IMPORTFILE':
                //        case 'IMPORTSAVE':
                //             if(isset($accessrights['ITAM']['ASSET_IMPORT'])) $access_rights = json_decode($accessrights['ITAM']['ASSET_IMPORT'], TRUE);
                //             break;
                //     }

                //     if (count($access_rights) > 0 && in_array('a', $access_rights ))
                //     {
                //         return $next($request);    
                //     }
                // }

                return new response(abort(403));
            }  
        }
        else
        {
            if (Session::has('issuperadmin')) 
            {
                return $next($request);
            }
            else
            {
                return new response(abort(403));
            }

        }
        return $next($request);  
    }
}
