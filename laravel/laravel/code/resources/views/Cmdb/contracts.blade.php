<!-- Start: Topbar -->
<header id="topbar" class="affix"  >
<div class="topbar-left">
   <?php breadcrum('Contracts'); ?>
</div>
<div class="topbar-right">
  @if(canuser('create','contract'))
	<div class="btn-group">
		<button id="timeline-toggle" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		<span class="glyphicons glyphicons-show_lines fs16"></span>
		</button>
		 <ul class="dropdown-menu pull-right" role="menu" >
			<li class="contractadd" id="contractadd" title="Contract Add">
            <a ><span title="Contract Add" class="contractadd"><?php echo trans('label.lbl_add_contract');?></span></a>
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
	<?php 
        $notupload = (isset($notupload) ? $notupload : '');
        if ($notupload = $errors->first('notupload')){
    ?>
            <div id='upload_err' class="alert alert-danger alert-dismissable" role="alert">
                <button type="button" class="close close_button" id="close_button" aria-hidden="true"  onclick="this.parentNode.remove();"><i class="fa fa-close"></i></button>
                <?php echo $notupload; ?>
            </div>
    <?php
    }
        if (session()->has('upload_success')){
    ?>
            <div id='upload_suc' class="alert alert-success alert-dismissable" role="alert">
                <button type="button" class="close close_button" id="close_button" aria-hidden="true"  onclick="this.parentNode.remove();"><i class="fa fa-close"></i></button>
                <?php echo session()->get('upload_success'); ?>
            </div>
    <?php } ?>
      <div class="alert hidden alert-dismissable" id="msg_div"></div>
    </div>
    <div class="col-md-12" id="">
    	<form method="post" name="frmdevices" id="frmdevices">  	
      		<div class="panel">
              <input type='hidden' name='active_contract_id' id='active_contract_id' value="<?php echo $contract_id; ?>" />
				<?php echo csrf_field(); ?>	
                <?php echo isset($emgridtop) ? $emgridtop : ''; ?>
                 <!--<div class="panel panel-visible" id="grid_data"></div>
               <div class="panel panel-visible col-md-12" id="contract_list"></div>-->
                <div class="panel panel-visible col-md-4" id="contract_list"></div>
                <div class="panel panel-visible col-md-8 br-l bw10 pln prn" id="contract_detail">
                </div>
        		
      		</div>
      	</form>
    </div>
  <!--  <div class="col-md-12">
        <div class="panel panel-visible col-md-12 br-l bw10 pln prn hidden" id="contract_detail"></div>
    </div>-->
  </div>
</div>
</div>
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/common.js"></script> 
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/cmdb/contracts.js"></script>
