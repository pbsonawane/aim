<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnContractHistory extends Model 
{
	use HasBinaryUuid;
    public $incrementing = false;
	protected $table = 'en_contract_history';
   //	public $timestamps = false;	
    protected $fillable = [
        'history_id', 'contract_id', 'action','details','created_by', 'notify_to_id', 'status', 'comment', 'created_at', 'updated_at'
    ];
	
	protected $primaryKey = 'history_id';
	public function getKeyName()
    {
        return 'history_id';
    }	

    protected function getNotifications($logged_in_user)
    {
        $query_one = DB::table('en_contract_history as h') 
                ->select(DB::raw('BIN_TO_UUID(history_id) AS history_id'), DB::raw('BIN_TO_UUID(contract_id) AS contract_id'), 'action','h.details', DB::raw('BIN_TO_UUID(created_by) AS created_by'), DB::raw('BIN_TO_UUID(notify_to_id) AS notify_to_id'), 'comment', 'h.created_at')
                ->where('notify_to_id',DB::raw('UUID_TO_BIN("' . $logged_in_user . '")'))
                ->join('en_contract AS con', 'con.contract_id', '=', 'h.contract_id');

           
               return $data; 
    }

}