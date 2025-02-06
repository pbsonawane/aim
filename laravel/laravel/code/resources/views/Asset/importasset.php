<div class="col-md-10">
    <div class="hidden alert-dismissable" id="msg_popup"></div>
</div>
<div class="panel">
      <div class="panel-body">
        <div id='frmimport_alert'></div>
        <form id="frmimport" name="frmimport" method="post" action="/importfile" enctype="multipart/form-data">
          <input type="hidden" name="filenm" id="filenm" value="<?php echo time();?>">
          <input type="hidden" name="ci_type_id" id="ci_type_id" value="<?php echo $ci_type_id;?>">
          <input type="hidden" name="ci_templ_id" id="ci_templ_id" value="<?php echo $ci_templ_id;?>">
        <div  class="form-group required col-md-12">
            <label for="Title" class="col-md-3 control-label"><i class="fa fa-question-circle" aria-hidden="true"></i>
<?php echo trans('label.lbl_csvfile');?></label>
            <div class="col-md-8">
                <input name="file" id="file" type="file" accept=".csv"/>   
            </div>
        </div>   
    
        <div class="form-group col-md-12">
          <label class="col-md-3 control-label"></label>
            <div class="col-xs-2">
              <button id="file_upload" name ="file_upload" type="submit" class="btn btn-success btn-block"><?php echo trans('label.btn_submit');?></button>
            </div>
            <div class="col-xs-2">
              <button onclick="importasset()" type="button" class="btn btn-info btn-block"><?php echo trans('label.btn_reset');?></button>
            </div>
        </div>
        </form>
        <div id="importdata"></div>
     </div>        
</div>


