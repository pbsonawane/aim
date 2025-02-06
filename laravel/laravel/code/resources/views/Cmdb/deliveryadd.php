<div class="row">
    <div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">

                <form class="form-horizontal"  name="addformdelivery" id="addformdelivery">
                    <input id="delivery_id" name="delivery_id" type="hidden" value="<?php echo $delivery_id ?>">

                    <?php //print_r($deliverydata[0]);die;?>
                        <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_delivery'); ?></label>
                                <div class="col-md-8">
                                    <input type="text" id="delivery" name="delivery" class="form-control input-sm" value="<?php if (isset($deliverydata[0]['delivery'])) { echo $deliverydata[0]['delivery']; } ?>">
                                </div>
                        </div>

                        <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                            <div class="col-xs-2">

                                <?php if ($delivery_id != '') {?>
                                <button id="deliveryeditsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_update'); ?></button>
                                <?php } else {?>
                                <button id="deliveryaddsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_submit'); ?></button>
                                <?php }?>
                            </div>
                            <div class="col-xs-2">
                                <button id="" type="reset" class="btn btn-info btn-block"><?php echo trans('label.btn_reset'); ?></button>
                            </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>