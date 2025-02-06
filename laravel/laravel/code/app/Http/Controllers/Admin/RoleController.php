<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use Illuminate\Http\Request;

/**
 * RoleController class is implemented to do CRUD operations on Role Module
 * @author Pravin Sonawane
 * @package role
 */
class RoleController extends Controller
{
    /**
     * Contructor function to initiate the API service and Request data
     * @author Pravin Sonawane
     * @access public
     * @package role
     * @param \App\Services\IAM\IamService $iam
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function __construct(IamService $iam, Request $request)
    {
        $this->iam = $iam;
        $this->emlib = new Emlib;
        $this->request = $request;
        $this->request_params = $this->request->all();
		$this->roletype = array('client' => 'Client', 'staff' => 'Staff');
    }

    /**
     * Roles controller function is implemented to initiate a page to get list of Roles.
     * @author Pravin Sonawane
     * @access public
     * @package role
     * @return string
     */
    public function roles()
    {
        $topfilter = array('gridsearch' => true, 'jsfunction' => 'roleList()');
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter);
        $data['pageTitle'] = "Role";
        $data['includeView'] = view("Admin/roles", $data);
        return view('template', $data);
    }
    /**
     * This controller function is implemented to get list of Roles.
     * @author Pravin Sonawane
     * @access public
     * @package role
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */
    public function rolelist()
    {
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
        $options = ['form_params' => $form_params];
        $roles_resp = $this->iam->getRoles($options);
        if ($roles_resp['is_error'])
        {
            $is_error = $roles_resp['is_error'];
            $msg = $roles_resp['msg'];
        }
        else
        {
            $roles = _isset(_isset($roles_resp, 'content'), 'records');
            $paging['total_rows'] = _isset(_isset($roles_resp, 'content'), 'totalrecords');
            $paging['showpagination'] = true;
            $paging['jsfunction'] = 'roleList()';
            $view = 'Admin/rolelist';
            $content = $this->emlib->emgrid($roles, $view, $columns = array(), $paging);
        }
        $response["html"] = $content;
        $response["is_error"] = $is_error;
        $response["msg"] = $msg;
        echo json_encode($response);
    }

    /**
     * This controller function is used to load role add form.
     * @author Pravin Sonawane
     * @access public
     * @package role
     * @return string
     */
    public function roleadd()
    {
        $roledata = array();
		$data['roletype'] = $this->roletype;
        $data['roledata'] = $roledata;
        $html = view("Admin/roleadd", $data);
        echo $html;
    }

    /**
     * This controller function is used to save role data in database.
     * @author Pravin Sonawane
     * @access public
     * @package role
     * @param string $role_name Role Name
     * @param string $role_type Role Type
     * @param string $role_key Role Key
     * @param string $role_description Role Description
     * @return json
     */
    public function rolesave(Request $request)
    {
        $data = $this->iam->addRole(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }

    /**
     * This controller function is used to load role edit form with existing data for selected role
     * @author Pravin Sonawane
     * @access public
     * @package role
     * @param \Illuminate\Http\Request $request
     * @param $role_id Role Unique Id
     * @return string
     */
    public function roleedit(Request $request)
    {
        $roleid =$request->input('roleid');
		$input_req = array('role_id' => $roleid);
        $data =  $this->iam->editRole(array('form_params' => $input_req));
        $data['roleid'] = $roleid;
		$data['roledata'] = $data['content'];
		$data['roletype'] = $this->roletype;
        $html = view("Admin/roleadd",$data);
        echo  $html;
    }
    /**
     * This controller function is used to update role data in database.
     * @author Pravin Sonawane
     * @access public
     * @package role
     * @param UUID $role_id Role Unique Id
     * @param string $role_name Role Name
     * @param string $role_type Role Type
     * @param string $role_key Role Key
     * @param string $role_description Role Description
     * @return json
     */
    public function roleupdate(Request $request)
    {
        $data = $this->iam->updateRole(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }

     /**
     * This controller function is used to delete role data from database.
     * @author Pravin Sonawane
     * @access public
     * @package role
     * @param UUID $role_id Role Unique Id
     * @return json
     */
    public function roledelete(Request $request)
    {
        $roleid = $request->input('roleid');
		$input_req = array('role_id' => $roleid);
        $data = $this->iam->deleterole(array('form_params' => $input_req));
        echo json_encode($data, true);
    }
    /**
     * This is model function is used get all Permissions by its PermissionCategories & permissions assigns to role.
     * @author Namrata Thakur
     * @access public
     * @package role
     * @param UUID $role_id Role Unique Id
     * @return string
     */
    public function rolepermissions(Request $request)
    {
        $roleid = $request->input('roleid');
		$input_req = array('role_id' => $roleid);
        $data = $this->iam->rolepermissions(array('form_params' => $input_req));
        $data['roleid'] = $roleid;
        if($data['content'])
        {
            //$data['permission_assign_to_role'] = $data['content']['permission_assign_to_role'];//not in use
            //$data['permission_access_rights'] = $data['content']['permission_access_rights'];//not in use
            $data['all_modules'] = $data['content']['all_modules'];
            $data['all_permisions_by_module'] = $data['content']['all_permisions_by_module'];
            $data['role_name'] = '';//$data['content'][0]['role_name'];
        }
        else{
            $data['rolepermissiondata'] = array();
            $data['role_name'] = '';
        }
		 
        $html = view("Admin/rolepermission",$data);
        echo  $html;
    }
    /**
     * This is model function is used update/add all Permissions by its PermissionCategories & permissions assigns to role.
     * @author Namrata Thakur
     * @access public
     * @package role
     * @param UUID $role_id Role Unique Id
     * @param array $accessrightsArr Array of Objects of Permissions with Access rights
     * @param string $permission_id comma Separated Permission Ids
     * @return json
     */
    public function roleassign(Request $request)
    {

      
        $roleid = $request->input('role_id');
        $accessrightsArr = _isset($this->request_params, 'accessrightsArr') ? $request->input('accessrightsArr'): array();
        $permission_id = $request->input('permission_id');
        $input_req = array('role_id' => $roleid, 'accessrightsArr'=> $accessrightsArr , 'permission_id' => $permission_id); 

        $data = $this->iam->assignrolepermissions(array('form_params' => $input_req));
        echo json_encode($data, true);
    }
}
