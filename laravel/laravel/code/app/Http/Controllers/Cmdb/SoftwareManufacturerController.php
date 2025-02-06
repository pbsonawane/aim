<?php

namespace App\Http\Controllers\Cmdb;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use Illuminate\Http\Request;
use View;

/**
 *softwaremanufacturerController class is implemented to do softwaremanufacturer operations.
 * @author Kavita Daware
 * @package softwaremanufacturer
 */
class SoftwareManufacturerController extends Controller
{
    /**
     * Contructor function to initiate the API service and Request data
     * @author Kavita Daware
     * @access public
     * @package softwaremanufacturer
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
     * softwaremanufacturerController function is implemented to initiate a page to get list of softwaremanufacturer.
     * @author Kavita Daware
     * @access public
     * @package softwaremanufacturer
     * @return string
     */

    public function softwaremanufacturer()
    {
        $topfilter = array('gridsearch' => true, 'jsfunction' => 'softwaremanufacturerList()', 'gridadvsearch' => false);
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter, '', array("software_type"));
        $data['pageTitle'] = trans('title.softwaremanufacturer');
        $data['includeView'] = view("Cmdb/softwaremanufacturer", $data);
        return view('template', $data);
    }
    /**
     * This controller function is implemented to get list of Software manufacturer.
     * @author Kavita Daware
     * @access public
     * @package softwaremanufacturer
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */

    public function softwaremanufacturerlist()
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

        $softwaremanufacturer__resp = $this->itam->getsoftwaremanufacturer($options);
        if ($softwaremanufacturer__resp['is_error'])
        {
            $is_error = $softwaremanufacturer__resp['is_error'];
            $msg = $softwaremanufacturer__resp['msg'];
        }
        else
        {
            $is_error = false;
            $softwaremanufacturers = _isset(_isset($softwaremanufacturer__resp, 'content'), 'records');
            $paging['total_rows'] = _isset(_isset($softwaremanufacturer__resp, 'content'), 'totalrecords');
            $paging['showpagination'] = true;
            $paging['jsfunction'] = 'softwaremanufacturerList()';
            
            $view = 'Cmdb/softwaremanufacturerlist';
            $content = $this->emlib->emgrid($softwaremanufacturers, $view, $columns = array(), $paging);
        }

        $response["html"] = $content;
        $response["is_error"] = $is_error;
        $response["msg"] = $msg;
        echo json_encode($response);
    }
    /**
     * This controller function is used to load Software manufacturer add form.
     * @author Kavita Daware
     * @access public
     * @package softwaremanufacturer
     * @return string
     */
    public function softwaremanufactureradd(Request $request)
    {
        $data['software_manufacturer_id'] = '';
        $softwaremanufacturerdata = array();
        $data['softwaremanufacturerdata'] = $softwaremanufacturerdata;
        $html = view("Cmdb/softwaremanufactureradd", $data);
        echo $html;
    }
    /**
     * This controller function is used to save Software manufacturer data in database.
     * @author Kavita Daware
     * @access public
     * @package softwaremanufacturer
     * @param string $softwaremanufacturer Software manufacturer
     * @param string $softwaremanufacturer_description Software manufacturer Description
     * @return json
     */
    public function softwaremanufactureraddsubmit(Request $request)
    {
       if(!empty(config('app.env')) && config('app.env') != 'production') $request['is_default'] = 'y';
        $data = $this->itam->addsoftwaremanufacturer(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to load softwaremanufacturer edit form with existing data for selected softwaremanufacturer
     * @author Kavita Daware
     * @access public
     * @package softwaremanufacturer
     * @param \Illuminate\Http\Request $request
     * @param $software_manufacturer_id softwaremanufacturer Unique Id
     * @return string
     */
    public function softwaremanufactureredit(Request $request)
    {
        $software_manufacturer_id = $request->id;
        $input_req = array('software_manufacturer_id' => $software_manufacturer_id);
        $data = $this->itam->editsoftwaremanufacturer(array('form_params' => $input_req));

        $data['software_manufacturer_id'] = $software_manufacturer_id;
        $data['softwaremanufacturerdata'] = $data['content'];

        $html = view("Cmdb/softwaremanufactureradd", $data);
        echo $html;
    }
    /**
     * This controller function is used to update softwaremanufacturer data in database.
     * @author Kavita Daware
     * @access public
     * @package softwaremanufacturer
     * @param UUID $software_manufacturer_id softwaremanufacturer  Unique Id
     * @param string $softwaremanufacturer softwaremanufacturer
     * @param string $softwaremanufacturer_description softwaremanufacturer Description
     * @return json
     */
    public function softwaremanufacturereditsubmit(Request $request)
    {
        $data = $this->itam->updatesoftwaremanufacturer(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to delete softwaremanufacturer data from database.
     * @author Kavita Daware
     * @access public
     * @package softwaremanufacturer
     * @param UUID $software_manufacturer_id softwaremanufacturer Unique Id
     * @return json
     */
    public function softwaremanufacturerdelete(Request $request)
    {
        $data = $this->itam->deletesoftwaremanufacturer(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }
}
