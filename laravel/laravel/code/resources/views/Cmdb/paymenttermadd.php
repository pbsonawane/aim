<div class="row">
    <div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">

                <form class="form-horizontal"  name="addformpaymentterm" id="addformpaymentterm">
                    <input id="paymentterm_id" name="paymentterm_id" type="hidden" value="<?php echo $paymentterm_id ?>">

                    <?php //print_r($paymenttermdata[0]);die;?>
                        <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_payment_term'); ?></label>
                                <div class="col-md-8">
                                    <input type="text" id="payment_term" name="payment_term" class="form-control input-sm" value="<?php if (isset($paymenttermdata[0]['payment_term'])) { echo $paymenttermdata[0]['payment_term']; } ?>">
                                </div>
                        </div>

                        <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                            <div class="col-xs-2">

                                <?php if ($paymentterm_id != '') {?>
                                <button id="paymenttermeditsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_update'); ?></button>
                                <?php } else {?>
                                <button id="paymenttermaddsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_submit'); ?></button>
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