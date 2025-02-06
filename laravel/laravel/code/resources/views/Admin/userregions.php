<div class="col-md-10">
        <div class="hidden alert-dismissable" id="msg_popup_region"></div>
</div>
<div class="col-md-12">
	
	<div class="col-md-3 ccursor crtype" id="Regions">
	   <div class="panel panel-info panel-border top">
		  <div class="panel-heading">
			 <span class="panel-title">Regions</span>
		  </div>
		  <div class="panel-body">
		  		<input type="hidden" id="userid" value="<?php echo $user_id;?>" />
			 	<table>
					<tbody>
						 <?php foreach($regions['regions'] as $each_region ){ 
						 	$region_checked = "";
								if($each_region['checked'])
									$region_checked = "checked = 'checked'";
						 ?>
						 	<tr><td>
						 	
							<div class="checkbox-custom mb5">
								<input type="checkbox" class="user_regions"  <?php echo $region_checked;?> id="<?php echo $each_region['region_id']?>" value="<?php echo $each_region['region_id'] ?>">
								<label for="<?php echo $each_region['region_id']?>"><?php echo $each_region['region_name']; ?></label>
							</div>
						
							</td></tr>                        
						 <?php } ?>
					</tbody>
				</table>
		  </div>
	   </div>
	</div>
	
	<div class="col-md-3 ccursor crtype" id="Locations">
	   <div class="panel panel-info panel-border top">
		  <div class="panel-heading">
			 <span class="panel-title">Locations</span>
		  </div>
		  <div class="panel-body">
			 <table>
					<tbody id="loc_lists">
						 <?php foreach($regions['locations'] as $each_locations ){ 
						 	$loc_checked = "";
								if($each_locations['checked'])
									$loc_checked = "checked = 'checked'";
						 ?>
						 	<tr><td>
						 	
							<div class="checkbox-custom mb5">
								<input type="checkbox" class="user_locations"  <?php echo $loc_checked;?> id="<?php echo $each_locations['location_id']?>" value="<?php echo $each_locations['location_id'] ?>">
								<label for="<?php echo $each_locations['location_id']?>"><?php echo $each_locations['location_name']; ?></label>
							</div>
							
							</td></tr>                        
						 <?php } ?>
					</tbody>
				</table>
		  </div>
	   </div>
	</div>
	
	<div class="col-md-3 ccursor crtype" id="Datacenters">
	   <div class="panel panel-info panel-border top">
		  <div class="panel-heading">
			 <span class="panel-title">Datacenters</span>
		  </div>
		  <div class="panel-body">
			  <table>
					<tbody id="dc_lists">
						 <?php foreach($regions['dcs'] as $each_dc ){ 
						 	$dc_checked = "";
								if($each_dc['checked'])
									$dc_checked = "checked = 'checked'";
						 ?>
						 	<tr><td>
							<div class="checkbox-custom mb5">
								<input type="checkbox" class="user_dcs"  <?php echo $dc_checked;?> id="<?php echo $each_dc['dc_id']?>" value="<?php echo $each_dc['dc_id'] ?>">
								<label for="<?php echo $each_dc['dc_id']?>"><?php echo $each_dc['dc_name']; ?></label>
							</div>
							    
							</td></tr>                        
						 <?php } ?>
					</tbody>
				</table>
		  </div>
	   </div>
	</div>
	
	<div class="col-md-3 ccursor crtype" id="PODs">
	   <div class="panel panel-info panel-border top">
		  <div class="panel-heading">
			 <span class="panel-title">PODs</span>
		  </div>
		  <div class="panel-body">
			 <table>
					<tbody id="pod_lists">
						 <?php foreach($regions['pods'] as $each_pod ){ 
						 	$pod_checked = "";
								if($each_pod['checked'])
									$pod_checked = "checked = 'checked'";
						 ?>
						 	<tr><td>
							<div class="checkbox-custom mb5">
								<input type="checkbox" class="user_pods"  <?php echo $pod_checked;?> id="<?php echo $each_pod['pod_id']?>" value="<?php echo $each_pod['pod_id'] ?>">
								<label for="<?php echo $each_pod['pod_id']?>"><?php echo $each_pod['pod_name']; ?></label>
							</div>
							  
							</td></tr>                        
						 <?php } ?>
					</tbody>
				</table>
		  </div>
	   </div>
	</div>
</div>
<div class="col-md-12">
	<div class="form-group align-left">
		<label class="col-md-3 control-label"></label>
			<div class="col-xs-2">
				<button id="userregion_submit" type="button" class="btn btn-success btn-block">Assign</button>
			</div>
	</div>
</div>