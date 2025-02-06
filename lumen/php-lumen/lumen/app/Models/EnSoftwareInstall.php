<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;
use App\Models\EnDatacenters;
use App\Models\EnUsers;
use App\Models\EnAssets;
use App\Models\EnSoftware;
use App\Models\EnLicenseType;
use App\Models\EnSoftwareCategory;
use App\Models\EnSoftwareManufacturer;
use App\Models\EnSoftwareLicenseAllocate;
use App\Models\EnSoftwareLicense;


class EnSoftwareInstall extends Model 
{
	use HasBinaryUuid;
    public $incrementing = false;
	protected $table = 'en_software_installation';
   	//public $timestamps = false;	
    protected $fillable = [
        'sw_install_id', 'asset_id','software_id', 'status','created_at','updated_at'
    ];
    
    
    
	protected $primaryKey = 'sw_install_id';
	public function getKeyName()
    {
        return 'sw_install_id';
    }

    /* This is model function is used get all installations of software

    * @author       Kavita Daware
    * @access       protected
    * @param_type   Integer
    * @return       Array
    * @tables       en_software_installation
    */
    protected function getswinstallation($inputdata=array(), $count=false)
    {
        $query = DB::table('en_software_installation')   
                ->select(DB::raw('BIN_TO_UUID(sw_install_id) AS sw_install_id'),'asset_id','software_id','created_at')
                ->where('en_software_installation.status', '!=', 'd');
                $data = $query->get();                        
                                       
        if($count)
            return   count($data);
        else      
            return $data;    

    }
     /* This is Model funtion used to delete the rocord, But Before that check the deletion ID's have relation with another table

    * @author       Kavita Daware
    * @access       protected
    * @param        sw_install_id
    * @param_type   Integer
    * @return       Array
    * @tables       en_software_installation
    */
    protected function checkforrelation($sw_install_id)
    {
        if($sw_install_id)
        {
            $sw_install_data= EnSoftwareInstall::where('sw_install_id', DB::raw('UUID_TO_BIN("'.$sw_install_id.'")'))->first();
        //     print_r( $sw_install_data);     exit;        
            if($sw_install_data)
            {    
                    
                    $sw_install_data->update(array('status' => 'd'));            
                    $sw_install_data->save();                     
                    $data['data']['deleted_id'] = $sw_install_id;
                    $data['message']['success']= showmessage('118', array('{name}'), array('Software Iinstall'));
                    $data['status'] = 'success';
               
            }
            else
            {
                $data['data'] = NULL;
                $data['message']['error'] = showmessage('119', array('{name}'), array('Software Install'));
                $data['status'] = 'error';                             
            }               
        }
        else
        {
            $data['data'] = NULL;
            $data['message']['error'] = showmessage('123', array('{name}'), array('Software Install'));
            $data['status'] = 'error';                             
        }   
        return $data;    
    }   

    /* This is Model funtion used to remove assets from software installation

    * @author       Kavita Daware
    * @access       protected
    * @param        software_id,asset_id
    * @param_type   Integer
    * @return       Array
    * @tables       en_software_installation
    */
    
    protected function swassetremove($software_id = NULL,$asset_id = NULL)
    {
           
            $query = DB::statement("UPDATE en_software_installation SET asset_id = JSON_REMOVE(
            asset_id, REPLACE(json_search(asset_id, 'all', '".$asset_id."'), '\"', ''))
            WHERE json_search(asset_id, 'all','".$asset_id."') IS NOT NULL AND software_id = UUID_TO_BIN('".$software_id."')");

            return $query;
    }
}