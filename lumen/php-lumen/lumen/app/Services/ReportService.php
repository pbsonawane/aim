<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

use App\Models\EnReports;

class ReportService
{
    public function __construct()
    {

    }

    public function generate_report($report_id=null,$type='',$get_paginate=false,$current_page=1)
    {
        if ($report_id!=null) 
        {

            $request_params = array();
            $response       = array();
            
            $limit          = config('enconfig.report_limit');
            $page           = isset($current_page) ? (int) $current_page : config('enconfig.page');
            $limit_offset   = limitoffset($limit,$page);
            $page           = $limit_offset['page'];
            $limit          = $limit_offset['limit'];
            $offset         = $limit_offset['offset'];
            $content        = null;
            $request_params['report_id']    = $report_id;
            $request_params['limit']        = $limit;
            $request_params['page']         = $page;
            $request_params['offset']       = $offset;
            $request = new \Illuminate\Http\Request();
            $request->replace($request_params);
            $reportsdetail_resp = app('App\Http\Controllers\Reports\ReportsController')->reportsdetail($request);
            $reportsdetail_resp = $reportsdetail_resp->getOriginalContent();
            $reportsdetail_resp = $reportsdetail_resp['data'];
            $reportsdetail_resp['records']  = json_decode($reportsdetail_resp['records'], true);
            $msg        = '';
            $is_error   = false;
            $paging     = array();

            if (isset($reportsdetail_resp['is_error']) && $reportsdetail_resp['is_error'])
            {
               $is_error = $reportsdetail_resp['is_error'];
               $msg      = $reportsdetail_resp['msg'];
            }
            else
            {
                if (isset($get_paginate) && $get_paginate==true) 
                {
                    $is_error               = false;
                    $paging['total_rows']   = isset($reportsdetail_resp['totalrecords']) ? $reportsdetail_resp['totalrecords'] : 0;
                    $paging['limit']        = (int) $limit;
                    $paging['offset']       = (int) $offset;
                    $paging['from_page']    = (int) $page;

                    if (isset($limit) && $limit > 0) 
                    {
                        $to_page = (int) ceil($paging['total_rows']/$limit);
                    }
                    else
                    {
                        $to_page = 1;
                    }

                    $paging['to_page']          = $to_page;
                    $response["pagination"]     = $paging;
                    $response['from_time']      = isset($reportsdetail_resp['from_time']) ? $reportsdetail_resp['from_time'] : ""; 
                    $response['to_time']        = isset($reportsdetail_resp['to_time']) ? $reportsdetail_resp['to_time'] : "";

                    $response['reportsdata'] = isset($reportsdetail_resp['records']) ? $reportsdetail_resp['records'] : null; 
                    $response['tableheaders']= isset($reportsdetail_resp['tableheaders']) ? $reportsdetail_resp['tableheaders'] : null;
                    $response['total_rows']  = isset($reportsdetail_resp['totalrecords']) ? $reportsdetail_resp['totalrecords'] : 0;
                }
                else
                {
                    $is_error              = false;
                    $response['from_time']      = isset($reportsdetail_resp['from_time']) ? $reportsdetail_resp['from_time'] : ""; 
                    $response['to_time']        = isset($reportsdetail_resp['to_time']) ? $reportsdetail_resp['to_time'] : "";
                    $response['tableheaders']= isset($reportsdetail_resp['tableheaders']) ? $reportsdetail_resp['tableheaders'] : null;
                    $response['reportsdata']= isset($reportsdetail_resp['records']) ? $reportsdetail_resp['records'] : null;
                    $response['total_rows']  = isset($reportsdetail_resp['totalrecords']) ? $reportsdetail_resp['totalrecords'] : 0;

                    $paging['total_rows']  = isset($reportsdetail_resp['totalrecords']) ? $reportsdetail_resp['totalrecords'] : 0;
                }
            }

            $response["is_error"] = $is_error;
            $response["msg"] = $msg;

            return $response;
        }
    }
}

?>