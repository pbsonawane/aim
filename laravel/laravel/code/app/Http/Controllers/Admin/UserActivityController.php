<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\IAM\IamService;
use App\Libraries\Emlib;
/**
 * Region Controller class is implemented to do CRUD operations related to userlog
 * @author Namrata Thakur
 * @package userLogs
 */
class UserActivityController extends Controller
{
	/**
     * Contructor function to initiate the API service and Request data
     * @author Namrata Thakur
     * @access public
     * @package userLogs
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
    * This UserActivity controller function is implemented to initiate a page to get list of User Activity.
    * @author Namrata Thakur
    * @access public
    * @package userLogs
    * @return string
    */
   public function userlogs() {

       $topfilter = array('gridsearch' => true,'jsfunction' => 'userlogList()', 'gridadvsearch' => true);
       $data['emgridtop'] = $this->emlib->emgridtop($topfilter, '', array("datesearch"));
       $data['pageTitle'] = "User Logs";
       $data['includeView'] = view("Admin/userlogs",$data);
       return view('template',$data);
   }
	 /**
     * This controller function is implemented to get list of userlog.
     * @author Namrata Thakur
     * @access public
     * @package region
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */
	public function userlogslist() 
	{
		$paging = array();
		$limit = _isset($this->request_params, 'limit', config('enconfig.def_limit'));
		$page = _isset($this->request_params, 'page', config('enconfig.page'));
        //$searchkeyword = _isset($this->request_params, 'searchkeyword');
        
        $timerange =  _isset($this->request_params, 'timerange') ? trim($this->request->input('timerange')) : '';

        $customtime =  _isset($this->request_params, 'customtime') ? trim($this->request->input('customtime')) : '';
        if($timerange != '' || $customtime != '')
        {
            $from_to_time = calculate_from_to_dates($timerange,$customtime);
            $fromtime = date('Y-m-d G:i:s',$from_to_time['from_time']);
            $totime = date('Y-m-d G:i:s',$from_to_time['to_time']);
        }  
              
        $form_params['fromtime'] = isset($fromtime) ? $fromtime :'' ;
        $form_params['totime'] = isset($totime) ? $totime :'' ;

        $is_error = false;
        $msg = '';$content="";
		$limit_offset = limitoffset($limit, $page);
		$page = $limit_offset['page'];
		$limit = $limit_offset['limit'];
		$offset = $limit_offset['offset'];

		$form_params['limit'] = $paging['limit'] = $limit;
		$form_params['page'] = $paging['page'] = $page;
		$form_params['offset'] = $paging['offset'] = $offset;
		//$form_params['searchkeyword'] = $searchkeyword;

		$options = [
            'form_params' => $form_params];

		$userlog_resp = $this->iam->getUserlogs($options);
		if($userlog_resp['is_error'])
		{
			$is_error = $userlog_resp['is_error'];
			$msg = $userlog_resp['msg'];
		}
		else
		{
			$userlogs = _isset(_isset($userlog_resp,'content'),'records');
			$paging['total_rows'] = _isset(_isset($userlog_resp,'content'),'totalrecords');
			$paging['showpagination'] = true;
			$paging['jsfunction'] = 'userlogList()';
			$view = 'Admin/userloglist';
			$content = $this->emlib->emgrid($userlogs, $view, array(), $paging);
		}

		$response["html"] = $content;
		$response["is_error"] = $is_error;
		$response["msg"] = $msg;
		echo json_encode($response);
	}   
}
