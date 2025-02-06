<div class="row">
    <div class="col-md-10">
        <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">
                <form class="form-horizontal"  name="addformreportcategory" id="addformreportcategory">
                    <input id="report_cat_id" name="report_cat_id" type="hidden" value="<?php echo $report_cat_id?>">
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_report_category');?></label>
                        <div class="col-md-8">
                        <input type="text" id="report_category" name="report_category" class="form-control input-sm" value="<?php if(isset($reportcategorydata[0]['report_category'])) echo $reportcategorydata[0]['report_category'];?>">
                        </div>
                    </div>
                    <div class="form-group required">
                        <label for="Description" class="col-md-3 control-label"><?php echo trans('label.lbl_description');?></label>
                        <div class="col-md-8">
                            <textarea id="description" name="description" class="form-control input-sm" ><?php if(isset($reportcategorydata[0]['description'])) echo $reportcategorydata[0]['description'];?></textarea>
                        </div>
                    </div> 
                    <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                        <div class="col-xs-2">
                            <?php if($report_cat_id != '') {?>
                            <button id="reportcategoryeditsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_update');?></button>
                            <?php }else{?>
                            <button id="reportcategoryaddsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_submit');?></button>
                            <?php } ?>
                        </div>
                        <div class="col-xs-2">
                            <button id="reportcategory_reset" type="reset" class="btn btn-info btn-block"><?php echo trans('label.btn_reset');?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 