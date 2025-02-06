<div class="row">
    <div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">
               
                <form class="form-horizontal"  name="addformlicensetype" id="addformlicensetype">
                    <input id="license_type_id" name="license_type_id" type="hidden" value="<?php echo $license_type_id?>">
                    <?php //print_r($licensetypedata);?>
                        <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_license_type');?></label>
                                <div class="col-md-8">
                                    <input type="text" id="license_type" name="license_type" class="form-control input-sm" value="<?php if(isset($licensetypedata[0]['license_type'])) echo $licensetypedata[0]['license_type'];?>">
                                </div>
                        </div>
                       
                    <div class="form-group required">
                            <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_is_perpetual'); ?></label>
                            <div class="col-md-8">
                                <input name="is_perpetual" type="checkbox" id="is_perpetual" value="y" <?php if(isset($licensetypedata[0]['is_perpetual']) && $licensetypedata[0]['is_perpetual'] == 'y'){ echo "checked"; } ?>>
                            </div>
                    </div>
                    <div class="form-group">
                            <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_is_free'); ?></label>
                            <div class="col-md-8">
                            <input name="is_free" type="checkbox" id="is_free" value="y" <?php if(isset($licensetypedata[0]['is_free']) && $licensetypedata[0]['is_free'] == 'y'){ echo "checked"; } ?>>
                               
                            </div>
                    </div>
                    <div class="form-group required">
					<label for="Description" class="col-md-3 control-label"><?php echo trans('label.lbl_installation_allow'); ?></label>
					<div class="col-md-6">
                        <input type="hidden" id="allow" value="<?php  if(isset($licensetypedata[0]['installation_allow'])){ echo $licensetypedata[0]['installation_allow']; } ?>">
                    <?php $installation_allow = trans('commonarr.installation_allow'); ?>
                    <select name="installation_allow" id="installation_allow" class="form-control input-sm">
                                <option value="">-Select-</option>
                                <?php
									if (is_array($installation_allow) && count($installation_allow) > 0)
									{
										foreach($installation_allow as $key => $value)
										{
                                ?>
                                		<!--<option value="<?php echo $key;?>" <?php echo isset($licensetypedata[0]['installation_allow']) && $licensetypedata[0]['installation_allow'] == $key ? 'selected="selected"' : ''; ?>><?php echo $value;?></option>-->
                                        <option id="optionselect" value="<?php echo $key;?>" ><?php echo $value;?></option>


                                        


                                <?php
										}
									}
                                ?>
                            </select>
                            
					</div>
                    </div>
                            
                        <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                            <div class="col-xs-2">
                        
                                <?php if($license_type_id != '') {?>
                                <button id="licensetypeeditsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_update');?></button>
                                <?php }else{?>
                                <button id="licensetypeaddsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_submit');?></button>
                                <?php } ?>
                            </div>
                            <div class="col-xs-2">
                                <button <?php if ($license_type_id != '') { ?> id ="update_reset" <?php }else{ ?> id="btn_reset" <?php } ?> type="button" class="btn btn-info btn-block"><?php echo trans('label.btn_reset');?></button>
                            </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 