<?php

namespace App\Http\Controllers\cmdb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EnFormTemplateDefault;
use Validator;
use Illuminate\Validation\Rule;


//use Illuminate\Validation\Rule;

class FormTemplateDefaultController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $ModeDevpProdConfig;
    public function __construct()
    {
        //
        $this->ModeDevpProdConfig = "development";
        DB::connection()->enableQueryLog();
      //  $this->enlog = new App\Services\eNsysconfig\Enlog;
    }

    /**
     * This is funtion used list Form template
     * @author Namrata Thakur
     * @access public
     * @package form_template_default
     * @param \Illuminate\Http\Request $request
     * @param UUID $form_templ_id
     * @return json
     *
     */

    public function formtemplatedefault(Request $request,$form_templ_id = null)
    {
        try{
            $request['form_templ_id'] = $form_templ_id;
            $validator = Validator::make($request->all(), [
                'form_templ_id' => 'nullable|allow_uuid|string|size:36',
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
                $totalrecords = EnFormTemplateDefault::getformTemplateDefault($form_templ_id, $inputdata, true);

                //$queries    = DB::getQueryLog();
                //$data['last_query'] = end($queries);

                $result = EnFormTemplateDefault::getformTemplateDefault($form_templ_id, $inputdata, false);

                $data['data']['records'] = $result->isEmpty() ? null : $result;
                $data['data']['totalrecords'] = $totalrecords;
                if ($totalrecords < 1)
                {
                    $data['message']['success'] = showmessage('101', array('{name}'), array(trans('label.lbl_form_template')));
                }
                else
                {
                    $data['message']['success'] = showmessage('102', array('{name}'), array('label.lbl_form_template'));
                }

                $data['status'] = 'success';
                $data['post'] = $request->all();

                return response()->json($data);
            }
        }
        catch(\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            //save_errlog("formtemplatedefault","This is funtion used list Form template.",$request->all(),$data['message']['error']);
            return response()->json($data);
        }
        catch(\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            //save_errlog("formtemplatedefault","This is funtion used list Form template.",$request->all(),$data['message']['error']);
            return response()->json($data);
        }
    }

    //================== formTemplateDefault END ======
    /**
     * This funtion used to generate/create Form using FormBuilder and store Form structure in JSON format.
     * @author Namrata Thakur
     * @access public
     * @package form_template_default
     * @param string $template_name Template Name 
     * @param string $template_title Template Title
     * @param string $details Details 
     * @param string $type Type
     * @param string $description Description
     * @param \Illuminate\Http\Request $request
     * @return json
     *
     */

    public function formtemplatedefaultadd(Request $request)
    {
        try{
            $messages = [
                'template_title.required' => showmessage('000', array('{name}'), array(trans('label.lbl_template_title')), true),
                'template_name.required' => showmessage('000', array('{name}'), array(trans('label.lbl_template_name')), true),
                'type.required' => showmessage('000', array('{name}'), array(trans('label.lbl_type')), true),
                'details.required' => showmessage('000', array('{name}'), array(trans('label.lbl_generatedjson')), true)." ".showmessage('008', array('{name}'), array(''), true),
                'default_template.required' => showmessage('000', array('{name}'), array(trans('label.lbl_default_template')), true),
                'template_title.allow_alpha_numeric_space_dash_underscore_only' => showmessage('000', array('{name}'), array(trans('label.lbl_template_title')), true),
                'template_title.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_template_title')), true),
                'template_name.allow_alphal_numeric_dash_underscore_only' => showmessage('007', array('{name}'), array(trans('label.lbl_template_name')), true),
                'template_name.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_template_name')), true),
                'description.required' => showmessage('000', array('{name}'), array(trans('label.lbl_desc')), true),
                'description.html_tags_not_allowed' => showmessage('001', array('{name}'), array(trans('label.lbl_desc
                    ')), true),

            ];
            $validator = Validator::make($request->all(), [
				'form_templ_id' => 'nullable|allow_uuid|string|size:36',
                'template_title' => 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_form_template_default, template_title, '.$request->input('template_title'),
                'template_name' => 'required|allow_alphal_numeric_dash_underscore_only|composite_unique:en_form_template_default, template_name, '.$request->input('template_name'),
                'type' => 'required',
                'details' => 'required',
                'default_template' => 'required|in:y,n,d',
                'description' => 'required|html_tags_not_allowed',
                /* 'template_title' => 'required',
            'template_name' => 'required',
            'type' => 'required',
            // 'details' => 'required|html_tags_not_allowed',
            'default_template' => 'required|in:y,n,d',
            'description' => 'required'  */
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
                //$request['details'] = json_encode($request['details'], JSON_UNESCAPED_SLASHES);
                $formTemplateDefault = EnFormTemplateDefault::create($request->all());
                if (!empty($formTemplateDefault['form_templ_id']))
                {
                    $data['postdata'] = $request;
                    $data['data']['insert_id'] = $formTemplateDefault->id_text;
                    $data['message']['success'] = showmessage('104', array('{name}'), array(trans('label.lbl_form_template')));
                    $data['status'] = 'success';
                    //Add into UserActivityLog
                    userlog(array('record_id' => $formTemplateDefault->id_text, 'data' => $request->all(), 'action' => 'added', 'message' => showmessage('104', array('{name}'), array(trans('label.lbl_form_template')))));
                }
                else
                {
                    $data['data'] = null;
                    $data['message']['error'] = showmessage('103', array('{name}'), array(trans('label.lbl_form_template')));
                    $data['status'] = 'error';
                }
                return response()->json($data);
            }
        }
        catch(\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            //save_errlog("formtemplatedefaultadd","This funtion used to generate/create Form using FormBuilder and store Form structure in JSON format.",$request->all(),$data['message']['error']);
            return response()->json($data);
        }
        catch(\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            //save_errlog("formtemplatedefaultadd","This funtion used to generate/create Form using FormBuilder and store Form structure in JSON format.",$request->all(),$data['message']['error']);
            return response()->json($data);
        }
    }
    //================== formtemplatedefaultadd END ======

/**
 * This funtion used to delete the form which is generated by FormBuilder.
 * @author Namrata Thakur
 * @access public
 * @package form_template_default
 * @param \Illuminate\Http\Request $request
 * @param  UUID $form_templ_id
 * @return json
 *
 */
    public function formtemplatedefaultdelete(Request $request,$form_templ_id = null)
    {
        try{
            $messages = [
                'form_templ_id.required' => showmessage('000', array('{name}'), array('Form Templ Id'), true),
            ];
            $request['form_templ_id'] = $form_templ_id;
            $validator = Validator::make($request->all(), [
                'form_templ_id' => 'required|allow_uuid|string|size:36',
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
                $form_templ_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('form_templ_id').'")');
                $result = EnFormTemplateDefault::where('form_templ_id', $form_templ_id_bin)->first();
                if ($result)
                {
                    // $device_type->delete();
                    $result->update(array('status' => 'd'));
                    $result->save();
                    $data['data']['deleted_id'] = $request->input('form_templ_id');
                    $data['message']['success'] = showmessage('118', array('{name}'), array(trans('label.lbl_form_template')));
                    $data['status'] = 'success';
                    //Add into UserActivityLog
                    userlog(array('record_id' => $request->input('form_templ_id'), 'data' => $request->all(), 'action' => 'deleted', 'message' => showmessage('118', array('{name}'), array(trans('label.lbl_form_template')))));
                }
                else
                {
                    $data['data'] = null;
                    $data['message']['error'] = showmessage('101', array('{name}'), array(trans('label.lbl_form_template')));
                    $data['status'] = 'error';
                }
                return response()->json($data);
            }
        }
        catch(\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            //save_errlog("formtemplatedefaultdelete","This funtion used to delete the form which is generated by FormBuilder.",$request->all(),$data['message']['error']);
            return response()->json($data);
        }
        catch(\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            //save_errlog("formtemplatedefaultdelete","This funtion used to delete the form which is generated by FormBuilder.",$request->all(),$data['message']['error']);
            return response()->json($data);
        }
    }
    //================== formtemplatedefaultdelete END ======

    //================== formtemplatedefaultclone Start ======
    /**
     * This is controller funtion used to clone the Form Template Default.
     * @author Vikas kumar
     * @access public
     * @package form_template_default
     * @param \Illuminate\Http\Request $request
     * @param UUID $form_templ_id
     * @return json
     *
     */

    public function formtemplatedefaultclone(Request $request,$form_templ_id = null)
    {
        try{
            $request['form_templ_id'] = $form_templ_id;
            $messages = [
                'form_templ_id.required' => showmessage('000', array('{name}'), array('Form Templ Id'), true),
            ];
            $validator = Validator::make($request->all(), [
                'form_templ_id' => 'required|allow_uuid|string|size:36',
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
                //$form_templ_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('form_templ_id').'")');
                $result = EnFormTemplateDefault::getformTemplateDefault($form_templ_id);

                if ($result)
                {
                    $name_exists = false;
                    $template_name = $result[0]->template_name;
                    $template_title = $result[0]->template_title;
                    $type = $result[0]->type;
                    $details = $result[0]->details;
                    $description = $result[0]->description;

                    $clone_template_name_data = $this->checktemplatename($template_name.'copy', $template_title.'-copy');

                    $new_template_array = array('template_name' => $clone_template_name_data['name'], 'template_title' => $clone_template_name_data['title'], 'type' => $type, 'details' => $details, 'description' => $description);

                    $insert_data = EnFormTemplateDefault::create($new_template_array);

                    if ($insert_data)
                    {
                        $data['data'] = null;
                        $data['message']['success'] = showmessage('104', array('{name}'), array(trans('label.lbl_clone')));
                        $data['status'] = 'success';
                        //Add into UserActivityLog
                        userlog(array('record_id' => $insert_data->form_templ_id_text, 'data' => $new_template_array, 'action' => 'add', 'message' => showmessage('104', array('{name}'), array(trans('label.lbl_clone')))));
                    }
                    else
                    {
                        $data['data'] = null;
                        $data['message']['error'] = showmessage('101', array('{name}'), array(trans('label.lbl_clone')));
                        $data['status'] = 'error';
                    }
                }
                else
                {
                    $data['data'] = null;
                    $data['message']['error'] = showmessage('101', array('{name}'), array(trans('label.lbl_clone')));
                    $data['status'] = 'error';
                }
                return response()->json($data);
            }
        }
        catch(\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            //save_errlog("formtemplatedefaultclone","This is controller funtion used to clone the Form Template Default.",$request->all(),$data['message']['error']);
            return response()->json($data);
        }
        catch(\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            //save_errlog("formtemplatedefaultclone","This is controller funtion used to clone the Form Template Default.",$request->all(),$data['message']['error']);
            return response()->json($data);
        }
    }
/**
 * This is controller funtion used in this function formtemplatedefaultclone to clone the Form Template Default.
 * @author Vikas kumar
 * @access public
 * @package form_template_default
 * @param \Illuminate\Http\Request $request string $name, string $title
 * @return json
 *
 */
    private function checktemplatename($name, $title)
    {
        try{
            $return_data = array();
            $name_exists = EnFormTemplateDefault::select('template_name')->where('status', '!=', 'd')->where('template_name', '=', $name)->get();
            if (!$name_exists->isEmpty())
            {
                return $this->checktemplatename($name.'copy', $title.'-copy');
            }
            else
            {
                $return_data['name'] = $name;
                $return_data['title'] = $title;
                return $return_data;
            }
        }        
        catch(\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            //save_errlog("checktemplatename","This is controller funtion used in this function formtemplatedefaultclone to clone the Form Template Default.",$request->all(),$data['message']['error']);
            return response()->json($data);
        }
        catch(\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            //save_errlog("checktemplatename","This is controller funtion used in this function formtemplatedefaultclone to clone the Form Template Default.",$request->all(),$data['message']['error']);
            return response()->json($data);
        }
    }
    // ******* Clone Template Ends here
    /**
     * This function will provide form template default details according to form templ id and data will be provided to edit form.
     * @author Namrata Thakur
     * @access public
     * @package form_template_default
     * @param \Illuminate\Http\Request $request
     * @param UUID $form_templ_id
     * @return json
     *
     */
    public function formtemplatedefaultedit(Request $request,$form_templ_id = null)
    {
        try{
            $request['form_templ_id'] = $form_templ_id;
            $messages = [
                'form_templ_id.required' => showmessage('000', array('{name}'), array('Form Templ Id'), true),
            ];
            $validator = Validator::make($request->all(), [
                'form_templ_id' => 'required|allow_uuid|string|size:36',
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
                $result = EnFormTemplateDefault::getformTemplateDefault($form_templ_id);
                $data['data'] = $result->isEmpty() ? null : $result;
                if ($data['data'])
                {
                    $data['message']['success'] = showmessage('102', array('{name}'), array(trans('label.lbl_form_template')));
                    $data['status'] = 'success';
                }
                else
                {
                    $data['message']['error'] = showmessage('101', array('{name}'), array(trans('label.lbl_form_template')));
                    $data['status'] = 'error';
                }

                return response()->json($data);
            }
        }
        catch(\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            //save_errlog("formtemplatedefaultedit","This function will provide form template default details according to form templ id and data will be provided to edit form.",$request->all(),$data['message']['error']);
            return response()->json($data);
        }
        catch(\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            //save_errlog("formtemplatedefaultedit","This function will provide form template default details according to form templ id and data will be provided to edit form.",$request->all(),$data['message']['error']);
            return response()->json($data);
        }
    }
    //===== formtemplatedefaultedit END ===========

/**
 * This function return Form Template Default details by template_name which generated by FormBuilder to render the Form.
 * @author Namrata Thakur
 * @access public
 * @package form_template_default
 * @param \Illuminate\Http\Request $request string $template_name
 * @return json
 *
 */

    public function formtemplatedefaultebyname(Request $request,$template_name = null)
    {
        try{
            $request['template_name'] = $template_name;
            $messages = [
                'template_name.required' => showmessage('000', array('{name}'), array(trans('label.lbl_template_name')), true),
            ];
            $validator = Validator::make($request->all(), [
                'template_name' => 'required|string',
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
                $result = EnFormTemplateDefault::getformTemplateDefault('', array('searchkeyword' => $template_name));
                $data['data'] = $result->isEmpty() ? null : $result;
                if ($data['data'])
                {
                    $data['message']['success'] = showmessage('102', array('{name}'), array(trans('label.lbl_form_template')));
                    $data['status'] = 'success';
                }
                else
                {
                    $data['message']['error'] = showmessage('101', array('{name}'), array(trans('label.lbl_form_template')));
                    $data['status'] = 'error';
                }

                return response()->json($data);
            }
        }
        catch(\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            //save_errlog("formtemplatedefaultebyname","This function return Form Template Default details by template_name which generated by FormBuilder to render the Form.",$request->all(),$data['message']['error']);
            return response()->json($data);
        }
        catch(\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            //save_errlog("formtemplatedefaultebyname","This function return Form Template Default details by template_name which generated by FormBuilder to render the Form.",$request->all(),$data['message']['error']);
            return response()->json($data);
        }
    }

    /**
     * This function return Form Template Default details by template type which generated by FormBuilder to list in Credentials etc..
     * @author Namrata Thakur
     * @access public
     * @package form_template_default
     * @param \Illuminate\Http\Request $request string $type
     * @return json
     *
     */
    public function formtemplatedefaultebytype(Request $request,$type = null)
    {
        try{
            $request['type'] = $type;
            $messages = [
                'type.required' => showmessage('000', array('{name}'), array(trans('label.lbl_type')), true),
            ];
            $validator = Validator::make($request->all(), [
                'type' => 'required|string',
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
                $result = EnFormTemplateDefault::getformTemplateDefault('', array('searchkeyword' => $type));
                $data['data'] = $result->isEmpty() ? null : $result;
                if ($data['data'])
                {
                    $data['message']['success'] = showmessage('102', array('{name}'), array(trans('label.lbl_form_template')));
                    $data['status'] = 'success';
                }
                else
                {
                    $data['message']['error'] = showmessage('101', array('{name}'), array(trans('label.lbl_form_template')));
                    $data['status'] = 'error';
                }

                return response()->json($data);
            }
        }
        catch(\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            //save_errlog("formtemplatedefaultebytype","This function return Form Template Default details by template type which generated by FormBuilder to list in Credentials etc.",$request->all(),$data['message']['error']);
            return response()->json($data);
        }
        catch(\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            //save_errlog("formtemplatedefaultebytype","This function return Form Template Default details by template type which generated by FormBuilder to list in Credentials etc.",$request->all(),$data['message']['error']);
            return response()->json($data);
        }        
    }

    /**
     * This funtion used to update Form using FormBuilder and update Form structure in JSON format.
     * @author Namrata Thakur
     * @access public
     * @package form_template_default
     * @param UUID $form_templ_id
     * @param string $template_name Template Name 
     * @param string $template_title Template Title
     * @param string $details Details 
     * @param string $type Type
     * @param string $description Description
     * @param \Illuminate\Http\Request $request
     * @return json
     *
     */

    public function formtemplatedefaultupdate(Request $request)
    {
        try{
            $messages = [
                'template_title.required' => showmessage('000', array('{name}'), array(trans('label.lbl_template_title')), true),
                'template_name.required' => showmessage('000', array('{name}'), array(trans('label.lbl_template_name')), true),
                'type.required' => showmessage('000', array('{name}'), array(trans('label.lbl_type')), true),
                'details.required' => showmessage('000', array('{name}'), array('Details'), true)." ".showmessage('008', array('{name}'), array(''), true),
                'default_template.required' => showmessage('000', array('{name}'), array(trans('label.lbl_default_template')), true),
                'template_title.allow_alpha_numeric_space_dash_underscore_only' => showmessage('003', array('{name}'), array(trans('label.lbl_template_title')), true),
                'template_title.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_template_title')), true),
                'template_name.allow_alphal_numeric_dash_underscore_only' => showmessage('007', array('{name}'), array(trans('label.lbl_template_name')), true),
                'template_name.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_template_name')), true),
                'description.required' => showmessage('000', array('{name}'), array(trans('label.desc')), true),
                'description.html_tags_not_allowed' => showmessage('001', array('{name}'), array(trans('label.desc')), true),
            ];
            $validator = Validator::make($request->all(), [
                'form_templ_id' => 'required|allow_uuid|string|size:36',
                'template_title' => 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_form_template_default, template_title, '.$request->input('template_title').', form_templ_id,'.$request->input('form_templ_id'),
                'template_name' => 'required|allow_alphal_numeric_dash_underscore_only|composite_unique:en_form_template_default, template_name, '.$request->input('template_name').', form_templ_id,'.$request->input('form_templ_id'),
                'type' => 'required',
                //'details' => 'required|html_tags_not_allowed',
                'default_template' => 'required|in:y,n,d',
                'description' => 'required|html_tags_not_allowed',
            ], $messages);
            if ($validator->fails())
            {

                $error = $validator->errors();
                $data['data'] = null;

                // $queries    = DB::getQueryLog();
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
                $result = EnFormTemplateDefault::where('form_templ_id', $id_bin)->first();

                if ($result)
                {
                    $result->update($request->all());
                    $result->save();
                    $data['data'] = null;
                    $data['message']['success'] = showmessage('106', array('{name}'), array(trans('label.lbl_form_template')));
                    $data['status'] = 'success';
                    //Add into UserActivityLog
                    userlog(array('record_id' => $id_uuid, 'data' => $request->all(), 'action' => 'updated', 'message' => showmessage('106', array('{name}'), array(trans('label.lbl_form_template')))));
                }
                else
                {
                    $data['data'] = null;
                    $data['message']['error'] = showmessage('101', array('{name}'), array(trans('label.lbl_form_template')));
                    $data['status'] = 'error';
                }
                return response()->json($data);
            }
        }
        catch(\Exception $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            //save_errlog("formtemplatedefaultupdate","This funtion used to update Form using FormBuilder and update Form structure in JSON format.",$request->all(),$data['message']['error']);
            return response()->json($data);
        }
        catch(\Error $e)
        {
            $data['data'] = null;
            $data['message']['error'] = $e->getMessage();
            $data['status'] = 'error';
            //save_errlog("formtemplatedefaultupdate","This funtion used to update Form using FormBuilder and update Form structure in JSON format.",$request->all(),$data['message']['error']);
            return response()->json($data);
        }
    }
    //===== formtemplatedefaultupdate END ===========
} // Class End
