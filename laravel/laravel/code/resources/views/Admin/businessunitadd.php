<div class="row">
    <div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">
               
                <form class="form-horizontal"  name="addformbusinessunit" id="addformbusinessunit">
                    <input id="bu_id" name="bu_id" type="hidden" value="<?php echo $bu_id?>">
                        <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label">Business Unit Name</label>
                                <div class="col-md-8">
                                    <input type="text" id="bu_name" name="bu_name" class="form-control input-sm" value="<?php if(isset($businessunitdata[0]['bu_name'])) echo $businessunitdata[0]['bu_name'];?>">
                                </div>
                        </div>
                            <div class="form-group required">
                                    <label for="Description" class="col-md-3 control-label">Description</label>
                                    <div class="col-md-8">
                                        <textarea id="bu_description" name="bu_description" class="form-control input-sm" ><?php if(isset($businessunitdata[0]['bu_description'])) echo $businessunitdata[0]['bu_description'];?></textarea>
                                </div>
                        </div>
                        
                            
                        <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                            <div class="col-xs-2">
                        
                                <?php if($bu_id != '') {?>
                                <button id="businessuniteditsubmit" type="button" class="btn btn-success btn-block">Update</button>
                                <?php }else{?>
                                <button id="businessunitaddsubmit" type="button" class="btn btn-success btn-block">Submit</button>
                                <?php } ?>
                            </div>
                            <div class="col-xs-2">
                                <button id="businessunit_reset" type="button" class="btn btn-info btn-block">Reset</button>
                            </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 