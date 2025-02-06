<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;
use App\Models\EnDatacenters;
use App\Models\EnUsers;

class EnContractType extends Model 
{
	use HasBinaryUuid;
    public $incrementing = false;
	protected $table = 'en_contract_type';
   	public $timestamps = true;	
    protected $fillable = [
        'contract_type_id', 'contract_type', 'contract_description','status','is_default'
    ];
    
    
    
	protected $primaryKey = 'contract_type_id';
	public function getKeyName()
    {
        return 'contract_type_id';
    }

    protected function getcontracttype($contract_type_id, $inputdata=array(), $count=false)
    {
        $searchkeyword = _isset($inputdata,'searchkeyword');

        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_contract_type')   
                ->select(DB::raw('BIN_TO_UUID(contract_type_id) AS contract_type_id'),'contract_type', 'contract_description','is_default')
                ->where('en_contract_type.status', '!=', 'd');
                
                

                $query->where(function ($query) use ($searchkeyword, $contract_type_id){
                    $query->where(function ($query) use ($searchkeyword, $contract_type_id) {
                        $query->when($searchkeyword, function ($query) use ($searchkeyword)
                            {
                                
                                 return $query->where('en_contract_type.contract_type', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_contract_type.contract_description', 'like', '%' . $searchkeyword . '%');
                               });       
                        });
                        $query->when($contract_type_id, function ($query) use ($contract_type_id)
                        {
                            return $query->where('en_contract_type.contract_type_id', '=', DB::raw('UUID_TO_BIN("'.$contract_type_id.'")'));
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
    * @access       public
    * @param        contract_type_id
    * @param_type   Integer
    * @return       Array
    * @tables       en_contract_type
    */
    protected function checkforrelation($contract_type_id)
    {
        if($contract_type_id)
        {
            $contract_type_data= EnContractType::where('contract_type_id', DB::raw('UUID_TO_BIN("'.$contract_type_id.'")'))->first();
        //     print_r( $contract_type_data);     exit;        
            if($contract_type_data)
            {    
                    
                    $contract_type_data->update(array('status' => 'd'));            
                    $contract_type_data->save();                     
                    $data['data']['deleted_id'] = $contract_type_id;
                    $data['message']['success']= showmessage('118', array('{name}'), array('Contract Type'));
                    $data['status'] = 'success';
               
            }
            else
            {
                $data['data'] = NULL;
                $data['message']['error'] = showmessage('119', array('{name}'), array('Contract Type'));
                $data['status'] = 'error';                             
            }               
        }
        else
        {
            $data['data'] = NULL;
            $data['message']['error'] = showmessage('123', array('{name}'), array('Contract Type'));
            $data['status'] = 'error';                             
        }   
        return $data;    
    }   
}