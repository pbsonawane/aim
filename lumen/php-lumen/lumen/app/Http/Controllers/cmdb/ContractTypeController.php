<?php
namespace App\Http\Controllers\cmdb;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EnContractType;
use Validator;

class ContractTypeController extends Controller
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
    * @param        URL : contract_type_id
    * @param_type   integer
    * @return       JSON
    * @tables       en_contract_type
    */
    public function contractstype(Request $request,$contract_type_id = null)
    {

        $request['contract_type_id'] = $contract_type_id;
        $validator = Validator::make($request->all(), [
            'contract_type_id'=> 'nullable|allow_uuid|string|size:36'
        ]);
         if($validator->fails())
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
            $inputdata['searchkeyword'] = trim(_isset($inputdata,'searchkeyword'));   
            $totalrecords = EnContractType::getcontracttype($contract_type_id,$inputdata, true);  
            $result = EnContractType::getcontracttype($contract_type_id, $inputdata , false);  
            
            $data['data']['records'] = $result->isEmpty() ? NULL : $result;
            $data['data']['totalrecords'] = $totalrecords;                
           
            if ($totalrecords < 0)
            {
                $data['message']['error'] = showmessage('102', array('{name}'), array('Contract Type'));
                $data['status'] = 'error';
            }
            else
            {
                $data['message']['success'] = showmessage('101', array('{name}'), array('Contract Type'));
                $data['status'] = 'success';
            }
            return response()->json($data);
        }
    }
     /*
    * This is controller funtion used to add the contracts type.

    * @author       Kavita Daware
    * @access       public
    * @param        contract_type_id, contract_type, contract_description
    * @param_type   POST array
    * @return       JSON
    * @tables       en_contract_type    
    */
    public function contracttypeadd(Request $request) 
    {
        $messages = [
                'contract_type.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_contract_type')), true),
                'contract_type.allow_alpha_numeric_space_dash_underscore_only' => showmessage('003', array('{name}'), array(trans('label.lbl_contract_type')), true),
                'contract_type.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_contract_type')), true),
                'contract_description.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_desc')), true),
    			'contract_description.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_desc')), true),
               
            ];
       $validator = Validator::make($request->all(), [  
			'contract_type_id'=> 'nullable|allow_uuid|string|size:36',
            'contract_type' => 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_contract_type, contract_type, '.$request->input('contract_type'),  
            //'contract_type' => 'required' , 
            'contract_description' => 'required|html_tags_not_allowed' ,  
            
        ], $messages);          
         if ($validator->fails())
        {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
        }
        else
        { 
            $contracttype_data = EnContractType::create($request->all());  
            if(!empty($contracttype_data['contract_type_id']))
            {
                $contract_type_id = $contracttype_data->contract_type_text;
                $data['data']['insert_id'] = $contract_type_id;
                $data['message']['success'] = showmessage('104', array('{name}'),array('Contract'));
                $data['status'] = 'success';
            }
            else
            {
                $data['data'] = $contracttype_data;
                $data['message']['error'] = showmessage('103', array('{name}'),array('Contract'));
                $data['status'] = 'error';
            }
        }
        return response()->json($data); 
    }
    /* Provides a window to user to update the contract information.

    * @author       Kavita Daware
    * @access       public
    * @param        URL : contract_type_id
    * @param_type   Integer
    * @return       JSON
    * @tables       en_contract_type     
    */
    public function contracttypeedit(Request $request)
    {
        //$request['contract_type_id'] = $contract_type_id;
        $validator = Validator::make($request->all(), [ 
            'contract_type_id' => 'required|string|size:36'
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
            $result = EnContractType::getcontracttype($request->input('contract_type_id'));  
            
             $data['data'] = $result->isEmpty() ? NULL : $result;
            
          
              if($data['data'])
            {
                $data['message']['success'] = showmessage('102', array('{name}'),array('Contract'));
                $data['status'] = 'success';            
            }
            else
            {
               
                $data['message']['error'] = showmessage('101', array('{name}'),array('Contract'));
                $data['status'] = 'error';          
            }
        }
        return response()->json($data); 
    }  
   /*
    * Updates the contract information, which is entered by user on Edit contract window.

    * @author       Kavita Daware
    * @access       public
    * @param        contract_type_id, contract_type, contract_description
    * @param_type   POST array
    * @return       JSON
    * @tables       en_contract_type    
    */
    public function contracttypeupdate(Request $request)
    {
      
          $messages = [
                'contract_type.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_contract_type')), true),
                'contract_type.allow_alpha_numeric_space_dash_underscore_only' => showmessage('003', array('{name}'), array(trans('label.lbl_contract_type')), true),
                'contract_type.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_contract_type')), true),
                'contract_description.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_desc')), true),
    			'contract_description.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_desc')), true),
               
            ];
       $validator = Validator::make($request->all(), [  
			'contract_type_id'=> 'required|allow_uuid|string|size:36',
            'contract_type' => 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_contract_type, contract_type, '.$request->input('contract_type').', contract_type_id,'.$request->input('contract_type_id'),  
            //'contract_type' => 'required' , 
            'contract_description' => 'required|html_tags_not_allowed' ,  
            
        ], $messages);                   
        if ($validator->fails())
        {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
        }
        else
        { 

            $contract_type_id_uuid = $request->input('contract_type_id');
            $contract_type_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('contract_type_id').'")');
            $request['contract_type_id'] = DB::raw('UUID_TO_BIN("'.$request->input('contract_type_id').'")');
            $result = EnContractType::where('contract_type_id', $contract_type_id_bin)->first();
        
            if($result)
            {
                $result->update($request->all());            
                $result->save();             
                $data['data'] = NULL;     
                $data['message']['success'] = showmessage('106', array('{name}'),array('Contract'));      
                $data['status'] = 'success'; 
    
            }
            else
            {             
                $data['data'] = NULL;             
                $data['message']['error'] = showmessage('101', array('{name}'),array('Contract'));     
                $data['status'] = 'error'; 
            } 
        }  
        return response()->json($data); 
    }


   /* public function contracttypedelete(Request $request)
    { 
       // $request['contract_type_id'] = $contract_type_id;     
        $validator = Validator::make($request->all(), [
                'contract_type_id' => 'required|string|size:36'
        ]);  
        if($validator->fails())
        {
            $error = $validator->errors(); 
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data); 
        }
        else
        { 
            //$data = EnContractType::find($request->input('contract_type_id'))->delete();
            $data = EnContractType::checkforrelation($request->input('contract_type_id'));
            return response()->json($data);  
        } 
    } 
    */
    public function contracttypedelete(Request $request,$contract_type_id = null)
    {
        $request['contract_type_id'] = $contract_type_id;
        $messages = [
            'contract_type_id.required' => showmessage('000', array('{name}'), array('Contract Type Id'), true),
        ];
       
        $validator = Validator::make($request->all(), [
            'contract_type_id' => 'required|allow_uuid|string|size:36',
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
            $data = EnContractType::checkforrelation($contract_type_id);
            //Add into UserActivityLog
            if ($data['data'])
            {
                userlog(array('record_id' => $contract_type_id, 'data' => $request->all(), 'action' => 'deleted', 'message' => showmessage('118', array('{name}'), array('Contract Type'))));
            }
            return response()->json($data);
        }
    }

}