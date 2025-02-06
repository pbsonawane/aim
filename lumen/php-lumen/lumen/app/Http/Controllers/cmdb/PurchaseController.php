<?php
namespace App\Http\Controllers\cmdb;

use App\Http\Controllers\Controller;
use App\Libraries\Maillib;
use App\Models\EnAssets;
use App\Models\EnBillTo;
use App\Models\EnCiTemplCustom;
use App\Models\EnCiTemplDefault;
use App\Models\EnContacts;
use App\Models\EnDelivery;
use App\Models\EnInvoice;
use App\Models\EnoppListing;
use App\Models\EnPaymentterms;
use App\Models\EnPrPoAssetDetails;
use App\Models\EnPrPoAssetDetailssample;
use App\Models\EnPrPoAttachment;
use App\Models\EnPrPoHistory;
use App\Models\EnPrPoQuotationcomparison;
use App\Models\EnPrPoQuotationcomparisonReject;
use App\Models\Enprsample;
use App\Models\EnPurchaseOrder;
use App\Models\EnPurchaseRequest;
use App\Models\EnPurchaseRequestsample;
use App\Models\EnRequesternames;
use App\Models\EnShipTo;
use App\Models\EnVendors;
use App\Models\EnComplaintRaised;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class PurchaseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @author       Vikas Kumar
     * @access       public
     * @package purchaseorder
     * @return void
     */
    public function __construct()
    {
        DB::connection()->enableQueryLog();
    }
    /**
     * This is controller funtion used to List purchase request.
     * @author       Vikas Kumar
     * @access       public
     * @param        URL : pr_id [Optional]
     * @param_type   Integer
     * @return       JSON
     * @tables       en_form_data_pr
     */

    public function getProject(Request $request)
    {
        $opp_id = $request["opp_id"];
        // $url= 'https://115.124.96.115:4108/uat/get_project_names.php';
        $url= 'https://115.124.96.115:4108/production/get_project_names.php';
		$url= 'https://swayatta.esds.co.in:31199/uat/get_project_names.php';
		
        $data = array(
            "opp_id"  => $opp_id
            );
        $respo = $this->call_rest_api($url,$data);
        $data['data'] = $respo;
        $data['status'] = 'success';
        $data['message']['success'] = 'success';
        return $data;
    }

    public function purchaserequests(Request $request, $pr_id = null)
    {
        // $data['data'] = $request->all();
        // $data['status'] = 'success';
        // $data['message']['success'] = 'success';
        // return response()->json($data);
        try
        {
            $request['pr_id'] = $pr_id;
            $validator = Validator::make($request->all(), [
                'pr_id' => 'nullable|allow_uuid|string|size:36',
            ]);
            if ($validator->fails()) {
                $error = $validator->errors();
                $data['data'] = null;
                $data['message']['error'] = $error;
                $data['status'] = 'error';
                return response()->json($data);
            } else {
                $inputdata = $request->all();
                if (!empty($inputdata['pr_po_ids'])) {
                    $result = EnPurchaseRequest::getAddresses($request['pr_po_ids']);
                    $data['data']['records'] = $result->isEmpty() ? null : $result;
                    $data['message']['success'] = '';

                } else {
                    // $data['data'] = $inputdata;                    
                    // $data['status'] = 'success';
                    // $data['message']['success'] = 'success';
                    // return response()->json($data);
                    $inputdata['searchkeyword'] = trim(_isset($inputdata, 'searchkeyword'));
                    $totalrecords = EnPurchaseRequest::getprs($pr_id, $inputdata, true);
                    $result = EnPurchaseRequest::getprs($pr_id, $inputdata, false);
                    // $data['data']['totalrecords'] = $totalrecords;
                    // $data['data']['result'] = $result;
                    // $data['status'] = 'success';
                    // $data['message']['success'] = 'success';
                    // return response()->json($data);
                    /* $queries    = DB::getQueryLog();
                    $data['last_query'] = end($queries);*/

                    foreach ($result as $key => $each_pr) {
                        $pr_details = $each_pr->details;
                        $pr_details_arr = json_to_array($pr_details);
                        $each_pr->details = $pr_details_arr;

                        //get vendor details
                        /*$pr_vendor = isset($pr_details_arr['pr_vendor']) ? $pr_details_arr['pr_vendor'] : "";
                        if ($pr_vendor) {
                        $vendor_details = EnVendors::getvendors($pr_vendor);
                        if ($vendor_details->isEmpty()) {
                        $each_pr->vendor_details = null;
                        } else {
                        $each_pr->vendor_details = $vendor_details[0];
                        }
                        } else {
                        $each_pr->vendor_details = array();
                        }*/

                        //get Bill To details
                        /*$pr_billto = isset($pr_details_arr['pr_billto']) ? $pr_details_arr['pr_billto'] : "";
                        if ($pr_billto) {
                        $billto_details = EnBillTo::getbilltos($pr_billto);
                        if ($billto_details->isEmpty()) {
                        $each_pr->billto_details = null;
                        } else {
                        $each_pr->billto_details = $billto_details[0];
                        }
                        } else {
                        $each_pr->billto_details = array();
                        }*/

                        //get Ship To details
                        $pr_shipto = isset($pr_details_arr['pr_shipto']) ? $pr_details_arr['pr_shipto'] : "";
                        if ($pr_shipto) {
                            $shipto_details = EnShipTo::getshiptos($pr_shipto);
                            if ($shipto_details->isEmpty()) {
                                $each_pr->shipto_details = null;
                            } else {
                                $each_pr->shipto_details = $shipto_details[0];
                            }
                        } else {
                            $each_pr->shipto_details = array();
                        }

                        //get Requester names details
                        $pr_requester_name = isset($pr_details_arr['pr_requester_name']) ? $pr_details_arr['pr_requester_name'] : "";
                        if ($pr_requester_name) {
                            $requester_name_details = EnRequesternames::getrequesternames($pr_requester_name);
                            if ($requester_name_details->isEmpty()) {
                                $each_pr->requester_name_details = null;
                            } else {
                                $each_pr->requester_name_details = $requester_name_details[0];
                            }
                        } else {
                            $each_pr->requester_name_details = array();
                        }

                        //get bill To Contact Details
                        /*$pr_billto_contact = isset($pr_details_arr['pr_billto_contact']) ? $pr_details_arr['pr_billto_contact'] : "";
                        if ($pr_billto_contact) {
                        $billto_contact_details = EnContacts::getcontacts_billto($pr_billto_contact);
                        if ($billto_contact_details->isEmpty()) {
                        $each_pr->billto_contact_details = null;
                        } else {
                        $each_pr->billto_contact_details = $billto_contact_details[0];
                        }
                        } else {
                        $each_pr->billto_contact_details = array();
                        }*/

                        //get Ship To Contact Details
                        $pr_shipto_contact = isset($pr_details_arr['pr_shipto_contact']) ? $pr_details_arr['pr_shipto_contact'] : "";
                        if ($pr_shipto_contact) {
                            $shipto_contact_details = EnContacts::getcontacts_shipto($pr_shipto_contact);
                            if ($shipto_contact_details->isEmpty()) {
                                $each_pr->shipto_contact_details = null;
                            } else {
                                $each_pr->shipto_contact_details = $shipto_contact_details[0];
                            }
                        } else {
                            $each_pr->shipto_contact_details = array();
                        }

                        //$resultPo = EnPurchaseOrder::select(DB::raw('BIN_TO_UUID(po_id) AS po_id'), DB::raw('JSON_UNQUOTE(JSON_EXTRACT(details,"$.pr_vendor")) as pr_vendor'),'status')->where(DB::raw('json_extract(`details`,"$.pr_id")'), 'LIKE', "%{$pr_id}%")->get();

                        $resultPo = EnPurchaseOrder::select(DB::raw('BIN_TO_UUID(po_id) AS po_id'), DB::raw('JSON_UNQUOTE(JSON_EXTRACT(details,"$.pr_vendor")) as pr_vendor'), 'status')->where(DB::raw('json_extract(`details`,"$.pr_id")'), 'LIKE', "%{$pr_id}%")->get();
                        //->where('status', '!=','rejected')

                        // $resultPo = EnPurchaseOrder::select(DB::raw('BIN_TO_UUID(po_id) AS po_id'),DB::raw('JSON_UNQUOTE(JSON_EXTRACT(details,"$.pr_vendor")) as pr_vendor'))->where('pr_id', DB::raw("UUID_TO_BIN('" . $each_pr->pr_id . "')"))->get();
                        if ($resultPo) {
                            $each_pr->already_po = json_encode($resultPo);
                        } else {
                            $each_pr->already_po = "";
                        }
                        /*' To Fetch Asset Name START */
                        /*$pr_details_asset = $each_pr->asset_details;
                        $pr_details_asset_arr = json_to_array($pr_details_asset);
                        $each_pr->asset_details = $pr_details_asset_arr;
                        //get Asset CI details
                        $ci_id = isset($pr_details_asset_arr['ci_name']) ? $pr_details_asset_arr['ci_name'] : "";*/

                        /* $prpoAssetDetails = EnPrPoAssetDetails::getPrPoAssetDetails($each_pr->pr_id, "pr");

                        if(!$prpoAssetDetails->isEmpty())
                        {
                        $ci_asset_detailsArr = array();
                        foreach($prpoAssetDetails as $asset)
                        {
                        $asset_arr  = json_decode($asset['asset_details'], true);
                        if(isset($asset_arr))
                        {
                        $ci_asset_details = EnCiTemplDefault::getcitemplatesD($asset_arr['item']);
                        if($ci_asset_details->isEmpty())
                        {
                        $each_pr->ci_asset_details = NULL;
                        }
                        else
                        {
                        $each_pr->ci_asset_details[$asset_arr['item']] = $ci_asset_details[0]->ci_name;
                        }
                        }
                        }
                        }
                        else
                        {
                        $each_pr->ci_asset_details = array();
                        }*/
                        /*if($ci_id)
                        {
                        $ci_asset_details = EnCiTemplDefault::getcitemplatesD($ci_id);
                        if($ci_asset_details->isEmpty())
                        {
                        $each_pr->ci_asset_details = NULL;
                        }
                        else
                        {
                        $each_pr->ci_asset_details = $ci_asset_details;
                        }
                        }
                        else
                        {
                        $each_pr->ci_asset_details = array();
                        }*/
                        $pr_details_approval = $each_pr->approval_details;
                        $pr_details_approval_arr = json_to_array($pr_details_approval);
                        $each_pr->approval_details = $pr_details_approval_arr;
                        /* To Fetch Asset Name END  */
                    }

                    $data['data']['records'] = $result->isEmpty() ? null : $result;
                    $data['data']['totalrecords'] = $totalrecords;

                    if ($totalrecords < 1) {
                        $data['message']['success'] = showmessage('102', array('{name}'), array(trans('label.lbl_purchaserequest')));
                    } else {
                        $data['message']['success'] = showmessage('101', array('{name}'), array(trans('label.lbl_purchaserequest')));
                    }
                }

                $data['status'] = 'success';
                return response()->json($data);
            }
        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("purchaserequests", "This controller function is implemented to show pr list.", $request->all(), $e->getMessage());
            return response()->json($data);
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("purchaserequests", "This controller function is implemented to show pr list.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }

    /**
     * Fetch Details of particular Purchase Request.
     * @author       Vikas Kumar
     * @access       public
     * @param        URL : pr_id
     * @param_type   Integer
     * @return       JSON
     * @tables       en_form_data_pr
     */
    public function prdetails(Request $request, $pr_id = null)
    {
        try
        {
            $request['pr_id'] = $pr_id;
            $validator = Validator::make($request->all(), [
                'pr_id' => 'required|allow_uuid|string|size:36',
            ]);
            if ($validator->fails()) {
                $error = $validator->errors();
                $data['data'] = null;
                $data['message']['error'] = $error;
                $data['status'] = 'error';
            } else {
                $result = EnPurchaseRequest::getprs($pr_id);

                foreach ($result as $key => $each_pr) {
                    $pr_details = $each_pr->details;
                    $pr_details_arr = json_to_array($pr_details);
                    $each_pr->details = $pr_details_arr;

                    $pr_details_asset = $each_pr->asset_details;
                    $pr_details_asset_arr = json_to_array($pr_details_asset);
                    $each_pr->asset_details = $pr_details_asset_arr;

                    $pr_details_approval = $each_pr->approval_details;
                    $pr_details_approval_arr = json_to_array($pr_details_approval);
                    $each_pr->approval_details = $pr_details_approval_arr;
                }

                $data['data'] = $result->isEmpty() ? null : $result;
                if ($data['data']) {
                    $data['message']['success'] = showmessage('102', array('{name}'), array(trans('label.lbl_purchaserequest')));
                    $data['status'] = 'success';
                } else {

                    $data['message']['error'] = showmessage('101', array('{name}'), array(trans('label.lbl_purchaserequest')));
                    $data['status'] = 'error';
                }
            }
        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("prdetails", "This controller function is implemented to fetch details of particular purchase request.", $request->all(), $e->getMessage());
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("prdetails", "This controller function is implemented to fetch details of particular purchase request.", $request->all(), $e->getMessage());
        } finally {
            return response()->json($data);
        }
    }
    /**
     * Fetch Details of particular Purchase Request.
     * @author       Vikas Kumar
     * @access       public
     * @param        URL : pr_id
     * @param_type   Integer
     * @return       JSON
     * @tables       en_form_data_pr
     */
    public function prconversionassetdetails(Request $request)
    {
        try
        {
            /*$validator        = Validator::make($request->all(), [
            'pr_id' => 'required|allow_uuid|string|size:36',
            ]);
            if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
            } else {*/

            $result = DB::table('en_pr_po_asset_details')
                ->join('en_form_data_pr', 'en_form_data_pr.pr_id', '=', 'en_pr_po_asset_details.pr_po_id')
                ->whereNotNull(DB::raw("JSON_EXTRACT(en_form_data_pr.approved_status, '$.confirmed')"))
                ->where('en_pr_po_asset_details.convert_status', '=', 'n')
            // ->where('en_pr_po_asset_details.assign_status', '=', 'n')
                ->where('en_form_data_pr.status', '=', 'approved')
                ->whereNull(DB::raw("JSON_EXTRACT(en_form_data_pr.approved_status, '$.convert_to_pr')"))
                ->select(DB::raw("BIN_TO_UUID(en_form_data_pr.pr_id) as pr_id"), DB::raw("JSON_ARRAYAGG(en_pr_po_asset_details.asset_details) as pritems"), DB::raw("JSON_UNQUOTE(JSON_EXTRACT(`details`, '$.pr_shipto')) as pr_shipto"), DB::raw("JSON_UNQUOTE(JSON_EXTRACT(`details`, '$.ship_to_other')) as ship_to_other"), 'pr_no')
                ->groupBy("en_pr_po_asset_details.pr_po_id")
                ->get();
            /*$queries    = DB::getQueryLog();
            $data['data'] = end($queries);*/

            $data['data'] = $result->isEmpty() ? null : $result;
            if ($data['data']) {
                $data['message']['success'] = showmessage('102', array('{name}'), array(trans('label.lbl_purchaserequest')));
                $data['status'] = 'success';
            } else {

                $data['message']['error'] = showmessage('101', array('{name}'), array(trans('label.lbl_purchaserequest')));
                $data['status'] = 'error';
            }
            //}
        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("prdetails", "This controller function is implemented to fetch details of particular purchase request.", $request->all(), $e->getMessage());
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("prdetails", "This controller function is implemented to fetch details of particular purchase request.", $request->all(), $e->getMessage());
        } finally {
            return response()->json($data);
        }
    }

    /**
     * This is controller funtion used to accept the values to add/update new Purchase Request & Add Purchase Order.
     * @author       Namrata Thakur
     * @access       public
     * @param
     * @param_type   POST array
     * @return       JSON
     * @tables       en_form_data_pr
     */

    public function complaintraisedDetail(Request $request)
    {
        $result = DB::table('en_complaint_raised')
        ->select(
            'cr_id','complaint_raised_no','complaint_raised_date',
            DB::raw('BIN_TO_UUID(user_id) AS user_id'), 
            DB::raw('BIN_TO_UUID(requester_id) AS requester_id'), 
            DB::raw('BIN_TO_UUID(asset_id) AS asset_id'), 
            'priority', 'problemdetail', 'attachment', 
            DB::raw('BIN_TO_UUID(hod_id) AS hod_id'),
            'hod_remark', 'hod_status', 'itfile', 'itstatus', 'it_remark', 'it_status',
            DB::raw('BIN_TO_UUID(vendor_id) AS vendor_id'),'storefile',
            'store_remark', 'store_status', 'status', 'created_at', 'updated_at'
            )
        ->where('cr_id', '=',$request['cr_id'])
        ->get();
        

        //return result
        $data['data'] = $result;
        $data['status'] = 'success';
        $data['message']['success'] = 'success';
        return $data;
    }

    public function track_cr_list(Request $request)
    {
        $inputdata                      = $request->all();
        $inputdata['searchkeyword']     = trim(_isset($inputdata, 'searchkeyword'));
        $totalrecords                   = EnComplaintRaised::get_track_cr_list($inputdata, true);
        $result                         = EnComplaintRaised::get_track_cr_list($inputdata, false);

        $data['data']['records']        = $result->isEmpty() ? null : $result;
        $data['data']['totalrecords']   = $totalrecords;

        if ($totalrecords < 0) {
            $data['message']['error']   = 'No data found';
            $data['status']             = 'error';
        } else {
            $data['message']['success'] = 'Data found';
            $data['status']             = 'success';
        }
        return response()->json($data);
    }

    public function complaintitremark(Request $request)
    {
        
        $actual_path  = 'uploads/purchase/';
        $target_dir   = public_path($actual_path);
        $itfile = "";
        if($request['saveimg_it_remark'])
        {
            $saveimg      = $request['saveimg_it_remark'];
            $file_dir     = $target_dir . "/" . $saveimg;
            $decoded_file = base64_decode($request['files_content_it_remark']); // decode the file
            if (file_put_contents($file_dir, $decoded_file)) {
                 $itfile = $actual_path . $saveimg;
            }
        }
        $itstatus = $request->input('ItsRepairable');
        $it_remark = $request->input('commentboxs');
        $cr_id = $request->input('crform_id');
        
       
        $result =  DB::table('en_complaint_raised')
                    ->where('cr_id', $cr_id)
                    ->update(
                        array(
                            'itstatus'=> $itstatus, 
                            'it_remark'=> $it_remark, 
                            'itfile'=> $itfile, 
                            'it_status'=> "APPROVE"
                        )
                    );
        $data['data'] = $result;
        $data['status'] = 'success';
        $data['message']['success'] = 'success';
        return $data;
    }

    public function complaintstoreremark(Request $request)
    {
        
        $actual_path  = 'uploads/purchase/';
        $target_dir   = public_path($actual_path);
        $storefile = "";
        if($request['saveimg_store_remark'])
        {
            $saveimg      = $request['saveimg_store_remark'];
            $file_dir     = $target_dir . "/" . $saveimg;
            $decoded_file = base64_decode($request['files_content_store_remark']); // decode the file
            if (file_put_contents($file_dir, $decoded_file)) {
                 $storefile = $actual_path . $saveimg;
            }
        }
        $store_remark = $request->input('store_commentboxs');
        $cr_id = $request->input('crStoreform_id');
        
       
        $result =  DB::table('en_complaint_raised')
                    ->where('cr_id', $cr_id)
                    ->update(
                        array(
                            'store_remark'=> $store_remark, 
                            'storefile'=> $storefile, 
                            'store_status'=> "APPROVE"
                        )
                    );
        $data['data'] = $result;
        $data['status'] = 'success';
        $data['message']['success'] = 'success';
        return $data;
    }

    public function complaintraised(Request $request, $cr_id = null)
    {
        try
        {
            
           
            $request['cr_id'] = $cr_id;
            $validator        = Validator::make($request->all(), ['cr_id' => 'nullable',]);
            if ($validator->fails()) 
            {
                $error                    = $validator->errors();
                $data['data']             = null;
                $data['message']['error'] = $error;
                $data['status']           = 'error';
                return response()->json($data);
            } 
            else 
            {
                $inputdata = $request->all();                
                $inputdata['searchkeyword'] = trim(_isset($inputdata, 'searchkeyword'));
                $totalrecords               = EnComplaintRaised::getcrs($cr_id, $inputdata, true);
                $result                     = EnComplaintRaised::getcrs($cr_id, $inputdata, false);
                
                /* $queries    = DB::getQueryLog();
                $data['last_query'] = end($queries);*/

                foreach ($result as $key => $each_pr) {
                    $pr_details       = $each_pr;
                    // $pr_details_arr   = json_to_array($pr_details);
                    // $each_pr = $pr_details_arr;
                    // $data['data'] = $pr_details->requester_id;
                    
                    //get Requester names details
                    $pr_requester_name = isset($pr_details->requester_id) ? $pr_details->requester_id : "";
                   
                    if ($pr_requester_name) {
                        $requester_name_details = EnRequesternames::getrequesternames($pr_requester_name);                        
                        // $data['data'][$key] = $requester_name_details[0]->fname . " " . $requester_name_details[0]->lname;
                        $result[$key]->requester_name_details = isset($requester_name_details[0]) ? $requester_name_details[0] : array();  
                    } else {
                        $result[$key]->requester_name_details = array();
                    }
                }
                
                $data['data']['records']      = $result->isEmpty() ? null : $result;
                $data['data']['totalrecords'] = $totalrecords;

                if ($totalrecords < 1) {
                    $data['message']['success'] = showmessage('102', array('{name}'), array(trans('label.lbl_purchaserequest')));
                } else {
                    $data['message']['success'] = showmessage('101', array('{name}'), array(trans('label.lbl_purchaserequest')));
                }
                

                $data['status'] = 'success';
                return response()->json($data);
            }
        } catch (\Exception $e) {
            $data['data']             = null;
            $data['message']['error'] = $e->getMessage();
            $data['status']           = 'error';
            save_errlog("complaintraised", "This controller function is implemented to show Cr list.", $request->all(), $e->getMessage());
            return response()->json($data);
        } catch (\Error $e) {
            $data['data']             = null;
            $data['message']['error'] = $e->getMessage();
            $data['status']           = 'error';
            save_errlog("complaintraised", "This controller function is implemented to show Cr list.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }

    public function complaintRaisedAdd(Request $request)
    {
        $department_name = $request->input('department_name');
        $user_id = $request->input('user_ids');
        $hod_id = $request->input('parent_ids');              
        $requester_id = $request->input('requester_id');        
        $priority = $request->input('priority');
        $problemdetail = $request->input('problemdetail');       
        $asset_id = $request->input('asset_id');        
        $complaint_raised_no = $request->input('complaint_raised_no');
        $complaint_raised_date = $request->input('complaint_raised_date');
        $attachment = "";
        if($request->input('file_ext') != "")
        {
            $actual_path = 'uploads/purchase/';
            $target_dir = public_path($actual_path);
            header('Content-Type: application/json');
            $file_ext = $request->input('file_ext');
            $saveimg = "complaint_request_attachments_" . time() . ".$file_ext";
            $file_dir = $target_dir . "/" . $saveimg;
            $decoded_file = base64_decode($request->input('file')); 
            if (file_put_contents($file_dir, $decoded_file)) 
            {
                $attachment = $actual_path . $saveimg;                
            }
        }

        $savedata['complaint_raised_no'] = $complaint_raised_no;
        $savedata['complaint_raised_date'] = $complaint_raised_date;
        $savedata['user_id'] = DB::raw('UUID_TO_BIN("' . $user_id . '")');
        $savedata['requester_id'] = DB::raw('UUID_TO_BIN("' . $requester_id . '")');
        $savedata['asset_id'] = DB::raw('UUID_TO_BIN("' . $asset_id . '")');
        $savedata['priority'] = $priority;
        $savedata['problemdetail'] = $problemdetail;
        $savedata['attachment'] = $attachment;
        $savedata['hod_id'] = DB::raw('UUID_TO_BIN("' . $hod_id . '")');
        $savedata['hod_remark'] = "";
        $savedata['hod_status'] = "PENDING";
        $savedata['it_remark'] = "";
        $savedata['it_status'] = "PENDING";
        $savedata['vendor_id'] = "";
        $savedata['store_remark'] = "";
        $savedata['store_status'] = "PENDING";       
        
       
        $result = EnComplaintRaised::create($savedata);

        $data['data'] = "inserted";
        $data['status'] = 'success';
        $data['message']['success'] = 'success';
        return $data;
    }
     
    public function getIssueAsset(Request $request)
    {
        $userId = $request['userId'];

        $validator            = Validator::make($request->all(), [
            'userId' => 'nullable|allow_uuid|string|size:36',
        ]);
        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
            return response()->json($data);
        } else {
            
            $getIssueAsset = DB::select( DB::raw('SELECT 
            BIN_TO_UUID(a1.asset_id) AS asset_id,a3.display_name 
            FROM en_assets_assign a1, en_ci_requesternames a2, en_assets a3 
            WHERE a1.requestername_id = a2.requestername_id and 
            a1.asset_id = a3.asset_id and a1.status="in_use" and 
            a2.requestername_id = uuid_to_bin("'.$userId.'")'));
            
            $data['data'] = $getIssueAsset;
            $data['status'] = 'success';
            $data['message']['success'] = 'success';
            return $data;            
        }
    }

    public function purchaserequestadd(Request $request)
    {
        $messages = [
            'form_templ_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_formdataid')), true),
            'asset_details.required' => showmessage('000', array('{name}'), array(trans('label.lbl_itemdetails')), true),
            'status.required' => showmessage('000', array('{name}'), array(trans('label.lbl_status')), true),
            /*'po_no.composite_unique'   => showmessage('006', array('{name}'), array(trans('label.lbl_po_number')), true),
            'po_name.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_poname')), true),
            'bv_id.required'           => showmessage('000', array('{name}'), array(trans('label.lbl_businessvertical')), true),
            'dc_id.required'           => showmessage('000', array('{name}'), array(trans('label.lbl_datacenter')), true),
            'location_id.required'     => showmessage('000', array('{name}'), array(trans('label.lbl_location')), true),
            'details.required'         => showmessage('000', array('{name}'), array(trans('label.lbl_formdatason')), true),*/
            //'form_templ_type.required' => showmessage('000', array('{name}'), array('Form Data Type'), true),

        ];

        $validator = Validator::make($request->all(), [

            'form_templ_id' => 'required|allow_uuid|string|size:36',
            'asset_details' => 'required',
            'status' => 'required|in:pending approval,open,partially approved,approved,closed,cancelled,deleted',
            /*'bv_id'         => 'required|allow_uuid|string|size:36',
            'dc_id'         => 'required|allow_uuid|string|size:36',
            'location_id'   => 'required|allow_uuid|string|size:36',
            'po_no'         => 'nullable|string|min:3|max:25|composite_unique:en_form_data_po, po_no, ' . $request->input('po_no'),
            'po_name'       => 'nullable|composite_unique:en_form_data_po, po_name, ' . $request->input('po_name'),
            'details'       => 'required',*/
            //'form_templ_type' => 'required',

        ], $messages);
        /*$pr_details = $request['details'];
        $pr_asset_details['assets'] = $request['details']['itemRows'];
        $total_cost = $request['details']['total_cost'];

        $pr_asset_details['total_cost'] = $total_cost;

        $approval_users = array();

        $cumpulsary_user = $request['details']['user_id'];
        $optional_user = $request['details']['user_id_optional'];
        $approval_users['cumpulsary_user'] = $cumpulsary_user;
        $approval_users['optional_user'] = $optional_user;

        unset($pr_details['itemRows'],$pr_details['total_cost'],$pr_details['user_id'],$pr_details['user_id_optional']);

        $request['details'] = json_encode($pr_details);
        $request['asset_details'] = json_encode($pr_asset_details);
        $request['approval_details'] = json_encode($approval_users);
        $request['requester_id'] =  DB::raw('UUID_TO_BIN("'.$request->input('loggedinuserid').'")'); */

        // $data['data'] = $request->all();
        // $data['status'] = 'success';
        // $data['message']['success'] = 'success';
        // return $data;
        $validator->after(function ($validator) {
            $request = request();
            
            $requestDetails = json_decode($request['details'], true);
            $pr_shipto_contact = $requestDetails['pr_shipto_contact'];

            $ship_to_contact_other = $requestDetails['ship_to_contact_other'];

            // $IsShipToContactOther = "false";
            // if($pr_shipto_contact == "0922a2be-268c-11ec-9548-4a4901e9af12")
            // {
            //     $IsShipToContactOther = "true";
            // }else{
            //     $ship_to_contact_other = "";
            // }


           
            $pr_po_type = $request['pr_po_type'];

            /*if ($pr_po_type == "po")
            {
            $po_name = $request['po_name'];
            if ($po_name == "")
            {
            $validator->errors()->add('po_name', showmessage('000', array('{name}'), array(trans('label.lbl_poname')), true));
            }
            else
            {
            $start_with_alpha_numeric_allow_alphal_numeric_dash_underscore_only_validation = $this->validation_start_with_alpha_numeric_allow_alphal_numeric_dash_underscore_only($po_name);
            if ($start_with_alpha_numeric_allow_alphal_numeric_dash_underscore_only_validation)
            {
            $validator->errors()->add('po_name', showmessage('007', array('{name}'), array(trans('label.lbl_poname')), true));
            }
            }

            $po_no = $request['po_no'];
            if ($po_no == "")
            {
            $validator->errors()->add('po_no', showmessage('000', array('{name}'), array(trans('label.lbl_ponumber')), true));
            }
            else
            {
            $start_with_alpha_numeric_allow_alphal_numeric_dash_underscore_only_validation = $this->validation_start_with_alpha_numeric_allow_alphal_numeric_dash_underscore_only($po_no);
            if ($start_with_alpha_numeric_allow_alphal_numeric_dash_underscore_only_validation) {
            $validator->errors()->add('po_no', showmessage('007', array('{name}'), array(trans('label.lbl_ponumber')), true));
            }
            }
            }*/

            $pr_details = $request['details'] = json_decode($request['details'], true);
            $pr_asset_details = json_decode($request['asset_details'], true);
            //$total_cost = $request['details']['total_cost'];

            //$pr_asset_details['total_cost'] = $total_cost;
            $approval_users = json_decode($request['approval_details'], true);

            /* $cumpulsary_user = $request['details']['user_id'];
            $optional_user = $request['details']['user_id_optional'];
            $approval_users['cumpulsary_user'] = $cumpulsary_user;
            $approval_users['optional_user'] = $optional_user; */

            //  unset($pr_details['itemRows'],$pr_details['total_cost'],$pr_details['user_id'],$pr_details['user_id_optional']);

            $request['details'] = json_encode($pr_details);
            $request['asset_details'] = json_encode($pr_asset_details);
            $request['approval_details'] = json_encode($approval_users);
            $request['requester_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');            
            $asset_arr = array();
            $asset_details = json_decode($request['asset_details'], true);
            $approval_details = json_decode($request['approval_details'], true);

            if ($asset_details) {
                // && isset($asset_details['item_estimated_cost'])
                if (isset($asset_details['item']) && isset($asset_details['item_desc']) && isset($asset_details['item_qty']) && isset($asset_details['warranty_support_required'])) {

                    $check_item_product = $check_item_qty = array();

                    foreach ($asset_details['item'] as $key => $item) {
                        $asset_arr[$key]['item'] = $item;
                        $asset_arr[$key]['item_product'] = $asset_details['item_product'][$key];
                        $asset_arr[$key]['item_unit'] = $asset_details['item_unit'][$key];
                        $asset_arr[$key]['item_desc'] = $asset_details['item_desc'][$key];
                        $asset_arr[$key]['item_qty'] = $asset_details['item_qty'][$key];
                        $asset_arr[$key]['warranty_support_required'] = $asset_details['warranty_support_required'][$key];
                        //$asset_arr[$key]['item_estimated_cost'] = $asset_details['item_estimated_cost'][$key];

                        $check_item_product[] = $asset_details['item_product'][$key];
                        $check_item_qty[] = $asset_details['item_qty'][$key];

                        $emptyArr = array();
                        $htmlNotAllowedArr = array();
                        if ($item == "") {
                            $emptyArr[] = trans('label.lbl_item');
                        }

                        if (isset($asset_details['item_product'][$key]) && $asset_details['item_product'][$key] == "") {
                            $emptyArr[] = "item name";
                        }

                        if ($asset_details['item_desc'][$key] == "") {
                            $emptyArr[] = trans('label.lbl_item_desc');
                        } else {

                            $html_tags_not_allowed_validation = $this->validation_html_tags_not_allowed($asset_details['item_desc'][$key]);
                            if ($html_tags_not_allowed_validation) {
                                $htmlNotAllowedArr[] = trans('label.lbl_item_desc');
                            }
                        }
                        if ($asset_details['item_qty'][$key] == "") {
                            $emptyArr[] = trans('label.lbl_item_qty');
                        }
                        /* if ($asset_details['item_estimated_cost'][$key] == "") {
                        $emptyArr[] = trans('label.lbl_item_estim_cost');
                        }*/
                        if (!empty($emptyArr)) {
                            $emptyStr = implode(",", $emptyArr);
                            $validator->errors()->add('item ' . ($key + 1), showmessage('000', array('{name}'), array("#" . ($key + 1) . " " . $emptyStr), true));
                        }
                        if (!empty($htmlNotAllowedArr)) {
                            $htmlNotAllowedStr = implode(",", $htmlNotAllowedArr);
                            $validator->errors()->add('item ' . ($key + 1), showmessage('001', array('{name}'), array("#" . ($key + 1) . " " . $htmlNotAllowedStr), true));
                        }

                    }

                }
            }
            //print_r($asset_arr);
            //$asset_json  = json_encode( $asset_arr, true );
            $request['asset_details'] = $asset_arr;
            $jsondata = json_decode($request['details'], true);
            $request['check_item_product'] = $check_item_product;
            $request['check_item_qty'] = $check_item_qty;
            $request['balanced_budget'] = json_decode($request['balanced_budget'], true);

            if ($request['approval_req'] == 'y') {
                if (empty($approval_details['confirmed']) && empty($approval_details['optional'])) {
                    $validator->errors()->add('approvers', showmessage('000', array('{name}'), array(trans('label.lbl_approvers')), true));
                } else {
                    $result = array_intersect($approval_details['confirmed'], $approval_details['optional']);
                    if (!empty($result)) {
                        $validator->errors()->add('approvers', showmessage('msg_pr_po_same_user_can_not_for_approval'));
                    }
                }

            }
            // if($IsShipToContactOther == "true")
            // {
            //     if($ship_to_contact_other == "")
            //     {
            //         $validator->errors()->add('ship_to_contact_other', "ship to contact is required");    
            //     }
            // }

            if ($request['urlpath'] == "purchaserequest") {
                //$pr_title       = isset($jsondata['pr_title']) ? $jsondata['pr_title'] : "";
                //$pr_req_date = isset($jsondata['pr_req_date']) ? $jsondata['pr_req_date'] : "";
                $pr_due_date = isset($jsondata['pr_due_date']) ? $jsondata['pr_due_date'] : "";
                $pr_priority = isset($jsondata['pr_priority']) ? $jsondata['pr_priority'] : "";

                $pr_requester_name = isset($jsondata['pr_requester_name']) ? $jsondata['pr_requester_name'] : "";

                if ($pr_requester_name == "" || $pr_requester_name == null) {
                    $validator->errors()->add('pr_requester_name', showmessage('000', array('{name}'), array(trans('label.lbl_requester_name')), true));
                }
                               
                $pr_requirement_for = isset($jsondata['pr_requirement_for']) ? $jsondata['pr_requirement_for'] : "";

                if ($pr_requirement_for == "" || $pr_requirement_for == "[Select Requirement For]") {
                    $validator->errors()->add('pr_requirement_for', showmessage('000', array('{name}'), array(trans('label.lbl_requirement_for')), true));
                }

                $pr_category = isset($jsondata['pr_category']) ? $jsondata['pr_category'] : "";

                if ($pr_category == "" || $pr_category == "[Select Category]") {
                    $validator->errors()->add('pr_category', showmessage('000', array('{name}'), array(trans('label.lbl_category')), true));
                }

                $pr_shipto = isset($jsondata['pr_shipto']) ? $jsondata['pr_shipto'] : "";
                if ($pr_shipto == "" || $pr_shipto == null) {
                    $validator->errors()->add('pr_shipto', showmessage('000', array('{name}'), array(trans('label.lbl_shipto')), true));
                }

                $pr_shipto_contact = isset($jsondata['pr_shipto_contact']) ? $jsondata['pr_shipto_contact'] : "";

                if ($pr_shipto_contact == "" || $pr_shipto_contact == "[Select Category]") {
                    $validator->errors()->add('pr_shipto_contact', showmessage('000', array('{name}'), array(trans('label.lbl_shipto_contact')), true));
                }

                $pr_project_category = isset($jsondata['pr_project_category']) ? $jsondata['pr_project_category'] : "";

                if ($pr_project_category == "" || $pr_project_category == "[Select Project Category]") {
                    $validator->errors()->add('pr_project_category', showmessage('000', array('{name}'), array(trans('label.lbl_project_category')), true));
                }

                if ($pr_project_category == 'Internal') {
                    $pr_project_name_dd = isset($jsondata['pr_project_name_dd']) ? $jsondata['pr_project_name_dd'] : "";

                    if ($pr_project_name_dd == "" || $pr_project_name_dd == "[Select Project]") {
                        $validator->errors()->add('pr_project_name_dd', showmessage('000', array('{name}'), array(trans('label.lbl_project_name')), true));
                    }
                }

                if ($pr_project_category == 'External') {
                    $project_name = isset($jsondata['project_name']) ? $jsondata['project_name'] : "";

                    if ($project_name == "" || $project_name == null) {
                        $validator->errors()->add('project_name', showmessage('000', array('{name}'), array(trans('label.lbl_project_name')), true));
                    }

                    $project_wo_details = isset($jsondata['project_wo_details']) ? $jsondata['project_wo_details'] : "";

                    if ($project_wo_details == "" || $project_wo_details == null) {
                        $validator->errors()->add('project_wo_details', showmessage('000', array('{name}'), array(trans('label.lbl_project_wo_details')), true));
                    }

                    $opportunity_code = isset($jsondata['opportunity_code']) ? $jsondata['opportunity_code'] : "";

                    if ($opportunity_code == "" || $opportunity_code == null) {
                        $validator->errors()->add('opportunity_code', showmessage('000', array('{name}'), array('Opportunity Code'), true));
                    }

                    /*commented part start*/
                    $customer_po_file_new = $request['customer_po_file_new'];

                    if ($customer_po_file_new == "" || $customer_po_file_new == null || $customer_po_file_new == "undefined") {
                        // $validator->errors()->add('customer_po_file_new', showmessage('000', array('{name}'), array('Customer PO'), true));
                    }

                    $gc_approval_file_new = $request['gc_approval_file_new'];

                    if ($gc_approval_file_new == "" || $gc_approval_file_new == null || $gc_approval_file_new == "undefined") {
                        // $validator->errors()->add('gc_approval_file_new', showmessage('000', array('{name}'), array('GC Approval'), true));
                    }

                    $costing_details_file_new = $request['costing_details_file_new'];

                    if ($costing_details_file_new == "" || $costing_details_file_new == null || $costing_details_file_new == "undefined") {
                        // $validator->errors()->add('costing_details_file_new', showmessage('000', array('{name}'), array('Costing Details Against the Requirement'), true));
                    }
                    /*commented part start*/

                }

                //$pr_cost_center = isset($jsondata['pr_cost_center']) ? $jsondata['pr_cost_center'] : "";

                // $pr_description = isset($jsondata['pr_description']) ? $jsondata['pr_description'] : "";
                //$shipping_address = isset($jsondata['shipping_address']) ? $jsondata['shipping_address'] : "";
                //$billing_address = isset($jsondata['billing_address']) ? $jsondata['billing_address'] : "";
                // $pr_vendor = isset($jsondata['pr_vendor']) ? $jsondata['pr_vendor'] : "";

                /*if ($pr_title == "")
                {
                $validator->errors()->add('pr_title', showmessage('000', array('{name}'), array(trans('label.lbl_purchasename')), true));
                }
                else
                {

                $validation_start_with_alpha_numeric_allow_alphal_numeric_dash_underscore_only = $this->validation_start_with_alpha_numeric_allow_alphal_numeric_dash_underscore_only($pr_title);
                if ($validation_start_with_alpha_numeric_allow_alphal_numeric_dash_underscore_only) {
                $validator->errors()->add('pr_title', showmessage('007', array('{name}'), array(trans('label.lbl_purchasetitle')), true));
                }
                $pr_po_type = $request['pr_po_type'];
                if ($pr_po_type == "po") {
                $table = "en_form_data_po";
                } else {
                $table = "en_form_data_pr";
                }
                $formAction = $request['formAction'];
                if ($formAction == "edit") {
                $pr_id      = $request['pr_id'];
                $parameters = array($table, "details", "pr_title", $pr_title, "pr_id", $pr_id);
                } else {
                $parameters = array($table, "details", "pr_title", $pr_title);
                }
                $validation_resp = validation_composite_unique_without_status_for_json_data($parameters);

                if (!$validation_resp) {
                $validator->errors()->add('pr_title', showmessage('006', array('{name}'), array(trans('label.lbl_purchasetitle')), true));
                }
                }*/

                /* if ($pr_vendor == "") {
                $validator->errors()->add('pr_vendor', showmessage('000', array('{name}'), array(trans('label.lbl_vendor')), true));
                }*/
                /*if ($pr_req_date == "") {
                $validator->errors()->add('pr_req_date', showmessage('000', array('{name}'), array(trans('label.lbl_req_date')), true));
                }*/
                if ($pr_due_date == "") {
                    $validator->errors()->add('pr_due_date', showmessage('000', array('{name}'), array(trans('label.lbl_purchaseduedate')), true));
                }
                if ($pr_priority == "" || $pr_priority == "[Select Priority]") {
                    $validator->errors()->add('pr_priority', showmessage('000', array('{name}'), array(trans('label.lbl_priority')), true));
                }
                /*if ($pr_cost_center == "") {
                $validator->errors()->add('pr_cost_center', showmessage('000', array('{name}'), array(trans('label.lbl_cost_center')), true));
                }*/
                /*if($pr_description=="")
                {
                $validator->errors()->add('pr_description', showmessage('000', array('{name}'), array(trans('label.lbl_purchase_desc')), true));
                }else{//added by snehal to html not allowed validation on date:16/07/2020
                $html_tags_not_allowed_validation = $this->validation_html_tags_not_allowed($pr_description);
                if($html_tags_not_allowed_validation){
                $validator->errors()->add('pr_description', showmessage('001', array('{name}'), array(trans('label.lbl_purchase_desc')), true));
                }

                }*/
                //added by snehal to html not allowed validation on date:16/07/2020
                /*if($shipping_address != ""){
                $html_tags_not_allowed_validation = $this->validation_html_tags_not_allowed($shipping_address);
                if($html_tags_not_allowed_validation){
                $validator->errors()->add('shipping_address', showmessage('001', array('{name}'), array(trans('label.lbl_shipping_address')), true));
                }
                }*/
                /*if($billing_address != ""){
            $html_tags_not_allowed_validation = $this->validation_html_tags_not_allowed($billing_address);
            if($html_tags_not_allowed_validation){
            $validator->errors()->add('billing_address', showmessage('001', array('{name}'), array(trans('label.lbl_billing_address')), true));
            }
            }*/
            }
        });

        if ($validator->fails()) {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        } else {

            /* Balance Check functionality Start */
            $check_Balance_arr =
            DB::table('en_pr_po_asset_details')
                ->select(DB::raw('MAX(JSON_UNQUOTE(JSON_EXTRACT(asset_details,"$.item_estimated_cost"))) as cost'), DB::raw('JSON_UNQUOTE(JSON_EXTRACT(asset_details,"$.item_product")) as id'))
                ->whereIn(DB::raw('JSON_EXTRACT(asset_details,"$.item_product")'), $request['check_item_product'])
                ->where('asset_type', 'po')
                ->groupBy(DB::raw('JSON_EXTRACT(asset_details,"$.item_product")'))
                ->orderBy(DB::raw('MAX(JSON_UNQUOTE(JSON_EXTRACT(asset_details,"$.item_estimated_cost")))'), 'desc')
                ->get();
            // Current PR selected Items id and qty's
            $selected_items_arr = array_combine($request['check_item_product'], $request['check_item_qty']);
            $sum = 0;
            foreach ($check_Balance_arr as $vals) {
                if (!empty($selected_items_arr[$vals->id])) {
                    $sum += $selected_items_arr[$vals->id] * $vals->cost;
                }
            }
            // Department Budget Error
            // if ($sum > $request['balanced_budget']) {
            //     $data['data'] = $request['balanced_budget'];
            //     $data['message']['error'] = "The request item amount is more than department budget..!!";
            //     $data['status'] = 'error';
            //     return response()->json($data);
            // }
            // Department Budget Error

            /* Balance Check functionality End */

            $formAction = $request->input('formAction');
            unset($request['formAction']);
            // print_r($request['asset_details']); exit;

            $form_templ_id_uuid = $request->input('form_templ_id');
            $request['form_templ_id'] = DB::raw('UUID_TO_BIN("' . $request->input('form_templ_id') . '")');
            /*$request['bv_id']         = DB::raw('UUID_TO_BIN("' . $request->input('bv_id') . '")');
            $request['dc_id']         = DB::raw('UUID_TO_BIN("' . $request->input('dc_id') . '")');
            $request['location_id']   = DB::raw('UUID_TO_BIN("' . $request->input('location_id') . '")');*/
            /*  $result = EnPurchaseRequest::where('form_templ_id', $request['form_templ_id'])->first();
            if($result)
            {
            $result->update($request->all());
            $result->save();
            $data['data'] = "";
            $data['message']['success'] = showmessage('106', array('{name}'),array('Purchase Request Update Form'));
            $data['status'] = 'success';
            //Add into UserActivityLog
            userlog( array('record_id' => $form_templ_id_uuid, 'data' => $request->all(), 'action' => 'updated', 'message' => showmessage('106', array('{name}'),array('Purchase Request'))));
            }
            else
            {    */
            $asset_detailsArr = $request['asset_details'];
            unset($request['asset_details']);
            $pr_po_type = $request['pr_po_type']; // As "Purchase Order Add (Without PR)" Will Work same link Purchase Request Save.

            if ($formAction == "add") {
                if ($request['approval_req'] == 'n') {
                    unset($request['approval_details']);
                }
                unset($request['pr_id']);
                DB::beginTransaction(); // begin transaction
                $result_id = "";
                $result_id_text = "";
                $result_message = trans('label.lbl_purchaserequest');

                if ($pr_po_type == "pr") {
                    $purchaserequestresponse = EnPurchaseRequest::create($request->all());
                    $result_id = $purchaserequestresponse['pr_id'];
                    $result_id_text = $purchaserequestresponse->pr_id_text;
                    $result_message = trans('label.lbl_purchaserequest');
                } else {
                    unset($request['form_templ_type']);
                    $purchaserequestresponse = EnPurchaseOrder::create($request->all());
                    $result_id = $purchaserequestresponse['po_id'];
                    $result_id_text = $purchaserequestresponse->po_id_text;
                    $result_message = trans('label.lbl_purchase_order');
                }

                if (!empty($result_id)) {
                    $asset_inputdata = array();

                    foreach ($asset_detailsArr as $key => $asset) {
                        $asset_inputdata['pr_po_id'] = $result_id;
                        // $asset_inputdata['po_id']       = "";
                        $asset_inputdata['asset_type'] = $pr_po_type;
                        $asset_inputdata['asset_details'] = json_encode($asset, true);
                        $asset_inputdata['created_by'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                        $purchaseAssetResponse = EnPrPoAssetDetails::create($asset_inputdata);
                    }

                    $data['data']['insert_id'] = $result_id_text;
                    $data['message']['success'] = showmessage('104', array('{name}'), array($result_message));
                    $data['status'] = 'success';

                    $hist_details = $this->gethistorydesc('created', $result_message);
                    //Add into Purchase History
                    
                    $this->prpohistoryadd(array('pr_po_id' => $result_id_text, 'history_type' => $pr_po_type, 'action' => 'created', 'details' => $hist_details, 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")')));
                    //Add into UserActivityLog
                    userlog(array('record_id' => $result_id_text, 'data' => $request->all(), 'action' => 'added', 'message' => showmessage('104', array('{name}'), array($result_message))));

                    // 'type','message','store_user','show_user','notification_read'

                    user_notification(array('type' => 'pr', 'message' => 'pr request', 'store_user' => DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")'), 'show_user' => '', 'action' => 'add'));

                    DB::commit();
                } else {
                    DB::rollBack();
                    $data['data'] = $request->all();
                    $data['message']['error'] = showmessage('103', array('{name}'), array($result_message));
                    $data['status'] = 'error';
                }
            } else //if($formAction == "edit")
            {
                $request['requester_id'] = DB::raw('UUID_TO_BIN("' . $request->input('editrequesterid') . '")');
                // $data['data'] = $request->all();
                // $data['status'] = 'success';
                // $data['message']['success'] = 'success';
                // return response()->json($data); 
                DB::beginTransaction(); // begin transaction
                $pr_id_uuid = $request->input('pr_id');
                $pr_id_bin = DB::raw('UUID_TO_BIN("' . $request->input('pr_id') . '")');
                $result = EnPurchaseRequest::where('pr_id', $pr_id_bin)->first();
                $request['pr_id'] = DB::raw('UUID_TO_BIN("' . $request->input('pr_id') . '")');
                if ($result) {
                    $request["approved_status"] = json_encode($request->input("approved_status"), true);
                    $result->update($request->all());
                    $result->save();

                    $prs_descroy = EnPrPoAssetDetails::destroyassetbyprpo($request->input('pr_id'), "pr");
                    // echo "jhjhj";
                    //  print_r( $prs_descroy);
                    $asset_inputdata = array();
                    foreach ($asset_detailsArr as $key => $asset) {
                        $asset_inputdata['pr_po_id'] = $request['pr_id'];
                        // $asset_inputdata['po_id']         = "";
                        $asset_inputdata['asset_type'] = $pr_po_type;
                        $asset_inputdata['asset_details'] = json_encode($asset, true);
                        $asset_inputdata['created_by'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                        $purchaseAssetResponse = EnPrPoAssetDetails::create($asset_inputdata);
                    }

                    $data['data']['insert_id'] = $pr_id_uuid;
                    $data['message']['success'] = showmessage('106', array('{name}'), array(trans('label.lbl_purchaserequest')));
                    $data['status'] = 'success';

                    $hist_details = $this->gethistorydesc('updated', trans('label.lbl_purchaserequest'));
                    //Add into Purchase History
                    $this->prpohistoryadd(array('pr_po_id' => $pr_id_uuid, 'history_type' => $pr_po_type, 'action' => 'updated', 'details' => $hist_details, 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")')));
                    //Add into UserActivityLog
                    userlog(array('record_id' => $pr_id_uuid, 'data' => $request->all(), 'action' => 'added', 'message' => showmessage('106', array('{name}'), array(trans('label.lbl_purchaserequest')))));
                    DB::commit();
                } else {
                    DB::rollBack();
                    $data['data'] = $request->all();
                    $data['message']['error'] = showmessage('105', array('{name}'), array(trans('label.lbl_purchaserequest')));
                    $data['status'] = 'error';
                }
            }
            //}
            return response()->json($data);
        }

        /* }
    catch(\Exception $e){
    $data['data']               = null;
    $data['message']['error']   = $e->getMessage();
    $data['status']             = 'error';
    save_errlog("purchaserequestadd","This controller function is implemented to add PR.",$request->all(),$e->getMessage());
    return response()->json($data);
    }
    catch(\Error $e){
    $data['data']               = null;
    $data['message']['error']   = $e->getMessage();
    $data['status']             = 'error';
    save_errlog("purchaserequestadd","This controller function is implemented to add PR.",$request->all(),$e->getMessage());
    return response()->json($data);
    }*/
    }

    public function purchaserequestaddsample(Request $request)
    {
        $messages = [
            'form_templ_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_formdataid')), true),
            'asset_details.required' => showmessage('000', array('{name}'), array(trans('label.lbl_itemdetails')), true),
            'status.required' => showmessage('000', array('{name}'), array(trans('label.lbl_status')), true),

        ];

        $validator = Validator::make($request->all(), [

            'form_templ_id' => 'required|allow_uuid|string|size:36',
            'asset_details' => 'required',
            'status' => 'required|in:pending approval,open,partially approved,approved,closed,cancelled,deleted',

        ], $messages);

        $validator->after(function ($validator) {
            $request = request();
            $pr_po_type = $request['pr_po_type'];

            $pr_details = $request['details'] = json_decode($request['details'], true);
            $pr_asset_details = json_decode($request['asset_details'], true);
            //$total_cost = $request['details']['total_cost'];

            //$pr_asset_details['total_cost'] = $total_cost;
            // $approval_users = json_decode($request['approval_details'], true);

            $request['details'] = json_encode($pr_details);
            $request['asset_details'] = json_encode($pr_asset_details);
            // $request['approval_details'] = json_encode($approval_users);
            $request['requester_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
            $asset_arr = array();
            $asset_details = json_decode($request['asset_details'], true);
            // $approval_details            = json_decode($request['approval_details'], true);

            if ($asset_details) {
                // && isset($asset_details['item_estimated_cost'])
                if (isset($asset_details['item_product']) && isset($asset_details['item_desc']) && isset($asset_details['item_qty']) && isset($asset_details['warranty_support_required'])) {

                    foreach ($asset_details['item_product'] as $key => $item) {
                        // $asset_arr[$key]['item']                      = $item;
                        $asset_arr[$key]['item_product'] = $asset_details['item_product'][$key];
                        $asset_arr[$key]['item_desc'] = $asset_details['item_desc'][$key];
                        $asset_arr[$key]['item_qty'] = $asset_details['item_qty'][$key];
                        $asset_arr[$key]['warranty_support_required'] = $asset_details['warranty_support_required'][$key];
                        //$asset_arr[$key]['item_estimated_cost'] = $asset_details['item_estimated_cost'][$key];
                        $emptyArr = array();
                        $htmlNotAllowedArr = array();
                        /* if ($item == "") {
                        $emptyArr[] = trans('label.lbl_item');
                        }*/

                        if (isset($asset_details['item_product'][$key]) && $asset_details['item_product'][$key] == "") {
                            $emptyArr[] = "item name";
                        }

                        if ($asset_details['item_desc'][$key] == "") {
                            $emptyArr[] = trans('label.lbl_item_desc');
                        } else {

                            $html_tags_not_allowed_validation = $this->validation_html_tags_not_allowed($asset_details['item_desc'][$key]);
                            if ($html_tags_not_allowed_validation) {
                                $htmlNotAllowedArr[] = trans('label.lbl_item_desc');
                            }
                        }
                        if ($asset_details['item_qty'][$key] == "") {
                            $emptyArr[] = trans('label.lbl_item_qty');
                        }
                        /* if ($asset_details['item_estimated_cost'][$key] == "") {
                        $emptyArr[] = trans('label.lbl_item_estim_cost');
                        }*/
                        if (!empty($emptyArr)) {
                            $emptyStr = implode(",", $emptyArr);
                            $validator->errors()->add('item ' . ($key + 1), showmessage('000', array('{name}'), array("#" . ($key + 1) . " " . $emptyStr), true));
                        }
                        if (!empty($htmlNotAllowedArr)) {
                            $htmlNotAllowedStr = implode(",", $htmlNotAllowedArr);
                            $validator->errors()->add('item ' . ($key + 1), showmessage('001', array('{name}'), array("#" . ($key + 1) . " " . $htmlNotAllowedStr), true));
                        }

                    }
                }
            }
            //print_r($asset_arr);
            //$asset_json  = json_encode( $asset_arr, true );
            $request['asset_details'] = $asset_arr;
            $jsondata = json_decode($request['details'], true);

            /* if ($request['approval_req'] == 'y') {
            if (empty($approval_details['confirmed']) && empty($approval_details['optional'])) {
            $validator->errors()->add('approvers', showmessage('000', array('{name}'), array(trans('label.lbl_approvers')), true));
            } else {
            $result = array_intersect($approval_details['confirmed'], $approval_details['optional']);
            if (!empty($result)) {
            $validator->errors()->add('approvers', showmessage('msg_pr_po_same_user_can_not_for_approval'));
            }
            }

            }*/

            if ($request['urlpath'] == "purchaserequest") {
                //$pr_title       = isset($jsondata['pr_title']) ? $jsondata['pr_title'] : "";
                //$pr_req_date = isset($jsondata['pr_req_date']) ? $jsondata['pr_req_date'] : "";
                $pr_due_date = isset($jsondata['pr_due_date']) ? $jsondata['pr_due_date'] : "";
                $pr_priority = isset($jsondata['pr_priority']) ? $jsondata['pr_priority'] : "";

                $pr_requester_name = isset($jsondata['pr_requester_name']) ? $jsondata['pr_requester_name'] : "";

                if ($pr_requester_name == "" || $pr_requester_name == null) {
                    $validator->errors()->add('pr_requester_name', showmessage('000', array('{name}'), array(trans('label.lbl_requester_name')), true));
                }

                $pr_requirement_for = isset($jsondata['pr_requirement_for']) ? $jsondata['pr_requirement_for'] : "";

                if ($pr_requirement_for == "" || $pr_requirement_for == "[Select Requirement For]") {
                    $validator->errors()->add('pr_requirement_for', showmessage('000', array('{name}'), array(trans('label.lbl_requirement_for')), true));
                }

                $pr_category = isset($jsondata['pr_category']) ? $jsondata['pr_category'] : "";

                if ($pr_category == "" || $pr_category == "[Select Category]") {
                    $validator->errors()->add('pr_category', showmessage('000', array('{name}'), array(trans('label.lbl_category')), true));
                }

                $pr_shipto = isset($jsondata['pr_shipto']) ? $jsondata['pr_shipto'] : "";
                if ($pr_shipto == "" || $pr_shipto == null) {
                    $validator->errors()->add('pr_shipto', showmessage('000', array('{name}'), array(trans('label.lbl_shipto')), true));
                }

                $pr_shipto_contact = isset($jsondata['pr_shipto_contact']) ? $jsondata['pr_shipto_contact'] : "";

                if ($pr_shipto_contact == "" || $pr_shipto_contact == "[Select Category]") {
                    $validator->errors()->add('pr_shipto_contact', showmessage('000', array('{name}'), array(trans('label.lbl_shipto_contact')), true));
                }

                $pr_project_category = isset($jsondata['pr_project_category']) ? $jsondata['pr_project_category'] : "";

                if ($pr_project_category == "" || $pr_project_category == "[Select Project Category]") {
                    $validator->errors()->add('pr_project_category', showmessage('000', array('{name}'), array(trans('label.lbl_project_category')), true));
                }

                if ($pr_project_category == 'Internal') {
                    /*$pr_project_name_dd = isset($jsondata['pr_project_name_dd']) ? $jsondata['pr_project_name_dd'] : "";

                if ($pr_project_name_dd == "" || $pr_project_name_dd == "[Select Project]") {
                $validator->errors()->add('pr_project_name_dd', showmessage('000', array('{name}'), array(trans('label.lbl_project_name')), true));
                }*/
                }

                if ($pr_project_category == 'External') {
                    $project_name = isset($jsondata['project_name']) ? $jsondata['project_name'] : "";

                    if ($project_name == "" || $project_name == null) {
                        $validator->errors()->add('project_name', showmessage('000', array('{name}'), array(trans('label.lbl_project_name')), true));
                    }

                    $project_wo_details = isset($jsondata['project_wo_details']) ? $jsondata['project_wo_details'] : "";

                    if ($project_wo_details == "" || $project_wo_details == null) {
                        $validator->errors()->add('project_wo_details', showmessage('000', array('{name}'), array(trans('label.lbl_project_wo_details')), true));
                    }

                    $opportunity_code = isset($jsondata['opportunity_code']) ? $jsondata['opportunity_code'] : "";

                    /*if ($opportunity_code=="" || $opportunity_code==null) {
                    $validator->errors()->add('opportunity_code', showmessage('000', array('{name}'),array('Opportunity Code'), true));
                    }*/

                    /*commented part start*/
                    $customer_po_file_new = $request['customer_po_file_new'];

                    /*if ($customer_po_file_new =="" || $customer_po_file_new==null || $customer_po_file_new=="undefined") {
                    $validator->errors()->add('customer_po_file_new', showmessage('000', array('{name}'), array('Customer PO'), true));
                    }*/

                    $gc_approval_file_new = $request['gc_approval_file_new'];

                    /*if ($gc_approval_file_new =="" || $gc_approval_file_new==null || $gc_approval_file_new=="undefined") {
                    $validator->errors()->add('gc_approval_file_new', showmessage('000', array('{name}'), array('GC Approval'), true));
                    }*/

                    $costing_details_file_new = $request['costing_details_file_new'];

                    /* if ($costing_details_file_new =="" || $costing_details_file_new==null || $costing_details_file_new=="undefined") {
                    $validator->errors()->add('costing_details_file_new', showmessage('000', array('{name}'), array('Costing Details Against the Requirement'), true));
                    } */
                    /*commented part start*/
                }

                if ($pr_due_date == "") {
                    $validator->errors()->add('pr_due_date', showmessage('000', array('{name}'), array(trans('label.lbl_purchaseduedate')), true));
                }
                if ($pr_priority == "" || $pr_priority == "[Select Priority]") {
                    $validator->errors()->add('pr_priority', showmessage('000', array('{name}'), array(trans('label.lbl_priority')), true));
                }

            }
        });

        if ($validator->fails()) {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        } else {
            $inputdata = $request->all();
            $inputdata['requester_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
            $inputdata['pr_requester_name'] = !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA";

            $formAction = $request->input('formAction');
            unset($request['formAction']);
            // print_r($request['asset_details']); exit;

            $form_templ_id_uuid = $request->input('form_templ_id');
            $request['form_templ_id'] = DB::raw('UUID_TO_BIN("' . $request->input('form_templ_id') . '")');

            $asset_detailsArr = $request['asset_details'];
            unset($request['asset_details']);
            $pr_po_type = $request['pr_po_type']; // As "Purchase Order Add (Without PR)" Will Work same link Purchase Request Save.

            if ($formAction == "add") {
                if ($request['approval_req'] == 'n') {
                    unset($request['approval_details']);
                }
                unset($request['pr_id']);

                DB::beginTransaction(); // begin transaction
                $result_id = "";
                $result_id_text = "";
                $result_message = trans('label.lbl_purchaserequest');

                if ($pr_po_type == "pr") {
                    $purchaserequestresponse = EnPurchaseRequestsample::create($inputdata);
                    $result_id = $purchaserequestresponse['pr_id'];
                    $result_id_text = $purchaserequestresponse->pr_id_text;
                    $result_message = trans('label.lbl_purchaserequest');
                } else {
                    unset($request['form_templ_type']);
                    $purchaserequestresponse = EnPurchaseOrder::create($request->all());
                    $result_id = $purchaserequestresponse['po_id'];
                    $result_id_text = $purchaserequestresponse->po_id_text;
                    $result_message = trans('label.lbl_purchase_order');
                }

                if (!empty($result_id)) {
                    $asset_inputdata = array();
                    foreach ($asset_detailsArr as $key => $asset) {
                        $asset_inputdata['pr_po_id'] = $result_id;
                        // $asset_inputdata['po_id']       = "";
                        $asset_inputdata['asset_type'] = $pr_po_type;
                        $asset_inputdata['asset_details'] = json_encode($asset, true);
                        $asset_inputdata['created_by'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                        $purchaseAssetResponse = EnPrPoAssetDetailssample::create($asset_inputdata);
                    }

                    $data['data']['insert_id'] = $result_id_text;
                    $data['message']['success'] = showmessage('104', array('{name}'), array($result_message));
                    $data['status'] = 'success';

                    $hist_details = $this->gethistorydesc('created', $result_message);
                    //Add into Purchase History
                    $this->prpohistoryadd(array('pr_po_id' => $result_id_text, 'history_type' => $pr_po_type, 'action' => 'created', 'details' => $hist_details, 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")')));
                    //Add into UserActivityLog
                    userlog(array('record_id' => $result_id_text, 'data' => $request->all(), 'action' => 'added', 'message' => showmessage('104', array('{name}'), array($result_message))));

                    DB::commit();
                } else {
                    DB::rollBack();
                    $data['data'] = $request->all();
                    $data['message']['error'] = showmessage('103', array('{name}'), array($result_message));
                    $data['status'] = 'error';
                }
            } else {

                DB::beginTransaction(); // begin transaction
                $pr_id_uuid = $request->input('pr_id');
                $pr_id_bin = DB::raw('UUID_TO_BIN("' . $request->input('pr_id') . '")');
                $result = EnPurchaseRequestsample::where('pr_id', $pr_id_bin)->first();
                $request['pr_id'] = DB::raw('UUID_TO_BIN("' . $request->input('pr_id') . '")');
                if ($result) {
                    $request["approved_status"] = json_encode($request->input("approved_status"), true);
                    $result->update($request->all());
                    $result->save();

                    $prs_descroy = EnPrPoAssetDetailssample::destroyassetbyprpo($request->input('pr_id'), "pr");

                    $asset_inputdata = array();
                    foreach ($asset_detailsArr as $key => $asset) {
                        $asset_inputdata['pr_po_id'] = $request['pr_id'];
                        // $asset_inputdata['po_id']         = "";
                        $asset_inputdata['asset_type'] = $pr_po_type;
                        $asset_inputdata['asset_details'] = json_encode($asset, true);
                        $asset_inputdata['created_by'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                        $purchaseAssetResponse = EnPrPoAssetDetailssample::create($asset_inputdata);
                    }

                    $data['data']['insert_id'] = $pr_id_uuid;
                    $data['message']['success'] = showmessage('106', array('{name}'), array(trans('label.lbl_purchaserequest')));
                    $data['status'] = 'success';

                    $hist_details = $this->gethistorydesc('updated', trans('label.lbl_purchaserequest'));
                    //Add into Purchase History
                    $this->prpohistoryadd(array('pr_po_id' => $pr_id_uuid, 'history_type' => $pr_po_type, 'action' => 'updated', 'details' => $hist_details, 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")')));
                    //Add into UserActivityLog
                    userlog(array('record_id' => $pr_id_uuid, 'data' => $request->all(), 'action' => 'added', 'message' => showmessage('106', array('{name}'), array(trans('label.lbl_purchaserequest')))));
                    DB::commit();
                } else {
                    DB::rollBack();
                    $data['data'] = $request->all();
                    $data['message']['error'] = showmessage('105', array('{name}'), array(trans('label.lbl_purchaserequest')));
                    $data['status'] = 'error';
                }
            }
            return response()->json($data);
        }
    }

    /**
     * This is controller funtion used to accept the values to add/update new Purchase Request & Add Purchase Order.
     * @author       Namrata Thakur
     * @access       public
     * @param
     * @param_type   POST array
     * @return       JSON
     * @tables       en_form_data_pr
     */

    public function purchaserequestconvertadd(Request $request)
    {
        $messages = [
            'form_templ_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_formdataid')), true),
            'asset_details.required' => showmessage('000', array('{name}'), array(trans('label.lbl_itemdetails')), true),
            'status.required' => showmessage('000', array('{name}'), array(trans('label.lbl_status')), true),
            /*'po_no.composite_unique'   => showmessage('006', array('{name}'), array(trans('label.lbl_po_number')), true),
            'po_name.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_poname')), true),
            'bv_id.required'           => showmessage('000', array('{name}'), array(trans('label.lbl_businessvertical')), true),
            'dc_id.required'           => showmessage('000', array('{name}'), array(trans('label.lbl_datacenter')), true),
            'location_id.required'     => showmessage('000', array('{name}'), array(trans('label.lbl_location')), true),
            'details.required'         => showmessage('000', array('{name}'), array(trans('label.lbl_formdatason')), true),*/
            //'form_templ_type.required' => showmessage('000', array('{name}'), array('Form Data Type'), true),

        ];

        $validator = Validator::make($request->all(), [

            'form_templ_id' => 'required|allow_uuid|string|size:36',
            'asset_details' => 'required',
            'status' => 'required|in:pending approval,open,partially approved,approved,closed,cancelled,deleted',
            /*'bv_id'         => 'required|allow_uuid|string|size:36',
            'dc_id'         => 'required|allow_uuid|string|size:36',
            'location_id'   => 'required|allow_uuid|string|size:36',
            'po_no'         => 'nullable|string|min:3|max:25|composite_unique:en_form_data_po, po_no, ' . $request->input('po_no'),
            'po_name'       => 'nullable|composite_unique:en_form_data_po, po_name, ' . $request->input('po_name'),
            'details'       => 'required',*/
            //'form_templ_type' => 'required',

        ], $messages);

        $asset_details = json_decode($request['asset_details'], true);

        $validator->after(function ($validator) {

            $request = request();
            $pr_po_type = $request['pr_po_type'];
            $pr_details = $request['details'] = json_decode($request['details'], true);
            $approval_users = json_decode($request['approval_details'], true);
            $request['details'] = json_encode($pr_details);
            $request['approval_details'] = json_encode($approval_users);
            $request['requester_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
            $jsondata = json_decode($request['details'], true);
            $selected_items = isset($jsondata['selected_items']) ? $jsondata['selected_items'] : "";

            /* if ($request['approval_req'] == 'y') {
            if (empty($approval_details['confirmed']) && empty($approval_details['optional'])) {
            $validator->errors()->add('approvers', showmessage('000', array('{name}'), array(trans('label.lbl_approvers')), true));
            } else {
            $result = array_intersect($approval_details['confirmed'], $approval_details['optional']);
            if (!empty($result)) {
            $validator->errors()->add('approvers', showmessage('msg_pr_po_same_user_can_not_for_approval'));
            }
            }

            }*/

            if ($request['urlpath'] == "purchaserequest") {
                "";
                $pr_due_date = isset($jsondata['pr_due_date']) ? $jsondata['pr_due_date'] : "";
                $pr_priority = isset($jsondata['pr_priority']) ? $jsondata['pr_priority'] : "";

                if ($pr_due_date == "") {
                    $validator->errors()->add('pr_due_date', showmessage('000', array('{name}'), array(trans('label.lbl_purchaseduedate')), true));
                }

                if (empty($selected_items)) {
                    $validator->errors()->add('selected_items', 'Item details select at least one.');
                }

                if ($pr_priority == "" || $pr_priority == "[Select Priority]") {
                    $validator->errors()->add('pr_priority', showmessage('000', array('{name}'), array(trans('label.lbl_priority')), true));
                }

            }
        });

        if ($validator->fails()) {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        } else {
            $formAction = $request->input('formAction');
            $pr_ids = $request->input('pr_ids');
            $item_id = $request->input('item_id');
            unset($request['formAction']);
            $asset_arr = array();
            $asset_details = json_decode($asset_details, true);

            if ($asset_details) {
                // && isset($asset_details['item_estimated_cost'])
                // if (isset($asset_details['item']) && isset($asset_details['item_desc']) && isset($asset_details['item_qty']) && isset($asset_details['warranty_support_required'])) {
                if (isset($asset_details['item']) && isset($asset_details['item_qty'])) {

                    foreach ($asset_details['item'] as $key => $item) {
                        $asset_arr[$key]['item'] = $item;
                        $asset_arr[$key]['item_product'] = $asset_details['item_product'][$key];
                        $asset_arr[$key]['item_desc'] = $asset_details['item_desc'][$key];
                        $asset_arr[$key]['item_qty'] = $asset_details['item_qty'][$key];
                        $asset_arr[$key]['warranty_support_required'] = $asset_details['warranty_support_required'][$key];
                        $asset_arr[$key]['addresses'] = $asset_details['addresses'][$key];
                        $asset_arr[$key]['pr_id'] = $asset_details['pr_id'][$key];
                        //$asset_arr[$key]['item_estimated_cost'] = $asset_details['item_estimated_cost'][$key];
                    }

                }
            }

            //print_r($asset_arr);
            //$asset_json  = json_encode( $asset_arr, true );
            $request['asset_details'] = $asset_arr;

            $form_templ_id_uuid = $request->input('form_templ_id');
            $request['form_templ_id'] = DB::raw('UUID_TO_BIN("' . $request->input('form_templ_id') . '")');
            /*$request['bv_id']         = DB::raw('UUID_TO_BIN("' . $request->input('bv_id') . '")');
            $request['dc_id']         = DB::raw('UUID_TO_BIN("' . $request->input('dc_id') . '")');
            $request['location_id']   = DB::raw('UUID_TO_BIN("' . $request->input('location_id') . '")');*/
            /*  $result = EnPurchaseRequest::where('form_templ_id', $request['form_templ_id'])->first();
            if($result)
            {
            $result->update($request->all());
            $result->save();
            $data['data'] = "";
            $data['message']['success'] = showmessage('106', array('{name}'),array('Purchase Request Update Form'));
            $data['status'] = 'success';
            //Add into UserActivityLog
            userlog( array('record_id' => $form_templ_id_uuid, 'data' => $request->all(), 'action' => 'updated', 'message' => showmessage('106', array('{name}'),array('Purchase Request'))));
            }
            else
            {    */
            $asset_detailsArr = $request['asset_details'];
            unset($request['asset_details']);
            unset($request['selected_items']);
            $pr_po_type = $request['pr_po_type']; // As "Purchase Order Add (Without PR)" Will Work same link Purchase Request Save.

            if ($formAction == "add") {
                if ($request['approval_req'] == 'n') {
                    unset($request['approval_details']);
                }
                unset($request['pr_id']);
                DB::beginTransaction(); // begin transaction
                $result_id = "";
                $result_id_text = "";
                $result_message = trans('label.lbl_purchaserequest');

                if ($pr_po_type == "pr") {
                    $purchaserequestresponse = EnPurchaseRequest::create($request->all());
                    $result_id = $purchaserequestresponse['pr_id'];
                    $result_id_text = $purchaserequestresponse->pr_id_text;
                    $result_message = trans('label.lbl_purchaserequest');
                } else {
                    unset($request['form_templ_type']);
                    $purchaserequestresponse = EnPurchaseOrder::create($request->all());
                    $result_id = $purchaserequestresponse['po_id'];
                    $result_id_text = $purchaserequestresponse->po_id_text;
                    $result_message = trans('label.lbl_purchase_order');
                }

                if (!empty($result_id)) {
                    $asset_inputdata = array();
                    foreach ($asset_detailsArr as $key => $asset) {
                        $asset_inputdata['pr_po_id'] = $result_id;
                        // $asset_inputdata['po_id']       = "";
                        $asset_inputdata['asset_type'] = $pr_po_type;
                        $asset_inputdata['convert_status'] = 'y';
                        $asset_inputdata['asset_details'] = json_encode($asset, true);
                        $asset_inputdata['created_by'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                        $purchaseAssetResponse = EnPrPoAssetDetails::create($asset_inputdata);

                    }
                    if (!empty($item_id)) {
                        foreach ($item_id as $key => $value) {
                            foreach ($value as $k => $v) {
                                foreach ($v as $kk => $vv) {
                                    $purchaseAssetResponse = EnPrPoAssetDetails::where('pr_po_id', '=', DB::raw('UUID_TO_BIN("' . $kk . '")'))->where(DB::raw(
                                        "JSON_EXTRACT(asset_details, '$.item')"), '=', $k)->update(array('convert_status' => 'y'));

                                }
                            }

                            // $purchaseAssetResponse = EnPrPoAssetDetails::where('pr_po_id', '=', DB::raw('UUID_TO_BIN("' . $value . '")'))->update(array('convert_status' => 'y'));
                        }

                        // $data['data']             =  $item_id;

                    }

                    $data['data']['insert_id'] = $result_id_text;
                    $data['message']['success'] = showmessage('104', array('{name}'), array($result_message));
                    $data['status'] = 'success';

                    $hist_details = $this->gethistorydesc('created', $result_message);
                    //Add into Purchase History
                    $this->prpohistoryadd(array('pr_po_id' => $result_id_text, 'history_type' => $pr_po_type, 'action' => 'created', 'details' => $hist_details, 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")')));
                    //Add into UserActivityLog
                    userlog(array('record_id' => $result_id_text, 'data' => $request->all(), 'action' => 'added', 'message' => showmessage('104', array('{name}'), array($result_message))));
                    DB::commit();
                } else {
                    DB::rollBack();
                    $data['data'] = $request->all();
                    $data['message']['error'] = showmessage('103', array('{name}'), array($result_message));
                    $data['status'] = 'error';
                }
            } else //if($formAction == "edit")
            {

                DB::beginTransaction(); // begin transaction
                $pr_id_uuid = $request->input('pr_id');
                $pr_id_bin = DB::raw('UUID_TO_BIN("' . $request->input('pr_id') . '")');
                $result = EnPurchaseRequest::where('pr_id', $pr_id_bin)->first();
                $request['pr_id'] = DB::raw('UUID_TO_BIN("' . $request->input('pr_id') . '")');
                if ($result) {
                    $request["approved_status"] = json_encode($request->input("approved_status"), true);
                    $result->update($request->all());
                    $result->save();

                    $prs_descroy = EnPrPoAssetDetails::destroyassetbyprpo($request->input('pr_id'), "pr");
                    // echo "jhjhj";
                    //  print_r( $prs_descroy);
                    $asset_inputdata = array();
                    foreach ($asset_detailsArr as $key => $asset) {
                        $asset_inputdata['pr_po_id'] = $request['pr_id'];
                        // $asset_inputdata['po_id']         = "";
                        $asset_inputdata['asset_type'] = $pr_po_type;
                        $asset_inputdata['asset_details'] = json_encode($asset, true);
                        $asset_inputdata['created_by'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                        $purchaseAssetResponse = EnPrPoAssetDetails::create($asset_inputdata);
                    }

                    $data['data']['insert_id'] = $pr_id_uuid;
                    $data['message']['success'] = showmessage('106', array('{name}'), array(trans('label.lbl_purchaserequest')));
                    $data['status'] = 'success';

                    $hist_details = $this->gethistorydesc('updated', trans('label.lbl_purchaserequest'));
                    //Add into Purchase History
                    $this->prpohistoryadd(array('pr_po_id' => $pr_id_uuid, 'history_type' => $pr_po_type, 'action' => 'updated', 'details' => $hist_details, 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")')));
                    //Add into UserActivityLog
                    userlog(array('record_id' => $pr_id_uuid, 'data' => $request->all(), 'action' => 'added', 'message' => showmessage('106', array('{name}'), array(trans('label.lbl_purchaserequest')))));
                    DB::commit();
                } else {
                    DB::rollBack();
                    $data['data'] = $request->all();
                    $data['message']['error'] = showmessage('105', array('{name}'), array(trans('label.lbl_purchaserequest')));
                    $data['status'] = 'error';
                }
            }
            //}
            return response()->json($data);
        }

        /* }
    catch(\Exception $e){
    $data['data']               = null;
    $data['message']['error']   = $e->getMessage();
    $data['status']             = 'error';
    save_errlog("purchaserequestadd","This controller function is implemented to add PR.",$request->all(),$e->getMessage());
    return response()->json($data);
    }
    catch(\Error $e){
    $data['data']               = null;
    $data['message']['error']   = $e->getMessage();
    $data['status']             = 'error';
    save_errlog("purchaserequestadd","This controller function is implemented to add PR.",$request->all(),$e->getMessage());
    return response()->json($data);
    }*/
    }
    /**
     * This is controller funtion used to Approve / reject PR.
     * @author       Namrata Thakur
     * @access       public
     * @param
     * @param_type   POST array
     * @return       JSON
     * @tables       en_form_data_pr
     */

    public function approve_reject_cr(Request $request)
    {
        try
        {
            $cr_id = $request->input('cr_id');
            $user_id = $request->input('user_id');
            $requester_id = $request->input('requester_id');
            $hod_id = $request->input('hod_id');
            $approval_status = $request->input('approval_status');
            $comment = $request->input('comment');

            
            $result = DB::table('en_complaint_raised')
            ->where('cr_id', $cr_id)
            ->update([
                'hod_remark' => $comment,
                'hod_status' => $approval_status,
                'status' => 'IT'
            ]);

            $data['data'] = $result;
            $data['status'] = 'success';
            $data['message']['success'] = 'Complaint request approved successfully';
            return $data;
        }catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("crapprovereject", "This controller function is implemented to approve or reject PR or PO.", $request->all(), $e->getMessage());
            return response()->json($data);
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("crapprovereject", "This controller function is implemented to approve or reject PR or PO.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }

    public function prpoapprovereject(Request $request)
    {

        try
        {
            $messages = [
                'pr_po_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_purchaseid')), true),
                'user_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_userid')), true),
                'approval_status.required' => showmessage('000', array('{name}'), array(trans('label.lbl_approvalstatus')), true),
                'confirmed_optional.required' => showmessage('000', array('{name}'), array(trans('label.lbl_confirmed_or_optional')), true),
                'comment.required' => showmessage('000', array('{name}'), array(trans('label.lbl_comment')), true),
                'pr_po_type.required' => showmessage('000', array('{name}'), array(trans('label.lbl_approvefor_prpo')), true),
            ];

            $validator = Validator::make($request->all(), [
                'pr_po_id' => 'required|allow_uuid|string|size:36',
                'user_id' => 'required|allow_uuid|string|size:36',
                'confirmed_optional' => 'required|:confirmed,optional',
                'approval_status' => 'required|:rejected,approved,comment,hold',
                'pr_po_type' => 'required',
                'comment' => 'required',
            ], $messages);

            if ($validator->fails()) {
                $error = $validator->errors();
                $data['data'] = null;
                $data['message']['error'] = $error;
                $data['status'] = 'error';
            } else {

                $pr_po_id_uuid = $request->input('pr_po_id');
                $pr_po_type = $request->input('pr_po_type');
                $is_comment = $request->input('is_comment');
                $result_message = trans('label.lbl_purchaserequest');

                // unset($request['pr_po_type']); //Commentd on 1st Oct 2020 as hardcoded PR mention as type.

                $pr_po_id_bin = DB::raw('UUID_TO_BIN("' . $request->input('pr_po_id') . '")');
                $comment = $request->has('comment') ? $request->input('comment') : "";

                if ($pr_po_type == "pr") {
                    $result = EnPurchaseRequest::where('pr_id', $pr_po_id_bin)->first();
                    $result_message = trans('label.lbl_purchaserequest');
                } else {
                    $result = EnPurchaseOrder::where('po_id', $pr_po_id_bin)->first();
                    $result1 = EnPurchaseOrder::select(DB::raw('JSON_UNQUOTE(JSON_EXTRACT(details, "$.pr_id")) as pr_id'))->where('po_id', $pr_po_id_bin)->first();
                    $result_message = trans('label.lbl_purchase_order');
                    $po_pr_id = explode(',', $result1['pr_id']);
                }

                $confirmed_optional = $request->has('confirmed_optional') ? $request->input('confirmed_optional') : "";

                // $queries    = DB::getQueryLog();
                //$data['last_query'] = end($queries);
                DB::beginTransaction(); // begin transaction
                if ($result) {
                    if (empty($is_comment)) {

                        $approved_statusArr = json_decode($result['approved_status'], true);
                        if (!empty($approved_statusArr)) {
                            $approved_statusArr[$confirmed_optional][$request->input('user_id')] = $request->input('approval_status');
                        } else {
                            // echo "in else";
                            $approved_statusArr[$confirmed_optional] = array($request->input('user_id') => $request->input('approval_status'));

                        }
                        //print_r($approved_statusArr);
                        $approved_status = json_encode($approved_statusArr);
                        $approval_details = json_decode($result['approval_details'], true);

                        if ($request->input('approval_status') == "rejected") {
                            $update_data = array('approved_status' => $approved_status, 'status' => "rejected");
                            //get items id by po id
                            $get_items_id = DB::table('en_pr_po_asset_details')->select(DB::raw('JSON_UNQUOTE(JSON_EXTRACT(asset_details,"$.item_product")) as item_product'))->where('pr_po_id', $pr_po_id_bin)->where('asset_type', '=', 'po')->get()->toArray();
                            $item_product = array_column($get_items_id, 'item_product');
                            if ($pr_po_type == "po") {
                                $po_pr_id1 = array_map(function ($id) {
                                    return DB::raw('UUID_TO_BIN("' . $id . '")');
                                }, $po_pr_id);

                                $item_product1 = array_map(function ($id) {
                                    return DB::raw('UUID_TO_BIN("' . $id . '")');
                                }, $item_product);

                                DB::table('en_ci_quotation_comparison')
                                    ->whereIn('pr_po_id', $po_pr_id1)
                                    ->whereIn('selected_item_id', $item_product1)
                                    ->update([
                                        'vendor_approve' => null,
                                        'approval' => null,
                                    ]);
                            }

                        } elseif ($request->input('approval_status') == "hold") {
                            $update_data = array('approved_status' => $approved_status, 'status' => "hold");
                            // code...
                        } else //approval_status = approved
                        {
                            if (empty($approval_details['confirmed'])) {
                                $update_data = array('approved_status' => $approved_status, 'status' => "approved");
                            } else {

                                $approval_details_confirmed = $approval_details['confirmed'];
                                $flag = 0;

                                if (!empty($approved_statusArr['confirmed'])) //If approved_status ! empty
                                {
                                    foreach ($approval_details_confirmed as $user_id) {
                                        if (!array_key_exists($user_id, $approved_statusArr['confirmed'])) {
                                            $flag = 1;
                                        }
                                    }
                                } else // //If approved_status is empty
                                {
                                    $flag = 1;
                                }

                                if ($flag == 1) //
                                {
                                    $update_data = array('approved_status' => $approved_status);
                                } else {
                                    $update_data = array('approved_status' => $approved_status, 'status' => "approved");
                                }
                            }

                        }

                        $result->update($update_data);
                        $result->save();
                    }
                    //set history details text
                    $hist_details = $this->gethistorydesc($request->input('approval_status'), $result_message);
                    //Commentd on 1st Oct 2020 as hardcoded PR mention as type.
                    //$this->prpohistoryadd(array('pr_po_id'=> $pr_po_id_uuid, 'history_type'=>'pr', 'action' => $request->input('approval_status'), 'details' => $hist_details, 'created_by' => DB::raw('UUID_TO_BIN("'.$request->input('loggedinuserid').'")'), 'comment'=> $comment));
                    $this->prpohistoryadd(array('pr_po_id' => $pr_po_id_uuid, 'history_type' => $request['pr_po_type'], 'action' => $request->input('approval_status'), 'details' => $hist_details, 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")'), 'comment' => $comment));

                    userlog(array('record_id' => $pr_po_id_uuid, 'data' => $request->all(), 'action' => 'updated', 'message' => showmessage('104', array('{name}'), array($result_message . ' ' . $request->input('approval_status')))));

                    $data['data'] = null;
                    $data['message']['success'] = showmessage('104', array('{name}'), array($result_message . '  ' . $request->input('approval_status')));
                    $data['status'] = 'success';
                    DB::commit();
                } else {
                    DB::rollBack();
                    $data['data'] = null;
                    $data['message']['error'] = showmessage('103', array('{name}'), array($result_message . ' Approved / Rejected '));
                    $data['status'] = 'error';
                }
            }
            return response()->json($data);
        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("prpoapprovereject", "This controller function is implemented to approve or reject PR or PO.", $request->all(), $e->getMessage());
            return response()->json($data);
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("prpoapprovereject", "This controller function is implemented to approve or reject PR or PO.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }
    /**
     * This is controller funtion is to return history description text according to its approval status.
     * @author Darshan Chaure
     * @access public
     * @package purchaseorder
     * @param string $approval_status
     * @param string $result_message
     * @return json
     */
    public function gethistorydesc($approval_status = '', $result_message = '')
    {
        //get history details text
        $hist_details = '';
        switch ($approval_status) {
            case "approved":
                $hist_details = showmessage('msg_approved', array('{name}'), array($result_message), true);
                break;
            case "cancelled":
                $hist_details = showmessage('msg_cancelled', array('{name}'), array($result_message), true);
                break;
            case "closed":
                $hist_details = showmessage('msg_closed', array('{name}'), array($result_message), true);
                break;
            case "deleted":
                $hist_details = showmessage('msg_deleted', array('{name}'), array($result_message), true);
                break;
            case "item received":
                $hist_details = showmessage('msg_item_received', array('{name}'), array($result_message), true);
                break;
            case "notifyagain":
                $hist_details = showmessage('msg_notifyagain', array('{name}'), array($result_message), true);
                break;
            case "notifyowner":
                $hist_details = showmessage('msg_notifyowner', array('{name}'), array($result_message), true);
                break;
            case "notifyvendor":
                $hist_details = showmessage('msg_notifyvendor', array('{name}'), array($result_message), true);
                break;
            case "open":
                $hist_details = showmessage('msg_open', array('{name}'), array($result_message), true);
                break;
            case "partially approved":
                $hist_details = showmessage('msg_partiallyapproved', array('{name}'), array($result_message), true);
                break;
            case "partially received":
                $hist_details = showmessage('msg_partiallyreceived', array('{name}'), array($result_message), true);
                break;
            case "pending approval":
                $hist_details = showmessage('msg_pendingapproval', array('{name}'), array($result_message), true);
                break;
            case "rejected":
                $hist_details = showmessage('msg_rejected', array('{name}'), array($result_message), true);
                break;
            case "hold":
                $hist_details = 'Purchase Order hold.';
                break;

            //----for custom messages which are not in approval status---
            case "created":
                $hist_details = showmessage('msg_created', array('{name}'), array($result_message), true);
                break;
            case "updated":
                $hist_details = showmessage('msg_updated', array('{name}'), array($result_message), true);
                break;

            case "ordered":
                $hist_details = showmessage('msg_ordered', array('{name}'), array($result_message), true);
                break;
            case "convert_to_pr":
                $hist_details = showmessage('msg_convert_to_pr', array('{name}'), array($result_message), true);
                break;
            case "added":
                $hist_details = showmessage('msg_added', array('{name}'), array($result_message), true);
                break;

            default:
                $hist_details = '';
                //----------------------------------------------------
        }
        return $hist_details;
    }

    /**
     * This is controller funtion used to Approve / reject PR.
     * @author       Namrata Thakur
     * @access       public
     * @param
     * @param_type   POST array
     * @return       JSON
     * @tables       en_form_data_pr
     */
    public function prpoformActions(Request $request)
    {
        try
        {
            $messages = [
                'pr_po_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_purchaseid')), true),
                'user_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_userid')), true),
                'action.required' => showmessage('000', array('{name}'), array(trans('label.lbl_action')), true),
                'comment.required' => showmessage('000', array('{name}'), array(trans('label.lbl_description_comment')), true),
                'pr_po_type.required' => showmessage('000', array('{name}'), array(trans('label.lbl_actionfor_prpo')), true),
            ];

            $validator = Validator::make($request->all(), [
                'pr_po_id' => 'required|allow_uuid|string|size:36',
                'user_id' => 'required|allow_uuid|string|size:36',
                'action' => 'required|:cancel,delete,close',
                'comment' => 'required',
                'pr_po_type' => 'required',
            ], $messages);

            $validator->after(function ($validator) {
                $request = request();
                $action = $request->has('action') ? $request->input('action') : "";

                if ($action != "" && $action == "invoice") {
                    $id = $request->has('id') ? $request->input('id') : "";
                    if ($id == "") {
                        $validator->errors()->add('id', showmessage('000', array('{name}'), array(trans('label.lbl_invoiceid')), true));
                    } else {
                        $start_with_alpha_numeric_allow_alphal_numeric_dash_underscore_only_validation = $this->validation_start_with_alpha_numeric_allow_alphal_numeric_dash_underscore_only($id);
                        if ($start_with_alpha_numeric_allow_alphal_numeric_dash_underscore_only_validation) {
                            $validator->errors()->add('id', showmessage('007', array('{name}'), array(trans('label.lbl_invoiceid')), true));
                        }
                    }
                    $received_date = $request->has('received_date') ? $request->input('received_date') : "";
                    if ($received_date == "") {
                        $validator->errors()->add('received_date', showmessage('000', array('{name}'), array(trans('label.lbl_receiveddate')), true));
                    }
                    $payment_due_date = $request->has('payment_due_date') ? $request->input('payment_due_date') : "";
                    if ($payment_due_date == "") {
                        $validator->errors()->add('payment_due_date', showmessage('000', array('{name}'), array(trans('label.lbl_paymentduedate')), true));
                    }
                    if (strtotime($received_date) > strtotime($payment_due_date)) {
                        $validator->errors()->add('received_date', showmessage('before_date', array('{date1}', '{date2}'), array(trans('label.lbl_receiveddate'), trans('label.lbl_paymentduedate')), true));
                    }
                    $id = $request->has('id') ? $request->input('id') : "";
                    $formaction = $request->has('formaction') ? $request->input('formaction') : "";

                    if ($id != "" && $request->has('pr_po_id') && $request->input('pr_po_id') != "" && $request->has('invoice_id') && $request->input('invoice_id') != "") {
                        $pr_po_id_bin = DB::raw('UUID_TO_BIN("' . $request->input('pr_po_id') . '")');
                        $invoice_id_bin = DB::raw('UUID_TO_BIN("' . $request->input('invoice_id') . '")');

                        if ($formaction == "edit") {
                            $po_invoice = EnInvoice::where('po_id', $pr_po_id_bin)->where('invoice_id', '!=', $invoice_id_bin)->whereRaw('LOWER(JSON_EXTRACT(details, "$.id")) like ?', ['"%' . strtolower($id) . '%"'])->paginate(5);
                        } else {
                            $po_invoice = EnInvoice::where('po_id', $pr_po_id_bin)->whereRaw('LOWER(JSON_EXTRACT(details, "$.id")) like ?', ['"%' . strtolower($id) . '%"'])->paginate(5);
                        }
                        if (!$po_invoice->isEmpty()) {
                            $validator->errors()->add('id', showmessage('006', array('{name}'), array(trans('label.lbl_invoiceid')), true));
                        }
                    }
                } else {
                    $mail_notification = $request->has('mail_notification') ? $request->input('mail_notification') : "";
                    $mail_notification_subject = $request->has('mail_notification_subject') ? $request->input('mail_notification_subject') : "";
                    $mail_notification_to = $request->has('mail_notification_to') ? $request->input('mail_notification_to') : "";

                    if ($mail_notification == "y" && $mail_notification_subject == "") {
                        $validator->errors()->add('mail_notification', showmessage('000', array('{name}'), array(trans('label.lbl_subject')), true));
                    }
                    if ($mail_notification == "y" && $mail_notification_to == "") {
                        $validator->errors()->add('mail_notification_to', showmessage('000', array('{name}'), array(trans('label.lbl_emailid')), true));
                    }
                }
            });
            if ($validator->fails()) {
                $error = $validator->errors();
                $data['data'] = null;
                $data['message']['error'] = $error;
                $data['status'] = 'error';
            } else {
                DB::beginTransaction(); // begin transaction
                $pr_po_type = $request->input('pr_po_type');
                $notify_to_id = $request->has('notify_to_id') ? $request->input('notify_to_id') : null;
                $pr_po_id_uuid = $request->input('pr_po_id');
                $pr_po_id_bin = DB::raw('UUID_TO_BIN("' . $request->input('pr_po_id') . '")');
                $action = $request->has('action') ? $request->input('action') : "";
                $history_type = $pr_po_type;

                if ($pr_po_type == "pr") {
                    $result = EnPurchaseRequest::where('pr_id', $pr_po_id_bin)->first();
                    $result_message = trans('label.lbl_purchaserequest');
                } else {
                    $result = EnPurchaseOrder::where('po_id', $pr_po_id_bin)->first();
                    $result_message = trans('label.lbl_purchase_order');
                }
                $comment = $request->has('comment') ? $request->input('comment') : "";

                if ($action != "" && $action == "delete") {
                    $action = "deleted";
                }
                if ($action != "" && $action == "close") {
                    $action = "closed";
                }
                if ($action != "" && $action == "cancel") {
                    $action = "cancelled";
                }
                if ($action != "" && $action == "order") {
                    $action = "ordered";
                }
                $flag = 0;
                // $queries    = DB::getQueryLog();
                //$data['last_query'] = end($queries);
                if ($result) {
                    if ($action == "notifyagain" || $action == "notifyowner" || $action == "notifyvendor" || $action == "invoice") {
                        //Add Mail Sending Code
                        if ($action == "invoice") {
                            $formaction = $request->has('formaction') ? $request->input('formaction') : "";
                            $invoice_id = $request->has('invoice_id') ? $request->input('invoice_id') : "";
                            $arrDetails = array();
                            $arrDetails['id'] = $request->has('id') ? $request->input('id') : "";
                            $arrDetails['received_date'] = $request->has('received_date') ? $request->input('received_date') : "";
                            $arrDetails['payment_due_date'] = $request->has('payment_due_date') ? $request->input('payment_due_date') : "";
                            $arrDetails['comment'] = $request->has('comment') ? $request->input('comment') : "";
                            $inputdata['po_id'] = DB::raw('UUID_TO_BIN("' . $pr_po_id_uuid . '")');

                            if ($invoice_id != "") {
                                $inputdata['invoice_id'] = DB::raw('UUID_TO_BIN("' . $invoice_id . '")');
                            } else {
                                $inputdata['invoice_id'] = DB::raw('UUID_TO_BIN(UUID())');
                            }

                            $inputdata['details'] = json_encode($arrDetails);
                            $inputdata['created_by'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');

                            if ($formaction == "edit") {
                                $invoiceData = EnInvoice::where('po_id', $pr_po_id_bin)->where('invoice_id', $inputdata['invoice_id'])->first();
                                if ($invoiceData) {
                                    $invoiceData->update($inputdata);
                                    $invoiceData->save();
                                    $action = 'updated'; //for history
                                    $result_message = trans('label.lbl_invoice');
                                }

                            } else {
                                // DB::enableQueryLog();
                                $result_invoice = EnInvoice::create($inputdata);
                                // $laQuery = DB::getQueryLog();
                                // apilog(json_encode($laQuery));
                                $action = 'created'; //for history
                                $result_message = trans('label.lbl_invoice');
                            }
                            $flag = 1;
                        } elseif ($action == "notifyagain") {
                            if ($notify_to_id) {
                                $notify_to_id = DB::raw('UUID_TO_BIN("' . $notify_to_id . '")');
                            }

                            $flag = 1;
                        } else {
                            $flag = 1;
                        }
                    } else {
                        $update_data = array('status' => $action);
                        $result->update($update_data);
                        $result->save();
                        $flag = 1;
                    }
                    if ($flag == 1) {
                        $hist_details = $this->gethistorydesc($action, $result_message);
                        if ($action == "notifyagain") {
                            //todo:history for notifyagain not working
                            // 
                            $this->prpohistoryadd(array('pr_po_id' => $pr_po_id_uuid, 'history_type' => $history_type, 'action' => $action, 'details' => $hist_details, 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("' . $request->input('user_id') . '")'), 'notify_to_id' => $notify_to_id, 'comment' => $comment));
                            // 

                        } else {
                            $this->prpohistoryadd(array('pr_po_id' => $pr_po_id_uuid, 'history_type' => $history_type, 'action' => $action, 'details' => $hist_details, 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")'), 'notify_to_id' => $notify_to_id, 'comment' => $comment));
                        }
                        userlog(array('record_id' => $pr_po_id_uuid, 'data' => $request->all(), 'action' => 'updated', 'message' => showmessage('140', array('{name}'), array($result_message . ' ' . $action))));

                        $data['data'] = null;
                        $data['message']['success'] = showmessage('140', array('{name}'), array($result_message . '  ' . $action));
                        $data['status'] = 'success';

                        DB::commit();
                    } else {
                        DB::rollBack();
                        $data['data'] = null;
                        $data['message']['error'] = showmessage('139', array('{name}'), array($action . ' ' . $result_message));
                        $data['status'] = 'error';
                    }
                } else {
                    DB::rollBack();
                    $data['data'] = null;
                    $data['message']['error'] = showmessage('139', array('{name}'), array($action . ' ' . $result_message));
                    $data['status'] = 'error';
                }
            }
            return response()->json($data);
        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("prpoformActions", "This controller function is implemented to PR or PO form actions.", $request->all(), $e->getMessage());
            return response()->json($data);
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("prpoformActions", "This controller function is implemented to PR or PO form actions.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }
    //================== Cost Center ADD END ======

    /**
     * This is controller funtion used to delete the Cost Center.
     * @author       Vikas Kumar
     * @access       public
     * @param        URL : cc_id
     * @param_type   Integer
     * @return       JSON
     * @tables       en_cost_Centers
     */
    public function prpoassetdetails(Request $request)
    {
        $messages = [
            'pr_po_id.required' => showmessage('000', array('{name}'), array('PR / PO Id'), true),
            'asset_type.required' => showmessage('000', array('{name}'), array('History Type '), true),
        ];
        $validator = Validator::make($request->all(), [
            'pr_po_id' => 'required|allow_uuid|string|size:36',
            'asset_type' => 'required|in:pr,po',
        ], $messages);
        
        if ($validator->fails()) {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        } else {
            
            //$pr_po_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('pr_po_id').'")');
            if (empty($request->input('po_vendor_id'))) {
                $prpoAssetDetails = EnPrPoAssetDetails::getPrPoAssetDetails($request->input('pr_po_id'), $request['asset_type']);
            } else {
                $prpoAssetDetails = EnPrPoAssetDetails::getPrPoAssetDetails($request->input('pr_po_id'), $request['asset_type'], $request->input('po_vendor_id'), $request->input('pr_po_ids'));
            }    
            /* $prpoAssetDetails = EnPrPoAssetDetails::select(DB::raw('BIN_TO_UUID(pr_po_asset_id) AS pr_po_asset_id'), DB::raw('BIN_TO_UUID(pr_po_id) AS pr_po_id'), 'asset_type', 'asset_details',DB::raw('BIN_TO_UUID(created_by) AS created_by'), 'status', 'created_at', 'updated_at')
            ->where('pr_po_id', $pr_po_id_bin)
            ->where('asset_type', $request['asset_type'])
            ->where('status', '!=', 'd')
            ->orderBy('created_at', 'desc')
            ->get();   */
            /* $queries    = DB::getQueryLog();
            $last_query = end($queries);
            print_r($last_query);*/
            if ($prpoAssetDetails->isEmpty()) {
                $data['data'] = null;
                $data['status'] = 'error';
                $data['message']['error'] = showmessage('101', array('{name}'), array('PR PO Asset'));
            } else {
                //------------ set item_name
                if (!$prpoAssetDetails->isEmpty()) {
                    $ci_asset_detailsArr = array();
                    $i = 0;                    
                    foreach ($prpoAssetDetails as $asset) {
                        $asset_arr = json_decode($asset['asset_details'], true);                        
                        if (isset($asset_arr)) {
                            $ci_asset_details_d = EnCiTemplDefault::getcitemplatesD($asset_arr['item']);
                           
                            $ci_asset_details_c = EnCiTemplCustom::getcitemplatesC($asset_arr['item']);
                            $getallassets = EnAssets::getallassets($asset_arr['item_product']);
                            
            // $data['data'] = $getallassets;
            // $data['message']['success'] = "success Nick";
            // $data['status'] = 'success';
            // return response()->json($data);
                            if (!$ci_asset_details_d->isEmpty()) {
                                if ($prpoAssetDetails[$i]['asset_details']) {
                                    $tmp = json_decode($prpoAssetDetails[$i]['asset_details'], true);
                                    $tmp['item_name'] = $ci_asset_details_d[0]->ci_name;
                                    $tmp['item_product_name'] = $getallassets[0]->display_name;
                                    $tmp['asset_sku'] = $getallassets[0]->asset_sku;
                                    $prpoAssetDetails[$i]['asset_details'] = json_encode($tmp);
                                }
                            } else if (!$ci_asset_details_c->isEmpty()) {
                                if ($prpoAssetDetails[$i]['asset_details']) {
                                    $tmp = json_decode($prpoAssetDetails[$i]['asset_details'], true);
                                    $tmp['item_name'] = $ci_asset_details_c[0]->ci_name;
                                    $tmp['item_product_name'] = $getallassets[0]->display_name;
                                    $tmp['asset_sku'] = $getallassets[0]->asset_sku;
                                    $prpoAssetDetails[$i]['asset_details'] = json_encode($tmp);
                                }
                            } else {
                                if ($prpoAssetDetails[$i]['asset_details']) {
                                    $tmp = json_decode($prpoAssetDetails[$i]['asset_details'], true);
                                    $tmp['item_name'] = '';
                                    $tmp['item_product_name'] = '';
                                    $tmp['asset_sku'] = '';
                                    $prpoAssetDetails[$i]['asset_details'] = json_encode($tmp);
                                }
                            }

                        }
                        $i = $i + 1;
                    }
                }
                //------------
                $data['data'] = $prpoAssetDetails;
                $data['status'] = 'success';
                $data['message']['success'] = showmessage('102', array('{name}'), array('PR PO Asset'));
            }
            return response()->json($data);
        }
    }

    /**
     * This is controller funtion is used to delete a cost center.
     * @author Darshan Chaure
     * @access public
     * @package costcenter
     * @param string $cc_id
     * @return json
     */
    public function costcenterdelete(Request $request, $cc_id = null)
    {
        $request['cc_id'] = $cc_id;
        $validator = Validator::make($request->all(), [
            'cc_id' => 'required|allow_uuid|string|size:36',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        } else {
            $cc_id_uuid = $cc_id;
            //$request['cc_id'] = DB::raw('UUID_TO_BIN("'.$cc_id_uuid.'")');
            $cc = EnCostCenters::where('cc_id', DB::raw('UUID_TO_BIN("' . $cc_id_uuid . '")'))->first();
            if ($cc) {
                $cc->update(array('status' => 'd'));
                $cc->save();
                /*
                $queries    = DB::getQueryLog();
                $last_query = end($queries);
                print_r($last_query); exit; */

                $data['data']['deleted_id'] = $cc_id_uuid;
                $data['message']['success'] = showmessage('118', array('{name}'), array('Cost Center'));
                $data['status'] = 'success';
                //Add into UserActivityLog
                userlog(array('record_id' => $cc_id_uuid, 'data' => $request->all(), 'action' => 'deleted', 'message' => showmessage('118', array('{name}'), array('Cost Center'))));
            } else {
                $data['data'] = null;
                $data['message']['error'] = showmessage('101', array('{name}'), array('Cost Center'));
                $data['status'] = 'error';
            }

            return response()->json($data);
        }
    }

    /**
     * This is controller funtion is used to delete a cost center.
     * @author Darshan Chaure
     * @access public
     * @package costcenter
     * @param string $cc_id
     * @return json
     */
    public function converttopr(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|allow_uuid|string',
            'pr_id' => 'required|allow_uuid|string',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        } else {
            $inv = DB::table('en_pr_po_asset_details')->select(DB::raw('BIN_TO_UUID(pr_po_asset_id) AS pr_po_asset_id'))->where('pr_po_id', DB::raw('UUID_TO_BIN("' . $request['pr_id'] . '")'))->where('convert_status', '=', 'y')->first();
            if (empty($inv)) {
                $inv = DB::table('en_form_data_pr')->select(DB::raw('BIN_TO_UUID(pr_id) AS pr_id'))->where('pr_id', DB::raw('UUID_TO_BIN("' . $request['pr_id'] . '")'))->first();
                if ($inv) {
                    $dtl = $inv->pr_id;
                }

                if ($dtl) {
                    $qry = DB::table('en_form_data_pr')->where('pr_id', DB::raw('UUID_TO_BIN(\'' . $request['pr_id'] . '\')'))->update(['approved_status' => DB::raw('JSON_MERGE_PRESERVE(approved_status, \'{"convert_to_pr": {"approved":"cbd4eb72-1b5c-11ec-b015-4e89be533080"}}\')')]);

                    DB::table('en_pr_po_asset_details')->where('pr_po_id', DB::raw('UUID_TO_BIN(\'' . $request['pr_id'] . '\')'))->update(['convert_status' => 'y']);

                    $result_message = trans('label.lbl_purchaserequest');
                    /*$queries    = DB::getQueryLog();
                    $last_query = end($queries);
                    print_r($last_query); exit; */
                    $pr_id = DB::raw('UUID_TO_BIN(\'' . $request['pr_id'] . '\')');
                    $data['data']['pr_id'] = $dtl;
                    $data['message']['success'] = showmessage('106', array('{name}'), array('Convert to PR'));
                    $data['status'] = 'success';
                    $hist_details = $this->gethistorydesc('convert_to_pr', $result_message);
                    $this->prpohistoryadd(array('pr_po_id' => $request['pr_id'], 'history_type' => 'pr', 'action' => 'Convert to PR', 'details' => $hist_details, 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("' . $request['user_id'] . '")'), 'comment' => ''));
                    //Add into UserActivityLog
                    userlog(array('record_id' => $request['pr_id'], 'data' => $request->all(), 'action' => 'update', 'message' => showmessage('106', array('{name}'), array('Convert to PR'))));

                    user_notification(array('type' => 'cpr', 'message' => 'convert to pr', 'store_user' => DB::raw('UUID_TO_BIN("' . $request['user_id'] . '")'), 'show_user' => '', 'action' => 'add'));

                } else {
                    $data['data'] = null;
                    $data['message']['error'] = showmessage('101', array('{name}'), array('Convert to PR'));
                    $data['status'] = 'error';
                }
            } else {
                $data['data'] = '';
                $data['message']['error'] = 'You can`t convert this PR, Please club to another PR.';
                $data['status'] = 'error';
            }

            return response()->json($data);
        }
    }
    /**
     * This is controller funtion is used to delete a cost center.
     * @author Darshan Chaure
     * @access public
     * @package costcenter
     * @param string $cc_id
     * @return json
     */
    public function assignprtouser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'pr_assign_user_id' => 'required|allow_uuid|string',
            'pr_po_id' => 'required|allow_uuid|string',
            'user_id' => 'required|allow_uuid|string',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        } else {
            $pr_id_bin = DB::raw('UUID_TO_BIN("' . $request['pr_po_id'] . '")');
            $pr_assign_user_id = DB::raw('UUID_TO_BIN("' . $request['pr_assign_user_id'] . '")');
            $invoiceData = EnPurchaseRequest::where('pr_id', $pr_id_bin)->first();
            if ($invoiceData) {

                $invoiceData = EnPurchaseRequest::where('pr_id', $pr_id_bin)->update(['assignpr_user_id' => $pr_assign_user_id]);

                /*$queries    = DB::getQueryLog();
                $data['data']['last_query'] = end($queries);*/
                //$result_message = 'PR assigned to user';

                $hist_details = 'Purchase request assigned to ' . $request['pr_assign_user_name'];
                $this->prpohistoryadd(array('pr_po_id' => $request['pr_po_id'], 'history_type' => 'pr', 'action' => 'updated', 'details' => $hist_details, 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("' . $request['user_id'] . '")'), 'comment' => ''));
                //Add into UserActivityLog
                userlog(array('record_id' => $request['pr_po_id'], 'data' => $request->all(), 'action' => 'update', 'message' => showmessage('106', array('{name}'), array('pr assigned to user'))));

                user_notification(array('type' => 'apr', 'message' => 'assign pr', 'store_user' => DB::raw('UUID_TO_BIN("' . $request['user_id'] . '")'), 'show_user' => $pr_assign_user_id, 'action' => 'add'));

                $data['data'] = $request['pr_po_id'];
                $data['message']['success'] = showmessage('101', array('{name}'), array('User assigned to PR'));
                $data['status'] = 'success';

            } else {
                $data['data'] = null;
                $data['message']['error'] = showmessage('101', array('{name}'), array('User assigned to PR failed'));
                $data['status'] = 'error';
            }

            return response()->json($data);
        }
    }
    //================== Cost Center Delete END ======

    /**
     * Provides a window to user to update the Cost Cneter's information.
     * @author       Vikas Kumar
     * @access       public
     * @param        URL : cc_id
     * @param_type   Integer
     * @return       JSON
     * @tables       en_cost_centers
     */
    public function costcenteredit(Request $request, $cc_id = null)
    {
        $request['cc_id'] = $cc_id;
        $validator = Validator::make($request->all(), [
            'cc_id' => 'required|allow_uuid|string|size:36',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        } else {
            $result = EnCostCenters::getcostcenters($cc_id);
            $data['data'] = $result->isEmpty() ? null : $result;

            if ($data['data']) {
                $data['message']['success'] = showmessage('102', array('{name}'), array('Cost Center'));
                $data['status'] = 'success';
            } else {
                $data['message']['error'] = showmessage('101', array('{name}'), array('Cost Center'));
                $data['status'] = 'error';
            }
            return response()->json($data);
        }
    }
    //===== Cost Center Edit END ===========

    /**
     * Updates the Pods information, which is entered by user on Edit Cost Centers window.
     * @author       Vikas Kumar
     * @access       public
     * @param        cc_code, cc_name, cc_description, owner_id,locations,departments, status
     * @param_type   POST array
     * @return       JSON
     * @tables       en_cost_centers
     */

    public function costcenterupdate(Request $request)
    {
        $messages = [];
        $validator = Validator::make($request->all(), [
            'cc_code' => 'required',
            'cc_name' => 'required',
            'location' => 'required',
            'departments' => 'required',
        ], $messages);

        if ($validator->fails()) {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        } else {
            $cc_id_uuid = $request->input('cc_id');
            $request['cc_id'] = DB::raw('UUID_TO_BIN("' . $request->input('cc_id') . '")');

            $result = EnCostCenters::where('cc_id', $request['cc_id'])->first();
            if ($result) {
                $inputdata = $request->all();
                $inputdata['departments'] = json_encode($request->departments, true);
                $inputdata['locations'] = DB::raw('UUID_TO_BIN("' . $request->location . '")');
                //$cc_data = EnCostCenters::create($inputdata);

                $result->update($inputdata);
                $result->save();
                $data['data'] = null;
                $data['message']['success'] = showmessage('106', array('{name}'), array('Cost Center'));
                $data['status'] = 'success';
                //Add into UserActivityLog
                userlog(array('record_id' => $cc_id_uuid, 'data' => $request->all(), 'action' => 'updated', 'message' => showmessage('106', array('{name}'), array('Cost Center'))));
            } else {
                $data['data'] = null;
                $data['message']['error'] = showmessage('101', array('{name}'), array('Cost Center'));
                $data['status'] = 'error';
            }
            return response()->json($data);
        }
    }
    /**
     * This is funtion used to create History of PR PO.
     * @author Namrata Thakur
     * @access public
     * @package purchase
     * @param \Illuminate\Http\Request $request
     * @return json
     *
     */
    //Note: For parameter value "$prpohistorylogdata['details']" set language translation keyword (ex. msg_approved)
    public function prpohistoryadd($prpohistorylogdata = array())
    {
        /*  $messages = [
        'pr_po_id.required' => showmessage('000', array('{name}'), array('PR / PO Id '), true),
        'history_type.required' => showmessage('000', array('{name}'), array('History Type'), true),
        'action.required' => showmessage('000', array('{name}'), array('action '), true),
        'details.required' => showmessage('000', array('{name}'), array('Details'), true),
        'details.html_tags_not_allowed' => showmessage('001', array('{name}'), array('Details'), true),
        'created_by.required' => showmessage('000', array('{name}'), array('Created by'), true),
        ];
        $validator = Validator::make($request->all(), [
        'pr_po_id' => 'required|string|size:36',
        'history_type' => 'required|in:pr,po',
        'action' => 'required|in:pending approval,open,partially approved,approved,partially received,item received,closed,cancelled,deleted',
        'details' => 'required|html_tags_not_allowed',
        'created_by' => 'required|string|size:36',
        ], $messages);

        if($validator->fails())
        {
        $error = $validator->errors();
        $data['data'] = null;
        $data['message']['error'] = $error;
        $data['status'] = 'error';
        return response()->json($data);
        }
        else
        {  */
        $inputdata = $prpohistorylogdata;
        $pr_po_id = $inputdata['pr_po_id'];
        $created_by = $inputdata['created_by'];
        $inputdata['pr_po_id'] = DB::raw('UUID_TO_BIN("' . $pr_po_id . '")');
        $inputdata['created_by'] = $created_by;

        $pr_po_history = EnPrPoHistory::create($inputdata);
        if (!empty($pr_po_history['history_id'])) {
            /* $data['data']['insert_id'] = $pr_po_history->history_id_text;
            $data['message']['success'] = showmessage('104', array('{name}'), array('PR PO History'));
            $data['status'] = 'success';*/
            //Add into UserActivityLog

            userlog(array('record_id' => $pr_po_history->history_id_text, 'data' => $prpohistorylogdata, 'action' => 'added', 'message' => showmessage('104', array('{name}'), array('PR PO History'))));

            return true;
        } else {
            /*$data['data'] = null;
            $data['message']['error'] = showmessage('103', array('{name}'), array('PR PO History'));
            $data['status'] = 'error';*/
            return false;
        }
        // return response()->json($data);
        //}
    }
    /**
     * This is funtion used to List PR PO History Log
     * @author Namrata Thakur
     * @access public
     * @package purchase
     * @param \Illuminate\Http\Request $request
     * @return json
     *
     */

    public function prpohistorylog(Request $request)
    {
        $messages = [
            'pr_po_id.required' => showmessage('000', array('{name}'), array('PR / PO Id'), true),
            'history_type.required' => showmessage('000', array('{name}'), array('History Type '), true),
        ];
        $validator = Validator::make($request->all(), [
            'pr_po_id' => 'required|allow_uuid|string|size:36',
            'history_type' => 'required|in:pr,po',
        ], $messages);
        if ($validator->fails()) {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        } else {
            $pr_po_id_bin = DB::raw('UUID_TO_BIN("' . $request->input('pr_po_id') . '")');
            $prpohistorylog = EnPrPoHistory::select(DB::raw('BIN_TO_UUID(history_id) AS history_id'), DB::raw('BIN_TO_UUID(pr_po_id) AS pr_po_id'), 'history_type', 'action', 'details', DB::raw('BIN_TO_UUID(created_by) AS created_by'), 'status', 'created_at', 'updated_at', 'comment')
                ->where('pr_po_id', $pr_po_id_bin)
                ->where('history_type', $request['history_type'])
                ->where('status', '!=', 'd')
                ->orderBy('created_at', 'desc')
                ->get();
            if ($prpohistorylog->isEmpty()) {
                $data['data'] = null;
                $data['status'] = 'error';
                $data['message']['error'] = showmessage('101', array('{name}'), array('PR PO History'));
            } else {
                $creatde_at_arr = array();
                foreach ($prpohistorylog as $i => $history) {
                    $created_at = date("d F Y", strtotime($history['created_at']));
                    if (!in_array($created_at, $creatde_at_arr, true)) {
                        array_push($creatde_at_arr, $created_at);
                        $prpohistorylog[$i]['history_date'] = $created_at;
                    } else {
                        $prpohistorylog[$i]['history_date'] = "";
                    }
                }
                $data['data'] = $prpohistorylog;
                $data['status'] = 'success';
                $data['message']['success'] = showmessage('102', array('{name}'), array('PR PO History'));
            }
            return response()->json($data);
        }
    }
    /**
     * This is funtion used to PR PO attachment
     * @author Namrata Thakur
     * @access public
     * @package purchase
     * @param \Illuminate\Http\Request $request
     * @return json
     *
     */

    public function prpoattachment(Request $request)
    {
        $messages = [
            'pr_po_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_prpoid')), true),
            'attachment_type.required' => showmessage('000', array('{name}'), array(trans('label.lbl_attachmenttype')), true),
        ];
        $validator = Validator::make($request->all(), [
            'pr_po_id' => 'required|allow_uuid|string|size:36',
            'attachment_type' => 'required|in:pr,po,qu',
        ], $messages);
        if ($validator->fails()) {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        } else {
            $prpoattachment = EnPrPoAttachment::getAttachments($request->input('pr_po_id'), $request->input('attachment_type'));
            if ($prpoattachment->isEmpty()) {
                $data['data'] = null;
                $data['status'] = 'success';
                $data['message']['success'] = showmessage('102', array('{name}'), array(trans('label.lbl_attachment')));
            } else {
                $data['data'] = $prpoattachment;
                $data['status'] = 'success';
                $data['message']['success'] = showmessage('101', array('{name}'), array(trans('label.lbl_attachment')));
            }
            return response()->json($data);
        }
    }
    /**
     * This is funtion used to delete attachment
     * @author Namrata Thakur
     * @access public
     * @package purchase
     * @param \Illuminate\Http\Request $request
     * @return json
     *
     */

    public function deleteattachment(Request $request)
    {
        $messages = [
            'attach_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_attachmentid')), true),
        ];
        $validator = Validator::make($request->all(), [
            'attach_id' => 'required|allow_uuid|string|size:36',
        ], $messages);
        if ($validator->fails()) {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        } else {
            $attach_id = DB::raw("UUID_TO_BIN('" . $request['attach_id'] . "')");
            $pr_po_id = DB::raw("UUID_TO_BIN('" . $request['pr_po_id'] . "')");
            $prpoattachment = EnPrPoAttachment::where('attach_id', $attach_id)->first();
            if ($prpoattachment) {
                $attachment_name = $prpoattachment['attachment_name'];
                $prpoattachment->update(array('status' => 'd'));
                $prpoattachment->save();
                if (!unlink($attachment_name)) {
                    $data['data'] = null;
                    $data['status'] = 'error';
                    $data['message']['error'] = showmessage('119', array('{name}'), array('Purchase Attached File '));
                } else {
                    $data['data'] = null;
                    $data['status'] = 'success';
                    $data['message']['success'] = showmessage('118', array('{name}'), array('Purchase Attached File'));

                    //save delete attachment history
                    $hist_details = $this->gethistorydesc('deleted', trans('label.lbl_attachment'));

                    //Add into Purchase History
                    $this->prpohistoryadd(array('pr_po_id' => $request->input('pr_po_id'), 'history_type' => $request['attachment_type'], 'action' => 'deleted', 'details' => $hist_details, 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")')));
                    //Add into UserActivityLog
                    userlog(array('record_id' => $request->input('pr_po_id'), 'data' => $request->all(), 'action' => 'deleted', 'message' => showmessage('118', array('{name}'), array(trans('label.lbl_attachment')))));
                }
            } else {
                $data['data'] = null;
                $data['status'] = 'error';
                $data['message']['error'] = showmessage('119', array('{name}'), array('Purchase Attached File '));
            }

            return response()->json($data);
        }
    }

    /*================= Purchase Order ========================*/
    /**
     * This is controller funtion used to list PO.
     * @author       Namrata Thakur
     * @access       public
     * @param        URL : po_id [Optional]
     * @param_type   Integer
     * @return       JSON
     * @tables       en_form_data_po
     */

    public function purchaseorders(Request $request, $po_id = null)
    {
        try
        {
            $request['po_id'] = $po_id;
            $validator = Validator::make($request->all(), [
                'po_id' => 'nullable|allow_uuid|string|size:36',
            ]);
            if ($validator->fails()) {
                $error = $validator->errors();
                $data['data'] = null;
                $data['message']['error'] = $error;
                $data['status'] = 'error';
                return response()->json($data);
            } else {
                $inputdata = $request->all();
                $inputdata['pr_type'] = trim(_isset($inputdata, 'pr_type'));
                $inputdata['searchkeyword'] = trim(_isset($inputdata, 'searchkeyword'));

                $totalrecords = EnPurchaseOrder::getpos($po_id, $inputdata, true);
                $result = EnPurchaseOrder::getpos($po_id, $inputdata, false);
                //$queries    = DB::getQueryLog();
                // $data['data']['last_query'] = end($queries);
                //$result = (array)$result;
                //print_r($result); exit;

                if ($result) {
                    foreach ($result as $key => $each_po) {
                        $po_details = $each_po->details;
                        $po_details_arr = json_to_array($po_details);
                        $each_po->details = $po_details_arr;
                        //get delivery term
                        $pr_delivery = isset($po_details_arr['pr_delivery']) ? $po_details_arr['pr_delivery'] : "";
                        if ($pr_delivery) {
                            $pr_delivery_details = EnDelivery::getdelivery($pr_delivery);
                            // $data['data']['last_query'] = $pr_delivery_details[0]->delivery;
                            if ($pr_delivery_details->isEmpty()) {
                                $each_po->pr_delivery = null;
                            } else {
                                $each_po->pr_delivery = $pr_delivery_details[0]->delivery;
                            }
                        } else {
                            $each_po->pr_delivery = array();
                        }

                        //get Payment Terms
                        $pr_payment_terms = isset($po_details_arr['pr_payment_terms']) ? $po_details_arr['pr_payment_terms'] : "";
                        if ($pr_payment_terms) {
                            $pr_payment_terms_details = EnPaymentterms::getpaymentterms($pr_payment_terms);
                            // $data['data']['last_query'] = $pr_payment_terms_details[0]->delivery;
                            if ($pr_payment_terms_details->isEmpty()) {
                                $each_po->pr_payment_terms = null;
                            } else {
                                $each_po->pr_payment_terms = $pr_payment_terms_details[0]->payment_term;
                            }
                        } else {
                            $each_po->pr_payment_terms = array();
                        }

                        //get vendor details
                        $po_vendor = isset($po_details_arr['pr_vendor']) ? $po_details_arr['pr_vendor'] : "";
                        if ($po_vendor) {
                            $vendor_details = EnVendors::getvendors($po_vendor);
                            if ($vendor_details->isEmpty()) {
                                $each_po->vendor_details = null;
                            } else {
                                $each_po->vendor_details = $vendor_details[0];
                            }
                        } else {
                            $each_po->vendor_details = array();
                        }

                        //get Bill To details
                        $po_billto = isset($po_details_arr['pr_billto']) ? $po_details_arr['pr_billto'] : "";
                        if ($po_billto) {
                            $billto_details = EnBillTo::getbilltos($po_billto);
                            if ($billto_details->isEmpty()) {
                                $each_po->billto_details = null;
                            } else {
                                $each_po->billto_details = $billto_details[0];
                            }
                        } else {
                            $each_po->billto_details = array();
                        }

                        //get Ship To details
                        $po_shipto = isset($po_details_arr['pr_shipto']) ? $po_details_arr['pr_shipto'] : "";
                        if ($po_shipto) {
                            $shipto_details = EnShipTo::getshiptos($po_shipto);
                            if ($shipto_details->isEmpty()) {
                                $each_po->shipto_details = null;
                            } else {
                                $each_po->shipto_details = $shipto_details[0];
                            }
                        } else {
                            $each_po->shipto_details = array();
                        }

                        //get bill To Contact Details
                        $po_billto_contact = isset($po_details_arr['pr_billto_contact']) ? $po_details_arr['pr_billto_contact'] : "";
                        if ($po_billto_contact) {
                            $billto_contact_details = EnContacts::getcontacts_billto($po_billto_contact);
                            if ($billto_contact_details->isEmpty()) {
                                $each_po->billto_contact_details = null;
                            } else {
                                $each_po->billto_contact_details = $billto_contact_details[0];
                            }
                        } else {
                            $each_po->billto_contact_details = array();
                        }

                        //get Ship To Contact Details
                        $po_shipto_contact = isset($po_details_arr['pr_shipto_contact']) ? $po_details_arr['pr_shipto_contact'] : "";
                        if ($po_shipto_contact) {
                            $shipto_contact_details = EnContacts::getcontacts_shipto($po_shipto_contact);
                            if ($shipto_contact_details->isEmpty()) {
                                $each_po->shipto_contact_details = null;
                            } else {
                                $each_po->shipto_contact_details = $shipto_contact_details[0];
                            }
                        } else {
                            $each_po->shipto_contact_details = array();
                        }
                        /* To Fetch Asset Name START */
                        /*$po_details_asset = $each_po->asset_details;
                        $pr_details_asset_arr = json_to_array($pr_details_asset);
                        $each_po->asset_details = $pr_details_asset_arr;
                        //get Asset CI details
                        $ci_id = isset($pr_details_asset_arr['ci_name']) ? $pr_details_asset_arr['ci_name'] : "";*/

                        $prpoAssetDetails = EnPrPoAssetDetails::getPrPoAssetDetails($each_po->po_id, "po");
                        /*$queries    = DB::getQueryLog();
                        $last_query = end($queries);
                        print_r($last_query);*/
                        if (!$prpoAssetDetails->isEmpty()) {
                            $ci_asset_detailsArr = array();
                            foreach ($prpoAssetDetails as $asset) {
                                $asset_arr = json_decode($asset['asset_details'], true);
                                if (isset($asset_arr)) {
                                    $ci_asset_details_d = EnCiTemplDefault::getcitemplatesD($asset_arr['item']);
                                    $ci_asset_details_c = EnCiTemplCustom::getcitemplatesC($asset_arr['item']);

                                    if (!$ci_asset_details_d->isEmpty()) {
                                        $each_po->ci_asset_details[$asset_arr['item']] = $ci_asset_details_d[0]->ci_name;
                                        $each_po->ci_asset_details[$ci_asset_details_d[0]->ci_name] = $ci_asset_details_d[0]->ci_sku;

                                        $each_po->ci_cutype_details[$asset_arr['item']] = "default";
                                        $each_po->ci_type_id_details[$asset_arr['item']] = $ci_asset_details_d[0]->ci_type_id;

                                    } else if (!$ci_asset_details_c->isEmpty()) {
                                        $each_po->ci_asset_details[$asset_arr['item']] = $ci_asset_details_c[0]->ci_name;
                                        $each_po->ci_asset_details[$ci_asset_details_c[0]->ci_name] = $ci_asset_details_c[0]->ci_sku;

                                        $each_po->ci_cutype_details[$asset_arr['item']] = "custom";
                                        $each_po->ci_type_id_details[$asset_arr['item']] = $ci_asset_details_c[0]->ci_type_id;
                                    } else {
                                        $each_po->ci_asset_details[$asset_arr['item']] = null;
                                        $each_po->ci_cutype_details[$asset_arr['item']] = null;
                                        $each_po->ci_type_id_details[$asset_arr['item']] = null;
                                    }

                                    //set received asset count
                                    $cnt = 0;
                                    $po_id_bin = DB::raw('UUID_TO_BIN("' . $po_id . '")');
                                    $cnt = DB::table('en_assets')->where('po_id', $po_id_bin)->where('display_name', $each_po->ci_asset_details[$asset_arr['item']])->count();

                                    $each_po->ci_asset_received_count[$asset_arr['item']] = $cnt;
                                }
                            }
                        } else {
                            $each_po->ci_asset_details = array();
                        }

                        $pr_details_approval = $each_po->approval_details;
                        $pr_details_approval_arr = json_to_array($pr_details_approval);
                        $each_po->approval_details = $pr_details_approval_arr;
                        /* To Fetch Asset Name END  */
                    }
                }
                //print_r($result);
                $data['data']['records'] = $result->isEmpty() ? null : $result;
                $data['data']['totalrecords'] = $totalrecords;

                if ($totalrecords < 1) {
                    $data['message']['success'] = showmessage('101', array('{name}'), array(trans('label.lbl_purchase_order')));
                } else {
                    $data['message']['success'] = showmessage('102', array('{name}'), array(trans('label.lbl_purchase_order')));
                }

                $data['status'] = 'success';

            }
        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("purchaseorders", "This controller function is implemented to show PO list.", $request->all(), $e->getMessage());
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("purchaseorders", "This controller function is implemented to show PO list.", $request->all(), $e->getMessage());
        } finally {
            return response()->json($data);
        }
    }
    /**
     * This function is used to encode user image, file upload
     * @author Kavita Daware
     * @access public
     * @package user
     * @param \Illuminate\Http\Request $request
     * @param UUID $user_ids
     * @return json
     *
     * */

    /*------------- Quotation Comparison Functions Start -----------*/

    /* Submit Quotation Comparison Reject Comment */
    public function prpoapprovereject_qc(Request $request)
    {
        $pr_po_id = DB::raw('UUID_TO_BIN("' . $request['pr_po_id'] . '")');
        $approve_reject_by = DB::raw('UUID_TO_BIN("' . $request['created_by'] . '")');
        $comment = $request['comment'];
        $approval_status = $request['approval_status'];

        $result_message = 'Quotation Comparison';
        $hist_details = $this->gethistorydesc($approval_status, $result_message);
        $this->prpohistoryadd(array('pr_po_id' => $request['pr_po_id'], 'history_type' => 'pr', 'action' => $approval_status, 'details' => $hist_details, 'comment' => $comment,
        'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("' . $request['created_by'] . '")')));

        $res = EnPrPoQuotationcomparisonReject::where('pr_po_id', $pr_po_id)->whereNotNull(DB::raw('JSON_EXTRACT(vendor_approve,"$.vendor_id")'));
        $update_array = array('reject_comment' => $comment, 'approve_reject_by' => $approve_reject_by, 'approval' => $approval_status);
        $res->update($update_array);
        // $res->save();

        if ($approval_status == 'approved') {
            user_notification(array('type' => 'qa', 'message' => 'quotation approved', 'store_user' => DB::raw('UUID_TO_BIN("' . $request['created_by'] . '")'), 'show_user' => '', 'action' => 'add'));

        } else {

            user_notification(array('type' => 'qr', 'message' => 'quotation rejected', 'store_user' => DB::raw('UUID_TO_BIN("' . $request['created_by'] . '")'), 'show_user' => '', 'action' => 'add'));

        }

        $data['data'] = json_encode($pr_po_id);
        $data['message']['success'] = 'success';
        $data['status'] = 'success';
        return $data;
    }
    /* Submit Quotation Comparison Approve */
    public function quotation_comparison_approval(Request $request)
    {
        $pr_po_id = DB::raw('UUID_TO_BIN("' . $request['pr_po_id'] . '")');
        $selected_item_id = DB::raw('UUID_TO_BIN("' . $request['selected_item_id'] . '")');
        $updated_by = DB::raw('UUID_TO_BIN("' . $request['created_by'] . '")');
        $vendor_approve = $request['vendor_approve'];
        $approve_reject_by = DB::raw('UUID_TO_BIN("' . $request['approve_reject_by'] . '")');
        $approval = $request['approval'];
        $reject_comment = $request['reject_comment'];

        $res = EnPrPoQuotationcomparison::where('pr_po_id', $pr_po_id)->where('selected_item_id', $selected_item_id)
            ->whereNull(DB::raw('JSON_EXTRACT(vendor_approve,"$.converted_as_po")'))
            ->first();
        $update_array = array('vendor_approve' => $vendor_approve, 'reject_comment' => $reject_comment, 'approve_reject_by' => $approve_reject_by, 'approval' => $approval);
        $res->update($update_array);

        $result_message = 'Quotation Comparison Approval';
        $hist_details = $this->gethistorydesc('updated', $result_message);
        $this->prpohistoryadd(array('pr_po_id' => $request['pr_po_id'], 'history_type' => 'pr', 'action' => 'updated', 'details' => $hist_details, 'comment' => 'NA', 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("' . $request['created_by'] . '")')));

        $data['data'] = json_encode($pr_po_id);
        $data['message']['success'] = 'success';
        $data['status'] = 'success';
        return $data;
    }
    /* Select items by vendors then submit Quotation Comparisons */
    public function quotation_comparison_final(Request $request)
    {
        $pr_po_id = DB::raw('UUID_TO_BIN("' . $request['pr_po_id'] . '")');
        $selected_item_id = DB::raw('UUID_TO_BIN("' . $request['selected_item_id'] . '")');
        $updated_by = DB::raw('UUID_TO_BIN("' . $request['created_by'] . '")');
        $vendor_approve = $request['vendor_approve'];

        $res = EnPrPoQuotationcomparison::where('pr_po_id', $pr_po_id)->where('selected_item_id', $selected_item_id)
            ->whereNull(DB::raw('JSON_EXTRACT(vendor_approve,"$.converted_as_po")'))
            ->first();
        $update_array = array('vendor_approve' => $vendor_approve, 'updated_by' => $updated_by);
        $res->update($update_array);

        $result_message = 'Quotation Comparison';
        $hist_details = $this->gethistorydesc('updated', $result_message);
        $this->prpohistoryadd(array('pr_po_id' => $request['pr_po_id'], 'history_type' => 'pr', 'action' => 'updated', 'details' => $hist_details, 'comment' => 'NA', 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("' . $request['created_by'] . '")')));

        user_notification(array('type' => 'qg', 'message' => 'quotation generated', 'store_user' => DB::raw('UUID_TO_BIN("' . $request['created_by'] . '")'), 'show_user' => '', 'action' => 'add'));

        $data['data'] = json_encode($pr_po_id);
        $data['message']['success'] = 'success';
        $data['status'] = 'success';
        return $data;
    }
    /* Show All items and vendor Quotation Details */
    public function quotation_comparison_details(Request $request)
    {
        $inputdata['pr_po_id'] = $request['pr_po_id'];
        $res = EnPrPoQuotationcomparison::getQuotationcomparisonDetails($inputdata);
        $data['data'] = json_encode($res);
        $data['message']['success'] = 'success';
        $data['status'] = 'success';
        return $data;
    }
    /* Show item (Edit) details when select item from details page */
    public function quotation_comparison_edit(Request $request)
    {
        $inputdata['pr_po_id'] = $request['pr_po_id'];
        $inputdata['selected_item_id'] = $request['selected_item_id'];
        $res = EnPrPoQuotationcomparison::getQuotationcomparison($inputdata);
        $data['data'] = json_encode($res);
        $data['message']['success'] = 'success';
        $data['status'] = 'success';
        return $data;
    }
    /* Each item Quotation Comparison add */
    public function quotation_comparison_save(Request $request)
    {
        $inputdata['created_by'] = DB::raw('UUID_TO_BIN("' . $request['created_by'] . '")');
        $inputdata['pr_po_id'] = DB::raw('UUID_TO_BIN("' . $request['pr_po_id'] . '")');
        $inputdata['selected_item_id'] = DB::raw('UUID_TO_BIN("' . $request['selected_item_id'] . '")');
        $inputdata['quotation_comparison_data'] = $request['quotation_comparison_data'];
        $inputdata['status'] = 'y';
        $inputdata['selected_item_name'] = $request['selected_item_name'];
        $inputdata['approve_vendor_id'] = DB::raw('UUID_TO_BIN("' . $request['approve_vendor_id'] . '")');
        $inputdata['approve_option'] = $request['approve_option'];

        $pr_po_id = DB::raw('UUID_TO_BIN("' . $request['pr_po_id'] . '")');
        $selected_item_id = DB::raw('UUID_TO_BIN("' . $request['selected_item_id'] . '")');
        $res = EnPrPoQuotationcomparison::where('pr_po_id', $pr_po_id)->where('selected_item_id', $selected_item_id)->first();
        if ($res) {
            $updated_by = DB::raw('UUID_TO_BIN("' . $request['created_by'] . '")');
            $update_array = array('quotation_comparison_data' => $request['quotation_comparison_data'], 'updated_by' => $updated_by);
            $res->update($update_array);
            $res->save();

            $result_message = 'Quotation';
            $hist_details = $this->gethistorydesc('updated', $result_message);
            $this->prpohistoryadd(array('pr_po_id' => $request['pr_po_id'], 'history_type' => 'pr', 'action' => 'updated', 'details' => $hist_details, 'comment' => 'NA', 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("' . $request['created_by'] . '")')));
        } else {

            $result_message = 'Quotation';
            $hist_details = $this->gethistorydesc('added', $result_message);
            $this->prpohistoryadd(array('pr_po_id' => $request['pr_po_id'], 'history_type' => 'pr', 'action' => 'added', 'details' => $hist_details, 'comment' => 'NA', 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("' . $request['created_by'] . '")')));

            $res = EnPrPoQuotationcomparison::create($inputdata);
        }
        //$res = EnPrPoQuotationcomparison::create($inputdata);

        $data['data'] = null;
        $data['message']['success'] = json_encode($inputdata);
        $data['status'] = 'success';
        return $data;
    }

    /*------------- Quotation Comparison Functions Close -----------*/

    public function save_estimatecost(Request $request)
    {
        $data = array();
        $created_by = DB::raw('UUID_TO_BIN("' . $request['created_by'] . '")');
        $pr_po_id = DB::raw('UUID_TO_BIN("' . $request['pr_po_id'] . '")');
        $estimate_cost = $request['estimate_cost'];
        $balanced_budget = $request['balanced_budget'];
        $comment = $data_status = $status = '';
        $result_message = 'Estimate Cost';

        if ($estimate_cost > $balanced_budget) {
            $comment = 'The request item(s) amount is more than department budget.';
            $status = "rejected";
            $data_status = 'error';
            $result = EnPurchaseRequest::where('pr_id', $pr_po_id)->first();
            if ($result) {
                $result->update(['estimate_status' => $status, 'estimate_cost' => $estimate_cost, 'estimate_cost_comment' => $comment]);
                $result->save();
                $hist_details = $this->gethistorydesc($status, $result_message);
            }
        } else {
            $result = EnPurchaseRequest::where('pr_id', $pr_po_id)->first();
            if ($result) {
                $comment = 'Verified & Approved Estimate Cost.';
                $status = "approved";
                $data_status = 'success';

                $result->update(['estimate_status' => $status, 'estimate_cost' => $estimate_cost, 'estimate_cost_comment' => $comment]);
                $result->save();
                $hist_details = $this->gethistorydesc($status, $result_message);
            }
        }

        $this->prpohistoryadd(array('pr_po_id' => $request['pr_po_id'], 'history_type' => 'pr', 'action' => 'approved', 'details' => $hist_details, 'comment' => $comment, 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("' . $request['created_by'] . '")')));

        $data['data'] = $status;
        $data['message'][$data_status] = $comment;
        $data['status'] = $data_status;
        return $data;
    }

    public function fileupload_pr_extra(Request $request)
    {
        $actual_path = 'uploads/purchase/';
        $target_dir = public_path($actual_path);
        header('Content-Type: application/json');
        $saveimg = $request['saveimg'];
        $file_dir = $target_dir . "/" . $saveimg;
        $decoded_file = base64_decode($request['files_content']); // decode the file
        if (file_put_contents($file_dir, $decoded_file)) {
            $customer_po_file_name = $actual_path . $saveimg;
            $inputdata['pr_po_id'] = DB::raw('UUID_TO_BIN("' . $request['pr_po_id'] . '")');
            $inputdata['attachment_name'] = $customer_po_file_name;
            $inputdata['created_by'] = $request['created_by'];
            $inputdata['file_title'] = $request['file_title'];
            $inputdata['type'] = 'document';
            $inputdata['attachment_type'] = 'pr';
            $inputdata['status'] = 'y';
            //echo '<pre>'; print_r($inputdata); echo '</pre>';
            $res = EnPrPoAttachment::create($inputdata);
            $data['data'] = null;
            $data['message']['success'] = json_encode($res);
            $data['status'] = 'success';
            return $data;
        }
    }
    public function fileupload(Request $request)
    {

        $data['data'] = $request;
        $username = $request['ENUSERNAME'];
        $inputdata = array();

        $validator = Validator::make($request->all(), [
            'pr_po_id' => 'required',
            'type' => 'required',
            'attachment_type' => 'required',
            'user_id' => 'required',
            'file' => 'required',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        }
        try
        {
            $inputdata['pr_po_id'] = DB::raw('UUID_TO_BIN("' . $request->input('pr_po_id') . '")');
            if ($request['attachment_type'] == 'qu') {
                $inputdata['pr_vendor_id'] = DB::raw('UUID_TO_BIN("' . $request->input('pr_vendor_id') . '")');

            }

            $inputdata['type'] = $request['type'];
            $inputdata['file_ext'] = $request['file_ext'];
            $inputdata['attachment_type'] = $request['attachment_type'];
            $inputdata['created_by'] = $request['user_id'];
            $actual_path = 'uploads/purchase/';
            $target_dir = public_path($actual_path); // add the specific path to save the file
            header('Content-Type: application/json');
            foreach ($request->input('file') as $key => $filename) {
                $file_ext = ($request->input('file_ext'))[$key];
                $saveimg = "purchase_request_attachments_" . $key . "_" . time() . ".$file_ext";
                //$target_dir ='/var/www/application/public/uploads/purchase/'; // add the specific path to save the file

                //$decoded_file = base64_decode($request->input('file')); // decode the file
                $file_dir = $target_dir . "/" . $saveimg;
                $decoded_file = base64_decode($filename); // decode the file

                if (file_put_contents($file_dir, $decoded_file)) {

                    $inputdata['attachment_name'] = $actual_path . $saveimg;
                    // $request['file']=$saveimg;
                    $pr_po_history = EnPrPoAttachment::create($inputdata);
                    //   $success=$this->updateprofilephoto($request);

                    $data['data'] = null;
                    $data['message']['success'] = showmessage('144', array('{name}'), array(trans('label.lbl_attachment'))); //144/ 145
                    $data['status'] = 'success';

                    //  return response()->json($data);

                    //save upload attachment history
                    $hist_details = $this->gethistorydesc('created', trans('label.lbl_attachment'));

                    //Add into Purchase History
                    $this->prpohistoryadd(array('pr_po_id' => $request->input('pr_po_id'), 'history_type' => $request['attachment_type'], 'action' => 'open', 'details' => $hist_details, 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")')));
                    //Add into UserActivityLog
                    userlog(array('record_id' => $request->input('pr_po_id'), 'data' => $request->all(), 'action' => 'added', 'message' => showmessage('104', array('{name}'), array(trans('label.lbl_attachment')))));
                } else {

                    $data['data'] = null;
                    $data['message']['error'] = showmessage('145', array('{name}'), array(trans('label.lbl_attachment'))); //144/ 145
                    $data['status'] = 'error';

                }
            }
            return response()->json($data);
        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("fileupload", "This controller function is implemented to upload file.", $request->all(), $e->getMessage());
            return response()->json($data);
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("fileupload", "This controller function is implemented to upload file.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }

    /**
     * This is controller funtion is used to download attachment
     * @author Darshan Chaure
     * @access public
     * @package purchaseorder
     * @param string $attach_id
     * @param string $attach_path
     * @return json
     */
    public function downloadattachment_pr(Request $request)
    {
        $inputdata = $request->all();
        $res = '';
        $messages = [
            'attach_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_attachmentid')), true),
            'attach_path.required' => showmessage('000', array('{name}'), array(trans('label.lbl_attachmentpath')), true),
        ];
        $validator = Validator::make($request->all(), [
            'attach_id' => 'required_without:attach_path|allow_uuid|string|size:36',
            'attach_path' => 'required_without:attach_id',
        ], $messages);
        if ($validator->fails()) {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        } else {
            if (isset($inputdata['attach_path']) && $inputdata['attach_path'] != '') {
                $filepath = public_path() . '/' . $inputdata['attach_path'];

                if (file_exists($filepath)) {
                    $res = (file_get_contents($filepath));
                } else {
                    if (isset($inputdata['attach_id']) && $inputdata['attach_id'] != '') {
                        $res = $this->get_path_of_attach_id($inputdata['attach_id']);
                    }
                }

            } else if (isset($inputdata['attach_id']) && $inputdata['attach_id'] != '') {
                $res = $this->get_path_of_attach_id($inputdata['attach_id']);
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

    // 
    public function download_complaintattachment(Request $request)
    {
        $inputdata  = $request->all();
        $res        = '';
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
    // 

    /**
     * This is controller funtion is used to get attachment file path
     * @author Darshan Chaure
     * @access public
     * @package relationshiptype
     * @param string $att_id
     * @return json
     */
    public function get_path_of_attach_id($att_id)
    {
        $filepath = '';
        $res = '';

        $att_id_bin = DB::raw('UUID_TO_BIN("' . $att_id . '")');
        $att_result = DB::table('en_pr_po_attachment')->select('attachment_name')->where('attach_id', $att_id_bin)->first();

        if (isset($att_result->attachment_name)) {
            $filepath = $att_result->attachment_name;
        }
        if (file_exists($filepath)) {
            $res = (file_get_contents($filepath));
        }

        return $res;
    }

    /**
     * This is controller funtion used to accept the values to Update new Purchase Order Only.
     * @author       Namrata Thakur
     * @access       public
     * @param
     * @param_type   POST array
     * @return       JSON
     * @tables       en_form_data_po
     */

    public function purchaseorderadd(Request $request)
    {
        try
        {
            $messages = [
                /*
                Not Required on Edit Page
                'po_name.required' => showmessage('000', array('{name}'), array(trans('label.lbl_po_name')), true),
                'po_no.required' => showmessage('000', array('{name}'), array(trans('label.lbl_po_number')), true),
                'po_no.start_with_alpha_numeric_allow_alphal_numeric_dash_underscore_only' => showmessage('007', array('{name}'), array(trans('label.lbl_po_number')), true),
                'po_name.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_po_number')), true), */

                'details.required' => showmessage('000', array('{name}'), array('Form Data JSON'), true),
                //'form_templ_type.required' => showmessage('000', array('{name}'), array('Form Data Type'), true),
                'form_templ_id.required' => showmessage('000', array('{name}'), array('Form Data Id'), true),

                //'bv_id.required'         => showmessage('000', array('{name}'), array('Business Vertical Id '), true),
                //'dc_id.required'         => showmessage('000', array('{name}'), array('Datacenter Id '), true),
                // 'location_id.required'   => showmessage('000', array('{name}'), array('Location Id '), true),

            ];
            $formAction = $request->input('formAction');
            if ($formAction == "edit") {
                $validation_rules = array(
                    'details' => 'required',
                    //'bv_id' => 'required|string|size:36',
                    //'dc_id' => 'required|string|size:36',
                    //'location_id' => 'required|string|size:36'
                );
            } else {
                $validation_rules = array();
            }

            $validator = Validator::make($request->all(), $validation_rules, $messages);
            /*$validator = Validator::make($request->all(), [
            'details' => 'required',
            //'form_templ_type' => 'required',
            'form_templ_id' => 'required|string|size:36',
            //'asset_details' => 'required',
            'bv_id' => 'required|string|size:36',
            'dc_id' => 'required|string|size:36',
            'location_id' => 'required|string|size:36',
            //'status' => 'required|in:pending approval,open,partially approved,approved,closed,cancelled,deleted',
            'po_name'   => 'required',
            'po_no'     => 'required|start_with_alpha_numeric_allow_alphal_numeric_dash_underscore_only|composite_unique:en_form_data_po, po_no, '.$request->input('po_no').', po_no,'.$request->input('po_no')

            ], $messages); */

            $validator->after(function ($validator) {
                $request = request();
                $formAction = $request['formAction'];

                if (true) {
                    // if ($formAction == "edit") {
                    $pr_details = $request['details'] = json_decode($request['details'], true);
                    $pr_asset_details = json_decode($request['asset_details'], true);
                    //$approval_users = json_decode($request['approval_details'], true);
                    $request['details'] = json_encode($pr_details);
                    $request['asset_details'] = json_encode($pr_asset_details);
                    //$request['approval_details'] = json_encode($approval_users);
                    $request['requester_id'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                    $asset_arr = array();
                    $asset_details = json_decode($request['asset_details'], true);
                    //$approval_details = json_decode( $request['approval_details'], true );

                    if ($asset_details) {
                        if (isset($asset_details['item']) && isset($asset_details['item_desc']) && isset($asset_details['item_qty']) && isset($asset_details['item_estimated_cost'])) {
                            foreach ($asset_details['item'] as $key => $item) {
                                $asset_arr[$key]['item'] = $item;
                                $asset_arr[$key]['item_desc'] = $asset_details['item_desc'][$key];
                                $asset_arr[$key]['item_product'] = $asset_details['item_product'][$key];
                                $asset_arr[$key]['item_qty'] = $asset_details['item_qty'][$key];
                                $asset_arr[$key]['item_estimated_cost'] = $asset_details['item_estimated_cost'][$key];
                                $emptyArr = array();
                                $htmlNotAllowedArr = array();
                                if ($item == "") {
                                    $emptyArr[] = trans('label.lbl_item');
                                }
                                if ($asset_details['item_desc'][$key] == "") {
                                    $emptyArr[] = trans('label.lbl_item_desc');
                                } else {
//added by snehal to html not allowed validation on date:16/07/2020
                                    $html_tags_not_allowed_validation = $this->validation_html_tags_not_allowed($asset_details['item_desc'][$key]);
                                    if ($html_tags_not_allowed_validation) {
                                        $htmlNotAllowedArr[] = trans('label.lbl_item_desc');
                                    }
                                }
                                if ($asset_details['item_qty'][$key] == "") {
                                    $emptyArr[] = trans('label.lbl_item_qty');
                                }
                                if ($asset_details['item_product'][$key] == "") {
                                    $emptyArr[] = 'item name ';
                                }
                                if ($asset_details['item_estimated_cost'][$key] == "") {
                                    $emptyArr[] = trans('label.lbl_item_estim_cost');
                                }
                                if (!empty($emptyArr)) {
                                    $emptyStr = implode(",", $emptyArr);
                                    $validator->errors()->add('item ' . ($key + 1), showmessage('000', array('{name}'), array("#" . ($key + 1) . " " . $emptyStr), true));
                                }
                                if (!empty($htmlNotAllowedArr)) {
                                    $htmlNotAllowedStr = implode(",", $htmlNotAllowedArr);
                                    $validator->errors()->add('item ' . ($key + 1), showmessage('001', array('{name}'), array("#" . ($key + 1) . " " . $htmlNotAllowedStr), true));
                                }

                            }
                        }
                    }
                    $request['asset_details'] = $asset_arr;
                    $jsondata = json_decode($request['details'], true);
                    /*if($request['approval_req']=='y')
                    {
                    if(empty($approval_details['confirmed']) && empty($approval_details['optional']))
                    {
                    $validator->errors()->add('approvers', showmessage('000', array('{name}'), array('Approvers'), true));
                    }

                    }*/
                    if ($request['urlpath'] == "purchaserequest") {
                        //$pr_title       = isset($jsondata['pr_title']) ? $jsondata['pr_title'] : "";
                        $pr_req_date = isset($jsondata['pr_req_date']) ? $jsondata['pr_req_date'] : "";
                        $pr_due_date = isset($jsondata['pr_due_date']) ? $jsondata['pr_due_date'] : "";
                        $pr_priority = isset($jsondata['pr_priority']) ? $jsondata['pr_priority'] : "";
                        //$pr_cost_center = isset($jsondata['pr_cost_center']) ? $jsondata['pr_cost_center'] : "";
                        //$pr_description = isset($jsondata['pr_description']) ? $jsondata['pr_description'] : "";
                        //$shipping_address = isset($jsondata['shipping_address']) ? $jsondata['shipping_address'] : "";
                        //$billing_address = isset($jsondata['billing_address']) ? $jsondata['billing_address'] : "";

                        $pr_vendor = isset($jsondata['pr_vendor']) ? $jsondata['pr_vendor'] : "";
                        $pr_shipto = isset($jsondata['pr_shipto']) ? $jsondata['pr_shipto'] : "";
                        $pr_billto = isset($jsondata['pr_billto']) ? $jsondata['pr_billto'] : "";
                        $pr_shipto_contact = isset($jsondata['pr_shipto_contact']) ? $jsondata['pr_shipto_contact'] : "";
                        $pr_billto_contact = isset($jsondata['pr_billto_contact']) ? $jsondata['pr_billto_contact'] : "";
                        $pr_payment_terms = isset($jsondata['pr_payment_terms']) ? $jsondata['pr_payment_terms'] : "";
                        $pr_taxes = isset($jsondata['pr_taxes']) ? $jsondata['pr_taxes'] : "";
                        $pr_warranty_support = isset($jsondata['pr_warranty_support']) ? $jsondata['pr_warranty_support'] : "";
                        $pr_special_terms = isset($jsondata['pr_special_terms']) ? $jsondata['pr_special_terms'] : "";
                        /* if ($pr_title == "") {
                        $validator->errors()->add('pr_title', showmessage('000', array('{name}'), array(trans('label.lbl_purchasename')), true));
                        } else {

                        $validation_start_with_alpha_numeric_allow_alphal_numeric_dash_underscore_only = $this->validation_start_with_alpha_numeric_allow_alphal_numeric_dash_underscore_only($pr_title);
                        if ($validation_start_with_alpha_numeric_allow_alphal_numeric_dash_underscore_only) {
                        $validator->errors()->add('pr_title', showmessage('007', array('{name}'), array(trans('label.lbl_purchasetitle')), true));
                        }

                        }*/
                        if ($pr_billto_contact == "") {
                            $validator->errors()->add('pr_billto_contact', 'The Bill To Contact field should be required.');
                        }
                        if ($pr_special_terms == "") {
                            $validator->errors()->add('pr_special_terms', 'The pr special terms field should be required.');
                        }
                        if ($pr_warranty_support == "") {
                            $validator->errors()->add('pr_warranty_support', 'The pr warranty support field should be required.');
                        }
                        if ($pr_taxes == "") {
                            $validator->errors()->add('pr_taxes', 'The pr taxes field should be required.');
                        }
                        if ($pr_payment_terms == "") {
                            $validator->errors()->add('pr_payment_terms', 'The pr payment terms field should be required.');
                        }
                        if ($pr_shipto_contact == "") {
                            $validator->errors()->add('pr_shipto_contact', 'The Shipp To Contact field should be required.');
                        }
                        if ($pr_shipto == "") {
                            $validator->errors()->add('pr_shipto', 'The Shipp To field should be required.');
                        }
                        if ($pr_billto == "") {
                            $validator->errors()->add('pr_billto', 'The Bill To field should be required.');
                        }
                        if ($pr_vendor == "") {
                            $validator->errors()->add('pr_vendor', showmessage('000', array('{name}'), array(trans('label.lbl_vendor')), true));
                        }
                        if ($pr_req_date == "") {
                            $validator->errors()->add('pr_req_date', showmessage('000', array('{name}'), array(trans('label.lbl_req_date')), true));
                        }
                        if ($pr_due_date == "") {
                            $validator->errors()->add('pr_due_date', showmessage('000', array('{name}'), array(trans('label.lbl_due_date')), true));
                        }
                        if ($pr_priority == "" || $pr_priority == "[Select Priority]") {
                            $validator->errors()->add('pr_priority', showmessage('000', array('{name}'), array(trans('label.lbl_priority')), true));
                        }
                        /*if ($pr_cost_center == "") {
                        $validator->errors()->add('pr_cost_center', showmessage('000', array('{name}'), array(trans('label.lbl_cost_center')), true));
                        }*/
                        /*if($pr_description=="")
                        {
                        $validator->errors()->add('pr_description', showmessage('000', array('{name}'), array(trans('label.lbl_purchase_desc')), true));
                        }else{//added by snehal to html not allowed validation on date:16/07/2020
                        $html_tags_not_allowed_validation = $this->validation_html_tags_not_allowed($pr_description);
                        if($html_tags_not_allowed_validation){
                        $validator->errors()->add('pr_description', showmessage('001', array('{name}'), array(trans('label.lbl_purchase_desc')), true));
                        }

                        }*/
                        //added by snehal to html not allowed validation on date:16/07/2020
                        /*if($shipping_address != ""){
                        $html_tags_not_allowed_validation = $this->validation_html_tags_not_allowed($shipping_address);
                        if($html_tags_not_allowed_validation){
                        $validator->errors()->add('shipping_address', showmessage('001', array('{name}'), array(trans('label.lbl_shipping_address')), true));
                        }
                        }*/
                        /*if($billing_address != ""){
                    $html_tags_not_allowed_validation = $this->validation_html_tags_not_allowed($billing_address);
                    if($html_tags_not_allowed_validation){
                    $validator->errors()->add('billing_address', showmessage('001', array('{name}'), array(trans('label.lbl_billing_address')), true));
                    }
                    }*/
                    }
                }
                /* Below vaidation for both add & edit*/
                $approval_users = json_decode($request['approval_details'], true);
                $request['approval_details'] = json_encode($approval_users);
                $approval_details = json_decode($request['approval_details'], true);
                if ($request['approval_req'] == 'y') {
                    if (empty($approval_details['confirmed']) && empty($approval_details['optional'])) {
                        $validator->errors()->add('approvers', showmessage('000', array('{name}'), array(trans('label.lbl_approvers')), true));
                    } else {
                        $result = array_intersect($approval_details['confirmed'], $approval_details['optional']);
                        if (!empty($result)) {
                            $validator->errors()->add('approvers', showmessage('msg_pr_po_same_user_can_not_for_approval'));
                        }
                    }

                }
                if ($formAction == "edit") {
                    //"composite_unique:en_contract, contract_name, '.$request->input('contract_name').', contract_id,'.$request->input('contract_id')";
                    // $pr_title        = isset($jsondata['pr_title']) ? $jsondata['pr_title'] : "";
                    //$parameters      = array("en_form_data_po", "details", "pr_title", $pr_title, "po_id", $request["po_id"]);
                    $parameters = array("en_form_data_po", "details", "po_id", $request["po_id"]);
                    $validation_resp = validation_composite_unique_without_status_for_json_data($parameters);

                    /*if (!$validation_resp) {
                $validator->errors()->add('pr_title', showmessage('006', array('{name}'), array(trans('label.lbl_purchasetitle')), true));
                }*/

                }

            });
            if ($validator->fails()) {
                $error = $validator->errors();
                $data['data'] = null;
                $data['message']['error'] = $error;
                $data['status'] = 'error';
                return response()->json($data);
            } else {
                $formAction = $request->input('formAction');
                unset($request['formAction']);

                // print_r($request['asset_details']); exit;
                /* $form_templ_id_uuid = $request->input('form_templ_id');
                $request['form_templ_id'] = DB::raw('UUID_TO_BIN("'.$request->input('form_templ_id').'")');
                $request['bv_id'] = DB::raw('UUID_TO_BIN("'.$request->input('bv_id').'")');
                $request['dc_id'] = DB::raw('UUID_TO_BIN("'.$request->input('dc_id').'")');
                $request['location_id'] = DB::raw('UUID_TO_BIN("'.$request->input('location_id').'")');
                $asset_detailsArr = $request['asset_details'];
                unset($request['asset_details']);*/
                $pr_po_type = $request['pr_po_type']; // As "Purchase Order Add (Without PR)" Will Work same link Purchase Request Save.
                $pr_po_type = $request['pr_po_type']; // As "Purchase Order Add (Without PR)" Will Work same link Purchase Request Save.

                if ($formAction == "add") {
                    if ($request['approval_req'] == 'n') {
                        unset($request['approval_details']);
                    }
                    $pr_id = $request->input('pr_id');
                    $purchaseRequestResponse = EnPurchaseRequest::where('pr_id', DB::raw('UUID_TO_BIN("' . $request->input('pr_id') . '")'))->first();

                    if ($purchaseRequestResponse) {

                        //$request['details']      = $purchaseRequestResponse['details'];
                        $request['bv_id'] = DB::raw('UUID_TO_BIN("d7df036a-0a10-11ec-ad77-4e89be533080")'); //$purchaseRequestResponse['bv_id'];
                        $request['dc_id'] = DB::raw('UUID_TO_BIN("14fe35f4-0a11-11ec-9503-4e89be533080")'); //$purchaseRequestResponse['bv_id'];
                        $request['location_id'] = DB::raw('UUID_TO_BIN("e0ce8c54-0c9d-11ec-905c-4e89be533080")'); //$purchaseRequestResponse['bv_id'];
                        /* $request['dc_id']        = '0x14fe35f40a1111ec95034e89be533080';//$purchaseRequestResponse['dc_id'];
                        $request['location_id']  = '0xe0ce8c540c9d11ec905c4e89be533080';//$purchaseRequestResponse['location_id'];
                        //$request['requester_id'] = $purchaseRequestResponse['requester_id'];*/
                        // $request['pr_id'] = $request->input('pr_id');
                        // $request['pr_id'] = $purchaseRequestResponse['pr_id'];
                    }

                    /*$purchaseAssetDetailsResponse = EnPrPoAssetDetails::where('pr_po_id', $request->input('pr_id'))->where('asset_type', 'pr')->get();*/

                    //unset($request['pr_id']);
                    //array();

                    unset($request['asset_details']);
                    unset($request['formAction']);
                    unset($request['pr_po_type']);
                    $purchaseAssetDetailsResponse = EnPrPoAssetDetails::getPrPoAssetDetails($request->input('pr_id'), 'pr', $request['pr_vendor']);

                    $asset_detailsArr = $purchaseAssetDetailsResponse ? $purchaseAssetDetailsResponse : array();
                    //  print_r($request->all());
                    $request['pr_id'] = DB::raw('UUID_TO_BIN("' . $request->input('pr_id') . '")');
                    $request['form_templ_id'] = DB::raw('UUID_TO_BIN("' . $request->input('form_templ_id') . '")');
                    // $request['pr_id']          = DB::raw('UUID_TO_BIN("'.$asset_detailsArr[0]['pr_po_id'].'")');

                    DB::beginTransaction(); // begin transaction
                    $result_id = "";
                    $result_id_text = "";
                    $result_message = "";
                    $purchaseOrderResponse = EnPurchaseOrder::create($request->all());
                    $result_id = $purchaseOrderResponse['po_id'];
                    $result_id_text = $purchaseOrderResponse->po_id_text;
                    $result_message = "Purchase Order";

                    if (!empty($result_id)) {
                        /* $asset_inputdata = array();
                        foreach ($asset_detailsArr as $key => $asset) {
                        $asset_inputdata['pr_po_id'] = $result_id;
                        // $asset_inputdata['po_id']  = "";
                        $asset_inputdata['asset_type']    = $pr_po_type;
                        $asset_inputdata['asset_details'] = $asset['asset_details']; //json_encode( $asset, true );
                        $asset_inputdata['created_by']    = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                        EnPrPoAssetDetails::create($asset_inputdata);
                        }*/
                        $asset_inputdata = array();
                        foreach ($asset_detailsArr as $key => $asset) {
                            $item_amt = json_decode($asset['vendor_approve'], true);
                            $vendor_id = $item_amt['vendor_id'];
                            $comp = json_decode($asset['quotation_comparison_data'], true);
                            $gst = $comp[$vendor_id];
                            $gst['vendor_id'] = $vendor_id;
                            $asset_inputdata['pr_po_id'] = $result_id;
                            // $asset_inputdata['po_id']  = "";
                            $asset_inputdata['asset_type'] = 'po';
                            $asset_aary = json_decode($asset['asset_details'], true);

                            $asset_inputdata['vendor_approval'] = json_encode($gst);
                            $asset_aary['item_estimated_cost'] = $item_amt['amount'];
                            $asset_inputdata['asset_details'] = json_encode($asset_aary); //json_encode( $asset, true );
                            $asset_inputdata['created_by'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                            EnPrPoAssetDetails::create($asset_inputdata);
                            DB::table('en_ci_quotation_comparison')
                                ->where(['pr_po_id' => DB::raw('UUID_TO_BIN("' . $pr_id . '")'), 'selected_item_id' => DB::raw('UUID_TO_BIN("' . $asset['item_product'] . '")')])
                                ->update(['vendor_approve' => DB::raw('JSON_INSERT(vendor_approve, "$.converted_as_po", "yes")')]);
                            // $asset_inputdata[]=$asset_inputdata1; // for demo

                        }
                        /*$data['data']             = $asset_inputdata;
                        $data['message']['error'] = '';
                        $data['status']           = 'error';
                        return response()->json($data);*/

                        //$purchaseAssetResponse = EnPrPoAssetDetails::insert($asset_inputdata);
                        $data['data']['insert_id'] = $result_id_text;
                        $data['message']['success'] = showmessage('104', array('{name}'), array(trans('label.lbl_purchase_order')));
                        $data['status'] = 'success';

                        $hist_details = $this->gethistorydesc('created', trans('label.lbl_purchase_order'));
                        //Add into Purchase History
                        $this->prpohistoryadd(array('pr_po_id' => $result_id_text, 'history_type' => $pr_po_type, 'action' => 'created', 'details' => $hist_details, 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")')));
                        //Add into UserActivityLog
                        userlog(array('record_id' => $result_id_text, 'data' => $request->all(), 'action' => 'added', 'message' => showmessage('104', array('{name}'), array($result_message))));
                        DB::commit();
                    } else {
                        DB::rollBack();
                        $data['data'] = $request->all();
                        $data['message']['error'] = showmessage('103', array('{name}'), array(trans('label.lbl_purchase_order')));
                        $data['status'] = 'error';
                    }
                } else //if($formAction == "edit")
                {
                    $vendore_data = json_decode($request->input('details'), true);

                    DB::beginTransaction(); // begin transaction
                    $asset_detailsArr = $request['asset_details'];
                    unset($request['asset_details']);
                    $po_id_uuid = $request->input('po_id');
                    $po_id_bin = DB::raw('UUID_TO_BIN("' . $request->input('po_id') . '")');
                    $result = EnPurchaseOrder::where('po_id', $po_id_bin)->first();
                    $request['po_id'] = DB::raw('UUID_TO_BIN("' . $request->input('po_id') . '")');
                    if ($result) {
                        $request["approved_status"] = json_encode($request->input("approved_status"), true);
                        $pr_id = $vendore_data['pr_id'];
                        if ($pr_po_type == "po") {
                            //$request['pr_id'] = null;
                            unset($request['pr_id']);
                            unset($request['formAction']);
                            unset($request['pr_po_type']);
                        }
                        $result->update($request->all());
                        $result->save();

                        //item edit not allowed for now.
                        $prs_descroy = EnPrPoAssetDetails::destroyassetbyprpo($request->input('po_id'), "po");
                        // echo "jhjhj";
                        //  print_r( $prs_descroy);
                        $asset_inputdata = array();
                        foreach ($asset_detailsArr as $key => $asset) {
                            $asset_inputdata['pr_po_id'] = $request['po_id'];
                            // $asset_inputdata['po_id']  = "";
                            $asset_inputdata['asset_type'] = $pr_po_type;
                            $asset_inputdata['vendor_approval'] = json_encode(array('vendor_id' => $vendore_data['pr_vendor']));
                            $asset_inputdata['asset_details'] = json_encode($asset, true);
                            $asset_inputdata['created_by'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');
                            $purchaseAssetResponse = EnPrPoAssetDetails::create($asset_inputdata);
                            DB::table('en_ci_quotation_comparison')
                                ->where(['pr_po_id' => DB::raw('UUID_TO_BIN("' . $pr_id . '")'), 'selected_item_id' => DB::raw('UUID_TO_BIN("' . $asset['item_product'] . '")')])
                                ->update(['vendor_approve' => DB::raw('JSON_INSERT(vendor_approve, "$.converted_as_po", "yes")')]);

                        }

                        $data['data']['insert_id'] = $po_id_uuid;
                        $data['message']['success'] = showmessage('106', array('{name}'), array(trans('label.lbl_purchase_order')));
                        $data['status'] = 'success';

                        $hist_details = $this->gethistorydesc('updated', trans('label.lbl_purchase_order'));
                        //Add into Purchase History
                        $this->prpohistoryadd(array('pr_po_id' => $po_id_uuid, 'history_type' => $pr_po_type, 'action' => 'updated', 'details' => $hist_details, 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")')));
                        //Add into UserActivityLog
                        userlog(array('record_id' => $po_id_uuid, 'data' => $request->all(), 'action' => 'added', 'message' => showmessage('106', array('{name}'), array('Purchase Order'))));
                        // convert to po
                        user_notification(array('type' => 'cpo', 'message' => 'convert to po', 'store_user' => DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")'), 'show_user' => '', 'action' => 'add'));

                        DB::commit();
                    } else {
                        DB::rollBack();
                        $data['data'] = $request->all();
                        $data['message']['error'] = showmessage('105', array('{name}'), array(trans('label.lbl_purchase_order')));
                        $data['status'] = 'error';
                    }
                }
                //}
            }
        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("purchaseorderadd", "This controller function is implemented to add/update purchase order.", $request->all(), $e->getMessage());
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("purchaseorderadd", "This controller function is implemented to add/update purchase order.", $request->all(), $e->getMessage());
        } finally {
            return response()->json($data);
        }
    }
    /**
     * This is controller funtion used to accept the Invoice data of Purchase Order.
     * @author       Namrata Thakur
     * @access       public
     * @param
     * @param_type   POST array
     * @return       JSON
     * @tables       en_po_invoice
     */
    public function purchaseinvoices(Request $request)
    {
        $messages = [
            'po_id.required' => showmessage('000', array('{name}'), array('PR / PO Id'), true),
        ];
        $validator = Validator::make($request->all(), [
            'po_id' => 'required|allow_uuid|string|size:36',
        ], $messages);
        if ($validator->fails()) {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        } else {
            $invoice_id = $request->has('invoice_id') ? $request->input('invoice_id') : "";
            $prpoattachment = EnInvoice::getinvoices($request->input('po_id'), $invoice_id);
            if ($prpoattachment->isEmpty()) {
                $data['data'] = null;
                $data['status'] = 'error';
                $data['message']['error'] = showmessage('101', array('{name}'), array('Purchase Invoice'));
            } else {
                $data['data'] = $prpoattachment;
                $data['status'] = 'success';
                $data['message']['success'] = showmessage('102', array('{name}'), array('Purchase Invoice'));
            }
            return response()->json($data);
        }
    }

    /**
     * This is controller funtion is used to PO receive items.
     * @author Darshan Chaure
     * @access public
     * @package purchaseorder
     * @param array $receiveitems
     * @param string $vendor_id
     * @param number $purchasecost
     * @return json
     */
    public function poreceiveditem(Request $request)
    {

        $messages = [
            'receiveitems.required' => showmessage('msg_chkoneitem'),
            'vendor_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_vendor')), true),
            'purchasecost.required' => showmessage('000', array('{name}'), array(trans('label.lbl_purchasecost')), true),
            /*
        'acquisitiondate.required'      => showmessage('000', array('{name}'), array('Acquisition Date'), true),
        'expirydate.required'           => showmessage('000', array('{name}'), array('Expiry Date'), true),
        'warrantyexpirydate.required'   => showmessage('000', array('{name}'), array('Warrantyexpiry Date'), true),
         */
        ];
        $validator = Validator::make($request->all(), [
            'receiveitems' => 'required',
            'vendor_id' => 'required|allow_uuid|string|size:36',
            'purchasecost' => 'required',
            /*
        'acquisitiondate'    => 'required',
        'expirydate'         => 'required',
        'warrantyexpirydate' => 'required'
         */
        ], $messages);
        if ($validator->fails()) {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        }
        $validator->after(function ($validator) {
            $request = request();
            //$this->_validate_pr_po_asset($validator, "validate", $request);
            $this->_validate_pr_po_asset($validator, $request);
        });
        if ($validator->fails()) {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        } else {
            $ci_type_id = $request['ci_type_id'];
            $cutype = $request['cutype'];
            $title = $request['title'];
            $pr_po_id = $request['pr_po_id'];
            $purchasecost = $request['purchasecost'];
            $itemqty = $request['itemqty'];

            DB::beginTransaction(); // begin transaction
            foreach ($request['receiveitems'] as $item) {
                $receiveitemsArr = explode("_", $item);
                $key = $receiveitemsArr[1];
                // unset($request['ci_type_id']);
                // unset($request['cutype']);
                // unset($request['title']);
                $request['itemqty'] = 0;
                $request['purchasecost'] = 0;
                $request['asset_prefix'] = "PO#" . strtoupper($title[$key]);
                $request['ci_templ_id'] = $receiveitemsArr[0];
                $request['ci_type_id'] = $ci_type_id[$key];
                $request['cutype'] = $cutype[$key];
                $request['title'] = $title[$key];
                $totalAssetCount = $itemqty[$key];
                $request['purchasecost'] = $purchasecost[$key];
                $request['acquisitiondate'] = "";
                $request['expirydate'] = "";
                $request['warrantyexpirydate'] = "";
                $request['pr_po_id'] = $pr_po_id;

                for ($i = 0; $i < $totalAssetCount; $i++) {
                    $result = app('App\Http\Controllers\asset\AssetController')->addasset($request);
                    //$datares = $result->getData();
                    //var_dump($datares);
                    // return $result;
                    //die;
                    if ($result) {
                        DB::commit();
                        $data['data'] = null;
                        $data['status'] = 'success';
                        $data['message']['success'] = showmessage('106', array('{name}'), array(trans('label.lbl_receiveditems')));

                    } else {
                        DB::rollBack();
                        $data['data'] = $request->all();
                        $data['message']['error'] = showmessage('105', array('{name}'), array(trans('label.lbl_receiveditems')));
                        $data['status'] = 'error';
                    }
                }
                if ($data['status'] == "success") {
                    $prpoAssetDetails = EnPrPoAssetDetails::getPrPoAssetDetails($request->input('pr_po_id'), 'po');

                    if (!$prpoAssetDetails->isEmpty()) {

                        $ci_asset_detailsArr = array();
                        $flag_received = array();
                        foreach ($prpoAssetDetails as $key => $asset) {
                            $asset_arr = json_decode($asset['asset_details'], true);
                            if (isset($asset_arr)) {
                                $ci_asset_detailsArr[$key]['item'] = $asset_arr['item'];
                                $ci_asset_detailsArr[$key]["item_qty"] = $asset_arr["item_qty"];

                                //$ci_asset_detailsArr[$key]["received_item_qty"] = 0;

                                $pr_po_id_bin = DB::raw('UUID_TO_BIN("' . $request->input('pr_po_id') . '")');
                                $item_bin = DB::raw('UUID_TO_BIN("' . $asset_arr['item'] . '")');

                                $cnt = DB::table('en_assets')->where('po_id', $pr_po_id_bin)->where('ci_templ_id', $item_bin)->count();
                                $ci_asset_detailsArr[$key]["received_item_qty"] = $cnt;
                                if ($ci_asset_detailsArr[$key]["received_item_qty"] == $ci_asset_detailsArr[$key]["item_qty"]) {
                                    $flag_received[] = 1;
                                } else {
                                    $flag_received[] = 0;
                                }
                            }
                        }
                        /*if($ci_asset_detailsArr)
                    {
                    foreach($ci_asset_detailsArr as $key2 => $asset2)
                    {
                    $pr_po_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('pr_po_id').'")');
                    $item_bin = DB::raw('UUID_TO_BIN("'.$asset2['item'].'")');

                    $cnt = DB::table('en_assets')->where('po_id',$pr_po_id_bin)->where('ci_templ_id', $item_bin)->count();
                    $ci_asset_detailsArr[$key]["received_item_qty"] = $cnt;
                    if($ci_asset_detailsArr[$key]["received_item_qty"] == $ci_asset_detailsArr[$key]["item_qty"] ){
                    $flag_received[] = 1;
                    }else
                    {
                    $flag_received[] = 0;
                    }
                    }
                    }*/
                    }

                    if ($flag_received) {
                        if (in_array(0, $flag_received)) {
                            $receive_status = "partially received";
                        } else {
                            $receive_status = "item received";
                        }
                    } else {
                        $receive_status = "partially received";
                    }
                    $pr_po_id_bin = DB::raw('UUID_TO_BIN("' . $request->input('pr_po_id') . '")');
                    $result = EnPurchaseOrder::where('po_id', $pr_po_id_bin)->first();
                    if ($result) {
                        $update_array = array('status' => $receive_status);
                        $result->update($update_array);
                        $result->save();
                    }
                    //set history
                    $hist_details = $this->gethistorydesc('item received', trans('label.lbl_received_qty'));

                    $this->prpohistoryadd(array('pr_po_id' => $pr_po_id, 'history_type' => 'po', 'action' => 'item received', 'details' => $hist_details . "<br>" . trans('label.lbl_purchase_order') . " : " . $totalAssetCount, 'comment' => $request['title'] . '__' . $totalAssetCount, 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")')));
                    //Add into UserActivityLog
                    userlog(array('record_id' => $pr_po_id, 'data' => $request->all(), 'action' => 'item received', 'message' => showmessage('msg_item_received', array('{name}'), array(trans('label.lbl_purchase_order')))));
                }

            }
            return response()->json($data);
        }

    }
    /**
     * This is controller funtion is used to validate PR PO assets
     * @author Darshan Chaure
     * @access public
     * @package purchaseorder
     * @param string $ci_type_id
     * @param string $cutype
     * @param string $title
     * @param number $purchasecost
     * @param number $itemqty
     * @param array $receiveitems
     * @return json
     */
    public function _validate_pr_po_asset($validator, Request $request)
    {
        $ci_type_id = $request['ci_type_id'];
        $cutype = $request['cutype'];
        $title = $request['title'];

        $purchasecost = $request['purchasecost'];
        $itemqty = $request['itemqty'];
        //  print_r($purchasecost[4]);
        foreach ($request['receiveitems'] as $item) {
            $receiveitemsArr = explode("_", $item);
            $key = $receiveitemsArr[1]; // Commented on 6 Oct 2020
            // unset($request['ci_type_id']);

            // unset($request['cutype']);
            // unset($request['title']);
            $inputdata['itemqty'] = 0;
            $inputdata['purchasecost'] = 0;
            $inputdata['asset_prefix'] = "PO#" . strtoupper($title[$key]);
            $inputdata['ci_templ_id'] = $receiveitemsArr[0];
            $inputdata['ci_type_id'] = $ci_type_id[$key];
            $inputdata['cutype'] = $cutype[$key];
            $inputdata['title'] = $title[$key];
            $totalAssetCount = 0;
            if (isset($itemqty[$key])) {
                $totalAssetCount = $itemqty[$key] == "" ? 0 : $itemqty[$key];
            } else {
                $totalAssetCount = 0;
            }
            $inputdata['purchasecost'] = $purchasecost[$key];
            $inputdata['acquisitiondate'] = "";
            $inputdata['expirydate'] = "";
            $inputdata['warrantyexpirydate'] = "";
            //if($currentAction == "validate")
            {
                if ($totalAssetCount <= 0) {

                    $validator->errors()->add('itemqty', showmessage('000', array('{name}'), array("#" . ($key + 1) . " " . "Qty"), true));
                }
            }

        }

        // return $validator;
    }
    public function getnotifications(Request $request)
    {
        $messages = [
            //'pr_po_id.required' => showmessage('000', array('{name}'), array('PR / PO Id'), true),
            //  'history_type.required' => showmessage('000', array('{name}'), array('History Type'), true)
        ];
        $validator = Validator::make($request->all(), [
            //   'pr_po_id' => 'required|string|size:36',
            //  'history_type' => 'required|string|size:36'
        ], $messages);
        if ($validator->fails()) {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        } else {
            $prponotification = EnPrPoHistory::getNotifications($request->input('user_id'));

            /* $queries    = DB::getQueryLog();
            $last_query = end($queries);
            print_r($last_query);*/
            if ($prponotification->isEmpty()) {
                $data['data'] = null;
                $data['status'] = 'error';
                $data['message']['error'] = showmessage('101', array('{name}'), array('Purchase Notification History'));
            } else {
                $data['data'] = $prponotification;
                $data['status'] = 'success';
                $data['message']['success'] = showmessage('102', array('{name}'), array('Purchase Notification History'));
            }
            return response()->json($data);
        }
    }

    /**
     * This is controller funtion is used to delete a PO invoice
     * @author Darshan Chaure
     * @access public
     * @package purchaseorder
     * @param string $invoice_id
     * @return json
     */
    public function poinvoicedelete(Request $request)
    {
        try
        {
            $messages = [
                'invoice_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_invoiceid')), true),
            ];
            $validator = Validator::make($request->all(), [
                'invoice_id' => 'required|allow_uuid|string|size:36',
            ], $messages);
            if ($validator->fails()) {
                $error = $validator->errors();
                $data['data'] = null;
                $data['message']['error'] = $error;
                $data['status'] = 'error';
                return response()->json($data);
            } else {
                $invoice_id_bin = DB::raw("UUID_TO_BIN('" . $request['invoice_id'] . "')");
                DB::table('en_po_invoice')->where('invoice_id', $invoice_id_bin)->update(['status' => 'd']);

                //for history
                $inv_detail_id = '';
                $po_id_uuid = '';

                $inv = DB::table('en_po_invoice')->select('po_id', 'details', DB::raw('BIN_TO_UUID(po_id) AS po_id_uuid'))->where('invoice_id', $invoice_id_bin)->first();
                if ($inv) {
                    $dtl = $inv->details;
                    $po_id_bin = $inv->po_id;
                    $po_id_uuid = $inv->po_id_uuid;

                    if ($dtl) {
                        $dtl_arr = json_decode($dtl, true);
                        if (isset($dtl_arr['id'])) {
                            $inv_detail_id = $dtl_arr['id'];
                        }

                    }
                }

                //history to delete invoice
                $hist_details = $this->gethistorydesc('deleted', trans('label.lbl_invoice'));

                $this->prpohistoryadd(array('pr_po_id' => $po_id_uuid, 'history_type' => 'po', 'action' => 'deleted', 'details' => $hist_details, 'comment' => $inv_detail_id, 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")')));

                //Add into UserActivityLog
                userlog(array('record_id' => $po_id_uuid, 'data' => $request->all(), 'action' => 'deleted', 'message' => showmessage('118', array('{name}'), array(trans('label.lbl_invoice')))));

                //return result
                $data['data'] = null;
                $data['message']['success'] = showmessage('118', array('{name}'), array(trans('label.lbl_invoice')), true);
                $data['status'] = 'success';
                return response()->json($data);
            }
        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("purchaserequests", "This controller function is implemented to delete a PO invoice.", $request->all(), $e->getMessage());
            return response()->json($data);
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("purchaserequests", "This controller function is implemented to delete a PO invoice.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }
    public function generateponumber()
    {
        try
        {

            $inv = DB::table('en_form_data_po')->select('po_no')->orderBy('created_at', 'desc')->first();
            if ($inv) {
                $dtl = $inv->po_no;
            }

            //return result
            $data['data'] = $dtl;
            $data['message']['success'] = showmessage('142', array('{name}'), array(trans('label.lbl_po_number')), true);
            $data['status'] = 'success';
            return response()->json($data);

        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("generateponumber", "This controller function is implemented to generate PO number.", array(), $e->getMessage());
            return response()->json($data);
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("generateponumber", "This controller function is implemented to generate PO number.", array(), $e->getMessage());
            return response()->json($data);
        }
    }

    public function generatecrnumber()
    {
        try
        {

            $inv = DB::table('en_complaint_raised')->select('complaint_raised_no')->orderBy('cr_id', 'desc')->first();
            if ($inv) {
                $dtl = $inv->complaint_raised_no;
            }

            //return result
            $data['data'] = $dtl;
            $data['message']['success'] = showmessage('142', array('{name}'), array(trans('label.lbl_pr_number')), true);
            $data['status'] = 'success';
            return response()->json($data);

        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("generatecrnumber", "This controller function is implemented to generate CR number.", array(), $e->getMessage());
            return response()->json($data);
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("generatecrnumber", "This controller function is implemented to generate CR number.", array(), $e->getMessage());
            return response()->json($data);
        }
    }

    public function generateprnumber()
    {
        try
        {

            $inv = DB::table('en_form_data_pr')->select('pr_no')->orderBy('created_at', 'desc')->first();
            if ($inv) {
                $dtl = $inv->pr_no;
            }

            //return result
            $data['data'] = $dtl;
            $data['message']['success'] = showmessage('142', array('{name}'), array(trans('label.lbl_pr_number')), true);
            $data['status'] = 'success';
            return response()->json($data);

        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("generateprnumber", "This controller function is implemented to generate PR number.", array(), $e->getMessage());
            return response()->json($data);
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("generateprnumber", "This controller function is implemented to generate PR number.", array(), $e->getMessage());
            return response()->json($data);
        }
    }

    public function getvendorbyid(Request $request)
    {
        try
        {

            $dtl = '';
            $inv = DB::table('en_ci_vendors')->select('vendor_name')->where('vendor_id', DB::raw("UUID_TO_BIN('" . $request->input('pr_vendor_id') . "')"))->first();
            if ($inv) {
                $dtl = $inv->vendor_name;
            }

            //return result
            $data['data'] = $dtl;
            $data['message']['success'] = showmessage('142', array('{name}'), array(trans('label.lbl_vendor')), true);
            $data['status'] = 'success';
            return response()->json($data);

        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("getvendorbyid", "This controller function is implemented to get vendor name .", $request->all(), $e->getMessage());
            return response()->json($data);
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("getvendorbyid", "This controller function is implemented to get vendor name .", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }
    public function purchaseuserdashboard(Request $request)
    {
        try
        {
            $prpouserdata = [];

            $dtl = '';
            $invoice_id_bin = [];
            foreach ($request->input('userids') as $value) {
                $invoice_id_bin[] = DB::raw("UUID_TO_BIN('" . $value . "')");
            }
            $inv = DB::table('en_form_data_pr')->select(DB::raw("BIN_TO_UUID(assignpr_user_id) as assignpr_user_id"),DB::raw("BIN_TO_UUID(requester_id) as requester_id"),DB::raw('count(pr_id) as total'),'pr_no','status','created_at')->whereIn('assignpr_user_id', $invoice_id_bin)->groupBy('assignpr_user_id')->get();

            $prpouserdata['Totalpr'] = $inv;

            /*  $queries    = DB::getQueryLog();
            $data['data'] = end($queries);*/

            $prpouserdata['openpo'] = DB::table('en_form_data_po')
                ->select(DB::raw("BIN_TO_UUID(requester_id) as assignpr_user_id"), DB::raw('count(po_id) as total_openpo'))
                ->whereIn('requester_id', $invoice_id_bin)
                ->where('status', 'open')
                ->groupBy('requester_id')
                ->get();

            $prpouserdata['closedpo'] = DB::table('en_form_data_po')
                ->select(DB::raw("BIN_TO_UUID(requester_id) as assignpr_user_id"), DB::raw('count(po_id) as total_closedpo'))
                ->whereIn('requester_id', $invoice_id_bin)
                ->where('status', 'closed')
                ->groupBy('requester_id')
                ->get();

            $prpouserdata['partallyopenpo'] = DB::table('en_form_data_po')
                ->select(DB::raw("BIN_TO_UUID(requester_id) as assignpr_user_id"), DB::raw('count(po_id) as total_partallyopenpo'))
                ->whereIn('requester_id', $invoice_id_bin)
                ->where('status', 'partially received')
                ->groupBy('requester_id')
                ->get();

            $prpouserdata['cancelledpo'] = DB::table('en_form_data_po')
                ->select(DB::raw("BIN_TO_UUID(requester_id) as assignpr_user_id"), DB::raw('count(po_id) as total_cancelledpo'))
                ->whereIn('requester_id', $invoice_id_bin)
                ->where('status', 'cancelled')
                ->groupBy('requester_id')
                ->get();

            $prpouserdata['rejectedpo'] = DB::table('en_form_data_po')
                ->select(DB::raw("BIN_TO_UUID(requester_id) as assignpr_user_id"), DB::raw('count(po_id) as total_rejectedpo'))
                ->whereIn('requester_id', $invoice_id_bin)
                ->where('status', 'rejected')
                ->groupBy('requester_id')
                ->get();

            $prpouserdata['totalpo'] = DB::table('en_form_data_po')
                ->select(DB::raw("BIN_TO_UUID(requester_id) as assignpr_user_id"), DB::raw('count(po_id) as totalpo'))
                ->whereIn('requester_id', $invoice_id_bin)
                ->whereRaw('JSON_EXTRACT(approved_status, "$.confirmed") LIKE "%approved%"')
                ->get();

            //return result
            $data['data'] = $prpouserdata;
            $data['message']['success'] = showmessage('142', array('{name}'), array(trans('label.lbl_vendor')), true);
            $data['status'] = 'success';
            return response()->json($data);

        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("getvendorbyid", "This controller function is implemented to get vendor name .", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }

    public function validation_html_tags_not_allowed($value)
    {
        if ((preg_match("/<(.|\n)*?>/", $value) !== 1)) {
            return false;
        }

        return true;
    }
    public function validation_start_with_alpha_numeric_allow_alphal_numeric_dash_underscore_only($value)
    {
        if ((preg_match("/^[a-zA-Z0-9][a-zA-Z0-9-_]*$/", $value) == 1)) {
            return false;
        }

        return true;
    }

    public function prpoassetstockdetails(Request $request)
    {

        try
        {

            $query = DB::table('en_assets AS ass')

                ->select(DB::raw('BIN_TO_UUID(ass.asset_id) AS asset_id'), 'ass.asset_tag', 'ass.display_name', 'ass.asset_status', 'ass.status', DB::raw('BIN_TO_UUID(ass.object_id) AS object_id'))
                ->where('ass.asset_status', '=', 'in_store');

            $query->where(function ($query) use ($asset_ids) {
                $query->when($asset_ids, function ($query) use ($asset_ids) {
                    return $query->whereIn(DB::raw('BIN_TO_UUID(ass.asset_id)'), $asset_ids);

                });

            });

            $query->groupBy('ass.asse');
            $data = $query->get();

            //return result
            $data['data'] = $request->all();
            $data['message']['success'] = showmessage('142', array('{name}'), array(trans('label.lbl_vendor')), true);
            $data['status'] = 'success';
            return response()->json($data);

        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("getvendorbyid", "This controller function is implemented to get asset stck count .", $request->all(), $e->getMessage());
            return response()->json($data);
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("getvendorbyid", "This controller function is implemented to get asset stck count .", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }

    public function crm_purchaserequestadd(Request $request)
    {
        $auth_user = 'aimapiclient';
        $auth_pw = 'BK{b@QNw/+8%{xK@';
        // https://www.php.net/manual/en/features.http-auth.php

        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            $error = 'Access denied. You did not enter a password.';
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);

        } else {

            if ($_SERVER['PHP_AUTH_PW'] != $auth_pw || $_SERVER['PHP_AUTH_USER'] != $auth_user) {
                $error = "Access denied. Credentials does not match";
                $data['message']['error'] = $error;
                $data['status'] = 'error';
                return response()->json($data);

            }
        }

        // dd($request);
        $messages = [
            'form_templ_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_formdataid')), true),
            'asset_details.required' => showmessage('000', array('{name}'), array(trans('label.lbl_itemdetails')), true),
            'status.required' => showmessage('000', array('{name}'), array(trans('label.lbl_status')), true),
        ];

        $validator = Validator::make($request->all(), [

            'form_templ_id' => 'required|allow_uuid|string|size:36',
            'asset_details' => 'required',

        ], $messages);

        $validator->after(function ($validator) {
            $request = request();
            $pr_po_type = 'pr';

            $pr_details = $request['details'] = json_decode($request['details'], true);

            $request['approved_status'] = '{
              "confirmed": {
                "73b9c9b8-87f8-11ec-afe5-86bd6599c53f": "approved"
              }
            }';

            $pr_asset_details = json_decode($request['asset_details'], true);

            $approval_users = json_decode('{"confirmed":["73b9c9b8-87f8-11ec-afe5-86bd6599c53f"],"optional":[]}', true);

            $request['details'] = json_encode($pr_details);
            $request['asset_details'] = json_encode($pr_asset_details);
            $request['approval_details'] = json_encode($approval_users);
            $request['requester_id'] = DB::raw('UUID_TO_BIN("73b9c9b8-87f8-11ec-afe5-86bd6599c53f")');
            $asset_arr = array();
            $asset_details = json_decode($request['asset_details'], true);
            $approval_details = json_decode($request['approval_details'], true);

            if ($asset_details) {

                if (isset($asset_details['item']) && isset($asset_details['item_desc']) && isset($asset_details['item_qty']) && isset($asset_details['warranty_support_required'])) {

                    foreach ($asset_details['item'] as $key => $item) {
                        $asset_arr[$key]['item'] = $item;
                        $asset_arr[$key]['item_product'] = $asset_details['item_product'][$key];
                        $asset_arr[$key]['item_desc'] = $asset_details['item_desc'][$key];
                        $asset_arr[$key]['item_qty'] = $asset_details['item_qty'][$key];
                        $asset_arr[$key]['warranty_support_required'] = $asset_details['warranty_support_required'][$key];
                        //$asset_arr[$key]['item_estimated_cost'] = $asset_details['item_estimated_cost'][$key];
                        $emptyArr = array();
                        $htmlNotAllowedArr = array();
                        if ($item == "") {
                            $emptyArr[] = trans('label.lbl_item');
                        }

                        if (isset($asset_details['item_product'][$key]) && $asset_details['item_product'][$key] == "") {
                            $emptyArr[] = "item name";
                        }

                        if ($asset_details['item_desc'][$key] == "") {
                            $emptyArr[] = trans('label.lbl_item_desc');
                        } else {

                            $html_tags_not_allowed_validation = $this->validation_html_tags_not_allowed($asset_details['item_desc'][$key]);
                            if ($html_tags_not_allowed_validation) {
                                $htmlNotAllowedArr[] = trans('label.lbl_item_desc');
                            }
                        }
                        if ($asset_details['item_qty'][$key] == "") {
                            $emptyArr[] = trans('label.lbl_item_qty');
                        }
                        /* if ($asset_details['item_estimated_cost'][$key] == "") {
                        $emptyArr[] = trans('label.lbl_item_estim_cost');
                        }*/
                        if (!empty($emptyArr)) {
                            $emptyStr = implode(",", $emptyArr);
                            $validator->errors()->add('item ' . ($key + 1), showmessage('000', array('{name}'), array("#" . ($key + 1) . " " . $emptyStr), true));
                        }
                        if (!empty($htmlNotAllowedArr)) {
                            $htmlNotAllowedStr = implode(",", $htmlNotAllowedArr);
                            $validator->errors()->add('item ' . ($key + 1), showmessage('001', array('{name}'), array("#" . ($key + 1) . " " . $htmlNotAllowedStr), true));
                        }

                    }
                }
            }
            //print_r($asset_arr);
            //$asset_json  = json_encode( $asset_arr, true );
            $request['asset_details'] = $asset_arr;
            $jsondata = json_decode($request['details'], true);

            $request['approval_req'] = 'y';

            if ($request['approval_req'] == 'y') {
                if (empty($approval_details['confirmed']) && empty($approval_details['optional'])) {
                    $validator->errors()->add('approvers', showmessage('000', array('{name}'), array(trans('label.lbl_approvers')), true));
                } else {
                    $result = array_intersect($approval_details['confirmed'], $approval_details['optional']);
                    if (!empty($result)) {
                        $validator->errors()->add('approvers', showmessage('msg_pr_po_same_user_can_not_for_approval'));
                    }
                }

            }

            $request['urlpath'] = 'purchaserequest';

            if ($request['urlpath'] == "purchaserequest") {
                //$pr_title       = isset($jsondata['pr_title']) ? $jsondata['pr_title'] : "";
                //$pr_req_date = isset($jsondata['pr_req_date']) ? $jsondata['pr_req_date'] : "";
                $pr_due_date = date('yy-mm-dd', strtotime(date('yy-mm-dd') . '+7 day'));
                $pr_priority = "high";

                $pr_requester_name = "75088434907d11ecab9e4eb834f5915d";

                if ($pr_requester_name == "" || $pr_requester_name == null) {
                    $validator->errors()->add('pr_requester_name', showmessage('000', array('{name}'), array(trans('label.lbl_requester_name')), true));
                }

                $pr_requirement_for = "IT";

                if ($pr_requirement_for == "" || $pr_requirement_for == "[Select Requirement For]") {
                    $validator->errors()->add('pr_requirement_for', showmessage('000', array('{name}'), array(trans('label.lbl_requirement_for')), true));
                }

                $pr_category = "Services";

                if ($pr_category == "" || $pr_category == "[Select Category]") {
                    $validator->errors()->add('pr_category', showmessage('000', array('{name}'), array(trans('label.lbl_category')), true));
                }

                $pr_shipto = "9ff21ebb-46d2-11ec-9512-764a8a13ae2c";

                if ($pr_shipto == "" || $pr_shipto == null) {
                    $validator->errors()->add('pr_shipto', showmessage('000', array('{name}'), array(trans('label.lbl_shipto')), true));
                }

                $pr_shipto_contact = "0922a2be-268c-11ec-9548-4a4901e9af12";

                if ($pr_shipto_contact == "" || $pr_shipto_contact == "[Select Category]") {
                    $validator->errors()->add('pr_shipto_contact', showmessage('000', array('{name}'), array(trans('label.lbl_shipto_contact')), true));
                }

                $pr_project_category = isset($jsondata['pr_project_category']) ? $jsondata['pr_project_category'] : "";

                if ($pr_project_category == "" || $pr_project_category == "[Select Project Category]") {
                    $validator->errors()->add('pr_project_category', showmessage('000', array('{name}'), array(trans('label.lbl_project_category')), true));
                }

                // api call for compay name

                $data['opportunity_id'] = $request['opp_no'];

                $res_data = $this->call_rest_api("https://115.124.96.115:4108/uat/get_opp_details_aim.php", $data);

                $company_data = json_decode($res_data);

                $opp_status = $company_data->result->status;

                if ($opp_status) {
                    $company_name = $company_data->result->customer_details->customer_name;
                } else {
                    $error = "Opportunity ID not valid";
                    $data['data'] = null;
                    $data['message']['error'] = $error;
                    $data['status'] = 'error';
                    return response()->json($data);
                }

                $word = "ESDS";

                // Test if string contains the word
                if (strpos($company_name, $word) !== false) {
                    $pr_project_category = 'Internal';
                } else {
                    $pr_project_category = 'External';
                }

                if ($pr_project_category == 'Internal') {
                    $pr_project_name_dd = isset($jsondata['pr_project_name_dd']) ? $jsondata['pr_project_name_dd'] : "";

                    if ($pr_project_name_dd == "" || $pr_project_name_dd == "[Select Project]") {
                        $validator->errors()->add('pr_project_name_dd', showmessage('000', array('{name}'), array(trans('label.lbl_project_name')), true));
                    }
                }

                if ($pr_project_category == 'External') {
                    $project_name = isset($jsondata['project_name']) ? $jsondata['project_name'] : "";

                    if ($project_name == "" || $project_name == null) {
                        $validator->errors()->add('project_name', showmessage('000', array('{name}'), array(trans('label.lbl_project_name')), true));
                    }

                    $project_wo_details = isset($jsondata['project_wo_details']) ? $jsondata['project_wo_details'] : "";

                    if ($project_wo_details == "" || $project_wo_details == null) {
                        $validator->errors()->add('project_wo_details', showmessage('000', array('{name}'), array(trans('label.lbl_project_wo_details')), true));
                    }

                    $opportunity_code = $request['opp_no'];

                    if ($opportunity_code == "" || $opportunity_code == null) {
                        $validator->errors()->add('opportunity_code', showmessage('000', array('{name}'), array('Opportunity Code'), true));
                    }

                    /*file upload part commented part start*/
                    // $customer_po_file_new = $request['customer_po_file_new'];

                    //  if ($customer_po_file_new =="" || $customer_po_file_new==null || $customer_po_file_new=="undefined") {
                    //  $validator->errors()->add('customer_po_file_new', showmessage('000', array('{name}'), array('Customer PO'), true));
                    //  }

                    //  $gc_approval_file_new = $request['gc_approval_file_new'];

                    //  if ($gc_approval_file_new =="" || $gc_approval_file_new==null || $gc_approval_file_new=="undefined") {
                    //  $validator->errors()->add('gc_approval_file_new', showmessage('000', array('{name}'), array('GC Approval'), true));
                    //  }

                    //  $costing_details_file_new = $request['costing_details_file_new'];

                    //  if ($costing_details_file_new =="" || $costing_details_file_new==null || $costing_details_file_new=="undefined") {
                    //  $validator->errors()->add('costing_details_file_new', showmessage('000', array('{name}'), array('Costing Details Against the Requirement'), true));
                    //  }
                    /*commented part start*/

                }

                if ($pr_due_date == "") {
                    $validator->errors()->add('pr_due_date', showmessage('000', array('{name}'), array(trans('label.lbl_purchaseduedate')), true));
                }
                if ($pr_priority == "" || $pr_priority == "[Select Priority]") {
                    $validator->errors()->add('pr_priority', showmessage('000', array('{name}'), array(trans('label.lbl_priority')), true));
                }

            }
        });

        $request['pr_no'] = $this->generatecrmprno();

        if ($validator->fails()) {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        } else {
            $formAction = $request->input('formAction');
            unset($request['formAction']);
            // print_r($request['asset_details']); exit;

            $form_templ_id_uuid = $request->input('form_templ_id');
            $request['form_templ_id'] = DB::raw('UUID_TO_BIN("' . $request->input('form_templ_id') . '")');

            $asset_detailsArr = $request['asset_details'];
            unset($request['asset_details']);
            $pr_po_type = $request['pr_po_type']; // As "Purchase Order Add (Without PR)" Will Work same link Purchase Request Save.

            // if ($formAction == "add") {

            if (1) {
                if ($request['approval_req'] == 'n') {
                    unset($request['approval_details']);
                }
                unset($request['pr_id']);
                DB::beginTransaction(); // begin transaction
                $result_id = "";
                $result_id_text = "";
                $result_message = trans('label.lbl_purchaserequest');

                if ($pr_po_type == "pr") {
                    // DB::enableQueryLog();
                    $purchaserequestresponse = EnPurchaseRequest::create($request->all());

                    // dd(DB::getQueryLog());

                    $result_id = $purchaserequestresponse['pr_id'];
                    $result_id_text = $purchaserequestresponse->pr_id_text;
                    $result_message = trans('label.lbl_purchaserequest');
                } else {
                    unset($request['form_templ_type']);
                    $purchaserequestresponse = EnPurchaseOrder::create($request->all());
                    $result_id = $purchaserequestresponse['po_id'];
                    $result_id_text = $purchaserequestresponse->po_id_text;
                    $result_message = trans('label.lbl_purchase_order');
                }

                //dd($purchaserequestresponse,$result_id);

                if (!empty($result_id)) {
                    $asset_inputdata = array();

                    foreach ($asset_detailsArr as $key => $asset) {
                        $asset_inputdata['pr_po_id'] = $result_id;
                        // $asset_inputdata['po_id']       = "";
                        $asset_inputdata['asset_type'] = $pr_po_type;
                        $asset_inputdata['asset_details'] = json_encode($asset, true);
                        $asset_inputdata['created_by'] = DB::raw('UUID_TO_BIN("73b9c9b8-87f8-11ec-afe5-86bd6599c53f")');
                        //print_r($asset_inputdata);
                        $purchaseAssetResponse = EnPrPoAssetDetails::create($asset_inputdata);
                    }

                    $data['data']['insert_id'] = $result_id_text;
                    $data['message']['success'] = showmessage('104', array('{name}'), array($result_message));
                    $data['status'] = 'success';

                    $hist_details = $this->gethistorydesc('created', $result_message);

                    //dd($result_id);

                    $docpostdata['opportunity_id'] = $request['opp_no'];

                    $doc_data = $this->call_rest_api("https://115.124.96.115:4108/uat/document_api_rest.php", $docpostdata);

                    $attach_doc = json_decode($doc_data);

                    $doc_data = $attach_doc->result->documents_details;

                    if ($doc_data != null) {
                        foreach ($doc_data as $docv) {

                            unset($inputdata);
                            $inputdata['pr_po_id'] = DB::raw('UUID_TO_BIN("' . $result_id_text . '")');
                            $inputdata['attachment_name'] = $docv->document_name;
                            $inputdata['created_by'] = DB::raw('UUID_TO_BIN("73b9c9b8-87f8-11ec-afe5-86bd6599c53f")');
                            $inputdata['file_title'] = "CRM uploaded file";
                            $inputdata['type'] = 'document';
                            $inputdata['attachment_type'] = 'pr';
                            $inputdata['status'] = 'y';
                            //echo '<pre>'; print_r($inputdata); echo '</pre>';
                            $res = EnPrPoAttachment::create($inputdata);

                        }
                    }
                    //Add into Purchase History
                    $this->prpohistoryadd(array('pr_po_id' => $result_id_text, 'history_type' => $pr_po_type, 'action' => 'created', 'details' => $hist_details, 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("73b9c9b8-87f8-11ec-afe5-86bd6599c53f")')));
                    //Add into UserActivityLog
                    userlog(array('record_id' => $result_id_text, 'data' => $request->all(), 'action' => 'added', 'message' => showmessage('104', array('{name}'), array($result_message))));
                    DB::commit();
                } else {
                    DB::rollBack();
                    $data['data'] = $request->all();
                    $data['message']['error'] = showmessage('103', array('{name}'), array($result_message));
                    $data['status'] = 'error';
                }
            }

            return response()->json($data);
        }
    }

    public function call_rest_api($rest_url, $post_array = array())
    {

        defined('CRM_API_AUTH') or define('CRM_API_AUTH', 'authorization: Basic Y3JtaWFwaWNsaWVudDo2QUc/eFIkczQ7UDkkPz8hSw=='); // crm api auth header
        $auth_header = CRM_API_AUTH;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $rest_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => json_encode($post_array),
            CURLOPT_FAILONERROR => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTPHEADER => array(
                $auth_header,
                "cache-control: no-cache",
                "content-type: Content-Type:application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return $response;
    }

    // function to parse the http auth header
    public function http_digest_parse($txt)
    {
        // protect against missing data
        $needed_parts = array('nonce' => 1, 'nc' => 1, 'cnonce' => 1, 'qop' => 1, 'username' => 1, 'uri' => 1, 'response' => 1);
        $data = array();
        $keys = implode('|', array_keys($needed_parts));

        preg_match_all('@(' . $keys . ')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $txt, $matches, PREG_SET_ORDER);

        foreach ($matches as $m) {
            $data[$m[1]] = $m[3] ? $m[3] : $m[4];
            unset($needed_parts[$m[1]]);
        }

        return $needed_parts ? false : $data;
    }

    public function generatecrmprno()
    {

        $dtl = '';

        $inv = DB::table('en_form_data_pr')->select('pr_no')->orderBy('created_at', 'desc')->first();
        if ($inv) {
            $dtl = $inv->pr_no;
        }

        $po_number_arr = $dtl;
        $po_no_array = explode('/', $po_number_arr);
        $last_po_number = end($po_no_array);
        if (date('m') <= 3) {
            //Upto Mar 2014-2015
            $financial_year = (date('Y') - 1) . '-' . date('y');
        } else {
            //After Mar 2015-2016
            $financial_year = date('Y') . '-' . (date('y') + 1);
        }

        if ($last_po_number != '1' && date('m') == '04') {
            $nextnumber = (int) 0001;
        } else {
            $nextnumber = (int) $last_po_number + 1;
        }

        //Ex: ESDS/2021-22/Sepember/0001
        $po_number = 'ESDS/PR/';
        $po_number .= $financial_year . '/';
        $po_number .= date('F') . '/';
        $po_number .= str_pad($nextnumber, 3, '0', STR_PAD_LEFT);
        return $po_number;
    }

    public function sd_purchaserequestadd(Request $request)
    {
        $auth_user = 'aimapiclient';
        $auth_pw = 'BK{b@QNw/+8%{xK@';

        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="My Realm"');
            header('HTTP/1.0 401 Unauthorized');
            $error = 'Access denied. You did not enter a password.';
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);

        } else {

            if ($_SERVER['PHP_AUTH_PW'] != $auth_pw || $_SERVER['PHP_AUTH_USER'] != $auth_user) {
                $error = "Access denied. Credentials does not match";
                $data['message']['error'] = $error;
                $data['status'] = 'error';
                return response()->json($data);

            }
        }        

        $opportunity_id = $request->opp_id;
        $pr_flag = $request->pr_flag;
        $shipping_address = $request->shipping_address;

        $contact_prefix = !empty($request->contact_prefix) ? crm_encrypt_decrypt_data($request->contact_prefix) : '';

        $contact_first_name = !empty($request->contact_first_name) ? crm_encrypt_decrypt_data($request->contact_first_name) : '';

        $contact_last_name = !empty($request->contact_last_name) ? crm_encrypt_decrypt_data($request->contact_last_name) : '';

        $contact_number = !empty($request->contact_number) ? crm_encrypt_decrypt_data($request->contact_number) : '';

        $shipping_contact = $contact_prefix . '' . $contact_first_name . ' ' . $contact_last_name . ' ' . $contact_number;

        $request = request();

        $opp_data = EnoppListing::where('opportunity_id', $opportunity_id)->get()->toArray();       

        if ($pr_flag == 'convert_to_pr') {
            if (!empty($opp_data)) {
                $basic_details = json_decode($opp_data[0]['basic_details']);
                $item_details = json_decode($opp_data[0]['item_json']);

                if (!empty($basic_details) && !empty($item_details)) {
                    //dd($basic_details,$item_details);// finel dd
                    $itemarr = array();
                    $invalidsku = array();
                    $iflag = false;
                    foreach ($item_details as $item) {
                        if ($item->item_quantity > 0) {
                            $assetdata = EnAssets::select(DB::raw('BIN_TO_UUID(asset_id) as asset_id'), 'asset_sku', DB::raw('BIN_TO_UUID(ci_templ_id) as ci_templ_id'))
                                ->where('asset_sku', $item->sku_code)
                                ->where('asset_status','=','in_procurement')
                                ->where('status', 'y')
                                ->first();

                            if ($assetdata) {
                                $itemarr['item'][] = $assetdata->ci_templ_id;
                                $itemarr['item_product'][] = $assetdata->asset_id;
                                $itemarr['item_desc'][] = $item->core_product_name;
                                $itemarr['warranty_support_required'][] = 'NA';
                                $itemarr['item_qty'][] = $item->item_quantity;

                            } else {
                                $invalidsku[] = $item;
                            }

                        }

                    }

                    if (!empty($invalidsku)) {

                        $emailbody = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml">
                <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <title></title>
                <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
                <style>
                body {
                  font-family: "Open Sans", sans-serif;
                }
                p {
                  margin: 0;
                }
                </style>
                </head>

                <body style="background:#f8f8f8;">
                <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="background:#FFF;">
                <tr>
                <td align="center" valign="top">
                <table width="550" border="0" cellspacing="0" cellpadding="0">
                <tr>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td><h3 style="color:#4349ac">Hello</h3>
                <p>Please set this unknown SKU to asset in AIM portal:</p>
                <br>

                </td>
                </tr>
                <tr>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td>
                <table style="width:100%;" cellspacing="0" cellpadding="0" >
                <tr>
                <th style="background:#f0f8ff; border:1px solid #ccc;padding:3px;"><p align="center"><strong>Sr No.</strong></p></th>
                <th style="background:#f0f8ff; border:1px solid #ccc;padding:3px;"><p align="center"><strong>Product Name</strong></p></th>
                <th style="background:#f0f8ff; border:1px solid #ccc;padding:3px;"><p align="center"><strong>SKU Code</strong></p></th>
                <th style="background:#f0f8ff; border:1px solid #ccc;padding:3px;"><p align="center"><strong>Unit Name</strong></p></th>
                </tr>';

                        $k = 1;
                        foreach ($invalidsku as $isku) {
                            $emailbody .= '
                  <tr>
                  <td style="border:1px solid #ccc;padding:5px;"><p>' . $k . '</p></td>
                  <td style="border:1px solid #ccc;padding:5px;"><p>' . $isku->core_product_name . '</p></td>
                  <td style="border:1px solid #ccc;padding:5px;"><p>' . $isku->sku_code . '</p></td>
                  <td style="border:1px solid #ccc;padding:5px;"><p>' . $isku->unit_name . ' </p></td>
                  </tr>';
                            $k++;
                        }

                        $emailbody .= '</table>
                </td>
                </tr>
                <tr>
                <td>&nbsp;</td>
                </tr>
                </table>
                </td>
                </tr>
                <tr>
                <td align="center" valign="top" style="background:#4349ac !important; height:35px; color:#fff; font-size:15px;">
                <table width="550" border="0" align="center" cellpadding="10" cellspacing="0">
                <tr>
                <td width="277" valign="top" align="left">Thank You</td>
                </tr>
                </table>
                </td>
                </tr>
                </table>
                </body>
                </html>';

                        $phpmailer = new Maillib();
                        $to_emails = 'rohit.r@esds.co.in';
                        $subject = 'AIM : Add unknown SKU details';
                        $email_body = $emailbody;
                        $mailresponse = $phpmailer->mailsent($to_emails, $subject, $email_body);

                    }

                    if (!empty($itemarr)) {
                        $request['asset_details'] = json_encode($itemarr);
                    } else {
                        $error = "Item data not found for PR request.";
                        $data['data'] = null;
                        $data['message']['error'] = $error;
                        $data['status'] = 'error';
                        return response()->json($data);
                    }

                    $request['approval_req'] = 'y';
                    $request['form_templ_id'] = '22a10f62-041e-11ec-bc69-a2bc2bf41391';
                    $request['urlpath'] = 'purchaserequest';
                    $request['form_templ_type'] = 'default';
                    $request['status'] = 'approved';
                    $request['approved_status'] = '{
                "confirmed": {
                  "73b9c9b8-87f8-11ec-afe5-86bd6599c53f": "approved"
                }
              }';

                    $approval_users = json_decode('{"confirmed":["73b9c9b8-87f8-11ec-afe5-86bd6599c53f"],"optional":[]}', true);

                    $request['approval_details'] = json_encode($approval_users);

                    $request['requester_id'] = DB::raw('UUID_TO_BIN("73b9c9b8-87f8-11ec-afe5-86bd6599c53f")');

                    $asset_arr = array();
                    $asset_details = json_decode($request['asset_details'], true);

                    if ($asset_details) {

                        if (isset($asset_details['item']) && isset($asset_details['item_desc']) && isset($asset_details['item_qty']) && isset($asset_details['warranty_support_required'])) {

                            foreach ($asset_details['item'] as $key => $item) {

                                $asset_arr[$key]['item'] = $item;
                                $asset_arr[$key]['item_product'] = $asset_details['item_product'][$key];
                                $asset_arr[$key]['item_desc'] = $asset_details['item_desc'][$key];
                                $asset_arr[$key]['item_qty'] = $asset_details['item_qty'][$key];
                                $asset_arr[$key]['warranty_support_required'] = $asset_details['warranty_support_required'][$key];

                            }
                        }
                    }

                    $request['asset_details'] = $asset_arr;

                    if ($request['urlpath'] == "purchaserequest") {

                        $pr_due_date = date('Y-m-d', strtotime(date('Y-m-d') . '+7 day'));
                        $pr_priority = "high";

                        $pr_requester_name = "75088434907d11ecab9e4eb834f5915d";

                        $pr_requirement_for = "IT";

                        $pr_category = "Services";

                        $pr_shipto = "9ff21ebb-46d2-11ec-9512-764a8a13ae2c";

                        $pr_shipto_contact = "0922a2be-268c-11ec-9548-4a4901e9af12";

                        $company_name = $basic_details->company_name;

                        $word = "ESDS";

                        $pr_project_name_dd = '';

                        // Test if string contains the word
                        if (strpos($company_name, $word) !== false) {
                            $pr_project_category = 'Internal';
                        } else {
                            $pr_project_category = 'External';
                        }

                        if ($pr_project_category == 'Internal') {
                            // $pr_project_name_dd = $basic_details->project_name;
                            $project_name = $basic_details->project_name;
                            $pr_project_name_dd = "NDC";
                            $project_wo_details = "NA";

                            $opportunity_code = $opportunity_id;
                        }

                        if ($pr_project_category == 'External') {
                            $project_name = $basic_details->project_name;

                            $project_wo_details = "NA";

                            $opportunity_code = $opportunity_id;
                        }
                    }

                    $request['pr_no'] = $this->generatecrmprno();
                    $pr_po_type = 'pr';
                    $request['pr_po_type'] = 'pr';

                    $detailsarr = array();

                    $detailsarr['pr_requester_name'] = $pr_requester_name;
                    $detailsarr['pr_requirement_for'] = $pr_requirement_for;
                    $detailsarr['pr_category'] = $pr_category;
                    $detailsarr['pr_due_date'] = $pr_due_date;
                    $detailsarr['pr_shipto'] = $pr_shipto;
                    $detailsarr['pr_shipto_contact'] = $pr_shipto_contact;
                    $detailsarr['ship_to_other'] = $shipping_address;
                    $detailsarr['ship_to_contact_other'] = $shipping_contact;
                    $detailsarr['pr_priority'] = $pr_priority;
                    $detailsarr['pr_project_category'] = $pr_project_category;
                    $detailsarr['pr_project_category'] = $pr_project_category;
                    $detailsarr['formAction'] = "add";
                    $detailsarr['pr_id'] = "";
                    $detailsarr['pr_project_category_hidden'] = "";
                    $detailsarr['pr_shipto_hidden'] = "";
                    $detailsarr['pr_shiptocontact_hidden'] = "";
                    $detailsarr['pr_project_name_dd'] = $pr_project_name_dd;
                    $detailsarr['project_name'] = $project_name;
                    $detailsarr['project_wo_details'] = $project_wo_details;
                    $detailsarr['opportunity_code'] = $opportunity_id;
                    $detailsarr['pr_department'] = 'SD';
                    $detailsarr['pr_req_date'] = date('Y-m-d');
                    $detailsarr['pr_req_date'] = date('Y-m-d');
                    $detailsarr['approvers_optional'] = "";

                    $request['details'] = json_encode($detailsarr);

                    $asset_detailsArr = $request['asset_details'];
                    unset($request['asset_details']);

                    if (1) {

                        unset($request['pr_id']);
                        DB::beginTransaction(); // begin transaction
                        $result_id = "";
                        $result_id_text = "";
                        $result_message = trans('label.lbl_purchaserequest');

                        if ($pr_po_type == "pr") {
                            // DB::enableQueryLog();
                            $purchaserequestresponse = EnPurchaseRequest::create($request->all());

                            // dd(DB::getQueryLog());

                            $result_id = $purchaserequestresponse['pr_id'];
                            $result_id_text = $purchaserequestresponse->pr_id_text;
                            $result_message = trans('label.lbl_purchaserequest');
                        }

                        //dd($purchaserequestresponse,$result_id);

                        if (!empty($result_id)) {
                            $asset_inputdata = array();

                            foreach ($asset_detailsArr as $key => $asset) {
                                $asset_inputdata['pr_po_id'] = $result_id;
                                // $asset_inputdata['po_id']       = "";
                                $asset_inputdata['asset_type'] = $pr_po_type;
                                $asset_inputdata['asset_details'] = json_encode($asset, true);
                                $asset_inputdata['created_by'] = DB::raw('UUID_TO_BIN("73b9c9b8-87f8-11ec-afe5-86bd6599c53f")');
                                // print_r($asset_inputdata);die;
                                $purchaseAssetResponse = EnPrPoAssetDetails::create($asset_inputdata);
                            }

                            $data['data']['insert_id'] = $result_id_text;
                            $data['message']['success'] = showmessage('104', array('{name}'), array($result_message));
                            $data['status'] = 'success';

                            $hist_details = $this->gethistorydesc('created', $result_message);

                            //dd($result_id);

                            $docpostdata['opportunity_id'] = $opportunity_id;
                            $doc_data = $this->call_rest_api("https://115.124.96.115:4108/uat/document_api_rest.php", $docpostdata);
                            $attach_doc = json_decode($doc_data);
                            $doc_data = $attach_doc->result->documents_details;
                            if ($doc_data != null) {
                                foreach ($doc_data as $docv) {

                                    unset($inputdata);
                                    $inputdata['pr_po_id'] = DB::raw('UUID_TO_BIN("' . $result_id_text . '")');
                                    $inputdata['attachment_name'] = $docv->document_name;
                                    $inputdata['created_by'] = DB::raw('UUID_TO_BIN("73b9c9b8-87f8-11ec-afe5-86bd6599c53f")');
                                    $inputdata['file_title'] = "CRM uploaded file";
                                    $inputdata['type'] = 'document';
                                    $inputdata['attachment_type'] = 'pr';
                                    $inputdata['status'] = 'y';
                                    // echo '<pre>'; print_r($inputdata); echo '</pre>';die;
                                    $res = EnPrPoAttachment::create($inputdata);
                                 }
                             }
                            //Add into Purchase History
                            $this->prpohistoryadd(array('pr_po_id' => $result_id_text, 'history_type' => $pr_po_type, 'action' => 'created', 'details' => 'Auto Purchase Request created.', 'comment' => 'Auto Approved', 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("73b9c9b8-87f8-11ec-afe5-86bd6599c53f")')));
                            //Add into UserActivityLog
                            userlog(array('record_id' => $result_id_text, 'data' => $request->all(), 'action' => 'added', 'message' => showmessage('104', array('{name}'), array($result_message))));

                            $opplist = EnoppListing::where('opportunity_id', $opportunity_id)->first();
                            $opplist->pr_id = $result_id;
                            $opplist->pr_no = $request['pr_no'];
                            $opplist->pr_create_date = date('Y-m-d H:i:s');
                            $opplist->update();

                            user_notification(array('type' => 'pr', 'message' => 'pr request', 'store_user' => DB::raw('UUID_TO_BIN("73b9c9b8-87f8-11ec-afe5-86bd6599c53f")'), 'show_user' => '', 'action' => 'add'));

                            DB::commit();
                        } else {
                            DB::rollBack();
                            $data['data'] = $request->all();
                            $data['message']['error'] = showmessage('103', array('{name}'), array($result_message));
                            $data['status'] = 'error';
                        }
                    }

                    return response()->json($data);

                } else {
                    $error = "Opportunity data not found";
                    $data['data'] = null;
                    $data['message']['error'] = $error;
                    $data['status'] = 'error';
                    return response()->json($data);
                }

            } else {
                $error = "Opportunity ID not valid";
                $data['data'] = null;
                $data['message']['error'] = $error;
                $data['status'] = 'error';
                return response()->json($data);
            }
        } else {
            $error = "PR flag not valid";
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        }
        exit;
        // dd($opportunity_id,$pr_flag,$opp_data);

    }
    public function sampleprexport(Request $request)
    {
        try
        {
            $result = Enprsample::getsamplepr();
            $data['data'] = $result;
            $data['message']['error'] = null;
            $data['status'] = 'success';
            return response()->json($data);

        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            return response()->json($data);
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            return response()->json($data);
        }
    }

    public function trackpurchaserequest() 
    {
        try {
                $result                     = EnPurchaseRequest::get_pr_list();

                $data['data']                   = $result->isEmpty() ? null : $result;
                if ($data['data']) {
                    $data['message']['error'] = "No Data Found";
                   
                } else {
                    $data['message']['success'] = "Success";
                }
                $data['status']          = 'success';
        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("purchaseorders", "This controller function is implemented to show Pr list.", $request->all(), $e->getMessage());
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("purchaseorders", "This controller function is implemented to show Pr list.", $request->all(), $e->getMessage());
        } finally {
            return response()->json($data);
        }
    }

    public function addremark(Request $request) {
        try {
            $inputdata                  = $request->all();           
            $res                        = EnPurchaseRequest::addremark($inputdata);
            $data['data']               = $res;
            $data['message']['error']   = '';
            $data['status']             = 'success';
        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("PurchaseController", "This controller function is implemented to show trackpurchaserequest.", $request->all(), $e->getMessage());
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("PurchaseController", "This controller function is implemented to show trackpurchaserequest.", $request->all(), $e->getMessage());
        } finally {
            return response()->json($data);
        }
    }
    

   public function track_pr_list(Request $request)
     {   
         $inputdata                      = $request->all();
         $inputdata['searchkeyword']     = trim(_isset($inputdata, 'searchkeyword'));
         $totalrecords                   = EnPurchaseRequest::get_track_pr_list($inputdata, true);
         $result                         = EnPurchaseRequest::get_track_pr_list($inputdata, false);

         $data['data']['records']        = $result->isEmpty() ? null : $result;
         $data['data']['totalrecords']   = $totalrecords;

         if ($totalrecords < 0) {
             $data['message']['error']   = 'No data found';
           $data['status']             = 'error';
         } else {
             $data['message']['success'] = 'Data found';
             $data['status']             = 'success';
         }
         return response()->json($data);
     }
 


public function track_pr_list_for_export(Request $request)
    {   
        $inputdata                      = $request->all();
        $inputdata['searchkeyword']     = trim(_isset($inputdata, 'searchkeyword'));
        $totalrecords                   = EnPurchaseRequest::get_track_pr_list_for_export($inputdata, true);
        $result                         = EnPurchaseRequest::get_track_pr_list_for_export($inputdata, false);

        $data['data']['records']        = $result->isEmpty() ? null : $result;
        $data['data']['totalrecords']   = $totalrecords;

        if ($totalrecords < 0) {
            $data['message']['error']   = 'No data found';
            $data['status']             = 'error';
        } else {
            $data['message']['success'] = 'Data found';
            $data['status']             = 'success';
        }
        return response()->json($data);
    }

/**********Start of New Method for PurchasRequest report********/

    public function purchaseprreport(Request $request)
    {
          try
        {

            $prpouserdata = [];

            $dtl = '';
            $invoice_id_bin = [];
            foreach ($request->input('userids') as $value) {
                $invoice_id_bin[] = DB::raw("UUID_TO_BIN('" . $value . "')");
            }
           // $inv = DB::table('en_form_data_pr')->select(DB::raw("BIN_TO_UUID(assignpr_user_id) as assignpr_user_id"),DB::raw('count(pr_id) as total'),'pr_no','created_at','status')->whereIn('assignpr_user_id', $invoice_id_bin)->groupBy('assignpr_user_id')->get();


	$inv = DB::table('en_form_data_pr')
	     ->select(DB::raw("BIN_TO_UUID(assignpr_user_id) as assignpr_user_id"),
		DB::raw('count(pr_id) as total'),
		'pr_no',
		'created_at',
		'status')
	    ->whereIn('assignpr_user_id', $invoice_id_bin)
	   ->groupBy('pr_no')
	   ->get();

	



            $prpouserdata['Totalpr'] = $inv;

            /*  $queries    = DB::getQueryLog();
            $data['data'] = end($queries);*/

           


            //return result
            $data['data'] = $prpouserdata;
            $data['message']['success'] = showmessage('142', array('{name}'), array(trans('label.lbl_vendor')), true);
            $data['status'] = 'success';
            return response()->json($data);

        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("getvendorbyid", "This controller function is implemented to get vendor name .", $request->all(), $e->getMessage());
            return response()->json($data);
        }

    }



/**********End of New Method for PurchasRequest report**********/


} // Class End
