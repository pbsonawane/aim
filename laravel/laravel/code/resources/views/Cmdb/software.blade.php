

<!-- Start: Topbar -->
<header id="topbar" class="affix"  >
  
<div class="topbar-left">
   <!-- Add bread crumb here -->
    <ol class="breadcrumb">
  
         <li class="crumb-active nounderline"><a class="nounderline"><?php echo(trans('title.softwares'));?></a></li>
         <li class="crumb-link"><a href="<?php echo config('enconfig.iamapp_url'); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
       
         <li class="crumb-link"><?php echo(trans('title.itam'));?></li>
         <li class="crumb-link"><?php echo(trans('title.assetmanagement'));?></li>
         <li class="crumb-link"><a href="/software"><?php echo(trans('title.softwares'));?></a></li>
        
      </ol>
</div>
<div class="topbar-right">
@if(canuser('create','software'))
	<div class="btn-group">
		<button id="timeline-toggle" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		<span class="glyphicons glyphicons-show_lines fs16"></span>
		</button>
        <ul class="dropdown-menu pull-right" role="menu" >
			<li class="softwareadd" id="softwareadd" title="Software Add">
            <a ><span title="Software Add" class="softwareadd"><?php echo trans('label.lbl_add_software');?></span></a>
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
    <div class="col-md-12" id="">
    	<form method="post" name="frmdevices" id="frmdevices">  	
      		<div class="panel">
				<?php echo csrf_field(); ?>	
                <?php echo isset($emgridtop) ? $emgridtop : ''; ?>
                <div class="panel panel-visible" id="grid_data"></div>	
                <!--<div class="panel panel-visible col-md-4" id="software_list"></div>
                <div class="panel panel-visible col-md-8 br-l bw10 pln prn" id="software_detail">-->
                </div>
        		
      		</div>
             
      	</form>
    </div>
  
  </div>
</div>
</div>

<script type="text/javascript">
  //location.reload(); 
  var type = id = "";  
  //var stid = '<?php echo isset($software_type_id) ? $software_type_id : "";?>';
  //var smid = '<?php echo isset($software_manufacturer_id) ? $software_manufacturer_id : "";?>';
  var type = '<?php echo isset($type) ? $type : "";?>';
  var id = '<?php echo isset($id) ? $id : "";?>';
  
</script>

<script language="javascript" type="text/javascript" src="<?php config('app.site_url')?>/enlight/scripts/common.js"></script> 
<script language="javascript" type="text/javascript" src="<?php config('app.site_url')?>/enlight/scripts/cmdb/softwarelist.js"></script>
<!--<script language="javascript" type="text/javascript" src="<?php config('app.site_url')?>/enlight/scripts/cmdb/software.js"></script>-->
  
