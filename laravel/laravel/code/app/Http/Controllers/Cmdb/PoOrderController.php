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
class PoOrderController extends Controller
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
        $this->itam = $itam;
        $this->iam = $iam;
        $this->emlib = new Emlib;
        $this->request = $request;
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
        $topfilter = array('gridsearch' => true, 'jsfunction' => 'prList() , prDetailsLoad()');

        $data['emgridtop'] = $this->emlib->emgridtop($topfilter);
        $data['pageTitle'] = trans('title.purchaserequest');
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
    public function purchaserequestlist(Request $request)
    {

        try
        {
            $paging = array();

            $limit = _isset($this->request_params, 'limit', config('enconfig.def_limit'));
            $page = _isset($this->request_params, 'page', config('enconfig.page'));
            $searchkeyword = _isset($this->request_params, 'searchkeyword');
            $issuperadmin = _isset($this->request_params, 'issuperadmin');
            $msg = "";
            $content = "";
            $is_error = false;

            $limit_offset = limitoffset($limit, $page);
            $page = $limit_offset['page'];
            $limit = $limit_offset['limit'];
            $offset = $limit_offset['offset'];

            $form_params['limit'] = $paging['limit'] = $limit;
            $form_params['page'] = $paging['page'] = $page;
            $form_params['offset'] = $paging['offset'] = $offset;
            $form_params['searchkeyword'] = $searchkeyword;

            $options = ['form_params' => array('user_id' => showuserid())];
            $pos_resp = $this->iam->getuserprofile($options);
            $pos = _isset($pos_resp, 'content');
            $department_id = $pos[0]['department_id'];
            $designation_id = $pos[0]['designation_id'];
            // store dept id: 29c1f8e4-1acf-11ec-b0ba-4e89be533080
            // print_r($pos);
            //exit;
            $options_history = ['form_params' => array()];

            /* $response_historyuser = $this->iam->getUsers($options_history);
            print_r($response_historyuser);
            exit;*/
            if (!$request->session()->has('issuperadmin')) {

                // purchase team dept id: fb1ff49a-201a-11ec-956c-4e89be533080
                if (!$issuperadmin && $department_id != '29c1f8e4-1acf-11ec-b0ba-4e89be533080' && $department_id != 'fb1ff49a-201a-11ec-956c-4e89be533080') {
                    $form_params['requester_id'] = showuserid();
                }
                // store dept id: 29c1f8e4-1acf-11ec-b0ba-4e89be533080
                if (!$issuperadmin && $department_id == '29c1f8e4-1acf-11ec-b0ba-4e89be533080') {
                    $form_params['dept_type'] = 'store';
                }

                // purchase team dept id: fb1ff49a-201a-11ec-956c-4e89be533080
                if (!$issuperadmin && $department_id == 'fb1ff49a-201a-11ec-956c-4e89be533080') {
                    $form_params['dept_type'] = 'purchase';
                    //Purchase Team Lead = 2b42af0e-0adc-11ec-b893-4e89be533080
                    $flag = false;
                    $arr = array('2b42af0e-0adc-11ec-b893-4e89be533080', '6b21d406-4cab-11ea-8db0-c281e8a6eb02');
                    if (!in_array($designation_id, $arr)) {
                        $form_params['flag'] = true;
                        $form_params['user_id'] = showuserid();
                        $form_params['designation_id'] = $designation_id;
                    } else {

                    }
                }

            }
            $options = ['form_params' => $form_params];
            /* print_r();

            exit;*/

            $pos_resp = $this->itam->purchaserequests($options);
            //print_r($pos_resp);
            //exit;
            $po_id = "";
            if ($pos_resp['is_error']) {
                $is_error = $pos_resp['is_error'];
                $msg = $pos_resp['msg'];
            } else {
                $pos = _isset(_isset($pos_resp, 'content'), 'records');
                $paging['total_rows'] = _isset(_isset($pos_resp, 'content'), 'totalrecords');
                $paging['showpagination'] = true;
                $paging['jsfunction'] = 'prList()';
                $view = 'Cmdb/purchaserequestlist';
                $po_id = isset($pos[0]['pr_id']) ? $pos[0]['pr_id'] : "";

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
                $content = $this->emlib->emgrid($pos, $view, array(), $paging);

                $response["html"] = $content;
                $response["is_error"] = $is_error;
                $response["msg"] = $msg;
                $response['po_id'] = $po_id;
            }
        } catch (\Exception $e) {
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            $response['po_id'] = '';
            save_errlog("purchaserequestlist", "This controller function is implemented to get list of PR.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            $response['po_id'] = '';
            save_errlog("purchaserequestlist", "This controller function is implemented to get list of PR.", $this->request_params, $e->getmessage());
        } finally {
            echo json_encode($response);
        }
    }
    /**
     * Function to assign pr to user
     * @author Rahul Badhe
     * @access public
     * @package PurchaseRequest
     * @return string
     */
    public function assignprtouser(Request $request)
    {

        $input_data = $request->all();

        $messages = [
            'pr_assign_user_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_assign_pr_to_user')), true),
            'pr_po_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_assign_pr_to_user')), true),
        ];
        $validator = Validator::make($input_data, ['pr_assign_user_id' => 'required', 'pr_po_id' => 'required'], $messages);
        if ($validator->fails()) {
            $error = $validator->errors();
            return Redirect::back()->withErrors($validator);
        }
        $request = $request->all();
        $request['user_id'] = showuserid();
        $options = ['form_params' => $request];
        $data = $this->itam->assignprtouser($options);

        if ($data['is_error']) {
            return Redirect::to('/purchaserequest')
                ->withErrors([
                    'notupload' => showerrormsg($data['msg']),
                ]);
        } else {
            return Redirect::to('/purchaserequest')
                ->with('upload_success', showerrormsg($data['msg']));
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
    public function purchaserequestdetail(Request $request)
    {

        try
        {
            $pr_po_id = _isset($this->request_params, 'first_pr_id');

            if ($pr_po_id != "") {
                $data['po_id'] = '';
                $purchaserequestdetail = array();
                $data['purchaserequestdetail'] = $purchaserequestdetail;
                //          $data['bv_id']                  = '';
                $form_params['pr_id'] = $pr_po_id;
                $form_params['limit'] = 0;
                $form_params['page'] = 0;
                $form_params['offset'] = 0;
                $form_params['searchkeyword'] = '';
                $options = ['form_params' => $form_params];
                $prs_resp = $this->itam->purchaserequests($options);

                // vendor list for shows in dropdown
                $options = array();
                // $options = ['form_params' => $form_params];
                $vendor_resp = $this->itam->getvendors($options);
                $vendors = _isset(_isset($vendor_resp, 'content'), 'records');
                $data['vendors'] = $vendors;

                $data['pr_first_detail'] = isset($prs_resp['content']['records'][0]) ? $prs_resp['content']['records'][0] : null;

                $pr_po_id = isset($prs_resp['content']['records'][0]) ? $prs_resp['content']['records'][0]['pr_id'] : null;
                $assetoptions = ['form_params' => array('pr_po_id' => $pr_po_id, 'asset_type' => 'pr')];
                $assetdetails_resp = $this->itam->prpoassetdetails($assetoptions);

                $data['assetdetails'] = isset($assetdetails_resp['content']) ? $assetdetails_resp['content'] : null;

                $historyoptions = ['form_params' => array('pr_po_id' => $pr_po_id, 'history_type' => 'pr')];
                $prpohistorylog_resp = $this->itam->prpohistorylog($historyoptions);
                $data['prpohistorylog'] = isset($prpohistorylog_resp['content']) ? $prpohistorylog_resp['content'] : null;

                $attachmentoptions = ['form_params' => array('pr_po_id' => $pr_po_id, 'attachment_type' => 'pr')];
                $prpoattachment_resp = $this->itam->prpoattachment($attachmentoptions);

                $data['prpoattachment'] = isset($prpoattachment_resp['content']) ? $prpoattachment_resp['content'] : null;

                $attachmentoptions1 = ['form_params' => array('pr_po_id' => $pr_po_id, 'attachment_type' => 'qu')];
                $prpoattachment_resp1 = $this->itam->prpoattachment($attachmentoptions1);

                $data['prpoattachment1'] = isset($prpoattachment_resp1['content']) ? $prpoattachment_resp1['content'] : null;

                $purchaserequestdata = array();
                $form_params['template_name'] = 'purchase_request';
                $options = ['form_params' => $form_params];
                $purchaserequestdata = $this->itam->getFormTemplateDefaulteConfigbyTemplateName($options);
                $data['form_templ_data'] = $purchaserequestdata;
                /* To get Approvers name fromm IAM */
                $approval_details_by_data = array('optional' => array(), 'confirmed' => array());
                if (isset($data['pr_first_detail']['approval_details']['optional']) && !empty($data['pr_first_detail']['approval_details']['optional'])) {
                    foreach ($data['pr_first_detail']['approval_details']['optional'] as $user_id) {
                        apilog("++++++++++++++++");
                        apilog("++++++++++++++++");
                        apilog($user_id);
                        $options_optional = ['form_params' => array('user_id' => $user_id)];
                        $response_optional = $this->iam->getAllUsersWithoputPermission($options_optional);

                        $response_data = _isset(_isset($response_optional, 'content'), 'records');
                        if (!(is_array($response_data) && count($response_data) > 0)) {
                            $response_data = array();
                            $response_data[0] = array();
                        }

                        $approval_details_by_data['optional'][] = $response_data[0];
                        apilog("++++++++++++++++");
                        apilog("++++++++++++++++");
                    }

                }
                //for get all users and his department but its not getting department
                $form_params = ['form_params' => array()];
                $allUsers = $this->iam->getAllUsersWithoputPermission($form_params);
                $data['allUsers'] = _isset(_isset($allUsers, 'content'), 'records');

                if (!empty($data['prpohistorylog'])) {
                    foreach ($data['prpohistorylog'] as $key => $history) {
                        $options_history = ['form_params' => array('user_id' => $history['created_by'])];
                        $response_historyuser = $this->iam->getAllUsersWithoputPermission($options_history);
                        $historyuser_data = _isset(_isset($response_historyuser, 'content'), 'records');

                        if (!(is_array($historyuser_data) && count($historyuser_data) > 0)) {
                            $historyuser_data = array();
                            $historyuser_data[0] = array();
                        }

                        $data['prpohistorylog'][$key]['created_by_name'] = $historyuser_data[0];
                    }
                }
                if (isset($data['pr_first_detail']['approval_details']['confirmed']) && !empty($data['pr_first_detail']['approval_details']['confirmed'])) {
                    foreach ($data['pr_first_detail']['approval_details']['confirmed'] as $user_id) {
                        $options_confirmed = ['form_params' => array('user_id' => $user_id)];
                        $response_confirmed = $this->iam->getAllUsersWithoputPermission($options_confirmed);
                        $response_data = _isset(_isset($response_confirmed, 'content'), 'records');

                        if (!(is_array($response_data) && count($response_data) > 0)) {
                            $response_data = array();
                            $response_data[0] = array();
                        }

                        $approval_details_by_data['confirmed'][] = $response_data[0];
                    }
                }

                $data['pr_first_detail']['approval_details_by_data'] = $approval_details_by_data;
                $contents = enview("Cmdb/purchaserequestdetail", $data);
                $response["html"] = $contents;
                $response["is_error"] = $is_error = "";
                $response["msg"] = $msg = "";
            } else {
                $response["html"] = '';
                $response["is_error"] = $is_error = "";
                $response["msg"] = $msg = "";
            }

        } catch (\Exception $e) {
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            save_errlog("purchaserequestdetail", "This controller function is implemented to get detail of PR.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            save_errlog("purchaserequestdetail", "This controller function is implemented to get detail of PR.", $this->request_params, $e->getmessage());
        } finally {
            return json_encode($response);
        }
    }
    public function getprnumberbyvendorid(Request $request)
    {

        try
        {
            $po_vendor_id = _isset($this->request_params, 'po_vendor_id');

            if ($po_vendor_id != "") {

                //          $data['bv_id']                  = '';
                $form_params['po_vendor_id'] = $po_vendor_id;
                $options = ['form_params' => $form_params];
                $prs_resp = $this->itam->getprnumberbyvendorid($options);
                $pr_nums = [];
                $html = '';
                if (!empty($prs_resp['content'])) {
                    foreach ($prs_resp['content'] as $value) {
                        $pr_nums[$value['pr_po_id']] = $value['pr_no'];
                    }

                    if (!empty($pr_nums)) {
                        $i = 1;
                        foreach ($pr_nums as $k => $v) {
                            $html .= '<div class="checkbox-custom checkbox-info mb5 ">
                <input  type="checkbox"  class="selectDeselectAll pr_ids" id="prnum-' . $i . '" name="pr_ids[]" value="' . $k . '">
                <label for="prnum-' . $i . '">' . $v . ' </label>
                </div>';
                            $i++;
                        }
                    }

                } else {
                    $html .= '<div class="text-danger">PR not found.</div>';
                }

                //$contents = enview("Cmdb/purchaserequestdetail", $data);
                $response["html"] = $html;
                $response["is_error"] = $is_error = "";
                $response["msg"] = $msg = "";
            } else {
                $response["html"] = '';
                $response["is_error"] = $is_error = "";
                $response["msg"] = $msg = "";
            }

        } catch (\Exception $e) {
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            save_errlog("purchaserequestdetail", "This controller function is implemented to get detail of PR.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
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

            $inputdata = array('template_name' => 'purchaserequest');
            $data = $this->itam->getFormTemplateDefaulteConfigbyTemplateName(array('form_params' => $inputdata));

            if ($data['content']) {
                $data['form_templ_data'] = $data['content'][0];
            } else {
                $data['form_templ_data'] = array();
            }
            $data['pr_id'] = "";
            $option = array();
            $ciDetails = $this->itam->getcitemplates($option);
            $data['ciDetails'] = _isset(_isset($ciDetails, 'content'), 'records');
            //Get Approvers List
            $option['form_params'] = array('advusertype' => "staff");
            $approversDetails = $this->iam->getUsers($option);
            $data['approversDetails'] = _isset(_isset($approversDetails, 'content'), 'records');

            $data['formAction'] = "add";

            $option_user = array('form_params' => array('user_id' => showuserid()));
            $userdata = $this->iam->getUsers($option_user);
            $user_id = _isset(_isset($userdata, 'content'), 'records');
            $department_name = $user_id[0]['department_name'];
            $data['pr_department'] = $department_name;

            $html = view("Cmdb/purchaserequestadd", $data);
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
     * Function to return add purchase request form
     * @author Rahul Badhe
     * @access public
     * @package converttopr
     * @param  string $first_pr_id
     * @return string
     */
    public function converttopr(Request $request)
    {

        try {
            $postData = $request->all();
            $data = $this->itam->converttopr(array('form_params' => $postData));
        } catch (\Exception $e) {
            $data["content"] = "";
            $data["is_error"] = "";
            $data["msg"] = $e->getmessage();
            $data["http_code"] = "";

            save_errlog("purchaserequestsave", "This controller function is implemented to save PR.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {

            $data["content"] = "";
            $data["is_error"] = "";
            $data["msg"] = $e->getmessage();
            $data["http_code"] = "";

            save_errlog("purchaserequestsave", "This controller function is implemented to save PR.", $this->request_params, $e->getmessage());
        } finally {
            echo json_encode($data, true);
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
            $inputdata = array('template_name' => 'purchaserequest');
            $data = $this->itam->getFormTemplateDefaulteConfigbyTemplateName(array('form_params' => $inputdata));
            if ($data['content']) {
                $data['form_templ_data'] = $data['content'][0];
            } else {
                $data['form_templ_data'] = array();
            }
            $option = array();
            $ciDetails = $this->itam->getcitemplates($option);
            $data['ciDetails'] = _isset(_isset($ciDetails, 'content'), 'records');

            //Get Approvers List
            $option['form_params'] = array('advusertype' => "staff");
            $approversDetails = $this->iam->getUsers($option);
            $data['approversDetails'] = _isset(_isset($approversDetails, 'content'), 'records');

            /* Fetch Edit Data */

            $pr_id = _isset($this->request_params, 'pr_id');
            $form_params['pr_id'] = $pr_id;
            $options = ['form_params' => $form_params];
            $prs_resp = $this->itam->purchaserequests($options);
            $purchaserequestdetail = isset($prs_resp['content']['records'][0]) ? $prs_resp['content']['records'][0] : null;
            $data['purchaserequestdetail'] = $purchaserequestdetail;

            $historyoptions = ['form_params' => array('pr_po_id' => $pr_id, 'history_type' => 'pr')];
            $prpohistorylog_resp = $this->itam->prpohistorylog($historyoptions);
            $data['prpohistorylog'] = isset($prpohistorylog_resp['content']) ? $prpohistorylog_resp['content'] : null;

            $assetoptions = ['form_params' => array('pr_po_id' => $pr_id, 'asset_type' => 'pr')];
            $assetdetails_resp = $this->itam->prpoassetdetails($assetoptions);
            $data['assetdetails'] = isset($assetdetails_resp['content']) ? $assetdetails_resp['content'] : null;

            $option_user = array('form_params' => array('user_id' => showuserid()));
            $userdata = $this->iam->getUsers($option_user);
            $user_id = _isset(_isset($userdata, 'content'), 'records');
            $department_name = $user_id[0]['department_name'];
            $data['pr_department'] = $department_name;

            $data['formAction'] = "edit";
            $data['pr_id'] = $pr_id;
            $html = view("Cmdb/purchaserequestadd", $data);
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
        $option = array();
        $vendorsDetails = $this->itam->getvendors($option);
        $vendorsDetailsArr = _isset(_isset($vendorsDetails, 'content'), 'records');
        $vendorsDetailsOptions = "<option value=''>[" . trans('label.lbl_selectvendor') . "]</option>";
        if ($vendorsDetailsArr) {
            foreach ($vendorsDetailsArr as $vendor) {
                $vendorsDetailsOptions .= "<option value='" . $vendor['vendor_id'] . "'>" . $vendor['vendor_name'] . "</option>";
            }
        }

        $option = array();
        $costcenterDetails = $this->itam->getcostcenters($option);
        $costcenterDetailsArr = _isset(_isset($costcenterDetails, 'content'), 'records');
        $costcenterDetailsOptions = "<option value=''>[" . trans('label.lbl_selectcostcenter') . "]</option>";
        if ($costcenterDetailsArr) {
            foreach ($costcenterDetailsArr as $cc) {
                $costcenterDetailsOptions .= "<option value='" . $cc['cc_id'] . "'>" . $cc['cc_code'] . "-" . $cc['cc_name'] . "</option>";
            }
        }

        //============= Ship To Master
        $option = array();
        $shiptoDetails = $this->itam->getshiptos($option);
        $shiptoDetailsArr = _isset(_isset($shiptoDetails, 'content'), 'records');
        $shiptoDetailsOptions = "<option value=''>[" . trans('label.lbl_selectshipto') . "]</option>";
        if ($shiptoDetailsArr) {
            foreach ($shiptoDetailsArr as $shipto) {
                $shiptoDetailsOptions .= "<option value='" . $shipto['shipto_id'] . "'>" . $shipto['address'] . "</option>";
            }
        }

        //============= Requester Names Master
        /*$option_user                 = array('form_params' => array('user_id' => showuserid()));
        $userdata                    = $this->iam->getUsers($option_user);
        $user_id                     = _isset(_isset($userdata, 'content'), 'records');
        $option                      = array('form_params' => array('department_id' => $user_id[0]['department_id']));
        $requesternameDetails        = $this->itam->getrequesternames($option);
        $requesternameDetailsArr     = _isset(_isset($requesternameDetails, 'content'), 'records');
        $requesternameDetailsOptions = "<option value=''>[" . trans('label.lbl_selectrequestername') . "]</option>";
        if ($requesternameDetailsArr) {
        foreach ($requesternameDetailsArr as $requestername) {
        $requester_name = $requestername['prefix'] . '. ' . $requestername['fname'] . ' ' . $requestername['lname'];
        $requesternameDetailsOptions .= "<option value='" . $requestername['requestername_id'] . "'>" . $requester_name . "</option>";
        }
        }*/

        //============= Bill To Master
        $option = array();
        $billtoDetails = $this->itam->getbilltos($option);
        $billtoDetailsArr = _isset(_isset($billtoDetails, 'content'), 'records');
        $billtoDetailsOptions = "<option value=''>[" . trans('label.lbl_selectbillto') . "]</option>";
        if ($billtoDetailsArr) {
            foreach ($billtoDetailsArr as $billto) {
                $billtoDetailsOptions .= "<option value='" . $billto['billto_id'] . "'>" . $billto['address'] . "</option>";
            }
        }

        //============= Ship To Contact Master
        $option = array();
        $shiptoContactDetails = $this->itam->getcontacts_shipto($option);
        $shiptoContactDetailsArr = _isset(_isset($shiptoContactDetails, 'content'), 'records');
        $shiptoContactDetailsOptions = "<option value=''>[" . trans('label.lbl_selectshiptoContact') . "]</option>";
        if ($shiptoContactDetailsArr) {
            foreach ($shiptoContactDetailsArr as $shiptoContact) {
                $contact_name = $shiptoContact['prefix'] . '. ' . $shiptoContact['fname'] . ' ' . $shiptoContact['lname'];
                $shiptoContactDetailsOptions .= "<option value='" . $shiptoContact['contact_id'] . "'>" . $contact_name . "</option>";
            }
        }

        //============= Bill To Contact Master
        $option = array();
        $billtoContactDetails = $this->itam->getcontacts_billto($option);
        $billtoContactDetailsArr = _isset(_isset($billtoContactDetails, 'content'), 'records');
        $billtoContactDetailsOptions = "<option value=''>[" . trans('label.lbl_selectbilltoContact') . "]</option>";
        if ($billtoContactDetailsArr) {
            foreach ($billtoContactDetailsArr as $billtoContact) {
                $contact_name = $billtoContact['prefix'] . '. ' . $billtoContact['fname'] . ' ' . $billtoContact['lname'];
                $billtoContactDetailsOptions .= "<option value='" . $billtoContact['contact_id'] . "'>" . $contact_name . "</option>";
            }
        }

        //============= Delivery Master
        $option = array();
        $deliveryDetails = $this->itam->getdelivery($option);
        $deliveryDetailsArr = _isset(_isset($deliveryDetails, 'content'), 'records');
        $deliveryDetailsOptions = "<option value=''>[" . trans('label.lbl_selectdelivery') . "]</option>";
        if ($deliveryDetailsArr) {
            foreach ($deliveryDetailsArr as $delivery) {
                $deliveryDetailsOptions .= "<option value='" . $delivery['delivery_id'] . "'>" . $delivery['delivery'] . "</option>";
            }
        }

        //============= Payment Terms Master
        $option = array();
        $paymenttermsDetails = $this->itam->getpaymentterms($option);
        $paymenttermsDetailsArr = _isset(_isset($paymenttermsDetails, 'content'), 'records');
        $paymenttermsDetailsOptions = "<option value=''>[" . trans('label.lbl_selectpaymentterms') . "]</option>";
        if ($paymenttermsDetailsArr) {
            foreach ($paymenttermsDetailsArr as $paymentterms) {
                $paymenttermsDetailsOptions .= "<option value='" . $paymentterms['paymentterm_id'] . "'>" . $paymentterms['payment_term'] . "</option>";
            }
        }
        /*
        //============= Locations
        $options                = ['form_params' => array('order_byregion' => true)];
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

        $options                        = ['form_params' => array('order_bybu' => true)];
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

        //============= Datacenter
        $options = [
        'form_params' => array('order_byregion' => true),
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
        }*/

//$pr_special_termsDetails = '1. On the receipt of this Purchase Order, the Supplier needs to provide an Acceptance in writing indicating the Delivery timelines. 2. If not accepted within 5 Days then PO should be considered as cancelled. 3. Delivery is a critical issue and no delay should be foreseen and the terms should be followed strictly. 4. The product supplied by the supplier will be strictly as per the technical specifications mentioned in the quotation document and email discussion. 5. If any deviation is foreseen technically, the equipment will be subject to immediate rejection. 6. If any loss or damage to the materials from when received then recovery from the Supplier will be done.';

        $pr_special_termsDetails = 'asdf';
        $data['costcenterDetailsOptions'] = $costcenterDetailsOptions;
        $data['locationDetailsOptions'] = $locationDetailsOptions;
        $data['businessVerticalDetailsOptions'] = $businessVerticalDetailsOptions;
        $data['datacenterDetailsOptions'] = $datacenterDetailsOptions;
        $data['requesternameDetailsOptions'] = $requesternameDetailsOptions;

        $data['vendorsDetailsOptions'] = $vendorsDetailsOptions;
        $data['shiptoDetailsOptions'] = $shiptoDetailsOptions;
        $data['shiptoContactDetailsOptions'] = $shiptoContactDetailsOptions;
        $data['billtoDetailsOptions'] = $billtoDetailsOptions;
        $data['billtoContactDetailsOptions'] = $billtoContactDetailsOptions;
        $data['deliveryDetailsOptions'] = $deliveryDetailsOptions;
        $data['paymenttermsDetailsOptions'] = $paymenttermsDetailsOptions;
        $data['pr_special_termsDetails'] = $pr_special_termsDetails;

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
    public function purchaserequestsave_org(Request $request)
    {
        try {
            $inputdata = $request->all();
            //echo "inputdata ";
            //echo '<pre>'; print_r($inputdata); echo '</pre>';
            $postData['asset_details']['item'] = _isset($inputdata, 'item', array());
            $postData['asset_details']['item_desc'] = _isset($inputdata, 'item_desc', array());
            $postData['asset_details']['warranty_support_required'] = _isset($inputdata, 'warranty_support_required', array());
            $postData['asset_details']['item_qty'] = _isset($inputdata, 'item_qty', array());
            $postData["approval_req"] = _isset($inputdata, 'approval_req', "n");
            $postData["form_templ_id"] = _isset($inputdata, 'form_templ_id', "");
            $postData["urlpath"] = _isset($inputdata, 'urlpath', "purchaserequest");
            $postData["form_templ_type"] = _isset($inputdata, 'form_templ_type', "default");
            $postData["requester_id"] = _isset($inputdata, 'requester_id', "");

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

            $postData["approved_status"] = array(); // On Edit - "approved_status" == NULL Means Open For reapproval all approvers and "status" change to "pending approval " on lumen side.

            $approval_details['confirmed'] = _isset($inputdata, 'approvers', array());
            $approval_details['optional'] = _isset($inputdata, 'approvers_optional', array());

            $postData['approval_details'] = json_encode($approval_details);
            unset($request['approval_req']);
            unset($request['status']);
            unset($request['action']);
            unset($request['form_templ_id']);
            unset($request['item']);
            unset($request['item_desc']);
            unset($request['item_qty']);
            unset($request['warranty_support_required']);
            unset($request['approvers']);
            /*unset($request['item_estimated_cost']);
            unset( $request['bv_id']);
            unset( $request['dc_id']);
            unset( $request['location_id']);*/

            $postData["pr_id"] = _isset($inputdata, 'pr_id', "");
            $postData['formAction'] = _isset($inputdata, 'formAction', "");
            $postData["details"] = json_encode($request->all());
            $postData["asset_details"] = json_encode($postData['asset_details']);
            $postData["pr_po_type"] = _isset($inputdata, 'pr_po_type', "pr");
            $postData["pr_no"] = generateprnumber();

            //echo "POST DATA";
            //echo '<pre>'; print_r($postData); echo '</pre>';
            // exit;

            $data = $this->itam->purchaserequestsave(array('form_params' => $postData));

        } catch (\Exception $e) {
            $data["content"] = "";
            $data["is_error"] = "";
            $data["msg"] = $e->getmessage();
            $data["http_code"] = "";

            save_errlog("purchaserequestsave", "This controller function is implemented to save PR.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {

            $data["content"] = "";
            $data["is_error"] = "";
            $data["msg"] = $e->getmessage();
            $data["http_code"] = "";

            save_errlog("purchaserequestsave", "This controller function is implemented to save PR.", $this->request_params, $e->getmessage());
        } finally {
            echo json_encode($data, true);
        }
    }
    public function convertprsave(Request $request)
    {
        try {
            $inputdata = $request->all();
            //echo "inputdata ";
            /*    print_r($inputdata);
            exit;*/

            /* $postData['asset_details']['item']                      = _isset($inputdata, 'item', array());
            $postData['asset_details']['item_desc']                 = _isset($inputdata, 'item_desc', array());
            $postData['asset_details']['warranty_support_required'] = _isset($inputdata, 'warranty_support_required', array());
            $postData['asset_details']['item_qty']                  = _isset($inputdata, 'item_qty', array());
            $postData['asset_details']['pr_id']                  = _isset($inputdata, 'pr_id', array());
            $postData['asset_details']['selected_items']                  = _isset($inputdata, 'selected_items', array());*/

            $postData["approval_req"] = _isset($inputdata, 'approval_req', "y");
            $postData["form_templ_id"] = _isset($inputdata, 'form_templ_id', "");
            $postData["urlpath"] = _isset($inputdata, 'urlpath', "purchaserequest");
            $postData["form_templ_type"] = _isset($inputdata, 'form_templ_type', "default");
            $postData["requester_id"] = showuserid();

            $postData['approval_details'] = json_encode(array("optional" => array(), 'confirmed' => showuserid()));
            $postData['approved_status'] = json_encode(array("optional" => array(), 'confirmed' => array(showuserid() => 'approved'), 'convert_to_pr' => array('approved' => showuserid())));
            /* For PO Without PR */
            //$postData["po_name"] = _isset($inputdata, 'po_name', "");
            //$postData["po_no"]   = _isset($inputdata, 'po_no', "");
            $request = $request->all();

            if (!empty($inputdata['selected_items'])) {
                $i = 0;
                foreach ($inputdata['selected_items'] as $value) {

                    $postData['asset_details']['item'][] = $inputdata['item'][$value];
                    $postData['asset_details']['item_desc'][] = $inputdata['item_desc'][$value];
                    $postData['asset_details']['warranty_support_required'][] = $inputdata['warranty_support_required'][$value];
                    $postData['asset_details']['item_qty'][] = $inputdata['item_qty'][$value];
                    $user_meta = array_map(function ($a) {
                        $t = explode('~', $a);
                        $ar['address_id'] = $t[0];
                        $ar['location'] = $t[1];
                        $ar['qty'] = $t[2];
                        return $ar;
                    }, $inputdata['addresses'][$value]);
                    //$addresses_json = explode('~',$inputdata['addresses'][$value][$i]);

                    $postData['asset_details']['addresses'][] = $user_meta;

                    $postData['asset_details']['pr_id'][] = $inputdata['pr_id'][$value][$inputdata['item'][$value]];
                    $arr['pr_id'][] = array_keys($inputdata['pr_id'][$value][$inputdata['item'][$value]]);
                    $arr['item_id'][] = $inputdata['pr_id'][$value];
                    $i++;

                }
                $request = array_merge($request, $arr);

                $postData["asset_details"] = json_encode($postData['asset_details']);

            } else {
                $inputdata['selected_items'] = '';
                $arr['pr_id'][] = '';
                $request = array_merge($request, $arr);
                $postData["asset_details"] = '';
            }

            if ($postData["approval_req"] == "n") {
                $postData["status"] = 'approved';
            } else {
                $postData["status"] = _isset($inputdata, 'status', 'pending approval');
            }
            $pr_ids = array();
            if (!empty($arr['pr_id'])) {

                foreach ($arr['pr_id'] as $value) {
                    if (is_array($value)) {
                        foreach ($value as $k => $v) {
                            $pr_ids[] = $v;
                        }
                    } else {
                        $pr_ids[] = $value;
                    }

                    /*if (strpos($value, ',') !== false) {
                $b      = explode(',', $value);
                $pr_ids = array_merge($b, $pr_ids);

                } else {
                $pr_ids[] = $value;
                }*/

                }
            }
            $postData['pr_ids'] = $pr_ids;
            $postData['item_id'] = $arr['item_id'];
            /*print_r($postData);
            exit;*/

            /*$otherDetails = array(
            "discount_per"    => _isset($inputdata, 'discount_per', ""),
            "discount_amount" => _isset($inputdata, 'discount_amount', ""),
            );*/
            //$postData['other_details'] = json_encode($otherDetails);

            // $postData["approved_status"]   = array(); // On Edit - "approved_status" == NULL Means Open For reapproval all approvers and "status" change to "pending approval " on lumen side.
            $inputdata['approvers'] = array(showuserid());
            $approval_details['confirmed'] = _isset($inputdata, 'approvers', array());
            $approval_details['optional'] = _isset($inputdata, 'approvers_optional', array());

            $postData['approval_details'] = json_encode($approval_details);
            unset($request['approval_req']);
            unset($request['status']);
            unset($request['action']);
            unset($request['form_templ_id']);
            unset($request['item']);
            unset($request['item_desc']);
            unset($request['item_qty']);
            unset($request['warranty_support_required']);
            unset($request['addresses']);
            // unset($request['selected_items']);
            unset($request['approvers']);
            /*unset($request['item_estimated_cost']);
            unset( $request['bv_id']);
            unset( $request['dc_id']);
            unset( $request['location_id']);*/

            // $postData["pr_id"]         = _isset($inputdata, 'pr_id', "");
            $postData['formAction'] = _isset($inputdata, 'formAction', "");

            $postData["details"] = json_encode($request);
            $postData["asset_details"] = json_encode($postData['asset_details']);
            // $postData["asset_details"] = $postData['asset_details'];
            $postData["pr_po_type"] = _isset($inputdata, 'pr_po_type', "pr");
            $postData["pr_no"] = generateprnumber();
            $postData["status"] = 'approved';
            // print_r($postData);exit;
            $data = $this->itam->purchaserequestconvertsave(array('form_params' => $postData));

        } catch (\Exception $e) {
            $data["content"] = "";
            $data["is_error"] = "";
            $data["msg"] = $e->getmessage();
            $data["http_code"] = "";

            save_errlog("purchaserequestsave", "This controller function is implemented to save PR.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {

            $data["content"] = "";
            $data["is_error"] = "";
            $data["msg"] = $e->getmessage();
            $data["http_code"] = "";

            save_errlog("purchaserequestsave", "This controller function is implemented to save PR.", $this->request_params, $e->getmessage());
        } finally {
            echo json_encode($data, true);
        }
    }

    public function purchaserequestsave(Request $request)
    {
        try {
            $inputdata = $request->all();
            $item_item_data = array('item' => explode(",", $inputdata['item']));
            $item_desc_data = array('item_desc' => explode(",", $inputdata['item_desc']));
            $item_wsr_data = array('warranty_support_required' => explode(",", $inputdata['warranty_support_required']));
            $item_qty_data = array('item_qty' => explode(",", $inputdata['item_qty']));
            $approvers_data = array();
            if ($inputdata['approvers'] != '') {
                $approvers_data = array('approvers' => explode(",", $inputdata['approvers']));
            }
            $approvers_optional_data = array();
            if ($inputdata['approvers_optional'] != '') {
                $approvers_optional_data = array('approvers_optional' => explode(",", $inputdata['approvers_optional']));
            }

            $postData['asset_details']['item'] = _isset($item_item_data, 'item', array());
            $postData['asset_details']['item_desc'] = _isset($item_desc_data, 'item_desc', array());
            $postData['asset_details']['warranty_support_required'] = _isset($item_wsr_data, 'warranty_support_required', array());
            $postData['asset_details']['item_qty'] = _isset($item_qty_data, 'item_qty', array());
            $postData["approval_req"] = _isset($inputdata, 'approval_req', "n");
            $postData["form_templ_id"] = _isset($inputdata, 'form_templ_id', "");
            $postData["urlpath"] = _isset($inputdata, 'urlpath', "purchaserequest");
            $postData["form_templ_type"] = _isset($inputdata, 'form_templ_type', "default");
            $postData["requester_id"] = _isset($inputdata, 'requester_id', "");
            if ($postData["approval_req"] == "n") {
                $postData["status"] = 'approved';
            } else {
                $postData["status"] = _isset($inputdata, 'status', 'pending approval');
            }

            $postData["approved_status"] = array(); // On Edit - "approved_status" == NULL Means Open For reapproval all approvers and "status" change to "pending approval " on lumen side.
            $approval_details['confirmed'] = _isset($approvers_data, 'approvers', array());
            $approval_details['optional'] = _isset($approvers_optional_data, 'approvers_optional', array());
            $postData['approval_details'] = json_encode($approval_details);
            unset($request['approval_req']);
            unset($request['status']);
            unset($request['action']);
            unset($request['form_templ_id']);
            unset($request['item']);
            unset($request['item_desc']);
            unset($request['item_qty']);
            unset($request['warranty_support_required']);
            unset($request['approvers']);
            $postData["pr_id"] = _isset($inputdata, 'pr_id', "");
            $postData['formAction'] = _isset($inputdata, 'formAction', "");
            $postData["details"] = json_encode($request->all());
            $postData["asset_details"] = json_encode($postData['asset_details']);
            $postData["pr_po_type"] = _isset($inputdata, 'pr_po_type', "pr");
            if ($request['formAction'] == 'add') {
                $postData["pr_no"] = generateprnumber();
            }

            $data = $this->itam->purchaserequestsave(array('form_params' => $postData));
            $last_insert_id = $data['content']['insert_id'];

            /* Pr File Upload Code */
            if (isset($_FILES['customer_po_file_new'])) {
                $name1 = $_FILES["customer_po_file_new"]["name"];
                $arr = explode('.', $name1);
                $file_ext = $arr[(count($arr) - 1)];
                $showuserid = showuserid();
                $form_params['created_by'] = $showuserid;
                $files_content = base64_encode(file_get_contents($_FILES['customer_po_file_new']['tmp_name']));
                $form_params['saveimg'] = "pr_customer_po_file_" . time() . '.' . $file_ext;
                $form_params['file_name'] = $_FILES['customer_po_file_new']['name'];
                $form_params['size'] = $_FILES['customer_po_file_new']['size'];
                $form_params['pr_po_id'] = $last_insert_id;
                $form_params['file_ext'] = $file_ext;
                $form_params['files_content'] = $files_content;
                $options = ['form_params' => $form_params];
                $pos_resp = $this->itam->fileupload_pr_extra($options);

            }
            if (isset($_FILES['gc_approval_file_new'])) {
                $name1 = $_FILES["gc_approval_file_new"]["name"];
                $arr = explode('.', $name1);
                $file_ext = $arr[(count($arr) - 1)];
                $showuserid = showuserid();
                $form_params['created_by'] = $showuserid;
                $files_content = base64_encode(file_get_contents($_FILES['gc_approval_file_new']['tmp_name']));
                $form_params['saveimg'] = "pr_gc_approval_file_" . time() . '.' . $file_ext;
                $form_params['file_name'] = $_FILES['gc_approval_file_new']['name'];
                $form_params['size'] = $_FILES['gc_approval_file_new']['size'];
                $form_params['pr_po_id'] = $last_insert_id;
                $form_params['file_ext'] = $file_ext;
                $form_params['files_content'] = $files_content;
                $options = ['form_params' => $form_params];
                $pos_resp = $this->itam->fileupload_pr_extra($options);

            }
            if (isset($_FILES['costing_details_file_new'])) {
                $name1 = $_FILES["costing_details_file_new"]["name"];
                $arr = explode('.', $name1);
                $file_ext = $arr[(count($arr) - 1)];
                $showuserid = showuserid();
                $form_params['created_by'] = $showuserid;
                $files_content = base64_encode(file_get_contents($_FILES['costing_details_file_new']['tmp_name']));
                $form_params['saveimg'] = "pr_costing_details_file_" . time() . '.' . $file_ext;
                $form_params['file_name'] = $_FILES['costing_details_file_new']['name'];
                $form_params['size'] = $_FILES['costing_details_file_new']['size'];
                $form_params['pr_po_id'] = $last_insert_id;
                $form_params['file_ext'] = $file_ext;
                $form_params['files_content'] = $files_content;
                $options = ['form_params' => $form_params];
                $pos_resp = $this->itam->fileupload_pr_extra($options);

            }

        } catch (\Exception $e) {
            $data["content"] = "";
            $data["is_error"] = "";
            $data["msg"] = $e->getmessage();
            $data["http_code"] = "";

            save_errlog("purchaserequestsave", "This controller function is implemented to save PR.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {

            $data["content"] = "";
            $data["is_error"] = "";
            $data["msg"] = $e->getmessage();
            $data["http_code"] = "";

            save_errlog("purchaserequestsave", "This controller function is implemented to save PR.", $this->request_params, $e->getmessage());
        } finally {
            echo json_encode($data, true);
        }
    }

    public function quotation_vendor_cmp_edit(Request $request)
    {
        try {
            $inputdata = $request->all();
            $form_params['pr_po_id'] = $inputdata['pr_po_id'];
            $form_params['selected_item_id'] = $inputdata['selected_item_id'];
            $options = ['form_params' => $form_params];
            $data = $this->itam->quotation_comparison_edit($options);
            /*echo '<pre>';
        print_r($data);
        exit;*/
        } catch (\Exception $e) {
            $data["content"] = "";
            $data["is_error"] = "";
            $data["msg"] = $e->getmessage();
            $data["http_code"] = "";
            save_errlog("purchaserequestsave", "This controller function is implemented to save PR.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {
            $data["content"] = "";
            $data["is_error"] = "";
            $data["msg"] = $e->getmessage();
            $data["http_code"] = "";
            save_errlog("purchaserequestsave", "This controller function is implemented to save PR.", $this->request_params, $e->getmessage());
        } finally {
            echo json_encode($data, true);
        }
    }

    public function quotation_vendor_cmp(Request $request)
    {
        try {
            $inputdata = $request->all();
            $showuserid = showuserid();
            $form_params['created_by'] = $showuserid;
            $form_params['pr_po_id'] = $inputdata['pr_po_id'];
            $form_params['selected_item_id'] = $inputdata['selected_item_id'];
            //$form_params['quotation_comparison_data'] = json_encode($inputdata, true);

            $json_data = array();
            $vendor_i = 1;
            $vendor_count = count($inputdata['pr_vendor_id']);
            for ($k = 0; $k < $vendor_count; $k++) {
                $row_array = array();
                $pr_vendor_id = $inputdata['pr_vendor_id'][$k];
                for ($i = 1; $i <= 3; $i++) {
                    $common_data = array();
                    $common_data['qty_' . $i] = $inputdata['qty_' . $i][0];
                    $common_data['rate_' . $i] = $inputdata['rate_' . $i][$k];
                    $common_data['amount_' . $i] = $inputdata['amount_' . $i][$k];
                    $row_array[] = $common_data;
                }
                $row_array['warranty_support'] = $inputdata['warranty_support_' . ($k + 1)];
                $row_array['gst_extra'] = $inputdata['gst_extra_' . ($k + 1)];
                $row_array['payment_terms'] = $inputdata['payment_terms_' . ($k + 1)];
                $row_array['transport'] = $inputdata['transport_' . ($k + 1)];
                $row_array['delivery_terms'] = $inputdata['delivery_terms_' . ($k + 1)];
                $row_array['material_description'] = $inputdata['material_description_' . ($k + 1)];
                $row_array['total'] = $inputdata['total_' . ($k + 1)];

                $json_data[$pr_vendor_id] = $row_array;
                $vendor_i++;
            }
            /* echo '<pre>';
            print_r($json_data);
            echo '</pre>';exit;*/
            $form_params['quotation_comparison_data'] = json_encode($json_data, true);
            $options = ['form_params' => $form_params];
            $data = $this->itam->quotation_comparison($options);

        } catch (\Exception $e) {
            $data["content"] = "";
            $data["is_error"] = "";
            $data["msg"] = $e->getmessage();
            $data["http_code"] = "";
            save_errlog("purchaserequestsave", "This controller function is implemented to save PR.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {
            $data["content"] = "";
            $data["is_error"] = "";
            $data["msg"] = $e->getmessage();
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
            $inputdata = $request->all();
            $postData["user_id"] = _isset($inputdata, 'user_id', "");
            $postData["approval_status"] = _isset($inputdata, 'approval_status', "");
            $postData["pr_po_id"] = _isset($inputdata, 'pr_po_id', "");
            $postData["comment"] = _isset($inputdata, 'comment', "");
            $postData["pr_po_type"] = _isset($inputdata, 'pr_po_type', "");
            $postData["confirmed_optional"] = _isset($inputdata, 'confirmed_optional', "");

            $data = $this->itam->prpoapprovereject(array('form_params' => $postData));
            
        } catch (\Exception $e) {
            $data["content"] = "";
            $data["is_error"] = "";
            $data["msg"] = $e->getmessage();
            $data["http_code"] = "";

            save_errlog("prpoapprovereject", "This controller function is implemented to PR approve or reject.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {

            $data["content"] = "";
            $data["is_error"] = "";
            $data["msg"] = $e->getmessage();
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
            $inputdata = $request->all();
            $postData["user_id"] = _isset($inputdata, 'user_id', "");
            $postData["pr_po_id"] = _isset($inputdata, 'pr_po_id', "");
            $postData["pr_po_type"] = _isset($inputdata, 'pr_po_type', "");
            $postData["action"] = _isset($inputdata, 'action', "");
            $postData["comment"] = _isset($inputdata, 'comment', "");
            // $postData["itemqty"]    = _isset($inputdata, 'itemqty', "");
            // $postData["item_title"] = _isset($inputdata, 'item_title', "");
            // print_r($request->all());
            // print_r($postData);die;
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
                $data = $this->itam->poreceiveditem(array('form_params' => $inputdata));
                // print_r($data);die;
            } else {
                /* For Notify */

                $postData["mail_notification_to"] = _isset($inputdata, 'mail_notification_to', "");
                $postData["mail_notification_subject"] = _isset($inputdata, 'mail_notification_subject', "");
                $postData["mail_notification"] = _isset($inputdata, 'mail_notification', "");
                $postData["notify_to_id"] = _isset($inputdata, 'notify_to_id', "");

                /* For Add Invoice */
                $messages = [
                    'invoice_file.mimes' => showmessage('000', array('{name}'), array(trans('label.lbl_attachmentid')), true),
                ];
                $validator = Validator::make($inputdata, [
                    'invoice_file' => 'required',
                    'invoice_file.*' => 'required|mimes:jpeg,png,pdf,doc,docx,csv,xlsx,xls|max:4096',
                ], $messages
                );
                if ($validator->fails()) {
                    $data["content"] = "";
                    $data["is_error"] = "true";
                    $data["msg"] = ['invoice_file' => 'file is required'];
                    $data["http_code"] = "";
                    echo json_encode($data, true);
                    die();
                }

                if (isset($_FILES['invoice_file'])) {

                    //get file extension
                    $file_ext = 'jpeg';
                    $name1 = $_FILES["invoice_file"]["name"];
                    $arr = explode('.', $name1);
                    if (count($arr) > 1) {
                        $file_ext = $arr[1];
                    }

                    $files_content = base64_encode(file_get_contents($_FILES['invoice_file']['tmp_name']));
                    $postData["invoice_id"] = _isset($inputdata, 'invoice_id', "");
                    $postData["formaction"] = _isset($inputdata, 'formaction', "");
                    $postData["id"] = _isset($inputdata, 'id', "");
                    $postData["received_date"] = _isset($inputdata, 'received_date', "");
                    $postData["payment_due_date"] = _isset($inputdata, 'payment_due_date', "");
                    $postData['file_ext'] = $file_ext;
                    $postData['file'] = $files_content;
                    $postData['file_name'] = $_FILES['invoice_file']['name'];
                    $postData['size'] = $_FILES['invoice_file']['size'];

                    $data = $this->itam->prpoformActions(array('form_params' => $postData));

                }

                if (isset($data["is_error"]) && $data["is_error"] == false) {
                    $phpmailer = new Maillib();
                    $to_emails = $postData['mail_notification_to'];
                    $subject = $postData['mail_notification_subject'];
                    $email_body = $postData['comment'];
                    $mailresponse = $phpmailer->mailsent($to_emails, $subject, $email_body);
                }
            }
        } catch (\Exception $e) {
            $data["content"] = "";
            $data["is_error"] = "";
            $data["msg"] = $e->getmessage();
            $data["http_code"] = "";

            save_errlog("prpoformActions", "This controller function is implemented to PR form actions.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {

            $data["content"] = "";
            $data["is_error"] = "";
            $data["msg"] = $e->getmessage();
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
    public function purchaseorders($pr_type = '', $id = '')
    {
        // $po_id      = _isset($this->request_params, 'po_id');
        $po_id = $id;
        if ($id == "") {
            $topfilter = array('gridsearch' => true, 'gridadvsearch' => true, 'jsfunction' => 'poList() , poDetailsLoad()');
            $data['show_single'] = "false";
        } else {
            $topfilter = array('gridsearch' => false, 'gridadvsearch' => true, 'jsfunction' => 'poList() , poDetailsLoad()');
            $data['show_single'] = "false";
        }
        
        $data['po_id'] = $po_id;
        $data['pr_type'] = $pr_type;
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter, '', ['vendors', 'datesearch', 'users']);
        $data['pageTitle'] = trans('title.purchaseorder');
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
            $paging = array();
            $limit = _isset($this->request_params, 'limit', 20);
            // $limit         = _isset($this->request_params, 'limit', config('enconfig.def_limit_short'));
            $page = _isset($this->request_params, 'page', config('enconfig.page'));
            $searchkeyword = _isset($this->request_params, 'searchkeyword');
            $po_id = _isset($this->request_params, 'active_po_id');
            $po_amt_status = _isset($this->request_params, 'po_amt_status');
            $show_single = _isset($this->request_params, 'show_single');
            $user_id = _isset($this->request_params, 'user_id');
            $vendor_id = _isset($this->request_params, 'vendor_id');
            $pr_type = _isset($this->request_params, 'pr_type');
            $timerange = _isset($this->request_params, 'timerange');
            $customtime = _isset($this->request_params, 'customtime');
            
            $is_error = false;
            $msg = "";
            $content = "";
            $limit_offset = limitoffset($limit, $page);
            $page = $limit_offset['page'];
            $limit = $limit_offset['limit'];
            $offset = $limit_offset['offset'];
            
            $form_params['limit'] = $paging['limit'] = $limit;
            $form_params['page'] = $paging['page'] = $page;
            $form_params['offset'] = $paging['offset'] = $offset;
            $form_params['searchkeyword'] = $searchkeyword;
            $form_params['po_id'] = $po_id;
            $form_params['pr_type'] = $pr_type;
            $form_params['user_id'] = $user_id;
            $form_params['vendor_id'] = $vendor_id;
            $form_params['po_amt_status'] = $po_amt_status;
            if (!empty($customtime)) {
                $cust_date = explode(' - ', $customtime);
                $form_params['customtime'] = ['start_date' => date('Y-m-d', strtotime($cust_date[0])), 'end_date' => date('Y-m-d', strtotime($cust_date[1]))];
            }
            
            if (!empty($timerange)) {
                if ($timerange == 'today') {
                    $form_params['timerange'] = date('Y-m-d');
                } else {
                    $clean_string = str_replace(" ", "_", $timerange);
                    if (strpos($clean_string, "_days")) {
                        $dt = str_replace('_', ' ', str_replace('last_', '-', $clean_string));
                        $final_dt = date('Y-m-d', strtotime($dt, strtotime(date('Y-m-d'))));
                        $form_params['timerange'] = $final_dt;
                    }
                }
            }
            
            $options = ['form_params' => array('user_id' => showuserid())];
            $pos_resp = $this->iam->getuserprofile($options);
            $pos = _isset($pos_resp, 'content');
            $department_id = $pos[0]['department_id'];
            $designation_id = $pos[0]['designation_id'];

            //payment committee dept
            if ($department_id == '627dc11c-e63b-11ec-a33e-86bd6599c53f') {
                $form_params['dept_type'] = 'payment_committee';
            }
            if ($department_id == '58132120-e61f-11ec-b010-86bd6599c53f') {
                $form_params['dept_type'] = 'internal_auditor';
            }

            $options = ['form_params' => $form_params];
            // print_r($options);die;
            // if(_isset($options)){
            $pos_resp = $this->itam->purchaseorder($options);
            // }
            // print_r($pos_resp);die;

            if ($pos_resp['is_error']) {
                $is_error = $pos_resp['is_error'];
                $msg = $pos_resp['msg'];
            } else {
                foreach ($pos_resp['content']['records'] as $key => $value) {                  
                  $requester_id = $value["requester_id"];
                                               
                  $reqoptions_optional = ['form_params' => array('user_id' => $requester_id,'emp_id' => "")];
                             
                  $reqresponse_optional = $this->iam->getrequesteruser($reqoptions_optional);
                                                
                  if (!(is_array($reqresponse_optional['content']['records'][0]) && count($reqresponse_optional['content']['records'][0]) > 0)) {
                      $reqresponse_optional = array();
                      $reqresponse_optional = array();                                
                      $pos_resp['content']['records'][$key]['requester_info'] = array();
                  }else{
                      $pos_resp['content']['records'][$key]['requester_info'] = $reqresponse_optional['content']['records'][0];
                  }
                }

                $pos = _isset(_isset($pos_resp, 'content'), 'records');

                /*if ($pos) {
                foreach ($pos as $key => $po) {
                $form_paramsother['bv_id']                = $po['details']['bv_id'];
                $form_paramsother['dc_id']                = $po['details']['dc_id'];
                $form_paramsother['location_id']          = $po['details']['location_id'];
                $options                                  = ['form_params' => $form_paramsother];
                $pos_other_resp                           = $this->iam->getdclocationbv($options);
                $bv_dc_loc_detail                         = _isset($pos_other_resp, 'content');
                $pos[$key]['details']['bv_dc_loc_detail'] = $bv_dc_loc_detail;
                }
                }*/

                $paging['total_rows'] = _isset(_isset($pos_resp, 'content'), 'totalrecords');
                if ($show_single == "true") {
                    $paging['showpagination'] = false;
                } else {
                    $paging['showpagination'] = true;
                }
                // print_r($pos);die;
                $paging['jsfunction'] = 'poList()';
                $view = 'Cmdb/purchaseorderlist';
                $po_id = isset($pos[0]['po_id']) ? $pos[0]['po_id'] : "";
                $pos_arr['pos'] = $pos;
                $pos_arr['show_single'] = $show_single;
                $content = $this->emlib->emgrid($pos_arr, $view, array(), $paging);
            }

            $response["html"] = $content;
            $response["is_error"] = $is_error;
            $response["msg"] = $msg;
            $response['po_id'] = $po_id;
        } catch (\Exception $e) {
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            $response['po_id'] = '';
            save_errlog("purchaseorderlist", "This controller function is implemented to get list of PO.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            $response['po_id'] = '';
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
                $data['po_id'] = '';
                $purchaserequestdetail = array();
                $data['purchaserequestdetail'] = $purchaserequestdetail;
                //$data['bv_id'] = '';
                $form_params['po_id'] = $pr_po_id;
                $form_params['limit'] = 0;
                $form_params['page'] = 0;
                $form_params['offset'] = 0;
                $form_params['searchkeyword'] = '';

                $options = ['form_params' => $form_params];
                $prs_resp = $this->itam->purchaseorder($options);
                // print_r($prs_resp);
                // exit;
                $data['pr_first_detail'] = isset($prs_resp['content']['records'][0]) ? $prs_resp['content']['records'][0] : null;

                $pr_po_id = isset($prs_resp['content']['records'][0]) ? $prs_resp['content']['records'][0]['po_id'] : null;

                $assetoptions = [
                    'form_params' => array('pr_po_id' => $pr_po_id, 'asset_type' => 'po')];
                $assetdetails_resp = $this->itam->prpoassetdetails($assetoptions);
               
                $data['assetdetails'] = isset($assetdetails_resp['content']) ? $assetdetails_resp['content'] : null;
                
                if ($data['assetdetails'] != null) {
                    $extraarray = array_column($data['assetdetails'], 'asset_details');
                    
                    $newarray = array_map(function ($val) {
                        return json_decode($val, true);
                    }, $extraarray);
                    
                    $arras = array_filter($newarray, function ($arr) {
                        return empty($arr['item_unit']);
                    });
                   
                    if (!empty($arras)) {                
                              
                        $item_sku_codes = array_column($newarray, 'asset_sku');                        
                        if($item_sku_codes[0] == ""){
                          $ItemFinalUnitSku = array("" => "");
                        }else{
                          $ItemFinalUnitSku = getItemUnitWithSKU($item_sku_codes);
                        }                        
                    } else {                      
                        $ItemFinalUnitSku = array_column($newarray, 'item_unit', 'asset_sku');
                    }
                } else {
                    $ItemFinalUnitSku = array("" => "");
                }
                
                // $skuoptions = [
                // 'form_params' => array('asset_sku' => $item_sku_codes)];
                // $item_sku_codes_unit = $this->itam->assetskuunit($skuoptions);
                // $contentcodeunit = _isset($item_sku_codes_unit, 'content');
                // $finalarray = array_column($contentcodeunit,'measurement_unit_name','sku_code');
                // print_r($finalarray);die;

                /*$receivedassetoptions = [
                'form_params' => array('pr_po_id' => $pr_po_id, 'asset_type' => 'po')];
                $receivedassetdetails_resp = $this->itam->prpoassetdetails($receivedassetoptions);

                $data['receivedassetdetails'] = isset($receivedassetdetails_resp['content']) ? $receivedassetdetails_resp['content'] : null;*/

                $historyoptions = [
                    'form_params' => array('pr_po_id' => $pr_po_id, 'history_type' => 'po')];
                $prpohistorylog_resp = $this->itam->prpohistorylog($historyoptions);
                $data['prpohistorylog'] = isset($prpohistorylog_resp['content']) ? $prpohistorylog_resp['content'] : null;

                $invoiceoptions = [
                    'form_params' => array('po_id' => $pr_po_id)];
                $purchaseinvoices_resp = $this->itam->purchaseinvoices($invoiceoptions);
                $data['purchaseinvoices'] = isset($purchaseinvoices_resp['content']) ? $purchaseinvoices_resp['content'] : null;

                $data['itemunit_sku'] = $ItemFinalUnitSku;

                $attachmentoptions = [
                    'form_params' => array('pr_po_id' => $pr_po_id, 'attachment_type' => 'po')];
                $prpoattachment_resp = $this->itam->prpoattachment($attachmentoptions);
                $data['prpoattachment'] = isset($prpoattachment_resp['content']) ? $prpoattachment_resp['content'] : null;
                $purchaserequestdata = array();
                $form_params['template_name'] = 'purchase_request';
                $options = [
                    'form_params' => $form_params,
                ];

                $purchaserequestdata = $this->itam->getFormTemplateDefaulteConfigbyTemplateName($options);
                $data['form_templ_data'] = $purchaserequestdata;

                /* To get Approvers name fromm IAM */
                $approval_details_by_data = array('optional' => array(), 'confirmed' => array());
                if (isset($data['pr_first_detail']['approval_details']['optional']) && !empty($data['pr_first_detail']['approval_details']['optional'])) {
                    foreach ($data['pr_first_detail']['approval_details']['optional'] as $user_id) {
                        $options_optional = [
                            'form_params' => array('user_id' => $user_id),
                        ];
                        $response_optional = $this->iam->getAllUsersWithoputPermission($options_optional);
                        $response_data = _isset(_isset($response_optional, 'content'), 'records');

                        if (!(is_array($response_data) && count($response_data) > 0)) {
                            $response_data = array();
                            $response_data[0] = array();
                        }

                        $approval_details_by_data['optional'][] = $response_data[0];
                    }
                }

                if (!empty($data['prpohistorylog'])) {
                    foreach ($data['prpohistorylog'] as $key => $history) {
                        $options_history = [
                            'form_params' => array('user_id' => $history['created_by']),
                        ];
                        $response_historyuser = $this->iam->getAllUsersWithoputPermission($options_history);
                        $historyuser_data = _isset(_isset($response_historyuser, 'content'), 'records');

                        if (!(is_array($historyuser_data) && count($historyuser_data) > 0)) {
                            $historyuser_data = array();
                            $historyuser_data[0] = array();
                        }

                        $data['prpohistorylog'][$key]['created_by_name'] = $historyuser_data[0];
                    }
                }

                if (isset($data['pr_first_detail']['approval_details']['confirmed']) && !empty($data['pr_first_detail']['approval_details']['confirmed'])) {
                    foreach ($data['pr_first_detail']['approval_details']['confirmed'] as $user_id) {
                        $options_confirmed = [
                            'form_params' => array('user_id' => $user_id),
                        ];
                        $response_confirmed = $this->iam->getAllUsersWithoputPermission($options_confirmed);
                        $response_data = _isset(_isset($response_confirmed, 'content'), 'records');

                        if (!(is_array($response_data) && count($response_data) > 0)) {
                            $response_data = array();
                            $response_data[0] = array();
                        }

                        $approval_details_by_data['confirmed'][] = $response_data[0];
                    }
                }

                $data['pr_first_detail']['approval_details_by_data'] = $approval_details_by_data;
                // print_r($data);die;

                $contents = enview("Cmdb/purchaseorderdetail", $data);
                $response["html"] = $contents;
                $response["is_error"] = $is_error = "";
                $response["msg"] = $msg = "";
            } else {
                $response["html"] = "";
                $response["is_error"] = $is_error = "";
                $response["msg"] = $msg = "";
            }
        } catch (\Exception $e) {
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            $response['po_id'] = '';
            save_errlog("purchaseorderdetail", "This controller function is implemented to show PO details.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            $response['po_id'] = '';
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
        $po_id = _isset($this->request_params, 'po_id');
        $data['po_id'] = '';
        $purchaseorderdetail = array();
        $data['purchaseorderdetail'] = $purchaseorderdetail;
        $contents = enview("Cmdb/purchaseorderdetailinvoice", $data);
        $response["html"] = $contents;
        $response["is_error"] = $is_error = "";
        $response["msg"] = $msg = "";
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

            $pr_id = _isset($this->request_params, 'pr_id', '');
            $po_id = _isset($this->request_params, 'po_id', '');
            $po_vendor_id = _isset($this->request_params, 'po_vendor_id', '');

            // $inputdata = array('template_name' => 'purchaserequest');
            $inputdata = array('template_name' => 'purchaseorder');
            $data = $this->itam->getFormTemplateDefaulteConfigbyTemplateName(array('form_params' => $inputdata));
            if ($data['content']) {
                $data['form_templ_data'] = $data['content'][0];
            } else {
                $data['form_templ_data'] = array();
            }
            $data['pr_id'] = $pr_id;
            $data['po_id'] = $po_id;
            $option = array();
            $ciDetails = $this->itam->getcitemplates($option);
            $data['ciDetails'] = _isset(_isset($ciDetails, 'content'), 'records');
            //Get Approvers List
            $option['form_params'] = array('advusertype' => "staff");
            $approversDetails = $this->iam->getUsers($option);
            $data['approversDetails'] = _isset(_isset($approversDetails, 'content'), 'records');
            /* Fetch Edit Data  Of PR*/
            //$pr_id = _isset($this->request_params, 'pr_id');
            $form_params['pr_id'] = $pr_id;
            $form_params['po_id'] = $po_id;
            $pr_id = explode(',', $pr_id);

            if (count($pr_id) <= 1) {

                $options = ['form_params' => $form_params];
                $prs_resp = $this->itam->purchaserequests($options);
                $purchaserequestdetail = isset($prs_resp['content']['records'][0]) ? $prs_resp['content']['records'][0] : null;
                $data['purchaserequestdetail'] = $purchaserequestdetail;

                $assetoptions = [
                    'form_params' => array('pr_po_id' => $pr_id[0], 'pr_po_ids' => '', 'asset_type' => 'pr', 'po_vendor_id' => $po_vendor_id)];

            } else {

                $assetoptions = [
                    'form_params' => array('pr_po_id' => $pr_id[0], 'pr_po_ids' => $pr_id, 'asset_type' => 'pr', 'po_vendor_id' => $po_vendor_id)];
                $options = ['form_params' => ['pr_po_ids' => $pr_id]];
                $prs_resp = $this->itam->purchaserequests($options);

                $pr_addresses = isset($prs_resp['content']['records']) ? $prs_resp['content']['records'] : array();
                $data['pr_addresses'] = $pr_addresses;
                $data['purchaserequestdetail'] = array();
            }
            $option = array();
            $shiptoDetails = $this->itam->getshiptos($option);
            $shiptoDetailsArr = _isset(_isset($shiptoDetails, 'content'), 'records');
            $data['shiptoDetailsArr'] = $shiptoDetailsArr;

            $assetdetails_resp = $this->itam->prpoassetdetails($assetoptions);
            $data['assetdetails'] = isset($assetdetails_resp['content']) ? $assetdetails_resp['content'] : null;

            /*$vendors = array_map(function($approve){
            $vendore_data = json_decode($approve['vendor_approve'],true);
            if(!empty($vendore_data)){
            return $vendore_data['vendor_id'];
            }
            }, $data['assetdetails']);
            $data['vendors'] = array_filter($vendors);*/
            $data['vendors'] = array($po_vendor_id);

            $data['formAction'] = "add";

            $html = view("Cmdb/purchaseorderadd", $data);
            echo $html;
        } catch (\Exception $e) {
            save_errlog("purchaseorderadd", "This controller function is implemented to render add PO form.", $this->request_params, $e->getmessage());
            echo $e->getmessage();
        } catch (\Error $e) {
            save_errlog("purchaseorderadd", "This controller function is implemented to render add PO form.", $this->request_params, $e->getmessage());
            echo $e->getmessage();
        }
    }

    public function purchaseorderadd_original()
    {

        try
        {
            $pr_id = _isset($this->request_params, 'pr_id', '');
            $po_id = _isset($this->request_params, 'po_id', '');
            $po_vendor_id = _isset($this->request_params, 'po_vendor_id', '');

            // $inputdata = array('template_name' => 'purchaserequest');
            $inputdata = array('template_name' => 'purchaseorder');
            $data = $this->itam->getFormTemplateDefaulteConfigbyTemplateName(array('form_params' => $inputdata));
            if ($data['content']) {
                $data['form_templ_data'] = $data['content'][0];
            } else {
                $data['form_templ_data'] = array();
            }
            $data['pr_id'] = $pr_id;
            $data['po_id'] = $po_id;
            $option = array();
            $ciDetails = $this->itam->getcitemplates($option);
            $data['ciDetails'] = _isset(_isset($ciDetails, 'content'), 'records');
            //Get Approvers List
            $option['form_params'] = array('advusertype' => "staff");
            $approversDetails = $this->iam->getUsers($option);
            $data['approversDetails'] = _isset(_isset($approversDetails, 'content'), 'records');
            /* Fetch Edit Data  Of PR*/
            //$pr_id = _isset($this->request_params, 'pr_id');
            $form_params['pr_id'] = $pr_id;
            $form_params['po_id'] = $po_id;
            if ($pr_id != "") {
                $options = ['form_params' => $form_params];
                $prs_resp = $this->itam->purchaserequests($options);

                $purchaserequestdetail = isset($prs_resp['content']['records'][0]) ? $prs_resp['content']['records'][0] : null;
                $data['purchaserequestdetail'] = $purchaserequestdetail;
            } else {
                $data['purchaserequestdetail'] = array();
            }

            $assetoptions = [
                'form_params' => array('pr_po_id' => $pr_id, 'asset_type' => 'pr', 'po_vendor_id' => $po_vendor_id)];
            $assetdetails_resp = $this->itam->prpoassetdetails($assetoptions);

            $data['assetdetails'] = isset($assetdetails_resp['content']) ? $assetdetails_resp['content'] : null;

            /*$vendors = array_map(function($approve){
            $vendore_data = json_decode($approve['vendor_approve'],true);
            if(!empty($vendore_data)){
            return $vendore_data['vendor_id'];
            }
            }, $data['assetdetails']);
            $data['vendors'] = array_filter($vendors);*/
            $data['vendors'] = array($po_vendor_id);

            $data['formAction'] = "add";
            $html = view("Cmdb/purchaseorderadd", $data);
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
     * Function to return add PO form
     * @author Darshan Chaure
     * @access public
     * @package PurchaseOrder
     * @param  string pr_id
     * @param  string po_id
     * @return json
     */
    public function convertitemsinpr()
    {
        try
        {
            $add = $this->itam->getshiptos(array('form_params' => array()));
            $content = _isset($add, 'content', '');
            $records = _isset($content, 'records', '');
            $addresses = array();
            if (!empty($records)) {
                foreach ($records as $value) {
                    $addresses[$value['shipto_id']] = $value['company_name'];
                }
            }

            $pr_id = _isset($this->request_params, 'pr_id', '');
            $po_id = _isset($this->request_params, 'po_id', '');
            // $inputdata = array('template_name' => 'purchaserequest');
            $inputdata = array('template_name' => 'converttopr');
            $data = $this->itam->getFormTemplateDefaulteConfigbyTemplateName(array('form_params' => $inputdata));
            if ($data['content']) {
                $data['form_templ_data'] = $data['content'][0];
            } else {
                $data['form_templ_data'] = array();
            }
            $data['pr_id'] = $pr_id;
            $data['po_id'] = $po_id;
            $option = array();
            $ciDetails = $this->itam->getcitemplates($option);
            $data['ciDetails'] = _isset(_isset($ciDetails, 'content'), 'records');

            $assetoptions = [
                'form_params' => array()];
            $assetdetails_resp = $this->itam->prconversionassetdetails($assetoptions);
            //  print_r($assetdetails_resp);
            // exit;
            $assetdetails = isset($assetdetails_resp['content']) ? $assetdetails_resp['content'] : null;
            $items_arr = [];

            if (!empty($assetdetails)) {
                foreach ($assetdetails as $prrecord) {
                    if (!empty($prrecord['pritems'])) {
                        $pritems = json_decode($prrecord['pritems'], true);
                        $i = 0;

                        foreach ($pritems as $val) {
                            $ship_to_other = $prrecord['ship_to_other'];

                            if (array_key_exists($val['item'], $items_arr)) {

                                $pr_id = $items_arr[$val['item']]['pr_id'];
                                $pr_no = $items_arr[$val['item']]['pr_no'];
                                $pr_no[$prrecord['pr_id']] = $prrecord['pr_no'];

                                $pr_shipto = $items_arr[$val['item']]['pr_shipto'];
                                $qty = $val['item_qty'];
                                // $qty                                   = $items_arr[$val['item']]['item_qty'];
                                $pr_shipto[$prrecord['pr_shipto']] = ['location' => (($ship_to_other != 'null') ? $ship_to_other : $addresses[$prrecord['pr_shipto']]), 'quantity' => $qty];
                                $q = $qty + $items_arr[$val['item']]['item_qty'];
                                $items_arr[$val['item']]['pr_id'] = $pr_id . ',' . $prrecord['pr_id'];
                                $items_arr[$val['item']]['item_qty'] = $q;
                                $items_arr[$val['item']]['warranty_support_required'] = $val['warranty_support_required'];
                                $items_arr[$val['item']]['item_desc'] = $val['item_desc'];
                                $items_arr[$val['item']]['item_id'] = $val['item'];
                                $items_arr[$val['item']]['pr_no'] = $pr_no;
                                $items_arr[$val['item']]['pr_shipto'] = $pr_shipto;
                                // $items_arr[$val['item']]['item_desc'] = $val['item_desc'];
                            } else {

                                $qty = $val['item_qty'];
                                $items_arr[$val['item']]['pr_id'] = $prrecord['pr_id'];
                                $items_arr[$val['item']]['item_qty'] = $qty;
                                $items_arr[$val['item']]['item_id'] = $val['item'];
                                $items_arr[$val['item']]['warranty_support_required'] = $val['warranty_support_required'];
                                $items_arr[$val['item']]['item_desc'] = $val['item_desc'];
                                $items_arr[$val['item']]['pr_no'] = array($prrecord['pr_id'] => $prrecord['pr_no']);
                                $items_arr[$val['item']]['pr_shipto'] =
                                array(
                                    $prrecord['pr_shipto'] => ['location' => (($ship_to_other != 'null') ? $ship_to_other : $addresses[$prrecord['pr_shipto']]), 'quantity' => $qty]);
                                // $items_arr[$val['item']]['item_desc'] = $val['item_desc'];
                            }

                        }

                    }

                }
            }
            /*print_r($items_arr);
            exit;*/
            $data['items_arr'] = $items_arr;

            $data['formAction'] = "add";
            $html = view("Cmdb/pr_convert_one_pr", $data);
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
        if ($input_data['attachment_type'] == "pr" || $input_data['attachment_type'] == "qu") {
            $redirect_url = '/purchaserequest';
        } else {
            $redirect_url = '/purchaseorders';
        }
        $messages = [
            'file.mimes' => showmessage('000', array('{name}'), array(trans('label.lbl_attachmentid')), true),
        ];
        $validator = Validator::make($input_data, [
            'file' => 'required',
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
                $name1 = $_FILES["file"]["name"];
                $arr = explode('.', $name1[$key]);
                if (count($arr) > 1) {
                    $file_ext = $arr[1];
                }

                $files_content = base64_encode(file_get_contents($_FILES['file']['tmp_name'][$key]));
                $form_params['user_id'] = $showuserid;
                $form_params['pr_po_id'] = _isset($this->request_params, 'pr_po_id');
                $form_params['type'] = _isset($this->request_params, 'type');
                $form_params['attachment_type'] = _isset($this->request_params, 'attachment_type');
                if ($input_data['attachment_type'] == "qu") {
                    $form_params['pr_vendor_id'] = _isset($this->request_params, 'pr_vendor_id');
                }
//              $form_params['showuserfullname']=  $showuserfullname ;
                $form_params['file_ext'][$key] = $file_ext;
                $form_params['file'][$key] = $files_content;
                $form_params['file_name'][$key] = $_FILES['file']['name'][$key];
                $form_params['size'][$key] = $_FILES['file']['size'][$key];
                $options = ['form_params' => $form_params];
                if ($form_params['attachment_type'] == "pr" || $input_data['attachment_type'] == "qu") {
                    $redirect_url = '/purchaserequest';
                } else {
                    $redirect_url = '/purchaseorders';
                }

            }

            if ($input_data['attachment_type'] == "pr" || $input_data['attachment_type'] == "qu") {
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
        $inputdata = $request->all();
        $postData["attach_id"] = _isset($inputdata, 'attach_id', "");
        $postData["pr_po_id"] = _isset($inputdata, 'pr_po_id', "");
        $postData["attachment_type"] = _isset($inputdata, 'attachment_type', "");
        $data = $this->itam->deleteattachment(array('form_params' => $postData));
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

            $inputdata = $request->all();

            $postData['asset_details']['item'] = _isset($inputdata, 'item', array());
            $postData['asset_details']['item_product'] = _isset($inputdata, 'item_product', array());
            $postData['asset_details']['item_unit'] = _isset($inputdata, 'item_unit', array());
            $postData['asset_details']['item_desc'] = _isset($inputdata, 'item_desc', array());
            $postData['asset_details']['item_qty'] = _isset($inputdata, 'item_qty', array());
            $postData['asset_details']['item_estimated_cost'] = _isset($inputdata, 'item_estimated_cost', array());
            $postData["approval_req"] = _isset($inputdata, 'approval_req', "n");
            $postData["form_templ_id"] = _isset($inputdata, 'form_templ_id', "");
            $postData["urlpath"] = _isset($inputdata, 'urlpath', "purchaserequest");
            $postData["po_amt"] = _isset($inputdata, 'total_cost');
            $pr_department_id = _isset($inputdata, 'pr_department_id');
            $po_amt = _isset($inputdata, 'total_cost');
            if (_isset($inputdata, 'item_addresses', array())) {

                $postData['asset_details']['item_addresses'] = _isset($inputdata, 'item_addresses', array());
            }
            //$postData["form_templ_type"] = _isset($inputdata,'form_templ_type', "default");
            // $postData["requester_id"] = _isset($inputdata, 'requester_id', "");
            /*$postData["bv_id"]        = 'd7df036a-0a10-11ec-ad77-4e89be533080';//_isset($inputdata, 'bv_id', "");
            $postData["dc_id"]        = '14fe35f4-0a11-11ec-9503-4e89be533080';//_isset($inputdata, 'dc_id', "");
            $postData["location_id"]  = 'e0ce8c54-0c9d-11ec-905c-4e89be533080';//_isset($inputdata, 'location_id', "");*/
            // $postData["po_name"]      = _isset($inputdata, 'po_name', "");
            $postData['formAction'] = _isset($inputdata, 'formAction', "");
            if ($postData['formAction'] == "add") {
                if ($postData["approval_req"] == "n") {
                    //$postData["status"] = 'open';
                    $postData["status"] = 'approved';
                } else {
                    $postData["status"] = _isset($inputdata, 'status', 'pending approval');
                }
                $postData["pr_id"] = _isset($inputdata, 'pr_id', "");
                $postData["po_no"] = generateponumber(); //_isset($inputdata, 'po_no', "");
               // print_r($postData["po_no"]);
               // die();
                $postData["requester_id"] = showuserid();

            }

            $postData["approved_status"] = array();
            // On Edit - "approved_status" == NULL Means Open For reapproval all approvers and "status" change to "pending approval " on lumen side.

            $approval_details['confirmed'] = _isset($inputdata, 'approvers', array());
            if (!empty($po_amt)) {
                // Internal Auditor & Payment committee
                // array_push($approval_details['confirmed'], '9a9610ac-e61f-11ec-9d86-86bd6599c53f');
                // array_push($approval_details['confirmed'], 'b7c6e2d4-e63b-11ec-8418-86bd6599c53f');
                // Internal Auditor & Payment committee
                /*if($po_amt > 25000){
            array_push($approval_details['confirmed'],'b7c6e2d4-e63b-11ec-8418-86bd6599c53f');
            }*/
            }

            $approval_details['optional'] = _isset($inputdata, 'approvers_optional', array());
            $postData['approval_details'] = json_encode($approval_details);
            unset($request['item']);
            unset($request['item_desc']);
            unset($request['item_product']);
            unset($request['item_unit']);
            unset($request['item_qty']);
            unset($request['item_addresses']);
            unset($request['item_estimated_cost']);
            unset($request['approval_req']);
            unset($request['approvers']);
            unset($request['total_cost']);
            //unset( $request['bv_id']);
            // unset( $request['dc_id']);
            //unset( $request['location_id']);
            unset($request['status']);
            unset($request['action']);
            unset($request['form_templ_id']);

            $postData["po_id"] = _isset($inputdata, 'po_id', "");
            $postData["pr_vendor"] = _isset($inputdata, 'pr_vendor', "");
            $postData['formAction'] = _isset($inputdata, 'formAction', "");
            $postData["details"] = json_encode($request->all());

            $otherDetails = array(
                "discount_per" => _isset($inputdata, 'discount_per', ""),
                "discount_amount" => _isset($inputdata, 'discount_amount', ""),
            );
            $postData['other_details'] = json_encode($otherDetails);
            // $postData['asset_details']['item_estimated_cost'] = $request['item_estimated_cost'];
            $postData["asset_details"] = json_encode($postData['asset_details']);
            $postData["pr_po_type"] = "po";
            // print_r($postData);
            $data = $this->itam->purchaseordersave(array('form_params' => $postData));
            // print_r($data);die;
            if (!empty($data['content']['insert_id'])) {

                $options = ['form_params' => array(
                    'department_id' => $pr_department_id,
                    'deductAmount' => $po_amt,
                )];
                $vars = $this->iam->updateDeptBalBudg($options);
            }
            echo json_encode($data, true);
        } catch (\Exception $e) {
            $response = array();
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            $response['po_id'] = '';
            save_errlog("purchaseordersave", "This controller function is implemented to save PO details.", $this->request_params, $e->getmessage());
            echo json_encode($response, true);
        } catch (\Error $e) {
            $response = array();
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            $response['po_id'] = '';
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

            $po_id = _isset($this->request_params, 'po_id', '');
            $pr_id = _isset($this->request_params, 'pr_id', '');
            //$inputdata               = array('template_name' => 'purchaserequest');
            $inputdata = array('template_name' => 'purchaseorder');
            $data = $this->itam->getFormTemplateDefaulteConfigbyTemplateName(array('form_params' => $inputdata));
            $data['form_templ_data'] = $data['content'][0];
            if (isset($data['form_templ_data']['details'])) {
                $details_arr_org = json_decode($data['form_templ_data']['details'], true);
                $details_fld_arr_org = _isset($details_arr_org, 'fields') ? $details_arr_org['fields'] : array();
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
                    $details_arr_lang = json_encode($details_arr_org);
                }
            }
            $data['details_arr_lang'] = $details_arr_lang;

            if ($data['content']) {
                $data['form_templ_data'] = $data['content'][0];
            } else {
                $data['form_templ_data'] = array();
            }
            $data['po_id'] = $po_id;
            $data['pr_id'] = $pr_id;
            $option = array();
            $ciDetails = $this->itam->getcitemplates($option);
            $data['ciDetails'] = _isset(_isset($ciDetails, 'content'), 'records');
            //Get Approvers List
            $option['form_params'] = array('advusertype' => "staff");
            $approversDetails = $this->iam->getUsers($option);
            $data['approversDetails'] = _isset(_isset($approversDetails, 'content'), 'records');

            /* Fetch Edit Data  Of PR*/
            //$po_id = _isset($this->request_params, 'po_id');
            $form_params['po_id'] = $po_id;
            $options = ['form_params' => $form_params];
            $prs_resp = $this->itam->purchaseorder($options);

            $purchaserequestdetail = isset($prs_resp['content']['records'][0]) ? $prs_resp['content']['records'][0] : null;
            $data['purchaserequestdetail'] = $purchaserequestdetail;

            $historyoptions = [
                'form_params' => array('pr_po_id' => $po_id, 'history_type' => 'po')];
            $prpohistorylog_resp = $this->itam->prpohistorylog($historyoptions);
            $data['prpohistorylog'] = isset($prpohistorylog_resp['content']) ? $prpohistorylog_resp['content'] : null;

            $assetoptions = [
                'form_params' => array('pr_po_id' => $po_id, 'asset_type' => 'po')];
            $assetdetails_resp = $this->itam->prpoassetdetails($assetoptions);

            $data['assetdetails'] = isset($assetdetails_resp['content']) ? $assetdetails_resp['content'] : null;

            $data['formAction'] = "edit";
            $html = view("Cmdb/purchaseorderadd", $data);
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

        $data['pageTitle'] = trans('title.purchase');
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
        $po_id = _isset($this->request_params, 'po_id', '');
        $invoice_id = _isset($this->request_params, 'invoice_id', '');
        $options['po_id'] = $po_id;
        $options['invoice_id'] = $invoice_id;
        $invoice_resp = $this->itam->purchaseinvoices(array('form_params' => $options));
        $invoice_data = isset($invoice_resp['content'][0]) ? $invoice_resp['content'][0] : null;
        return json_encode($invoice_data);
    }
    public function getnotifications()
    {
        /*$pr_po_id = _isset($this->request_params, 'pr_po_id', '');
        $history_type = _isset($this->request_params, 'history_type', '');
        $options['pr_po_id'] = $pr_po_id;
        $options['history_type'] = $history_type;*/
        $user_id = showuserid();
        $options['user_id'] = $user_id;
        $notify_resp = $this->itam->getnotifications(array('form_params' => $options));
        $notify_data = isset($notify_resp['content'][0]) ? $notify_resp['content'] : null;
        $notify_dataArr = array();
        $notify_data_result = "";

        if ($notify_data) {
            foreach ($notify_data as $notification) {
                if ($notification['history_type'] == "pr") {
                    $purchase_type = "Purchase Request";
                    $prpoList = "prlist";
                } else {
                    $purchase_type = "Purchase Order";
                    $prpoList = "polist";
                }

                $notify_data_result .= '<li data-id=' . $notification['pr_po_id'] . ' class=" ' . $prpoList . ' br-t of-h notificationmsg"> <a href="#" class="fw600 p12 animated animated-short fadeInDown">Your approval is required for the ' . $purchase_type . ' ##' . @$notification['title'] . '## <span class="mv15 floatright" style="color: #999;">on ' . date("d F Y : H:i A", strtotime($notification['created_at'])) . '</span></a> </li>';
            }
            return json_encode(array("result" => $notify_data_result));
        } else {
            return json_encode(array("result" => "<li class='br-t of-h notificationmsg fw600 p12'>NO Notifications</li>"));
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
            $attach_id = _isset($this->request_params, 'attach_id');
            $attach_path = _isset($this->request_params, 'attach_path');
            $msg = "";
            $content = "";
            $extention = "txt";
            $is_error = false;
            $file_created = false;
            $user_id = showuserid();
            $download_dir = public_path() . '/download/temp';
            $download_fp = public_path() . '/download/temp/tmp_' . $user_id;
            $user_down_fp = 'download/temp/tmp_' . $user_id;

            $form_params['attach_id'] = $attach_id;
            $form_params['attach_path'] = $attach_path;
            $options = ['form_params' => $form_params];

            $response = $this->itam->downloadattachment_pr($options);
            -$get_data = _isset($response, 'content');
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
                $response["html"] = '';
                $response["is_error"] = true;
                $response["msg"] = 'error';
            } else {
                $response["html"] = $user_down_fp . '.' . $extention;
                $response["is_error"] = '';
                $response["msg"] = 'success';
            }

        } catch (\Exception $e) {
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            save_errlog("downloadattachment_pr", "This controller function is implemented to download attachment.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
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
            $response = $this->itam->poinvoicedelete(array('form_params' => $request->all()));
        } catch (\Exception $e) {
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            save_errlog("poinvoicedelete", "This controller function is implemented to delete po invoice.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            save_errlog("poinvoicedelete", "This controller function is implemented to delete po invoice.", $this->request_params, $e->getmessage());
        } finally {
            echo json_encode($response);
        }
    }
    /**
     * Function to change status
     * @author Rahul Badhe
     * @access public
     * @package PurchaseOrder
     * @return json
     */
    public function resendtoapproval(Request $request)
    {
        try {
            $response = $this->itam->resendtoapproval(array('form_params' => $request->all()));
            /* print_r($response);
        exit;*/
        } catch (\Exception $e) {
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            save_errlog("poinvoicedelete", "This controller function is implemented to resedn approval to rejecte status.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            save_errlog("poinvoicedelete", "This controller function is implemented to resedn approval to rejecte status.", $this->request_params, $e->getmessage());
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
