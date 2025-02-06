
<!-- Start: Topbar -->
<header id="topbar" class="affix"  >
<div class="topbar-left">
  <ol class="breadcrumb">
    <li class="crumb-active"><a class="nounderline">Locations</a></li>
    <li class="crumb-link">Admin</li>
    <li class="crumb-trail"><a href="<?php echo url()->current();?>">Location List</a></li>
  </ol>
</div>
<div class="topbar-right">
	<div class="btn-group">
		<button id="timeline-toggle" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		<span class="glyphicons glyphicons-show_lines fs16"></span>
		</button>
		<ul class="dropdown-menu pull-right" role="menu">
      <li id="locadd">
        <a ><span title="Location Add" class="locadd">Add Location</span></a>
      </li>
    </ul>
	</div>
</div>
</header>
<!-- End: Topbar -->
<div id="content">
  <div class="row">
    <div class="col-md-12">
      <div class="alert hidden alert-dismissable" id="msg_div"></div>
    </div>
    <div class="col-md-12">
    	<form method="post" name="frmlist" id="frmlist">  	
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
<script language="javascript" type="text/javascript" src="enlight/scripts/common.js"></script> 
<script language="javascript" type="text/javascript" src="enlight/scripts/admin/locations.js"></script>