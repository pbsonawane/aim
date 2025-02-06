<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;
//use App\Models\EnDatacenters;
//use App\Models\EnUsers;

class EnRelationshipType extends Model 
{
	use HasBinaryUuid;
    public $incrementing    = false;
	protected $table        = 'en_relationship_type';
   	public $timestamps      = true;	
    protected $fillable     = [
        'rel_type_id', 'rel_type','inverse_rel_type', 'description','status','is_default'
    ];
    
	protected $primaryKey   = 'rel_type_id';
	public function getKeyName()
    {
        return 'rel_type_id';
    }

    protected function getrelationshiptype($rel_type_id, $inputdata=array(), $count=false)
    {
        $searchkeyword = _isset($inputdata,'searchkeyword');
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_relationship_type')   
                ->select(DB::raw('BIN_TO_UUID(rel_type_id) AS rel_type_id'),'rel_type','inverse_rel_type', 'description','is_default')
                ->where('en_relationship_type.status', '!=', 'd');
                
                $query->where(function ($query) use ($searchkeyword, $rel_type_id){
                    $query->where(function ($query) use ($searchkeyword, $rel_type_id) {
                        $query->when($searchkeyword, function ($query) use ($searchkeyword)
                            {
                                
                                 return $query->where('en_relationship_type.rel_type', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_relationship_type.inverse_rel_type', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_relationship_type.description', 'like', '%' . $searchkeyword . '%');
                               });       
                        });
                        $query->when($rel_type_id, function ($query) use ($rel_type_id)
                        {
                            return $query->where('en_relationship_type.rel_type_id', '=', DB::raw('UUID_TO_BIN("'.$rel_type_id.'")'));
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
    //  /* This is Model funtion used to delete the rocord, But Before that check the deletion ID's have relation with another table

    // * @author       Kavita Daware
    // * @access       public
    // * @param        rel_type_id
    // * @param_type   Integer
    // * @return       Array
    // * @tables       en_relationship_type
    // */
    // protected function checkforrelation($rel_type_id)
    // {
    //     if($rel_type_id)
    //     {
    //         $contract_type_data= EnContractType::where('rel_type_id', DB::raw('UUID_TO_BIN("'.$rel_type_id.'")'))->first();
    //     //     print_r( $contract_type_data);     exit;        
    //         if($contract_type_data)
    //         {    
                    
    //                 $contract_type_data->update(array('status' => 'd'));            
    //                 $contract_type_data->save();                     
    //                 $data['data']['deleted_id'] = $rel_type_id;
    //                 $data['message']['success']= 'Record Deleted Successfully.';
    //                 $data['status'] = 'success';
               
    //         }
    //         else
    //         {
    //             $data['data'] = NULL;
    //             $data['message']['error'] = 'Record Not Found.';
    //             $data['status'] = 'error';                             
    //         }               
    //     }
    //     else
    //     {
    //         $data['data'] = NULL;
    //         $data['message']['error'] = 'The Id field is required';
    //         $data['status'] = 'error';                             
    //     }   
    //     return $data;    
    // }


    protected function get_asset_relationship($asset_id='')
    {
        /*
        SELECT *,
        (SELECT asset_tag FROM en_assets WHERE asset_id = en_asset_relationship.`parent_asset_id`) AS pname,
        (SELECT asset_tag FROM en_assets WHERE asset_id = en_asset_relationship.`child_asset_id`) AS cname 
        FROM en_asset_relationship
        LEFT JOIN en_relationship_type
        ON en_asset_relationship.`rel_type_id` = en_relationship_type.`rel_type_id`
        WHERE en_asset_relationship.`status` = 'y' AND en_relationship_type.`status` = 'y'
        AND (en_asset_relationship.parent_asset_id = UUID_TO_BIN('08c72c2e-5504-11e9-b6d3-0242ac110006')
        OR en_asset_relationship.child_asset_id = UUID_TO_BIN('08c72c2e-5504-11e9-b6d3-0242ac110006')
        )
        ORDER BY en_relationship_type.`rel_type_id`
        */
        DB::enableQueryLog();
        $query = DB::table('en_asset_relationship')   
                ->select(
                        DB::raw('BIN_TO_UUID(en_asset_relationship.asset_relationship_id) AS asset_relationship_id'),
                        DB::raw('BIN_TO_UUID(en_asset_relationship.parent_asset_id) AS parent_asset_id'),
                        DB::raw('BIN_TO_UUID(en_asset_relationship.child_asset_id) AS child_asset_id'),
                        DB::raw('BIN_TO_UUID(en_asset_relationship.rel_type_id) AS rel_type_id'),
                        DB::raw('(SELECT asset_tag FROM en_assets WHERE asset_id = en_asset_relationship.parent_asset_id) AS parent_asset_name'),
                        DB::raw('(SELECT asset_tag FROM en_assets WHERE asset_id = en_asset_relationship.child_asset_id) AS child_asset_name'),
                        'en_relationship_type.rel_type',
                        'en_relationship_type.inverse_rel_type','en_asset_relationship.ci_templ_id')
                ->leftJoin('en_relationship_type', 'en_asset_relationship.rel_type_id', '=', 'en_relationship_type.rel_type_id')
                ->where('en_asset_relationship.status', '=', 'y')
                ->where('en_relationship_type.status', '=', 'y')
                
                ->where(function($query) use ($asset_id){
                    return $query
                    ->where('en_asset_relationship.parent_asset_id', '=', DB::raw('UUID_TO_BIN("'.$asset_id.'")'))
                    ->orWhere('en_asset_relationship.child_asset_id', '=', DB::raw('UUID_TO_BIN("'.$asset_id.'")'));
                });
                $query->orderBy('en_relationship_type.rel_type_id', 'asc');

        $data = $query->get();

        apilog('------------------get_asset_relationship query----------');
        $laQuery = DB::getQueryLog();
        apilog(json_encode($laQuery));
        apilog('--------------------------------------------------');

        return $data;
    }
}