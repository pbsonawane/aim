<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnSoftwareHistory extends Model 
{	
	use HasBinaryUuid;
    public $incrementing = false;
	// Testing COment
	protected $table = 'en_software_history';
   	public $timestamps = true;	
    protected $fillable = [
        'software_id', 'user_id', 'action', 'message','created_at','updated_at'
    ];
	protected $primaryKey = 'id';

  
	public function getKeyName()
    {
        return 'id';
    }  
    /* This is model function is used get history of software operations

    * @author       Kavita Daware
    * @access       protected
    * @param        software_id
    * @param_type   Integer
    * @return       Array
    * @tables       en_software_history
    */

    protected function getswhistory($software_id,$inputdata=[], $count=false)
    {
        $searchkeyword = _isset($inputdata,'searchkeyword');
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_software_history')   
                ->select(DB::raw('BIN_TO_UUID(software_id) AS software_id'),DB::raw('BIN_TO_UUID(user_id) as user_id'),'action','message','created_at','updated_at')
                ->where(DB::raw('BIN_TO_UUID(software_id)'), $software_id)
                 ->orderBy('updated_at','DESC');
               // ->where('en_software_installation.status', '!=', 'd');


                $query->where(function ($query) use ($searchkeyword, $software_id){
                    $query->where(function ($query) use ($searchkeyword, $software_id) {
                        $query->when($searchkeyword, function ($query) use ($searchkeyword)
                            {
                                
                                 return $query->where('action', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('message', 'like', '%' . $searchkeyword . '%');
                                
                                
                               });       
                        });
                        $query->when($software_id, function ($query) use ($software_id)
                        {
                            return $query->where('software_id', '=', DB::raw('UUID_TO_BIN("'.$software_id.'")'));
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