<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnPrPoHistory extends Model 
{
	use HasBinaryUuid;
    public $incrementing = false;
	protected $table = 'en_pr_po_history';
   //	public $timestamps = false;	
    protected $fillable = [
        'history_id', 'pr_po_id', 'history_type', 'action','details','created_by', 'created_by_name', 'notify_to_id', 'status', 'comment', 'created_at', 'updated_at'
    ];
	
	protected $primaryKey = 'history_id';
	public function getKeyName()
    {
        return 'history_id';
    }	

    protected function getNotifications($logged_in_user)
    {
        $query_one = DB::table('en_pr_po_history as h') 
                ->select(DB::raw('BIN_TO_UUID(history_id) AS history_id'), DB::raw('BIN_TO_UUID(pr_po_id) AS pr_po_id'), 'history_type', 'action','h.details', DB::raw('BIN_TO_UUID(created_by) AS created_by'), DB::raw('BIN_TO_UUID(notify_to_id) AS notify_to_id'), 'comment', 'h.created_at', 'pr.details->pr_title as title')
                ->where('notify_to_id',DB::raw('UUID_TO_BIN("' . $logged_in_user . '")'))
                ->join('en_form_data_pr AS pr', 'pr.pr_id', '=', 'h.pr_po_id');

        $data = DB::table('en_pr_po_history as h') 
                ->select(DB::raw('BIN_TO_UUID(history_id) AS history_id'), DB::raw('BIN_TO_UUID(pr_po_id) AS pr_po_id'), 'history_type', 'action','h.details', DB::raw('BIN_TO_UUID(created_by) AS created_by'), DB::raw('BIN_TO_UUID(notify_to_id) AS notify_to_id'), 'comment', 'h.created_at', 'po_name as title')
                ->where('notify_to_id',DB::raw('UUID_TO_BIN("' . $logged_in_user . '")'))
                ->join('en_form_data_po AS po', 'po.po_id', '=', 'h.pr_po_id')
                ->union($query_one)
                ->get();

                //->orderBy('h.created_at','ASC');

               
               return $data; 
    }

}//, 'pr.details->pr_title as pr_title','h.created_at' )
             //       ->leftjoin('en_form_data_pr AS pr', 'pr.pr_id', '=', 'h.pr_po_id', 'left outer')
                //    ->leftjoin('en_form_data_po AS po', 'po.po_id', '=', 'h.pr_po_id', 'left outer')
               // ->where('history_type', $history_type) 
                //->where('pr_po_id',DB::raw('UUID_TO_BIN("' . $inputdata['pr_po_id'] . '")'))