<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;
use App\Models\EnDatacenters;
use App\Models\EnUsers;

class EnContract extends Model 
{
	use HasBinaryUuid;
    public $incrementing = false;
	protected $table = 'en_contract';
   	//public $timestamps = false;	
    protected $fillable = [
        'contract_id','parent_contract','vendor_id','contract_type_id','contractid','contract_name','renewed','from_date','to_date','contract_status','status','primary_contract','user_id','renewed_to'
    ];
    
    
    
	protected $primaryKey = 'contract_id';
	public function getKeyName()
    {
        return 'contract_id';
    }
    protected function getcontract($contract_id, $inputdata=[], $count=false)
    {
        $searchkeyword = _isset($inputdata,'searchkeyword');
        $contracts = [];
        $contract_id_ = $searchkeyword = null;
        $contract_id = _isset($inputdata, "contract_id");
        $advcontract_type_id = _isset($inputdata, "advcontract_type_id");
        $advcontract_status = _isset($inputdata, "advcontract_status");
       
        $searchkeyword_ = _isset($inputdata, "searchkeyword");
        $searchkeyword = $searchkeyword != null ? $searchkeyword : $searchkeyword_;
        
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_contract AS c')   
                ->select(DB::raw('BIN_TO_UUID(c.contract_id) AS contract_id'),DB::raw('BIN_TO_UUID(c.parent_contract) AS parent_contract'),DB::raw('BIN_TO_UUID(c.renewed_to) AS renewed_to'),DB::raw('BIN_TO_UUID(c.primary_contract) AS primary_contract'),DB::raw('BIN_TO_UUID(c.user_id) AS user_id'),DB::raw('BIN_TO_UUID(en_ci_vendors.vendor_id) AS vendor_id'),'en_ci_vendors.vendor_name','en_ci_vendors.contact_person','en_ci_vendors.address','en_ci_vendors.contactno','en_contract_type.contract_type','ccp.contract_name AS parent_name',DB::raw('BIN_TO_UUID(en_contract_details.contract_id) as contract_id'),
                DB::raw('BIN_TO_UUID(en_contract_details.contract_details_id) as contract_details_id'),
                 'en_contract_details.asset_id',
                'en_contract_details.support','en_contract_details.description','en_contract_details.cost','en_contract_details.attachments',
                DB::raw('BIN_TO_UUID(en_contract_type.contract_type_id) as contract_type_id'),'c.contractid','c.contract_name','c.renewed','c.from_date','c.to_date','c.contract_status','c.status')
                ->leftjoin('en_ci_vendors', 'c.vendor_id', '=', 'en_ci_vendors.vendor_id') 
                ->leftjoin('en_contract_type', 'c.contract_type_id', '=', 'en_contract_type.contract_type_id')->leftjoin('en_contract_details', 'c.contract_id', '=', 'en_contract_details.contract_id') 
                ->leftjoin('en_contract AS cc', 'c.contract_id', '=', 'cc.contract_id')
                ->leftjoin('en_contract AS ccp', 'ccp.contract_id', '=', 'c.parent_contract')
                ->where('c.status', '!=', 'd')
               ->orderBy('c.contract_id', 'DESC');

            $query->when($advcontract_type_id, function ($query) use ($advcontract_type_id)
                {
                    return $query->where('c.contract_type_id', DB::raw('UUID_TO_BIN("'.$advcontract_type_id.'")'));       
                });
                $query->when($advcontract_status, function ($query) use ($advcontract_status)
                {
                    return $query->where('c.contract_status', $advcontract_status);       
                });
                $query->where(function ($query) use ($searchkeyword, $contract_id){
                    $query->where(function ($query) use ($searchkeyword, $contract_id) {
                        $query->when($searchkeyword, function ($query) use ($searchkeyword)
                            {
                                
                                 return $query->where('c.contract_name', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('c.vendor_id', 'like', '%' . $searchkeyword . '%');
                               });       
                        });
                        $query->when($contract_id, function ($query) use ($contract_id)
                        {
                            return $query->where('c.contract_id', '=', DB::raw('UUID_TO_BIN("'.$contract_id.'")'));
                        });
                    });
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
    * @param        contract_id
    * @param_type   Integer
    * @return       Array
    * @tables       en_contract
    */
    protected function checkforrelation($contract_id)
    {
        if($contract_id)
        {
            $contract_data= EnContract::where('contract_id', DB::raw('UUID_TO_BIN("'.$contract_id.'")'))->first();
            if($contract_data)
            {    
                    
                    $contract_data->update(['status' => 'd']);            
                    $contract_data->save();                     
                    $data['data']['deleted_id'] = $contract_id;
                    $data['message']['success']= 'Record Deleted Successfully.';
                    $data['status'] = 'success';
               
            }
            else
            {
                $data['data'] = NULL;
                $data['message']['error'] = 'Record Not Found.';
                $data['status'] = 'error';                             
            }               
        }
        else
        {
            $data['data'] = NULL;
            $data['message']['error'] = 'The Id field is required';
            $data['status'] = 'error';                             
        }   
        return $data;    
    }   
    //SELECT * FROM en_contract WHERE parent_contract = {contract_id};

    protected function childcontract($contract_id)
    { 
              $query = DB::table('en_contract AS c')  
              ->select(DB::raw('BIN_TO_UUID(c.contract_id) AS contract_id'),DB::raw('BIN_TO_UUID(c.parent_contract) AS parent_contract'),'c.contract_name','c.contract_name','c.contract_status','c.from_date','c.to_date','c.contractid','ct.contract_type','cd.description')
                
                 ->leftjoin('en_contract_type AS ct', 'c.contract_type_id', '=', 'ct.contract_type_id')
                 ->leftjoin('en_contract_details AS cd', 'c.contract_id', '=', 'cd.contract_id') 
               ->where('c.parent_contract', '=' ,  DB::raw('UUID_TO_BIN("'.$contract_id.'")'));

               $data = $query->get();                        
                                            
              return $data;    

    }

 protected function associatechildcontract($contract_id, $vendor_id=null)
    { 
           
              $query = DB::table('en_contract AS c')  
              ->select(DB::raw('BIN_TO_UUID(c.contract_id) AS contract_id'),DB::raw('BIN_TO_UUID(c.parent_contract) AS parent_contract'),'c.contract_name','c.contract_status','c.from_date','c.to_date','c.contractid','ct.contract_type','cd.description',DB::raw('BIN_TO_UUID(v.vendor_id) AS vendor_id'))
                
                 ->leftjoin('en_contract_type AS ct', 'c.contract_type_id', '=', 'ct.contract_type_id')
                 ->leftjoin('en_contract_details AS cd', 'c.contract_id', '=', 'cd.contract_id') 
                 ->leftjoin('en_ci_vendors AS v', 'c.vendor_id', '=', 'v.vendor_id') 
                //->where('c.contract_status', '=', 'active');    
                ->where('c.contract_id', '!=', DB::raw('UUID_TO_BIN("'.$contract_id.'")'));
               

                   $query->where(function ($query) use ($vendor_id, $contract_id)
            {
            $query->where(function ($query) use ($vendor_id, $contract_id)
            {
                $query->when($vendor_id, function ($query) use ($vendor_id)
                {
                    return 
                    $query->WhereNull('c.parent_contract')
                       ->where('c.contract_status', '=', 'active')          
                    ->where('c.vendor_id', '=', $vendor_id);  
                });
            });
           
           });

               $data = $query->get();                        
               return($data);                      
              //print_r($data);    

    }
    protected function renewcontract($primary_contract, $count = false)
    { 
        $query = DB::table('en_contract AS c')  
              ->select(DB::raw('BIN_TO_UUID(c.contract_id) AS contract_id'),DB::raw('BIN_TO_UUID(c.parent_contract) AS parent_contract'),'c.contract_name','c.contract_name','c.contract_status','c.from_date','c.to_date','c.contractid','c.created_at','ct.contract_type','cd.description','cd.cost')
                
                 ->leftjoin('en_contract_type AS ct', 'c.contract_type_id', '=', 'ct.contract_type_id')
                 ->leftjoin('en_contract_details AS cd', 'c.contract_id', '=', 'cd.contract_id') 
               ->where('c.primary_contract', '=' ,  DB::raw('UUID_TO_BIN("'.$primary_contract.'")'))
               ->orderBy('c.contract_id', 'DESC');
              // ->where('c.contract_id', '!=' ,  DB::raw('UUID_TO_BIN("'.$contract_id.'")'));


               $data = $query->get();                        
               if($count) 
                return count($data);    
              else
                return $data;    
    }
	protected function getassetcontract($asset_id, $count = false)
    { 
        $query = DB::table('en_contract AS c')  
              ->select(DB::raw('BIN_TO_UUID(c.contract_id) AS contract_id'),DB::raw('BIN_TO_UUID(c.parent_contract) AS parent_contract'),'c.contract_name','c.contract_name','c.contract_status','c.from_date','c.to_date','c.contractid','c.created_at','cd.description','cd.cost','ct.contract_type')
                
                 ->leftjoin('en_contract_type AS ct', 'c.contract_type_id', '=', 'ct.contract_type_id')
                 ->leftjoin('en_contract_details AS cd', 'c.contract_id', '=', 'cd.contract_id') 
				// ->whereJsonContains('cd.asset_id',$asset_id)
				 ->whereRaw('json_contains(cd.asset_id, \'["' . $asset_id . '"]\')')
				 ->where('c.status', '!=', 'd')
               ->orderBy('c.contract_id', 'DESC');
              // ->where('c.contract_id', '!=' ,  DB::raw('UUID_TO_BIN("'.$contract_id.'")'));


               $data = $query->get();                        
               if($count) 
                return count($data);    
              else
                return $data;    
    }
	
	
		protected function getallassets($asset_id, $inputdata=[], $count=false)
    {
    
        $searchkeyword = _isset($inputdata,'searchkeyword');
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
		/*$query = DB::table('en_assets AS a')   
                ->leftJoin('en_asset_details AS ad', 'a.asset_id', '=', 'ad.asset_id') 
                ->select(DB::raw('BIN_TO_UUID(a.asset_id) AS asset_id'),DB::raw('BIN_TO_UUID(a.parent_asset_id) AS parent_asset_id'), DB::raw('BIN_TO_UUID(a.po_id) AS po_id') , 'a.asset_tag','a.display_name', DB::raw('BIN_TO_UUID(a.bv_id) AS bv_id'), DB::raw('BIN_TO_UUID(a.location_id) AS location_id'), DB::raw('BIN_TO_UUID(a.object_id) AS object_id'), DB::raw('BIN_TO_UUID(a.ci_templ_id) AS ci_templ_id'), 'a.ci_templ_type', 'a.asset_status', 'a.status', DB::raw('BIN_TO_UUID(ad.asset_detail_id) AS asset_detail_id'),'ad.asset_details','ad.auto_discovered','ad.add_comment','a.created_at',DB::raw('BIN_TO_UUID(ad.vendor_id) AS vendor_id'),'ad.purchasecost','ad.acquisitiondate','ad.expirydate','ad.warrantyexpirydate', DB::raw('(SELECT po_name FROM en_form_data_po WHERE po_id = a.po_id) AS po_name')) */            
        $query = DB::table('en_assets as a') 
				//->leftJoin('en_asset_details', 'en_assets.asset_id', '=', 'en_asset_details.asset_id') 		
                ->select(DB::raw('BIN_TO_UUID(a.asset_id) AS asset_id'),'a.asset_tag', 'a.display_name','a.asset_status','a.ci_templ_type','a.status')
                ->where('a.status', '!=', 'd');
                
                

                $query->where(function ($query) use ($searchkeyword, $asset_id){
                    $query->where(function ($query) use ($searchkeyword, $asset_id) {
                        $query->when($searchkeyword, function ($query) use ($searchkeyword)
                            {
                                
                                 return $query->where('a.asset_tag', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('a.display_name', 'like', '%' . $searchkeyword . '%');
                               });       
                        });
                        $query->when($asset_id, function ($query) use ($asset_id)
                        {
                            return $query->where('a.asset_id', '=', DB::raw('UUID_TO_BIN("'.$asset_id.'")'));
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
	
	 /* This function is used to delete the email template
    * @author       Snehal C
    * @access       public
    * @param        template_id
    * @param_type   Integer
    * @return       Array
    * @tables       en_email_template
    */

    protected function changecontractstatus($contract_id, $contract_status)
    {
        if($contract_id)
        {

            DB::table('en_contract')
		   // ->whereIn(DB::raw('BIN_TO_UUID(contract_id)'), $contract_ids);
			->where('contract_id', DB::raw('UUID_TO_BIN("'.$contract_id.'")'))
            ->update(['contract_status' => $contract_status]);

            $data['data']['deleted_id'] = $contract_id;
            $data['message']['success']= showmessage('140', ['{name}'], ['Status']);
            $data['status'] = 'success';
        }
        else
        {
            $data['data'] = NULL;
            $data['message']['error'] = showmessage('105', ['{name}'], ['Status']);
            $data['status'] = 'error';                             
        }   
        return $data;    
    }
	

}