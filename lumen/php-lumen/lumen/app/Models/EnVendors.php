<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnVendors extends Model
{
	/*  
    * This is model function is used get all departments data with its foregin key data

    * @author       Amit Khairnar
    * @access       public
    * @param        vendor_id
    * @param_type   Integer
    * @return       array
    * @tables       en_ci_vendors
    */

	use HasBinaryUuid;
    public $incrementing = false;
	
   protected $table = 'en_ci_vendors';
    //public $timestamps = false;
    protected $fillable = [
        'vendor_id', 
        'vendor_name', 
	'vendor_state',
        'vendor_ref_id', 
        'vendor_email', 
        'contact_person', 
        'contactno',
        'address', 
        'city', 
        'pincode', 
        'warehouse_location', 
        'vendor_gst_no', 
        'vendor_pan', 
        'bank_name', 
        'vendor_gst_no_file', 
        'vendor_pan_file',        
        'is_msme_reg',
        'meme_reg_num',
        'products_services_offered',
        'associate_oem',
        'delivery_time',
        'payment_terms',
        'annual_turnover',
        'known_client',
        'bank_name_file',
        'bank_address', 
        'bank_branch', 
        'bank_account_no', 
        'ifsc_code', 
        'micr_code', 
        'account_type',        
        'director_name',
        'director_contact_no',        
        'director_email',
        'sales_officer_name',
        'sales_officer_contact_no',
        'sales_officer_email',
        'account_officer_name',
        'account_officer_contact_no',
        'account_officer_email',
        'any_legal_notices',
        'legal_notice_elaborate',        
        'is_legal_requirements',
        'worker_minimum_age',
        'submit_original_documents',
        'any_serious_incidents',
        'elaborate_serious_incidents',
        'is_anti_bribe_policy',
        'is_health_safety_policy',
        'is_env_regulation',
        'elaborate_env_regulation',
        'name',
        'date',
        'designation',
        'status',
        'vendors_assets',
        'approve_status',
        'msme_certificate',
    ];
	protected $primaryKey = 'vendor_id';
	public function getKeyName()
    {
        return 'vendor_id';
    }	


    /*  
    * This is model function is used get all Role's data with its foregin key data

    * @author       Amit Khairnar
    * @access       public
    * @param        vendor_id
    * @param_type   Integer
    * @return       array
    * @tables       en_ci_vendors
    */
    protected function getvendors($vendor_id=null, $inputdata=array(), $count=false)
    {
        $searchkeyword = _isset($inputdata,'searchkeyword');
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_ci_vendors')   
                ->select(DB::raw(
                    'BIN_TO_UUID(vendor_id) AS vendor_id'),
                    'vendor_name', 
	
        'vendor_ref_id', 
        'vendor_unique_id',
        'vendor_email', 
        'contact_person', 
        'contactno',
        'address', 
        'city', 
        'pincode', 
        'warehouse_location', 
        'vendor_gst_no', 
        'vendor_pan', 
        'bank_name', 
        'vendor_gst_no_file', 
        'vendor_pan_file',        
        'is_msme_reg',
        'meme_reg_num',
        'products_services_offered',
        'associate_oem',
        'delivery_time',
        'payment_terms',
        'annual_turnover',
        'known_client',
        'bank_name_file',
        'bank_address', 
        'bank_branch', 
        'bank_account_no', 
        'ifsc_code', 
        'micr_code', 
        'account_type',        
        'director_name',
        'director_contact_no',        
        'director_email',
        'sales_officer_name',
        'sales_officer_contact_no',
        'sales_officer_email',
        'account_officer_name',
        'account_officer_contact_no',
        'account_officer_email',
        'any_legal_notices',
        'legal_notice_elaborate',        
        'is_legal_requirements',
        'worker_minimum_age',
        'submit_original_documents',
        'any_serious_incidents',
        'elaborate_serious_incidents',
        'is_anti_bribe_policy',
        'is_health_safety_policy',
        'is_env_regulation',
        'elaborate_env_regulation',
        'name',
        'date',
        'designation',
        'status',
        'approve_status',
        'vendors_assets',
        'created_at')->where('en_ci_vendors.status', '!=', 'd');

               /*if(is_array($inputdata) && count($inputdata) > 0) // Condition for user accessibility
                {
                    $user_id = $inputdata['loggedinuserid'];
                    $is_admin = EnUsers::isadmin($user_id);
                    if(!$is_admin)
                    {   
                        $user_id_bin = DB::raw('UUID_TO_BIN("'.$user_id.'")');
                         $result = EnUsersDetails::where('user_id', DB::raw('UUID_TO_BIN("'.$user_id.'")'))->first(); 
                        if($result)
                        {

                            $user_dept_ids = $result->vendor_id;
                            
                            $query->where('en_ci_vendors.vendor_id',$user_dept_ids);
                        }
                        else
                        {
                            $query->where('en_ci_vendors.vendor_id','');// When No accessible entity
                        }
                    }
                } */


                $query->where(function ($query) use ($searchkeyword, $vendor_id){
                    $query->where(function ($query) use ($searchkeyword, $vendor_id) {
                        $query->when($searchkeyword, function ($query) use ($searchkeyword)
                            {
                                
                                return $query->where('en_ci_vendors.vendor_name', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_ci_vendors.vendor_ref_id', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_ci_vendors.vendor_unique_id', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_ci_vendors.contact_person', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_ci_vendors.contactno', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_ci_vendors.address', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_ci_vendors.products_services_offered', 'like', '%' . $searchkeyword . '%');
                            });       
                        });
                        $query->when($vendor_id, function ($query) use ($vendor_id)
                        {
                            return $query->where('en_ci_vendors.vendor_id', '=', DB::raw('UUID_TO_BIN("'.$vendor_id.'")'));
                        });});
               //  $query->orderBy('created_at', 'DESC');
                // $query->groupBy('vendor_name');

          $query->orderBy('vendor_name', 'ASC');
            //    $query->orderBy('vendor_id','DESC');
             //   $query->orderBy('updated_at','DESC');
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


     /* This is Model funtion used to delete the rocord, But Before that check the deletion ID's have relation with another table(en_user_details) 

    * @author      Amit Khairnar
    * @access       public
    * @param        vendor_id
    * @param_type   Integer
    * @return       Array
    * @tables       en_ci_vendors
    */

    protected function checkforrelation($vendor_id)
    {
        if($vendor_id)
        {
            $vendor_data= EnVendors::where('vendor_id', DB::raw('UUID_TO_BIN("'.$vendor_id.'")'))->where('status','!=','d')->first();                 
            if($vendor_data)
            {    
                    
                    $vendor_data->update(array('status' => 'd'));            
                    $vendor_data->save();                     
                    $data['data']['deleted_id'] = $vendor_id;
                    $data['message']['success']= showmessage('118', array('{name}'), array('Vendor'));
                    $data['status'] = 'success';
               
            }
            else
            {
                $data['data'] = NULL;
                $data['message']['error'] = showmessage('119', array('{name}'), array('Vendor'));
                $data['status'] = 'error';                             
            }               
        }
        else
        {
            $data['data'] = NULL;
            $data['message']['error'] = showmessage('123', array('{name}'), array('Vendor'));
            $data['status'] = 'error';                             
        }   
        return $data;    
    }  

    protected function update_approval_status($post_data)
    {
        
        $vendor_data= EnVendors::where('vendor_id', DB::raw('UUID_TO_BIN("'.$post_data['vendor_id'].'")'))->where('status','!=','d')->first();
       

        if($vendor_data)
        {    
            $vendor_data->update(array('status' => 'd'));            
            $vendor_data->save();                     
            $data['data']['deleted_id'] = $vendor_id;
            $data['message']['success']= showmessage('118', array('{name}'), array('Vendor'));
            $data['status'] = 'success';
        }
        else
        {
            $data['data'] = NULL;
            $data['message']['error'] = showmessage('119', array('{name}'), array('Vendor'));
            $data['status'] = 'error';                             
        }               
   
        $data['data'] = NULL;
        $data['message']['error'] = showmessage('123', array('{name}'), array('Vendor'));
        $data['status'] = 'error';                             
     
        return $data;    
    }    

}
