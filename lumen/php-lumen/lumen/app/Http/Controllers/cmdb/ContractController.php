<?php
namespace App\Http\Controllers\cmdb;

use App\Http\Controllers\Controller;
use App\Models\EnContract;
use App\Models\EnContractDetails;
use App\Models\EnContractHistory;
use App\Models\EnContractAttachment;
use App\Models\EnAssets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class ContractController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        DB::connection()->enableQueryLog();
    }
    /*
     *This is controller funtion used for Contracts.

     * @author       Kavita Daware
     * @access       public
     * @param        URL : contract_id
     * @param_type   integer
     * @return       JSON
     * @tables       en_contract
     */
    public function contracts(Request $request,$contract_id = null)
    {
        try
        {
            $requset['contract_id'] = $contract_id;
            $validator = Validator::make($request->all(), [
                'contract_id' => 'nullable|allow_uuid|string|size:36',
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

                $inputdata['searchkeyword'] = trim(_isset($inputdata, 'searchkeyword'));
                $totalrecords = EnContract::getcontract($contract_id, $inputdata, true);
                $result = EnContract::getcontract($contract_id, $inputdata, false);
                $queries = DB::getQueryLog();
                $last_query = end($queries);
                $data['data']['query'] = $last_query;

                $data['data']['records'] = $result->isEmpty() ? null : $result;
                $data['data']['totalrecords'] = $totalrecords;

                if ($totalrecords < 1)
                {
                    $data['message']['error'] = showmessage('102', array('{name}'), array('Contract Type'));
                    $data['status'] = 'error';
                }
                else
                {
                    $data['message']['success'] = showmessage('101', array('{name}'), array('Contract Type'));
                    $data['status'] = 'success';
                }
                $data['status'] = 'success';
                return response()->json($data);
            }
        }
        catch (\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("contracts", "This is controller funtion used for Contracts.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("contracts", "This is controller funtion used for Contracts.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }

	 /*
     *This is controller funtion used for open the add form for contract with predefined data.

     * @author       Kavita Daware
     * @access       public
     * @param        $request
     * @param_type   array
     * @return       JSON
     * @tables       en_contract
     */
	 
    public function contractadd(Request $request)
    {
        try
        {

            $messages = [
			  'contract_name.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_contract_name')), true),
			  'contract_name.allow_alpha_numeric_space_dash_underscore_only' => showmessage('003', array('{name}'), array(trans('label.lbl_contract_name')), true),
              'contract_name.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_contract_name')), true),
              'vendor_id.required' => showmessage('123', array('{name}'), array(trans('label.lbl_vendor')), true),
			  'contract_type_id.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_contract_type')), true),
			  'contractid.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_contract_id')), true),
			  'contractid.allow_alphal_numeric_dash_underscore_only'       => showmessage('007', array('{name}'), array(trans('label.lbl_contract_id')),true),
			  'from_date.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_active_period_from')), true),
			  'from_date.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_active_period_from')),true),
			  'to_date.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_to')), true),
			  'to_date.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_to')),true),
			  'support.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_support')), true),
			  'support.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_support')),true),
			  'description.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_desc')), true),
			  'description.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_desc')),true),
			  'cost.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_maintenance_cost')), true),
			  'cost.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_maintenance_cost')),true),
			  'asset_id.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_asset')), true),
            ];
			
            $validator = Validator::make($request->all(), [
                'contract_name' => 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_contract, contract_name, '.$request->input('contract_name'),
                'vendor_id' => 'required',
                'contract_type_id' => 'required',
                'contractid' => 'required|allow_alphal_numeric_dash_underscore_only',
                //'renewed' => 'required' ,
                'from_date' => 'required|html_tags_not_allowed',
                'to_date' => 'required|html_tags_not_allowed',
                //'parent_contract'  =>'required',
                'support' => 'required|html_tags_not_allowed',
                'description' => 'required|html_tags_not_allowed',
                'cost' => 'required|html_tags_not_allowed',
                'asset_id' => 'required',
            ],$messages);
            if ($validator->fails())
            {
                $error = $validator->errors();
                $data['data'] = null;
                $data['message']['error'] = $error;
                $data['status'] = 'error';
            }
            else
            {
                $inputdata = $request->all();
                DB::beginTransaction();
                $parent = _isset($inputdata, 'parent_contract');
                if ($parent != '')
                {
                    $contract['parent_contract'] = DB::raw('UUID_TO_BIN("'.$inputdata['parent_contract'].'")');
                }
                $contract['contract_type_id'] = DB::raw('UUID_TO_BIN("'.$inputdata['contract_type_id'].'")');
                $contract['vendor_id'] = DB::raw('UUID_TO_BIN("'.$inputdata['vendor_id'].'")');
                $contract['primary_contract'] = DB::raw('UUID_TO_BIN(UUID())');
                $contract['renewed_to'] = DB::raw('UUID_TO_BIN(UUID())');
                $contract['user_id'] = DB::raw('UUID_TO_BIN("'.$inputdata['loggedinuserid'].'")');
                // print_r($contract['user_id']);exit;

                $contract['contractid'] = _isset($inputdata, 'contractid');
                $contract['contract_name'] = _isset($inputdata, 'contract_name');
                $contract['renewed'] = _isset($inputdata, 'renewed', 'n');
                $contract['from_date'] = _isset($inputdata, 'from_date');
                $contract['to_date'] = _isset($inputdata, 'to_date');
                $todayDate = date("Y-m-d");
                //$contract['to_date'] = date("Y-m-d");

                if ($contract['to_date'] < $todayDate)
                {
                    $contract['contract_status'] = _isset($inputdata, 'contract_status', 'expired');
                }
                else
                {
                    $contract['contract_status'] = _isset($inputdata, 'contract_status', 'active');
                }

                $contract['status'] = _isset($inputdata, 'status', 'y');
                //apilog('contract----'.json_encode($contract));
                //echo "<pre>"; print_r($contract); die;
                $contract_data = EnContract::create($contract);

                if ($contract_data->contract_id_text != '')
                {
                    // $data['query1'] ='success';
                    $contract_id = $contract_data->contract_id_text;
                    $contractdetails['contract_id'] = DB::raw('UUID_TO_BIN("'.$contract_id.'")');
                    $contractdetails['support'] = _isset($inputdata, 'support');
                    $contractdetails['description'] = _isset($inputdata, 'description');
                    $contractdetails['cost'] = _isset($inputdata, 'cost');
                    $asset = _isset($inputdata, 'asset_id');
                    $contractdetails['asset_id'] = json_encode($asset);
                    //print_r($contractdetails['asset_id']);exit;
                    //$contractdetails['asset_id'] = _isset($inputdata,'asset_id');

                    $contractdetails_data = EnContractDetails::create($contractdetails);

                    if ($contract_data->contract_id_text == '' || $contractdetails_data->contract_details_id_text == '')
                    {

                        DB::rollBack();
                        $data['data'] = null;
                        $data['message']['error'] = showmessage('103', array('{name}'), array('Contract'));
                        $data['status'] = 'error';
                    }
                    else
                    {

                        // add contract history when contract is added
                        $hist_details = $this->gethistorydesc('created',trans('label.lbl_contract'));
                        $this->contracthistoryadd(array('contract_id'=> $contract_id,'action' => 'created', 'details' =>  $hist_details, 'created_by' => DB::raw('UUID_TO_BIN("'.$request->input('loggedinuserid').'")')));

                        userlog(array('record_id' => $contract_data->contract_id_text, 'data' => $inputdata, 'action' => 'create', 'message' => showmessage('104', array('{name}'), array('Contract'), true)));
                        DB::commit();
                        $data['data']['insert_id'] = 1;
                        $data['message']['success'] = showmessage('104', array('{name}'), array('Contract'));
                        $data['status'] = 'success';
                    }
                }

            }

            return response()->json($data);
        }
        catch (\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("contractadd", "This is controller funtion used to add Contracts.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("contractadd", "This is controller funtion used to add Contracts.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }

    /* Provides a window to user to update the information.

     * @author       Kavita Daware
     * @access       public
     * @param        URL : contract_id
     * @param_type   Integer
     * @return       JSON
     * @tables       en_contract
     */
    public function contractedit(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
                'contract_id' => 'required|allow_uuid|string|size:36',
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
                $inputdata = $request->all();
                $result = EnContract::getcontract($request->input('contract_id'), $inputdata);

                $data['data'] = $result->isEmpty() ? null : $result;

                if ($data['data'])
                {
                    $data['message']['success'] = showmessage('102', array('{name}'), array('Contract'));
                    $data['status'] = 'success';
                }
                else
                {

                    $data['message']['error'] = showmessage('101', array('{name}'), array('Contract'));
                    $data['status'] = 'error';
                }
            }
            return response()->json($data);
        }
        catch (\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            //save_errlog("contractedit", "This is controller funtion used to add Contracts.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            //save_errlog("contractedit", "This is controller funtion used to add Contracts.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }

    /*
     * Updates the contract information, which is entered by user on Edit contract window.

     * @author       Kavita Daware
     * @access       public
     * @param        contract_id,parent_contract,vendor_id,contract_type_id,
    contractid,contract_name,renewed,from_date,to_date,contract_status,status
     * @param_type   POST array
     * @return       JSON
     * @tables       en_contract
     */
    public function contractupdate(Request $request)
    {
        try
        {
            $messages = [
		  'contract_name.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_contract_name')), true),
		  'contract_name.allow_alpha_numeric_space_dash_underscore_only' => showmessage('003', array('{name}'), array(trans('label.lbl_contract_name')), true),
          'contract_name.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_contract_name')), true),
          'vendor_id.required' => showmessage('123', array('{name}'), array(trans('label.lbl_vendor')), true),
		  'contract_type_id.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_contract_type')), true),
		  'contractid.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_contract_id')), true),
		  'contractid.allow_alphal_numeric_dash_underscore_only'       => showmessage('007', array('{name}'), array(trans('label.lbl_contract_id')),true),
		  'from_date.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_active_period_from')), true),
		  'from_date.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_active_period_from')),true),
		  'to_date.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_to')), true),
		  'to_date.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_to')),true),
		  'support.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_support')), true),
		  'support.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_support')),true),
		  'description.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_desc')), true),
		  'description.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_desc')),true),
		  'cost.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_maintenance_cost')), true),
		  'cost.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_maintenance_cost')),true),
		  'asset_id.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_asset')), true),
            ];
    		
    		$validator = Validator::make($request->all(), [
    			'contract_id' => 'required|allow_uuid|string|size:36',
                'contract_name' => 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_contract, contract_name, '.$request->input('contract_name').', contract_id,'.$request->input('contract_id'),
                'vendor_id' => 'required',
                'contract_type_id' => 'required',
                'contractid' => 'required|allow_alphal_numeric_dash_underscore_only',
                //'renewed' => 'required' ,
                'from_date' => 'required|html_tags_not_allowed',
                'to_date' => 'required|html_tags_not_allowed',
                //'parent_contract'  =>'required',
                'support' => 'required|html_tags_not_allowed',
                'description' => 'required|html_tags_not_allowed',
                'cost' => 'required|html_tags_not_allowed',
                'asset_id' => 'required',
            ],$messages);
			
			
            if ($validator->fails())
            {
                $error = $validator->errors();
                $data['data'] = $request->all();
                $data['message']['error'] = $error;
                $data['status'] = 'error';
            }
            else
            {
                $contract_details_id_uuid = $request->input('contract_details_id');

                $request['contract_details_id'] = DB::raw('UUID_TO_BIN("'.$request->input('contract_details_id').'")');

                $contract_id = $request->input('contract_id');

                $request['contract_id'] = DB::raw('UUID_TO_BIN("'.$contract_id.'")');

                $request['renewed_to'] = DB::raw('UUID_TO_BIN("'.$contract_id.'")');

                $contract_type_id = $request->input('contract_type_id');

                $request['contract_type_id'] = DB::raw('UUID_TO_BIN("'.$contract_type_id.'")');

                $vendor_id = $request->input('vendor_id');

                $request['vendor_id'] = DB::raw('UUID_TO_BIN("'.$vendor_id.'")');

                $parent_contract = $request->input('parent_contract');

                $request['parent_contract'] = DB::raw('UUID_TO_BIN("'.$parent_contract.'")');

                $request["asset_id"] = json_encode($request->input("asset_id"), true);

                $result = EnContract::where('contract_id', $request['contract_id'])->first();

                $result1 = EnContractDetails::where('contract_details_id', $request['contract_details_id'])->first();

                if ($result && $result1)
                {
                    $result->update($request->all());
                    $result->save();

                    $result1->update($request->all());
                    $result1->save();

                    // add contract history when contract is added
                    $hist_details = $this->gethistorydesc('updated',trans('label.lbl_contract'));
                    $this->contracthistoryadd(array('contract_id'=> $contract_id,'action' => 'updated', 'details' =>  $hist_details, 'created_by' => DB::raw('UUID_TO_BIN("'.$request->input('loggedinuserid').'")')));

                    $data['data'] = null;
                    $data['message']['success'] = showmessage('106', array('{name}'), array('Contract'));
                    $data['status'] = 'success';

                }
                else
                {
                    $data['data'] = null;
                    $data['message']['error'] = showmessage('105', array('{name}'), array('Contract'));
                    $data['status'] = 'error';
                }
            }
            return response()->json($data);
        }
        catch (\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            // save_errlog("contractupdate", "Updates the contract information, which is entered by user on Edit contract window.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            // save_errlog("contractupdate", "Updates the contract information, which is entered by user on Edit contract window.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }
    /* This is controller funtion used to delete the contract

     * @author      Kavita Daware
     * @access       public
     * @param        URL : contract_id
     * @param_type   integer
     * @return       JSON
     * @tables       en_contract
     */
    public function contractdelete(Request $request,$contract_id = null)
    {
        try
        {
            $request['contract_id'] = $contract_id;
            $messages = [
                'contract_id.required' => showmessage('000', array('{name}'), array('Contract Id'), true),
            ];

            $validator = Validator::make($request->all(), [
                'contract_id' => 'required|allow_uuid|string|size:36',
            ], $messages);
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
                $data = EnContract::checkforrelation($contract_id);
                //Add into UserActivityLog
                if ($data['data'])
                {

                    $hist_details = $this->gethistorydesc('deleted',trans('label.lbl_contract'));
                    $this->contracthistoryadd(array('contract_id'=> $contract_id,'action' => 'deleted', 'details' =>  $hist_details, 'created_by' => DB::raw('UUID_TO_BIN("'.$request->input('loggedinuserid').'")')));   

                    userlog(array('record_id' => $contract_id, 'data' => $request->all(), 'action' => 'deleted', 'message' => showmessage('118', array('{name}'), array('Contract '))));
                }
                return response()->json($data);
            }
        }
        catch (\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("contractdelete", "This is controller funtion used to delete the contract.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("contractdelete", "This is controller funtion used to delete the contract", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }
	 /*
     *This is controller funtion used for set as a child contract

     * @author       Kavita Daware
     * @access       public
	 * @param        $contract_id
     * @param        $request
     * @param_type   array
     * @return       JSON
     * @tables       en_contract
     */
    public function childcontract(Request $request,$contract_id = null)
    {

        try
        {
            $contract_id = $request->input('contract_id');
            $result = EnContract::childcontract($contract_id);
            if ($result)
            {

                $data['data'] = $result;
                $data['message']['success'] = showmessage('102', array('{name}'), array('Contract'));
                $data['status'] = 'success';

            }
            else
            {
                $data['data'] = null;
                $data['message']['error'] = showmessage('101', array('{name}'), array('Contract'));
                $data['status'] = 'error';
            }
            return response()->json($data);
        }
        catch (\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("contractupdate", "Updates the contract information, which is entered by user on Edit contract window.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("contractupdate", "Updates the contract information, which is entered by user on Edit contract window.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }
	 /*
     *This is controller funtion used for get the associated child contract

     * @author       Kavita Daware
     * @access       public
     * @param        $request
     * @param_type   array
     * @return       JSON
     * @tables       en_contract
     */
    public function associatechildcontract(Request $request)
    {
        $contract_id = $request->input('contract_id');
        $request['contract_id'] = DB::raw('UUID_TO_BIN("'.$contract_id.'")');
        $vendor_data = EnContract::where('contract_id', $request['contract_id'])->first();
        if ($vendor_data)
        {
            $vendor_id = $vendor_data['vendor_id'];
        }
        else
        {
            $vendor_id = null;
        }
        $result = EnContract::associatechildcontract($contract_id, $vendor_id);
        if ($result)
        {

            $data['data'] = $result;
            $data['message']['success'] = showmessage('102', array('{name}'), array('Contract'));
            $data['status'] = 'success';

        }
        else
        {
            $data['data'] = null;
            $data['message']['error'] = showmessage('101', array('{name}'), array('Contract'));
            $data['status'] = 'error';
        }
        return response()->json($data);
    }
	 /*
     *This is controller funtion used for update the associated child contract

     * @author       Kavita Daware
     * @access       publi
     * @param        $request
     * @param_type   array
     * @return       JSON
     * @tables       en_contract
     */
    public function contractupdateassociatechild(Request $request)
    {

        $contract_id = $request->input('contract_id');
        $request['contract_id'] = DB::raw('UUID_TO_BIN("'.$contract_id.'")');
        $request['contract_type_id'] = DB::raw('UUID_TO_BIN("'.$request->input('contract_type_id').'")');
        $request['vendor_id'] = DB::raw('UUID_TO_BIN("'.$request->input('vendor_id').'")');
        $contract_details_id = $request->input('contract_details_id');

        $request['contract_details_id'] = DB::raw('UUID_TO_BIN("'.$contract_details_id.'")');

        $associat_bin_arr = array();
        $associates_chk = $request->input('associates_chk');
        if (is_array($associates_chk))
        {
            foreach ($associates_chk as $val)
            {
                $associat_bin_arr[] = DB::raw('UUID_TO_BIN("'.$val.'")');

            }
        }

        $parentcontract = $request->input('parent_contract');
        $request['parent_contract'] = DB::raw('UUID_TO_BIN("'.$parentcontract.'")');

        $result = EnContract::whereIn('contract_id', $associat_bin_arr)->get();

        if ($result)
        {
            unset($request['associates_chk']);
            $result = EnContract::whereIn('contract_id', $associat_bin_arr)->update(array('parent_contract' => $request['parent_contract']));

            //add history for renewed  the contract
            $hist_details = $this->gethistorydesc('associatedchild',trans('label.lbl_contract'));
            $this->contracthistoryadd(array('contract_id'=> $parentcontract,'action' => 'associatedchild', 'details' =>  $hist_details, 'created_by' => DB::raw('UUID_TO_BIN("'.$request->input('loggedinuserid').'")')));

            $data['data'] = null;
            $data['message']['success'] = showmessage('106', array('{name}'), array('Contract'));
            $data['status'] = 'success';

        }
        else
        {
            $data['data'] = null;
            $data['message']['error'] = showmessage('101', array('{name}'), array('Contract'));
            $data['status'] = 'error';
        }

        return response()->json($data);
    }
	/*
     *This is controller funtion used for renew the contract

     * @author       Kavita Daware
     * @access       publi
     * @param        $request
     * @param_type   array
     * @return       JSON
     * @tables       en_contract
     */
    public function contractrenewsubmit(Request $request)
    {
		$messages = [
			  'contract_name.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_contract_name')), true),
			  'contract_name.allow_alpha_numeric_space_dash_underscore_only' => showmessage('003', array('{name}'), array(trans('label.lbl_contract_name')), true),
              'contract_name.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_contract_name')), true),
              'vendor_id.required' => showmessage('123', array('{name}'), array(trans('label.lbl_vendor')), true),
			  'contract_type_id.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_contract_type')), true),
			  'contractid.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_contract_id')), true),
			  'contractid.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_contract_id')),true),
			  'from_date.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_active_period_from')), true),
			  'from_date.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_active_period_from')),true),
			  'to_date.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_to')), true),
			  'to_date.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_to')),true),
			  'support.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_support')), true),
			  'support.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_support')),true),
			  'description.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_desc')), true),
			  'description.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_desc')),true),
			  'cost.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_maintenance_cost')), true),
			  'cost.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_maintenance_cost')),true),
			  'asset_id.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_asset')), true),
            ];
        $validator = Validator::make($request->all(), [

                'contract_name' => 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_contract, contract_name, '.$request->input('contract_name').', contract_id,'.$request->input('contract_id'),
                'vendor_id' => 'required',
                'contract_type_id' => 'required',
                'contractid' => 'required|html_tags_not_allowed',
                //'renewed' => 'required' ,
                'from_date' => 'required|html_tags_not_allowed',
                'to_date' => 'required|html_tags_not_allowed',
                //'parent_contract'  =>'required',
                'support' => 'required|html_tags_not_allowed',
                'description' => 'required|html_tags_not_allowed',
                'cost' => 'required|html_tags_not_allowed',
                'asset_id' => 'required',

            ],$messages);
        if ($validator->fails())
        {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
        }
        else
        {
            $inputdata = $request->all();
            DB::beginTransaction();
            $contract_id = $inputdata['contract_id'];
            $contract_details = EnContract::getcontract($contract_id, $inputdata, false);
            if ($contract_details)
            {

                $primary_contract = $contract_details[0]->primary_contract;
                $renewed_to = $contract_details[0]->renewed_to;
                $vendor_id = $contract_details[0]->vendor_id;
                $parent_contract = $contract_details[0]->parent_contract;
                //$parent= _isset($inputdata,'parent_contract');
                if ($parent_contract != '')
                {
                    $contract['parent_contract'] = DB::raw('UUID_TO_BIN("'.$parent_contract.'")');
                }
                $contract['contract_type_id'] = DB::raw('UUID_TO_BIN("'.$inputdata['contract_type_id'].'")');
                $contract['vendor_id'] = DB::raw('UUID_TO_BIN("'.$vendor_id.'")');
                $contract['primary_contract'] = DB::raw('UUID_TO_BIN("'.$primary_contract.'")');
                $contract['user_id'] = DB::raw('UUID_TO_BIN("'.$inputdata['user_id'].'")');
                $contract['renewed_to'] = DB::raw('UUID_TO_BIN("'.$contract_id.'")');

                $contract['contractid'] = _isset($inputdata, 'contractid');
                $contract['contract_name'] = _isset($inputdata, 'contract_name');
                $contract['renewed'] = _isset($inputdata, 'renewed', 'y');
                $contract['from_date'] = _isset($inputdata, 'from_date');
                $contract['to_date'] = _isset($inputdata, 'to_date');
                $todayDate = date("Y-m-d");
                //$contract['to_date'] = date("Y-m-d");

                //if ($todayDate < $contract['to_date'])
				if ($contract['to_date'] < $todayDate)
                {
                    $contract['contract_status'] = _isset($inputdata, 'contract_status', 'expired');
                }
                else
                {
                    $contract['contract_status'] = _isset($inputdata, 'contract_status', 'active');
                }

                $contract['status'] = _isset($inputdata, 'status', 'y');
                // print_r($contract);
                $contract_data = EnContract::create($contract);

                if ($contract_data->contract_id_text != '')
                {
                    // $data['query1'] ='success';
                    $contract_id = $contract_data->contract_id_text;
                    $contractdetails['contract_id'] = DB::raw('UUID_TO_BIN("'.$contract_id.'")');
                    $contractdetails['support'] = _isset($inputdata, 'support');
                    $contractdetails['description'] = _isset($inputdata, 'description');
                    $contractdetails['cost'] = _isset($inputdata, 'cost');
                    $asset = _isset($inputdata, 'asset_id');
                    $contractdetails['asset_id'] = json_encode($asset);
                    //$contractdetails['asset_id'] = _isset($inputdata,'asset_id');

                    $contractdetails_data = EnContractDetails::create($contractdetails);

                    if ($contract_data->contract_id_text == '' || $contractdetails_data->contract_details_id_text == '')
                    {

                        DB::rollBack();
                        $data['data'] = null;
                        $data['message']['error'] = showmessage('103', array('{name}'), array('Contract'));
                        $data['status'] = 'error';
                    }
                    else
                    {


                        //add history for renewed  the contract
                        $hist_details = $this->gethistorydesc('renewed',trans('label.lbl_contract'));
                        $this->contracthistoryadd(array('contract_id'=> $contract_id,'action' => 'renewed', 'details' =>  $hist_details, 'created_by' => DB::raw('UUID_TO_BIN("'.$request->input('loggedinuserid').'")')));


                        userlog(array('record_id' => $contract_data->contract_id_text, 'data' => $inputdata, 'action' => 'create', 'message' => showmessage('104', array('{name}'), array('Contract'), true)));
                        DB::commit();
                        $data['data']['insert_id'] = 1;
                        $data['message']['success'] = showmessage('104', array('{name}'), array('Contract'));
                        $data['status'] = 'success';
                    }
                }
            }
            else
            {
                $data['data'] = null;
                $data['message']['error'] = showmessage("104", array('{name}'), array('Contract'));
                $data['status'] = 'error';
            }
        }
        return response()->json($data);
    }
	/*
     *This is controller funtion used for get the details of renew contract

     * @author       Kavita Daware
     * @access       publi
     * @param        $request
     * @param_type   array
     * @return       JSON
     * @tables       en_contract
     */
    public function renewdetails(Request $request)
    {
        $primary_contract = $request->input('primary_contract');
        $request['primary_contract'] = DB::raw('UUID_TO_BIN("'.$primary_contract.'")');

        $result = EnContract::renewcontract($primary_contract);
        $resultCount = EnContract::renewcontract($primary_contract, true);
        if ($resultCount > 1)
        {

            $data['data'] = $result;
            $data['message']['success'] = showmessage('102', array('{name}'), array('Contract'));
            $data['status'] = 'success';

        }
        else
        {
            $data['data'] = null;
            $data['message']['error'] = showmessage('101', array('{name}'), array('Contract'));
            $data['status'] = 'error';
        }
        return response()->json($data);
    }
	/*
     *This is controller funtion used for attach file

     * @author       Kavita Daware
     * @access       publi
     * @param        $request
     * @param_type   array
     * @return       JSON
     * @tables       en_contract
     */
    public function attachfile(Request $request)
    {

        $contract_id = $request->input('contract_id');

        $username = $request['ENUSERNAME'];

       /* $saveimg = $username."_Contract.jpeg";
        $actual_path = 'uploads/contract/';
        $target_dir = public_path($actual_path); // add the specific path to save the file

        $decoded_file = base64_decode($request->input('attachments')); // decode the file

        $file_dir = $target_dir."/".$saveimg;*/
        $inputdata = array();
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'attachments' => 'required',
            'contract_id' => 'required',
            'contract_details_id' => 'required',
        ]);
        if ($validator->fails())
        {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        }
        try {

           /* $contract_details_id = $request->input('contract_details_id');
            $contract_details_id_bin = DB::raw('UUID_TO_BIN("'.$contract_details_id.'")');

            if (file_put_contents($file_dir, $decoded_file))
            {
                header('Content-Type: application/json');
                $request['content'] = $decoded_file;
                $request['attachments'] = $saveimg;

                $result = EnContractDetails::where('contract_details_id', $contract_details_id_bin)->first();

                if ($result)
                {

                    $result->update(array('attachments' => $request['attachments']));
                    $result->save();

                    $data['data'] = null;
                    $data['message']['success'] = showmessage('144', array('{name}'), array('Attachment')); //144/ 145
                    $data['status'] = 'success';

                    //
                }
                else
                {

                    $data['data'] = null;
                    $data['message']['error'] = showmessage('145', array('{name}'), array('Attachment')); //144/ 145
                    $data['status'] = 'error';

                }
                return response()->json($data);
            }*/


                $inputdata['contract_id'] = DB::raw('UUID_TO_BIN("'.$request->input('contract_id').'")');      
                $inputdata['attachment_ext'] = $request->input('attachment_ext');
                $inputdata['created_by'] = $request['user_id'];
                $actual_path = 'uploads/contract/';
                $target_dir = public_path($actual_path); // add the specific path to save the file
				header('Content-Type: application/json');
                foreach($request->input('attachments') as $key => $filename)
                {
                    $file_ext = ($request->input('attachment_ext'))[$key];
                    $saveimg = "contract_attachments_".$key."_".time().".$file_ext";
                    //$target_dir ='/var/www/application/public/uploads/purchase/'; // add the specific path to save the file
                   
                    //$decoded_file = base64_decode($request->input('file')); // decode the file
                    $file_dir = $target_dir."/".$saveimg;
                    $decoded_file = base64_decode($filename); // decode the file

                    if(file_put_contents($file_dir, $decoded_file))
                    {

                        
                        $inputdata['attachment_name']= $actual_path.$saveimg;


                       // $request['file']=$saveimg;
                        $pr_po_history = EnContractAttachment::create($inputdata);
                     //   $success=$this->updateprofilephoto($request);

                        $data['data'] = null;
                        $data['message']['success'] = showmessage('144', array('{name}'), array(trans('label.lbl_attachment'))); //144/ 145
                        $data['status'] = 'success';

                      //  return response()->json($data);

                        //save upload attachment history
                        $hist_details = $this->gethistorydesc('created',trans('label.lbl_attachment'));

                        //Add into Purchase History
                        $this->contracthistoryadd(array('contract_id'=> $request->input('contract_id'), 'action' => 'created', 'details' =>  $hist_details, 'created_by' => DB::raw('UUID_TO_BIN("'.$request->input('loggedinuserid').'")')));
                        //Add into UserActivityLog
                       userlog(array('record_id' => $request->input('pr_po_id'), 'data' => $request->all(), 'action' => 'added', 'message' => showmessage('104', array('{name}'),array(trans('label.lbl_attachment')))));
                    }
                    else
                    {

                        $data['data'] = null;
                        $data['message']['error'] = showmessage('145', array('{name}'), array(trans('label.lbl_attachment'))); //144/ 145
                        $data['status'] = 'error';
                       
                    }
            }
        }
        catch (Exception $e)
        {
            //header('Content-Type: application/json');
            echo json_encode($e->getMessage());
        }
        return response()->json($data);
    }
	/*
     *This is controller funtion used for get the attach file

     * @author       Kavita Daware
     * @access       publi
     * @param        $request
     * @param_type   array
     * @return       JSON
     * @tables       en_contract
     */
    public function getattachfile(Request $request)
    {

        $data['data'] = $request;
        $username = $request['ENUSERNAME'];
        // print_r( $username);
        //$target_dir = public_path('uploads/profile_photos/');
        $result = base64_encode(file_get_contents("/var/www/application/public/uploads/".$username));

        if ($result)
        {
            $data['data'] = $result;
            $data['message']['success'] = showmessage('102', array('{name}'), array('Attachment'));
            $data['status'] = 'success';
        }
        else
        {
            $data['data'] = null;
            $data['message']['error'] = showmessage('101', array('{name}'), array('Attachment'));
            $data['status'] = 'error';
        }

        return response()->json($data);

    }
		/*
     *This is controller funtion used for get specific contract attachment

     * @author       Kavita Daware
     * @access       publi
     * @param        $request
     * @param_type   array
     * @return       JSON
     * @tables       en_contract
     */
    public function contractattachment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contract_id' => 'required',
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
            $contractattachment = EnContractAttachment::getAttachments($request->input('contract_id'));
            if ($contractattachment->isEmpty())
            {
                $data['data'] = null;
                $data['status'] = 'error';
                $data['message']['error'] = showmessage('101', array('{name}'), array('Contract Attachment'));
            }
            else
            {
                $data['data'] = $contractattachment;
                $data['status'] = 'success';
                $data['message']['success'] = showmessage('102', array('{name}'), array('Contract Attachment'));
            }
            return response()->json($data);
        }
    }
	
		/*
     *This is controller funtion used for remove the selected asset

     * @author       Kavita Daware
     * @access       publi
     * @param        $contract_id, $asset_id, $request
     * @param_type   array
     * @return       JSON
     * @tables       en_contract
     */
    public function assetremove(Request $request,$contract_id = null,$asset_id = null)
    {
        /*if (($key = array_search('strawberry', $array)) !== false) {
        unset($array[$key]);
        }
        $result = EnContractDetails::where('contract_id', DB::raw('UUID_TO_BIN("'. $user->contract_id .'")'))->first();
        $result->update(array('asset_id' => date('Y-m-d G:i:s')));
        $result->save(); */
        $validator = Validator::make($request->all(), [
            'contract_id' => 'required',
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

            $assetremove = EnContractDetails::associatedassetremove($request->input('contract_id'), $request->input('asset_id'));
            //$queries = DB::getQueryLog();
            //$last_query = end($queries);
            if ($assetremove)
            {
                $data['data'] = null;
                $data['status'] = 'success';
                $data['message']['success'] = showmessage('118', array('{name}'), array('Contract Asset'), true);
            }
            else
            {

                $data['data'] = null;
                $data['status'] = 'error';
                $data['message']['error'] = showmessage('119', array('{name}'), array('Contract Asset'), true);
            }
            return response()->json($data);
        }
    }
    /**
     * This is funtion used to create History of Contract.
     * @author Kavita Daware
     * @access public
     * @package purchase
     * @param \Illuminate\Http\Request $request
     * @return json
     *
     */

    public function contracthistoryadd($contracthistorylogdata = array())
    {

        $inputdata = $contracthistorylogdata;
        $contract_id = $inputdata['contract_id'];
        $created_by = $inputdata['created_by'];
        $inputdata['contract_id'] = DB::raw('UUID_TO_BIN("'.$contract_id.'")');
        $inputdata['created_by'] = $created_by;

        $contract_history = EnContractHistory::create($inputdata);
        if (!empty($contract_history['history_id']))
        {
            /* $data['data']['insert_id'] = $pr_po_history->history_id_text;
            $data['message']['success'] = showmessage('104', array('{name}'), array('PR PO History'));
            $data['status'] = 'success';*/
            //Add into UserActivityLog
            userlog(array('record_id' => $contract_history->history_id_text, 'data' => $contracthistorylogdata, 'action' => 'added', 'message' => showmessage('104', array('{name}'), array('Contract History'))));
            return true;
        }
        else
        {
            /*$data['data'] = null;
            $data['message']['error'] = showmessage('103', array('{name}'), array('PR PO History'));
            $data['status'] = 'error';*/
            return false;
        }
    }
	
	 /**
     * This is funtion used to save data of contract actions
     * @author Kavita Daware
     * @access public
     * @package purchase
     * @param \Illuminate\Http\Request $request
     * @return json
     *
     */
    public function contractaction(Request $request)
    {  
		
        $messages = [
            'contract_id.required' => showmessage('000', array('{name}'), array('Contract Id'), true),
            'user_id.required' => showmessage('000', array('{name}'), array('User Id'), true),
            'action.required' => showmessage('000', array('{name}'), array('Action'), true) ,
			/*'mail_notification_to.required' => showmessage('000', array('{name}'), array(trans('label.lbl_to')), true) ,
			'mail_notification_to.allow_alpha_numeric_space_dash_underscore_only' => showmessage('003', array('{name}'), array(trans('label.lbl_to')), true) ,
			'mail_notification_subject.required' => showmessage('000', array('{name}'), array(trans('label.lbl_subject')), true),
			'mail_notification_subject.allow_alpha_numeric_space_dash_underscore_only' => showmessage('003', array('{name}'), array(trans('label.lbl_subject')), true),*/
            'comment.required' => showmessage('000', array('{name}'), array(trans('label.lbl_desc')), true),
			'comment.html_tags_not_allowed' => showmessage('001', array('{name}'), array(trans('label.lbl_desc')), true) ,
           
        ];

        $validator = Validator::make($request->all(), [            
            'contract_id' => 'required|allow_uuid|string|size:36',
            'user_id' => 'required|allow_uuid|string|size:36',
            'action' => 'required|:cancel,delete,close',  
			/*'mail_notification_to' => 'required|allow_alpha_numeric_space_dash_underscore_only',
			'mail_notification_subject' => 'required|allow_alpha_numeric_space_dash_underscore_only',*/
            'comment' => 'required|html_tags_not_allowed',           
                  
        ], $messages); 
       /* $validator = Validator::make($request->all(), [
            'contract_id' => 'required',
        ]);*/
		
		
        
		//Added Validator for email
		$validator->after(function ($validator)
        {
            $request      = request();
			
			$mail_notification = $request->has('mail_notification')  ? $request->input('mail_notification') : "";
			$mail_notification_subject = $request->has('mail_notification_subject')  ? $request->input('mail_notification_subject') : "";
			$mail_notification_to = $request->has('mail_notification_to')  ? $request->input('mail_notification_to') : "";
			if($mail_notification == 'y'){
				if ($mail_notification_subject == "")
				{
					$validator->errors()->add('mail_notification_subject.required',showmessage('000', array('{name}'), array(trans('label.lbl_subject')), true));
				}
				if($mail_notification_to == "")
				{
					$validator->errors()->add('mail_notification_to.required',showmessage('000', array('{name}'), array(trans('label.lbl_to')), true));
				}else{
					
					if (!filter_var($mail_notification_to, FILTER_VALIDATE_EMAIL)) {
					
						$validator->errors()->add('mail_notification_to.required',showmessage('014', array('{name}'), array(trans('label.lbl_to')), true));
					}
				}
			}
        });
		
        if ($validator->fails())
        {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';           
        }
        else
        {
            DB::beginTransaction(); // begin transaction
           // $pr_po_type = $request->input('pr_po_type');

            $notify_to_id = $request->has('notify_to_id')  ? $request->input('notify_to_id') : NULL;

            $contract_id_uuid = $request->input('contract_id');
            $contract_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('contract_id').'")');
            $action = $request->has('action')  ? $request->input('action') : "";
            $result = EnContract::where('contract_id',$contract_id_bin)->first();
            $result_message=  "Contract";   
			if($action == 'notifyowner'){
				$action_msg = 'notify owner';
			}else if($action == 'notifyvendor'){
				$action_msg = 'notify vendor';
			}else{
				$action_msg = $action;
			}
             
            $comment = $request->has('comment')  ? $request->input('comment') : "";

           
            $flag = 0;
           // $queries    = DB::getQueryLog();
            //$data['last_query'] = end($queries); 
            if($result) 
            {
                if($action == "notifyagain" || $action == "notifyowner" || $action == "notifyvendor" )
                {
                    //Add Mail Sending Code
                    
                    if($action == "notifyagain")             
                    {
                        if($notify_to_id)
                        {
                           $notify_to_id =   DB::raw('UUID_TO_BIN("'.$notify_to_id.'")');
                        }
                       
                         $flag = 1;
                    }
                    else
                    {
                        $flag = 1;   
                    }
                }
               
                if($flag == 1){
                     
                  // $this->contracthistoryadd(array('contract_id'=> $contract_id_uuid, 'action' => $action, 'details' => $result_message." ".$request->input('action').".", 'created_by' => DB::raw('UUID_TO_BIN("'.$request->input('loggedinuserid').'")'), 'notify_to_id' => $notify_to_id, 'comment'=> $comment)); 
     
                    userlog(array('record_id' => $contract_id_uuid, 'data' => $request->all(), 'action' => 'updated', 'message' => showmessage('140', array('{name}'),array($result_message.' '.$action_msg))));                 

                    $data['data'] = null;
                    $data['message']['success'] = showmessage('140', array('{name}'), array($result_message.'  '.$action_msg));
                    $data['status'] = 'success'; 
                     DB::commit();
                }
                else
                {
                    DB::rollBack();
                    $data['data'] = null;
                    $data['message']['error'] = showmessage('139', array('{name}'), array($action_msg.' '.$result_message));
                    $data['status'] = 'error'; 
                }
            }
            else
            {
                DB::rollBack();
                $data['data'] = null;
                $data['message']['error'] = showmessage('139', array('{name}'), array($action_msg.' '.$result_message));
                $data['status'] = 'error';    
            }
        }
        return response()->json($data);
    }
	
	/**
     * This is funtion used to displays the attachment
     * @author Kavita Daware
     * @access public
     * @package purchase
     * @param \Illuminate\Http\Request $request
     * @return json
     *
     */
	 
    public function showattachment(Request $request)
    {
        $data = $this->itam->getattachfile();
		header("Content-type: image/jpeg");
		echo base64_decode($data['content']);
	}


   
	/**
     * This is funtion used to return history description text according to its approval status.
     * @author Kavita Daware
     * @access public
     * @package contract
     * @param $approval_status, $result_manager
     * @return json
     *
     */
    public function gethistorydesc($approval_status='',$result_message='')
    {
        //get history details text
        $hist_details = '';
        switch($approval_status){

             case "created":
                $hist_details = showmessage('msg_created', array('{name}'), array($result_message), true);
                break;
            case "updated":
                $hist_details = showmessage('msg_updated', array('{name}'), array($result_message), true);
                break;
            case "deleted":
                $hist_details = showmessage('msg_deleted', array('{name}'), array($result_message), true);
                break;
            case "renewed":
                $hist_details = showmessage('msg_renewed', array('{name}'), array($result_message), true);
                break;
            case "associatedchild":
                $hist_details = showmessage('msg_associatedchild', array('{name}'), array($result_message), true);
                break;
            case "notifyvendor":
                $hist_details = showmessage('msg_notifyvendor', array('{name}'), array($result_message), true);
                break;
            case "open":
                $hist_details = showmessage('msg_open', array('{name}'), array($result_message), true);
                break;
           
           
            default:
                $hist_details = '';
            //----------------------------------------------------
        }
        return $hist_details;
    }
	/**
     * This is funtion used to maintaing the contract history log
     * @author Kavita Daware
     * @access public
     * @package contract
     * @param $request
     * @return json
     *
     */
    public function contracthistorylog(Request $request)
    {
        $messages = [
            'contract_id.required' => showmessage('000', array('{name}'), array('contract'), true)
        ];
        $validator = Validator::make($request->all(), [
            'contract_id' => 'required|allow_uuid|string|size:36'
        ], $messages);
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
            $contract_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('contract_id').'")');
            $contracthistorylog = EnContractHistory::select(DB::raw('BIN_TO_UUID(history_id) AS history_id'), DB::raw('BIN_TO_UUID(contract_id) AS contract_id'),'action','details',DB::raw('BIN_TO_UUID(created_by) AS created_by'), 'status', 'created_at', 'updated_at', 'comment')
                ->where('contract_id', $contract_id_bin)  
                ->where('status', '!=', 'd')            
                ->orderBy('created_at', 'desc')      
                ->get();   
            if($contracthistorylog->isEmpty())
            {
                $data['data'] = NULL;
                $data['status'] = 'error';
                $data['message']['error'] = showmessage('101', array('{name}'), array('Contract History'));
            }
            else
            {
                $creatde_at_arr = array();
                foreach($contracthistorylog as $i => $history)
                {
                   $created_at =  date("d F Y", strtotime($history['created_at']));
                   if(!in_array($created_at, $creatde_at_arr, true)){
                        array_push($creatde_at_arr, $created_at);
                        $contracthistorylog[$i]['history_date'] = $created_at;
                    }
                    else
                    {
                        $contracthistorylog[$i]['history_date'] = "";
                    }                   
                }
                $data['data'] = $contracthistorylog;
                $data['status'] = 'success';
                $data['message']['success'] = showmessage('102', array('{name}'), array('Contract History'));
            } 
            return response()->json($data);
         }
    }
	/**
     * This is funtion used to delete the contract attachment
     * @author Snehal C
     * @access public
     * @package contract
     * @param $request
     * @return json
     *
     */
    public function deletecontractattachment(Request $request)
    {
        $messages = [
            'attach_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_attachmentid')), true)
        ];
        $validator = Validator::make($request->all(), [
            'attach_id' => 'required|allow_uuid|string|size:36'
        ], $messages);
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
            $attach_id = DB::raw("UUID_TO_BIN('".$request['attach_id']."')");
            $contract_id  = DB::raw("UUID_TO_BIN('".$request['contract_id']."')");
            $contractattachment = EnContractAttachment::where('attach_id', $attach_id)->first();
            if($contractattachment)
            {
                $attachment_name = $contractattachment['attachment_name'];
                $contractattachment->update(array('status' => 'd'));
                $contractattachment->save(); 
                if(!unlink($attachment_name))
                {
                    $data['data'] = NULL;
                    $data['status'] = 'error';
                    $data['message']['error'] = showmessage('119', array('{name}'), array('Contract Attached File '));
                }
                else
                {
                    $data['data'] = NULL;
                    $data['status'] = 'success';
                    $data['message']['success'] = showmessage('118', array('{name}'), array('Contract Attached File'));

                    //save delete attachment history
                    $hist_details = $this->gethistorydesc('deleted',trans('label.lbl_attachment'));

                    //Add into Purchase History
                    $this->contracthistoryadd(array('contract_id'=> $request->input('contract_id'), 'action' => 'deleted', 'details' =>  $hist_details, 'created_by' => DB::raw('UUID_TO_BIN("'.$request->input('loggedinuserid').'")')));
                    //Add into UserActivityLog
                   userlog(array('record_id' => $request->input('contract_id'), 'data' => $request->all(), 'action' => 'deleted', 'message' => showmessage('118', array('{name}'),array(trans('label.lbl_attachment')))));
                }                 
            }
            else
            {
                $data['data'] = NULL;
                $data['status'] = 'error';
                $data['message']['error'] = showmessage('119', array('{name}'), array('Purchase Attached File '));
            }
 
            return response()->json($data);
         }
    }
	/**
     * This is funtion used to get all assets for contract
     * @author Snehal C
     * @access public
     * @package contract
     * @param $asset_id, $request
     * @return json
     *
     */
	public function getallassets(Request $request,$asset_id = null)
    {
        try
        { 
            
            $requset['asset_id'] = $asset_id;
            $validator = Validator::make($request->all(), [
                'asset_id' => 'nullable|allow_uuid|string|size:36',
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
				
                $inputdata['searchkeyword'] = trim(_isset($inputdata, 'searchkeyword'));
                
                $totalrecords = EnAssets::getallassets($asset_id, $inputdata, true);
                $result = EnAssets::getallassets($asset_id, $inputdata, false);
				
                $queries = DB::getQueryLog();
                $last_query = end($queries);

                $data['data']['query'] = $last_query;

                $data['data']['records'] = $result->isEmpty() ? null : $result;
                $data['data']['totalrecords'] = $totalrecords;
                if ($totalrecords < 1)
                {
                    $data['message']['error'] = showmessage('102', array('{name}'), array('Asset'));
                    $data['status'] = 'error';
                }
                else
                {
                    $data['message']['success'] = showmessage('101', array('{name}'), array('Asset'));
                    $data['status'] = 'success';
                }
                $data['status'] = 'success';
				
                return response()->json($data);
            }
        }
        catch (\Exception $e)
        {
			
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            //save_errlog("contracts", "This is controller funtion used for Contracts.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            //save_errlog("contracts", "This is controller funtion used for Contracts.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }
	
	public function updatecontractstatus(Request $request)
    {
        $contract_id = $request->input('contract_id');
        $data = EnContract::changecontractstatus($contract_id, $request->input('contract_status'));
        //Add into UserActivityLog
        return response()->json($data);
	}
}
