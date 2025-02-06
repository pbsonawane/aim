<div class="row">
    <div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">
               
                <form class="form-horizontal"  name="addformsoftwarecategory" id="addformsoftwarecategory">
                    <input id="software_category_id" name="software_category_id" type="hidden" value="<?php echo $software_category_id?>">
                    <?php //print_r($softwarecategorydata);?>
                        <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_software_category');?></label>
                                <div class="col-md-8">
                                    <input type="text" id="software_category" name="software_category" class="form-control input-sm" value="<?php if(isset($softwarecategorydata[0]['software_category'])) echo $softwarecategorydata[0]['software_category'];?>">
                                </div>
                        </div>
                            <div class="form-group required">
                                    <label for="Description" class="col-md-3 control-label"><?php echo trans('label.lbl_description');?></label>
                                    <div class="col-md-8">
                                        <textarea id="description" name="description" class="form-control input-sm" ><?php if(isset($softwarecategorydata[0]['description'])) echo $softwarecategorydata[0]['description'];?></textarea>
                                </div>
                        </div>
                        
                            
                        <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                            <div class="col-xs-2">
                        
                                <?php if($software_category_id != '') {?>
                                <button id="softwarecategoryeditsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_update');?></button>
                                <?php }else{?>
                                <button id="softwarecategoryaddsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_submit');?></button>
                                <?php } ?>
                            </div>
                            <div class="col-xs-2">
                                <button id="" type="reset" class="btn btn-info btn-block"><?php echo trans('label.btn_reset');?></button>
                            </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 