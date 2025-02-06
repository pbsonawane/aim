<!-- Start: Topbar -->
<header id="topbar" class="affix"  >
<div class="topbar-left">
   <?php breadcrum('Cost Centers'); ?>
   <?php breadcrum(trans('title.costcenter')); ?>
</div>
<div class="topbar-right">
 @if(canuser('create','costcenter'))
	<div class="btn-group">
		<button id="timeline-toggle" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		<span class="glyphicons glyphicons-show_lines fs16"></span>
		</button>
		 <ul class="dropdown-menu pull-right" role="menu" >
			<li class="costcenteradd" id="costcenteradd" title="Costcenter Add">
            <a ><span title="Costcenter Add" class="costcenteradd"><?php echo trans('label.lbl_add_cc');?></span></a>
			</li>
		</ul>
	</div>
  @endif
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
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/cmdb/costcenter.js"></script>