<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnContractAttachment extends Model 
{
	use HasBinaryUuid;
    public $incrementing = false;
	protected $table = 'en_contract_attachment';
   //	public $timestamps = false;	
    protected $fillable = [
        'attach_id', 'contract_id','attachment_name','created_by', 'status',
    ];
	
	protected $primaryKey = 'attach_id';
	public function getKeyName()
    {
        return 'attach_id';
    }	
    protected function getAttachments($contract_id = NULL)
    {
            $query = EnContractAttachment::select(DB::raw('BIN_TO_UUID(attach_id) AS attach_id'), DB::raw('BIN_TO_UUID(contract_id) AS contract_id'),'attachment_name',DB::raw('BIN_TO_UUID(created_by) AS created_by'), 'status', 'created_at', 'updated_at')   
            ->where('status', '!=', 'd')            
            ->orderBy('created_at', 'desc');   

            $query->where(function ($query) use ($contract_id)
            {
                $query->when($contract_id, function ($query) use ($contract_id)
                    {
                        return $query->where('contract_id', '=', DB::raw('UUID_TO_BIN("'.$contract_id.'")'));
                    });
            });
                     

           $data = $query->get(); 
           return $data; 
             
    }
}