<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\IAM\IamService;
use App\Libraries\Emlib;
/**
 * Region Controller class is implemented to do CRUD operations related to Regions
 * @author Vikash Kumar
 * @package region
 */
class RegionController extends Controller
{	
	/**
     * Contructor function to initiate the API service and Request data
     * @author Vikash Kumar
     * @access public
     * @package region
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
     * This Region controller function is implemented to initiate a page to get list of Region.
     * @author Vikash Kumar
     * @access public
     * @package region
     * @return string
     */
	public function regions() {

		$topfilter = ['gridsearch' => true,'jsfunction' => 'regionList()'];
		$data['emgridtop'] = $this->emlib->emgridtop($topfilter);
		$data['pageTitle'] = "Regions";
		$data['includeView'] = view("Admin/regions",$data);
		return view('template',$data);
	}
	 /**
     * This controller function is implemented to get list of Regions.
     * @author Vikash Kumar
     * @access public
     * @package region
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */
	public function regionlist() 
	{	
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

		$regions_resp = $this->iam->getRegions($options);
		if($regions_resp['is_error'])
		{
			$is_error = $regions_resp['is_error'];
			$msg = $regions_resp['msg'];
		}
		else
		{
			$regions = _isset(_isset($regions_resp,'content'),'records');
			$paging['total_rows'] = _isset(_isset($regions_resp,'content'),'totalrecords');
			$paging['showpagination'] = true;
			$paging['jsfunction'] = 'regionList()';
			$view = 'Admin/regionlist';
			$content = $this->emlib->emgrid($regions, $view, [], $paging);
		}

		$response["html"] = $content;
		$response["is_error"] = $is_error;
		$response["msg"] = $msg;
		echo json_encode($response);
	}
	/**
     * This controller function is used to load region add form.
     * @author Vikash Kumar
     * @access public
     * @package region
     * @return string
     */
	public  function regionadd(Request $request)
    {
        $data['region_id'] = '';
		$regiondata = [];
		$data['regiondata'] = $regiondata;

        $html = view("Admin/regionadd",$data);
        echo  $html;
    }
	/**
     * This controller function is used to save region data in database.
     * @author Vikash Kumar
     * @access public
     * @package region
     * @param string $region_name Region Name
     * @param string $region_description Region Description
     * @return json
     */
	public function regionsave(Request $request)
    {
       $data =  $this->iam->addRegion([ 'form_params' => $request->all()]);
       echo json_encode($data,true);
    }
	/**
     * This controller function is used to load region edit form with existing data for selected region
     * @author Vikash Kumar
     * @access public
     * @package region
     * @param \Illuminate\Http\Request $request
     * @param $region_id Region Unique Id
     * @return string
     */
	public function regionedit(Request $request)
	{	
		$region_id = $request->id;
		$input_req = ['region_id' => $region_id];
		$data =  $this->iam->getRegions([ 'form_params' => $input_req]);
		
		$data['region_id'] = $region_id;
		$data['regiondata'] = $data['content']['records'];

        $html = view("Admin/regionadd",$data);
        echo  $html;
	}
	/**
     * This controller function is used to update region data in database.
     * @author Vikash Kumar
     * @access public
     * @package region
     * @param UUID $region_id Region Unique Id
     * @param string $region_name Region Name
	 * @param string $action Action [delete/update]
     * @param string $region_description Region Description
     * @return json
     */
	public function regionupdate(Request $request)
    {
       $data =  $this->iam->updateRegion([ 'form_params' => $request->all()]);
       echo json_encode($data,true);
    }
	
	/**
     * This function will delete Region for selected region id.
     * @author Vikas
     * @access public
     * @package region
     * @param UUID $region_id Unique Region Id
     * @param \Illuminate\Http\Request $request
	 * @param string $action Action [delete]
     * @return json
     *
     */
	public function regiondelete(Request $request)
	{
		$data =  $this->iam->updateRegion([ 'form_params' => $request->all()]);
       	echo json_encode($data,true);
	}
	
	/**
     * This controller function is used to load DC assign page for selected Region. IT fetches all DC need to assign to selected Region
     * @author Vikash Kumar
     * @access public
     * @package region
     * @param \Illuminate\Http\Request $request
     * @param $region_id Region Unique Id
     * @return string
     */
	public function assigndc(Request $request)
	{	
		$region_id = $request->region_id;
		$region_name = $request->region_name;
		
		$input_req = ['region_id' => $region_id];
		$dc_data =  $this->iam->dcRegions([ 'form_params' => $input_req]);
		
		$data['region_id'] = $region_id;
		$data['region_name'] = $region_name;
		$data['dc_data'] = $dc_data['content'];

        $html = view("Admin/regiondcassign",$data);
        echo  $html;
	}
	/**
     * This controller function save DCs assigned to selected Region
     * @author Vikash Kumar
     * @access public
     * @package region
     * @param \Illuminate\Http\Request $request
     * @param $region_id Region Unique Id
	 * @param $dc_id Datacenter Ids (comma seperated)
     * @return string
     */
	public function assigndcregions(Request $request)
    {	
		
	   $input_data = ['dc_id' => trim($request->dc_id,","),'region_id' => $request->region_id];
       $data =  $this->iam->saveRegionDc([ 'form_params' => $input_data]);
       echo json_encode($data,true);
    }
	
	
}
