<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnFormTemplateCustfileds extends Model 
{	
	use HasBinaryUuid;
    public $incrementing = false;
	// Testing COment
	protected $table = 'en_form_template_custfileds';
   	public $timestamps = false;	
    protected $fillable = [
        'ci_custfield_id', 'ci_templ_id', 'custom_attributes', 'status'
    ];
	protected $primaryKey = 'ci_custfield_id';

    /*  
    * This is model function is used get all Form Template Default's data.

    * @author       Namrata Thakur
    * @access       public
    * @param        ci_custfield_id
    * @param_type   Integer
    * @return       array
    * @tables       en_form_template_custfileds
    */
	public function getKeyName()
    {
        return 'ci_custfield_id';
    }   

}