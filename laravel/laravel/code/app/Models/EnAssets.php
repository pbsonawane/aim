<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnAssets extends Model 
{
	use HasBinaryUuid;
    public $incrementing = false;
	protected $table = 'en_assets';
   	public $timestamps = true;	
    protected $fillable = [
        'asset_id', 
        'asset_sku',
        'asset_tag', 
        'display_name',
        'bv_id', 
        'department_id',
        'po_id', 
        'location_id', 
        'parent_asset_id',
        'object_id',
        'ci_templ_id',
        'ci_templ_type',
        'asset_status',
        'status',
    ];
	
	protected $primaryKey = 'asset_id';
	public function getKeyName()
    {
        return 'asset_id';
    }
    /*  
    * This is model function is used get all Cost Centers data

    * @author       Amit Khairnar
    * @access       public
    * @param        asset_id
    * @param_type   integer
    * @return       array
    * @tables       en_assets
    */
	
	protected function getassets($inputdata=array(), $count=false)
    {
        $parent_asset_id = _isset($inputdata,'parent_asset_id');
        $asset_id = _isset($inputdata,'asset_id');
        $searchkeyword = _isset($inputdata,'searchkeyword');
        $ci_templ_id = _isset($inputdata,'ci_templ_id');
        $bv_id = _isset($inputdata,'bv_id');
        $location_id = _isset($inputdata,'location_id');
        $asset_status = _isset($inputdata,'asset_status');
        $skip_ids = _isset($inputdata,'skip_ids');
        $po_id = _isset($inputdata,'po_id');
        $asset_sku = _isset($inputdata,'asset_sku');

        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_assets AS a')   
                ->leftJoin('en_asset_details AS ad', 'a.asset_id', '=', 'ad.asset_id') 

                ->select(DB::raw('BIN_TO_UUID(a.asset_id) AS asset_id'),DB::raw('BIN_TO_UUID(a.parent_asset_id) AS parent_asset_id'), DB::raw('BIN_TO_UUID(a.department_id) AS department_id'), DB::raw('BIN_TO_UUID(a.ci_templ_id) AS ci_templ_id'),DB::raw('BIN_TO_UUID(a.po_id) AS po_id') , 'a.asset_sku','a.asset_tag','a.display_name','a.asset_sku', DB::raw('BIN_TO_UUID(a.bv_id) AS bv_id'), DB::raw('BIN_TO_UUID(a.location_id) AS location_id'), DB::raw('BIN_TO_UUID(a.object_id) AS object_id'), DB::raw('BIN_TO_UUID(a.ci_templ_id) AS ci_templ_id'), 'a.ci_templ_type', 'a.asset_status', 'a.status', DB::raw('BIN_TO_UUID(ad.asset_detail_id) AS asset_detail_id'),'ad.asset_details','ad.auto_discovered','ad.add_comment','a.created_at',DB::raw('BIN_TO_UUID(ad.vendor_id) AS vendor_id'),'ad.purchasecost','ad.acquisitiondate','ad.expirydate','ad.warrantyexpirydate', DB::raw('(SELECT po_name FROM en_form_data_po WHERE po_id = a.po_id) AS po_name'))             

                ->where('a.status', '!=', 'd');
                // ->where('a.asset_status', '!=', 'in_procurement');

                if(isset($ci_templ_id) && $ci_templ_id == '27e1f36e-2779-11ed-ad63-eabef2fa66d0'){                    
                }else{
                    $query->where('a.asset_status', '!=', 'in_procurement');
                }
                
                $query->where(function ($query) use ($searchkeyword){
                    $query->when($searchkeyword, function ($query) use ($searchkeyword)
                    {
                         return $query->where('a.asset_tag', 'like', '%' . $searchkeyword . '%')
                        ->orWhere('a.display_name', 'like', '%' . $searchkeyword . '%')
                        ->orWhere(DB::raw('JSON_EXTRACT(asset_details, "$.serial_number")'),'like', '%' . $searchkeyword . '%');
                        //->orWhere('en_assets.description', 'like', '%' . $searchkeyword . '%');
                    });       
                    
                });

                $query->where(function ($query) use ($ci_templ_id){
                    $query->when($ci_templ_id, function ($query) use ($ci_templ_id)
                    {
                        
                         return $query->where('a.ci_templ_id', '=', DB::raw('UUID_TO_BIN("'.$ci_templ_id.'")'));
                       
                    });       
                    
                });
                $query->where(function ($query) use ($asset_id){
                    $query->when($asset_id, function ($query) use ($asset_id)
                    {
                        
                         return $query->where('a.asset_id', '=', DB::raw('UUID_TO_BIN("'.$asset_id.'")'));
                       
                    });       
                    
                });
                $query->where(function ($query) use ($parent_asset_id){
                    $query->when($parent_asset_id, function ($query) use ($parent_asset_id)
                    {
                         return $query->where('a.parent_asset_id', '=', DB::raw('UUID_TO_BIN("'.$parent_asset_id.'")'));
                       
                    });       
                    
                });
                $query->where(function ($query) use ($asset_sku){
                    $query->when($asset_sku, function ($query) use ($asset_sku)
                    {
                         return $query->where('a.asset_sku', '=', $asset_sku);
                       
                    });       
                    
                });

                $query->where(function ($query) use ($bv_id){
                    $query->when($bv_id, function ($query) use ($bv_id)
                    {
                         return $query->where('a.bv_id', '=', DB::raw('UUID_TO_BIN("'.$bv_id.'")'));
                       
                    });       
                    
                });
                $query->where(function ($query) use ($location_id){
                    $query->when($location_id, function ($query) use ($location_id)
                    {
                         return $query->where('a.location_id', '=', DB::raw('UUID_TO_BIN("'.$location_id.'")'));
                       
                    });       
                    
                });

                $query->where(function ($query) use ($asset_status){
                    $query->when($asset_status, function ($query) use ($asset_status)
                    {
                         return $query->where('a.asset_status', '=', $asset_status);
                       
                    });       
                    
                });

                if(is_array($skip_ids) && count($skip_ids) > 0){
                    $query->whereNotIn(DB::raw('BIN_TO_UUID(a.asset_id)'),$skip_ids);
                }
  
                $query->when(!$count, function ($query) use ($inputdata)
                        {
                            if( isset($inputdata["offset"]) && isset($inputdata["limit"]) )
                            {
                                return $query->offset($inputdata["offset"])->limit($inputdata["limit"]);
                            }
                        });

                if(isset($po_id) && $po_id != ''  && $po_id != '0'){
                    $query->where('a.po_id', '=', DB::raw('UUID_TO_BIN("'.$po_id.'")'));
                }
                $query->orderBy('a.created_at', 'desc');
                
                $data = $query->get();  
				$queries    = DB::getQueryLog();
				$last_query = end($queries);
				apilog('---Asset Model query---');
				apilog(json_encode($last_query));
                                            
        if($count)
            return   count($data);
        else      
            return $data;    
    }
	
	protected function getallassets($asset_id, $inputdata=array(), $count=false)
    {
    
        $searchkeyword = _isset($inputdata,'searchkeyword');
		$asset_ids = _isset($inputdata,'asset_ids');
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
		/*$query = DB::table('en_assets AS a')   
                ->leftJoin('en_asset_details AS ad', 'a.asset_id', '=', 'ad.asset_id') 
                ->select(DB::raw('BIN_TO_UUID(a.asset_id) AS asset_id'),DB::raw('BIN_TO_UUID(a.parent_asset_id) AS parent_asset_id'), DB::raw('BIN_TO_UUID(a.po_id) AS po_id') , 'a.asset_tag','a.display_name', DB::raw('BIN_TO_UUID(a.bv_id) AS bv_id'), DB::raw('BIN_TO_UUID(a.location_id) AS location_id'), DB::raw('BIN_TO_UUID(a.object_id) AS object_id'), DB::raw('BIN_TO_UUID(a.ci_templ_id) AS ci_templ_id'), 'a.ci_templ_type', 'a.asset_status', 'a.status', DB::raw('BIN_TO_UUID(ad.asset_detail_id) AS asset_detail_id'),'ad.asset_details','ad.auto_discovered','ad.add_comment','a.created_at',DB::raw('BIN_TO_UUID(ad.vendor_id) AS vendor_id'),'ad.purchasecost','ad.acquisitiondate','ad.expirydate','ad.warrantyexpirydate', DB::raw('(SELECT po_name FROM en_form_data_po WHERE po_id = a.po_id) AS po_name')) */            
        $query = DB::table('en_assets AS ass') 
				->join('en_asset_details AS ad', 'ad.asset_id', '=', 'ass.asset_id') 		
                ->select(DB::raw('BIN_TO_UUID(ass.asset_id) AS asset_id'),'ass.asset_tag', 'ass.display_name','ass.asset_status','ass.asset_sku','ass.ci_templ_type','ass.status',DB::raw('BIN_TO_UUID(ass.parent_asset_id) AS parent_asset_id'), DB::raw('BIN_TO_UUID(ass.po_id) AS po_id') ,DB::raw('BIN_TO_UUID(ass.bv_id) AS bv_id'), DB::raw('BIN_TO_UUID(ass.location_id) AS location_id'), DB::raw('BIN_TO_UUID(ass.object_id) AS object_id'), DB::raw('BIN_TO_UUID(ass.ci_templ_id) AS ci_templ_id'),'ad.asset_details','ad.auto_discovered','ad.add_comment','ass.created_at',DB::raw('BIN_TO_UUID(ad.vendor_id) AS vendor_id'),'ad.purchasecost','ad.acquisitiondate','ad.expirydate','ad.warrantyexpirydate', DB::raw('(SELECT po_name FROM en_form_data_po WHERE po_id = ass.po_id) AS po_name'))
                ->where('ass.status', '!=', 'd');
                
                

                $query->where(function ($query) use ($searchkeyword, $asset_id){
                    $query->where(function ($query) use ($searchkeyword, $asset_id) {
                        $query->when($searchkeyword, function ($query) use ($searchkeyword)
                            {
                                
                                 return $query->where('ass.asset_tag', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('ass.display_name', 'like', '%' . $searchkeyword . '%');
                               });       
                        });
                        $query->when($asset_id, function ($query) use ($asset_id)
                        {
                            return $query->where('ass.asset_id', '=', DB::raw('UUID_TO_BIN("'.$asset_id.'")'));
                        });});
						
						
			$query->where(function ($query) use ($asset_ids){
                    $query->when($asset_ids, function ($query) use ($asset_ids)
                    {
                         return $query->whereIn(DB::raw('BIN_TO_UUID(ass.asset_id)'), $asset_ids);
						 
                       
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
	/*protected function getallassets($asset_id, $inputdata=array(), $count=false)
    {
        $searchkeyword = _isset($inputdata,'searchkeyword');
        if(isset($inputdata["limit"]) && $inputdata["limit"] < 1)
        {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_assets')   
                ->select(DB::raw('BIN_TO_UUID(asset_id) AS asset_id'), 'asset_tag', 'display_name','asset_status','status')       
                ->where('en_assets.status', '!=', 'd');
                 $query->where(function ($query) use ($searchkeyword, $asset_id){
                    $query->where(function ($query) use ($searchkeyword, $asset_id) {
                        $query->when($searchkeyword, function ($query) use ($searchkeyword)
                            {
                                
                                  return $query->where('en_assets.asset_tag', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_assets.display_name', 'like', '%' . $searchkeyword . '%')
                                //->orWhere('en_assets.description', 'like', '%' . $searchkeyword . '%');
                            });       
                        });
                        $query->when($asset_id, function ($query) use ($asset_id)
                        {
                            return $query->where('en_assets.asset_id', '=', DB::raw('UUID_TO_BIN("'.$asset_id.'")'));
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
            return  count($data);
        else      
            return $data;    
    	}*/

    
}
