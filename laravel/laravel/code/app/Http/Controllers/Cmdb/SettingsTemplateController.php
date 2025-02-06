<?php

namespace App\Http\Controllers\Cmdb;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Redirect;
use View;

class SettingsTemplateController extends Controller
{
    /**
     * Contructor function to initiate the API service and Request data
     * @author Namrata Thakur
     * @access public
     * @package SettingsTemplate
     * @param \App\Services\IAM\IamService $iam
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function __construct(IamService $iam,ItamService $itam,Request $request)
    {
        $this->itam = $itam;
        $this->iam = $iam;
        $this->emlib = new Emlib;
        $this->request = $request;
        $this->request_params = $this->request->all();     
    }	
    /**
     * SettingsTemplate controller function is implemented to initiate a page to get list of SettingsTemplate.
     * @author Namrata Thakur
     * @access public
     * @package SettingsTemplate
     * @return string
     */     
	public function settingstemplate() 
    {
		
		$topfilter = ['gridsearch' => true,'jsfunction' => 'settingtemplateList()'];
		$data['emgridtop'] = $this->emlib->emgridtop($topfilter);   
		$data['pageTitle'] = trans('title.setting_templates');
		$data['includeView'] = view("SettingsTemplate/settingstemplate",$data);
		return view('template',$data);
    }
    /**
     * This controller function is implemented to get list of SettingsTemplate.
     * @author Namrata Thakur
     * @access public
     * @package SettingsTemplate
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */    
	public function settingstemplatelist() 
    {
        try
        {
            $paging = [];
            $limit = _isset($this->request_params, 'limit', config('enconfig.def_limit'));
            $page = _isset($this->request_params, 'page', config('enconfig.page'));
            $searchkeyword = _isset($this->request_params, 'searchkeyword');
            $is_error = false;
            $msg = '';$content="";
            $limit_offset = limitoffset($limit, $page);
            $page = $limit_offset['page'];
            $limit = $limit_offset['limit'];
            $offset = $limit_offset['offset'];
            
            $form_params['limit'] = $paging['limit'] = $limit;
            $form_params['page'] = $paging['page'] = $page;
            $form_params['offset'] = $paging['offset'] = $offset;
            $form_params['searchkeyword'] = $searchkeyword;
            
            $options = [
                'form_params' => $form_params];
                
            $regions_resp = $this->itam->getSettingstemplates($options);
            if($regions_resp['is_error'])
            {
                $is_error = $regions_resp['is_error'];
                $msg = $regions_resp['msg'];
            }
            else
            {
                $regions = _isset(_isset($regions_resp,'content'),'records');
                $paging['total_rows'] = _isset(_isset($regions_resp,'content'),'totalrecords');
                $paging['showpagination'] = true;
                $paging['jsfunction'] = 'settingtemplateList()';				
                $view = 'SettingsTemplate/settingstemplatelist';
                $content = $this->emlib->emgrid($regions, $view, [], $paging);
            }

            $response["html"] = $content;
            $response["is_error"] = $is_error;
            $response["msg"] = $msg;
            echo json_encode($response);
        }
        catch(\Exception $e){
        
            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            //save_errlog("settingstemplatelist","This controller function is implemented to get list of SettingsTemplate.", $request->all(), $response['msg']);
            echo json_encode($response);
        }
        catch (\Error $e) {
        
            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            //save_errlog("settingstemplatelist"," This controller function is implemented to get list of SettingsTemplate.", $request->all(), $response['msg']);
            echo json_encode($response);
        }
	}    

