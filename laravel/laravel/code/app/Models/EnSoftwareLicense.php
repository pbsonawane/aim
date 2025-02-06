<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;
use App\Models\EnDatacenters;
use App\Models\EnUsers;
use App\Models\EnVendors;
use App\Models\EnSoftware;
use App\Models\EnLicenseType;
use App\Models\EnSoftwareCategory;
use App\Models\EnSoftwareManufacturer;
use App\Models\EnSoftwareInstall;
use App\Models\EnSoftwareLicenseAllocate;

    


class EnSoftwareLicense extends Model 
{
	use HasBinaryUuid;
    public $incrementing = false;
	protected $table = 'en_software_license';
   	//public $timestamps = false;	
    protected $fillable = [
        'software_license_id', 'software_id','software_manufacturer_id','license_type_id', 'vendor_id','department_id','location_id','bv_id','max_installation','purchase_cost','description','acquisition_date','expiry_date','status','license_key'
    ];
    
    
    
	protected $primaryKey = 'software_license_id';
	public function getKeyName()
    {
        return 'software_license_id';
    }
    /* This is model function is used get all license of software

    * @author       Kavita Daware
    * @access       protected
    * @param        software_id
    * @param_type   Integer
    * @return       Array
    * @tables       en_software_license
    */

    protected function getsoftwarelicense($software_id, $inputdata=[], $count=false)
    {
        $searchkeyword = _isset($inputdata,'searchkeyword');
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }

        $query = DB::table('en_software_license AS sl')   
                ->select(DB::raw('BIN_TO_UUID(sl.software_license_id) AS software_license_id'), DB::raw('BIN_TO_UUID(en_software_manufacturer.software_manufacturer_id) as software_manufacturer_id'),DB::raw('BIN_TO_UUID(en_software.software_id) as software_id'),DB::raw('BIN_TO_UUID(en_license_type.license_type_id) as license_type_id'),DB::raw('BIN_TO_UUID(en_ci_vendors.vendor_id) as vendor_id'),'sl.max_installation','sl.description','sl.purchase_cost','sl.acquisition_date','sl.expiry_date','en_software_manufacturer.software_manufacturer','en_license_type.license_type','sl.license_key','en_ci_vendors.vendor_name','sl.department_id',DB::raw('BIN_TO_UUID(sl.department_id) AS department_id'),DB::raw('BIN_TO_UUID(sl.bv_id) AS bv_id'),DB::raw('BIN_TO_UUID(sl.location_id) AS location_id'))
                ->leftjoin('en_software', 'sl.software_id', '=', 'en_software.software_id')
                ->leftjoin('en_software_manufacturer', 'sl.software_manufacturer_id', '=', 'en_software_manufacturer.software_manufacturer_id') 
                ->leftjoin('en_license_type', 'sl.license_type_id', '=', 'en_license_type.license_type_id')
                ->leftjoin('en_ci_vendors', 'sl.vendor_id', '=', 'en_ci_vendors.vendor_id')
                ->where('sl.status', '!=', 'd')
                ->where(DB::raw('BIN_TO_UUID(sl.software_id)'), $software_id);

              
              /*  $query->where(function ($query) use ($searchkeyword, $software_license_id){
                    $query->where(function ($query) use ($searchkeyword, $software_license_id) {
                        $query->when($searchkeyword, function ($query) use ($searchkeyword)
                            {
                                
                                 return $query->where('en_license_type.license_type', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_software.version', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_license_type.installation_allow', 'like', '%' . $searchkeyword . '%');
                                
                               });       
                        });
                        $query->when($software_license_id, function ($query) use ($software_license_id)
                        {
                            return $query->where('sl.software_license_id', '=', DB::raw('UUID_TO_BIN("'.$software_license_id.'")'));
                        });});
                $query->when(!$count, function ($query) use ($inputdata)
                        {
                            if( isset($inputdata["offset"]) && isset($inputdata["limit"]) )
                            {
                                return $query->offset($inputdata["offset"])->limit($inputdata["limit"]);
                            }
                        });
                        */
                $data = $query->get();                        
                                            
