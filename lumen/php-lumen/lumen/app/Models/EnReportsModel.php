<?php
namespace App\Models;

use DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnReportsModel extends Model
{

    protected $table = 'en_reports';
	protected $fillable = 
					[
        				'report_name', 
        				'report_cat_id',
        				'report_category',
        				'report_title',
        				'module',
        				'filter_value',
        				'user_id',
        				'share_report',
        				'schedule_type',
        				'gen_report_at',
        				'gen_report_for',
        				'report_format',
        				'email_to',
        				'email_subject',
        				'email_body',
        				'next_report_time',
        				'enableschedule	',
        				'status',
        				'last_updated',
        				'date'
    				];
    protected $primaryKey = 'report_id';
    public function getKeyName()
    {
        return 'report_id';
    }
    
    protected function getreport($inputdata = array(), $count = false)
    {
        $query = DB::table('en_reports')   
                ->select('report_id','report_name','report_type', 'report_category', 'report_title','module','filter_value', 'user_id', 'share_report', 'schedule_type','gen_report_at','gen_report_for', 'report_format', 'email_to', 'email_subject','email_body','next_report_time', 'enableschedule', 'status')
                ->where('status', '!=', 'd')
                ->orderBy('gen_report_at', 'DESC')->get();
       
        return $query;
            
    }
}
