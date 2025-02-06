<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnCiTemplCustom extends Model 
{	
	use HasBinaryUuid; 
    public $incrementing = false;
	// Testing COment
	protected $table = 'en_ci_templ_custom';
   	//public $timestamps = false;	
    protected $fillable = [
        'ci_templ_id', 'ci_name','ci_sku', 'ci_type_id', 'custom_attributes', 'status','prefix','variable_name'
    ];
	protected $primaryKey = 'ci_templ_id';

    /*  
    * This is model function is used get all Form Template Default's data.

    * @author       Amit Khairnar
    * @access       public
    * @param        ci_type_id
    * @param_type   Integer
    * @return       array
    * @tables       en_ci_templ_custom
    */
	public function getKeyName()
    {
        return 'ci_templ_id';
    }          

    protected function getcitemplatesC($ci_templ_id=null, $ci_type_id=null)
    {
         $query = DB::table('en_ci_templ_custom AS tc')   
                ->leftJoin('en_ci_types AS ty', 'ty.ci_type_id', '=', 'tc.ci_type_id')                     
                ->select(DB::raw('BIN_TO_UUID(tc.ci_templ_id) AS ci_templ_id'),DB::raw('BIN_TO_UUID(tc.ci_type_id) AS ci_type_id'), 'tc.ci_name','tc.ci_sku','tc.custom_attributes','tc.status','tc.prefix','ty.citype','tc.variable_name')
                ->where('tc.status', '!=', 'd')
                ->orderBy('tc.ci_name','ASC');

                $query->where(function ($query) use ($ci_templ_id)
                {
                    $query->when($ci_templ_id, function ($query) use ($ci_templ_id)
                        {
                            return $query->where('tc.ci_templ_id', '=', DB::raw('UUID_TO_BIN("'.$ci_templ_id.'")'));
                        });
                });
                 $query->where(function ($query) use ($ci_type_id)
                {
                    $query->when($ci_type_id, function ($query) use ($ci_type_id)
                        {
                            return $query->where('tc.ci_type_id', '=', DB::raw('UUID_TO_BIN("'.$ci_type_id.'")'));
                        });
                });


               $data = $query->get(); 
               //$queries    = DB::getQueryLog();
               // $last_query = end($queries);
              //  apilog(json_encode($last_query));
               return $data; 
    }        
    
   
        
}