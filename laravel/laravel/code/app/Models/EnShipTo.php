<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnShipTo extends Model
{
    use HasBinaryUuid;
    public $incrementing = false;
    protected $table     = 'en_ship_to';
    //public $timestamps = false;
    protected $fillable = [
        'shipto_id', 'locations', 'company_name', 'address', 'pan_no', 'gstn', 'status',
    ];

    protected $primaryKey = 'shipto_id';
    public function getKeyName()
    {
        return 'shipto_id';
    }
    /*
     * This is model function is used get all Ship To data

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        shipto_id
     * @param_type   integer
     * @return       array
     * @tables       en_ship_to
     */
    protected function getshiptos($shipto_id, $inputdata = [], $count = false)
    {
        $searchkeyword = _isset($inputdata, 'searchkeyword');
        if (isset($inputdata["limit"]) && $inputdata["limit"] < 1) {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_ship_to')
            ->select(DB::raw('BIN_TO_UUID(shipto_id) AS shipto_id'), DB::raw('BIN_TO_UUID(locations) AS locations'), 'company_name', 'address', 'pan_no', 'gstn', 'status')
            ->where('en_ship_to.status', '!=', 'd');
        $query->where(function ($query) use ($searchkeyword, $shipto_id) {
            $query->where(function ($query) use ($searchkeyword, $shipto_id) {
                $query->when($searchkeyword, function ($query) use ($searchkeyword) {

                    return $query->where('en_ship_to.address', 'like', '%' . $searchkeyword . '%')
                        ->orWhere('en_ship_to.pan_no', 'like', '%' . $searchkeyword . '%')
                        ->orWhere('en_ship_to.company_name', 'like', '%' . $searchkeyword . '%')
                        ->orWhere('en_ship_to.gstn', 'like', '%' . $searchkeyword . '%');
                });
            });
            $query->when($shipto_id, function ($query) use ($shipto_id) {
                return $query->where('en_ship_to.shipto_id', '=', DB::raw('UUID_TO_BIN("' . $shipto_id . '")'));
            });
        });
        /*$query->where(function ($query) use ($searchkeyword){
        $query->where(function ($query) use ($searchkeyword) {
        $query->when($searchkeyword, function ($query) use ($searchkeyword)
        {

        return $query->where('en_ship_to.cc_name', 'like', '%' . $searchkeyword . '%')
        ->orWhere('en_ship_to.cc_code', 'like', '%' . $searchkeyword . '%')
        ->orWhere('en_ship_to.description', 'like', '%' . $searchkeyword . '%');
        });
        });
        });*/

        $query->when(!$count, function ($query) use ($inputdata) {
            if (isset($inputdata["offset"]) && isset($inputdata["limit"])) {
                return $query->offset($inputdata["offset"])->limit($inputdata["limit"]);
            }
        });
        $data = $query->get();

        if ($count) {
            return count($data);
        } else {
            return $data;
        }

    }

    protected function checkforrelation($shipto_id)
    {
        if ($shipto_id) {
            $shipto_data = EnShipTo::where('shipto_id', DB::raw('UUID_TO_BIN("' . $shipto_id . '")'))->first();

            if ($shipto_data) {
                //apilog('sdhfksdf'.json_encode($shipto_data));
                $shipto_data->update(['status' => 'd']);
                $shipto_data->save();
                /*$queries    = DB::getQueryLog();
                $last_query = end($queries);
                apilog(json_encode($last_query));   */

                $data['data']['deleted_id'] = $shipto_id;
                $data['message']['success'] = showmessage('118', ['{name}'], ['Ship To']);
                $data['status']             = 'success';

            } else {
                $data['data']             = null;
                $data['message']['error'] = showmessage('119', ['{name}'], ['Ship To']);
                $data['status']           = 'error';
            }
        } else {
            $data['data']             = null;
            $data['message']['error'] = showmessage('123', ['{name}'], ['Ship To']);
            $data['status']           = 'error';
        }
        return $data;
    }

}
