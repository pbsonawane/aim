<!-- Start: Topbar -->
<header id="topbar" class="affix"  >
<div class="topbar-left">
   <?php breadcrum(trans('title.vendor')); ?> 
</div>
<div class="topbar-right">
  <button id="export_excel" class="btn btn-default btn-sm" type="button"><i class="fa fa-file" aria-hidden="true"></i>&nbsp;<?php echo trans('label.lbl_export_excel');?></button>
</div>
<style>
  .error{
    color:red;
  }
</style>
</header>
<!-- End: Topbar -->
<div id="content">
  <div class="row">
    <div class="col-md-12">
      <form type="hidden" class="save_dbtbl_prm" id="save_dbtbl_prm" name="save_dbtbl_prm" method="post" action="{{ url('/Export_track_cr') }}">
        @csrf
          <input type="hidden" name="limit" id="save_limit">
          <input type="hidden" name="offset" id="save_offset">
          <input type="hidden" name="page" id="save_page">
          <input type="hidden" name="searchkeyword" id="save_searchkeyword">
          <input type="hidden" name="total_records" id="total_records">
          <button style="display: none;" type="submit" name="submit" id="save_submit">submit</button>
          <button style="display: none;" type="reset">reset</button>
      </form>
    </div>
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

<div id="myModal_add_remark" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <form class="form-horizontal" id="form_add_remark">
      <input type="hidden" id="pr_id" name="pr_id">
      <input type="hidden" id="remark_content" name="remark_content">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><span id="modal-title_add_remark">Add Remark</span></h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="hidden alert-dismissable" id="msg_modal_add_remark"></div>
            </div>
          </div>
           
          <div class="form-group required ">
            <label for="inputStandard" class="col-md-12 control-label textalignleft">Remark</label>
            <div class="col-md-12">
              <textarea class="col-md-12" id="add_remark" name="add_remark" maxlength="250"></textarea>
              <br><code style="float: right;">(Max 250 Characters)</code>
            </div>
          </div>
        </div> 
        <div class="modal-footer">
           <button type="button" id="submit_add_remark" class="btn btn-success"><?php echo trans('label.btn_submit'); ?></button>&nbsp;|&nbsp;
           <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo trans('label.btn_close'); ?></button>
        </div>
      </div>
    </form>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>
<script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>

<!-- Select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script language="javascript" type="text/javascript" src="enlight/scripts/common.js"></script> 

<script>
  var countRow = 0;

	$(document).ready(function () {
		trackcrList();
	});

  //on excel button hidden form submited
  $(document).on("click", "#export_excel", function () { 
    countRow = $('#total_records').val();
    if(countRow < 1) {
      alert("No data found..");
    } else {
      $('#save_submit').click();
    }
  });

	function trackcrList() {        
        $('.save_dbtbl_prm')[0].reset();
        closeMsgAuto('msg_div');
        emLoader('show', trans('messages.msg_vendor_loading'));
        var url = SITE_URL + '/trackcr/list';			
        var postData = $("#frmdevices").serialize();
       
        var mongraphsajax = ajaxCall(mongraphsajax, url, postData, function (data) {
            showResponse(data, 'grid_data', 'msg_div');
            emLoader('hide');
            resp_data = JSON.parse(data);
            countRow = resp_data.totalrecords;
            $('#total_records').val(countRow);
            if(typeof resp_data.option_param !== 'undefined') {
              var data = resp_data.option_param;
              limit = (typeof data.limit    !== 'undefined')  ? data.limit  : 0;
              page = (typeof data.page      !== 'undefined')  ? data.page   : 0 ;
              offset = (typeof data.offset) !== 'undefined'   ? data.offset : 0 ;
              searchkeyword = (typeof data.searchkeyword !== 'undefined') ? data.searchkeyword : 0 ;
              $('#save_limit').val(limit);
              $('#save_offset').val(page);
              $('#save_page').val(offset);
              $('#save_searchkeyword').val(searchkeyword);
            }
        });
    }
</script>