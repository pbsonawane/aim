<?php
namespace App\Http\Controllers\cmdb;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EnCostCenters;
use Validator;

class CostCenterController extends Controller
{ 
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
        DB::connection()->enableQueryLog();
    }
/*
    *This is controller funtion used to List Cost Centers.

    * @author       Vikas Kumar
    * @access       public
    * @param        URL : cc_id [Optional] 
    * @param_type   Integer
    * @return       JSON
    * @tables       en_cost_centers     
    */

    public function costcenters(Request $request,$cc_id = NULL)
    {
        $request['cc_id'] = $cc_id;
        $validator = Validator::make($request->all(), [
            'cc_id'=> 'nullable|allow_uuid|string|size:36' 
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
            $totalrecords = EnCostCenters::getcostcenters($cc_id,$inputdata, true);  
            $result = EnCostCenters::getcostcenters($cc_id, $inputdata , false);  

            $queries    = DB::getQueryLog();
            $data['last_query'] = end($queries);
            
            $data['data']['records'] = $result->isEmpty() ? NULL : $result;
            $data['data']['totalrecords'] = $totalrecords;
            
            if($totalrecords < 1)   
                $data['message']['success']= showmessage('101', array('{name}'),array('Cost Center'));
            else
                $data['message']['success']= showmessage('102', array('{name}'),array('Cost Center'));
            $data['status'] = 'success';

            return response()->json($data);             
        }
    }
    
    //================== Cost Center List END ====== 

    /*
    * This is controller funtion used to accept the values for new Cost Center. 
    * @author       Vikas Kumar
    * @access       public
    * @param        cc_code, cc_name, cc_description, owner_id,locations,departments, status
    * @param_type   POST array
    * @return       JSON
    * @tables       en_cost_centers      
    */

  
    public function costcenteradd(Request $request) 
    {	
        $messages = [
			'cc_code.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_cc_code')), true),
            'cc_code.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_cc_code')), true),
            'cc_code.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_cc_code')), true),
            'cc_name.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_cc_name')), true),
            'cc_name.allow_alpha_numeric_space_dash_underscore_only'       => showmessage('003', array('{name}'), array(trans('label.lbl_cc_name')), true),
            'cc_name.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_cc_name')), true),
            'description.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_desc')), true),
            'description.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_desc')), true),
			'locations.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_location')), true),
			'departments.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_department')), true),
			];
       $validator = Validator::make($request->all(), [
			'cc_id'=> 'nullable|allow_uuid|string|size:36',
            'cc_code' => 'required|html_tags_not_allowed|composite_unique:en_cost_centers, cc_code, '.$request->input('cc_code'),  
			'cc_name' => 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_cost_centers, cc_name, '.$request->input('cc_name'),
            'locations' => 'required' ,  
            'description' => 'required|html_tags_not_allowed' ,  
            'departments' => 'required', 
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
            $inputdata = $request->all();
            $costcenter = $inputdata;
            $costcenter['locations'] = DB::raw('UUID_TO_BIN("'.$inputdata['locations'].'")');
            $departments = _isset($inputdata, 'departments');
            $costcenter['departments'] = json_encode($departments);
            
			//$inputdata['departments'] = json_encode($request->departments,true);
			//$inputdata['locations'] = DB::raw('UUID_TO_BIN("'.$request->locations.'")');
            $cc_data = EnCostCenters::create($costcenter); 
			 
            if(!empty($cc_data['cc_id']))
            {
                $cc_id = $cc_data->cc_id_text;
                $data['data']['insert_id'] = $cc_id;
                $data['message']['success'] = showmessage('104', array('{name}'),array('Cost Center'));
                $data['status'] = 'success';
                 //Add into UserActivityLog
                userlog(array('record_id' => $cc_data->cc_id_text, 'data' => $request->all(), 'action' => 'added', 'message' => showmessage('104', array('{name}'),array('Cost Center'))));
            }
            else
            {
                $data['data'] = NULL;
                $data['message']['error'] = showmessage('103', array('{name}'),array('Cost Center'));
                $data['status'] = 'error';
            }
        }
        return response()->json($data); 
    }
    //================== Cost Center ADD END ====== 

    /*
    * This is controller funtion used to delete the Cost Center.  

    * @author       Vikas Kumar
    * @access       public
    * @param        URL : cc_id
    * @param_type   Integer
    * @return       JSON
    * @tables       en_cost_Centers     

    */  

    public function costcenterdelete(Request $request,$cc_id = NULL)
    {   
        $request['cc_id'] = $cc_id;     
        $validator = Validator::make($request->all(), [
                'cc_id' => 'required|allow_uuid|string|size:36'             
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
            /*$cc_id_uuid = $cc_id;     
            //$request['cc_id'] = DB::raw('UUID_TO_BIN("'.$cc_id_uuid.'")'); 
            $cc = EnCostCenters::where('cc_id', DB::raw('UUID_TO_BIN("'.$cc_id_uuid.'")'))->first();
            if($cc)
            {
                $cc->update(array('status' => 'd'));            
                $cc->save();   
				/*
				$queries    = DB::getQueryLog();
                $last_query = end($queries); 
                print_r($last_query); exit; 
		   
				              
                $data['data']['deleted_id'] = $cc_id_uuid;
                $data['message']['success'] = showmessage('118', array('{name}'),array('Cost Center'));
                $data['status'] = 'success';
                 //Add into UserActivityLog
                userlog(array('record_id' => $cc_id_uuid, 'data' => $request->all(), 'action' => 'deleted', 'message' => showmessage('118', array('{name}'),array('Cost Center'))));                
            }
            else
            {
                $data['data'] = NULL;
                $data['message']['error'] = showmessage('101', array('{name}'),array('Cost Center'));
                $data['status'] = 'error';                             
            }
     
            return response()->json($data);  */

            $data = EnCostCenters::checkforrelation($cc_id);
            //Add into UserActivityLog
            if ($data['data'])
            {
                //userlog(array('record_id' => $vendor_id, 'data' => $request->all(), 'action' => 'deleted', 'message' => 'Record Deleted Successfully'));
            }
            return response()->json($data);
        } 
    }
    //================== Cost Center Delete END ======

    /*  
    * Provides a window to user to update the Cost Cneter's information.

    * @author       Vikas Kumar
    * @access       public
    * @param        URL : cc_id
    * @param_type   Integer
    * @return       JSON
    * @tables       en_cost_centers    
    */
    public function costcenteredit(Request $request,$cc_id = NULL)
    {
        //$request['cc_id'] = $cc_id;  
        $validator = Validator::make($request->all(), [
            'cc_id' => 'required|allow_uuid|string|size:36'     
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
            $result = EnCostCenters::getcostcenters($request->input('cc_id'));
            $data['data'] = $result->isEmpty() ? NULL : $result;

            if($data['data'])
            {
                $data['message']['success'] = showmessage('102', array('{name}'),array('Cost Center'));
                $data['status'] = 'success';            
            }
            else
            {
                $data['message']['error'] = showmessage('101', array('{name}'),array('Cost Center')); 
                $data['status'] = 'error';          
            }
            return response()->json($data); 
        }
    }     
    //===== Cost Center Edit END ===========

    /*
    * Updates the Pods information, which is entered by user on Edit Cost Centers window.

    * @author       Vikas Kumar
    * @access       public
    * @param        cc_code, cc_name, cc_description, owner_id,locations,departments, status
    * @param_type   POST array
    * @return       JSON
    * @tables       en_cost_centers   
    */

    public function costcenterupdate(Request $request)
    {
        $messages = [
			'cc_code.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_cc_code')), true),
            'cc_code.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_cc_code')), true),
            'cc_code.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_cc_code')), true),
            'cc_name.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_cc_name')), true),
            'cc_name.allow_alpha_numeric_space_dash_underscore_only'       => showmessage('003', array('{name}'), array(trans('label.lbl_cc_name')), true),
            'cc_name.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_cc_name')), true),
            'description.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_desc')), true),
            'description.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_desc')), true),
			'locations.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_location')), true),
			'departments.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_department')), true),
			];
       $validator = Validator::make($request->all(), [
			'cc_id'=> 'required|allow_uuid|string|size:36',
            'cc_code' => 'required|html_tags_not_allowed|composite_unique:en_cost_centers, cc_code, '.$request->input('cc_code').', cc_id,'.$request->input('cc_id'),   
			'cc_name' => 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_cost_centers, cc_name, '.$request->input('cc_name').', cc_id,'.$request->input('cc_id'),   
            'locations' => 'required' ,  
            'description' => 'required|html_tags_not_allowed' ,  
            'departments' => 'required', 
        ], $messages);   

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
            $cc_id_uuid = $request->input('cc_id');     
            $request['cc_id'] = DB::raw('UUID_TO_BIN("'.$request->input('cc_id').'")');     
                  
            $result = EnCostCenters::where('cc_id', $request['cc_id'])->first();
            if($result)
            {	
                $inputdata = $request->all();
                $request = $inputdata;
                $request["departments"] = json_encode($inputdata['departments']);
                $request["locations"] = DB::raw('UUID_TO_BIN("'.$inputdata['locations'].'")');
                
                
				//$inputdata['departments'] = json_encode($request->departments,true);
				//$inputdata['locations'] = DB::raw('UUID_TO_BIN("'.$request->location.'")');
				//$cc_data = EnCostCenters::create($inputdata); 
			 
                $result->update($request);            
                $result->save();             
                $data['data'] = NULL;     
                $data['message']['success'] = showmessage('106', array('{name}'),array('Cost Center'));         
                $data['status'] = 'success'; 
                 //Add into UserActivityLog
                //userlog(array('record_id' => $cc_id_uuid, 'data' => $request->all(), 'action' => 'updated', 'message' => showmessage('106', array('{name}'),array('Cost Center'))));                 
            }
            else
            {             
                $data['data'] = NULL;             
                $data['message']['error'] = showmessage('101', array('{name}'),array('Cost Center'));
                $data['status'] = 'error'; 
            }   
            return response()->json($data); 
        }
    }
    
    
}// Class End