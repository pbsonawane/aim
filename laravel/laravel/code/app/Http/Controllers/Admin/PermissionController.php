<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use Illuminate\Http\Request;

/**
 * PermissionController class is implemented to do CRUD operations on permission master
 * @author Pravin Sonawane
 * @package user
 */
class PermissionController extends Controller
{
    /**
     * Contructor function to initiate the API service and Request data
     * @author Pravin Sonawane
     * @access public
     * @package user
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
    }

    /**
     * This controller function is implemented to initiate a page to get list of permissions.
     * @author Pravin Sonawane
     * @access public
     * @package user
     * @return string
     */
    public function permissions()
    {
        $topfilter = ['gridsearch' => true, 'jsfunction' => 'permissionList()'];
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter);
        $data['pageTitle'] = "Permission";
        $data['includeView'] = view("Admin/permissions", $data);
        return view('template', $data);
    }
    /**
     * This controller function is implemented to get list of permissions.
     * @author Pravin Sonawane
     * @access public
     * @package user
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */
    public function permissionlist()
    {
        $paging = [];
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
        $permissions_resp = $this->iam->getPermissions($options);
        if ($permissions_resp['is_error'])
        {
            $is_error = $permissions_resp['is_error'];
            $msg = $permissions_resp['msg'];
        }
        else
        {
            $permissions = _isset(_isset($permissions_resp, 'content'), 'records');
            $paging['total_rows'] = _isset(_isset($permissions_resp, 'content'), 'totalrecords');
            $paging['showpagination'] = true;
            $paging['jsfunction'] = 'permissionList()';
            $view = 'Admin/permissionlist';
            $content = $this->emlib->emgrid($permissions, $view, $columns = [], $paging);
        }
        $response["html"] = $content;
        $response["is_error"] = $is_error;
        $response["msg"] = $msg;
        echo json_encode($response);
    }

    /**
     * This controller function is used to load permission add form.
     * @author Pravin Sonawane
     * @access public
     * @package user
     * @return string
     */
    public function permissionadd(Request $request)
    {
        $categories = $permissiondata = [];
        $modules = $this->iam->getModules(['form_params' => $request->all()]);
        $data['modules'] = is_array($modules['content']['records']) ? $modules['content']['records'] : [];
        $data['permissiondata'] = $permissiondata;
        $categories = is_array($this->permissioncategories(true)) ? $this->permissioncategories(true) : [];
        $data['categories'] = is_array($categories['content']) && count($categories['content']) > 0 ? $categories['content'] : [];
        $html = view("Admin/permissionadd", $data);
        echo $html;
    }

    /**
     * This controller function is used to save permission data in database.
     * @author Pravin Sonawane
     * @access public
     * @package user
     * @param string $permission_name Permission Name
     * @param string $permission_key Permission key
     * @param string $permission_type Permission type
     * @param string $perm_category_name Permission category will have autocomplete to show existing categories or can be added new category
     * @param UUID $module_id Module
     * @param string $permission_description Description
     * @return json
     */
    public function permissionsave(Request $request)
    {
        $data = $this->iam->addPermission(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }

    /**
     * This controller function is used to load permission edit form with existing data for selected permission
     * @author Pravin Sonawane
     * @access public
     * @package user
     * @param \Illuminate\Http\Request $request
     * @param $permission_id Permission Unique Id
     * @return string
     */
    public function permissionedit(Request $request)
    {
        $permissionid = $request->input('permissionid');
        $input_req = ['permission_id' => $permissionid];
        $data = $this->iam->editPermission(['form_params' => $input_req]);
        $modules = $this->iam->getModules(['form_params' => $request->all()]);
        $data['modules'] = is_array($modules['content']['records']) ? $modules['content']['records'] : [];
        $data['permissionid'] = $permissionid;
        $data['permissiondata'] = $data['content'];
        $categories = is_array($this->permissioncategories(true)) ? $this->permissioncategories(true) : [];
        $data['categories'] = is_array($categories['content']) && count($categories['content']) > 0 ? $categories['content'] : [];
        $html = view("Admin/permissionadd", $data);
        echo $html;
    }

    /**
     * This controller function is used to update permission data in database.
     * @author Pravin Sonawane
     * @access public
     * @package user
     * @param UUID $permission_id Permission Unique Id
     * @param string $permission_name Permission Name
     * @param string $permission_key Permission key
     * @param string $permission_type Permission type
     * @param string $perm_category_name Permission category will have autocomplete to show existing categories or can be added new category
     * @param UUID $module_id Module
     * @param string $permission_description Description
     * @return json
     */
    public function permissionupdate(Request $request)
    {
        $data = $this->iam->updatePermission(['form_params' => $request->all()]);
        echo json_encode($data, true);
    }

    /**
     * This controller function is used to delete permission data from database.
     * @author Pravin Sonawane
     * @access public
     * @package user
     * @param UUID $permission_id Permission Unique Id
     * @return json
     */
    public function permissiondelete(Request $request)
    {
        $permissionid = $request->input('permissionid');
        $input_req = ['permission_id' => $permissionid];
        $data = $this->iam->deletePermission(['form_params' => $input_req]);
        echo json_encode($data, true);
    }

    /**
     * This controller function is used to get permission categories.
     * @author Pravin Sonawane
     * @access public
     * @package user
     * @return json
     */
    public function permissioncategories($return = false)
    {
        $data = $this->iam->permissionCategories();
        if ($return == true)
        {
            return $data;
        }
        else
        {
            echo json_encode($data, true);
        }
    }
}
