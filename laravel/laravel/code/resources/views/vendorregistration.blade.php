<!DOCTYPE html>
<html lang="en">

<head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="AIM - Asset Inventory Manager">
    <meta name="author" content="AIM - Asset Inventory Manager">
    <meta name="keyword" content="AIM - Asset Inventory Manager,eNlight 360,eNlight Cloud Services">
    <title>AIM - Asset Inventory Manager</title>
    <!-- Icons-->
    <!-- Main styles for this application-->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
    <script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>
    <script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
    <link href="<?php echo config("app.site_url"); ?>/coreui/css/style.css" rel="stylesheet">
    <link href="<?php echo config("app.site_url"); ?>/coreui/vendors/pace-progress/css/pace.min.css" rel="stylesheet">
    <style>
    .error {
        color: red;
    }
    </style>

    <script>
    $(document).ready(function() {

        $("#meme_reg_num_div,.is_gstnumber_file,#legalNoticeYesDiv,#elaborate_serious_incidentsDIV,#elaborate_env_regulationDiv,#msme_certificate_div")
            .hide();
        // 
        $("#name,#contact_person,#director_name,#sales_officer_name,#account_officer_name").keydown(function(event){
            var userGetData = event.which;  
            if((userGetData >= 97 && userGetData <= 105) || (userGetData >= 48 && userGetData <= 57) || 
            (userGetData >= 33 && userGetData <= 47) || 
            (userGetData >= 58 && userGetData <= 64) || 
            (userGetData >= 91 && userGetData <= 96) || 
            (userGetData >= 123 && userGetData <= 126)
            ) { 
                event.preventDefault(); 
            }
        });
        // 
        $("#is_msme_reg").on("change", function() {
            if ($(this).val() == "Yes") {
                $("#meme_reg_num_div").show();
                 $("#msme_certificate_div").show();
            } else {
                $("#meme_reg_num_div").hide();
                 $("#msme_certificate_div").hide();
            }
        });
        $("#is_gstnumber_reg").on("change", function() {
                    if ($(this).val() == "Yes") {
                        $(".is_gstnumber_file").show();
                    } else {
                        $(".is_gstnumber_file").hide();
                    }
                });

        // 

        $("#any_legal_notices").on("change", function() {
            if ($(this).val() == "Yes") {
                $("#legalNoticeYesDiv").show();
            } else {
                $("#legalNoticeYesDiv").hide();
            }
        });

        // 
        $("#any_serious_incidents").on("change", function() {
            if ($(this).val() == "Yes") {
                $("#elaborate_serious_incidentsDIV").show();
            } else {
                $("#elaborate_serious_incidentsDIV").hide();
            }
        });

        // 
        $("#is_env_regulation").on("change", function() {
            if ($(this).val() == "Yes") {
                $("#elaborate_env_regulationDiv").show();
            } else {
                $("#elaborate_env_regulationDiv").hide();
            }
        });
    });
    </script>
</head>

