<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\IAM\IamService;
use App\Libraries\Emlib;

class DatacentersController extends Controller
{
	/**
     * Contructor function to initiate the API service and Request data
     * @author Amit Khairnar
     * @access public
     * @package user
     * @param \App\Services\IAM\IamService $iam
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function __construct(IamService $iam, Request $request) {
		$this->iam = $iam;
		$this->emlib = new Emlib;
		$this->request = $request;
		$this->request_params = $this->request->all();
	}
	/**
     * Users controller function is implemented to initiate a page to get list of Datacenter.
     * @author Amit Khairnar
     * @access public
     * @package user
     * @return string
     */ 
	public function datacenters() {
		$topfilter = array('gridsearch' => true,'jsfunction' => 'dcList()');
		$data['emgridtop'] = $this->emlib->emgridtop($topfilter);   
		$data['pageTitle'] = "Datacenters";
		$data['includeView'] = view("Admin/datacenters",$data);
		return view('template',$data);
	}
	/**
     * This controller function is implemented to get list of Datacenters.
     * @author Amit Khairnar
     * @access public
     * @package user
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */
	public function dclist() {
		$paging = array();
        $fromtime = $totime = '';
        $limit = _isset($this->request_params, 'limit', config('enconfig.def_limit'));
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
        $form_params['searchkeyword'] = $searchkeyword;
        $form_params['page'] = $paging['page'] = $page;
        $form_params['offset'] = $paging['offset'] = $offset;
		
		$options = [
            'form_params' => $form_params];
		$dcs_resp = $this->iam->getDatacenters($options);
		if($dcs_resp['is_error'])
		{
			$is_error = $dcs_resp['is_error'];
			$msg = $dcs_resp['msg'];
		}
		else
		{
			$dcs = _isset(_isset($dcs_resp,'content'),'records');
			$paging['total_rows'] = _isset(_isset($dcs_resp,'content'),'totalrecords');
			$paging['showpagination'] = true;
			$paging['jsfunction'] = 'dcList()';				
			$offset = $limit * $page;
			$paging['limit'] = $limit;
			$paging['offset'] = $offset;
			$paging['page'] = $page;
			$view = 'Admin/dclist';
			$content = $this->emlib->emgrid($dcs, $view, $columns=array(), $paging);
		}
		$response["html"] = $content;
		$response["is_error"] = $is_error;
		$response["msg"] = $msg;
		echo json_encode($response);
	}
	/**
     * This controller function is used to load datacenter add form.
     * @author Amit Khairnar
     * @access public
     * @package user
     * @return string
     */	
	public  function dcadd(Request $request)
    {
    	$regions = array();
        $data['dc_id'] = '';
        $limit_offset = limitoffset(0, 0);
		$form_params['limit'] = $limit_offset['limit'];
		$form_params['page'] = $limit_offset['page'];
		$form_params['offset'] = $limit_offset['offset'];
		$options = ['form_params' => $form_params];

        $regions_resp = $this->iam->getRegions($options);
						
		if($regions_resp['is_error'])
		{
			$regions = array();
		}
		else
		{
			$regions = _isset(_isset($regions_resp,'content'),'records');
		}
		//print_r($regions);exit;
		
		$data['dcdata'] = array();
		$data['regions'] = $regions;
        $html = view("Admin/dcadd", $data);
        echo  $html;
    } 
	/**
     * This controller function is used to save Datacenter data in database.
     * @author Amit Khairnar
     * @access public
     * @package user
     * @param string $dc_name Datacenter Name
     * @param string $region_id UUID Region ID
     * @param string $dc_discription Datacenter Discription
     * @return json
     */
	public function dcaddsubmit(Request $request)
    {		
    	$data =  $this->iam->addDatacenter(array( 'form_params' => $request->all()));
      	echo json_encode($data,true);
    } 
    /**
     * This controller function is used to load datacenter edit form with existing data for selected datacenter
     * @author Amit Khairnar
     * @access public
     * @package user
     * @param \Illuminate\Http\Request $request
     * @param $dc_id datacenter Unique Id
     * @return string
     */
    public function dcedit(Request $request)
	{	
		$dc_id = $request->id;
		$input_req = array('dc_id' => $dc_id);
		$data =  $this->iam->editDatacenter(array( 'form_params' => $input_req));
		$data['dcdata'] = $data['content'];
		$data['dc_id'] = $dc_id;
		$limit_offset = limitoffset(0, 0);
		$form_params['limit'] = $limit_offset['limit'];
		$form_params['page'] = $limit_offset['page'];
		$form_params['offset'] = $limit_offset['offset'];
		$options = ['form_params' => $form_params];
        $regions_resp = $this->iam->getRegions($options);
		if($regions_resp['is_error'])
		{
			$regions = array();
		}
		else
		{
			$regions = _isset(_isset($regions_resp,'content'),'records');
		}
		
		$data['regions'] = $regions;
        $html = view("Admin/dcadd",$data);
        echo  $html;
	}
	/**
     * This controller function is used to update datacenter data in database.
     * @author Amit Khairnar
     * @access public
     * @package datacenter
     * @param string $dc_name Datacenter Name
     * @param string $region_id UUID Region ID
     * @param string $dc_discription Datacenter Discription
     * @return json
     */
	public function dceditsubmit(Request $request)
    {
       $data =  $this->iam->updateDatacenter(array( 'form_params' => $request->all()));
       echo json_encode($data,true);
    } 
    /**
     * This controller function is used to delete datacenter data from database.
     * @author Amit Khairnar
     * @access public
     * @package datacenter
     * @param UUID $dc_id Datacenter Unique Id
     * @return json
     */ 
    public function dcdelete(Request $request)
	{
		$data =  $this->iam->deleteDatacenter(array( 'form_params' => $request->all()));
       	echo json_encode($data,true);
	}
}
