<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class Ensku extends Model 
{	
	use HasBinaryUuid; 
    public $incrementing = false;
	// Testing COment
	protected $table = 'en_sku_mst';
   	//public $timestamps = false;	
    protected $fillable = [
        'sku_code', 'sku_code_id', 'core_product_id', 'core_product_name', 'coreproduct_description','primary_category_id','primary_category_name','primary_category_abbreviation','secondary_category_id','secondary_category_name','secondary_category_abbreviation','tertiary_category_id','tertiary_category_name','tertiary_category_abbreviation','fourth_category_id','fourth_category_name','fourth_category_abbreviation','fifth_category_id','fifth_category_name','fifth_category_abbreviation','measurement_unit_id','measurement_unit_name','measurement_unit_code','crm_created_dt','crm_updated_dt','is_added_by_cron'
    ];
	protected $primaryKey = 'id';

    const CREATED_AT = 'creation_date';
    const UPDATED_AT = 'updated_date';
        
}