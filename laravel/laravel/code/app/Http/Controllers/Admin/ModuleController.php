<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\IAM\IamService;
use App\Libraries\Emlib;
/**
 * ModuleController class is implemented to do CRUD operations on Modules
 * @author Vikash Kumar
 * @package module
 */
class ModuleController extends Controller
{	
	/**
     * Contructor function to initiate the API service and Request data
     * @author Vikash Kumar
     * @access public
     * @package module
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
     * Module controller function is implemented to initiate a page to get list of Modules.
     * @author Vikash Kumar
     * @access public
     * @package module
     * @return string
     */
	public function modules() {

		$topfilter = array('gridsearch' => true,'jsfunction' => 'moduleList()');
		$data['emgridtop'] = $this->emlib->emgridtop($topfilter);
		$data['pageTitle'] = "Modules";
		$data['includeView'] = view("Admin/modules",$data);
		return view('template',$data);
	}
	/**
     * This controller function is implemented to get list of Modules.
     * @author Vikash Kumar
     * @access public
     * @package module
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */
	public function modulelist() {
		$paging = array();
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

		$modules_resp = $this->iam->getModules($options);
		if($modules_resp['is_error'])
		{
			$is_error = $modules_resp['is_error'];
			$msg = $modules_resp['msg'];
		}
		else
		{
			$modules = _isset(_isset($modules_resp,'content'),'records');
			$paging['total_rows'] = _isset(_isset($modules_resp,'content'),'totalrecords');
			$paging['showpagination'] = true;
			$paging['jsfunction'] = 'moduleList()';
			$view = 'Admin/modulelist';
			$content = $this->emlib->emgrid($modules, $view, array(), $paging);
		}

		$response["html"] = $content;
		$response["is_error"] = $is_error;
		$response["msg"] = $msg;
		echo json_encode($response);
	}
	/**
     * This controller function is used to load module add form.
     * @author Vikash Kumar
     * @access public
     * @package module
     * @return string
     */
	public  function moduleadd(Request $request)
    {
        $data['module_id'] = '';
		$moduledata = array();
		$data['moduledata'] = $moduledata;
        $html = view("Admin/moduleadd",$data);
        echo  $html;
    }
	/**
     * This controller function is used to save module data in database.
     * @author Vikash Kumar
     * @access public
     * @package module
	 * @param string $module_name Module Name
     * @param string $module_key Module Key
     * @return json
     */
	public function modulesave(Request $request)
    {
       $data =  $this->iam->addModule(array( 'form_params' => $request->all()));
       echo json_encode($data,true);
    }
	/**
     * This controller function is used to load module edit form with existing data for selected module
     * @author Vikash Kumar
     * @access public
     * @package module
     * @param \Illuminate\Http\Request $request
     * @param $module_id Module Unique Id
     * @return string
     */
	public function moduleedit(Request $request)
	{	
		$module_id = $request->id;
		$input_req = array('module_id' => $module_id);
		$data =  $this->iam->getModules(array( 'form_params' => $input_req));
		
		$data['module_id'] = $module_id;
		$data['moduledata'] = $data['content']['records'];

        $html = view("Admin/moduleadd",$data);
        echo  $html;
	}
	/**
     * This controller function is used to update module data in database.
     * @author Vikash Kumar
     * @access public
     * @package module
     * @param UUID $module_id Module Unique Id
	 * @param string $module_name Module Name
	 * @param string $module_key Module Key
     * @return json
     */
	public function moduleupdate(Request $request)
    {
       $data =  $this->iam->updateModule(array( 'form_params' => $request->all()));
       echo json_encode($data,true);
    }
	 /**
     * This controller function is used to delete module data from database.
     * @author Vikash Kumar
     * @access public
     * @package module
     * @param UUID $module_id Module Unique Id
     * @return json
     */
	public function moduledelete(Request $request)
	{
		$data =  $this->iam->deleteModule(array( 'form_params' => $request->all()));
       	echo json_encode($data,true);
	}
	
}
