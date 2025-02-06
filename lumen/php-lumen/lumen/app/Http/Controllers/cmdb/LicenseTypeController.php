<?php
namespace App\Http\Controllers\cmdb;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EnLicenseType;
use Validator;

class LicenseTypeController extends Controller
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
     /**This is controller funtion used for License Types.

    * @author       Kavita Daware
    * @access       public
    * @param        license_type_id
    * @param_type   integer
    * @return       JSON
    * @tables       en_license_type
    */
    public function licensetype(Request $request,$license_type_id = null)
    {
        try
        {

            $request['license_type_id'] = $license_type_id;
            $validator = Validator::make($request->all(), [
		'license_type_id'=> 'nullable|allow_uuid|string|size:36'
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
                $totalrecords = EnLicenseType::getlicensetype($license_type_id,$inputdata, true);  
                $result = EnLicenseType::getlicensetype($license_type_id, $inputdata , false);  
                
                $data['data']['records'] = $result->isEmpty() ? NULL : $result;
                $data['data']['totalrecords'] = $totalrecords;                
               
                if ($totalrecords < 0)
                {
                    $data['message']['error'] = showmessage('102', array('{name}'), array('License Type'));
                    $data['status'] = 'error';
                }
                else
                {
                    $data['message']['success'] = showmessage('101', array('{name}'), array('License Type'));
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
            save_errlog("licensetype", "This is controller funtion used for License Types.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("licensetype", "This is controller funtion used for License Types.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }
     /*
    * This is controller funtion used to add the License Types type.

    * @author       Kavita Daware
    * @access       public
    * @param        license_type_id, license_type, description,installation_allow,is_perpetual,is_free
    * @param_type   POST array
    * @return       JSON
    * @tables       en_license_type    
    */
    public function licensetypeadd(Request $request) 
    {
        try
        {

    		$messages = [
                'license_type.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_license_type')), true),
                'license_type.allow_alpha_numeric_space_dash_underscore_only' => showmessage('003', array('{name}'), array(trans('label.lbl_license_type')), true),
                'license_type.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_license_type')), true),

                'installation_allow.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_installation_allow')), true),
    			'is_perpetual.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_is_perpetual')), true),
               
            ];
           $validator = Validator::make($request->all(), [ 
				'license_type_id'=> 'nullable|allow_uuid|string|size:36',
                'license_type' => 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_license_type, license_type, '.$request->input('license_type'), 
                'installation_allow' => 'required' , 
                'is_perpetual' => 'required' , 
                //'is_free' => 'required' , 
                
                
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
                $licensetype_data = EnLicenseType::create($request->all());  
                if(!empty($licensetype_data['license_type_id']))
                {
                    $license_type_id = $licensetype_data->license_type_text;
                    $data['data']['insert_id'] = $license_type_id;
                    $data['message']['success'] = showmessage('104', array('{name}'),array('License Type'));
                    $data['status'] = 'success';
                }
                else
                {
                    $data['data'] = NULL;
                    $data['message']['error'] = showmessage('103', array('{name}'),array('License Type'));
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
            save_errlog("softwareadd", "This is controller funtion used to add softwares.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwareadd", "This is controller funtion used to add softwares.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }
    /* Provides a window to user to update the licensetype information.

    * @author       Kavita Daware
    * @access       public
    * @param        license_type_id
    * @param_type   Integer
    * @return       JSON
    * @tables       en_license_type     
    */
    public function licensetypeedit(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [ 
                'license_type_id' => 'required|allow_uuid|string|size:36'
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
                $result = EnLicenseType::getlicensetype($request->input('license_type_id'));  
                
                 $data['data'] = $result->isEmpty() ? NULL : $result;
                
              
                  if($data['data'])
                {
                    $data['message']['success'] = showmessage('101', array('{name}'),array('License Type'));
                    $data['status'] = 'success';            
                }
                else
                {
                   
                    $data['message']['error'] = showmessage('102', array('{name}'),array('License Type'));
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
            save_errlog("licensetypeedit", "Provides a window to user to update the licensetype information.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("licensetypeedit", "Provides a window to user to update the licensetype information.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }  
   /*
    * Updates the License Type information, which is entered by user on Edit License Type window.

    * @author       Kavita Daware
    * @access       public
    * @param        license_type_id, license_type, description,installation_allow,is_perpetual,is_free
    * @param_type   POST array
    * @return       JSON
    * @tables       en_license_type    
    */
    public function licensetypeupdate(Request $request)
    {
        try
        {

    		$messages = [
                'license_type.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_license_type')), true),
                'license_type.allow_alpha_numeric_space_dash_underscore_only' => showmessage('003', array('{name}'), array(trans('label.lbl_license_type')), true),
                'license_type.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_license_type')), true),

                'installation_allow.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_installation_allow')), true),
    			'is_perpetual.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_is_perpetual')), true),
               
            ];
    		
            $validator = Validator::make($request->all(), [ 
				'license_type_id' => 'required|allow_uuid|string|size:36',
                'license_type' => 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_license_type, license_type, '.$request->input('license_type').', license_type_id,'.$request->input('license_type_id'), 
                'installation_allow' => 'required' , 
                'is_perpetual' => 'required' , 
                //'is_free' => 'required' , 
                
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

                $license_type_id_uuid = $request->input('license_type_id');
                $license_type_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('license_type_id').'")');
                $request['license_type_id'] = DB::raw('UUID_TO_BIN("'.$request->input('license_type_id').'")');
                $request['installation_allow'] = $request->input('installation_allow');
                $result = EnLicenseType::where('license_type_id', $license_type_id_bin)->first();
               
                if($result)
                {
                   
                    $result->update($request->all());            
                    $result->save();             
                    $data['data'] = NULL;     
                    $data['message']['success'] = showmessage('106', array('{name}'),array('License Type'));      
                    $data['status'] = 'success'; 
        
                }
                else
                {             
                    $data['data'] = NULL;             
                    $data['message']['error'] = showmessage('101', array('{name}'),array('License Type'));     
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
            save_errlog("licensetypeupdate", "Updates the License Type information, which is entered by user on Edit License Type window.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("licensetypeupdate", "Updates the License Type information, which is entered by user on Edit License Type window.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }

    /* This function is used to delete licensetype record.

    * @author       Kavita Daware
    * @access       public
    * @param        license_type_id
    * @param_type   Integer
    * @return       JSON
    * @tables       en_license_type     
    */

    public function licensetypedelete(Request $request,$license_type_id = null)
    {
        try
        {
            $request['license_type_id'] = $license_type_id;
            $messages = [
                'license_type_id.required' => showmessage('000', array('{name}'), array('License Type Id'), true),
            ];
           
            $validator = Validator::make($request->all(), [
                'license_type_id' => 'required|allow_uuid|string|size:36',
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
                $data = EnLicenseType::checkforrelation($license_type_id);
                //Add into UserActivityLog
                if ($data['data'])
                {
                    userlog(array('record_id' => $license_type_id, 'data' => $request->all(), 'action' => 'deleted', 'message' => showmessage('118', array('{name}'), array('License Type'))));
                }
                return response()->json($data);
            }
        }
        catch (\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwareadd", "This is controller funtion used to add softwares.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwareadd", "This is controller funtion used to add softwares.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }

}