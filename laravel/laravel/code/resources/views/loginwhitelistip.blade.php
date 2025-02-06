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
    <link href="<?php echo config("app.site_url"); ?>/coreui/css/style.css?v=<?php echo time();?>" rel="stylesheet">
    <link href="<?php echo config("app.site_url"); ?>/coreui/vendors/pace-progress/css/pace.min.css" rel="stylesheet">
  </head>
  <body class="app flex-row align-items-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card-group">
            <div class="card p-4">
              <div class="card-body">
				<form action="<?php echo config("app.site_url"); ?>/authenticate/validatewhitelistip" method="post">
				{{ csrf_field() }}
                <input class="form-control" type="hidden" name ="resettoken" value="<?php echo $resettoken ?>" name="token">
                <h1>Whitelist public IP address.</h1>
                <p class="text-muted">Validate public IP address to access account.</p>
                @if ($success = $errors->first('success'))
					 <div class="alert alert-success" role="alert">
					   <?php echo $success; ?>
					 </div>
					@endif
                    @if ($invalid = $errors->first('invalid'))
					 <div class="alert alert-danger" role="alert">
					   <?php echo $invalid; ?>
					 </div>
					@endif                    
                <div class="row">
                  <!--<div class="col-6">
					@if (!$success = $errors->first('success'))
						<button class="btn btn-primary px-4" type="submit">Submit</button>
					@endif	
                  </div>-->
                  @if (!$success = $errors->first('success'))
                  <div class="col-6">
                        <button class="btn btn-primary px-4" type="submit">Validate</button>
                  </div>
                  @endif
                </div>
				</form>
              </div>
                @if ($success = $errors->first('success'))
                <div class="col-12 text-right">
                    <a href="<?php echo config("app.site_url"); ?>/login" ><button class="btn btn-link px-0" type="button">Login</button></a>
                </div>
                @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>