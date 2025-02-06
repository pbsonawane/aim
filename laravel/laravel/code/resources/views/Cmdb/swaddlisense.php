<div class="row">
    <div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">
               
                <form class="form-horizontal"  name="addformsoftwarelicense" id="addformsoftwarelicense">
                    <?php 
                    $stid = isset($softwarelicensedata[0]['software_manufacturer_id']) ? $softwarelicensedata[0]['software_manufacturer_id'] : '';
                    ?>
                    <input id="software_license_id" name="software_license_id" type="hidden" value="<?php echo $software_license_id?>">
                    <input id="software_manufacturer_id" name="software_manufacturer_id" type="hidden" value="<?php echo $stid?>">
                    <input id="software_id" name="software_id" type="hidden" value="<?php echo $software_id?>">
                    
                    <?php //print_r($softwarelicensedata);?>
                       
               
                <div class="form-group required">
					<label for="Description" class="col-md-3 control-label"><?php echo trans('label.lbl_software_manufacturer'); ?></label>
					<div class="col-md-6">

                        <select class="form-control input-sm" name="software_manufacturer_id" id="software_manufacturer_id" disabled="disabled">
								<option value="">-<?php echo trans('label.lbl_software_manufacturer'); ?>-</option>
						        <?php
if (is_array($softwaremanufacturers) && count($softwaremanufacturers) > 0)
{
    foreach ($softwaremanufacturers as $softwaremanufacturer)
    {
        $stid = isset($softwarelicensedata[0]['software_manufacturer_id']) ? $softwarelicensedata[0]['software_manufacturer_id'] : '';
        ?>
										<option value="<?php echo $softwaremanufacturer['software_manufacturer_id'] ?>" <?php if ($stid == $softwaremanufacturer['software_manufacturer_id'])
        {
            echo "selected";}?>  > <?php echo $softwaremanufacturer['software_manufacturer'] ?> </option>
								<?php
}
}
?>
							</select>
					</div>
                </div>

                <div class="form-group required">
					<label for="Description" class="col-md-3 control-label"><?php echo trans('label.lbl_license_type'); ?></label>
					<div class="col-md-6">
                        <select class="form-control input-sm" name="license_type_id" id="license_type_id" >
								<option value="">-<?php echo trans('label.lbl_license_type'); ?>-</option>
						        <?php
if (is_array($licensetypes) && count($licensetypes) > 0)
{
    foreach ($licensetypes as $licensetype)
    {

        $stid = isset($softwarelicensedata[0]['license_type_id']) ? $softwarelicensedata[0]['license_type_id'] : '';
        ?>
										<option  data-installation_allow="<?php echo $licensetype['installation_allow'] ?>" value="<?php echo $licensetype['license_type_id'] ?>" <?php if ($stid == $licensetype['license_type_id'])
        {
            echo "selected";}?>  name="<?php echo $licensetype['license_type'] ?>"> <?php echo $licensetype['license_type'] ?></option>


								<?php
}
}
?>
							</select>
					</div>
                </div>
                <div class="form-group required ">
                    <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_max_installation');?></label>
                    <div class="col-md-6">
                        <input type="text" id="maxinstallation" name="max_installation" class="form-control input-sm" value="<?php if(isset($softwarelicensedata[0]['max_installation'])) echo $softwarelicensedata[0]['max_installation'];?>">
                    </div>
                </div>
                <div class="form-group required">
                                    <label for="Description" class="col-md-3 control-label"><?php echo trans('label.lbl_license_key');?></label>
                                    <div class="col-md-6">
                                        <textarea id="license_key" name="license_key" class="form-control input-sm" ><?php if(isset($softwarelicensedata[0]['license_key'])) echo $softwarelicensedata[0]['license_key'];?></textarea>
                                </div>
                        </div>



                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_purchase_cost');?></label>
                        <div class="col-md-6">
                            <input type="text" id="purchase_cost" name="purchase_cost" class="form-control input-sm" value="<?php if(isset($softwarelicensedata[0]['purchase_cost'])) echo $softwarelicensedata[0]['purchase_cost'];?>">
                        </div>
                    </div>

                    <div class="form-group required">
                        <label for="Description" class="col-md-3 control-label"><?php echo trans('label.lbl_description');?></label>
                        <div class="col-md-6">
                            <textarea id="description" name="description" class="form-control input-sm" ><?php if(isset($softwarelicensedata[0]['description'])) echo $softwarelicensedata[0]['description'];?></textarea>
                        </div>
                    </div>
                 <div class="form-group required">
					<label for="Description" class="col-md-3 control-label"><?php echo trans('label.lbl_vendor'); ?></label>
					<div class="col-md-6">

                        <select class="form-control input-sm" name="vendor_id" id="vendor_id">
								<option value="">-<?php echo trans('label.lbl_vendor'); ?>-</option>
						        <?php
