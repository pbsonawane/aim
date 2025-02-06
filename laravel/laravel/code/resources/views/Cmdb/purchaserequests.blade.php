

<!-- Start: Topbar -->
<header id="topbar" class="affix"  >
    <div class="topbar-left">
       <?php breadcrum(trans('label.lbl_purchaserequest')); ?>
   </div>
   <div class="topbar-right">
      @if(canuser('create','purchaserequest') || canuser('convert','purchaserequest') || canuser('create','purchaserequestsample'))
      <div class="btn-group">
          <button id="timeline-toggle" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
              <span class="glyphicons glyphicons-show_lines fs16"></span>
          </button>
          <ul class="dropdown-menu pull-right" role="menu">
             
             <!-- <li id="praddsample" title="<?php echo trans('label.lbl_addpurchaserequest');?>">
                <a><span title="<?php echo trans('label.lbl_addpurchaserequest');?>"><?php echo trans('label.lbl_addpr');?></span></a>
            </li> -->

           

             <li id="pradd" title="<?php echo trans('label.lbl_addpurchaserequest');?>">
                <a><span title="<?php echo trans('label.lbl_addpurchaserequest');?>">
                     <?php echo trans('label.lbl_addpr');?></span></a>                
            </li> 
            @if(canuser('create','convert'))
            <!--  <li id="getsampleexport1" class="getsampleexport1" title="PR Export">
                <a href="/getsampleexport">PR Export</a>
            </li> -->
            <!-- <li id="conversionPr" title="Convert Selected PR items to One PR">
                <a><span title="Convert Selected PR items to One PR">Conversion PRs</span></a>
            </li> -->
            @endif
        </ul>
    </div>
    @endif
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
                <!--<input type="hidden" name="searchkeyword" value="">-->
                <div class="panel panel-visible col-md-3 pl0 pr0" id="pr_list"></div>
                <div class="panel panel-visible col-md-9 br-l bw1 pln prn" id="pr_detail"><?php //echo view('Cmdb/purchaseorderdetail'); ?></div>
                
            </div>
        </form>
    </div>
</div>
</div>
</div>
<script language="javascript" type="text/javascript" src="enlight/scripts/common.js"></script> 
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/dropzone/downloads/dropzone.min.js"></script>
<script language="javascript" type="text/javascript" src="enlight/scripts/cmdb/prs.js?<?php echo time();?>"></script>
<script type="text/javascript">


$(document).on('click','.getsampleexport',function(){
    alert('88');
        var win = window.open('/getsampleexport','_self');
    })

    $(document).on('click','#attachmentbtn', function(){
        if( document.getElementById("uploadFile").files.length == 0 ){
            alert(trans('messages.msg_nofilesattached'));
            return false;
        }
    });

    // $(document).on('click','#quotationattachmentbtn', function(){
    //     if( document.getElementById("pr_vendor_id").value =='' ){
    //         alert(trans('messages.msg_vendor_select'));
    //         return false;
    //     }
    //     window.lang_trans_js
    //     if( document.getElementById("quotationuploadFile").files.length == 0 ){
    //         alert(trans('messages.msg_nofilesattached'));
    //         return false;
    //     }
    // });
    $(document).on('click','#assign_pr_to_user_btn', function(){
        if( document.getElementById("pr_assign_user_id").value =='' ){
            alert('There are no user select');
            // alert(trans('messages.msg_user'));
            return false;
        } 
    });
  
</script>
<style>
    textarea{
        resize: vertical;
    }
</style>    