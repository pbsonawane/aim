<?php

namespace App\Http\Controllers\Cmdb;

use App\Http\Controllers\Controller;
use App\Libraries\digilocker_ecos\eCos\eCos;
use App\Libraries\Emlib;
use App\Libraries\Maillib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use Illuminate\Http\Request;
use Redirect;
use Validator;
use View;
use DateTime;

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
    public function purchaserequests(Request $request)
    {
        // Sync Reqeuster Data Start
        $options = ['form_params' => array('user_id' => showuserid())];
        $user_details = $this->iam->getuserprofile($options);
        
        $user_id = $user_details['content'][0]['user_id'];
        $parent_id = $user_details['content'][0]['parent_id'];
        $firstname = $user_details['content'][0]['firstname'];
        $lastname = $user_details['content'][0]['lastname'];
        $emp_id = $user_details['content'][0]['emp_id'];
        $department_id = $user_details['content'][0]['department_id'];
        $status = $user_details['content'][0]['status'];
       
        if (!empty($emp_id) && !empty($department_id)) {
            $form_params['user_id'] = $user_id;
            $form_params['parent_id'] = $parent_id;
            $form_params['firstname'] = $firstname;
            $form_params['lastname'] = $lastname;
            $form_params['emp_id'] = $emp_id;
            $form_params['department_id'] = $department_id;
            $form_params['status'] = $status;
            $options = ['form_params' => $form_params];
            
            $requestername_resp = $this->itam->syncrequesteruser($options);
            
        }
        
        // Sync Reqeuster Data END

        $topfilter = array('gridsearch' => true, 'gridadvsearch' => true, 'jsfunction' => 'prList() , prDetailsLoad()');

        $data['emgridtop'] = $this->emlib->emgridtop($topfilter, '', ['users', 'vendors', 'datesearch']);
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
            $user_id = _isset($this->request_params, 'user_id');
            $vendor_id = _isset($this->request_params, 'vendor_id');
            $timerange = _isset($this->request_params, 'timerange');
            $customtime = _isset($this->request_params, 'customtime');
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
            $form_params['user_id'] = $user_id;
            $form_params['vendor_id'] = $vendor_id;
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
            $role_id = json_decode($pos[0]['role_id']);
            $role_id = $role_id[0];
            
            // store dept id: 29c1f8e4-1acf-11ec-b0ba-4e89be533080

            $options_history = ['form_params' => array()];

            /* $response_historyuser = $this->iam->getUsers($options_history);
            print_r($response_historyuser);
            exit;*/
            if (!$request->session()->has('issuperadmin')) {

                $option['form_params'] = array('advusertype' => "staff");
                $getUsers = $this->iam->getUsers($option);

                // purchase team dept id: fb1ff49a-201a-11ec-956c-4e89be533080
                // role_id departmnet head: de3451b2-0adc-11ec-abff-4e89be533080
                // Purchase head role : cfe061c6-2019-11ec-8142-4e89be533080

                // $role_ids = array("de3451b2-0adc-11ec-abff-4e89be533080", "cfe061c6-2019-11ec-8142-4e89be533080","9a9610ac-e61f-11ec-9d86-86bd6599c53f","b7c6e2d4-e63b-11ec-8418-86bd6599c53f");
                $role_ids = array("de3451b2-0adc-11ec-abff-4e89be533080", "12ceb012-6f81-11ec-9c34-92ff989c7103", "9a9610ac-e61f-11ec-9d86-86bd6599c53f", "b7c6e2d4-e63b-11ec-8418-86bd6599c53f");

                if ($department_id != '29c1f8e4-1acf-11ec-b0ba-4e89be533080' && $department_id != 'fb1ff49a-201a-11ec-956c-4e89be533080' && $department_id != '627dc11c-e63b-11ec-a33e-86bd6599c53f' && $department_id != '58132120-e61f-11ec-b010-86bd6599c53f' && !in_array($role_id, $role_ids)) {

                    $form_params['requester_id'] = showuserid();
                } elseif (in_array($role_id, $role_ids) && $department_id != 'fb1ff49a-201a-11ec-956c-4e89be533080') {

                    $option['form_params'] = array('advusertype' => "staff");
                    $getUsers = $this->iam->getUsers($option);
                    $getUsers = _isset(_isset($getUsers, 'content'), 'records');
                    $team_members = array_column($getUsers, 'user_id');
                    $form_params['requester_id'] = $team_members;
                }
                // store dept id: 29c1f8e4-1acf-11ec-b0ba-4e89be533080
                elseif ($department_id == '29c1f8e4-1acf-11ec-b0ba-4e89be533080') {
                    $form_params['dept_type'] = 'store';
                    $option['form_params'] = array('advusertype' => "staff");
                    $getUsers = $this->iam->getUsers($option);
                    $getUsers = _isset(_isset($getUsers, 'content'), 'records');
                    $team_members = array_column($getUsers, 'user_id');
                    $form_params['requester_id1'] = $team_members;

                }
                // payment committee dept id: 627dc11c-e63b-11ec-a33e-86bd6599c53f
                elseif ($department_id == '627dc11c-e63b-11ec-a33e-86bd6599c53f') {
                    $form_params['dept_type'] = 'payment_committee';
                } elseif ($department_id == '58132120-e61f-11ec-b010-86bd6599c53f') {
                    $form_params['dept_type'] = 'internal_auditor';
                }
                // purchase team dept id: fb1ff49a-201a-11ec-956c-4e89be533080
                elseif ($department_id == 'fb1ff49a-201a-11ec-956c-4e89be533080') {
                    $form_params['dept_type'] = 'purchase';
                    //Purchase Team Lead = 2b42af0e-0adc-11ec-b893-4e89be533080
                    $flag = false;
                    $arr = array('2b42af0e-0adc-11ec-b893-4e89be533080', '6b21d406-4cab-11ea-8db0-c281e8a6eb02');
                    if (!in_array($designation_id, $arr)) {
                        $form_params['flag'] = true;
                        $form_params['user_id'] = showuserid();
                        $form_params['designation_id'] = $designation_id;
                    }
                }

            }
            
            $options = ['form_params' => $form_params];
            $pos_resp = $this->itam->purchaserequests($options);

            // Check PO is Created Or Not Start
            $extraarray = $pos_resp['content']['records'];
            $pr_id_arrays = array();
            if (!empty($extraarray)) {
                $pr_id_arrays = array_column($extraarray, 'pr_id');
                $options = [
                    'form_params' => array('pr_ids' => $pr_id_arrays)];
                
                $pr_ids = $this->itam->checkPOisGeneratedOrNot($options);
                $pr_id_arrays = array_column($pr_ids['content'], 'difference_prpo_asset_count', 'pr_id');
 
            }
            
            // Get PR Asset Details                
            // $pr_id_asset_array = array();
            // if(!empty($pr_id_arrays))
            // {
            //     foreach($pr_id_arrays as $rowprid)
            //     {
            //         $assetoptions = ['form_params' => array('pr_po_id' => $rowprid, 'asset_type' => 'pr')];
            //         $assetdetails_resp = $this->itam->prpoassetdetails($assetoptions);
            //         print_r($assetdetails_resp);
            //     }
            // }
            // //
            // die;
            // Check PO is Created Or Not End
            // exit;
            
            $po_id = "";
            if ($pos_resp['is_error']) {
                $is_error = $pos_resp['is_error'];
                $msg = $pos_resp['msg'];
            } else {
                $pos['data'] = _isset(_isset($pos_resp, 'content'), 'records');
                // print_r($pos);die;
                $paging['total_rows'] = _isset(_isset($pos_resp, 'content'), 'totalrecords');
                $paging['showpagination'] = true;
                $paging['jsfunction'] = 'prList()';
                $view = 'Cmdb/purchaserequestlist';
                $po_id = isset($pos['data'][0]['pr_id']) ? $pos['data'][0]['pr_id'] : "";
                $pos['pr_id_status'] = $pr_id_arrays;
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
        $pr_assign_user_id_and_name = explode("~", $request['pr_assign_user_id']);
        $request['pr_assign_user_id'] = $pr_assign_user_id_and_name[0];
        $request['pr_assign_user_name'] = $pr_assign_user_id_and_name[1];
        $request['user_id'] = showuserid();

//echo "<pre>";print_r($request);
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
     * @author Rahul badhe
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

                // echo "<pre>";
                // $requester_name_details = $prs_resp['content']['records'][0]['requester_name_details'];
                // die;

                

                // vendor list for shows in dropdown
                $options = array();
                // $options = ['form_params' => $form_params];

                $vendor_resp = $this->itam->getvendors($options);
                $vendors = _isset(_isset($vendor_resp, 'content'), 'records');
                $data['vendors'] = $vendors;

                $data['pr_first_detail'] = isset($prs_resp['content']['records'][0]) ? $prs_resp['content']['records'][0] : null;

                $pr_po_id = isset($prs_resp['content']['records'][0]) ? $prs_resp['content']['records'][0]['pr_id'] : null;
                $assetoptions = ['form_params' => array('pr_po_id' => $pr_po_id, 'asset_type' => 'pr')];

                // print_r("Nick");die;
                $assetdetails_resp = $this->itam->prpoassetdetails($assetoptions);
                
                // print_r($assetdetails_resp);die;
                // Get Vendors from quotation Start
                $assetoptions = ['form_params' => array('pr_po_id' => $pr_po_id)];
                $vendorInPrQuotations = $this->itam->getvendorsinquotation($assetoptions);
                if (!empty($vendorInPrQuotations['content'])) {
                    $strs = str_replace("[", "", $vendorInPrQuotations['content'][0]['VendorId']);
                    $strs = str_replace("]", "", $strs);
                    $strs = str_replace('"', "", $strs);
                    $vendorarray = explode(", ", $strs);
                    $data['vendorInPrQuotations'] = isset($vendorInPrQuotations['content']) ? $vendorarray : null;
                } else {
                    $data['vendorInPrQuotations'] = null;
                }

                // Get Vendors from quotation End

                // inStoreAssetCount Start
                // $options = [
                //   'form_params' => array('asset_status' => 'in_store')];
                // $asset_count = $this->itam->inStoreAssetCount($options);
                // $asset_counts_instock = array_column($asset_count['content'],'asset_count','asset_sku');
                // $data['asset_counts_instock'] = $asset_counts_instock;
                // inStoreAssetCount End
                
                $data['assetdetails'] = isset($assetdetails_resp['content']) ? $assetdetails_resp['content'] : null;
                
                if($data['assetdetails']!=null)
                {
                  $asset_skus = array_unique(array_column(array_map(function ($itemarr) {
                    return json_decode($itemarr, true);}, array_column($data['assetdetails'], 'asset_details')), 'asset_sku'));                
                }else{
                  $asset_skus = array();
                }
                
                    $asset_skus_arr = ['form_params' => array('asset_skus' => $asset_skus)];
                $asset_in_stock_data = $this->itam->getassetsbyskus($asset_skus_arr);
                
                $asset_in_stock_data = isset($asset_in_stock_data['content']) ? $asset_in_stock_data['content'] : null;
                $new_asset = [];
                if (!empty($asset_in_stock_data)) {
                    foreach ($asset_in_stock_data as $v) {
                        $new_asset[$v['asset_sku']] = ['total_assets' => $v['total_assets'], 'ci_templ_id' => $v['ci_templ_id']];
                    }
                }
                // print_r($new_asset);die;
                $data['asset_in_stock_data'] = $new_asset;

                /*$data['asset_in_stock_data'] = array_column($asset_in_stock_data,'total_assets','asset_sku');
                 */
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

                        if($user_id == "")
                        {
                            continue;
                        }

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
                $options = ['form_params' => array()];
                $allUsers = $this->iam->getUsers($options);

                $data['allUsers'] = _isset(_isset($allUsers, 'content'), 'records');

                if (!empty($data['prpohistorylog'])) {
                    foreach ($data['prpohistorylog'] as $key => $history) {
                        if($history['created_by'] == "")
                        {
                            continue;
                        }
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
                        if($user_id == "")
                        {
                            continue;
                        }
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

                $form_params['pr_po_id'] = $pr_po_id;
                $options = ['form_params' => $form_params];
                $data['quotation_comparison_details'] = $this->itam->quotation_comparison_details($options);
                $data['isEditOpen'] = "OpenEdit";
                $loginUserId = showuserid();

                if ($data['pr_first_detail']['requester_id'] == $loginUserId) {
                    $data['isEditOpen'] = "CloseEdit";
                }
                $option_user = array('form_params' => array('user_id' => showuserid()));
                $userdata = $this->iam->getUsers($option_user);
                $data['loginUser'] = _isset(_isset($userdata, 'content'), 'records');
                
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

    /**
     * Function to return add purchase request form
     * @author Rahul badhe
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
            $approversDetails = _isset(_isset($approversDetails, 'content'), 'records');
            $data['approversDetails'] = $approversDetails;

            $data['formAction'] = "add";
            $option_user = array('form_params' => array('user_id' => showuserid()));
            $userdata = $this->iam->getUsers($option_user);
            
           
            $dept = $this->iam->getDepartment($option_user);
            
            $user_id = _isset(_isset($userdata, 'content'), 'records');

            if($user_id[0]['department_name'] == "")
            {
                $user_id[0]['department_name'] = "IDX";
            }

            if($user_id[0]['department_id'] == "")
            {
                $user_id[0]['department_id'] = "da8570a6-e7ad-11ec-8c9f-86bd6599c53f";
            }
            // IDX
            // da8570a6-e7ad-11ec-8c9f-86bd6599c53f

            $department_name = $user_id[0]['department_name'];
            $data['user_id'] = $user_id[0]['user_id'];
            $data['pr_department'] = $department_name;
            $data['pr_department_id'] = $user_id[0]['department_id'];

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
     * @author Rahul badhe
     * @access public
     * @package PurchaseOrder
     * @param  string $first_pr_id
     * @return string
     */
    public function purchaserequestaddsample()
    {
        try {

            $inputdata = array('template_name' => 'purchaserequestsample');
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

            $html = view("Cmdb/purchaserequestaddsample", $data);
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
     * @author Rahul badhe
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
            $data['pr_department_id'] = $user_id[0]['department_id'];
            $data['user'] = $user_id[0];

            $data['user_id'] = $purchaserequestdetail['approval_details']['confirmed'][0];
            $data['formAction'] = "edit";
            $data['pr_id'] = $pr_id;

            // print_r($data);die;
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
     * @author Rahul badhe
     * @access public
     * @package PurchaseOrder
     * @return json
     */
    

    // 
    public function getAssignAsset(Request $request)
    {
        $userId = $request->input('userId');
        $option = array('form_params' => array('userId' => $userId));    
        
        $assetDetails = $this->itam->getIssueAsset($option);     
        
        $assetDetailsArr = _isset($assetDetails, 'content');
        
        $assetDetailsOptions = "<option value=''>Select Asset</option>";
        if ($assetDetailsArr) {
            foreach ($assetDetailsArr as $asset) {                
                $assetDetailsOptions .= "<option value='" . $asset['asset_id'] . "'>" . $asset['display_name'] . "</option>";                
            }
        }        
        $data['getAssignAsset'] = $assetDetailsOptions;
        return json_encode($data);
    }

    public function complaintRaisedAdd(Request $request)
    {
        $option_user = array('form_params' => array('user_id' => showuserid()));        
        $userdata = $this->iam->getUsers($option_user);
        $dept = $this->iam->getDepartment($option_user);
        $user_id = _isset(_isset($userdata, 'content'), 'records');
        $data['department_name'] = $user_id[0]['department_name'];
        $data['user_ids'] = $user_id[0]['user_id'];
        $data['parent_ids'] = $user_id[0]['parent_id'];
        $data['requester_id'] = $request['pr_requester_name'];
        $data['priority'] = $request['priority'];
        $data['problemdetail'] = $request['problemdetail'];
        $data['asset_id'] = $request['asset'];
        
        $data['complaint_raised_no'] = generatecrnumber();
        $data['complaint_raised_date'] = date('Y-m-d H:i:s');
        
        
        if (isset($_FILES['browseFile'])) {            
            $file_ext = 'jpeg';
            $name1 = $_FILES["browseFile"]["name"];
            $arr = explode('.', $name1);
            if (count($arr) > 1) {
                $file_ext = $arr[1];
            }
            $files_content = base64_encode(file_get_contents($_FILES['browseFile']['tmp_name']));                
            $data['file_ext'] = $file_ext;
            $data['file'] = $files_content;
            $data['file_name'] = $_FILES['browseFile']['name'];
            $data['size'] = $_FILES['browseFile']['size'];              
        }
        $options = ['form_params' => $data];
        
        $dataResult = $this->itam->complaintRaisedAdd($options);
        print_r($dataResult);
    }
    // 

    public function getPurchaseRenderFormData(Request $request)
    {

        $vendore_id = $request->input('vendor_id');

        $option = array();
        $vendorsDetails = $this->itam->getvendors($option);
        $vendorsDetailsArr = _isset(_isset($vendorsDetails, 'content'), 'records');
        $vendorsDetailsOptions = "<option value=''>[" . trans('label.lbl_selectvendor') . "]</option>";
        if ($vendorsDetailsArr) {
            foreach ($vendorsDetailsArr as $vendor) {
                if (!empty($vendore_id)) {
                    if ($vendor['vendor_id'] == $vendore_id) {
                        $vendorsDetailsOptions .= "<option selected value='" . $vendor['vendor_id'] . "'>" . $vendor['vendor_name'] . "</option>";
                        break;
                    }
                } else {
                    $vendorsDetailsOptions .= "<option selected value='" . $vendor['vendor_id'] . "'>" . $vendor['vendor_name'] . "</option>";
                }
            }
        }

        /*$option                   = array();
        $costcenterDetails        = $this->itam->getcostcenters($option);
        $costcenterDetailsArr     = _isset(_isset($costcenterDetails, 'content'), 'records');
        $costcenterDetailsOptions = "<option value=''>[" . trans('label.lbl_selectcostcenter') . "]</option>";
        if ($costcenterDetailsArr) {
        foreach ($costcenterDetailsArr as $cc) {
        $costcenterDetailsOptions .= "<option value='" . $cc['cc_id'] . "'>" . $cc['cc_code'] . "-" . $cc['cc_name'] . "</option>";
        }
        }*/

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
        $option_user = array('form_params' => array('user_id' => showuserid()));
        $userdata = $this->iam->getUsers($option_user);
        $user_id = _isset(_isset($userdata, 'content'), 'records');
        $option = array('form_params' => array('department_id' => $user_id[0]['department_id']));
        $requesternameDetails = $this->itam->getrequesternames($option);
        $requesternameDetailsArr = _isset(_isset($requesternameDetails, 'content'), 'records');
        $requesternameDetailsOptions = "<option value=''>[" . trans('label.lbl_selectrequestername') . "]</option>";
        if ($requesternameDetailsArr) {
            foreach ($requesternameDetailsArr as $requestername) {
                $requester_name = $requestername['fname'] . ' ' . $requestername['lname'];
                // $requestername_id = $requestername['requestername_id'] . "~" . $requestername['user_id']. "~" . $requestername['parent_id'];
                $requesternameDetailsOptions .= "<option value='" . $requestername['requestername_id'] . "'>" . $requester_name . "</option>";
            }
        }

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
        $shiptoContactDetails = $this->itam->getcontacts($option);
        $shiptoContactDetailsArr = _isset(_isset($shiptoContactDetails, 'content'), 'records');

        $shiptoContactDetailsOptions = "<option value=''>[" . trans('label.lbl_selectshiptoContact') . "]</option>";
        $billtoContactDetailsOptions = "<option value=''>[" . trans('label.lbl_selectbilltoContact') . "]</option>";
        if ($shiptoContactDetailsArr) {
            foreach ($shiptoContactDetailsArr as $shiptoContact) {
                if ($shiptoContact['associated_with'] == 'Bill To') {
                    // $contact_name = $shiptoContact['prefix'] . '. ' . $shiptoContact['fname'] . ' ' . $shiptoContact['lname'];
                    $contact_name = $shiptoContact['fname'] . ' ' . $shiptoContact['lname'];
                    $billtoContactDetailsOptions .= "<option value='" . $shiptoContact['contact_id'] . "'>" . $contact_name . "</option>";
                } else {
                    // $contact_name = $shiptoContact['prefix'] . '. ' . $shiptoContact['fname'] . ' ' . $shiptoContact['lname'];
                    $contact_name = $shiptoContact['fname'] . ' ' . $shiptoContact['lname'];
                    $shiptoContactDetailsOptions .= "<option value='" . $shiptoContact['contact_id'] . "'>" . $contact_name . "</option>";
                }

            }
        }

        //============= Bill To Contact Master
        /* $option                      = array();

        $billtoContactDetailsArr     = _isset(_isset($billtoContactDetails, 'content'), 'records');
        $billtoContactDetailsOptions = "<option value=''>[" . trans('label.lbl_selectbilltoContact') . "]</option>";
        if ($billtoContactDetailsArr) {
        foreach ($billtoContactDetailsArr as $billtoContact) {
        $contact_name = $billtoContact['prefix'] . '. ' . $billtoContact['fname'] . ' ' . $billtoContact['lname'];
        $billtoContactDetailsOptions .= "<option value='" . $billtoContact['contact_id'] . "'>" . $contact_name . "</option>";
        }
        }
         */
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

        /* //============= Locations
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

        $pr_special_termsDetails = '1. On the receipt of this Purchase Order, the Supplier needs to provide an Acceptance in writing indicating the Delivery timelines. 2. If not accepted within 5 Days then PO should be considered as cancelled. 3. Delivery is a critical issue and no delay should be foreseen and the terms should be followed strictly. 4. The product supplied by the supplier will be strictly as per the technical specifications mentioned in the quotation document and email discussion. 5. If any deviation is foreseen technically, the equipment will be subject to immediate rejection. 6. If any loss or damage to the materials from when received then recovery from the Supplier will be done.';

        //$pr_special_termsDetails = 'asdf';
        /*$data['businessVerticalDetailsOptions'] = $businessVerticalDetailsOptions;
        $data['costcenterDetailsOptions']       = $costcenterDetailsOptions;
        $data['datacenterDetailsOptions']       = $datacenterDetailsOptions;
        $data['locationDetailsOptions']         = $locationDetailsOptions;
         */
        $data['vendorsDetailsOptions'] = $vendorsDetailsOptions;
        $data['shiptoDetailsOptions'] = $shiptoDetailsOptions;
        $data['shiptoContactDetailsOptions'] = $shiptoContactDetailsOptions;
        $data['billtoDetailsOptions'] = $billtoDetailsOptions;
        $data['billtoContactDetailsOptions'] = $billtoContactDetailsOptions;
        $data['deliveryDetailsOptions'] = $deliveryDetailsOptions;
        $data['paymenttermsDetailsOptions'] = $paymenttermsDetailsOptions;
        $data['pr_special_termsDetails'] = $pr_special_termsDetails;
        $data['requesternameDetailsOptions'] = $requesternameDetailsOptions;

        return json_encode($data);
    }

    /**
     * Function to save new or update existing purchase request
     * @author Rahul badhe
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
            //     print_r($inputdata);
            // exit;

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
                    $postData['asset_details']['item_product'][] = $inputdata['item_product'][$value];
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
            // ship to addresses set as As Per annaxure.
            $request['pr_shipto'] = 'ef8c00a6-0f12-11ec-b5b6-4a4901e9af12';
            $request['pr_shipto_contact'] = 'fd4e7acc-9881-11ec-b511-4eb834f5915d';

            $postData["details"] = json_encode($request);
            $postData["asset_details"] = json_encode($postData['asset_details']);
            // $postData["asset_details"] = $postData['asset_details'];
            $postData["pr_po_type"] = _isset($inputdata, 'pr_po_type', "pr");
            $postData["pr_no"] = generateprnumber();
            $postData["status"] = 'approved';

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

    public function getProject(Request $request)
    {        
        $opp_id = $request["opp_id"];
        $option_id = array('form_params' => array('opp_id' => $opp_id));     
        $getProject = $this->itam->getProject($option_id);
        $response = json_decode($getProject['content'], true);
        // $response['result']['project_name'];
        if ($response['result']['status'] == true) {
            echo json_encode($response['result']['project_name'], true);
        } else {
            $response['result']['project_name'] = "";
            echo json_encode($response['result']['project_name'], true);
        }
    }

    public function purchaserequestsave(Request $request)
    {

        try {
            $inputdata = $request->all();
            $item_item_data = array();
            $itemProductArray = array();
            $itemProductUnitArray = array();
            $item_product = array();
            $item_unit = array();
            $item_desc_data = array();
            $item_wsr_data = array();
            $item_qty_data = array();

            if ($request['formAction'] == 'add') {
                $item_item_data = array('item' => explode(",", $inputdata['item']));
                $itemProductDetail = explode(",", $inputdata['item_product']);
                if ($inputdata['item_product'] != '') {
                    foreach ($itemProductDetail as $itemPro) {
                        $ItemProd = explode("~", $itemPro);
                        array_push($itemProductArray, $ItemProd[0]);
                        array_push($itemProductUnitArray, $ItemProd[1]);
                    }
                } else {
                    array_push($itemProductArray, "");
                    array_push($itemProductUnitArray, "");
                }

                $item_product = array('item_product' => $itemProductArray);
                $item_unit = array('item_unit' => $itemProductUnitArray);
                // $item_product = array('item_product' => explode(",", $inputdata['item_product']));

                $item_desc_data = array('item_desc' => explode(",", $inputdata['item_desc']));
                $item_wsr_data = array('warranty_support_required' => explode(",", $inputdata['warranty_support_required']));
                $item_qty_data = array('item_qty' => explode(",", $inputdata['item_qty']));
            }else{
                if (strpos($inputdata['item'], ',') !== false) {
                    $item_item_data = array('item' => explode(",", $inputdata['item']));
                    $itemProductDetail = explode(",", $inputdata['item_product']);
                    if ($inputdata['item_product'] != '') {
                        foreach ($itemProductDetail as $itemPro) {
                            $ItemProd = explode("~", $itemPro);
                            array_push($itemProductArray, $ItemProd[0]);
                            array_push($itemProductUnitArray, $ItemProd[1]);
                        }
                    } else {
                        array_push($itemProductArray, "");
                        array_push($itemProductUnitArray, "");
                    }
    
                    $item_product = array('item_product' => $itemProductArray);
                    $item_unit = array('item_unit' => $itemProductUnitArray);
                    // $item_product = array('item_product' => explode(",", $inputdata['item_product']));
    
                    $item_desc_data = array('item_desc' => explode(",", $inputdata['item_desc']));
                    $item_wsr_data = array('warranty_support_required' => explode(",", $inputdata['warranty_support_required']));
                    $item_qty_data = array('item_qty' => explode(",", $inputdata['item_qty']));
                }
            }            
            
            $approvers_data = array();
            if ($inputdata['approvers'] != '') {
                $approvers_data = array('approvers' => explode(",", $inputdata['approvers']));
            }
            $approvers_optional_data = array();
            if ($inputdata['approvers_optional'] != '') {
                $approvers_optional_data = array('approvers_optional' => explode(",", $inputdata['approvers_optional']));
            }

            $postData['asset_details']['item'] = _isset($item_item_data, 'item', array());
            $postData['asset_details']['item_product'] = _isset($item_product, 'item_product', array());
            $postData['asset_details']['item_unit'] = _isset($item_unit, 'item_unit', array());
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
            unset($request['item_product']);
            unset($request['item_desc']);
            unset($request['item_qty']);
            unset($request['warranty_support_required']);
            unset($request['approvers']);
            $postData["pr_id"] = _isset($inputdata, 'pr_id', "");
            $postData['formAction'] = _isset($inputdata, 'formAction', "");
            $postData["details"] = json_encode($request->all());
            $postData["asset_details"] = json_encode($postData['asset_details']);
            $postData["pr_po_type"] = _isset($inputdata, 'pr_po_type', "pr");

            if ($inputdata['formAction'] == 'add') {
                $postData["customer_po_file_new"] = isset($_FILES["customer_po_file_new"]) ? $_FILES["customer_po_file_new"]["name"] : $request->customer_po_file_new;
                $postData["gc_approval_file_new"] = isset($_FILES["gc_approval_file_new"]) ? $_FILES["gc_approval_file_new"]["name"] : $request->gc_approval_file_new;
                $postData["costing_details_file_new"] = isset($_FILES["costing_details_file_new"]) ? $_FILES["costing_details_file_new"]["name"] : $request->costing_details_file_new;
            } elseif ($inputdata['formAction'] == 'edit') {
                $postData["customer_po_file_new"] = 'customer_po_file_new';
                $postData["gc_approval_file_new"] = 'gc_approval_file_new';
                $postData["costing_details_file_new"] = 'costing_details_file_new';
            }

            if ($request['formAction'] == 'add') {
                $postData["pr_no"] = generateprnumber();
            }

            // Get Department ID
            $option_user = array('form_params' => array('user_id' => showuserid()));
            $userdata = $this->iam->getUsers($option_user);
            $user_id = _isset(_isset($userdata, 'content'), 'records');
            
            $department_id = $user_id[0]['department_id'];

            if($user_id[0]['department_id'] == "")
            {
                $department_id = "da8570a6-e7ad-11ec-8c9f-86bd6599c53f";
            }

            if($user_id[0]['user_id'] != "7117a498-41c3-11ea-9e9a-0242ac110003")
            {
                // Get Department balanced_budget
                $option_id = array('form_params' => array('department_id' => $department_id));
                $department_arr = $this->iam->editDepartment($option_id);
                $department_data = _isset($department_arr, 'content');
                $postData['balanced_budget'] = $department_data[0]['balanced_budget'];
            }else{                
                $postData['balanced_budget'] = "100000000000";
            }

            
            $data = $this->itam->purchaserequestsave(array('form_params' => $postData));
            // echo '<pre>';
            // print_r($data);exit;
            $last_insert_id = $data['content']['insert_id'];

            /* Pr File Upload Code */
            if (isset($_FILES['customer_po_file_new']) && isset($last_insert_id)) {
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
                $form_params['file_title'] = 'Customer Po';
                $options = ['form_params' => $form_params];
                $pos_resp = $this->itam->fileupload_pr_extra($options);

            }
            if (isset($_FILES['gc_approval_file_new']) && isset($last_insert_id)) {
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
                $form_params['file_title'] = 'GC Approval';
                $options = ['form_params' => $form_params];
                $pos_resp = $this->itam->fileupload_pr_extra($options);

            }
            if (isset($_FILES['costing_details_file_new']) && isset($last_insert_id)) {
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
                $form_params['file_title'] = 'Costing Details Against the Requirement';
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

    // Sample PR
    public function purchaserequestsavesample(Request $request)
    {
        try {
            $inputdata = $request->all();

            //$item_item_data = array('item' => explode(",", $inputdata['item']));
            $item_product = array('item_product' => explode(",", $inputdata['item_product']));
            $item_desc_data = array('item_desc' => explode(",", $inputdata['item_desc']));
            $item_wsr_data = array('warranty_support_required' => explode(",", $inputdata['warranty_support_required']));
            $item_qty_data = array('item_qty' => explode(",", $inputdata['item_qty']));
            /* $approvers_data = array();
            if ($inputdata['approvers'] != '') {
            $approvers_data = array('approvers' => explode(",", $inputdata['approvers']));
            }
            $approvers_optional_data = array();
            if ($inputdata['approvers_optional'] != '') {
            $approvers_optional_data = array('approvers_optional' => explode(",", $inputdata['approvers_optional']));
            }*/

            /*  $postData['asset_details']['item']                      = _isset($item_item_data, 'item', array());*/
            $postData['asset_details']['item_product'] = _isset($item_product, 'item_product', array());
            $postData['asset_details']['item_desc'] = _isset($item_desc_data, 'item_desc', array());
            $postData['asset_details']['warranty_support_required'] = _isset($item_wsr_data, 'warranty_support_required', array());
            $postData['asset_details']['item_qty'] = _isset($item_qty_data, 'item_qty', array());
            $postData["approval_req"] = _isset($inputdata, 'approval_req', "n");
            $postData["form_templ_id"] = _isset($inputdata, 'form_templ_id', "");
            $postData["urlpath"] = _isset($inputdata, 'urlpath', "purchaserequest");
            $postData["form_templ_type"] = _isset($inputdata, 'form_templ_type', "default");
            $postData["requester_id"] = _isset($inputdata, 'requester_id', showuserid());
            $postData["status"] = 'approved';

            //$postData["approved_status"]   = array(); // On Edit - "approved_status" == NULL Means Open For reapproval all approvers and "status" change to "pending approval " on lumen side.
            // $approval_details['confirmed'] = _isset($approvers_data, 'approvers', array());
            // $approval_details['optional']  = _isset($approvers_optional_data, 'approvers_optional', array());
            // $postData['approval_details']  = json_encode($approval_details);
            unset($request['approval_req']);
            unset($request['status']);
            unset($request['action']);
            unset($request['form_templ_id']);
            unset($request['item']);
            unset($request['item_product']);
            unset($request['item_desc']);
            unset($request['item_qty']);
            unset($request['warranty_support_required']);
            unset($request['approvers']);
            $postData["pr_id"] = _isset($inputdata, 'pr_id', "");
            $postData['formAction'] = _isset($inputdata, 'formAction', "");
            $postData["details"] = json_encode($request->all());
            $postData["asset_details"] = json_encode($postData['asset_details']);
            $postData["pr_po_type"] = _isset($inputdata, 'pr_po_type', "pr");

            if ($inputdata['formAction'] == 'add') {
                $postData["customer_po_file_new"] = isset($_FILES["customer_po_file_new"]) ? $_FILES["customer_po_file_new"]["name"] : $request->customer_po_file_new;
                $postData["gc_approval_file_new"] = isset($_FILES["gc_approval_file_new"]) ? $_FILES["gc_approval_file_new"]["name"] : $request->gc_approval_file_new;
                $postData["costing_details_file_new"] = isset($_FILES["costing_details_file_new"]) ? $_FILES["costing_details_file_new"]["name"] : $request->costing_details_file_new;
            } elseif ($inputdata['formAction'] == 'edit') {
                $postData["customer_po_file_new"] = 'customer_po_file_new';
                $postData["gc_approval_file_new"] = 'gc_approval_file_new';
                $postData["costing_details_file_new"] = 'costing_details_file_new';
            }

            if ($request['formAction'] == 'add') {
                //$postData["pr_no"] = generateprnumber();
            }

            $data = $this->itam->purchaserequestsavesample(array('form_params' => $postData));
            $last_insert_id = $data['content']['insert_id'];
            /*print_r($data);
            exit;*/
            /* Pr File Upload Code */
            if (isset($_FILES['customer_po_file_new']) && isset($last_insert_id)) {
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
                $form_params['file_title'] = 'Customer Po';
                $options = ['form_params' => $form_params];
                $pos_resp = $this->itam->fileupload_pr_extra($options);

            }
            if (isset($_FILES['gc_approval_file_new']) && isset($last_insert_id)) {
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
                $form_params['file_title'] = 'GC Approval';
                $options = ['form_params' => $form_params];
                $pos_resp = $this->itam->fileupload_pr_extra($options);

            }
            if (isset($_FILES['costing_details_file_new']) && isset($last_insert_id)) {
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
                $form_params['file_title'] = 'Costing Details Against the Requirement';
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
    /*------------- Quotation Comparison Functions Start -----------*/

    /* Show item (Edit) details when select item from details page */
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

    /* Each item Quotation Comparison add */
    public function quotation_vendor_cmp(Request $request)
    {
        try {
            $inputdata = $request->all();
            $showuserid = showuserid();
            $form_params['created_by'] = $showuserid;
            $form_params['pr_po_id'] = $inputdata['pr_po_id'];
            $form_params['selected_item_name'] = $inputdata['selected_item_name'];
            $form_params['selected_item_id'] = $inputdata['selected_item_id'];
            //$form_params['quotation_comparison_data'] = json_encode($inputdata, true);

            $json_data = array();
            $vendor_i = 1;
            $vendor_count = count($inputdata['pr_vendor_id']);
            for ($k = 0; $k < $vendor_count; $k++) {
                $row_array = array();
                $pr_vendor_id = $inputdata['pr_vendor_id'][$k];
                if (!empty($pr_vendor_id) && $pr_vendor_id != '') {
                    for ($i = 1; $i <= 3; $i++) {
                        $common_data = array();
                        $common_data['qty_' . $i] = $inputdata['qty_' . $i][0];
                        $common_data['rate_' . $i] = $inputdata['rate_' . $i][$k];
                        $common_data['amount_' . $i] = $inputdata['amount_' . $i][$k];
                        $row_array[] = $common_data;
                    }
                    $row_array['quotation_reference_no'] = $inputdata['quotation_reference_no_' . ($k + 1)];
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

            }

            $vendor_arr = $inputdata['pr_vendor_id'];
            $approve_option = isset($inputdata['approve']) ? $inputdata['approve'] : 'NA'; // 0 / 1 / 2
            $approve_vendor_id = $showuserid;
            //if ($approve_option != 'NA') {
            for ($h = 0; $h < $vendor_count; $h++) {
                if ($approve_option == $h) {
                    $approve_vendor_id = $inputdata['pr_vendor_id'][$h];
                }
            }
            //}

            $form_params['approve_vendor_id'] = $approve_vendor_id;
            $form_params['approve_option'] = $approve_option;
            $form_params['quotation_comparison_data'] = json_encode($json_data, true);

            /*echo '<pre>';
            print_r($form_params);
            echo '</pre>';
            exit;*/

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

    /* Select items by vendors then submit Quotation Comparisons */
    public function final_quotation(Request $request)
    {
        try
        {
            $inputdata = $request->all();
            $temp_one = array();
            $showuserid = showuserid();
            $str_item_id = 0;

            foreach ($inputdata as $kay => $value) {
                $pr_po_id = $inputdata['pr_po_id'];

                if (strpos($kay, 'approve') !== false) {
                    $explode_arr = explode('##', $value);

                    $temp_two = array();

                    $temp_two['pr_po_id'] = $pr_po_id;
                    $temp_two['vendor_id'] = $str_vendor_id = $explode_arr[0];
                    $temp_two['item_id'] = $str_item_id = $explode_arr[1];
                    $temp_two['amount'] = $str_min_amount = $explode_arr[2];
                    $temp_two['qty'] = $str_qty = $explode_arr[3];
                    $temp_two['item_name'] = $str_item_name = $explode_arr[4];
                    $temp_two['rate'] = $str_display_rate = $explode_arr[5];

                    //$temp_one[] = $temp_two;

                    $form_params['selected_item_id'] = $explode_arr[1];
                    $form_params['created_by'] = $showuserid;
                    $form_params['vendor_approve'] = json_encode($temp_two);
                    $form_params['pr_po_id'] = $inputdata['pr_po_id'];

                    $options = ['form_params' => $form_params];
                    $data = $this->itam->quotation_comparison_final($options);

                    //echo '<pre>'; print_r($options); echo '</pre>';exit;
                }

            }

        } catch (\Exception $e) {
            $data["content"] = "";
            $data["is_error"] = "";
            $data["msg"] = $e->getmessage();
            $data["http_code"] = "";
        } catch (\Error $e) {
            $data["content"] = "";
            $data["is_error"] = "";
            $data["msg"] = $e->getmessage();
            $data["http_code"] = "";
        } finally {
            echo json_encode($data, true);
        }
    }

    /* Submit Quotation Comparison Approve */
    public function approve_quotation(Request $request)
    {
        try
        {
            $inputdata = $request->all();
            $temp_one = array();
            $showuserid = showuserid();
            $str_item_id = 0;
            foreach ($inputdata as $kay => $value) {
                $pr_po_id = $inputdata['pr_po_id'];

                if (strpos($kay, 'approve') !== false) {
                    $explode_arr = explode('##', $value);
                    $temp_two = array();

                    $temp_two['pr_po_id'] = $pr_po_id;
                    $temp_two['vendor_id'] = $str_vendor_id = $explode_arr[0];
                    $temp_two['item_id'] = $str_item_id = $explode_arr[1];
                    $temp_two['amount'] = $str_min_amount = $explode_arr[2];
                    $temp_two['qty'] = $str_qty = $explode_arr[3];
                    $temp_two['item_name'] = $str_item_name = $explode_arr[4];
                    $temp_two['rate'] = $str_display_rate = $explode_arr[5];

                    //$temp_one[] = $temp_two;

                    $form_params['selected_item_id'] = $explode_arr[1];
                    $form_params['created_by'] = $showuserid;
                    $form_params['vendor_approve'] = json_encode($temp_two);
                    $form_params['pr_po_id'] = $inputdata['pr_po_id'];
                    $form_params['approval'] = "Approve";
                    $form_params['reject_comment'] = "";
                    $form_params['approve_reject_by'] = $showuserid;

                    $options = ['form_params' => $form_params];
                    $data = $this->itam->quotation_comparison_approval($options);
                    //echo '<pre>'; print_r($options); echo '</pre>';//exit;
                }

            }

        } catch (\Exception $e) {
            $data["content"] = "";
            $data["is_error"] = "";
            $data["msg"] = $e->getmessage();
            $data["http_code"] = "";
        } catch (\Error $e) {
            $data["content"] = "";
            $data["is_error"] = "";
            $data["msg"] = $e->getmessage();
            $data["http_code"] = "";
        } finally {
            echo json_encode($data, true);
        }
    }

    /* Submit Quotation Comparison Reject Comment */
    public function prpoapprovereject_qc(Request $request)
    {

        try
        {
            $inputdata = $request->all();
            $showuserid = showuserid();
            $postData['created_by'] = $showuserid;
            $postData["pr_po_id"] = _isset($inputdata, 'pr_po_id', "");
            $postData["comment"] = _isset($inputdata, 'comment', "");
            $postData["approval_status"] = _isset($inputdata, 'approval_status', "");
            /* echo '<pre>'; print_r($postData); echo '</pre>';
            exit;*/

            $data = $this->itam->prpoapprovereject_qc(array('form_params' => $postData));
        } catch (\Exception $e) {
            $data["content"] = "";
            $data["is_error"] = "";
            $data["msg"] = $e->getmessage();
            $data["http_code"] = "";
            save_errlog("prpoapprovereject_qc", "This controller function is implemented to quotation comparison approve or reject.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {

            $data["content"] = "";
            $data["is_error"] = "";
            $data["msg"] = $e->getmessage();
            $data["http_code"] = "";
            save_errlog("prpoapprovereject_qc", "This controller function is implemented to quotation comparison approve or reject.", $this->request_params, $e->getmessage());
        } finally {
            echo json_encode($data, true);
        }
    }

    /*------------- Quotation Comparison Functions Close -----------*/

    /**
     * Function to approve or reject PR PO
     * @author Rahul badhe
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
            $postData["is_comment"] = _isset($inputdata, 'is_comment', "");

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

    public function save_estimatecost(Request $request)
    {
        try
        {
            $inputdata = $request->all();

            // Get Department ID
            $department_id = _isset($inputdata, 'pr_department_id', "");

            // Get Department balanced_budget
            $option_id = array('form_params' => array('department_id' => $department_id));
            $department_arr = $this->iam->editDepartment($option_id);
            $department_data = _isset($department_arr, 'content');

            $postData["balanced_budget"] = $department_data[0]['balanced_budget'];
            $postData['created_by'] = showuserid();
            $postData["pr_po_id"] = _isset($inputdata, 'pr_po_id', "");
            $postData["estimate_cost"] = _isset($inputdata, 'estimate_cost', "");

            // echo "<pre>";print_r($inputdata);echo "--------";print_r($postData);exit;

            $data = $this->itam->save_estimatecost(array('form_params' => $postData));

        } catch (\Exception $e) {
            $data["content"] = "";
            $data["is_error"] = "";
            $data["msg"] = $e->getmessage();
            $data["http_code"] = "";

            save_errlog("prpoapprovereject", "This controller function is implemented to estimate_cost.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {

            $data["content"] = "";
            $data["is_error"] = "";
            $data["msg"] = $e->getmessage();
            $data["http_code"] = "";

            save_errlog("prpoapprovereject", "This controller function is implemented to estimate_cost.", $this->request_params, $e->getmessage());
        } finally {
            echo json_encode($data, true);
        }
    }
    /**
     * Function to perform various actions on PR PO
     * @author Rahul badhe
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
            } else {
                /* For Notify */
                $postData["mail_notification_to"] = _isset($inputdata, 'mail_notification_to', "");
                $postData["mail_notification_subject"] = _isset($inputdata, 'mail_notification_subject', "");
                $postData["mail_notification"] = _isset($inputdata, 'mail_notification', "");
                $postData["notify_to_id"] = _isset($inputdata, 'notify_to_id', "");

                /* For Add Invoice */
                $postData["invoice_id"] = _isset($inputdata, 'invoice_id', "");
                $postData["formaction"] = _isset($inputdata, 'formaction', "");
                $postData["id"] = _isset($inputdata, 'id', "");
                $postData["received_date"] = _isset($inputdata, 'received_date', "");
                $postData["payment_due_date"] = _isset($inputdata, 'payment_due_date', "");

                $data = $this->itam->prpoformActions(array('form_params' => $postData));

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
    public function purchaseorders($id = '')
    {
        // $po_id      = _isset($this->request_params, 'po_id');
        $po_id = $id;
        if ($id == "") {
            $topfilter = array('gridsearch' => true, 'jsfunction' => 'poList() , poDetailsLoad()');
            $data['show_single'] = "false";
        } else {
            $topfilter = array('gridsearch' => false, 'jsfunction' => 'poList() , poDetailsLoad()');
            $data['show_single'] = "true";
        }
        $data['po_id'] = $po_id;
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter);
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
            $limit = _isset($this->request_params, 'limit', config('enconfig.def_limit_short'));
            $page = _isset($this->request_params, 'page', config('enconfig.page'));
            $searchkeyword = _isset($this->request_params, 'searchkeyword');
            $po_id = _isset($this->request_params, 'active_po_id');
            $show_single = _isset($this->request_params, 'show_single');

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

            $options = ['form_params' => $form_params];
            $pos_resp = $this->itam->purchaseorder($options);

            if ($pos_resp['is_error']) {
                $is_error = $pos_resp['is_error'];
                $msg = $pos_resp['msg'];
            } else {
                $pos = _isset(_isset($pos_resp, 'content'), 'records');

                if ($pos) {
                    foreach ($pos as $key => $po) {
                        $form_paramsother['bv_id'] = $po['details']['bv_id'];
                        $form_paramsother['dc_id'] = $po['details']['dc_id'];
                        $form_paramsother['location_id'] = $po['details']['location_id'];
                        $options = ['form_params' => $form_paramsother];
                        $pos_other_resp = $this->iam->getdclocationbv($options);
                        $bv_dc_loc_detail = _isset($pos_other_resp, 'content');
                        $pos[$key]['details']['bv_dc_loc_detail'] = $bv_dc_loc_detail;
                    }
                }

                $paging['total_rows'] = _isset(_isset($pos_resp, 'content'), 'totalrecords');
                if ($show_single == "true") {
                    $paging['showpagination'] = false;
                } else {
                    $paging['showpagination'] = true;
                }
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
     * @author Rahul badhe
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
                $data['pr_first_detail'] = isset($prs_resp['content']['records'][0]) ? $prs_resp['content']['records'][0] : null;

                $pr_po_id = isset($prs_resp['content']['records'][0]) ? $prs_resp['content']['records'][0]['po_id'] : null;

                $assetoptions = [
                    'form_params' => array('pr_po_id' => $pr_po_id, 'asset_type' => 'po')];
                $assetdetails_resp = $this->itam->prpoassetdetails($assetoptions);

                $data['assetdetails'] = isset($assetdetails_resp['content']) ? $assetdetails_resp['content'] : null;

                $receivedassetoptions = [
                    'form_params' => array('pr_po_id' => $pr_po_id, 'asset_type' => 'po')];
                $receivedassetdetails_resp = $this->itam->prpoassetdetails($receivedassetoptions);

                $data['receivedassetdetails'] = isset($receivedassetdetails_resp['content']) ? $receivedassetdetails_resp['content'] : null;

                $historyoptions = [
                    'form_params' => array('pr_po_id' => $pr_po_id, 'history_type' => 'po')];
                $prpohistorylog_resp = $this->itam->prpohistorylog($historyoptions);
                $data['prpohistorylog'] = isset($prpohistorylog_resp['content']) ? $prpohistorylog_resp['content'] : null;

                $invoiceoptions = [
                    'form_params' => array('po_id' => $pr_po_id)];
                $purchaseinvoices_resp = $this->itam->purchaseinvoices($invoiceoptions);
                $data['purchaseinvoices'] = isset($purchaseinvoices_resp['content']) ? $purchaseinvoices_resp['content'] : null;

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
                // print_r($data);
                $data['pr_first_detail']['approval_details_by_data'] = $approval_details_by_data;

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
     * @author Rahul badhe
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
     * @author Rahul badhe
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
            $historyoptions = [
                'form_params' => array('pr_po_id' => $pr_id, 'history_type' => 'pr')];
            $prpohistorylog_resp = $this->itam->prpohistorylog($historyoptions);
            $data['prpohistorylog'] = isset($prpohistorylog_resp['content']) ? $prpohistorylog_resp['content'] : null;

            $assetoptions = [
                'form_params' => array('pr_po_id' => $pr_id, 'asset_type' => 'pr')];
            $assetdetails_resp = $this->itam->prpoassetdetails($assetoptions);

            $data['assetdetails'] = isset($assetdetails_resp['content']) ? $assetdetails_resp['content'] : null;

            // print_r($data);
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
     * @author Rahul badhe
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

            $assetdetails = isset($assetdetails_resp['content']) ? $assetdetails_resp['content'] : null;
            $items_arr = [];
            if (!empty($assetdetails)) {
                foreach ($assetdetails as $prrecord) {
                    if (!empty($prrecord['pritems'])) {
                        $pritems = json_decode($prrecord['pritems'], true);
                        $i = 0;

                        foreach ($pritems as $val) {
                            $ship_to_other = $prrecord['ship_to_other'];
                            $item_product[] = $val['item_product'];
                            if (array_key_exists($val['item_product'], $items_arr)) {

                                $pr_id = $items_arr[$val['item_product']]['pr_id'];
                                $pr_no = $items_arr[$val['item_product']]['pr_no'];
                                $pr_no[$prrecord['pr_id']] = $prrecord['pr_no'];

                                $pr_shipto = $items_arr[$val['item_product']]['pr_shipto'];
                                $qty = $val['item_qty'];
                                if (!empty($items_arr[$val['item_product']]['pr_shipto'][$prrecord['pr_shipto']])) {

                                    $pr_shipto[$prrecord['pr_id']] = [
                                        'address_id' => $prrecord['pr_shipto'],
                                        'location' => (($ship_to_other != 'null') ? $ship_to_other : $addresses[$prrecord['pr_shipto']]), 'quantity' => $qty + $items_arr[$val['item_product']]['item_qty']];
                                } else {
                                    $pr_shipto[$prrecord['pr_id']] = ['address_id' => $prrecord['pr_shipto'], 'location' => (($ship_to_other != 'null') ? $ship_to_other : $addresses[$prrecord['pr_shipto']]), 'quantity' => $qty];
                                }
                                // $qty                                   = $items_arr[$val['item_product']]['item_qty'];

                                $q = $qty + $items_arr[$val['item_product']]['item_qty'];
                                $items_arr[$val['item_product']]['pr_id'] = $pr_id . ',' . $prrecord['pr_id'];
                                $items_arr[$val['item_product']]['item_qty'] = $q;
                                $items_arr[$val['item_product']]['warranty_support_required'] = $val['warranty_support_required'];
                                $items_arr[$val['item_product']]['item_desc'] = $val['item_desc'];
                                $items_arr[$val['item_product']]['item_product'] = $val['item_product'];
                                $items_arr[$val['item_product']]['item_id'] = $val['item'];
                                $items_arr[$val['item_product']]['pr_no'] = $pr_no;
                                $items_arr[$val['item_product']]['pr_shipto'] = $pr_shipto;
                                // $items_arr[$val['item_product']]['item_desc'] = $val['item_desc'];
                            } else {

                                $qty = $val['item_qty'];
                                $items_arr[$val['item_product']]['pr_id'] = $prrecord['pr_id'];
                                $items_arr[$val['item_product']]['item_qty'] = $qty;
                                $items_arr[$val['item_product']]['item_id'] = $val['item'];
                                $items_arr[$val['item_product']]['item_product'] = $val['item_product'];
                                $items_arr[$val['item_product']]['warranty_support_required'] = $val['warranty_support_required'];
                                $items_arr[$val['item_product']]['item_desc'] = $val['item_desc'];
                                $items_arr[$val['item_product']]['pr_no'] = array($prrecord['pr_id'] => $prrecord['pr_no']);
                                $items_arr[$val['item_product']]['pr_shipto'] =
                                array(
                                    $prrecord['pr_id'] => ['address_id' => $prrecord['pr_shipto'], 'location' => (($ship_to_other != 'null') ? $ship_to_other : $addresses[$prrecord['pr_shipto']]), 'quantity' => $qty]);
                                // $items_arr[$val['item']]['item_desc'] = $val['item_desc'];
                            }

                        }

                    }

                }
                $form_params['item_product_id'] = implode(',', $item_product);
                $options = ['form_params' => $form_params];
                $citemplates = $this->itam->getitembycategory($options);
                $data['products'] = _isset($citemplates, 'content');

                $data['items_arr'] = $items_arr;
            }
            // $option            = ['form_params'=>['wherein_ids'=>$item_product]];

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
     * @author Rahul badhe
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
            'file.*' => 'required|mimes:jpeg,jpg,png,pdf,doc,docx,csv,xlsx,xls',
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
     * @author Rahul badhe
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
     * @author Rahul badhe
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
            $postData['asset_details']['item_desc'] = _isset($inputdata, 'item_desc', array());
            $postData['asset_details']['item_qty'] = _isset($inputdata, 'item_qty', array());
            $postData['asset_details']['item_estimated_cost'] = _isset($inputdata, 'item_estimated_cost', array());
            $postData["approval_req"] = _isset($inputdata, 'approval_req', "n");
            $postData["form_templ_id"] = _isset($inputdata, 'form_templ_id', "");
            $postData["urlpath"] = _isset($inputdata, 'urlpath', "purchaserequest");
            //$postData["form_templ_type"] = _isset($inputdata,'form_templ_type', "default");
            $postData["requester_id"] = _isset($inputdata, 'requester_id', "");
            $postData["bv_id"] = _isset($inputdata, 'bv_id', "");
            $postData["dc_id"] = _isset($inputdata, 'dc_id', "");
            $postData["location_id"] = _isset($inputdata, 'location_id', "");
            $postData["po_name"] = _isset($inputdata, 'po_name', "");
            $postData["po_no"] = _isset($inputdata, 'po_no', "");
            $postData['formAction'] = _isset($inputdata, 'formAction', "");
            if ($postData['formAction'] == "add") {
                if ($postData["approval_req"] == "n") {
                    //$postData["status"] = 'open';
                    $postData["status"] = 'approved';
                } else {
                    $postData["status"] = _isset($inputdata, 'status', 'pending approval');
                }
            }

            $postData["approved_status"] = array();
            // On Edit - "approved_status" == NULL Means Open For reapproval all approvers and "status" change to "pending approval " on lumen side.

            $approval_details['confirmed'] = _isset($inputdata, 'approvers', array());
            $approval_details['optional'] = _isset($inputdata, 'approvers_optional', array());
            $postData['approval_details'] = json_encode($approval_details);
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
            $postData["pr_id"] = _isset($inputdata, 'pr_id', "");
            $postData["po_id"] = _isset($inputdata, 'po_id', "");
            $postData['formAction'] = _isset($inputdata, 'formAction', "");
            $postData["details"] = json_encode($request->all());

            $otherDetails = array(
                "discount_per" => _isset($inputdata, 'discount_per', ""),
                "discount_amount" => _isset($inputdata, 'discount_amount', ""),
            );
            $postData['other_details'] = json_encode($otherDetails);
            $postData["asset_details"] = json_encode($postData['asset_details']);
            $postData["pr_po_type"] = "po";

            $data = $this->itam->purchaseordersave(array('form_params' => $postData));
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
     * @author Rahul badhe
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
            // print_r($data);
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
     * @author Rahul badhe
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
     * @author Rahul badhe
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

            $attach_title = _isset($this->request_params, 'attach_title');

            $msg = "";
            $content = "";
            $extention = "txt";
            $is_error = false;
            $file_created = false;
            $user_id = showuserid();
            $download_dir = public_path() . '/download/temp';
            $download_fp = public_path() . '/download/temp/tmp_' . $user_id;
            $user_down_fp = 'download/temp/tmp_' . $user_id;

            if ($attach_title == 'CRM uploaded file') {
                $container_name = 'Opportunities';
                $ecos = new eCos();
                $ecos->getToken();
                $folder_name = ''; //UPLOAD_PIC;
                $responsedata = $ecos->downloadObject($container_name, $folder_name, $attach_path);
                // $finfo = finfo_open();
                // $mime_type = finfo_buffer($finfo, $downloadObject_resp, FILEINFO_MIME_TYPE);
                // header("Content-Type: " . $mime_type);
                // header('Content-Disposition: attachment; filename="' . $attach_path . '"');
                // force file download
                // $get_data = base64_decode($response, true);
                $get_data = $responsedata;

            } else {
                $form_params['attach_id'] = $attach_id;
                $form_params['attach_path'] = $attach_path;
                $options = ['form_params' => $form_params];

                $responsedata = $this->itam->downloadattachment_pr($options);

                $get_data = _isset($responsedata, 'content');
                $get_data = base64_decode($get_data, true);
            }

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
     * @author Rahul badhe
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
     * Function to download attachment
     * @author Rahul badhe
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

    public function sampleprexport()
    {

        $data = $this->itam->sampleprexportservice(array('form_params' => array()));
        $this->download_send_headers("pr_data_export_" . date("Y-m-d") . ".csv");
        echo $this->array2csv($data['content']);
        die();

    }
    public function array2csv(array &$array)
    {
        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $df = fopen("php://output", 'w');
        fputcsv($df, array_keys(reset($array)));
        foreach ($array as $row) {
            fputcsv($df, $row);
        }
        fclose($df);
        return ob_get_clean();
    }
    public function download_send_headers($filename)
    {
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    }

    public function trackpurchaserequest(Request $request)
    {
        try {
            $pos_resp = $this->itam->trackpurchaserequest($request->all());

            $content = ($pos_resp['is_error']) ? '' : $pos_resp;
            $response['includeView'] = view("Cmdb/track_orders", $content);
            $response["html"] = $pos_resp;
            $response['is_error'] = $pos_resp['is_error'];
            $response['msg'] = $pos_resp['msg'];
        } catch (\Exception $e) {
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            save_errlog("pocontroller", "This controller function is implemented to get list of PR.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            save_errlog("pocontroller", "This controller function is implemented to get list of PR.", $this->request_params, $e->getmessage());
        } finally {
            $response['pageTitle'] = "Track Orders";
            return view('template', $response);
        }
    }

    public function add_remark(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), ['pr_id' => 'required', 'add_remark' => 'required|max:250']);
            if ($validator->fails()) {
                $error = $validator->errors();
                $response['is_error'] = true;
                $response['msg'] = $error;
                $response['status'] = 'validation_error';
            } else {
                //GetCurrentUser
                $options = ['form_params' => array('user_id' => showuserid())];
                $user_details = $this->iam->getuserprofile($options);                
                $firstname = $user_details['content'][0]['firstname'];
                $lastname = $user_details['content'][0]['lastname']; 
                $request['requestername'] = $firstname ." ".$lastname;                
                // 
                $pos_resp = $this->itam->addremark(array('form_params' => $request->all()));
                $response['is_error'] = $pos_resp['is_error'];
                $response['msg'] = $pos_resp['msg'];
                $response['status'] = ($pos_resp['is_error']) ? 'error' : 'success';
            }
        } catch (\Exception $e) {
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            save_errlog("pocontroller", "This controller function is implemented to get list of PR.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            save_errlog("pocontroller", "This controller function is implemented to get list of PR.", $this->request_params, $e->getmessage());
        } finally {
            return response()->json($response);
        }
    }

      public function add_poremark(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), ['po_id' => 'required', 'add_remark' => 'required|max:250']);
            if ($validator->fails()) {
                $error = $validator->errors();
                $response['is_error'] = true;
                $response['msg'] = $error;
                $response['status'] = 'validation_error';
            } else {

                $pos_resp = $this->itam->add_poremark(array('form_params' => $request->all()));
                
                $response['is_error'] = $pos_resp['is_error'];
                $response['msg'] = $pos_resp['msg'];
                $response['status'] = ($pos_resp['is_error']) ? 'error' : 'success';
            }
        } catch (\Exception $e) {
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            save_errlog("pocontroller", "This controller function is implemented to get list of PR.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            save_errlog("pocontroller", "This controller function is implemented to get list of PR.", $this->request_params, $e->getmessage());
        } finally {
            return response()->json($response);
        }
    }

    public function track_pr_request()
    {
        $topfilter = array('gridsearch' => true, 'jsfunction' => 'trackprList()', 'gridadvsearch' => false);
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter, '', '');
        $data['pageTitle'] = "Track Purchase Request";
        $data['includeView'] = view("Cmdb/trackpr", $data);
        return view('template', $data);
    }

    public function track_po_order()
    {
      // print_r("data"); die(); 
        $topfilter = array('gridsearch' => true, 'jsfunction' => 'trackpoList()', 'gridadvsearch' => false);
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter, '', '');
        $data['pageTitle'] = "Track Purchase Request";
        $data['includeView'] = view("Cmdb/track_orders", $data);
        return view('template', $data);
    }

    public function track_pr_list(Request $request)
    {
        $save_param = array();
        try
        {
            $paging = array();
            $limit = _isset($this->request_params, 'limit', config('enconfig.def_limit'));
            $page = _isset($this->request_params, 'page', config('enconfig.page'));
            $searchkeyword = _isset($this->request_params, 'searchkeyword');
            $issuperadmin = _isset($this->request_params, 'issuperadmin');
            $user_id = _isset($this->request_params, 'user_id');
            $timerange = _isset($this->request_params, 'timerange');
            $customtime = _isset($this->request_params, 'customtime');
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

            $save_param = $form_params;
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
            // echo '<pre>';print_r($form_params);die;
            $options = ['form_params' => $form_params];
            $pos_resp = $this->itam->track_pr_list($options);
           
            $nick = 0;
            if ($pos_resp['is_error']) {
                $is_error = $pos_resp['is_error'];
                $msg = $pos_resp['msg'];
            } else {
                if (isset($pos_resp) && !empty($pos_resp)) {
                    if($pos_resp['content']['totalrecords'] > 0) {
                        foreach ($pos_resp['content']['records'] as $key => $value) {
                            $nick++; 
                            $assigned_pr_id = '';
                            $approval_details_json = isset($value['approval_details']) ? json_decode($value['approval_details'], true) : '';
                            $approved_status_json = isset($value['approved_status']) ? json_decode($value['approved_status'], true) : '';

                            $details_json = isset($value['details']) ? json_decode($value['details'], true) : '';

                            if($approval_details_json == null)
                            {
                                $pos_resp['content']['records'][$key]['pending_by']  = array();
                                continue;
                            }

                            if($approved_status_json == null) {
                                //pending for manager approval
                                $assigned_pr_id = isset($approval_details_json) ? $approval_details_json['confirmed'][0] : '';
                            } else if(count($approved_status_json) == 1 && empty($value['assignpr_user_id'])) {
                                //pending from store 
                                $assigned_pr_id = 'ad03914e-1ad0-11ec-b204-4e89be533080';
                            } else if(count($approved_status_json) > 1 && !empty($value['assignpr_user_id'])) {
                                //pending from purchase team 
                                $assigned_pr_id = isset($value['assignpr_user_id']) ? $value['assignpr_user_id'] : '';
                            }
                            if($assigned_pr_id == null || $assigned_pr_id == '') {
                                $pos_resp['content']['records'][$key]['pending_by'] = array();
                                continue;
                            }
                            $options_optional = ['form_params' => array('user_id' => $assigned_pr_id)];
            
                            $response_optional = $this->iam->getAllUsersWithoputPermission($options_optional);

                            $response_data = _isset(_isset($response_optional, 'content'), 'records');
                            
                            if (!(is_array($response_data) && count($response_data) > 0)) {
                                $response_data = array();
                                $response_data[0] = array();                                
                            }else{
                                $pos_resp['content']['records'][$key]['pending_by'] = $response_data[0];
                            }
                                                        
                            // $requester_id = $value['requester_id']; 
                            $requester_id = $details_json["pr_requester_name"];
                            
                            $reqsoptions_optional = ['form_params' => array('requestername_id' => $requester_id)];
                            $reqsresponse_optional = $this->itam->getrequesternames($reqsoptions_optional);
                            
                            $requester_ids = $reqsresponse_optional['content']['records']['0']['user_id'];
                            
                            $reqoptions_optional = ['form_params' => array('user_id' => $requester_ids,'emp_id' => "")];
                            print_r($reqoptions_optional);
                            print_r($nick);
                            $reqresponse_optional = $this->iam->getrequesteruser($reqoptions_optional);
                            
                            print_r($reqresponse_optional);die;
                            if (!(is_array($reqresponse_optional['content']['records'][0]) && count($reqresponse_optional['content']['records'][0]) > 0)) {
                                $reqresponse_optional = array();
                                $reqresponse_optional = array();                                
                                $pos_resp['content']['records'][$key]['requester_info'] = array();
                            }else{
                                $pos_resp['content']['records'][$key]['requester_info'] = $reqresponse_optional['content']['records'][0];
                            }

                        }
                    }
                }
                echo '<pre>';print_r($pos_resp);die;
                $is_error = false;
                $pos = _isset(_isset($pos_resp, 'content'), 'records');
                $paging['total_rows'] = _isset(_isset($pos_resp, 'content'), 'totalrecords');
                $paging['showpagination'] = true;
                $paging['jsfunction'] = 'trackprList()';
                $view = 'Cmdb/trackprlist';
                $content = $this->emlib->emgrid($pos, $view, array(), $paging);

                $response["html"] = $content;
                $response["is_error"] = $is_error;
                $response["msg"] = $msg;
                $response["option_param"] = $save_param;
                $response["totalrecords"] = _isset(_isset($pos_resp, 'content'), 'totalrecords');
            }
        } catch (\Exception $e) {
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            save_errlog("pocontroller", "This controller function is implemented to get TrackPR List.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            save_errlog("pocontroller", "This controller function is implemented to get TrackPR List.", $this->request_params, $e->getmessage());
        } finally {
            echo json_encode($response);
        }
    }

    public function track_po_list(Request $request)
    {
        
        $save_param = array();
        try
        {
            $paging = array();
            $limit = _isset($this->request_params, 'limit', config('enconfig.def_limit'));
            $page = _isset($this->request_params, 'page', config('enconfig.page'));
            $searchkeyword = _isset($this->request_params, 'searchkeyword');
            $issuperadmin = _isset($this->request_params, 'issuperadmin');
            $user_id = _isset($this->request_params, 'user_id');
            $timerange = _isset($this->request_params, 'timerange');
            $customtime = _isset($this->request_params, 'customtime');
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

            $save_param = $form_params;
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
            // echo '<pre>';print_r($form_params);die;
            $options = ['form_params' => $form_params];
            $pos_resp = $this->itam->track_po_list($options);
            
            if ($pos_resp['is_error']) {
                $is_error = $pos_resp['is_error'];
                $msg = $pos_resp['msg'];
            } else {
                if (isset($pos_resp) && !empty($pos_resp)) {
                    if($pos_resp['content']['totalrecords'] > 0) {
                        foreach ($pos_resp['content']['records'] as $key => $value) {
                           
                            $requester_id = $value['requester_id'];
                            $options_optional = ['form_params' => array('user_id' => $requester_id)];
            
                            $response_optional = $this->iam->getAllUsersWithoputPermission($options_optional);

                            $response_data = _isset(_isset($response_optional, 'content'), 'records');

                            if (!(is_array($response_data) && count($response_data) > 0)) {
                                $response_data = array();
                                $response_data[0] = array();
                                
                            }
                            $pos_resp['content']['records'][$key]['requester_info'] = $response_data[0];
                        }
                    }
                }
                //echo '<pre>';print_r($pos_resp);die;
                $is_error = false;
                $pos = _isset(_isset($pos_resp, 'content'), 'records');
                $paging['total_rows'] = _isset(_isset($pos_resp, 'content'), 'totalrecords');
                $paging['showpagination'] = true;
                $paging['jsfunction'] = 'trackprList()';
                $view = 'Cmdb/trackpolist';
                $content = $this->emlib->emgrid($pos, $view, array(), $paging);

                $response["html"] = $content;
                $response["is_error"] = $is_error;
                $response["msg"] = $msg;
                $response["option_param"] = $save_param;
                $response["totalrecords"] = _isset(_isset($pos_resp, 'content'), 'totalrecords');
            }
        } catch (\Exception $e) {
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            save_errlog("pocontroller", "This controller function is implemented to get TrackPR List.", $this->request_params, $e->getmessage());
        } catch (\Error $e) {
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            save_errlog("pocontroller", "This controller function is implemented to get TrackPR List.", $this->request_params, $e->getmessage());
        } finally {
            echo json_encode($response);
        }
    }

    public function Export_track_po(Request $request)
    {
        // export track po
        $paging = array();
        $limit = _isset($this->request_params, 'limit', config('enconfig.def_limit'));
        $page = _isset($this->request_params, 'page', config('enconfig.page'));
        $searchkeyword = _isset($this->request_params, 'searchkeyword');
        $msg = "";
        $content = "";
        $is_error = false;

        $limit_offset = limitoffset($limit, $page);
        $page = $limit_offset['page'];
        $limit = $limit_offset['limit'];
        $offset = $limit_offset['offset'];

        $form_params['limit'] = $limit;
        $form_params['page'] = $page;
        $form_params['offset'] = $offset;
        $form_params['searchkeyword'] = $searchkeyword;

        $options = ['form_params' => $form_params];
        $pos_resp = $this->itam->track_po_list($options);
            
        if ($pos_resp['is_error']) {
            $is_error = $pos_resp['is_error'];
            $msg = $pos_resp['msg'];
        } else {
            if (isset($pos_resp) && !empty($pos_resp)) {
                if($pos_resp['content']['totalrecords'] > 0) {
                    foreach ($pos_resp['content']['records'] as $key => $value) {
                       
                        $requester_id = $value['requester_id'];
                        $options_optional = ['form_params' => array('user_id' => $requester_id)];
        
                        $response_optional = $this->iam->getAllUsersWithoputPermission($options_optional);

                        $response_data = _isset(_isset($response_optional, 'content'), 'records');

                        if (!(is_array($response_data) && count($response_data) > 0)) {
                            $response_data = array();
                            $response_data[0] = array();
                            
                        }
                        $pos_resp['content']['records'][$key]['requester_info'] = $response_data[0];
                    }
                }
            }
        }
        if ($pos_resp['is_error']) {
            $is_error = $pos_resp['is_error'];
            $msg = $pos_resp['msg'];
        } else {
            if (isset($pos_resp) && !empty($pos_resp)) {
                if($pos_resp['content']['totalrecords'] > 0) {
                    $datas = array();
                    foreach($pos_resp['content']['records'] as $i => $val)
                    {   
                        $fname = isset($val['pending_by']['firstname']) ? $val['pending_by']['firstname'] : '';
                        $lname = isset($val['pending_by']['lastname']) ? $val['pending_by']['lastname'] : '';

                        $po_id                  = $val['po_id'];
                        $po_no                  = $val['po_no'];
                        $project_name           = '';
                        $segment                = '';
                        $project                = '';
                        $component              = array();
                        $request_initiated_date = $val['created_at'];

                        $created_date           = new DateTime($val['created_at']);
                        $interval               = $created_date->diff(new DateTime(Date('Y-m-d h:i:s')));
                        $day_lapsed             = $interval->d;

                        $delivery_timeline      = '';
                        $priority               = '';
                        $status                 = $val['status'];
                        // $remark                 = $val['remark'] != 'null' ? implode("<br/>", json_decode($val['remark']) ) : '';       
                        $dependancy             = $fname. " " . $lname;
                        $component_desc = array();
                        $component_qty = array();

                        if(!empty($val['details'])) {
                            $json_data            = json_decode($val['details'], true);
                            $project_name         = (!empty($json_data['project_name'])) ? $json_data['project_name'] : (!empty($json_data['pr_project_name_dd'])?$json_data['pr_project_name_dd']:'');
                            $priority             = $json_data['pr_priority'];
                            $delivery_timeline    = $json_data['pr_due_date'];
                        }
                        if(!empty($val['asset_details'])) {
                            //get item_id from asset_deatails
                            $item_id_array        = array();  
                            $asset_details_json   = explode('#',$val['asset_details']);
                            foreach ($asset_details_json as $key => $value) {
                                $item_id_list       = json_decode($value, true);
                                $item_id_array[]    = $item_id_list['item_product'];
                                $component_desc[]     = $item_id_list['item_desc'];
                                $component_qty[]      = $item_id_list['item_qty'];
                            }

                            if(!empty($val['asset_name'])) {
                                //get item_name and id from asset_name
                                $item_name_array      = array();
                                $asset_name_json      = explode(',',$val['asset_name']);
                                foreach ($asset_name_json as $key => $value) {
                                    $tmp_item_name                  = json_decode($value, true);
                                    $key_data                       = array_keys($tmp_item_name);
                                    $val_data                       = array_values($tmp_item_name);
                                    $item_name_array[$key_data[0]]  = $val_data[0];
                                }

                                //prepare array of name 
                                foreach ($item_id_array as $key => $id) {
                                    if(array_key_exists($id, $item_name_array)) {
                                        $component[]    = $item_name_array[$id];
                                    }
                                }

                            }
                            
                                                    
                        }
                        $datas[$i]['Po.No']  =  $po_no; 
                        $datas[$i]['Components']  =  implode(", ", $component); 
                        $datas[$i]['Request Initiated Date']  =  $request_initiated_date; 
                        $datas[$i]['Day Lapsed']  =  $day_lapsed; 
                        $datas[$i]['Delivery Timeline']  =  $delivery_timeline; 
                        $datas[$i]['Priority']  =  $priority; 
                        $datas[$i]['Status']  =  $status;                                             
                    }
                }
            }
            return view('exportexcel', compact(['datas']));
        }
    }


    public function Export_track_pr(Request $request) {
        $paging = array();
        $limit = _isset($this->request_params, 'limit', config('enconfig.def_limit'));
        $page = _isset($this->request_params, 'page', config('enconfig.page'));
        $searchkeyword = _isset($this->request_params, 'searchkeyword');
        $msg = "";
        $content = "";
        $is_error = false;

        $limit_offset = limitoffset($limit, $page);
        $page = $limit_offset['page'];
        $limit = $limit_offset['limit'];
        $offset = $limit_offset['offset'];

        $form_params['limit'] = $limit;
        $form_params['page'] = $page;
        $form_params['offset'] = $offset;
        $form_params['searchkeyword'] = $searchkeyword;

        $options = ['form_params' => $form_params];
        $pos_resp = $this->itam->track_pr_list($options);
        if ($pos_resp['is_error']) {
            $is_error = $pos_resp['is_error'];
            $msg = $pos_resp['msg'];
        } else {
            if (isset($pos_resp) && !empty($pos_resp)) {
                if($pos_resp['content']['totalrecords'] > 0) {
                    foreach ($pos_resp['content']['records'] as $key => $value) {
                       
                        $assigned_pr_id = '';
                        $approval_details_json = isset($value['approval_details']) ? json_decode($value['approval_details'], true) : '';
                        $approved_status_json = isset($value['approved_status']) ? json_decode($value['approved_status'], true) : '';

                        if($approval_details_json == null)
                        {
                            $pos_resp['content']['records'][$key]['pending_by']  = array();
                            continue;
                        }

                        if($approved_status_json == null) {
                            //pending for manager approval
                            $assigned_pr_id = isset($approval_details_json) ? $approval_details_json['confirmed'][0] : '';
                        } else if(count($approved_status_json) == 1 && empty($value['assignpr_user_id'])) {
                            //pending from store 
                            $assigned_pr_id = 'ad03914e-1ad0-11ec-b204-4e89be533080';
                        } else if(count($approved_status_json) > 1 && !empty($value['assignpr_user_id'])) {
                            //pending from purchase team 
                            $assigned_pr_id = isset($value['assignpr_user_id']) ? $value['assignpr_user_id'] : '';
                        }
                        if($assigned_pr_id == null || $assigned_pr_id == '') {
                            $pos_resp['content']['records'][$key]['pending_by'] = array();
                            continue;
                        }
                        $options_optional = ['form_params' => array('user_id' => $assigned_pr_id)];
        
                        $response_optional = $this->iam->getAllUsersWithoputPermission($options_optional);

                        $response_data = _isset(_isset($response_optional, 'content'), 'records');

                        if (!(is_array($response_data) && count($response_data) > 0)) {
                            $response_data = array();
                            $response_data[0] = array();
                            
                        }
                        $pos_resp['content']['records'][$key]['pending_by'] = $response_data[0];
                        
                        // Get requester information with requester id
                        $requester_id = $value['requester_id'];                            
                        $reqoptions_optional = ['form_params' => array('user_id' => $requester_id,'emp_id' => "")];
                        
                        $reqresponse_optional = $this->iam->getrequesteruser($reqoptions_optional);
                        
                        if (!(is_array($reqresponse_optional['content']['records'][0]) && count($reqresponse_optional['content']['records'][0]) > 0)) {
                            $reqresponse_optional = array();
                            $reqresponse_optional = array();                                
                        }else{
                            $pos_resp['content']['records'][$key]['requester_info'] = $reqresponse_optional['content']['records'][0];
                        }
                    }
                }
            }
        }
        if ($pos_resp['is_error']) {
            $is_error = $pos_resp['is_error'];
            $msg = $pos_resp['msg'];
        } else {
            if (isset($pos_resp) && !empty($pos_resp)) {
                if($pos_resp['content']['totalrecords'] > 0) {
                    $datas = array();
                    foreach($pos_resp['content']['records'] as $i => $val)
                    {   
                        $fname = isset($val['pending_by']['firstname']) ? $val['pending_by']['firstname'] : '';
                        $lname = isset($val['pending_by']['lastname']) ? $val['pending_by']['lastname'] : '';
                        $dependancy             = $fname. " " . $lname;
                        
                        // 
                        $requesterfname = isset($val['requester_info']['firstname']) ? $val['requester_info']['firstname'] : '';
                        $requesterlname = isset($val['requester_info']['lastname']) ? $val['requester_info']['lastname'] : '';
                        $requesterdepartment = isset($val['requester_info']['department_name']) ? $val['requester_info']['department_name'] : '';
                        $requesterinfo             = $requesterfname. " " . $requesterlname;
                        // 

                        $pr_id                  = $val['pr_id'];
                        $pr_no                  = $val['pr_no'];
                        $project_name           = '';
                        $segment                = '';
                        $project                = '';
                        $component              = array();
                        $request_initiated_date = $val['created_at'];

                        $created_date           = new DateTime($val['created_at']);
                        $interval               = $created_date->diff(new DateTime(Date('Y-m-d h:i:s')));
                        $day_lapsed             = $interval->d;

                        $delivery_timeline      = '';
                        $priority               = '';
                        $status                 = $val['status'];
                        $remark                 = ($val['remark'] != 'null') ? implode(" ", json_decode($val['remark']) ) : '';    
                        
                        $component_desc = array();
                        $component_qty = array();
                        $component_warranty = array();

                        if(!empty($val['details'])) {
                            $json_data            = json_decode($val['details'], true);
                            if($json_data['project_name'] == "null")
                            {
                                $project_name = $json_data['pr_project_name_dd'];
                            }else{
                                $project_name = $json_data['project_name'];
                            }
                            // $project_name         = (!empty($json_data['project_name'])) ? $json_data['project_name'] : (!empty($json_data['pr_project_name_dd'])?$json_data['pr_project_name_dd']:'');
                            $pr_project_category = $json_data['pr_project_category'];

                            $priority             = $json_data['pr_priority'];
                            $delivery_timeline    = $json_data['pr_due_date'];
                        }
                        if(!empty($val['asset_details'])) {
                            //get item_id from asset_deatails
                            $item_id_array        = array();  
                            $asset_details_json   = explode('#',$val['asset_details']);
                            foreach ($asset_details_json as $key => $value) {
                                $item_id_list       = json_decode($value, true);
                                $item_id_array[]    = $item_id_list['item_product'];
                                $component_desc[]	  = $item_id_list['item_desc'];
                                $component_qty[]	  = $item_id_list['item_qty'];
                                $component_warranty[]	  = $item_id_list['warranty_support_required'];
                            }

                            if(!empty($val['asset_name'])) {
                                //get item_name and id from asset_name
                                $item_name_array      = array();
                                $asset_name_json      = explode(',',$val['asset_name']);
                                foreach ($asset_name_json as $key => $value) {
                                    $tmp_item_name                  = json_decode($value, true);
                                    $key_data                       = array_keys($tmp_item_name);
                                    $val_data                       = array_values($tmp_item_name);
                                    $item_name_array[$key_data[0]]  = $val_data[0];
                                }

                                //prepare array of name 
                                foreach ($item_id_array as $key => $id) {
                                    if(array_key_exists($id, $item_name_array)) {
                                        $component[]    = $item_name_array[$id];
                                    }
                                }
                            }
                        }

                        $result = array();
                        foreach($component_warranty as $key=>$val){
                            $val1 = $component[$key];
                            $val2 = $component_desc[$key];
                            $val3 = $component_qty[$key];
                            $result[$key] = " Name - ". $val1 . ", Desc - ". $val2  . ", Qty - ". $val3 . ", Warranty - ". $val; 
                        }


                        $datas[$i]['Pr.No']  =  $pr_no; 
                        $datas[$i]['Status']  =  $status; 
                        $datas[$i]['Requester Name']  =  $requesterinfo; 
                        $datas[$i]['Department']  =  $requesterdepartment; 
                        $datas[$i]['Project Name']  =  $project_name; 
                        $datas[$i]['Project Category']  =  $pr_project_category; 
                        $datas[$i]['Asset Details']  =  implode(", ", $result); 
                        // $datas[$i]['Item Description']  =  implode(", ", $component_desc); 
                        // $datas[$i]['Quantity']  =  implode(", ", $component_qty);
                        $datas[$i]['Request Initiated Date']  =  $request_initiated_date; 
                        $datas[$i]['Day Lapsed']  =  $day_lapsed; 
                        $datas[$i]['Delivery Timeline']  =  $delivery_timeline; 
                        $datas[$i]['Priority']  =  $priority; 
                        $datas[$i]['Remark']  =  $remark;
                        $datas[$i]['Dependancy']  =  $dependancy; 
                    }
                }
            }
            return view('exportexcel', compact(['datas']));
        }
    }


}
