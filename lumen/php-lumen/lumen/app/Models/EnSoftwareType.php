<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;
use App\Models\EnDatacenters;
use App\Models\EnUsers;

class EnSoftwareType extends Model 
{
	use HasBinaryUuid;
    public $incrementing = false;
	protected $table = 'en_software_types';
   	public $timestamps = true;	
    protected $fillable = [
        'software_type_id', 'software_type', 'description','status','is_default'
    ];
    
    
    
	protected $primaryKey = 'software_type_id';
	public function getKeyName()
    {
        return 'software_type_id';
    }

    /* This is model function is used get all software type data

    * @author       Kavita Daware
    * @access       protected
    * @param        software_type_id
    * @param_type   Integer
    * @return       Array
    * @tables       en_software_types
    */


    protected function getsoftwaretype($software_type_id, $inputdata=array(), $count=false)
    {
        $searchkeyword = _isset($inputdata,'searchkeyword');
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_software_types')   
                ->select(DB::raw('BIN_TO_UUID(software_type_id) AS software_type_id'),'software_type', 'description', 'status','env','is_default')
                ->where('en_software_types.status', '!=', 'd');
                
                

                $query->where(function ($query) use ($searchkeyword, $software_type_id){
                    $query->where(function ($query) use ($searchkeyword, $software_type_id) {
                        $query->when($searchkeyword, function ($query) use ($searchkeyword)
                            {
                                
                                 return $query->where('en_software_types.software_type', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_software_types.description', 'like', '%' . $searchkeyword . '%');
                               });       
                        });
                        $query->when($software_type_id, function ($query) use ($software_type_id)
                        {
                            return $query->where('en_software_types.software_type_id', '=', DB::raw('UUID_TO_BIN("'.$software_type_id.'")'));
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
    * @param        software_type_id
    * @param_type   Integer
    * @return       Array
    * @tables       en_software_types
    */
    protected function checkforrelation($software_type_id)
    {
        if($software_type_id)
        {
            $software_type_data= EnSoftwareType::where('software_type_id', DB::raw('UUID_TO_BIN("'.$software_type_id.'")'))->first();
        //     print_r( $software_type_data);     exit;        
            if($software_type_data)
            {    
                    
                    $software_type_data->update(array('status' => 'd'));            
                    $software_type_data->save();                     
                    $data['data']['deleted_id'] = $software_type_id;
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
            $data['message']['error'] = sshowmessage('123', array('{name}'), array('Software Type'));
            $data['status'] = 'error';                             
        }   
        return $data;    
    }   
}