<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;
use App\Models\EnDatacenters;
use App\Models\EnUsers;

class EnSoftwareCategory extends Model 
{
	use HasBinaryUuid;
    public $incrementing = false;
	protected $table = 'en_software_category';
   	public $timestamps = true;	
    protected $fillable = [
        'software_category_id', 'software_category', 'description','status','is_default'
    ];
    
    
    
	protected $primaryKey = 'software_category_id';
	public function getKeyName()
    {
        return 'software_category_id';
    }

    /* This is model function is used get all software category data

    * @author       Kavita Daware
    * @access       protected
    * @param        software_category_id
    * @param_type   Integer
    * @return       Array
    * @tables       en_software_category
    */

    protected function getsoftwarecategory($software_category_id, $inputdata=[], $count=false)
    {
        $searchkeyword = _isset($inputdata,'searchkeyword');
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_software_category')   
                ->select(DB::raw('BIN_TO_UUID(software_category_id) AS software_category_id'),'software_category', 'description', 'status','env','is_default')
                ->where('en_software_category.status', '!=', 'd');
                
                

                $query->where(function ($query) use ($searchkeyword, $software_category_id){
                    $query->where(function ($query) use ($searchkeyword, $software_category_id) {
                        $query->when($searchkeyword, function ($query) use ($searchkeyword)
                            {
                                
                                 return $query->where('en_software_category.software_category', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_software_category.description', 'like', '%' . $searchkeyword . '%');
                               });       
                        });
                        $query->when($software_category_id, function ($query) use ($software_category_id)
                        {
                            return $query->where('en_software_category.software_category_id', '=', DB::raw('UUID_TO_BIN("'.$software_category_id.'")'));
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
    * @param        software_category_id
    * @param_type   Integer
    * @return       Array
    * @tables       en_software_category
    */
    protected function checkforrelation($software_category_id)
    {
        if($software_category_id)
        {
            $software_category_data= EnSoftwareCategory::where('software_category_id', DB::raw('UUID_TO_BIN("'.$software_category_id.'")'))->first();
        //     print_r( $software_category_data);     exit;        
            if($software_category_data)
            {    
                    
                    $software_category_data->update(['status' => 'd']);            
                    $software_category_data->save();                     
                    $data['data']['deleted_id'] = $software_category_id;
                    $data['message']['success']= showmessage('118', ['{name}'], ['Software Category']);
                    $data['status'] = 'success';
               
            }
            else
            {
                $data['data'] = NULL;
                $data['message']['error'] = showmessage('119', ['{name}'], ['Software Category']);
                $data['status'] = 'error';                             
            }               
        }
        else
        {
            $data['data'] = NULL;
            $data['message']['error'] = showmessage('123', ['{name}'], ['Software Category']);
            $data['status'] = 'error';                             
        }   
        return $data;    
    }   
}