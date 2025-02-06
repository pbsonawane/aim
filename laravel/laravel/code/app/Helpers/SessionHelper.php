<?php
use App\Models\EnSessions;
use App\Models\EnSessionTokens;
use App\Models\EnSessionData;

function saveSession($data)
{
	Session::forget('username');
	Session::forget('token');
    Session::forget('displayname');
    Session::forget('user_id');
    Session::forget('userfullname');
    Session::forget('issuperadmin');
    Session::forget('role_id');
    Session::forget('accessrights');
    
    Session::flush();

	

	if (isset($data['user_id'])) 
	{
    	Session::put('user_id',$data['user_id']);
	}
    
    if (isset($data['token'])) 
    {
		Session::put('token',$data['token']);
    }
    if (isset($data['userfullname'])) 
	{
		Session::put('userfullname',$data['userfullname']);
	}
    if (isset($data['displayname'])) 
    {
    	Session::put('displayname',$data['displayname']);
    }
    if (isset($data['username'])) 
    {
    	Session::put('username',$data['username']);
    }
  
    if (isset($data['issuperadmin']) && $data['issuperadmin']) 
    {
    	Session::put('issuperadmin',$data['issuperadmin']);
    }
    if (isset($data['role_id'])) 
    {
    	Session::put('role_id',$data['role_id']);
    }
    if (isset($data['accessrights'])) 
    {
    	Session::put('accessrights',$data['accessrights']);
    }
    if (isset($data['locale'])) 
    {
    	Session::put('locale',$data['locale']);
    }
}

function saveSessToken($sesstoken)
{

	Session::forget('sesstoken');
	Session::put('sesstoken',$sesstoken);
}
function getSessionItem($key)
{
	if (Session::has($key)) {
	  return Session::get($key);
	}
}
function showname()
{

	return  getSessionItem('displayname');
}

function showuserid()
{
    return getSessionItem('user_id') ;
    
}
function showusername()
{
    return getSessionItem('username') ;
}
function showuserfullname()
{
    return getSessionItem('userfullname') ;
    
}

function getTokenFromSession()
{
	return getSessionItem('token') ;
}
function putSessionItem($key, $val)
{
	return Session::set($key, $val);
}
function deleteSession()
{
	Session::forget('username');
	Session::forget('token');
	Session::forget('displayname');
	Session::forget('accessrights');
	Session::forget('role_id');
	Session::forget('issuperadmin');
	Session::flush();	
}
function verifytoken($access_token, $domainkey)
{
	echo $access_token." ".$domainkey;
}

function saveAccessTokenToSession($access_token, $domainkey)
{
	Session::forget('access_token');
	Session::forget('domainkey');
	Session::forget('userfullname');
	Session::forget('token');
	Session::forget('displayname');
	Session::forget('accessrights');
	Session::forget('role_id');
	Session::forget('issuperadmin');
	Session::flush();
	Session::put('access_token',$access_token);
	Session::put('domainkey',$domainkey);
}
//set shared access
function setSharedAccess($userdetails)
{
	$request = request();
    $input = $request->all();
	$sesstoken = DB::raw('UUID()');
	$session = ["username" => $input['username'], 'token' => $sesstoken, 'url' => (string)$request->fullUrl(), 'method' => (string)$request->method(), 'ip' => (string)$request->ip(), 'agent' => (string)$request->header('user-agent')];
	$session_id = EnSessions::create($session);
	if($session_id->session_id)
	{
		$sessiondata = EnSessions::getSession($session_id->session_id, "session_id");
		if($sessiondata)
		{
			saveSessToken($sessiondata[0]->token);
			$sessiondetails = json_encode(['username' => $userdetails['username'], 'user_id' => $userdetails['user_id'], 'token' => $userdetails['token'], 'displayname' => $userdetails['displayname']]);
			$sessdata = ["token" => $sessiondata[0]->token, "sessiondetails" => $sessiondetails];
			saveSessionData($sessdata);
			$domaintokens = setDomainAccess($sessiondata[0]->token);
			if($domaintokens)
				return $domaintokens;
		}
	}
	return false;
}
function saveSessionData($sessdata)
{
	return EnSessionData::create($sessdata);
}
function setDomainAccess($sesstoken)
{
	$request = request();
	$tokendata['accesstoken'] = DB::raw('UUID()');;
	$tokendata['domainkey'] = 'itamkey';
	$tokendata['url'] = '';
	$tokendata['method'] = '';
	$tokendata['ip'] = '';
	$tokendata['agent'] = '';
	$tokendata['authtime'] = date("Y-m-d H:i:s");
	$tokendata['token'] = $sesstoken;
	$session_id = EnSessionTokens::create($tokendata);
	if($session_id)
	{
		return true;
	}
	else
		return false;
}
function processsesstokens($sesstokens)
{
	$domains = [];
	if($sesstokens && is_object($sesstokens))
	{
		foreach($sesstokens as $key => $val)
		{
			$data['token'] = $val->accesstoken;
			$data['time'] = time();
			$domains[$key]['encytoken'] = sharedTokenEncrypt(json_encode($data));
			$domains[$key]['domainkey'] = $val->domainkey;
			$domains[$key]['domainurl'] = 'http://10.10.99.2:6200/setaccess';
		}
	}
	return $domains;
}
function processtoken($access_token)
{
	$tokendetails = sharedTokenDecrypt($access_token);
	$tokendetails = json_to_array($tokendetails);
	$accesstoken = _isset($tokendetails, 'token');
	return $accesstoken;
}