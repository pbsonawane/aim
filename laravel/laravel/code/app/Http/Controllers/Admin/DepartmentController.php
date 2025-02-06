<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use Illuminate\Http\Request;

/**
 * DepartmentController class is implemented to do CRUD operations on Department Module
 * @author Kavita Daware
 * @package department
 */
class DepartmentController extends Controller
{
    /**
     * Contructor function to initiate the API service and Request data
     * @author Kavita Daware
     * @access public
     * @package department
     * @param \App\Services\IAM\IamService $iam
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function __construct(IamService $iam, Request $request)
    {
        $this->iam = $iam;
        $this->emlib = new Emlib;
        $this->request = $request;
        $this->request_params = $this->request->all();
    }
    /**
     * Department Controller function is implemented to initiate a page to get list of Departments.
     * @author Kavita Daware
     * @access public
     * @package department
     * @return string
     */
    public function departments()
    {

        $topfilter = array('gridsearch' => true, 'jsfunction' => 'departmentList()');
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter);
        $data['pageTitle'] = "Department";
        $data['includeView'] = view("Admin/departments", $data);
        return view('template', $data);
    }
    /**
     * This controller function is implemented to get list of Departments.
     * @author Kavita Daware
     * @access public
     * @package department
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */
    public function departmentlist()
    {
        $paging = array();
        $fromtime = $totime = '';
        $limit = _isset($this->request_params, 'limit', config('enconfig.def_limit'));
        $exporttype = _isset($this->request_params, 'exporttype');
        $page = _isset($this->request_params, 'page', config('enconfig.page'));
        $searchkeyword = _isset($this->request_params, 'searchkeyword');
        $is_error = true;
        $msg = '';
        $content = "";
        $limit_offset = limitoffset();
        $page = $limit_offset['page'];
        $limit = $limit_offset['limit'];
        $offset = $limit_offset['offset'];
        $form_params['limit'] = $limit;
        $form_params['searchkeyword'] = $searchkeyword;
        $form_params['page'] = $page;
        $options = [
            'form_params' => $form_params];

        $departments_resp = $this->iam->getDepartment($options);
        if ($departments_resp['is_error'])
        {
            $is_error = $departments_resp['is_error'];
            $msg = $departments_resp['msg'];
        }
        else
        {
            $is_error = false;
            $departments = _isset(_isset($departments_resp, 'content'), 'records');
            $paging['total_rows'] = _isset(_isset($departments_resp, 'content'), 'totalrecords');
            $paging['showpagination'] = true;
            $paging['jsfunction'] = 'departmentList()';
            $paging['limit'] = $limit;
            $paging['offset'] = $offset;
            $paging['page'] = $page;
            $view = 'Admin/departmentlist';
            $content = $this->emlib->emgrid($departments, $view, $columns = array(), $paging);
        }

        $response["html"] = $content;
        $response["is_error"] = $is_error;
        $response["msg"] = $msg;
        echo json_encode($response);
    }
    /**
     * This controller function is used to load department add form.
     * @author Kavita Daware
     * @access public
     * @package department
     * @return string
     */
    public function departmentadd(Request $request)
    {
        $data['department_id'] = '';
        $departmentdata = array();
        $data['departmentdata'] = $departmentdata;
        $html = view("Admin/departmentadd", $data);
        echo $html;
    }
    /**
     * This controller function is used to save department data in database.
     * @author Kavita Daware
     * @access public
     * @package department
     * @param string $department_name department Name
     * @return json
     */
    public function departmentaddsubmit(Request $request)
    {
        $data = $this->iam->addDepartment(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to load department edit form with existing data for selected department
     * @author Kavita Daware
     * @access public
     * @package department
     * @param \Illuminate\Http\Request $request
     * @param $department_id department Unique Id
     * @return string
     */
    public function departmentedit(Request $request)
    {
        $department_id = $request->id;
        $input_req = array('department_id' => $department_id);
        $data = $this->iam->editDepartment(array('form_params' => $input_req));

        $data['department_id'] = $department_id;
        $data['departmentdata'] = $data['content'];
        $html = view("Admin/departmentadd", $data);
        echo $html;
    }
    /**
     * This controller function is used to update department data in database.
     * @author Kavita Daware
     * @access public
     * @package department
     * @param UUID $department_id department Unique Id
     * @param string $department_name department Name
     * @return json
     */
    public function departmenteditsubmit(Request $request)
    {
        $data = $this->iam->updateDepartment(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to delete department  data from database.
     * @author Kavita Daware
     * @access public
     * @package department
     * @param UUID $department_id department Unique Id
     * @return json
     */
    public function departmentdelete(Request $request)
    {
        $data = $this->iam->deleteDepartment(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }
}
