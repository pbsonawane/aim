<div class="row">
    <div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">

                <form class="form-horizontal"  name="addformbillto" id="addformbillto">
                    <input id="billto_id" name="billto_id" type="hidden" value="<?php echo $billto_id ?>">
                    <div class="form-group required">
                            <label for="Description" class="col-md-3 control-label"><?php echo trans('label.lbl_location'); ?></label>
                            <div class="col-md-6">
                             <select class="form-control input-sm" name="locations" id="locations">
                                <option value="">-<?php echo trans('label.lbl_location'); ?>-</option>
                                <?php
                                    if (is_array($locations) && count($locations) > 0) {
                                    foreach ($locations as $location) {
                                        ?>
                                        <option value="<?php echo $location['location_id'] ?>" <?php if (isset($billtodata[0]['locations']) && $billtodata[0]['locations'] == $location['location_id']) {echo "selected";}?> > <?php echo $location['location_name'] ?> </option>
                                <?php
                                    }
                                }
                            ?>
                            </select>
                         </div>
                    </div>

                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_company_name'); ?></label>
                        <div class="col-md-6">
                            <input type="text" id="lbl_company_name" name="company_name" class="form-control input-sm" value="<?php if (isset($billtodata[0]['company_name'])) { echo $billtodata[0]['company_name'];} ?>">
                        </div>
                    </div>

                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_address'); ?></label>
                        <div class="col-md-6">
                            <textarea id="address" name="address" class="form-control input-sm" ><?php if(isset($billtodata[0]['address'])) echo $billtodata[0]['address'];?></textarea>
                        </div>
                    </div>

                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_pan_no'); ?></label>
                        <div class="col-md-6">
                            <input type="text" id="lbl_pan_no" name="pan_no" class="form-control input-sm" value="<?php if (isset($billtodata[0]['pan_no'])) { echo $billtodata[0]['pan_no'];} ?>">
                        </div>
                    </div>

                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_gstn'); ?></label>
                        <div class="col-md-6">
                            <input type="text" id="lbl_gstn" name="gstn" class="form-control input-sm" value="<?php if (isset($billtodata[0]['gstn'])) { echo $billtodata[0]['gstn'];} ?>">
                        </div>
                    </div>
                        
                    <div class="form-group">
                      <label class="col-md-3 control-label"></label>
                        <div class="col-xs-2">
                            <?php if ($billto_id != '') { ?>
                            <button id="billtoeditsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_update'); ?></button>
                            <?php } else { ?>
                            <button id="billtoaddsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_submit'); ?></button>
                            <?php }?>
                        </div>
                        <div class="col-xs-2">
                            <button  <?php if ($billto_id != '') {?> id ="update_reset" <?php } else {?> id="reset" <?php }?> type="reset" class="btn btn-info btn-block"><?php echo trans('label.btn_reset'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>