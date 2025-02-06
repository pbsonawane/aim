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

class TemplateconfigController extends Controller
{
    public function __construct(IamService $iam, Request $request) 
    {
		$this->itam = $itam;
        $this->iam = $iam;
        $this->emlib = new Emlib;
        $this->request = $request;
        $this->request_params = $this->request->all();
    }
	
    /**
     * This is model function is used to get From Template JSON with Data based on Template Name.
     * @author Namrata Thakur
     * @access public
     * @package TemplateConfig
     * @param string $template_name Template Name
     * @return string
     */    
    public function getconfigtemplate($template_name) 
    {	
        try{
            $request['template_name'] = $template_name;	
            $inputdata	= ['template_name' => $request['template_name']];
            $data		= $this->itam->getFormTemplateDefaulteConfigbyTemplateName([ 'form_params' => $inputdata]);
            
			//api call to get all ensysconfig list and decide to load data from ensysconfig or not
			$ensyscall = is_ensyscall($template_name);
			
			//replace unnecessary '\r' from template data/structure json string
			if(isset($data['content'][0]['details'])) $data['content'][0]['details'] = str_replace('\r"','"',($data['content'][0]['details']));
			
            if($data['content'])        
            {
				if($ensyscall == true && $template_name != ''){
					$ensysdata = get_ensys_configdetails($template_name);
					
					if(isset($ensysdata) && is_array($ensysdata) && count($ensysdata) > 0){
					
						$configdata = ["content" =>["form_templ_id"=>$data['content'][0]['form_templ_id'],
														 "details" =>json_encode($ensysdata),
														 "is_error" => '',
														 "msg" => "Template Config record/s found." //internal msg
														]];
										
						$disabled_fields = get_ensys_disabledfields($template_name);
						
						if(isset($disabled_fields) && is_array($disabled_fields) && count($disabled_fields) > 0){
							$temp = [];
							if(isset($disabled_fields['content']) && ($disabled_fields['content'] != "")){
								foreach($disabled_fields['content'] as $key=>$val){
									array_push($temp,$key);
								}
							}
							
							if(isset($configdata['content'])){
								$configdata['content']['disabledfields'] = json_encode($temp);
								$data['disabledfields'] = json_encode($temp);
							}
						}
					}
					else{
						$configdata = ["content" =>[],
											"is_error" => true,
											"msg" => "Template Config record/s not found." //internal msg
										];
					}
				}
				else{
					$inputdata  = ['config_id' => $data['content'][0]['form_templ_id']];
					$configdata = $this->itam->getFormDataConfig([ 'form_params' => $inputdata]);
				}
				
                $data['form_templ_id']	 = $data['content'][0]['form_templ_id'];
                $data['urlpath']		 = $data['content'][0]['template_name'];
                $data['templatetitle']	 = $data['content'][0]['template_title'];
                $data['form_templ_data'] = $data['content'][0];

                if(isset($data['form_templ_data']['details']))
                {
                    $details_arr_org	 = json_decode($data['form_templ_data']['details'], true);
                    $details_fld_arr_org = _isset($details_arr_org, 'fields') ? $details_arr_org['fields'] : [];
                    if(is_array($details_fld_arr_org) && count($details_fld_arr_org) > 0)
                    {
                        foreach($details_fld_arr_org as $key => $field)
                        {                            
                            if(_isset(_isset($field, 'config'),'label') && _isset(_isset($field, 'attrs'),'name'))
                            {
                                $details_fld_arr_org[$key]['config']['label'] = trans('settingtemplate.'.$field['attrs']['name']);
                            }

                            if(_isset(_isset($field, 'config'),'label') && _isset(_isset($field, 'attrs'),'name') && _isset(_isset($field, 'attrs'),'placeholder'))
                            {
                                $details_fld_arr_org[$key]['attrs']['placeholder'] = trans('settingtemplate.'.$field['attrs']['name']);
                            }
                           
                            if(_isset($field, 'options') && is_array($field['options']) && count($field['options']) > 0)
                            {                                
                                foreach ($field['options'] as $opkey => $option) {
                                    if(_isset($option, 'value') && _isset($option, 'label')){
                                        $details_fld_arr_org[$key]['options'][$opkey]['label'] = trans('settingtemplate.'.$option['value']);
                                    } 
                                }
                            }
                        }
                        $details_arr_org['fields'] = $details_fld_arr_org;
                        $details_arr_lang = json_encode($details_arr_org);
                    }
                }
				
				
                $data['details_arr_lang'] = $details_arr_lang;
                $data['form_templ_configdata'] = $configdata['content'];
                $data['action'] = "edit";
            }
            
            $data['pageTitle'] = "Config - ".$template_name;
            $data['site_url'] = config('app.site_url');                
            $data['includeView'] = view("Admin/templateconfig",$data);
            return view('template',$data);
            
        }
        catch(\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("getconfigtemplate","This is model function is used to get From Template JSON with Data based on Template Name.",$this->request_params,$data['message']['error']);
            return response()->json($data);
        }
        catch(\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("getconfigtemplate","This is model function is used to get From Template JSON with Data based on Template Name.",$this->request_params,$data['message']['error']);
           	return response()->json($data);
        }
    }
    /**
     * This is model function is used to Update From Template Config Data based on Template Name.
     * @author Namrata Thakur
     * @access public
     * @package TemplateConfig
     * @param json $details JSON of Data
     * @param string $form_templ_type JSON of Data
     * @param UUID $form_templ_id Form Templ Id Unique Id
     * @param string $urlpath Template Name
     * @return json
     */     
    public function formdataconfigupdate(request $request)
    {
		$details		= $request['details'];
		$template_name  = $request['urlpath'];
		$ensyscall		= is_ensyscall($template_name);
		
		if($ensyscall == true)
		{
			//remove unnecessary fields from json data
			$dtl_array	= json_decode($details, true);
			if(array_key_exists("_token",$dtl_array)) unset($dtl_array["_token"]);
			$details	= json_encode($dtl_array);
			
			//api call to ensysconfig to update config
			$data = update_ensysconfig($template_name,$details);
			
			if($data == true){
				$data = ["content"=>"","is_error"=>false,"msg"=>"Template Config updated successfully."];//internal msg returned from api, no need to translate
			}else{
				$data = ["content"=>"","is_error"=>true,"msg"=>"Template Config could not updated."];//internal msg
			}
		}else
		{
			$data = $this->itam->formdataconfigupdate([ 'form_params' => $request->all()]);
		}
		
        echo json_encode($data,true);
    }
    /**
     * This is model function is used to upload file.
     * @author Namrata Thakur
     * @access public
     * @package TemplateConfig
     * @param file $certificate_file
     * @return json
     */     
    public function formdataconfigUploadfile(Request $request)
    {
        try{
            $image_content= base64_encode(file_get_contents($_FILES['certificate_file']['tmp_name']));
            $form_params['certificate_file'] =  $image_content ;
            $form_params['certificate_file_name'] =  $_FILES['certificate_file']['name'];
            $form_params['size'] = $_FILES['certificate_file']['size'];
            
            $options = ['form_params' => $form_params];         
            $data =  $this->itam->formdataconfigUploadfile($options);
            
            echo json_encode($data,true);
        }
        catch(\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("formdataconfigUploadfile","This is model function is used to upload file.",$request->all(),$data['message']['error']);
            return response()->json($data);
        }
        catch(\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("formdataconfigUploadfile","This is model function is used to upload file.",$request->all(),$data['message']['error']);
           	return response()->json($data);
        }
    }

