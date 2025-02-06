<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnBillTo extends Model
{
    use HasBinaryUuid;
    public $incrementing = false;
    protected $table     = 'en_bill_to';
    //public $timestamps = false;
    protected $fillable = [
        'billto_id', 'locations', 'company_name', 'address', 'pan_no', 'gstn', 'status',
    ];

    protected $primaryKey = 'billto_id';
    public function getKeyName()
    {
        return 'billto_id';
    }
    /*
     * This is model function is used get all Bill To data

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        billto_id
     * @param_type   integer
     * @return       array
     * @tables       en_bill_to
     */
    protected function getbilltos($billto_id, $inputdata = array(), $count = false)
    {
        $searchkeyword = _isset($inputdata, 'searchkeyword');
        if (isset($inputdata["limit"]) && $inputdata["limit"] < 1) {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_bill_to')
            ->select(DB::raw('BIN_TO_UUID(billto_id) AS billto_id'), DB::raw('BIN_TO_UUID(locations) AS locations'), 'company_name', 'address', 'pan_no', 'gstn', 'status')
            ->where('en_bill_to.status', '!=', 'd');
        $query->where(function ($query) use ($searchkeyword, $billto_id) {
            $query->where(function ($query) use ($searchkeyword, $billto_id) {
                $query->when($searchkeyword, function ($query) use ($searchkeyword) {

                    return $query->where('en_bill_to.address', 'like', '%' . $searchkeyword . '%')
                        ->orWhere('en_bill_to.pan_no', 'like', '%' . $searchkeyword . '%')
                        ->orWhere('en_bill_to.company_name', 'like', '%' . $searchkeyword . '%')
                        ->orWhere('en_bill_to.gstn', 'like', '%' . $searchkeyword . '%');
                });
            });
            $query->when($billto_id, function ($query) use ($billto_id) {
                return $query->where('en_bill_to.billto_id', '=', DB::raw('UUID_TO_BIN("' . $billto_id . '")'));
            });});
        /*$query->where(function ($query) use ($searchkeyword){
        $query->where(function ($query) use ($searchkeyword) {
        $query->when($searchkeyword, function ($query) use ($searchkeyword)
        {

        return $query->where('en_bill_to.cc_name', 'like', '%' . $searchkeyword . '%')
        ->orWhere('en_bill_to.cc_code', 'like', '%' . $searchkeyword . '%')
        ->orWhere('en_bill_to.description', 'like', '%' . $searchkeyword . '%');
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

    protected function checkforrelation($billto_id)
    {
        if ($billto_id) {
            $billto_data = EnBillTo::where('billto_id', DB::raw('UUID_TO_BIN("' . $billto_id . '")'))->first();

            if ($billto_data) {
                //apilog('sdhfksdf'.json_encode($billto_data));
                $billto_data->update(array('status' => 'd'));
                $billto_data->save();
                /*$queries    = DB::getQueryLog();
                $last_query = end($queries);
                apilog(json_encode($last_query));   */

                $data['data']['deleted_id'] = $billto_id;
                $data['message']['success'] = showmessage('118', array('{name}'), array('Bill To'));
                $data['status']             = 'success';

            } else {
                $data['data']             = null;
                $data['message']['error'] = showmessage('119', array('{name}'), array('Bill To'));
                $data['status']           = 'error';
            }
        } else {
            $data['data']             = null;
            $data['message']['error'] = showmessage('123', array('{name}'), array('Bill To'));
            $data['status']           = 'error';
        }
        return $data;
    }

}
