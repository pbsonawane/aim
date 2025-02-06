<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnCiTemplCustfields extends Model 
{	
	use HasBinaryUuid;
    public $incrementing = false;
	// Testing COment
	protected $table = 'en_ci_templ_custfields';
   	//public $timestamps = false;	
    protected $fillable = [
        'ci_custfield_id','ci_templ_id', 'custom_attributes', 'status'
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
    
    
    protected function getcitemplatesCF($ci_templ_id)
    {
         $query = DB::table('en_ci_templ_custfields AS tc') 
                ->join('en_ci_templ_default AS td', 'tc.ci_templ_id', '=', 'td.ci_templ_id')    
                ->select(DB::raw('BIN_TO_UUID(tc.ci_custfield_id) AS ci_custfield_id'),DB::raw('BIN_TO_UUID(tc.ci_templ_id) AS ci_templ_id'),'tc.custom_attributes','tc.status','td.ci_name')
                ->where('tc.status', '!=', 'd');
                //->orderBy('ci_name','ASC');
                $query->where(function ($query) use ($ci_templ_id)
                {
                    $query->when($ci_templ_id, function ($query) use ($ci_templ_id)
                        {
                            return $query->where('tc.ci_templ_id', '=', DB::raw('UUID_TO_BIN("'.$ci_templ_id.'")'));
                        });
                });


               $data = $query->get(); 
               return $data; 
    }      
        
}