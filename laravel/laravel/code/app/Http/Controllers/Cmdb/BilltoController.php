<?php

namespace App\Http\Controllers\Cmdb;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use Illuminate\Http\Request;
use View;

/**
 * BilltoController class is implemented to do Bill To operations.
 * @author Bhushan Amrutkar
 * @package bill to
 */
class BilltoController extends Controller
{
    /**
     * Contructor function to initiate the API service and Request data
     * @author Bhushan Amrutkar
     * @access public
     * @package bill to
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
     * Bill To Controller function is implemented to initiate a page to get list of Bill To
     * @author Bhushan Amrutkar
     * @access public
     * @package bill to
     * @return string
     */

    public function billtos()
    {
        $topfilter           = array('gridsearch' => true, 'jsfunction' => 'billtoList()', 'gridadvsearch' => false);
        $data['emgridtop']   = $this->emlib->emgridtop($topfilter, '', array("address"));
        $data['pageTitle']   = trans('title.billto');
        $data['includeView'] = view("Cmdb/billto", $data);
        return view('template', $data);
    }
    /**
     * This controller function is implemented to get list of costcenters.
     * @author Bhushan Amrutkar
     * @access public
     * @package bill to
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */

    public function billtolist()
    {
        //try
        //{
        $paging        = array();
        $fromtime      = $totime      = '';
        $limit         = _isset($this->request_params, 'limit', config('enconfig.def_limit_short'));
        $exporttype    = _isset($this->request_params, 'exporttype');
        $page          = _isset($this->request_params, 'page', config('enconfig.page'));
        $searchkeyword = _isset($this->request_params, 'searchkeyword');

        $is_error     = false;
        $msg          = '';
        $content      = "";
        $limit_offset = limitoffset($limit, $page);
        $page         = $limit_offset['page'];
        $limit        = $limit_offset['limit'];
        $offset       = $limit_offset['offset'];

        $form_params['limit']         = $paging['limit']         = $limit;
        $form_params['page']          = $paging['page']          = $page;
        $form_params['offset']        = $paging['offset']        = $offset;
        $form_params['searchkeyword'] = $searchkeyword;

        $options = ['form_params' => $form_params];

        $billto_resp = $this->itam->getbilltos($options);
        $billtos     = _isset(_isset($billto_resp, 'content'), 'records');
        if ($billtos == '') {
            $billtos = array();
        }

        $location_resp = $this->iam->getLocations(array());
        $loc_data      = _isset(_isset($location_resp, 'content'), 'records');
        //echo "<pre>"; print_r($loc_data);
        $all_loc = array();
        if (isset($loc_data) && is_array($loc_data) && count($loc_data) > 0) {
            foreach ($loc_data as $loc) {
                $location_id           = isset($loc['location_id']) ? $loc['location_id'] : "";
                $location_name         = isset($loc['location_name']) ? $loc['location_name'] : "";
                $all_loc[$location_id] = $location_name;
            }
        }
        //print_r($all_loc);

        foreach ($billtos as $key => $loc) {
            $location_id = isset($loc['locations']) ? $loc['locations'] : "";

            $billtos[$key]['location_name'] = isset($all_loc[$location_id]) ? $all_loc[$location_id] : "";

        }

        //print_r($billtos);exit;

        if ($billto_resp['is_error']) {
            $is_error = $billto_resp['is_error'];
            $msg      = $billto_resp['msg'];
        } else {
            $is_error = false;

            //$billtos = _isset(_isset($billto_resp, 'content'), 'records');

            $paging['total_rows']     = _isset(_isset($billto_resp, 'content'), 'totalrecords');
            $paging['showpagination'] = true;
            $paging['jsfunction']     = 'billtoList()';

            $view = 'Cmdb/billtolist';

            $content = $this->emlib->emgrid($billtos, $view, $columns = array(), $paging);
        }

        $response["html"]     = $content;
        $response["is_error"] = $is_error;
        $response["msg"]      = $msg;
        //$response['billto_id'] = $billto_id;
        echo json_encode($response);

    }

    /**
     * This controller function is used to load billto add form.
     * @author Bhushan Amrutkar
     * @access public
     * @package bill to
     * @return string
     */
    public function billtoadd(Request $request)
    {
        $billto_id                    = $request->billto_id;
        $data['billto_id']            = '';
        $form_params['limit']         = 0;
        $form_params['page']          = 0;
        $form_params['offset']        = 0;
        $form_params['searchkeyword'] = '';
        $form_params['billto_id']     = $billto_id;
        $options                      = ['form_params' => $form_params];
        $data['location_id']          = '';
        $options                      = ['form_params' => $form_params];
        $location_resp                = $this->iam->getLocations($options);
        if ($location_resp['is_error']) {
            $locations = array();
        } else {
            $locations = _isset(_isset($location_resp, 'content'), 'records');
        }

        $billtodata = array();

        $data['locations']  = $locations;
        $data['billtodata'] = $billtodata;
        $html               = view("Cmdb/billtoadd", $data);
        echo $html;
    }
    /**
     * This controller function is used to save billto data in database.
     * @author Bhushan Amrutkar
     * @access public
     * @package bill to
     * @param string $billto
     * @return json
     */
    public function billtoaddsubmit(Request $request)
    {
        $data = $this->itam->addbillto(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }

    /**
     * This controller function is used to load billto edit form with existing data for selected billto
     * @author Bhushan Amrutkar
     * @access public
     * @package bill to
     * @param \Illuminate\Http\Request $request
     * @param $billto_id billto Unique Id
     * @return string
     */
    public function billtoedit(Request $request)
    {
        $billto_id             = $request->id;
        $input_req             = array('billto_id' => $billto_id);
        $data                  = $this->itam->editbillto(array('form_params' => $input_req));
        $data['billto_id']     = $billto_id;
        $limit_offset          = limitoffset(0, 0);
        $form_params['limit']  = $limit_offset['limit'];
        $form_params['page']   = $limit_offset['page'];
        $form_params['offset'] = $limit_offset['offset'];

        $data['location_id'] = '';
        $options             = ['form_params' => $form_params];
        $location_resp       = $this->iam->getLocations($options);
        if ($location_resp['is_error']) {
            $locations = array();
        } else {
            $locations = _isset(_isset($location_resp, 'content'), 'records');
        }
        $data['locations']  = $locations;
        $data['billtodata'] = $data['content'];
        $html               = view("Cmdb/billtoadd", $data);
        echo $html;
    }

    /**
     * This controller function is used to update billto data in database.
     * @author Bhushan Amrutkar
     * @access public
     * @package bill to
     * @param UUID $billto_id billto  Unique Id
     * @param string $billto
     * @return json
     */
    public function billtoeditsubmit(Request $request)
    {
        $data = $this->itam->updatebillto(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to delete billto  data from database.
     * @author Bhushan Amrutkar
     * @access public
     * @package bill to
     * @param UUID $cc_id billto Unique Id
     * @return json
     */
    public function billtodelete(Request $request)
    {
        $data = $this->itam->deletebillto(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }
}