<body class="app flex-row align-items-center1">
    <div class="container">
        <div class="row justify-content-center1">
            <div class="col-md-12">
                <div class="card-group">
                    <div class="card p-4">
                        <div class="card-body">

                            <form id="vendorForm" action="<?php echo config("app.site_url"); ?>/vendors/save/{{$token}}"
                                method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <h1>Vendor Registration</h1>
                                <p class="text-muted">&nbsp;</p>

                                <input type="hidden" id="vendor_token" name="vendor_token" class="form-control input-sm"
                                    value="{{$token}}">
                                @if (!empty($message['msg']) && $message['is_error']=='')
                                <span class="text-success">Thank you for your registration!</span>
                                @endif
                                <label style="font-weight: bold;">1. Vendor Details</label>
                                <hr>
                                <div class="row">
                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">1.1 Registered
                                            Business
                                            Name<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="vendor_name" name="vendor_name"
                                                class="form-control input-sm" value="{{ old('vendor_name') }}">

                                            @if (!empty($message['msg']['vendor_name'][0]))
                                            <small class="text-danger">{{$message['msg']['vendor_name'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">1.2
                                            <?php echo trans('label.lbl_vendor_reference');?></label>
                                        <div class="col-md-12">
                                            <input type="text" id="vendor_ref_id" name="vendor_ref_id"
                                                class="form-control input-sm" value="">
                                            @if (!empty($message['msg']['vendor_ref_id'][0]))
                                            <small class="text-danger">{{$message['msg']['vendor_ref_id'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">1.3 Warehouse
                                            Location<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <textarea id="warehouse_location" name="warehouse_location"
                                                class="form-control input-sm"></textarea>
                                            @if (!empty($message['msg']['warehouse_location'][0]))
                                            <small
                                                class="text-danger">{{$message['msg']['warehouse_location'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">1.4 Contact
                                            Person<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="contact_person" name="contact_person"
                                                class="form-control input-sm" value="{{ old('vendor_name') }}">

                                            @if (!empty($message['msg']['contact_person'][0]))
                                            <small class="text-danger">{{$message['msg']['contact_person'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">1.5 Vendor Contact
                                            No<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="contactno" name="contactno"
                                                class="form-control input-sm" value="">
                                            @if (!empty($message['msg']['contactno'][0]))
                                            <small class="text-danger">{{$message['msg']['contactno'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">1.6 Vendor Email<span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="vendor_email" name="vendor_email"
                                                class="form-control input-sm" value="">
                                            @if (!empty($message['msg']['vendor_email'][0]))
                                            <small class="text-danger">{{$message['msg']['vendor_email'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">1.7 Registered
                                            Address<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <textarea id="address" name="address"
                                                class="form-control input-sm"></textarea>
                                            @if (!empty($message['msg']['address'][0]))
                                            <small class="text-danger">{{$message['msg']['address'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">1.8 City<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="city" name="city" class="form-control input-sm"
                                                value="">
                                            @if (!empty($message['msg']['city'][0]))
                                            <small class="text-danger">{{$message['msg']['city'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">1.9 Pin Code<span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="pincode" name="pincode"
                                                class="form-control input-sm" value="">
                                            @if (!empty($message['msg']['pincode'][0]))
                                            <small class="text-danger">{{$message['msg']['pincode'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">1.10 GST No<span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="vendor_gst_no" name="vendor_gst_no"
                                                class="form-control input-sm">
                                            @if (!empty($message['msg']['vendor_gst_no'][0]))
                                            <small class="text-danger">{{$message['msg']['vendor_gst_no'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">1.10.1 If GST number is registered?<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select name="is_gstnumber_reg" id="is_gstnumber_reg" class="form-control input-sm">
                                                <option value="">Select</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                            @if (!empty($message['msg']['is_gstnumber_reg'][0]))
                                            <small class="text-danger">{{$message['msg']['is_gstnumber_reg'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group required col-md-4 is_gstnumber_file">
                                        <label for="inputStandard" class="col-md-12 control-label">1.11 GST Prof<span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="file" id="vendor_gst_no_file" name="vendor_gst_no_file"
                                                class="form-control input-sm">
                                            @if (!empty($message['msg']['vendor_gst_no_file'][0]))
                                            <small
                                                class="text-danger">{{$message['msg']['vendor_gst_no_file'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">1.12 PAN No<span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="vendor_pan" name="vendor_pan"
                                                class="form-control input-sm">
                                            @if (!empty($message['msg']['vendor_pan'][0]))
                                            <small class="text-danger">{{$message['msg']['vendor_pan'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">1.13 PAN Prof<span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="file" id="vendor_pan_file" name="vendor_pan_file"
                                                class="form-control input-sm">
                                            @if (!empty($message['msg']['vendor_pan_file'][0]))
                                            <small class="text-danger">{{$message['msg']['vendor_pan_file'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group required col-md-6">
                                        <label for="inputStandard" class="col-md-12 control-label">1.14 If firm is MSME
                                            registered ?<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select name="is_msme_reg" id="is_msme_reg" class="form-control input-sm">
                                                <option value="">Select</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                            @if (!empty($message['msg']['is_msme_reg'][0]))
                                            <small class="text-danger">{{$message['msg']['is_msme_reg'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6" id="meme_reg_num_div">
                                        <label for="inputStandard" class="col-md-12 control-label">1.14.1 if Yes,
                                            MEME
                                            Registration Number<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="meme_reg_num" name="meme_reg_num"
                                                class="form-control input-sm" value="">
                                            @if (!empty($message['msg']['meme_reg_num'][0]))
                                            <small class="text-danger">{{$message['msg']['meme_reg_num'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6" id="msme_certificate_div">
                                        <label for="inputStandard" class="col-md-12 control-label">1.14.2<?php echo trans('label.lbl_msme_certificate');?><span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="file" id="msme_certificate" name="msme_certificate" class="form-control input-sm" value="<?php if(isset($vendordata[0]['msme_certificate'])) echo $vendordata[0]['msme_certificate'];?>">
                            <label style="color:red">(only jepg,png,jpg,csv,txt,xlx,xls,pdf format accepted.)</label>
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="form-group required col-md-12">
                                        <label for="inputStandard" class="col-md-12 control-label">1.15
                                            Products/Services
                                            Offered:  (Enter Products / Services by comma separated.)<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                        <textarea id="products_services_offered" name="products_services_offered"
                                                class="form-control input-sm"></textarea>                                            
                                            @if (!empty($message['msg']['products_services_offered'][0]))
                                            <small
                                                class="text-danger">{{$message['msg']['products_services_offered'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                <div class="form-group col-md-6">
                                        <label for="inputStandard" class="col-md-12 control-label">1.16 Association with
                                            OEM:<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="associate_oem" name="associate_oem"
                                                class="form-control input-sm" value="">
                                            @if (!empty($message['msg']['associate_oem'][0]))
                                            <small class="text-danger">{{$message['msg']['associate_oem'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="inputStandard" class="col-md-12 control-label">1.17 Delivery
                                            Time<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="delivery_time" name="delivery_time"
                                                class="form-control input-sm" value="">
                                            @if (!empty($message['msg']['delivery_time'][0]))
                                            <small class="text-danger">{{$message['msg']['delivery_time'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group required col-md-8">
                                        <label for="inputStandard" class="col-md-12 control-label">1.18 Payment
                                            Terms<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <textarea id="payment_terms" name="payment_terms"
                                                class="form-control input-sm"></textarea>
                                            @if (!empty($message['msg']['payment_terms'][0]))
                                            <small class="text-danger">{{$message['msg']['payment_terms'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">1.19 Annual
                                            Turnover<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="annual_turnover" name="annual_turnover"
                                                class="form-control input-sm" value="">
                                            @if (!empty($message['msg']['annual_turnover'][0]))
                                            <small class="text-danger">{{$message['msg']['annual_turnover'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group required col-md-12">
                                        <label for="inputStandard" class="col-md-12 control-label">1.20 Known
                                            Clients<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <textarea id="known_client" name="known_client"
                                                class="form-control input-sm"></textarea>
                                            @if (!empty($message['msg']['known_client'][0]))
                                            <small class="text-danger">{{$message['msg']['known_client'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!--  -->
                                <label style="font-weight: bold;">2. Bank Details</label>
                                <hr>
                                <div class="row">
                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">2.1
                                            <?php echo trans('label.lbl_bank_name');?><span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="bank_name" name="bank_name"
                                                class="form-control input-sm">
                                            @if (!empty($message['msg']['bank_name'][0]))
                                            <small class="text-danger">{{$message['msg']['bank_name'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">2.2
                                            <?php echo trans('label.lbl_bank_branch');?><span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="bank_branch" name="bank_branch"
                                                class="form-control input-sm">
                                            @if (!empty($message['msg']['bank_branch'][0]))
                                            <small class="text-danger">{{$message['msg']['bank_branch'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">2.3 Bank Address<span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="bank_address" name="bank_address"
                                                class="form-control input-sm">
                                            @if (!empty($message['msg']['bank_address'][0]))
                                            <small class="text-danger">{{$message['msg']['bank_address'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group required col-md-6">
                                        <label for="inputStandard" class="col-md-12 control-label">2.4 Bank Cancel Cheque<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="file" id="bank_name_file" name="bank_name_file"
                                                class="form-control input-sm">
                                            @if (!empty($message['msg']['bank_name_file'][0]))
                                            <small class="text-danger">{{$message['msg']['bank_name_file'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group required col-md-6">
                                        <label for="inputStandard" class="col-md-12 control-label">2.5
                                            <?php echo trans('label.lbl_account_type');?><span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select id="account_type" name="account_type" class="form-control input-sm">
                                                <option value="">Select</option>
                                                <option value="CC Acount">CC Acount</option>
                                                <option value="Saving Account">Saving Account</option>
                                                <option value="Current Account">Current Account</option>
                                                <option value="OD Account">OD Account</option>
                                            </select>
                                            @if (!empty($message['msg']['account_type'][0]))
                                            <small class="text-danger">{{$message['msg']['account_type'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">2.6
                                            <?php echo trans('label.lbl_bank_account_no');?><span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="bank_account_no" name="bank_account_no"
                                                class="form-control input-sm">
                                            @if (!empty($message['msg']['bank_account_no'][0]))
                                            <small class="text-danger">{{$message['msg']['bank_account_no'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">2.7
                                            <?php echo trans('label.lbl_ifsc_code');?><span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="ifsc_code" name="ifsc_code"
                                                class="form-control input-sm">
                                            @if (!empty($message['msg']['ifsc_code'][0]))
                                            <small class="text-danger">{{$message['msg']['ifsc_code'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">2.8
                                            <?php echo trans('label.lbl_micr_code');?><span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input id="micr_code" type="text" name="micr_code"
                                                class="form-control input-sm">
                                            @if (!empty($message['msg']['micr_code'][0]))
                                            <small class="text-danger">{{$message['msg']['micr_code'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <!--  -->
                                <label style="font-weight: bold;">3. Contact Details</label>
                                <hr>
                                <div class="row">
                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">3.1.1
                                            Director/Proprietor
                                            Name<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="director_name" name="director_name"
                                                class="form-control input-sm" value="">
                                            @if (!empty($message['msg']['director_name'][0]))
                                            <small class="text-danger">{{$message['msg']['director_name'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">3.1.2 Contact
                                            Number<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="number" id="director_contact_no" name="director_contact_no"
                                                class="form-control input-sm" value="">
                                            @if (!empty($message['msg']['director_contact_no'][0]))
                                            <small
                                                class="text-danger">{{$message['msg']['director_contact_no'][0]}}</small>
                                            @endif

                                        </div>
                                    </div>

                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">3.1.3 Email<span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="director_email" name="director_email"
                                                class="form-control input-sm" value="">
                                            @if (!empty($message['msg']['director_email'][0]))
                                            <small class="text-danger">{{$message['msg']['director_email'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">3.2.1 Sales Officer
                                            Name<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="sales_officer_name" name="sales_officer_name"
                                                class="form-control input-sm" value="">
                                            @if (!empty($message['msg']['sales_officer_name'][0]))
                                            <small
                                                class="text-danger">{{$message['msg']['sales_officer_name'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">3.2.2 Contact
                                            Number<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="number" id="sales_officer_contact_no"
                                                name="sales_officer_contact_no" class="form-control input-sm" value="">
                                            @if (!empty($message['msg']['sales_officer_contact_no'][0]))
                                            <small
                                                class="text-danger">{{$message['msg']['sales_officer_contact_no'][0]}}</small>
                                            @endif

                                        </div>
                                    </div>

                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">3.2.3 Email<span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="sales_officer_email" name="sales_officer_email"
                                                class="form-control input-sm" value="">
                                            @if (!empty($message['msg']['sales_officer_email'][0]))
                                            <small
                                                class="text-danger">{{$message['msg']['sales_officer_email'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">3.3.1 Account Officer
                                            Name<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="account_officer_name" name="account_officer_name"
                                                class="form-control input-sm" value="">
                                            @if (!empty($message['msg']['account_officer_name'][0]))
                                            <small
                                                class="text-danger">{{$message['msg']['account_officer_name'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">3.3.2 Contact
                                            Number<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="number" id="account_officer_contact_no"
                                                name="account_officer_contact_no" class="form-control input-sm"
                                                value="">
                                            @if (!empty($message['msg']['account_officer_contact_no'][0]))
                                            <small
                                                class="text-danger">{{$message['msg']['account_officer_contact_no'][0]}}</small>
                                            @endif

                                        </div>
                                    </div>

                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">3.3.3 Email<span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="account_officer_email" name="account_officer_email"
                                                class="form-control input-sm" value="">
                                            @if (!empty($message['msg']['account_officer_email'][0]))
                                            <small
                                                class="text-danger">{{$message['msg']['account_officer_email'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>


                                <!--  -->
                                <label style="font-weight: bold;">4. Compliance Section</label>
                                <hr>
                                <div class="row">
                                    <div class="form-group required col-md-6">
                                        <label for="inputStandard" class="col-md-12 control-label">4.1 Have any legal
                                            notices been served to the company in the last 1 or 2 years by any of the
                                            authorities?<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select name="any_legal_notices" id="any_legal_notices"
                                                class="form-control input-sm">
                                                <option value="">Select</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                            @if (!empty($message['msg']['any_legal_notices'][0]))
                                            <small
                                                class="text-danger">{{$message['msg']['any_legal_notices'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group required col-md-6" id="legalNoticeYesDiv">
                                        <label for="inputStandard" class="col-md-12 control-label">4.1.1 If Yes please
                                            elaborate<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <textarea id="legal_notice_elaborate" name="legal_notice_elaborate"
                                                class="form-control input-sm"></textarea>
                                            @if (!empty($message['msg']['legal_notice_elaborate'][0]))
                                            <small
                                                class="text-danger">{{$message['msg']['legal_notice_elaborate'][0]}}</small>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">4.2 Is the company
                                            compliant with all the mandatory EHS statutory and legal
                                            requirements?<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select name="is_legal_requirements" id="is_legal_requirements"
                                                class="form-control input-sm">
                                                <option value="">Select</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                                <option value="Not Applicable">Not Applicable</option>
                                            </select>
                                            @if (!empty($message['msg']['is_legal_requirements'][0]))
                                            <small
                                                class="text-danger">{{$message['msg']['is_legal_requirements'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">4.3 What is the
                                            minimum
                                            age of your workers/employees?<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="worker_minimum_age" name="worker_minimum_age"
                                                class="form-control input-sm" />
                                            @if (!empty($message['msg']['worker_minimum_age'][0]))
                                            <small
                                                class="text-danger">{{$message['msg']['worker_minimum_age'][0]}}</small>
                                            @endif

                                        </div>
                                    </div>
                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">4.4 Do the
                                            workers/employees have to submit any of their original governmental IDs to
                                            your company, while they join the company?<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select name="submit_original_documents" id="submit_original_documents"
                                                class="form-control input-sm">
                                                <option value="">Select</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                            @if (!empty($message['msg']['submit_original_documents'][0]))
                                            <small
                                                class="text-danger">{{$message['msg']['submit_original_documents'][0]}}</small>
                                            @endif

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group required col-md-6">
                                        <label for="inputStandard" class="col-md-12 control-label">4.5 Have there been
                                            any
                                            serious incidents/accidents to any of their workers at any of the sites in
                                            the last 2 years?<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select name="any_serious_incidents" id="any_serious_incidents"
                                                class="form-control input-sm">
                                                <option value="">Select</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                            @if (!empty($message['msg']['any_serious_incidents'][0]))
                                            <small
                                                class="text-danger">{{$message['msg']['any_serious_incidents'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group required col-md-6" id="elaborate_serious_incidentsDIV">
                                        <label for="inputStandard" class="col-md-12 control-label">4.5.1 If Yes please
                                            elaborate<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <textarea id="elaborate_serious_incidents"
                                                name="elaborate_serious_incidents"
                                                class="form-control input-sm"></textarea>
                                            @if (!empty($message['msg']['elaborate_serious_incidents'][0]))
                                            <small
                                                class="text-danger">{{$message['msg']['elaborate_serious_incidents'][0]}}</small>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group required col-md-6">
                                        <label for="inputStandard" class="col-md-12 control-label">4.6 Do the Company
                                            have
                                            an Anti-Corruption and Bribery Policy, Policy on POSH, Anti Child Labour and
                                            Forced and Bonded Labour Policy?<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select name="is_anti_bribe_policy" id="is_anti_bribe_policy"
                                                class="form-control input-sm">
                                                <option value="">Select</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                            @if (!empty($message['msg']['is_anti_bribe_policy'][0]))
                                            <small
                                                class="text-danger">{{$message['msg']['is_anti_bribe_policy'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group required col-md-6">
                                        <label for="inputStandard" class="col-md-12 control-label">4.7 Do the Company
                                            have
                                            an environmental, health, safety and social (EHS&S) policy?<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select name="is_health_safety_policy" id="is_health_safety_policy"
                                                class="form-control input-sm">
                                                <option value="">Select</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                            @if (!empty($message['msg']['is_health_safety_policy'][0]))
                                            <small
                                                class="text-danger">{{$message['msg']['is_health_safety_policy'][0]}}</small>
                                            @endif

                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group required col-md-6">
                                        <label for="inputStandard" class="col-md-12 control-label">4.8 Indicate whether
                                            your
                                            organization has been found to be out of compliance with any local labor,
                                            tax, or environmental regulations in 1-2 years?<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <select name="is_env_regulation" id="is_env_regulation"
                                                class="form-control input-sm">
                                                <option value="">Select</option>
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                            @if (!empty($message['msg']['is_env_regulation'][0]))
                                            <small
                                                class="text-danger">{{$message['msg']['is_env_regulation'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group required col-md-6" id="elaborate_env_regulationDiv">
                                        <label for="inputStandard" class="col-md-12 control-label">4.8.1 If Yes please
                                            elaborate<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <textarea id="elaborate_env_regulation" name="elaborate_env_regulation"
                                                class="form-control input-sm"></textarea>
                                            @if (!empty($message['msg']['elaborate_env_regulation'][0]))
                                            <small
                                                class="text-danger">{{$message['msg']['elaborate_env_regulation'][0]}}</small>
                                            @endif

                                        </div>
                                    </div>
                                </div>

                                <!--  -->
                                <label style="font-weight: bold;">5. Acknowledgment Section <span class="text-danger">*</span></label>
                                <hr>
                                <div class="row">
                                    <div class="form-group required col-md-12">
                                        <div class="col-md-12">
                                            <div class="acknowledgment_err"></div>
                                            <label class="col-md-12 control-label"><input type="checkbox"
                                                    id="acknowledgment" name="acknowledgment"> 1. No deviation without
                                                approval shall be accepted. 2. All disputes arising in regards to
                                                purchase, shall be subject to the exclusive jurisdiction of courts in
                                                Nashik. Any prior understanding in this regard hereby stands revoked. 3.
                                                We hereby declare that the particulars given are correct and
                                                complete.</label>
                                        </div>
                                    </div>
                                </div>

                                <!--  -->
                                <div class="row">
                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">5.1 Name<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="name" name="name" class="form-control input-sm">
                                            @if (!empty($message['msg']['name'][0]))
                                            <small class="text-danger">{{$message['msg']['name'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">5.2 Date<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input id="date" type="date" name="date" class="form-control input-sm">
                                            @if (!empty($message['msg']['date'][0]))
                                            <small class="text-danger">{{$message['msg']['date'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group required col-md-4">
                                        <label for="inputStandard" class="col-md-12 control-label">5.3
                                            Designation<span class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <input type="text" id="designation" name="designation"
                                                class="form-control input-sm">
                                            @if (!empty($message['msg']['designation'][0]))
                                            <small class="text-danger">{{$message['msg']['designation'][0]}}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-primary px-4" type="submit">Submit</button>
                                    <button class="btn btn-primary px-4" type="reset">Reset</button>
                                </div>

                            </form>

                            <script type="text/javascript">
                            $("#vendorForm").validate({
                                rules: {
                                    vendor_name: {
                                        required: true,
                                        maxlength: 255
                                    },
                                    acknowledgment: {
                                        required: true
                                    },
                                    warehouse_location: {
                                        required: true,
                                        maxlength: 500
                                    },
                                    contact_person: {
                                        required: true,
                                        maxlength: 50
                                    },
                                    contactno: {
                                        required: true,
                                        maxlength: 10,
                                        minlength: 10,
                                        digits: true
                                    },
                                    vendor_email: {
                                        required: true,
                                        maxlength: 255,
                                        email: true,
                                    },
                                    address: {
                                        required: true,
                                        maxlength: 255
                                    },
                                    city: {
                                        required: true,
                                        maxlength: 255
                                    },
                                    pincode: {
                                        required: true,
                                        maxlength: 6,
                                        digits: true
                                    },
                                    vendor_gst_no: {
                                        required: true,
                                        maxlength: 15
                                    },
                                    vendor_pan: {
                                        required: true,
                                        maxlength: 10
                                    },
                                    is_msme_reg: {
                                        required: true,
                                        maxlength: 50
                                    },
                                    is_gstnumber_reg: {
                                        required: true,
                                        maxlength: 50
                                    },
                                    products_services_offered: {
                                        required: true,
                                    },
                                    associate_oem: {
                                        required: true,
                                    },
                                    delivery_time: {
                                        required: true,
                                        maxlength: 100
                                    },
                                    payment_terms: {
                                        required: true,
                                    },
                                    annual_turnover: {
                                        required: true,
                                        maxlength: 100,
                                        
                                    },
                                    known_client: {
                                        required: true,
                                    },
                                    bank_name: {
                                        required: true,
                                        maxlength: 255
                                    },
                                    bank_address: {
                                        required: true,
                                    },
                                    bank_branch: {
                                        required: true,
                                        maxlength: 255
                                    },
                                    account_type: {
                                        required: true,
                                        maxlength: 255
                                    },
                                    bank_account_no: {
                                        required: true,
                                        digits:true,
                                        minlength: 9,
                                        maxlength: 20
                                    },
                                    ifsc_code: {
                                        required: true,
                                        maxlength: 11
                                    },
                                    director_name: {
                                        required: true,
                                    },
                                    director_contact_no: {
                                        required: true,
                                        maxlength: 10,
                                        minlength: 10,
                                    },
                                    director_email: {
                                        required: true,
                                        maxlength: 100,
                                        email: true,
                                    },
                                    sales_officer_name: {
                                        required: true,
                                    },
                                    sales_officer_contact_no: {
                                        required: true,
                                        maxlength: 10,
                                        minlength: 10,
                                    },
                                    sales_officer_email: {
                                        required: true,
                                        maxlength: 100,
                                        email: true,
                                    },
                                    account_officer_name: {
                                        required: true,
                                    },
                                    account_officer_contact_no: {
                                        required: true,
                                        maxlength: 10,
                                        minlength: 10,
                                    },
                                    account_officer_email: {
                                        required: true,
                                        maxlength: 100,
                                        email: true,
                                    },
                                    any_legal_notices: {
                                        required: true,
                                        maxlength: 50
                                    },
                                    legal_notice_elaborate: {
                                        required: true,
                                        maxlength: 255
                                    },
                                    is_legal_requirements: {
                                        required: true,
                                        maxlength: 100
                                    },
                                    worker_minimum_age: {
                                        required: true,
                                        maxlength: 100
                                    },
                                    submit_original_documents: {
                                        required: true,
                                        maxlength: 100
                                    },
                                    any_serious_incidents: {
                                        required: true,
                                        maxlength: 100
                                    },
                                    elaborate_serious_incidents: {
                                        required: true,
                                        maxlength: 255
                                    },
                                    is_anti_bribe_policy: {
                                        required: true,
                                        maxlength: 100
                                    },
                                    is_health_safety_policy: {
                                        required: true,
                                        maxlength: 100
                                    },
                                    is_env_regulation: {
                                        required: true,
                                        maxlength: 100
                                    },
                                    elaborate_env_regulation: {
                                        required: true,
                                        maxlength: 255
                                    },
                                    name: {
                                        required: true,
                                    },
                                    date: {
                                        required: true,
                                        maxlength: 100
                                    },
                                    designation: {
                                        required: true,
                                        maxlength: 100
                                    },
                                    vendor_gst_no_file: {
                                        required: true,
                                        extension: "jpeg|png|jpg|csv|txt|xlx|xls|pdf"
                                    },
                                    vendor_pan_file: {
                                        required: true,
                                        extension: "jpeg|png|jpg|csv|txt|xlx|xls|pdf"
                                    },
                                    bank_name_file: {
                                        required: true,
                                        extension: "jpeg|png|jpg|csv|txt|xlx|xls|pdf"
                                    },
                                    msme_certificate: {
                                        required: true,
                                        extension: "jpeg|png|jpg|csv|txt|xlx|xls|pdf"
                                    },
                                },
                                messages: {
                                    vendor_name: {
                                        required: "Please enter Vendor Name",
                                        maxlength: "Your Vendor Name maxlength should be 255 characters long."
                                    },
                                    warehouse_location: {
                                        required: "Please enter Warehouse Location",
                                        maxlength: "Your Warehouse Location maxlength should be 500 characters long."
                                    },
                                    contact_person: {
                                        required: "Please enter Contact Person",
                                        maxlength: "Your Contact Person maxlength should be 50 characters long."
                                    },
                                    contactno: {
                                        required: "Please enter Contact Number",
                                        maxlength: "Your Contact Number maxlength should be 10 characters long."
                                    },
                                    vendor_email: {
                                        required: "Please enter Vendor Email",
                                        maxlength: "Your Vendor Email maxlength should be 255 characters long."
                                    },
                                    address: {
                                        required: "Please enter Address",
                                        maxlength: "Your Address maxlength should be 255 characters long."
                                    },
                                    city: {
                                        required: "Please enter City",
                                        maxlength: "Your City maxlength should be 255 characters long."
                                    },
                                    pincode: {
                                        required: "Please enter Pin Code",
                                        maxlength: "Your Pin Code maxlength should be 6 characters long."
                                    },
                                    vendor_gst_no: {
                                        required: "Please enter Vendor GST No",
                                        maxlength: "Your Vendor GST No maxlength should be 15 characters long."
                                    },
                                    vendor_pan: {
                                        required: "Please enter Vendor PAN No",
                                        maxlength: "Your Vendor PAN No maxlength should be 10 characters long."
                                    },
                                    is_msme_reg: {
                                        required: "Please Select Here",
                                        maxlength: "Your MSME Register No maxlength should be 50 characters long."
                                    },
                                    is_gstnumber_reg: {
                                        required: "Please Select Here",
                                    },
                                    products_services_offered: {
                                        required: "Please enter Product/Services Offered.",
                                    },
                                    associate_oem: {
                                        required: "Please enter Associate OEM",
                                    },
                                    delivery_time: {
                                        required: "Please enter Delivery Time",
                                        maxlength: "Your Delivery Time maxlength should be 100 characters long."
                                    },
                                    payment_terms: {
                                        required: "Please enter Payment Terms",
                                    },
                                    annual_turnover: {
                                        required: "Please enter Annual Turnover",
                                        maxlength: "Your Annual Turnover maxlength should be 100 characters long."
                                    },
                                    known_client: {
                                        required: "Please enter Known Clients",
                                    },
                                    bank_name: {
                                        required: "Please enter Bank Name",
                                        maxlength: "Your Bank Name maxlength should be 255 characters long."
                                    },
                                    bank_branch: {
                                        required: "Please enter Branch Name",
                                        maxlength: "Your Branch Name maxlength should be 255 characters long."
                                    },
                                    bank_address: {
                                        required: "Please enter Bank Address",
                                    },
                                    account_type: {
                                        required: "Please Select Here",
                                        maxlength: "Your Account Type maxlength should be 255 characters long."
                                    },
                                    bank_account_no: {
                                        required: "Please Enter Account Number",
                                    },
                                    ifsc_code: {
                                        required: "Please Enter IFSC Code",
                                        maxlength: "Your IFSC Code maxlength should be 40 characters long."
                                    },
                                    director_name: {
                                        required: "Please Enter Director Name",
                                    },
                                    director_contact_no: {
                                        required: "Please Enter Director Contact No",
                                        maxlength: "Your Director Contact No maxlength should be 10 characters long."
                                    },
                                    director_email: {
                                        required: "Please Enter Director Email",
                                        maxlength: "Your Director Email maxlength should be 100 characters long."
                                    },
                                    sales_officer_name: {
                                        required: "Please Enter Sales Officer Name",
                                    },
                                    sales_officer_contact_no: {
                                        required: "Please Enter Sales Officer Contact No",
                                        maxlength: "Your Sales Officer Contact No maxlength should be 10 characters long."
                                    },
                                    sales_officer_email: {
                                        required: "Please Enter Sales Officer Email",
                                        maxlength: "Your Sales Officer Email maxlength should be 100 characters long."
                                    },
                                    account_officer_name: {
                                        required: "Please Enter Account Officer Name",
                                    },
                                    account_officer_contact_no: {
                                        required: "Please Enter Account Officer Contact No",
                                        maxlength: "Your Account Officer Contact No maxlength should be 10 characters long."
                                    },
                                    account_officer_email: {
                                        required: "Please Enter Account Officer Email",
                                        maxlength: "Your Account Officer Email maxlength should be 100 characters long."
                                    },
                                    any_legal_notices: {
                                        required: "Please Select Here",
                                        maxlength: "Your Any Legal Notices maxlength should be 100 characters long."
                                    },
                                    is_legal_requirements: {
                                        required: "Please Select Here",
                                        maxlength: "Your Is Legal Requirements maxlength should be 100 characters long."
                                    },
                                    submit_original_documents: {
                                        required: "Please Select Here",
                                        maxlength: "Your Is Submit Original Documents maxlength should be 100 characters long."
                                    },
                                    any_serious_incidents: {
                                        required: "Please Select Here",
                                        maxlength: "Your Any Serious Incidents maxlength should be 100 characters long."
                                    },
                                    elaborate_serious_incidents: {
                                        required: "Please Enter Here",
                                        maxlength: "Your maxlength should be 255 characters long."
                                    },
                                    legal_notice_elaborate: {
                                        required: "Please Enter Here",
                                        maxlength: "Your maxlength should be 255 characters long."
                                    },
                                    elaborate_env_regulation: {
                                        required: "Please Enter Here",
                                        maxlength: "Your maxlength should be 255 characters long."
                                    },
                                    is_anti_bribe_policy: {
                                        required: "Please Select Here",
                                        maxlength: "Your Anti Bribe Policy maxlength should be 100 characters long."
                                    },
                                    is_health_safety_policy: {
                                        required: "Please Select Here",
                                        maxlength: "Your Health Safety Policy maxlength should be 100 characters long."
                                    },
                                    is_env_regulation: {
                                        required: "Please Select Here",
                                        maxlength: "Your Environment Regulation maxlength should be 100 characters long."
                                    },
                                    name: {
                                        required: "Please Enter Name",
                                    },
                                    date: {
                                        required: "Please Select Date",
                                        maxlength: "Your Date maxlength should be 100 characters long."
                                    },
                                    designation: {
                                        required: "Please Enter Designation",
                                        maxlength: "Your Designation maxlength should be 100 characters long."
                                    },
                                    vendor_gst_no_file: {
                                        required: "Please upload Vendor GST No File",
                                        maxlength: "File accept only jepg|png|jpg|csv|txt|xlx|xls|pdf."
                                    },
                                    vendor_pan_file: {
                                        required: "Please upload Vendor PAN No File",
                                        maxlength: "File accept only jepg|png|jpg|csv|txt|xlx|xls|pdf."
                                    },
                                    bank_name_file: {
                                        required: "Please upload Bank Proof File",
                                        maxlength: "File accept only jepg|png|jpg|csv|txt|xlx|xls|pdf."
                                    },

                                    msme_certificate: {
                                        required: "Please upload  MSME Certificate",
                                        maxlength: "File accept only jepg|png|jpg|csv|txt|xlx|xls|pdf."
                                    },
                                },
                                errorPlacement: function (error, element) {
                                    if (element.attr("type") == "checkbox") {
                                        error.appendTo('.acknowledgment_err');                                
                                    } else {
                                       error.insertAfter(element);
                                    }
                                }
                                // submitHandler: function(form) {                                    
                                //     var formData = new FormData(document.getElementById('vendorForm'));
                                //     $.ajaxSetup({
                                //         headers: {
                                //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                //         }
                                //     });                        
                                //     $.ajax({
                                //         url: "<?php echo config("app.site_url"); ?>/vendors/save/{{$token}}",
                                //         type: "POST",
                                //         data: formData,
                                //         contentType: false,
                                //         processData: false,
                                //         success: function(response) {
                                //             alert("Vendor Added");
                                //         }
                                //     });
                                // }
                            })
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>

</html>