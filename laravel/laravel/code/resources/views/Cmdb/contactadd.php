<div class="row">
    <div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">
                <form class="form-horizontal"  name="addformcontact" id="addformcontact">
                    <input id="contact_id" name="contact_id" type="hidden" value="<?php echo $contact_id ?>">

                        <div class="form-group required ">
                            <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_prefix'); ?></label>
                            <div class="col-md-4">
                            <select id="prefix" name="prefix" class="form-control input-sm">
                                <?php
                                $option = array();
                                $prefix = trans('commonarr.prefix');
                                if ($prefix) {
                                    if (is_array($prefix) && count($prefix) > 0) {
                                        foreach ($prefix as $key => $value) {
                                            $selected = $selected_value = '';
                                            if (isset($contactdata[0]['prefix'])) {$selected_value = $contactdata[0]['prefix'];}
                                            if ($selected_value == $key) {
                                                $selected = 'selected="selected"';
                                            }
                                            echo "<option value='" . $key . "' " . $selected . ">" . $value . "</option>";
                                        }
                                    }
                                }
                                ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group required">
                            <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_contact_fname'); ?></label>
                            <div class="col-md-4">
                                <input type="text" id="fname" name="fname" class="form-control input-sm" value="<?php if (isset($contactdata[0]['fname'])) {echo $contactdata[0]['fname'];}?>">
                            </div>
                        </div>
                        <div class="form-group required ">
                            <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_contact_lname'); ?></label>
                            <div class="col-md-4">
                                <input type="text" id="lname" name="lname" class="form-control input-sm" value="<?php if (isset($contactdata[0]['lname'])) {echo $contactdata[0]['lname'];}?>">
                            </div>
                        </div>
                         <div class="form-group required ">
                            <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('Email'); ?></label>
                            <div class="col-md-4">
                                <input type="text" id="email" name="email" class="form-control input-sm" value="<?php if (isset($contactdata[0]['email'])) {echo $contactdata[0]['email'];}?>">
                            </div>
                        </div>
                        <div class="form-group required ">
                            <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_contact1'); ?></label>
                            <div class="col-md-4">
                                <input type="text" id="contact1" name="contact1" class="form-control input-sm" value="<?php if (isset($contactdata[0]['contact1'])) {echo $contactdata[0]['contact1'];}?>">
                            </div>
                        </div>
                        <div class="form-group ">
                            <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_contact2'); ?></label>
                            <div class="col-md-4">
                                <input type="text" id="contact2" name="contact2" class="form-control input-sm" value="<?php if (isset($contactdata[0]['contact2'])) {echo $contactdata[0]['contact2'];}?>">
                            </div>
                        </div>

                        <div class="form-group required ">
                            <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_associated_with'); ?></label>
                            <div class="col-md-4">
                            <select id="associated_with" name="associated_with" class="form-control input-sm">
                                <?php
                                $option          = array();
                                $associated_with = trans('commonarr.associated_with');
                                if ($associated_with) {
                                    if (is_array($associated_with) && count($associated_with) > 0) {
                                        foreach ($associated_with as $key => $value) {
                                            $selected = $selected_value = '';
                                            if (isset($contactdata[0]['associated_with'])) {$selected_value = $contactdata[0]['associated_with'];}
                                            if ($selected_value == $key) {
                                                $selected = 'selected="selected"';
                                            }
                                            echo "<option value='" . $key . "' " . $selected . ">" . $value . "</option>";
                                        }
                                    }
                                }
                                ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                            <div class="col-xs-2">
                                <?php if ($contact_id != '') {?>
                                <button id="contacteditsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_update'); ?></button>
                                <?php } else {?>
                                <button id="contactaddsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_submit'); ?></button>
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