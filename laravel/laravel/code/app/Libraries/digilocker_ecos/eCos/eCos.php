<?php
namespace App\Libraries\digilocker_ecos\eCos;
class eCos{
	
	private $api_auth_url = "http://iecos.enlightcloud.com:5000/v2.0/";
	//private $api_auth_url = "http://nsk-ecos.enlightcloud.com:8080/v1/AUTH_028794c62e284d9b95d4c4c769251ede/";

//http://nsk-ecos.enlightcloud.com:8080/v1/AUTH_028794c62e284d9b95d4c4c769251ede/PartnerDocuments/1611168297971_Information_abt_CGTMSE_under_Section_4.docx
	

	private $tenantName = "ESDS-704";
	private $username = "ESDS-704";
	private $password = "esds704";

	/*private $tenantName = "nigs-dev";
	private $username = "nigs-dev";
	private $password = "NPVC9z1LZwhYI70t";*/
	
	
	//"tenantName": "ESDS-704",
    //"username": "ESDS-704",
    //"password": "esds704",
    //"Retry": 2,
    //"ContainerName": "SalesCRM",
    //PartnerDocumentss

	public $token;
	//public $tenant_id;
	public $api_public_url;
	
	public function __construct(){
	   	
		/*$this->tenantName = $tenantName;
		$this->username = $username;
		$this->password = $password;*/
    }
	
	// Obtain Token [ For initial login/authentication ] 
	public function getToken(){
		
		$api_url = $this->api_auth_url . 'tokens';

		$params = array();
		$params['auth']['tenantName'] = $this->tenantName;
		$params['auth']['passwordCredentials']['username'] = $this->username;
		$params['auth']['passwordCredentials']['password'] = $this->password;
		
		$res = $this->apiCall($api_url, NULL, $params, "POST");
		
		$auth_data = json_decode($res, TRUE);
		
		$this->token = $auth_data['access']['token']['id'];
		//$this->tenant_id = $auth_data['access']['token']['tenant']['id'];
		$this->api_public_url = $auth_data['access']['serviceCatalog'][0]['endpoints'][0]['publicURL'];
        
        return $res;	
	}
	
	// Create Container
	public function createContainer($container_name){
		
		//$api_url = $this->api_public_url . 'AUTH_' . $this->tenant_id . '/' . $container_name;
		$api_url = $this->api_public_url . '/' . $container_name;
       
		$res = $this->apiCall($api_url, $this->token, NULL, "PUT");
			
		//$this->token = $res['access']['token']['id'];
        
        return $res;
	}
	
	// Make Container Public/Private
	public function makeContainerPublicPrivate($container_name, $role = "public"){
		
		// Ref : https://ask.openstack.org/en/question/80609/how-to-make-a-container-public-in-swift-rest-api/
		// https://www.swiftstack.com/docs/cookbooks/swift_usage/container_acl.html
		
		//$api_url = $this->api_public_url . 'AUTH_' . $this->tenant_id . '/' . $container_name;
		$api_url = $this->api_public_url . '/' . $container_name;
		
		if($role == "public")
		{
			$headers[] = "X-Container-Read: .r:*";	// Public
		}
		elseif($role == "private")
		{
			$headers[] = "X-Container-Read: ''";	// Private	
		}
       
		$res = $this->apiCall($api_url, $this->token, NULL, "POST", $headers);
			
		//$this->token = $res['access']['token']['id'];
        
        return $res;
	}
	
	// Create Folder in Container
	public function createFolder($container_name, $folder_name){
		
		//$api_url = $this->api_public_url . 'AUTH_' . $this->tenant_id . '/' . $container_name . '/' . $folder_name;
		$api_url = $this->api_public_url . '/' . $container_name . '/' . $folder_name;
		
		$headers[] = "Content-Length: 0";
       
		$res = $this->apiCall($api_url, $this->token, NULL, "PUT", $headers);
			
		//$this->token = $res['access']['token']['id'];
        
        return $res;
	}
	
	// Get Container List
	public function getContainerList(){
		
		//$api_url = $this->api_public_url . 'AUTH_' . $this->tenant_id . '?format=json';
		$api_url = $this->api_public_url . '/' . '?format=json';
       
		$res = $this->apiCall($api_url, $this->token);
			
		//$this->token = $res['access']['token']['id'];
        
        return $res;
	}
	
