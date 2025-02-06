<?php
namespace App\Http\Controllers\cmdb;

use App\Http\Controllers\Controller;
use App\Libraries\Maillib;
use App\Models\EnVendors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class VendorRegController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        DB::connection()->enableQueryLog();
    }

    /*
     *This is controller funtion used for Vendors.

     * @author       Amit Khairnar
     * @access       public
     * @param        URL : vendor_id
     * @param_type   integer
     * @return       JSON
     * @tables       en_ci_vendors
     */
    public function vendors(Request $request, $vendor_id = null)
    {

        $requset['vendor_id'] = $vendor_id;
        $validator            = Validator::make($request->all(), [
            'vendor_id' => 'nullable|allow_uuid|string|size:36',
        ]);
        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
            return response()->json($data);
        } else {

            $inputdata                  = $request->all();
            $inputdata['searchkeyword'] = trim(_isset($inputdata, 'searchkeyword'));
            $totalrecords               = EnVendors::getvendors($vendor_id, $inputdata, true);
            $result                     = EnVendors::getvendors($vendor_id, $inputdata, false);

            $data['data']['records']      = $result->isEmpty() ? null : $result;
            $data['data']['totalrecords'] = $totalrecords;

            if ($totalrecords < 0) {
                $data['message']['error'] = showmessage('102', array('{name}'), array('Vendor'));
                $data['status']           = 'error';
            } else {
                $data['message']['success'] = showmessage('101', array('{name}'), array('Vendor'));
                $data['status']             = 'success';
            }
            return response()->json($data);
        }
    }

    /*
     * This is controller funtion used to accept the values for new Department. This function is called when user enters new values for department and submits that form.

     * @author       Amit Khairnar
     * @access       public
     * @param        vendor_name,vendor_ref_id, contact_person, contactno, address, status
     * @param_type   POST array
     * @return       JSON
     * @tables       en_ci_vendors
     */
    public function vendorsadd(Request $request)
    {        
        $messages = [
            'vendor_name.required'                 => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_name.allow_alpha_space_only'   => showmessage('009', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            /*'vendor_name.composite_unique'         => showmessage('006', array('{name}'), array(trans('label.lbl_vendor_name')), true),*/
            'vendor_name.html_tags_not_allowed'    => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_ref_id.html_tags_not_allowed'  => showmessage('001', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_name.required'                 => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_ref_id.html_tags_not_allowed'  => showmessage('001', array('{name}'), array(trans('label.lbl_vendor_reference')), true),
            'contact_person.required'              => showmessage('000', array('{name}'), array(trans('label.lbl_contact_person')), true),
            'contact_person.html_tags_not_allowed' => showmessage('001', array('{name}'), array(trans('label.lbl_contact_person')), true),
            'contactno.required'                   => showmessage('000', array('{name}'), array(trans('label.lbl_contact_no')), true),
            'contactno.digits'                     => showmessage(trans('msg_contactno_10digit')),
            'address.required'                     => showmessage('000', array('{name}'), array(trans('label.lbl_address')), true),
            'address.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_address')), true),
            'bank_address.required'                     => showmessage('000', array('{name}'), array(trans('label.lbl_bank_address')), true),
            'bank_address.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_bank_address')), true),
            'bank_name.required'                     => showmessage('000', array('{name}'), array(trans('label.lbl_bank_name')), true),
            'bank_name.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_bank_name')), true),
            'bank_name.allow_alpha_space_only'        => showmessage('001', array('{name}'), array(trans('label.lbl_bank_name')), true),
            'bank_address.required'                     => showmessage('000', array('{name}'), array(trans('label.lbl_bank_address')), true),
            'bank_address.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_bank_address')), true),
            'bank_branch.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_bank_branch')), true),
            'bank_branch.required'        => showmessage('000', array('{name}'), array(trans('label.lbl_bank_branch')), true),
            'bank_branch.allow_alpha_space_only'        => showmessage('001', array('{name}'), array(trans('label.lbl_bank_branch')), true),
            'bank_account_no.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_bank_account_no')), true),
            'bank_account_no.required'        => showmessage('000', array('{name}'), array(trans('label.lbl_bank_account_no')), true),
            'bank_account_no.numeric'        => showmessage('025', array('{name}'), array(trans('label.lbl_bank_account_no')), true),
            'bank_account_no.composite_unique'        => showmessage('004', array('{name}'), array(trans('label.lbl_bank_account_no')), true),
            'ifsc_code.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_ifsc_code')), true),
            'ifsc_code.required'        => showmessage('000', array('{name}'), array(trans('label.lbl_ifsc_code')), true),
            // 'micr_code.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_micr_code')), true),
            // 'micr_code.required'        => showmessage('000', array('{name}'), array(trans('label.lbl_micr_code')), true),
            // 'micr_code.numeric'        => showmessage('025', array('{name}'), array(trans('label.lbl_micr_code')), true),
            'account_type.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_account_type')), true),
            'account_type.required'        => showmessage('000', array('{name}'), array(trans('label.lbl_account_type')), true),
            'vendor_email.required'        => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_email')), true),
            'vendor_email.email'        => showmessage('127', array('{name}'), array(trans('label.lbl_vendor_email')), true),
            'vendor_email.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_vendor_email')), true),
            'vendor_gst_no.required'        => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_gst_no')), true),
            'vendor_gst_no.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_vendor_gst_no')), true),
            'vendor_pan.required'        => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_pan')), true),
            'vendor_pan.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_vendor_pan')), true),            
        ];
        $validator = Validator::make($request->all(), [
            'vendor_id'      => 'nullable|allow_uuid|string|size:36',
            'vendor_name'    => 'required|allow_alpha_space_only|html_tags_not_allowed|',
            'vendor_ref_id'  => 'html_tags_not_allowed',
            'contact_person' => 'required|html_tags_not_allowed',
            'contactno'      => 'required|digits:10|composite_unique:en_ci_vendors, contactno, ' . $request->input('contact_person'),
            'address'        => 'required|html_tags_not_allowed',
            'bank_name'        => 'required|html_tags_not_allowed|allow_alpha_space_only',
            'bank_address'        => 'required|html_tags_not_allowed',
            'bank_branch'        => 'required|html_tags_not_allowed|allow_alpha_space_only',
            'bank_account_no'        => 'required|html_tags_not_allowed|numeric|composite_unique:en_ci_vendors, bank_account_no, ' . $request->input('bank_account_no'),
            'ifsc_code'        => 'required|html_tags_not_allowed',
            'micr_code'        => 'required|html_tags_not_allowed|numeric',
            'account_type'        => 'required|html_tags_not_allowed',
            'vendor_email'        => 'required|email',
            'vendor_gst_no'        => 'required|html_tags_not_allowed',
            'vendor_pan'        => 'required|html_tags_not_allowed',            
        ], $messages);
        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
        } else {
            $vend_assets = $request->only('vendors_assets')? $request->only('vendors_assets'): array();
            $test = $request->all();

            $actual_path  = 'uploads/purchase/';
            $target_dir   = public_path($actual_path);
            // header('Content-Type: application/json');
            if($request['saveimg_bank_name'])
            {
                $saveimg      = $request['saveimg_bank_name'];
                $file_dir     = $target_dir . "/" . $saveimg;
                $decoded_file = base64_decode($request['files_content_bank_name']); // decode the file
                if (file_put_contents($file_dir, $decoded_file)) {
                     $test['bank_name_file'] = $actual_path . $saveimg;
                }
            }
            if($request['saveimg_pan'])
            {
                $saveimg      = $request['saveimg_pan'];
                $file_dir     = $target_dir . "/" . $saveimg;
                $decoded_file = base64_decode($request['files_content_pan']); // decode the file
                if (file_put_contents($file_dir, $decoded_file)) {
                     $test['vendor_pan_file'] = $actual_path . $saveimg;
                }
            }

             if($request['is_msme_reg'] == "Yes")
            {
             if($request['saveimg_msme_certificate']!="")
            {
                $saveimg      = $request['saveimg_msme_certificate'];
                $file_dir     = $target_dir . "/" . $saveimg;
                $decoded_file = base64_decode($request['files_content_msme_certificate']); // decode the file
                if (file_put_contents($file_dir, $decoded_file)) {
                     $test['msme_certificate'] = $actual_path . $saveimg;
                }
            }
}else{
                $test['msme_certificate'] = "";
            }




            if($request['is_gstnumber_reg'] == "Yes")
            {
                if($request['saveimg_gst_no'])
                {
                    $saveimg      = $request['saveimg_gst_no'];
                    $file_dir     = $target_dir . "/" . $saveimg;
                    $decoded_file = base64_decode($request['files_content_gst_no']); // decode the file
                    if (file_put_contents($file_dir, $decoded_file)) {
                         $test['vendor_gst_no_file'] = $actual_path . $saveimg;
                    }
                }
            }else{
                $test['vendor_gst_no_file'] = "";
            }

             

            
            $test['vendors_assets'] = json_encode($vend_assets);
            $vendor_data = EnVendors::create($test);
            // $vendor_data = EnVendors::create($request->all());
            if (!empty($vendor_data['vendor_id'])) {
                $vendor_id                  = $vendor_data->vendor_id_text;
                $data['data']['insert_id']  = $vendor_id;
                $data['message']['success'] = showmessage('104', array('{name}'), array('Vendor'));
                $data['status']             = 'success';
                //Add into UserActivityLog
                // userlog(array('record_id' => $vendor_data->vendor_id_text, 'data' => $request->all(), 'action' => 'added', 'message' => showmessage('104', array('{name}'),array('Vendor'))));
            } else {
                $data['data']             = null;
                $data['message']['error'] = showmessage('103', array('{name}'), array('Vendor'));
                $data['status']           = 'error';
            }
        }
        return response()->json($data);
    }

    public function generatetoken(Request $request)
    {
        $messages = [
            'vendor_email.required'                 => showmessage('000', array('{name}'), array('email'), true)
        ];
        $validator = Validator::make($request->all(), [
            'vendor_email'      => 'required|email'
        ], $messages);
        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
        } else {
            $deleteData = DB::table('vendor_otp_verification')
            ->where('email','=', $request->input('vendor_email'))
            ->delete();
            // DELETE n1 FROM `vendor_otp_verification` n1, `vendor_otp_verification` n2 WHERE 
            // n1.id < n2.id AND n1.email = n2.email AND n1.email='rahul.badhe@esds.co.in'
            
            // $otp = mt_rand(1111,9999);
            $otp = substr(str_shuffle("0123456789@#$%&*abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
            $token = md5($request->input('vendor_email').$otp);

            $qry = DB::table('vendor_otp_verification')->insert([
                'email' =>  $request->input('vendor_email'),
                'otp' => $otp,
                'token' =>  $token,
                'created_date' => date('y-m-d H:i:s')
            ]);


            if (!empty($qry)) {
                $emailbody = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml">
                <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <title></title>
                <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
                <style>
                body {
                    font-family: "Open Sans", sans-serif;
                }
                p {
                    margin: 0;
                }
                </style>
                </head>

                <body style="background:#f8f8f8;">
                <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="background:#FFF;">
                <tr>
                <td align="center" valign="top">
                <table width="550" border="0" cellspacing="0" cellpadding="0">
                <tr>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td><h3 style="color:#4349ac">Dear Customer</h3>
                <p>Your OTP for registration is <b>'.$otp.'</b>. Use this Passcode to complete your registration process.</p>

                <br>

                </td>
                </tr>
                <tr>
                <td>&nbsp;</td>
                </tr>
                <tr>
                <td>

                </td>
                </tr>
                <tr>
                <td>&nbsp;</td>
                </tr>
                </table>
                </td>
                </tr>
                <tr>
                <td align="center" valign="top" style="background:#4349ac !important; height:35px; color:#fff; font-size:15px;">
                <table width="550" border="0" align="center" cellpadding="10" cellspacing="0">
                <tr>
                <td width="277" valign="top" align="left">Thank You <br>
                ESDS Team</td>
                </tr>
                </table>
                </td>
                </tr>
                </table>
                </body>
                </html>';
                $phpmailer    = new Maillib();
                $to_emails    = $request->input('vendor_email');
            // $to_emails    = 'rahul.badhe@esds.co.in';
                $subject      = 'AIM : Vendor registration OTP';
                $email_body   = $emailbody;
                $mailresponse = $phpmailer->mailsent($to_emails, $subject, $email_body);
                $data['data']                  = $token;
                $data['message']['success'] = showmessage('104', array('{name}'), array('Vendor'));
                $data['status']             = 'success';

            } else {
                $data['data']             = null;
                $data['message']['error'] = showmessage('103', array('{name}'), array('Vendor'));
                $data['status']           = 'error';
            }
        }
        return response()->json($data);
    }

    public function verifyotptoken(Request $request)
    {


        $messages = [
            'token.required'                 => showmessage('000', array('{name}'), array('token'), true)
        ];
        $validator = Validator::make($request->all(), [
            'token'      => 'required'
        ], $messages);
        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
        } else {

            $token = $request->input('token');
            $otp = $request->input('otp');
            $reset = $request->input('reset');
            if (!empty($reset)) {
                $affected = DB::table('vendor_otp_verification')
                ->where('token', $token)
                ->update(['token' => null]);
                $data['data']                  = null;
                $data['message']['success'] = 'valid token';
                $data['status']             = 'success';
            }else{
                $query = DB::table('vendor_otp_verification')
                ->where('token', '=', $token)
                ->where('created_date', '>', DB::raw('DATE_SUB("'.date('Y-m-d H:i:s').'", INTERVAL 60 MINUTE)'));
                // AND `agreement_send_on` > DATE_SUB(now(), INTERVAL 10 MINUTE)

                if(!empty($otp))
                {
                    $query->where('otp', '=', $otp);
                }
                $users = $query->get();       

                if (!$users->isEmpty()) {
                    $data['data']                  = $users;
                    $data['message']['success'] = 'valid token';
                    $data['status']             = 'success';
                } else {
                    $data['data']             = null;
                    $data['message']['error'] = 'Invalid token/OTP';
                    $data['status']           = 'error';
                }
            }

        }
        return response()->json($data);
    }

    /*
     * This is controller funtion used to accept the values for new Department. This function is called when user enters new values for department and submits that form.

     * @author       Amit Khairnar
     * @access       public
     * @param        vendor_name,vendor_ref_id, contact_person, contactno, address, status
     * @param_type   POST array
     * @return       JSON
     * @tables       en_ci_vendors
     */
    public function vendoradd1(Request $request)
    {
        $messages = [
            'vendor_name.required'                 => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_name.allow_alpha_space_only'   => showmessage('009', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_name.composite_unique'         => showmessage('006', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_name.html_tags_not_allowed'    => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_ref_id.html_tags_not_allowed'  => showmessage('001', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_name.required'                 => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_ref_id.html_tags_not_allowed'  => showmessage('001', array('{name}'), array(trans('label.lbl_vendor_reference')), true),
            'contact_person.required'              => showmessage('000', array('{name}'), array(trans('label.lbl_contact_person')), true),
            'contact_person.html_tags_not_allowed' => showmessage('001', array('{name}'), array(trans('label.lbl_contact_person')), true),
            'contactno.required'                   => showmessage('000', array('{name}'), array(trans('label.lbl_contact_no')), true),
            'contactno.digits'                     => showmessage(trans('msg_contactno_10digit')),
            'address.required'                     => showmessage('000', array('{name}'), array(trans('label.lbl_address')), true),
            'address.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_address')), true),

        ];
        $validator = Validator::make($request->all(), [
            'vendor_id'      => 'nullable|allow_uuid|string|size:36',
            'vendor_name'    => 'required|allow_alpha_space_only|html_tags_not_allowed|composite_unique:en_ci_vendors, vendor_name, ' . $request->input('vendor_name'),
            'vendor_ref_id'  => 'html_tags_not_allowed',
            'contact_person' => 'required|html_tags_not_allowed',
            'contactno'      => 'required|digits:10',
            'address'        => 'required|html_tags_not_allowed',
        ], $messages);
        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
        } else {
            $vendor_data = EnVendors::create($request->all());
            if (!empty($vendor_data['vendor_id'])) {
                $vendor_id                  = $vendor_data->vendor_id_text;
                $data['data']['insert_id']  = $vendor_id;
                $data['message']['success'] = showmessage('104', array('{name}'), array('Vendor'));
                $data['status']             = 'success';
                //Add into UserActivityLog
                // userlog(array('record_id' => $vendor_data->vendor_id_text, 'data' => $request->all(), 'action' => 'added', 'message' => showmessage('104', array('{name}'),array('Vendor'))));
            } else {
                $data['data']             = null;
                $data['message']['error'] = showmessage('103', array('{name}'), array('Vendor'));
                $data['status']           = 'error';
            }
        }
        return response()->json($data);
    }

    /* Provides a window to user to update the vendor information.

     * @author       Amit Khairnar
     * @access       public
     * @param        URL : vendor_id
     * @param_type   Integer
     * @return       JSON
     * @tables       en_ci_vendors
     */
    public function vendoredit(Request $request, $vendor_id = null)
    {
        //$request['vendor_id'] = $vendor_id;
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|allow_uuid|string|size:36',
        ]);
        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
        } else {
            $result = EnVendors::getvendors($request->input('vendor_id'));

            $data['data'] = $result->isEmpty() ? null : $result;

            if ($data['data']) {
                $data['message']['success'] = showmessage('102', array('{name}'), array('Vendor'));
                $data['status']             = 'success';
            } else {

                $data['message']['error'] = showmessage('101', array('{name}'), array('Vendor'));
                $data['status']           = 'error';
            }
        }
        return response()->json($data);
    }

    //===== designationedit END ===========
    /*
     * Updates the vendor information, which is entered by user on Edit vendor window.

     * @author       Amit Khairnar
     * @access       public
     * @param        vendor_name , vendor_ref_id, address, contact_person, contactno, status
     * @param_type   POST array
     * @return       JSON
     * @tables       en_ci_vendors
     */
    public function vendorupdate(Request $request)
    {
        $vendor_id_bin = DB::raw('UUID_TO_BIN("' . $request->input('vendor_id') . '")');
        $messages      = [
            'vendor_name.required'                 => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_name.allow_alpha_space_only'   => showmessage('009', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_name.composite_unique'         => showmessage('006', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_name.html_tags_not_allowed'    => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_ref_id.html_tags_not_allowed'  => showmessage('001', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_name.required'                 => showmessage('000', array('{name}'), array(trans('label.lbl_vendor_name')), true),
            'vendor_ref_id.html_tags_not_allowed'  => showmessage('001', array('{name}'), array(trans('label.lbl_vendor_reference')), true),
            'contact_person.required'              => showmessage('000', array('{name}'), array(trans('label.lbl_contact_person')), true),
            'contact_person.html_tags_not_allowed' => showmessage('001', array('{name}'), array(trans('label.lbl_contact_person')), true),
            'contactno.required'                   => showmessage('000', array('{name}'), array(trans('label.lbl_contact_no')), true),
            'contactno.digits'                     => showmessage(trans('msg_contactno_10digit')),
            'address.required'                     => showmessage('000', array('{name}'), array(trans('label.lbl_address')), true),
            'address.html_tags_not_allowed'        => showmessage('001', array('{name}'), array(trans('label.lbl_address')), true),

        ];

        $validator = Validator::make($request->all(), [
            'vendor_id'      => 'required|allow_uuid|string|size:36',
            'vendor_name'    => 'required|allow_alpha_space_only|html_tags_not_allowed|composite_unique:en_ci_vendors, vendor_name, ' . $request->input('vendor_name') . ', vendor_id,' . $request->input('vendor_id'),
            'vendor_ref_id'  => 'html_tags_not_allowed',
            'contact_person' => 'required|html_tags_not_allowed',
            'contactno'      => 'required|digits:10',
            'address'        => 'required|html_tags_not_allowed',
            //'status' => 'required|in:y,n,d'
        ], $messages);
        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
        } else {
            $vendor_id_uuid       = $request->input('vendor_id');
            $vendor_id_bin        = DB::raw('UUID_TO_BIN("' . $request->input('vendor_id') . '")');
            $request['vendor_id'] = DB::raw('UUID_TO_BIN("' . $request->input('vendor_id') . '")');
            $result               = EnVendors::where('vendor_id', $vendor_id_bin)->first();

            if ($result) {
                $result->update($request->all());
                $result->save();
                $data['data']               = null;
                $data['message']['success'] = showmessage('106', array('{name}'), array('Vendor'));
                $data['status']             = 'success';
                // userlog(array('record_id' => $vendor_id_uuid, 'data' => $request->all(), 'action' => 'updated', 'message' => showmessage('106', array('{name}'),array('Designation'))));

            } else {
                $data['data']             = null;
                $data['message']['error'] = showmessage('101', array('{name}'), array('Vendor'));
                $data['status']           = 'error';
            }
        }
        return response()->json($data);
    }
    //===== Vendorupdate END ===========

    /* This is controller funtion used to delete the designation.

     * @author       Amit Khairnar
     * @access       public
     * @param        URL : vendor_id
     * @param_type   integer
     * @return       JSON
     * @tables       en_ci_vendors
     */

    public function vendordelete(Request $request, $vendor_id = null)
    {
        $request['vendor_id'] = $vendor_id;
        $validator            = Validator::make($request->all(), [
            'vendor_id' => 'required|allow_uuid|string|size:36',
        ]);
        if ($validator->fails()) {
            $error                    = $validator->errors();
            $data['data']             = null;
            $data['message']['error'] = $error;
            $data['status']           = 'error';
            return response()->json($data);
        } else {
            $data = EnVendors::checkforrelation($vendor_id);
            //Add into UserActivityLog
            if ($data['data']) {
                //userlog(array('record_id' => $vendor_id, 'data' => $request->all(), 'action' => 'deleted', 'message' => 'Record Deleted Successfully'));
            }
            return response()->json($data);

        }
    }
} // Class End