if (is_array($vendor) && count($vendor) > 0)
{
    foreach ($vendor as $vendors)
    {
        $stid = isset($softwarelicensedata[0]['vendor_id']) ? $softwarelicensedata[0]['vendor_id'] : '';
        ?>
										<option value="<?php echo $vendors['vendor_id'] ?>" <?php if ($stid == $vendors['vendor_id'])
        {
            echo "selected";}?> > <?php echo $vendors['vendor_name'] ?> </option>
								<?php
}
}
?>
							</select>
					</div>
                </div>


               
                             <div class="form-group required" >
                    <label for="email" class="col-md-3 control-label"><?php echo trans('label.lbl_bv'); ?></label>
					<div class="col-md-6">

                        <select class="form-control input-sm" name="bv_id" id="bv_id">
								<option value="">-<?php echo trans('label.lbl_bv'); ?>-</option>

								<?php
if (is_array($bvs) && count($bvs) > 0)
{
    foreach ($bvs as $bv)
    {
        ?>
            <option value="<?php echo $bv['bv_id']; ?>" <?php echo isset($softwarelicensedata[0]['bv_id']) && $softwarelicensedata[0]['bv_id'] == $bv['bv_id'] ? 'selected="selected"' : ''; ?>><?php echo $bv['bv_name']; ?></option>
								<?php
}
}
?>
							</select>
					</div>
                    </div>

                       <div class="form-group required" >
                    <label for="email" class="col-md-3 control-label"><?php echo trans('label.lbl_department'); ?></label>
					<div class="col-md-6">

                        <select class="form-control input-sm" name="department_id" id="department_id">
								<option value="">-<?php echo trans('label.lbl_department'); ?>-</option>

								<?php
if (is_array($department) && count($department) > 0)
{
    foreach ($department as $departments)
    {
        ?>
            <option value="<?php echo $departments['department_id']; ?>" <?php echo isset($softwarelicensedata[0]['department_id']) && $softwarelicensedata[0]['department_id'] == $departments['department_id'] ? 'selected="selected"' : ''; ?>><?php echo $departments['department_name']; ?></option>
								<?php
}
}
?>
							</select>
					</div>
                    </div>

 <div class="form-group  required" >
                    <label for="email" class="col-md-3 control-label"><?php echo trans('label.lbl_location'); ?></label>
					<div class="col-md-6">

                        <select class="form-control input-sm" name="location_id" id="location_id">
								<option value="">-<?php echo trans('label.lbl_location'); ?>-</option>

								<?php
if (is_array($locations) && count($locations) > 0)
{
    foreach ($locations as $location)
    {
       
      ?>
            <option value="<?php echo $location['location_id']; ?>" <?php echo isset($softwarelicensedata[0]['location_id']) && $softwarelicensedata[0]['location_id'] == $location['location_id'] ? 'selected="selected"' : ''; ?>><?php echo $location['location_name']; ?></option>
								<?php
}
}
?>
							</select>

                            
					</div>
                    </div>
                    <div class="form-group required">
                        <label class="col-md-3 control-label" for=""><?php echo trans('label.lbl_acquisition_date'); ?></label>
                            <div class="col-md-6">
                                <input type="text" class="form-control input-sm" name="acquisition_date" id="acquisition_date" value="<?php if (isset($softwarelicensedata[0]['acquisition_date']))
                                    {
                                        echo $softwarelicensedata[0]['acquisition_date'];
                                    }
                                    ?>">
                                </div>
                            </div>

                            <div class="form-group required">
                        <label class="col-md-3 control-label" for=""><?php echo trans('label.lbl_expiry_date'); ?></label>
                            <div class="col-md-6">
                                <input type="text" class="form-control input-sm" name="expiry_date" id="expiry_date" value="<?php if (isset($softwarelicensedata[0]['expiry_date']))
                                    {
                                        echo $softwarelicensedata[0]['expiry_date'];
                                    }
                                    ?>">
                                </div>
                            </div>

                        <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                            <div class="col-xs-2">
                        
                                <?php if($software_license_id != '') {?>
                                <button id="swaddLicenseeditsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_update');?></button>
                                <?php }else{?>
                                <button id="swaddLicensesubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_submit');?></button>
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


