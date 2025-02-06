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

              <form action="<?php echo config("app.site_url"); ?>/vendors" method="POST">
                {{ csrf_field() }}
                <h1>Send OTP</h1>
                <p class="text-muted">&nbsp;</p>
                <?php if(Session::get('status')){
                   echo '<div class="text-success">'.Session::get('status').'</div>';
                }

                if(!empty($errors['vendor_email'])){
                  foreach ($errors['vendor_email'] as $value) {
                   echo '<div class="text-danger">'.$value.'</div>';
                 }}?>
                 
                 <div class="form-group required ">
                  <label for="inputStandard" class="col-md-3 control-label">Vendor Email<span class="text-danger">*</span></label>
                  <div class="col-md-8">
                    <input type="text" id="vendor_email" placeholder="enter email" name="vendor_email" class="form-control input-sm" value="{{ old('vendor_email') }}">

                  </div>
                </div>


                <div class="col-6">
                  <button class="btn btn-primary px-4" name="send_otp" id="send_otp"  value="verify_otp" type="submit">Submit</button>
                  <button class="btn btn-primary px-4" type="reset">Reset</button>
                </div>

              </form>
              <br>
              <div class="text-danger">Note: OTP valid only 5 Minute</div>
          <?php /*?>
            <form action="<?php echo config("app.site_url"); ?>/vendors" method="POST">
                {{ csrf_field() }}
                <h1>OTP Verification</h1>
                <p class="text-muted">&nbsp;</p>

               
                    <?php if(!empty($errors['otp'])){
                    foreach ($errors['otp'] as $value) {
                       echo '<div class="text-danger">'.$value.'</div>';
                    }}?>
                            <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label">Enter OTP<span class="text-danger">*</span></label>
                                <div class="col-md-8">
                                    <input type="text" placeholder="enter otp" id="otp" name="otp" class="form-control input-sm" value="{{ old('otp') }}">
                                    
                                </div>
                        </div>
                       

                  <div class="col-6">
                    <button class="btn btn-primary px-4" name="verify_otp" value="verify_otp" type="submit">Submit</button>
                    <button class="btn btn-primary px-4" type="reset">Reset</button>
                  </div>

              </form>
              <?php */?>
            </div> 
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>