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
  <link href="<?php echo config("app.site_url"); ?>/coreui/css/style.css" rel="stylesheet">
  <link href="<?php echo config("app.site_url"); ?>/coreui/vendors/pace-progress/css/pace.min.css" rel="stylesheet">
</head>
<body class="app flex-row align-items-center1">
  <div class="container">
    <div class="row justify-content-center1">
      <div class="col-md-12">
        <div class="card-group">
          <div class="card p-4">
            <div class="card-body">

              <form action="<?php echo config("app.site_url"); ?>/vendors/save/{{$token}}" method="POST" 
                enctype="multipart/form-data">
                {{ csrf_field() }}
                <h1>Vendor Registration</h1>
                <p class="text-muted">&nbsp;</p>

                 <input type="hidden" id="vendor_token" name="vendor_token" class="form-control input-sm" value="{{$token}}">
                 @if (!empty($message['msg']) && $message['is_error']=='')
                 <span class="text-success">Thank you for your registration!</span>
                 @endif
                 <div class="row">
                            <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label"><?php echo trans('label.lbl_vendor_name');?><span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" id="vendor_name" name="vendor_name" class="form-control input-sm" value="{{ old('vendor_name') }}">
                                  
                                    @if (!empty($message['msg']['vendor_name'][0]))
                                    <small class="text-danger">{{$message['msg']['vendor_name'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                        <div class="form-group col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label"><?php echo trans('label.lbl_vendor_reference');?></label>
                                <div class="col-md-12">
                                    <input type="text" id="vendor_ref_id" name="vendor_ref_id" class="form-control input-sm" value="">
                                     @if (!empty($message['msg']['vendor_ref_id'][0]))
                                    <small class="text-danger">{{$message['msg']['vendor_ref_id'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">Warehouse Location<span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                     <textarea id="warehouse_location" name="warehouse_location" class="form-control input-sm"></textarea>
                                     @if (!empty($message['msg']['warehouse_location'][0]))
                                    <small class="text-danger">{{$message['msg']['warehouse_location'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group required col-md-4">
                        <label for="inputStandard" class="col-md-12 control-label">Contact Person<span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" id="contact_person" name="contact_person" class="form-control input-sm" value="">
                                    @if (!empty($message['msg']['contact_person'][0]))
                                    <small class="text-danger">{{$message['msg']['contact_person'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">Contact Number<span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="number" id="contactno" name="contactno" class="form-control input-sm" value="">
                                    @if (!empty($message['msg']['contactno'][0]))
                                    <small class="text-danger">{{$message['msg']['contactno'][0]}}</small>
                                    @endif

                                </div>
                        </div>
                        
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">Vendor Email<span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" id="vendor_email" name="vendor_email" class="form-control input-sm" value="">
                                    @if (!empty($message['msg']['vendor_email'][0]))
                                    <small class="text-danger">{{$message['msg']['vendor_email'][0]}}</small>
                                    @endif
                                </div>
                                
                        </div>
                    </div>
                    <div class="row">
                            <div class="form-group required col-md-4">
                             <label for="inputStandard" class="col-md-12 control-label">Address<span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                     <textarea id="address" name="address" class="form-control input-sm"></textarea>
                                     @if (!empty($message['msg']['address'][0]))
                                    <small class="text-danger">{{$message['msg']['address'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                        <div class="form-group col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">City</label>
                                <div class="col-md-12">
                                    <input type="text" id="city" name="city" class="form-control input-sm" value="">
                                     @if (!empty($message['msg']['city'][0]))
                                    <small class="text-danger">{{$message['msg']['city'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">Pin Code<span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="number" id="pincode" name="pincode" class="form-control input-sm" value="">
                                     @if (!empty($message['msg']['pincode'][0]))
                                    <small class="text-danger">{{$message['msg']['pincode'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                    </div>
                    <!--  -->
                    <div class="row">
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">Contact Person 2<span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" id="contact_person2" name="contact_person2" class="form-control input-sm" value="">
                                    @if (!empty($message['msg']['contact_person_2'][0]))
                                    <small class="text-danger">{{$message['msg']['contact_person_2'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">Contact Number 2<span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="number" id="contact_no_2" name="contact_no_2" class="form-control input-sm" value="">
                                    @if (!empty($message['msg']['contact_no_2'][0]))
                                    <small class="text-danger">{{$message['msg']['contact_no_2'][0]}}</small>
                                    @endif

                                </div>
                        </div>
                        
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">Contact Email 2<span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" id="contact_email_2" name="contact_email_2" class="form-control input-sm" value="">
                                    @if (!empty($message['msg']['contact_email_2'][0]))
                                    <small class="text-danger">{{$message['msg']['contact_email_2'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">Contact Person 3<span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" id="contact_person_3" name="contact_person_3" class="form-control input-sm" value="">
                                    @if (!empty($message['msg']['contact_person_3'][0]))
                                    <small class="text-danger">{{$message['msg']['contact_person_3'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">Contact Number 3<span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="number" id="contact_no_3" name="contact_no_3" class="form-control input-sm" value="">
                                    @if (!empty($message['msg']['contact_no_3'][0]))
                                    <small class="text-danger">{{$message['msg']['contact_no_3'][0]}}</small>
                                    @endif

                                </div>
                        </div>
                        
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">Contact Email 3<span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" id="contact_email_3" name="contact_email_3" class="form-control input-sm" value="">
                                    @if (!empty($message['msg']['contact_email_3'][0]))
                                    <small class="text-danger">{{$message['msg']['contact_email_3'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                    </div>
                    <!--  -->
                    <div class="row">
                         <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label"><?php echo trans('label.lbl_gstno');?><span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                <input type="text" id="vendor_gst_no" name="vendor_gst_no" class="form-control input-sm">
                                     @if (!empty($message['msg']['vendor_gst_no'][0]))
                                    <small class="text-danger">{{$message['msg']['vendor_gst_no'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">GST Prof<span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                <input type="file" id="vendor_gst_no_file" name="vendor_gst_no_file" class="form-control input-sm">
                                     @if (!empty($message['msg']['vendor_gst_no_file'][0]))
                                    <small class="text-danger">{{$message['msg']['vendor_gst_no_file'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                         <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label"><?php echo trans('label.lbl_pan');?><span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                     <input type="text" id="vendor_pan" name="vendor_pan" class="form-control input-sm">
                                     @if (!empty($message['msg']['vendor_pan'][0]))
                                    <small class="text-danger">{{$message['msg']['vendor_pan'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">PAN Prof<span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                <input type="file" id="vendor_pan_file" name="vendor_pan_file" class="form-control input-sm">
                                     @if (!empty($message['msg']['vendor_pan_file'][0]))
                                    <small class="text-danger">{{$message['msg']['vendor_pan_file'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label"><?php echo trans('label.lbl_bank_name');?><span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                     <input type="text" id="bank_name" name="bank_name" class="form-control input-sm">
                                     @if (!empty($message['msg']['bank_name'][0]))
                                    <small class="text-danger">{{$message['msg']['bank_name'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">Bank Passbook Prof<span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                <input type="file" id="bank_name_file" name="bank_name_file" class="form-control input-sm">
                                     @if (!empty($message['msg']['bank_name_file'][0]))
                                    <small class="text-danger">{{$message['msg']['bank_name_file'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                    </div>
                    <div class="row">                        
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label"><?php echo trans('label.lbl_bank_address');?><span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                     <textarea id="bank_address" name="bank_address" class="form-control input-sm"></textarea>
                                     @if (!empty($message['msg']['bank_address'][0]))
                                    <small class="text-danger">{{$message['msg']['bank_address'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label"><?php echo trans('label.lbl_bank_branch');?><span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                     <input type="text" id="bank_branch" name="bank_branch" class="form-control input-sm">
                                     @if (!empty($message['msg']['bank_branch'][0]))
                                    <small class="text-danger">{{$message['msg']['bank_branch'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label"><?php echo trans('label.lbl_bank_account_no');?><span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                     <input type="text" id="bank_account_no" name="bank_account_no" class="form-control input-sm">
                                     @if (!empty($message['msg']['bank_account_no'][0]))
                                    <small class="text-danger">{{$message['msg']['bank_account_no'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label"><?php echo trans('label.lbl_ifsc_code');?><span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                     <input type="text" id="ifsc_code" name="ifsc_code" class="form-control input-sm">
                                     @if (!empty($message['msg']['ifsc_code'][0]))
                                    <small class="text-danger">{{$message['msg']['ifsc_code'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label"><?php echo trans('label.lbl_micr_code');?><span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                     <input id="micr_code" type="text" name="micr_code" class="form-control input-sm">
                                     @if (!empty($message['msg']['micr_code'][0]))
                                    <small class="text-danger">{{$message['msg']['micr_code'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label"><?php echo trans('label.lbl_account_type');?><span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                     <input type="text" id="account_type" name="account_type" class="form-control input-sm">
                                     @if (!empty($message['msg']['account_type'][0]))
                                    <small class="text-danger">{{$message['msg']['account_type'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">Client 1<span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                     <input type="text" id="client_1" name="client_1" class="form-control input-sm">
                                     @if (!empty($message['msg']['client_1'][0]))
                                    <small class="text-danger">{{$message['msg']['client_1'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">Client 2<span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                     <input id="client_2" type="text" name="client_2" class="form-control input-sm">
                                     @if (!empty($message['msg']['client_2'][0]))
                                    <small class="text-danger">{{$message['msg']['client_2'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">Client 3<span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                     <input type="text" id="client_3" name="client_3" class="form-control input-sm">
                                     @if (!empty($message['msg']['client_3'][0]))
                                    <small class="text-danger">{{$message['msg']['client_3'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">Client 4<span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                     <input type="text" id="client_4" name="client_4" class="form-control input-sm">
                                     @if (!empty($message['msg']['client_4'][0]))
                                    <small class="text-danger">{{$message['msg']['client_4'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">Client 5<span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                     <input id="client_5" type="text" name="client_5" class="form-control input-sm">
                                     @if (!empty($message['msg']['client_5'][0]))
                                    <small class="text-danger">{{$message['msg']['client_5'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">If firm is MSME registered</label>
                                <div class="col-md-12">
                                     <input type="text" id="is_meme_reg" name="is_meme_reg" class="form-control input-sm">
                                     @if (!empty($message['msg']['is_meme_reg'][0]))
                                    <small class="text-danger">{{$message['msg']['is_meme_reg'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">if Yes, MEME Registration Number</label>
                                <div class="col-md-12">
                                     <input type="text" id="meme_reg_num" name="meme_reg_num" class="form-control input-sm">
                                     @if (!empty($message['msg']['meme_reg_num'][0]))
                                    <small class="text-danger">{{$message['msg']['meme_reg_num'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">Products/Services Offered</label>
                                <div class="col-md-12">
                                     <input id="products_services_offered" type="text" name="products_services_offered" class="form-control input-sm">
                                     @if (!empty($message['msg']['products_services_offered'][0]))
                                    <small class="text-danger">{{$message['msg']['products_services_offered'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">Association with OEM</label>
                                <div class="col-md-12">
                                     <input type="text" id="associate_oem" name="associate_oem" class="form-control input-sm">
                                     @if (!empty($message['msg']['associate_oem'][0]))
                                    <small class="text-danger">{{$message['msg']['associate_oem'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">if Yes, MEME Registration Number</label>
                                <div class="col-md-12">
                                     <input type="text" id="meme_reg_num" name="meme_reg_num" class="form-control input-sm">
                                     @if (!empty($message['msg']['meme_reg_num'][0]))
                                    <small class="text-danger">{{$message['msg']['meme_reg_num'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">Annual Turnover</label>
                                <div class="col-md-12">
                                     <input id="annual_turnover" type="text" name="annual_turnover" class="form-control input-sm">
                                     @if (!empty($message['msg']['annual_turnover'][0]))
                                    <small class="text-danger">{{$message['msg']['annual_turnover'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">Payment Terms</label>
                                <div class="col-md-12">
                                     <input type="text" id="payment_terms" name="payment_terms" class="form-control input-sm">
                                     @if (!empty($message['msg']['payment_terms'][0]))
                                    <small class="text-danger">{{$message['msg']['payment_terms'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                    </div>
                    <!--  -->
                    
                    <div class="row">
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">Name</label>
                                <div class="col-md-12">
                                     <input type="text" id="name" name="name" class="form-control input-sm">
                                     @if (!empty($message['msg']['name'][0]))
                                    <small class="text-danger">{{$message['msg']['name'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">Date</label>
                                <div class="col-md-12">
                                     <input id="date" type="date" name="date" class="form-control input-sm">
                                     @if (!empty($message['msg']['date'][0]))
                                    <small class="text-danger">{{$message['msg']['date'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label">Designation</label>
                                <div class="col-md-12">
                                     <input type="text" id="designation" name="designation" class="form-control input-sm">
                                     @if (!empty($message['msg']['designation'][0]))
                                    <small class="text-danger">{{$message['msg']['designation'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                    </div>
                    <!--  -->
                    <div class="row">
                        <div class="form-group required col-md-12">
                            <label for="inputStandard" class="col-md-12 control-label">Have any legal notices been served to the company in the last 1 or 2 years by any of the authorities?</label>
                            <div class="col-md-12">
                                    <textarea id="any_legal_notices" name="any_legal_notices" class="form-control input-sm"></textarea>                                    
                                    @if (!empty($message['msg']['any_legal_notices'][0]))
                                <small class="text-danger">{{$message['msg']['any_legal_notices'][0]}}</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group required col-md-12">
                            <label for="inputStandard" class="col-md-12 control-label">Is the company compliant with all the mandatory EHS statutory and legal requirements?</label>
                            <div class="col-md-12">
                                    <textarea id="legal_requirements" name="legal_requirements" class="form-control input-sm"></textarea>                                    
                                    @if (!empty($message['msg']['legal_requirements'][0]))
                                <small class="text-danger">{{$message['msg']['legal_requirements'][0]}}</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group required col-md-12">
                            <label for="inputStandard" class="col-md-12 control-label">What is the minimum age of your workers/employees?</label>
                            <div class="col-md-12">
                                <input id="minimum_worker_age" type="number" name="minimum_worker_age" class="form-control input-sm">                                    
                                    @if (!empty($message['msg']['minimum_worker_age'][0]))
                                <small class="text-danger">{{$message['msg']['minimum_worker_age'][0]}}</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group required col-md-12">
                            <label for="inputStandard" class="col-md-12 control-label">Do the workers/employees have to submit any of their original governmental IDs to your company, while they join the company?</label>
                            <div class="col-md-12">
                                    <textarea id="submit_documents" name="submit_documents" class="form-control input-sm"></textarea>                                    
                                    @if (!empty($message['msg']['submit_documents'][0]))
                                <small class="text-danger">{{$message['msg']['submit_documents'][0]}}</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group required col-md-12">
                            <label for="inputStandard" class="col-md-12 control-label">Have there been any serious incidents/accidents to any of their workers at any of the sites in the last 2 years?</label>
                            <div class="col-md-12">
                                    <textarea id="serious_incidents" name="serious_incidents" class="form-control input-sm"></textarea>                                    
                                    @if (!empty($message['msg']['serious_incidents'][0]))
                                <small class="text-danger">{{$message['msg']['serious_incidents'][0]}}</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group required col-md-12">
                            <label for="inputStandard" class="col-md-12 control-label">Do the Company have an Anti-Corruption and Bribery Policy, Policy on POSH, Anti Child Labour and Forced and Bonded Labour Policy?</label>
                            <div class="col-md-12">
                                    <textarea id="company_policies" name="company_policies" class="form-control input-sm"></textarea>                                    
                                    @if (!empty($message['msg']['company_policies'][0]))
                                <small class="text-danger">{{$message['msg']['company_policies'][0]}}</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group required col-md-12">
                            <label for="inputStandard" class="col-md-12 control-label">Do the Company have an environmental, health, safety and social (EHS&S) policy?</label>
                            <div class="col-md-12">
                                    <textarea id="env_health_policy" name="env_health_policy" class="form-control input-sm"></textarea>                                    
                                    @if (!empty($message['msg']['env_health_policy'][0]))
                                <small class="text-danger">{{$message['msg']['env_health_policy'][0]}}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group required col-md-12">
                            <label for="inputStandard" class="col-md-12 control-label">Indicate whether your organization has been found to be out of compliance with any local labor, tax, or environmental regulations in 1-2 years?</label>
                            <div class="col-md-12">
                                    <textarea id="out_of_compliance" name="out_of_compliance" class="form-control input-sm"></textarea>                                    
                                    @if (!empty($message['msg']['out_of_compliance'][0]))
                                <small class="text-danger">{{$message['msg']['out_of_compliance'][0]}}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!--  -->
                       
                  <div class="col-6">
                    <button class="btn btn-primary px-4" type="submit">Submit</button>
                    <button class="btn btn-primary px-4" type="reset">Reset</button>
                  </div>

              </form>
            </div> 
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>