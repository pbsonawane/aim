<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnCiTemplCustfield extends Model 
{	
	use HasBinaryUuid;
    public $incrementing = false;
	// Testing COment
	protected $table = 'en_ci_templ_custfields';
   	//public $timestamps = false;	
    protected $fillable = [
        'ci_custfield_id','ci_templ_id','custom_attributes', 'status'
    ];
	protected $primaryKey = 'ci_custfield_id';

    /*  
    * This is model function is used get all Form Template Default's data.

    * @author       Amit Khairnar
    * @access       public
    * @param        ci_type_id
    * @param_type   Integer
    * @return       array
    * @tables       en_form_template_custfileds
    */
	public function getKeyName()
    {
        return 'ci_custfield_id';
    } 

    


    
    
    
        
}