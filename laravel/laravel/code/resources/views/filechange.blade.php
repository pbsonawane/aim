<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo trans("title.enlight_360")." ".trans("title.en_notification"); ?></title>
<style type="text/css">
::selection{ background-color: #E13300; color: white; }
::moz-selection{ background-color: #E13300; color: white; }
::webkit-selection{ background-color: #E13300; color: white; }
body {
	background-color: #fff;
	margin: 60px;
	font: 10px/20px normal "Open Sans",sans-serif;
	color: #4F5155;
}
a {
	color: #003399;
	background-color: transparent;
	font-weight: normal;
	font-size:11px;
}
h1 {
	color: #444;
	background-color: transparent;
	border-bottom: 1px solid #CCC;
	font-size: 18px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 0 0 10px 0;
}
#container {
	margin: 10px;
	border: 1px solid #CC;
	-webkit-box-shadow: 0 0 15px red;
	padding:20px;
	font-family: "Open Sans",sans-serif;
	font-size:14px;
	width:400px;
}
p {
	margin: 12px 15px 12px 15px;
}
</style>

    <link rel="stylesheet" type="text/css" href="<?php echo config('enconfig.site_url'); ?>/login/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?php echo config('enconfig.site_url'); ?>/login/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="<?php echo config('enconfig.site_url'); ?>/login/css/style.css">
    <link rel="icon" type="image/png" href="<?php echo config('enconfig.site_url'); ?>/enlight/images/favicon.ico">
</head>
<body>
	
<div class="login_frm">
  	<div class="container">
    	<div class="row justify-content-center">
	        <div class="col-md-6">
	        	<form action="">
        						{{ csrf_field() }}

				 <div class="text-center"> <img src="<?php echo config('enconfig.site_url'); ?>/showlogo" class="responsive" title="<?php echo isset($product_name) ? $product_name : ''; ?>">
				 </div>

                	<h2 class="text-center">{{ trans('label.lbl_en_notification') }}</h2>

	                <?php
					   //print_r($checksum);
					    if (isset($checksum['is_error']) && $checksum['is_error'] == 1)
					    {
					        echo "<br>".(strstr($checksum['msg'], '##') ? str_replace('##', '<br><br>', $checksum['msg']) : $checksum['msg']);
					    ?>
					        <meta http-equiv="refresh" content="60;URL='<?php echo config('enconfig.site_url'); ?>'" />
					<?php
					    }
					    else
					    {
					    ?>
					<br><br><a href="<?php echo config('enconfig.site_url'); ?>"><?php echo trans('label.lbl_click_here'); ?></a> <?php echo trans('label.lbl_go_to_home_page'); ?>
					<?php
					}
					    //echo "<br><br>I: ".$this->config->item('checksum_interval')."----C: ".date("F j, Y, g:i:s a", time())."----N: ".date("F j, Y, g:i:s a", $this->session->userdata("EMCHECKSUMTIME"));
					?>

					</form>  			   
	      	</div>
 		</div>
	</div>
	<?php echo view('footer_outer'); ?>  
</div>
<div class="annim"></div>
</div>  
</body>
</html>
