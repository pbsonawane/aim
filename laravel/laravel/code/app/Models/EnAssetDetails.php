<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnAssetDetails extends Model 
{
	use HasBinaryUuid;
    public $incrementing = false;
	protected $table = 'en_asset_details';
   	public $timestamps = true;	
    protected $fillable = [
        'asset_detail_id', 'asset_id', 'asset_details', 'auto_discovered', 'add_comment','vendor_id','purchasecost','acquisitiondate','expirydate','warrantyexpirydate'
    ];
	
	protected $primaryKey = 'asset_detail_id';
	public function getKeyName()
    {
        return 'asset_detail_id';
    }
    /*  
    * This is model function is used get all Cost Centers data

    * @author       Amit Khairnar
    * @access       public
    * @param        asset_id
    * @param_type   integer
    * @return       array
    * @tables       en_assets
    */
	
	protected function getassets($asset_id, $inputdata=[], $count=false)
    {
        
	}
}