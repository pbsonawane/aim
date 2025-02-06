<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Libraries\Emlib;
use App\Services\IAM\IamService;
use App\Services\ITAM\ItamService;
use App\Services\RemoteDownloadApi;
use Illuminate\Http\Request;
use View;
use PDF;

/**
 * report Controller class is implemented to do reports Type operations.
 * @author Bhushan Amrutkar
 * @package report
 */
class PrReportsController extends Controller
{
  /**
  * Contructor function to initiate the API service and Request data
  * @author Bhushan Amrutkar
  * @access public
  * @package report
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
    $this->remoteapi  = new RemoteDownloadApi;
    $this->url = config('enconfig.itamservice_url');
  }
  /**
  * report Controller function is implemented to initiate a report page.
  * @author Bhushan Amrutkar
  * @access public
  * @package report
  * @return string
  */
  public function reports()
  {
    $topfilter              = array('gridsearch' => true, 'jsfunction' => 'reportsList()');
    $data['emgridtop']      = $this->emlib->emgridtop();
    $data['pageTitle']      = trans('title.reports');
    $reportcategory         = array();
    $reportcategory_resp    = $this->itam->getreportcategory(['form_params' => array()]);
    if(isset($reportcategory_resp['is_error']) && $reportcategory_resp['is_error']==false)
    {
      $reportcategory = _isset(_isset($reportcategory_resp, 'content'), 'records');
    }
    $reportsdata      = array();
    $reportsdata_resp = $this->itam->getreports(['form_params' => array()]);
    if(isset($reportsdata_resp['is_error']) && $reportsdata_resp['is_error']==false)
    {
      $reportsdata = _isset(_isset($reportsdata_resp, 'content'), 'records');
    }

    $data['reportcategory']   = $reportcategory;
    $data['reportsdata']      = $reportsdata;
    $data['includeView']      = view("Reports/reportdashboard", $data);
    return view('template', $data);
  }
  /**
  * This controller function is implemented to get list of reports.
  * @author Bhushan Amrutkar
  * @access public
  * @package reports
  * @param int $limit, int $page Pagination Variables
  * @param string $searchkeyword
  * @return json
  */
  public function reportslist()
  {
    try
    {
      $paging         = array();
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
      $reports_resp = $this->itam->getreports($options);

      if ($reports_resp['is_error'])
      {
        $is_error           = $reports_resp['is_error'];
        $msg                = $reports_resp['msg'];
      }
      else
      {
        $is_error                 = false;
        $reports                  = _isset(_isset($reports_resp, 'content'), 'records');
        $paging['total_rows']     = _isset(_isset($reports_resp, 'content'), 'totalrecords');
        $paging['showpagination'] = true;
        $paging['jsfunction']     = 'reportsList()';
        $view                     = 'Reports/reportslist';
        $content                  = $this->emlib->emgrid($reports, $view, $columns = array(), $paging);
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
      save_errlog("reportsList","This controller function is implemented to reports type list.",$this->request_params,$e->getmessage());  
      echo json_encode($response, true);
    }
    catch (\Error $e)
    {
      $response["html"]      = '';
      $response["is_error"]  = true;
      $response["msg"]       = $e->getmessage();
      save_errlog("reportsList","This controller function is implemented to reports type list.",$this->request_params,$e->getmessage());  
      echo json_encode($response, true);
    }
  }
 
 /**
  * This controller function is implemented to get list of reports Type.
  * @author Bhushan Amrutkar
  * @access public
  * @package reports
  * @param int $limit, int $page Pagination Variables
  * @param string $searchkeyword
  * @return json
  */
  public function reportsdetail(Request $request,$report_id = null)
  {
    $reportsdata = $reportmodules[0] = $mergeArr = array();
    $form_params['report_id'] = $report_id;
    $options                  = ['form_params' => $form_params];
    $reportsdata_resp = $this->itam->getreports($options);
    if(isset($reportsdata_resp['is_error']) && $reportsdata_resp['is_error']==false)
    {
      $reportsdata = _isset(_isset($reportsdata_resp, 'content'), 'records');
    }
    if (isset($reportsdata[0]['module']) && $reportsdata[0]['module']!="")
    {
      $searchkeyword  = $reportsdata[0]['module'];
      $form_params['searchkeyword'] = $searchkeyword;
      $reportmodules_resp    = $this->itam->getreportmodules(['form_params' => $form_params]);
      if(isset($reportmodules_resp['is_error']) && $reportmodules_resp['is_error']==false)
      {
        $reportmodules = _isset(_isset($reportmodules_resp, 'content'), 'records');
      }
      if (isset($reportsdata[0]['details'])) 
      {
        $details  = json_decode($reportsdata[0]['details'],true);
        if (is_array($details) && count($details)>0) 
        {
          $response['ci_templ_id']  = isset($details['ci_templ_id']) ? $details['ci_templ_id']:"";
          $response['ci_type_id']   = isset($details['ci_type_id']) ? $details['ci_type_id']:"";
        }
      }
      if ($reportmodules[0]['module_key'] == "CMDB" || $reportmodules[0]['module_key'] == "ALLCOMP")
      {
        $ci_templ_id = isset($response['ci_templ_id']) ? $response['ci_templ_id']:"";
        $ci_type_id  = isset($response['ci_type_id']) ? $response['ci_type_id']:"";

        if ($ci_templ_id !="" && $ci_type_id !="") 
        {
          $form_param['ci_templ_id']  = $ci_templ_id;
          $form_param['ci_type_id']   = $ci_type_id;
          $options     = ['form_params' => $form_param];
          $assetdata = $this->itam->getcitemplate($options);
          $assetdata = _isset(_isset($assetdata, 'content'), 'records');
          if(isset($assetdata['attributes']) && is_array($assetdata['attributes']) && count($assetdata['attributes']) > 0)
          {
            foreach($assetdata['attributes'] as $attr)
            {
              if (isset($attr['veriable_name']) && isset($attr['attribute']))
              {
                $mergeArr[$attr['veriable_name']] = $attr['attribute'];
              }
            }
          }
          $reportsdata[0]['ci_templ_id']  = $ci_templ_id;
          $reportsdata[0]['ci_type_id']   = $ci_type_id;
        }
      }
      $module_fields = json_decode($reportmodules[0]['module_fields'],true);
      if(is_array($module_fields) && count($module_fields)>0)
        $module_fields = json_encode(array_merge($mergeArr,$module_fields));

      $filter_fields = json_decode($reportmodules[0]['filter_fields'],true);
      if (array_key_exists('ram', $mergeArr) !== false) 
      {
        unset($mergeArr['ram']);
      }
      if (array_key_exists('hdd', $mergeArr) !== false) 
      {
        unset($mergeArr['hdd']);
      }
      if(is_array($filter_fields) && count($filter_fields)>0)
        $filter_fields = json_encode(array_merge($mergeArr,$filter_fields));

      if($module_fields)
        $reportmodules[0]['module_fields'] = $module_fields;
      if($filter_fields)
        $reportmodules[0]['filter_fields'] = $filter_fields;
    }

    $data['reportsdata']    = $reportsdata;
    $data['reportmodules']  = $reportmodules[0];
    $data['report_id']      = $report_id;
    $topfilter              = array('gridsearch' => false, 'jsfunction' => 'reportdetailsList_pr()');
    $data['emgridtop']      = $this->emlib->emgridtop($topfilter);
    $data['pageTitle']      = trans('title.reportdetails');
    $data['includeView']    = view("Reports/reports-det", $data);
    return view('template', $data);
  }
  /**
   * This controller function is implemented to get list of reports Type.
   * @author Bhushan Amrutkar
   * @access public
   * @package reports
   * @param int $limit, int $page Pagination Variables
   * @param string $searchkeyword
   * @return json
   */
  public function reportDetailsList(Request $request)
  {
    try
    {
      $paging         = array();
      $limit          = _isset($this->request_params, 'limit', config('enconfig.def_limit_short'));
      $page           = _isset($this->request_params, 'page', config('enconfig.page'));
      $searchkeyword  = _isset($this->request_params, 'searchkeyword');
      //$exporttype     = _isset($this->request_params, 'exporttype');
      $report_id     = _isset($this->request_params, 'report_id');
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
      $form_params['report_id'] = $report_id;
      $options                  = ['form_params' => $form_params];
      
      $reports_resp = $this->itam->getreportdetails_pr($options);       
      //print_r($reports_resp);die;           
      if ($reports_resp['is_error'])
      {
        $is_error           = $reports_resp['is_error'];
        $msg                = $reports_resp['msg'];
      }
      else
      {
        $is_error                 = false;
        $reports                  = _isset(_isset($reports_resp, 'content'), 'records');
        $tableheaders             = _isset(_isset($reports_resp, 'content'), 'tableheaders');
        $paging['total_rows']     = _isset(_isset($reports_resp, 'content'), 'totalrecords');
        if(!is_array($tableheaders))
          $tableheaders = array();
        $paging['showpagination'] = true;
        $paging['jsfunction']     = 'reportdetailsList_pr()';
        $view                     = 'Reports/reportsdetlist';
        $content                  = $this->emlib->emgrid($reports, $view,$tableheaders, $paging);
      }
      $response["html"]       = $content;
      $response["is_error"]   = $is_error;
      $response["msg"]        = $msg;
      $response["total_records"]        = $paging['total_rows'];
      echo json_encode($response);
    }
    catch (\Exception $e)
    {
      $response["html"]      = '';
      $response["is_error"]  = true;
      $response["msg"]       = $e->getmessage();
      save_errlog("reportsList","This controller function is implemented to reports type list.",$this->request_params,$e->getmessage());  
      echo json_encode($response, true);
    }
    catch (\Error $e)
    {
      $response["html"]      = '';
      $response["is_error"]  = true;
      $response["msg"]       = $e->getmessage();
      save_errlog("reportsList","This controller function is implemented to reports type list.",$this->request_params,$e->getmessage());  
      echo json_encode($response, true);
    }
  }
  /**
   * This controller function is implemented to get list of reports Type.
   * @author Bhushan Amrutkar
   * @access public
   * @package reports
   * @param int $limit, int $page Pagination Variables
   * @param string $searchkeyword
   * @return json
   */
  public function exportreport(Request $request)
  {
    try
    {
      
      $report_id     = _isset($this->request_params, 'report_id');
      $export_type   = _isset($this->request_params, 'report_type');
      $form_params['report_id']   = $report_id;
      $form_params['export_type'] = $export_type;
      $options       = ['form_params' => $form_params];
      $repot_resp  = $this->itam->exportreport(['form_params' => $form_params]);
      echo json_encode($repot_resp, true);
      //$data = $this->remoteapi->apicall("POST", $this->url, 'reports/export', $options);
      //$fp = public_path() .'/download/temp/file.zip';
      //$file_created = file_put_contents($fp, $data);
      //return response()->download($fp,'Report.zip'); 
    }
    catch (\Exception $e)
    {
      $response["html"]      = '';
      $response["is_error"]  = true;
      $response["msg"]       = $e->getmessage();
      save_errlog("reportsList","This controller function is implemented to reports type list.",$this->request_params,$e->getmessage());  
      echo json_encode($response, true);
    }
    catch (\Error $e)
    {
      $response["html"]      = '';
      $response["is_error"]  = true;
      $response["msg"]       = $e->getmessage();
      save_errlog("reportsList","This controller function is implemented to reports type list.",$this->request_params,$e->getmessage());  
      echo json_encode($response, true);
    }
  }
  /**
  * This controller function is used to load reports modules data.
  * @author Bhushan Amrutkar
  * @access public
  * @package reports
  * @return string
  */
  public function getreportmodules(Request $request)
  {
    try
    {
      $searchkeyword  = _isset($this->request_params, 'module_key');
      $form_params['searchkeyword']               = $searchkeyword;
      $reportmodules_resp    = $this->itam->getreportmodules(['form_params' => $form_params]);
      if(isset($reportmodules_resp['is_error']) && $reportmodules_resp['is_error']==false)
      {
        $reportmodules = _isset(_isset($reportmodules_resp, 'content'), 'records');
      }
      echo json_encode($reportmodules[0]);
    }
    catch (\Exception $e)
    {
      $data["html"]      = '';
      $data["is_error"]  = true;
      $data["msg"]       = $e->getmessage();
      save_errlog("reportsdelete","This controller function is implemented to delete reports.",$this->request_params,$e->getmessage());  
      echo json_encode($data, true);
    }
    catch (\Error $e)
    {
      $data["html"]      = '';
      $data["is_error"]  = true;
      $data["msg"]       = $e->getmessage();
      save_errlog("reportsdelete","This controller function is implemented to delete reports.",$this->request_params,$e->getmessage());  
      echo json_encode($data, true);
    }
  }
  /**
   * This controller function is implemented to get list of reports Type.
   * @author Bhushan Amrutkar
   * @access public
   * @package reports
   * @param int $limit, int $page Pagination Variables
   * @param string $searchkeyword
   * @return json
   */
  public function getreportnotifications(Request $request)
  {
    try
    { 
      $is_error  = false;
      $msg       = '';
      $count     = 'no';
      $count     = _isset($this->request_params, 'count');
      $form_params['count']  = $count;
      $options      = ['form_params' => $form_params];
      $notifi_resp  = $this->itam->getreportnotifications(['form_params' => $form_params]);
      if ($notifi_resp['is_error'])
      {
        $is_error           = $notifi_resp['is_error'];
        $msg                = $notifi_resp['msg'];
      }
      else
      {
        $is_error                 = false;
        $notification             = _isset(_isset($notifi_resp, 'content'), 'records');

        if(is_numeric($notification) || $count=="yes")
        {
          $content = $notification;
        }
        else
        {
          $view = 'Reports/reportnotifications';
          $view_data['notificationdata'] = $notification;
          $html = View::make($view, $view_data)->render();
          $content = $html;
        }
        
      }
      $response["html"]       = $content;
      $response["is_error"]   = $is_error;
      $response["msg"]        = $msg;
      echo json_encode($response, true);
    }
    catch (\Exception $e)
    {
      $response["html"]      = '';
      $response["is_error"]  = true;
      $response["msg"]       = $e->getmessage();
      save_errlog("reportsList","This controller function is implemented to reports type list.",$this->request_params,$e->getmessage());  
      echo json_encode($response, true);
    }
    catch (\Error $e)
    {
      $response["html"]      = '';
      $response["is_error"]  = true;
      $response["msg"]       = $e->getmessage();
      save_errlog("reportsList","This controller function is implemented to reports type list.",$this->request_params,$e->getmessage());  
      echo json_encode($response, true);
    }
  }
 /**
 * Function to download report
 * @author Bhushan Amrutkar
 * @access public
 * @package reports
 * @param  string attach_id
 * @return json
 */
  public function downloadreport(Request $request)
  {
    try
    {
       $response["html"]     = $request->all();
      $response["is_error"] = true;
      $response["msg"]      = '';
      $notification_id  = _isset($this->request_params, 'notification_id');
      $report_name      = _isset($this->request_params, 'report_name');
      $msg              = "";
      $content          = "";
      $is_error         = false;
      $file_created     = false;
      $user_id          = showuserid();
      $download_dir     = public_path() . '/download/temp/';
      $filepath         = $download_dir.$report_name.'.zip';
      $user_down_fp     = 'download/temp/'.$report_name.'.zip';
      $form_params['notification_id'] = $notification_id;
      $options                        = ['form_params' => $form_params];
      $response  = $this->itam->downloadreport($options);
      
      $get_data = _isset($response, 'content');
      $get_data = base64_decode($get_data,true);

      if($get_data != false) $file_created = file_put_contents($filepath, $get_data); //return false if failed

      if($file_created == false)
      {
        $response["html"]     = '';
        $response["is_error"] = true;
        $response["msg"]      = 'error';
      }
      else
      {
        $response["html"]     = $user_down_fp;
        $response["is_error"] = '';
        $response["msg"]      = 'success';
      }
    }
    catch (\Exception $e)
    {
      $response["html"]     = '';
      $response["is_error"] = true;
      $response["msg"]      = $e->getmessage();
      save_errlog("downloadreport", "This controller function is implemented to download report.", $this->request_params, $e->getmessage());
    }
    catch (\Error $e)
    {
      $response["html"]     = '';
      $response["is_error"] = true;
      $response["msg"]      = $e->getmessage();
      save_errlog("downloadreport", "This controller function is implemented to download report.", $this->request_params, $e->getmessage());
    }
    finally
    {
      echo json_encode($response);
    }
  }
  public function downloadPDF(Request $request) 
  {
    
    /*$session_data = $request->session()->all();
    print_r($session_data);
    die();*/
    //$test=['test'=>111];
    $po_id = $request->input('po_id');
    $options = ['po_id' => $po_id];
    // $options = ['po_id' => 'c78923f0-16b9-11ec-b078-4a4901e9af12'];
    
    $response = $this->itam->downloadPo($options);
    $assetoptions = ['form_params' => array('pr_po_id' => $po_id, 'asset_type' => 'po')];
    $response['assetdetails_resp'] = $this->itam->prpoassetdetails($assetoptions);
    $get_data = _isset(_isset($response, 'content'),'records');
     
  $file_name = str_replace('/','_',$get_data[0]['po_no']);

    //$response  = $this->itam->downloadPo($options);
    $pdf = PDF::loadView('PO_pdf',$response);
    // $pdf = PDF::loadView('PO_pdf', compact('show'));
    $pdf->setPaper('A4', 'portrait'); 
 
    // Render the HTML as PDF 
    // $pdf->render(); 
    //return $pdf->stream("codexworld", array("Attachment" => 0));
    // Output the generated PDF (1 = download and 0 = preview) 
    return $pdf->download(time().'_'.$file_name.'.pdf');
  }

  /**
   * Function to read the notification
   * @author Snehal Chaturvedi
   * @access public
   * @package reports
   * @param  string notification_id
   * @return json
  */
  public function readnotification(Request $request)
  {
    try
    {
    $notification_id     = _isset($this->request_params, 'notification_id');
    $notification_type   = _isset($this->request_params, 'notification_type');
    $action              = _isset($this->request_params, 'action');

    $form_params['notification_id']   = $notification_id;
    $form_params['notification_type'] = $notification_type;
    $form_params['action']            = $action;
    $options       = ['form_params' => $form_params];

    $read_report  = $this->itam->readnotification(['form_params' => $form_params]);

      echo json_encode($read_report, true);
    }
    catch (\Exception $e)
    {
      $data["html"]      = '';
      $data["is_error"]  = true;
      $data["msg"]       = $e->getmessage();
      save_errlog("reportseditsubmit","This controller function is implemented to update reports.",$this->request_params,$e->getmessage());  
      echo json_encode($data, true);
    }
    catch (\Error $e)
    {
      $data["html"]      = '';
      $data["is_error"]  = true;
      $data["msg"]       = $e->getmessage();
      save_errlog("reportseditsubmit","This controller function is implemented to update reports.",$this->request_params,$e->getmessage());  
      echo json_encode($data, true);
    }
  }
 /**
  * Function to return vendor,BV,cost center and location data in html option tag format
  * @author Bhushan Amrutkar
  * @access public
  * @package reports
  * @return json
  */
  public function getReportFormData()
  {
    $field           =  _isset($this->request_params, 'field');
    $selected_value  =  _isset($this->request_params, 'selected_value');
    $selected_value  = explode(",",$selected_value);
    $option = array();
    switch ($field) 
    {
      case "vendor":
          $vendorsDetailsOptions = "";
          $vendorsDetails        = $this->itam->getvendors($option);
          $vendorsDetailsArr     = _isset(_isset($vendorsDetails, 'content'), 'records');
          if ($vendorsDetailsArr)
          {
            foreach ($vendorsDetailsArr as $vendor)
            {
              $selected = is_array($selected_value) && in_array($vendor['vendor_id'], $selected_value) ? 'selected="selected"' : '';
              $vendorsDetailsOptions .= "<option value='".$vendor['vendor_id']."' ".$selected.">".$vendor['vendor_name']."</option>";
            }
          }
          return json_encode($vendorsDetailsOptions);
      break;

      case "location":
        $locationDetailsOptions = "";
        //============= Locations
        $options                = ['form_params' => array('order_byregion' => true)];
        $locationDetails        = $this->iam->getLocations($options);
        $locationDetailsArr     = _isset(_isset($locationDetails, 'content'), 'records');
        if ($locationDetailsArr)
        {
            $region_name = '';
            foreach ($locationDetailsArr as $lo)
            {
                if ($region_name != $lo['region_name'])
                {
                    if ($region_name != '')
                    {
                        $locationDetailsOptions .= '</optgroup>';
                    }
                    $locationDetailsOptions .= '<optgroup label="'.ucfirst($lo['region_name']).'">';
                }
                $selected = is_array($selected_value) && in_array($lo['location_id'], $selected_value) ? 'selected="selected"' : '';
                $locationDetailsOptions .= '<option value="'.$lo['location_id'].'" '.$selected.'>'.htmlspecialchars($lo['location_name']).'</option>';
                $region_name            = $lo['region_name'];
            }
            if ($region_name != '')
            {
                $locationDetailsOptions .= '</optgroup>';
            }
        }
        return json_encode($locationDetailsOptions);
      break;

      case "business_vertical":
        //============= Business Vertical
        $options                        = ['form_params' => array('order_bybu' => true)];
        $businessVerticalDetails        = $this->iam->getBusinessVertical($options);
        $businessVerticalDetailsArr     = _isset(_isset($businessVerticalDetails, 'content'), 'records');
        $businessVerticalDetailsOptions = "";
        if ($businessVerticalDetailsArr)
        {
            $bu_name = '';
            foreach ($businessVerticalDetailsArr as $bv)
            {
                if ($bu_name != $bv['bu_name'])
                {
                    if ($bu_name != '')
                    {
                        $businessVerticalDetailsOptions .= '</optgroup>';
                    }
                    $businessVerticalDetailsOptions .= '<optgroup label="'.ucfirst($bv['bu_name']).'">';
                }
                $selected = is_array($selected_value) && in_array($bv['bv_id'], $selected_value) ? 'selected="selected"' : '';
                $businessVerticalDetailsOptions .= "<option value='".$bv['bv_id']."' ".$selected.">".$bv['bv_name']."</option>";
                $bu_name = $bv['bu_name'];
            }
            if ($bu_name != '')
            {
                $businessVerticalDetailsOptions .= '</optgroup>';
            }
        }
        return json_encode($businessVerticalDetailsOptions);
      break;
      
      case "datacenter":
        $options = [
            'form_params' => array('order_byregion' => true),
        ];
        $datacenterDetails        = $this->iam->getDatacenters($options);
        $datacenterDetailsArr     = _isset(_isset($datacenterDetails, 'content'), 'records');
        $datacenterDetailsOptions = "";
        if ($datacenterDetailsArr)
        {
            $region_name = '';
            foreach ($datacenterDetailsArr as $dc)
            {
                if ($region_name != $dc['regions_name'])
                {
                    if ($region_name != '')
                    {
                        $datacenterDetailsOptions .= '</optgroup>';
                    }
                    $datacenterDetailsOptions .= '<optgroup label="'.ucfirst($dc['regions_name']).'">';
                }
                $selected = is_array($selected_value) && in_array($dc['dc_id'], $selected_value) ? 'selected="selected"' : '';
                $datacenterDetailsOptions .= "<option value='".$dc['dc_id']."' ".$selected.">".$dc['dc_name']."</option>";
                $region_name = $dc['regions_name'];
            }
            if ($region_name != '')
            {
              $datacenterDetailsOptions .= '</optgroup>';
            }
        }
        return json_encode($datacenterDetailsOptions);
      break;
      //PO Name
      case "po_name":
        $options = [
            'form_params' => array('order_byregion' => true),
        ];
        $ponameDetails        = $this->itam->purchaseorder($options);
        $ponameDetailsArr     = _isset(_isset($ponameDetails, 'content'), 'records');
        $ponameDetailsOptions = "";
        if ($ponameDetailsArr)
        {
          foreach ($ponameDetailsArr as $po)
          {
            $selected = is_array($selected_value) && in_array($po['po_name'], $selected_value) ? 'selected="selected"' : '';
            $ponameDetailsOptions .= "<option value='".$po['po_name']."' ".$selected.">".$po['po_name']."</option>";
          }
        }
        return json_encode($ponameDetailsOptions);
      break;

      //Contarct
      case "contracttype":
        $options = [
            'form_params' => array(),
        ];
        $contracttypeDetails        = $this->itam->getcontracttype($options);
        $contracttypeDetailsArr     = _isset(_isset($contracttypeDetails, 'content'), 'records');
        $contracttypeDetailsOptions = "";
        if ($contracttypeDetailsArr)
        {
          if (is_array($contracttypeDetailsArr) && count($contracttypeDetailsArr) > 0)
          {
            foreach ($contracttypeDetailsArr as $contracttype)
            {
              $selected = is_array($selected_value) && in_array($contracttype['contract_type_id'], $selected_value) ? 'selected="selected"' : '';
              $contracttypeDetailsOptions .= "<option value='".$contracttype['contract_type_id']."' ".$selected.">".$contracttype['contract_type']."</option>";
            }
          }
        }
        return json_encode($contracttypeDetailsOptions);
      break;

      case "contract_status":
        $contractstatusOptions = '';
        $contract_status = trans('commonarr.contract_status');
        if ($contract_status)
        {
          if (is_array($contract_status) && count($contract_status) > 0)
          {
            foreach ($contract_status as $key => $value)
            {
              $selected = is_array($selected_value) && in_array($key, $selected_value) ? 'selected="selected"' : '';
              $contractstatusOptions .= "<option value='".$key."' ".$selected.">".$value."</option>";
            }
          }
        }
        return json_encode($contractstatusOptions);
      break;
        
      case "renewed":
        $renewedOptions = "";
        $renewed = trans('commonarr.yes_no');
        if ($renewed)
        {
          if (is_array($renewed) && count($renewed) > 0)
          {
            foreach ($renewed as $key => $value)
            {
              $selected = is_array($selected_value) && in_array($key, $selected_value) ? 'selected="selected"' : '';
              $renewedOptions .= "<option value='".$key."' ".$selected.">".$value."</option>";
            }
          }
        }
        return json_encode($renewedOptions);
      break;
      //Software
      case "sw_category":
        $options = [
            'form_params' => array(),
        ];
        $swcatDetails        = $this->itam->getsoftwarecategory($options);
        $swcatDetailsArr     = _isset(_isset($swcatDetails, 'content'), 'records');
        $swcatDetailsOptions = "";
        if ($swcatDetailsArr)
        {
          if (is_array($swcatDetailsArr) && count($swcatDetailsArr) > 0)
          {
            foreach ($swcatDetailsArr as $swcat)
            {
              $selected = is_array($selected_value) && in_array($swcat['software_category_id'], $selected_value) ? 'selected="selected"' : '';
              $swcatDetailsOptions .= "<option value='".$swcat['software_category_id']."' ".$selected.">".$swcat['software_category']."</option>";
            }
          }
        }
        return json_encode($swcatDetailsOptions);
      break;

      case "sw_type":
         $options = [
            'form_params' => array(),
        ];
        $swtypeDetails        = $this->itam->getsoftwaretype($options);
        $swtypeDetailsArr     = _isset(_isset($swtypeDetails, 'content'), 'records');
        $swtypeDetailsOptions = "";
        if ($swtypeDetailsArr)
        {
          if (is_array($swtypeDetailsArr) && count($swtypeDetailsArr) > 0)
          {
            foreach ($swtypeDetailsArr as $swtype)
            {
              $selected = is_array($selected_value) && in_array($swtype['software_type_id'], $selected_value) ? 'selected="selected"' : '';
              $swtypeDetailsOptions .= "<option value='".$swtype['software_type_id']."' ".$selected.">".$swtype['software_type']."</option>";
            }
          }
        }
        return json_encode($swtypeDetailsOptions);
      break;
      
      case "sw_manufacturer":
        $options = [
            'form_params' => array(),
        ];
        $swmanufacturerDetails         = $this->itam->getsoftwaremanufacturer($options);
        $sw_manufacturerDetailsArr     = _isset(_isset($swmanufacturerDetails, 'content'), 'records');
        $sw_manufacturerDetailsOptions = "";
        if ($sw_manufacturerDetailsArr)
        {
          if (is_array($sw_manufacturerDetailsArr) && count($sw_manufacturerDetailsArr) > 0)
          {
            foreach ($sw_manufacturerDetailsArr as $sw_manufacturer)
            {
              $selected = is_array($selected_value) && in_array($sw_manufacturer['software_manufacturer_id'], $selected_value) ? 'selected="selected"' : '';
              $sw_manufacturerDetailsOptions .= "<option value='".$sw_manufacturer['software_manufacturer_id']."' ".$selected.">".$sw_manufacturer['software_manufacturer']."</option>";
            }
          }
        }
        return json_encode($sw_manufacturerDetailsOptions);
      break;
      //Purchase
      case "cost_center":
        $option                   = array();
        $costcenterDetails        = $this->itam->getcostcenters($option);
        $costcenterDetailsArr     = _isset(_isset($costcenterDetails, 'content'), 'records');
        $costcenterDetailsOptions ="";
        if ($costcenterDetailsArr)
        {
          foreach ($costcenterDetailsArr as $cc)
          {
            $selected = is_array($selected_value) && in_array($cc['cc_id'], $selected_value) ? 'selected="selected"' : '';
            $costcenterDetailsOptions .= "<option value='".$cc['cc_id']."' ".$selected.">".$cc['cc_code']."-".$cc['cc_name']."</option>";
          }
        }
        return json_encode($costcenterDetailsOptions);
      break;

      case "po_status":
        $postatusOptions = '';
        $po_status = trans('commonarr.po_status');
        if ($po_status)
        {
          if (is_array($po_status) && count($po_status) > 0)
          {
            foreach ($po_status as $key => $value)
            {
              $selected = is_array($selected_value) && in_array($key, $selected_value) ? 'selected="selected"' : '';
              $postatusOptions .= "<option value='".$key."' ".$selected.">".$value."</option>";
            }
          }
        }
        return json_encode($postatusOptions);
      break;

      case "pr_priority":
        $priorityOptions = '';
        $priority = trans('commonarr.pr_priority');
        if ($priority)
        {
          if (is_array($priority) && count($priority) > 0)
          {
            foreach ($priority as $key => $value)
            {
              $selected = is_array($selected_value) && in_array($key, $selected_value) ? 'selected="selected"' : '';
              $priorityOptions .= "<option value='".$key."' ".$selected.">".$value."</option>";
            }
          }
        }
        return json_encode($priorityOptions);
      break;
      //Assets
      case "asset_status":
        $assetstatusOptions = '';
        $assetstatus = trans('commonarr.asset_status');
        if ($assetstatus)
        {
          if (is_array($assetstatus) && count($assetstatus) > 0)
          {
            foreach ($assetstatus as $key => $value)
            {
              $selected = is_array($selected_value) && in_array($key, $selected_value) ? 'selected="selected"' : '';
              $assetstatusOptions .= "<option value='".$key."' ".$selected.">".$value."</option>";
            }
          }
        }
        return json_encode($assetstatusOptions);
      break;

      case "citype":
        $citypesOptions = '';
        $citypes = $this->itam->citypes($option);
        $citypes = _isset(_isset($citypes, 'content'), 'records');
        if ($citypes)
        {
          if (is_array($citypes) && count($citypes) > 0)
          {
            foreach ($citypes as $citype)
            {
              $selected = is_array($selected_value) && in_array($citype['ci_type_id'], $selected_value) ? 'selected="selected"' : '';
              $citypesOptions .= "<option value='".$citype['ci_type_id']."' ".$selected.">".$citype['citype']."</option>";
            }
          }
        }
        return json_encode($citypesOptions);
      break;

      case "ciname":
        $cinameOptions = '';
        $ciname = $this->itam->getallcitemplates($option);
        $ciname = _isset($ciname, 'content');
        if ($ciname)
        {
          if (is_array($ciname) && count($ciname) > 0)
          {
            foreach ($ciname as $ci)
            {
              $selected = is_array($selected_value) && in_array($ci['ci_templ_id'], $selected_value) ? 'selected="selected"' : '';
              $cinameOptions .= "<option value='".$ci['ci_templ_id']."' ".$selected.">".$ci['ci_name']."</option>";
            }
          }
        }
        return json_encode($cinameOptions);
      break;
  
      case "":
        $Options = "";        
        $Options .= "<option value=''></option>";
        return json_encode($Options);
      break;
      default:
      return json_encode(array());
    } 
  }



  }

