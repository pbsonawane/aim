<?php

namespace App\Http\Controllers\Cmdb;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use Illuminate\Http\Request;
use View;

/**
 * Delivery Controller class is implemented to do Delivery operations.
 * @author Bhushan Amrutkar
 * @package Contract
 */
class DeliveryController extends Controller
{
    /**
     * Contructor function to initiate the API service and Request data
     * @author Bhushan Amrutkar
     * @access public
     * @package Delivery
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
     * Delivery Controller function is implemented to initiate a page to get list of Delivery
     * @author Bhushan Amrutkar
     * @access public
     * @package delivery
     * @return string
     */

    public function delivery()
    {
        $topfilter           = ['gridsearch' => true, 'jsfunction' => 'deliveryList()', 'gridadvsearch' => false];
        $data['emgridtop']   = $this->emlib->emgridtop($topfilter, '', ["delivery"]);
        $data['pageTitle']   = trans('title.delivery');
        $data['includeView'] = view("Cmdb/delivery", $data);
        return view('template', $data);
    }
    /**
     * This controller function is implemented to get list of Delivery.
     * @author Bhushan Amrutkar
     * @access public
     * @package delivery
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */

    public function deliverylist()
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

        $delivery_resp = $this->itam->getdelivery($options);
        //print_r($delivery_resp); die;
        if ($delivery_resp['is_error']) {
            $is_error = $delivery_resp['is_error'];
            $msg      = $delivery_resp['msg'];
        } else {
            $is_error                 = false;
            $delivery             = _isset(_isset($delivery_resp, 'content'), 'records');
            $paging['total_rows']     = _isset(_isset($delivery_resp, 'content'), 'totalrecords');
            $paging['showpagination'] = true;
            $paging['jsfunction']     = 'deliveryList()';

            $view = 'Cmdb/deliverylist';
            //$delivery_id = isset($delivery[0]['delivery_id']) ? $delivery[0]['delivery_id'] : "";
            $content = $this->emlib->emgrid($delivery, $view, $columns = [], $paging);
        }

        $response["html"]     = $content;
        $response["is_error"] = $is_error;
        $response["msg"]      = $msg;
        //$response['vendor_id'] = $vendor_id;
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
     * This controller function is used to load delivery add form.
     * @author Bhushan Amrutkar
     * @access public
     * @package delivery
     * @return string
     */
    public function deliveryadd(Request $request)
    {
        $data['delivery_id']  = '';
        $deliverydata         = [];
        $data['deliverydata'] = $deliverydata;
        $html                    = view("Cmdb/deliveryadd", $data);
        echo $html;
    }
    /**
     * This controller function is used to save delivery data in database.
     * @author Bhushan Amrutkar
     * @access public
     * @package delivery
     * @param string $delivery_id
     * @return json
     */
    public function deliveryaddsubmit(Request $request)
    {
        $data = $this->itam->adddelivery(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to load delivery edit form with existing data for selected delivery
     * @author Bhushan Amrutkar
     * @access public
     * @package delivery
     * @param \Illuminate\Http\Request $request
     * @param $delivery_id delivery Unique Id
     * @return string
     */
    public function deliveryedit(Request $request)
    {
        $delivery_id = $request->id;
        $input_req      = ['delivery_id' => $delivery_id];
        $data           = $this->itam->editdelivery(['form_params' => $input_req]);

        $data['delivery_id']  = $delivery_id;
        $data['deliverydata'] = $data['content'];

        $html = view("Cmdb/deliveryadd", $data);
        echo $html;
    }
    /**
     * This controller function is used to update delivery data in database.
     * @author Bhushan Amrutkar
     * @access public
     * @package Delivery
     * @param UUID $delivery_id Delivery  Unique Id
     * @return json
     */
    public function deliveryeditsubmit(Request $request)
    {
        $data = $this->itam->updatedelivery(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to delete delivery  data from database.
     * @author Bhushan Amrutkar
     * @access public
     * @package Delivery
     * @param UUID $delivery_id Unique Id
     * @return json
     */
    public function deliverydelete(Request $request)
    {
        $data = $this->itam->deletedelivery(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }
}
