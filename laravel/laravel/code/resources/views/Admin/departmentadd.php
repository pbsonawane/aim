<div class="row">
<div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div>
    <div class="col-md-12">
    <div class="panel">
        <div class="panel-body">
            <form class="form-horizontal"  name="addformdepartment" id="addformdepartment">
                <input id="department_id" name="department_id" type="hidden" value="<?php echo $department_id ?>">
                    <div class="form-group required ">
                            <label for="inputStandard" class="col-md-3 control-label">Department Name</label>
                            <div class="col-md-8">
                                <input type="text" id="department_name" name="department_name" class="form-control input-sm"  value="<?php if(isset($departmentdata[0]['department_name'])) echo $departmentdata[0]['department_name'];?>">
                            </div>
                    </div>
                    <div class="form-group">
                    <label class="col-md-3 control-label"></label>
                        <div class="col-xs-2">
                
                            <?php if($department_id != '') {?>
                                <button id="departmenteditsubmit" type="button" class="btn btn-success btn-block">Update</button>
                                <?php }else{?>
                                <button id="departmentaddsubmit" type="button" class="btn btn-success btn-block">Submit</button>
                                <?php } ?>
                        </div>
                        <div class="col-xs-2">
                            <button id="department_reset"  type="button" class="btn btn-info btn-block">Reset</button>
                        </div>
                </div>
            </form>
        </div>
    </div>  
</div>
