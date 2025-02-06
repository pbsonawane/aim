<?php

namespace App\Http\Controllers\Asset;
use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\EnAssets;
use Redirect;
use View;
use Validator;


class AssetController extends Controller
{
 public function __construct(IamService $iam, ItamService $itam, Request $request)
 {
   $this->itam = $itam;        
   $this->iam = $iam;
   $this->emlib = new Emlib;
   $this->request = $request;
   $this->request_params = $this->request->all();
}

    /*function assets()
    {
         $data['title'] = "";
         $data['ci_type_id'] = "";
        $data['ci_templ_id'] = "";
        $data['asset_id'] = "";
    	$data['pageTitle'] = trans('title.assets');//assets;
        $data['includeView'] = view("Asset/assets", $data);
        return view('template', $data);
    }*/
    /**
     * This Asset controller function is implemented to initiate a page to get list
     *  of Assets.
     * @author Amit Khairnar
     * @access public
     * @package Assets
     * @return string
     */
    function assets($asset_id = "",$ci_templ_id = "",$po_id = "")
    {	
		//echo 'sdsds'; exit;
        //ini_set('max_execution_time', '1000');
       // ini_set("memory_limit","-1");
        $topfilter = ['gridsearch' => true, 'jsfunction' => 'assetTracking()', 'gridadvsearch' => true];
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter, '', ['userlist']);
        $citemps = [];
        $defaultci = $this->itam->getallcitemplates([]);
        if($ci_templ_id != "")
        {   
            if ($defaultci['is_error'])
            {
                $citemps = [];
            }
            else
            {                  
                $defaultci = _isset($defaultci, 'content');
                if(is_array($defaultci) && count($defaultci) > 0)
                {
                    foreach($defaultci as $ci)
                    {
                        if($ci['ci_templ_id'] != "")
                        {   
                            $citemps[$ci['ci_templ_id']]['variable_name'] = $ci['variable_name'];
                            $citemps[$ci['ci_templ_id']]['ci_type_id'] = $ci['ci_type_id'];
                            $citemps[$ci['ci_templ_id']]['ci_name'] = $ci['ci_name'];
                        }
                    } 
                }
            }
        }
        if($ci_templ_id != "" && count($citemps) > 0)
        {
            $data['title'] = $citemps[$ci_templ_id]['ci_name'];
            $data['ci_type_id'] = $citemps[$ci_templ_id]['ci_type_id'];
            $data['ci_templ_id'] = $ci_templ_id;
            $data['asset_id'] = $asset_id;
            $data['po_id'] = $po_id;
        } 
        else
        {
            $data['title'] = $data['ci_type_id'] = $data['ci_templ_id'] = $data['asset_id'] = $data['po_id'] = "";
        } 
        
       // print_r($data);
        $data['pageTitle'] = trans('title.assets');//assets;
        $data['includeView'] = view("Asset/assets", $data);
        return view('template', $data);
    }

    // Asset Sku Issue
    function assetsSku($asset_id = "",$ci_templ_id = "",$po_id = "",$asset_sku="")
    {	
        $topfilter = ['gridsearch' => true, 'jsfunction' => 'assetTracking()', 'gridadvsearch' => true];
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter, '', ['userlist']);
        $citemps = [];
        $defaultci = $this->itam->getallcitemplates([]);
        if($ci_templ_id != "")
        {   
            if ($defaultci['is_error'])
            {
                $citemps = [];
            }
            else
            {                  
                $defaultci = _isset($defaultci, 'content');
                if(is_array($defaultci) && count($defaultci) > 0)
                {
                    foreach($defaultci as $ci)
                    {
                        if($ci['ci_templ_id'] != "")
                        {   
                            $citemps[$ci['ci_templ_id']]['variable_name'] = $ci['variable_name'];
                            $citemps[$ci['ci_templ_id']]['ci_type_id'] = $ci['ci_type_id'];
                            $citemps[$ci['ci_templ_id']]['ci_name'] = $ci['ci_name'];
                        }
                    } 
                }
            }
        }
        if($ci_templ_id != "" && count($citemps) > 0)
        {
            $data['title'] = $citemps[$ci_templ_id]['ci_name'];
            $data['ci_type_id'] = $citemps[$ci_templ_id]['ci_type_id'];
            $data['ci_templ_id'] = $ci_templ_id;
            $data['asset_id'] = $asset_id;
            $data['asset_sku'] = $asset_sku;
            $data['po_id'] = $po_id;
        } 
        else
        {
            $data['title'] = $data['ci_type_id'] = $data['ci_templ_id'] = $data['asset_id'] = $data['po_id'] = "";
        } 
        
       // print_r($data);
        $data['pageTitle'] = trans('title.assets');//assets;
        $data['includeView'] = view("Asset/assets", $data);
        return view('template', $data);
    }
    // 
    public function assetTracking(Request $request){

        $form_params['searchkeyword'] = $request->input('searchkeyword');
        $form_params['employee_id'] = $request->input('employee_id');
        if(!empty($form_params['searchkeyword'])){

            $options = ['form_params' => $form_params];
            $citemplates = $this->itam->assetTracking($options);
            $citemplates['tracking'] = 1;
            echo json_encode($citemplates);
            die();
        }elseif(!empty($form_params['employee_id'])){            

            $options = ['form_params' => $form_params];
            $citemplates = $this->itam->assetTrackingByEmpId($options);
            if(empty($citemplates['content']) && $citemplates['is_error'] == 1){
                $citemplates['tracking'] = 0;
            }else{
                $citemplates['tracking'] = 2;
                $view = View::make("Asset/assettrackingshow", $citemplates);
                $citemplates['html'] = $view->render();
                $citemplates['is_error'] = '';
                $citemplates['msg'] = '';
            }
            
            echo json_encode($citemplates);           
          //  die();
        }else{          
            echo json_encode(["content"=>null,"is_error"=>false,"msg"=>"","http_code"=>200]);
            die();
        }
        

    } 
    function getitembycategory(Request $request)
    {
        try
        {
            $is_error = $msg = "";
            $data = [];
            $form_params['ci_templ_id'] = $request->input('ci_templ_id');
            $options = ['form_params' => $form_params];
            $citemplates = $this->itam->getitembycategory($options);               
            $citemplates = _isset($citemplates,'content');      
            $sel_opt = '<option value="">-Select-</option>';
            if(!empty($citemplates)){
                foreach($citemplates as $value){
                    $sel_opt .='<option value="'.$value['pa_id'].'~'.$value['asset_unit'].'">'.$value['display_name'].' ('.$value['asset_sku'].')</option>';
                }
            }
            
            $response["html"] = $sel_opt;
            $response["msg"] = $msg;
            
        }
        catch (\Exception $e)
        {
            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();

            save_errlog("dashboard","This controller function is implemented to get Asset dashboard data.",$this->request_params,$e->getmessage());  
        }
        catch (\Error $e)
        {
            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            save_errlog("dashboard","This controller function is implemented to get Asset dashboard data.",$this->request_params,$e->getmessage());  
        }
        finally
        {
          echo json_encode($response);
      } 
  } 
  function treedata()
  {
    try
    {
        $is_error = false;
        $msg = '';
        $data = [];
        $option = [];
        $citemplates = $this->itam->getciitems($option);

        $view = View::make("Cmdb/citemplatedata", $data);
        $contents = $view->render();
        $response["html"] = $contents;
        $response["is_error"] = $is_error;
        $response["msg"] = $msg;
            $response["cidata"] = _isset(_isset($citemplates,'content'),'records'); //$data['content']['records'];
            

        }
        catch (\Exception $e)
        {
            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            $response["cidata"] = "";
            save_errlog("treedata","This controller function is implemented to get data of tree.",$this->request_params,$e->getmessage());  
        }
        catch (\Error $e)
        {
            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            $response["cidata"] = "";
            save_errlog("treedata","This controller function is implemented to get data of tree.",$this->request_params,$e->getmessage());  
        }
        finally
        {
          echo json_encode($response);
      } 
  }

  function dashboard()
  {
    try
    {
        $is_error = $msg = "";
        $data = [];
        $citemplates = $this->itam->assetdashboard([]);    
            // dd($citemplates);
        $data['citemplates'] = _isset($citemplates,'content');      
        $view = View::make("Asset/assetdashboard", $data);
        $contents = $view->render();
        $response["html"] = $contents;
        $response["is_error"] = $is_error;
        $response["msg"] = $msg;

    }
    catch (\Exception $e)
    {
        $response["html"] = "";
        $response["is_error"] = true;
        $response["msg"] = $e->getmessage();

        save_errlog("dashboard","This controller function is implemented to get Asset dashboard data.",$this->request_params,$e->getmessage());  
    }
    catch (\Error $e)
    {
        $response["html"] = "";
        $response["is_error"] = true;
        $response["msg"] = $e->getmessage();
        save_errlog("dashboard","This controller function is implemented to get Asset dashboard data.",$this->request_params,$e->getmessage());  
    }
    finally
    {
      echo json_encode($response);
  } 
}

