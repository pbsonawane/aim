<?php
namespace App\Http\Controllers\cmdb;

use App\Http\Controllers\Controller;
use App\Models\EnPaymentterms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class PaymenttermController extends Controller
{
    public function __construct()
    {
        DB::connection()->enableQueryLog();
    }

    /*
     *This is controller funtion used for Paymentterms.
     * @author       Bhushan Amrutkar
     * @access       public
     * @param        URL : paymentterm_id
     * @param_type   integer
     * @return       JSON
     * @tables       en_ci_paymentterms
     */
    public function paymentterms(Request $request,$paymentterm_id = null)
    {
        $requset['paymentterm_id'] = $paymentterm_id;
        $validator = Validator::make($request->all(), [
            'paymentterm_id' => 'nullable|allow_uuid|string|size:36',
        ]);
        if ($validator->fails())
        {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        }
        else
        {
            $inputdata = $request->all();
            $inputdata['searchkeyword'] = trim(_isset($inputdata, 'searchkeyword'));
            $totalrecords = EnPaymentterms::getpaymentterms($paymentterm_id, $inputdata, true);
            $result = EnPaymentterms::getpaymentterms($paymentterm_id, $inputdata, false);

            $data['data']['records'] = $result->isEmpty() ? null : $result;
            $data['data']['totalrecords'] = $totalrecords;

            if ($totalrecords < 0)
            {
                $data['message']['error'] = showmessage('102', array('{name}'), array('Paymentterm'));
                $data['status'] = 'error';
            }
            else
            {
                $data['message']['success'] = showmessage('101', array('{name}'), array('Paymentterm'));
                $data['status'] = 'success';
            }
            return response()->json($data);
        }
    }

    /**
     * @author       Bhushan Amrutkar
     * @access       public
     * @param        payment_term, status
     * @param_type   POST array
     * @return       JSON
     * @tables       en_ci_paymentterms
     */
    public function paymenttermadd(Request $request)
    {
       $messages = [
            'payment_term.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_payment_term')), true),
            'payment_term.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_payment_term')), true),
            'payment_term.html_tags_not_allowed'       => showmessage('000', array('{name}'), array(trans('label.lbl_payment_term')), true)
        ];
        $validator = Validator::make($request->all(), [
            'paymentterm_id'     => 'nullable|allow_uuid|string|size:36',
            'payment_term'   => 'required|html_tags_not_allowed|composite_unique:en_ci_paymentterms, payment_term, '.$request->input('payment_term'),
        ], $messages);
        if ($validator->fails())
        {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
        }
        else
        {
            $paymentterm_data = EnPaymentterms::create($request->all());
            if (!empty($paymentterm_data['paymentterm_id']))
            {
                $paymentterm_id = $paymentterm_data->paymentterm_id_text;
                $data['data']['insert_id'] = $paymentterm_id;
                $data['message']['success'] = showmessage('104', array('{name}'), array('Paymentterm'));
                $data['status'] = 'success';
            }
            else
            {
                $data['data'] = null;
                $data['message']['error'] = showmessage('103', array('{name}'), array('Paymentterm'));
                $data['status'] = 'error';
            }
        }
        return response()->json($data);
    }

    /* Provides a window to user to update the paymentterm information.

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        URL : paymentterm_id
     * @param_type   Integer
     * @return       JSON
     * @tables       en_ci_paymentterms
     */
    public function paymenttermedit(Request $request,$paymentterm_id = null)
    {
        //$request['paymentterm_id'] = $paymentterm_id;
        $validator = Validator::make($request->all(), [
            'paymentterm_id' => 'required|allow_uuid|string|size:36',
        ]);
        if ($validator->fails())
        {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
        }
        else
        {
            $result = EnPaymentterms::getpaymentterms($request->input('paymentterm_id'));

            $data['data'] = $result->isEmpty() ? null : $result;

            if ($data['data'])
            {
                $data['message']['success'] = showmessage('102', array('{name}'), array('Paymentterm'));
                $data['status'] = 'success';
            }
            else
            {
                $data['message']['error'] = showmessage('101', array('{name}'), array('Paymentterm'));
                $data['status'] = 'error';
            }
        }
        return response()->json($data);
    }

    
    /*
     * Updates the paymentterm information, which is entered by user on Edit paymentterm window.

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        payment_term, status
     * @param_type   POST array
     * @return       JSON
     * @tables       en_ci_paymentterms
     */
    public function paymenttermupdate(Request $request)
    {
        $paymentterm_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('paymentterm_id').'")');
        $messages = [
            'payment_term.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_payment_term')), true),
            'payment_term.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_payment_term')), true),
            'payment_term.html_tags_not_allowed'       => showmessage('000', array('{name}'), array(trans('label.lbl_payment_term')), true),
            
        ];


        $validator = Validator::make($request->all(), [
            'paymentterm_id'     => 'required|allow_uuid|string|size:36',
            'payment_term'   => 'required|html_tags_not_allowed|composite_unique:en_ci_paymentterms, payment_term, '.$request->input('payment_term').', paymentterm_id,'.$request->input('paymentterm_id'),
        ], $messages);
        if ($validator->fails())
        {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
        }
        else
        {
            $paymentterm_id_uuid = $request->input('paymentterm_id');
            $paymentterm_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('paymentterm_id').'")');
            $request['paymentterm_id'] = DB::raw('UUID_TO_BIN("'.$request->input('paymentterm_id').'")');
            $result = EnPaymentterms::where('paymentterm_id', $paymentterm_id_bin)->first();

            if ($result)
            {
                $result->update($request->all());
                $result->save();
                $data['data'] = null;
                $data['message']['success'] = showmessage('106', array('{name}'), array('Paymentterm'));
                $data['status'] = 'success';
                // userlog(array('record_id' => $paymentterm_id_uuid, 'data' => $request->all(), 'action' => 'updated', 'message' => showmessage('106', array('{name}'),array('Designation'))));

            }
            else
            {
                $data['data'] = null;
                $data['message']['error'] = showmessage('101', array('{name}'), array('Paymentterm'));
                $data['status'] = 'error';
            }
        }
        return response()->json($data);
    }
    //===== paymenttermupdate END ===========

    /* This is controller funtion used to delete the designation.

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        URL : paymentterm_id
     * @param_type   integer
     * @return       JSON
     * @tables       en_ci_paymentterms
     */

    public function paymenttermdelete(Request $request,$paymentterm_id = null)
    {
        $request['paymentterm_id'] = $paymentterm_id;
        $validator = Validator::make($request->all(), [
            'paymentterm_id' => 'required|allow_uuid|string|size:36',
        ]);
        if ($validator->fails())
        {
            $error = $validator->errors();
            $data['data'] = null;
            $data['message']['error'] = $error;
            $data['status'] = 'error';
            return response()->json($data);
        }
        else
        {
            $data = EnPaymentterms::checkforrelation($paymentterm_id);
            //Add into UserActivityLog
            if ($data['data'])
            {
                //userlog(array('record_id' => $paymentterm_id, 'data' => $request->all(), 'action' => 'deleted', 'message' => 'Record Deleted Successfully'));
            }
            return response()->json($data);

        }
    }
} // Class End
