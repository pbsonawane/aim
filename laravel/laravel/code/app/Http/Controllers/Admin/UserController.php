<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Redirect;
/**
 * UserController class is implemented to do CRUD operations on User Module
 * @author Amit Khairnar
 * @package user
 */
class UserController extends Controller
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
    public function __construct(IamService $iam, Request $request)
    {
        $this->iam = $iam;
        $this->emlib = new Emlib;
        $this->request = $request;
        $this->request_params = $this->request->all();
    }

    /**
     * Users controller function is implemented to initiate a page to get list of Users.
     * @author Amit Khairnar
     * @access public
     * @package user
     * @return string
     */
    public function users()
    {
        $topfilter = array('gridsearch' => true, 'jsfunction' => 'userList()', 'gridadvsearch' => true);
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter, '', array('roles','usertypes','departments','designations','organizations'));
        $data['pageTitle'] = "User";
        $data['includeView'] = view("Admin/users", $data);
        return view('template', $data);
        
    }
    /**
     * This controller function is implemented to get list of Users.
     * @author Amit Khairnar
     * @access public
     * @package user
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */
    public function userlist()
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
        $options = [
            'form_params' => $form_params];
        $users_resp = $this->iam->getUsers($options);
        if ($users_resp['is_error'])
        {
            $is_error = $users_resp['is_error'];
            $msg = $users_resp['msg'];
        }
        else
        {	
            $users = _isset(_isset($users_resp, 'content'), 'records');
            

            $paging['total_rows'] = _isset(_isset($users_resp, 'content'), 'totalrecords');
            $paging['showpagination'] = true;
            $paging['jsfunction'] = 'userList()';
            $view = 'Admin/userlist';
			
			$show_fields = array();
			
			$show_fields_data = $this->userfields();
			if($show_fields_data != '')
			{
				$show_fields = json_decode($show_fields_data,'true');
			}
			$columns = $show_fields;
			
            $content = $this->emlib->emgrid($users, $view, $columns, $paging);
        }
        $response["html"] = $content;
        $response["is_error"] = $is_error;
        $response["msg"] = $msg;
        echo json_encode($response);
    }

    /**
     * This controller function is used to load user add form.
     * @author Amit Khairnar
     * @access public
     * @package user
     * @return string
     */
    public function useradd()
    {
        $userdata = array();
        $alldata = $this->userdata();
        $data['alldata'] = $alldata;
        $data['userid'] = '';
        $data['userdata'] = $userdata;
        $html = view("Admin/useradd", $data);
        echo $html;
    }
    public function userdata()
    {
        $limit_offset = limitoffset(0, 0);
        $form_params['limit'] = $limit_offset['limit'];
        $form_params['page'] = $limit_offset['page'];
        $form_params['offset'] = $limit_offset['offset'];
        $options = ['form_params' => $form_params];
        //Users Data
        $users = $this->iam->getUsers($options);
        $userData['users'] =  _isset(_isset($users,'content'),'records'); 
        //Roles Data
        $roles = $this->iam->getRoles($options);
        $userData['roles'] = _isset(_isset($roles, 'content'), 'records');
        //Department Data
         $departments = $this->iam->getDepartment($options);
        $userData['departments'] = _isset(_isset($departments, 'content'), 'records');
        //Designations Data
        $designations = $this->iam->getDesignations($options);
        $userData['designations'] = _isset(_isset($designations, 'content'), 'records');
        //Organizations Data

        $orgs = $this->iam->getOrg($options);
        $userData['orgs'] = _isset(_isset($orgs, 'content'), 'records');

        return $userData;
    }
    /**
     * This controller function is used to change user status
     * @author Amit Khairnar
     * @access public
     * @package user
     * @return string
     */
    function suspenduser(Request $request)
    {
        $params['userid'] = $request->input('userid');
        $params['status'] = $request->input('status');
        $data = $this->iam->suspenduser(array('form_params' => $params));
        echo json_encode($data, true);
    }

    /**
     * This controller function is used to get organization options by user type
     * @author Amit Khairnar
     * @access public
     * @package user
     * @return string
     */

    function getorgoptions(Request $request)
    {
        if($request->input('selected') != '')
            $selected = $request->input('selected');
        else
            $selected = array();
                $limit_offset = limitoffset(0, 0);
        $option = '';
        $form_params['limit'] = $limit_offset['limit'];
        $form_params['page'] = $limit_offset['page'];
        $form_params['offset'] = $limit_offset['offset'];
        $options = ['form_params' => $form_params];
        $orgs = $this->iam->getOrg($options);
        $orgs = _isset(_isset($orgs, 'content'), 'records');

        if(is_array($orgs) && count($orgs) > 0)
        { 
            foreach($orgs as $org)
            {
                $sel = $org['organization_id'] == $selected ? "selected" : ""; 
                $option .='<option value="'.$org['organization_id'].'" '.$sel.'>'.ucfirst($org['organization_name']) .'</option>'; 
            }
        }
        return $option;   
    }
    /**
     * This controller function is used to get role options
     * @author Amit Khairnar
     * @access public
     * @package user
     * @return string
     */
    function getroleoptions(Request $request)
    {
        $type = $request->input('type');
        if($request->input('selected') != '')
            $selectedarray = json_decode($request->input('selected'),true);
        else
            $selectedarray = array();
      
        $limit_offset = limitoffset(0, 0);
        $option = '';
        $form_params['limit'] = $limit_offset['limit'];
        $form_params['page'] = $limit_offset['page'];
        $form_params['offset'] = $limit_offset['offset'];
        $form_params['roletype'] = $type;
        $options = ['form_params' => $form_params];
        //Roles Data
        $roles = $this->iam->getRoles($options);
        $roleoptions = _isset(_isset($roles, 'content'), 'records');
        if($type != '')
        {
            if(is_array($roleoptions) && count($roleoptions) > 0)
            {
               
                foreach($roleoptions as $roleoption)
                {
                    $sel = '';
                    $sel = in_array($roleoption['role_id'], $selectedarray) ? "selected" : ""; 
                    $option .='<option value="'.$roleoption['role_id'].'" '.$sel.'>'.ucfirst($roleoption['role_name']) .'</option>'; 
                }
                
            }
        } 
        return $option;        
    }
    /**
     * This controller function is used to save user data in database.
     * @author Amit Khairnar
     * @access public
     * @package user
     * @param string $username User Name
     * @param string $email email
     * @param array $role_id Role ID
     * @param string $firstname First Name
     * @param string $lastname Last Name
     * @param string $email Email ID
     * @param string $organization_id Organization ID
     * @param string $designation_id Designation ID
     * @param string $department_id Department ID
     * @param string $password Password
     * @param string $password_confirmation Password Confirmation
     * @param string $manager_id Mnager ID
     * @return json
     */
    public function usersave(Request $request)
    {
       $userdata = $request->all();
       
       if(is_array($userdata) && count($userdata) > 0)
       {
            foreach($userdata as $key => $udata)
            {
                if($key == 'password' || $key == 'password_confirmation')
                {
                    $farray[$key] =_isset($this->request_params, $key) ? base64_encode($request->input($key)): '';
                }
                else
                $farray[$key] =_isset($this->request_params, $key) ? $request->input($key): '';
            }
            
       }
       $role_id = _isset($this->request_params, 'role_id') ? $request->input('role_id'): '';
       $farray['role_id'] = $role_id;
       //print_r($farray);
        $data = $this->iam->addUser(array('form_params' => $farray));
        echo json_encode($data, true);
    }
    /**
     * This controller function is used to save organization name in database.
     * @author Amit Khairnar
     * @access public
     * @package user
     * @param string $organization_name Organization Name
     * @return json
     */
    public function orgsave(Request $request)
    {
        $data = $this->iam->createOrg(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }

    /**
     * This controller function is used to genrate new user password .
     * @author Amit Khairnar
     * @access public
     * @package user
     * @return json
     */
    public function userpassword()
    {
        $data = $this->iam->userPassword();
        echo json_encode($data, true);
    }

    /**
     * This controller function is used to load user edit form with existing data for selected user
     * @author Amit Khairnar
     * @access public
     * @package user
     * @param \Illuminate\Http\Request $request
     * @param $user_id User Unique Id
     * @return string
     */
    public function useredit(Request $request)
    {
        $userid =$request->input('userid');
		$input_req = array('user_id' => $userid);
        $data =  $this->iam->editUser(array('form_params' => $input_req));
        $alldata = $this->userdata();
        $data['alldata'] = $alldata;
        $data['userid'] = $userid;
		$data['userdata'] = $data['content'];
        $html = view("Admin/useradd",$data);
        echo  $html;
    }
    /**
     * This controller function is used to update user data in database.
     * @author Amit Khairnar
     * @access public
     * @package user
     * @param UUID $user_id User Unique Id
     * @param string $username User Name
     * @param string $email email
     * @param array $role_id Role ID
     * @param string $firstname First Name
     * @param string $lastname Last Name
     * @param string $email Email ID
     * @param string $organization_id Organization ID
     * @param string $designation_id Designation ID
     * @param string $department_id Department ID
     * @param string $manager_id Mnager ID
     * @return json
     */
    public function userupdate(Request $request)
    {
        $userdata = $request->all();
       
       if(is_array($userdata) && count($userdata) > 0)
       {
            foreach($userdata as $key => $udata)
            {
                $farray[$key] =_isset($this->request_params, $key) ? $request->input($key): '';
            }
            
       }
       $role_id = _isset($this->request_params, 'role_id') ? $request->input('role_id'): '';
       $farray['role_id'] = $role_id;
        $data = $this->iam->updateUser(array('form_params' => $farray));
        echo json_encode($data, true);
    }

     /**
     * This controller function is used to delete user data from database.
     * @author Amit Khairnar
     * @access public
     * @package user
     * @param UUID $user_id User Unique Id
     * @return json
     */
    public function userdelete(Request $request)
    {
        $userid = $request->input('userid');
		$input_req = array('user_id' => $userid);
        $data = $this->iam->deleteuser(array('form_params' => $input_req));
        echo json_encode($data, true);
    }
	
	/**
     * This controller function is used to get fields user want to display in user list page.
     * @author Vikash Kumar
     * @access public
     * @package user
     * @param string $type [user]
     * @return json
     */
	public function userfields()
	{
		$return_data = "[]";
		$input_req = array('type' => 'user');
        $regions_resp =  $this->iam->getFields(array('form_params' => $input_req));
		$dislay_data = _isset(_isset($regions_resp,'content'),'records'); //$data['content']['records'];
		$totalrecords =  _isset(_isset($regions_resp,'content'),'totalrecords');
		if($totalrecords > 0)
		{
			$return_data = $dislay_data[0]['details'];
		}
		return $return_data;
	}
	
	/**
     * This controller function is used to load User List Coloumn Configuration page.
     * @author Vikash Kumar
     * @access public
     * @package user
     * @param \Illuminate\Http\Request $request
     * @param $type string [user]
     * @return string
     */
	public function userdisplaysetting(Request $request)
	{	
		$data['display_fields'] = array();
		$type = $request->type;

		$input_req = array('type' => $type);
		$regions_resp =  $this->iam->getFields(array('form_params' => $input_req));
		
		$display_data = _isset(_isset($regions_resp,'content'),'records'); //$data['content']['records'];
		$totalrecords =  _isset(_isset($regions_resp,'content'),'totalrecords');
		if($totalrecords > 0)
		{
			$data['display_fields'] = json_decode($display_data[0]['details'],true);
		}
        $html = view("Admin/userlistsetting",$data);
        echo  $html;
	}
	
	/**
     * This controller function is used to Save coloumns user want in user list page.
     * @author Vikash Kumar
     * @access public
     * @package user
     * @param \Illuminate\Http\Request $request
     * @param $type string [user]
	 * @param $selected_fields string Selected coloumns comma seperated
     * @return string
     */
	public function userdisplaysettingsave(Request $request)
	{	
	   $input_data = array('selected_fields' => trim($request->selected_fields,","),'type' => $request->type);
       $data =  $this->iam->saveUserFields(array( 'form_params' => $input_data));
       echo json_encode($data,true);
	}
	
	/**
     * This controller function is used to Assign different enitites like Region,DC,Locations etc to user
     * @author Vikash Kumar
     * @access public
     * @package user
     * @param \Illuminate\Http\Request $request
     * @param $user_id UUID [user_id]
     * @return string
     */
	public function userassignentities(Request $request)
	{	
        $userinfo = $request->userinfo;
		$data['userdata'] = array();
        $data['userinfo'] = json_decode($userinfo,true);
        
    
        $user_id = $data['userinfo']['user_id'];
        $form_params['user_id'] = $user_id;
        $options = ['form_params' => $form_params];
        $modules_resp = $this->iam->getUserModules($options);
        if ($modules_resp['is_error'])
        {
            $modules = array();
        }
        else
        {
            $modules = _isset($modules_resp, 'content');
        }
        $data['modules'] = $modules;
        $html = view("Admin/userassignentities",$data);
        echo  $html;
    }
    public function usermoduleupdate(Request $request)
    {
        $data = $this->iam->usermoduleupdate(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }
	
	/**
     * This controller function is used to fetch Business Verticals assign/assigned to user
     * @author Vikash Kumar
     * @access public
     * @package user
     * @param \Illuminate\Http\Request $request
     * @param $user_id UUID [user_id]
     * @return string
     */
	public function userbvs(Request $request)
	{	
        $user_id = $request->input('user_id');
        $form_params['user_id'] = $user_id;
        $options = ['form_params' => $form_params];
        $bv_resp = $this->iam->getUserBvs($options);
        if ($bv_resp['is_error'])
        {
            $bvs = array();
        }
        else
        {
            $bvs = _isset($bv_resp, 'content');
        }
        $data['bvs'] = $bvs;
		$data['user_id'] = $user_id;
        $html = view("Admin/userbvs",$data);
        echo  $html;
    }
	
	/**
     * This controller function is used to Save user Business Verticals.
     * @author Vikash Kumar
     * @access public
     * @package user
     * @param \Illuminate\Http\Request $request
     * @param $user_id UUID user ID
	 * @param $bv_ids string Selected business Verticals IDs comma seperated
     * @return string
     */
	public function userbvupdate(Request $request)
	{	

        $data = $this->iam->userBvUpdate(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }
	
	/**
     * This controller function is used to fetch Business Verticals assign/assigned to user
     * @author Vikash Kumar
     * @access public
     * @package user
     * @param \Illuminate\Http\Request $request
     * @param $user_id UUID [user_id]
     * @return string
     */
	public function userregions(Request $request)
	{	
        $user_id = $request->input('user_id');
        $form_params['user_id'] = $user_id;
        $options = ['form_params' => $form_params];
        $regions_resp = $this->iam->userRegions($options);
        if ($regions_resp['is_error'])
        {
            $regions = array();
        }
        else
        {
            $regions = _isset($regions_resp, 'content');
        }
        $data['regions'] = $regions;
		$data['user_id'] = $user_id;
        $html = view("Admin/userregions",$data);
        echo  $html;
    }
	public function regiondcspods(Request $request)
	{	
		$result_res = array();
		$locations_str  = $dc_str = $pod_str = "";
        $user_id = $request->input('user_id');
		$region_ids = $request->input('region_ids');
		$region_array = array();
		if($region_ids != '')
		{
			$region_ids = trim($region_ids,",");
			$region_array = explode(",",$region_ids);
		}	
			
        $form_params['user_id'] = $user_id;
		$form_params['region_ids'] = $region_array;
		
        $options = ['form_params' => $form_params];
        $regions_resp = $this->iam->regionDcsPodsLoc($options);
		
        if ($regions_resp['is_error'])
        {
            $result_res = array();
        }
        else
        {
            $result_res = _isset($regions_resp, 'content');
			
			if(is_array($result_res['locations']) && count($result_res['locations']) > 0)
			{
				foreach($result_res['locations'] as $each_loc)
				{	
					$loc_checked = "";
					if($each_loc['checked'])
						$loc_checked = 'checked = "checked"';
					$locations_str .= '<tr><td><div class="checkbox-custom mb5"><input type="checkbox" class="user_locations" id="'.$each_loc['location_id'].'" value="'.$each_loc['location_id'].'" '.$loc_checked.'/><label for="'.$each_loc['location_id'].'">'.$each_loc['location_name'].'</label></div></td></tr>';
					
				}
			}
			if(is_array($result_res['dcs']) && count($result_res['dcs']) > 0)
			{
				foreach($result_res['dcs'] as $each_dc)
				{	
					$dc_checked = "";
					if($each_dc['checked'])
						$dc_checked = 'checked = "checked"';
					$dc_str .= '<tr><td><div class="checkbox-custom mb5"><input type="checkbox" class="user_dcs" id="'.$each_dc['dc_id'].'" value="'.$each_dc['dc_id'].'" '.$dc_checked.'/><label for="'.$each_dc['dc_id'].'">'.$each_dc['dc_name'].'</label></div></td></tr>';
					
				}
			}
			
			if(is_array($result_res['pods']) && count($result_res['pods']) > 0)
			{
				foreach($result_res['pods'] as $each_pod)
				{	
					$pod_checked = "";
					if($each_pod['checked'])
						$pod_checked = 'checked = "checked"';
					$pod_str .= '<tr><td><div class="checkbox-custom mb5"><input type="checkbox" class="user_pods" id="'.$each_pod['pod_id'].'" value="'.$each_pod['pod_id'].'" '.$pod_checked.'/><label for="'.$each_pod['pod_id'].'">'.$each_pod['pod_name'].'</label></div></td></tr>';
					
				}
			}
        }
        echo  $locations_str."#|#".$dc_str."#|#".$pod_str;
    }
	public function dcspods(Request $request)
	{	
		$result_res = array();
		$pod_str = "";
        $user_id = $request->input('user_id');
		$dc_ids = $request->input('dc_ids');
		$region_array = array();
		if($dc_ids != '')
		{
			$dc_ids = trim($dc_ids,",");
			$dc_array = explode(",",$dc_ids);
		}	
			
        $form_params['user_id'] = $user_id;
		$form_params['dc_ids'] = $dc_array;
		
        $options = ['form_params' => $form_params];
        $regions_resp = $this->iam->dcPods($options);
		
        if ($regions_resp['is_error'])
        {
            $result_res = array();
        }
        else
        {
            $result_res = _isset($regions_resp, 'content');
			if(is_array($result_res['pods']) && count($result_res['pods']) > 0)
			{
				foreach($result_res['pods'] as $each_pod)
				{	
					$pod_checked = "";
					if($each_pod['checked'])
						$pod_checked = 'checked = "checked"';
					$pod_str .= '<tr><td><div class="checkbox-custom mb5"><input type="checkbox" class="user_pods" id="'.$each_pod['pod_id'].'" value="'.$each_pod['pod_id'].'" '.$pod_checked.'/><label for="'.$each_pod['pod_id'].'">'.$each_pod['pod_name'].'</label></div></td></tr>';
				}
			}
        }
        echo  $pod_str;
    }
	
	/**
     * This controller function is used to Save user Entities like Region,DC,Location and POD.
     * @author Vikash Kumar
     * @access public
     * @package user
     * @param \Illuminate\Http\Request $request
     * @param $user_id UUID user ID
	 * @param $region_ids Comma seperated region IDs
	 * @param $location_ids Comma seperated location IDS
	 * @param $dc_ids Comma seperated Datacenter IDS
	 * @param $pod_ids Comma seperated POD IDS
     * @return string
     */
	public function userregionupdate(Request $request)
	{	
        $data = $this->iam->userEntitiesSave(array('form_params' => $request->all()));
        echo json_encode($data, true);
    }
	
	
    public function getuserprofile(Request $request){
    
        $data =  $this->iam->getuserprofile(array('form_params' => $request->all()));
        $uploadfilepath=$data['content'][0]['profile_photo'];
        $data['alldata'] = $data;
        $data['userdata'] = $data['content'][0];
        $data['uploadfilepath']=$uploadfilepath;
        $data['pageTitle'] = "Viewprofile";
        $data['includeView'] = view("Admin/viewprofile", $data);
        return view('template', $data);
       
    }
    public function checkvalidpassword(Request $request){
        $username=showusername();
        $oldpassword = base64_encode($request->input('oldpassword'));
        $form_params['username'] = $username;
		$form_params['password'] = $oldpassword;
        $options = ['form_params' => $form_params];
        //print_r($options);
        $data =  $this->iam->checkvalidpassword($options);
        echo json_encode($data, true);
    }
    public function updatenewpassword(Request $request){
        $username=showusername();
       // $userid=showuserid();
       
        $userid = $request->input('user_id');
        $oldpassword = $request->input('oldpassword');
        $password = base64_encode($request->input('password'));
        $password_confirmation = base64_encode($request->input('password_confirmation'));
        $form_params['oldpassword'] = $oldpassword;
        $form_params['username'] = $username;
        $form_params['user_id'] = $userid;
        $form_params['password'] = $password;
        $form_params['password_confirmation'] = $password_confirmation;
        $options = ['form_params' => $form_params];
      
        $data =  $this->iam->updatenewpassword($options);
        //print_r($userid);
        echo json_encode($data, true);
    }
    
    /*public function profilephoto(Request $request)
    {
        $data = array();
        $file = $request->file('profile_photos');
        var_dump($file);
        die();
        $data['pageTitle'] = "test";
        $data['includeView'] = view("Admin/test", $data);
        return view('template', $data);
    }*/
    
    public function editprofilesubmit(Request $request)
    {
        
       // $profile_photos = $request->file('profile_photo');
       // print_r($profile_photos);
        $profile_photos_content= base64_encode(file_get_contents($_FILES['profile_photo']['tmp_name']));
        //$base64=base64_encode($profile_photos_content);
        //print_r($base64);exit;
        $form_params['profile_photo'] =  $profile_photos_content ;
        $form_params['profile_photo_name'] =  $_FILES['profile_photo']['tmp_name'];
        $options = ['form_params' => $form_params];
        $data =  $this->iam->editprofilesubmit($options);
        //print_r($data);
        echo json_encode($data, true);

       /* $req=$request->all();
        $profile_photos = $request->input('profile_photo');
        $profile_photos_content= base64_encode(file_get_contents($_FILES['profile_photo']['tmp_name']));
        header('Content-type:image/jpeg');
        readfile($_FILES['profile_photo']['tmp_name']);
        $form_params['profile_photo'] =  $profile_photos_content ;
        //print_r($form_params['profile_photo'] );
        $options = ['form_params' => $form_params];
        $data =  $this->iam->editprofilesubmit($options);
        //$data = $this->iam->editprofilesubmit(array('form_params' => $request->all()));
        echo json_encode($data, true);
        
        */
       // $req=$request->all();
        //print_r($req);
    

        /*Working code
        $filename = "";
        $uploadfilepath='';
        if ($request->hasFile('profile_photo'))
        {
            $data['data'] = $request;
            if ($request->file('profile_photo')->isValid())
            {
                $userid=showuserid();
                $extension = $request->file('profile_photo')->getClientOriginalExtension(); // getting image extension
                $filename = "__profile".time().'.'.$extension;
                //$userid = $request->input('user_id');
               // print_r($user_id);exit;
                $profile_photo_link='/uploads/profiles/'.$filename;
                $request->file('profile_photo')->move('uploads/profiles', $filename);
                $form_params['user_id'] = $userid;
                $form_params['profile_photo'] =  $profile_photo_link ;
                $options = ['form_params' => $form_params];
                //dd($options);
                $validateresponse =  $this->iam->editprofilesubmit($options);
               // echo json_encode($data, true);
               if($validateresponse['is_error'])
               {
                   return Redirect::to('/getuserprofile')
                   ->withErrors([
                       'notupload' => showerrormsg($validateresponse['msg']),
                   ]);
               }
               else
               {
                   return Redirect::to('/getuserprofile')
                   ->withErrors([
                       'upload' => showerrormsg($validateresponse['msg']),
                       'uploadfilepath' => $profile_photo_link,
                   ]);
               }
            }
            
        }*/

    }
	
}