//*****************************************************************
//@Author:- Harshal Mahajan
//@Module:-License Dashboard
//Model :- EnAssets
//*******************************************************************

function LicenseDashboard1()
{
         try
    {
        $is_error = $msg = "";
        $data = [];
        $citemplates = $this->itam->licensedashboard([]);    
//dd($citemplates);
        $data['citemplates'] = _isset($citemplates,'content');      
        $view = View::make("Asset/licensedashboard", $data);
        $contents = $view->render();
        $response["html"] = $contents;
        $response["is_error"] = $is_error;
        $response["msg"] = $msg;

    }
    catch (\Exception $e)
    {
        $response["html"] = "";
        $response["is_error"] = true;
        $response["msg"] = $e->getmessage();

        save_errlog("dashboard","This controller function is implemented to get License dashboard data.",$this->request_params,$e->getmessage());  
    }
    catch (\Error $e)
    {
        $response["html"] = "";
        $response["is_error"] = true;
        $response["msg"] = $e->getmessage();
        save_errlog("dashboard","This controller function is implemented to get Lic dashboard data.",$this->request_params,$e->getmessage());  
    }
    finally
    {
      echo json_encode($response);
  }
}



public function LicenseDashboard(Request $request)
    {
       
       return view('Asset/licensedashboard');
    }




//-------------------------------------------------------------------------

function asset(Request $request)
{
        //ini_set('max_execution_time', '1000');
        //ini_set("memory_limit","-1");
    try
    {
        $topfilter = ['gridsearch' => true, 'jsfunction' => 'assetslist()', 'gridadvsearch' => false];
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter, '', '');
        $is_error = $msg = "";
        $data['ci_templ_id'] = _isset($this->request_params, 'ci_templ_id');
        $data['ci_type_id'] = _isset($this->request_params, 'ci_type_id');
        $data['title'] = _isset($this->request_params, 'title');
        $data['po_id'] = _isset($this->request_params, 'po_id');

        $view = View::make("Asset/asset", $data);
        $contents = $view->render();
        $response["html"] = $contents;
        $response["is_error"] = $is_error;
        $response["msg"] = $msg;
            //echo json_encode($response);
    }
    catch (\Exception $e)
    {
        $response["html"] = "";
        $response["is_error"] = true;
        $response["msg"] = $e->getmessage();

        save_errlog("asset","This controller function is implemented to get Asset.",$this->request_params,$e->getmessage());  
    }
    catch (\Error $e)
    {
        $response["html"] = "";
        $response["is_error"] = true;
        $response["msg"] = $e->getmessage();
        save_errlog("asset","This controller function is implemented to get Asset.",$this->request_params,$e->getmessage());  
    }
    finally
    {
        echo json_encode($response);
    } 
}

