<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;
use App\Models\EnDatacenters;
use App\Models\EnUsers;

class EnLicenseType extends Model 
{
	use HasBinaryUuid;
    public $incrementing = false;
	protected $table = 'en_license_type';
   	public $timestamps = true;	
    protected $fillable = [
        'license_type_id', 'license_type', 'installation_allow','is_perpetual','is_free','status','is_default'
    ];
    
    
    
	protected $primaryKey = 'license_type_id';
	public function getKeyName()
    {
        return 'license_type_id';
    }
    /* This is model function is used get all license type data

    * @author       Kavita Daware
    * @access       protected
    * @param        license_type_id
    * @param_type   Integer
    * @return       Array
    * @tables       en_license_type
    */

    
    protected function getlicensetype($license_type_id, $inputdata=[], $count=false)
    {
        $searchkeyword = _isset($inputdata,'searchkeyword');
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_license_type')   
                ->select(DB::raw('BIN_TO_UUID(license_type_id) AS license_type_id'),'license_type', 'installation_allow','is_perpetual','is_free','env','is_default')
                ->where('en_license_type.status', '!=', 'd');
                
                

                $query->where(function ($query) use ($searchkeyword, $license_type_id){
                    $query->where(function ($query) use ($searchkeyword, $license_type_id) {
                        $query->when($searchkeyword, function ($query) use ($searchkeyword)
                            {
                                
                                 return $query->where('en_license_type.license_type', 'like', '%' . $searchkeyword . '%');
                               // ->orWhere('en_license_type.contract_description', 'like', '%' . $searchkeyword . '%');
                               });       
                        });
                        $query->when($license_type_id, function ($query) use ($license_type_id)
                        {
                            return $query->where('en_license_type.license_type_id', '=', DB::raw('UUID_TO_BIN("'.$license_type_id.'")'));
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

    }
     /* This is Model funtion used to delete the record, But Before that check the deletion ID's have relation with another table

    * @author       Kavita Daware
    * @access       protected
    * @param        license_type_id
    * @param_type   Integer
    * @return       Array
    * @tables       en_license_type
    */
    protected function checkforrelation($license_type_id)
    {
        if($license_type_id)
        {
            $license_type_data= EnLicenseType::where('license_type_id', DB::raw('UUID_TO_BIN("'.$license_type_id.'")'))->first();
        //     print_r( $license_type_data);     exit;        
            if($license_type_data)
            {    
                    
                    $license_type_data->update(['status' => 'd']);            
                    $license_type_data->save();                     
                    $data['data']['deleted_id'] = $license_type_id;
                    $data['message']['success']= showmessage('118', ['{name}'], ['License Type']);
                    $data['status'] = 'success';
               
            }
            else
            {
                $data['data'] = NULL;
                $data['message']['error'] = showmessage('119', ['{name}'], ['License Type']);
                $data['status'] = 'error';                             
            }               
        }
        else
        {
            $data['data'] = NULL;
            $data['message']['error'] = showmessage('123', ['{name}'], ['License Type']);
            $data['status'] = 'error';                             
        }   
        return $data;    
    }   
}