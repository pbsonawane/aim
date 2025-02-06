<?php
namespace App\Http\Controllers\cmdb;

use App\Http\Controllers\Controller;
use App\Models\EnDelivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class DeliveryController extends Controller
{
    public function __construct()
    {
        DB::connection()->enableQueryLog();
    }

    /*
     *This is controller funtion used for Delivery.
     * @author       Bhushan Amrutkar
     * @access       public
     * @param        URL : delivery_id
     * @param_type   integer
     * @return       JSON
     * @tables       en_ci_delivery
     */
    public function delivery(Request $request,$delivery_id = null)
    {
        $requset['delivery_id'] = $delivery_id;
        $validator = Validator::make($request->all(), [
            'delivery_id' => 'nullable|allow_uuid|string|size:36',
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
            $totalrecords = EnDelivery::getdelivery($delivery_id, $inputdata, true);
            $result = EnDelivery::getdelivery($delivery_id, $inputdata, false);

            $data['data']['records'] = $result->isEmpty() ? null : $result;
            $data['data']['totalrecords'] = $totalrecords;

            if ($totalrecords < 0)
            {
                $data['message']['error'] = showmessage('102', array('{name}'), array('Delivery'));
                $data['status'] = 'error';
            }
            else
            {
                $data['message']['success'] = showmessage('101', array('{name}'), array('Delivery'));
                $data['status'] = 'success';
            }
            return response()->json($data);
        }
    }

    /**
     * @author       Bhushan Amrutkar
     * @access       public
     * @param        delivery, status
     * @param_type   POST array
     * @return       JSON
     * @tables       en_ci_delivery
     */
    public function deliveryadd(Request $request)
    {
       $messages = [
            'delivery.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_delivery')), true),
            'delivery.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_delivery')), true),
            'delivery.html_tags_not_allowed'       => showmessage('000', array('{name}'), array(trans('label.lbl_delivery')), true)
        ];
        $validator = Validator::make($request->all(), [
            'delivery_id'     => 'nullable|allow_uuid|string|size:36',
            'delivery'   => 'required|html_tags_not_allowed|composite_unique:en_ci_delivery, delivery, '.$request->input('delivery'),
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
            $delivery_data = EnDelivery::create($request->all());
            if (!empty($delivery_data['delivery_id']))
            {
                $delivery_id = $delivery_data->delivery_id_text;
                $data['data']['insert_id'] = $delivery_id;
                $data['message']['success'] = showmessage('104', array('{name}'), array('Delivery'));
                $data['status'] = 'success';
            }
            else
            {
                $data['data'] = null;
                $data['message']['error'] = showmessage('103', array('{name}'), array('Delivery'));
                $data['status'] = 'error';
            }
        }
        return response()->json($data);
    }

    /* Provides a window to user to update the delivery information.

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        URL : delivery_id
     * @param_type   Integer
     * @return       JSON
     * @tables       en_ci_delivery
     */
    public function deliveryedit(Request $request,$delivery_id = null)
    {
        //$request['delivery_id'] = $delivery_id;
        $validator = Validator::make($request->all(), [
            'delivery_id' => 'required|allow_uuid|string|size:36',
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
            $result = EnDelivery::getdelivery($request->input('delivery_id'));

            $data['data'] = $result->isEmpty() ? null : $result;

            if ($data['data'])
            {
                $data['message']['success'] = showmessage('102', array('{name}'), array('Delivery'));
                $data['status'] = 'success';
            }
            else
            {
                $data['message']['error'] = showmessage('101', array('{name}'), array('Delivery'));
                $data['status'] = 'error';
            }
        }
        return response()->json($data);
    }

    
    /*
     * Updates the delivery information, which is entered by user on Edit delivery window.

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        delivery, status
     * @param_type   POST array
     * @return       JSON
     * @tables       en_ci_delivery
     */
    public function deliveryupdate(Request $request)
    {
        $delivery_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('delivery_id').'")');
        $messages = [
            'delivery.required'       => showmessage('000', array('{name}'), array(trans('label.lbl_delivery')), true),
            'delivery.composite_unique' => showmessage('006', array('{name}'), array(trans('label.lbl_delivery')), true),
            'delivery.html_tags_not_allowed'       => showmessage('000', array('{name}'), array(trans('label.lbl_delivery')), true),
            
        ];


        $validator = Validator::make($request->all(), [
            'delivery_id'     => 'required|allow_uuid|string|size:36',
            'delivery'   => 'required|html_tags_not_allowed|composite_unique:en_ci_delivery, delivery, '.$request->input('delivery').', delivery_id,'.$request->input('delivery_id'),
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
            $delivery_id_uuid = $request->input('delivery_id');
            $delivery_id_bin = DB::raw('UUID_TO_BIN("'.$request->input('delivery_id').'")');
            $request['delivery_id'] = DB::raw('UUID_TO_BIN("'.$request->input('delivery_id').'")');
            $result = EnDelivery::where('delivery_id', $delivery_id_bin)->first();

            if ($result)
            {
                $result->update($request->all());
                $result->save();
                $data['data'] = null;
                $data['message']['success'] = showmessage('106', array('{name}'), array('Delivery'));
                $data['status'] = 'success';
                // userlog(array('record_id' => $delivery_id_uuid, 'data' => $request->all(), 'action' => 'updated', 'message' => showmessage('106', array('{name}'),array('Designation'))));

            }
            else
            {
                $data['data'] = null;
                $data['message']['error'] = showmessage('101', array('{name}'), array('Delivery'));
                $data['status'] = 'error';
            }
        }
        return response()->json($data);
    }
    //===== deliveryupdate END ===========

    /* This is controller funtion used to delete the designation.

     * @author       Bhushan Amrutkar
     * @access       public
     * @param        URL : delivery_id
     * @param_type   integer
     * @return       JSON
     * @tables       en_ci_delivery
     */

    public function deliverydelete(Request $request,$delivery_id = null)
    {
        $request['delivery_id'] = $delivery_id;
        $validator = Validator::make($request->all(), [
            'delivery_id' => 'required|allow_uuid|string|size:36',
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
            $data = EnDelivery::checkforrelation($delivery_id);
            //Add into UserActivityLog
            if ($data['data'])
            {
                //userlog(array('record_id' => $delivery_id, 'data' => $request->all(), 'action' => 'deleted', 'message' => 'Record Deleted Successfully'));
            }
            return response()->json($data);

        }
    }
} // Class End
