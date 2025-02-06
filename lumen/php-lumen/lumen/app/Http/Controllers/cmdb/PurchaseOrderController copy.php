<?php
namespace App\Http\Controllers\cmdb;

use App\Http\Controllers\Controller;
use App\Models\EnAssets;
use App\Models\EnBillTo;
use App\Models\EnCiTemplCustom;
use App\Models\EnCiTemplDefault;
use App\Models\EnContacts;
use App\Models\EnDelivery;
use App\Models\EnInvoice;
use App\Models\EnPaymentterms;
use App\Models\EnPrPoAssetDetails;
use App\Models\EnPrPoAttachment;
use App\Models\EnPrPoHistory;
use App\Models\EnPrPoQuotationcomparison;
use App\Models\EnPrPoQuotationcomparisonReject;
use App\Models\EnPurchaseOrder;
use App\Models\EnPurchaseRequest;
use App\Models\EnRequesternames;
use App\Models\EnShipTo;
use App\Models\EnVendors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class PurchaseOrderController extends Controller
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

    public function purchaserequests(Request $request, $pr_id = null)
    {
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
                $inputdata['searchkeyword'] = trim(_isset($inputdata, 'searchkeyword'));
                $totalrecords = EnPurchaseRequest::getprs($pr_id, $inputdata, true);
                $result = EnPurchaseRequest::getprs($pr_id, $inputdata, false);

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

                    $resultPo = EnPurchaseOrder::select(DB::raw('BIN_TO_UUID(po_id) AS po_id'), DB::raw('JSON_UNQUOTE(JSON_EXTRACT(details,"$.pr_vendor")) as pr_vendor'))->where('pr_id', DB::raw("UUID_TO_BIN('" . $each_pr->pr_id . "')"))->get();
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

    public function resendtoapproval(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'po_id' => 'required|allow_uuid|string|size:36',
                'user_id' => 'required|allow_uuid|string|size:36',
            ]);
            if ($validator->fails()) {
                $error = $validator->errors();
                $data['data'] = null;
                $data['message']['error'] = $error;
                $data['status'] = 'error';
            } else {

                $result = EnPurchaseOrder::select('approved_status', 'status', DB::raw('BIN_TO_UUID(po_id) as po_id'))->where('po_id', DB::raw('UUID_TO_BIN("' . $request['po_id'] . '")'))->first();

                if (!empty($result)) {
                    $details = json_decode($result['approved_status'], true);
                    if (array_key_exists($request['user_id'], $details['confirmed'])) {
                        unset($details['confirmed'][$request['user_id']]);
                    }
                    /* $request['approved_status'] = json_encode($details,true);
                    $request['status'] = 'pending approval';*/
                    /* $result->update($request->all());
                    $result->save();*/
                    DB::table('en_form_data_po')
                        ->where('po_id', DB::raw('UUID_TO_BIN("' . $request['po_id'] . '")'))
                        ->update(['approved_status' => json_encode($details, true), 'status' => 'pending approval']);

                    $this->prpohistoryadd(array('pr_po_id' => $request->input('po_id'), 'history_type' => 'po', 'action' => 'resend approval', 'details' => 'Purchase order resend to approval', 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")')));
                }

                /*$queries    = DB::getQueryLog();
                $data['data'] = end($queries);*/

                $data['data'] = $result['po_id'];
                if ($data['data']) {
                    $data['message']['success'] = 'Purchase order resent to approval';
                    $data['status'] = 'success';
                } else {

                    $data['message']['error'] = 'Purchase order resent to approval failed';
                    $data['status'] = 'error';
                }
            }
        } catch (\Exception $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("prdetails", "This controller function is implemented to fetch details of particular purchase order.", $request->all(), $e->getMessage());
        } catch (\Error $e) {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("prdetails", "This controller function is implemented to fetch details of particular purchase order.", $request->all(), $e->getMessage());
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

        $validator->after(function ($validator) {
            $request = request();
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
                        $validator->errors()->add('customer_po_file_new', showmessage('000', array('{name}'), array('Customer PO'), true));
                    }

                    $gc_approval_file_new = $request['gc_approval_file_new'];

                    if ($gc_approval_file_new == "" || $gc_approval_file_new == null || $gc_approval_file_new == "undefined") {
                        $validator->errors()->add('gc_approval_file_new', showmessage('000', array('{name}'), array('GC Approval'), true));
                    }

                    $costing_details_file_new = $request['costing_details_file_new'];

                    if ($costing_details_file_new == "" || $costing_details_file_new == null || $costing_details_file_new == "undefined") {
                        $validator->errors()->add('costing_details_file_new', showmessage('000', array('{name}'), array('Costing Details Against the Requirement'), true));
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
                'approval_status' => 'required|:rejected,approved,comment',
                'pr_po_type' => 'required',
                'comment' => 'required',
            ], $messages);
            if ($validator->fails()) {
                $error = $validator->errors();
                $data['data'] = null;
                $data['message']['error'] = $error;
                $data['status'] = 'error';
            } else {
                DB::beginTransaction(); // begin transaction
                $pr_po_id_uuid = $request->input('pr_po_id');
                $pr_po_type = $request->input('pr_po_type');
                $result_message = trans('label.lbl_purchaserequest');

                // unset($request['pr_po_type']); //Commentd on 1st Oct 2020 as hardcoded PR mention as type.

                $pr_po_id_bin = DB::raw('UUID_TO_BIN("' . $request->input('pr_po_id') . '")');
                $comment = $request->has('comment') ? $request->input('comment') : "";

                if ($pr_po_type == "pr") {
                    $result = EnPurchaseRequest::where('pr_id', $pr_po_id_bin)->first();
                    $result_message = trans('label.lbl_purchaserequest');
                } else {
                    $result = EnPurchaseOrder::where('po_id', $pr_po_id_bin)->first();
                    $result_message = trans('label.lbl_purchase_order');
                }

                $confirmed_optional = $request->has('confirmed_optional') ? $request->input('confirmed_optional') : "";

                // $queries    = DB::getQueryLog();
                //$data['last_query'] = end($queries);
                if ($result) {
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
                            $actual_path = 'uploads/purchase/';
                            $target_dir = public_path($actual_path); // add the specific path to save the file
                            $saveimg = 'Invoice_' . $request->input('file_name');
                            $file_dir = $target_dir . "/" . $saveimg;
                            $decoded_file = base64_decode($request->input('file'));

                            $formaction = $request->has('formaction') ? $request->input('formaction') : "";
                            $invoice_id = $request->has('invoice_id') ? $request->input('invoice_id') : "";
                            $arrDetails = array();
                            $arrDetails['id'] = $request->has('id') ? $request->input('id') : "";
                            $arrDetails['received_date'] = $request->has('received_date') ? $request->input('received_date') : "";
                            $arrDetails['payment_due_date'] = $request->has('payment_due_date') ? $request->input('payment_due_date') : "";
                            $arrDetails['comment'] = $request->has('comment') ? $request->input('comment') : "";
                            $arrDetails['invoice_file_name'] = $actual_path . $saveimg;
                            $inputdata['po_id'] = DB::raw('UUID_TO_BIN("' . $pr_po_id_uuid . '")');

                            if ($invoice_id != "") {
                                $inputdata['invoice_id'] = DB::raw('UUID_TO_BIN("' . $invoice_id . '")');
                            } else {
                                $inputdata['invoice_id'] = DB::raw('UUID_TO_BIN(UUID())');
                            }

                            $inputdata['details'] = json_encode($arrDetails);
                            $inputdata['created_by'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');

                            if ($formaction == "edit") {

                                if (file_put_contents($file_dir, $decoded_file)) {

                                    $invoiceData = EnInvoice::where('po_id', $pr_po_id_bin)->where('invoice_id', $inputdata['invoice_id'])->first();
                                    if ($invoiceData) {
                                        $invoiceData->update($inputdata);
                                        $invoiceData->save();
                                        $action = 'updated'; //for history
                                        $result_message = trans('label.lbl_invoice');
                                    }
                                }
                            } else {
                                // DB::enableQueryLog();

                                $decoded_file = base64_decode($request->input('file')); // decode the file

                                if (file_put_contents($file_dir, $decoded_file)) {
                                    $result_invoice = EnInvoice::create($inputdata);
                                }

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

    public function checkPOisGeneratedOrNot(Request $request)
    {
        $pr_ids = $request->input('pr_ids');
        $prId = "";
        foreach ($pr_ids as $pr_id) {
            if ($prId != '') {
                $prId .= ",";
            }
            $prId .= "uuid_to_bin('" . $pr_id . "')";
        }
        // $results = DB::select( DB::raw("SELECT bin_to_uuid(p.pr_id) as pr_id ,pr_no,
        // (select count(*) from en_pr_po_asset_details where pr_po_id =  p.pr_id) as pr_asset_cnt,
        // SUM((select count(*) from en_pr_po_asset_details where pr_po_id = po.po_id)) as po_asset_cnt,
        // (select count(*) from en_pr_po_asset_details where pr_po_id =  p.pr_id)
        // - SUM((select count(*) from en_pr_po_asset_details where pr_po_id = po.po_id)) as difference_prpo_asset_count
        //  FROM `en_form_data_pr` p
        // left join en_pr_po_asset_details as a on a.pr_po_id= p.pr_id
        // left join en_form_data_po as po on po.pr_id = p.pr_id
        // WHERE p.pr_id
        // IN  ($prId)
        // -- and p.status='approved'
        // GROUP by p.pr_id") );

        $results = DB::select(DB::raw("SELECT bin_to_uuid(p.pr_id) as pr_id ,pr_no,
        (select count(*) from en_pr_po_asset_details where pr_po_id =  p.pr_id) as pr_asset_cnt,
        SUM((select count(*) from en_pr_po_asset_details where pr_po_id = po.po_id)) as po_asset_cnt,
        (select count(*) from en_pr_po_asset_details where pr_po_id =  p.pr_id)
        - SUM((select count(*) from en_pr_po_asset_details where pr_po_id = po.po_id)) as difference_prpo_asset_count
        FROM `en_form_data_pr` p
        left join en_form_data_po as po on po.pr_id = p.pr_id
        WHERE p.pr_id
        IN  ($prId)
        -- and p.status='approved'
        GROUP by p.pr_id"));
        $data['data'] = $results;
        $data['status'] = 'success';
        $data['message']['success'] = "Success";
        return response()->json($data);
    }

    public function assetskuunit(Request $request)
    {
        $SkuCodes = $request->input('asset_sku');
        $skyCode = "";
        foreach ($SkuCodes as $code) {
            if ($skyCode != '') {
                $skyCode .= ",";
            }
            $skyCode .= "'" . $code . "'";
        }
        $results = DB::select(DB::raw("SELECT sku_code,measurement_unit_name FROM `en_sku_mst` WHERE `sku_code` IN  ($skyCode)"));
        $data['data'] = $results;
        $data['status'] = 'success';
        $data['message']['success'] = "Success";
        return response()->json($data);
    }
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
            /*$result = DB::select('call getAssetsData(?,?)',array('pr','144d8ba6-1167-11ed-b2a2-0233c6d207bf'));
            $data['data']             = $result;
            $data['message']['success'] = 'success';
            $data['status'] = 'success;
            return response()->json($data);*/

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
                $inputdata['po_amt_status'] = trim(_isset($inputdata, 'po_amt_status'));
                $inputdata['vendor_id'] = trim(_isset($inputdata, 'vendor_id'));

                $totalrecords = EnPurchaseOrder::getpos($po_id, $inputdata, true);
                $result = EnPurchaseOrder::getpos($po_id, $inputdata, false);
                // $queries    = DB::getQueryLog();
                // $data['data']             = $queries;
                // $data['message']['success'] = 'success';
                // $data['status'] = 'success';
                // return response()->json($data);
                
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
                        //get vendor details
                        $pr_id = isset($po_details_arr['pr_id']) ? explode(',', $po_details_arr['pr_id']) : "";

                        if (!empty($pr_id)) {
                            $ids = array_map(function ($id) {
                                return DB::raw('UUID_TO_BIN("' . $id . '")');
                            }, $pr_id);
                            $db_pr_id = DB::raw('bin_to_uuid(pr_id) as pr_id');
                            $pr_ids = EnPurchaseRequest::select('pr_no', $db_pr_id)->whereIn('pr_id', $ids)->get();
                            if (empty($pr_ids)) {
                                $each_po->pr_no = null;
                            } else {
                                $each_po->pr_no = $pr_ids;
                            }
                        } else {
                            $each_po->pr_no = null;
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

                                    $db_skucodes = DB::table('en_assets')->select('asset_sku')->where('asset_id', DB::raw('UUID_TO_BIN("' . $asset_arr["item_product"] . '")'))->first();
                                    $ci_asset_details_a = EnAssets::getassets(['asset_sku' => $db_skucodes->asset_sku]);

                                    $ci_asset_details_c = EnCiTemplCustom::getcitemplatesC($asset_arr['item']);

                                    if (!$ci_asset_details_d->isEmpty()) {
                                        $each_po->ci_asset_details[$asset_arr['item']] = $ci_asset_details_d[0]->ci_name;

                                        if (!$ci_asset_details_a->isEmpty()) {
                                            $each_po->ci_asset_details['skucodes'] = $ci_asset_details_a[0]->asset_sku;
                                            $each_po->ci_asset_details['asset_id'] = $ci_asset_details_a[0]->asset_id;
                                            $each_po->ci_asset_details['item_name'] = $ci_asset_details_a[0]->display_name;
                                        } else {
                                            $each_po->ci_asset_details['skucodes'] = '';
                                        }

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
                                        $each_po->ci_asset_details['skucodes'] = null;
                                    }

                                    //set received asset count
                                    $cnt = 0;
                                    if (!empty($each_po->ci_asset_details['skucodes'])) {
                                        $po_id_bin = DB::raw('UUID_TO_BIN("' . $po_id . '")');
                                        $cnt = DB::table('en_assets')->where('po_id', $po_id_bin)->where('asset_sku', $each_po->ci_asset_details['skucodes'])->count();
                                    }

                                    $each_po->ci_asset_received_count[$asset_arr['item_product']] = $cnt;
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

        $res = EnPrPoQuotationcomparisonReject::where('pr_po_id', $pr_po_id);
        $update_array = array('reject_comment' => $comment, 'approve_reject_by' => $approve_reject_by, 'approval' => $approval_status);
        $res->update($update_array);
        $res->save();

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

        $res = EnPrPoQuotationcomparison::where('pr_po_id', $pr_po_id)->where('selected_item_id', $selected_item_id)->first();
        $update_array = array('vendor_approve' => $vendor_approve, 'reject_comment' => $reject_comment, 'approve_reject_by' => $approve_reject_by, 'approval' => $approval);
        $res->update($update_array);
        $res->save();

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

        $res = EnPrPoQuotationcomparison::where('pr_po_id', $pr_po_id)->where('selected_item_id', $selected_item_id)->first();
        $update_array = array('vendor_approve' => $vendor_approve, 'updated_by' => $updated_by);
        $res->update($update_array);
        $res->save();

        $result_message = 'Quotation Comparison';
        $hist_details = $this->gethistorydesc('updated', $result_message);
        $this->prpohistoryadd(array('pr_po_id' => $request['pr_po_id'], 'history_type' => 'pr', 'action' => 'updated', 'details' => $hist_details, 'comment' => 'NA', 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("' . $request['created_by'] . '")')));

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

                                if (!empty($asset_details['item_addresses'][$key])) {
                                    $asset_arr[$key]['item_addresses'] = $asset_details['item_addresses'][$key];
                                }

                                $asset_arr[$key]['item'] = $item;
                                $asset_arr[$key]['item_desc'] = $asset_details['item_desc'][$key];
                                $asset_arr[$key]['item_product'] = $asset_details['item_product'][$key];
                                $asset_arr[$key]['item_unit'] = $asset_details['item_unit'][$key];
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
                        $pr_delivery = isset($jsondata['pr_delivery']) ? $jsondata['pr_delivery'] : "";
                        $pr_delivery_terms = isset($jsondata['pr_delivery_terms']) ? $jsondata['pr_delivery_terms'] : "";
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
                        if ($pr_delivery == "") {
                            $validator->errors()->add('pr_delivery', 'The pr delivery field should be required.');
                        }
                        if ($pr_delivery_terms == "") {
                            $validator->errors()->add('pr_delivery_terms', 'The pr delivery term field should be required.');
                        }
                        if ($pr_taxes == "") {
                            $validator->errors()->add('pr_taxes', 'The pr taxes field should be required.');
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
            // $request->all()
            // $data['data'] = $request->all();
            // $data['status'] = 'success';
            // $data['message']['success'] = 'success';
            // return response()->json($data);
            // 
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

                if ($formAction == "add") {
                    if ($request['approval_req'] == 'n') {
                        unset($request['approval_details']);
                    }

                    //unset($request['asset_details']);
                    unset($request['formAction']);
                    unset($request['pr_po_type']);

                    $pr_id = explode(',', $request->input('pr_id'));
                    if (!is_array($pr_id) && count($pr_id) <= 1) {
                        $purchaseRequestResponse = EnPurchaseRequest::where('pr_id', DB::raw('UUID_TO_BIN("' . $pr_id[0] . '")'))->first();
                        if ($purchaseRequestResponse) {

                            //$request['details']      = $purchaseRequestResponse['details'];
                            $request['bv_id'] = DB::raw('UUID_TO_BIN("d7df036a-0a10-11ec-ad77-4e89be533080")'); //$purchaseRequestResponse['bv_id'];
                            $request['dc_id'] = DB::raw('UUID_TO_BIN("14fe35f4-0a11-11ec-9503-4e89be533080")'); //$purchaseRequestResponse['bv_id'];
                            $request['location_id'] = DB::raw('UUID_TO_BIN("e0ce8c54-0c9d-11ec-905c-4e89be533080")'); //$purchaseRequestResponse['bv_id'];

                            // $request['pr_id'] = $request->input('pr_id');
                            // $request['pr_id'] = $purchaseRequestResponse['pr_id'];
                        }
                    } else {
                        $purchaseAssetDetailsResponse = EnPrPoAssetDetails::getPrPoAssetDetails($pr_id[0], 'pr', $request['pr_vendor'], $pr_id);

                        $asset_detailsArr = $purchaseAssetDetailsResponse ? $purchaseAssetDetailsResponse : array();
                        //  print_r($request->all());
                        // $request['pr_id'] = DB::raw('UUID_TO_BIN("' . $pr_id[0] . '")');
                    }
                    // $request['pr_id'] = DB::raw('UUID_TO_BIN("' . $request['pr_id'] . '")');
                    $request['pr_id'] = DB::raw('UUID_TO_BIN("' . $request->input('pr_id') . '")');
                    /*$purchaseAssetDetailsResponse = EnPrPoAssetDetails::where('pr_po_id', $request->input('pr_id'))->where('asset_type', 'pr')->get();*/

                    //unset($request['pr_id']);
                    //array();

                    $request['form_templ_id'] = DB::raw('UUID_TO_BIN("' . $request->input('form_templ_id') . '")');
                    // $request['pr_id']          = DB::raw('UUID_TO_BIN("'.$asset_detailsArr[0]['pr_po_id'].'")');
                    // $request['pr_id']          = $asset_detailsArr[0]['pr_po_id'];
                    //
                    // $data['data'] = $request->all();                                        
                    // $data['status'] = 'success';
                    // $data['message']['success'] = 'success';
                    // return response()->json($data);
                    //
                    DB::beginTransaction(); // begin transaction
                    $result_id = "";
                    $result_id_text = "";
                    $result_message = "";
                    $purchaseOrderResponse = EnPurchaseOrder::create($request->all());
                    $result_id = $purchaseOrderResponse['po_id'];
                    $result_id_text = $purchaseOrderResponse->po_id_text;
                    $result_message = "Purchase Order";
                    
                    if (!empty($result_id)) {
                        // $data['data'] = $purchaseOrderResponse;
                        // $data['status'] = 'success';
                        // $data['message']['success'] = 'success';
                        // return response()->json($data);
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
                        $post_assets_details = $request['asset_details'];
                        foreach ($asset_detailsArr as $key => $asset) {
                            $item_amt = json_decode($asset['vendor_approve'], true);
                            $vendor_id = $item_amt['vendor_id'];
                            $comp = json_decode($asset['quotation_comparison_data'], true);

                            //$gst = $comp[$vendor_id];
                            $gst['vendor_id'] = $vendor_id;
                            if (!empty($comp[$vendor_id]['gst_extra'][0])) {
                                $gst['gst_extra'] = $comp[$vendor_id]['gst_extra'][0];
                            }
                            if (!empty($comp[$vendor_id]['quotation_reference_no'][0])) {
                                $gst['quotation_reference_no'] = $comp[$vendor_id]['quotation_reference_no'][0];
                            }

                            $asset_inputdata['pr_po_id'] = $result_id;
                            // $asset_inputdata['po_id']  = "";
                            $asset_inputdata['asset_type'] = 'po';

                            $asset_aary = json_decode($asset['asset_details'], true);
                            if (!empty($post_assets_details)) {
                                foreach ($post_assets_details as $itemarr) {
                                    if (!empty($itemarr['item_addresses'])) {
                                        $add1 = [];
                                        foreach ($itemarr['item_addresses'] as $value) {
                                            if ($itemarr['item_product'] == $asset_aary['item_product']) {
                                                $add = explode('~', $value);
                                                $add1[] = array('location' => $add[1], 'qty' => $add[2], 'address_id' => $add[0]);
                                                $asset_aary['addresses'] = $add1;
                                            }
                                        }

                                    }
                                    /* if(!empty($itemarr['item_addresses'])){
                                if($itemarr['item_product'] == $asset_aary['item_product']){
                                if(!empty($itemarr['item_addresses'])){
                                $add = explode('~',$itemarr['item_addresses']);
                                $asset_aary['addresses'] = [array('location'=>$add[1],'qty'=>$add[2],'address_id'=>$add[0])];
                                }

                                }
                                }*/

                                }
                            }
                            $asset_inputdata['vendor_approval'] = json_encode($gst);
                            $asset_aary['item_estimated_cost'] = $item_amt['rate'];
                            $asset_inputdata['asset_details'] = json_encode($asset_aary); //json_encode( $asset, true );
                            $asset_inputdata['created_by'] = DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")');

                            EnPrPoAssetDetails::create($asset_inputdata);
                            DB::table('en_ci_quotation_comparison')
                                ->where(['pr_po_id' => DB::raw('UUID_TO_BIN("' . $asset['pr_po_id'] . '")'), 'selected_item_id' => DB::raw('UUID_TO_BIN("' . $asset_aary['item_product'] . '")')])
                                ->update(['vendor_approve' => DB::raw('JSON_INSERT(vendor_approve, "$.converted_as_po", "yes")')]);
                            // $asset_inputdata[] = $asset_inputdata; // for demo

                        }

                        //$purchaseAssetResponse = EnPrPoAssetDetails::insert($asset_inputdata);
                        $data['data']['insert_id'] = $result_id_text;
                        $data['message']['success'] = showmessage('104', array('{name}'), array(trans('label.lbl_purchase_order')));
                        $data['status'] = 'success';

                        $hist_details = $this->gethistorydesc('created', trans('label.lbl_purchase_order'));
                        //Add into Purchase History
                        $this->prpohistoryadd(array('pr_po_id' => $result_id_text, 'history_type' => $pr_po_type, 'action' => 'created', 'details' => $hist_details, 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")')));
                        //Add into UserActivityLog
                        userlog(array('record_id' => $result_id_text, 'data' => $request->all(), 'action' => 'added', 'message' => showmessage('104', array('{name}'), array($result_message))));

                        user_notification(array('type' => 'cpo', 'message' => 'convert to po', 'store_user' => DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")'), 'show_user' => '', 'action' => 'add'));

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
                            $add1 = [];
                            if (!empty($asset['item_addresses'])) {
                                foreach ($asset['item_addresses'] as $value) {

                                    $add = explode('~', $value);
                                    $add1[] = array('location' => $add[1], 'qty' => $add[2], 'address_id' => $add[0]);
                                    $asset['addresses'] = $add1;

                                }
                                unset($asset['item_addresses']);
                            }

                            /*if(!empty($asset['item_addresses'])){
                            $add = explode('~',$asset['item_addresses']);
                            $asset['addresses'] = [array('location'=>$add[1],'qty'=>$add[2],'address_id'=>$add[0])];
                            unset($asset['item_addresses']);
                            }*/

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
            $item_title = $request['item_title'];
            $skucode = $request['skucode'];
            $pr_po_id = $request['pr_po_id'];
            $purchasecost = $request['purchasecost'];
            $itemqty = $request['itemqty'];
            $actualqty = $request['actualqty'];
            $reqeustArray = array();
            $messagestr = array();
            DB::beginTransaction(); // begin transaction
            $count = 1;

            $request['item_category'] = $request['title'];
            // $data['data'] = $request->all();
            // $data['status'] = 'success';
            // $data['message']['success'] = 'success';
            // return $data;
            foreach ($request['receiveitems'] as $item) {
                $num = 1;
                $receiveitemsArr = explode("_", $item);
                $key = $receiveitemsArr[1];
                // Create Message Start
                for ($j = 0; $j < count($request['item_title']); $j++) {
                    if ($key == $j) {
                        $strings = $request['item_title'][$key] . " : " . $itemqty[$key] . " Item Received";
                        array_push($messagestr, $strings);
                    }
                }
                $count++;
                $itemCategory = "";
                $request['item_categorys'] = $request['item_category'][$key];
                $request['actualqty'] = $actualqty[$key];
                // $data['data'][] = $request['item_categorys'] . ' - ' . $key;
                // $request['itemqty']            = 0;
                $request['purchasecost'] = 0;
                $request['asset_prefix'] = "PO#" . strtoupper($title[$key]);
                $request['ci_templ_id'] = $receiveitemsArr[0];
                $request['ci_type_id'] = $ci_type_id[$key];
                $request['cutype'] = $cutype[$key];
                $request['asset_sku'] = $skucode[$key];
                $request['title'] = $item_title[$key];
                $totalAssetCount = $itemqty[$key];
                $request['totalAssetReceivedCount'] = $totalAssetCount;
                $request['purchasecost'] = $purchasecost[$key];
                $request['acquisitiondate'] = "";
                $request['expirydate'] = "";
                $request['warrantyexpirydate'] = "";
                $request['pr_po_id'] = $pr_po_id;
                // $StrPass = "itemqty- ".$request['itemqty'] . " purchasecost-" . $request['purchasecost'] . " asset_prefix-" . $request['asset_prefix'] . " ci_templ_id-" . $request['ci_templ_id'] . " ci_type_id-" . $request['ci_type_id'] . " cutype-" . $request['cutype'] . " asset_sku-" . $request['asset_sku'] . " title-" . $request['title'] . " totalAssetCount-" .$totalAssetCount . " purchasecost-" . $request['purchasecost'] . " acquisitiondate-" . $request['acquisitiondate'] . " expirydate-" . $request['expirydate'] . " warrantyexpirydate-" . $request['warrantyexpirydate'] . " pr_po_id-" .$request['pr_po_id'];

                if (true) {
                    if ($request["item_categorys"] == "Consumable") {
                        $result = app('App\Http\Controllers\asset\AssetController')->addConsumableAsset($request);
                        if ($result) {
                            DB::commit();
                            $data['data'] = null;
                            $data['status'] = 'success';
                            $data['message']['success'] = 'Update Successfully';

                        } else {
                            DB::rollBack();
                            $data['data'] = $request->all();
                            $data['message']['error'] = "Error While Upating assets";
                            $data['status'] = 'error';
                        }
                        if ($data['status'] == "success") {
                            $itemStatus = $resultPartially = DB::table('en_consumable_received')
                                ->select('id', DB::raw('BIN_TO_UUID(asset_id) AS asset_id'),
                                    DB::raw('BIN_TO_UUID(po_id) AS po_id'),
                                    'asset_sku', 'total_count', 'partially_received')
                                ->where('asset_sku', '=', $request['asset_sku'])
                                ->where('po_id', '=', DB::raw('UUID_TO_BIN("' . $request['pr_po_id'] . '")'))
                                ->get()->toArray();
                            $total_count = $itemStatus[0]->total_count;
                            $partially_received = $itemStatus[0]->partially_received;
                            if ($total_count == $partially_received) {
                                $flag_received[] = 1;
                            } else {
                                $flag_received[] = 0;
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
                            userlog(array('record_id' => $pr_po_id, 'data' => $request->all(), 'action' => 'item received', 'message' => showmessage('msg_item_received', array('{name}'), array(trans('label.lbl_purchase_order')))));
                        }
                    } else {
                        for ($i = 0; $i < $totalAssetCount; $i++) {
                            $result = app('App\Http\Controllers\asset\AssetController')->addasset($request);

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
                                        $ci_asset_detailsArr[$key]['item_product'] = $asset_arr['item_product'];
                                        $ci_asset_detailsArr[$key]['asset_sku'] = $skucode[$key];
                                        $ci_asset_detailsArr[$key]["item_qty"] = $asset_arr["item_qty"];

                                        //$ci_asset_detailsArr[$key]["received_item_qty"] = 0;

                                        $pr_po_id_bin = DB::raw('UUID_TO_BIN("' . $request->input('pr_po_id') . '")');
                                        $asset_sku = $skucode[$key];
                                        // $item_bin     = DB::raw('UUID_TO_BIN("' . $asset_arr['item'] . '")');

                                        $cnt = DB::table('en_assets')->where('po_id', $pr_po_id_bin)->where('asset_sku', $asset_sku)->count();
                                        // $cnt                                            = DB::table('en_assets')->where('po_id', $pr_po_id_bin)->where('ci_templ_id', $item_bin)->count();
                                        $ci_asset_detailsArr[$key]["received_item_qty"] = $cnt;
                                        if ($ci_asset_detailsArr[$key]["received_item_qty"] == $ci_asset_detailsArr[$key]["item_qty"]) {
                                            $flag_received[] = 1;
                                        } else {
                                            $flag_received[] = 0;
                                        }

                                    }
                                }
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

                            // $this->prpohistoryadd(array('pr_po_id' => $pr_po_id, 'history_type' => 'po', 'action' => 'item received', 'details' => $hist_details . "<br>" . trans('label.lbl_purchase_order') . " : " . $totalAssetCount, 'comment' => $request['title'] . '__' . $totalAssetCount, 'created_by' => DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")')));
                            //Add into UserActivityLog
                            userlog(array('record_id' => $pr_po_id, 'data' => $request->all(), 'action' => 'item received', 'message' => showmessage('msg_item_received', array('{name}'), array(trans('label.lbl_purchase_order')))));
                        }
                    }
                }
            }
            $hist_details = "";
            foreach ($messagestr as $hintmsg) {
                $hist_details = $hintmsg;
                $this->prpohistoryadd(array('pr_po_id' => $pr_po_id, 'history_type' => 'po', 'action' => 'item received', 'details' => $hist_details, 'comment' => $request['title'] . '__' . $totalAssetCount, 'created_by_name' => !empty($request->input('ENFULLNAME')) ? $request->input('ENFULLNAME') : "NA", 'created_by' => DB::raw('UUID_TO_BIN("' . $request->input('loggedinuserid') . '")')));
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
            $inv = DB::table('en_form_data_pr')->select(DB::raw("BIN_TO_UUID(assignpr_user_id) as assignpr_user_id"), DB::raw('count(pr_id) as total'))->whereIn('assignpr_user_id', $invoice_id_bin)->groupBy('assignpr_user_id')->get();

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

                $pr_requester_name = "e46978a4-2c16-11ec-9c4b-4a4901e9af12";

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

                $data['opp_id'] = $request['opp_no'];

                $res_data = $this->call_rest_api("https://115.124.96.115:4108/uat/get_opp_details_aim.php", $data);

                $company_data = json_decode($res_data);

                $company_name = $company_data->result->customer_details->customer_name;

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
    public function getprnumberbyvendorid(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'po_vendor_id' => 'required|allow_uuid|string|size:36',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        } else {
            $result = EnPrPoAssetDetails::getPrPoAssetDetails('', 'pr', $request->input('po_vendor_id'));

            $data['data'] = $result->isEmpty() ? null : $result;
            $data['message']['success'] = 'success';
            $data['status'] = 'success';
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

} // Class End
