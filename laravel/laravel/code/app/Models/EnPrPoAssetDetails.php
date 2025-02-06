<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnPrPoAssetDetails extends Model 
{
	use HasBinaryUuid;
  public $incrementing = false;
  protected $table = 'en_pr_po_asset_details';
   //	public $timestamps = false;	
  protected $fillable = [
    'pr_po_asset_id', 'pr_po_id','convert_status', 'asset_type','vendor_approval','asset_details','created_by', 'status', 'created_at', 'updated_at'
  ];

  protected $primaryKey = 'pr_po_asset_id';
  public function getKeyName()
  {
    return 'pr_po_asset_id';
  }
    /**
     * This is model function is used to Destroy [Multiple] Asset by its pr_id
     * @author Namrata Thakur
     * @access protected
     * @param int $pr_id
     * @return array
     */
    protected function getPrPoAssetDetails($pr_po_id, $asset_type,$vendor_id = '',$pr_po_ids='')
    {      
        /*$prpoAssetDetails  = EnPrPoAssetDetails::select(
            DB::raw('BIN_TO_UUID(en_pr_po_asset_details.pr_po_asset_id) AS pr_po_asset_id'), 
            DB::raw('BIN_TO_UUID(en_pr_po_asset_details.pr_po_id) AS pr_po_id'), 
            'en_pr_po_asset_details.asset_type', 'en_pr_po_asset_details.asset_details','en_ci_quotation_comparison.vendor_approve',
            DB::raw('BIN_TO_UUID(en_pr_po_asset_details.created_by) AS created_by'), 
            'en_pr_po_asset_details.status', 'en_pr_po_asset_details.created_at', 'en_pr_po_asset_details.updated_at','en_pr_po_asset_details.convert_status')
            ->leftJoin('en_ci_quotation_comparison', DB::raw('JSON_UNQUOTE(JSON_EXTRACT(en_pr_po_asset_details.asset_details, "$.item"))'), '=',DB::raw('bin_to_uuid(en_ci_quotation_comparison.selected_item_id)'))        
            ->where('en_pr_po_asset_details.pr_po_id', '=', DB::raw('UUID_TO_BIN("'.$pr_po_id.'")'))  
            ->where('en_pr_po_asset_details.asset_type', $asset_type)      
            ->where('en_pr_po_asset_details.status', '!=', 'd')                  
            ->get();   */
            if(empty($vendor_id)){
              $prpoAssetDetails  = EnPrPoAssetDetails::select(
                DB::raw('BIN_TO_UUID(en_pr_po_asset_details.pr_po_asset_id) AS pr_po_asset_id'), 
                DB::raw('BIN_TO_UUID(en_pr_po_asset_details.pr_po_id) AS pr_po_id'), 
                'en_pr_po_asset_details.asset_type', 'en_pr_po_asset_details.asset_details','en_ci_quotation_comparison.vendor_approve','en_ci_quotation_comparison.approval','en_pr_po_asset_details.vendor_approval',
                DB::raw('BIN_TO_UUID(en_pr_po_asset_details.created_by) AS created_by'), 
                'en_pr_po_asset_details.status', 'en_pr_po_asset_details.created_at', 'en_pr_po_asset_details.updated_at','en_pr_po_asset_details.convert_status')
              ->leftJoin('en_ci_quotation_comparison', function($join){
                $join->on(DB::raw('JSON_UNQUOTE(JSON_EXTRACT(en_pr_po_asset_details.asset_details, "$.item_product"))'), '=',DB::raw('bin_to_uuid(en_ci_quotation_comparison.selected_item_id)'));
                $join->on('en_ci_quotation_comparison.pr_po_id','=', 'en_pr_po_asset_details.pr_po_id');
              })             
              ->where('en_pr_po_asset_details.pr_po_id', '=', DB::raw('UUID_TO_BIN("'.$pr_po_id.'")'))  
              ->where('en_pr_po_asset_details.asset_type', $asset_type)      
              ->where('en_pr_po_asset_details.status', '!=', 'd')                             
              ->get(); 
            }elseif(!empty($pr_po_id) && !empty($vendor_id)){

              if(is_array($pr_po_ids))
              {
                $ids = array_map(function($pr_po_id){
                  return DB::raw('UUID_TO_BIN("'.$pr_po_id.'")');
                }, $pr_po_ids);

               $prpoAssetDetails  = EnPrPoAssetDetails::select(
                DB::raw('BIN_TO_UUID(en_pr_po_asset_details.pr_po_asset_id) AS pr_po_asset_id'), 
                DB::raw('BIN_TO_UUID(en_pr_po_asset_details.pr_po_id) AS pr_po_id'), 
                'en_pr_po_asset_details.asset_type', 'en_pr_po_asset_details.asset_details','en_ci_quotation_comparison.vendor_approve','en_ci_quotation_comparison.quotation_comparison_data','en_ci_quotation_comparison.approval','en_pr_po_asset_details.vendor_approval',
                DB::raw('BIN_TO_UUID(en_pr_po_asset_details.created_by) AS created_by'), 
                'en_pr_po_asset_details.status', 'en_pr_po_asset_details.created_at', 'en_pr_po_asset_details.updated_at','en_pr_po_asset_details.convert_status')
               ->leftJoin('en_ci_quotation_comparison', function($join){
                $join->on(DB::raw('JSON_UNQUOTE(JSON_EXTRACT(en_pr_po_asset_details.asset_details, "$.item_product"))'), '=',DB::raw('bin_to_uuid(en_ci_quotation_comparison.selected_item_id)'));
                $join->on('en_ci_quotation_comparison.pr_po_id','=', 'en_pr_po_asset_details.pr_po_id');
              })             
               ->whereIn('en_pr_po_asset_details.pr_po_id',$ids)  
               ->where('en_pr_po_asset_details.asset_type', $asset_type)      
               ->where('en_pr_po_asset_details.status', '!=', 'd')                             
               ->where(DB::raw('JSON_EXTRACT(en_ci_quotation_comparison.vendor_approve, "$.vendor_id")'), '=', $vendor_id)                             
               ->get(); 
             }else{
               $prpoAssetDetails  = EnPrPoAssetDetails::select(
                DB::raw('BIN_TO_UUID(en_pr_po_asset_details.pr_po_asset_id) AS pr_po_asset_id'), 
                DB::raw('BIN_TO_UUID(en_pr_po_asset_details.pr_po_id) AS pr_po_id'), 
                'en_pr_po_asset_details.asset_type', 'en_pr_po_asset_details.asset_details','en_ci_quotation_comparison.vendor_approve','en_ci_quotation_comparison.approval','en_pr_po_asset_details.vendor_approval',
                DB::raw('BIN_TO_UUID(en_pr_po_asset_details.created_by) AS created_by'), 
                'en_pr_po_asset_details.status', 'en_pr_po_asset_details.created_at', 'en_pr_po_asset_details.updated_at','en_pr_po_asset_details.convert_status')
               ->leftJoin('en_ci_quotation_comparison', function($join){
                $join->on(DB::raw('JSON_UNQUOTE(JSON_EXTRACT(en_pr_po_asset_details.asset_details, "$.item_product"))'), '=',DB::raw('bin_to_uuid(en_ci_quotation_comparison.selected_item_id)'));
                $join->on('en_ci_quotation_comparison.pr_po_id','=', 'en_pr_po_asset_details.pr_po_id');
              })             
               ->where('en_pr_po_asset_details.pr_po_id', '=', DB::raw('UUID_TO_BIN("'.$pr_po_id.'")'))  
               ->where('en_pr_po_asset_details.asset_type', $asset_type)      
               ->where('en_pr_po_asset_details.status', '!=', 'd')                             
               ->where(DB::raw('JSON_EXTRACT(en_ci_quotation_comparison.vendor_approve, "$.vendor_id")'), '=', $vendor_id)                             
               ->get(); 
             }

           }else{
            $prpoAssetDetails  = EnPrPoAssetDetails::select(
              DB::raw('BIN_TO_UUID(en_pr_po_asset_details.pr_po_asset_id) AS pr_po_asset_id'), 
              DB::raw('BIN_TO_UUID(en_pr_po_asset_details.pr_po_id) AS pr_po_id'), 
              'en_pr_po_asset_details.asset_type', 'en_pr_po_asset_details.asset_details','en_ci_quotation_comparison.vendor_approve','en_ci_quotation_comparison.approval','en_pr_po_asset_details.vendor_approval','en_form_data_pr.pr_no',
              DB::raw('BIN_TO_UUID(en_pr_po_asset_details.created_by) AS created_by'), 
              'en_pr_po_asset_details.status', 'en_pr_po_asset_details.created_at', 'en_pr_po_asset_details.updated_at','en_pr_po_asset_details.convert_status')
            ->leftJoin('en_ci_quotation_comparison', function($join){
              $join->on(DB::raw('JSON_UNQUOTE(JSON_EXTRACT(en_pr_po_asset_details.asset_details, "$.item_product"))'), '=',DB::raw('bin_to_uuid(en_ci_quotation_comparison.selected_item_id)'));
              $join->on('en_ci_quotation_comparison.pr_po_id','=', 'en_pr_po_asset_details.pr_po_id');
            })          
            ->leftJoin('en_form_data_pr', 'en_form_data_pr.pr_id', '=', 'en_ci_quotation_comparison.pr_po_id')     
            ->where('en_pr_po_asset_details.asset_type', $asset_type)      
            ->where('en_ci_quotation_comparison.approval', '=', 'approved')                             
            ->where('en_pr_po_asset_details.status', '!=', 'd')                             
            ->where(DB::raw('JSON_EXTRACT(en_ci_quotation_comparison.vendor_approve, "$.vendor_id")'), '=', $vendor_id)                             
            ->whereNull(DB::raw('JSON_EXTRACT(en_ci_quotation_comparison.vendor_approve, "$.converted_as_po")'))                             
            ->get(); 
          }

             /*
             select en_form_data_pr.pr_no,BIN_TO_UUID(en_pr_po_asset_details.pr_po_asset_id) AS pr_po_asset_id, BIN_TO_UUID(en_pr_po_asset_details.pr_po_id) AS pr_po_id, `en_pr_po_asset_details`.`asset_type`, `en_pr_po_asset_details`.`asset_details`, `en_ci_quotation_comparison`.`vendor_approve`, `en_ci_quotation_comparison`.`approval`, `en_pr_po_asset_details`.`vendor_approval`, BIN_TO_UUID(en_pr_po_asset_details.created_by) AS created_by, `en_pr_po_asset_details`.`status`, `en_pr_po_asset_details`.`created_at`, `en_pr_po_asset_details`.`updated_at`, `en_pr_po_asset_details`.`convert_status` from `en_pr_po_asset_details` 
left join `en_ci_quotation_comparison` on JSON_UNQUOTE(JSON_EXTRACT(en_pr_po_asset_details.asset_details, "$.item_product")) = bin_to_uuid(en_ci_quotation_comparison.selected_item_id) and `en_ci_quotation_comparison`.`pr_po_id` = `en_pr_po_asset_details`.`pr_po_id` 
left join en_form_data_pr ON en_form_data_pr.pr_id = en_ci_quotation_comparison.pr_po_id
where  `en_pr_po_asset_details`.`asset_type` = 'pr' and `en_pr_po_asset_details`.`status` != 'd' and JSON_EXTRACT(en_ci_quotation_comparison.vendor_approve, "$.vendor_id") = '3c33c450-1c58-11ec-a3c7-4a4901e9af12'
and JSON_EXTRACT(en_ci_quotation_comparison.vendor_approve, "$.converted_as_po") is null;
*/


            //$prpoAssetDetails = DB::select('exec getAssetsData("'.$asset_type.'","'.$pr_po_id.'")');
/* SELECT a.pr_po_id,bin_to_uuid(a.pr_po_id),a.asset_details,q.vendor_approve,q.selected_item_name FROM en_pr_po_asset_details a LEFT JOIN en_ci_quotation_comparison q on JSON_UNQUOTE(JSON_EXTRACT(a.`asset_details`, "$.item")) = bin_to_uuid(q.selected_item_id) where a.`pr_po_id`= uuid_to_bin('d8153374-575c-11ec-aff8-de45d7d88510');*/


        /* // original
        $prpoAssetDetails = EnPrPoAssetDetails::select(DB::raw('BIN_TO_UUID(pr_po_asset_id) AS pr_po_asset_id'), DB::raw('BIN_TO_UUID(pr_po_id) AS pr_po_id'), 'asset_type', 'asset_details','vendor_approval',DB::raw('BIN_TO_UUID(created_by) AS created_by'), 'status', 'created_at', 'updated_at','convert_status')
            ->where('pr_po_id', '=', DB::raw('UUID_TO_BIN("'.$pr_po_id.'")'))  
            ->where('asset_type', $asset_type)      
            ->where('status', '!=', 'd')            
            ->orderBy('created_at', 'desc')      
            ->get();   */
            return $prpoAssetDetails;
          }	
    /**
     * This is model function is used to Destroy [Multiple] Asset by its pr_id
     * @author Namrata Thakur
     * @access protected
     * @param int $pr_id
     * @return array
     */
    protected function destroyassetbyprpo($pr_po_id, $asset_type = "")
    {
      $prs = EnPrPoAssetDetails::where('pr_po_id', '=', $pr_po_id)->where('asset_type', '=', $asset_type)->delete();
      return $prs;
    }

  }