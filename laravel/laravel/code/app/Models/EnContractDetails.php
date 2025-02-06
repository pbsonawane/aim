<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnContractDetails extends Model 
{
	use HasBinaryUuid;
    public $incrementing = false;
	protected $table = 'en_contract_details';
   	//public $timestamps = false;	
    protected $fillable = [
        'contract_details_id','contract_id','support','description','cost','asset_id','attachments'];
    
  
	protected $primaryKey = 'contract_details_id';
	public function getKeyName()
    {
        return 'contract_details_id';
    }

    protected function getcontractdetails($contract_details_id, $inputdata=[], $count=false)
    {
        $searchkeyword = _isset($inputdata,'searchkeyword');
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        
        
        $query = DB::table('en_contract_details')   	
        ->select(DB::raw('BIN_TO_UUID(contract_details_id) AS contract_details_id'),DB::raw('BIN_TO_UUID(contract_id) AS contract_id'),'support','description','attachments','cost','asset_id')	
        ->where('en_contract_details');
                
               
        $data = $query->get();                        
                                            
        if($count)
            return   count($data);
        else      
            return $data;    

    }
    protected function getAttachments($contract_details_id = NULL)
    {
            $query = EnContractDetails::select( DB::raw('BIN_TO_UUID(contract_details_id) AS contract_details_id'), 'attachments','created_at', 'updated_at')            
            ->orderBy('created_at', 'desc');   
            $query->where(function ($query) use ($contract_details_id)
            {
                $query->when($contract_details_id, function ($query) use ($contract_details_id)
                    {
                        return $query->where('contract_details_id', '=', DB::raw('UUID_TO_BIN("'.$contract_details_id.'")'));
                    });
            });

           $data = $query->get(); 
           return $data; 
             
    }
    
    protected function associatedassetremove($contract_id = NULL,$asset_id = NULL)
    {
           
            $query = DB::statement("UPDATE en_contract_details SET asset_id = JSON_REMOVE(
            asset_id, REPLACE(json_search(asset_id, 'all', '".$asset_id."'), '\"', ''))
            WHERE json_search(asset_id, 'all','".$asset_id."') IS NOT NULL AND contract_id = UUID_TO_BIN('".$contract_id."')");

            return $query;
            
/*
            $query = DB::table('en_contract_details')->where('contract_id', $contract_id)->update(['asset_id' => DB::raw('JSON_REMOVE(asset_id, "'.$asset_id.'")')]);
            $queries = DB::getQueryLog();
            $last_query = end($queries);
            print_r($query);
            return $query; */

             
    }
	
    
}