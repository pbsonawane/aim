<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use Illuminate\Http\Request;

/**
 * DesignationController class is implemented to do CRUD operations on Designation Master
 * @author Pravin Sonawane
 * @package user
 */
class DesignationController extends Controller
{
    /**
     * Contructor function to initiate the API service and Request data
     * @author Pravin Sonawane
     * @access public
     *  @package user
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
     * This controller function is implemented to initiate a page to get list of Designations.
     * @author Pravin Sonawane
     * @access public
     * @package user
     * @return string
     */
    public function designations()
    {
        $topfilter = ['gridsearch' => true, 'jsfunction' => 'designationList()'];
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter);
        $data['pageTitle'] = "Designation";
        $data['includeView'] = view("Admin/designations", $data);
        return view('template', $data);
    }
    /**
     * This controller function is implemented to get list of designations.
     * @author Pravin Sonawane
     * @access public
     * @package user
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */
    public function designationlist()
    {
        $paging = [];
        $fromtime = $totime = '';
        $limit = _isset($this->request_params, 'limit', config('enconfig.def_limit'));
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
        $form_params['searchkeyword'] = $searchkeyword;
        $form_params['page'] = $paging['page'] = $page;
        $form_params['offset'] = $paging['offset'] = $offset;
        $options = [
            'form_params' => $form_params];
        $designations_resp = $this->iam->getDesignations($options);
        if ($designations_resp['is_error'])
        {
            $is_error = $designations_resp['is_error'];
            $msg = $designations_resp['msg'];
        }
        else
        {
            $designations = _isset(_isset($designations_resp, 'content'), 'records');
            $paging['total_rows'] = _isset(_isset($designations_resp, 'content'), 'totalrecords');
            $paging['showpagination'] = true;
            $paging['jsfunction'] = 'designationList()';
            $view = 'Admin/designationlist';
            $content = $this->emlib->emgrid($designations, $view, $columns = [], $paging);
        }
        $response["html"] = $content;
        $response["is_error"] = $is_error;
        $response["msg"] = $msg;
        echo json_encode($response);
    }

    /**
     * This controller function is used to load designation add form.
     * @author Pravin Sonawane
     * @access public
     * @package user
     * @return string
     */
    public function designationadd()
    {
        $designationdata = [];
        $data['designationdata'] = $designationdata;
        $html = view("Admin/designationadd", $data);
        echo $html;
    }

    /**
     * This controller function is used to save designation data in database.
     * @author Pravin Sonawane
     * @access public
     * @package user
     * @param string $designation_name Designation Name
     * @return json
     */
    public function designationsave(Request $request)
    {
        $data = $this->iam->addDesignation(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }

    /**
     * This controller function is used to load designation edit form with existing data for selected designation
     * @author Pravin Sonawane
     * @access public
     * @package user
     * @param \Illuminate\Http\Request $request
     * @param $designation_id Designation Unique Id
     * @return string
     */
    public function designationedit(Request $request)
    {
        $designationid =$request->input('designationid');
		$input_req = ['designation_id' => $designationid];
        $data =  $this->iam->editDesignation(['form_params' => $input_req]);
        $data['designationid'] = $designationid;
		$data['designationdata'] = $data['content'];
        $html = view("Admin/designationadd",$data);
        echo  $html;
    }
    /**
     * This controller function is used to update designation data in database.
     * @author Pravin Sonawane
     * @access public
     *  @package user
     * @param UUID $designation_id Designation Unique Id
     * @param string $designation_name Designation Name
     * @return json
     */
    public function designationupdate(Request $request)
    {
        $data = $this->iam->updateDesignation(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }

     /**
     * This controller function is used to delete designation data from database.
     * @author Pravin Sonawane
     * @access public
     *  @package user
     * @param UUID $designation_id Designation Unique Id
     * @return json
     */
    public function designationdelete(Request $request)
    {
        $designationid = $request->input('designationid');
		$input_req = ['designation_id' => $designationid];
        $data = $this->iam->deleteDesignation(['form_params' => $input_req]);
        echo json_encode($data, true);
    }
}
