<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EnoppListing extends Model
{
    protected $table = 'en_opportunity_listing';
    //public $timestamps = false;
    protected $fillable = [
        'opportunity_id', 'opportunity_code', 'lead_id', 'status_id', 'opportunity_status', 'created_date', 'created_by_name', 'created_by','basic_details', 'item_json', 'details_updated_at', 'opportunity_stage', ' pr_id', 'pr_no', 'pr_create_date'
    ];
    protected $primaryKey = 'id';
    //const CREATED_AT = 'created_at';
    //const UPDATED_AT = 'updated_at';

    protected function getopportunities($id, $inputdata = array(), $count = false)
    {
        $searchkeyword = _isset($inputdata, 'searchkeyword');
        if (isset($inputdata["limit"]) && $inputdata["limit"] < 1) {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]); 
        }
        $query = DB::table('en_opportunity_listing')
            ->select('id', 'opportunity_id', 'opportunity_code', 'lead_id', 'status_id', 'opportunity_status', 'created_date', 'created_by_name', 'created_by','opportunity_stage','pr_no',DB::raw('BIN_TO_UUID(pr_id) AS pr_id'));

                $query->where(function ($query) use ($searchkeyword, $id) {
                    $query->where(function ($query) use ($searchkeyword, $id) {
                        $query->when($searchkeyword, function ($query) use ($searchkeyword) {

                            return $query->where('en_opportunity_listing.opportunity_code', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_opportunity_listing.opportunity_status', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_opportunity_listing.pr_no', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_opportunity_listing.opportunity_stage', 'like', '%' . $searchkeyword . '%')
                                ->orWhere('en_opportunity_listing.created_by_name', 'like', '%' . $searchkeyword . '%');
                        });
                    });
                    $query->when($id, function ($query) use ($id) {
                        return $query->where('en_opportunity_listing.id', '=', $id);
                    });});

                $query->when(!$count, function ($query) use ($inputdata) {
                    if (isset($inputdata["offset"]) && isset($inputdata["limit"])) {
                        return $query->offset($inputdata["offset"])->limit($inputdata["limit"]);
                    }
                });
                //$data = $query->get();

                $data = $query->orderBy('en_opportunity_listing.created_date', 'desc')->get();

                if ($count) {
                    return count($data);
                } else {
                    return $data;
                }

            }
}
