<div class="col-md-10">
        <div class="hidden alert-dismissable" id="msg_popup"></div>
</div>
<?php 
	$vstring = ''; 
?>
<script>
	$(document).ready(function () {
		var instock_asset_prid = "1";
		var instock_asset_pr_department_id = "1";
		var instock_asset_pr_requester_id = "1";
		var instock_asset_pr_item_product = "1";
		if (localStorage.getItem("instock_asset_prid") != null) {
			instock_asset_prid = localStorage.getItem("instock_asset_prid");
		}
		if (localStorage.getItem("instock_asset_pr_department_id") != null) {
			instock_asset_pr_department_id = localStorage.getItem("instock_asset_pr_department_id");
		}
		if (localStorage.getItem("instock_asset_pr_requester_id") != null) {
			instock_asset_pr_requester_id = localStorage.getItem("instock_asset_pr_requester_id");
		}
		if (localStorage.getItem("instock_asset_pr_item_product") != null) {
			instock_asset_pr_item_product = localStorage.getItem("instock_asset_pr_item_product");
		}

		$("#instock_asset_pr_requester_id").val(instock_asset_pr_requester_id);
		$("#instock_asset_pr_item_product").val(instock_asset_pr_item_product);
		$("#instock_asset_prid").val(instock_asset_prid);
		$("#instock_asset_pr_department_id").val(instock_asset_pr_department_id);
		
	});
