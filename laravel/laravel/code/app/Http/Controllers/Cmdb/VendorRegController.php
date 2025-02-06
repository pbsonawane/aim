<?php

namespace App\Http\Controllers\Cmdb;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use Illuminate\Http\Request;
use View;
use Validator;
use Redirect;


/**
 * Vendor Controller class is implemented to do Vendor operations.
 * @author Kavita Daware
 * @package Contract
 */
class VendorRegController extends Controller
{
    /**
     * Contructor function to initiate the API service and Request data
     * @author Kavita Daware
     * @access public
     * @package Vendor
     * @param \App\Services\ITAM\ItamService $itam
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function __construct(IamService $iam, ItamService $itam, Request $request)
    {
        $this->itam = $itam;
        $this->iam = $iam;
        $this->emlib = new Emlib;
        $this->request = $request;
        $this->request_params = $this->request->all();
    }

    /**
     * Vendor Controller function is implemented to initiate a page to get list of Vendors
     * @author Kavita Daware
     * @access public
     * @package vendor
     * @return string
     */

    public function vendors(Request $request)
    {


        $data['is_verify'] = false;
        $data['errors'] ='';
        $requests = $request->all();
        if(!empty($requests['send_otp'])){

            $validator = Validator::make($request->all(), ['vendor_email' => 'required|email']);
            if ($validator->fails()) {
                $errors = $validator->errors();
                $data['errors'] = $errors->toArray();

                return view('vendorotp', $data);
            }else{

                $token = $this->itam->generatetoken(['form_params' => ['vendor_email' => $requests['vendor_email']]]);
                                
                $token = _isset($token, 'content');

                return Redirect::to('/vendors/verification/'.$token);
            }
        }

        return view('vendorotp', $data);


            ///return view('vendorotp', $data);

        
        //return view('vendorregistration', $data);
    }

    /**
     * Vendor Controller function is implemented to initiate a page to get list of Vendors
     * @author Kavita Daware
     * @access public
     * @package vendor
     * @return string
     */

    public function otpverification(Request $request,$token)
    {

        $request1 = $request->all();
        $option['form_params'] = [ 'token' => $token ];
        $token_data = $this->itam->verifyotptoken($option);
        $token_data = _isset($token_data ,'content');
        $data['errors']['otp'] =[];
        if(!empty($token_data)){ 

            $data['token'] = $token_data[0]['token'];

            if(!empty($request1['verify_otp'])){

                $validator = Validator::make($request->all(), ['otp' => 'required']);
                if ($validator->fails()) {                
                    $errors = $validator->errors();
                    $data['errors'] = $errors->toArray();                   
                    return view('verification', $data);
                }else{
                    $token_data = $this->itam->verifyotptoken(['form_params'=>["otp"=>$request1['otp'],"token"=>$token]]);
                    $otp_data = _isset($token_data ,'content');
                    $is_error = _isset($token_data ,'is_error');
                    $data['msg'] ='';
                    if($is_error){
                        $data['errors']['otp']= [$token_data['msg']];
                    }else{

                        return Redirect::to('/vendors/registration/'.$token);
                    }

                }
            }
            return view('verification', $data);
        }else{
            return Redirect::to('/vendors');
        }
        
        
    }
    public function vendorregistration(Request $request,$token)
    {

        $request1 = $request->all();
        $option['form_params'] = [ 'token' => $token ];
        $token_data = $this->itam->verifyotptoken($option);
        $token_data = _isset($token_data ,'content');

        if(!empty($token_data)){ 
            $option = [];
            $data['citemplates'] = $this->itam->getciitems_vendor($option);
            $data['token'] = $token_data[0]['token'];


            return view('vendorregistration', $data);


        }else{
            return Redirect::to('/vendors');
        }
    }
    
