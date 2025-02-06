<?php

namespace App\Http\Controllers\Cmdb;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use Illuminate\Http\Request;
use View;

/**
 *SoftwareCategoryController class is implemented to do softwarecategory operations.
 * @author Kavita Daware
 * @package softwarecategory
 */
class SoftwareCategoryController extends Controller
{
    /**
     * Contructor function to initiate the API service and Request data
     * @author Kavita Daware
     * @access public
     * @package softwarecategory
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
     * softwarecategoryController function is implemented to initiate a page to get list of softwarecategory.
     * @author Kavita Daware
     * @access public
     * @package softwarecategory
     * @return string
     */

    public function softwarecategory()
    {
        $topfilter = ['gridsearch' => true, 'jsfunction' => 'softwarecategoryList()', 'gridadvsearch' => false];
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter, '', ["software_type"]);
        $data['pageTitle'] = trans('title.softwarecategory');
        $data['includeView'] = view("Cmdb/softwarecategory", $data);
        return view('template', $data);
    }
    /**
     * This controller function is implemented to get list of Software Category.
     * @author Kavita Daware
     * @access public
     * @package softwarecategory
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */

    public function softwarecategorylist()
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

        $softwarecategory__resp = $this->itam->getsoftwarecategory($options);
        if ($softwarecategory__resp['is_error'])
        {
            $is_error = $softwarecategory__resp['is_error'];
            $msg = $softwarecategory__resp['msg'];
        }
        else
        {
            $is_error = false;
            $softwarecategorys = _isset(_isset($softwarecategory__resp, 'content'), 'records');
            $paging['total_rows'] = _isset(_isset($softwarecategory__resp, 'content'), 'totalrecords');
            $paging['showpagination'] = true;
            $paging['jsfunction'] = 'softwarecategoryList()';
            
            $view = 'Cmdb/softwarecategorylist';
            $content = $this->emlib->emgrid($softwarecategorys, $view, $columns = [], $paging);
        }

        $response["html"] = $content;
        $response["is_error"] = $is_error;
        $response["msg"] = $msg;
        echo json_encode($response);
    }
    /**
     * This controller function is used to load Software category add form.
     * @author Kavita Daware
     * @access public
     * @package softwarecategory
     * @return string
     */
    public function softwarecategoryadd(Request $request)
    {
        $data['software_category_id'] = '';
        $softwarecategorydata = [];
        $data['softwarecategorydata'] = $softwarecategorydata;
        $html = view("Cmdb/softwarecategoryadd", $data);
        echo $html;
    }
    /**
     * This controller function is used to save Software category data in database.
     * @author Kavita Daware
     * @access public
     * @package softwarecategory
     * @param string $softwarecategory Software category
     * @param string $softwarecategory_description Software category Description
     * @return json
     */
    public function softwarecategoryaddsubmit(Request $request)
    {
        if(!empty(config('app.env')) && config('app.env') != 'production') $request['is_default'] = 'y';
        $data = $this->itam->addsoftwarecategory(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to load softwarecategory edit form with existing data for selected softwarecategory
     * @author Kavita Daware
     * @access public
     * @package softwarecategory
     * @param \Illuminate\Http\Request $request
     * @param $software_category_id softwarecategory Unique Id
     * @return string
     */
    public function softwarecategoryedit(Request $request)
    {
        $software_category_id = $request->id;
        $input_req = ['software_category_id' => $software_category_id];
        $data = $this->itam->editsoftwarecategory(['form_params' => $input_req]);

        $data['software_category_id'] = $software_category_id;
        $data['softwarecategorydata'] = $data['content'];

        $html = view("Cmdb/softwarecategoryadd", $data);
        echo $html;
    }
    /**
     * This controller function is used to update softwarecategory data in database.
     * @author Kavita Daware
     * @access public
     * @package softwarecategory
     * @param UUID $software_category_id softwarecategory  Unique Id
     * @param string $softwarecategory softwarecategory
     * @param string $softwarecategory_description softwarecategory Description
     * @return json
     */
    public function softwarecategoryeditsubmit(Request $request)
    {
        $data = $this->itam->updatesoftwarecategory(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to delete softwarecategory  data from database.
     * @author Kavita Daware
     * @access public
     * @package softwarecategory
     * @param UUID $software_category_id softwarecategory Unique Id
     * @return json
     */
    public function softwarecategorydelete(Request $request)
    {
        $data = $this->itam->deletesoftwarecategory(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }
}