	// Get Objects List in Container
	public function getObjectList($container_name){
		
		//$api_url = $this->api_public_url . 'AUTH_' . $this->tenant_id . '?format=json';
		$api_url = $this->api_public_url . '/' . $container_name . '/' . '?format=json';
       
		$res = $this->apiCall($api_url, $this->token);
			
		//$this->token = $res['access']['token']['id'];
        
        return $res;
	}
	
	// Upload Object
	public function uploadObject($container_name, $folder_name, $file_path, $file_name){
		
		//$api_url = $this->api_public_url . 'AUTH_' . $this->tenant_id . '/' . $container_name . '/' . $folder_name . '/' . $file_name;
		$api_url = $this->api_public_url . '/' . $container_name . '/' . $file_name;
		
		//$api_url = $this->api_public_url . '/' . $container_name . '/' . $folder_name . '/' . $file_name;
		//$res = $this->apiCall($api_url, $this->token, $params, "PUT");
		
		// Ref : https://secretdeveloper.co.uk/post/ovh-cloud-storage-getting-uploading-objects
		// https://stackoverflow.com/questions/17600389/using-php-curl-to-store-object-with-open-stack-api-file-storage
		// https://evertpot.com/222/
		
		//$image = fopen($file_path . $file_name, "r");	// folder image path
		$image = fopen($file_path, "r");	// image base64 encoded string
		
		$url = $api_url;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			'X-Auth-Token: ' . $this->token
		));
		//curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($curl, CURLOPT_HEADER, true);
		//curl_setopt($curl, CURLINFO_HEADER_OUT, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($curl, CURLOPT_VERBOSE, true);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_PUT, true);
		//curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($curl, CURLOPT_INFILE, $image);
		//curl_setopt($curl, CURLOPT_INFILESIZE, filesize($file_path . $file_name));	// if folder image path
		$res = curl_exec($curl);
		$info = curl_getinfo($curl);
		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
		
		//echo 'Response was ' . $httpcode; die();
			
		//$this->token = $res['access']['token']['id'];
		
		$res = false;
		if($httpcode >= 200 && $httpcode < 300)
		{
			$res = true;	
		}
        
        return $res;
	}
	
	// Download Object
	public function downloadObject($container_name, $folder_name, $file_name){
		
		//$api_url = $this->api_public_url . 'AUTH_' . $this->tenant_id . '/' . $container_name . '/' . $folder_name . '/' . $file_name;
	//	$api_url = $this->api_public_url . '/' . $container_name . '/' . $folder_name . '/' . $file_name;
		$api_url = $this->api_public_url . '/' . $container_name . '/' . $file_name;
       
		$res = $this->apiCall($api_url, $this->token);
			
		//$this->token = $res['access']['token']['id'];
        
        return $res;
	}
	
	// Delete Object
	public function deleteObject($container_name, $folder_name, $file_name){
		
		// NOTE : You cant delete a container without deleting the objects inside them.If that's not the case more probable that there are multiple objects/containers with the same name.
		
		//$api_url = $this->api_public_url . 'AUTH_' . $this->tenant_id . '/' . $container_name . '/' . $folder_name;
		$api_url = $this->api_public_url . '/' . $container_name . '/' . $folder_name . '/' . $file_name;
       
		$res = $this->apiCall($api_url, $this->token, NULL, "DELETE");
			
		//$this->token = $res['access']['token']['id'];
        
        return $res;
	}
	
	private function apiCall($api_url, $token = NULL, $params = NULL, $request = NULL, $headers = array()){
		
		//$headers = array();
		
		if($token != NULL)
		{
			$headers[] = "X-Auth-Token: {$token}";
		}
		
		if($token == NULL)
		{
			//$headers[] = "Content-Type: application/json";
			$post_params = json_encode($params);
		}
		else
		{
			if($params != NULL)
			{
				$post_params = $params;
			}
		}
		
		$headers[] = "Content-Type: application/json";
		
		$ch = curl_init();
		curl_setopt($ch ,CURLOPT_URL, $api_url);
		//curl_setopt($ch, CURLOPT_PORT, "5000");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_VERBOSE, true);
		if($params !== NULL)
		{
			//curl_setopt($ch,CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_params);
		}
		if($request !== NULL)
		{
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request);
		}
		$response = curl_exec($ch);
	 $err = curl_error($ch);
		$info = curl_getinfo($ch);
		//print_array($info); 

		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		/*if ($err) {
		  echo "cURL Error #:" . $err;
		  die();
		} else {
		  return $response;
		}*/	
		$res = false;
		if($httpcode >= 200 && $httpcode < 300)
		{
			$res = $response;	
		}
        
  return $res;
	}
}
?>