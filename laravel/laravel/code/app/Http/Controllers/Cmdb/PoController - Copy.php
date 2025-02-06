<?php

namespace App\Http\Controllers\Cmdb;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Libraries\Maillib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use Illuminate\Http\Request;
use Redirect;
use Validator;
use View;

/**
 * PO Controller class is implemented to do Purchase Order operations.
 * @author Namrata Thakur
 * @package PurchaseOrder
 */
class PoController extends Controller
{
    /**
     * Contructor function to initiate the API service and Request data
     * @author Namrata Thakur
     * @access public
     * @package PurchaseOrder
     * @param \App\Services\ITAM\ItamService $itam
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function __construct(IamService $iam, ItamService $itam, Request $request)
    {
        $this->itam           = $itam;
        $this->iam            = $iam;
        $this->emlib          = new Emlib;
        $this->request        = $request;
        $this->request_params = $this->request->all();
    }
    /*===================PURCHASE REQUEST=======================*/
    /**
     * This Po controller function is implemented to initiate a page to get list
     *  of PR.
     * @author Namrata Thakur
     * @access public
     * @package Purchase
     * @return string
     */
    public function purchaserequests()
    {
        $topfilter = ['gridsearch' => true, 'jsfunction' => 'prList() , prDetailsLoad()'];

        $data['emgridtop']   = $this->emlib->emgridtop($topfilter);
        $data['pageTitle']   = trans('title.purchaserequest');
        $data['includeView'] = view("Cmdb/purchaserequests", $data);
        return view('template', $data);
    }
    /**
     * This controller function is implemented to get list of PR.
     * @author Namrata Thakur
     * @access public
     * @package po
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */
    public function purchaserequestlist()
    {
        try
        {
            $paging        = [];
            $limit         = _isset($this->request_params, 'limit', config('enconfig.def_limit'));
            $page          = _isset($this->request_params, 'page', config('enconfig.page'));
            $searchkeyword = _isset($this->request_params, 'searchkeyword');
            $msg           = "";
            $content       = "";
            $is_error      = false;

            $limit_offset = limitoffset($limit, $page);
            $page         = $limit_offset['page'];
            $limit        = $limit_offset['limit'];
            $offset       = $limit_offset['offset'];

            $form_params['limit']         = $paging['limit']         = $limit;
            $form_params['page']          = $paging['page']          = $page;
            $form_params['offset']        = $paging['offset']        = $offset;
            $form_params['searchkeyword'] = $searchkeyword;
            $options                      = ['form_params' => $form_params];

            $pos_resp = $this->itam->purchaserequests($options);

            $po_id = "";
            if ($pos_resp['is_error']) {
                $is_error = $pos_resp['is_error'];
                $msg      = $pos_resp['msg'];
            } else {
                $pos                      = _isset(_isset($pos_resp, 'content'), 'records');
                $paging['total_rows']     = _isset(_isset($pos_resp, 'content'), 'totalrecords');
                $paging['showpagination'] = true;
                $paging['jsfunction']     = 'prList()';
                $view                     = 'Cmdb/purchaserequestlist';
                $po_id                    = isset($pos[0]['pr_id']) ? $pos[0]['pr_id'] : "";

                if (is_array($pos) && count($pos) > 0) {
                    foreach ($pos as $key => $po) {
                        /*$form_paramsother['bv_id']       = $po['details']['bv_id'];
                        $form_paramsother['dc_id']       = $po['details']['dc_id'];
                        $form_paramsother['location_id'] = $po['details']['location_id'];
                        $options                         = ['form_params' => $form_paramsother];

                        $pos_other_resp                           = $this->iam->getdclocationbv($options);
                        $bv_dc_loc_detail                         = _isset($pos_other_resp, 'content');
                        $pos[$key]['details']['bv_dc_loc_detail'] = $bv_dc_loc_detail;*/
                    }
                }
                $content = $this->emlib->emgrid($pos, $view, [], $paging);

                $response["html"]     = $content;
                $response["is_error"] = $is_error;
                $response["msg"]      = $msg;
                $response['po_id']    = $po_id;
            }
        } catch (\Exception $e) {
            $response["html"]     = '';
            $response["is_error"] = true;
            $response["msg"]      = $e->getmessage();
            $response['po_id']    = '';
            save_errlog("purchaserequestlist", "This controller function is implemented to get list of PR.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {
            $response["html"]     = '';
            $response["is_error"] = true;
            $response["msg"]      = $e->getmessage();
            $response['po_id']    = '';
            save_errlog("purchaserequestlist", "This controller function is implemented to get list of PR.", $this->request_params, $e->getmessage());
        } finally {
            echo json_encode($response);
        }
    }
    /**
     * Function to return purchase request details
     * @author Darshan Chaure
     * @access public
     * @package PurchaseOrder
     * @param  string $first_pr_id
     * @return json
     */
    public function purchaserequestdetail()
    {
        try
        {
            $pr_po_id = _isset($this->request_params, 'first_pr_id');

            if ($pr_po_id != "") {
                $data['po_id']                 = '';
                $purchaserequestdetail         = [];
                $data['purchaserequestdetail'] = $purchaserequestdetail;
                //          $data['bv_id']                  = '';
                $form_params['pr_id']         = $pr_po_id;
                $form_params['limit']         = 0;
                $form_params['page']          = 0;
                $form_params['offset']        = 0;
                $form_params['searchkeyword'] = '';
                $options                      = ['form_params' => $form_params];
                $prs_resp                     = $this->itam->purchaserequests($options);

                $data['pr_first_detail'] = isset($prs_resp['content']['records'][0]) ? $prs_resp['content']['records'][0] : null;

                $pr_po_id          = isset($prs_resp['content']['records'][0]) ? $prs_resp['content']['records'][0]['pr_id'] : null;
                $assetoptions      = ['form_params' => ['pr_po_id' => $pr_po_id, 'asset_type' => 'pr']];
                $assetdetails_resp = $this->itam->prpoassetdetails($assetoptions);

                $data['assetdetails'] = isset($assetdetails_resp['content']) ? $assetdetails_resp['content'] : null;

                $historyoptions         = ['form_params' => ['pr_po_id' => $pr_po_id, 'history_type' => 'pr']];
                $prpohistorylog_resp    = $this->itam->prpohistorylog($historyoptions);
                $data['prpohistorylog'] = isset($prpohistorylog_resp['content']) ? $prpohistorylog_resp['content'] : null;
                $attachmentoptions      = ['form_params' => ['pr_po_id' => $pr_po_id, 'attachment_type' => 'pr']];
                $prpoattachment_resp    = $this->itam->prpoattachment($attachmentoptions);

                $data['prpoattachment']       = isset($prpoattachment_resp['content']) ? $prpoattachment_resp['content'] : null;
                $purchaserequestdata          = [];
                $form_params['template_name'] = 'purchase_request';
                $options                      = ['form_params' => $form_params];
                $purchaserequestdata          = $this->itam->getFormTemplateDefaulteConfigbyTemplateName($options);
                $data['form_templ_data']      = $purchaserequestdata;
                /* To get Approvers name fromm IAM */
                $approval_details_by_data = ['optional' => [], 'confirmed' => []];
                if (isset($data['pr_first_detail']['approval_details']['optional']) && !empty($data['pr_first_detail']['approval_details']['optional'])) {
                    foreach ($data['pr_first_detail']['approval_details']['optional'] as $user_id) {
                        apilog("++++++++++++++++");
                        apilog("++++++++++++++++");
                        apilog($user_id);
                        $options_optional  = ['form_params' => ['user_id' => $user_id]];
                        $response_optional = $this->iam->getAllUsersWithoputPermission($options_optional);
                        $response_data     = _isset(_isset($response_optional, 'content'), 'records');

                        if (!(is_array($response_data) && count($response_data) > 0)) {
                            $response_data    = [];
                            $response_data[0] = [];
                        }

                        $approval_details_by_data['optional'][] = $response_data[0];
                        apilog("++++++++++++++++");
                        apilog("++++++++++++++++");
                    }
                }
                if (!empty($data['prpohistorylog'])) {
                    foreach ($data['prpohistorylog'] as $key => $history) {
                        $options_history      = ['form_params' => ['user_id' => $history['created_by']]];
                        $response_historyuser = $this->iam->getAllUsersWithoputPermission($options_history);
                        $historyuser_data     = _isset(_isset($response_historyuser, 'content'), 'records');

                        if (!(is_array($historyuser_data) && count($historyuser_data) > 0)) {
                            $historyuser_data    = [];
                            $historyuser_data[0] = [];
                        }

                        $data['prpohistorylog'][$key]['created_by_name'] = $historyuser_data[0];
                    }
                }
                if (isset($data['pr_first_detail']['approval_details']['confirmed']) && !empty($data['pr_first_detail']['approval_details']['confirmed'])) {
                    foreach ($data['pr_first_detail']['approval_details']['confirmed'] as $user_id) {
                        $options_confirmed  = ['form_params' => ['user_id' => $user_id]];
                        $response_confirmed = $this->iam->getAllUsersWithoputPermission($options_confirmed);
                        $response_data      = _isset(_isset($response_confirmed, 'content'), 'records');

                        if (!(is_array($response_data) && count($response_data) > 0)) {
                            $response_data    = [];
                            $response_data[0] = [];
                        }

                        $approval_details_by_data['confirmed'][] = $response_data[0];
                    }
                }

                $data['pr_first_detail']['approval_details_by_data'] = $approval_details_by_data;
                $contents                                            = enview("Cmdb/purchaserequestdetail", $data);
                $response["html"]                                    = $contents;
                $response["is_error"]                                = $is_error                                = "";
                $response["msg"]                                     = $msg                                     = "";
            } else {
                $response["html"]     = '';
                $response["is_error"] = $is_error = "";
                $response["msg"]      = $msg      = "";
            }

        } catch (\Exception $e) {
            $response["html"]     = '';
            $response["is_error"] = true;
            $response["msg"]      = $e->getmessage();
            save_errlog("purchaserequestdetail", "This controller function is implemented to get detail of PR.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {
            $response["html"]     = '';
            $response["is_error"] = true;
            $response["msg"]      = $e->getmessage();
            save_errlog("purchaserequestdetail", "This controller function is implemented to get detail of PR.", $this->request_params, $e->getmessage());
        } finally {
            return json_encode($response);
        }
    }

    /**
     * Function to return add purchase request form
     * @author Darshan Chaure
     * @access public
     * @package PurchaseOrder
     * @param  string $first_pr_id
     * @return string
     */
    public function purchaserequestadd()
    {
        try {

            $inputdata = ['template_name' => 'purchaserequest'];
            $data      = $this->itam->getFormTemplateDefaulteConfigbyTemplateName(['form_params' => $inputdata]);

            if ($data['content']) {
                $data['form_templ_data'] = $data['content'][0];
            } else {
                $data['form_templ_data'] = [];
            }
            $data['pr_id']     = "";
            $option            = [];
            $ciDetails         = $this->itam->getcitemplates($option);
            $data['ciDetails'] = _isset(_isset($ciDetails, 'content'), 'records');
            //Get Approvers List
            $option['form_params']    = ['advusertype' => "staff"];
            $approversDetails         = $this->iam->getUsers($option);
            $data['approversDetails'] = _isset(_isset($approversDetails, 'content'), 'records');

            $data['formAction'] = "add";
            $html               = view("Cmdb/purchaserequestadd", $data);
        } catch (\Exception $e) {
            $html = $e->getmessage();
            save_errlog("purchaserequestadd", "This controller function is implemented to get add PR form.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {
            $html = $e->getmessage();
            save_errlog("purchaserequestadd", "This controller function is implemented to get add PR form.", $this->request_params, $e->getmessage());
        } finally {
            echo $html;
        }
    }

    /**
     * Function to return edit purchase request form
     * @author Darshan Chaure
     * @access public
     * @package PurchaseOrder
     * @param  string $first_pr_id
     * @return string
     */
    public function purchaserequestedit()
    {
        try
        {
            $inputdata = ['template_name' => 'purchaserequest'];
            $data      = $this->itam->getFormTemplateDefaulteConfigbyTemplateName(['form_params' => $inputdata]);
            if ($data['content']) {
                $data['form_templ_data'] = $data['content'][0];
            } else {
                $data['form_templ_data'] = [];
            }
            $option            = [];
            $ciDetails         = $this->itam->getcitemplates($option);
            $data['ciDetails'] = _isset(_isset($ciDetails, 'content'), 'records');

            //Get Approvers List
            $option['form_params']    = ['advusertype' => "staff"];
            $approversDetails         = $this->iam->getUsers($option);
            $data['approversDetails'] = _isset(_isset($approversDetails, 'content'), 'records');

            /* Fetch Edit Data */

            $pr_id                         = _isset($this->request_params, 'pr_id');
            $form_params['pr_id']          = $pr_id;
            $options                       = ['form_params' => $form_params];
            $prs_resp                      = $this->itam->purchaserequests($options);
            $purchaserequestdetail         = isset($prs_resp['content']['records'][0]) ? $prs_resp['content']['records'][0] : null;
            $data['purchaserequestdetail'] = $purchaserequestdetail;

            $historyoptions         = ['form_params' => ['pr_po_id' => $pr_id, 'history_type' => 'pr']];
            $prpohistorylog_resp    = $this->itam->prpohistorylog($historyoptions);
            $data['prpohistorylog'] = isset($prpohistorylog_resp['content']) ? $prpohistorylog_resp['content'] : null;

            $assetoptions         = ['form_params' => ['pr_po_id' => $pr_id, 'asset_type' => 'pr']];
            $assetdetails_resp    = $this->itam->prpoassetdetails($assetoptions);
            $data['assetdetails'] = isset($assetdetails_resp['content']) ? $assetdetails_resp['content'] : null;

            $data['formAction'] = "edit";
            $data['pr_id']      = $pr_id;
            $html               = view("Cmdb/purchaserequestadd", $data);
        } catch (\Exception $e) {
            $html = $e->getmessage();
            save_errlog("purchaserequestedit", "This controller function is implemented to get edit PR form.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {
            $html = $e->getmessage();
            save_errlog("purchaserequestedit", "This controller function is implemented to get edit PR form.", $this->request_params, $e->getmessage());
        } finally {
            echo $html;
        }
    }

    /**
     * Function to return vendor,BV,cost center and location data in html option tag format
     * @author Darshan Chaure
     * @access public
     * @package PurchaseOrder
     * @return json
     */
    public function getPurchaseRenderFormData()
    {
        $option                = [];
        $vendorsDetails        = $this->itam->getvendors($option);
        $vendorsDetailsArr     = _isset(_isset($vendorsDetails, 'content'), 'records');
        $vendorsDetailsOptions = "<option value=''>[" . trans('label.lbl_selectvendor') . "]</option>";
        if ($vendorsDetailsArr) {
            foreach ($vendorsDetailsArr as $vendor) {
                $vendorsDetailsOptions .= "<option value='" . $vendor['vendor_id'] . "'>" . $vendor['vendor_name'] . "</option>";
            }
        }

        $option                   = [];
        $costcenterDetails        = $this->itam->getcostcenters($option);
        $costcenterDetailsArr     = _isset(_isset($costcenterDetails, 'content'), 'records');
        $costcenterDetailsOptions = "<option value=''>[" . trans('label.lbl_selectcostcenter') . "]</option>";
        if ($costcenterDetailsArr) {
            foreach ($costcenterDetailsArr as $cc) {
                $costcenterDetailsOptions .= "<option value='" . $cc['cc_id'] . "'>" . $cc['cc_code'] . "-" . $cc['cc_name'] . "</option>";
            }
        }

        //============= Ship To Master
        $option               = [];
        $shiptoDetails        = $this->itam->getshiptos($option);
        $shiptoDetailsArr     = _isset(_isset($shiptoDetails, 'content'), 'records');
        $shiptoDetailsOptions = "<option value=''>[" . trans('label.lbl_selectshipto') . "]</option>";
        if ($shiptoDetailsArr) {
            foreach ($shiptoDetailsArr as $shipto) {
                $shiptoDetailsOptions .= "<option value='" . $shipto['shipto_id'] . "'>" . $shipto['address'] . "</option>";
            }
        }

        //============= Bill To Master
        $option               = [];
        $billtoDetails        = $this->itam->getbilltos($option);
        $billtoDetailsArr     = _isset(_isset($billtoDetails, 'content'), 'records');
        $billtoDetailsOptions = "<option value=''>[" . trans('label.lbl_selectbillto') . "]</option>";
        if ($billtoDetailsArr) {
            foreach ($billtoDetailsArr as $billto) {
                $billtoDetailsOptions .= "<option value='" . $billto['billto_id'] . "'>" . $billto['address'] . "</option>";
            }
        }

        //============= Ship To Contact Master
        $option                      = [];
        $shiptoContactDetails        = $this->itam->getcontacts_shipto($option);
        $shiptoContactDetailsArr     = _isset(_isset($shiptoContactDetails, 'content'), 'records');
        $shiptoContactDetailsOptions = "<option value=''>[" . trans('label.lbl_selectshiptoContact') . "]</option>";
        if ($shiptoContactDetailsArr) {
            foreach ($shiptoContactDetailsArr as $shiptoContact) {
                $contact_name = $shiptoContact['prefix'] . '. ' . $shiptoContact['fname'] . ' ' . $shiptoContact['lname'];
                $shiptoContactDetailsOptions .= "<option value='" . $shiptoContact['contact_id'] . "'>" . $contact_name . "</option>";
            }
        }

        //============= Bill To Contact Master
        $option                      = [];
        $billtoContactDetails        = $this->itam->getcontacts_billto($option);
        $billtoContactDetailsArr     = _isset(_isset($billtoContactDetails, 'content'), 'records');
        $billtoContactDetailsOptions = "<option value=''>[" . trans('label.lbl_selectbilltoContact') . "]</option>";
        if ($billtoContactDetailsArr) {
            foreach ($billtoContactDetailsArr as $billtoContact) {
                $contact_name = $billtoContact['prefix'] . '. ' . $billtoContact['fname'] . ' ' . $billtoContact['lname'];
                $billtoContactDetailsOptions .= "<option value='" . $billtoContact['contact_id'] . "'>" . $contact_name . "</option>";
            }
        }

        //============= Delivery Master
        $option                 = [];
        $deliveryDetails        = $this->itam->getdelivery($option);
        $deliveryDetailsArr     = _isset(_isset($deliveryDetails, 'content'), 'records');
        $deliveryDetailsOptions = "<option value=''>[" . trans('label.lbl_selectdelivery') . "]</option>";
        if ($deliveryDetailsArr) {
            foreach ($deliveryDetailsArr as $delivery) {
                $deliveryDetailsOptions .= "<option value='" . $delivery['delivery_id'] . "'>" . $delivery['delivery'] . "</option>";
            }
        }

        //============= Payment Terms Master
        $option                     = [];
        $paymenttermsDetails        = $this->itam->getpaymentterms($option);
        $paymenttermsDetailsArr     = _isset(_isset($paymenttermsDetails, 'content'), 'records');
        $paymenttermsDetailsOptions = "<option value=''>[" . trans('label.lbl_selectpaymentterms') . "]</option>";
        if ($paymenttermsDetailsArr) {
            foreach ($paymenttermsDetailsArr as $paymentterms) {
                $paymenttermsDetailsOptions .= "<option value='" . $paymentterms['paymentterm_id'] . "'>" . $paymentterms['payment_term'] . "</option>";
            }
        }

        //============= Locations
        $options                = ['form_params' => ['order_byregion' => true]];
        $locationDetails        = $this->iam->getLocations($options);
        $locationDetailsArr     = _isset(_isset($locationDetails, 'content'), 'records');
        $locationDetailsOptions = "<option value=''>[" . trans('label.lbl_selectlocation') . "]</option>";
        if ($locationDetailsArr) {
            $region_name = '';
            foreach ($locationDetailsArr as $lo) {
                if ($region_name != $lo['region_name']) {
                    if ($region_name != '') {
                        $locationDetailsOptions .= '</optgroup>';
                    }
                    $locationDetailsOptions .= '<optgroup label="' . ucfirst($lo['region_name']) . '">';
                }
                $locationDetailsOptions .= '<option value="' . $lo['location_id'] . '">' . htmlspecialchars($lo['location_name']) . '</option>';
                $region_name = $lo['region_name'];
            }
            if ($region_name != '') {
                $locationDetailsOptions .= '</optgroup>';
            }
        }
        //============= Business Vertical

        $options                        = ['form_params' => ['order_bybu' => true]];
        $businessVerticalDetails        = $this->iam->getBusinessVertical($options);
        $businessVerticalDetailsArr     = _isset(_isset($businessVerticalDetails, 'content'), 'records');
        $businessVerticalDetailsOptions = "<option value=''>[" . trans('label.lbl_selectbv') . "]</option>";
        if ($businessVerticalDetailsArr) {
            $bu_name = '';
            foreach ($businessVerticalDetailsArr as $bv) {
                if ($bu_name != $bv['bu_name']) {
                    if ($bu_name != '') {
                        $businessVerticalDetailsOptions .= '</optgroup>';
                    }
                    $businessVerticalDetailsOptions .= '<optgroup label="' . ucfirst($bv['bu_name']) . '">';
                }
                $businessVerticalDetailsOptions .= "<option value='" . $bv['bv_id'] . "'>" . $bv['bv_name'] . "</option>";
                $bu_name = $bv['bu_name'];
            }
            if ($bu_name != '') {
                $businessVerticalDetailsOptions .= '</optgroup>';
            }
        }
        $options = [
            'form_params' => ['order_byregion' => true],
        ];
        $datacenterDetails        = $this->iam->getDatacenters($options);
        $datacenterDetailsArr     = _isset(_isset($datacenterDetails, 'content'), 'records');
        $datacenterDetailsOptions = "<option value=''>[" . trans('label.lbl_selectdatacenter') . "]</option>";
        if ($businessVerticalDetailsArr) {
            $region_name = '';
            foreach ($datacenterDetailsArr as $dc) {
                if ($region_name != $dc['regions_name']) {
                    if ($region_name != '') {
                        $datacenterDetailsOptions .= '</optgroup>';
                    }
                    $datacenterDetailsOptions .= '<optgroup label="' . ucfirst($dc['regions_name']) . '">';
                }
                $datacenterDetailsOptions .= "<option value='" . $dc['dc_id'] . "'>" . $dc['dc_name'] . "</option>";
                $region_name = $dc['regions_name'];
            }
            if ($region_name != '') {
                $datacenterDetailsOptions .= '</optgroup>';
            }
        }

//$pr_special_termsDetails = '1. On the receipt of this Purchase Order, the Supplier needs to provide an Acceptance in writing indicating the Delivery timelines. 2. If not accepted within 5 Days then PO should be considered as cancelled. 3. Delivery is a critical issue and no delay should be foreseen and the terms should be followed strictly. 4. The product supplied by the supplier will be strictly as per the technical specifications mentioned in the quotation document and email discussion. 5. If any deviation is foreseen technically, the equipment will be subject to immediate rejection. 6. If any loss or damage to the materials from when received then recovery from the Supplier will be done.';

$pr_special_termsDetails = 'asdf';
        $data['vendorsDetailsOptions']          = $vendorsDetailsOptions;
        $data['costcenterDetailsOptions']       = $costcenterDetailsOptions;
        $data['shiptoDetailsOptions']           = $shiptoDetailsOptions;
        $data['shiptoContactDetailsOptions']    = $shiptoContactDetailsOptions;
        $data['billtoDetailsOptions']           = $billtoDetailsOptions;
        $data['billtoContactDetailsOptions']    = $billtoContactDetailsOptions;
        $data['deliveryDetailsOptions']         = $deliveryDetailsOptions;
        $data['paymenttermsDetailsOptions']     = $paymenttermsDetailsOptions;
        $data['locationDetailsOptions']         = $locationDetailsOptions;
        $data['businessVerticalDetailsOptions'] = $businessVerticalDetailsOptions;
        $data['datacenterDetailsOptions']       = $datacenterDetailsOptions;
        $data['pr_special_termsDetails']        = $pr_special_termsDetails;

        return json_encode($data);
    }

    /**
     * Function to save new or update existing purchase request
     * @author Darshan Chaure
     * @access public
     * @package PurchaseOrder
     * @param  string bv_id
     * @param  string pr_title
     * @param  string location_id
     * @param  string pr_req_date
     * @param  string dc_id
     * @param  string pr_due_date
     * @param  string pr_vendor
     * @param  string pr_priority
     * @param  string pr_description
     * @param  string pr_cost_center
     * @param  string shipping_address
     * @param  string billing_address
     * @param  string formAction
     * @param  string form_templ_id
     * @param  string pr_id
     * @param  string item
     * @param  string item_desc
     * @param  string item_qty
     * @param  string item_estimated_cost
     * @param  string approval_req
     * @return json
     */
    public function purchaserequestsave(Request $request)
    {
        try {
            $inputdata                                        = $request->all();
            $postData['asset_details']['item']                = _isset($inputdata, 'item', []);
            $postData['asset_details']['item_desc']           = _isset($inputdata, 'item_desc', []);
            $postData['asset_details']['warranty_support_required']           = _isset($inputdata, 'warranty_support_required', []);
            $postData['asset_details']['item_qty']            = _isset($inputdata, 'item_qty', []);
            
            //$postData['asset_details']['item_estimated_cost'] = _isset($inputdata, 'item_estimated_cost', array());
            $postData["approval_req"]                         = _isset($inputdata, 'approval_req', "n");
            $postData["form_templ_id"]                        = _isset($inputdata, 'form_templ_id', "");
            $postData["urlpath"]                              = _isset($inputdata, 'urlpath', "purchaserequest");
            $postData["form_templ_type"]                      = _isset($inputdata, 'form_templ_type', "default");
            $postData["requester_id"]                         = _isset($inputdata, 'requester_id', "");
            
            /*$postData["bv_id"]                                = _isset($inputdata, 'bv_id', "");
            $postData["dc_id"]                                = _isset($inputdata, 'dc_id', "");
            $postData["location_id"]                          = _isset($inputdata, 'location_id', "");*/
            
            /* For PO Without PR */
            //$postData["po_name"] = _isset($inputdata, 'po_name', "");
            //$postData["po_no"]   = _isset($inputdata, 'po_no', "");

            if ($postData["approval_req"] == "n") {
                $postData["status"] = 'approved';
            } else {
                $postData["status"] = _isset($inputdata, 'status', 'pending approval');
            }
            /*$otherDetails = array(
                "discount_per"    => _isset($inputdata, 'discount_per', ""),
                "discount_amount" => _isset($inputdata, 'discount_amount', ""),
            );*/
            //$postData['other_details'] = json_encode($otherDetails);

            $postData["approved_status"] = []; // On Edit - "approved_status" == NULL Means Open For reapproval all approvers and "status" change to "pending approval " on lumen side.

            $approval_details['confirmed'] = _isset($inputdata, 'approvers', []);
            $approval_details['optional']  = _isset($inputdata, 'approvers_optional', []);

            $postData['approval_details'] = json_encode($approval_details);
            unset($request['item']);
            unset($request['item_desc']);
            unset($request['item_qty']);
            unset($request['warranty_support_required']);
            //unset($request['item_estimated_cost']);
            unset($request['approval_req']);
            unset($request['approvers']);
            //unset( $request['bv_id']);
            // unset( $request['dc_id']);
            //unset( $request['location_id']);
            unset($request['status']);
            unset($request['action']);
            unset($request['form_templ_id']);

            $postData["pr_id"]         = _isset($inputdata, 'pr_id', "");
            $postData['formAction']    = _isset($inputdata, 'formAction', "");
            $postData["details"]       = json_encode($request->all());
            $postData["asset_details"] = json_encode($postData['asset_details']);
            $postData["pr_po_type"]    = _isset($inputdata, 'pr_po_type', "pr");
            //$postData["pr_no"]    = generateponumber();

            //print_r( $postData);
            $data = $this->itam->purchaserequestsave(['form_params' => $postData]);
        } catch (\Exception $e) {
            $data["content"]   = "";
            $data["is_error"]  = "";
            $data["msg"]       = $e->getmessage();
            $data["http_code"] = "";

            save_errlog("purchaserequestsave", "This controller function is implemented to save PR.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {

            $data["content"]   = "";
            $data["is_error"]  = "";
            $data["msg"]       = $e->getmessage();
            $data["http_code"] = "";

            save_errlog("purchaserequestsave", "This controller function is implemented to save PR.", $this->request_params, $e->getmessage());
        } finally {
            echo json_encode($data, true);
        }
    }

    /**
     * Function to approve or reject PR PO
     * @author Darshan Chaure
     * @access public
     * @package PurchaseOrder
     * @param  string user_id
     * @param  string approval_status
     * @param  string pr_po_id
     * @param  string comment
     * @param  string pr_po_type
     * @param  string confirmed_optional
     * @return json
     */
    public function prpoapprovereject(Request $request)
    {
        try
        {
            $inputdata                      = $request->all();
            $postData["user_id"]            = _isset($inputdata, 'user_id', "");
            $postData["approval_status"]    = _isset($inputdata, 'approval_status', "");
            $postData["pr_po_id"]           = _isset($inputdata, 'pr_po_id', "");
            $postData["comment"]            = _isset($inputdata, 'comment', "");
            $postData["pr_po_type"]         = _isset($inputdata, 'pr_po_type', "");
            $postData["confirmed_optional"] = _isset($inputdata, 'confirmed_optional', "");

            $data = $this->itam->prpoapprovereject(['form_params' => $postData]);
        } catch (\Exception $e) {
            $data["content"]   = "";
            $data["is_error"]  = "";
            $data["msg"]       = $e->getmessage();
            $data["http_code"] = "";

            save_errlog("prpoapprovereject", "This controller function is implemented to PR approve or reject.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {

            $data["content"]   = "";
            $data["is_error"]  = "";
            $data["msg"]       = $e->getmessage();
            $data["http_code"] = "";

            save_errlog("prpoapprovereject", "This controller function is implemented to PR approve or reject.", $this->request_params, $e->getmessage());
        } finally {
            echo json_encode($data, true);
        }
    }

    /**
     * Function to perform various actions on PR PO
     * @author Darshan Chaure
     * @access public
     * @package PurchaseOrder
     * @param  string user_id
     * @param  string pr_po_id
     * @param  string pr_po_type
     * @param  string action
     * @param  string comment
     * @return json
     */
    public function prpoformActions(Request $request)
    {
        try
        {
            $inputdata              = $request->all();
            $postData["user_id"]    = _isset($inputdata, 'user_id', "");
            $postData["pr_po_id"]   = _isset($inputdata, 'pr_po_id', "");
            $postData["pr_po_type"] = _isset($inputdata, 'pr_po_type', "");
            $postData["action"]     = _isset($inputdata, 'action', "");
            $postData["comment"]    = _isset($inputdata, 'comment', "");

            /* For Item Received */
            if ($postData["action"] == "received") {
                /*$postData['cutype'] = "default";
                $postData['ci_templ_id'] = "";
                $postData['ci_type_id'] = "";
                $postData["location_id"] = _isset($inputdata,'location_id', "");
                $postData["dc_id"] = _isset($inputdata,'dc_id', "");
                $postData["bv_id"] = _isset($inputdata,'bv_id', "");
                $postData["title"] = _isset($inputdata,'bv_id', "");
                print_r($postData);*/

                $data = $this->itam->poreceiveditem(['form_params' => $inputdata]);
            } else {
                /* For Notify */
                $postData["mail_notification_to"]      = _isset($inputdata, 'mail_notification_to', "");
                $postData["mail_notification_subject"] = _isset($inputdata, 'mail_notification_subject', "");
                $postData["mail_notification"]         = _isset($inputdata, 'mail_notification', "");
                $postData["notify_to_id"]              = _isset($inputdata, 'notify_to_id', "");

                /* For Add Invoice */
                $postData["invoice_id"]       = _isset($inputdata, 'invoice_id', "");
                $postData["formaction"]       = _isset($inputdata, 'formaction', "");
                $postData["id"]               = _isset($inputdata, 'id', "");
                $postData["received_date"]    = _isset($inputdata, 'received_date', "");
                $postData["payment_due_date"] = _isset($inputdata, 'payment_due_date', "");

                $data = $this->itam->prpoformActions(['form_params' => $postData]);

                if (isset($data["is_error"]) && $data["is_error"] == false) {
                    $phpmailer    = new Maillib();
                    $to_emails    = $postData['mail_notification_to'];
                    $subject      = $postData['mail_notification_subject'];
                    $email_body   = $postData['comment'];
                    $mailresponse = $phpmailer->mailsent($to_emails, $subject, $email_body);
                }
            }
        } catch (\Exception $e) {
            $data["content"]   = "";
            $data["is_error"]  = "";
            $data["msg"]       = $e->getmessage();
            $data["http_code"] = "";

            save_errlog("prpoformActions", "This controller function is implemented to PR form actions.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {

            $data["content"]   = "";
            $data["is_error"]  = "";
            $data["msg"]       = $e->getmessage();
            $data["http_code"] = "";

            save_errlog("prpoformActions", "This controller function is implemented to PR form actions.", $this->request_params, $e->getmessage());
        } finally {
            echo json_encode($data, true);
        }
    }
    /*=================== PURCHASE ORDER ======================*/
    /**
     * This Po controller function is implemented to initiate a page to get list of po.
     * @author Namrata Thakur
     * @access public
     * @package PurchaseOrder
     * @return string
     */
    public function purchaseorders($id = '')
    {
        // $po_id      = _isset($this->request_params, 'po_id');
        $po_id = $id;
        if ($id == "") {
            $topfilter           = ['gridsearch' => true, 'jsfunction' => 'poList() , poDetailsLoad()'];
            $data['show_single'] = "false";
        } else {
            $topfilter           = ['gridsearch' => false, 'jsfunction' => 'poList() , poDetailsLoad()'];
            $data['show_single'] = "true";
        }
        $data['po_id']       = $po_id;
        $data['emgridtop']   = $this->emlib->emgridtop($topfilter);
        $data['pageTitle']   = trans('title.purchaseorder');
        $data['includeView'] = view("Cmdb/purchaseorders", $data);
        return view('template', $data);
    }
    /**
     * This controller function is implemented to get list of PO.
     * @author Namrata Thakur
     * @access public
     * @package po
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */
    public function purchaseorderlist()
    {
        try
        {
            $paging        = [];
            $limit         = _isset($this->request_params, 'limit', config('enconfig.def_limit_short'));
            $page          = _isset($this->request_params, 'page', config('enconfig.page'));
            $searchkeyword = _isset($this->request_params, 'searchkeyword');
            $po_id         = _isset($this->request_params, 'active_po_id');
            $show_single   = _isset($this->request_params, 'show_single');

            $is_error     = false;
            $msg          = "";
            $content      = "";
            $limit_offset = limitoffset($limit, $page);
            $page         = $limit_offset['page'];
            $limit        = $limit_offset['limit'];
            $offset       = $limit_offset['offset'];

            $form_params['limit']         = $paging['limit']         = $limit;
            $form_params['page']          = $paging['page']          = $page;
            $form_params['offset']        = $paging['offset']        = $offset;
            $form_params['searchkeyword'] = $searchkeyword;
            $form_params['po_id']         = $po_id;

            $options  = ['form_params' => $form_params];
            $pos_resp = $this->itam->purchaseorder($options);

            if ($pos_resp['is_error']) {
                $is_error = $pos_resp['is_error'];
                $msg      = $pos_resp['msg'];
            } else {
                $pos = _isset(_isset($pos_resp, 'content'), 'records');

                if ($pos) {
                    foreach ($pos as $key => $po) {
                        $form_paramsother['bv_id']                = $po['details']['bv_id'];
                        $form_paramsother['dc_id']                = $po['details']['dc_id'];
                        $form_paramsother['location_id']          = $po['details']['location_id'];
                        $options                                  = ['form_params' => $form_paramsother];
                        $pos_other_resp                           = $this->iam->getdclocationbv($options);
                        $bv_dc_loc_detail                         = _isset($pos_other_resp, 'content');
                        $pos[$key]['details']['bv_dc_loc_detail'] = $bv_dc_loc_detail;
                    }
                }

                $paging['total_rows'] = _isset(_isset($pos_resp, 'content'), 'totalrecords');
                if ($show_single == "true") {
                    $paging['showpagination'] = false;
                } else {
                    $paging['showpagination'] = true;
                }
                $paging['jsfunction']   = 'poList()';
                $view                   = 'Cmdb/purchaseorderlist';
                $po_id                  = isset($pos[0]['po_id']) ? $pos[0]['po_id'] : "";
                $pos_arr['pos']         = $pos;
                $pos_arr['show_single'] = $show_single;
                $content                = $this->emlib->emgrid($pos_arr, $view, [], $paging);
            }

            $response["html"]     = $content;
            $response["is_error"] = $is_error;
            $response["msg"]      = $msg;
            $response['po_id']    = $po_id;
        } catch (\Exception $e) {
            $response["html"]     = '';
            $response["is_error"] = true;
            $response["msg"]      = $e->getmessage();
            $response['po_id']    = '';
            save_errlog("purchaseorderlist", "This controller function is implemented to get list of PO.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {
            $response["html"]     = '';
            $response["is_error"] = true;
            $response["msg"]      = $e->getmessage();
            $response['po_id']    = '';
            save_errlog("purchaserequestlist", "This controller function is implemented to get list of PO.", $this->request_params, $e->getmessage());
        } finally {
            echo json_encode($response);
        }
    }

    /**
     * Function to return PO details
     * @author Darshan Chaure
     * @access public
     * @package PurchaseOrder
     * @param  string first_po_id
     * @return json
     */
    public function purchaseorderdetail()
    {
        try
        {
            $pr_po_id = _isset($this->request_params, 'first_po_id');
            if ($pr_po_id != "") {
                $data['po_id']                 = '';
                $purchaserequestdetail         = [];
                $data['purchaserequestdetail'] = $purchaserequestdetail;
                //$data['bv_id'] = '';
                $form_params['po_id']         = $pr_po_id;
                $form_params['limit']         = 0;
                $form_params['page']          = 0;
                $form_params['offset']        = 0;
                $form_params['searchkeyword'] = '';

                $options                 = ['form_params' => $form_params];
                $prs_resp                = $this->itam->purchaseorder($options);
                $data['pr_first_detail'] = isset($prs_resp['content']['records'][0]) ? $prs_resp['content']['records'][0] : null;

                $pr_po_id = isset($prs_resp['content']['records'][0]) ? $prs_resp['content']['records'][0]['po_id'] : null;

                $assetoptions = [
                    'form_params' => ['pr_po_id' => $pr_po_id, 'asset_type' => 'po']];
                $assetdetails_resp = $this->itam->prpoassetdetails($assetoptions);

                $data['assetdetails'] = isset($assetdetails_resp['content']) ? $assetdetails_resp['content'] : null;

                $receivedassetoptions = [
                    'form_params' => ['pr_po_id' => $pr_po_id, 'asset_type' => 'po']];
                $receivedassetdetails_resp = $this->itam->prpoassetdetails($receivedassetoptions);

                $data['receivedassetdetails'] = isset($receivedassetdetails_resp['content']) ? $receivedassetdetails_resp['content'] : null;

                $historyoptions = [
                    'form_params' => ['pr_po_id' => $pr_po_id, 'history_type' => 'po']];
                $prpohistorylog_resp    = $this->itam->prpohistorylog($historyoptions);
                $data['prpohistorylog'] = isset($prpohistorylog_resp['content']) ? $prpohistorylog_resp['content'] : null;

                $invoiceoptions = [
                    'form_params' => ['po_id' => $pr_po_id]];
                $purchaseinvoices_resp    = $this->itam->purchaseinvoices($invoiceoptions);
                $data['purchaseinvoices'] = isset($purchaseinvoices_resp['content']) ? $purchaseinvoices_resp['content'] : null;

                $attachmentoptions = [
                    'form_params' => ['pr_po_id' => $pr_po_id, 'attachment_type' => 'po']];
                $prpoattachment_resp    = $this->itam->prpoattachment($attachmentoptions);
                $data['prpoattachment'] = isset($prpoattachment_resp['content']) ? $prpoattachment_resp['content'] : null;

                $purchaserequestdata          = [];
                $form_params['template_name'] = 'purchase_request';
                $options                      = [
                    'form_params' => $form_params,
                ];
                $purchaserequestdata     = $this->itam->getFormTemplateDefaulteConfigbyTemplateName($options);
                $data['form_templ_data'] = $purchaserequestdata;
                /* To get Approvers name fromm IAM */
                $approval_details_by_data = ['optional' => [], 'confirmed' => []];
                if (isset($data['pr_first_detail']['approval_details']['optional']) && !empty($data['pr_first_detail']['approval_details']['optional'])) {
                    foreach ($data['pr_first_detail']['approval_details']['optional'] as $user_id) {
                        $options_optional = [
                            'form_params' => ['user_id' => $user_id],
                        ];
                        $response_optional = $this->iam->getAllUsersWithoputPermission($options_optional);
                        $response_data     = _isset(_isset($response_optional, 'content'), 'records');

                        if (!(is_array($response_data) && count($response_data) > 0)) {
                            $response_data    = [];
                            $response_data[0] = [];
                        }

                        $approval_details_by_data['optional'][] = $response_data[0];
                    }
                }
                if (!empty($data['prpohistorylog'])) {
                    foreach ($data['prpohistorylog'] as $key => $history) {
                        $options_history = [
                            'form_params' => ['user_id' => $history['created_by']],
                        ];
                        $response_historyuser = $this->iam->getAllUsersWithoputPermission($options_history);
                        $historyuser_data     = _isset(_isset($response_historyuser, 'content'), 'records');

                        if (!(is_array($historyuser_data) && count($historyuser_data) > 0)) {
                            $historyuser_data    = [];
                            $historyuser_data[0] = [];
                        }

                        $data['prpohistorylog'][$key]['created_by_name'] = $historyuser_data[0];
                    }
                }
                if (isset($data['pr_first_detail']['approval_details']['confirmed']) && !empty($data['pr_first_detail']['approval_details']['confirmed'])) {
                    foreach ($data['pr_first_detail']['approval_details']['confirmed'] as $user_id) {
                        $options_confirmed = [
                            'form_params' => ['user_id' => $user_id],
                        ];
                        $response_confirmed = $this->iam->getAllUsersWithoputPermission($options_confirmed);
                        $response_data      = _isset(_isset($response_confirmed, 'content'), 'records');

                        if (!(is_array($response_data) && count($response_data) > 0)) {
                            $response_data    = [];
                            $response_data[0] = [];
                        }

                        $approval_details_by_data['confirmed'][] = $response_data[0];
                    }
                }
                // print_r($data);
                $data['pr_first_detail']['approval_details_by_data'] = $approval_details_by_data;

                $contents             = enview("Cmdb/purchaseorderdetail", $data);
                $response["html"]     = $contents;
                $response["is_error"] = $is_error = "";
                $response["msg"]      = $msg      = "";
            } else {
                $response["html"]     = "";
                $response["is_error"] = $is_error = "";
                $response["msg"]      = $msg      = "";
            }
        } catch (\Exception $e) {
            $response["html"]     = '';
            $response["is_error"] = true;
            $response["msg"]      = $e->getmessage();
            $response['po_id']    = '';
            save_errlog("purchaseorderdetail", "This controller function is implemented to show PO details.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {
            $response["html"]     = '';
            $response["is_error"] = true;
            $response["msg"]      = $e->getmessage();
            $response['po_id']    = '';
            save_errlog("purchaseorderdetail", "This controller function is implemented to show PO details.", $this->request_params, $e->getmessage());
        } finally {
            return json_encode($response);
        }
    }

    /**
     * Function to return PO invoice form
     * @author Darshan Chaure
     * @access public
     * @package PurchaseOrder
     * @param  string po_id
     * @return json
     */
    public function purchaseorderinvoice()
    {
        $po_id                       = _isset($this->request_params, 'po_id');
        $data['po_id']               = '';
        $purchaseorderdetail         = [];
        $data['purchaseorderdetail'] = $purchaseorderdetail;
        $contents                    = enview("Cmdb/purchaseorderdetailinvoice", $data);
        $response["html"]            = $contents;
        $response["is_error"]        = $is_error        = "";
        $response["msg"]             = $msg             = "";
        return json_encode($response);
    }

    /**
     * Function to return add PO form
     * @author Darshan Chaure
     * @access public
     * @package PurchaseOrder
     * @param  string pr_id
     * @param  string po_id
     * @return json
     */
    public function purchaseorderadd()
    {
        try
        {
            $pr_id     = _isset($this->request_params, 'pr_id', '');
            $po_id     = _isset($this->request_params, 'po_id', '');
           // $inputdata = array('template_name' => 'purchaserequest');
            $inputdata = ['template_name' => 'purchaseorder'];
            $data      = $this->itam->getFormTemplateDefaulteConfigbyTemplateName(['form_params' => $inputdata]);
            if ($data['content']) {
                $data['form_templ_data'] = $data['content'][0];
            } else {
                $data['form_templ_data'] = [];
            }
            $data['pr_id']     = $pr_id;
            $data['po_id']     = $po_id;
            $option            = [];
            $ciDetails         = $this->itam->getcitemplates($option);
            $data['ciDetails'] = _isset(_isset($ciDetails, 'content'), 'records');
            //Get Approvers List
            $option['form_params']    = ['advusertype' => "staff"];
            $approversDetails         = $this->iam->getUsers($option);
            $data['approversDetails'] = _isset(_isset($approversDetails, 'content'), 'records');
            /* Fetch Edit Data  Of PR*/
            //$pr_id = _isset($this->request_params, 'pr_id');
            $form_params['pr_id'] = $pr_id;
            $form_params['po_id'] = $po_id;
            if ($pr_id != "") {
                $options                       = ['form_params' => $form_params];
                $prs_resp                      = $this->itam->purchaserequests($options);
                $purchaserequestdetail         = isset($prs_resp['content']['records'][0]) ? $prs_resp['content']['records'][0] : null;
                $data['purchaserequestdetail'] = $purchaserequestdetail;
            } else {
                $data['purchaserequestdetail'] = [];
            }
            $historyoptions = [
                'form_params' => ['pr_po_id' => $pr_id, 'history_type' => 'pr']];
            $prpohistorylog_resp    = $this->itam->prpohistorylog($historyoptions);
            $data['prpohistorylog'] = isset($prpohistorylog_resp['content']) ? $prpohistorylog_resp['content'] : null;

            $assetoptions = [
                'form_params' => ['pr_po_id' => $pr_id, 'asset_type' => 'pr']];
            $assetdetails_resp = $this->itam->prpoassetdetails($assetoptions);

            $data['assetdetails'] = isset($assetdetails_resp['content']) ? $assetdetails_resp['content'] : null;

            // print_r($data);
            $data['formAction'] = "add";
            $html               = view("Cmdb/purchaseorderadd", $data);
            echo $html;
        } catch (\Exception $e) {
            save_errlog("purchaseorderadd", "This controller function is implemented to render add PO form.", $this->request_params, $e->getmessage());
            echo $e->getmessage();
        } catch (\Error $e) {
            save_errlog("purchaseorderadd", "This controller function is implemented to render add PO form.", $this->request_params, $e->getmessage());
            echo $e->getmessage();
        }
    }

    /**
     * Function to upload attachment files
     * @author Darshan Chaure
     * @access public
     * @package PurchaseOrder
     * @param  string pr_po_id
     * @param  string type
     * @param  string attachment_type
     * @return json
     */
    public function upload(Request $request)
    {

        $showuserid = showuserid();
        //$showuserfullname=showuserfullname();
        //print_r($_FILES);exit;

        $input_data = $request->all();
        if ($input_data['attachment_type'] == "pr") {
            $redirect_url = '/purchaserequest';
        } else {
            $redirect_url = '/purchaseorders';
        }
        $messages = [
            'file.mimes' => showmessage('000', ['{name}'], [trans('label.lbl_attachmentid')], true),
        ];
        $validator = Validator::make($input_data, [
            'file'   => 'required',
            'file.*' => 'required|mimes:jpeg,png,pdf,doc,docx,csv,xlsx,xls|max:4096',
        ], $messages
        );
        if ($validator->fails()) {
            $error = $validator->errors();
            return Redirect::back()->withErrors($validator);
        }

        if (isset($_FILES['file'])) {
            foreach ($_FILES['file']['tmp_name'] as $key => $tmp_name) {
                //get file extension
                $file_ext = 'jpeg';
                $name1    = $_FILES["file"]["name"];
                $arr      = explode('.', $name1[$key]);
                if (count($arr) > 1) {
                    $file_ext = $arr[1];
                }

                $files_content                  = base64_encode(file_get_contents($_FILES['file']['tmp_name'][$key]));
                $form_params['user_id']         = $showuserid;
                $form_params['pr_po_id']        = _isset($this->request_params, 'pr_po_id');
                $form_params['type']            = _isset($this->request_params, 'type');
                $form_params['attachment_type'] = _isset($this->request_params, 'attachment_type');
//              $form_params['showuserfullname']=  $showuserfullname ;
                $form_params['file_ext'][$key]  = $file_ext;
                $form_params['file'][$key]      = $files_content;
                $form_params['file_name'][$key] = $_FILES['file']['name'][$key];
                $form_params['size'][$key]      = $_FILES['file']['size'][$key];
                $options                        = ['form_params' => $form_params];
                if ($form_params['attachment_type'] == "pr") {
                    $redirect_url = '/purchaserequest';
                } else {
                    $redirect_url = '/purchaseorders';
                }

            }

            if ($input_data['attachment_type'] == "pr") {
                $data = $this->itam->fileupload_pr($options);
            } else {
                $data = $this->itam->fileupload_po($options);
            }

        }
        if ($data['is_error']) {
            return Redirect::to($redirect_url)
                ->withErrors([
                    'notupload' => showerrormsg($data['msg']),
                ]);
        } else {
            return Redirect::to($redirect_url)
                ->with('upload_success', showerrormsg($data['msg']));
        }
        //echo json_encode($data, true);
        /*  $image = $request->file('file');
    $imageName = $image->getClientOriginalName();
    $image->move(public_path('uploads'),$imageName);

    $imageUpload = new ImageUpload();
    $imageUpload->filename = $imageName;
    $imageUpload->save();
    $data = array("success" => $imageName);
    // return response()->json(['success'=>$imageName]);
    echo json_encode($data,true);*/
    }

    /**
     * Function to delete attachment
     * @author Darshan Chaure
     * @access public
     * @package PurchaseOrder
     * @param  string pr_po_id
     * @param  string attach_id
     * @param  string attachment_type
     * @return json
     */
    public function deleteattachment(Request $request)
    {
        $inputdata                   = $request->all();
        $postData["attach_id"]       = _isset($inputdata, 'attach_id', "");
        $postData["pr_po_id"]        = _isset($inputdata, 'pr_po_id', "");
        $postData["attachment_type"] = _isset($inputdata, 'attachment_type', "");
        $data                        = $this->itam->deleteattachment(['form_params' => $postData]);
        echo json_encode($data, true);
    }

    /**
     * Function to save new or update existing PO
     * @author Darshan Chaure
     * @access public
     * @package PurchaseOrder
     * @param  string pr_po_id
     * @param  string po_no
     * @param  string po_name
     * @param  string bv_id
     * @param  string pr_title
     * @param  string location_id
     * @param  string pr_req_date
     * @param  string dc_id
     * @param  string pr_due_date
     * @param  string pr_vendor
     * @param  string pr_priority
     * @param  string pr_description
     * @param  string pr_cost_center
     * @param  string shipping_address
     * @param  string billing_address
     * @param  string formAction
     * @param  string pr_po_type
     * @param  string form_templ_id
     * @param  string pr_id
     * @param  string po_id
     * @param  string item
     * @param  string item_desc
     * @param  string item_qty
     * @param  string item_estimated_cost
     * @param  string discount_per
     * @param  string discount_amount
     * @param  string approval_req
     * @return json
     */
    public function purchaseordersave(Request $request)
    {
        try
        {
            $inputdata                                        = $request->all();
            $postData['asset_details']['item']                = _isset($inputdata, 'item', []);
            $postData['asset_details']['item_desc']           = _isset($inputdata, 'item_desc', []);
            $postData['asset_details']['item_qty']            = _isset($inputdata, 'item_qty', []);
            $postData['asset_details']['item_estimated_cost'] = _isset($inputdata, 'item_estimated_cost', []);
            $postData["approval_req"]                         = _isset($inputdata, 'approval_req', "n");
            $postData["form_templ_id"]                        = _isset($inputdata, 'form_templ_id', "");
            $postData["urlpath"]                              = _isset($inputdata, 'urlpath', "purchaserequest");
            //$postData["form_templ_type"] = _isset($inputdata,'form_templ_type', "default");
            $postData["requester_id"] = _isset($inputdata, 'requester_id', "");
            $postData["bv_id"]        = _isset($inputdata, 'bv_id', "");
            $postData["dc_id"]        = _isset($inputdata, 'dc_id', "");
            $postData["location_id"]  = _isset($inputdata, 'location_id', "");
            $postData["po_name"]      = _isset($inputdata, 'po_name', "");
            $postData["po_no"]        = _isset($inputdata, 'po_no', "");
            $postData['formAction']   = _isset($inputdata, 'formAction', "");
            if ($postData['formAction'] == "add") {
                if ($postData["approval_req"] == "n") {
                    //$postData["status"] = 'open';
                    $postData["status"] = 'approved';
                } else {
                    $postData["status"] = _isset($inputdata, 'status', 'pending approval');
                }
            }

            $postData["approved_status"] = [];
            // On Edit - "approved_status" == NULL Means Open For reapproval all approvers and "status" change to "pending approval " on lumen side.

            $approval_details['confirmed'] = _isset($inputdata, 'approvers', []);
            $approval_details['optional']  = _isset($inputdata, 'approvers_optional', []);
            $postData['approval_details']  = json_encode($approval_details);
            unset($request['item']);
            unset($request['item_desc']);
            unset($request['item_qty']);
            unset($request['item_estimated_cost']);
            unset($request['approval_req']);
            unset($request['approvers']);
            //unset( $request['bv_id']);
            // unset( $request['dc_id']);
            //unset( $request['location_id']);
            unset($request['status']);
            unset($request['action']);
            unset($request['form_templ_id']);
            $postData["pr_id"]      = _isset($inputdata, 'pr_id', "");
            $postData["po_id"]      = _isset($inputdata, 'po_id', "");
            $postData['formAction'] = _isset($inputdata, 'formAction', "");
            $postData["details"]    = json_encode($request->all());

            $otherDetails = [
                "discount_per"    => _isset($inputdata, 'discount_per', ""),
                "discount_amount" => _isset($inputdata, 'discount_amount', ""),
            ];
            $postData['other_details'] = json_encode($otherDetails);
            $postData["asset_details"] = json_encode($postData['asset_details']);
            $postData["pr_po_type"]    = "po";

            $data = $this->itam->purchaseordersave(['form_params' => $postData]);
            echo json_encode($data, true);
        } catch (\Exception $e) {
            $response             = [];
            $response["html"]     = '';
            $response["is_error"] = true;
            $response["msg"]      = $e->getmessage();
            $response['po_id']    = '';
            save_errlog("purchaseordersave", "This controller function is implemented to save PO details.", $this->request_params, $e->getmessage());
            echo json_encode($response, true);
        } catch (\Error $e) {
            $response             = [];
            $response["html"]     = '';
            $response["is_error"] = true;
            $response["msg"]      = $e->getmessage();
            $response['po_id']    = '';
            save_errlog("purchaseordersave", "This controller function is implemented to save PO details.", $this->request_params, $e->getmessage());
            echo json_encode($response, true);
        }
    }

    /**
     * Function to return edit PO form
     * @author Darshan Chaure
     * @access public
     * @package PurchaseOrder
     * @param  string po_id
     * @param  string pr_id
     * @return json
     */
    public function purchaseorderedit()
    {
        try
        {
            $po_id                   = _isset($this->request_params, 'po_id', '');
            $pr_id                   = _isset($this->request_params, 'pr_id', '');
            //$inputdata               = array('template_name' => 'purchaserequest');
            $inputdata = ['template_name' => 'purchaseorder'];
            $data                    = $this->itam->getFormTemplateDefaulteConfigbyTemplateName(['form_params' => $inputdata]);
            $data['form_templ_data'] = $data['content'][0];
            if (isset($data['form_templ_data']['details'])) {
                $details_arr_org     = json_decode($data['form_templ_data']['details'], true);
                $details_fld_arr_org = _isset($details_arr_org, 'fields') ? $details_arr_org['fields'] : [];
                if (is_array($details_fld_arr_org) && count($details_fld_arr_org) > 0) {
                    foreach ($details_fld_arr_org as $key => $field) {
                        //echo "<pre> Label : "; print_r($field);  echo "</pre>";
                        if (_isset(_isset($field, 'config'), 'label') && _isset(_isset($field, 'attrs'), 'name')) {
                            //echo "<pre> Label : "; print_r($field['config']['label']);  //echo "</pre>";
                            //echo "<pre>Name: "; print_r($field['attrs']['name']);  echo "</pre>";
                            $details_fld_arr_org[$key]['config']['label'] = trans('settingtemplate.' . $field['attrs']['name']);
                        }
                        //echo "<pre> Label : "; print_r($field);  echo "</pre>";
                        //echo "<pre> Label : "; print_r("==========================");  echo "</pre>";
                    }
                    //echo "<pre> "; print_r($details_fld_arr_org);  echo "</pre>";

                    $details_arr_org['fields'] = $details_fld_arr_org;
                    $details_arr_lang          = json_encode($details_arr_org);
                }
            }
            $data['details_arr_lang'] = $details_arr_lang;

            if ($data['content']) {
                $data['form_templ_data'] = $data['content'][0];
            } else {
                $data['form_templ_data'] = [];
            }
            $data['po_id']     = $po_id;
            $data['pr_id']     = $pr_id;
            $option            = [];
            $ciDetails         = $this->itam->getcitemplates($option);
            $data['ciDetails'] = _isset(_isset($ciDetails, 'content'), 'records');
            //Get Approvers List
            $option['form_params']    = ['advusertype' => "staff"];
            $approversDetails         = $this->iam->getUsers($option);
            $data['approversDetails'] = _isset(_isset($approversDetails, 'content'), 'records');

            /* Fetch Edit Data  Of PR*/
            //$po_id = _isset($this->request_params, 'po_id');
            $form_params['po_id'] = $po_id;
            $options              = ['form_params' => $form_params];
            $prs_resp             = $this->itam->purchaseorder($options);

            $purchaserequestdetail         = isset($prs_resp['content']['records'][0]) ? $prs_resp['content']['records'][0] : null;
            $data['purchaserequestdetail'] = $purchaserequestdetail;

            $historyoptions = [
                'form_params' => ['pr_po_id' => $po_id, 'history_type' => 'po']];
            $prpohistorylog_resp    = $this->itam->prpohistorylog($historyoptions);
            $data['prpohistorylog'] = isset($prpohistorylog_resp['content']) ? $prpohistorylog_resp['content'] : null;

            $assetoptions = [
                'form_params' => ['pr_po_id' => $po_id, 'asset_type' => 'po']];
            $assetdetails_resp = $this->itam->prpoassetdetails($assetoptions);

            $data['assetdetails'] = isset($assetdetails_resp['content']) ? $assetdetails_resp['content'] : null;
            // print_r($data);
            $data['formAction'] = "edit";
            $html               = view("Cmdb/purchaseorderadd", $data);
            echo $html;
        } catch (\Exception $e) {
            save_errlog("purchaseorderadd", "This controller function is implemented to render add PO form.", $this->request_params, $e->getmessage());
            echo $e->getmessage();
        } catch (\Error $e) {
            save_errlog("purchaseorderadd", "This controller function is implemented to render add PO form.", $this->request_params, $e->getmessage());
            echo $e->getmessage();
        }
    }
    public function printpreview()
    {

        $data['pageTitle']   = trans('title.purchase');
        $data['includeView'] = view("Cmdb/purchaseorderdetailinvoice", $data);
        return view('template', $data);
    }

    /**
     * Function to return purchase invoice data
     * @author Darshan Chaure
     * @access public
     * @package PurchaseOrder
     * @param  string po_id
     * @param  string invoice_id
     * @return json
     */
    public function purchaseinvoices()
    {
        $po_id                 = _isset($this->request_params, 'po_id', '');
        $invoice_id            = _isset($this->request_params, 'invoice_id', '');
        $options['po_id']      = $po_id;
        $options['invoice_id'] = $invoice_id;
        $invoice_resp          = $this->itam->purchaseinvoices(['form_params' => $options]);
        $invoice_data          = isset($invoice_resp['content'][0]) ? $invoice_resp['content'][0] : null;
        return json_encode($invoice_data);
    }
    public function getnotifications()
    {
        /*$pr_po_id = _isset($this->request_params, 'pr_po_id', '');
        $history_type = _isset($this->request_params, 'history_type', '');
        $options['pr_po_id'] = $pr_po_id;
        $options['history_type'] = $history_type;*/
        $user_id            = showuserid();
        $options['user_id'] = $user_id;
        $notify_resp        = $this->itam->getnotifications(['form_params' => $options]);
        $notify_data        = isset($notify_resp['content'][0]) ? $notify_resp['content'] : null;
        $notify_dataArr     = [];
        $notify_data_result = "";

        if ($notify_data) {
            foreach ($notify_data as $notification) {
                if ($notification['history_type'] == "pr") {
                    $purchase_type = "Purchase Request";
                    $prpoList      = "prlist";
                } else {
                    $purchase_type = "Purchase Order";
                    $prpoList      = "polist";
                }

                $notify_data_result .= '<li data-id=' . $notification['pr_po_id'] . ' class=" ' . $prpoList . ' br-t of-h notificationmsg"> <a href="#" class="fw600 p12 animated animated-short fadeInDown">Your approval is required for the ' . $purchase_type . ' ##' . @$notification['title'] . '## <span class="mv15 floatright" style="color: #999;">on ' . date("d F Y : H:i A", strtotime($notification['created_at'])) . '</span></a> </li>';
            }
            return json_encode(["result" => $notify_data_result]);
        } else {
            return json_encode(["result" => "<li class='br-t of-h notificationmsg fw600 p12'>NO Notifications</li>"]);
        }
    }

    /**
     * Function to download attachment
     * @author Darshan Chaure
     * @access public
     * @package PurchaseOrder
     * @param  string attach_id
     * @param  string attach_path
     * @return json
     */
    public function downloadattachment_pr()
    {
        try {
            $attach_id    = _isset($this->request_params, 'attach_id');
            $attach_path  = _isset($this->request_params, 'attach_path');
            $msg          = "";
            $content      = "";
            $extention    = "txt";
            $is_error     = false;
            $file_created = false;
            $user_id      = showuserid();
            $download_dir = public_path() . '/download/temp';
            $download_fp  = public_path() . '/download/temp/tmp_' . $user_id;
            $user_down_fp = 'download/temp/tmp_' . $user_id;

            $form_params['attach_id']   = $attach_id;
            $form_params['attach_path'] = $attach_path;
            $options                    = ['form_params' => $form_params];

            $response = $this->itam->downloadattachment_pr($options);
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
            save_errlog("downloadattachment_pr", "This controller function is implemented to download attachment.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {
            $response["html"]     = '';
            $response["is_error"] = true;
            $response["msg"]      = $e->getmessage();
            save_errlog("downloadattachment_pr", "This controller function is implemented to download attachment.", $this->request_params, $e->getmessage());
        } finally {
            echo json_encode($response);
        }
    }

    /**
     * Function to delete PO invoice
     * @author Darshan Chaure
     * @access public
     * @package PurchaseOrder
     * @return json
     */
    public function poinvoicedelete(Request $request)
    {
        try {
            $response = $this->itam->poinvoicedelete(['form_params' => $request->all()]);
        } catch (\Exception $e) {
            $response["html"]     = '';
            $response["is_error"] = true;
            $response["msg"]      = $e->getmessage();
            save_errlog("poinvoicedelete", "This controller function is implemented to delete po invoice.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {
            $response["html"]     = '';
            $response["is_error"] = true;
            $response["msg"]      = $e->getmessage();
            save_errlog("poinvoicedelete", "This controller function is implemented to delete po invoice.", $this->request_params, $e->getmessage());
        } finally {
            echo json_encode($response);
        }
    }

    /**
     * Function to download attachment
     * @author Darshan Chaure
     * @access public
     * @package PurchaseOrder
     */
    public function downloadprattachment()
    {
        $this->downloadattachment_pr();
    }
    public function downloadpoattachment()
    {
        $this->downloadattachment_pr();
    }
}
