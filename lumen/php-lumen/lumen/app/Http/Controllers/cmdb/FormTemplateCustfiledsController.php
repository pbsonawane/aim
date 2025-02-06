<?php
namespace App\Http\Controllers\cmdb;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EnFormTemplateCustfileds;
use Validator;
use Illuminate\Validation\Rule;

class FormTemplateCustfiledsController extends Controller
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
    *This is controller funtion used for Form Template Default present.

    * @author       Namrata Thakur
    * @access       public
    * @param        URL : form_templ_id
    * @param_type   integer
    * @return       JSON
    * @tables       form_template_default
    */
  
    public function formtemplatecustomfields(Request $request,$form_templ_id = NULL)
    {
        $request['form_templ_id'] = $form_templ_id;
        $validator = Validator::make($request->all(), [
            'form_templ_id' => 'nullable|allow_uuid|string|size:36'
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
            $totalrecords = EnFormTemplateCustfileds::getformTemplateCustomfields($form_templ_id,$inputdata, true);  


            //$queries    = DB::getQueryLog();
            //$data['last_query'] = end($queries); 

            $result = EnFormTemplateCustfileds::getformTemplateCustomfields($form_templ_id, $inputdata , false);

            $data['data']['records'] = $result->isEmpty() ? NULL : $result;
            $data['data']['totalrecords'] = $totalrecords;
           	if($totalrecords < 1)   
                $data['message']['success']= showmessage('101', array('{name}'),array('Form Template'));
            else
                $data['message']['success']= showmessage('102', array('{name}'),array('Form Template'));
            $data['status'] = 'success';                    
                        $data['post'] = $request->all(); 

            return response()->json($data); 
        }
    }
    
    //================== formTemplateCustomfields END ====== 

    /*
    * This is controller funtion used to accept the values for new Form Template Default. This function is called when user enters new values for Form Template Default and submits that form.

    * @author       Namrata Thakur
    * @access       public
    * @param        custom_fields, status
    * @param_type   POST array
    * @return       JSON
    * @tables       form_template_default    
    */
  
    public function formtemplatecustomfieldsadd(Request $request) 
    {
        $messages = [    
            'custom_fields.html_tags_not_allowed' => 'Form Template HTML Tags not Allowed'
        ];
        $validator = Validator::make($request->all(), [ 
			'form_templ_id' => 'nullable|allow_uuid|string|size:36',
            'custom_fields' => 'required|html_tags_not_allowed',        
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
    		$formTemplateCustomfields = EnFormTemplateCustfileds::create($request->all()); 
    		if(!empty($formTemplateCustomfields['form_templ_id']))
    		{
                $data['data']['insert_id'] = $formTemplateCustomfields->id_text;
    			$data['message']['success'] = showmessage('104', array('{name}'),array('Form Template'));
    			$data['status'] = 'success';
                //Add into UserActivityLog
              //userlog(array('record_id' => $formTemplateCustomfields->id_text, 'data' => $request->all(), 'action' => 'added', 'message' => showmessage('104', array('{name}'),array('Form Template'))));
    		}
    		else
    		{
    			$data['data'] = NULL;
    			$data['message']['error'] = showmessage('103', array('{name}'),array('Form Template'));
    			$data['status'] = 'error';
    		}
            return response()->json($data);
        } 
    }
    //================== formtemplatecustomfieldsadd END ====== 

    /*
    * This is controller funtion used to delete the Form Template Default.  

    * @author       Namrata Thakur
    * @access       public
    * @param        URL : form_templ_id
    * @param_type   integer
    * @return       JSON
    * @tables       form_template_default      
    */

    public function formtemplatecustomfieldsdelete(Request $request,$form_templ_id = NULL)
    {   
        $request['form_templ_id'] = $form_templ_id;     
        $validator = Validator::make($request->all(), [
                'form_templ_id' => 'required|allow_uuid|string|size:36'
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
            $form_templ_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('form_templ_id').'")');
            $result = EnFormTemplateCustfileds::where('form_templ_id',$form_templ_id_bin)->first();
            if($result)
            {
               // $device_type->delete(); 
                $result->update(array('status' => 'd'));            
                $result->save();                
                $data['data']['deleted_id'] = $request->input('form_templ_id');
                $data['message']['success'] = showmessage('118', array('{name}'),array('Form Template')); 
                $data['status'] = 'success';
                //Add into UserActivityLog
               // userlog( array('record_id' => $result['form_templ_id'], 'data' => $request->all(), 'action' => 'deleted', 'message' => showmessage('118', array('{name}'),array('Device Type'))));
            }
            else
            {
                $data['data'] = NULL;
                $data['message']['error'] = showmessage('101', array('{name}'),array('Form Template')); 
                $data['status'] = 'error';                             
            } 
            return response()->json($data);
        } 
    }
    //================== formtemplatecustomfieldsdelete END ======

    /*  
    * Provides a window to user to update the Form Template Default information.

    * @author       Namrata Thakur
    * @access       public
    * @param        URL : form_templ_id
    * @param_type   Integer
    * @return       JSON
    * @tables       form_template_default     
    */

     
    public function formtemplatecustomfieldsedit(Request $request,$form_templ_id = NULL)
    {
        $request['form_templ_id'] = $form_templ_id;
        $validator = Validator::make($request->all(), [
            'form_templ_id' => 'required|allow_uuid|string|size:36'
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
            $result = EnFormTemplateCustfileds::getformTemplateCustomfields($form_templ_id);
            $data['data'] = $result->isEmpty() ? NULL : $result;
            if($data['data'])
            {
                $data['message']['success'] = showmessage('102', array('{name}'),array('Form Template'));
                $data['status'] = 'success';            
            }
    		else
    		{
                $data['message']['error'] = showmessage('101', array('{name}'),array('Form Template'));
                $data['status'] = 'error';          
            }

            return response()->json($data); 
        }
    }     
    //===== formtemplatecustomfieldsedit END ===========

    /*
    * Updates the Form Template Default information, which is entered by user on Edit Form Template Default window.

    * @author       Namrata Thakur
    * @access       public
    * @param        form_templ_id, custom_fields, status
    * @param_type   POST array
    * @return       JSON
    * @tables       form_template_default     
    */
  
    public function formtemplatecustomfieldsupdate(Request $request)
    {        
        $messages = [    
            'custom_fields.html_tags_not_allowed' => 'Form Template HTML Tags not Allowed'
        ];
        $validator = Validator::make($request->all(), [            
            'custom_fields' => 'required|html_tags_not_allowed',  
            'form_templ_id' => 'required|allow_uuid|string|size:36'      
        ], $messages);  
        if($validator->fails())
        {

            $error = $validator->errors(); 
            $data['data'] = null;
            
            //$queries    = DB::getQueryLog();
            //$data['last_query'] = end($queries); 

            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data); 
        }
        else
        {         
            $id_uuid = $request->input('form_templ_id');
            $id_bin = DB::raw('UUID_TO_BIN("'.$request->input('form_templ_id').'")');     
            $request['form_templ_id'] = DB::raw('UUID_TO_BIN("'.$request->input('form_templ_id').'")');  
            $result = EnFormTemplateCustfileds::where('form_templ_id',$id_bin)->first();

            if($result)
            {
                $result->update($request->all());            
                $result->save();              
                $data['data'] = NULL;     
                $data['message']['success'] = showmessage('106', array('{name}'),array('Form Template'));         
                $data['status'] = 'success';
                //Add into UserActivityLog
               // userlog( array('record_id' => $id_uuid, 'data' => $request->all(), 'action' => 'updated', 'message' => showmessage('106', array('{name}'),array('Form Template'))));
            }
            else
            {             
                $data['data'] = NULL;             
                $data['message']['error'] = showmessage('101', array('{name}'),array('Form Template'));
                $data['status'] = 'error'; 
            }   
            return response()->json($data); 
        }
    }
    //===== formtemplatecustomfieldsupdate END ===========
   
}// Class End