function assetlist(Request $request)
{ 
    try
    {  
        // /print_r(config('enconfig'));
        $paging = [];
        $fromtime = $totime = '';
        $limit = _isset($this->request_params, 'limit', config('enconfig.def_limit_short'));
        $page = _isset($this->request_params, 'page', config('enconfig.page'));
        $searchkeyword = _isset($this->request_params, 'searchkeyword');
        $po_id = _isset($this->request_params, 'po_id');
        $asset_sku = _isset($this->request_params, 'asset_sku');

        $is_error = false;
        $msg = '';
        $content = "";
        $limit_offset = limitoffset($limit, $page);
        $page = $limit_offset['page'];
        $limit = $limit_offset['limit'];
        $offset = $limit_offset['offset'];
        $form_params['ci_templ_id'] = $request->input('ci_templ_id');
        $form_params['po_id'] = $po_id;
        $form_params['asset_sku'] = $asset_sku;

        $form_params['limit'] = $paging['limit'] = $limit;
        $form_params['searchkeyword'] = $searchkeyword;
        $form_params['page'] = $paging['page'] = $page;
        $form_params['offset'] = $paging['offset'] = $offset;
        $options = [
            'form_params' => $form_params];
            
            // print_r($options);
            $assetlist = $this->itam->assets($options);
            
            if ($assetlist['is_error'])
            {
                $is_error = $assetlist['is_error'];
                $msg = $assetlist['msg'];
            }
            else
            {  

                $assetlits = _isset(_isset($assetlist, 'content'), 'records');
                $paging['total_rows'] = _isset(_isset($assetlist, 'content'), 'totalrecords');
                $paging['showpagination'] = true;
                $paging['jsfunction'] = 'assetslist()';
                $view = 'Asset/assetlist';
                
                $show_fields = [];         
                $columns = $show_fields;
                $content = $this->emlib->emgrid($assetlits, $view, $columns, $paging);
            }
            $response["html"] = $content;
            $response["is_error"] = $is_error;
            $response["msg"] = $msg;
           // echo json_encode($response);
        }
        catch (\Exception $e)
        {
            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();

            save_errlog("assetlist","This controller function is implemented to get Assetlist.",$this->request_params,$e->getmessage());  
        }
        catch (\Error $e)
        {
            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            save_errlog("assetlist","This controller function is implemented to get Assetlist.",$this->request_params,$e->getmessage());  
        }
        finally
        {
            echo json_encode($response);
        } 
    }

    function assetadd(Request $request)
    {
        try
        { 

            $inputdata = $request->all();
            $type = _isset($inputdata,'type') ? $request->input('type') : "";
            $limit_offset = limitoffset(0, 0);
            $page = $limit_offset['page'];
            $limit = $limit_offset['limit'];
            $offset = $limit_offset['offset'];
            $form_params['limit'] = $paging['limit'] = $limit;
            $form_params['page'] = $paging['page'] = $page;
            $form_params['offset'] = $paging['offset'] = $offset;



            $options = [
                'form_params' => $form_params];

                $bvdata = $this->iam->getBusinessVertical($options);
                $locdata = $this->iam->getLocations($options);
                $vendordata = $this->itam->getvendors($options);
                $form_params = [];
                $form_params['ci_templ_id'] = $request->input('ci_templ_id');
                $form_params['ci_type_id'] = $request->input('ci_type_id');
                $options = [
                    'form_params' => $form_params];
                    $assetdata = $this->itam->getcitemplate($options);
                    $data['assetdata'] = _isset(_isset($assetdata,'content'),'records');


                    $skucodes = $this->itam->skucodename(['form_params'=>
                        ['prefix' => $data['assetdata']['prefix']]]);

                    $data['assets'] = _isset(_isset($assetdata,'content'),'assets');
                    $data['locdata'] = _isset(_isset($locdata,'content'),'records');
                    $data['bvdata'] = _isset(_isset($bvdata,'content'),'records');
                    $data['vendordata'] = _isset(_isset($vendordata,'content'),'records');
                    $data['editdata'] =  $data['asset_details'] = $data['childdata'] =  [];
                    $data['asset_id'] = ""; 

                    $data["skucodes"] = _isset($skucodes,'content');
    		//echo "<pre>";	print_r($data); die();
                    if($type == 'data')
                    {
                        return $data;
                    }
                    $html = view("Asset/assetadd",$data);
                    echo  $html;
                }
                catch (\Exception $e)
                {
                    $html = "";

                    save_errlog("assetlist","This controller function is implemented to get Asset add.",$this->request_params,$e->getmessage());  
                    echo  $html;
                }
                catch (\Error $e)
                {
                    $html = "";
                    save_errlog("assetlist","This controller function is implemented to get Asset add.",$this->request_params,$e->getmessage());  
                    echo  $html;
                }



            }

            function assetsave(Request $request)
            {
             try
             { 
                 $assetdata = $request->all();
           //print_r($assetdata); exit;
                 if(is_array($assetdata) && count($assetdata) > 0)
                 {
                    foreach($assetdata as $key => $adata)
                    {
                        if(is_array($adata) && count($adata) > 0)
                        {
                            foreach($adata as $k => $data)
                                $farray[$key][$k] =_isset($adata, $k) ? $adata[$k] : '';
                        }    
                        else
                            $farray[$key] =_isset($this->request_params, $key) ? $request->input($key): '';
                    }
                }

                $data = $this->itam->addasset(['form_params' => $farray]);
            //$data["content"]   = "";
            //$data["is_error"]  = "";
            //$data["msg"]       = $e->getmessage();
            //$data["http_code"] = "";
                echo json_encode($data, true);
            }
            catch (\Exception $e)
            {
                $data = [];
                save_errlog("assetsave","This controller function is implemented to get Asset add.",$this->request_params,$e->getmessage());  
                echo json_encode($data, true);
            }
            catch (\Error $e)
            {
               $data = [];
               save_errlog("assetsave","This controller function is implemented to get Asset add.",$this->request_params,$e->getmessage());  
               echo json_encode($data, true);
           }

       }

       function assetedit(Request $request)
       {
         try
         { 
            $option=[];
            $callfrom = $request->input('callfrom');    
            $returntype = $request->input('given');    
            $limit_offset = limitoffset(0, 0);
            $page = $limit_offset['page'];
            $limit = $limit_offset['limit'];
            $offset = $limit_offset['offset'];
            $form_params['limit'] = $paging['limit'] = $limit;
            $form_params['page'] = $paging['page'] = $page;
            $form_params['offset'] = $paging['offset'] = $offset;

            

            $options = ['form_params' => $form_params];
            
            $bvdata = $this->iam->getBusinessVertical($options);
            $locdata = $this->iam->getLocations($options);
            
            if($callfrom == 'assetdashboard'){
                $vendordata = $this->itam->getvendors_withoutpermission($options);
            }else{
                $vendordata = $this->itam->getvendors($options);
            }

            $form_params = [];
            $form_params['asset_id'] = $request->input('asset_id');
            $form_params['ci_templ_id'] = $request->input('ci_templ_id');
            $form_params['ci_type_id'] = $request->input('ci_type_id');
            $options = ['form_params' => $form_params];
          // echo "<pre>"; print_r($options); 
            
            if($callfrom == 'assetdashboard'){
                $editdata = $this->itam->editasset_withoutpermission($options);
            }else{
                $editdata = $this->itam->editasset($options);
            }
            
            $data['editdata'] = _isset(_isset($editdata,'content'),'records'); 
           // echo "<pre>"; print_r($data); die();
            $assetdata = $this->itam->getcitemplate($options);    
            $data['assetdata'] = _isset(_isset($assetdata,'content'),'records');
            

            $skucodes = $this->itam->skucodename(['form_params'=>
                ['prefix' => $data['assetdata']['prefix']]]);     
            $data['asset_id'] = $form_params['asset_id'];
            $data['assetdata'] = _isset(_isset($assetdata,'content'),'records');
            $data['assets'] = _isset(_isset($assetdata,'content'),'assets');
            
         //  echo "<pre>"; print_r($data); die();
            $childdata = [];
            $childs = _isset(_isset($editdata,'content'),'childs');
            //print_r($editdata);die();    
            if(is_array($childs) && count($childs) > 0)
            {
                foreach($childs as $child)
                {
                    if($child['asset_details'] != "")
                        $child['asset_detailsarray'] = json_decode($child['asset_details'],true);
                    $childdata[$child['ci_templ_id']][] = $child;
                }
            }
            $data['childdata'] = $childdata;
            if($data['editdata'][0]['asset_details'] != "")
                $data['asset_details'] = json_decode($data['editdata'][0]['asset_details'],true);
            $data['locdata'] = _isset(_isset($locdata,'content'),'records');
            $data['bvdata'] = _isset(_isset($bvdata,'content'),'records');
            $data['vendordata'] = _isset(_isset($vendordata,'content'),'records');
            $data["skucodes"] = _isset($skucodes,'content');
            //$data['assets'] = array();
            
            $html = view("Asset/assetadd",$data);
           // echo  $html;
        }
        catch (\Exception $e)
        {
            $data = [];
            $html = "";

            save_errlog("assetedit","This controller function is implemented to edit asset.",$this->request_params,$e->getmessage());  
        }
        catch (\Error $e)
        {
           $data = [];
           $html = "";
           save_errlog("assetedit","This controller function is implemented to edit asset.",$this->request_params,$e->getmessage());  
       }
       finally
       {
        if($returntype == "info")
            return $data;
        else
            echo $html;
    } 
}

