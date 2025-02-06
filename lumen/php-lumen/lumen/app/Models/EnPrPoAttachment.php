<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnPrPoAttachment extends Model 
{
	use HasBinaryUuid;
    public $incrementing = false;
	protected $table = 'en_pr_po_attachment';
   //	public $timestamps = false;	
    protected $fillable = [
        'attach_id', 'pr_po_id', 'type', 'attachment_type','attachment_name','created_by', 'status',  'created_at', 'updated_at','pr_vendor_id','file_title'
    ];
	
	protected $primaryKey = 'attach_id';
	public function getKeyName()
    {
        return 'attach_id';
    }	
    protected function getAttachments($pr_po_id = NULL, $attachment_type = NULL, $type = NULL )
    {
            $query = EnPrPoAttachment::select(DB::raw('BIN_TO_UUID(attach_id) AS attach_id'),DB::raw('BIN_TO_UUID(pr_vendor_id) AS pr_vendor_id'), DB::raw('BIN_TO_UUID(pr_po_id) AS pr_po_id'), 'type', 'attachment_type','attachment_name',DB::raw('BIN_TO_UUID(created_by) AS created_by'), 'status', 'created_at', 'updated_at','file_title')   
            ->where('status', '!=', 'd')            
            ->orderBy('created_at', 'desc');   

            $query->where(function ($query) use ($pr_po_id)
            {
                $query->when($pr_po_id, function ($query) use ($pr_po_id)
                    {
                        return $query->where('pr_po_id', '=', DB::raw('UUID_TO_BIN("'.$pr_po_id.'")'));
                    });
            });
            $query->where(function ($query) use ($attachment_type)
            {
                $query->when($attachment_type, function ($query) use ($attachment_type)
                    {
                        return $query->where('attachment_type', '=', $attachment_type);
                    });
            });
            $query->where(function ($query) use ($type)
            {
                $query->when($type, function ($query) use ($type)
                    {
                        return $query->where('type', '=', $type);
                    });
            });                


           $data = $query->get(); 
           return $data; 
             
    }
}