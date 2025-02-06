<?php

namespace App\Http\Controllers\Cmdb;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use Illuminate\Http\Request;
use View;

/**
 *SoftwareTypeController class is implemented to do SoftwareType operations.
 * @author Kavita Daware
 * @package softwaretype
 */
class SoftwareTypeController extends Controller
{
    /**
     * Contructor function to initiate the API service and Request data
     * @author Kavita Daware
     * @access public
     * @package softwaretype
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
     * SoftwareTypeController function is implemented to initiate a page to get list of SoftwareType.
     * @author Kavita Daware
     * @access public
     * @package softwaretype
     * @return string
     */

    public function softwaretypes()
    {
        $topfilter = ['gridsearch' => true, 'jsfunction' => 'softwaretypeList()', 'gridadvsearch' => false];
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter, '', [""]);
        $data['pageTitle'] = trans('title.softwaretype');
        $data['includeView'] = view("Cmdb/softwaretypes", $data);
        return view('template', $data);
    }
    /**
     * This controller function is implemented to get list of Software Type.
     * @author Kavita Daware
     * @access public
     * @package softwaretype
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */

    public function softwaretypelist()
    {
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

        $softwaretype__resp = $this->itam->getsoftwaretype($options);
        if ($softwaretype__resp['is_error'])
        {
            $is_error = $softwaretype__resp['is_error'];
            $msg = $softwaretype__resp['msg'];
        }
        else
        {
            $is_error = false;
            $softwaretypes = _isset(_isset($softwaretype__resp, 'content'), 'records');
            
            $paging['total_rows'] = _isset(_isset($softwaretype__resp, 'content'), 'totalrecords');
            $paging['showpagination'] = true;
            $paging['jsfunction'] = 'softwaretypeList()';
            
            $view = 'Cmdb/softwaretypelist';
            $content = $this->emlib->emgrid($softwaretypes, $view, $columns = [], $paging);
        }

        $response["html"] = $content;
        $response["is_error"] = $is_error;
        $response["msg"] = $msg;
        echo json_encode($response);
    }
    /**
     * This controller function is used to load Software Type add form.
     * @author Kavita Daware
     * @access public
     * @package softwaretype
     * @return string
     */
    public function softwaretypeadd(Request $request)
    {
        $data['software_type_id'] = '';
        $softwaretypedata = [];
        $data['softwaretypedata'] = $softwaretypedata;
        $html = view("Cmdb/softwaretypeadd", $data);
        echo $html;
    }
    /**
     * This controller function is used to save Software Type data in database.
     * @author Kavita Daware
     * @access public
     * @package softwaretype
     * @param string $softwaretype_type Software Type
     * @param string $softwaretype_description Software Type Description
     * @return json
     */
    public function softwaretypeaddsubmit(Request $request)
    {
        if(!empty(config('app.env')) && config('app.env') != 'production') $request['is_default'] = 'y';
        $data = $this->itam->addsoftwaretype(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to load softwaretype edit form with existing data for selected softwaretype
     * @author Kavita Daware
     * @access public
     * @package softwaretype
     * @param \Illuminate\Http\Request $request
     * @param $software_type_id softwaretype Unique Id
     * @return string
     */
    public function softwaretypeedit(Request $request)
    {
        $software_type_id = $request->id;
        $input_req = ['software_type_id' => $software_type_id];
        $data = $this->itam->editsoftwaretype(['form_params' => $input_req]);

        $data['software_type_id'] = $software_type_id;
        $data['softwaretypedata'] = $data['content'];

        $html = view("Cmdb/softwaretypeadd", $data);
        echo $html;
    }
    /**
     * This controller function is used to update softwaretype data in database.
     * @author Kavita Daware
     * @access public
     * @package softwaretype
     * @param UUID $software_type_id softwaretype  Unique Id
     * @param string $softwaretype_type softwaretype
     * @param string $softwaretype_description softwaretype Description
     * @return json
     */
    public function softwaretypeeditsubmit(Request $request)
    {
        $data = $this->itam->updatesoftwaretype(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to delete softwaretype  data from database.
     * @author Kavita Daware
     * @access public
     * @package softwaretype
     * @param UUID $software_type_id softwaretype Unique Id
     * @return json
     */
    public function softwaretypedelete(Request $request)
    {
        $data = $this->itam->deletesoftwaretype(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }
}
