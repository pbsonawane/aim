<?php
namespace App\Http\Controllers\Cmdb;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use Illuminate\Http\Request;
use View;

/**
 * Requestername Controller class is implemented to do Requestername operations.
 * @author Bhushan Amruktar
 * @package Requestername
 */
class RequesternameController extends Controller
{
    /**
     * Contructor function to initiate the API service and Request data
     * @author Bhushan Amruktar
     * @access public
     * @package Requestername
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
    /**
Requestername Controller function is implemented to initiate a page to get list of Requesternames
     * @author Bhushan Amruktar
     * @access public
     * @package Requestername
     * @return string
     */

    public function requesternames()
    {
        /*Sync Reqeuster Data Start
        $options        = ['form_params' => array('user_id' => showuserid())];
        $user_details       = $this->iam->getuserprofile($options);     
        $firstname = $user_details['content'][0]['firstname'];
        $lastname = $user_details['content'][0]['lastname'];
        $emp_id = $user_details['content'][0]['emp_id'];
        $department_id = $user_details['content'][0]['department_id'];
        $status = $user_details['content'][0]['status'];
        $form_params['firstname']=$firstname;
        $form_params['lastname']=$lastname;
        $form_params['emp_id']=$emp_id;
        $form_params['department_id']=$department_id;
        $form_params['status']=$status;
        $options      = ['form_params' => $form_params];
        $requestername_resp = $this->itam->syncrequesteruser($options);
        Sync Reqeuster Data END*/ 

        $topfilter           = array('gridsearch' => true, 'jsfunction' => 'requesternameList()', 'gridadvsearch' => false);
        $data['emgridtop']   = $this->emlib->emgridtop($topfilter, '', array("fname"));
        $data['pageTitle']   = trans('title.requesternames');
        $data['includeView'] = view("Cmdb/requesternames", $data);
        return view('template', $data);
    }
     public function getrequesters()
    {
       
        $department_id = _isset($this->request_params, 'dept_id');
        $pre_requestername_id = _isset($this->request_params, 'pre_requestername_id');
        $form_params['department_id'] = $department_id;

        $options      = ['form_params' => $form_params];
        $requestername_resp = $this->itam->getrequesternames($options);

        $requesternames     = _isset(_isset($requestername_resp, 'content'), 'records');
        if ($requestername_resp['is_error']) {
            $requesternames = null;
            $is_error = $requestername_resp['is_error'];
            $msg      = $requestername_resp['msg'];
        }else{ 
            $opt='<option value="">-Select-</option>';
            if(!empty($requesternames)){
                foreach($requesternames as $rows){
                    $selected = $pre_requestername_id;
                    if(!empty($pre_requestername_id)){
                        if($pre_requestername_id == $rows['requestername_id']){
                            $selected = 'selected';                        
                            $opt .='<option value="'.$rows['requestername_id'].'" '.$selected.'>'.$rows['fname'].' '.$rows['lname'].'</option>';
                            break;
                        }
                    }else{
                        $opt .='<option value="'.$rows['requestername_id'].'">'.$rows['fname'].' '.$rows['lname'].'</option>';
                    }
                    

                }
            }
            $requesternames = $opt;
            $is_error = false;
            $msg      = '';
        }
        $response["html"]     = $requesternames;
        $response["is_error"] = $is_error;
        $response["msg"]      = $msg;
        echo json_encode($response);
    }
    /**
     * This controller function is implemented to get list of Requesternames.
     * @author Bhushan Amruktar
     * @access public
     * @package Requestername
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */
    public function requesternameList()
    {
        $paging        = array();
        $fromtime      = $totime      = '';
        $limit         = _isset($this->request_params, 'limit', config('enconfig.def_limit_short'));
        $exporttype    = _isset($this->request_params, 'exporttype');
        $page          = _isset($this->request_params, 'page', config('enconfig.page'));
        $searchkeyword = _isset($this->request_params, 'searchkeyword');
        $is_error      = false;
        $msg           = '';
        $content       = "";
        $limit_offset  = limitoffset($limit, $page);
        $page          = $limit_offset['page'];
        $limit         = $limit_offset['limit'];
        $offset        = $limit_offset['offset'];

        $form_params['limit']         = $paging['limit']         = $limit;
        $form_params['page']          = $paging['page']          = $page;
        $form_params['offset']        = $paging['offset']        = $offset;
        $form_params['searchkeyword'] = $searchkeyword;

        // Show listing as per department wise
        $option_user                 = array('form_params' => array('user_id' => showuserid()));
        $userdata                    = $this->iam->getUsers($option_user);
        $user_id                     = _isset(_isset($userdata, 'content'), 'records');
        $form_params['department_id'] = $user_id[0]['department_id'];

        $options      = ['form_params' => $form_params];
        $requestername_resp = $this->itam->getrequesternames($options);

        $requesternames     = _isset(_isset($requestername_resp, 'content'), 'records');
        if ($requesternames == '') {
            $requesternames = array();
        }
        $department_resp = $this->iam->getDepartment(array());
        $dept_data      = _isset(_isset($department_resp, 'content'), 'records');
        //echo "<pre>"; print_r($dept_data);exit;
        $all_dept = array();
        
        if (isset($dept_data) && is_array($dept_data) && count($dept_data) > 0) {
            foreach ($dept_data as $dept) {
                $department_id           = isset($dept['department_id']) ? $dept['department_id'] : "";
                $department_name         = isset($dept['department_name']) ? $dept['department_name'] : "";
                $all_dept[$department_id] = $department_name;
            }
        }
        foreach ($requesternames as $key => $dept) {
            $department_id = isset($dept['departments']) ? $dept['departments'] : "";
            $requesternames[$key]['department_name'] = isset($all_dept[$department_id]) ? $all_dept[$department_id] : "";
        }
        //echo "<pre>"; print_r($requesternames);exit;
        if ($requestername_resp['is_error']) {
            $is_error = $requestername_resp['is_error'];
            $msg      = $requestername_resp['msg'];
        } else {
            $is_error                 = false;
            //$requesternames                 = _isset(_isset($requestername_resp, 'content'), 'records');
            $paging['total_rows']     = _isset(_isset($requestername_resp, 'content'), 'totalrecords');
            $paging['showpagination'] = true;
            $paging['jsfunction']     = 'requesternameList()';

            $view    = 'Cmdb/requesternamelist';
            $content = $this->emlib->emgrid($requesternames, $view, $columns = array(), $paging);
        }
        $response["html"]     = $content;
        $response["is_error"] = $is_error;
        $response["msg"]      = $msg;
        echo json_encode($response);
    }
    /**
     * This controller function is used to load requestername add form.
     * @author Bhushan Amruktar
     * @access public
     * @package requestername
     * @return string
     */
    public function requesternameadd(Request $request)
    {
        $requestername_id             = $request->requestername_id;
        $data['requestername_id']     = '';
        $form_params['limit']         = 0;
        $form_params['page']          = 0;
        $form_params['offset']        = 0;
        $form_params['searchkeyword'] = '';
        $form_params['requestername_id'] = $requestername_id;
        $options                      = ['form_params' => $form_params];
        $data['department_id']          = '';

         // Show listing as per department wise
        $option_user                 = array('form_params' => array('user_id' => showuserid()));
        $userdata                    = $this->iam->getUsers($option_user);
        $user_id                     = _isset(_isset($userdata, 'content'), 'records');
        $data['department_id'] = $user_id[0]['department_id'];

        $department_resp                = $this->iam->getDepartment($options);
        if ($department_resp['is_error']) {
            $departments = array();
        } else {
            $departments = _isset(_isset($department_resp, 'content'), 'records');
        }
        $requesternamedata         = array();
        $data['requesternamedata'] = $requesternamedata;
        $data['departments']  = $departments;
        $html                = view("Cmdb/requesternameadd", $data);
        echo $html;
    }
    /**
     * This controller function is used to save requestername data in database.
     * @author Bhushan Amruktar
     * @access public
     * @package requestername
     * @param string $requestername_id
     * @return json
     */
    public function requesternameaddsubmit(Request $request)
    {
        $data = $this->itam->addrequestername(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to load requestername edit form with existing data for selected requestername
     * @author Bhushan Amruktar
     * @access public
     * @package requestername
     * @param \Illuminate\Http\Request $request
     * @param $requestername_id requestername Unique Id
     * @return string
     */
    public function requesternameedit(Request $request)
    {
        $requestername_id = $request->id;

        $input_req  = array('requestername_id' => $requestername_id);        
        $data       = $this->itam->editrequestername(array('form_params' => $input_req));

        $data['requestername_id']  = $requestername_id;
         $limit_offset          = limitoffset(0, 0);
        $form_params['limit']  = $limit_offset['limit'];
        $form_params['page']   = $limit_offset['page'];
        $form_params['offset'] = $limit_offset['offset'];

        $data['department_id'] = '';
        $options             = ['form_params' => $form_params];
        $department_resp       = $this->iam->getDepartment($options);
        if ($department_resp['is_error']) {
            $departments = array();
        } else {
            $departments = _isset(_isset($department_resp, 'content'), 'records');
        }
        $data['departments']  = $departments;
        $data['requesternamedata'] = $data['content'];

        $html = view("Cmdb/requesternameadd", $data);
        echo $html;
    }
    /**
     * This controller function is used to update requestername data in database.
     * @author Bhushan Amruktar
     * @access public
     * @package requestername
     * @param UUID $requestername_id requestername  Unique Id
     * @return json
     */
    public function requesternameeditsubmit(Request $request)
    {
        $data = $this->itam->updaterequestername(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to delete requestername data from database.
     * @author Bhushan Amruktar
     * @access public
     * @package requestername
     * @param UUID $requestername_id Unique Id
     * @return json
     */
    public function requesternamedelete(Request $request)
    {
        $data = $this->itam->deleterequestername(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }
}