    /**
     * This is model function is used to upload file.
     * @author Namrata Thakur
     * @access public
     * @package TemplateConfig
     * @param file $certificate_file
     * @return json
     */   
    public function ajaxUploadImage(Request $request){
        try{
            // print_r($request->all());
           /* $extension = $request->file('file')->getClientOriginalExtension();
            $dir = 'uploads/product_logo';
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $request->file('file')->move($dir, $filename);
            echo  $filename;*/
            if ($request->hasFile('image')) {
                // your code here
                echo trans('messages.148');
            }
            else
            {
                echo trans('messages.149');
            }
            exit;
            $file = $request->file('image');
   
            //Display File Name
            echo 'File Name: '.$file->getClientOriginalName();
            echo '<br>';
        
            //Display File Extension
            echo 'File Extension: '.$file->getClientOriginalExtension();
            echo '<br>';
        
            //Display File Real Path
            echo 'File Real Path: '.$file->getRealPath();
            echo '<br>';
        
            //Display File Size
            echo 'File Size: '.$file->getSize();
            echo '<br>';
        
            //Display File Mime Type
            echo 'File Mime Type: '.$file->getMimeType();
        
            //Move Uploaded File
            $destinationPath = 'uploads';
            $file->move($destinationPath,$file->getClientOriginalName());
        }
        catch(\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("ajaxUploadImage","This is model function is used to upload file.",$request->all(),$data['message']['error']);
            return response()->json($data);
        }
        catch(\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("ajaxUploadImage","This is model function is used to upload file.",$request->all(),$data['message']['error']);
           	return response()->json($data);
        }
    }
}
