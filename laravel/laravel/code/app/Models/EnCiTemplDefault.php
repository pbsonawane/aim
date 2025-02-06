<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnCiTemplDefault extends Model 
{	
	use HasBinaryUuid;
    public $incrementing = false;
	// Testing COment
	protected $table = 'en_ci_templ_default';
   	public $timestamps = true;	
    protected $fillable = [
        'ci_templ_id', 'ci_name','ci_sku' ,'ci_type_id', 'default_attributes', 'status','prefix', 'variable_name'
    ];
	protected $primaryKey = 'ci_templ_id';

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
        return 'ci_templ_id';
    } 

    protected function getcitemplatesD($ci_templ_id=null,$ci_type_id=null)
    {
         $query = DB::table('en_ci_templ_default AS td')  
                ->leftJoin('en_ci_templ_custfields AS tc', 'tc.ci_templ_id', '=', 'td.ci_templ_id') 
                ->leftJoin('en_ci_types AS ty', 'ty.ci_type_id', '=', 'td.ci_type_id') 
                ->select(DB::raw('BIN_TO_UUID(td.ci_templ_id) AS ci_templ_id'),DB::raw('BIN_TO_UUID(td.ci_type_id) AS ci_type_id'), 'td.ci_name','td.ci_sku','td.default_attributes','td.status','tc.custom_attributes','td.prefix','ty.citype','td.variable_name')
                ->where('td.status', '!=', 'd')
                ->orderBy('td.ci_name','ASC');
                $query->where(function ($query) use ($ci_templ_id)
                {
                    $query->when($ci_templ_id, function ($query) use ($ci_templ_id)
                        {
                            return $query->where('td.ci_templ_id', '=', DB::raw('UUID_TO_BIN("'.$ci_templ_id.'")'));
                        });
                });
                $query->where(function ($query) use ($ci_type_id)
                {
                    $query->when($ci_type_id, function ($query) use ($ci_type_id)
                        {
                            return $query->where('td.ci_type_id', '=', DB::raw('UUID_TO_BIN("'.$ci_type_id.'")'));
                        });
                });


               $data = $query->get(); 
               return $data; 
    }     



    
    /*  
    * This is model function is used get all Role's data with its foregin key data

    * @author       Amit Khairnar
    * @access       public
    * @param        ci_type_id
    * @param_type   Integer
    * @return       array
    * @tables       en_ci_templ_default
    */
  /*  protected function getcitypes($ci_type_id, $inputdata=array(), $count=false)
    {
        $searchkeyword = _isset($inputdata,'searchkeyword');
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_ci_templ_default')   
                ->select(DB::raw('BIN_TO_UUID(ci_type_id) AS ci_type_id'), 'citype', 'status')             
                ->where('en_ci_templ_default.status', '!=', 'd')
                ->orderBy('citype','ASC');

                $query->where(function ($query) use ($searchkeyword, $ci_type_id){
                    $query->where(function ($query) use ($searchkeyword, $ci_type_id) {
                        $query->when($searchkeyword, function ($query) use ($searchkeyword)
                            {
                                return $query->where('en_ci_templ_default.citype', 'like', '%' . $searchkeyword . '%');
                            });       
                        });
                        $query->when($ci_type_id, function ($query) use ($ci_type_id)
                        {
                            return $query->where('en_ci_templ_default.ci_type_id', '=', DB::raw('UUID_TO_BIN("'.$ci_type_id.'")'));
                        });});
                $query->when(!$count, function ($query) use ($inputdata)
                        {
                            if( isset($inputdata["offset"]) && isset($inputdata["limit"]) )
                            {
                                return $query->offset($inputdata["offset"])->limit($inputdata["limit"]);
                            }
                        });
                $data = $query->get();                        
                                            
        if($count)
            return   count($data);
        else      
            return $data; 
    }*/
        
}