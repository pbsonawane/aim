<?php
namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\BinaryUuid\HasBinaryUuid;

class Enprsample extends Model
{
    use HasBinaryUuid;
    public $incrementing = false;
    protected $table = 'en_form_data_pr_sample';
    protected $fillable = [
        'pr_no','details','status'
    ];
    protected $primaryKey = 'pr_id';
    public function getKeyName()
    {
        return 'pr_id';
    }
    protected function getsamplepr()
    {
         $query = DB::table('en_form_data_pr_sample')
            ->select('pr_no',
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(details, "$.pr_req_date")) AS PR_Request_date'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(details, "$.pr_due_date")) AS PR_Due_Date'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(details, "$.pr_shipto")) AS Ship_To_Address'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(details, "$.pr_shipto_contact")) AS Ship_To_Contact'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(details, "$.pr_category")) AS Category'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(details, "$.pr_project_category")) AS Project_Category'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(details, "$.pr_requirement_for")) AS Requirement_For'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(details, "$.pr_priority")) AS Priority'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(details, "$.pr_remark")) AS Remark'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(details, "$.project_name")) AS Project_Name'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(details, "$.pr_department")) AS Department'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(details, "$.opportunity_code")) AS Opportunity_Code'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(details, "$.pr_requester_name")) AS Requester_Name'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(details, "$.pr_project_name_dd")) AS Project_Name'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(details, "$.project_wo_details")) AS Project_wo_Details'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(en_pr_po_asset_details_sample.asset_details, "$.item_product")) AS Item_product'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(en_pr_po_asset_details_sample.asset_details, "$.item_desc")) AS Item_Desc'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(en_pr_po_asset_details_sample.asset_details, "$.item_qty")) AS Item_Qty'),
                DB::raw('JSON_UNQUOTE(JSON_EXTRACT(en_pr_po_asset_details_sample.asset_details, "$.warranty_support_required")) AS Warranty_support'),
                'en_form_data_pr_sample.status AS PR_Status'
            );
          $query->join('en_pr_po_asset_details_sample', 'en_form_data_pr_sample.pr_id','=','en_pr_po_asset_details_sample.pr_po_id');

          $data = $query->get();                        
                                            
        
            return $data;    
    }
}
