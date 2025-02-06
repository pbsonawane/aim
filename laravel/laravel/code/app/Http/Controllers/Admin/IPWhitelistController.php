<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\IAM\IamService;
use App\Libraries\Emlib;
/**
 * IP Whitelist Controller class is implemented to do manage all operations related to IP Whitelisting
 * @author Vikash Kumar
 * @package ipwhitelist
 */
class IPWhitelistController extends Controller
{	
	/**
     * Contructor function to initiate the API service and Request data
     * @author Vikash Kumar
     * @access public
     * @package ipwhitelist
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
     * This controller function is implemented to initiate a page to get list of IP Whitelisted, to add new IP, Approve IP and delete IP/Subnet from whitelisted list.
     * @author Vikash Kumar
     * @access public
     * @package ipwhitelist
     * @return string
     */
	public function whitelistip() 
	{
		$topfilter_tokenip = ['gridsearch' => true,'jsfunction' => 'ipList()'];
		$data['emgridtop_tokenip'] = $this->emlib->emgridtop($topfilter_tokenip);
		$data['pageTitle'] = "IP Whitelist";
		$data['includeView'] = view("Admin/ipwhitelist",$data);
		return view('template',$data);
	}
	 /**
     * This function is used to function used to add Whitelisted IPs / Subnet of user.
     * @author Vikash Kumar
     * @access public
     * @package ipwhitelist
     * @param \Illuminate\Http\Request $request
     * @param string $add_ip
     * @return json
     *
     */
	public function adduserwhitelistedips(Request $request)
	{
		$data =  $this->iam->addUserWhitelistedIps([ 'form_params' => $request->all()]);
       	echo json_encode($data,true);
	}
	/**
	* This function is used to list All Whitelisted / to be Whitelist Ip's.
	* @author Vikash Kumar
	* @access public
	* @package ipwhitelist
	* @param \Illuminate\Http\Request $request
	* @param int $limit, int $page Pagination Variables
	* @param string $searchkeyword
	* @return json
	*
	*/
	public function gettokenwhitelist() 
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

		$ip_resp = $this->iam->getTokenWhitelist($options);
		if($ip_resp['is_error'])
		{
			$is_error = $ip_resp['is_error'];
			$msg = $ip_resp['msg'];
		}
		else
		{
			$tokenips = _isset(_isset($ip_resp,'content'),'records');
			$paging['total_rows'] = _isset(_isset($ip_resp,'content'),'totalrecords');
			$paging['showpagination'] = true;
			$paging['jsfunction'] = 'ipList()';
			$view = 'Admin/tokenwhitelist';
			$content = $this->emlib->emgrid($tokenips, $view, [], $paging);
		}

		$response["html"] = $content;
		$response["is_error"] = $is_error;
		$response["msg"] = $msg;
		echo json_encode($response);
	}
	
	/**
	* This function is used to approve, requested Ip's for Whitelist.
	* @author Vikash Kumar
	* @access public
	* @package ipwhitelist
	* @param \Illuminate\Http\Request $request
	* @return json
	*
	*/
	public function approveuserwhitelistedips(Request $request)
	{
		$data =  $this->iam->approveUserWhitelistedips([ 'form_params' => $request->all()]);
       	echo json_encode($data,true);
	}
	
	/**
	* This function is used to get WhiteList IP's of user.
	* @author Vikash Kumar
	* @access public
	* @package ipwhitelist
	* @param \Illuminate\Http\Request $request
	* @return json
	*
	*/
	public function userwhilistedips(Request $request)
	{
		$is_error = false;
        $msg = $content="";
		$form_params = [];
		
		$options = [
            'form_params' => $form_params];

		$wip_resp = $this->iam->userWhilistedIps($options);
		
		if($wip_resp['is_error'])
		{
			$is_error = $wip_resp['is_error'];
			$msg = $wip_resp['msg'];
		}
		else
		{
			$whitelisted_ips = _isset(_isset($wip_resp,'content'),'0');	
			
			$view = 'Admin/ipswhitelisted';
			//view("Admin/ipwhitelist",$data);
			$finaldata['whitelisted_ips_data'] = $whitelisted_ips;
			$content = \View::make('Admin/ipswhitelisted', $finaldata)->render();
		}

		$response["html"] = $content;
		$response["is_error"] = $is_error;
		$response["msg"] = $msg;
		echo json_encode($response);
	}
	/**
	* This function is used to function used to delete single or multiple Whitelisted IPs/Subnet of user.
	* @author Vikash Kumar
	* @access public
	* @package ipwhitelist
	* @param \Illuminate\Http\Request $request
	* @param string $flag  [ip/subnet]
	* @param string $delete_ip
	* @param string $delete_subnet
	* @return json
	*
	*/
	public function deletewhitelistip(Request $request)
	{	
		$input_data = [];
		$input_data['flag'] = $request->flag;
		if($input_data['flag'] == 'subnet')
			$input_data['delete_subnet'] = trim($request->delete_subnet,",");
		else
			$input_data['delete_ip'] = trim($request->delete_ip,",");
		$data =  $this->iam->deleteWhiteListIp([ 'form_params' => $input_data]);
       	echo json_encode($data,true);
	}
	
	
}
