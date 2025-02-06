<div class="row">
    <div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body"> 
                <form class="form-horizontal"  name="addformrequestername" id="addformrequestername">
                    <input id="requestername_id" name="requestername_id" type="hidden" value="<?php echo $requestername_id ?>">
                    <div class="form-group required">
                            <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_department'); ?></label>
                            <div class="col-md-4">
                             <select class="form-control input-sm" name="departments" id="departments">
                                <option value="">-<?php echo trans('label.lbl_department'); ?>-</option>
                                <?php
                                    if (is_array($departments) && count($departments) > 0) {
                                    foreach ($departments as $department) {
                                        if($department_id == $department['department_id']){


                                        ?>
                                        <option value="<?php echo $department['department_id'] ?>" <?php if (isset($requesternamedata[0]['departments']) && $requesternamedata[0]['departments'] == $department['department_id']) {echo "selected";}?> > <?php echo $department['department_name'] ?> </option>
                                <?php
                                   }
                                    }
                                }
                            ?>
                            </select>
                         </div>
                    </div>

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
                                            if (isset($requesternamedata[0]['prefix'])) {$selected_value = $requesternamedata[0]['prefix'];}
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
                            <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_requestername_fname'); ?></label>
                            <div class="col-md-4">
                                <input type="text" id="fname" name="fname" class="form-control input-sm" value="<?php if (isset($requesternamedata[0]['fname'])) {echo $requesternamedata[0]['fname'];}?>">
                            </div>
                        </div>
                        <div class="form-group required ">
                            <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_requestername_lname'); ?></label>
                            <div class="col-md-4">
                                <input type="text" id="lname" name="lname" class="form-control input-sm" value="<?php if (isset($requesternamedata[0]['lname'])) {echo $requesternamedata[0]['lname'];}?>">
                            </div>
                        </div>
                         <div class="form-group required ">
                            <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_requestername_employee_id'); ?></label>
                            <div class="col-md-4">
                                <input type="text" id="employee_id" name="employee_id" class="form-control input-sm" value="<?php if (isset($requesternamedata[0]['employee_id'])) {echo $requesternamedata[0]['employee_id'];}?>">
                            </div>
                        </div>                       
                        <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                            <div class="col-xs-2">
                                <?php if ($requestername_id != '') {?>
                                <button id="requesternameeditsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_update'); ?></button>
                                <?php } else {?>
                                <button id="requesternameaddsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_submit'); ?></button>
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