<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\IAM\IamService;
use App\Libraries\Emlib;

class CredentialController extends Controller
{
    /**
     * Contructor function to initiate the API service and Request data
     * @author Namrata Thakur
     * @access public
     * @package Credential
     * @param \App\Services\IAM\IamService $iam
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function __construct(IamService $iam, Request $request) {
		$this->iam = $iam;
        $this->emlib = new Emlib;
        $this->request = $request;
        $this->request_params = $this->request->all();
    }	
    /**
     * Credential controller function is implemented to initiate a page to get list of Credential.
     * @author Namrata Thakur
     * @access public
     * @package Credential
     * @return string
     */ 
    public function credential() {
		
		$topfilter = ['gridsearch' => true,'jsfunction' => 'credentialList()'];
		$data['emgridtop'] = $this->emlib->emgridtop($topfilter);   
		$data['pageTitle'] = "Settings Template";
		$data['includeView'] = view("Admin/credential",$data);
		return view('template',$data);
    }
    /**
     * This controller function is implemented to get list of Credential.
     * @author Namrata Thakur
     * @access public
     * @package Credential
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */     
	public function credentiallist() {
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
			
        $template_resp = $this->iam->getcredentials($options);
        $form_paramstype['type'] = "cr";
        $optionsType = ['form_params' => $form_paramstype];
        $formTmplCredential_resp = $this->iam->getFormTemplateDefaulteCredentialbyType($optionsType);
     //   echo "<pre>";
     //  print_r($template_resp); 
       
      // $formTmplCredential = _isset(_isset($formTmplCredential_resp,'content'),'records');
		if($template_resp['is_error'])
		{
			$is_error = $template_resp['is_error'];
            $msg = $template_resp['msg'];            
		}
		else
		{
            $templateData = $template_resp['content']['records'];
            $formTmplCredential = $formTmplCredential_resp['content'];
            if(!empty($formTmplCredential) && !empty($templateData))
            {
                foreach( $templateData as $keyTempl=>$template)
                {
                    foreach($formTmplCredential as $key=>$credential)
                    {
                        if($templateData[$keyTempl]['form_templ_id'] == $formTmplCredential[$key]['form_templ_id']){
                            $templateData[$keyTempl]['template_name'] = $formTmplCredential[$key]['template_name'];
                            $templateData[$keyTempl]['template_title'] = $formTmplCredential[$key]['template_title'];
                        }
                    }
                }

            }
            
            $is_error = false;
            //$templateData = _isset(_isset($template_resp, 'content'), 'records');
            $paging['total_rows'] = _isset(_isset($template_resp, 'content'), 'totalrecords');
           // $paging['total_rows'] = true; //_isset(_isset($formTmplCredential,'content'),'totalrecords');
            $paging['showpagination'] = true;
            $paging['jsfunction'] = 'credentialList()';	
            $paging['limit'] = $limit;
            $paging['offset'] = $offset;
            $paging['page'] = $page;			
            $view = 'Admin/credentiallist';
            $content = $this->emlib->emgrid($templateData, $view, $columns = [], $paging);
		} 		
		$response["html"] = $content;
		$response["is_error"] = $is_error;
		$response["msg"] = $msg;
		echo json_encode($response);
    }  
    /**
     * This controller function is used to List  Template Name of Type Credential.
     * @author Namrata Thakur
     * @access public
     * @package Credential
     * @param string $type Template type
     * @return string
     */      
    public function credentialtemplatetypes() {
        $form_paramstype['type'] = "cr";
        $optionsType = ['form_params' => $form_paramstype];
        $formTmplCredential_resp = $this->iam->getFormTemplateDefaulteCredentialbyType($optionsType);
        
        if($formTmplCredential_resp['is_error'])
		{
			$formTmplCredential = [];
		}
		else
		{
			$formTmplCredential = _isset($formTmplCredential_resp,'content');
		}

        $data['action'] = "list";
        $data['form_templ_id'] = '';
        $data['cr_types'] = $formTmplCredential;
        
        $html = view('Admin.credentialtypelist', $data);
        echo  $html; 
    }
    /**
     * This controller function is used to get From Credential Data based on render Form BuilderForm of selected Template from List of Credential's.
     * @author Namrata Thakur
     * @access public
     * @package Credential
     * @param string $template_name Template Name
     * @return string
     */     
    public function getCredentialtemplatebytype(Request $request)
    {
        $inputdata= ['template_name' => $request->template_name];
        $data =  $this->iam->getFormTemplateDefaulteConfigbyTemplateName([ 'form_params' => $inputdata]);
        if($data['content'])        
        {
            $data['form_templ_data'] = $data['content'][0];
        }  
        else{
            $data['form_templ_data'] = [];
        }
        $data['action'] = "add";
        $html = view('Admin.credentialtypetemplate', $data);
        echo  $html; 
    }
    /**
     * This controller function is used to Edit Credential Data Of Render FormBuilder Form.
     * @author Namrata Thakur
     * @access public
     * @package Credential
     * @param string $template_name Template Name
     * @return string
     */       
    public function credentialedit(Request $request)
    {
        $inputdata= ['template_name' => $request->template_name];
        $data =  $this->iam->getFormTemplateDefaulteConfigbyTemplateName([ 'form_params' => $inputdata]);
        $data['action'] = "edit";
        if($data['content'])        
        {
            $inputdataCre = ['config_id' => $request->config_id];
            $configdata =  $this->iam->getformdatacredential([ 'form_params' => $inputdataCre]);
            $data['form_templ_id'] = $data['content'][0]['form_templ_id'];
            $data['urlpath'] = $data['content'][0]['template_name'];
            $data['form_templ_data'] = $data['content'][0];
            $data['form_templ_creditdata'] = $configdata['content'][0];
            
             
        }   
        else{
            $data['form_templ_data'] = [];
        }        
        $html = view('Admin.credentialtypetemplate', $data);
        echo  $html; 
    }
    /**
     * This controller function is used to Add Credential Data Of Render FormBuilder Form.
     * @author Namrata Thakur
     * @access public
     * @package Credential
     * @param json $details JSON of Data
     * @param string $form_templ_type JSON of Data
     * @param UUID $form_templ_id Form Templ Id Unique Id
     * @param string $urlpath Template Name
     * @return json
     */     
    public function templatecredentialadd(request $request)
    {
        $data =  $this->iam->formdatacredentialadd([ 'form_params' => $request->all()]);
        echo json_encode($data,true);
    }
    /**
     * This controller function is used to Update Credential Data Of Render FormBuilder Form.
     * @author Namrata Thakur
     * @access public
     * @package Credential
     * @param json $details JSON of Data
     * @param string $form_templ_type JSON of Data
     * @param UUID $form_templ_id Form Templ Id Unique Id
     * @param UUID $config_id Config Id
     * @param string $urlpath Template Name
     * @return json
     */     
    public function templatecredentialupdate(request $request)
    {
        $data =  $this->iam->formdatacredentialupdate([ 'form_params' => $request->all()]);
        echo json_encode($data,true);
    }
    /**
     * This controller function is used to Delete Credential Data Of Render FormBuilder Form.
     * @author Namrata Thakur
     * @access public
     * @package Credential
     * @param UUID $config_id Config Id
     * @param string $status Status
     * @return json
     */     
    public function credentialdelete(Request $request)
    {
        $data =  $this->iam->deletecredential([ 'form_params' => $request->all()]);
        echo json_encode($data,true);
    }
}
