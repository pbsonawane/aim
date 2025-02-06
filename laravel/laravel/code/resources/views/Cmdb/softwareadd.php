<div class="row">
    <div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">
               
                <form class="form-horizontal"  name="addformsoftware" id="addformsoftware">
                    <input id="software_id" name="software_id" type="hidden" value="<?php echo $software_id?>">
                    <?php //print_r($softwaredata);?>
                        <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_software_name');?></label>
                                <div class="col-md-6">
                                    <input type="text" id="software_name" name="software_name" class="form-control input-sm" value="<?php if(isset($softwaredata[0]['software_name'])) echo $softwaredata[0]['software_name'];?>">
                                </div>
                        </div>
                        <div class="form-group required">
					<label for="Description" class="col-md-3 control-label"><?php echo trans('label.lbl_software_type'); ?></label>
					<div class="col-md-6">

                        <select class="form-control input-sm" name="software_type_id" id="software_type_id">
								<option value="">-<?php echo trans('label.lbl_software_type'); ?>-</option>
						        <?php
                                
if (is_array($softwaretypes) && count($softwaretypes) > 0)
{
    foreach ($softwaretypes as $softwaretype)
    {
        $stid = isset($softwaredata[0]['software_type_id']) ? $softwaredata[0]['software_type_id'] : '';
        ?>
										<option value="<?php echo $softwaretype['software_type_id'] ?>" <?php if ($stid == $softwaretype['software_type_id'])
        {
            echo "selected";}?> > <?php echo $softwaretype['software_type'] ?> </option>
								<?php
}
}
?>
							</select>
					</div>
                </div>
                <div class="form-group required">
					<label for="Description" class="col-md-3 control-label"><?php echo trans('label.lbl_software_category'); ?></label>
					<div class="col-md-6">

                        <select class="form-control input-sm" name="software_category_id" id="software_category_id">
								<option value="">-<?php echo trans('label.lbl_software_category'); ?>-</option>
						        <?php
if (is_array($softwarecategorys) && count($softwarecategorys) > 0)
{
    foreach ($softwarecategorys as $softwarecategory)
    {
        $stid = isset($softwaredata[0]['software_category_id']) ? $softwaredata[0]['software_category_id'] : '';
        ?>
										<option value="<?php echo $softwarecategory['software_category_id'] ?>" <?php if ($stid == $softwarecategory['software_category_id'])
        {
            echo "selected";}?> > <?php echo $softwarecategory['software_category'] ?> </option>
								<?php
}
}
?>
							</select>
					</div>
                </div>
                <div class="form-group required">
					<label for="Description" class="col-md-3 control-label"><?php echo trans('label.lbl_software_manufacturer'); ?></label>
					<div class="col-md-6">

                        <select class="form-control input-sm" name="software_manufacturer_id" id="software_manufacturer_id">
								<option value="">-<?php echo trans('label.lbl_software_manufacturer'); ?>-</option>
						        <?php
if (is_array($softwaremanufacturers) && count($softwaremanufacturers) > 0)
{
    foreach ($softwaremanufacturers as $softwaremanufacturer)
    {
        $stid = isset($softwaredata[0]['software_manufacturer_id']) ? $softwaredata[0]['software_manufacturer_id'] : '';
        ?>
										<option value="<?php echo $softwaremanufacturer['software_manufacturer_id'] ?>" <?php if ($stid == $softwaremanufacturer['software_manufacturer_id'])
        {
            echo "selected";}?> > <?php echo $softwaremanufacturer['software_manufacturer'] ?> </option>
								<?php
}
}
?>
							</select>
					</div>
                </div>

                <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_version');?></label>
                                <div class="col-md-6">
                                    <input type="text" id="version" name="version" class="form-control input-sm" value="<?php if(isset($softwaredata[0]['version'])) echo $softwaredata[0]['version'];?>">
                                </div>
                        </div>
                        <div class="form-group required">
					<label for="Description" class="col-md-3 control-label"><?php echo trans('label.lbl_ci_type'); ?></label>
					<div class="col-md-6">
                    <?php $ci_types = trans('commonarr.ci_types'); ?>
                    <select name="ci_type" id="ci_type" class="form-control input-sm">
                                <option value="">-Select-</option>
                                <?php
									if (is_array($ci_types) && count($ci_types) > 0)
									{
										foreach($ci_types as $key => $value)
										{
                                ?>
                                		<option value="<?php echo $key;?>" <?php echo isset($softwaredata[0]['ci_type']) && $softwaredata[0]['ci_type'] == $key ? 'selected="selected"' : ''; ?>><?php echo $value;?></option>
                                <?php
										}
									}
                                ?>
                            </select>
                            
					</div>
                    </div>
                            <div class="form-group required">
                                    <label for="Description" class="col-md-3 control-label"><?php echo trans('label.lbl_description');?></label>
                                    <div class="col-md-6">
                                        <textarea id="description" name="description" class="form-control input-sm" ><?php if(isset($softwaredata[0]['description'])) echo $softwaredata[0]['description'];?></textarea>
                                </div>
                        </div>
                        
                            
                        <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                            <div class="col-xs-2">
                        
                                <?php if($software_id != '') {?>
                                <button id="softwareeditsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_update');?></button>
                                <?php }else{?>
                                <button id="softwareaddsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_submit');?></button>
                                <?php } ?>
                            </div>
                            <div class="col-xs-2">
                                <button id="" type="reset" class="btn btn-info btn-block"><?php echo trans('label.btn_reset');?></button>
                            </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 