<div class="col-md-10">
    <div class="hidden alert-dismissable" id="msg_popup"></div>
</div>
<div class="col-md-12">
	<div class="panel">
		<div class="panel-body">
			<form class="form-horizontal"  name="frmdc" id="frmdc">
				<input id="dc_id" name="dc_id" type="hidden" value="<?php echo $dc_id?>">
					<div class="form-group required ">
	                        <label for="inputStandard" class="col-md-4 control-label">Datacenter Name</label>
	                        <div class="col-md-8">
	                            <input type="text" id="dc_name" name="dc_name" class="form-control input-sm" value="<?php echo isset($dcdata[0]['dc_name']) ? $dcdata[0]['dc_name'] : ''; ?>">
	                        </div>
					</div>

					<div class="form-group required">
						<label for="Description" class="col-md-4 control-label">Region</label>
						<div class="col-md-8">
							<select class="form-control input-sm" <?php if($dc_id != ''){echo "disabled='disabled'";}?> name="region_id" id="region_id">
								<option value="">-Region-</option>
								<?php 
									if(is_array($regions) && count($regions)>0)
									{
										foreach($regions as $region)
										{
											$curegion_id = isset($dcdata[0]['region_id']) ? $dcdata[0]['region_id'] : '';
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
						<label for="Description" class="col-md-4 control-label">Description</label>
						<div class="col-md-8">
							<textarea id="dc_description" name="dc_description" class="form-control input-sm"><?php echo isset($dcdata[0]['dc_description']) ? $dcdata[0]['dc_description'] : ''; ?></textarea>
						</div>
					</div>
					
						
					<div class="form-group">
					<label class="col-md-4 control-label"></label>
						<div class="col-xs-2">
							<?php if($dc_id != '') {?>
								<button id="dceditsubmit" type="button" class="btn btn-success btn-block">Update</button>
							<?php }else{?>
								<button id="dcaddsubmit" type="button" class="btn btn-success btn-block">Submit</button>
							<?php } ?>
						</div>
						<div class="col-xs-2">
							<?php 
								if($dc_id != '')
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