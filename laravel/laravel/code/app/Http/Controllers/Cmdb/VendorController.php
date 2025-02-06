<?php

namespace App\Http\Controllers\Cmdb;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use Illuminate\Http\Request;
use View;

/**
 * Vendor Controller class is implemented to do Vendor operations.
 * @author Kavita Daware
 * @package Contract
 */
class VendorController extends Controller
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

    public function vendors()
    {

        $topfilter = ['gridsearch' => true, 'jsfunction' => 'vendorList()', 'gridadvsearch' => false];
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter, '', ["vendor_name"]);
        $data['pageTitle'] = trans('title.vendor');
        // 
        
        $form_params['getServices'] = "getServices";
        $options = ['form_params' => $form_params];
        $data['getvendorservices'] = $this->itam->getvendorservices($options);
        // 
        $data['includeView'] = view("Cmdb/vendors", $data);
        return view('template', $data);
    }
    /** * This controller function is implemented to get list of Vendors.
     * @author Kavita Daware
     * @access public
     * @package vendor
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */

    public function vendorServicesearch(Request $request)
    {
        $concatservice = $request->search_service;
        $search_service = "";
        foreach($concatservice as $service)
        {
            if($search_service!="")
            {
                $search_service .=",";
            }
            $search_service .="'" . $service ."'";
        }
        $paging = [];
        $fromtime = $totime = '';
        $limit = _isset($this->request_params, 'limit', config('enconfig.def_limit_short'));
        $exporttype = _isset($this->request_params, 'exporttype');
        $page = _isset($this->request_params, 'page', config('enconfig.page'));
        $searchkeyword = _isset($this->request_params, 'searchkeyword');

        $is_error = false;
        $msg = '';
        $content = "";
        $limit_offset = limitoffset($limit, $page);
        $page = $limit_offset['page'];
        $limit = $limit_offset['limit'];
        $offset = $limit_offset['offset'];
        $form_params['limit'] = $paging['limit'] = $limit;
        $form_params['page'] = $paging['page'] = $page;
        $form_params['offset'] = $paging['offset'] = $offset;
        
        $search_service = $request->search_service;
        $form_params['search_service'] = $search_service;
        $options = ['form_params' => $form_params];

        $vendor_resp = $this->itam->getvendorbyservices($options);   
                  
        if ($vendor_resp['is_error'])
        {
            $is_error = $vendor_resp['is_error'];
            $msg = $vendor_resp['msg'];
        }
        else
        {
            $is_error = false;
            $vendors = _isset(_isset($vendor_resp, 'content'), 'records');
            $paging['total_rows'] = _isset(_isset($vendor_resp, 'content'), 'totalrecords');
            $paging['showpagination'] = true;
            $paging['jsfunction'] = 'vendorList()';
            $view = 'Cmdb/vendorlist';
            $content = $this->emlib->emgrid($vendors, $view, $columns = [], $paging);
        }

        $response["html"] = $content;
        $response["is_error"] = $is_error;
        $response["msg"] = $msg;
        echo json_encode($response);
    }
    public function vendorlist()
    {
        //try
        //{
            $paging = [];
            $fromtime = $totime = '';
            $limit = _isset($this->request_params, 'limit', config('enconfig.def_limit_short'));
            $exporttype = _isset($this->request_params, 'exporttype');
            $page = _isset($this->request_params, 'page', config('enconfig.page'));
            $searchkeyword = _isset($this->request_params, 'searchkeyword');

            $is_error = false;
            $msg = '';
            $content = "";
            $limit_offset = limitoffset($limit, $page);
            $page = $limit_offset['page'];
            $limit = $limit_offset['limit'];
            $offset = $limit_offset['offset'];

            $form_params['limit'] = $paging['limit'] = $limit;
            $form_params['page'] = $paging['page'] = $page;
            $form_params['offset'] = $paging['offset'] = $offset;
            $form_params['searchkeyword'] = $searchkeyword;

            $options = ['form_params' => $form_params];

            $vendor_resp = $this->itam->getvendors($options);
            // print_r($vendor_resp); die;
            if ($vendor_resp['is_error'])
            {
                $is_error = $vendor_resp['is_error'];
                $msg = $vendor_resp['msg'];
            }
            else
            {
                $is_error = false;
                $vendors = _isset(_isset($vendor_resp, 'content'), 'records');
                $paging['total_rows'] = _isset(_isset($vendor_resp, 'content'), 'totalrecords');
                $paging['showpagination'] = true;
                $paging['jsfunction'] = 'vendorList()';
                $view = 'Cmdb/vendorlist';
                //$vendor_id = isset($vendors[0]['vendor_id']) ? $vendors[0]['vendor_id'] : "";
                $content = $this->emlib->emgrid($vendors, $view, $columns = [], $paging);
            }

            $response["html"] = $content;
            $response["is_error"] = $is_error;
            $response["msg"] = $msg;
            //$response['vendor_id'] = $vendor_id;
            echo json_encode($response);
       // }
       /* catch (\Exception $e)
        {
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            
            echo json_encode($response);
        }
        catch (\Error $e)
        {
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
           
            echo json_encode($response);
        }*/
    }
    /**
     * This controller function is used to load vendor add form.
     * @author Kavita Daware
     * @access public
     * @package vendor
     * @return string
     */
    public function vendoradd(Request $request)
    {
        $data['vendor_id'] = '';
        $vendordata = [];
        $data['vendordata'] = $vendordata;
        $option = [];
        $data['citemplates'] = $this->itam->getciitems($option);
        $html = view("Cmdb/vendoradd", $data);
        echo $html;
    }
    /**
     * This controller function is used to save vendor data in database.
     * @author Kavita Daware
     * @access public
     * @package vendor
     * @param string $vendor_id
     * @return json
     */
    public function vendoraddsubmit(Request $request)
    {
        $request1 = $request->all();

        // 
        
        if ($request['is_gstnumber_reg'] == "Yes") {
            if (isset($_FILES['vendor_gst_no_file'])  && $_FILES['vendor_gst_no_file']['name'] != "") {
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
        if (isset($_FILES['vendor_pan_file']) && $_FILES['vendor_pan_file']['name'] != "") {
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
        if (isset($_FILES['bank_name_file']) && $_FILES['bank_name_file']['name'] != "") {
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
        // Vendor MSME Certificate
	
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

        
        $data = $this->itam->addvendor(['form_params' => $request1]);
        
        echo json_encode($data, true);
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
        $option = [];
        $data['citemplates'] = $this->itam->getciitems($option);        
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
        $request1 = $request->all();
        // 
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
        }
        // 
        // $data = $this->itam->updatevendor(array('form_params' => $request->all()));
        $data = $this->itam->updatevendor(['form_params' => $request1]);
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

    public function vendor_view($vendor_id) 
    {
        $data                   = [];
        $input_req              = ['vendor_id' => $vendor_id];
        $result                 = $this->itam->viewvendor(['form_params' => $input_req]);
        $data['vendor_id']      = $vendor_id;
        $data['vendordata']     = $result['content'];
        $data['pageTitle']      = 'Vendor View';
        $data['includeView']    = view("Cmdb/vendordetails", $data);
        return view('template', $data);
    }
    public function vendor_approvereject (Request $request) 
    {
        $response                       = [];
        $inputdata                      = $request->all();
        $postData['created_by']         = showuserid();
        $postData['created_by_name']    = showusername();
        $postData["vendor_id"]          = _isset($inputdata, 'vendor_id', "");
        $postData["comment"]            = _isset($inputdata, 'comment', "");
        $postData["approval_status"]    = _isset($inputdata, 'approval_status', "");

        $data   = $this->itam->approvereject_vendor(['form_params' => $postData]);
        $response["html"]       = $data['content'];
        $response["is_error"]   = $data['is_error'];
        $response["msg"]        = $data['msg'];
        return json_encode($response);
    }

     public function get_vendor_docs()
    {
        try {
            $attach_id    = _isset($this->request_params, 'attach_id');
            $attach_path  = _isset($this->request_params, 'attach_path');
            $attach_title = _isset($this->request_params, 'attach_title');

            $msg          = "";
            $content      = "";
            $extention    = "txt";
            $is_error     = false;
            $file_created = false;
            $user_id      = showuserid();
            // $download_fp  = public_path() . '/download/temp/' . $attach_title . '' . $user_id;
            $download_dir = public_path() . '/download/temp';
            $download_fp  = public_path() . '/download/temp/tmp_' . $user_id;
            $user_down_fp = 'download/temp/tmp_' . $user_id;

            $form_params['attach_id']   = $attach_id;
            $form_params['attach_path'] = $attach_path;
            $options                    = ['form_params' => $form_params];

            
            $response = $this->itam->download_vendorattachment($options);
            
            $get_data = _isset($response, 'content');
            $get_data = base64_decode($get_data, true);

            if ($attach_path != '') {
                $arr = explode('.', $attach_path);
                if (count($arr) > 1) {
                    $extention = $arr[1];
                }

            }

            //check folder exists or not, if not exist then create it.
            if (!file_exists($download_dir)) {
                mkdir($download_dir, 0777, true);
            }

            $fp = $download_fp . '.' . $extention;
            if ($get_data != false) {
                $file_created = file_put_contents($fp, $get_data);
            }
            //return false if failed

            if ($file_created == false) {
                $response["html"]     = '';
                $response["is_error"] = true;
                $response["msg"]      = 'error';
            } else {
                $response["html"]     = $user_down_fp . '.' . $extention;
                $response["is_error"] = '';
                $response["msg"]      = 'success';
            }

        } catch (\Exception $e) {
            $response["html"]     = '';
            $response["is_error"] = true;
            $response["msg"]      = $e->getmessage();
            save_errlog("get_vendor_docs", "This controller function is implemented to download vendor attachment.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {
            $response["html"]     = '';
            $response["is_error"] = true;
            $response["msg"]      = $e->getmessage();
            save_errlog("get_vendor_docs", "This controller function is implemented to download vendor attachment.", $this->request_params, $e->getmessage());
        } finally {
            echo json_encode($response);
        }
    }


}
