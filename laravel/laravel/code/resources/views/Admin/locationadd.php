<div class="col-md-10">
    <div class="hidden alert-dismissable" id="msg_popup"></div>
</div>
<div class="col-md-12">
	<div class="panel">
		<div class="panel-body">
			<form class="form-horizontal"  name="frmloc" id="frmloc">
				<input id="location_id" name="location_id" type="hidden" value="<?php echo $loc_id?>">
					<div class="form-group required ">
	                        <label for="inputStandard" class="col-md-3 control-label">Location Name</label>
	                        <div class="col-md-8">
	                            <input type="text" id="location_name" name="location_name" class="form-control input-sm" value="<?php echo isset($locdata[0]['location_name']) ? $locdata[0]['location_name'] : ''; ?>">
	                        </div>
					</div>

					<div class="form-group required">
						<label for="Description" class="col-md-3 control-label">Region</label>
						<div class="col-md-8">
							<select class="form-control input-sm" name="region_id" id="region_id">
								<option value="">-Region-</option>
								<?php 
									if(is_array($regions) && count($regions)>0)
									{
										foreach($regions as $region)
										{
											$curegion_id = isset($locdata[0]['region_id']) ? $locdata[0]['region_id'] : '';
								?>
										<option value="<?php echo $region['region_id'] ?>" <?php if($curegion_id == $region['region_id']){echo "selected";} ?> > <?php echo $region['region_name'] ?> </option>
								<?php
										}
									}	
								?>
							</select>				
						</div>
					</div>
					<div class="form-group required">
						<label for="Description" class="col-md-3 control-label">Description</label>
						<div class="col-md-8">
							<textarea id="location_description" name="location_description" class="form-control input-sm"><?php echo isset($locdata[0]['location_description']) ? $locdata[0]['location_description'] : ''; ?></textarea>
						</div>
					</div>
					
						
					<div class="form-group">
					<label class="col-md-3 control-label"></label>
						<div class="col-xs-2">
							<?php if($loc_id != '') {?>
								<button id="loceditsubmit" type="button" class="btn btn-success btn-block">Update</button>
							<?php }else{?>
								<button id="locaddsubmit" type="button" class="btn btn-success btn-block">Submit</button>
							<?php } ?>
						</div>
						<div class="col-xs-2">
							<?php 
								if($loc_id != '')
									$reid = "editreset";
								else
									$reid = "addreset";
							 ?>		
							<button id="<?php echo $reid; ?>" type="button" class="btn btn-info btn-block">Reset</button>
						</div>
					</div>
			</form>
		</div>
	</div>
</div>	