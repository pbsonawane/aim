<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;
use App\Models\EnDatacenters;
use App\Models\EnUsers;

class EnSoftwareManufacturer extends Model 
{
	use HasBinaryUuid;
    public $incrementing = false;
	protected $table = 'en_software_manufacturer';
   	public $timestamps = true;	
    protected $fillable = [
        'software_manufacturer_id', 'software_manufacturer', 'description','status','is_default'
    ];
    
    
    
	protected $primaryKey = 'software_manufacturer_id';
	public function getKeyName()
    {
        return 'software_manufacturer_id';
    }
    /* This is model function is used get all software manufacturer data

    * @author       Kavita Daware
    * @access       protected
    * @param        software_manufacturer_id
    * @param_type   Integer
    * @return       Array
    * @tables       en_software_manufacturer
    */

    protected function getsoftwaremanufacturer($software_manufacturer_id, $inputdata=array(), $count=false)
    {
        $searchkeyword = _isset($inputdata,'searchkeyword');
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_software_manufacturer')   
                ->select(DB::raw('BIN_TO_UUID(software_manufacturer_id) AS software_manufacturer_id'),'software_manufacturer', 'description', 'status','env','is_default')
                ->where('en_software_manufacturer.status', '!=', 'd');
                
                

                $query->where(function ($query) use ($searchkeyword, $software_manufacturer_id){
                    $query->where(function ($query) use ($searchkeyword, $software_manufacturer_id) {
                        $query->when($searchkeyword, function ($query) use ($searchkeyword)
                            {
                                
                                 return $query->where('en_software_manufacturer.software_manufacturer', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_software_manufacturer.description', 'like', '%' . $searchkeyword . '%');
                               });       
                        });
                        $query->when($software_manufacturer_id, function ($query) use ($software_manufacturer_id)
                        {
                            return $query->where('en_software_manufacturer.software_manufacturer_id', '=', DB::raw('UUID_TO_BIN("'.$software_manufacturer_id.'")'));
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
    * @param        software_manufacturer_id
    * @param_type   Integer
    * @return       Array
    * @tables       en_software_manufacturer
    */
    protected function checkforrelation($software_manufacturer_id)
    {
        if($software_manufacturer_id)
        {
            $software_manufacturer_data= EnSoftwareManufacturer::where('software_manufacturer_id', DB::raw('UUID_TO_BIN("'.$software_manufacturer_id.'")'))->first();
        //     print_r( $software_manufacturer_data);     exit;        
            if($software_manufacturer_data)
            {    
                    
                    $software_manufacturer_data->update(array('status' => 'd'));            
                    $software_manufacturer_data->save();                     
                    $data['data']['deleted_id'] = $software_manufacturer_id;
                    $data['message']['success']= showmessage('118', array('{name}'), array('Software Manufacturer'));
                    $data['status'] = 'success';
               
            }
            else
            {
                $data['data'] = NULL;
                $data['message']['error'] = showmessage('119', array('{name}'), array('Software Manufacturer'));
                $data['status'] = 'error';                             
            }               
        }
        else
        {
            $data['data'] = NULL;
            $data['message']['error'] = showmessage('123', array('{name}'), array('Software Manufacturer'));
            $data['status'] = 'error';                             
        }   
        return $data;    
    }   
}