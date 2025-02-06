<div class="row">
    <div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">
               
                <form class="form-horizontal"  name="addformcontracttype" id="addformcontracttype">
                    <input id="contract_type_id" name="contract_type_id" type="hidden" value="<?php echo $contract_type_id?>">
                    <?php //print_r($contracttypedata);?>
                        <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_contract_type');?></label>
                                <div class="col-md-8">
                                    <input type="text" id="contract_type" name="contract_type" class="form-control input-sm" value="<?php if(isset($contracttypedata[0]['contract_type'])) echo $contracttypedata[0]['contract_type'];?>">
                                </div>
                        </div>
                            <div class="form-group required">
                                    <label for="Description" class="col-md-3 control-label"><?php echo trans('label.lbl_description');?></label>
                                    <div class="col-md-8">
                                        <textarea id="contract_description" name="contract_description" class="form-control input-sm" ><?php if(isset($contracttypedata[0]['contract_description'])) echo $contracttypedata[0]['contract_description'];?></textarea>
                                </div>
                        </div>
                        
                            
                        <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                            <div class="col-xs-2">
                        
                                <?php if($contract_type_id != '') {?>
                                <button id="contracttypeeditsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_update');?></button>
                                <?php }else{?>
                                <button id="contracttypeaddsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_submit');?></button>
                                <?php } ?>
                            </div>
                            <div class="col-xs-2">
                                <button id="contract_reset" type="reset" class="btn btn-info btn-block"><?php echo trans('label.btn_reset');?></button>
                            </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 