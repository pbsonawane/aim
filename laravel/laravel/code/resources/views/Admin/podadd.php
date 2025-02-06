<div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
</div>
<div class="col-md-12">
	<div class="panel">
		<div class="panel-body">
			<form class="form-horizontal"  name="addformpod" id="addformpod">
				<input id="pod_id" name="pod_id" type="hidden" value="<?php echo $pod_id?>">
					
					<div class="form-group required ">
							<label for="inputStandard" class="col-md-3 control-label">Region</label>
							<div class="col-md-8">
								<select id="region_id" name="region_id" class="form-control input-sm">
									<option value="">-Select Region-</option>
									<?php 
										if(is_array($regions) && count($regions) > 0)
										{
											foreach($regions as $each_region)
											{ 	
												$selected = "";
												if(isset($poddata[0]['region_id']))
												{
													if($each_region['region_id'] == $poddata[0]['region_id'])
														$selected = "selected = 'selected'";
												}
											
											?>
												<option value="<?php echo $each_region['region_id'];?>" <?php echo $selected;?>><?php echo $each_region['region_name'];?></option>
										<?php }
										}
									?>
								</select>							
							</div>
					</div>
					<div class="form-group required ">
							<label for="inputStandard" class="col-md-3 control-label">Datacenter</label>
							<div class="col-md-8">
								<select id="dc_id" name="dc_id" class="form-control input-sm">
									<option value="">-Select Datacenter-</option>
									<?php 
										if(isset($poddata[0]['dc_id']))
										{
											if(is_array($region_dcs) && count($region_dcs) > 0)
											{
												foreach($region_dcs as $each_dc)
												{ 
													$selected = "";
													if($each_dc['dc_id'] == $poddata[0]['dc_id'])
														$selected = "selected = 'selected'";
												?>
													<option value="<?php echo $each_dc['dc_id']?>" <?php echo $selected;?>><?php echo $each_dc['dc_name']?></option>
											<?php }
											}
										}
									?>
								</select>							
							</div>
					</div>
					
					<div class="form-group required ">
							<label for="inputStandard" class="col-md-3 control-label">POD Name</label>
							<div class="col-md-8">
								<input type="text" id="pod_name" name="pod_name" class="form-control input-sm" value="<?php if(isset($poddata[0]['pod_name'])) echo $poddata[0]['pod_name'];?>">
							</div>
					</div>
						<div class="form-group required">
								<label for="Description" class="col-md-3 control-label">POD Description</label>
								<div class="col-md-8">
									<textarea id="pod_description" name="pod_description" class="form-control input-sm"><?php  if(isset($poddata[0]['pod_description'])) echo $poddata[0]['pod_description'];?></textarea>
							</div>
					</div>
					
						
					<div class="form-group">
					<label class="col-md-3 control-label"></label>
						<div class="col-xs-2">
							<?php if($pod_id != '') {?>
							<button id="podeditsubmit" type="button" class="btn btn-success btn-block">Submit</button>
							<?php }else{?>
							<button id="podaddsubmit" type="button" class="btn btn-success btn-block">Submit</button>
							<?php } ?>
						</div>
						<div class="col-xs-2">
							<button id="pod_reset" type="button" class="btn btn-info btn-block">Reset</button>
						</div>
				</div>
			</form>
		</div>
	</div>
</div>