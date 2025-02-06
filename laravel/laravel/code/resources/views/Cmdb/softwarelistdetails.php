<!-- Start: Topbar -->
<header id="topbar" class="affix"  >
<div class="topbar-left">
  <!--  <?php //breadcrum(trans('title.softwaredetail')); ?> -->
</div>
<div class="topbar-right">

<?php if(canuser('create','software')){ ?>
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
<?php }?>
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
        <input type='hidden' name='software_id' id='software_id' value="<?php echo $s_id; ?>" />       
                <?php echo csrf_field(); ?>

                <input type="hidden" name="searchkeyword" value="">
                <!--<div class="panel panel-visible col-md-4" id="software_list"></div>-->
                <div class="panel panel-visible col-md-12 br-l bw10 pln prn" id="software_detail"></div>
                
      		</div>
             
      	</form>
    </div>
  </div>

</div>
</div>
</div>


<script>
var sid ="<?php echo $s_id;?>";
</script>
<script language="javascript" type="text/javascript" src="<?php config('app.site_url')?>/enlight/scripts/common.js"></script> 

<script language="javascript" type="text/javascript" src="<?php config('app.site_url')?>/enlight/scripts/cmdb/software.js"></script>

<script language="javascript" type="text/javascript" src="<?php config('app.site_url')?>/enlight/scripts/cmdb/softwarelist.js"></script>
<!--<script language="javascript" type="text/javascript" src="<?php config('app.site_url')?>/enlight/scripts/cmdb/softwaredashboard.js"></script>-->


<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/datatables/media/js/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/datatables/extensions/TableTools/js/dataTables.tableTools.min.js"></script>


  


