<?php
namespace App\Http\Controllers\emailtemplate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EnEmailTemplate;
use App\Models\EnEmailQuote;
use Validator;

class EmailTemplateController extends Controller
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
    * This is controller funtion used to get the email templates

    * @author       Snehal C
    * @access       public
    * @param_type   template_id, POST array
    * @return       JSON
    * @tables       en_email_template
    */
    public function emailtemplates(Request $request,$template_id = null)
    {

        $requset['template_id'] = $template_id;
        $validator = Validator::make($request->all(), [
            'template_id' => 'nullable|allow_uuid|string|size:36',
        ]);
        if ($validator->fails())
        {
            apilog('-----list in validator fails----'); die;
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';

        }
        else
        {
            $inputdata = $request->all();
            apilog('---- Sending Email------');
            apilog(json_encode($inputdata));
            $inputdata['searchkeyword'] = trim(_isset($inputdata, 'searchkeyword'));
            $inputdata['template_key'] = trim(_isset($inputdata, 'template_key'));
            $totalrecords = EnEmailTemplate::getemailtemplates($template_id, $inputdata, true);
            //apilog("totalrecords---".json_encode($totalrecords));
            $queries    = DB::getQueryLog();
            $last_query = end($queries);
            apilog(json_encode($last_query));
            
            $result = EnEmailTemplate::getemailtemplates($template_id, $inputdata, false);

            $data['data']['records'] = $result->isEmpty() ? null : $result;
            $data['data']['totalrecords'] = $totalrecords;
            if ($totalrecords < 0)
           {
                $data['message']['error'] = showmessage('102', array('{name}'), array('Emailtemplate'));
                $data['status'] = 'error';
            }
            else
            {
                $data['message']['success'] = showmessage('101', array('{name}'), array('Emailtemplate'));
                $data['status'] = 'success';
            }
           
        }
        return response()->json($data);
    }
    /*
    * This is controller funtion used to add the contracts type.

    * @author       Snehal C
    * @access       public
    * @param_type   POST array
    * @return       JSON
    * @tables       en_email_template
    */
    public function emailtemplateadd(Request $request) 
    {
        $messages = [
            'template_name.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_template_name')), true),
            'template_name.allow_alpha_numeric_space_dash_underscore_only'       => showmessage('003', array('{name}'), array(trans('label.lbl_template_name')), true),
			'template_name.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_template_name')), true),
            'template_key.required' => showmessage('000', array('{name}'), array(trans('label.lbl_template_key')), true),
            'template_key.allow_alphal_numeric_dash_underscore_only' => showmessage('007', array('{name}'), array(trans('label.lbl_template_key')), true),
            'template_key.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_template_key')), true),
            'template_category.required' => showmessage('000', array('{name}'), array(trans('label.lbl_template_category')), true),
            'subject.required' => showmessage('000', array('{name}'), array(trans('label.lbl_subject')), true),
			'subject.html_tags_not_allowed' => showmessage('001', array('{name}'), array(trans('label.lbl_subject')), true),
            'email_body.required' => showmessage('000', array('{name}'), array(trans('label.lbl_email_body')), true),
        ];
        $validator = Validator::make($request->all(), [  
			'template_id' => 'nullable|allow_uuid|string|size:36',
            'template_name' => 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_email_template,template_name, '.$request->input('template_name'), 
            'template_key' => 'required|allow_alphal_numeric_dash_underscore_only|composite_unique:en_email_template, template_key, '.$request->input('template_key'), 
            'template_category' => 'required',
            'subject' => 'required|html_tags_not_allowed',
            'email_body' => 'required' ,
            
        ],$messages);
		//Added Validator for email
		$validator->after(function ($validator)
        {
            $request      = request();
			if($request->input('configure_email_id') == 'y'){
				if ($request->input('email_ids')!="")
				{
					$email_ids = $request->has('email_ids')  ? $request->input('email_ids') : "";
					if($email_ids != "")
					{
						$email_arr = explode(",",$email_ids);
						foreach($email_arr as $val)
						{
							if(!filter_var($val, FILTER_VALIDATE_EMAIL)) 
							{
								$validator->errors()->add('email_ids.required',showmessage('014', array('{name}'), array($val), true));
							}
						}
					}  
				}else{
					  $validator->errors()->add('email_ids.required',showmessage('000', array('{name}'), array('Email Ids'), true));
				}
			}
        });
        //End Validator for email
        if ($validator->fails())
        {

            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
        }
        else
        {
			
				$template_data = EnEmailTemplate::create($request->all());

				 /*$queries    = DB::getQueryLog();
				   $last_query = end($queries);
					 echo 'Query<pre>';
				  apilog(json_encode($last_query);*/

				if (!empty($template_data['template_id']))
				{
					$template_id = $template_data->template_id_text;
					$data['data']['insert_id'] = $template_id;
					$data['message']['success'] = showmessage('104', array('{name}'), array('Template'));
					$data['status'] = 'success';
					//Add into UserActivityLog
					// userlog(array('record_id' => $vendor_data->vendor_id_text, 'data' => $request->all(), 'action' => 'added', 'message' => showmessage('104', array('{name}'),array('Vendor'))));
				}
				else
				{
					$data['data'] = null;
					$data['message']['error'] = showmessage('103', array('{name}'), array('Template'));
					$data['status'] = 'error';
				} 
			}
     
        return response()->json($data);
    }


    /*
    * This is controller funtion used to get the email templates categories

    * @author       Snehal C
    * @access       public
    * @param_type   template_id, POST array
    * @return       JSON
    * @tables       en_email_template
    */

    public function emailtemplatecategory(Request $request,$template_id = null)
    {

        $requset['template_id'] = $template_id;
        $validator = Validator::make($request->all(), [
            'template_id' => 'nullable|allow_uuid|string|size:36',
        ]);
        if ($validator->fails())
        {
            apilog('-----list in validator fails----'); die;
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';

        }
        else
        {
            apilog('-------------in succes-----------');
            $inputdata = $request->all();
            $inputdata['searchkeyword'] = trim(_isset($inputdata, 'searchkeyword'));
            $totalrecords = EnEmailTemplate::getemailtemplatescategory($template_id, $inputdata, true);

            /*$queries    = DB::getQueryLog();
            $last_query = end($queries);
            apilog(json_encode($last_query));die;*/
            
            $result = EnEmailTemplate::getemailtemplatescategory($template_id, $inputdata, false);

            $data['data']['records'] = $result->isEmpty() ? null : $result;
            $data['data']['totalrecords'] = $totalrecords;

            if ($totalrecords < 1)
            {
                $data['message']['error'] = showmessage('102', array('{name}'), array('Emailtemplate'));
                $data['status'] = 'error';
            }
            else
            {
                $data['message']['success'] = showmessage('101', array('{name}'), array('Emailtemplate'));
                $data['status'] = 'success';
            }
           
        }
        return response()->json($data);

    }

    /*
    * This is controller funtion used to add the email quote

    * @author       Snehal C
    * @access       public
    * @param_type   POST array
    * @return       JSON
    * @tables       en_email_template
    */
    public function emailquoteadd(Request $request)
    {
        $messages = [
            'quotes.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_quote')), true),
        ];
        $validator = Validator::make($request->all(), [  
            'quotes' => 'required',  
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

            $template_data = EnEmailQuote::create($request->all());

           /*  $queries    = DB::getQueryLog();
               $last_query = end($queries);
             
              apilog(json_encode($last_query);*/

               apilog("----------------QUOTE---------------");

            if (!empty($template_data['template_id']))
            {
                $template_id = $template_data->template_id_text;
                $data['data']['insert_id'] = $template_id;
                $data['message']['success'] = showmessage('104', array('{name}'), array('Quote'));
                $data['status'] = 'success';
                //Add into UserActivityLog
                // userlog(array('record_id' => $vendor_data->vendor_id_text, 'data' => $request->all(), 'action' => 'added', 'message' => showmessage('104', array('{name}'),array('Vendor'))));
            }
            else
            {
                $data['data'] = null;
                $data['message']['error'] = showmessage('103', array('{name}'), array('Quote'));
                $data['status'] = 'error';
            }  
        }
        return response()->json($data);
    }

     /*
    * This is controller funtion used to get the email quotes

    * @author       Snehal C
    * @access       public
    * @param_type   POST array
    * @return       JSON
    * @tables       en_email_template
    */

    public function emailquotes(Request $request,$quote_id = null)
    {

        $requset['quote_id'] = $quote_id;
        $validator = Validator::make($request->all(), [
            'quote_id' => 'nullable|allow_uuid|string|size:36',
        ]);
        if ($validator->fails())
        {
            apilog('-----list in validator fails----'); die;
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';

        }
        else
        {
            apilog('-------------in succes-----------');
            $inputdata = $request->all();
            $inputdata['searchkeyword'] = trim(_isset($inputdata, 'searchkeyword'));
            $totalrecords = EnEmailQuote::getemailquotes($quote_id, $inputdata, true);

            /*$queries    = DB::getQueryLog();
            $last_query = end($queries);
            apilog(json_encode($last_query));die;*/
            
            $result = EnEmailQuote::getemailquotes($quote_id, $inputdata, false);

            $data['data']['records'] = $result->isEmpty() ? null : $result;
            $data['data']['totalrecords'] = $totalrecords;

            if ($totalrecords < 1)
            {
                $data['message']['error'] = showmessage('102', array('{name}'), array('Emailtemplate'));
                $data['status'] = 'error';
            }
            else
            {
                $data['message']['success'] = showmessage('101', array('{name}'), array('Emailtemplate'));
                $data['status'] = 'success';
            }
           
        }
        return response()->json($data);
    }

    /* Provides a window to user to update the email template information.

     * @author       Snehal C
     * @access       public
     * @param        URL : template_id
     * @param_type   Integer
     * @return       JSON
     * @tables       en_email_template
     */
    public function emailtemplateedit(Request $request,$template_id = null)
    {
        //$request['vendor_id'] = $vendor_id;
        $validator = Validator::make($request->all(), [
            'template_id' => 'required|allow_uuid|string|size:36',
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
            $result = EnEmailTemplate::getemailtemplates($request->input('template_id'));

            $data['data'] = $result->isEmpty() ? null : $result;

            if ($data['data'])
            {
                $data['message']['success'] = showmessage('102', array('{name}'), array('Vendor'));
                $data['status'] = 'success';
            }
            else
            {

                $data['message']['error'] = showmessage('101', array('{name}'), array('Vendor'));
                $data['status'] = 'error';
            }
        }
        return response()->json($data);
    }

    /*
     * Updates the email template information, which is entered by user on Edit email tmeplate window.

     * @author       Snehal C
     * @access       public
     * @param        $request
     * @param_type   POST array
     * @return       JSON
     * @tables       en_email_template
     */
    public function emailtemplateupdate(Request $request)
    {

        $template_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('template_id').'")');
        
        $messages = [
		
            'template_name.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_template_name')), true),
            'template_name.allow_alpha_numeric_space_dash_underscore_only'       => showmessage('003', array('{name}'), array(trans('label.lbl_template_name')), true),
			'template_name.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_template_name')), true),
            'template_category.required' => showmessage('000', array('{name}'), array(trans('label.lbl_template_category')), true),
			'template_category.html_tags_not_allowed' => showmessage('001', array('{name}'), array(trans('label.lbl_template_category')), true),
            'subject.required' => showmessage('000', array('{name}'), array(trans('label.lbl_subject')), true),
			'subject.html_tags_not_allowed' => showmessage('001', array('{name}'), array(trans('label.lbl_subject')), true),
            'email_body.required' => showmessage('000', array('{name}'), array(trans('label.lbl_email_body')), true),
        ];
        $validator = Validator::make($request->all(), [  
			'template_id' => 'required|allow_uuid|string|size:36',
            'template_name' => 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_email_template, template_name, '.$request->input('template_name').', template_id,'.$request->input('template_id'), 
            'template_category' => 'required|html_tags_not_allowed',
            'subject' => 'required|html_tags_not_allowed',
            'email_body' => 'required',
            
        ],$messages);
		//Added Validator for email
		$validator->after(function ($validator)
        {
            $request      = request();
			if($request->input('configure_email_id') == 'y'){
				if ($request->input('email_ids')!="")
				{
					$email_ids = $request->has('email_ids')  ? $request->input('email_ids') : "";
					if($email_ids != "")
					{
						$email_arr = explode(",",$email_ids);
						foreach($email_arr as $val)
						{
							if(!filter_var($val, FILTER_VALIDATE_EMAIL)) 
							{
								$validator->errors()->add('email_ids.required',showmessage('014', array('{name}'), array($val), true));
							}
						}
					}  
				}else{
					  $validator->errors()->add('email_ids.required',showmessage('000', array('{name}'), array('Email Ids'), true));
				}
			}
        });
        //End Validator for email
        if ($validator->fails())
        {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
        }
        else
        {
            $template_id_uuid = $request->input('template_id');
            $template_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('template_id').'")');
            $request['template_id'] = DB::raw('UUID_TO_BIN("'.$request->input('template_id').'")');
            $result = EnEmailTemplate::where('template_id', $template_id_bin)->first();

            if ($result)
            {
                $result->update($request->all());
                $result->save();
                $data['data'] = null;
                $data['message']['success'] = showmessage('106', array('{name}'), array('Template'));
                $data['status'] = 'success';
                // userlog(array('record_id' => $vendor_id_uuid, 'data' => $request->all(), 'action' => 'updated', 'message' => showmessage('106', array('{name}'),array('Designation'))));

            }
            else
            {
                $data['data'] = null;
                $data['message']['error'] = showmessage('101', array('{name}'), array('Vendor'));
                $data['status'] = 'error';
            }
        }
        return response()->json($data);
    }

    /* This is controller funtion used to delete the Email template.

     * @author       Snehal C
     * @access       public
     * @param        template_id
     * @param_type   integer
     * @return       JSON
     * @tables       en_email_template
     */

    public function emailtemplatedelete(Request $request,$template_id = null)
    {
        $request['template_id'] = $template_id;
        $validator = Validator::make($request->all(), [
            'template_id' => 'required|string|size:36',
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
            $data = EnEmailTemplate::checkforrelation($template_id);
            //Add into UserActivityLog
            if ($data['data'])
            {
                //userlog(array('record_id' => $vendor_id, 'data' => $request->all(), 'action' => 'deleted', 'message' => 'Record Deleted Successfully'));
            }
            return response()->json($data);

        }
    }

    public function emailtemplatestatusupdate(Request $request)
    {

        apilog('---in template status change----');
        $template_id_uuid = $request->input('id');
        apilog('template_id---'.$template_id_uuid);
        $data = EnEmailTemplate::changetemplatestatus($template_id_uuid, $request->input('status'));
        //Add into UserActivityLog
        return response()->json($data);
    }


}
  