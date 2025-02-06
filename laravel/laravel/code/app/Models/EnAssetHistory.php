<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnAssetHistory extends Model 
{	
	use HasBinaryUuid;
    public $incrementing = false;
	// Testing COment
	protected $table = 'en_asset_history';
   	public $timestamps = true;	
    protected $fillable = [
        'asset_id', 'user_id', 'action', 'message','comment'
    ];
	protected $primaryKey = 'id';

  
	public function getKeyName()
    {
        return 'id';
    }  
        
}