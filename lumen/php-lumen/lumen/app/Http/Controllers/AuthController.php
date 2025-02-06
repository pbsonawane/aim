<?php
namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\EnUsers;
use App\Models\EnAuth;
use App\Models\EnTokenForgotpwd;
use App\Models\EnTokenWhitelistip;
use Illuminate\Support\Facades\DB;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use Validator;

/**
 * Auth Controller Class - This controller used for authentication.
 *
 * @package Lumen
 * @subpackage controller
 * @category Category
 * @author Vishal
 * @link NULL
 */
class AuthController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $request;
    private $IpWhitelistConfig;
    public function __construct(Request $request)
    {
        $this->IpWhitelistConfig  = TRUE; //Keep configurable to check IP whitelist or not.
        $this->request = $request;
        DB::connection()->enableQueryLog();
        $this->status = array("active" => "y", "delete" => "n", "suspend" => 's');
    }

    /*
     * Create a new token.
     * @author Vishal Chaudhari
     * @access public
     * @param \App\User   $user
     * @request      POST
     * @return string
     * @category      Auth.
     *
     */
    protected function jwttoken(EnUsers $user)
    {
        $payload = [
            'issuer' => "lumen-jwt", // Issuer of the token
            'subject' => $user->user_id, // Subject of the token
            'genat' => time(), // Time when JWT was issued. 
            //'ipaddress' => $this->request->ip(), // Time when JWT was issued. 
            'expireon' => time() + 60*60 // Expiration time
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will 
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_KEY'));
    }

    /*
     * Authenticate a user and return the token if the provided credentials are correct.
     * @author Vishal Chaudhari
     * @access public
     * @param \App\User   $user
     * @request      POST
     * @return mixed
     * @category      Auth.
     *
    */

    public function authenticate(EnUsers $user)
    {
        $inputdata = $this->request->all();
        $validator = Validator::make($inputdata, [
            'username' => 'required|email',
            'password' => 'required'
        ]);
		
			
        if ($validator->fails())
        {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        }
		else
		{
			// Find the user by username
			$user = EnUsers::where('username', $this->request->input('username'))->select('username', 'allowed_ip', 'allowed_subnets', 'password', 'status', DB::raw('BIN_TO_UUID(user_id) AS user_id'), DB::raw('BIN_TO_UUID(parent_id) AS parent_id'), DB::raw('user_id AS user_id_bin'))->first();
			if (!$user) {
				$data['data'] = null;
				$data['message']['error'] = showmessage('109');
				$data['status'] = 'error';
			}    
			else
			{                
				// Verify the password and generate the token
				if (stringencrypt($this->request->input('password')) === $user->password) {
                    
                     //START : Code For Whitelist Ip, When First Time Login
                    if($this->IpWhitelistConfig)
                    {
                        $whitelist_result = $this->whitelistip_authenticate($user, $inputdata);
                        if($whitelist_result)
                        {
                            return response()->json($whitelist_result);
                        }
                    }
                    //END : Code For Whitelist Ip, When First Time Login else check for IP or else send Link throuh Email for IP Whitelist                     

                    $data['data']['token'] = $this->jwttoken($user);
                    /* newly Added*/
                    $data['data']['user_id'] = $user->user_id;
                    //$data['data']['user_id'] = DB::raw('UUID_TO_BIN("'.$user->user_id.'")');
					$data['data']['username'] = $user->username;
                    
					$data['message']['error'] = showmessage('110');
                    $data['status'] = 'success';                    
                   
                    userlog(array('record_id' => $user->user_id, 'data' => $inputdata, 'action' => 'login', 'message' => showmessage('600', array('{name}'),array($user->username))));
				}
				else
				{
					// Bad Request response
					$data['data'] = null;
					$data['message']['error'] = showmessage('111');
					$data['status'] = 'error';
				}
			}
		}	
        return response()->json($data);
    }

    /*
     * Token refresh 
     * @author Vishal Chaudhari
     * @access public
     * @param \App\User   $user
     * @request      POST
     * @return mixed
     * @category      Auth.
     *
    */

    public function tokenrefresh(EnUsers $user)
    {
        $inputdata = $this->request->all();
        $validator = Validator::make($inputdata, [
            'token' => 'required'
        ]);
        if ($validator->fails())
        {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
        }
		else
		{
			$token = (string)$inputdata['token'];
            $data = EnAuth::validateToken( $token );
            if( isset($data['status']) && $data['status'] == "error" &&  $data['data'] == "expired")
            {
                // Find the user by username
                $user = EnUsers::where('user_id', DB::raw('UUID_TO_BIN("'.$data['subject'].'")'))->first();
                if (!$user) {
                    $data['data'] = null;
                    $data['message']['error'] = showmessage('109');
                    $data['status'] = 'error';
                }    
                else
                {
                    // Verify the password and generate the token
                    $data = array();
                    $data['data']['token'] = $this->jwttoken($user);
                    $data['message']['error'] = showmessage('110');
                    $data['status'] = 'success';
                }
            }
        }	
        return response()->json($data);
    }
    /*
     * User Logout
     * @author Vishal Chaudhari
     * @access public
     * @param \App\User   $user
     * @request      POST
     * @return mixed
     * @category      Auth.
     *
    */

    public function logout(EnUsers $user)
    {
        $inputdata = $this->request->all();
        $validator = Validator::make($inputdata, [
            'token' => 'required'
        ]);
        if ($validator->fails())
        {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
        }
		else
		{
			$token = (string)$inputdata['token'];
            $data = EnAuth::validateToken( $token );
            if( isset($data['valid']) && $data['valid'] == 1)
            {

                $user = EnUsers::where('user_id', DB::raw('UUID_TO_BIN("'.$data['subject'].'")'))->select(DB::raw('BIN_TO_UUID(user_id) AS user_id'))->first();
                if (!$user) {
                    userlog(array('record_id' => $user->user_id, 'data' => $inputdata, 'action' => 'logout', 'message' => showmessage('700', array('{name}'),array('NA'))));
                    $data['data'] = null;
                    $data['message']['error'] = showmessage('700', array('{name}'),array('NA'));
                    $data['status'] = 'error';
                }    
                else
                {
                    // Verify the password and generate the token
                    userlog(array('record_id' => $user->user_id, 'data' => $inputdata, 'action' => 'logout', 'message' => showmessage('700', array('{name}'),array($user->user_id))));
                    $data = array();
                    $data['data']= '';
                    $data['message']['error'] = showmessage('700', array('{name}'),array($user->user_id));
                    $data['status'] = 'success';
                }   
            }
        }	
        return response()->json($data);
    }
  /*
     * Check for VAlid Username To Forgot  password.
     * @author Namrata Thakur
     * @access public
     * @param \App\User   $user
     * @request      POST
     * @return mixed
     * @category      User
     *
    */

    public function checkvaliduser(Request $request)
    {
        $inputdata = $request->all();
        $validator = Validator::make($inputdata, [
            'username' => 'required|email',
        ]);
        if ($validator->fails())
        {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        }
        else
        {
            // Find the user by username
            $user = EnUsers::where('username', $request->input('username'))->select('username', 'allowed_ip', 'password', 'status', DB::raw('BIN_TO_UUID(user_id) AS user_id'), DB::raw('BIN_TO_UUID(parent_id) AS parent_id'), DB::raw('user_id AS user_id_bin'))->first();
            if (!$user) {
                $data['data'] = NULL;
                $data['message']['error'] = showmessage('127');
                $data['status'] = 'error';               
            }    
            else
            {
                $inputdata['email'] = $request->input('username');
                $inputdata['user_id'] = $user['user_id'];
                $inputdata['token'] = $this->jwttoken($user);
                $inputdata['ip'] = $request->ip();
                $inputdata['date'] = date('Y-m-d H:i:s');
                
                $currentDate = strtotime(date('Y-m-d H:i:s'));
                $futureDate = $currentDate + (60*30); // Expire time will be 30 Minutes
                $inputdata['expiry_date'] = date("Y-m-d H:i:s", $futureDate);                
                $tokenForgotpwd = EnTokenForgotpwd::create($inputdata); 
                $link = "http://10.99.99.235:5200/#/site/reset/".$inputdata['token'];

                if(!empty($tokenForgotpwd['id']))
                {
                    $data['data'] = NULL;
                    $data['message']['success'] =   showmessage('126', array('{name}'),array($link));
                    $data['status'] = 'success';
                    //Add into UserActivityLog
                    userlog(array('record_id' => $tokenForgotpwd->id_text, 'data' => $inputdata, 'action' => 'added', 'message' => showmessage('126')));
                }
                else
                {
                    $data['data'] = NULL;
                    $data['message']['error'] = showmessage('129');
                    $data['status'] = 'error';
                }                 
            }
        }   
        return response()->json($data);
    }  
     /*
     * Token Valid / InValid 
     * @author Namrata Thakur
     * @access public
     * @param \App\User   $user
     * @request      POST
     * @return mixed
     * @category      Auth.
     *
    */

    public function isValidToken(Request $request)
    {
        $inputdata = $this->request->all();
        //$inputdata['token'] = $token;
        $validator = Validator::make($inputdata, [
            'token' => 'required'
        ]);
        if ($validator->fails())
        {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
        }
        else
        {
            $token = (string)$inputdata['token'];            
            $data = (array)EnAuth::validateToken( $token, 1 );
            //$data['op'] = $data;
            //print_r($data); exit;
            //if( isset($data['status']) && $data['status'] == "error" &&  $data['data'] == "expired")
            if( isset($data['valid']) && $data['valid'] == 1)
            {
                $data['data'] = NULL;
                $data['message']['error'] = showmessage('131');//131
                $data['status'] = 'error';
            }
            else
            {

               // $user = EnUsers::where('user_id', $data['subject'])->first();
                $user_id_bin = DB::raw('UUID_TO_BIN("'.$data['subject'].'")');
                $user = EnUsers::select( 'allowed_ip', 'password', DB::raw('BIN_TO_UUID(user_id) AS user_id'), DB::raw('BIN_TO_UUID(parent_id) AS parent_id'))
                    ->where('user_id', $user_id_bin)
                    ->first();

                if (!$user) {
                    $data['data'] = NULL;
                    $data['message']['error'] = showmessage('109');//131
                    $data['status'] = 'error';
                }    
                else
                {
                    $data['data'] = $user;
                    $data['message']['success'] = showmessage('132');
                    $data['status'] = 'success';
                }
            }

            //$queries    = DB::getQueryLog();
            //$data['data']['query'] = $last_query = end($queries); 
        }   
        return response()->json($data);
    }
    /*
     * Reset User Password if in forgot password.
     * @author Namrata Thakur
     * @access public
     * @param \App\User   $user
     * @request      POST
     * @return mixed
     * @category      Auth.
     *
    */

    public function resetForgotpwd(Request $request)
    {
        $inputdata = $request->all();
        $validator = Validator::make($inputdata, [
            'user_id' => 'required|string|size:36',
            'parent_id' => 'required|string|size:36',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'max:50',
                'regex:/^(?=.*[a-z|A-Z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/']
        ]);        
        if ($validator->fails())
        {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        }
        else
        {
            $inputdata = $request->all();
            //DB::beginTransaction(); // begin transaction
            // update user
            // unset readonly fields
            $validateuseraccess = EnUsers::validateuseraccess($inputdata['user_id'], $inputdata['parent_id']);
            if ($validateuseraccess)
            {               
                $result = EnUsers::where('user_id', DB::raw('UUID_TO_BIN("'.$inputdata['user_id'].'")'))->first();
                if ($result)
                {
                    //$inputdata = _unset($inputdata, "username");
                    $inputdata['password'] = stringencrypt($inputdata['password']);
                    $inputdata['password_confirmation'] = stringencrypt($inputdata['password_confirmation']);

                    // update user details
                    $result = EnUsers::where('user_id', DB::raw('UUID_TO_BIN("'.$inputdata['user_id'].'")'))->first();
                    if ($result)
                    {
                        $result->update(array('password' => $inputdata['password']));
                        $result->save();
                    }
                   
                   userlog(array('record_id' => $inputdata['user_id'], 'data' => $inputdata, 'action' => 'update', 'message' => showmessage('106', array('{name}'), array('User Password'), true)));

                    $data['data']['insert_id'] = $inputdata['user_id'];
                    $data['message']['success'] = showmessage('130', array('{name}'), array('User Password'));
                    $data['status'] = 'success';
                }
                else
                {
                   // DB::rollBack();
                    $data['data'] = null;
                    $data['message']['error'] = showmessage('105', array('{name}'), array('User'));
                    $data['status'] = 'error';
                }
            }
            else
            {
                $data['data'] = null;
                $data['message']['error'] = showmessage('100');
                $data['status'] = 'error';
            }
        }   
        return response()->json($data);
    }
    public function whitelistipvalidate(Request $request)
    {
        $inputdata = $request->all();
        $validator = Validator::make($inputdata, [
            'token' => 'required'
        ]);        
        if ($validator->fails())
        {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        }
        else
        {   
            $wResult = EnTokenWhitelistip::where('token', $inputdata['token'])->select('ip', DB::raw('BIN_TO_UUID(user_id) AS user_id'))->first();
            $uResult = EnUsers::where('user_id', DB::raw('UUID_TO_BIN("'.$wResult['user_id'].'")'))->select('allowed_ip', DB::raw('BIN_TO_UUID(user_id) AS user_id'))->first();
            
            if (!empty($uResult) && !empty($wResult))
            {
                //$wResult->update(array("status" => 'n'));
               // $wResult->save();


                $curr_allowed_ip = json_to_array($uResult['allowed_ip']);

                $allowed_ip = array_merge($curr_allowed_ip, array(trim($wResult->ip)));
                $allowed_ip = array_unique(array_filter($allowed_ip));
                $allowed_ip = converttojson($allowed_ip, "array");

                $update_ip  = array("allowed_ip" => $allowed_ip);
                $user_id_bin = DB::raw('UUID_TO_BIN("'.$wResult->user_id.'")');

                $user = EnUsers::where('user_id', $user_id_bin)->first();
                $user->update($update_ip);
                $data['data'] = $update_ip;
                //$queries    = DB::getQueryLog();
                //$data['data']['query'] = $last_query = end($queries);

                $upResult = EnTokenWhitelistip::where('token', $inputdata['token'])
                        ->select('ip', DB::raw('BIN_TO_UUID(user_id) AS user_id'))
                        ->update(array("status" => 'n'));
                
                userlog(array('record_id' => $wResult->user_id, 'data' => $inputdata, 'action' => 'update', 'message' => showmessage('134', array('{name}'), array('Whitelist IP'), true)));
                
                $data['message']['success'] = showmessage('134', array('{name}'), array('IP'));
                $data['status'] = 'success';
            }
            else
            {
                $data['data'] = null;
                $data['message']['error'] = showmessage('125');
                $data['status'] = 'error';                

            }
            return response()->json($data);
        }
       
    }
    public function whitelistip_authenticate($user, $inputdata)
    {
        $data = array();
        $allowed_ip = array(); 
        if($user->allowed_ip == ""){
            $allowed_ip[] = $this->request->ip();                         
            $allowed_ip = converttojson($allowed_ip, "array");
            $update_ip  = array("allowed_ip" => $allowed_ip);
            $user->update($update_ip);
            $user->save();
        }
        else
        {
            $curr_allowed_ip = json_to_array($user->allowed_ip);
           $ipFlag = false;
            //$ipFlag = "";
            //check if IP Not exists Exists;
            if(!in_array(trim($this->request->ip()), $curr_allowed_ip))
            {
                $test = "ip";
                $curr_allowed_subnets = json_to_array($user->allowed_subnets);
                $subnetsDATA = (boolean)$this->cidrs_match( $this->request->ip(), $curr_allowed_subnets);
                if(!$subnetsDATA)
                {
                    $test = "subnet";
                    $ipFlag = true;
                }
            }
            if($ipFlag)
            {
               // $data['subnet'] = $subnetsDATA;//10.10.14.170/27 //First IP-10.10.14.160  Last IP - 10.10.14.191
                $inputdata['email'] = $this->request->input('username');
                $inputdata['user_id'] = DB::raw('UUID_TO_BIN("'.$user->user_id.'")');
                $inputdata['token'] = $this->jwttoken($user);
                $inputdata['ip'] = $this->request->ip();
                $inputdata['date'] = date('Y-m-d H:i:s');
                
                $currentDate = strtotime(date('Y-m-d H:i:s'));
                $futureDate = $currentDate + (60*30); // Expire time will be 30 Minutes
                $inputdata['expiry_date'] = date("Y-m-d H:i:s", $futureDate);                
                $tokenForgotpwd = EnTokenWhitelistip::create($inputdata); 
                //dd(DB::getQueryLog());
                $link = "http://10.99.99.235:5200/#/site/whitelistip/".$inputdata['token'];

                $data['data'] = null;
                $data['message']['error'] = showmessage('137', array('{name}'), array($link));
                $data['status'] = 'error';                
            }            
        }
        return $data;
    }
    public function cidrs_match( $ip, $cidrs, &$cidr_matched = null )
    {
        if (!is_array($cidrs))
        {
            $cidrs = array( $cidrs );

        }
        foreach ($cidrs as $cidr)
        {
            if($this->cidr_match($ip, $cidr))
            {
                $cidr_matched = $cidr;
                 return true;
            }
        }
        return false;
    }    
    public function cidr_match( $ip, $cidr )
    {

        list($subnet, $mask) = explode('/', $cidr);

        return(ip2long($ip) & ~ ((1<<(32-$mask))-1)) == (ip2long($subnet) >> (32-$mask)) << (32-$mask);

    }
} // Class End