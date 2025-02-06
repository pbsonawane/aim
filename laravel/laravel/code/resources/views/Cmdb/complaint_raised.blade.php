

<!-- Start: Topbar -->
<header id="topbar" class="affix"  >
    <div class="topbar-left">
       <?php breadcrum('Complaint Raised'); ?>
   </div>
   <div class="topbar-right">
      <div class="btn-group">
          <button id="timeline-toggle" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
              <span class="glyphicons glyphicons-show_lines fs16"></span>
          </button>
          <ul class="dropdown-menu pull-right" role="menu">
            
             <li id="pradd" title="Add Complaint Raised">
                <a><span title="Add Complaint Raised">
                   Add Complaint Raised</span></a>                
            </li> 
           
        </ul>
    </div>
</div>
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
    	<form method="prst" name="frmdevices" id="frmdevices">  	
            <div class="panel">             
                <?php echo csrf_field(); ?>
                <?php echo isset($emgridtop) ? $emgridtop : ''; ?>                  
                <div class="panel panel-visible col-md-3 pl0 pr0" id="cr_list"></div>
                <div class="panel panel-visible col-md-9 br-l bw1 pln prn" id="pr_detail">
                </div>
                
            </div>
        </form>
    </div>
</div>
</div>
</div>
<style>
  .error{
    color:red;
  }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>
<script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>

<script language="javascript" type="text/javascript" src="enlight/scripts/common.js"></script> 
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/dropzone/downloads/dropzone.min.js"></script>
<script language="javascript" type="text/javascript" src="enlight/scripts/cmdb/crs.js?<?php echo time();?>"></script>
<script type="text/javascript">
$('#browseFile').show();
</script>
<style>
    textarea{
        resize: vertical;
    }
</style>    