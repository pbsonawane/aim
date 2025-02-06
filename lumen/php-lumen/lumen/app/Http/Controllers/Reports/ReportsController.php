<?php
namespace App\Http\Controllers\Reports;
use App\Http\Controllers\Controller;
use App\Jobs\GenrateReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Models\EnReports;
use App\Models\EnReportModules;
use App\Models\EnReportNotifications;
use App\Models\EnImportNotifications;
use Illuminate\Support\Facades\Lang;
use App\Services\RemoteApi;

use Validator;

class ReportsController extends Controller
{
  
  /**
  * Create a new controller instance.
  *
  * @return void
  */
  public function __construct()
  {
    DB::connection()->enableQueryLog();
    $this->remote_api = new RemoteApi();     
  }
  /**
  * This controller function is implemented to get Reports.
  * @author Shadab Khan
  * @access public
  * @package reports
  * @param \Illuminate\Http\Request $request
  * @param UUID $report_id
  * @return json
  * @tables  en_reports 
  */
  public function reports(Request $request)
  {
    try
    {
      $report_id = $request->input('report_id');
      $validator                = Validator::make($request->all(), [
          'report_id'=> 'nullable|allow_uuid|string|size:36'
      ]);
      if($validator->fails())
      {
        $error                    = $validator->errors(); 
        $data['data']             = null;
        $data['message']['error'] = $error;
        $data['status']           = 'error';
        return response()->json($data); 
      }
      else
      {          
        $inputdata                  = $request->all();
        $inputdata['searchkeyword'] = trim(_isset($inputdata,'searchkeyword'));   
        $totalrecords   = EnReports::getreport($report_id,$inputdata, true);  
        $result         = EnReports::getreport($report_id, $inputdata , false);  
        
        $data['data']['records']      = $result->isEmpty() ? NULL : $result;
        $data['data']['totalrecords'] = $totalrecords;                
        //$last_query     = DB::getQueryLog();
        //$data['data']['last_query']   = $last_query; 
        if ($totalrecords < 1)
        {
          $data['message']['success'] = showmessage('102', array('{name}'), array(trans('label.lbl_reports')));
          $data['status']           = 'success';
        }
        else
        {
          $data['message']['success'] = showmessage('101', array('{name}'), array(trans('label.lbl_reports')));
          $data['status']             = 'success';
        }
      }
    }
    catch(\Exception $e)
    {
      $data['data']               = null;
      $data['message']['error']   = $e->getMessage();
      $data['status']             = 'error';
      save_errlog("reports","This controller function is implemented to get Reports.",$request->all(),$e->getMessage());
    }
    catch(\Error $e)
    {
      $data['data']   = null;
      $data['message']['error'] = $e->getMessage();
      $data['status'] = 'error';
      save_errlog("reports","This controller function is implemented to get Reports.",$request->all(),$e->getMessage());
    }
    finally
    {
      return response()->json($data);
    }
  }
  /**
  * This is controller funtion used to add the Reports.
  * @author Shadab Khan
  * @access public
  * @package reports
  * @param \Illuminate\Http\Request $request
  * @return json
  * @tables  en_reports 
  */
  public function reportsadd(Request $request) 
  {
    try
    {
      $messages = [
        'report_name.required' => showmessage('000', array('{name}'), array(trans('label.lbl_report_name')), true),
        'report_name.allow_alpha_numeric_space_dash_underscore_only' => showmessage('003', array('{name}'), array(trans('label.lbl_report_name')), true),
        'report_name.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_report_name')), true),
        'module.required' => showmessage('000', array('{name}'), array(trans('label.lbl_module')), true),
        'report_cat_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_report_category')), true),
        'filter_fields.required' => trans('messages.msg_choose_field'),
      ];
      $validator = Validator::make($request->all(), [
        'report_id'   => 'nullable|allow_uuid|string|size:36',
        'report_name' => 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_reports, report_name, '.$request->input('report_name'),
        'report_cat_id'       => 'required|allow_uuid|string|size:36',
        'module'              => 'required|html_tags_not_allowed',
        'filter_fields'       => 'required',
      ],$messages);

      $validator->after(function ($validator)
      {
        $request      = request();
        $filters      = json_decode($request['filters'], true );
        $filters_arr  = array();

        if ($request->input('filter_date_field')!="" && $request->input('filter_date_value')=="" && $request->input('filter_date_range')=="")
        {
          $validator->errors()->add('filter_date_range.required',showmessage('000', array('{name}'), array(trans('label.filter_date_range')), true));
        }

        if ($request->input('filter_date_value')!="" && $request->input('filter_date_field')=="")
        {
          $validator->errors()->add('filter_date_field.required',showmessage('000', array('{name}'), array(trans('label.filter_date_field')), true));
        }elseif ($request->input('filter_date_range')!="" && $request->input('filter_date_field')=="") 
        {
          $validator->errors()->add('filter_date_field.required',showmessage('000', array('{name}'), array(trans('label.filter_date_field')), true));
        }
        if($filters)
        {
          if(isset($filters['filter_column']) && isset($filters['criteria']) && isset($filters['criteria_value']) && isset($filters['criteria_match']))
          {
            if(((isset($filters['filter_column'][0]) && $filters['filter_column'][0]!="")) || ((isset($filters['criteria'][0]) && $filters['criteria'][0]!="")) || ((isset($filters['criteria_value'][0]) && !empty($filters['criteria_value'][0]))) || ((isset($filters['criteria_match'][0]) && $filters['criteria_match'][0]!="")))
            {
              foreach($filters['filter_column'] as $key=>$filter_column)
              {
                $filters_arr[$key]['filter_column']  = $filter_column;
                $filters_arr[$key]['criteria']       = $filters['criteria'][$key];
                $filters_arr[$key]['criteria_value'] = $filters['criteria_value'][$key];
                $filters_arr[$key]['criteria_match'] = $filters['criteria_match'][$key];

                $emptyArr = array();
                if($filter_column=="")
                {
                  $emptyArr[] = trans('label.lbl_column_name');
                }

                if($filters['criteria'][$key] == "")
                {
                  $emptyArr[] = trans('label.lbl_criteria');
                }

                if(empty($filters['criteria_value'][$key]) || $filters['criteria_value'][$key]==null)
                {
                  $emptyArr[] = trans('label.lbl_value');
                }
                if($filters['criteria_match'][$key] == "")
                {
                  $emptyArr[] = trans('label.lbl_match');
                }
                if(!empty($emptyArr))
                {
                  $emptyStr = implode(",", $emptyArr);
                  $validator->errors()->add('Column '.($key+1), showmessage('000', array('{name}'), array("#".($key+1)." ".$emptyStr), true));
                }
              }
            }
          }
          $request['filters'] = $filters_arr;
        }
      });           
      if ($validator->fails())
      {
        $error          = $validator->errors();
        $data['data']   = null;
        $data['message']['error'] = $error;
        $data['status'] = 'error';
      }
      else
      { 
        $request['report_cat_id'] = DB::raw('UUID_TO_BIN("'.$request->input('report_cat_id').'")');
        $filter_fields = $request['filter_fields'];   
        if (is_array($filter_fields) && count($filter_fields)>0) 
        {
          $request['filter_fields'] = json_encode($request['filter_fields']);
        }
        else
        {
          $request['filter_fields'] = NULL;
        }
        if (is_array($request['filters']) && count($request['filters'])>0) 
        {
          $request['filters'] = json_encode($request['filters']);
        }
        else
        {
          $request['filters'] = NULL;
        }
        $request['user_id']    = DB::raw('UUID_TO_BIN("'.$request->input('loggedinuserid').'")');
        $report_data = EnReports::create($request->all());  
        if(!empty($report_data['report_id']))
        {
          $report_id                  = $report_data->report_id_text;
          $data['data']['insert_id']  = $report_id;
          $data['message']['success'] = showmessage('104', array('{name}'),array(trans('label.lbl_reports')));
          $data['status']             = 'success';
        }
        else
        {
          $data['data']   = NULL;
          $data['message']['error'] = showmessage('103', array('{name}'),array(trans('label.lbl_reports')));
          $data['status'] = 'error';
        }
      }
    }
    catch(\Exception $e)
    {
      $data['data']               = null;
      $data['message']['error']   = $e->getMessage();
      $data['status']             = 'error';
      save_errlog("reportsadd","This controller function is implemented to add the Reports.",$request->all(),$e->getMessage());
    }
    catch(\Error $e)
    {
      $data['data']             = null;
      $data['message']['error'] = $e->getMessage();
      $data['status']           = 'error';
      save_errlog("reportsadd","This controller function is implemented to add the Reports.",$request->all(),$e->getMessage());
    }
    finally
    {
      return response()->json($data);
    }
  }
 /**
  * This function provides a window to user to update the Reports information.
  * @author Shadab Khan
  * @access public
  * @package reports
  * @param \Illuminate\Http\Request $request
  * @return json
  * @tables  en_reports 
  */
  public function reportsedit(Request $request)
  {
    try
    {
      $validator = Validator::make($request->all(), [ 
        'report_id' => 'required|string|size:36'
      ]);
      if ($validator->fails())
      {
        $error          = $validator->errors();
        $data['data']   = null;
        $data['message']['error'] = $error;
        $data['status'] = 'error';
      }
      else
      {    
        $result       = EnReports::getreport($request->input('report_id'));  
        $data['data'] = $result->isEmpty() ? NULL : $result;
        if($data['data'])
        {
          $data['message']['success'] = showmessage('102', array('{name}'),array(trans('label.lbl_reports')));
          $data['status'] = 'success';            
        }
        else
        { 
          $data['message']['error'] = showmessage('101', array('{name}'),array(trans('label.lbl_reports')));
          $data['status'] = 'error';          
        }
      }
    }
    catch(\Exception $e)
    {
      $data['data']               = null;
      $data['message']['error']   = $e->getMessage();
      $data['status']             = 'error';
      save_errlog("reportsedit","This controller function is implemented to edit the Reports.",$request->all(),$e->getMessage());
    }
    catch(\Error $e)
    {
      $data['data']   = null;
      $data['message']['error'] = $e->getMessage();
      $data['status'] = 'error';
      save_errlog("reportsedit","This controller function is implemented to edit the Reports.",$request->all(),$e->getMessage());
    }
    finally
    {
      return response()->json($data);
    }
  }
 /**
  * This is controller funtion used to update the Reports.
  * @author Shadab Khan
  * @access public
  * @package reports
  * @param \Illuminate\Http\Request $request
  * @return json
  * @tables  en_reports 
  */
  public function reportsupdate(Request $request)
  {
    try
    {
      $messages = [
        'report_name.required' => showmessage('000', array('{name}'), array(trans('label.lbl_report_name')), true),
        'report_name.allow_alpha_numeric_space_dash_underscore_only' => showmessage('003', array('{name}'), array(trans('label.lbl_report_name')), true),
        'report_name.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_report_name')), true),
        'module.required' => showmessage('000', array('{name}'), array(trans('label.lbl_module')), true),
        'report_cat_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_report_category')), true),
        'filter_fields.required' => trans('messages.msg_choose_field'),
      ];
      $validator = Validator::make($request->all(), [
        'report_id'   => 'nullable|allow_uuid|string|size:36',
        'report_name' => 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_reports, report_name, '.$request->input('report_name').', report_id,'.$request->input('report_id'),
        'report_cat_id'       => 'required|allow_uuid|string|size:36',
        'module'              => 'required|html_tags_not_allowed',
        'filter_fields'       => 'required',
      ],$messages);
      
      $validator->after(function ($validator)
      {
        $request      = request();
        $filters      = json_decode($request['filters'], true );
        $filters_arr  = array();

        if ($request->input('filter_date_field')!="" && $request->input('filter_date_value')=="" && $request->input('filter_date_range')=="")
        {
          $validator->errors()->add('filter_date_range.required',showmessage('000', array('{name}'), array(trans('label.filter_date_range')), true));
        }

        if ($request->input('filter_date_value')!="" && $request->input('filter_date_field')=="")
        {
          $validator->errors()->add('filter_date_field.required',showmessage('000', array('{name}'), array(trans('label.filter_date_field')), true));
        }elseif ($request->input('filter_date_range')!="" && $request->input('filter_date_field')=="") 
        {
          $validator->errors()->add('filter_date_field.required',showmessage('000', array('{name}'), array(trans('label.filter_date_field')), true));
        }

        if($filters)
        {
          if(isset($filters['filter_column']) && isset($filters['criteria']) && isset($filters['criteria_value']) && isset($filters['criteria_match']))
          {
            if(((isset($filters['filter_column'][0]) && $filters['filter_column'][0]!="")) || ((isset($filters['criteria'][0]) && $filters['criteria'][0]!="")) || ((isset($filters['criteria_value'][0]) && !empty($filters['criteria_value'][0]))) || ((isset($filters['criteria_match'][0]) && $filters['criteria_match'][0]!="")))
            {
              foreach($filters['filter_column'] as $key=>$filter_column)
              {
                  $filters_arr[$key]['filter_column']  = $filter_column;
                  $filters_arr[$key]['criteria']       = $filters['criteria'][$key];
                  $filters_arr[$key]['criteria_value'] = $filters['criteria_value'][$key];
                  $filters_arr[$key]['criteria_match'] = $filters['criteria_match'][$key];
                  $emptyArr = array();
                  if($filter_column=="")
                  {
                    $emptyArr[] = trans('label.lbl_column_name');
                  }
                  if($filters['criteria'][$key] == "")
                  {
                    $emptyArr[] = trans('label.lbl_criteria');
                  }
                  if(empty($filters['criteria_value'][$key]) || $filters['criteria_value'][0]==null)
                  {
                    $emptyArr[] = trans('label.lbl_value');
                  }
                  if($filters['criteria_match'][$key] == "")
                  {
                    $emptyArr[] = trans('label.lbl_match');
                  }
                  if(!empty($emptyArr))
                  {
                    $emptyStr = implode(",", $emptyArr);
                    $validator->errors()->add('Column '.($key+1), showmessage('000', array('{name}'), array("#".($key+1)." ".$emptyStr), true));
                  }
              }
            }
          }
          $request['filters'] = $filters_arr;
        }
      });           
      if ($validator->fails())
      {
        $error          = $validator->errors();
        $data['data']   = null;
        $data['message']['error'] = $error;
        $data['status'] = 'error';
      }
      else
      { 
        $reports_id_uuid = $request->input('report_id');
        $reports_id_bin  = DB::raw('UUID_TO_BIN("'.$request->input('report_id').'")');
        $request['report_id']   = DB::raw('UUID_TO_BIN("'.$request->input('report_id').'")');
        $request['report_cat_id'] = DB::raw('UUID_TO_BIN("'.$request->input('report_cat_id').'")');
        $filter_fields = $request['filter_fields'];   
        if (is_array($filter_fields) && count($filter_fields)>0) 
        {
          $request['filter_fields'] = json_encode($request['filter_fields']);
        }
        else
        {
          $request['filter_fields'] = NULL;
        }
        if (is_array($request['filters']) && count($request['filters'])>0) 
        {
          $request['filters'] = json_encode($request['filters']);
        }
        else
        {
          $request['filters'] = NULL;
        }
        if ($request->input('filter_date_field')==null)
          $request['filter_date_field'] = NULL;
        if ($request->input('filter_date_value')==null)
          $request['filter_date_value'] = NULL;
        if ($request->input('filter_date_range')==null)
          $request['filter_date_range'] = NULL;
        $request['user_id']    = DB::raw('UUID_TO_BIN("'.$request->input('loggedinuserid').'")');

        if ($request->input('share_report') !="")
        {
          $request['share_report'] = "y";
        }
        else
        {
          $request['share_report'] = "n";
        }

        $result = EnReports::where('report_id', $reports_id_bin)->first();
        if($result)
        {
          $result->update($request->all());            
          $result->save();             
          $data['data']   = NULL;     
          $data['message']['success'] = showmessage('106', array('{name}'),array(trans('label.lbl_reports')));      
          $data['status'] = 'success'; 

        }
        else
        {             
          $data['data']   = NULL;             
          $data['message']['error'] = showmessage('102', array('{name}'),array(trans('label.lbl_reports')));     
          $data['status'] = 'error'; 
        } 
      }
    }
    catch(\Exception $e)
    {
      $data['data']               = null;
      $data['message']['error']   = $e->getMessage();
      $data['status']             = 'error';
      save_errlog("reportsupdate","This is controller funtion used to update the Reports.",$request->all(),$e->getMessage());
    }
    catch(\Error $e)
    {
      $data['data']   = null;
      $data['message']['error'] = $e->getMessage();
      $data['status'] = 'error';
      save_errlog("reportsupdate","This is controller funtion used to update the Reports.",$request->all(),$e->getMessage());
    }
    finally
    {
      return response()->json($data);
    }
  }
  /**
  * This is controller funtion used to delete the Reports.
  * @author Shadab Khan
  * @access public
  * @package reports
  * @param \Illuminate\Http\Request $request
  * @return json
  * @tables  en_reports 
  */
  public function reportsdelete(Request $request)
  {
    try
    {
      $messages = [
        'report_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_reports')), true),
      ];
      $validator = Validator::make($request->all(), [
          'report_id' => 'required|allow_uuid|string|size:36',
      ], $messages);
      if ($validator->fails())
      {
        $error          = $validator->errors();
        $data['data']   = null;
        $data['message']['error'] = $error;
        $data['status'] = 'error';
        return response()->json($data);
      }
      else
      {
        $reports_id_uuid = $request->input('report_id');
        $reports_id_bin  = DB::raw('UUID_TO_BIN("'.$request->input('report_id').'")');
        $request['report_id']  = DB::raw('UUID_TO_BIN("'.$request->input('report_id').'")');
        $result                    = EnReports::where('report_id', $reports_id_bin)->first();
        
        if($result)
        {
          $result->update(['status' => 'd']);
          $result->save();             
          $data['data']   = NULL;     
          $data['message']['success'] = showmessage('118', array('{name}'),array(trans('label.lbl_reports')));      
          $data['status'] = 'success'; 
        }
        else
        {             
          $data['data']   = NULL;             
          $data['message']['error'] = showmessage('102', array('{name}'),array(trans('label.lbl_reports')));     
          $data['status'] = 'error'; 
        }
      }
    }
    catch(\Exception $e)
    {
      $data['data']               = null;
      $data['message']['error']   = $e->getMessage();
      $data['status']             = 'error';
      save_errlog("reportsdelete","This is controller funtion used to delete the Reports.",$request->all(),$e->getMessage());
    }
    catch(\Error $e)
    {
      $data['data']   = null;
      $data['message']['error'] = $e->getMessage();
      $data['status'] = 'error';
      save_errlog("reportsdelete","This is controller funtion used to delete the Reports.",$request->all(),$e->getMessage());
    }
    finally
    {
      return response()->json($data);
    }
  }
  /**
  * This controller function is implemented to get Report modules.
  * @author Shadab Khan
  * @access public
  * @package reports
  * @param \Illuminate\Http\Request $request
  * @param UUID $module_id
  * @return json
  * @tables  en_report_modules 
  */
  public function getreportmodules(Request $request)
  {
    try
    {
      $validator                = Validator::make($request->all(), [
          'module_id'=> 'nullable|allow_uuid|string|size:36'
      ]);
      if($validator->fails())
      {
        $error                    = $validator->errors(); 
        $data['data']             = null;
        $data['message']['error'] = $error;
        $data['status']           = 'error';
        return response()->json($data); 
      }
      else
      {          
        $inputdata                  = $request->all();
        $module_id                  = isset($inputdata['module_id']) ? $inputdata['module_id'] : null;
        $inputdata['searchkeyword'] = trim(_isset($inputdata,'searchkeyword'));

        $totalrecords   = EnReportModules::getmodules($module_id,$inputdata, true);  
        $result         = EnReportModules::getmodules($module_id, $inputdata , false);  
        
        $data['data']['records']      = $result->isEmpty() ? NULL : $result;
        $data['data']['totalrecords'] = $totalrecords;                
       
        if ($totalrecords < 1)
        {
          $data['message']['success'] = showmessage('102', array('{name}'), array(trans('label.lbl_reports')));
          $data['status']           = 'success';
        }
        else
        {
          $data['message']['success'] = showmessage('101', array('{name}'), array(trans('label.lbl_reports')));
          $data['status']             = 'success';
        }
      }
    }
    catch(\Exception $e)
    {
      $data['data']               = null;
      $data['message']['error']   = $e->getMessage();
      $data['status']             = 'error';
      save_errlog("getreportmodules","This controller function is implemented to get Report Modules.",$request->all(),$e->getMessage());
    }
    catch(\Error $e)
    {
      $data['data']   = null;
      $data['message']['error'] = $e->getMessage();
      $data['status'] = 'error';
      save_errlog("getreportmodules","This controller function is implemented to get Report Modules.",$request->all(),$e->getMessage());
    }
    finally
    {
      return response()->json($data);
    }
  }
  /**
  * This controller function is implemented to get Report Details.
  * @author Shadab Khan
  * @access public
  * @package reports
  * @param \Illuminate\Http\Request $request
  * @param UUID $report_id
  * @return json
  * @tables  en_reports 
  */
  public function reportsdetail(Request $request)
  {    
    try
    {
      $validator                = Validator::make($request->all(), [
          'report_id'=> 'required|allow_uuid|string|size:36'
      ]);
      if($validator->fails())
      {
        $error                    = $validator->errors(); 
        $data['data']             = null;
        $data['message']['error'] = $error;
        $data['status']           = 'error';
        return response()->json($data); 
      }
      else
      {
        $reports_id_uuid = $request->input('report_id');
                

        $reports_id_bin  = DB::raw('UUID_TO_BIN("'.$request->input('report_id').'")');
        $query = DB::table('en_reports')   
                ->select(
                    DB::raw('BIN_TO_UUID(report_id) AS report_id'),
                    DB::raw('BIN_TO_UUID(report_cat_id) AS report_cat_id'),
                    DB::raw('BIN_TO_UUID(user_id) AS user_id'),
                        'report_name',
                        'module',
                        'filter_fields',
                        'filter_date_field',
                        'filter_date_value',
                        'filter_date_range',
                        'filters',
                        'details')
                ->where('en_reports.report_id', '=', $reports_id_bin)
                ->where('en_reports.status', '!=', 'd');  




        //user Acessiblity
        $inputdata  = $request->all();
        $user_id    = isset($inputdata['loggedinuserid']) ? $inputdata['loggedinuserid'] : '';
        $is_admin   = isset($inputdata['ENMASTERADMIN']) ? $inputdata['ENMASTERADMIN'] : '';

       /* $data['data'] = $reports_id_bin;
                $data['message']['success'] = "success";
                $data['status'] = 'success';
                return response()->json($data);*/

                
        if($is_admin !="" && $is_admin !="y")
        {   
          if ($user_id != "") 
          {
            $query->where(function ($query) use ($user_id)
            {
              return $query->where('en_reports.user_id', '=', DB::raw('UUID_TO_BIN("'.$user_id.'")'))->orWhere('en_reports.share_report', '=','y');
            });
              
          }
        }
        $result         = $query->first();

        //$last_query   = DB::getQueryLog();
        //$data['data']['last_query']   = $last_query;   
        $inputdata['searchkeyword'] = trim(_isset($inputdata,'searchkeyword'));                
        if ($result) 
        {
          $user_id            = $result->user_id;
          $module             = $result->module;
          $sanitize_result    =  $cifields  = $cifilterfields = array();
          if ($module != "")
          {
            $moduleresult = EnReportModules::select('orignal_fields')->where('module_key', $module)->first();
            $sanitize_fields = json_decode($moduleresult->orignal_fields,true);
            $filter_fields   = json_decode($result->filter_fields,true);
            
            //$filter_fields   = array_intersect_key( $sanitize_fields , array_flip( $filter_fields ) );
            if (is_array($filter_fields) && is_array($sanitize_fields)) 
            {
              foreach($filter_fields AS $key) 
              {

                if (!isset($sanitize_fields[$key])) 
                {
                  $cifields[] = $key;
                }
                $fields_common[$key] = isset($sanitize_fields[$key]) ? $sanitize_fields[$key] : $key;
              }
              $filter_fields = array_values($fields_common);
            }
            else
            {
              $filter_fields = array();
            }
            $filters            = json_decode($result->filters,true);            
            if (is_array($filters) && count($filters)>0) 
            {
              foreach($filters as $key => $filter)
              {
                if (isset($filter['filter_column']) && !empty($filter['filter_column']))
                {
                  $data['data']['sanitize_fields']['key'][$key] = $key;
                  if (!isset($sanitize_fields[$key])) 
                  {
                    $cifilterfields[] = $filter['filter_column'];
                  }
                  $filters[$key]['filter_column'] = isset($sanitize_fields[$filter['filter_column']]) ? $sanitize_fields[$filter['filter_column']]: $filter['filter_column'];                  
                }
              }
            }
            apilog('--------filters----------');
            apilog(json_encode($filters));

            $filter_date_field  = $result->filter_date_field;

            apilog('--------filter_date_field----------');
            apilog(json_encode($filter_date_field));

            if (isset($sanitize_fields[$filter_date_field]))
            {
              $filter_date_field = $sanitize_fields[$filter_date_field];
              apilog('--------sanitize_filter_date_field----------');
              apilog(json_encode($filter_date_field));
            }

            $sanitize_result['filter_fields']     = $filter_fields; 
            $sanitize_result['filters']           = $filters;
            $sanitize_result['filter_date_field'] = $filter_date_field;
            $sanitize_result['filter_date_value'] = $result->filter_date_value;
            $sanitize_result['filter_date_range'] = $result->filter_date_range;
            $sanitize_result['details']           = $result->details;
          }
          $filter_fields      = json_decode($result->filter_fields,true);
          $filter_date_field  = $result->filter_date_field;
          $filter_date_value  = $result->filter_date_value;
          $filter_date_range  = $result->filter_date_range;
          $from_time = "";
          $to_time   = "";

          if ($filter_date_value !="") 
          {
            $filter_date = EnReports::getDate($filter_date_value);
            if (is_array($filter_date) && count($filter_date)>0) 
            {
              $from_time   = $filter_date['start'];
              $to_time     = $filter_date['end'];
            }
            else
            {
              $from_time   = $filter_date;
              $to_time     = date("Y-m-d H:i");
            }
          }elseif ($filter_date_range !="") 
          {//
            $date_range = explode(" - ", $filter_date_range);
            
            $from_time = isset($date_range[0]) && $date_range[0] !="" ? date("Y-m-d H:i",strtotime($date_range[0])) :  "0000-00-00 00:00:00";
            
            $to_time = isset($date_range[1]) && $date_range[1] !="" ? date("Y-m-d H:i",strtotime($date_range[1])) :  "0000-00-00 00:00:00";
          }

          if ($module !="" && $module=="CONTRACT") 
          {
            $inputdata                  = $request->all();
            $inputdata['searchkeyword'] = trim(_isset($inputdata,'searchkeyword'));   
            $totalrecords   = EnReports::getcontractreport($sanitize_result,$inputdata, true);  
            $result         = EnReports::getcontractreport($sanitize_result,$inputdata , false);
            $tableheaders   = $this->get_table_headers("CONTRACT",$filter_fields);
            //$last_query     = DB::getQueryLog();
            $data['data']['from_time'] = $from_time;
            $data['data']['to_time']   = $to_time;
            $data['data']['tableheaders'] = $tableheaders;
            $data['data']['records']      = $result->isEmpty() ? NULL : $result;
            $data['data']['totalrecords'] = $totalrecords;
            //$data['data']['last_query']   = $last_query;         
            if ($totalrecords < 1)
            {
              $data['message']['success']   = showmessage('102', array('{name}'), array(trans('label.lbl_reports')));
              $data['status']               = 'success';
            }
            else
            {
              $data['message']['success']   = showmessage('101', array('{name}'), array(trans('label.lbl_reports')));
              $data['status']               = 'success';
            }
          }
          if ($module !="" && $module=="SOFTWARE") 
          {
            $inputdata                  = $request->all();
            $inputdata['searchkeyword'] = trim(_isset($inputdata,'searchkeyword'));   
            $totalrecords   = EnReports::getsoftwarereport($sanitize_result,$inputdata, true);  
            $result         = EnReports::getsoftwarereport($sanitize_result,$inputdata , false);
            $tableheaders   = $this->get_table_headers("SOFTWARE",$filter_fields);

            $data['data']['from_time'] = $from_time;
            $data['data']['to_time']   = $to_time;

            $data['data']['tableheaders'] = $tableheaders;
            $data['data']['records']      = $result->isEmpty() ? NULL : $result;
            $data['data']['totalrecords'] = $totalrecords;
            //$last_query     = DB::getQueryLog();
            //$data['data']['last_query']   = $last_query;                
            if ($totalrecords < 1)
            {
              $data['message']['success']   = showmessage('102', array('{name}'), array(trans('label.lbl_reports')));
              $data['status']               = 'success';
            }
            else
            {
              $data['message']['success']   = showmessage('101', array('{name}'), array(trans('label.lbl_reports')));
              $data['status']               = 'success';
            }
          }
          if ($module !="" && $module=="PURCHASE") 
          {
            /*$jwttoken             = genratejwttoken($user_id);
            $inputdata            = $request->all();
            $getbvlocdatacenter   = $this->getbvlocdatacenter($jwttoken);
            $inputdata['bvdcloc'] = $getbvlocdatacenter;*/
            
            $inputdata['searchkeyword'] = trim(_isset($inputdata,'searchkeyword'));   
            $totalrecords   = EnReports::getpurchasereport($sanitize_result,$inputdata, true);  
            $result         = EnReports::getpurchasereport($sanitize_result,$inputdata , false);  
            // pr_req_date
            // pr_due_date
            // $result = json_decode($result, true);
            // for($i=0;$i<count($result);$i++)
            // {       
            //   if(isset($result[$i]['pr_req_date']))  
            //   {
            //     $result[$i]['pr_req_date'] = date("d-m-Y", strtotime($result[$i]['pr_req_date']));
            //   }    
            //   if(isset($result[$i]['pr_due_date']))  
            //   {
            //     $result[$i]['pr_due_date'] = date("d-m-Y", strtotime($result[$i]['pr_due_date']));
            //   }                  
            // }           
            // $result = (object) $result; 
            // 
            // $data['data'] = $result;
            // $data['status'] = 'success';
            // $data['message']['success'] = 'success';
            // return $data;  
            $tableheaders   = $this->get_table_headers("PURCHASE",$filter_fields);
            $data['data']['from_time']    = $from_time;
            $data['data']['to_time']      = $to_time;
            $data['data']['tableheaders'] = $tableheaders;
            // $data['data']['records']      = $result->isEmpty() ? NULL : $result;
            $data['data']['records']      = $result;
            $data['data']['totalrecords'] = $totalrecords;
            //$last_query     = DB::getQueryLog();
            //$data['data']['last_query']   = $last_query;                
            if ($totalrecords < 1)
            {
              $data['message']['success']   = showmessage('102', array('{name}'), array(trans('label.lbl_reports')));
              $data['status']               = 'success';
            }
            else
            {
              $data['message']['success']   = showmessage('101', array('{name}'), array(trans('label.lbl_reports')));
              $data['status']               = 'success';
            }
          }
          if ($module !="" && $module=="PURCHASE REQUEST") 
          {            
            /*$jwttoken             = genratejwttoken($user_id);
            $inputdata            = $request->all();
            $getbvlocdatacenter   = $this->getbvlocdatacenter($jwttoken);
            $inputdata['bvdcloc'] = $getbvlocdatacenter;*/
            $totalrecords   = EnReports::getpurchasereport_pr($sanitize_result,$inputdata, true);              
            $result         = EnReports::getpurchasereport_pr($sanitize_result,$inputdata , false);
            // $result = json_decode($result, true);
            // for($i=0;$i<count($result);$i++)
            // {              
            //   $result[$i]['created_at'] = date("d-m-Y H:i:s", strtotime($result[$i]['created_at']));
            //   $result[$i]['updated_at'] = date("d-m-Y H:i:s", strtotime($result[$i]['updated_at']));
            // }           
            // $result = (object) $result;            
            $tableheaders   = $this->get_table_headers("PURCHASE REQUEST",$filter_fields);

            $data['data']['from_time']    = $from_time;
            $data['data']['to_time']      = $to_time;
            $data['data']['tableheaders'] = $tableheaders;
            // $data['data']['records']      = $result->isEmpty() ? NULL : $result;
            $data['data']['records']      = $result;
            $data['data']['totalrecords'] = $totalrecords;
            //$last_query     = DB::getQueryLog();
            //$data['data']['last_query']   = $last_query;                
            if ($totalrecords < 1)
            {
              $data['message']['success']   = showmessage('102', array('{name}'), array(trans('label.lbl_reports')));
              $data['status']               = 'success';
            }
            else
            {
              $data['message']['success']   = showmessage('101', array('{name}'), array(trans('label.lbl_reports')));
              $data['status']               = 'success';
            }
          }
          if ($module !="" && $module=="ASSETS") 
          {
            $jwttoken                   = genratejwttoken($user_id);
            $inputdata                  = $request->all();
            $getbvlocdatacenter         = $this->getbvlocdatacenter($jwttoken);
            $inputdata['bvdcloc']       = $getbvlocdatacenter;
            $inputdata['searchkeyword'] = trim(_isset($inputdata,'searchkeyword'));
            $totalrecords   = EnReports::getassetsreport($sanitize_result,$inputdata, true);
            //$totalrecords   = '100000';
            $result         = EnReports::getassetsreport($sanitize_result,$inputdata , false);
            $tableheaders   = $this->get_table_headers("ASSETS",$filter_fields);
            
            $data['data']['from_time'] = $from_time;
            $data['data']['to_time']   = $to_time;

            $data['data']['tableheaders'] = $tableheaders;
            $data['data']['records']      = $result->isEmpty() ? NULL : $result;
            $data['data']['totalrecords'] = $totalrecords;
            //$last_query     = DB::getQueryLog();
            //$data['data']['last_query']   = $last_query;                
            if ($totalrecords < 1)
            {
              $data['message']['success']   = showmessage('102', array('{name}'), array(trans('label.lbl_reports')));
              $data['status']               = 'success';
            }
            else
            {
              $data['message']['success']   = showmessage('101', array('{name}'), array(trans('label.lbl_reports')));
              $data['status']               = 'success';
            }
          }
          if ($module !="" && $module=="CMDB") 
          {
            $jwttoken                   = genratejwttoken($user_id);
            $inputdata                  = $request->all();
            $getbvlocdatacenter         = $this->getbvlocdatacenter($jwttoken);
            $inputdata['bvdcloc']       = $getbvlocdatacenter;
            $inputdata['cifields']      = $cifields;
            $inputdata['cifilterfields']= $cifilterfields;
            
            $inputdata['searchkeyword'] = trim(_isset($inputdata,'searchkeyword'));
            $totalrecords   = EnReports::getcmdbreport($sanitize_result,$inputdata, true);
            //$totalrecords   = '100000';
            $result         = EnReports::getcmdbreport($sanitize_result,$inputdata , false);
            $tableheaders   = $this->get_table_headers("CMDB",$filter_fields);
            
            $data['data']['from_time'] = $from_time;
            $data['data']['to_time']   = $to_time;

            $data['data']['tableheaders'] = $tableheaders;
            $data['data']['records']      = $result->isEmpty() ? NULL : $result;
            $data['data']['totalrecords'] = $totalrecords;
            //$last_query     = DB::getQueryLog();
            //$data['data']['last_query']   = $last_query;                
            if ($totalrecords < 1)
            {
              $data['message']['success']   = showmessage('102', array('{name}'), array(trans('label.lbl_reports')));
              $data['status']               = 'success';
            }
            else
            {
              $data['message']['success']   = showmessage('101', array('{name}'), array(trans('label.lbl_reports')));
              $data['status']               = 'success';
            }
          }
           if ($module !="" && $module == "ALLCOMP") 
          {
            $jwttoken                   = genratejwttoken($user_id);
            $inputdata                  = $request->all();
            $getbvlocdatacenter         = $this->getbvlocdatacenter($jwttoken);
            $inputdata['bvdcloc']       = $getbvlocdatacenter;
            $inputdata['cifields']      = $cifields;
            $inputdata['cifilterfields']= $cifilterfields;
            
            $inputdata['searchkeyword'] = trim(_isset($inputdata,'searchkeyword'));
            $totalrecords   = EnReports::getallcompreport($sanitize_result,$inputdata, true);
            //$totalrecords   = '100000';
            $result         = EnReports::getallcompreport($sanitize_result,$inputdata , false);
            $tableheaders   = $this->get_table_headers("ALLCOMP",$filter_fields);
            
            $data['data']['from_time'] = $from_time;
            $data['data']['to_time']   = $to_time;

            $data['data']['tableheaders'] = $tableheaders;
            $data['data']['records']      = $result->isEmpty() ? NULL : $result;
            $data['data']['totalrecords'] = $totalrecords;
            //$last_query     = DB::getQueryLog();
            //$data['data']['last_query']   = $last_query;                
            if ($totalrecords < 1)
            {
              $data['message']['success']   = showmessage('102', array('{name}'), array(trans('label.lbl_reports')));
              $data['status']               = 'success';
            }
            else
            {
              $data['message']['success']   = showmessage('101', array('{name}'), array(trans('label.lbl_reports')));
              $data['status']               = 'success';
            }
          }
        }
        else
        {
          $data['data']                 = null;
          $data['message']['success']   = showmessage('102', array('{name}'), array(trans('label.lbl_reports')));
          $data['status']               = 'success';
        }          
      }
    }
    catch(\Exception $e)
    {
      $data['data']               = null;
      $data['message']['error']   = $e->getMessage();
      $data['status']             = 'error';
      save_errlog("reportsdetail","This controller function is implemented to get Report Details.",$request->all(),$e->getMessage());
    }
    catch(\Error $e)
    {
      $data['data']   = null;
      $data['message']['error'] = $e->getMessage();
      $data['status'] = 'error';
      save_errlog("reportsdetail","This controller function is implemented to get Report Details.",$request->all(),$e->getMessage());
    }
    finally
    {
      return response()->json($data);
    }
  }
  /**
  * This controller function is implemented to get  table headers for report.
  * @author Shadab Khan
  * @access public
  * @package reports
  * @param \Illuminate\Http\Request $request
  * @param UUID $report_id
  * @return json
  * @tables  en_reports 
  */
  public function get_table_headers($module="",$fields=null)
  {
    $moduleresp          = EnReportModules::where('module_key', $module)->first();
    if ($moduleresp) 
    {
      $reports_fields_arr  = $fields;
      $module_fields_arr   = json_decode($moduleresp->module_fields,true);
      if (is_array($reports_fields_arr) && is_array($module_fields_arr)) 
      {
        //commented as mismatching indexes
        //$module_fields_common = array_intersect(array_keys($module_fields_arr), $reports_fields_arr);
        foreach($reports_fields_arr AS $key) 
        {
          $transkey = str_replace(' ', '_', strtolower(isset($module_fields_arr[$key]) ? $module_fields_arr[$key] : ""));
          if (Lang::has('report.'.$transkey)) 
          {
            $fields_common[$key] = trans('report.'.$transkey);
          }else
          {
            $fields_common[$key] = isset($module_fields_arr[$key]) ? $module_fields_arr[$key] : $key;
          }
        }
      }
    }
    return array_values($fields_common);
  }
  /**
  * This controller function is implemented to Export Report.
  * @author Shadab Khan
  * @access public
  * @package reports
  * @param \Illuminate\Http\Request $request
  * @param UUID $report_id
  * @return json
  * @tables  en_reports 
  */
  public function export_report(Request $request)
  {
    

    $reports_id_uuid = $request->input('report_id');
    $reports_id_bin  = DB::raw('UUID_TO_BIN("'.$request->input('report_id').'")');
    $export_type     = $request->input('export_type');    
    if (isset($reports_id_bin) && $reports_id_bin!=null) 
    {
      $obj_report = EnReports::where('report_id',$reports_id_bin)->whereIn('status',['y','q'])->first();      
      if ($obj_report) 
      {        
        $arr_report_deatils = $obj_report->toArray();
        $requestdata  = array();
        $report_name  = preg_replace('/\s+/', '_', $obj_report->report_name);
        $requestdata['report_name'] = $report_name.'_'.date('d_m_Y_h_i_s_a');
        $requestdata['report_id']   =  $reports_id_bin;
        $requestdata['export_type'] =  $export_type;
        $requestdata['user_id']     =  DB::raw('UUID_TO_BIN("'.$request->input('loggedinuserid').'")');
        
        
        
        $notif_data = EnReportNotifications::create($requestdata);
        if(!empty($notif_data['notification_id']))
        {
          $notification_id   = $notif_data->notification_id_text;
          $job = (new GenrateReport($reports_id_uuid,$export_type,$notification_id))->onQueue('report');
          $this->dispatch($job);
          //chdir('..');
          //$cmd = exec('php artisan queue:work --queue=report --tries=3 &');
          $data['data']['insert_id']  = $notification_id;
          $data['message']['success'] = trans('messages.msg_added_queue');
          $data['status']             = 'success';
        }
        else
        {
          $data['data']             = NULL;
          $data['message']['error'] = trans('messages.163');
          $data['status']           = 'error';
        }
      return response()->json($data);   
      }
    }
  }
  /**
  * This controller function is implemented to get Report Notifications.
  * @author Shadab Khan
  * @access public
  * @package reports
  * @param \Illuminate\Http\Request $request
  * @param UUID $report_id
  * @return json 
  */
  public function getreportnotifications(Request $request)
  {
    
    $inputdata                  = $request->all();
    $notification_id  = isset($inputdata['notification_id']) ? $inputdata['notification_id'] : null;
    $inputdata['searchkeyword'] = trim(_isset($inputdata,'searchkeyword'));
    $count = $inputdata['count'];

    $totalrecords = EnReportNotifications::getnotifications($notification_id,$inputdata , true);
    $totalrecords = !empty($totalrecords) ? $totalrecords : 0;
    //$last_query     = DB::getQueryLog();
    //$data['data']['last_query']   = $last_query;  
    $totalrecords_asset = EnImportNotifications::getnotifications($inputdata, true);
    $totalrecords_asset = !empty($totalrecords_asset) ? $totalrecords_asset : 0;
    $totalrecords       = $totalrecords + $totalrecords_asset;

    if ($count == 'yes') 
    {
      $data['data']['records'] = $totalrecords;
    }
    else
    {
      $result       = EnReportNotifications::getnotifications($notification_id,$inputdata , false);
      $report_notify      = $result->isEmpty() ? NULL : $result;
    
      $result_asset = EnImportNotifications::getnotifications($inputdata, false);
        $import_notify = $result_asset->isEmpty() ? null : $result_asset;
      
      $data1 = $data2 = array();
      if(!empty($report_notify)){ $data1 = $report_notify; $data1 = json_decode($data1,true);}
      if(!empty($import_notify)){ $data2 = $import_notify; $data2 = json_decode($data2,true);}
      
      $data3 = array_merge($data1,$data2);

      if($data3) $data['data']['records'] = $data3;
      else $data['data']['records'] = NULL;
    }
    $data['data']['totalrecords'] = $totalrecords;                
    if ($totalrecords < 1)
    {
      $data['message']['success']   = showmessage('102', array('{name}'), array(trans('label.lbl_reports')));
      $data['status']               = 'success';
    }
    else
    {
      $data['message']['success']   = showmessage('101', array('{name}'), array(trans('label.lbl_reports')));
      $data['status']               = 'success';
    }
    return response()->json($data);
  }
  /**
  * This controller function is implemented to get Download Report.
  * @author Shadab Khan
  * @access public
  * @package reports
  * @param \Illuminate\Http\Request $request
  * @param UUID $report_id
  * @return json
  */
  public function download(Request $request)
  {   
    try
    {
      $messages = [
        'notification_id.required' => showmessage('000', array('{name}'), array(trans('notification_id.lbl_reports')), true),
      ];
      $validator = Validator::make($request->all(), [
          'notification_id' => 'required',
      ], $messages);
      if ($validator->fails())
      {
        $error          = $validator->errors();
        $data['data']   = null;
        $data['message']['error'] = $error;
        $data['status'] = 'error';
        return response()->json($data);
      }
      else
      {
        $notification_id_uuid = $request->input('notification_id');
       
        $notification_id_bin  = DB::raw('UUID_TO_BIN("'.$request->input('notification_id').'")');
        
        $result               = EnReportNotifications::where('notification_id', $notification_id_bin)->first();
       
        if($result)
        {          
          $zipfilePath  = storage_path('app/public/reports/');
          $fileurl      = $zipfilePath.$result->report_name.'.zip';
           if (file_exists($fileurl)) 
           {
              $res    =  (file_get_contents($fileurl));
           }
           else
           {
              $res = "";
           }            
           if($res == '')
           {
              $data['status'] = 'error';
              $data['message']['error'] = showmessage('102', array('{name}'),array(trans('label.lbl_attachment')));

            }
            else
            {
              $data['data']   = base64_encode($res);
              $data['status'] = 'success';
              $data['message']['success'] = showmessage('101', array('{name}'),array(trans('label.lbl_attachment')));
            }
        }
        else
        {             
          $data['data']   = NULL;             
          $data['message']['error'] = showmessage('102', array('{name}'),array(trans('label.lbl_reports')));     
          $data['status'] = 'error'; 
        }
      }
    }
    catch(\Exception $e)
    {
      $data['data']               = null;
      $data['message']['error']   = $e->getMessage();
      $data['status']             = 'error';
      save_errlog("download","This is controller funtion used to download the Report.",$request->all(),$e->getMessage());
    }
    catch(\Error $e)
    {
      $data['data']   = null;
      $data['message']['error'] = $e->getMessage();
      $data['status'] = 'error';
      save_errlog("download","This is controller funtion used to download the Report.",$request->all(),$e->getMessage());
    }
    finally
    {
      return response()->json($data);
    }
  }                                            
  /**
  * Function to return BV,Datacenter and location data 
  * @author Shadab Khan
  * @access public
  * @package reports
  * @return json
  */
  public function getbvlocdatacenter($token)
  {
    $location_id = $locationArr = $dc_id = $datacenterArr = $bv_id = $businessVerticalArr = array();
    $iam_service_apiurl = config('enconfig.iam_service_apiurl');
    $token = 'encoded '.$token;
    $options                = ['token' => $token,'form_params' => array('order_byregion' => true ,'order_bybu' => true)];
    $bvlocdcresponse = $this->remote_api->apicall("POST", $iam_service_apiurl, 'getdclocbvdata', $options);
    $response     = _isset(_isset($bvlocdcresponse, 'content'), 'records');
    if ($response) 
    {
      //============= Locations
      if (isset($response['loc']) && is_array($response['loc']) && !empty($response['loc']))
      {
        $locationDetailsArr = $response['loc'];
        $region_name = '';
        foreach ($locationDetailsArr as $lo)
        {
          if ($lo['region_name']!="")
          {
            $region_name =  ucfirst($lo['region_name']); 
          }
          $locationArr[$lo['location_id']] = $region_name.'-'.htmlspecialchars($lo['location_name']);
          $location_id[] = $lo['location_id'];
        }
      }
      //============= Business Vertical
      if (isset($response['bv']) && is_array($response['bv']) && !empty($response['bv']))
      {
        $businessVerticalDetailsArr  = $response['bv'];
        $bu_name = '';
        foreach ($businessVerticalDetailsArr as $bv)
        {
          if ($bv['bu_name']!="")
          {
            $bu_name =  ucfirst($bv['bu_name']); 
          }
          $businessVerticalArr[$bv['bv_id']] = $bu_name.'-'.htmlspecialchars($bv['bv_name']);
          $bv_id[] = $bv['bv_id'];
        }
      }
      //============= Data center
      if (isset($response['dc']) && is_array($response['dc']) && !empty($response['dc']))
      {
        $datacenterDetailsArr = $response['dc'];
        $region_name = '';
        foreach ($datacenterDetailsArr as $dc)
        {
          if ($dc['regions_name']!="")
          {
            $region_name =  ucfirst($dc['regions_name']); 
          }
          $datacenterArr[$dc['dc_id']] = $region_name.'-'.htmlspecialchars($dc['dc_name']);
          $dc_id[] = $dc['dc_id'];
        }
      }
    }
    return array('location_id'=>$location_id,'location'=>$locationArr,'bv_id'=>$bv_id,'businessvertical'=>$businessVerticalArr,'dc_id'=>$dc_id,'datacenter'=>$datacenterArr);
  }
	/**
	* Function to update the read flag of notification
	* @author Snehal Chaturvedi
	* @access public
	* @package reports
	* @return json
	*/
  public function readnotification(Request $request)
  {
    try
    {
      $notification_id_uuid = $request->input('notification_id');
      $notification_type    = $request->input('notification_type');
      $action               = $request->input('action');

      $notification_id_bin  = DB::raw('UUID_TO_BIN("'.$request->input('notification_id').'")');
      $request['notification_id']  = DB::raw('UUID_TO_BIN("'.$request->input('notification_id').'")');

      if($notification_type == 'report') $result = EnReportNotifications::where('notification_id', $notification_id_bin)->first();
      elseif ($notification_type == 'import_asset') $result = EnImportNotifications::where('notification_id', $notification_id_bin)->first();
        
      if($result)
      {
          if(isset($action) && $action == 'r'){
            $result->update(['read' => 'y','read_at'=>date('Y-m-d H:i:s')]);
          }
          elseif(isset($action) && $action == 'd'){
            $result->update(['status' => 'd']);
          }
          $result->save();             
          $data['data']   = NULL;     
          $data['message']['success'] = showmessage('118', array('{name}'),array('read notification'));      
          $data['status'] = 'success'; 
      }
      else
      {             
          $data['data']   = NULL;             
          $data['message']['error'] = showmessage('102', array('{name}'),array('read notification'));     
          $data['status'] = 'error'; 
      }
      
    }
    catch(\Exception $e)
    {
      $data['data']               = null;
      $data['message']['error']   = $e->getMessage();
      $data['status']             = 'error';
      save_errlog("readnotification","This is controller funtion used to read notification.",$request->all(),$e->getMessage());
    }
    catch(\Error $e)
    {
      $data['data']   = null;
      $data['message']['error'] = $e->getMessage();
      $data['status'] = 'error';
      save_errlog("readnotification","This is controller funtion used to read notification.",$request->all(),$e->getMessage());
    }
    finally
    {
      return response()->json($data);
    }
  }

  public function downloadpbireport(Request $request) {
    $post_data    = $request->all();

    $output_data  = EnReports::get_pbireports($post_data['fromdate'],$post_data['todate'], $post_data['report_type']);

    if($output_data) {
      $data['data']               = $output_data;             
      $data['message']['success'] = "Success";     
      $data['status']             = 'success'; 
    } else {
      $data['data']               = null;             
      $data['message']['error']   = "Erorr";     
      $data['status']             = 'error'; 
    }
    return response()->json($data);
  }





}