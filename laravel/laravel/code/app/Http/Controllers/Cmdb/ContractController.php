<?php

namespace App\Http\Controllers\Cmdb;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Libraries\Maillib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use Illuminate\Http\Request;
use Redirect;
use View;

/**
 * Contract Controller class is implemented to do Contract  operations.
 * @author Kavita Daware
 * @package Contract
 */
class ContractController extends Controller
{
    /**
     * Contructor function to initiate the API service and Request data
     * @author Kavita Daware
     * @access public
     * @package Contract
     * @param \App\Services\ITAM\ItamService $itam
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function __construct(IamService $iam, ItamService $itam, Request $request)
    {
        $this->itam = $itam;
        $this->iam = $iam;
        $this->emlib = new Emlib;
        $this->request = $request;
        $this->request_params = $this->request->all();
    }
    /**
     * Contract Controller function is implemented to initiate a page to get list of Contract .
     * @author Kavita Daware
     * @access public
     * @package contract
     * @return string
     */

    public function contracts($id='')
    {
        $contract_id = $id;
        $topfilter = array('gridsearch' => true, 'jsfunction' => 'contractList()', 'gridadvsearch' => true);
        $data['contract_id'] = $contract_id;
        $data['emgridtop'] = $this->emlib->emgridtop($topfilter, '', array('contract_type', 'contract_status'));
        $data['pageTitle'] = trans('title.contract');
        $data['includeView'] = view("Cmdb/contracts", $data);
        return view('template', $data);
    }
    /**
     * This controller function is implemented to get list of Contract.
     * @author Kavita Daware
     * @access public
     * @package contract
     * @param int $limit, int $page Pagination Variables
     * @param string $searchkeyword
     * @return json
     */

    public function contractList()
    {
        try
        {

            $paging = array();
            $fromtime = $totime = '';
            $limit = _isset($this->request_params, 'limit', config('enconfig.def_limit_short'));
            $exporttype = _isset($this->request_params, 'exporttype');
            $page = _isset($this->request_params, 'page', config('enconfig.page'));
            $searchkeyword = _isset($this->request_params, 'searchkeyword');
			$active_contract_id   = _isset($this->request_params, 'active_contract_id');

            $form_params['advcontract_type_id'] = _isset($this->request_params, 'advcontract_type_id');
            $form_params['advcontract_status'] = _isset($this->request_params, 'advcontract_status');
            $is_error = false;
            $msg = '';
            $content = "";
            $limit_offset = limitoffset($limit, $page);

            $page = $limit_offset['page'];
            $limit = $limit_offset['limit'];
            $offset = $limit_offset['offset'];

            $form_params['limit'] = $paging['limit'] = $limit;
            $form_params['page'] = $paging['page'] = $page;
            $form_params['offset'] = $paging['offset'] = $offset;
            $form_params['searchkeyword'] = $searchkeyword;
            //$form_params['contract_id'] = $contract_id;

            $options = ['form_params' => $form_params];
           
            $contract__resp = $this->itam->getcontract($options);
           
            if ($contract__resp['is_error'])
            {
                $is_error = $contract__resp['is_error'];
                $msg = $contract__resp['msg'];
            }
            else
            {
                $is_error = false;
                $contracts = _isset(_isset($contract__resp, 'content'), 'records');
				if(!empty($contracts)){
				$contract_exp_ids = array();
				for($index = 0; $index < count($contracts); $index++){
					if(strtotime($contracts[$index]['to_date']) < strtotime(date("Y-m-d"))){
					
						$contracts[$index]['contract_status'] = 'expired';
						$parameters['contract_status'] = 'expired';
						$parameters['contract_id'] = $contracts[$index]['contract_id'];
						$this->itam->updatecontractstatus(array('form_params' => $parameters));
					}
					
				}
				
				}
				//echo "<pre>";print_r($contracts); die;
                $paging['total_rows'] = _isset(_isset($contract__resp, 'content'), 'totalrecords');
                $paging['showpagination'] = true;
                $paging['jsfunction'] = 'contractList()';

                $view = 'Cmdb/contractlist';
				if(isset($active_contract_id) && $active_contract_id != ""){
					$contract_id = $active_contract_id;
				}else{
					if(!empty($contracts)){
						$contract_id = isset($contracts[0]['contract_id']) ? $contracts[0]['contract_id'] : "";
					}else{
						$contract_id = "";
					}
				}
                
                
                
                $content = $this->emlib->emgrid($contracts, $view, $columns = array(), $paging);
            }

            $response["html"] = $content;
            $response["is_error"] = $is_error;
            $response["msg"] = $msg;
            $response['contract_id'] = $contract_id;
            echo json_encode($response);
        }
        catch (\Exception $e)
        {
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            // save_errlog("contractList", "This controller function is implemented to get list of Contract.", "", $response['msg']);
            echo json_encode($response);
        }
        catch (\Error $e)
        {
            $response["html"] = '';
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            //save_errlog("contractList", "This controller function is implemented to get list of Contract.", "", $response['msg']);
            echo json_encode($response);
        }
    }

