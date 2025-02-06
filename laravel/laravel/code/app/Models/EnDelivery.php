<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnDelivery extends Model
{
    /*
     * This is model function is used get all departments data with its foregin key data

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        delivery_id
     * @param_type   Integer
     * @return       array
     * @tables       en_ci_delivery
     */

    use HasBinaryUuid;
    public $incrementing = false;

    protected $table = 'en_ci_delivery';
    //public $timestamps = false;
    protected $fillable = [
        'delivery_id', 'delivery', 'status',
    ];
    protected $primaryKey = 'delivery_id';
    public function getKeyName()
    {
        return 'delivery_id';
    }

    /*
     * This is model function is used get all Role's data with its foregin key data

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        delivery_id
     * @param_type   Integer
     * @return       array
     * @tables       en_ci_delivery
     */
    protected function getdelivery($delivery_id = null, $inputdata = [], $count = false)
    {
        $searchkeyword = _isset($inputdata, 'searchkeyword');
        if (isset($inputdata["limit"]) && $inputdata["limit"] < 1) {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_ci_delivery')
            ->select(DB::raw('BIN_TO_UUID(delivery_id) AS delivery_id'), 'delivery', 'status')
            ->where('status', '=', 'y');

        $query->where(function ($query) use ($searchkeyword, $delivery_id) {
            $query->where(function ($query) use ($searchkeyword, $delivery_id) {
                $query->when($searchkeyword, function ($query) use ($searchkeyword) {
                    return $query->where('delivery', 'like', '%' . $searchkeyword . '%');
                });
            });
            $query->when($delivery_id, function ($query) use ($delivery_id) {
                return $query->where('delivery_id', '=', DB::raw('UUID_TO_BIN("' . $delivery_id . '")'));
            });});
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

    /* This is Model funtion used to delete the rocord, But Before that check the deletion ID's have relation with another table(en_user_details)

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        delivery_id
     * @param_type   Integer
     * @return       Array
     * @tables       en_ci_delivery
     */

    protected function checkforrelation($delivery_id)
    {
        if ($delivery_id) {
            $vendor_data = EnDelivery::where('delivery_id', DB::raw('UUID_TO_BIN("' . $delivery_id . '")'))->where('status', '!=', 'd')->first();
            if ($vendor_data) {

                $vendor_data->update(['status' => 'd']);
                $vendor_data->save();
                $data['data']['deleted_id'] = $delivery_id;
                $data['message']['success'] = showmessage('118', ['{name}'], ['Delivery']);
                $data['status']             = 'success';

            } else {
                $data['data']             = null;
                $data['message']['error'] = showmessage('119', ['{name}'], ['Delivery']);
                $data['status']           = 'error';
            }
        } else {
            $data['data']             = null;
            $data['message']['error'] = showmessage('123', ['{name}'], ['Delivery']);
            $data['status']           = 'error';
        }
        return $data;
    }

}