function updateasset(Request $request) 
{
    try
    {
     $assetdata = $request->all();
           //print_r($assetdata); exit;
     if(is_array($assetdata) && count($assetdata) > 0)
     {
        foreach($assetdata as $key => $adata)
        {
            if(is_array($adata) && count($adata) > 0)
            {
                foreach($adata as $k => $data)
                    $farray[$key][$k] =_isset($adata, $k) ? $adata[$k] : '';
            }    
            else
                $farray[$key] =_isset($this->request_params, $key) ? $request->input($key): '';
        }
    }
           //print_r($farray); exit;
    $data = $this->itam->updateasset(['form_params' => $farray]);

}
catch (\Exception $e)
{
    $data = "";

    save_errlog("updateasset","This controller function is implemented to update asset.",$this->request_params,$e->getmessage());  
}
catch (\Error $e)
{
   $data = "";
   save_errlog("updateasset","This controller function is implemented to update asset.",$this->request_params,$e->getmessage());  
}
finally
{
   echo json_encode($data, true);
} 
}

function deleteasset(Request $request)
{
    $form_params['asset_id'] = $request->input('asset_id');
    $options = [
     'form_params' => $form_params];
     $data = $this->itam->assetdelete($options);  
     echo json_encode($data, true);        
 }


 function assetdashboard(Request $request)
 {
    $request->request->add(['callfrom' => 'assetdashboard']);
    $data = $this->assetedit($request);
    $is_error = $msg = "";
    $user_details = [];
    $limit_offset = limitoffset(0, 0);
    $page = $limit_offset['page'];
    $limit = $limit_offset['limit'];
    $offset = $limit_offset['offset'];
    $form_params['limit'] = $limit;
    $form_params['page'] = $page;
    $form_params['offset']  = $offset;
    $options = [
        'form_params' => $form_params];


        $deptdata = $this->iam->getDepartment($options);
        $data['dept'] = _isset(_isset($deptdata,'content'),'records');
        $defaultci = $this->itam->getallcitemplates([]);
        if ($defaultci['is_error'])
        {
            $citemps = [];
        }
        else
        {                  
            $defaultci = _isset($defaultci, 'content');
            if(is_array($defaultci) && count($defaultci) > 0)
            {
                foreach($defaultci as $ci)
                {
                    if($ci['ci_templ_id'] != "")
                        $citemps[$ci['ci_templ_id']] = $ci['variable_name'];
                }  
            }
        }
        $data['citemps'] = $citemps;
        $data['ci_templ_id'] = _isset($this->request_params, 'ci_templ_id');
        $data['asset_id'] = _isset($this->request_params, 'asset_id');
        $data['ci_type_id'] = _isset($this->request_params, 'ci_type_id');
        $data['title'] = _isset($this->request_params, 'title');
        $options = ['form_params' => ['asset_id' => $request->input('asset_id')]];
        $historydata = $this->itam->assignassethistory($options); 
        $historydata = _isset(_isset($historydata,'content'),'records');
        $data['historydata'] = isset($historydata[0])?$historydata[0]:[];
        $view =  enview("Asset/singledashboard", $data);
        $response["html"] = $view;
        $response["is_error"] = $is_error;
        $response["msg"] = $msg;
        echo json_encode($response);
    }

    /**
    * This controller function is used to delete assetrelationship data from database.
    * @author Darshan Chaure
    * @access public
    * @package assetrelationship
    * @param UUID $asset_relationship_id assetrelationship Unique Id
    * @return json
    */
    function assetrelationshipdelete(Request $request)
    {
        try{
            $data = $this->itam->deleteassetrelationship(['form_params' => $request->all()]);
            echo json_encode($data, true);
        }
        catch (\Exception $e)
        {
            $data["html"]       = '';
            $data["is_error"]   = true;
            $data["msg"]        = $e->getmessage();

            save_errlog("assetrelationshipdelete","This controller function is implemented to delete asset relationship.",$this->request_params,$e->getmessage());  
            echo json_encode($data, true);
        }
        catch (\Error $e)
        {
            $data["html"]       = '';
            $data["is_error"]   = true;
            $data["msg"]        = $e->getmessage();

            save_errlog("assetrelationshipdelete","This controller function is implemented to delete asset relationship.",$this->request_params,$e->getmessage());  
            echo json_encode($data, true);
        }
    }
    /**
    * This controller function is used to add assetrelationship data.
    * @author Darshan Chaure
    * @access public
    * @package assetrelationship
    * @param asset_id
    * @return string
    */
    function assetrelationshipadd(Request $request)
    {
        $form_params['searchkeyword'] = '';
        $form_params['callfor']       = 'assetrelationshipadd';
        $form_params['callfor_id']    = $request->input('asset_id');
        $options                      = ['form_params' => $form_params];
        //$assetlist                    = $this->itam->assets($options);
        $option = [];
        $citemplates = $this->itam->getciitems($option);
        $data['citemplates'] = _isset(_isset($citemplates,'content'),'records');
        $assetlists                   = [];
        $reltypelist                  = $this->itam->getrelationshiptype($options);
        $reltypelists                 = [];

       /* if (!$assetlist['is_error'])
        {
            $assetlists = _isset(_isset($assetlist, 'content'), 'records');
        }*/
        if (!$reltypelist['is_error'])
        {

            $reltypelists = _isset(_isset($reltypelist, 'content'), 'records');
        }

        $asset_id             = $request->input('asset_id');
        $data['asset_id']     = $asset_id;
       // $data['assetlists']   = $assetlists;
        $data['reltypelists'] = $reltypelists;
        $html                 = view("Cmdb/assetrelationshipadd", $data);
        echo $html;
    }

   /**
    * This controller function is used to save assetrelationship data.
    * @author Darshan Chaure
    * @access public
    * @package assetrelationship
    * @param asset_id relationship_type_id child_asset_id
    * @return string
    */
   function assetrelationshipsave(Request $request)
   {
    try
    {
        $data = $this->itam->addassetrelationship(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }
    catch (\Exception $e)
    {
        $data["html"]       = '';
        $data["is_error"]   = true;
        $data["msg"]        = $e->getmessage();

        save_errlog("assetrelationshipsave","This controller function is implemented to save asset relationship.",$this->request_params,$e->getmessage());  
        echo json_encode($data, true);
    }
    catch (\Error $e)
    {
        $data["html"]       = '';
        $data["is_error"]   = true;
        $data["msg"]        = $e->getmessage();

        save_errlog("assetrelationshipsave","This controller function is implemented to save asset relationship.",$this->request_params,$e->getmessage());  
        echo json_encode($data, true);
    }
}

    /**
    * This controller function is used to delete assetrelationship data from database.
    * @author Amit Khainar
    * @access public
    * @package attachasset
    * @param UUID $bv_id $location_id Unique Id
    * @return json
    */   
    function attachasset(Request $request)
    {
        $data['asset_id'] = $request->input('asset_id');
        $data['bv_id'] = $request->input('bv_id');
        $data['location_id'] = $request->input('location_id');
        $data['tag'] = $request->input('tag');
        $data['asset_ci_templ_id'] = $request->input('asset_ci_templ_id');
        $option = [];
        $citypes = $this->itam->citypes($option);
        $citemplates = $this->itam->getciitems($option);
        $data['citemplates'] = _isset(_isset($citemplates,'content'),'records');
        $data["citypes"] = _isset(_isset($citypes,'content'),'records'); 
        $html = view("Asset/assetattach",$data);
        echo $html;
    }
    /**
    * This controller function is used to delete assetrelationship data from database.
    * @author Amit Khainar
    * @access public
    * @package assetwithstatus
    * @param UUID $bv_id $location_id Unique Id
    * @return json
    */   
    function assetwithstatus(Request $request)
    {
        $data['ci_templ_id'] = $request->input('ci_templ_id');
        $data['bv_id'] = $request->input('bv_id');    
        $data['location_id'] = $request->input('location_id'); 
        $data['asset_status'] = $request->input('asset_status');    
        $limit_offset = limitoffset(0, 0);
        $page = $limit_offset['page'];
        $limit = $limit_offset['limit'];
        $offset = $limit_offset['offset'];
        $form_params['ci_templ_id'] = $request->input('ci_templ_id');
        $form_params['limit'] = $paging['limit'] = $limit;
        $form_params['page'] = $paging['page'] = $page;
        $form_params['asset_status'] = $data['asset_status'];
        $form_params['offset'] = $paging['offset'] = $offset;
        $form_params['bv_id'] = $data['bv_id'];
        $form_params['location_id'] = $data['location_id'];
        $options = [
            'form_params' => $form_params];
            
            $assetlist = $this->itam->assets($options);

            if ($assetlist['is_error'])
            {
                $is_error = $assetlist['is_error'];
                $msg = $assetlist['msg'];
            }
            else
            {  
                $assetlits = _isset(_isset($assetlist, 'content'), 'records');
            }
            return $assetlits;

        }

        function attachassetsave(Request $request)
        {
            $assetdata = $request->all();
            $data = $this->itam->attachassetsave(['form_params' => $assetdata]);
            echo json_encode($data, true);
        }

    /**
    * This controller function is used to get the assets of specific ci type from database.
    * @author Snehal C
    * @access public
    * @package assetsofcitype
    * @param $ci_type_id
    * @return json
    */   
    function assetsofcitype(Request $request){

        $data['ci_templ_id'] = $request->input('ci_templ_id');
        $data1= "";
        $limit_offset = limitoffset(0, 0);
        $page = $limit_offset['page'];
        $limit = $limit_offset['limit'];
        $offset = $limit_offset['offset'];
        $form_params['ci_templ_id'] = $request->input('ci_templ_id');
        $form_params['limit'] = $paging['limit'] = $limit;
        $form_params['page'] = $paging['page'] = $page;
        $form_params['offset'] = $paging['offset'] = $offset;
        $options = [
            'form_params' => $form_params];


            if($request->input('ci_templ_id') == 'USER'){

                $option1 = [];
                $userlist = $this->iam->getUsers($option1);
                if ($userlist['is_error'])
                {
                    $is_error = $userlist['is_error'];
                    $msg = $userlist['msg'];
                }
                else
                {  
                    $userlist = _isset(_isset($userlist, 'content'), 'records');
                    $data1 = '<div class="form-group required ">
                    <label for="child_asset_id" class="col-md-3 control-label">'.trans('label.lbl_selectuser').'</label>
                    <div class="col-md-8">
                    <select class="chosen-select form-control input-sm" name="child_asset_id" id="child_asset_id">
                    <option value="">['.trans('label.lbl_selectuser').']</option>';
                    if(is_array($userlist) && count($userlist) > 0){
                        foreach ($userlist as $key => $value){  

                          $data1 = $data1."<option value='".$value['user_id']."'>".$value['firstname']." ".$value['lastname']."</option>";
                      }
                  } 
              }


          }else{

            $assetlist = $this->itam->assets($options);
            if ($assetlist['is_error'])
            {
                $is_error = $assetlist['is_error'];
                $msg = $assetlist['msg'];
            }
            else
            {  
                $assetlist_new = _isset(_isset($assetlist, 'content'), 'records');

                $data1 = '<div class="form-group required ">
                <label for="child_asset_id" class="col-md-3 control-label">'.trans('label.lbl_selectasset').'</label>
                <div class="col-md-8">
                <select class="chosen-select form-control input-sm" name="child_asset_id" id="child_asset_id">
                <option value="">['.trans('label.lbl_selectasset').']</option>';
                if(is_array($assetlist_new) && count($assetlist_new) > 0){
                    foreach ($assetlist_new as $key => $value){  

                      $data1 = $data1.'<option value="'.$value['asset_id'].'">'.$value['asset_tag'].'</option>';

                  }
              } 
          }

      }
      echo $data1;
  }
  function assetfree(Request $request)
  {
    $form_params['parent_asset_id'] = $request->input('parent_asset_id');
    $form_params['asset_id'] = $request->input('asset_id');
    $options = [
        'form_params' => $form_params];
        $result = $this->itam->assetfree($options);   
        echo json_encode($result, true); 
    }

    function assethistory(Request $request)
    {
        $userids = [];
        $form_params['asset_id'] =  $request->input('asset_id');;
        $options = ['form_params' => $form_params];
        $historydata = $this->itam->assethistory($options); 
        $data['historydata'] = _isset(_isset($historydata,'content'),'records');     
        if(is_array($data['historydata']) && count($data['historydata']) > 0)
        {
            foreach($data['historydata'] as $history)
            {
             $userids[$history['user_id']] = $history['user_id'];
         }
     } 

     if(is_array($userids) && count($userids) > 0)
     {
        foreach($userids as $userid)
        {
            $options_optional = [
             'form_params' => ['user_id' => $userid,'status' => "s,y,d,n"],
         ];
         $response_optional = $this->iam->getUsers($options_optional);
         $response_data = _isset(_isset($response_optional, 'content'), 'records');
         if($response_data) $data['users'][$response_data[0]['user_id']] = $response_data[0];
     }

 }
 echo  $html = view("Asset/assethistory",$data);
}
function assignassethistory(Request $request)
{
    $userids = [];
    $form_params['asset_id'] =  $request->input('asset_id');;
    $options = ['form_params' => []];
    $deptdata = $this->iam->getDepartment($options);
    $data['deptdata'] = _isset(_isset($deptdata,'content'),'records');  
    $options = ['form_params' => $form_params];
    $historydata = $this->itam->assignassethistory($options); 
    $data['historydata'] = _isset(_isset($historydata,'content'),'records');   

    echo  $html = view("Asset/assignassethistory",$data);
}

function assetrelationship(Request $request)
{
    $asset_id = $request->input('asset_id');
    $form_params['asset_id'] = $asset_id;
    $options = ['form_params' => $form_params];         
    $asset_rel_data = $this->itam->get_asset_relationship($options);
    $asset_rel_data = _isset($asset_rel_data,'content');
    $final = $user_details = [];
    if(is_array($asset_rel_data) && count($asset_rel_data) > 0){

        for($i=0; $i<count($asset_rel_data);$i++){
            $dt = $asset_rel_data[$i];
            if($dt['child_asset_name'] == ''){
                $option1   = ['form_params' => ['user_id' => $dt['child_asset_id']]];
                $user_details = $this->iam->getUsers($option1);
                $user_details = _isset(_isset($user_details, 'content'), 'records');
                $dt['child_asset_name'] = ucfirst($user_details[0]['firstname']." ".$user_details[0]['lastname']);
            }

            if($dt['parent_asset_id'] == $asset_id){
                if(array_key_exists($dt['rel_type'], $final))
                {
                    ($final[$dt['rel_type']])[$dt['child_asset_name']] = $dt['asset_relationship_id'];
                }
                else
                {                          
                    ($final[$dt['rel_type']])[$dt['child_asset_name']] = $dt['asset_relationship_id'];
                }
            }
            else if($dt['child_asset_id'] == $asset_id){
                if(array_key_exists($dt['inverse_rel_type'], $final)){
                    ($final[$dt['inverse_rel_type']])[$dt['parent_asset_name']] = $dt['asset_relationship_id'];
                }
                else
                {
                    ($final[$dt['inverse_rel_type']])[$dt['parent_asset_name']] = $dt['asset_relationship_id'];
                }
            }
        }
    }
    $data["asset_rel_data"] = $final;
    $data['user_details'] = $user_details;
    echo  $html = view("Asset/assetrelationship",$data);
}

function statuschange(Request $request)
{
    $asset_id = $request->input('asset_id');
    $bv_id = $request->input('bv_id');
    $location_id = $request->input('location_id');
    $parent_asset_id = $request->input('parent_asset_id');
    $department_id = $request->input('department_id');
    $requestername_id = $request->input('requestername_id');

    $instock_asset_prid = $request->input('instock_asset_prid');
    $instock_asset_pr_department_id = $request->input('instock_asset_pr_department_id');
    $instock_asset_pr_requester_id = $request->input('instock_asset_pr_requester_id');

    $status = $request->input('status');
    $limit_offset = limitoffset(0, 0);
    $page = $limit_offset['page'];
    $limit = $limit_offset['limit'];
    $offset = $limit_offset['offset'];
    $form_params['limit'] = $limit;
    $form_params['page'] = $page;
    $form_params['bv_id'] = $bv_id;
    $form_params['location_id'] = $location_id;
    $form_params['offset'] = $offset;
    $options = ['form_params' => $form_params];
    $bvdata = $this->iam->getBusinessVertical($options);
    $locdata = $this->iam->getLocations($options);
    $deptdata = $this->iam->getDepartment($options);
    $assetlist = $this->itam->assets($options);    
    $data['assetlist'] =  _isset(_isset($assetlist,'content'),'records'); 
       // echo "<pre>";print_r($data['assetlist']);   die();
    $data['instock_asset_pr_department_id'] = $instock_asset_pr_department_id;
    $data['instock_asset_pr_requester_id'] = $instock_asset_pr_requester_id;
    
    $data['parent_asset_id'] = $parent_asset_id;
    $data['asset_id'] = $asset_id;
    $data['status'] = $status;
    $data['location_id'] = $location_id;
    $data['bv_id'] = $bv_id;
    $data['requestername_id'] = $requestername_id;
    $data['department_id'] = $department_id;
    $data['locdata'] = _isset(_isset($locdata,'content'),'records');
    $data['deptdata'] = _isset(_isset($deptdata,'content'),'records');
    $data['bvdata'] = _isset(_isset($bvdata,'content'),'records');
        //============= Requester Names Master
    $option                      = ['form_params' => []];
    $requesternameDetails        = $this->itam->getrequesternames($option);
    $data['requesternameDetailsArr']     = _isset(_isset($requesternameDetails, 'content'), 'records');

    echo $html = view("Asset/statuschange",$data);
}

function statuschangesubmit(Request $request)
{
    
    $cahangedata = $request->all();
    $farray = [];
    if(is_array($cahangedata) && count($cahangedata) > 0)
    {
        foreach($cahangedata as $key => $change)
        {
            $farray[$key] = _isset($cahangedata,$key,'');
        }
    }
    
    $data = $this->itam->statuschangesubmit(['form_params' => $farray]);    
    
    echo json_encode($data, true);
}

function importasset(Request $request)
{
 $data['ci_templ_id'] = $request->input('ci_templ_id'); 
 $data['ci_type_id'] = $request->input('ci_type_id'); 
 echo $html = view("Asset/importasset",$data);
}

function importfile(Request $request)
{
    try{
        $file       = $request->file('file');
        $ci_templ_id= $request->input('ci_templ_id');
        $cititle    = $request->input('cititle');
        $ci_type_id = $request->input('ci_type_id');
        $extension  = "";
        $fsize      = "";

        if($file){
            $extension  = strtolower($file->getClientOriginalExtension());
            $fsize      = $file->getClientSize();
        }

        $messages  = [
            'ci_templ_id.required'  => showmessage('000', ['{name}'], [trans('label.lbl_ci')], true),
            'file.required'         => showmessage('000', ['{name}'], [trans('label.lbl_csvfile')], true),
            'file.size'             => showmessage('msg_max_allowed_size', ['{name}'], ['2 MB'], true),
            'extension.required'    => "",
            'extension.in'          => showmessage("162"),
        ];

        $validator         = Validator::make([
            'file'          => $file,
            'extension'     => $extension,
            'size'          => $fsize,
            'ci_templ_id'   => $ci_templ_id,
        ],
        [
            'ci_templ_id'   => 'required',
            'file'          => 'required',
            'size'          => 'max:5000',
            'extension'     => 'required|in:csv',
        ],$messages);
        if ($validator->fails())
        {
            $error              = $validator->errors();
            $data['callfor']    = 'asset_import';
            $data['content']    = "";
            $data['is_error']   = TRUE;
            $data['msg']        = $error;
            echo json_encode($data);
        }
        else
        {
            $fnam = $request->input('filenm');
            if($request->hasFile('file'))
            {
                $fname          = $fnam.".".$extension; 
                $oldfileexists  = Storage::disk('importfile')->exists( $fname );

                if($oldfileexists == $fname){
                    Storage::disk('importfile')->delete( $fname );
                }  

                $request->file->storeAs('importfile', $fname);
                $fileavailabel = Storage::disk('importfile')->exists( $fname );

                if($fileavailabel)
                {
                    if (($handle = fopen( storage_path('app/importfile').'/'.$fname, "r")) !== FALSE) 
                    {       
                        $row = 0;      
                        while (($data = fgetcsv($handle, 1001, ",")) !== FALSE) 
                        {
                            if($row == 0)
                                $col_array = $data;
                            break;
                        }
                        $request->request->add(['type' => 'data']); 
                        $resp                = $this->assetadd($request);
                        $resp['ci_templ_id'] = $ci_templ_id;   
                        $resp['ci_type_id']  = $ci_type_id;
                        $resp['fname']       = $fname;
                        $resp['col_array']   = $col_array;
                        $resp['cititle']     = $cititle;

                        $html               = enview("Asset/importdata",$resp);
                        $data['html']       = $html;
                        $data['is_error']   = false;
                        $data['msg']        = "upload file";
                        echo json_encode($data);
                    }
                    else
                    {
                        $col_array          = $data_array = [];
                        $data['content']    = "";
                        $data['is_error']   = TRUE;
                        $data['msg']        = "File not open";
                        $data['callfor']    = 'asset_import';
                        echo json_encode($data);
                    }

                }
                else
                {
                        //$error = $validator->errors();
                    $data['content']    = "";
                    $data['is_error']   = TRUE;
                    $data['msg']        = "File not upload";
                    $data['callfor']    = 'asset_import';
                    echo json_encode($data);
                }

            }
        }
    }
    catch(\Exception $e){
        $col_array          = $data_array = [];
        $data['content']    = "";
        $data['is_error']   = TRUE;
        $data['msg']        = $e->getMessage();
        $data['callfor']    = 'asset_import';
        echo json_encode($data);
    }
}


function importsave(Request $request)
{
    ini_set('max_execution_time', '1000');
    ini_set("memory_limit","-1");
    $type = $request->input('type'); 

    $limit_offset = limitoffset(0, 0);
    $page = $limit_offset['page'];
    $limit = $limit_offset['limit'];
    $offset = $limit_offset['offset'];
    $form_params['limit'] = $limit;
    $form_params['page'] = $page;
    $form_params['offset']  = $offset;
    $options = [
        'form_params' => $form_params];
        
        $bvdata = $this->iam->getBusinessVertical($options);
        $locdata = $this->iam->getLocations($options);
        $deptdata = $this->iam->getDepartment($options);
        $vendordata = $this->itam->getvendors($options); 
        $locdata = _isset(_isset($locdata,'content'),'records');
        $bvdata = _isset(_isset($bvdata,'content'),'records');
        $vendordata = _isset(_isset($vendordata,'content'),'records');
        $deptdata = _isset(_isset($deptdata,'content'),'records');
        $fbv = $floc = $fvendor = [];
        if(is_array($bvdata) && count($bvdata) > 0)
        {
         foreach($bvdata as $bv)
         {
          $fbv[$bv['bv_name']] =  $bv['bv_id'];
      } 
  }
  if(is_array($locdata) && count($locdata) > 0)
  {
     foreach($locdata as $loc)
     {
      $floc[$loc['location_name']] =  $loc['location_id'];
  } 
}
if(is_array($vendordata) && count($vendordata) > 0)
{
 foreach($vendordata as $vendor)
 {
  $fvendor[$vendor['vendor_name']] =  $vendor['vendor_id'];
} 
}
if(is_array($deptdata) && count($deptdata) > 0)
{
 foreach($deptdata as $dept)
 {
  $fdept[$dept['department_name']] =  $dept['department_id'];
} 
}

$assetdata = $request->all();

$fname = $request->input('fname');   
if(is_array($assetdata) && count($assetdata) > 0)
{
    foreach($assetdata as $key => $adata)
    {
        if(is_array($adata) && count($adata) > 0)
        {
            foreach($adata as $k => $data)
            {  

                $farray[$key][$k] = _isset($adata, $k) ? $adata[$k]:"";

            }
        }    
        else
            $farray[$key] =_isset($this->request_params, $key) ? $request->input($key): '';
    }
}

if($fname != '')
{
    $fileavailabel = Storage::disk('importfile')->exists( $fname );
    if($fileavailabel)
    {

                $contents = file_get_contents(storage_path('app/importfile').'/'.$fname);//Storage::get($fname);
                //d($contents);
                $files_content  = base64_encode($contents);
                /*if (($handle = fopen( storage_path('app/importfile').'/'.$fname, "r")) !== FALSE) 
                {       
                    $row = 0;      
                    while (($fldata = fgetcsv($handle, 1001, ",")) !== FALSE) 
                    { 
                        if($row == 0)
                            $col_array = $fldata;
                        else
                            $data_array[$row] = $fldata;
                        $row++;
                    }
                }*/
            }            
        }
        $assets = [];

       /* if(is_array($data_array) && count($data_array) > 0)
        {
            foreach($data_array as $fdata)
            {
                if(is_array($farray) && count($farray) > 0)
                {   $asset = array();    
                    foreach($farray as $key=>$val)
                    {
                        if($val >= 0 && is_numeric($val))
                        {
                            $asset[$key] = _isset($fdata,$val,$val);
                            if(in_array($key,array('bv_id','location_id','vendor_id')))
                            {
                                if(trim($asset[$key]) != "")
                                {
                                   // die($asset[$key]);
                                    if($key == 'bv_id')
                                        $asset[$key] = _isset($fbv,$asset[$key],"");
                                    elseif($key == 'location_id')
                                        $asset[$key] = _isset($floc,$asset[$key],"");
                                    elseif($key == 'vendor_id')
                                        $asset[$key] = _isset($fvendor,$asset[$key],"");
                                    else
                                         $asset[$key] = $asset[$key];
                                }      
                            }
                            if(in_array($key,array('acquisitiondate','expirydate','warrantyexpirydate')))
                            {
                               if(trim($asset[$key]) != "")
                                {
                                    $asset[$key]= date("Y-m-d H:i:s",strtotime($asset[$key]));
                                }     
                            }
                        }
                        else
                        {
                            if(is_array($val) && count($val) > 0)
                            {
                                foreach($val as $k=> $v)
                                { //if($v == '0')
                                   // echo $v.'<br>';
                                    if($v >= 0 && is_numeric($v))
                                        $asset[$key][$k] = _isset($fdata,$v,$v);
                                    else
                                        $asset[$key][$k] = $v;
                                }
                            }
                            else
                            $asset[$key] = $val;
                        }
                    }
                    $assets[] = $asset;  
                }
            } 
        }*/
        $total = $failed = $import = 0;
       /* if(is_array($assets) && count($assets) > 0)
        {
            $cunkassets = array_chunk($assets,20);
            if(is_array($cunkassets) && count($cunkassets) > 0)
            {
                foreach($cunkassets as $chunk)
                {*/
                   // $form_params['assets'] = $assets;

                    $form_params['farray'] = $farray;
                    $form_params['fdept'] = $fdept;
                    $form_params['fbv'] = $fbv;
                    $form_params['floc'] = $floc;
                    $form_params['fvendor'] = $fvendor;
                    $form_params['files_content'] = $files_content;
                    $options = ['form_params' => $form_params];

                    if($type == "import")
                    {
                        $resp = $this->itam->importdata($options);
                        echo json_encode($resp, true);
                    }
                    else
                    {    
                        //validate required title
                        if(isset($farray) && is_array($farray) && count($farray) > 0){
                            if(isset($farray["title"]) && empty($farray["title"])){
                                $resp = [];
                                $resp['content']    = null;
                                $resp['is_error']   = true;
                                $resp['msg']        = showmessage('123', ['{name}'], [trans('label.lbl_title')], true);
                                $resp['html']       = '';
                                
                                echo json_encode($resp,true);
                                exit;
                            }
                        }else{
                            $resp = [];
                            $resp['content']    = null;
                            $resp['is_error']   = true;
                            $resp['msg']        = showmessage('123', ['{name}'], [trans('label.lbl_title')], true);
                            $resp['html']       = '';

                            echo json_encode($resp,true);
                            exit;
                        }

                        $resp = $this->itam->importsave($options);
                        $total = _isset(_isset($resp,'content'),'total');
                        $failed = _isset(_isset($resp,'content'),'failed');
                        $import = _isset(_isset($resp,'content'),'import');
              /*  }
            }
        }*/
        $response['total'] = $total;
        $response['failed'] = $failed;
        $response['import'] = $import;
        $html = enview("Asset/import_result",$response);
        $resp['html'] = $html;
        echo json_encode($resp,true);
    } 
} 


function swonassetdashboard(Request $request)
{
    $asset_id = $request->input('asset_id');
    $form_params['asset_id'] = $asset_id;
    $options = ['form_params' => $form_params];
    $resp = $this->itam->swonassetdashboard($options);
    if ($resp['is_error'])
    {
        $softwaredata = [];
    }
    else
    {
        $softwaredata = _isset($resp, 'content');
    }
    $data['softwaredata'] = $softwaredata;
        //dd($data['softwaredata']);
    $data['asset_id'] = $asset_id;
    echo  $html = view("Asset/softwareassetdashboard",$data);
}


function assetcontract(Request $request)
{
    $userids = [];
    $form_params['asset_id'] =  $request->input('asset_id');;
    $options = ['form_params' => $form_params];
    $contractdata = $this->itam->assetcontract($options); 
    $data['contractdata'] = _isset(_isset($contractdata,'content'),'records');     
        /*if(is_array($data['historydata']) && count($data['historydata']) > 0)
        {
            foreach($data['historydata'] as $history)
            {
               $userids[$history['user_id']] = $history['user_id'];
            }
        } 
       
        if(is_array($userids) && count($userids) > 0)
        {
            foreach($userids as $userid)
            {
                $options_optional = [
                   'form_params' => array('user_id' => $userid,'status' => "s,y,d,n"),
               ];
                $response_optional = $this->iam->getUsers($options_optional);
               $response_data = _isset(_isset($response_optional, 'content'), 'records');
               if($response_data) $data['users'][$response_data[0]['user_id']] = $response_data[0];
            }

        }*/
        echo  $html = view("Asset/assetcontract",$data);
    }

    function import()
    {
        $notifications = $this->itam->importnotification([]);
        $citemplates = $this->itam->getciitems([]);
        $data['notifications'] = _isset(_isset($notifications,'content'),'records');
        $data['citemplates'] = _isset(_isset($citemplates,'content'),'records');
        $data['pageTitle'] = trans('title.asset_import');//assets;
        $data['includeView'] = view("Asset/import", $data);
        return view('template', $data);
    }
}
?>
