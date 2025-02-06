<div class="row">
    <div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">
               
                <form class="form-horizontal"  name="addformsoftwaremanufacturer" id="addformsoftwaremanufacturer">
                    <input id="software_manufacturer_id" name="software_manufacturer_id" type="hidden" value="<?php echo $software_manufacturer_id?>">
                    <?php //print_r($softwaremanufacturerdata);?>
                        <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_software_manufacturer');?></label>
                                <div class="col-md-8">
                                    <input type="text" id="software_manufacturer" name="software_manufacturer" class="form-control input-sm" value="<?php if(isset($softwaremanufacturerdata[0]['software_manufacturer'])) echo $softwaremanufacturerdata[0]['software_manufacturer'];?>">
                                </div>
                        </div>
                            <div class="form-group required">
                                    <label for="Description" class="col-md-3 control-label"><?php echo trans('label.lbl_description');?></label>
                                    <div class="col-md-8">
                                        <textarea id="description" name="description" class="form-control input-sm" ><?php if(isset($softwaremanufacturerdata[0]['description'])) echo $softwaremanufacturerdata[0]['description'];?></textarea>
                                </div>
                        </div>
                        
                            
                        <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                            <div class="col-xs-2">
                        
                                <?php if($software_manufacturer_id != '') {?>
                                <button id="softwaremanufacturereditsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_update');?></button>
                                <?php }else{?>
                                <button id="softwaremanufactureraddsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_submit');?></button>
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