<!-- Start: Topbar -->
<header id="topbar" class="affix"  >
<div class="topbar-left">
	<?php breadcrum('title.purchaseorder'); ?>
</div>
<!-- @if(canuser('create','purchaseorder'))
<div class="topbar-right">
	<div class="btn-group">
		<button id="timeline-toggle" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		<span class="glyphicons glyphicons-show_lines fs16"></span>
		</button>
		<ul class="dropdown-menu pull-right" role="menu">
			<li id="pocreate" title="@lang('label.btn_createpo')">
        <a><span title="@lang('label.btn_createpo')">@lang('label.btn_createpo')</span></a>
			</li>
		</ul>
	</div>
</div>
@endif -->
</header>
<!-- End: Topbar -->
<div id="content">
  <div class="row">
    @if($errors->any() && is_array($errors->all()) && count($errors->all()) > 0)
        {!! implode('', $errors->all(' <div class="alert alert-dismissable alert-danger" role="alert"><button type="button" class="close close_button" id="close_button" aria-hidden="true"  onclick="this.parentNode.remove();"><i class="fa fa-close"></i></button>:message</div>')) !!}
    @endif
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
    <div class="col-md-12">
    	<form method="prst" name="frpurchase" id="frpurchase">  	
      		<div class="panel">
                <input type='hidden' name='active_po_id' id='active_po_id' value="<?php echo $po_id; ?>" />
                <input type='hidden' name='pr_type' id='pr_type' value="<?php echo $pr_type; ?>" />
                <input type='hidden' name='show_single' id='show_single' value="<?php echo $show_single; ?>" />
                <?php echo csrf_field(); ?>	
                <?php echo isset($emgridtop) ? $emgridtop : ''; ?>			      
                <div class="panel panel-visible col-md-3 pl0 pr0" id="po_list"></div>
                <div class="panel panel-visible col-md-9 br-l bw1 pln prn" id="po_detail"><?php //echo view('Cmdb/purchaseorderdetail'); ?></div>
                
      		</div>
      	</form>
    </div>
  </div>
</div>
</div>
<script type="text/javascript">
    var view_asset_permission = "<?php echo canuser('view','asset');?>";
</script>
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/common.js"></script> 
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/cmdb/pos.js"></script>
<script type="text/javascript">
    $(document).on('click','#attachmentbtn', function(){
                if( document.getElementById("uploadFile").files.length == 0 ){
                    alert(trans('messages.msg_nofilesattached'));
                    return false;
                }
            });
    
</script>
<style>
  textarea{
    resize: vertical;
  }
</style>