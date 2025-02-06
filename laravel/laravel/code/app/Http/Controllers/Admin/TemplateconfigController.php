<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\IAM\IamService;
use App\Libraries\Emlib;

class TemplateconfigController extends Controller
{
    public function __construct(IamService $iam, Request $request) {
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
        
        $request['template_name'] = $template_name;	
        $inputdata= ['template_name' => $request['template_name']];
        $data =  $this->iam->getFormTemplateDefaulteConfigbyTemplateName([ 'form_params' => $inputdata]);
       
        if($data['content'])        
        {
            $inputdata= ['config_id' => $data['content'][0]['form_templ_id']];
            $configdata =  $this->iam->getFormDataConfig([ 'form_params' => $inputdata]);
            $data['form_templ_id'] = $data['content'][0]['form_templ_id'];
            $data['urlpath'] = $data['content'][0]['template_name'];
            $data['templatetitle'] = $data['content'][0]['template_title'];
            $data['form_templ_data'] = $data['content'][0];
            $data['form_templ_configdata'] = $configdata['content'];
            $data['action'] = "edit";
        }      
        $data['pageTitle'] = "Config - ".$template_name;
        $data['site_url'] = config('app.site_url');
         //dd($data);
		$data['includeView'] = view("Admin/templateconfig",$data);
		return view('template',$data);
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
        $data =  $this->iam->formdataconfigupdate([ 'form_params' => $request->all()]);
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
    public function formdataconfigUploadfile(request $request)
    {
       //echo "<script>alert('hello555')</script>";
        $data =  $this->iam->formdataconfigUploadfile([ 'form_params' => $request->all()]);
        //echo $data;
        echo json_encode($data,true);
    }
    public function ajaxUploadImage(Request $request){
        // print_r($request->all());
           /* $extension = $request->file('file')->getClientOriginalExtension();
            $dir = 'uploads/product_logo';
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $request->file('file')->move($dir, $filename);
            echo  $filename;*/
            if ($request->hasFile('image')) {
                // your code here
                echo "Image Available";
            }
            else
            {
                echo "Image Not found";
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
}
