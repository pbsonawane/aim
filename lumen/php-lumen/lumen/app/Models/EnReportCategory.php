<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnReportCategory extends Model 
{
	use HasBinaryUuid;
    public $incrementing    = false;
	protected $table        = 'en_report_category';
   	//public $timestamps    = false;	
    protected $fillable     = [
        'report_cat_id','report_category','description','status'
    ];
	protected $primaryKey   = 'report_cat_id';
	public function getKeyName()
    {
        return 'report_cat_id';
    }
    protected function getreportCategory($report_cat_id, $inputdata=array(), $count=false)
    {
        $searchkeyword = _isset($inputdata,'searchkeyword');
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_report_category')   
                ->select(DB::raw('BIN_TO_UUID(report_cat_id) AS report_cat_id'),'report_category','description')
                ->where('en_report_category.status', '!=', 'd');
                
                $query->where(function ($query) use ($searchkeyword, $report_cat_id){
                    $query->where(function ($query) use ($searchkeyword, $report_cat_id) {
                        $query->when($searchkeyword, function ($query) use ($searchkeyword)
                            {
                                
                                return $query->where('en_report_category.report_category', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_report_category.description', 'like', '%' . $searchkeyword . '%');
                            });       
                        });
                        $query->when($report_cat_id, function ($query) use ($report_cat_id)
                        {
                            return $query->where('en_report_category.report_cat_id', '=', DB::raw('UUID_TO_BIN("'.$report_cat_id.'")'));
                        });});
                $query->when(!$count, function ($query) use ($inputdata)
                        {
                            if( isset($inputdata["offset"]) && isset($inputdata["limit"]) )
                            {
                                return $query->offset($inputdata["offset"])->limit($inputdata["limit"]);
                            }
                        });
        $data = $query->get();
        if($count)
            return   count($data);
        else      
            return $data;
    }
}