    public function vendoraddsubmit(Request $request,$token)
    {
        // return Redirect::to('/vendors')->with(['status'=>'Thank you for your registration!']);
        
        $request1 = $request->all();

        $option['form_params'] = [ 'token' => $token ];
        $token_data = $this->itam->verifyotptoken($option);
         $data['token'] = $token;
        $token_data = _isset($token_data ,'content');
        $validator = Validator::make($request->all(), [            
            'vendor_pan_file' => 'required|mimes:png,jpg,jpeg,csv,txt,xlx,xls,pdf|max:2048',
            'bank_name_file' => 'required|mimes:png,jpg,jpeg,csv,txt,xlx,xls,pdf|max:2048',            
        ]);
        if ($request['is_gstnumber_reg'] == "Yes") {
            if (empty($_FILES['vendor_gst_no_file'])) {
                $validator->after(function ($validator) {
                    $validator->errors()->add("vendor_gst_no_file","GST File is Required"); 
                });
            }            
        }
        if ($validator->fails()) {           
            $errors = $validator->errors();
            $data['message']['is_error'] = true;      
            $data['message']['msg'] = $errors->toArray();                  
            return view('vendorregistration',$data);
        }else{            
            
            if(!empty($token_data)){ 
                                                
                $option = [];
                $data['citemplates'] = $this->itam->getciitems_vendor($option);
                $data['token'] = $token_data[0]['token'];
                $data['token'] = $token;
                if ($request['is_gstnumber_reg'] == "Yes") {
                    if (isset($_FILES['vendor_gst_no_file'])) {
                        $name1                        = $_FILES["vendor_gst_no_file"]["name"];
                        $arr                          = explode('.', $name1);
                        $file_ext                     = $arr[(count($arr) - 1)];
                        $files_content                = base64_encode(file_get_contents($_FILES['vendor_gst_no_file']['tmp_name']));
                        $request1['saveimg_gst_no']       = "vendor_gst_no_file_" . time() . '.' . $file_ext;
                        $request1['file_name_gst_no']     = $_FILES['vendor_gst_no_file']['name'];
                        $request1['size_gst_no']          = $_FILES['vendor_gst_no_file']['size'];
                        $request1['file_ext_gst_no']      = $file_ext;
                        $request1['files_content_gst_no'] = $files_content;
                    //unset($request1['vendor_gst_no_file']);                
                    }
                }
                if (isset($_FILES['vendor_pan_file'])) {
                    $name1                        = $_FILES["vendor_pan_file"]["name"];
                    $arr                          = explode('.', $name1);
                    $file_ext                     = $arr[(count($arr) - 1)];
                    $files_content                = base64_encode(file_get_contents($_FILES['vendor_pan_file']['tmp_name']));
                    $request1['saveimg_pan']       = "vendor_pan_file_" . time() . '.' . $file_ext;
                    $request1['file_name_pan']     = $_FILES['vendor_pan_file']['name'];
                    $request1['size_pan']          = $_FILES['vendor_pan_file']['size'];
                    $request1['file_ext_pan']      = $file_ext;
                    $request1['files_content_pan'] = $files_content;
                //unset($request1['vendor_pan_file']);                
                }
                if (isset($_FILES['bank_name_file'])) {
                    $name1                        = $_FILES["bank_name_file"]["name"];
                    $arr                          = explode('.', $name1);
                    $file_ext                     = $arr[(count($arr) - 1)];
                    $files_content                = base64_encode(file_get_contents($_FILES['bank_name_file']['tmp_name']));
                    $request1['saveimg_bank_name']       = "bank_name_file_" . time() . '.' . $file_ext;
                    $request1['file_name_bank_name']     = $_FILES['bank_name_file']['name'];
                    $request1['size_bank_name']          = $_FILES['bank_name_file']['size'];
                    $request1['file_ext_bank_name']      = $file_ext;
                    $request1['files_content_bank_name'] = $files_content;
                //unset($request1['bank_name_file']);                
                }

		if ($request['is_msme_reg'] == "Yes") {
                if (isset($_FILES['msme_certificate']) && $_FILES['msme_certificate']['name'] != "") {
            $name1                        = $_FILES["msme_certificate"]["name"];
          
            $arr                          = explode('.', $name1);
            $file_ext                     = $arr[(count($arr) - 1)];
            $files_content                = base64_encode(file_get_contents($_FILES['msme_certificate']['tmp_name']));
            $request1['saveimg_msme_certificate']       = "msme_certificate_" . time() . '.' . $file_ext;
            $request1['file_name_msme_certificate']     = $_FILES['msme_certificate']['name'];
            $request1['size_msme_certificate']          = $_FILES['msme_certificate']['size'];
            $request1['file_ext_msme_certificate']      = $file_ext;
            $request1['files_content_msme_certificate'] = $files_content;
        //unset($request1['bank_name_file']);                
        } 
}
                
                $vendor_data = $this->itam->addvendors(['form_params' => $request1]); 

                              
                $insent_data = _isset($vendor_data ,'content');
                $data['message'] = $vendor_data;
        // $vendor_data['insert_id'] = 'a8df4e10-dff5-11ec-b779-a24dafd8ee00';
                if (!empty($insent_data['insert_id'])) {
                 $request1 = $request->all();
                 $option['form_params'] = [ 'token' => $token , 'reset' => 'yes'];
                 $token_data = $this->itam->verifyotptoken($option);
                 return Redirect::to('/vendors')->with(['status'=>'Thank you for your registration!']);
             }             
             return view('vendorregistration',$data);
         }else{
            return Redirect::to('/vendors');
        }
    }

    
    

        // return redirect('vendors')->withInput();

}
    /**
     * This controller function is used to load vendor edit form with existing data for selected vendor
     * @author Kavita Daware
     * @access public
     * @package vendor
     * @param \Illuminate\Http\Request $request
     * @param $vendor_id vendor Unique Id
     * @return string
     */
    public function vendoredit(Request $request)
    {
        $vendor_id = $request->id;
        $input_req = ['vendor_id' => $vendor_id];
        $data = $this->itam->editvendor(['form_params' => $input_req]);

        $data['vendor_id'] = $vendor_id;
        $data['vendordata'] = $data['content'];

        $html = view("Cmdb/vendoradd", $data);
        echo $html;
    }
    /**
     * This controller function is used to update vendor data in database.
     * @author Kavita Daware
     * @access public
     * @package vendor
     * @param UUID $vendor_id vendor  Unique Id
     * @return json
     */
    public function vendoreditsubmit(Request $request)
    {
        $data = $this->itam->updatevendor(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to delete vendor  data from database.
     * @author Kavita Daware
     * @access public
     * @package vendor
     * @param UUID $vendor_id Unique Id
     * @return json
     */
    public function vendordelete(Request $request)
    {
        $data = $this->itam->deletevendor(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }
}
