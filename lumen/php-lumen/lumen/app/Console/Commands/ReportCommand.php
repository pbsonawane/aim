<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\ReportService;
use App\Models\EnReports;
use App\Models\EnReportNotifications;

use Illuminate\Support\Facades\File;
use Illuminate\Filesystem\Filesystem;
use App\Exports\ReportExport;


use PDF;
use Excel;
class ReportCommand extends Command
{
  /**
  * The name and signature of the console command.
  *
  * @var string
  */
  protected $signature = 'report:generate {report_id?} {type?} {notification_id?}';

  /**
  * The console command description.
  *
  * @var string
  */
  protected $description = 'generate report';

  /**
  * Create a new command instance.
  *
  * @return void
  */
  public function __construct()
  {
    parent::__construct();
    $this->ReportService = new ReportService();
  }
  /**
  * Execute the console command.
  *
  * @return mixed
  */
  public function handle()
  {
    try
    {
      ini_set('max_execution_time', 0);
      $report_id        = $this->argument('report_id',null);
      $export_type      = $this->argument('type',null);
      $notification_id  = $this->argument('notification_id',null);
      $reports_id_bin   = DB::raw('UUID_TO_BIN("'.$report_id.'")');
      $obj_repeort_data = EnReports::where('report_id',$reports_id_bin)->first();
      if ($report_id!=null && $obj_repeort_data && isset($obj_repeort_data->report_name)) 
      {
        $resp_data = $this->ReportService->generate_report($report_id,'pdf',true);
        if (isset($resp_data['is_error']) && $resp_data['is_error']==false && !empty($resp_data['reportsdata'])) 
        {
          if (isset($resp_data['pagination']['from_page']) && isset($resp_data['pagination']['to_page'])) 
          {
            $data_from = $resp_data['pagination']['from_page'];
            $data_to   = $resp_data['pagination']['to_page'];

            $from_time = $resp_data['from_time'];
            $to_time   = $resp_data['to_time'];
            
            $pdf_reports_created = 0;

            for ($i=$data_from; $i <= $data_to; $i++) 
            { 
              $resp_data = $this->ReportService->generate_report($report_id,'pdf',false,$i);
              $resp_data['complete_filter'] = $i==$data_from ? "yes Filter" : "";

              if (isset($resp_data['is_error']) && $resp_data['is_error']==false) 
              {
                $from_time = $resp_data['from_time'];
                $to_time   = $resp_data['to_time'];
                if (isset($resp_data['reportsdata']) && sizeof($resp_data['reportsdata']) > 0 )
                {
                  if ($export_type == "pdf") 
                  {
                    $report_path = 'app/public/reports/'.$obj_repeort_data->report_name;
                    $report_name = $obj_repeort_data->report_name.'_'.$i.'.pdf';
                    $report_name = preg_replace('/\s+/', '_', $report_name);
                    $storage_path = storage_path($report_path);
                    $file_with_path = $storage_path.'/'.$report_name;
                    if ($i==1) 
                    {
                        $file = new Filesystem;
                        $file->cleanDirectory($storage_path);                                       
                    }
                    if (!file_exists($storage_path)) 
                    {
                        File::makeDirectory($storage_path, $mode = 0777, true, true);
                    }
                    $resp_data['from_time']      = $from_time;
                    $resp_data['to_time']        = $to_time;
                    $resp_data['report_title']   = isset($obj_repeort_data->report_name) ? $obj_repeort_data->report_name : '';
                    $resp_data['totalrecords']   = isset($resp_data['totalrecords']) ? $resp_data['totalrecords'] : 0;

                    $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('Reports.reportlist', $resp_data)->setPaper('a4', 'landscape');
                    $file = $pdf->save($file_with_path);
                    if (file_exists($file_with_path)) 
                    {
                      $pdf_reports_created++ ;
                    }
                  } 
                  if ($export_type == "excel" || $export_type == "csv") 
                  {
                    $extension = '.xlsx';
                    if ($export_type =="excel") 
                    {
                        $extension ='.xlsx';
                    }
                    if ($export_type =="csv") 
                    {
                        $extension ='.csv';
                    }
                    $report_path = 'app/public/reports/'.$obj_repeort_data->report_name;
                    $report_name = $obj_repeort_data->report_name.'_'.$i.$extension;
                    $report_name = preg_replace('/\s+/', '_', $report_name);
                    $storage_path = storage_path($report_path);
                    $file_with_path = $obj_repeort_data->report_name.'/'.$report_name;
                    if ($i==1) 
                    {
                        $file = new Filesystem;
                        $file->cleanDirectory($storage_path);                                       
                    }
                    if (!file_exists($storage_path)) 
                    {
                        File::makeDirectory($storage_path, $mode = 0777, true, true);
                    }
                    $resp_data['report_title']   = isset($obj_repeort_data->report_title) ? $obj_repeort_data->report_title : '';
                    $file = Excel::store(new ReportExport($resp_data), $file_with_path ,'reports');
                    if ($file) 
                    {
                      $pdf_reports_created++ ;
                    }
                  }
                }
              }
            }
          }
        }
        else
        {
          $pdf_reports_created = 0; 
          $data_to = $i = 1;
          if ($export_type == "pdf") 
          {
             
              $report_path      = 'app/public/reports/'.$obj_repeort_data->report_name;
              $report_name    = $obj_repeort_data->report_name.'_'.$i.'.pdf';
              $report_name    = preg_replace('/\s+/', '_', $report_name);
              $storage_path   = storage_path($report_path);
              $file_with_path = $storage_path.'/'.$report_name;
              if ($i==1) 
              {
                $file = new Filesystem;
                $file->cleanDirectory($storage_path);                                       
              }
              if (!file_exists($storage_path)) 
              {
                  File::makeDirectory($storage_path, $mode = 0777, true, true);
              }
              $resp_data['report_title']   = isset($obj_repeort_data->report_name) ? $obj_repeort_data->report_name : '';
              $resp_data['totalrecords']   = isset($resp_data['totalrecords']) ? $resp_data['totalrecords'] : 0;

              $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('Reports.reportlist', $resp_data)->setPaper('a4', 'landscape');
              $file = $pdf->save($file_with_path);
              if (file_exists($file_with_path)) 
              {
                $pdf_reports_created++ ;
              }
          } 
          if ($export_type == "excel" || $export_type == "csv") 
          {
            $extension = '.xlsx';
            if ($export_type =="excel") 
            {
                $extension ='.xlsx';
            }
            if ($export_type =="csv") 
            {
                $extension ='.csv';
            }
            $report_path = 'app/public/reports/'.$obj_repeort_data->report_name;
            $report_name = $obj_repeort_data->report_name.'_'.$i.$extension;
            $report_name = preg_replace('/\s+/', '_', $report_name);
            $storage_path = storage_path($report_path);
            $file_with_path = $obj_repeort_data->report_name.'/'.$report_name;
            if ($i==1) 
            {
                $file = new Filesystem;
                $file->cleanDirectory($storage_path);                                       
            }
            if (!file_exists($storage_path)) 
            {
                File::makeDirectory($storage_path, $mode = 0777, true, true);
            }
            $resp_data['report_title']   = isset($obj_repeort_data->report_title) ? $obj_repeort_data->report_title : '';
            $file = Excel::store(new ReportExport($resp_data), $file_with_path ,'reports');
            if ($file) 
            {
              $pdf_reports_created++ ;
            }
          }
        }
        if (isset($file) && $file) 
        {
          if (isset($pdf_reports_created) && isset($data_to) && $pdf_reports_created == $data_to) 
          {
              $obj_repeort_update_data = EnReports::where('report_id',$reports_id_bin)->update(['status' => 'y']);

              $notification_id_bin = DB::raw('UUID_TO_BIN("'.$notification_id.'")');
              $obj_notification_data = EnReportNotifications::where('notification_id',$notification_id_bin)->update(['status' => 'y']);

              $obj_notifcation = EnReportNotifications::where('notification_id',$notification_id_bin)->first();
              if ($obj_notifcation && isset($obj_notifcation->report_name) && $obj_notifcation->report_name!='' && isset($obj_notifcation->status) && $obj_notifcation->status=='y')
              {
                if ($export_type =="pdf") 
                {
                  $extension ='_1.pdf';
                }
                if ($export_type =="excel") 
                {
                  $extension ='_1.xlsx';
                }
                if ($export_type =="csv") 
                {
                  $extension ='_1.csv';
                }

                $report_path    = 'app/public/reports/'.$obj_repeort_data->report_name;
                $report_name    = $obj_repeort_data->report_name.$extension;
                $report_name    = preg_replace('/\s+/', '_', $report_name);
                $storage_path   = storage_path($report_path);
                $file_with_path = $storage_path.'/'.$report_name;
                $zipfilePath    = storage_path('app/public/reports/'); 
                if (file_exists($file_with_path)) 
                {
                  $path = $storage_path;
                  $zip_path = $zipfilePath.'/'.$obj_notifcation->report_name.'.zip';
                  $zip = new \ZipArchive();

                  if ($zip->open($zip_path, \ZIPARCHIVE::CREATE | \ZIPARCHIVE::OVERWRITE) !== TRUE) 
                  {
                    die ("An error occurred creating your ZIP file.");
                  }
                  $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));  
                  foreach ($files as $name => $file)
                  {
                    if (!$file->isDir()) 
                    {
                      $filePath     = $file->getRealPath();
                      $relativePath = substr($filePath, strlen($path) + 1);
                      $zip->addFile($filePath, $relativePath);
                    }
                  }
                  $zip->close();
                  return true;
                }
              } 
          }
          return true;
        }
      }
      else
      {
        Log::error('Invalid report id or Report not found.');
        return false;
      }
      return false;
    }
    catch(\Exception $e)
    {
      return false;
    }
    catch(\Error $e)
    {
      return false;
    }
  }
}