    /**
     * This controller function is used to load contract  add form.
     * @author Kavita Daware
     * @access public
     * @package contract
     * @return string
     */
    public function contractadd(Request $request)
    {
        try
        {
			
            $contract_id = $request->contract_id;
            $data['contract_id'] = '';
            $user_id = showuserid();
            $primary_contract = $request->input('primary_contract');
            $form_params['limit'] = 0;
            $form_params['page'] = 0;
            $form_params['offset'] = 0;
            $form_params['searchkeyword'] = '';
            $form_params['contract_id'] = $contract_id;
            $form_params['user_id'] = $user_id;
            $form_params['primary_contract'] = $primary_contract;
            $options = ['form_params' => $form_params];
            $contracts_resp = $this->itam->getcontract($options);
			
            if ($contracts_resp['is_error'])
            {
                $parentcontract = array();
            }
            else
            {
                $parentcontract = _isset(_isset($contracts_resp, 'content'), 'records');
            }

            $data['contractdata'] = array();
            $data['parentcontract'] = $parentcontract;

            $data['contract_type_id'] = '';
            $options = ['form_params' => $form_params];
            $contracttypes_resp = $this->itam->getcontracttype($options);

            if ($contracttypes_resp['is_error'])
            {
                $contracttypes = array();
            }
            else
            {
                $contracttypes = _isset(_isset($contracttypes_resp, 'content'), 'records');
            }

            $data['contracttypedata'] = array();
            $data['contracttypes'] = $contracttypes;

            $data['vendor_id'] = '';
            $options = ['form_params' => $form_params];
            $vendors_resp = $this->itam->getvendors($options);

            if ($vendors_resp['is_error'])
            {
                $vendors = array();
            }
            else
            {
                $vendors = _isset(_isset($vendors_resp, 'content'), 'records');
            }

            $data['vendordata'] = array();
            $data['vendors'] = $vendors;

            $data['asset_id'] = '';
            
            /*$assets_resp = $this->itam->getallassets($options);

            if ($assets_resp['is_error'])
            {
                $assets = array();
            }
            else
            {
                $assets = _isset(_isset($assets_resp, 'content'), 'records');
            }*/
            $data['assets'] = array();//$assets;
            $data['formAction'] = "add";

            $assetsarr = array();
            $asset_chk = $request->asset_id;

            if (is_array($asset_chk))
            {
                foreach ($asset_chk as $val)
                {
                    $assetsarr[] = array("id" => $val);

                }
            }

            //echo "<pre>";print_r($data); die;
            $data['assets_json'] = json_encode($assetsarr);
            $html = enview("Cmdb/contractadd", $data);
            echo $html;
        }
        catch (\Exception $e)
        {
            //save_errlog('contractadd', 'show view to add new contract details', '', $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }
        catch (\Error $e)
        {
            // save_errlog('contractadd', 'show view to add new contract details', '', $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }
    }
    /**
     * This controller function is used to submit contract form.
     * @author Kavita Daware
     * @access public
     * @package contract
     * @return string
     */
    public function contractaddsubmit(Request $request)
    {
        try
        {
            $data = $this->itam->addcontract(array('form_params' => $request->all()));
            echo json_encode($data, true);
        }
        catch (\Exception $e)
        {
            //save_errlog('contractaddsubmit', 'This controller function is used to submit contract form', json_encode($request->all()), $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }
        catch (\Error $e)
        {
            //save_errlog('contractaddsubmit', 'This controller function is used to submit contract form', json_encode($request->all()), $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }
    }
    /**
     * This controller function is used to load contractedit form with existing data for selected contract
     * @author Kavita Daware
     * @access public
     * @package contract
     * @param \Illuminate\Http\Request $request
     * @param $contract_id contract Unique Id
     * @return string
     */
    public function contractedit(Request $request)
    {
        try
        {
            $contract_id = $request->id;
            //$contract_details_id = $request->contract_details_id;
            $input_req = array('contract_id' => $contract_id);
            $user_id = showuserid();
            $primary_contract = $request->input('primary_contract');
            $datacontract = $this->itam->editcontract(array('form_params' => $input_req));
            $data['contractdata'] = $datacontract['content'];
            $data['contract_id'] = $contract_id;
            //$data['contract_details_id'] = $contract_details_id;
            $limit_offset = limitoffset(0, 0);
            $form_params['limit'] = $limit_offset['limit'];
            $form_params['page'] = $limit_offset['page'];
            $form_params['offset'] = $limit_offset['offset'];
            $form_params['user_id'] = $user_id;
            $form_params['primary_contract'] = $primary_contract;
            $options = ['form_params' => $form_params];
            $contracts_resp = $this->itam->getcontract($options);

            if ($contracts_resp['is_error'])
            {
                $parentcontract = array();
            }
            else
            {
                $parentcontract = _isset(_isset($contracts_resp, 'content'), 'records');
            }
            $data['parentcontract'] = $parentcontract;
            $data['contract_id'] = $contract_id;
            $options = ['form_params' => $form_params];
            $contracttypes_resp = $this->itam->getcontracttype($options);

            if ($contracttypes_resp['is_error'])
            {
                $contracttypes = array();
            }
            else
            {
                $contracttypes = _isset(_isset($contracttypes_resp, 'content'), 'records');
            }

            $data['contracttypes'] = $contracttypes;
            $data['contract_type_id'] = 'contract_type_id';

            $options = ['form_params' => $form_params];
            $vendors_resp = $this->itam->getvendors($options);

            if ($vendors_resp['is_error'])
            {
                $vendors = array();
            }
            else
            {
                $vendors = _isset(_isset($vendors_resp, 'content'), 'records');
            }

            $data['vendors'] = $vendors;
            $data['vendor_id'] = '';

            $contractasset_idarr = isset($data['contractdata'][0]['asset_id']) & $data['contractdata'][0]['asset_id'] != NULL ? json_decode($data['contractdata'][0]['asset_id'],true) : array();
			if($contractasset_idarr != null){
				$options = ['form_params' => array('asset_ids' => $contractasset_idarr)];
				$assets_resp = $this->itam->getallassets($options);
				if ($assets_resp['is_error'])
				{
					$assets = array();
				}
				else
				{
					$assets = _isset(_isset($assets_resp, 'content'), 'records');
				}

				$data['assets'] = $assets;
			}else{
				$data['assets'] = array();
			}
            $data['asset_id'] = '';

            /* $assets = array();
            $assetChk = $request->assetChk;
            if(is_array($assetChk))
            {
            foreach($assetChk as $val)
            {
            $assets[] = array("id" => $val);
            }
            }*/
            //$data['assets_json'] = json_encode($assets);

            $data['formAction'] = "edit";
            $html = view("Cmdb/contractadd", $data);
            echo $html;
        }
        catch (\Exception $e)
        {
            //save_errlog('contractedit', 'This controller function is used to load contractedit form with existing data for selected contract', json_encode($request->all()), $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }
        catch (\Error $e)
        {
            //save_errlog('contractedit', 'This controller function is used to load contractedit form with existing data for selected contract', json_encode($request->all()), $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }
    }
    /**
     * This controller function is used to update contract data in database.
     * @author Kavita Daware
     * @access public
     * @package contracttype
     * @param UUID $contract_id contract Unique Id
     * @param string $contract_id contract
     * @return json
     */
    public function contracteditsubmit(Request $request)
    {
        // try
        // {
        $data = $this->itam->updatecontract(array('form_params' => $request->all()));
        echo json_encode($data, true);
        // }
        /* catch (\Exception $e)
    {
    //save_errlog('contracteditsubmit', 'This controller function is used to update contract data in database.', json_encode($request->all()), $e->getMessage());

    $response["html"] = "";
    $response["is_error"] = true;
    $response["msg"] = $e->getMessage();
    echo json_encode($response);
    }
    catch (\Error $e)
    {
    //save_errlog('contracteditsubmit', 'This controller function is used to update contract data in database', json_encode($request->all()), $e->getMessage());

    $response["html"] = "";
    $response["is_error"] = true;
    $response["msg"] = $e->getMessage();
    echo json_encode($response);
    }*/
    }
    /**
     * This controller function is used to delete contract  data from database.
     * @author Kavita Daware
     * @access public
     * @package contract
     * @param UUID $contract_id contract Unique Id
     * @return json
     */
    public function contractdelete(Request $request)
    {
        try
        {
            $data = $this->itam->deletecontract(array('form_params' => $request->all()));
            echo json_encode($data, true);
        }
        catch (\Exception $e)
        {
            //save_errlog('contractdelete', 'This controller function is used to delete contract  data from database.', json_encode($request->all()), $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }
        catch (\Error $e)
        {
            //save_errlog('contractdelete', 'This controller function is used to delete contract  data from database.', json_encode($request->all()), $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }
    }
    /**
     * This controller function is used to display contract details from database.
     * @author Kavita Daware
     * @access public
     * @package contract
     * @param UUID $contract_id contract Unique Id
     * @return json
     */
    public function contractdetails(Request $request)
    {
        try
        {
            $form_params['limit'] = 0;
            $form_params['page'] = 0;
            $form_params['offset'] = 0;
            $form_params['searchkeyword'] = '';
            $contract_id = $request->contract_id;
            //$contract_details_id = $request->contract_details_id;
            $input_req = array('contract_id' => $contract_id);

            $datacontract = $this->itam->editcontract_withoutpermission(array('form_params' => $input_req));
			
            $data['contractdata'] = $datacontract['content'];
			
            $data['contract_id'] = $contract_id;
            $contract_details_id = $datacontract['content'][0]['contract_details_id'];
		

            $form_params['contract_id'] = $contract_id;

            $associatechildcontract_resp = $this->itam->getassociatechildcontract(array('form_params' => $form_params));
				
            if ($associatechildcontract_resp['is_error'])
            {
                $associatechildcontracts = array();
            }
            else
            {
                $associatechildcontracts = _isset($associatechildcontract_resp, 'content');
            }

            $data['associatechildcontracts'] = $associatechildcontracts;
            $data['contract_id'] = '';

            //  $contract_details_id = $request->contract_details_id;
            $input_req = array('contract_id' => $contract_id);
            //$form_params['contract_details_id'] = $contract_details_id;
            
            $contractattachment_resp = $this->itam->contractattachment_withoutpermission(array('form_params' => $input_req));
			
            if ($associatechildcontract_resp['is_error'])
            {
                $contractattachment = array();
            }
            else
            {
                $contractattachment = _isset($contractattachment_resp, 'content');
            }
      
            $data['contractattachment'] = $contractattachment;
			$contractasset_idarr = isset($data['contractdata'][0]['asset_id']) & $data['contractdata'][0]['asset_id'] != NULL ? json_decode($data['contractdata'][0]['asset_id'],true) : array();
			if($contractasset_idarr != null){
				$options = ['form_params' => array('asset_ids' => $contractasset_idarr)];
						
				$assets_resp = $this->itam->getallassets($options);
		
			 
				if ($assets_resp['is_error'])
				{
					$assets = array();
				}
				else
				{
					$assets = _isset(_isset($assets_resp, 'content'), 'records');
				}

				$data['assets'] = $assets;
			}else{
				$data['assets'] = array();
			}
            $data['asset_id'] = '';

            //get the contract history data
            $historyoptions = ['form_params' => array('contract_id' => $contract_id)];
            $contracthistorylog_resp    = $this->itam->contracthistorylog($historyoptions);
            $data['contracthistorylog'] = isset($contracthistorylog_resp['content']) ? $contracthistorylog_resp['content'] : null;

            if (!empty($data['contracthistorylog']))
              {
                  foreach ($data['contracthistorylog'] as $key => $history)
                  {
                      $options_history  = ['form_params' => array('user_id' => $history['created_by'])];
                      $response_historyuser = $this->iam->getUsers($options_history);
                      $historyuser_data = _isset(_isset($response_historyuser, 'content'), 'records');
					  
					  if(!(is_array($historyuser_data) && count($historyuser_data) > 0)){
                        $historyuser_data    = array();
                        $historyuser_data[0] = array();
                      }
                      $data['contracthistorylog'][$key]['created_by_name'] = $historyuser_data[0];
                  }
              }

            $data['pageTitle'] = trans('title.contractdetails');
            // print_r( $data);

            $contents = enview("Cmdb/contractdetails", $data);
            // return view('template', $data);
            $response["html"] = $contents;
            $response["is_error"] = $is_error = "";
            $response["msg"] = $msg = "";
            return json_encode($response);
        }
        catch (\Exception $e)
        {
            //save_errlog('contractdetails', 'This controller function is used to display contract details from database.', json_encode($request->all()), $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }
        catch (\Error $e)
        {
            //save_errlog('contractdetails', 'This controller function is used to display contract details from database.', json_encode($request->all()), $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }

    }
    /**
     * This controller function is used to load renew contract form.
     * @author Kavita Daware
     * @access public
     * @package contract
     * @param UUID $contract_id contract Unique Id
     * @return json
     */
    public function contractrenew(Request $request)
    {
        try
        {
            $contract_id = $request->id;
            //$contract_details_id = $request->contract_details_id;
            $input_req = array('contract_id' => $contract_id);
            $user_id = showuserid();
            // $primary_contract = $request->input('primary_contract');
            $datacontract = $this->itam->editcontract(array('form_params' => $input_req));
            $data['contractdata'] = $datacontract['content'];
		
            $data['contract_id'] = $contract_id;
            $data['user_id'] = $user_id;
            //$data['primary_contract']=$primary_contract;
            //$data['contract_details_id'] = $contract_details_id;
            $limit_offset = limitoffset(0, 0);
            $form_params['user_id'] = $user_id;
            //$form_params['primary_contract'] = $primary_contract;
            $form_params['limit'] = $limit_offset['limit'];
            $form_params['page'] = $limit_offset['page'];
            $form_params['offset'] = $limit_offset['offset'];
            $options = ['form_params' => $form_params];
            $contracts_resp = $this->itam->getcontract($options);

            if ($contracts_resp['is_error'])
            {
                $parentcontract = array();
            }
            else
            {
                $parentcontract = _isset(_isset($contracts_resp, 'content'), 'records');
            }
            $data['parentcontract'] = $parentcontract;
            $data['contract_id'] = $contract_id;
            $options = ['form_params' => $form_params];
            $contracttypes_resp = $this->itam->getcontracttype($options);

            if ($contracttypes_resp['is_error'])
            {
                $contracttypes = array();
            }
            else
            {
                $contracttypes = _isset(_isset($contracttypes_resp, 'content'), 'records');
            }

            $data['contracttypes'] = $contracttypes;
            $data['contract_type_id'] = 'contract_type_id';

            $options = ['form_params' => $form_params];
            $vendors_resp = $this->itam->getvendors($options);

            if ($vendors_resp['is_error'])
            {
                $vendors = array();
            }
            else
            {
                $vendors = _isset(_isset($vendors_resp, 'content'), 'records');
            }

            $data['asset_id'] = '';
            $contractasset_idarr = isset($data['contractdata'][0]['asset_id']) & $data['contractdata'][0]['asset_id'] != NULL ? json_decode($data['contractdata'][0]['asset_id'],true) : array();
			if($contractasset_idarr != null){
				$options = ['form_params' => array('asset_ids' => $contractasset_idarr)];
				$assets_resp = $this->itam->getallassets($options);
				

				if ($assets_resp['is_error'])
				{
					$assets = array();
				}
				else
				{
					$assets = _isset(_isset($assets_resp, 'content'), 'records');
				}

				$data['assets'] = $assets;
			}else{
				$data['assets'] = array();
			}

            $assets = array();
            $assetChk = $request->assetChk;
            if (is_array($assetChk))
            {
                foreach ($assetChk as $val)
                {
                    $assets[] = array("id" => $val);
                }
            }
            $data['assets_json'] = json_encode($assets);

            $data['vendors'] = $vendors;
            $data['vendor_id'] = '';

            $data['formAction'] = "renew";
            // print_r($data);
            $html = view("Cmdb/contractadd", $data);
            echo $html;
        }
        catch (\Exception $e)
        {
            //save_errlog('contractrenew', 'This controller function is used to load renew contract form.', json_encode($request->all()), $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }
        catch (\Error $e)
        {
            //save_errlog('contractrenew', 'This controller function is used to load renew contract form.', json_encode($request->all()), $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }
    }
    /**
     * This controller function is used to submit renew contract form.
     * @author Kavita Daware
     * @access public
     * @package contract
     * @param UUID $contract_id contract Unique Id
     * @return json
     */
    public function contractrenewsubmit(Request $request)
    {
        try
        {
            $data = $this->itam->addcontractrenewsubmit(array('form_params' => $request->all()));
            echo json_encode($data, true);
        }
        catch (\Exception $e)
        {
            //save_errlog('contractrenewsubmit', 'This controller function is used to submit renew contract form.', json_encode($request->all()), $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }
        catch (\Error $e)
        {
            //save_errlog('contractrenewsubmit', 'This controller function is used to submit renew contract form.', json_encode($request->all()), $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }
    }
    /**
     * This controller function is used to display records of child contracts.
     * @author Kavita Daware
     * @access public
     * @package contract
     * @param UUID $contract_id contract Unique Id
     * @return json
     */
    public function childcontract(Request $request)
    {
        try
        {
            $contract_id = $request->contract_id;

            $data['contract_id'] = '';

            $content = "";
            $form_params['limit'] = 0;
            $form_params['page'] = 0;
            $form_params['offset'] = 0;
            $form_params['searchkeyword'] = '';
            $form_params['contract_id'] = $contract_id;

            $options = ['form_params' => $form_params];
            $contracts_resp = $this->itam->getchildcontract($options);
            //print_r($contracts_resp);
            if ($contracts_resp['is_error'])
            {
                $childcontracts = array();
            }
            else
            {
                $childcontracts = _isset($contracts_resp, 'content');
            }

            $contract_id = _isset($this->request_params, 'contract_id');
            $data['contract_id'] = '';
            // $childcontracts = array();
            $data['childcontracts'] = $childcontracts;
            $contents = enview("Cmdb/childcontract", $data);
            $response["html"] = $contents;
            $response["is_error"] = $is_error = "";
            $response["msg"] = $msg = "";
            return json_encode($response);
        }
        catch (\Exception $e)
        {
            save_errlog('childcontract', 'This controller function is used to display records of child contracts.', json_encode($request->all()), $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }
        catch (\Error $e)
        {
            //save_errlog('childcontract', 'This controller function is used to display records of child contracts.', json_encode($request->all()), $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }
    }
    /**
     * This controller function is used to display renew details contracts.
     * @author Kavita Daware
     * @access public
     * @package contract
     * @param UUID $contract_id contract Unique Id
     * @return json
     */
    public function renewdetails(Request $request)
    {
        try
        {
            $contract_id = $request->contract_id;
            $primary_contract = $request->primary_contract;
            $data['contract_id'] = '';

            $content = "";
            $form_params['limit'] = 0;
            $form_params['page'] = 0;
            $form_params['offset'] = 0;
            $form_params['searchkeyword'] = '';
            $form_params['contract_id'] = $contract_id;
            $form_params['primary_contract'] = $primary_contract;
            $options = ['form_params' => $form_params];
            $renewdetails_resp = $this->itam->getrenewdetails($options);
            //print_r($contracts_resp);
            if ($renewdetails_resp['is_error'])
            {
                $renewdetails = array();
            }
            else
            {
                $renewdetails = _isset($renewdetails_resp, 'content');
            }

            $contract_id = _isset($this->request_params, 'contract_id');
            $data['contract_id'] = '';
            $data['renewdetails'] = $renewdetails;
            $contents = enview("Cmdb/renewcontractdetails", $data);
            $response["html"] = $contents;
            $response["is_error"] = $is_error = "";
            $response["msg"] = $msg = "";
            return json_encode($response);
        }
        catch (\Exception $e)
        {
            save_errlog('renewdetails', 'This controller function is used to display renew details contracts.', json_encode($request->all()), $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }
        catch (\Error $e)
        {
            //save_errlog('renewdetails', 'This controller function is used to display renew details contracts.', json_encode($request->all()), $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }

    }
    /**
     * This controller function is used to update asscociate contract details.
     * @author Kavita Daware
     * @access public
     * @package contract
     * @param UUID $contract_id contract Unique Id
     * @return json
     */
    public function contractupdateassociatechild(Request $request)
    {
        try
        {
            $data = $this->itam->contractupdateassociatechild(array('form_params' => $request->all()));
            echo json_encode($data, true);
        }
        catch (\Exception $e)
        {
            //save_errlog('contractupdateassociatechild', 'This controller function is used to update asscociate contract details.', json_encode($request->all()), $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }
        catch (\Error $e)
        {
            //save_errlog('contractupdateassociatechild', 'This controller function is used to update asscociate contract details.', json_encode($request->all()), $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }
    }
    /**
     * This controller function is used to upload contract attachments.
     * @author Kavita Daware
     * @access public
     * @package contract
     * @param UUID $contract_id contract Unique Id
     * @return json
     */
    public function attachfile(Request $request)
    {
        try
        {
            $showuserid = showuserid();
            //$showuserid = '7eb60499-089d-11e9-a0f6-0242ac110003';
            $contract_id = $request->contract_id;
            $contract_details_id = $request->contract_details_id;
           // echo "<pre>"; print_r($_FILES); die;
            if (isset($_FILES['attachments']))
            {
                foreach ($_FILES['attachments']['tmp_name'] as $key => $tmp_name)
                {
                    //get file extension
                    $file_ext = 'jpeg';
                    $name1    = $_FILES["attachments"]["name"];
                    $arr      = explode('.',$name1[$key]);
                    if(count($arr) > 1){
                      $file_ext = $arr[1];
                    }

                    $attachments_content = base64_encode(file_get_contents($_FILES['attachments']['tmp_name'][$key]));
                    $form_params['user_id'] = $showuserid;
                    $form_params['contract_id'] = $contract_id;
                    $form_params['contract_details_id'] = $contract_details_id;
                    $form_params['attachments'][$key] = $attachments_content;
                    $form_params['attachments_name'][$key] = $_FILES['attachments']['name'][$key];
                    $form_params['attachment_ext'][$key]  = $file_ext;
                    $form_params['size'][$key] = $_FILES['attachments']['size'][$key];
                    $options = ['form_params' => $form_params];
                    

                }
                $data = $this->itam->attachfile($options);
            }
		
            if ($data['is_error'])
            {
                return Redirect::to('/contract')
                    ->withErrors([
                        'notupload' => showerrormsg($data['msg']),
                    ]);
            }
            else
            {
                return Redirect::to('/contract')
                ->with('upload_success' , showerrormsg($data['msg']));
            }
            // echo json_encode($data, true);

        }
        catch (\Exception $e)
        {
            //save_errlog('attachfile', 'This controller function is used to upload contract attachments.', json_encode($request->all()), $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }
        catch (\Error $e)
        {
            //save_errlog('attachfile', 'This controller function is used to upload contract attachments.', json_encode($request->all()), $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }
    }
    /**
     * This controller function is used to display contract attachments.
     * @author Kavita Daware
     * @access public
     * @package contract
     * @param UUID $contract_id contract Unique Id
     * @return json
     */
    public function showattachment(Request $request)
    {
        try
        {
            $data = $this->itam->getattachfile();
            header("Content-type: image/jpeg");
            echo base64_decode($data['content']);
        }
        catch (\Exception $e)
        {
            //save_errlog('showattachment', 'This controller function is used to display contract attachments.', json_encode($request->all()), $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }
        catch (\Error $e)
        {
            //save_errlog('showattachment', 'This controller function is used to display contract attachments.', json_encode($request->all()), $e->getMessage());

            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getMessage();
            echo json_encode($response);
        }
    }
    /**
     * This Function is used to delete associated asset in contract details page
     * @author Kavita Daware
     * @access public
     * @package contract
     * @param UUID $contract_details_id contract Unique Id
     * @return json
     */
    public function associatedassetremove(Request $request)
    {
        // try
        //{
        $data = $this->itam->assetremove(array('form_params' => $request->all()));
        echo json_encode($data, true);
        // }
        /*  catch (\Exception $e)
    {
    //save_errlog('contractupdateassociatechild', 'This controller function is used to update asscociate contract details.', json_encode($request->all()), $e->getMessage());

    $response["html"] = "";
    $response["is_error"] = true;
    $response["msg"] = $e->getMessage();
    echo json_encode($response);
    }
    catch (\Error $e)
    {
    //save_errlog('contractupdateassociatechild', 'This controller function is used to update asscociate contract details.', json_encode($request->all()), $e->getMessage());

    $response["html"] = "";
    $response["is_error"] = true;
    $response["msg"] = $e->getMessage();
    echo json_encode($response);
    }*/
    }

     /**
     * This Function is used to send email
     * @author Kavita Daware
     * @access public
     * @package contract
     * @param $request
     * @return json
     */

    public function sendmail(Request $request)
    {
        $inputdata = $this->request->all();
        $phpmailer = new Maillib();
        $to_emails = $inputdata['mail_notification_to'];
        $subject = $inputdata['mail_notification_subject'];
        $email_body = $inputdata['comment'];
        $mailresponse = $phpmailer->mailsent($to_emails, $subject, $email_body);
        /* if($mailresponse){
        echo 'Success';
        }else{
        echo 'Failed';
        }*/
        $response["html"] = $mailresponse;
        $response["is_error"] = $is_error = "";
        $response["msg"] = $msg = "Mail Send Successfully";
        echo json_encode($response);
    }

     /**
     * This Function is used to contract action
     * @author Kavita Daware
     * @access public
     * @package contract
     * @param $request
     * @return json
     */

    public function contractaction(Request $request)
    {
        try
        {
            $inputdata = $request->all();
            $postData["user_id"] = _isset($inputdata, 'user_id', "");
            $postData["contract_id"] = _isset($inputdata, 'contract_id', "");
            $postData["action"] = _isset($inputdata, 'action', "");
            $postData["comment"] = _isset($inputdata, 'comment', "");
            $postData["notify_to_id"] = _isset($inputdata, 'notify_to_id', "");
            // For Notify 
            $postData["mail_notification_to"] = _isset($inputdata, 'mail_notification_to', "");
            $postData["mail_notification_subject"] = _isset($inputdata, 'mail_notification_subject', "");
            $postData["mail_notification"] = _isset($inputdata, 'mail_notification', "");
            $postData["notify_to_id"] = _isset($inputdata, 'notify_to_id', "");

            $phpmailer = new Maillib();
            $to_emails = $inputdata['mail_notification_to'];
            $subject = $inputdata['mail_notification_subject'];
            $email_body = $inputdata['comment'];
            $mailresponse = $phpmailer->mailsent($to_emails, $subject, $email_body);

            if($postData["action"] == 'notifyowner') $data = $this->itam->contractaction_notifyowner(array('form_params' => $postData));
            else $data = $this->itam->contractaction_notifyvendor(array('form_params' => $postData));
            //$data = $this->itam->contractaction(array('form_params' => $postData));
        }
        catch (\Exception $e)
        {
            $data["content"] = "";
            $data["is_error"] = "";
            $data["msg"] = $e->getmessage();
            $data["http_code"] = "";

            save_errlog("contractaction", "This controller function is implemented to Contract form actions.", $this->request_params, $e->getmessage());
        }
        catch (\Error $e)
        {

            $data["content"] = "";
            $data["is_error"] = "";
            $data["msg"] = $e->getmessage();
            $data["http_code"] = "";

            save_errlog("contractaction", "This controller function is implemented to Contract form actions.", $this->request_params, $e->getmessage());
        }
        finally
        {
            echo json_encode($data, true);
        }
    }

     /**
     * This Function is used to delete contract attachement
     * @author Kavita Daware
     * @access public
     * @package contract
     * @param $request
     * @return json
     */

     public function deletecontractattachment(Request $request)
    {
        $inputdata             = $request->all();
        $postData["attach_id"] = _isset($inputdata, 'attach_id', "");
        $postData["contract_id"]  = _isset($inputdata, 'contract_id', "");
        $data = $this->itam->deletecontractattachment(array('form_params' => $postData));
        echo json_encode($data, true);
    }

     /**
     * This Function is used to download the contract attachements
     * @author Snehal C
     * @access public
     * @package contract
     */


    public function downloadcontractattachment(){
        app('App\Http\Controllers\Cmdb\PoController')->downloadattachment_pr();
    }

    /**
     * This Function is used to displays the list of assets on contract add form
     * @author Snehal C
     * @access public
     * @package contract
     * @param $request
     */
    function contractassetlist(Request $request)
    { 
        try
        {  
            // /print_r(config('enconfig'));
            $paging = array();
            $fromtime = $totime = '';
            $limit = _isset($this->request_params, 'limit', config('enconfig.def_limit'));
            $page = _isset($this->request_params, 'page', config('enconfig.page'));
            $searchkeyword = _isset($this->request_params, 'searchkeyword');
		
            //$po_id = _isset($this->request_params, 'po_id');
           
            $is_error = false;
            $msg = '';
            $content = "";
            $limit_offset = limitoffset($limit, $page);
            $page = $limit_offset['page'];
            $limit = $limit_offset['limit'];
            $offset = $limit_offset['offset'];
           // $form_params['ci_templ_id'] = $request->input('ci_templ_id');
           // $form_params['po_id'] = $po_id;

            $form_params['limit'] = $paging['limit'] = $limit;
            $form_params['searchkeyword'] = $searchkeyword;
            $form_params['page'] = $paging['page'] = $page;
            $form_params['offset'] = $paging['offset'] = $offset;
            $options = [
                'form_params' => $form_params];
            
			
            $assetlist = $this->itam->getallassets($options);

            if ($assetlist['is_error'])
            {
                $is_error = $assetlist['is_error'];
                $msg = $assetlist['msg'];
            }
            else
            {  

                $assets = _isset(_isset($assetlist, 'content'), 'records');
                $paging['total_rows'] = _isset(_isset($assetlist, 'content'), 'totalrecords');
                $paging['showpagination'] = true;
                $paging['jsfunction'] = 'contractassetlist()';
                $view = 'Cmdb/contractassetlist';
               //echo "<pre>";print_r($assets);die;
                $show_fields = array();         
                $columns = $show_fields;
                $content = $this->emlib->emgrid($assets, $view, $columns, $paging);
            }
            $response["html"] = $content;
            $response["is_error"] = $is_error;
            $response["msg"] = $msg;
           // echo json_encode($response);
        }
        catch (\Exception $e)
        {
            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
           
            save_errlog("assetlist","This controller function is implemented to get Assetlist.",$this->request_params,$e->getmessage());  
        }
        catch (\Error $e)
        {
            $response["html"] = "";
            $response["is_error"] = true;
            $response["msg"] = $e->getmessage();
            save_errlog("assetlist","This controller function is implemented to get Assetlist.",$this->request_params,$e->getmessage());  
        }
        finally
        {
            echo json_encode($response);
        } 
    }
}
