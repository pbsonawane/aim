<?php
namespace App\Http\Controllers\cmdb;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EnSoftwareCategory;
use Validator;

class SoftwareCategoryController extends Controller
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
    *This is controller funtion used for Software Categorys.

    * @author       Kavita Daware
    * @access       public
    * @param        software_category_id
    * @param_type   integer
    * @return       JSON
    * @tables       en_software_category
    */
    public function softwarecatgory(Request $request,$software_category_id = null)
    {
        try
        {

            $request['software_category_id'] = $software_category_id;
            $validator = Validator::make($request->all(), [
                'software_category_id'=> 'nullable|allow_uuid|string|size:36'
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
                $totalrecords = EnSoftwareCategory::getsoftwarecategory($software_category_id,$inputdata, true);  
                $result = EnSoftwareCategory::getsoftwarecategory($software_category_id, $inputdata , false);  
                
                $data['data']['records'] = $result->isEmpty() ? NULL : $result;
                $data['data']['totalrecords'] = $totalrecords;                
               
                if ($totalrecords < 0)
                {
                    $data['message']['error'] = showmessage('102', array('{name}'), array('Software Category'));
                    $data['status'] = 'error';
                }
                else
                {
                    $data['message']['success'] = showmessage('101', array('{name}'), array('Software Category'));
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
            save_errlog("softwarecatgory", "This is controller funtion used for Software Categorys.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwarecatgory", "This is controller funtion used for Software Categorys.", $request->all(), $e->getMessage());
            return response()->json($data);
        }     
    }
    /*
    * This is controller funtion used to add the Software Categorys .

    * @author       Kavita Daware
    * @access       public
    * @param        software_category_id, software_category, description
    * @param_type   POST array
    * @return       JSON
    * @tables       en_software_category    
    */
    public function softwarecatgoryadd(Request $request) 
    {
        try
        {
            $messages = [
                'software_category.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_software_category')), true),
                'software_category.allow_alpha_numeric_space_dash_underscore_only' => showmessage('003', array('{name}'), array(trans('label.lbl_software_category')), true),
                'software_category.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_software_category')), true),

                'description.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_desc')), true),
               
                'description.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_desc')), true),
                
            ];
           
            
           $validator = Validator::make($request->all(), [ 
				'software_category_id'=> 'nullable|allow_uuid|string|size:36',
                'software_category' => 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_software_category, software_category, '.$request->input('software_category'), 
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
                $softwaretype_data = EnSoftwareCategory::create($request->all());  
                if(!empty($softwaretype_data['software_category_id']))
                {
                    $software_category_id = $softwaretype_data->software_category_text;
                    $data['data']['insert_id'] = $software_category_id;
                    $data['message']['success'] = showmessage('104', array('{name}'),array('Software Category'));
                    $data['status'] = 'success';
                }
                else
                {
                    $data['data'] = NULL;
                    $data['message']['error'] = showmessage('103', array('{name}'),array('Software Category'));
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
                save_errlog("softwarecatgoryadd", "This is controller funtion used to add the Software Categorys .", $request->all(), $e->getMessage());
                return response()->json($data);
            }
            catch (\Error $e)
            {
                $data['data'] = null;
                $data['message']['error'] = $e->getMessage();
                $data['status'] = 'error';
                save_errlog("softwarecatgoryadd", "This is controller funtion used to add the Software Categorys .", $request->all(), $e->getMessage());
                return response()->json($data);
            
            }     

    }
    /* Provides a window to user to update the softwaretype information.

    * @author       Kavita Daware
    * @access       public
    * @param        software_category_id
    * @param_type   Integer
    * @return       JSON
    * @tables       en_software_category     
    */
    public function softwarecatgoryedit(Request $request)
    {
        try
        {
            //$request['software_category_id'] = $software_category_id;
            $validator = Validator::make($request->all(), [ 
                'software_category_id' => 'required|allow_uuid|string|size:36'
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
                $result = EnSoftwareCategory::getsoftwarecategory($request->input('software_category_id'));  
                
                 $data['data'] = $result->isEmpty() ? NULL : $result;
                
              
                  if($data['data'])
                {
                    $data['message']['success'] = showmessage('101', array('{name}'),array('Software Category'));
                    $data['status'] = 'success';            
                }
                else
                {
                   
                    $data['message']['error'] = showmessage('102', array('{name}'),array('Software Category'));
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
            save_errlog("softwarecatgoryedit", "Provides a window to user to update the softwaretype information.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwarecatgoryedit", "Provides a window to user to update the softwaretype information.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }  
   /*
    * Updates the Software Category information, which is entered by user on Edit Software Category window.

    * @author       Kavita Daware
    * @access       public
    * @param        software_category_id, software_category, description
    * @param_type   POST array
    * @return       JSON
    * @tables       en_software_category    
    */
    public function softwarecatgoryupdate(Request $request)
    {	
        try
        {
    		$messages = [
                'software_category.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_software_category')), true),
                'software_category.allow_alpha_numeric_space_dash_underscore_only' => showmessage('003', array('{name}'), array(trans('label.lbl_software_category')), true),
                'software_category.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_software_category')), true),

                'description.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_desc')), true),
               
                'description.html_tags_not_allowed'       => showmessage('001', array('{name}'), array(trans('label.lbl_desc')), true),
                
            ];
           
          
             $validator = Validator::make($request->all(), [  
			 'software_category_id'=> 'nullable|allow_uuid|string|size:36',
                'software_category' => 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_software_category, software_category, '.$request->input('software_category').', software_category_id,'.$request->input('software_category_id'), 
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

                $software_category_id_uuid = $request->input('software_category_id');
                $software_category_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('software_category_id').'")');
                $request['software_category_id'] = DB::raw('UUID_TO_BIN("'.$request->input('software_category_id').'")');
                $result = EnSoftwareCategory::where('software_category_id', $software_category_id_bin)->first();
            
                if($result)
                {
                    $result->update($request->all());            
                    $result->save();             
                    $data['data'] = NULL;     
                    $data['message']['success'] = showmessage('106', array('{name}'),array('Software Category'));      
                    $data['status'] = 'success'; 
        
                }
                else
                {             
                    $data['data'] = NULL;             
                    $data['message']['error'] = showmessage('101', array('{name}'),array('Software Category'));     
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
            save_errlog("softwarecatgoryupdate", "Updates the Software Category information, which is entered by user on Edit Software Category window.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwarecatgoryupdate", "Updates the Software Category information, which is entered by user on Edit Software Category window.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }

    /* This function is used to delete softwarecatgory record.

    * @author       Kavita Daware
    * @access       public
    * @param        software_category_id
    * @param_type   Integer
    * @return       JSON
    * @tables       en_software_category     
    */

    public function softwarecatgorydelete(Request $request,$software_category_id = null)
    {
        try
        {
            $request['software_category_id'] = $software_category_id;
            $messages = [
                'software_category_id.required' => showmessage('000', array('{name}'), array('Software Category Id'), true),
            ];
           
            $validator = Validator::make($request->all(), [
                'software_category_id' => 'required|allow_uuid|string|size:36',
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
                $data = EnSoftwareCategory::checkforrelation($software_category_id);
                //Add into UserActivityLog
                if ($data['data'])
                {
                    userlog(array('record_id' => $software_category_id, 'data' => $request->all(), 'action' => 'deleted', 'message' => showmessage('118', array('{name}'), array('Software Category'))));
                }
                return response()->json($data);
            }
        }
        catch (\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwarecatgorydelete", "This function is used to delete softwarecatgory record.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
        catch (\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            save_errlog("softwarecatgorydelete", "This function is used to delete softwarecatgory record.", $request->all(), $e->getMessage());
            return response()->json($data);
        }
    }

}