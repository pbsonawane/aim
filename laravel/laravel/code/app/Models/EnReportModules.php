<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnReportModules extends Model 
{
	use HasBinaryUuid;
    public $incrementing = false;
	protected $table = 'en_report_modules';
   	//public $timestamps = false;	
    protected $fillable = [
        'module_id', 'module_name', 'module_key','module_fields','filter_fields','date_filter_fields','orignal_fields','module_description','status'
    ];
	
	protected $primaryKey = 'module_id';
	public function getKeyName()
    {
        return 'module_id';
    }	
    /**
    * This is model function is used get all Report Module's data with its foregin key data
    * @author Shadab Khan
    * @access protected
    * @param UUID $module_id Unique Module Id
    * @param array $inputdata
    * @param module_key string
    * @param boolean $count
    * @param string $searchkeyword Search keyword
    * @param int  $limit, int $offset Pagination variables    
    * @return array
    */
    protected function getmodules($module_id, $inputdata=[], $count=false,$module_key=null)
    {
        $searchkeyword = _isset($inputdata,'searchkeyword');
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_report_modules')
				->select(DB::raw('BIN_TO_UUID(module_id) AS module_id'), 'module_name', 'module_key','module_fields','filter_fields','date_filter_fields','module_description','status')                
                ->where('en_report_modules.status', '!=', 'd');
                //->orderBy('module_name','ASC');                         
                $query->where(function ($query) use ($searchkeyword, $module_id,$module_key){
                    $query->where(function ($query) use ($searchkeyword, $module_id) {                
                        $query->when($searchkeyword, function ($query) use ($searchkeyword)
                        {
                                return $query->where('en_report_modules.module_name', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_report_modules.module_key', 'like', '%' . $searchkeyword . '%');                      
                        });
                    });
                    $query->when($module_id, function ($query) use ($module_id)
                    {
                        return $query->where('en_report_modules.module_id', '=', DB::raw('UUID_TO_BIN("'.$module_id.'")'));
                    });
                });
                /* Pagination Code Start */
                $query->when(!$count, function ($query) use ($inputdata)
                {
                    if( isset($inputdata["offset"]) && isset($inputdata["limit"]) )
                    {
                        return $query->offset($inputdata["offset"])->limit($inputdata["limit"]);
                    }
                });     
                 
        $data = $query->get();       

        if($count)
        {
            return count($data);
        }
        else
        {      
            return $data;
        }
    }
}