<?php

namespace App\Http\Controllers\Reports;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EnReportCategory;
use Validator;

class ReportCategoryController extends Controller
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
  /**
  * This controller function is implemented to get Report Category.
  * @author Shadab Khan
  * @access public
  * @package report_category
  * @param \Illuminate\Http\Request $request
  * @param UUID $report_cat_id
  * @return json
  * @tables  en_report_category 
  */
  public function reportcategory(Request $request)
  {
    try
    {
      $validator                = Validator::make($request->all(), [
		  'report_cat_id'=> 'nullable|allow_uuid|string|size:36'
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
        $report_cat_id  = isset($inputdata['report_cat_id']) ? $inputdata['report_cat_id'] : null;
        $totalrecords   = EnReportCategory::getreportCategory($report_cat_id,$inputdata, true);  
        $result         = EnReportCategory::getreportCategory($report_cat_id, $inputdata , false);  
        
        $data['data']['records']      = $result->isEmpty() ? NULL : $result;
        $data['data']['totalrecords'] = $totalrecords;                
       
        if ($totalrecords < 1)
        {
          $data['message']['success'] = showmessage('102', array('{name}'), array(trans('label.lbl_report_category')));
          $data['status']           = 'success';
        }
        else
        {
          $data['message']['success'] = showmessage('101', array('{name}'), array(trans('label.lbl_report_category')));
          $data['status']             = 'success';
        }
      }
    }
    catch(\Exception $e)
    {
      $data['data']               = null;
      $data['message']['error']   = $e->getMessage();
      $data['status']             = 'error';
      save_errlog("reportcategory","This controller function is implemented to get Report Category.",$request->all(),$e->getMessage());
    }
    catch(\Error $e)
    {
      $data['data']   = null;
      $data['message']['error'] = $e->getMessage();
      $data['status'] = 'error';
      save_errlog("reportcategory","This controller function is implemented to get Report Category.",$request->all(),$e->getMessage());
    }
    finally
    {
      return response()->json($data);
    }
  }
  /**
  * This is controller funtion used to add the Report Category.
  * @author Shadab Khan
  * @access public
  * @package report_category
  * @param \Illuminate\Http\Request $request
  * @return json
  * @tables  en_report_category 
  */
  public function reportcategoryadd(Request $request) 
  {
    try
    {
      $messages = [
          'report_category.required' => showmessage('000', array('{name}'), array(trans('label.lbl_report_category')), true),
          'report_category.allow_alpha_numeric_space_dash_underscore_only' => showmessage('003', array('{name}'), array(trans('label.lbl_report_category')), true),
          'report_category.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_report_category')), true),
          'description.required' => showmessage('000', array('{name}'), array(trans('label.lbl_desc')), true),
		  'description.html_tags_not_allowed' => showmessage('001', array('{name}'), array(trans('label.lbl_desc')), true),
      ];
      $validator = Validator::make($request->all(), [
		    'report_cat_id'     => 'nullable|allow_uuid|string|size:36',
        'report_category'   => 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_report_category, report_category, '.$request->input('report_category'), 
        'description'       => 'required|html_tags_not_allowed', 
      ],$messages);          
      if ($validator->fails())
      {
        $error          = $validator->errors();
        $data['data']   = null;
        $data['message']['error'] = $error;
        $data['status'] = 'error';
      }
      else
      { 
        $report_cat_data = EnReportCategory::create($request->all());  
        if(!empty($report_cat_data['report_cat_id']))
        {
          $report_cat_id              = $report_cat_data->report_cat_id_text;
          $data['data']['insert_id']  = $report_cat_id;
          $data['message']['success'] = showmessage('104', array('{name}'),array(trans('label.lbl_report_category')));
          $data['status']             = 'success';
        }
        else
        {
          $data['data']   = NULL;
          $data['message']['error'] = showmessage('103', array('{name}'),array(trans('label.lbl_report_category')));
          $data['status'] = 'error';
        }
      }
      return response()->json($data);
    }
    catch(\Exception $e)
    {
      $data['data']               = null;
      $data['message']['error']   = $e->getMessage();
      $data['status']             = 'error';
      save_errlog("reportcategoryadd","This controller function is implemented to add the Report Category.",$request->all(),$e->getMessage());
    }
    catch(\Error $e)
    {
      $data['data']   = null;
      $data['message']['error'] = $e->getMessage();
      $data['status'] = 'error';
      save_errlog("reportcategoryadd","This controller function is implemented to add the Report Category.",$request->all(),$e->getMessage());
    }
    finally
    {
      return response()->json($data);
    }
  }
 /**
  * This function provides a window to user to update the report category information.
  * @author Shadab Khan
  * @access public
  * @package report_category
  * @param \Illuminate\Http\Request $request
  * @return json
  * @tables  en_report_category 
  */
  public function reportcategoryedit(Request $request)
  {
    try
    {
      $validator = Validator::make($request->all(), [ 
        'report_cat_id' => 'required|allow_uuid|string|size:36'
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
        $result       = EnReportCategory::getreportCategory($request->input('report_cat_id'));  
        $data['data'] = $result->isEmpty() ? NULL : $result;
        if($data['data'])
        {
          $data['message']['success'] = showmessage('102', array('{name}'),array(trans('label.lbl_report_category')));
          $data['status'] = 'success';            
        }
        else
        { 
          $data['message']['error'] = showmessage('101', array('{name}'),array(trans('label.lbl_report_category')));
          $data['status'] = 'error';          
        }
      }
    }
    catch(\Exception $e)
    {
      $data['data']               = null;
      $data['message']['error']   = $e->getMessage();
      $data['status']             = 'error';
      save_errlog("reportcategoryedit","This controller function is implemented to edit the report category.",$request->all(),$e->getMessage());
    }
    catch(\Error $e)
    {
      $data['data']   = null;
      $data['message']['error'] = $e->getMessage();
      $data['status'] = 'error';
      save_errlog("reportcategoryedit","This controller function is implemented to edit the report category.",$request->all(),$e->getMessage());
    }
    finally
    {
      return response()->json($data);
    }
  }
 /**
  * This is controller funtion used to update the Report Category.
  * @author Shadab Khan
  * @access public
  * @package report_category
  * @param \Illuminate\Http\Request $request
  * @return json
  * @tables  en_report_category 
  */
  public function reportcategoryupdate(Request $request)
  {
    try
    {
      $messages = [
          'report_category.required' => showmessage('000', array('{name}'), array(trans('label.lbl_report_category')), true),
          'report_category.allow_alpha_numeric_space_dash_underscore_only' => showmessage('003', array('{name}'), array(trans('label.lbl_report_category')), true),
          'report_category.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_report_category')), true),
          'description.required' => showmessage('000', array('{name}'), array(trans('label.lbl_desc')), true),
		  'description.html_tags_not_allowed' => showmessage('001', array('{name}'), array(trans('label.lbl_desc')), true),
      ];
      $validator = Validator::make($request->all(), [
        'report_cat_id'     => 'required|allow_uuid|string|size:36',
        'report_category' => 'required|allow_alpha_numeric_space_dash_underscore_only|composite_unique:en_report_category, report_category, '.$request->input('report_category').', report_cat_id,'.$request->input('report_cat_id'),
        'description'       => 'required|html_tags_not_allowed',  
      ],$messages);       
      if ($validator->fails())
      {
          $error          = $validator->errors();
          $data['data']   = null;
          $data['message']['error'] = $error;
          $data['status'] = 'error';
      }
      else
      { 
        $report_category_id_uuid = $request->input('report_cat_id');
        $report_category_id_bin  = DB::raw('UUID_TO_BIN("'.$request->input('report_cat_id').'")');
        $request['report_cat_id']= DB::raw('UUID_TO_BIN("'.$request->input('report_cat_id').'")');
        $result                  = EnReportCategory::where('report_cat_id', $report_category_id_bin)->first();
        if($result)
        {
          $result->update($request->all());            
          $result->save();             
          $data['data']   = NULL;     
          $data['message']['success'] = showmessage('106', array('{name}'),array(trans('label.lbl_report_category')));      
          $data['status'] = 'success'; 

        }
        else
        {             
          $data['data']   = NULL;             
          $data['message']['error'] = showmessage('102', array('{name}'),array(trans('label.lbl_report_category')));     
          $data['status'] = 'error'; 
        } 
      }
    }
    catch(\Exception $e)
    {
      $data['data']               = null;
      $data['message']['error']   = $e->getMessage();
      $data['status']             = 'error';
      save_errlog("reportcategoryupdate","This is controller funtion used to update the Report Category.",$request->all(),$e->getMessage());
    }
    catch(\Error $e)
    {
      $data['data']   = null;
      $data['message']['error'] = $e->getMessage();
      $data['status'] = 'error';
      save_errlog("reportcategoryupdate","This is controller funtion used to update the Report Category.",$request->all(),$e->getMessage());
    }
    finally
    {
      return response()->json($data);
    }
  }
  /**
  * This is controller funtion used to delete the Report Category.
  * @author Shadab Khan
  * @access public
  * @package report_category
  * @param \Illuminate\Http\Request $request
  * @return json
  * @tables  en_report_category 
  */
  public function reportcategorydelete(Request $request)
  {
    try
    {
      $messages = [
        'report_cat_id.required' => showmessage('000', array('{name}'), array(trans('label.lbl_report_category')), true),
      ];
      $validator = Validator::make($request->all(), [
          'report_cat_id' => 'required|allow_uuid|string|size:36',
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
        $report_category_id_uuid = $request->input('report_cat_id');
        $report_category_id_bin  = DB::raw('UUID_TO_BIN("'.$request->input('report_cat_id').'")');
        $request['report_cat_id']  = DB::raw('UUID_TO_BIN("'.$request->input('report_cat_id').'")');
        $result                    = EnReportCategory::where('report_cat_id', $report_category_id_bin)->first();
        $count_report = DB::table('en_reports')->where('report_cat_id',$report_category_id_bin)->count();
        //check if report_cat_id  record exists in 'en_reports' table, if exists then can not delete relative record.
        if($count_report > 0)
        {
          $data['data']   = NULL;             
          $data['message']['error'] = showmessage('121', array('{name}'),array(trans('label.lbl_report_category')));
          $data['status'] = 'error';
          return response()->json($data);
        }
        if($result)
        {
          $result->update(['status' => 'd']);
          $result->save();             
          $data['data']   = NULL;     
          $data['message']['success'] = showmessage('118', array('{name}'),array(trans('label.lbl_report_category')));      
          $data['status'] = 'success'; 
        }
        else
        {             
          $data['data']   = NULL;             
          $data['message']['error'] = showmessage('102', array('{name}'),array(trans('label.lbl_report_category')));     
          $data['status'] = 'error'; 
        }
      }
    }
    catch(\Exception $e)
    {
      $data['data']               = null;
      $data['message']['error']   = $e->getMessage();
      $data['status']             = 'error';
      save_errlog("reportcategorydelete","This is controller funtion used to delete the Report Category.",$request->all(),$e->getMessage());
    }
    catch(\Error $e)
    {
      $data['data']   = null;
      $data['message']['error'] = $e->getMessage();
      $data['status'] = 'error';
      save_errlog("reportcategorydelete","This is controller funtion used to delete the Report Category.",$request->all(),$e->getMessage());
    }
    finally
    {
      return response()->json($data);
    }
  }
}