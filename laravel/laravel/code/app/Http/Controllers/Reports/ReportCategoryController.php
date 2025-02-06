<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use Illuminate\Http\Request;
use View;

/**
 * ReportCategory Controller class is implemented to do reportcategory Type operations.
 * @author Shadab Khan
 * @package ReportCategory
 */
class ReportCategoryController extends Controller
{
  /**
  * Contructor function to initiate the API service and Request data
  * @author Shadab Khan
  * @access public
  * @package ReportCategory
  * @param \App\Services\ITAM\ItamService $itam
  * @param \Illuminate\Http\Request $request
  * @return mixed
  */
  public function __construct(IamService $iam, ItamService $itam, Request $request)
  {
    $this->itam           = $itam;
    $this->iam            = $iam;
    $this->emlib          = new Emlib;
    $this->request        = $request;
    $this->request_params = $this->request->all();
  }
  /**
  * ReportCategory Controller function is implemented to initiate a page to get list of Report Category .
  * @author Shadab Khan
  * @access public
  * @package ReportCategory
  * @return string
  */
  public function reportcategory()
  {
    $topfilter              = ['gridsearch' => true, 'jsfunction' => 'reportcategoryList()'];
    $data['emgridtop']      = $this->emlib->emgridtop($topfilter);
    $data['pageTitle']      = trans('title.reportcategory');
    $data['includeView']    = view("Reports/reportcategory", $data);
    return view('template', $data);
  }
  /**
   * This controller function is implemented to get list of reportcategory Type.
   * @author Shadab Khan
   * @access public
   * @package reportcategory
   * @param int $limit, int $page Pagination Variables
   * @param string $searchkeyword
   * @return json
   */
  public function reportcategorylist()
  {
    try
    {
      $paging         = [];
      $limit          = _isset($this->request_params, 'limit', config('enconfig.def_limit_short'));
      $page           = _isset($this->request_params, 'page', config('enconfig.page'));
      $searchkeyword  = _isset($this->request_params, 'searchkeyword');
      //$exporttype     = _isset($this->request_params, 'exporttype');
      $is_error       = false;
      $msg            = '';
      $content        = "";
      $limit_offset   = limitoffset($limit, $page);
      $page           = $limit_offset['page'];
      $limit          = $limit_offset['limit'];
      $offset         = $limit_offset['offset'];

      $form_params['limit']   = $paging['limit']  = $limit;
      $form_params['page']    = $paging['page']   = $page;
      $form_params['offset']  = $paging['offset'] = $offset;
      $form_params['searchkeyword']               = $searchkeyword;

      $options                = ['form_params' => $form_params];
      $reportcategory_resp = $this->itam->getreportcategory($options);

      if ($reportcategory_resp['is_error'])
      {
        $is_error           = $reportcategory_resp['is_error'];
        $msg                = $reportcategory_resp['msg'];
      }
      else
      {
        $is_error                 = false;
        $reportcategory           = _isset(_isset($reportcategory_resp, 'content'), 'records');
        $paging['total_rows']     = _isset(_isset($reportcategory_resp, 'content'), 'totalrecords');
        $paging['showpagination'] = true;
        $paging['jsfunction']     = 'reportcategoryList()';
        $view                     = 'Reports/reportcategorylist';
        $content                  = $this->emlib->emgrid($reportcategory, $view, $columns = [], $paging);
      }
      $response["html"]       = $content;
      $response["is_error"]   = $is_error;
      $response["msg"]        = $msg;
      echo json_encode($response);
    }
    catch (\Exception $e)
    {
      $response["html"]      = '';
      $response["is_error"]  = true;
      $response["msg"]       = $e->getmessage();
      save_errlog("reportcategoryList","This controller function is implemented to reportcategory type list.",$this->request_params,$e->getmessage());  
      echo json_encode($response, true);
    }
    catch (\Error $e)
    {
      $response["html"]      = '';
      $response["is_error"]  = true;
      $response["msg"]       = $e->getmessage();
      save_errlog("reportcategoryList","This controller function is implemented to reportcategory type list.",$this->request_params,$e->getmessage());  
      echo json_encode($response, true);
    }
  }
  /**
  * This controller function is used to load reportcategory type add form.
  * @author Shadab Khan
  * @access public
  * @package reportcategory
  * @return string
  */
  public function reportcategoryadd(Request $request)
  {
    $data['report_cat_id']         = '';
    $reportcategorydata            = [];
    $data['reportcategorydata']    = $reportcategorydata;
    $html                          = view("Reports/reportcategoryadd", $data);
    echo $html;
  }
  /**
  * This controller function is used to save reportcategory  data in database.
  * @author Shadab Khan
  * @access public
  * @package reportcategory
  * @param string $report_category reportcategory 
  * @param string $description reportcategory  Description
  * @return json
  */
  public function reportcategoryaddsubmit(Request $request)
  {
    try
    {
      $data = $this->itam->addreportcategory(['form_params' => $request->all()]);
      echo json_encode($data, true);
    }
    catch (\Exception $e)
    {
      $data["html"]      = '';
      $data["is_error"]  = true;
      $data["msg"]       = $e->getmessage();
      save_errlog("reportcategoryaddsubmit","This controller function is implemented to save reportcategory type.",$this->request_params,$e->getmessage());  
      echo json_encode($data, true);
    }
    catch (\Error $e)
    {
      $data["html"]      = '';
      $data["is_error"]  = true;
      $data["msg"]       = $e->getmessage();
      save_errlog("reportcategoryaddsubmit","This controller function is implemented to save reportcategory type.",$this->request_params,$e->getmessage());  
      echo json_encode($data, true);
    }
  }
  /**
  * This controller function is used to load reportcategory edit form with existing data for selected reportcategory.
  * @author Shadab Khan
  * @access public
  * @package reportcategory
  * @param \Illuminate\Http\Request $request
  * @param $report_cat_id reportcategory Unique Id
  * @return string
  */
  public function reportcategoryedit(Request $request)
  {
    $report_cat_id         = $request->id;
    $input_req             = ['report_cat_id' => $report_cat_id];
    $data                  = $this->itam->editreportcategory(['form_params' => $input_req]);
    $data['report_cat_id'] = $report_cat_id;
    $data['reportcategorydata'] = $data['content'];
    $html                 = view("Reports/reportcategoryadd", $data);
    echo $html;
  }
  /**
  * This controller function is used to update reportcategory data in database.
  * @author Shadab Khan
  * @access public
  * @package reportcategory
  * @param UUID $report_cat_id reportcategory  Unique Id
  * @param string $report_category reportcategory
  * @param string $description reportcategory Description
  * @return json
  */
  public function reportcategoryeditsubmit(Request $request)
  {
    try
    {
      $data = $this->itam->updatereportcategory(['form_params' => $request->all()]);
      echo json_encode($data, true);
    }
    catch (\Exception $e)
    {
      $data["html"]      = '';
      $data["is_error"]  = true;
      $data["msg"]       = $e->getmessage();
      save_errlog("reportcategoryeditsubmit","This controller function is implemented to update reportcategory.",$this->request_params,$e->getmessage());  
      echo json_encode($data, true);
    }
    catch (\Error $e)
    {
      $data["html"]      = '';
      $data["is_error"]  = true;
      $data["msg"]       = $e->getmessage();
      save_errlog("reportcategoryeditsubmit","This controller function is implemented to update reportcategory.",$this->request_params,$e->getmessage());  
      echo json_encode($data, true);
    }
  }
  /**
  * This controller function is used to delete reportcategory data from database.
  * @author Shadab Khan
  * @access public
  * @package reportcategory
  * @param UUID $report_cat_id reportcategory Unique Id
  * @return json
  */
  public function reportcategorydelete(Request $request)
  {
    try
    {
      $data = $this->itam->deletereportcategory(['form_params' => $request->all()]);
      echo json_encode($data, true);
    }
    catch (\Exception $e)
    {
      $data["html"]      = '';
      $data["is_error"]  = true;
      $data["msg"]       = $e->getmessage();
      save_errlog("reportcategorydelete","This controller function is implemented to delete reportcategory.",$this->request_params,$e->getmessage());  
      echo json_encode($data, true);
    }
    catch (\Error $e)
    {
      $data["html"]      = '';
      $data["is_error"]  = true;
      $data["msg"]       = $e->getmessage();
      save_errlog("reportcategorydelete","This controller function is implemented to delete reportcategory.",$this->request_params,$e->getmessage());  
      echo json_encode($data, true);
    }
  }
}



