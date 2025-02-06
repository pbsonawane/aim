<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnCostCenters extends Model 
{
	use HasBinaryUuid;
    public $incrementing = false;
	protected $table = 'en_cost_centers';
   	//public $timestamps = false;	
    protected $fillable = [
        'cc_id', 'cc_code', 'cc_name', 'description', 'locations','departments','status'
    ];
	
	protected $primaryKey = 'cc_id';
	public function getKeyName()
    {
        return 'cc_id';
    }
    /*  
    * This is model function is used get all Cost Centers data

    * @author       Vikash Kumar
    * @access       public
    * @param        cc_id
    * @param_type   integer
    * @return       array
    * @tables       en_cost_centers
    */
	protected function getcostcenters($cc_id, $inputdata=[], $count=false)
    {
        $searchkeyword = _isset($inputdata,'searchkeyword');
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_cost_centers')   
                ->select(DB::raw('BIN_TO_UUID(cc_id) AS cc_id'), 'cc_code','cc_name', 'description', DB::raw('BIN_TO_UUID(locations) AS locations'),'departments', 'status')       
                ->where('en_cost_centers.status', '!=', 'd');
                 $query->where(function ($query) use ($searchkeyword, $cc_id){
                    $query->where(function ($query) use ($searchkeyword, $cc_id) {
                        $query->when($searchkeyword, function ($query) use ($searchkeyword)
                            {
                                
                                  return $query->where('en_cost_centers.cc_name', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_cost_centers.cc_code', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_cost_centers.description', 'like', '%' . $searchkeyword . '%');
                            });       
                        });
                        $query->when($cc_id, function ($query) use ($cc_id)
                        {
                            return $query->where('en_cost_centers.cc_id', '=', DB::raw('UUID_TO_BIN("'.$cc_id.'")'));
                        });});
                /*$query->where(function ($query) use ($searchkeyword){
                    $query->where(function ($query) use ($searchkeyword) {
                        $query->when($searchkeyword, function ($query) use ($searchkeyword)
                            {
                                
                                 return $query->where('en_cost_centers.cc_name', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_cost_centers.cc_code', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_cost_centers.description', 'like', '%' . $searchkeyword . '%');
                            });       
                        });
                      });*/

 
                $query->when(!$count, function ($query) use ($inputdata)
                        {
                            if( isset($inputdata["offset"]) && isset($inputdata["limit"]) )
                            {
                                return $query->offset($inputdata["offset"])->limit($inputdata["limit"]);
                            }
                        });
                $data = $query->get();                        
                                            
        if($count)
            return  count($data);
        else      
            return $data;    
    	}

    protected function checkforrelation($cc_id)
    {
        if($cc_id)
        {
            $cc_data = EnCostCenters::where('cc_id', DB::raw('UUID_TO_BIN("'.$cc_id.'")'))->first();

            if($cc_data)
            {    
                    //apilog('sdhfksdf'.json_encode($cc_data));
                    $cc_data->update(['status' => 'd']);            
                    $cc_data->save();     
                   /*$queries    = DB::getQueryLog();
                    $last_query = end($queries); 
                    apilog(json_encode($last_query));   */          
                    
                    $data['data']['deleted_id'] = $cc_id;
                    $data['message']['success']= showmessage('118', ['{name}'], ['Cost Center']);
                    $data['status'] = 'success';
               
            }
            else
            {
                $data['data'] = NULL;
                $data['message']['error'] = showmessage('119', ['{name}'], ['Cost Center']);
                $data['status'] = 'error';                             
            }               
        }
        else
        {
            $data['data'] = NULL;
            $data['message']['error'] = showmessage('123', ['{name}'], ['Cost Center']);
            $data['status'] = 'error';                             
        }   
        return $data;    
    }  
    
}