        if($count)
            return   count($data);
        else      
            return $data;    

    }

    /* This is model function is used to edit the software license record

    * @author       Kavita Daware
    * @access       protected
    * @param        software_license_id
    * @param_type   Integer
    * @return       Array
    * @tables       en_software_license
    */

    protected function getsoftwarelicenseedit($software_license_id, $inputdata=[], $count=false)
    {
        
        $query = DB::table('en_software_license AS sl')   
                ->select(DB::raw('BIN_TO_UUID(sl.software_license_id) AS software_license_id'), DB::raw('BIN_TO_UUID(en_software_manufacturer.software_manufacturer_id) as software_manufacturer_id'),DB::raw('BIN_TO_UUID(en_software.software_id) as software_id'),DB::raw('BIN_TO_UUID(en_license_type.license_type_id) as license_type_id'),DB::raw('BIN_TO_UUID(en_ci_vendors.vendor_id) as vendor_id'),'sl.max_installation','sl.description','sl.purchase_cost','sl.acquisition_date','sl.expiry_date','en_software_manufacturer.software_manufacturer','en_license_type.license_type','sl.license_key','en_ci_vendors.vendor_name','sl.department_id',DB::raw('BIN_TO_UUID(sl.department_id) AS department_id'),DB::raw('BIN_TO_UUID(sl.bv_id) AS bv_id'),DB::raw('BIN_TO_UUID(sl.location_id) AS location_id'))
                ->leftjoin('en_software', 'sl.software_id', '=', 'en_software.software_id')
                ->leftjoin('en_software_manufacturer', 'sl.software_manufacturer_id', '=', 'en_software_manufacturer.software_manufacturer_id') 
                ->leftjoin('en_license_type', 'sl.license_type_id', '=', 'en_license_type.license_type_id')
                ->leftjoin('en_ci_vendors', 'sl.vendor_id', '=', 'en_ci_vendors.vendor_id')
                ->where(DB::raw('BIN_TO_UUID(sl.software_license_id)'), $software_license_id)
                ->where('sl.status', '!=', 'd');

        $data = $query->get();  

                                            
        if($count)
            return   count($data);
        else      
            return $data;    

    }
     /* This is Model funtion used to delete the record, But Before that check the deletion ID's have relation with another table

    * @author       Kavita Daware
    * @access       protected
    * @param        software_license_id
    * @param_type   Integer
    * @return       Array
    * @tables       en_software_license
    */
    protected function checkforrelation($software_license_id)
    {
        if($software_license_id)
        {
            $software_license_data= EnSoftwareLicense::where('software_license_id', DB::raw('UUID_TO_BIN("'.$software_license_id.'")'))->first();      
            if($software_license_data)
            {    
                    
                    $software_license_data->update(['status' => 'd']);            
                    $software_license_data->save();                     
                    $data['data']['deleted_id'] = $software_license_id;
                    $data['message']['success']= showmessage('118', ['{name}'], ['Software License']);
                    $data['status'] = 'success';
            }
            else
            {
                $data['data'] = NULL;
                $data['message']['error'] = showmessage('119', ['{name}'], ['Software License']);
                $data['status'] = 'error';                             
            }               
        }
        else
        {
            $data['data'] = NULL;
            $data['message']['error'] = showmessage('123', ['{name}'], ['Software License']);
            $data['status'] = 'error';                             
        }   
        return $data;    
    }  

    /* This is Model funtion used to get purchase count of software

    * @author       Kavita Daware
    * @access       protected
    * @param        software_id
    * @param_type   Integer
    * @return       Array
    * @tables       en_software_license
    */
    protected function swpurchasecount($software_id=null,$inputdata=[])
    { 
        /*$query = DB::table('en_software_license')
               ->select(DB::raw('sum(max_installation) as sumdata'))
                ->where('software_id', $software_id)
                ->get();

                ->where(DB::raw('BIN_TO_UUID(software_id)'), $software_id)*/
            /* $query =   DB::table('en_software_license')->selectRaw('*, count(max_installation)')->groupBy('software_id');*/

             $query = DB::table("en_software_license")
                    ->select(DB::raw('sum(max_installation) as max_installation'))->groupBy(DB::raw('BIN_TO_UUID(software_id)'))
                    ->where(DB::raw('BIN_TO_UUID(software_id)'), $software_id)
                    ->get();
                                            
        return $query;    

    } 
    /* This is Model funtion used to get purchase count of all softwares

    * @author       Kavita Daware
    * @access       protected
    * @return       Array
    * @tables       en_software_license
    */

    protected function swpurchasecountallsw()
    { 
        
             $query = DB::table("en_software_license")
                    ->select(DB::raw('sum(max_installation) as max_installation'))
                    ->get();
                                            
        return $query;    

    } 

}