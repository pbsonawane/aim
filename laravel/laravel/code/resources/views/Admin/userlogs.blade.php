
<!-- Start: Topbar -->
<header id="topbar" class="affix"  >
<div class="topbar-left">
  <ol class="breadcrumb">
    <li class="crumb-active"><a class="nounderline" href="<?php echo url()->current();?>">User Logs</a></li>
    <li class="crumb-link">Admin</li>
    <li class="crumb-trail">User Logs</li>
  </ol>
</div>
 
</header>
<!-- End: Topbar -->
<div id="content">
  <div class="row">
    <div class="col-md-12">
      <div class="alert hidden alert-dismissable" id="msg_div"></div>
    </div>
    <div class="col-md-12">
    	<form method="post" name="frmdevices" id="frmdevices">  	
      		<div class="panel">
				<?php echo csrf_field(); ?>	
				<?php echo isset($emgridtop) ? $emgridtop : ''; ?>
        		<div class="panel panel-visible" id="grid_data"></div>
      		</div>
      	</form>
    </div>
  </div>
</div>
</div>
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/common.js"></script> 
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/admin/useractivity.js"></script>