    /**
     * This controller function is used to load SettingsTemplate add form.
     * @author Namrata Thakur
     * @access public
     * @package SettingsTemplate
     * @return string
     */
    public  function settingstemplateadd(Request $request)
    {
        $data['action'] = "add";
        $data['form_templ_id'] = '';
		$data['form_templ_data'] = [];
        $html = view('SettingsTemplate.settingstemplateadd', $data);
        echo  $html;        
    }   
    /**
     * This controller function is used to save SettingsTemplate data in database.
     * @author Namrata Thakur
     * @access public
     * @package SettingsTemplate
     * @param string $template_title Template Title
     * @param string $template_name Template Name
     * @param string $template_type Template Type
     * @param string $details Details
     * @param string $description Descriptions
     * @return json
     */     
    public function settingstemplatesubmit(Request $request)
    {
       $data =  $this->itam->addSettingstemplate([ 'form_params' => $request->all()]);
       echo json_encode($data,true);
    }
    /**
     * This controller function for Edit to get SettingsTemplate data in database.
     * @author Namrata Thakur
     * @access public
     * @package SettingsTemplate
     * @param string $form_templ_id Form Template Id
     * @return string
     */       
    public  function settingstemplateedit(Request $request)
    {
       try{         
            $inputdata= ['form_templ_id' => $request->id];
            $data =  $this->itam->editSettingstemplate([ 'form_params' => $inputdata]);
            $data['form_templ_id'] = $request->id;
			
			if(isset($data['content'][0]['details'])) $data['content'][0]['details'] = str_replace('\r"','"',($data['content'][0]['details']));
			
            $data['form_templ_data'] = $data['content'][0];
            if(isset($data['form_templ_data']['details']))
            {
                $details_arr_org = json_decode($data['form_templ_data']['details'], true);
                $details_fld_arr_org = _isset($details_arr_org, 'fields') ? $details_arr_org['fields'] : [];
                if(is_array($details_fld_arr_org) && count($details_fld_arr_org) > 0)
                {
                    foreach($details_fld_arr_org as $key => $field)
                    {
                        //echo "<pre> Label : "; print_r($field);  echo "</pre>"; 
                        if(_isset(_isset($field, 'config'),'label') && _isset(_isset($field, 'attrs'),'name'))
                        {
                            //echo "<pre> Label : "; print_r($field['config']['label']);  //echo "</pre>"; 
                            //echo "<pre>Name: "; print_r($field['attrs']['name']);  echo "</pre>"; 
                            $details_fld_arr_org[$key]['config']['label'] = trans('settingtemplate.'.$field['attrs']['name']);
                        }
                        //echo "<pre> Label : "; print_r($field);  echo "</pre>"; 
                        //echo "<pre> Label : "; print_r("==========================");  echo "</pre>"; 
                    }
                    //echo "<pre> "; print_r($details_fld_arr_org);  echo "</pre>"; 
                    
                    $details_arr_org['fields'] = $details_fld_arr_org;
                    $details_arr_lang = json_encode($details_arr_org);
                }
            }
            $data['details_arr_lang'] = $details_arr_lang;
            $data['action'] = "edit";        
            $html = view('SettingsTemplate.settingstemplateadd', $data);
            echo  $html;       
        }        
        catch(\Exception $e){
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            save_errlog("settingstemplateedit","This controller function for Edit to get SettingsTemplate data in database", $request->all(), $response['msg']);
            echo json_encode($response); 
        }
        catch(\Error $e){
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            save_errlog("settingstemplateedit","This controller function for Edit to get SettingsTemplate data in database", $request->all(), $response['msg']);
            echo json_encode($response);
        } 
    }
    /**
     * This controller function is used to Update SettingsTemplate data in database.
     * @author Namrata Thakur
     * @access public
     * @package SettingsTemplate
     * @param string $form_templ_id Form Template Id
     * @param string $template_title Template Title
     * @param string $template_name Template Name
     * @param string $template_type Template Type
     * @param json $details Details Form Builder JSON
     * @param string $description Descriptions
     * @return json
     */         
    public function settingstemplateupdate(Request $request)
    {
       $data =  $this->itam->updateSettingstemplate([ 'form_params' => $request->all()]);
       echo json_encode($data,true);
    }
    /**
     * This controller function is used to Delete SettingsTemplate data in database.
     * @author Namrata Thakur
     * @access public
     * @package SettingsTemplate
     * @param string $form_templ_id Form Template Id
     * @param string $status Status - d
     * @return json
     */       
    public function settingstemplatedelete(Request $request)
    {
        $data =  $this->itam->deleteSettingstemplate([ 'form_params' => $request->all()]);
        echo json_encode($data,true);
    }
    /**
     * This controller function is used to make Clone of Template data in database.
     * @author Namrata Thakur
     * @access public
     * @package SettingsTemplate
     * @param string $form_templ_id Form Template Id
     * @return json
     */       
    public function formtemplatedefaultclone(Request $request)
    {
        $data =  $this->itam->formtemplatedefaultclone([ 'form_params' => $request->all()]);
        echo json_encode($data,true);
    }
    
}

