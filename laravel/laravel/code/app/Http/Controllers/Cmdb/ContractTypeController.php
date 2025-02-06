<?php

namespace App\Http\Controllers\Cmdb;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use Illuminate\Http\Request;
use View;

/**
 * Contract Controller class is implemented to do Contract Type operations.
 * @author Kavita Daware
 * @package Contract
 */
class ContractTypeController extends Controller
{
    /**
     * Contructor function to initiate the API service and Request data
     * @author Kavita Daware
     * @access public
     * @package Contract
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
     * Contract Controller function is implemented to initiate a page to get list of Contract Type.
     * @author Kavita Daware
     * @access public
     * @package contracttype
     * @return string
     */

    public function contracttypes()
    {
        $topfilter = array('gridsearch' => true, 'jsfunction' => 'contracttypeList()', 'gridadvsearch' => false);
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter, '', array("contract_type"));
        $data['pageTitle'] = trans('title.contract_type');
        $data['includeView'] = view("Cmdb/contracttypes", $data);
        return view('template', $data);
    }
    /**
     * This controller function is implemented to get list of Contract Type.
     * @author Kavita Daware
     * @access public
     * @package contracttype
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */

    public function contracttypeList()
    {
        $paging = array();
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

        $contracttype__resp = $this->itam->getcontracttype($options);
        if ($contracttype__resp['is_error'])
        {
            $is_error = $contracttype__resp['is_error'];
            $msg = $contracttype__resp['msg'];
        }
        else
        {
            $is_error = false;
            $contracttypes = _isset(_isset($contracttype__resp, 'content'), 'records');
            $paging['total_rows'] = _isset(_isset($contracttype__resp, 'content'), 'totalrecords');
            $paging['showpagination'] = true;
            $paging['jsfunction'] = 'contracttypeList()';
            
            $view = 'Cmdb/contracttypelist';
            $content = $this->emlib->emgrid($contracttypes, $view, $columns = array(), $paging);
        }

        $response["html"] = $content;
        $response["is_error"] = $is_error;
        $response["msg"] = $msg;
        echo json_encode($response);
    }
    /**
     * This controller function is used to load contract type add form.
     * @author Kavita Daware
     * @access public
     * @package contracttype
     * @return string
     */
    public function contracttypeadd(Request $request)
    {
        $data['contract_type_id'] = '';
        $contracttypedata = array();
        $data['contracttypedata'] = $contracttypedata;
        $html = view("Cmdb/contracttypeadd", $data);
        echo $html;
    }
    /**
     * This controller function is used to save contract type data in database.
     * @author Kavita Daware
     * @access public
     * @package contracttype
     * @param string $contract_type contract type
     * @param string $contract_description contract type Description
     * @return json
     */
    public function contracttypeaddsubmit(Request $request)
    {
        if(!empty(config('app.env')) && config('app.env') != 'production') $request['is_default'] = 'y';
        $data = $this->itam->addcontracttype(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to load contracttype edit form with existing data for selected contracttype
     * @author Kavita Daware
     * @access public
     * @package contracttype
     * @param \Illuminate\Http\Request $request
     * @param $contract_type_id contracttype Unique Id
     * @return string
     */
    public function contracttypeedit(Request $request)
    {
        $contract_type_id = $request->id;
        $input_req = array('contract_type_id' => $contract_type_id);
        $data = $this->itam->editcontracttype(array('form_params' => $input_req));

        $data['contract_type_id'] = $contract_type_id;
        $data['contracttypedata'] = $data['content'];

        $html = view("Cmdb/contracttypeadd", $data);
        echo $html;
    }
    /**
     * This controller function is used to update contracttype data in database.
     * @author Kavita Daware
     * @access public
     * @package contracttype
     * @param UUID $contract_type_id contracttype  Unique Id
     * @param string $contract_type contracttype
     * @param string $contract_description contracttype Description
     * @return json
     */
    public function contracttypeeditsubmit(Request $request)
    {
        $data = $this->itam->updatecontracttype(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to delete contracttype  data from database.
     * @author Kavita Daware
     * @access public
     * @package contracttype
     * @param UUID $contract_type_id contracttype Unique Id
     * @return json
     */
    public function contracttypedelete(Request $request)
    {
        $data = $this->itam->deletecontracttype(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }
}
