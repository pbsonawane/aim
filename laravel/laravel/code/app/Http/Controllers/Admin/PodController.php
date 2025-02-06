<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\IAM\IamService;
use App\Libraries\Emlib;
/**
 * PodController class is implemented to do CRUD operations on PODs
 * @author Vikash Kumar
 * @package pod
 */
class PodController extends Controller
{	
	 /**
     * Contructor function to initiate the API service and Request data
     * @author Vikash Kumar
     * @access public
     * @package pod
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
     * PODs controller function is implemented to initiate page to get list of PODs.
     * @author Vikash Kumar
     * @access public
     * @package pod
     * @return string
     */
	public function pods() {

		$topfilter = ['gridsearch' => true,'jsfunction' => 'podList()'];
		$data['emgridtop'] = $this->emlib->emgridtop($topfilter);
		$data['pageTitle'] = "Pods";
		$data['includeView'] = view("Admin/pods",$data);
		return view('template',$data);
	}
	/**
     * This controller function is implemented to get list of PODs.
     * @author Vikash Kumar
     * @access public
     * @package pod
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */
	public function podlist() {
		$paging = [];
		$limit = _isset($this->request_params, 'limit', config('enconfig.def_limit'));
		$page = _isset($this->request_params, 'page', config('enconfig.page'));
		$searchkeyword = _isset($this->request_params, 'searchkeyword');
        $is_error = false;
        $msg = '';$content="";
		$limit_offset = limitoffset($limit, $page);
		$page = $limit_offset['page'];
		$limit = $limit_offset['limit'];
		$offset = $limit_offset['offset'];

		$form_params['limit'] = $paging['limit'] = $limit;
		$form_params['page'] = $paging['page'] = $page;
		$form_params['offset'] = $paging['offset'] = $offset;
		$form_params['searchkeyword'] = $searchkeyword;

		$options = [
            'form_params' => $form_params];

		$pods_resp = $this->iam->getPods($options);
		if($pods_resp['is_error'])
		{
			$is_error = $pods_resp['is_error'];
			$msg = $pods_resp['msg'];
		}
		else
		{
			$pods = _isset(_isset($pods_resp,'content'),'records');
			$paging['total_rows'] = _isset(_isset($pods_resp,'content'),'totalrecords');
			$paging['showpagination'] = true;
			$paging['jsfunction'] = 'podList()';
			$view = 'Admin/podlist';
			$content = $this->emlib->emgrid($pods, $view, [], $paging);
		}

		$response["html"] = $content;
		$response["is_error"] = $is_error;
		$response["msg"] = $msg;
		echo json_encode($response);
	}
	/**
     * This controller function is used to load POD add form.
     * @author Vikash Kumar
     * @access public
     * @package pod
     * @return string
     */
	public  function podadd(Request $request)
    {
        $data['pod_id'] = $is_error = $msg = '';
		$poddata = $regions = [];
		$data['poddata'] = $poddata;
		
		$options = [
            'form_params' => []];

		$regions_resp = $this->iam->getRegions($options);
		
		if($regions_resp['is_error'])
		{
			$is_error = $regions_resp['is_error'];
			$msg = $regions_resp['msg'];
		}
		else
		{
			$regions = _isset(_isset($regions_resp,'content'),'records');
		}
		
		$data["regions"] = $regions;
		$data["is_error"] = $is_error;
		$data["msg"] = $msg;
		
        $html = view("Admin/podadd",$data);
        echo  $html;
    }
	/**
     * This controller function is used to save POD data in database.
     * @author Vikash Kumar
     * @access public
     * @package pod
	 * @param \Illuminate\Http\Request $request
     * @param UUID $region_id Region ID
     * @param UUID $dc_id Datacenter ID
     * @param string $pod_name POD Name
     * @param string $pod_description POD Description
     * @return json
     */
	public function podsave(Request $request)
    {
       $data =  $this->iam->addPod([ 'form_params' => $request->all()]);
       echo json_encode($data,true);
    }
	/**
	* This controller function is used to load POD edit form with existing data for selected POD
	* @author Vikash Kumar
	* @access public
	* @package pod
	* @param \Illuminate\Http\Request $request
	* @param UUID $pod_id POD Unique Id
	* @return string
	*/
	public function podedit(Request $request)
	{	
		$region_dcs = [];
		$is_error = $msg = "";	
		$pod_id = $request->id;
		$input_req = ['pod_id' => $pod_id];
		$podcontent =  $this->iam->getPods([ 'form_params' => $input_req]);
		
		$data['pod_id'] = $pod_id;
		$data['poddata'] = $podcontent['content']['records'];
		
		$pod_region_id = $podcontent['content']['records'][0]['region_id'];
		
		if($pod_region_id != '')  // Fetching all DCs of selected region
		{	
			$region_dcs_data =  $this->iam->getRegionDc([ 'form_params' => ['region_id' => $pod_region_id]]);
			$region_dcs = $region_dcs_data['content'];
		}	
		
		
		
		$options = [
            'form_params' => []];

		$regions_resp = $this->iam->getRegions($options);
		
		if($regions_resp['is_error'])
		{
			$is_error = $regions_resp['is_error'];
			$msg = $regions_resp['msg'];
		}
		else
		{
			$regions = _isset(_isset($regions_resp,'content'),'records');
		}
		
		$data["regions"] = $regions;
		$data["is_error"] = $is_error;
		$data["msg"] = $msg;
		$data["region_dcs"] = $region_dcs;
		
        $html = view("Admin/podadd",$data);
        echo  $html;
	}
	
	/**
	* This controller function is used to update POD data in database.
	* @author Vikash Kumar
	* @access public
	* @package pod
	* @param UUID $pod_id POD Unique Id
	* @param UUID $region_id Region ID
	* @param UUID $dc_id Datacenter ID
	* @param string $pod_name POD Name
	* @param string $pod_description POD Description
	* @return json
	*/
	public function podupdate(Request $request)
    {
       $data =  $this->iam->updatePod([ 'form_params' => $request->all()]);
       echo json_encode($data,true);
    }
	/**
	* This controller function is used to delete POD data from database.
	* @author Vikash Kumar
	* @access public
	* @package pod
	* @param UUID $pod_id POD Unique Id
	* @return json
	*/
	public function poddelete(Request $request)
	{
		$data =  $this->iam->deletePod([ 'form_params' => $request->all()]);
       	echo json_encode($data,true);
	}
	
	/**
	* This controller function is used to fetch all datacenters of particular Region.
	* @author Vikash Kumar
	* @access public
	* @package pod
	* @param UUID $region_id Region Unique Id
	* @return json
	*/
	public function getregiondcs(Request $request)
	{
		$data =  $this->iam->getRegionDc([ 'form_params' => $request->all()]);
		$final_data = $data['content'];
       	echo json_encode($final_data,true);
	}
	
	
}
