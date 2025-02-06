<div class="row">
    <div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">
               
                <form class="form-horizontal"  name="addformrelationshiptype" id="addformrelationshiptype">
                    <input id="rel_type_id" name="rel_type_id" type="hidden" value="<?php echo $rel_type_id?>">
                    <?php //print_r($relationshiptypedata);?>
                        <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_relationshiptype');?></label>
                                <div class="col-md-8">
                                    <input type="text" id="rel_type" name="rel_type" class="form-control input-sm" value="<?php if(isset($relationshiptypedata[0]['rel_type'])) echo $relationshiptypedata[0]['rel_type'];?>">
                                </div>
                        </div>
                        <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_inverserelationtype');?></label>
                                <div class="col-md-8">
                                    <input type="text" id="inverse_rel_type" name="inverse_rel_type" class="form-control input-sm" value="<?php if(isset($relationshiptypedata[0]['inverse_rel_type'])) echo $relationshiptypedata[0]['inverse_rel_type'];?>">
                                </div>
                        </div>
                            <div class="form-group required">
                                    <label for="Description" class="col-md-3 control-label"><?php echo trans('label.lbl_description');?></label>
                                    <div class="col-md-8">
                                        <textarea id="description" name="description" class="form-control input-sm" ><?php if(isset($relationshiptypedata[0]['description'])) echo $relationshiptypedata[0]['description'];?></textarea>
                                </div>
                        </div>
                        
                            
                        <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                            <div class="col-xs-2">
                        
                                <?php if($rel_type_id != '') {?>
                                <button id="relationshiptypeeditsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_update');?></button>
                                <?php }else{?>
                                <button id="relationshiptypeaddsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_submit');?></button>
                                <?php } ?>
                            </div>
                            <div class="col-xs-2">
                                <button id="relationship_reset" type="reset" class="btn btn-info btn-block"><?php echo trans('label.btn_reset');?></button>
                            </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 