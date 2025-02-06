<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnFormTemplateDefault extends Model 
{	
	use HasBinaryUuid;
    public $incrementing = false;
	// Testing COment
	protected $table = 'en_form_template_default';
   	public $timestamps = false;	
    protected $fillable = [
        'form_templ_id', 'template_name', 'template_title', 'type', 'details', 'default_template', 'status', 'description'
    ];
	protected $primaryKey = 'form_templ_id';

    /*  
    * This is model function is used get all Form Template Default's data.

    * @author       Namrata Thakur
    * @access       public
    * @param        form_templ_id
    * @param_type   Integer
    * @return       array
    * @tables       en_form_template_default
    */
	public function getKeyName()
    {
        return 'form_templ_id';
    }
    protected function getformTemplateDefault($form_templ_id, $inputdata=[], $count=false)
    {
        $searchkeyword = _isset($inputdata,'searchkeyword');
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_form_template_default')  
                ->select(DB::raw('BIN_TO_UUID(en_form_template_default.form_templ_id) AS form_templ_id'), 'template_name', 'template_title', 'type', 'details', 'default_template', 'status', 'description')      
                ->where('status', '!=', 'd')
                ->orderBy('template_name', 'ASC');
                
              /*  if(is_array($inputdata) && count($inputdata) > 0) // Condition for user accessibility
                {
                    $user_id = $inputdata['loggedinuserid'];
                    $is_admin = EnUsers::isadmin($user_id);
                    if(!$is_admin)
                    {   
                        $user_entities_data = EnUsers::getaccessibleentities($user_id,'BV');
                        if($user_entities_data)
                        {
                            $user_bv_ids = $user_entities_data['BV']['entity_id'];
                            $user_bv_ids = json_decode($user_bv_ids);
                            $query->whereIn('en_business_verticals.bv_id',$user_bv_ids);
                        }
                        else
                        {
                            $query->whereIn('en_business_verticals.bv_id',array());// When No accessible entity
                        }
                    }
                }    */            
                $query->where(function ($query) use ($searchkeyword, $form_templ_id){
                    $query->where(function ($query) use ($searchkeyword, $form_templ_id) {                
                        $query->when($searchkeyword, function ($query) use ($searchkeyword)
                        {
                                return $query->where('template_title', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('template_name', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('type', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('details', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('default_template', 'like', '%' . $searchkeyword . '%');                      
                        });
                    });
                    $query->when($form_templ_id, function ($query) use ($form_templ_id)
                    {
                        return $query->where('form_templ_id', '=', DB::raw('UUID_TO_BIN("'.$form_templ_id.'")'));
                    });
                });
                /* Pagination Code Start */
                $query->when(!$count, function ($query) use ($inputdata)
                {
                    if( isset($inputdata["offset"]) && isset($inputdata["limit"]) )
                    {
                        return $query->offset($inputdata["offset"])->limit($inputdata["limit"]);
                    }
                });     
                 /* Pagination Code END */ 
        $query->groupby('form_templ_id');// added to display unique records as join gives duplicate records
        $data = $query->get();       

        if($count)
            return   count($data);
        else      
            return $data;
    }
        
}