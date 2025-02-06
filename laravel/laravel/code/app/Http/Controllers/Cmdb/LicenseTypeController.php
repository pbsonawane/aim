<?php

namespace App\Http\Controllers\Cmdb;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use Illuminate\Http\Request;
use View;

/**
 *licensetypeController class is implemented to do licensetype operations.
 * @author Kavita Daware
 * @package licensetype
 */
class LicenseTypeController extends Controller
{
    /**
     * Contructor function to initiate the API service and Request data
     * @author Kavita Daware
     * @access public
     * @package licensetype
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
     * licensetypeController function is implemented to initiate a page to get list of licensetype.
     * @author Kavita Daware
     * @access public
     * @package licensetype
     * @return string
     */

    public function licensetype()
    {
        $topfilter = array('gridsearch' => true, 'jsfunction' => 'licensetypeList()', 'gridadvsearch' => false);
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter, '', array("license_type"));
        $data['pageTitle'] = trans('title.licensetype');
        $data['includeView'] = view("Cmdb/licensetype", $data);
        return view('template', $data);
    }
    /**
     * This controller function is implemented to get list of License Type.
     * @author Kavita Daware
     * @access public
     * @package licensetype
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */

    public function licensetypelist()
    {
        try
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

            $licensetype__resp = $this->itam->getlicensetype($options);
            if ($licensetype__resp['is_error'])
            {
                $is_error = $licensetype__resp['is_error'];
                $msg = $licensetype__resp['msg'];
            }
            else
            {
                $is_error = false;
                $licensetypes = _isset(_isset($licensetype__resp, 'content'), 'records');
                $paging['total_rows'] = _isset(_isset($licensetype__resp, 'content'), 'totalrecords');
                $paging['showpagination'] = true;
                $paging['jsfunction'] = 'licensetypeList()';
                
                $view = 'Cmdb/licensetypelist';
                $content = $this->emlib->emgrid($licensetypes, $view, $columns = array(), $paging);
            }

            $response["html"] = $content;
            $response["is_error"] = $is_error;
            $response["msg"] = $msg;
            echo json_encode($response);
        }
        catch(\Exception $e){
        
            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            //save_errlog("licensetypelist","This controller function is implemented to get list of License Type..", $request->all(), $response['msg']);
            echo json_encode($response);
        }
        catch (\Error $e) {
        
            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            //save_errlog("licensetypelist","This controller function is implemented to get list of License Type.", $request->all(), $response['msg']);
            echo json_encode($response);
        }
    }
    /**
     * This controller function is used to load License Type add form.
     * @author Kavita Daware
     * @access public
     * @package licensetype
     * @return string
     */
    public function licensetypeadd(Request $request)
    {
        $data['license_type_id'] = '';
        $license_type_id = $request->license_type_id;
        $form_params['license_type_id'] = $license_type_id;
        $options = ['form_params' => $form_params];
        $installation_allow_resp = $this->itam->getlicensetype($options);

        if ($installation_allow_resp['is_error'])
        {
            $installation_allow = array();
        }
        else
        {
            $installation_allow = _isset(_isset($installation_allow_resp, 'content'), 'records');
        }
        $data['installation_allow'] = $installation_allow;
        //print_r($data['installation_allow']);
        $licensetypedata = array();
        $data['licensetypedata'] = $licensetypedata;
        
        $html = view("Cmdb/licensetypeadd", $data);   
        echo $html;
    }
    /**
     * This controller function is used to save License Type data in database.
     * @author Kavita Daware
     * @access public
     * @package licensetype
     * @param string $license_type License Type
     * @param string $licensetype_description License Type Description
     * @return json
     */
    public function licensetypeaddsubmit(Request $request)
    {
        if(!empty(config('app.env')) && config('app.env') != 'production') $request['is_default'] = 'y';
        $data = $this->itam->addlicensetype(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to load licensetype edit form with existing data for selected licensetype
     * @author Kavita Daware
     * @access public
     * @package licensetype
     * @param \Illuminate\Http\Request $request
     * @param $license_type_id licensetype Unique Id
     * @return string
     */
    public function licensetypeedit(Request $request)
    {
        $license_type_id = $request->id;
        $input_req = array('license_type_id' => $license_type_id);
        $data = $this->itam->editlicensetype(array('form_params' => $input_req));
        //print_r($data);
        $data['license_type_id'] = $license_type_id;
        $data['licensetypedata'] = $data['content'];
        $data['edit'] = true;

        $html = view("Cmdb/licensetypeadd", $data);
        echo $html;
    }
    /**
     * This controller function is used to update licensetype data in database.
     * @author Kavita Daware
     * @access public
     * @package licensetype
     * @param UUID $license_type_id licensetype  Unique Id
     * @param string $licensetype licensetype
     * @param string $licensetype_description licensetype Description
     * @return json
     */
    public function licensetypeeditsubmit(Request $request)
    {
        /*$requestData = $request->all();
			
			if(isset($request->is_perpetual))
			{
				$requestData['is_perpetual'] = "y";
			}
			else
			{
				$requestData['is_perpetual'] = "n";
			}*/
        $data = $this->itam->updatelicensetype(array('form_params' => $request->all()));
        //$data = $this->itam->updatelicensetype($requestData);
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to delete licensetype  data from database.
     * @author Kavita Daware
     * @access public
     * @package licensetype
     * @param UUID $license_type_id licensetype Unique Id
     * @return json
     */
    public function licensetypedelete(Request $request)
    {
        $data = $this->itam->deletelicensetype(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }
}