</script>
<div class="col-md-12">
	<div class="panel">
		<form id="changestatus" name="changestatus" method="post"> 
			<input type="hidden" value="" name="instock_asset_pr_requester_id" id="instock_asset_pr_requester_id">
			<input type="hidden" value="" name="instock_asset_pr_item_product" id="instock_asset_pr_item_product">
			<input type="hidden" value="" name="instock_asset_prid" id="instock_asset_prid">
			<input type="hidden" value="" name="instock_asset_pr_department_id" id="instock_asset_pr_department_id">
		
		
			<input type="hidden" name="asset_id" value="<?php echo $asset_id ?>">
			<input type="hidden" name="pre_bv_id" value="<?php echo $bv_id ?>">
			<input type="hidden" name="pre_location_id" value="<?php echo $location_id ?>">
			<input type="hidden" name="pre_parent_asset_id" value="<?php echo $parent_asset_id ?>">
			<input type="hidden" name="pre_status" value="<?php echo $status ?>">
			<input type="hidden" name="pre_department_id" value="<?php echo $department_id ?>">
			<div class="panel-body">
				<div class="form-group required col-md-12 ">
					<label for="status" class="col-md-4 control-label"><?php echo trans('label.lbl_asset_state');?></label>
					<div class="col-md-8">
	                    <select name="status" onchange="statchange(this.value)" id="status"  class="chosen-select">
	                    	<!--<option value=""><?php //echo trans('label.drop_select');?></option> -->
	                    	<option value="in_store" <?php echo isset($status) && $status == "in_store" ? 'selected="selected"' : ''; ?>><?php echo trans('label.lbl_instore');?></option>
	                    	<option value="in_use" <?php echo isset($status) && $status == "in_use" ? 'selected="selected"' : ''; ?>><?php echo trans('label.lbl_inuse');?></option>
	                    	<option value="return">Return</option>
	                    	<option value="in_repair" <?php echo isset($status) && $status == "in_repair" ? 'selected="selected"' : ''; ?>><?php echo trans('label.lbl_inrepair');?></option>
	                    	<option value="expired" <?php echo isset($status) && $status == "expired" ? 'selected="selected"' : ''; ?>><?php echo trans('label.lbl_expired');?></option>
	                    	<option value="disposed" <?php echo isset($status) && $status == "disposed" ? 'selected="selected"' : ''; ?>><?php echo trans('label.lbl_disposed');?></option>

	                    </select>	
					</div>
				</div>
				<?php 
					if($status == "in_use")
						$cucss = "block";
					else
						$cucss = "none";
				?>
				<div id = "requesters" style="display:<?php echo $cucss?>">
				
			</div>
				<?php 
					if($status == "in_store")
						$cucss = "block";
					else
						$cucss = "none";
				?>
				<div id = "bvlocinfo" style="display:<?php echo $cucss?>">
					<div class="form-group required col-md-12 ">
						<label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_businessvertical');?></label>
						<div class="col-md-8">
		                    <select name="bv_id" id="bv_id"  class="form-control input-sm">
		                    	<option value=""><?php echo trans('label.drop_select');?></option>
		                    	<?php 
		                    	if(is_array($bvdata) && count($bvdata) > 0)
		                    	{
		                        	foreach($bvdata as $bv)
		                        	{?>
		                        		<option value="<?php echo $bv['bv_id']; ?>" <?php echo $bv_id && $bv_id == $bv['bv_id'] ? 'selected="selected"' : ''; ?>><?php echo $bv['bv_name']; ?></option>
		                       <?php 	}
		                        }	
		                    	?>
		                    </select>	
						</div>
					</div>

					<div class="form-group required col-md-12">
						<label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_location');?></label>
						<div class="col-md-8">
		                    <select name="location_id" id="location_id"  class="form-control input-sm">
		                    	<option value=""><?php echo trans('label.drop_select');?></option>
		                    	<?php 
		                    	if(is_array($locdata) && count($locdata) > 0)
		                    	{
		                        	foreach($locdata as $loc)
		                        	{?>
		                        		<option value="<?php echo $loc['location_id']; ?>" <?php echo isset($location_id) && $location_id == $loc['location_id'] ? 'selected="selected"' : ''; ?>><?php echo $loc['location_name']; ?></option>
		                       <?php 	}
		                        }	
		                    	?>
		                    </select>	
						</div>
					</div>
				</div>

					<?php 
						if($status == "in_use")
							$cucss = "block";
						else
							$cucss = "none";
					?>
				<div id="assetinfo" style="display:<?php echo $cucss?>">
					
					 <div  class="form-group required col-md-12">
						<label for="Title" class="col-md-4"><?php echo trans('label.lbl_associated_assets');?></label>
						<div class="col-md-8">
		                    <select name="parent_asset_id" id="parent_asset_id"  class="form-control input-sm">
		                    	<option value=""><?php echo trans('label.drop_select');?></option>
		                    	<?php 
		                    	if(is_array($assetlist) && count($assetlist) > 0)
		                    	{
		                    		$asset_id = isset($asset_id) ? $asset_id : '';
		                        	foreach($assetlist as $asset)
		                        	{
		                        		if (isset($asset['asset_id']) && $asset['asset_id'] != 
		                        			$asset_id) 
		                        		{
		                        		?>
		                        		<option value="<?php echo $asset['asset_id']; ?>" <?php echo isset($parent_asset_id) && $parent_asset_id == $asset['asset_id'] ? 'selected="selected"' : ''; ?>><?php echo $asset['asset_tag']; ?></option>
		                       <?php  	}
		                       		}
		                        }	
		                    	?>
		                    </select>	
						</div>

					</div>					
					<div  class="form-group required col-md-12">
						<label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_associated_department');?></label>
						<div class="col-md-8">
		                    <select name="department_id" onchange="getrequesters(this.value)" id="department_id"  class="form-control input-sm">
		                    	<option value=""><?php echo trans('label.drop_select');?></option>
		                    	<?php 
		                    	if(is_array($deptdata) && count($deptdata) > 0)
		                    	{
									$selected = "";
		                        	foreach($deptdata as $dept)
		                        	{
										if(isset($instock_asset_pr_department_id))
										{
											if($dept['department_id'] == $instock_asset_pr_department_id)
											{
												$selected = "selected";
												?>
												<option value="<?php echo $dept['department_id']; ?>" <?php echo $selected; ?>><?php echo $dept['department_name']; ?></option>
												<?php
											}
										}else{
											?>
											<option value="<?php echo $dept['department_id']; ?>"><?php echo $dept['department_name']; ?></option>
											<?php
										}
										?>		                        		
		                       <?php  }
		                        }	
		                    	?>
		                    </select>	
						</div>
					</div>
					<div class="form-group required col-md-12 ">
					<label for="requesters_id" class="col-md-4 control-label">Requester Name</label>
					<div class="col-md-8">
	                    <select name="requesters_id" id="requesters_id"  class="form-control input-sm">
	                    	<!--<option value=""><?php //echo trans('label.drop_select');?></option> -->
	                    	<?php /*
	                    	 $requesternameDetailsOptions = "<option value=''>[" . trans('label.lbl_selectrequestername') . "]</option>";
        if ($requesternameDetailsArr) {
            foreach ($requesternameDetailsArr as $requestername) {
                $requester_name = $requestername['prefix'] . '. ' . $requestername['fname'] . ' ' . $requestername['lname'];
                $requesternameDetailsOptions .= "<option value='" . $requestername['requestername_id'] . "'>" . $requester_name . "</option>";
                
            }
        }
        echo $requesternameDetailsOptions;*/
        ?>

	                    </select>	
					</div>
				</div>
				</div>
								<div class="form-group required col-md-12 ">
					<label for="comment" class="col-md-4 control-label"><?php echo trans('label.lbl_comment');?></label>
					<div class="col-md-8">
	                    <textarea id="comment" name="comment" class="form-control input-sm"></textarea>
					</div>
				</div>	

				<div class="form-group col-md-12">
					<label class="col-md-4 control-label"></label>
						<div class="col-xs-2">
                             <button id="stat_change" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_submit');?></button>                       
						</div>
						<div class="col-xs-2">
							<button onclick="changeStatus('<?php echo $status ?>')" type="button" class="btn btn-info btn-block"><?php echo trans('label.btn_reset');?></button>
						</div>
				</div>
			</div>	
		</form>
	</div>
</div>