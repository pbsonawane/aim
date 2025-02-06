<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\BinaryUuid\HasBinaryUuid;

class EnPaymentterms extends Model
{
    /*
     * This is model function is used get all departments data with its foregin key data

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        paymentterm_id
     * @param_type   Integer
     * @return       array
     * @tables       en_ci_paymentterms
     */

    use HasBinaryUuid;
    public $incrementing = false;

    protected $table = 'en_ci_paymentterms';
    //public $timestamps = false;
    protected $fillable = [
        'paymentterm_id', 'payment_term', 'status',
    ];
    protected $primaryKey = 'paymentterm_id';
    public function getKeyName()
    {
        return 'paymentterm_id';
    }

    /*
     * This is model function is used get all Role's data with its foregin key data

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        paymentterm_id
     * @param_type   Integer
     * @return       array
     * @tables       en_ci_paymentterms
     */
    protected function getpaymentterms($paymentterm_id = null, $inputdata = array(), $count = false)
    {
        $searchkeyword = _isset($inputdata, 'searchkeyword');
        if (isset($inputdata["limit"]) && $inputdata["limit"] < 1) {
            unset($inputdata["offset"]);
            unset($inputdata["limit"]);
        }
        $query = DB::table('en_ci_paymentterms')
            ->select(DB::raw('BIN_TO_UUID(paymentterm_id) AS paymentterm_id'), 'payment_term', 'status')
            ->where('en_ci_paymentterms.status', '!=', 'd');

        $query->where(function ($query) use ($searchkeyword, $paymentterm_id) {
            $query->where(function ($query) use ($searchkeyword, $paymentterm_id) {
                $query->when($searchkeyword, function ($query) use ($searchkeyword) {
                    return $query->where('en_ci_paymentterms.payment_term', 'like', '%' . $searchkeyword . '%');
                });
            });
            $query->when($paymentterm_id, function ($query) use ($paymentterm_id) {
                return $query->where('en_ci_paymentterms.paymentterm_id', '=', DB::raw('UUID_TO_BIN("' . $paymentterm_id . '")'));
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
     * @param        paymentterm_id
     * @param_type   Integer
     * @return       Array
     * @tables       en_ci_paymentterms
     */

    protected function checkforrelation($paymentterm_id)
    {
        if ($paymentterm_id) {
            $vendor_data = EnPaymentterms::where('paymentterm_id', DB::raw('UUID_TO_BIN("' . $paymentterm_id . '")'))->where('status', '!=', 'd')->first();
            if ($vendor_data) {

                $vendor_data->update(array('status' => 'd'));
                $vendor_data->save();
                $data['data']['deleted_id'] = $paymentterm_id;
                $data['message']['success'] = showmessage('118', array('{name}'), array('Paymentterm'));
                $data['status']             = 'success';

            } else {
                $data['data']             = null;
                $data['message']['error'] = showmessage('119', array('{name}'), array('Paymentterm'));
                $data['status']           = 'error';
            }
        } else {
            $data['data']             = null;
            $data['message']['error'] = showmessage('123', array('{name}'), array('Paymentterm'));
            $data['status']           = 'error';
        }
        return $data;
    }

}
