<?php

namespace App\Http\Controllers\Cmdb;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use Illuminate\Http\Request;
use View;

/**
 * CostcenterController class is implemented to do Costcenter operations.
 * @author Kavita Daware
 * @package costcenter
 */
class CostcenterController extends Controller
{
    /**
     * Contructor function to initiate the API service and Request data
     * @author Kavita Daware
     * @access public
     * @package costcenter
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
    /**
     * costcenter Controller function is implemented to initiate a page to get list of costcenter
     * @author Kavita Daware
     * @access public
     * @package costcenter
     * @return string
     */

    public function costcenters()
    {

        $topfilter = ['gridsearch' => true, 'jsfunction' => 'costcenterList()', 'gridadvsearch' => false];
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter, '', ["cc_name"]);
        $data['pageTitle'] = trans('title.costcenter');
        $data['includeView'] = view("Cmdb/costcenters", $data);
        return view('template', $data);
    }
    /**
     * This controller function is implemented to get list of costcenters.
     * @author Kavita Daware
     * @access public
     * @package costcenter
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */

    public function costcenterlist()
    {
        //try
        //{
        $paging = [];
        $fromtime = $totime = '';
        $limit = _isset($this->request_params, 'limit', config('enconfig.def_limit_short'));
        $exporttype = _isset($this->request_params, 'exporttype');
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
        $form_params['page'] = $paging['page'] = $page;
        $form_params['offset'] = $paging['offset'] = $offset;
        $form_params['searchkeyword'] = $searchkeyword;

        $options = ['form_params' => $form_params];

        $cc_resp = $this->itam->getcostcenters($options);
        $costcenters = _isset(_isset($cc_resp, 'content'), 'records');
        if($costcenters == '') $costcenters = [];

        //$cc_id          = isset($cc_resp['content']['records'][0]) ? $cc_resp['content']['records'][0]['cc_id'] : null;

        //$ccoptions      = ['form_params' => array('cc_id' => $cc_id)];

        $location_resp = $this->iam->getLocations([]);
        $loc_data = _isset(_isset($location_resp, 'content'), 'records');
        //echo "<pre>"; print_r($loc_data);
        $all_loc = [];
        if(isset($loc_data) && is_array($loc_data) && count($loc_data) > 0){
        foreach ($loc_data as $loc)
        {
            $location_id = isset($loc['location_id']) ? $loc['location_id'] : "";
            $location_name = isset($loc['location_name']) ? $loc['location_name'] : "";
            $all_loc[$location_id] = $location_name;
        }
        }
        //print_r($all_loc);

        foreach ($costcenters as $key => $loc)
        {
            $location_id = isset($loc['locations']) ? $loc['locations'] : "";
            $costcenters[$key]['location_name'] = isset($all_loc['location_id']) ? $all_loc['location_id'] : "";

            //$all_loc[$location_id];
        }

        if ($cc_resp['is_error'])
        {
            $is_error = $cc_resp['is_error'];
            $msg = $cc_resp['msg'];
        }
        else
        {
            $is_error = false;

            //$costcenters = _isset(_isset($cc_resp, 'content'), 'records');

            $paging['total_rows'] = _isset(_isset($cc_resp, 'content'), 'totalrecords');
            $paging['showpagination'] = true;
            $paging['jsfunction'] = 'costcenterList()';

            $view = 'Cmdb/costcenterlist';

            $content = $this->emlib->emgrid($costcenters, $view, $columns = [], $paging);
        }

        $response["html"] = $content;
        $response["is_error"] = $is_error;
        $response["msg"] = $msg;
        //$response['cc_id'] = $cc_id;
        echo json_encode($response);
        // }
        /* catch (\Exception $e)
    {
    $response["html"] = '';
    $response["is_error"] = true;
    $response["msg"] = $e->getMessage();

    echo json_encode($response);
    }
    catch (\Error $e)
    {
    $response["html"] = '';
    $response["is_error"] = true;
    $response["msg"] = $e->getMessage();

    echo json_encode($response);
    }*/
    }

    /**
     * This controller function is used to load costcenter add form.
     * @author Kavita Daware
     * @access public
     * @package costcenter
     * @return string
     */
    public function costcenteradd(Request $request)
    {
        $cc_id = $request->cc_id;
        $data['cc_id'] = '';
        $form_params['limit'] = 0;
        $form_params['page'] = 0;
        $form_params['offset'] = 0;
        $form_params['searchkeyword'] = '';
        $form_params['cc_id'] = $cc_id;
        $options = ['form_params' => $form_params];

        $data['department_id'] = '';
        $dept_resp = $this->iam->getDepartment($options);
        if ($dept_resp['is_error'])
        {
            $depts = [];
        }
        else
        {
            $depts = _isset(_isset($dept_resp, 'content'), 'records');
        }
        $data['location_id'] = '';
        $options = ['form_params' => $form_params];
        $location_resp = $this->iam->getLocations($options);
        if ($location_resp['is_error'])
        {
            $locations = [];
        }
        else
        {
            $locations = _isset(_isset($location_resp, 'content'), 'records');
        }

        $costcenterdata = [];
        $data['depts'] = $depts;
        $data['locations'] = $locations;
        $data['costcenterdata'] = $costcenterdata;
        $html = view("Cmdb/costcenteradd", $data);
        echo $html;
    }
    /**
     * This controller function is used to save costcenter data in database.
     * @author Kavita Daware
     * @access public
     * @package costcenter
     * @param string $costcenter 
     * @return json
     */
    public function costcenteraddsubmit(Request $request)
    {
        $data = $this->itam->addcostcenter(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }

    /**
     * This controller function is used to load costcenter edit form with existing data for selected costcenter
     * @author Kavita Daware
     * @access public
     * @package costcenter
     * @param \Illuminate\Http\Request $request
     * @param $cc_id costcenter Unique Id
     * @return string
     */
    public function costcenteredit(Request $request)
    {
        $cc_id = $request->id;
        $input_req = ['cc_id' => $cc_id];
        $data = $this->itam->editcostcenter(['form_params' => $input_req]);
        $data['cc_id'] = $cc_id;
        $data['department_id'] = '';
        $limit_offset = limitoffset(0, 0);
        $form_params['limit'] = $limit_offset['limit'];
        $form_params['page'] = $limit_offset['page'];
        $form_params['offset'] = $limit_offset['offset'];

        $options = ['form_params' => $form_params];
        $dept_resp = $this->iam->getDepartment($options);
        if ($dept_resp['is_error'])
        {
            $depts = [];
        }
        else
        {
            $depts = _isset(_isset($dept_resp, 'content'), 'records');
        }
        $data['location_id'] = '';
        $options = ['form_params' => $form_params];
        $location_resp = $this->iam->getLocations($options);
        if ($location_resp['is_error'])
        {
            $locations = [];
        }
        else
        {
            $locations = _isset(_isset($location_resp, 'content'), 'records');
        }
        $data['depts'] = $depts;
        $data['locations'] = $locations;
        $data['costcenterdata'] = $data['content'];
        $html = view("Cmdb/costcenteradd", $data);
        echo $html;
    }

    /**
     * This controller function is used to update costcenter data in database.
     * @author Kavita Daware
     * @access public
     * @package costcenter
     * @param UUID $costcenter_id costcenter  Unique Id
     * @param string $costcenter
     * @return json
     */
    public function costcentereditsubmit(Request $request)
    {
        $data = $this->itam->updatecostcenter(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to delete costcenter  data from database.
     * @author Kavita Daware
     * @access public
     * @package costcenter
     * @param UUID $cc_id costcenter Unique Id
     * @return json
     */
    public function costcenterdelete(Request $request)
    {
        $data = $this->itam->deletecostcenter(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }
}
