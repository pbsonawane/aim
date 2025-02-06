<?php
namespace App\Http\Controllers\cmdb;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EnSoftwareType;
use Validator;

class SoftwareTypeController extends Controller
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
     /**This is controller funtion used for Software Types.

    * @author       Kavita Daware
    * @access       public
    * @param        software_type_id
    * @param_type   integer
    * @return       JSON
    * @tables       en_software_types
    */
    public function softwaretype(Request $request,$software_type_id = null)
    {
        try
        {

            $request['software_type_id'] = $software_type_id;
            $validator = Validator::make($request->all(), [
                'software_type_id'=> 'nullable|allow_uuid|string|size:36'
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
                $totalrecords = EnSoftwareType::getsoftwaretype($software_type_id,$inputdata, true);  
                $result = EnSoftwareType::getsoftwaretype($software_type_id, $inputdata , false);  
                
                $data['data']['records'] = $result->isEmpty() ? NULL : $result;
                $data['data']['totalrecords'] = $totalrecords;                
               
                if ($totalrecords < 0)
                {
                    $data['message']['error'] = showmessage('102', array('{name}'), array('Software Type'));
                    $data['status'] = 'error';
                }
                else
                {
                    $data['message']['success'] = showmessage('101', array('{name}'), array('Software Type'));
                    $data['status'] = 'success';
                }
                return response()->json($data);
            }
        }
        catch (\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwaretype", "This is controller funtion used for Software Types.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwaretype", "This is controller funtion used for Software Types.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }
     /*
    * This is controller funtion used to add the Software Types .

    * @author       Kavita Daware
    * @access       public
    * @param        software_type_id, software_type, description
    * @param_type   POST array
    * @return       JSON
    * @tables       en_software_types    
    */
    public function softwaretypeadd(Request $request) 
    {
        try
        {
            $messages = [
                'software_type.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_software_type')), true),
                'software_type.allow_alpha_numeric_space_dash_underscore_only' => showmessage('003', array('{name}'), array(trans('label.lbl_software_type')), true),
                'software_type.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_software_type')), true),
                'description.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_desc')), true),
               
                'description.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_desc')), true),
                
            ];
           $validator = Validator::make($request->all(), [ 
				'software_type_id'=> 'nullable|allow_uuid|string|size:36',
                'software_type' => 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_software_types, software_type, '.$request->input('software_type'),  
                'description' => 'required|html_tags_not_allowed' ,  
                
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
                $softwaretype['software_type'] = _isset($inputdata, 'software_type');
                $softwaretype['description'] = _isset($inputdata, 'description');
                $softwaretype['status'] = _isset($inputdata, 'status', 'y');
                $softwaretype_data = EnSoftwareType::create($softwaretype);  
                if(!empty($softwaretype_data['software_type_id']))
                {
                    $software_type_id = $softwaretype_data->software_type_text;
                    $data['data']['insert_id'] = $software_type_id;
                    $data['message']['success'] = showmessage('104', array('{name}'),array('Software Type'));
                    $data['status'] = 'success';
                }
                else
                {
                    $data['data'] = NULL;
                    $data['message']['error'] = showmessage('103', array('{name}'),array('Software Type'));
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
            save_errlog("softwaretypeadd", "This is controller funtion used to add the Software Types ", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwaretypeadd", "This is controller funtion used to add the Software Types", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }
    /* Provides a window to user to update the softwaretype information.

    * @author       Kavita Daware
    * @access       public
    * @param        software_type_id
    * @param_type   Integer
    * @return       JSON
    * @tables       en_software_types     
    */
    public function softwaretypeedit(Request $request)
    {
        try
        {
            //$request['software_type_id'] = $software_type_id;
            $validator = Validator::make($request->all(), [ 
                'software_type_id' => 'required|allow_uuid|string|size:36'
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
                $result = EnSoftwareType::getsoftwaretype($request->input('software_type_id'));  
                
                 $data['data'] = $result->isEmpty() ? NULL : $result;
                
              
                  if($data['data'])
                {
                    $data['message']['success'] = showmessage('101', array('{name}'),array('Software Type'));
                    $data['status'] = 'success';            
                }
                else
                {
                   
                    $data['message']['error'] = showmessage('102', array('{name}'),array('Software Type'));
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
            save_errlog("softwaretypeedit", "Provides a window to user to update the softwaretype information. ", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwaretypeedit", "Provides a window to user to update the softwaretype information.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }  
   /*
    * Updates the Software Type information, which is entered by user on Edit Software Type window.

    * @author       Kavita Daware
    * @access       public
    * @param        software_type_id, software_type, description
    * @param_type   POST array
    * @return       JSON
    * @tables       en_software_types    
    */
    public function softwaretypeupdate(Request $request)
    {
        try
        {
      
    		$messages = [
                'software_type.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_software_type')), true),
                'software_type.allow_alpha_numeric_space_dash_underscore_only' => showmessage('003', array('{name}'), array(trans('label.lbl_software_type')), true),
                'software_type.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_software_type')), true),
                'description.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_desc')), true),
               
                'description.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_desc')), true),
                
            ];
           $validator = Validator::make($request->all(), [
				'software_type_id' => 'required|allow_uuid|string|size:36',
                'software_type' => 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_software_types, software_type, '.$request->input('software_type').', software_type_id,'.$request->input('software_type_id'),  
                'description' => 'required|html_tags_not_allowed' ,  
                
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

                $software_type_id_uuid = $request->input('software_type_id');
                $software_type_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('software_type_id').'")');
                $request['software_type_id'] = DB::raw('UUID_TO_BIN("'.$request->input('software_type_id').'")');
                $result = EnSoftwareType::where('software_type_id', $software_type_id_bin)->first();
            
                if($result)
                {
                    $result->update($request->all());            
                    $result->save();             
                    $data['data'] = NULL;     
                    $data['message']['success'] = showmessage('106', array('{name}'),array('Software Type'));      
                    $data['status'] = 'success'; 
        
                }
                else
                {             
                    $data['data'] = NULL;             
                    $data['message']['error'] = showmessage('101', array('{name}'),array('Software Type'));     
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
            save_errlog("softwaretypeupdate", "Updates the Software Type information, which is entered by user on Edit Software Type window. ", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwaretypeupdate", "Updates the Software Type information, which is entered by user on Edit Software Type window.", $request->all(), $e->getMessage());
            return response()->json($data);
        } 
    }

    /* This function is used to delete software type record.

    * @author       Kavita Daware
    * @access       public
    * @param        software_type_id
    * @param_type   Integer
    * @return       JSON
    * @tables       en_software_type     
    */

    public function softwaretypedelete(Request $request,$software_type_id = null)
    {
        try
        {
            $request['software_type_id'] = $software_type_id;
            $messages = [
                'software_type_id.required' => showmessage('000', array('{name}'), array('Software Type Id'), true),
            ];
           
            $validator = Validator::make($request->all(), [
                'software_type_id' => 'required|allow_uuid|string|size:36',
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
                $data = EnSoftwareType::checkforrelation($software_type_id);
                //Add into UserActivityLog
                if ($data['data'])
                {
                    userlog(array('record_id' => $software_type_id, 'data' => $request->all(), 'action' => 'deleted', 'message' => showmessage('118', array('{name}'), array('Software Type'))));
                }
                return response()->json($data);
            }
        }
        catch (\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwaretypedelete", "This function is used to delete software type record.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwaretypedelete", "This function is used to delete software type record.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }

}