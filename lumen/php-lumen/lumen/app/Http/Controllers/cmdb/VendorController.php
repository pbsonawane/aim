<?php
namespace App\Http\Controllers\cmdb;

use App\Http\Controllers\Controller;
use App\Models\EnVendors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class VendorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        DB::connection()->enableQueryLog();
    }

    /*
     *This is controller funtion used for Vendors.

     * @author       Amit Khairnar
     * @access       public
     * @param        URL : vendor_id
     * @param_type   integer
     * @return       JSON
     * @tables       en_ci_vendors
     */

    public function getvendorsinquotation(Request $request)
    {
        $pr_po_id = $request->input('pr_po_id'); 
        $vendorIds = array();
        // $result = DB::select( DB::raw("SELECT json_unquote(JSON_KEYS(quotation_comparison_data)) as VendorId FROM 
        // `en_ci_quotation_comparison` WHERE pr_po_id = uuid_to_bin('$pr_po_id');") );

        $result = DB::select( DB::raw("SELECT GROUP_CONCAT(json_unquote(JSON_KEYS(quotation_comparison_data))) as VendorId FROM 
        `en_ci_quotation_comparison` WHERE pr_po_id = uuid_to_bin('$pr_po_id');") );
        $data['data'] = $result;
        $data['message']['success'] = 'Record fetch';
        $data['status'] = 'success';
        return response()->json($data);
    }
    public function vendors(Request $request, $vendor_id = null)
    {

        $requset['vendor_id'] = $vendor_id;
        $validator            = Validator::make($request->all(), [
            'vendor_id' => 'nullable|allow_uuid|string|size:36',
        ]);
        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
            return response()->json($data);
        } else {

            $inputdata                  = $request->all();
            $inputdata['searchkeyword'] = trim(_isset($inputdata, 'searchkeyword'));
            $totalrecords               = EnVendors::getvendors($vendor_id, $inputdata, true);
            $result                     = EnVendors::getvendors($vendor_id, $inputdata, false);

            $data['data']['records']      = $result->isEmpty() ? null : $result;
            $data['data']['totalrecords'] = $totalrecords;

            if ($totalrecords < 0) {
                $data['message']['error'] = showmessage('102', array('{name}'), array('Vendor'));
                $data['status']           = 'error';
            } else {
                $data['message']['success'] = showmessage('101', array('{name}'), array('Vendor'));
                $data['status']             = 'success';
            }
            return response()->json($data);
        }
    }

    public function getvendorbyservices(Request $request)
    {
        $inputdata                  = $request->all();
        $search_service = $request->input('search_service');
        $inputdata['searchkeyword'] = trim(_isset($inputdata, 'searchkeyword'));
        
        $query                     = DB::table('en_ci_vendors')   
                                    ->select(
                                        DB::raw('BIN_TO_UUID(vendor_id) AS vendor_id'),
                                        'vendor_unique_id',
                                        'vendor_name', 
                                        'vendor_ref_id', 
                                        'vendor_email', 
                                        'contact_person', 
                                        'contactno',
                                        'address', 
                                        'city', 
                                        'pincode', 
                                        'warehouse_location', 
                                        'vendor_gst_no', 
                                        'vendor_pan', 
                                        'bank_name', 
                                        'vendor_gst_no_file', 
                                        'vendor_pan_file',        
                                        'is_msme_reg',
                                        'meme_reg_num',
                                        'products_services_offered',
                                        'associate_oem',
                                        'delivery_time',
                                        'payment_terms',
                                        'annual_turnover',
                                        'known_client',
                                        'bank_address', 
                                        'bank_branch', 
                                        'bank_account_no', 
                                        'ifsc_code', 
                                        'micr_code', 
                                        'account_type',        
                                        'director_name',
                                        'director_contact_no',        
                                        'director_email',
                                        'sales_officer_name',
                                        'sales_officer_contact_no',
                                        'sales_officer_email',
                                        'account_officer_name',
                                        'account_officer_contact_no',
                                        'account_officer_email',
                                        'any_legal_notices',
                                        'legal_notice_elaborate',        
                                        'is_legal_requirements',
                                        'worker_minimum_age',
                                        'submit_original_documents',
                                        'any_serious_incidents',
                                        'elaborate_serious_incidents',
                                        'is_anti_bribe_policy',
                                        'is_health_safety_policy',
                                        'is_env_regulation',
                                        'elaborate_env_regulation',
                                        'name',
                                        'date',
                                        'designation',
                                        'status',
                                        'approve_status',
                                        'vendors_assets')             
                                        ->where('status', '!=', 'd');
                                        $num=0;
                if(!empty($search_service))
                {
                    foreach($search_service as $service)
                    {
                        if($num==0)
                        {
                            $query->Where('products_services_offered', 'like', '%' . $service . '%');
                            $num++;
                        }else{
                            $query->orWhere('products_services_offered', 'like', '%' . $service . '%');
                            $num++;
                        }
                    }
                }
                $result = $query->get();

                                        
        $totalrecords               = count($result);
        $data['data']['records']      = $result->isEmpty() ? null : $result;
        $data['data']['totalrecords'] = $totalrecords;

        if ($totalrecords < 0) {
            $data['message']['error'] = showmessage('102', array('{name}'), array('Vendor'));
            $data['status']           = 'error';
        } else {
            $data['message']['success'] = showmessage('101', array('{name}'), array('Vendor'));
            $data['status']             = 'success';
        }
        return response()->json($data); 
    }

    public function getvendorservices(Request $request)
    {
        $result = DB::select( DB::raw("SELECT GROUP_CONCAT(DISTINCT products_services_offered ORDER BY products_services_offered ASC) as VendorServices FROM en_ci_vendors order by products_services_offered"));

        $data['data'] = $result;
        $data['message']['success'] = 'Record fetch';
        $data['status'] = 'success';
        return response()->json($data);
    }

    /*
     * This is controller funtion used to accept the values for new Department. This function is called when user enters new values for department and submits that form.

     * @author       Amit Khairnar
     * @access       public
     * @param        vendor_name,vendor_ref_id, contact_person, contactno, address, status
     * @param_type   POST array
     * @return       JSON
     * @tables       en_ci_vendors
     */
    public function vendoradd(Request $request)
    {       
        // $data['data'] = $request->all();
        // $data['status'] = 'success';
        // $data['message']['success'] = 'success';
        // return  response()->json($data);
        $messages = [
            'vendor_name.required'                 => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_name.allow_alpha_space_only'   => showmessage('009', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            /*'vendor_name.composite_unique'         => showmessage('006', array('{name}'), array(trans('label.lbl_vendor_name')), true),*/
            'vendor_name.html_tags_not_allowed'    => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_ref_id.html_tags_not_allowed'  => showmessage('001', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_name.required'                 => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_ref_id.html_tags_not_allowed'  => showmessage('001', array('{name}'), array(trans('label.lbl_vendor_reference')), true),
            'contact_person.required'              => showmessage('000', array('{name}'), array(trans('label.lbl_contact_person')), true),
            'contact_person.html_tags_not_allowed' => showmessage('001', array('{name}'), array(trans('label.lbl_contact_person')), true),
            'contactno.required'                   => showmessage('000', array('{name}'), array(trans('label.lbl_contact_no')), true),
            'contactno.digits'                     => showmessage(trans('msg_contactno_10digit')),
            'address.required'                     => showmessage('000', array('{name}'), array(trans('label.lbl_address')), true),
            'address.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_address')), true),
            'bank_address.required'                     => showmessage('000', array('{name}'), array(trans('label.lbl_bank_address')), true),
            'bank_address.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_bank_address')), true),
            'bank_name.required'                     => showmessage('000', array('{name}'), array(trans('label.lbl_bank_name')), true),
            'bank_name.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_bank_name')), true),
            'bank_name.allow_alpha_space_only'        => showmessage('001', array('{name}'), array(trans('label.lbl_bank_name')), true),
            'bank_address.required'                     => showmessage('000', array('{name}'), array(trans('label.lbl_bank_address')), true),
            'bank_address.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_bank_address')), true),
            'bank_branch.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_bank_branch')), true),
            'bank_branch.required'        => showmessage('000', array('{name}'), array(trans('label.lbl_bank_branch')), true),
            'bank_branch.allow_alpha_space_only'        => showmessage('001', array('{name}'), array(trans('label.lbl_bank_branch')), true),
            'bank_account_no.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_bank_account_no')), true),
            'bank_account_no.required'        => showmessage('000', array('{name}'), array(trans('label.lbl_bank_account_no')), true),
            'bank_account_no.numeric'        => showmessage('025', array('{name}'), array(trans('label.lbl_bank_account_no')), true),
            'bank_account_no.composite_unique'        => showmessage('004', array('{name}'), array(trans('label.lbl_bank_account_no')), true),
            'ifsc_code.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_ifsc_code')), true),
            'ifsc_code.required'        => showmessage('000', array('{name}'), array(trans('label.lbl_ifsc_code')), true),
            'micr_code.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_micr_code')), true),
            'micr_code.required'        => showmessage('000', array('{name}'), array(trans('label.lbl_micr_code')), true),
            'micr_code.numeric'        => showmessage('025', array('{name}'), array(trans('label.lbl_micr_code')), true),
            'account_type.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_account_type')), true),
            'account_type.required'        => showmessage('000', array('{name}'), array(trans('label.lbl_account_type')), true),
            'vendor_email.required'        => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_email')), true),
            'vendor_email.email'        => showmessage('127', array('{name}'), array(trans('label.lbl_vendor_email')), true),
            'vendor_email.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_vendor_email')), true),
            'vendor_gst_no.required'        => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_gst_no')), true),
            'vendor_gst_no.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_vendor_gst_no')), true),
            'vendor_pan.required'        => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_pan')), true),
            'vendor_pan.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_vendor_pan')), true),            
        ];
        $validator = Validator::make($request->all(), [
            'vendor_id'      => 'nullable|allow_uuid|string|size:36',
            'vendor_name'    => 'required|allow_alpha_space_only|html_tags_not_allowed|',
    
            'vendor_ref_id'  => 'html_tags_not_allowed',
            'contact_person' => 'required|html_tags_not_allowed',
            'contactno'      => 'required|digits:10|composite_unique:en_ci_vendors, contactno, ' . $request->input('contact_person'),
            'address'        => 'required|html_tags_not_allowed',
            'bank_name'        => 'required|html_tags_not_allowed|allow_alpha_space_only',
            'bank_address'        => 'required|html_tags_not_allowed',
            'bank_branch'        => 'required|html_tags_not_allowed|allow_alpha_space_only',
            'bank_account_no'        => 'required|html_tags_not_allowed|numeric|composite_unique:en_ci_vendors, bank_account_no, ' . $request->input('bank_account_no'),
            'ifsc_code'        => 'required|html_tags_not_allowed',
            'micr_code'        => 'required|html_tags_not_allowed|numeric',
            'account_type'        => 'required|html_tags_not_allowed',
            'vendor_email'        => 'required|email',
            'vendor_gst_no'        => 'required|html_tags_not_allowed',
            'vendor_pan'        => 'required|html_tags_not_allowed',            
        ], $messages);
        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
        } else {
           
            // $vend_assets = $request->only('vendors_assets');
            $test = $request->all();

            $actual_path  = 'uploads/purchase/';
            $target_dir   = public_path($actual_path);
            // header('Content-Type: application/json');
            $test['bank_name_file'] = "";
            $test['vendor_pan_file'] = "";
            $test['vendor_gst_no_file'] = "";
            $test['msme_certificate'] = "";
           
            
            if($request['saveimg_bank_name']!="")
            {
                $saveimg      = $request['saveimg_bank_name'];
                $file_dir     = $target_dir . "/" . $saveimg;
                $decoded_file = base64_decode($request['files_content_bank_name']); // decode the file
                if (file_put_contents($file_dir, $decoded_file)) {
                     $test['bank_name_file'] = $actual_path . $saveimg;
                }
            }

            if($request['is_msme_reg'] == "Yes")
            {
                if($request['saveimg_msme_certificate']!="")
                {
                    $saveimg      = $request['saveimg_msme_certificate'];
                    $file_dir     = $target_dir . "/" . $saveimg;
                    $decoded_file = base64_decode($request['files_content_msme_certificate']); // decode the file
                    if (file_put_contents($file_dir, $decoded_file)) {
                         $test['msme_certificate'] = $actual_path . $saveimg;
                    }
                }
            }else{
                $test['msme_certificate'] = "";
            }
           
            if($request['saveimg_pan']!="")
            {
                $saveimg      = $request['saveimg_pan'];
                $file_dir     = $target_dir . "/" . $saveimg;
                $decoded_file = base64_decode($request['files_content_pan']); // decode the file
                if (file_put_contents($file_dir, $decoded_file)) {
                     $test['vendor_pan_file'] = $actual_path . $saveimg;
                }
            }

            if($request['is_gstnumber_reg'] == "Yes")
            {
                if($request['saveimg_gst_no']!="")
                {
                    $saveimg      = $request['saveimg_gst_no'];
                    $file_dir     = $target_dir . "/" . $saveimg;
                    $decoded_file = base64_decode($request['files_content_gst_no']); // decode the file
                    if (file_put_contents($file_dir, $decoded_file)) {
                         $test['vendor_gst_no_file'] = $actual_path . $saveimg;
                    }
                }
            }else{
                $test['vendor_gst_no_file'] = "";
            }
            // $test['vendors_assets'] = json_encode($vend_assets);
            
            
            
            $vendor_data = EnVendors::create($test);
            if (!empty($vendor_data['vendor_id'])) {
                $vendor_id                  = $vendor_data->vendor_id_text;
                $data['data']['insert_id']  = $vendor_id;
                $data['message']['success'] = showmessage('104', array('{name}'), array('Vendor'));
                $data['status']             = 'success';
                //Add into UserActivityLog
                // userlog(array('record_id' => $vendor_data->vendor_id_text, 'data' => $request->all(), 'action' => 'added', 'message' => showmessage('104', array('{name}'),array('Vendor'))));
            } else {
                $data['data']             = null;
                $data['message']['error'] = showmessage('103', array('{name}'), array('Vendor'));
                $data['status']           = 'error';
            }
        }
        return response()->json($data);
        
        /*$messages = [
            'vendor_name.required'                 => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_name.allow_alpha_space_only'   => showmessage('009', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_name.composite_unique'         => showmessage('006', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_name.html_tags_not_allowed'    => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_ref_id.html_tags_not_allowed'  => showmessage('001', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_name.required'                 => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_ref_id.html_tags_not_allowed'  => showmessage('001', array('{name}'), array(trans('label.lbl_vendor_reference')), true),
            'contact_person.required'              => showmessage('000', array('{name}'), array(trans('label.lbl_contact_person')), true),
            'contact_person.html_tags_not_allowed' => showmessage('001', array('{name}'), array(trans('label.lbl_contact_person')), true),
            'contactno.required'                   => showmessage('000', array('{name}'), array(trans('label.lbl_contact_no')), true),
            'contactno.digits'                     => showmessage(trans('msg_contactno_10digit')),
            'address.required'                     => showmessage('000', array('{name}'), array(trans('label.lbl_address')), true),
            'address.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_address')), true),

        ];
        $validator = Validator::make($request->all(), [
            'vendor_id'      => 'nullable|allow_uuid|string|size:36',
            'vendor_name'    => 'required|allow_alpha_space_only|html_tags_not_allowed|composite_unique:en_ci_vendors, vendor_name, ' . $request->input('vendor_name'),
            'vendor_ref_id'  => 'html_tags_not_allowed',
            'contact_person' => 'required|html_tags_not_allowed',
            'contactno'      => 'required|digits:10',
            'address'        => 'required|html_tags_not_allowed',
        ], $messages);
        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
        } else {
            $vendor_data = EnVendors::create($request->all());
            if (!empty($vendor_data['vendor_id'])) {
                $vendor_id                  = $vendor_data->vendor_id_text;
                $data['data']['insert_id']  = $vendor_id;
                $data['message']['success'] = showmessage('104', array('{name}'), array('Vendor'));
                $data['status']             = 'success';
                //Add into UserActivityLog
                // userlog(array('record_id' => $vendor_data->vendor_id_text, 'data' => $request->all(), 'action' => 'added', 'message' => showmessage('104', array('{name}'),array('Vendor'))));
            } else {
                $data['data']             = null;
                $data['message']['error'] = showmessage('103', array('{name}'), array('Vendor'));
                $data['status']           = 'error';
            }
        }
        return response()->json($data);*/
    }

    /* Provides a window to user to update the vendor information.

     * @author       Amit Khairnar
     * @access       public
     * @param        URL : vendor_id
     * @param_type   Integer
     * @return       JSON
     * @tables       en_ci_vendors
     */
    public function vendoredit(Request $request, $vendor_id = null)
    {
        //$request['vendor_id'] = $vendor_id;
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|allow_uuid|string|size:36',
        ]);
        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
        } else {
            $result = EnVendors::getvendors($request->input('vendor_id'));

            $data['data'] = $result->isEmpty() ? null : $result;

            if ($data['data']) {
                $data['message']['success'] = showmessage('102', array('{name}'), array('Vendor'));
                $data['status']             = 'success';
            } else {

                $data['message']['error'] = showmessage('101', array('{name}'), array('Vendor'));
                $data['status']           = 'error';
            }
        }
        return response()->json($data);
    }

    //===== designationedit END ===========
    /*
     * Updates the vendor information, which is entered by user on Edit vendor window.

     * @author       Amit Khairnar
     * @access       public
     * @param        vendor_name , vendor_ref_id, address, contact_person, contactno, status
     * @param_type   POST array
     * @return       JSON
     * @tables       en_ci_vendors
     */
    public function vendorupdate(Request $request)
    {
        $messages = [
            'vendor_name.required'                 => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_name.allow_alpha_space_only'   => showmessage('009', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            /*'vendor_name.composite_unique'         => showmessage('006', array('{name}'), array(trans('label.lbl_vendor_name')), true),*/
            'vendor_name.html_tags_not_allowed'    => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_ref_id.html_tags_not_allowed'  => showmessage('001', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_name.required'                 => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_ref_id.html_tags_not_allowed'  => showmessage('001', array('{name}'), array(trans('label.lbl_vendor_reference')), true),
            'contact_person.required'              => showmessage('000', array('{name}'), array(trans('label.lbl_contact_person')), true),
            'contact_person.html_tags_not_allowed' => showmessage('001', array('{name}'), array(trans('label.lbl_contact_person')), true),
            'contactno.required'                   => showmessage('000', array('{name}'), array(trans('label.lbl_contact_no')), true),
            'contactno.digits'                     => showmessage(trans('msg_contactno_10digit')),
            'address.required'                     => showmessage('000', array('{name}'), array(trans('label.lbl_address')), true),
            'address.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_address')), true),
            'bank_address.required'                     => showmessage('000', array('{name}'), array(trans('label.lbl_bank_address')), true),
            'bank_address.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_bank_address')), true),
            'bank_name.required'                     => showmessage('000', array('{name}'), array(trans('label.lbl_bank_name')), true),
            'bank_name.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_bank_name')), true),
            'bank_name.allow_alpha_space_only'        => showmessage('001', array('{name}'), array(trans('label.lbl_bank_name')), true),
            'bank_address.required'                     => showmessage('000', array('{name}'), array(trans('label.lbl_bank_address')), true),
            'bank_address.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_bank_address')), true),
            'bank_branch.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_bank_branch')), true),
            'bank_branch.required'        => showmessage('000', array('{name}'), array(trans('label.lbl_bank_branch')), true),
            'bank_branch.allow_alpha_space_only'        => showmessage('001', array('{name}'), array(trans('label.lbl_bank_branch')), true),
            'bank_account_no.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_bank_account_no')), true),
            'bank_account_no.required'        => showmessage('000', array('{name}'), array(trans('label.lbl_bank_account_no')), true),
            'bank_account_no.numeric'        => showmessage('025', array('{name}'), array(trans('label.lbl_bank_account_no')), true),
            'bank_account_no.composite_unique'        => showmessage('004', array('{name}'), array(trans('label.lbl_bank_account_no')), true),
            'ifsc_code.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_ifsc_code')), true),
            'ifsc_code.required'        => showmessage('000', array('{name}'), array(trans('label.lbl_ifsc_code')), true),
            'micr_code.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_micr_code')), true),
            'micr_code.required'        => showmessage('000', array('{name}'), array(trans('label.lbl_micr_code')), true),
            'micr_code.numeric'        => showmessage('025', array('{name}'), array(trans('label.lbl_micr_code')), true),
            'account_type.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_account_type')), true),
            'account_type.required'        => showmessage('000', array('{name}'), array(trans('label.lbl_account_type')), true),
            'vendor_email.required'        => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_email')), true),
            'vendor_email.email'        => showmessage('127', array('{name}'), array(trans('label.lbl_vendor_email')), true),
            'vendor_email.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_vendor_email')), true),
            'vendor_gst_no.required'        => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_gst_no')), true),
            'vendor_gst_no.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_vendor_gst_no')), true),
            'vendor_pan.required'        => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_pan')), true),
            'vendor_pan.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_vendor_pan')), true),                       
        ];
        $validator = Validator::make($request->all(), [
            'vendor_id'      => 'nullable|allow_uuid|string|size:36',
            'vendor_name'    => 'required|allow_alpha_space_only|html_tags_not_allowed|',
            'vendor_ref_id'  => 'html_tags_not_allowed',
            'contact_person' => 'required|html_tags_not_allowed',
            'contactno'      => 'required|digits:10',
            'address'        => 'required|html_tags_not_allowed',
            'bank_name'        => 'required|html_tags_not_allowed|allow_alpha_space_only',
            'bank_address'        => 'required|html_tags_not_allowed',
            'bank_branch'        => 'required|html_tags_not_allowed|allow_alpha_space_only',
            'bank_account_no'        => 'required|html_tags_not_allowed|numeric',
            'ifsc_code'        => 'required|html_tags_not_allowed',
            'micr_code'        => 'required|html_tags_not_allowed|numeric',
            'account_type'        => 'required|html_tags_not_allowed',
            'vendor_email'        => 'required|email',
            'vendor_gst_no'        => 'required|html_tags_not_allowed',
            'vendor_pan'        => 'required|html_tags_not_allowed',
        ], $messages);

        /*$vendor_id_bin = DB::raw('UUID_TO_BIN("' . $request->input('vendor_id') . '")');
        $messages      = [
            'vendor_name.required'                 => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_name.allow_alpha_space_only'   => showmessage('009', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_name.composite_unique'         => showmessage('006', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_name.html_tags_not_allowed'    => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_ref_id.html_tags_not_allowed'  => showmessage('001', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_name.required'                 => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_ref_id.html_tags_not_allowed'  => showmessage('001', array('{name}'), array(trans('label.lbl_vendor_reference')), true),
            'contact_person.required'              => showmessage('000', array('{name}'), array(trans('label.lbl_contact_person')), true),
            'contact_person.html_tags_not_allowed' => showmessage('001', array('{name}'), array(trans('label.lbl_contact_person')), true),
            'contactno.required'                   => showmessage('000', array('{name}'), array(trans('label.lbl_contact_no')), true),
            'contactno.digits'                     => showmessage(trans('msg_contactno_10digit')),
            'address.required'                     => showmessage('000', array('{name}'), array(trans('label.lbl_address')), true),
            'address.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_address')), true),

        ];

        $validator = Validator::make($request->all(), [
            'vendor_id'      => 'required|allow_uuid|string|size:36',
            'vendor_name'    => 'required|allow_alpha_space_only|html_tags_not_allowed|composite_unique:en_ci_vendors, vendor_name, ' . $request->input('vendor_name') . ', vendor_id,' . $request->input('vendor_id'),
            'vendor_ref_id'  => 'html_tags_not_allowed',
            'contact_person' => 'required|html_tags_not_allowed',
            'contactno'      => 'required|digits:10',
            'address'        => 'required|html_tags_not_allowed',
            //'status' => 'required|in:y,n,d'
        ], $messages);*/
        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
        } else {
            $vendor_id_uuid       = $request->input('vendor_id');
            $vendor_id_bin        = DB::raw('UUID_TO_BIN("' . $request->input('vendor_id') . '")');
            $request['vendor_id'] = DB::raw('UUID_TO_BIN("' . $request->input('vendor_id') . '")');
            $result               = EnVendors::where('vendor_id', $vendor_id_bin)->first();

            if ($result) {
                $vend_assets = $request->only('vendors_assets');
                $test = $request->all();
                //
                if($request['saveimg_bank_name'])
                {
                    $saveimg      = $request['saveimg_bank_name'];
                    $file_dir     = $target_dir . "/" . $saveimg;
                    $decoded_file = base64_decode($request['files_content_bank_name']); // decode the file
                    if (file_put_contents($file_dir, $decoded_file)) {
                        $test['bank_name_file'] = $actual_path . $saveimg;
                    }
                }else{
                    $test['bank_name_file'] = $test['bank_name_file_url'];
                }
                if($request['saveimg_pan'])
                {
                    $saveimg      = $request['saveimg_pan'];
                    $file_dir     = $target_dir . "/" . $saveimg;
                    $decoded_file = base64_decode($request['files_content_pan']); // decode the file
                    if (file_put_contents($file_dir, $decoded_file)) {
                        $test['vendor_pan_file'] = $actual_path . $saveimg;
                    }
                }else{
                    $test['vendor_pan_file'] = $test['vendor_pan_file_url'];
                }
                if($request['saveimg_gst_no'])
                {
                    $saveimg      = $request['saveimg_gst_no'];
                    $file_dir     = $target_dir . "/" . $saveimg;
                    $decoded_file = base64_decode($request['files_content_gst_no']); // decode the file
                    if (file_put_contents($file_dir, $decoded_file)) {
                        $test['vendor_gst_no_file'] = $actual_path . $saveimg;
                    }
                }else{
                    $test['vendor_gst_no_file'] = $test['vendor_gst_no_file_url'];
                }
                // 
                $test['vendors_assets'] = json_encode($vend_assets);
                $result->update($test);
                $result->save();
                $data['data']               = null;
                $data['message']['success'] = showmessage('106', array('{name}'), array('Vendor'));
                $data['status']             = 'success';
                // userlog(array('record_id' => $vendor_id_uuid, 'data' => $request->all(), 'action' => 'updated', 'message' => showmessage('106', array('{name}'),array('Designation'))));

            } else {
                $data['data']             = null;
                $data['message']['error'] = showmessage('101', array('{name}'), array('Vendor'));
                $data['status']           = 'error';
            }
        }
        return response()->json($data);
    }
    //===== Vendorupdate END ===========

    /* This is controller funtion used to delete the designation.

     * @author       Amit Khairnar
     * @access       public
     * @param        URL : vendor_id
     * @param_type   integer
     * @return       JSON
     * @tables       en_ci_vendors
     */

    public function vendordelete(Request $request, $vendor_id = null)
    {
        $request['vendor_id'] = $vendor_id;
        $validator            = Validator::make($request->all(), [
            'vendor_id' => 'required|allow_uuid|string|size:36',
        ]);
        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
            return response()->json($data);
        } else {
            $data = EnVendors::checkforrelation($vendor_id);
            //Add into UserActivityLog
            if ($data['data']) {
                //userlog(array('record_id' => $vendor_id, 'data' => $request->all(), 'action' => 'deleted', 'message' => 'Record Deleted Successfully'));
            }
            return response()->json($data);

        }
    }

    private function generate_vendor_id() {
        $max_vendor_id        =   DB::select(DB::raw("SELECT(MAX(CAST(SUBSTRING(vendor_unique_id,8,LENGTH(vendor_unique_id)) as UNSIGNED))) as max_vendor_id FROM `en_ci_vendors`") );
        $max_vendor_id    = json_decode(json_encode($max_vendor_id), true);

        if(!empty($max_vendor_id)) {
            $max_vendor_id    = $max_vendor_id[0]['max_vendor_id'] + 1;
            $app_vendor_id    = 'esds/v-' . str_pad($max_vendor_id, 5, 0, STR_PAD_LEFT);
        } else {
            $app_vendor_id    = 'esds/v-00001';
        }
        return $app_vendor_id;
    }


    public function approvereject_vendor(request $request)
    {   
        $data_array     = array();
        $post_data      = $request->all();

        $validator      = Validator::make($request->all(), [
            'vendor_id' => 'required|allow_uuid|string|size:36',
            'comment' => 'required',
            'approval_status' => 'required',
            'created_by' => 'required',
        ]);

        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = "Validation error";
            $data['message']['error'] = $error;
            $data['status']           = 'error';
        } else {  
            $vendor_data= EnVendors::where('vendor_id', DB::raw('UUID_TO_BIN("'.$post_data['vendor_id'].'")'))->where('status','!=','d')->first();
            if($vendor_data)
            {    
                $data_array = array (
                    "approval_status"  => $post_data['approval_status'],
                    "comment"          => $post_data['comment'],
                    "created_by"       => $post_data['created_by'],
                    "created_by_name"  => $post_data['created_by_name'],
                    "created_at"       => date('Y-m-d h:i:s')
                );

                 DB::enableQueryLog();
                //create unique vendor id if approval status is approve
                if($post_data['approval_status'] == 'approve') {
                    $vendor_unique_id  = self::generate_vendor_id();
                    $result     =   DB::table('en_ci_vendors')
                                    ->where('vendor_id', DB::raw('UUID_TO_BIN("'.$post_data['vendor_id'].'")'))
                                    ->update(array('vendor_unique_id'=> $vendor_unique_id, 'approve_status' => json_encode($data_array)));
                } else {
                    $result     =   DB::table('en_ci_vendors')
                                    ->where('vendor_id', DB::raw('UUID_TO_BIN("'.$post_data['vendor_id'].'")'))
                                    ->update(array('approve_status'=> json_encode($data_array) ));
                }
                if($result) {
                    $data['data']               = 'success';
                    $data['message']['success'] = 'Vendor updated Successfully';
                    $data['status']             = 'success';    
                } else {
                    $data['data']               = 'error';
                    $data['message']['error']   = 'Vendor updated failure';
                    $data['status']             = 'error';
                }
                
            } else {
                $data['data']               = 'error';
                $data['message']['error']   = 'Vendor details not found.';
                $data['status']             = 'error';                             
            }               
        }
        return response()->json($data);
    }

    public function download_vendorattachment(Request $request)
    {
        $inputdata  = $request->all();
        $res        = '';
        $messages   = [
            'attach_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_attachmentid')), true),
            'attach_path.required' => showmessage('000', array('{name}'), array(trans('label.lbl_attachmentpath')), true),
        ];
        $validator = Validator::make($request->all(), [
            'attach_id' => 'required|allow_uuid|string|size:36',
            'attach_path' => 'required',
        ], $messages);
        if ($validator->fails()) {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        } else {
            $filepath = public_path() . '/' . $inputdata['attach_path'];
            if (file_exists($filepath)) {
                $res = (file_get_contents($filepath));
            } else {
                $res = '';
            }
           
            if ($res == '') {
                $data['status'] = 'error';
                $data['message']['error'] = showmessage('102', array('{name}'), array(trans('label.lbl_attachment')));

            } else {
                $data['status'] = 'success';
                $data['message']['success'] = showmessage('101', array('{name}'), array(trans('label.lbl_attachment')));
            }
            $data['data'] = base64_encode($res);
            return response()->json($data);
        }
    }



} // Class End

