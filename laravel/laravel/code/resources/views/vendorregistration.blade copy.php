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
                                <label for="inputStandard" class="col-md-12 control-label"><?php echo trans('label.lbl_email');?><span class="text-danger">*</span></label>
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
                                <label for="inputStandard" class="col-md-12 control-label"><?php echo trans('label.lbl_contact_person');?><span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" id="contact_person" name="contact_person" class="form-control input-sm" value="">
                                    @if (!empty($message['msg']['contact_person'][0]))
                                    <small class="text-danger">{{$message['msg']['contact_person'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label"><?php echo trans('label.lbl_contact_no');?><span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                    <input type="text" id="contactno" name="contactno" class="form-control input-sm" value="">
                                    @if (!empty($message['msg']['contactno'][0]))
                                    <small class="text-danger">{{$message['msg']['contactno'][0]}}</small>
                                    @endif

                                </div>
                        </div>
                        <div class="form-group required col-md-4">
                                <label for="inputStandard" class="col-md-12 control-label"><?php echo trans('label.lbl_address');?><span class="text-danger">*</span></label>
                                <div class="col-md-12">
                                     <textarea id="address" name="address" class="form-control input-sm"></textarea>
                                     @if (!empty($message['msg']['address'][0]))
                                    <small class="text-danger">{{$message['msg']['address'][0]}}</small>
                                    @endif
                                </div>
                        </div>
                    </div>
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
                       <?php /* <div class="form-group required col-md-12">
                               <label for="inputStandard" class="col-md-12 control-label"><strong>Choose Assets:<span class="text-danger">*</span></strong></label>
                                
                                <div class="col-md-12">
                                    @if (!empty($message['msg']['vendors_assets'][0]))
                                        <small class="text-danger">{{$message['msg']['vendors_assets'][0]}}</small>
                                    @endif
                                    <?php $assets = $citemplates['content']['records'];
                                    if(!empty($assets)){
                                    foreach($assets as $key => $val){
                                        echo '<div> <label for="inputStandard" class="col-md-2 control-label"><strong>'.$val['title'].'</strong></label>';
                                        foreach($val['children'] as $items){
                                            echo "<input type='checkbox' id='' 
                                            name='vendors_assets[".$val["key"]."][]' value='".$items['key']."'> ".$items['title']." ";

                                        }
                                        echo "</div>";
                                    }
                                }
                                    ?>
                                </div>
                        </div> */?> 

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