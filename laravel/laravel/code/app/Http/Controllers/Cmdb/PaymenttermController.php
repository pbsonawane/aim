<?php

namespace App\Http\Controllers\Cmdb;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use Illuminate\Http\Request;
use View;

/**
 * Paymentterm Controller class is implemented to do Paymentterm operations.
 * @author Bhushan Amrutkar
 * @package Contract
 */
class PaymenttermController extends Controller
{
    /**
     * Contructor function to initiate the API service and Request data
     * @author Bhushan Amrutkar
     * @access public
     * @package Paymentterm
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
     * Paymentterm Controller function is implemented to initiate a page to get list of Paymentterms
     * @author Bhushan Amrutkar
     * @access public
     * @package paymentterm
     * @return string
     */

    public function paymentterms()
    {
        $topfilter           = array('gridsearch' => true, 'jsfunction' => 'paymenttermList()', 'gridadvsearch' => false);
        $data['emgridtop']   = $this->emlib->emgridtop($topfilter, '', array("payment_term"));
        $data['pageTitle']   = trans('title.paymentterm');
        $data['includeView'] = view("Cmdb/paymentterm", $data);
        return view('template', $data);
    }
    /**
     * This controller function is implemented to get list of Paymentterms.
     * @author Bhushan Amrutkar
     * @access public
     * @package paymentterm
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */

    public function paymenttermlist()
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

        $paymentterm_resp = $this->itam->getpaymentterms($options);
        //print_r($paymentterm_resp); die;
        if ($paymentterm_resp['is_error']) {
            $is_error = $paymentterm_resp['is_error'];
            $msg      = $paymentterm_resp['msg'];
        } else {
            $is_error                 = false;
            $paymentterms             = _isset(_isset($paymentterm_resp, 'content'), 'records');
            $paging['total_rows']     = _isset(_isset($paymentterm_resp, 'content'), 'totalrecords');
            $paging['showpagination'] = true;
            $paging['jsfunction']     = 'paymenttermList()';

            $view = 'Cmdb/paymenttermlist';
            //$paymentterm_id = isset($paymentterms[0]['paymentterm_id']) ? $paymentterms[0]['paymentterm_id'] : "";
            $content = $this->emlib->emgrid($paymentterms, $view, $columns = array(), $paging);
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
     * This controller function is used to load paymentterm add form.
     * @author Bhushan Amrutkar
     * @access public
     * @package paymentterm
     * @return string
     */
    public function paymenttermadd(Request $request)
    {
        $data['paymentterm_id']  = '';
        $paymenttermdata         = array();
        $data['paymenttermdata'] = $paymenttermdata;
        $html                    = view("Cmdb/paymenttermadd", $data);
        echo $html;
    }
    /**
     * This controller function is used to save paymentterm data in database.
     * @author Bhushan Amrutkar
     * @access public
     * @package paymentterm
     * @param string $paymentterm_id
     * @return json
     */
    public function paymenttermaddsubmit(Request $request)
    {
        $data = $this->itam->addpaymentterm(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to load paymentterm edit form with existing data for selected paymentterm
     * @author Bhushan Amrutkar
     * @access public
     * @package paymentterm
     * @param \Illuminate\Http\Request $request
     * @param $paymentterm_id paymentterm Unique Id
     * @return string
     */
    public function paymenttermedit(Request $request)
    {
        $paymentterm_id = $request->id;
        $input_req      = array('paymentterm_id' => $paymentterm_id);
        $data           = $this->itam->editpaymentterm(array('form_params' => $input_req));

        $data['paymentterm_id']  = $paymentterm_id;
        $data['paymenttermdata'] = $data['content'];

        $html = view("Cmdb/paymenttermadd", $data);
        echo $html;
    }
    /**
     * This controller function is used to update paymentterm data in database.
     * @author Bhushan Amrutkar
     * @access public
     * @package Paymentterm
     * @param UUID $paymentterm_id Paymentterm  Unique Id
     * @return json
     */
    public function paymenttermeditsubmit(Request $request)
    {
        $data = $this->itam->updatepaymentterm(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to delete paymentterm  data from database.
     * @author Bhushan Amrutkar
     * @access public
     * @package Paymentterm
     * @param UUID $paymentterm_id Unique Id
     * @return json
     */
    public function paymenttermdelete(Request $request)
    {
        $data = $this->itam->deletepaymentterm(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }
}
