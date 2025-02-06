<?php

namespace App\Models;

use DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class EnSystemSettings extends Model
{
    protected $table = 'en_system_settings';
	protected $fillable = [
        'configuration', 'setting_id', 'status', 'type'
    ];
    protected $primaryKey = null;
	public $incrementing = false;
	public $timestamps = true;	
	protected function getSystemSetting()
	{
		$query = DB::table($this->table)
                ->select('configuration', 'setting_id', 'status', 'type');
        $data =  $query->get();
		return $data;	
	}
	// protected function getsystemsettings($setting_id, $inputdata=array(), $count=false)
    // {
    //     $searchkeyword = _isset($inputdata,'searchkeyword'); 
    //     if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
    //     {
    //         unset($inputdata["offset"]);
    //         unset($inputdata["limit"]);
    //     }
    //     $query = DB::table('en_system_settings')
       
    //             ->select('setting_id', 'configuration', 'en_system_settings.status', 'en_system_settings.type')
                            
    //             ->where('en_system_settings.status', '=', 'y')
    //             ->orderBy('type', 'ASC');
                
    //             if(is_array($inputdata) && count($inputdata) > 0) // Condition for user accessibility
    //             {
    //                /* $user_id = $inputdata['loggedinuserid'];
    //                 $is_admin = EnUsers::isadmin($user_id);
    //                 if(!$is_admin)
    //                 {   
    //                     $user_entities_data = EnUsers::getaccessibleentities($user_id,'BV');
    //                     if($user_entities_data)
    //                     {
    //                         $user_bv_ids = $user_entities_data['BV']['entity_id'];
    //                         //$user_bv_ids = json_decode($user_bv_ids);
    //                         //$query->whereIn('en_business_verticals.bv_id',$user_bv_ids);
    //                         $query->(DB::raw('BIN_TO_UUID(en_business_verticals.bv_id)'),$usewhereInr_bv_ids);
    //                     }
    //                     else
    //                     {
    //                         $query->whereIn('en_business_verticals.bv_id',array());// When No accessible entity
    //                     }
    //                 }*/
    //             }                
    //             $query->where(function ($query) use ($searchkeyword, $setting_id){
    //                 $query->where(function ($query) use ($searchkeyword, $setting_id) {                
    //                     $query->when($searchkeyword, function ($query) use ($searchkeyword)
    //                     {
    //                             return $query->where('en_system_settings.type', 'like', '%' . $searchkeyword . '%')
    //                             ->orWhere('en_system_settings.configuration', 'like', '%' . $searchkeyword . '%');                      
    //                     });
    //                 });
    //                 $query->when($setting_id, function ($query) use ($setting_id)
    //                 {
    //                     return $query->where('en_system_settings.setting_id', '=', $setting_id);
    //                 });
    //             });
    //             /* Pagination Code Start */
    //             $query->when(!$count, function ($query) use ($inputdata)
    //             {
    //                 if( isset($inputdata["offset"]) && isset($inputdata["limit"]) )
    //                 {
    //                     return $query->offset($inputdata["offset"])->limit($inputdata["limit"]);
    //                 }
    //             });     
    //              /* Pagination Code END */         
    //     $data = $query->get();       

    //     if($count)
    //         return   count($data);
    //     else      
    //         return $data;
    // }

}