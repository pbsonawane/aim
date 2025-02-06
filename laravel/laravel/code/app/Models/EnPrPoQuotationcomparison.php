<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnPrPoQuotationcomparison extends Model
{
    use HasBinaryUuid;
    public $incrementing = false;
    protected $table     = 'en_ci_quotation_comparison';
    //    public $timestamps = false;
    protected $fillable = [
        'quotation_cmp_id', 'pr_po_id', 'quotation_comparison_data', 'created_by', 'status', 'approve_vendor_id', 'approve_option', 'created_at', 'updated_at', 'selected_item_id', 'selected_item_name', 'vendor_approve', 'reject_comment', 'approve_reject_by', 'approval',
    ];

    protected $primaryKey = 'quotation_cmp_id';
    public function getKeyName()
    {
        return 'quotation_cmp_id';
    }
    protected function getQuotationcomparison($inputdata = [])
    {
        $pr_po_id         = _isset($inputdata, 'pr_po_id');
        $selected_item_id = _isset($inputdata, 'selected_item_id');

        $query = EnPrPoQuotationcomparison::select(DB::raw('BIN_TO_UUID(quotation_cmp_id) AS quotation_cmp_id'), DB::raw('BIN_TO_UUID(selected_item_id) AS selected_item_id'), 'selected_item_name', DB::raw('BIN_TO_UUID(pr_po_id) AS pr_po_id'), 'quotation_comparison_data', DB::raw('BIN_TO_UUID(created_by) AS created_by'), 'status', DB::raw('BIN_TO_UUID(approve_vendor_id) AS approve_vendor_id'), 'approve_option', 'created_at', 'updated_at','vendor_approve','reject_comment', DB::raw('BIN_TO_UUID(approve_reject_by) AS approve_reject_by'), 'approval')
            ->where('status', '!=', 'd')
            ->orderBy('created_at', 'desc')
            ->limit(1);

        $query->where(function ($query) use ($pr_po_id) {
            $query->when($pr_po_id, function ($query) use ($pr_po_id) {
                return $query->where('pr_po_id', '=', DB::raw('UUID_TO_BIN("' . $pr_po_id . '")'));
            });
        });
        $query->where(function ($query) use ($selected_item_id) {
            $query->when($selected_item_id, function ($query) use ($selected_item_id) {
                return $query->where('selected_item_id', '=', DB::raw('UUID_TO_BIN("' . $selected_item_id . '")'));
            });
        });
        $data = $query->get();
        return $data;
    }
    protected function getQuotationcomparisonAll($inputdata = [])
    {
        $pr_po_id         = _isset($inputdata, 'pr_po_id');
        $selected_item_id = _isset($inputdata, 'selected_item_id');

        $query = EnPrPoQuotationcomparison::select(DB::raw('BIN_TO_UUID(quotation_cmp_id) AS quotation_cmp_id'), DB::raw('BIN_TO_UUID(selected_item_id) AS selected_item_id'), 'selected_item_name', DB::raw('BIN_TO_UUID(pr_po_id) AS pr_po_id'), 'quotation_comparison_data', DB::raw('BIN_TO_UUID(created_by) AS created_by'), 'status', DB::raw('BIN_TO_UUID(approve_vendor_id) AS approve_vendor_id'), 'approve_option', 'created_at', 'updated_at','vendor_approve','reject_comment', DB::raw('BIN_TO_UUID(approve_reject_by) AS approve_reject_by'), 'approval')
            ->where('status', '!=', 'd')
            ->orderBy('created_at', 'desc');

        $query->where(function ($query) use ($pr_po_id) {
            $query->when($pr_po_id, function ($query) use ($pr_po_id) {
                return $query->where('pr_po_id', '=', DB::raw('UUID_TO_BIN("' . $pr_po_id . '")'));
            });
        });
        $query->where(function ($query) use ($selected_item_id) {
            $query->when($selected_item_id, function ($query) use ($selected_item_id) {
                return $query->where('selected_item_id', '=', DB::raw('UUID_TO_BIN("' . $selected_item_id . '")'));
            });
        });
        $data = $query->get();
        return $data;
    }
    protected function getQuotationcomparisonDetails($inputdata = [])
    {
        $pr_po_id = _isset($inputdata, 'pr_po_id');

        $query = EnPrPoQuotationcomparison::select(DB::raw('BIN_TO_UUID(quotation_cmp_id) AS quotation_cmp_id'), DB::raw('BIN_TO_UUID(selected_item_id) AS selected_item_id'), 'selected_item_name', DB::raw('BIN_TO_UUID(pr_po_id) AS pr_po_id'), 'quotation_comparison_data','vendor_approve','reject_comment', DB::raw('BIN_TO_UUID(approve_reject_by) AS approve_reject_by'), 'approval','pr_no');

         $query->join('en_form_data_pr', 'en_form_data_pr.pr_id', '=', 'en_ci_quotation_comparison.pr_po_id');

        $query->where(function ($query) use ($pr_po_id) {
            $query->when($pr_po_id, function ($query) use ($pr_po_id) {
                return $query->where('pr_po_id', '=', DB::raw('UUID_TO_BIN("' . $pr_po_id . '")'));
            });
        });

        $data = $query->get();
        return $data;
    }
}
