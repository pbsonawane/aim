<?php

namespace App\Http\Controllers\Cmdb;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use Illuminate\Http\Request;
use View;

/**
 *SoftwareController class is implemented to do Software operations.
 * @author Kavita Daware
 * @package software
 */
class SoftwareController extends Controller
{
    /**
     * Contructor function to initiate the API service and Request data
     * @author Kavita Daware
     * @access public
     * @package software
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
     * SoftwareController function is implemented to initiate a page to get list of Software.
     * @author Kavita Daware
     * @access public
     * @package software
     * @return string
     */

    public function softwares(Request $request,$type = '',$id = '')
    {
        $topfilter = array('gridsearch' => true, 'jsfunction' => 'softwaremainList()', 'gridadvsearch' => true);
        //$data['emgridtop'] = $this->emlib->emgridtop($topfilter, '', array("software"));
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter, '', array('software_type','software_category','software_manufacturer'));
        $data['pageTitle'] = trans('title.softwares');
        $data['type'] = $type;
        $data['id'] = $id;
        if($type="software_type")
        {
        $data['software_type_id'] = $id;
        
        }else if($type="software_manufacture")
        {
        $data['software_manufacturer_id'] = $id;
        }
        $data['includeView'] = view("Cmdb/software", $data);
        return view('template', $data);
    }

    
    /**
     * SoftwareController function is implemented to get list of Software details.
     * @author Kavita Daware
     * @access public
     * @package software
     * @return string
     */

    public function softwarelistdetails(Request $request, $id)
    {
        $topfilter = array('gridsearch' => true, 'jsfunction' => 'softwareList()', 'gridadvsearch' => true);
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter, '', array("software"));
        $data['pageTitle'] = trans('title.software');
        $data['s_id'] = $id;
        $software_id = $request->id;
        $input_req = array('software_id' => $software_id);
        $software_details = $this->itam->editsoftware(array('form_params' => $input_req));

        $data['softwaredata'] = $software_details['content'];
        //print_r($data['softwaredata']);die;
        $data['software_id'] = $software_id;

        $data['includeView'] = view("Cmdb/softwarelistdetails", $data);
        return view('template', $data);
    }
    /**
     * This controller function is implemented to get list of Software .
     * @author Kavita Daware
     * @access public
     * @package software
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */

    public function softwarelist(Request $request)
    {
        try
        {
            $form_params['limit'] = 0;
            $form_params['page'] = 0;
            $form_params['offset'] = 0;
            $form_params['searchkeyword'] = '';
            $form_params['software_id'] = $request->software_id;
            $options = ['form_params' => $form_params];
            $softwaredata_resp = $this->itam->getsoftware($options);

            if ($softwaredata_resp['is_error'])
            {
                $data['softwaredata'] = array();
            }
            else
            {
                $data['softwaredata'] = _isset(_isset($softwaredata_resp, 'content'), 'records');
            }

            $data['pageTitle'] = trans('title.softwaredetails');
            $contents = enview("Cmdb/softwarelists", $data);
            $response["html"] = $contents;
            $response["is_error"] = $is_error = "";
            $response["msg"] = $msg = "";
            return json_encode($response);
        }
        catch (\Exception $e)
        {
            //save_errlog('contractdetails', 'This controller function is used to display contract details from database.', json_encode($request->all()), $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }
        catch (\Error $e)
        {
            //save_errlog('contractdetails', 'This controller function is used to display contract details from database.', json_encode($request->all()), $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }

    }

    /**
     * This controller function is implemented to get main list of Software .
     * @author Kavita Daware
     * @access public
     * @package software
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */
    public function softwaremainlist(Request $request)
    {
        //$query = $request->all();
        //print_r($query);

        $paging = array();
        $fromtime = $totime = '';
        $limit = _isset($this->request_params, 'limit', config('enconfig.def_limit_short'));
        $exporttype = _isset($this->request_params, 'exporttype');
        $page = _isset($this->request_params, 'page', config('enconfig.page'));
        $searchkeyword = _isset($this->request_params, 'searchkeyword');

        $form_params['advsoftware_type_id'] = _isset($this->request_params, 'advsoftware_type_id');
        $form_params['advsoftware_manufacturer_id'] = _isset($this->request_params, 'advsoftware_manufacturer_id');
        $form_params['advsoftware_category_id'] = _isset($this->request_params, 'advsoftware_category_id');

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
        

        $software__resp = $this->itam->getsoftwaremainlist($options);
        if ($software__resp['is_error'])
        {
            $is_error = $software__resp['is_error'];
            $msg = $software__resp['msg'];
        }
        else
        {
            $is_error = false;
            $softwares = _isset(_isset($software__resp, 'content'), 'records');
            $paging['total_rows'] = _isset(_isset($software__resp, 'content'), 'totalrecords');
            $paging['showpagination'] = true;
            $paging['jsfunction'] = 'softwaremainList()';

            $view = 'Cmdb/softwaremainlist';
            

            $content = $this->emlib->emgrid($softwares, $view, $columns = array(), $paging);
        }

        $response["html"] = $content;
        $response["is_error"] = $is_error;
        $response["msg"] = $msg;

        echo json_encode($response);
    }
    /**
     * This controller function is used to load Software  add form.
     * @author Kavita Daware
     * @access public
     * @package software
     * @return string
     */
    public function softwareadd(Request $request)
    {
        $software_id = $request->software_id;
        $data['software_id'] = '';
        $form_params['limit'] = 0;
        $form_params['page'] = 0;
        $form_params['offset'] = 0;
        $form_params['searchkeyword'] = '';
        $form_params['software_id'] = $software_id;
        //get software type
        $data['software_type_id'] = '';
        $options = ['form_params' => $form_params];

        $software_type_resp = $this->itam->getsoftwaretype($options);

        if ($software_type_resp['is_error'])
        {
            $softwaretypes = array();
        }
        else
        {
            $softwaretypes = _isset(_isset($software_type_resp, 'content'), 'records');
        }

        $data['softwaredata'] = array();
        $data['softwaretypes'] = $softwaretypes;

        //get software category
        $data['software_category_id'] = '';
        $options = ['form_params' => $form_params];
        $software_category_resp = $this->itam->getsoftwarecategory($options);

        if ($software_category_resp['is_error'])
        {
            $softwarecategorys = array();
        }
        else
        {
            $softwarecategorys = _isset(_isset($software_category_resp, 'content'), 'records');
        }

        $data['softwaretypesdata'] = array();
        $data['softwarecategorys'] = $softwarecategorys;

        //get software manufacturer
        $data['software_manufacturer_id'] = '';
        $options = ['form_params' => $form_params];
        $software_manufacturer_resp = $this->itam->getsoftwaremanufacturer($options);

        if ($software_manufacturer_resp['is_error'])
        {
            $softwaremanufacturers = array();
        }
        else
        {
            $softwaremanufacturers = _isset(_isset($software_manufacturer_resp, 'content'), 'records');
        }

        $data['softwaremanufacturerdata'] = array();
        $data['softwaremanufacturers'] = $softwaremanufacturers;

        //get license type
        $data['license_type_id'] = '';
        $options = ['form_params' => $form_params];
        $license_type_resp = $this->itam->getlicensetype($options);

        if ($license_type_resp['is_error'])
        {
            $licensetypes = array();
        }
        else
        {
            $licensetypes = _isset(_isset($license_type_resp, 'content'), 'records');
        }

        $data['licensesdata'] = array();
        $data['licensetypes'] = $licensetypes;

        $html = view("Cmdb/softwareadd", $data);
        echo $html;
    }
    /**
     * This controller function is used to save Software  data in database.
     * @author Kavita Daware
     * @access public
     * @package software
     * @param string $software Software
     * @param string $software_description Software  Description
     * @return json
     */
    public function softwareaddsubmit(Request $request)
    {
        $data = $this->itam->addsoftware(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to load software edit form with existing data for selected software
     * @author Kavita Daware
     * @access public
     * @package software
     * @param \Illuminate\Http\Request $request
     * @param $software_id software Unique Id
     * @return string
     */
    public function softwareedit(Request $request)
    {
        $software_id = $request->id;
        $input_req = array('software_id' => $software_id);
        $data = $this->itam->editsoftware(array('form_params' => $input_req));

        $data['softwaredata'] = $data['content'];
        $data['software_id'] = $software_id;

        $limit_offset = limitoffset(0, 0);
        $form_params['limit'] = $limit_offset['limit'];
        $form_params['page'] = $limit_offset['page'];
        $form_params['offset'] = $limit_offset['offset'];
        //get software type
        $options = ['form_params' => $form_params];
        $software_type_resp = $this->itam->getsoftwaretype($options);

        if ($software_type_resp['is_error'])
        {
            $softwaretypes = array();
        }
        else
        {
            $softwaretypes = _isset(_isset($software_type_resp, 'content'), 'records');
        }

        $data['softwaretypes'] = $softwaretypes;
        $data['software_type_id'] = '';

        //get software category
        $options = ['form_params' => $form_params];
        $software_category_resp = $this->itam->getsoftwarecategory($options);

        if ($software_category_resp['is_error'])
        {
            $softwarecategorys = array();
        }
        else
        {
            $softwarecategorys = _isset(_isset($software_category_resp, 'content'), 'records');
        }

        $data['softwarecategorys'] = $softwarecategorys;
        $data['software_category_id'] = '';

        //get software manufacturer
        $form_params['limit'] = 0;
        $form_params['page'] = 0;
        $form_params['offset'] = 0;
        $form_params['searchkeyword'] = '';
        $form_params['software_id'] = $software_id;

        $options = ['form_params' => $form_params];
        $software_manufacturer_resp = $this->itam->getsoftwaremanufacturer($options);

        if ($software_manufacturer_resp['is_error'])
        {
            $softwaremanufacturers = array();
        }
        else
        {
            $softwaremanufacturers = _isset(_isset($software_manufacturer_resp, 'content'), 'records');
        }

        $data['softwaremanufacturers'] = $softwaremanufacturers;
        $data['software_manufacturer_id'] = '';

        //get license type

        $options = ['form_params' => $form_params];
        $license_type_resp = $this->itam->getlicensetype($options);

        if ($license_type_resp['is_error'])
        {
            $licensetypes = array();
        }
        else
        {
            $licensetypes = _isset(_isset($license_type_resp, 'content'), 'records');
        }

        $data['licensetypes'] = $licensetypes;
        $data['license_type_id'] = '';

        $html = view("Cmdb/softwareadd", $data);
        echo $html;

    }
    /**
     * This controller function is used to update software data in database.
     * @author Kavita Daware
     * @access public
     * @package software
     * @param UUID $software_id software  Unique Id
     * @param string $software software
     * @param string $software_description software Description
     * @return json
     */
    public function softwareeditsubmit(Request $request)
    {
        $data = $this->itam->updatesoftware(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to delete software  data from database.
     * @author Kavita Daware
     * @access public
     * @package software
     * @param UUID $software_id software Unique Id
     * @return json
     */
    public function softwaredelete(Request $request)
    {
        $data = $this->itam->deletesoftware(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }

    /**
     * This controller function is used to display software details from database.
     * @author Kavita Daware
     * @access public
     * @package software
     * @param UUID $software_id software Unique Id
     * @return json
     */
    public function softwaredetails(Request $request)
    {
        try
        {
            $form_params['software_id'] = $request->software_id;

            $options = ['form_params' => $form_params];
            $softwaredata_resp = $this->itam->getsoftware($options);

            if ($softwaredata_resp['is_error'])
            {
                $data['softwaredata'] = array();
                //$softwaredata = array();

            }
            else
            {
                $data['softwaredata'] = _isset(_isset($softwaredata_resp, 'content'), 'records');
                //$softwaredata = _isset(_isset($softwaredata_resp, 'content'), 'records');
                //$softwaredata = _isset($softwaredata_resp, 'content');

            }

            $data['software_id'] = $request->software_id;

            $options = ['form_params' => $form_params];
            $softwaredata_resp = $this->itam->getsoftwareinstallation($options);
            //print_r($softwaredata_resp);
            if ($softwaredata_resp['is_error'])
            {
                $swinstalldata = array();
            }
            else
            {
                $is_error = false;
                $swinstalldata = _isset($softwaredata_resp, 'content');

            }

           $data['swinstalldata'] = $swinstalldata;
           $options = ['form_params' => $form_params];

            $resp = $this->itam->getswallocation($options);
            //dd($resp);

            if ($resp['is_error'])
            {
                $swallocations = array();
            }
            else
            {
                //$swallocations = _isset(_isset($resp, 'content'), 'records');
                $swallocations = _isset($resp, 'content');

            }

            $data['swallocations'] = $swallocations;
            $options = ['form_params' => $form_params];
            $respcount = $this->itam->getswpurchasecount($options);

            if ($respcount['is_error'])
            {
                $purchasecount = array();
            }
            else
            {
                $purchasecount = _isset($respcount, 'content');

            }

            $data['purchasecount'] = $purchasecount;
            //dd($data['purchasecount']);
            $contents = enview("Cmdb/softwaredetails", $data);

            $response["html"] = $contents;
            $response["is_error"] = $is_error = "";
            $response["msg"] = $msg = "";
            return json_encode($response);
        }
        catch (\Exception $e)
        {
            //save_errlog('contractdetails', 'This controller function is used to display softwaredetails from database.', json_encode($request->all()), $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }
        catch (\Error $e)
        {
            //save_errlog('softwaredetails', 'This controller function is used to display softwaredetails from database.', json_encode($request->all()), $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }
    }

    /**
    * This controller function is used to delete assetrelationship data from database.
    * @author Kavita Daware
    * @access public
    * @package assetwithstatus
    * @param UUID $bv_id $location_id Unique Id
    * @return json
    */   

    public function assetwithstatus(Request $request)
    {
        $data['ci_templ_id'] = $request->input('ci_templ_id');
        $data['bv_id'] = $request->input('bv_id');
        $data['location_id'] = $request->input('location_id');
        $data['asset_status'] = $request->input('asset_status');
        $limit_offset = limitoffset(0, 0);
        $page = $limit_offset['page'];
        $limit = $limit_offset['limit'];
        $offset = $limit_offset['offset'];
        $form_params['ci_templ_id'] = $request->input('ci_templ_id');
        $form_params['limit'] = $paging['limit'] = $limit;
        $form_params['page'] = $paging['page'] = $page;
        $form_params['asset_status'] = $data['asset_status'];
        $form_params['offset'] = $paging['offset'] = $offset;
        $form_params['bv_id'] = $data['bv_id'];
        $form_params['location_id'] = $data['location_id'];
        $options = [
            'form_params' => $form_params];
        // $data['ci_templ_id'] = $request->input('ci_templ_id');
        $assetlist = $this->itam->assets($options);

        if ($assetlist['is_error'])
        {
            $is_error = $assetlist['is_error'];
            $msg = $assetlist['msg'];
        }
        else
        {
            $assetlits = _isset(_isset($assetlist, 'content'), 'records');
        }
        return $assetlits;

    }

    /**
     * This controller function is used to load Software asset add form.
     * @author Kavita Daware
     * @access public
     * @package swaddasset
     * @param UUID $software_id
     * @return json
     */
    public function swaddasset(Request $request)
    {
        //print_r($request->all());
        $software_id = $request->input('software_id');
        $data['software_id'] = '';
        $form_params['limit'] = 0;
        $form_params['page'] = 0;
        $form_params['offset'] = 0;
        $form_params['searchkeyword'] = '';
        //$form_params['software_id'] =  $request->input('software_id');
        $form_params['software_id'] = $software_id;

        $options = ['form_params' => $form_params];

        $data['asset_id'] = $request->input('asset_id');
        //print_r($data['asset_id']);
        $data['bv_id'] = $request->input('bv_id');
       
        //$data['location_id'] = $request->input('location_id');
        //print_r($data['location_id']);
        $data['tag'] = $request->input('tag');
        $option = array();
        $citypes = $this->itam->citypes($option);
        $citemplates = $this->itam->getciitems($option);
        $data['citemplates'] = _isset(_isset($citemplates, 'content'), 'records');
        $data["citypes"] = _isset(_isset($citypes, 'content'), 'records');

        $data['location_id'] = '';
        $options = ['form_params' => $form_params];
        $location_resp = $this->iam->getLocations($options);
        if ($location_resp['is_error'])
        {
            $locations = array();
        }
        else
        {
            $locations = _isset(_isset($location_resp, 'content'), 'records');
        }
        $locationdata = array();
        $data['locations'] = $locations;

        $data['bv_id'] = '';
        $options = ['form_params' => $form_params];
        $bv_resp = $this->iam->getBusinessVertical($options);
        if ($bv_resp['is_error'])
        {
            $bvs = array();
        }
        else
        {
            $bvs = _isset(_isset($bv_resp, 'content'), 'records');
        }
        $bvsdata = array();
        $data['bvs'] = $bvs;

        $html = view("Cmdb/swaddasset", $data);
        echo $html;
    }

     /**
     * This controller function is used to get ci temp id from database.
     * @author Kavita Daware
     * @access public
     * @package software
     * @param UUID $software_id
     * @return json
     */

    public function getcitempidsw(Request $request)
    {
        //$data['ci_templ_id'] = $request->input('ci_templ_id');
        $data['bv_id'] = $request->input('bv_id');
        $data['location_id'] = $request->input('location_id');
        $data['asset_status'] = $request->input('asset_status');
        $limit_offset = limitoffset(0, 0);
        $page = $limit_offset['page'];
        $limit = $limit_offset['limit'];
        $offset = $limit_offset['offset'];

        $form_params['limit'] = $paging['limit'] = $limit;
        $form_params['page'] = $paging['page'] = $page;
        $form_params1['asset_status'] = $data['asset_status'];
        $form_params['offset'] = $paging['offset'] = $offset;
        $form_params1['bv_id'] = $data['bv_id'];
        $form_params1['location_id'] = $data['location_id'];
        $form_params1['variable_name'] = $request->input('variable_name');

        // $ci_templ_id = $request->ci_templ_id;
        $options1 = [
            'form_params' => $form_params1];
        $ci_temps = $this->itam->getcitempidsoftware($options1);
        //print_r($ci_temps);

        if ($ci_temps['is_error'])
        {
            $is_error = $ci_temps['is_error'];
            $msg = $ci_temps['msg'];
            return array();
        }
        else
        {
            $ci_temp = _isset($ci_temps, 'content');
        }
//print_r($ci_temp);
        if (isset($ci_temp[0]['ci_templ_id']))
        {
            $form_params['ci_templ_id'] = $ci_temp[0]['ci_templ_id'];
            $options = [
                'form_params' => $form_params];
            $assetlist = $this->itam->assets($options);
            //print_r($assetlist);

            if ($assetlist['is_error'])
            {
                $is_error = $assetlist['is_error'];
                $msg = $assetlist['msg'];
            }
            else
            {
                $assetlits = _isset(_isset($assetlist, 'content'), 'records');

            }
            return $assetlits;

        }
        else
        {
            return array();
        }

    }

    /**
     * This controller function is used to save Software asset data in database.
     * @author Kavita Daware
     * @access public
     * @package software
     * @param UUID $software_id
     * @return json
     */

    public function swattachassetsave(Request $request)
    {
        $assetdata = $request->all();
        $data = $this->itam->swattachassetsave(array('form_params' => $assetdata));
        echo json_encode($data, true);
    }

    /**
     * This controller function is used to remove Software asset data in database.
     * @author Kavita Daware
     * @access public
     * @package software
     * @param UUID $software_id
     * @return json
     */

    public function swassetremove(Request $request)
    {
        $data = $this->itam->swassetremove(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }

    /**
     * This controller function is used to get software install data from database.
     * @author Kavita Daware
     * @access public
     * @package software
     * @param UUID $software_id
     * @return json
     */


    public function getswinstallation(Request $request)
    {
        $software_id = $request->input('software_id');
        $form_params['software_id'] = $software_id;
        
        

        $options = ['form_params' => $form_params];
        $softwaredata_resp = $this->itam->getsoftwareinstallation($options);
        //print_r($softwaredata_resp);
        if ($softwaredata_resp['is_error'])
        {
            $swinstalldata = array();
        }
        else
        {
            $is_error = false;
            $swinstalldata = _isset($softwaredata_resp, 'content');

        }

        $data['swinstalldata'] = $swinstalldata;
        $data['sw_install_id'] = '';
        $resp = $this->itam->getsoftware($options);

        if ($resp['is_error'])
        {
            $data['softwaredata'] = array();
                //$softwaredata = array();

        }
        else
        {
            $data['softwaredata'] = _isset(_isset($resp, 'content'), 'records');
        }

        //print_r($data['swinstalldata']);
        $data['software_id'] = $software_id;


        $html = view("Cmdb/swinstall_details", $data);
        echo $html;

    }

    
    public function getswhistory(Request $request)
    {
        
        //echo $software_id;
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
        $software_id = $request->input('software_id');
        $form_params['software_id'] =  $software_id;
        $options = ['form_params' => $form_params];
        $historydata = $this->itam->getswhistory($options);
        //dd($historydata);
        //$userids = array(); 
        //print_r($historydata); 
        if ($historydata['is_error'])
        {
            $is_error = $historydata['is_error'];
            $msg = $historydata['msg'];
        }

        else
        {
            $is_error = false;
            $history = _isset(_isset($historydata,'content'),'records'); 
            $data['software_id'] =  $software_id;
       
            //$paging['total_rows'] = _isset(_isset($data, 'content'), 'totalrecords');
            $paging['total_rows'] = _isset(_isset($historydata, 'content'), 'totalrecords');
            $paging['showpagination'] = true;
            $paging['jsfunction'] = 'getswhistory()';

            $view = 'Cmdb/swhistory_details';

            $content = $this->emlib->emgrid($history, $view, $columns = array(), $paging);
        }

        $response["html"] = $content;
        $response["is_error"] = $is_error;
        $response["msg"] = $msg;
        echo json_encode($response);
    }

    /**
     * This controller function is used to get software history data from database.
     * @author Kavita Daware
     * @access public
     * @package software
     * @param UUID $software_id
     * @return json
     */
     
    public function getswhistory1(Request $request)
    {
        $software_id = $request->input('software_id');

        $form_params['software_id'] = $software_id;

        $options = ['form_params' => $form_params];

        $resp = $this->itam->getswhistory($options);
        //dd($resp);
        if ($resp['is_error'])
        {
            $history = array();
                //$softwaredata = array();

        }
        else
        {
            //$history = _isset(_isset($resp, 'content'), 'records');
            $history = _isset($resp, 'content');

            //dd($history);
        }
        $data['history'] = $history;
        //dd($data['history']);
        $data['software_id'] = $software_id;
        $html = view("Cmdb/swhistory_details", $data);
        echo $html;
    }


    public function getsoftwarelicense(Request $request)

    {
        $software_id = $request->input('software_id');

        $form_params['software_id'] = $software_id;

        //$software_license_id = $request->input('software_license_id');

        $software_license_id = $request->software_license_id;


        $form_params['software_license_id'] = $software_license_id;
        
        $options = ['form_params' => $form_params];

        $resp = $this->itam->getsoftwarelicense($options);
        
        if ($resp['is_error'])
        {
            $swlicenses = array();
        }
        else
        {
            //$swlicenses = _isset(_isset($resp, 'content'), 'records');
            $swlicenses = _isset($resp, 'content');

        }

        $data['swlicenses'] = $swlicenses;
        $data['software_license_id'] = $software_license_id;

        
        $options = ['form_params' => $form_params];

        $resp = $this->itam->getswallocation($options);
        //dd($resp);
        if ($resp['is_error'])
        {
            $swallocations = array();
        }
        else
        {
            //$swallocations = _isset(_isset($resp, 'content'), 'records');
            $swallocations = _isset($resp, 'content');

        }

        $data['swallocations'] = $swallocations;
        //dd($data['swallocations']);
        $options = ['form_params' => $form_params];
        $softwaredata_resp = $this->itam->getsoftwareinstallation($options);
        //print_r($softwaredata_resp);
        if ($softwaredata_resp['is_error'])
        {
            $swinstalldata = array();
        }
        else
        {
            $is_error = false;
            $swinstalldata = _isset($softwaredata_resp, 'content');

        }

        $data['swinstalldata'] = $swinstalldata;
       
        $data['software_id'] = $software_id;
        
        //dd($data);

        $html = view("Cmdb/swlicense_details", $data);
        echo $html;
    }

     /**
     * This controller function is used to load Software license add form.
     * @author Kavita Daware
     * @access public
     * @package softwarelicense
     * @return string
     */

    public function swaddlisense(Request $request)
    {
        //print_r($request->all());
        $software_license_id = $request->software_license_id;
        $data['software_license_id'] = '';
        $data['software_id'] = '';
        $software_id = $request->input('software_id');
        $form_params['limit'] = 0;
        $form_params['page'] = 0;
        $form_params['offset'] = 0;
        $form_params['searchkeyword'] = '';
        $form_params['software_id'] = $software_id;
        $form_params['software_license_id'] = $software_license_id;

        if(!empty($software_id)) $data['software_id'] = $software_id;
        else $data['software_id'] = '';
        
        $options = ['form_params' => $form_params];

        $option = array();
        //get software manufacturer

        $options = ['form_params' => $form_params];
        $software_manufacturer_resp = $this->itam->getsoftwaremanufacturer($options);

        if ($software_manufacturer_resp['is_error'])
        {
            $softwaremanufacturers = array();
        }
        else
        {
            $softwaremanufacturers = _isset(_isset($software_manufacturer_resp, 'content'), 'records');
        }        
        $options = ['form_params' => $form_params];
        $softwaredata_resp = $this->itam->getsoftware($options);

        if ($softwaredata_resp['is_error'])
        {
            $softwarelicensedata = array();
        }
        else
        {
            $softwarelicensedata = _isset(_isset($softwaredata_resp, 'content'), 'records');
        }        
        $data['softwarelicensedata'] = $softwarelicensedata;
        $data['softwaremanufacturerdata'] = array();
        $data['softwaremanufacturers'] = $softwaremanufacturers;

        //get license type

        $options = ['form_params' => $form_params];
        $license_type_resp = $this->itam->getlicensetype($options);

        if ($license_type_resp['is_error'])
        {
            $licensetypes = array();
        }
        else
        {
            $licensetypes = _isset(_isset($license_type_resp, 'content'), 'records');
        }

        $data['licensesdata'] = array();
        $data['licensetypes'] = $licensetypes;

        //get vendor

        $options = ['form_params' => $form_params];
        $vendor_resp = $this->itam->getvendors($options);

        if ($vendor_resp['is_error'])
        {
            $vendor = array();
        }
        else
        {
            $vendor = _isset(_isset($vendor_resp, 'content'), 'records');
        }

        $data['vendordata'] = array();
        $data['vendor'] = $vendor;

        //get department

        $options = ['form_params' => $form_params];
        $department_resp = $this->iam->getDepartment($options);

        if ($department_resp['is_error'])
        {
            $department = array();
        }
        else
        {
            $department = _isset(_isset($department_resp, 'content'), 'records');
        }

        $data['departmentdata'] = array();
        $data['department'] = $department;

        //get location

        $options = ['form_params' => $form_params];
        $location_resp = $this->iam->getLocations($options);

        if ($location_resp['is_error'])
        {
            $locations = array();
        }
        else
        {
            $locations = _isset(_isset($location_resp, 'content'), 'records');
        }

        $data['locationdata'] = array();
        $data['locations'] = $locations;

        //get bv

        $options = ['form_params' => $form_params];
        $bv_resp = $this->iam->getBusinessVertical($options);

        if ($bv_resp['is_error'])
        {
            $bvs = array();
        }
        else
        {
            $bvs = _isset(_isset($bv_resp, 'content'), 'records');
        }

        $data['bvsdata'] = array();
        $data['bvs'] = $bvs;

        $html = view("Cmdb/swaddlisense", $data);
        echo $html;
    }

    /**
     * This controller function is used to save Software license data in database.
     * @author Kavita Daware
     * @access public
     * @package softwarelicense
     * @param string $softwarelicense Software license
     * @return json
     */
    public function swaddLicensesubmit(Request $request)
    {
        $data = $this->itam->addsoftwarelicense(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }

    /**
     * This controller function is used to load software license edit form with existing data for selected softwarelicense
     * @author Kavita Daware
     * @access public
     * @package softwarelicenselicense
     * @param \Illuminate\Http\Request $request
     * @param $software_license_id softwarelicense Unique Id
     * @return string
     */

    public function softwarelicenseedit(Request $request)
    {
        $software_license_id = $request->id;
        $software_id = $request->software_id;
        $input_req = array('software_license_id' => $software_license_id, 'software_id' => $software_id);
        $data = $this->itam->editsoftwarelicense(array('form_params' => $input_req));

        $data['softwarelicensedata'] = $data['content'];
        $data['software_license_id'] = $software_license_id;
        $data['software_id'] = $software_id;

        $limit_offset = limitoffset(0, 0);
        $form_params['limit'] = $limit_offset['limit'];
        $form_params['page'] = $limit_offset['page'];
        $form_params['offset'] = $limit_offset['offset'];

        //get software manufacturer
        $form_params['limit'] = 0;
        $form_params['page'] = 0;
        $form_params['offset'] = 0;
        $options = ['form_params' => $form_params];
        $software_manufacturer_resp = $this->itam->getsoftwaremanufacturer($options);

        if ($software_manufacturer_resp['is_error'])
        {
            $softwaremanufacturers = array();
        }
        else
        {
            $softwaremanufacturers = _isset(_isset($software_manufacturer_resp, 'content'), 'records');
        }

        $data['softwaremanufacturerdata'] = array();
        $data['softwaremanufacturers'] = $softwaremanufacturers;

        //get license type

        $options = ['form_params' => $form_params];
        $license_type_resp = $this->itam->getlicensetype($options);

        if ($license_type_resp['is_error'])
        {
            $licensetypes = array();
        }
        else
        {
            $licensetypes = _isset(_isset($license_type_resp, 'content'), 'records');
        }

        $data['licensesdata'] = array();
        $data['licensetypes'] = $licensetypes;

        //get vendor

        $options = ['form_params' => $form_params];
        $vendor_resp = $this->itam->getvendors($options);

        if ($vendor_resp['is_error'])
        {
            $vendor = array();
        }
        else
        {
            $vendor = _isset(_isset($vendor_resp, 'content'), 'records');
        }

        $data['vendordata'] = array();
        $data['vendor'] = $vendor;

        //get department

        $options = ['form_params' => $form_params];
        $department_resp = $this->iam->getDepartment($options);

        if ($department_resp['is_error'])
        {
            $department = array();
        }
        else
        {
            $department = _isset(_isset($department_resp, 'content'), 'records');
        }

        $data['departmentdata'] = array();
        $data['department'] = $department;

        //get location

        $options = ['form_params' => $form_params];
        $location_resp = $this->iam->getLocations($options);

        if ($location_resp['is_error'])
        {
            $locations = array();
        }
        else
        {
            $locations = _isset(_isset($location_resp, 'content'), 'records');
        }

        $data['locationdata'] = array();
        $data['locations'] = $locations;

        //get bv

        $options = ['form_params' => $form_params];
        $bv_resp = $this->iam->getBusinessVertical($options);

        if ($bv_resp['is_error'])
        {
            $bvs = array();
        }
        else
        {
            $bvs = _isset(_isset($bv_resp, 'content'), 'records');
        }

        $data['bvsdata'] = array();
        $data['bvs'] = $bvs;

        $html = view("Cmdb/swaddlisense", $data);
        echo $html;

    }

    /**
     * This controller function is used to update softwarelicense data in database.
     * @author Kavita Daware
     * @access public
     * @package softwarelicense
     * @param UUID $software_license_id softwarelicense  Unique Id
     * @param string $software_license_id 
     * @return json
     */
    public function softwarelicenseeditsubmit(Request $request)
    {
        $data = $this->itam->updatesoftwarelicense(array('form_params' => $request->all()));
        echo json_encode($data, true);

    }
    /**
     * This controller function is used to get Software license allocate data from database.
     * @author Kavita Daware
     * @access public
     * @package softwarelicense
     * @return string
     */

    public function softwarelicensellocate(Request $request)
    {
        $data = $this->itam->softwarelicensellocate(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }

    /**
     * This controller function is used to remove Software asset data.
     * @author Kavita Daware
     * @access public
     * @package softwarelicense
     * @return string
     */

    public function swallocateassetremove(Request $request)
    {
        $data = $this->itam->swallocateassetremove(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }

    /**
     * This controller function is used to uninstall and deallocate Software asset data.
     * @author Kavita Daware
     * @access public
     * @package softwarelicense
     * @return string
     */
    public function swdeallocateuninstall(Request $request)
    {
        $data = $this->itam->swdeallocateuninstall(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }
    public function swlicensemaxacount(Request $request)
    {

        $software_license_id = $request->software_license_id;
        $input_req = array('software_license_id' => $software_license_id);
        $maxdata = $this->itam->getswlicensemaxacount(array('form_params' => $input_req));
        //dd($maxdata);
        echo json_encode($maxdata['content'][0]['allocationmaxcount'], true);
        
        
    }
    

    
}
