<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;
use App\Models\EnDatacenters;
use App\Models\EnUsers;
use App\Models\EnLicenseType;
use App\Models\EnSoftwareCategory;
use App\Models\EnSoftwareManufacturer;
use App\Models\EnSoftwareInstall;
use App\Models\EnSoftwareLicenseAllocate;
use App\Models\EnSoftwareLicense;

class EnSoftware extends Model 
{
	use HasBinaryUuid;
    public $incrementing = false;
	protected $table = 'en_software';
   	//public $timestamps = false;	
    protected $fillable = [
        'software_id', 'software_name','software_type_id', 'software_category_id','software_manufacturer_id','license_type_id','description','ci_type','version','status'
    ];
    
    
    
	protected $primaryKey = 'software_id';
	public function getKeyName()
    {
        return 'software_id';
    }

    /* This is model function is used get all software data

    * @author       Kavita Daware
    * @access       protected
    * @param        software_id
    * @param_type   Integer
    * @return       Array
    * @tables       en_software
    */
    protected function getsoftware($software_id, $inputdata=array(), $count=false)
    {
        $searchkeyword = _isset($inputdata,'searchkeyword');
        $software_id = $searchkeyword = null;
        $software_id = _isset($inputdata, "software_id");
        $advsoftware_type_id = _isset($inputdata, "advsoftware_type_id");
        $advsoftware_manufacturer_id = _isset($inputdata, "advsoftware_manufacturer_id");
        $advsoftware_category_id = _isset($inputdata, "advsoftware_category_id");


       
        $searchkeyword_ = _isset($inputdata, "searchkeyword");
        $searchkeyword = $searchkeyword != null ? $searchkeyword : $searchkeyword_;
        
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_software AS s')   
                ->select(DB::raw('BIN_TO_UUID(s.software_id) AS software_id'), DB::raw('BIN_TO_UUID(en_software_types.software_type_id) as software_type_id'),DB::raw('BIN_TO_UUID(en_software_category.software_category_id) as software_category_id'),DB::raw('BIN_TO_UUID(en_software_manufacturer.software_manufacturer_id) as software_manufacturer_id'),DB::raw('BIN_TO_UUID(en_license_type.license_type_id) as license_type_id'),'software_name','s.description','s.ci_type','s.version','en_software_types.software_type','en_software_category.software_category','en_software_manufacturer.software_manufacturer','en_license_type.license_type')
                ->leftjoin('en_software_types', 's.software_type_id', '=', 'en_software_types.software_type_id') 
                ->leftjoin('en_software_category', 's.software_category_id', '=', 'en_software_category.software_category_id') 
                ->leftjoin('en_software_manufacturer', 's.software_manufacturer_id', '=', 'en_software_manufacturer.software_manufacturer_id') 
                ->leftjoin('en_license_type', 's.license_type_id', '=', 'en_license_type.license_type_id')
                ->where('s.status', '!=', 'd');
               //print_r($query);
              
              $query->when($advsoftware_type_id, function ($query) use ($advsoftware_type_id)
                {
                    return $query->where('s.software_type_id',  DB::raw('UUID_TO_BIN("'.$advsoftware_type_id.'")') );       
                });

              $query->when($advsoftware_manufacturer_id, function ($query) use ($advsoftware_manufacturer_id)
                {
                    return $query->where('s.software_manufacturer_id', DB::raw('UUID_TO_BIN("'.$advsoftware_manufacturer_id.'")'));       
                });
              
              $query->when($advsoftware_category_id, function ($query) use ($advsoftware_category_id)
                {
                    return $query->where('s.software_category_id', DB::raw('UUID_TO_BIN("'.$advsoftware_category_id.'")'));       
                });
              
                $query->where(function ($query) use ($searchkeyword, $software_id){
                    $query->where(function ($query) use ($searchkeyword, $software_id) {
                        $query->when($searchkeyword, function ($query) use ($searchkeyword)
                            {
                                
                                 return $query->where('s.software_name', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('s.version', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('s.description', 'like', '%' . $searchkeyword . '%');
                                
                               });       
                        });
                        $query->when($software_id, function ($query) use ($software_id)
                        {
                            return $query->where('s.software_id', '=', DB::raw('UUID_TO_BIN("'.$software_id.'")'));
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
     /* This is Model funtion used to delete the rocord, But Before that check the deletion ID's have relation with another table

    * @author       Kavita Daware
    * @access       protected
    * @param        software_id
    * @param_type   Integer
    * @return       Array
    * @tables       en_software
    */
    protected function checkforrelation($software_id)
    {
        if($software_id)
        {
            $software_data= EnSoftware::where('software_id', DB::raw('UUID_TO_BIN("'.$software_id.'")'))->first();      
            if($software_data)
            {    
                    
                    $software_data->update(array('status' => 'd'));            
                    $software_data->save();                     
                    $data['data']['deleted_id'] = $software_id;
                    $data['message']['success']= showmessage('118', array('{name}'), array('Software Type'));
                    $data['status'] = 'success';
               
            }
            else
            {
                $data['data'] = NULL;
                $data['message']['error'] = showmessage('119', array('{name}'), array('Software Type'));
                $data['status'] = 'error';                             
            }               
        }
        else
        {
            $data['data'] = NULL;
            $data['message']['error'] = showmessage('123', array('{name}'), array('Software Type'));
            $data['status'] = 'error';                             
        }   
        return $data;    
    }   


     
}