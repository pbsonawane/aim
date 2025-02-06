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
  <body class="app flex-row align-items-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card-group">
            <div class="card p-4">
              <div class="card-body">
				<form action="<?php echo config("app.site_url"); ?>/doreset" method="get">
				{{ csrf_field() }}
                <h1>Forgot Password</h1>
                <p class="text-muted">Just follow this simple steps to get a new password.</p>
				
				@if (!$errors->isEmpty())
					@if ($error = $errors->first('username'))
					 <div class="alert alert-danger" role="alert">
					   {{ $error }}
					 </div>
					@endif	
					@if ($error = $errors->first('unauth'))
					 <div class="alert alert-danger" role="alert">
					   {{ $error }}
					 </div>
					@endif	
					@if ($success = $errors->first('success'))
					 <div class="alert alert-success" role="alert">
					   {{ $success }}
					 </div>
					@endif	
				@endif		
				@if (!$success = $errors->first('success'))
				<div class="input-group mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text">
                      <i class="icon-user"></i>
                    </span>
                  </div>
                  <input class="form-control" type="text" autocomplete="off"  placeholder="Username" name="username">
                </div>
				@endif	
                <div class="row">
                  <div class="col-6">
					@if (!$success = $errors->first('success'))
						<button class="btn btn-primary px-4" type="submit">Submit</button>
					@endif	
                  </div>
                  <div class="col-6 text-right">
                    <a href="<?php echo config("app.site_url"); ?>/login"><button class="btn btn-link px-0" type="button">Login</button></a>
                  </div>
                </div>
				</form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>