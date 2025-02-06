<?php

namespace App\Http\Controllers\Cmdb;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use Illuminate\Http\Request;
use View;

/**
 * ShiptoController class is implemented to do Ship To operations.
 * @author Bhushan Amrutkar
 * @package ship to
 */
class ShiptoController extends Controller
{
    /**
     * Contructor function to initiate the API service and Request data
     * @author Bhushan Amrutkar
     * @access public
     * @package ship to
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
     * Ship To Controller function is implemented to initiate a page to get list of Ship To
     * @author Bhushan Amrutkar
     * @access public
     * @package ship to
     * @return string
     */

    public function shiptos()
    {
        $topfilter           = ['gridsearch' => true, 'jsfunction' => 'shiptoList()', 'gridadvsearch' => false];
        $data['emgridtop']   = $this->emlib->emgridtop($topfilter, '', ["address"]);
        $data['pageTitle']   = trans('title.shipto');
        $data['includeView'] = view("Cmdb/shipto", $data);
        return view('template', $data);
    }
    /**
     * This controller function is implemented to get list of costcenters.
     * @author Bhushan Amrutkar
     * @access public
     * @package ship to
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */

    public function shiptolist()
    {
        //try
        //{
        $paging        = [];
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

        $shipto_resp = $this->itam->getshiptos($options);
        $shiptos     = _isset(_isset($shipto_resp, 'content'), 'records');
        if ($shiptos == '') {
            $shiptos = [];
        }

        $location_resp = $this->iam->getLocations([]);
        $loc_data      = _isset(_isset($location_resp, 'content'), 'records');
        //echo "<pre>"; print_r($loc_data);
        $all_loc = [];
        if (isset($loc_data) && is_array($loc_data) && count($loc_data) > 0) {
            foreach ($loc_data as $loc) {
                $location_id           = isset($loc['location_id']) ? $loc['location_id'] : "";
                $location_name         = isset($loc['location_name']) ? $loc['location_name'] : "";
                $all_loc[$location_id] = $location_name;
            }
        }
        //print_r($all_loc);

        foreach ($shiptos as $key => $loc) {
            $location_id = isset($loc['locations']) ? $loc['locations'] : "";

            $shiptos[$key]['location_name'] = isset($all_loc[$location_id]) ? $all_loc[$location_id] : "";

        }

        //print_r($shiptos);exit;

        if ($shipto_resp['is_error']) {
            $is_error = $shipto_resp['is_error'];
            $msg      = $shipto_resp['msg'];
        } else {
            $is_error = false;

            //$shiptos = _isset(_isset($shipto_resp, 'content'), 'records');

            $paging['total_rows']     = _isset(_isset($shipto_resp, 'content'), 'totalrecords');
            $paging['showpagination'] = true;
            $paging['jsfunction']     = 'shiptoList()';

            $view = 'Cmdb/shiptolist';

            $content = $this->emlib->emgrid($shiptos, $view, $columns = [], $paging);
        }

        $response["html"]     = $content;
        $response["is_error"] = $is_error;
        $response["msg"]      = $msg;
        //$response['shipto_id'] = $shipto_id;
        echo json_encode($response);

    }

    /**
     * This controller function is used to load shipto add form.
     * @author Bhushan Amrutkar
     * @access public
     * @package ship to
     * @return string
     */
    public function shiptoadd(Request $request)
    {
        $shipto_id                    = $request->shipto_id;
        $data['shipto_id']            = '';
        $form_params['limit']         = 0;
        $form_params['page']          = 0;
        $form_params['offset']        = 0;
        $form_params['searchkeyword'] = '';
        $form_params['shipto_id']     = $shipto_id;
        $options                      = ['form_params' => $form_params];
        $data['location_id']          = '';
        $options                      = ['form_params' => $form_params];
        $location_resp                = $this->iam->getLocations($options);
        if ($location_resp['is_error']) {
            $locations = [];
        } else {
            $locations = _isset(_isset($location_resp, 'content'), 'records');
        }

        $shiptodata = [];

        $data['locations']  = $locations;
        $data['shiptodata'] = $shiptodata;
        $html               = view("Cmdb/shiptoadd", $data);
        echo $html;
    }
    /**
     * This controller function is used to save shipto data in database.
     * @author Bhushan Amrutkar
     * @access public
     * @package ship to
     * @param string $shipto
     * @return json
     */
    public function shiptoaddsubmit(Request $request)
    {
        $data = $this->itam->addshipto(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }

    /**
     * This controller function is used to load shipto edit form with existing data for selected shipto
     * @author Bhushan Amrutkar
     * @access public
     * @package ship to
     * @param \Illuminate\Http\Request $request
     * @param $shipto_id shipto Unique Id
     * @return string
     */
    public function shiptoedit(Request $request)
    {
        $shipto_id             = $request->id;
        $input_req             = ['shipto_id' => $shipto_id];
        $data                  = $this->itam->editshipto(['form_params' => $input_req]);
        $data['shipto_id']     = $shipto_id;
        $limit_offset          = limitoffset(0, 0);
        $form_params['limit']  = $limit_offset['limit'];
        $form_params['page']   = $limit_offset['page'];
        $form_params['offset'] = $limit_offset['offset'];

        $data['location_id'] = '';
        $options             = ['form_params' => $form_params];
        $location_resp       = $this->iam->getLocations($options);
        if ($location_resp['is_error']) {
            $locations = [];
        } else {
            $locations = _isset(_isset($location_resp, 'content'), 'records');
        }
        $data['locations']  = $locations;
        $data['shiptodata'] = $data['content'];
        $html               = view("Cmdb/shiptoadd", $data);
        echo $html;
    }

    /**
     * This controller function is used to update shipto data in database.
     * @author Bhushan Amrutkar
     * @access public
     * @package ship to
     * @param UUID $shipto_id shipto  Unique Id
     * @param string $shipto
     * @return json
     */
    public function shiptoeditsubmit(Request $request)
    {
        $data = $this->itam->updateshipto(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to delete shipto  data from database.
     * @author Bhushan Amrutkar
     * @access public
     * @package ship to
     * @param UUID $cc_id shipto Unique Id
     * @return json
     */
    public function shiptodelete(Request $request)
    {
        $data = $this->itam->deleteshipto(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }
}
