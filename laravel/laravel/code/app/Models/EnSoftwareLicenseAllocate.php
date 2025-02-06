<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\BinaryUuid\HasBinaryUuid;
use App\Models\EnSoftware;
use App\Models\EnLicenseType;
use App\Models\EnSoftwareCategory;
use App\Models\EnSoftwareManufacturer;
use App\Models\EnSoftwareInstall;
use App\Models\EnSoftwareLicense;



class EnSoftwareLicenseAllocate extends Model 
{	
	use HasBinaryUuid;
    public $incrementing = false;
	// Testing COment
	protected $table = 'en_software_license_allocation';
   	public $timestamps = true;	
    protected $fillable = [
        'sw_license_allocation_id', 'software_id', 'software_license_id', 'asset_id','created_at','updated_at','status'
    ];
	protected $primaryKey = 'sw_license_allocation_id';

	public function getKeyName()
    {
        return 'sw_license_allocation_id';
    }  


    /* This is model function is used get all allocation of software

    * @author       Kavita Daware
    * @access       protected
    * @param_type   Integer
    * @return       Array
    * @tables       en_software_license_allocation
    */
    protected function getswlicenseallocate($inputdata=[], $count=false)
    {
        $query = DB::table('en_software_license_allocation')   
                //->select(DB::raw('BIN_TO_UUID(sw_license_allocation_id) AS sw_license_allocation_id'),DB::raw('BIN_TO_UUID(software_id) AS software_id'),DB::raw('BIN_TO_UUID(software_license_id) AS software_license_id'),'asset_id');//with empty table
                ->select(DB::raw('BIN_TO_UUID(sw_license_allocation_id) AS sw_license_allocation_id'),'software_id','software_license_id','asset_id')
                ->where('en_software_license_allocation.status', '!=', 'd');
                //->where(DB::raw('BIN_TO_UUID(en_software_license_allocation.software_license_id)'), $software_license_id);

                $data = $query->get();                        
                                       
        if($count)
            return   count($data);
        else      
            return $data;    

    }
    /* This is Model funtion used to remove assets from software allocation

    * @author       Kavita Daware
    * @access       protected
    * @param        software_id,asset_id
    * @param_type   Integer
    * @return       Array
    * @tables       en_software_license_allocation
    */

    protected function swallocateassetremove($software_id = NULL,$asset_id = NULL)
    {
           
            $query = DB::statement("UPDATE en_software_license_allocation SET asset_id = JSON_REMOVE(
            asset_id, REPLACE(json_search(asset_id, 'all', '".$asset_id."'), '\"', ''))
            WHERE json_search(asset_id, 'all','".$asset_id."') IS NOT NULL AND software_id = UUID_TO_BIN('".$software_id."')");

           // print_r($query);
            return $query;
    }

    /* This is Model funtion used to get allocated count for all softwares.

    * @author       Kavita Daware
    * @access       protected
    * @param        software_id,asset_id
    * @param_type   Integer
    * @return       Array
    * @tables       en_software_license_allocation
    */

    protected function getswallocationcount()
    {
           
            //$query = DB::statement("SELECT sum(JSON_LENGTH(asset_id))  AS allocationcount FROM en_software_license_allocation");
            $query = DB::table("en_software_license_allocation")
                    ->select(DB::raw('sum(JSON_LENGTH(asset_id)) as allocationcount'))
                    ->get();
            //print_r($query);die;
            // select sum(JSON_LENGTH(`asset_id`)) from `en_software_license_allocation`
            return $query;
    }




        
}