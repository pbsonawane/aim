<?php
namespace App\Http\Controllers\cmdb;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EnSoftwareManufacturer;
use Validator;

class SoftwareManufacturerController extends Controller
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
    *This is controller funtion used for Software Manufacturers.

    * @author       Kavita Daware
    * @access       public
    * @param        software_manufacturer_id
    * @param_type   integer
    * @return       JSON
    * @tables       en_software_manufacturer
    */
    public function softwaremanufacturer(Request $request,$software_manufacturer_id = null)
    {
        try
        {

            $request['software_manufacturer_id'] = $software_manufacturer_id;
            $validator = Validator::make($request->all(), [
                'software_manufacturer_id'=> 'nullable|allow_uuid|string|size:36'
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
                $totalrecords = EnSoftwareManufacturer::getsoftwaremanufacturer($software_manufacturer_id,$inputdata, true);  
                $result = EnSoftwareManufacturer::getsoftwaremanufacturer($software_manufacturer_id, $inputdata , false);  
                
                $data['data']['records'] = $result->isEmpty() ? NULL : $result;
                $data['data']['totalrecords'] = $totalrecords;                
               
                if ($totalrecords < 0)
                {
                    $data['message']['error'] = showmessage('102', array('{name}'), array('Software Manufacturer'));
                    $data['status'] = 'error';
                }
                else
                {
                    $data['message']['success'] = showmessage('101', array('{name}'), array('Software Manufacturer'));
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
            save_errlog("softwaremanufacturer", "This is controller funtion used for Software Manufacturers.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwaremanufacturer", "This is controller funtion used for Software Manufacturers.", $request->all(), $e->getMessage());
            return response()->json($data);    
        }
    }
     /*
    * This is controller funtion used to add the Software Manufacturers .

    * @author       Kavita Daware
    * @access       public
    * @param        software_manufacturer_id, software_manufacturer, description
    * @param_type   POST array
    * @return       JSON
    * @tables       en_software_manufacturer    
    */
    public function softwaremanufactureradd(Request $request) 
    {
        try
        {
            $messages = [
                'software_manufacturer.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_software_manufacturer')), true),
                'software_manufacturer.allow_alpha_numeric_space_dash_underscore_only' => showmessage('003', array('{name}'), array(trans('label.lbl_software_manufacturer')), true),
                'software_manufacturer.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_software_manufacturer')), true),

                'description.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_desc')), true),
               
                'description.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_desc')), true),
                
            ];
    		$validator = Validator::make($request->all(), [
				'software_manufacturer_id'=> 'nullable|allow_uuid|string|size:36',
                'software_manufacturer' => 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_software_manufacturer, software_manufacturer, '.$request->input('software_manufacturer'),  
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
                $softwaremanufacturer_data = EnSoftwareManufacturer::create($request->all());  
                if(!empty($softwaremanufacturer_data['software_manufacturer_id']))
                {
                    $software_manufacturer_id = $softwaremanufacturer_data->software_manufacturer_text;
                    $data['data']['insert_id'] = $software_manufacturer_id;
                    $data['message']['success'] = showmessage('104', array('{name}'),array('Software Manufacturer'));
                    $data['status'] = 'success';
                }
                else
                {
                    $data['data'] = NULL;
                    $data['message']['error'] = showmessage('103', array('{name}'),array('Software Manufacturer'));
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
            save_errlog("softwaremanufactureradd", "This is controller funtion used to add the Software Manufacturers.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwaremanufactureradd", "This is controller funtion used to add the Software Manufacturers.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }
    /* Provides a window to user to update the software manufacturer information.

    * @author       Kavita Daware
    * @access       public
    * @param        software_manufacturer_id
    * @param_type   Integer
    * @return       JSON
    * @tables       en_software_manufacturer     
    */
    public function softwaremanufactureredit(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [ 
                'software_manufacturer_id' => 'required|allow_uuid|string|size:36'
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
                $result = EnSoftwareManufacturer::getsoftwaremanufacturer($request->input('software_manufacturer_id'));  
                
                 $data['data'] = $result->isEmpty() ? NULL : $result;
                
              
                  if($data['data'])
                {
                    $data['message']['success'] = showmessage('101', array('{name}'),array('Software Manufacturer'));
                    $data['status'] = 'success';            
                }
                else
                {
                   
                    $data['message']['error'] = showmessage('102', array('{name}'),array('Software Manufacturer'));
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
            save_errlog("softwaremanufactureredit", "Provides a window to user to update the software manufacturer information.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwaremanufactureredit", "Provides a window to user to update the software manufacturer information.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }  
   /*
    * Updates the Software Manufacturer information, which is entered by user on Edit Software Manufacturer window.

    * @author       Kavita Daware
    * @access       public
    * @param        software_manufacturer_id, software_manufacturer, description
    * @param_type   POST array
    * @return       JSON
    * @tables       en_software_manufacturer    
    */
    public function softwaremanufacturerupdate(Request $request)
    {
        try
        {
    		$messages = [
                'software_manufacturer.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_software_manufacturer')), true),
                'software_manufacturer.allow_alpha_numeric_space_dash_underscore_only' => showmessage('003', array('{name}'), array(trans('label.lbl_software_manufacturer')), true),
                'software_manufacturer.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_software_manufacturer')), true),

                'description.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_desc')), true),
               
                'description.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_desc')), true),
                
            ];
    		
            $validator = Validator::make($request->all(), [  
				'software_manufacturer_id' => 'required|allow_uuid|string|size:36',
                'software_manufacturer' => 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_software_manufacturer, software_manufacturer, '.$request->input('software_manufacturer').', software_manufacturer_id,'.$request->input('software_manufacturer_id'),
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

                $software_manufacturer_id_uuid = $request->input('software_manufacturer_id');
                $software_manufacturer_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('software_manufacturer_id').'")');
                $request['software_manufacturer_id'] = DB::raw('UUID_TO_BIN("'.$request->input('software_manufacturer_id').'")');
                $result = EnSoftwareManufacturer::where('software_manufacturer_id', $software_manufacturer_id_bin)->first();
            
                if($result)
                {
                    $result->update($request->all());            
                    $result->save();             
                    $data['data'] = NULL;     
                    $data['message']['success'] = showmessage('106', array('{name}'),array('Software Manufacturer'));      
                    $data['status'] = 'success'; 
        
                }
                else
                {             
                    $data['data'] = NULL;             
                    $data['message']['error'] = showmessage('101', array('{name}'),array('Software Manufacturer'));     
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
                save_errlog("softwaremanufacturerupdate", "Updates the Software Manufacturer information, which is entered by user on Edit Software Manufacturer window.", $request->all(), $e->getMessage());
                return response()->json($data);
            }
            catch (\Error $e)
            {
                $data['data'] = null;
                $data['message']['error'] = $e->getMessage();
                $data['status'] = 'error';
                save_errlog("softwaremanufacturerupdate", "Updates the Software Manufacturer information, which is entered by user on Edit Software Manufacturer window.", $request->all(), $e->getMessage());
                return response()->json($data);
            
            } 
    }

    /* This function is used to delete software manufacturer record.

    * @author       Kavita Daware
    * @access       public
    * @param        software_manufacturer_id
    * @param_type   Integer
    * @return       JSON
    * @tables       en_software_manufacturer     
    */

    public function softwaremanufacturerdelete(Request $request,$software_manufacturer_id = null)
    {
        try
        {
            $request['software_manufacturer_id'] = $software_manufacturer_id;
            $messages = [
                'software_manufacturer_id.required' => showmessage('000', array('{name}'), array('Software Manufacturer Id'), true),
            ];
           
            $validator = Validator::make($request->all(), [
                'software_manufacturer_id' => 'required|allow_uuid|string|size:36',
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
                $data = EnSoftwareManufacturer::checkforrelation($software_manufacturer_id);
                //Add into UserActivityLog
                if ($data['data'])
                {
                    userlog(array('record_id' => $software_manufacturer_id, 'data' => $request->all(), 'action' => 'deleted', 'message' => showmessage('118', array('{name}'), array('Software Manufacturer'))));
                }
                return response()->json($data);
            }
        }
        catch (\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwaremanufacturerdelete", "This function is used to delete software manufacturer record.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwaremanufacturerdelete", "This function is used to delete software manufacturer record.", $request->all(), $e->getMessage());
            return response()->json($data);
        } 
    }
}