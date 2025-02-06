<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\IAM\IamService;
use App\Libraries\Emlib;

class LocationsController extends Controller
{
    public function __construct(IamService $iam, Request $request) {
		$this->iam = $iam;
		$this->emlib = new Emlib;
		$this->request = $request;
		$this->request_params = $this->request->all();
	}
	 
	public function locations() {
		$topfilter = array('gridsearch' => true,'jsfunction' => 'locationList()');
		$data['emgridtop'] = $this->emlib->emgridtop($topfilter);   
		$data['pageTitle'] = "Locations";
		$data['includeView'] = view("Admin/locations",$data);
		return view('template',$data);
	}
	public function locationlist() {
		$paging = array();
		$fromtime = $totime = '';
		$limit = _isset($this->request_params, 'limit', config('enconfig.def_limit'));
		$exporttype = _isset($this->request_params,'exporttype');
		$page = _isset($this->request_params, 'page', config('enconfig.page'));
		$searchkeyword = _isset($this->request_params, 'searchkeyword');
		$is_error = false;$msg = '';$content="";
		$limit_offset = limitoffset($limit, $page);
		$page = $limit_offset['page'];
		$limit = $limit_offset['limit'];
		$offset = $limit_offset['offset'];
		$form_params['limit'] = $limit;
		$form_params['searchkeyword'] = $searchkeyword;
		$form_params['page'] = $page;
		$form_params['offset'] = $offset;
		
		$options = [
            'form_params' => $form_params];
		$locs_resp = $this->iam->getLocations($options);
		if($locs_resp['is_error'])
		{
			$is_error = $locs_resp['is_error'];
			$msg = $locs_resp['msg'];
		}
		else
		{
			$locs = _isset(_isset($locs_resp,'content'),'records');
			$paging['total_rows'] = _isset(_isset($locs_resp,'content'),'totalrecords');
			$paging['showpagination'] = true;
			$paging['jsfunction'] = 'locationList()';				
			$offset = $limit * $page;
			$paging['limit'] = $limit;
			$paging['offset'] = $offset;
			$paging['page'] = $page;
			$view = 'Admin/locationlist';
			$content = $this->emlib->emgrid($locs, $view, $columns=array(), $paging);
		}
		$response["html"] = $content;
		$response["is_error"] = $is_error;
		$response["msg"] = $msg;
		echo json_encode($response);
	}

	public  function locationadd(Request $request)
    {
    	$regions = array();
        $data['loc_id'] = '';
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
		
		$data['locdata'] = array();
		$data['regions'] = $regions;
        $html = view("Admin/locationadd", $data);
        echo  $html;
    }
    public function locationaddsubmit(Request $request)
    {		
    	$data =  $this->iam->addLocation(array( 'form_params' => $request->all()));
      	echo json_encode($data,true);
    } 

    public function locationedit(Request $request)
	{	
		$loc_id = $request->id;
		$input_req = array('loc_id' => $loc_id);
		$data =  $this->iam->editLocation(array( 'form_params' => $input_req));
		$data['locdata'] = $data['content'];
		$data['loc_id'] = $loc_id;
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
        $html = view("Admin/locationadd",$data);
        echo  $html;
	}
	public function locationeditsubmit(Request $request)
    {
       $data =  $this->iam->updateLocation(array( 'form_params' => $request->all()));
       echo json_encode($data,true);
    }  
    public function locationdelete(Request $request)
	{
		$data =  $this->iam->deleteLocation(array( 'form_params' => $request->all()));
       	echo json_encode($data,true);
	} 
}
