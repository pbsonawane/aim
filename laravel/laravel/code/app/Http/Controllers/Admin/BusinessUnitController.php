<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use Illuminate\Http\Request;

/**
 * BusinessUnitController class is implemented to do CRUD operations on BusinessUnit Module
 * @author Kavita Daware
 * @package businessunit
 */

class BusinessUnitController extends Controller
{
    /**
     * Contructor function to initiate the API service and Request data
     * @author Kavita Daware
     * @access public
     * @package businessunit
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
     * BusinessUnit Controller function is implemented to initiate a page to get list of Businessunits.
     * @author Kavita Daware
     * @access public
     * @package businessunit
     * @return string
     */

    public function businessunits()
    {

        $topfilter = ['gridsearch' => true, 'jsfunction' => 'businessunitList()'];
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter);
        $data['pageTitle'] = "Business Unit";
        $data['includeView'] = view("Admin/businessunits", $data);
        return view('template', $data);
    }

    /**
     * This controller function is implemented to get list of Businessunits.
     * @author Kavita Daware
     * @access public
     * @package businessunit
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */
    public function businessunitlist()
    {
        $paging = [];
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

        $businessunits_resp = $this->iam->getBusinessunit($options);
        if ($businessunits_resp['is_error'])
        {
            $is_error = $businessunits_resp['is_error'];
            $msg = $businessunits_resp['msg'];
        }
        else
        {
            $is_error = false;
            $businessunits = _isset(_isset($businessunits_resp, 'content'), 'records');
            $paging['total_rows'] = _isset(_isset($businessunits_resp, 'content'), 'totalrecords');
            $paging['showpagination'] = true;
            $paging['jsfunction'] = 'businessunitList()';
            $paging['limit'] = $limit;
            $paging['offset'] = $offset;
            $paging['page'] = $page;
            $view = 'Admin/businessunitlist';
            $content = $this->emlib->emgrid($businessunits, $view, $columns = [], $paging);
        }

        $response["html"] = $content;
        $response["is_error"] = $is_error;
        $response["msg"] = $msg;
        echo json_encode($response);
    }
    /**
     * This controller function is used to load business unit add form.
     * @author Kavita Daware
     * @access public
     * @package businessunit
     * @return string
     */
    public function businessunitadd(Request $request)
    {
        $data['bu_id'] = '';
        $businessunitdata = [];
        $data['businessunitdata'] = $businessunitdata;
        $html = view("Admin/businessunitadd", $data);
        echo $html;
    }
    /**
     * This controller function is used to save businessunit data in database.
     * @author Kavita Daware
     * @access public
     * @package businessunit
     * @param string $bu_name Business Unit Name
     * @param string $bu_description Business Unit Description
     * @return json
     */
    public function businessunitaddsubmit(Request $request)
    {
        $data = $this->iam->addBusinessunit(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to load businessunit edit form with existing data for selected businessunit
     * @author Kavita Daware
     * @access public
     * @package businessunit
     * @param \Illuminate\Http\Request $request
     * @param $bu_id Business Unit Unique Id
     * @return string
     */
    public function businessunitedit(Request $request)
    {
        $bu_id = $request->id;
        $input_req = ['bu_id' => $bu_id];
        $data = $this->iam->editBusinessunit(['form_params' => $input_req]);

        $data['bu_id'] = $bu_id;
        $data['businessunitdata'] = $data['content'];

        $html = view("Admin/businessunitadd", $data);
        echo $html;
    }
    /**
     * This controller function is used to update businessunit data in database.
     * @author Kavita Daware
     * @access public
     * @package businessunit
     * @param UUID $bu_id Business Unit Unique Id
     * @param string $bu_name Business Unit Name
     * @param string $bu_description Business Unit Description
     * @return json
     */
    public function businessuniteditsubmit(Request $request)
    {
        $data = $this->iam->updateBusinessunit(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }

    /**
     * This controller function is used to delete businessunit  data from database.
     * @author Kavita Daware
     * @access public
     * @package businessunit
     * @param UUID $bu_id Business Unit Unique Id
     * @return json
     */
    public function businessunitdelete(Request $request)
    {
        $data = $this->iam->deleteBusinessunit(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }
}
