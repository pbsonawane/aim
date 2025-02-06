<div class="row">
    <div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">

                <form class="form-horizontal"  name="addformcc" id="addformcc">
                    <input id="cc_id" name="cc_id" type="hidden" value="<?php echo $cc_id ?>">
                    
                    <?php //print_r($costcenterdata[0]);die;?>
                        <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_cc_code'); ?></label>
                                <div class="col-md-8">
                                    <input type="text" id="lbl_cc_code" name="cc_code" class="form-control input-sm" value="<?php if (isset($costcenterdata[0]['cc_code']))
{
    echo $costcenterdata[0]['cc_code'];
}
?>">
                                </div>
                        </div>
                        <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_cc_name'); ?></label>
                                <div class="col-md-8">
                                    <input type="text" id="cc_name" name="cc_name" class="form-control input-sm" value="<?php if (isset($costcenterdata[0]['cc_name']))
{
    echo $costcenterdata[0]['cc_name'];
}
?>">
                                </div>
                        </div>
                        <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_description'); ?></label>
                                <div class="col-md-8">
                                    <input type="text" id="description" name="description" class="form-control input-sm" value="<?php if (isset($costcenterdata[0]['description']))
{
    echo $costcenterdata[0]['description'];
}
?>">
                                </div>
                        </div>
                        <div class="form-group required">
					<label for="Description" class="col-md-3 control-label"><?php echo trans('label.lbl_location'); ?></label>
					<div class="col-md-6">

                        <select class="form-control input-sm" name="locations" id="locations">
								<option value="">-<?php echo trans('label.lbl_location'); ?>-</option>

								<?php
if (is_array($locations) && count($locations) > 0)
{
    foreach ($locations as $location)
    {
        //$locationdata_id = isset($costcenterdata[0]['location_id']) ? $costcenterdata[0]['location_id'] : '';
        ?>
										<option value="<?php echo $location['location_id'] ?>" <?php if(isset($costcenterdata[0]['locations']) && $costcenterdata[0]['locations'] == $location['location_id']){ echo "selected"; }?> > <?php echo $location['location_name'] ?> </option>
								<?php
}
}
?>
							</select>
					</div>
                    </div>

                    <div class="form-group required">
					<label for="Description" class="col-md-3 control-label"><?php echo trans('label.lbl_department'); ?></label>
					<div class="col-md-8">

                        <select class="chosen-select form-control input-sm" name="departments[]" id="departments" multiple="multiple">
								<?php /*?><option value="">-<?php echo trans('label.lbl_department'); ?>-</option><?php */?>

								<?php
if (is_array($depts) && count($depts) > 0)
{
    foreach ($depts as $dept)
    {
        $deptdata_ids = isset($costcenterdata[0]['departments']) ? json_decode($costcenterdata[0]['departments']) : array();
        ?>
										<option value="<?php echo $dept['department_id'] ?>" <?php if (in_array($dept['department_id'],$deptdata_ids))
        {
            echo "selected";}?> > <?php echo $dept['department_name'] ?> </option>
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

                                <?php if ($cc_id != '')
{
    ?>
                                <button id="costcentereditsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_update'); ?></button>
                                <?php }
else
{
    ?>
                                <button id="costcenteraddsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_submit'); ?></button>
                                <?php }?>
                            </div>
                            <div class="col-xs-2">
                                <button  <?php if ($cc_id != '') { ?> id ="update_reset" <?php }else{ ?> id="reset" <?php } ?> type="reset" class="btn btn-info btn-block"><?php echo trans('label.btn_reset'); ?></button>
                            </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>