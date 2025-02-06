<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use Illuminate\Http\Request;

/**
 * BusinessVerticalController class is implemented to do CRUD operations on BusinessVertical Module
 * @author Kavita Daware
 * @package businessvertical
 */

class BusinessVerticalController extends Controller
{
    /**
     * Contructor function to initiate the API service and Request data
     * @author Kavita Daware
     * @access public
     * @package businessvertical
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
     * BusinessVertical Controller function is implemented to initiate a page to get list of BusinessVertical.
     * @author Kavita Daware
     * @access public
     * @package businessvertical
     * @return string
     */

    public function businessverticals()
    {

        $topfilter = ['gridsearch' => true, 'jsfunction' => 'businessverticalList()'];
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter);
        $data['pageTitle'] = "Business Verticals";
        $data['includeView'] = view("Admin/businessverticals", $data);
        return view('template', $data);
    }
    /**
     * This controller function is implemented to get list of BusinessVertical.
     * @author Kavita Daware
     * @access public
     * @package businessvertical
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */
    public function businessverticallist()
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

        $businessverticals_resp = $this->iam->getBusinessVertical($options);
        if ($businessverticals_resp['is_error'])
        {
            $is_error = $businessverticals_resp['is_error'];
            $msg = $businessverticals_resp['msg'];
        }
        else
        {
            $is_error = false;
            $businessverticals = _isset(_isset($businessverticals_resp, 'content'), 'records');
            $paging['total_rows'] = _isset(_isset($businessverticals_resp, 'content'), 'totalrecords');
            $paging['showpagination'] = true;
            $paging['jsfunction'] = 'businessverticalList()';
            $paging['limit'] = $limit;
            $paging['offset'] = $offset;
            $paging['page'] = $page;
            $view = 'Admin/businessverticallist';
            $content = $this->emlib->emgrid($businessverticals, $view, $columns = [], $paging);
        }

        $response["html"] = $content;
        $response["is_error"] = $is_error;
        $response["msg"] = $msg;
        echo json_encode($response);
    }
    /**
     * This controller function is used to load business vertical add form.
     * @author Kavita Daware
     * @access public
     * @package businessvertical
     * @return string
     */
    public function businessverticaladd(Request $request)
    {

        $data['bv_id'] = '';
        $form_params['limit'] = 0;
        $form_params['page'] = 0;
        $form_params['offset'] = 0;
        $form_params['searchkeyword'] = '';

        $options = ['form_params' => $form_params];
        $businessunits_resp = $this->iam->getBusinessunit($options);
        if ($businessunits_resp['is_error'])
        {
            $businessunits = [];
        }
        else
        {
            $businessunits = _isset(_isset($businessunits_resp, 'content'), 'records');
        }
        $data['businessverticaldata'] = [];
        $data['businessunits'] = $businessunits;
        $html = view("Admin/businessverticaladd", $data);
        echo $html;
    }
    /**
     * This controller function is used to save business vertical data in database.
     * @author Kavita Daware
     * @access public
     * @package businessvertical
     * @param string $bu_name Business Unit Name
     * * @param string $bv_name Business vertical Name
     * @param string $bv_description Business vertical Description
     * @return json
     */
    public function businessverticaladdsubmit(Request $request)
    {
        $data = $this->iam->addBusinessvertical(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to load business vertical edit form with existing data for selected businessvertical
     * @author Kavita Daware
     * @access public
     * @package businessvertical
     * @param \Illuminate\Http\Request $request
     * @param $bv_id Business Vertical Unique Id
     * @return string
     */
    public function businessverticaledit(Request $request)
    {
        $bv_id = $request->id;
        $input_req = ['bv_id' => $bv_id];
        $data = $this->iam->editBusinessvertical(['form_params' => $input_req]);
        $data['businessverticaldata'] = $data['content'];
        $data['bv_id'] = $bv_id;
        $limit_offset = limitoffset(0, 0);
        $form_params['limit'] = $limit_offset['limit'];
        $form_params['page'] = $limit_offset['page'];
        $form_params['offset'] = $limit_offset['offset'];
        $options = ['form_params' => $form_params];
        $businessunits_resp = $this->iam->getBusinessunit($options);
        if ($businessunits_resp['is_error'])
        {
            $businessunits = [];
        }
        else
        {
            $businessunits = _isset(_isset($businessunits_resp, 'content'), 'records');
        }
        //print_r($data);exit;
        $data['businessunits'] = $businessunits;
        //print_r($data); exit;
        $html = view("Admin/businessverticaladd", $data);
        echo $html;

    }
    /**
     * This controller function is used to update business vertical data in database.
     * @author Kavita Daware
     * @access public
     * @package businessvertical
     * @param UUID $bv_id Business vertical Unique Id
     * @param string $bv_name Business vertical Name
     * @param string $bu_name Business unit Name
     * @param string $bv_description Business vertical Description
     * @return json
     */
    public function businessverticaleditsubmit(Request $request)
    {
        $data = $this->iam->updateBusinessvertical(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to delete business vertical data from database.
     * @author Kavita Daware
     * @access public
     * @package businessvertical
     * @param UUID $bv_id Business Unit Unique Id
     * @return json
     */
    public function businessverticaldelete(Request $request)
    {
        $data = $this->iam->deleteBusinessvertical(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }
}
