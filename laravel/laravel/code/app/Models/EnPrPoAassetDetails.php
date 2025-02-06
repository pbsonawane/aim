<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnPrPoAassetDetails extends Model 
{
	use HasBinaryUuid;
    public $incrementing = false;
	protected $table = 'en_pr_po_asset_details';
   //	public $timestamps = false;	
    protected $fillable = [
        'pr_po_asset_id', 'pr_id', 'po_id', 'asset_type','asset_details','created_by', 'status', 'created_at', 'updated_at'
    ];
	
	protected $primaryKey = 'pr_po_asset_id';
	public function getKeyName()
    {
        return 'pr_po_asset_id';
    }	

}