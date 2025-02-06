        <div class="row">
    <div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div>
        <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">
    	<form id="attach" name ="attach"  method="POST" action="">
    		 <input type="hidden" name="software_id" id="software_id" value="<?php echo $software_id; ?>">
    	
        <div class="form-group col-md-6 " >
                    <label for="email" class="col-md-4 control-label"><?php echo trans('label.lbl_bv'); ?></label>
					<div class="col-md-8">

                        <select class="form-control input-sm" name="bv_id" id="bv_id" onchange="assetSelect('in_store')">
								<option value="">-<?php echo trans('label.lbl_bv'); ?>-</option>

								<?php
if (is_array($bvs) && count($bvs) > 0)
{
    foreach ($bvs as $bv)
    {
        $bv_id = isset($bvsdata[0]['bv_id']) ? $bvsdata[0]['bv_id'] : '';
        ?>
										<option value="<?php echo $bv['bv_id'] ?>" <?php if ($bv_id == $bv['bv_id'])
        {
            echo "selected";}?> > <?php echo $bv['bv_name'] ?> </option>
            <!--<option value="13883632-2b95-11e9-9038-0242ac110004"> BV1 </option>-->
								<?php
}
}
?>
							</select>
					</div>
                    </div>
        <div class="form-group col-md-6 " >
                    <label for="email" class="col-md-4 control-label"><?php echo trans('label.lbl_location'); ?></label>
					<div class="col-md-8">

                        <select class="form-control input-sm" name="location_id" id="location_id" onchange="assetSelect('in_store')" >
								<option value="">-<?php echo trans('label.lbl_location'); ?>-</option>

								<?php
if (is_array($locations) && count($locations) > 0)
{
    foreach ($locations as $location)
    {
        $locationdata_id = isset($locationdata[0]['location_id']) ? $locationdata[0]['location_id'] : '';
        ?>
										<option value="<?php echo $location['location_id'] ?>" <?php if ($locationdata_id == $location['location_id'])
        {
            echo "selected";}?> > <?php echo $location['location_name'] ?> </option>
            <!--<option value="0584834a-2b97-11e9-bc8c-0242ac110004"> loc </option>-->
								<?php
}
}
?>
							</select>
					</div>
                    </div>
        <div class="form-group col-md-6 required" >
			<label for="email" class="col-md-4 control-label"><?php echo trans('label.lbl_selectcitypes'); ?></label>
			<div class="col-md-8">
            <?php $devices = trans('commonarr.devices');?>

            <select name="variable_name" id="variable_name" onchange="assetSelect('in_store')"  class="chosen-select">
            <!--<select name="variable_name" id="variable_name" onchange="assetSelect(this.value,'in_use')"  class="chosen-select">-->
						<option value=""><?php echo trans('label.drop_select'); ?></option>-->
                	<?php
if (is_array($devices) && count($devices) > 0)
{
    foreach ($devices as $key => $value)
    {
        ?>
                                		<option value="<?php echo $key; ?>" <?php echo isset($softwaredata[0]['ci_type']) && $softwaredata[0]['ci_type'] == $key ? 'selected="selected"' : ''; ?>><?php echo $value; ?></option>
                                <?php
}
}
?>
                	?>
                </select>
			</div>
		</div>
		<div class="form-group col-md-12">
			<div class="form-group col-md-5">
				<label for="email" class="col-md-5 control-label"><?php echo trans('label.lbl_assets'); ?></label>
				<select id="multiassetids" size="15" name="multiassetids[]"  multiple="multiple" class="form-control medwidth">
					<option value=""><?php echo trans('label.drop_select'); ?></option>
				</select>
			</div>
			<div class="form-group col-md-1">
				<div class="col-md-1" style="margin-top: 150%">
					<a href="javascript:void(0);" id="hideoption"><img src="<?php config('app.site_url')?>/enlight/images/right_arr.png"></a>
					<br>
					<br>
					<br>
					<br>

					<a href="javascript:void(0);"  id="showoption"><img src="<?php config('app.site_url')?>/enlight/images/left_arr.png"></a>
				</div>
			</div>
			<div class="form-group col-md-5">
				<label for="email" class="col-md-5 control-label"><?php echo trans('label.lbl_selected_asset'); ?></label>
				<select id="selectassetids" size="15" name="selectassetids[]" multiple="multiple" class="form-control medwidth">
					<option value=""><?php echo trans('label.drop_select'); ?></option>
				</select>
		</div>

		</div>
		</form>
		<div class="form-group col-md-12">
		<label class="col-md-4 control-label"></label>
				<div class="col-xs-2">
		                <button id="attchasset" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_submit'); ?></button>
				</div>
				<div class="col-xs-2">
					<button id=""  type="button" class="btn btn-info btn-block"><?php echo trans('label.btn_reset'); ?></button>
				</div>
		</div>

    </div>

    <script>
//var sid ="<?php //echo $s_id;?>";
</script>
