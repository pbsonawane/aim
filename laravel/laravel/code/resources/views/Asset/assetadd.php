<div class="col-md-10">
        <div class="hidden alert-dismissable" id="msg_popup"></div>
</div>
<?php // echo "<pre>";  print_r($childdata); 
	$vstring = ''; 
?>
<div class="col-md-12">
	<div class="panel">
		<div class="panel-body">
			<form class="form-horizontal"  name="saveassetfrm" id="saveassetfrm" autocomplete="off">
				<?php if($asset_id != ""){?>
					<input id="asset_id" name="asset_id" type="hidden" value="<?php echo $asset_id; ?>">
					<?php } ?>
				<input id="asset_prefix" name="asset_prefix" type="hidden" value="<?php echo $assetdata['prefix'] ?>">
			
				<input id="cutype" name="cutype" type="hidden" value="<?php echo $assetdata['type'] ?>">
				<input id="ci_type_id" name="ci_type_id" type="hidden" value="<?php echo $assetdata['ci_type_id'] ?>">
				<input id="ci_templ_id" name="ci_templ_id" type="hidden" value="<?php echo $assetdata['ci_templ_id'] ?>">
				<input id="deletedasset" name="deletedasset" type="hidden" value="">
					
					<div class="form-group required col-md-12 ">
						<label for="Title" class="col-md-2 control-label"><?php echo trans('label.lbl_title');?></label>
						<div class="col-md-8">
							<select name="title" id="asset_title"  class="chosen-select" onchange="getasset_title()">
							<option value=""><?php echo trans('label.drop_select');?></option>
                            	<?php 
                            	if(is_array($skucodes) && count($skucodes) > 0)
                            	{
	                            	foreach($skucodes as $sku)
	                            	{?>
	                            		<option value="<?php echo $sku['core_product_name']; ?>" <?php echo isset($editdata[0]['asset_sku']) && trim($editdata[0]['asset_sku']) == trim($sku['sku_code'])?'selected="selected"':''; ?>> <?php echo $sku['core_product_name'].' ('.$sku['sku_code'].')'; ?></option>
	                           <?php } }?>
                            </select>
						</div>
					</div>
					<?php 
					
					if(isset($assetdata['attributes']) && is_array($assetdata['attributes']) && count($assetdata['attributes']) > 0)
					{
						
						$type = $assetdata['type'];
						foreach($assetdata['attributes'] as $attr)
						{
								if($type == "default")
									$cnm = trans('citree.'.str_replace(" ","_", $attr['attribute']));
								else
									$cnm =  $attr['attribute'];
								$customcss = "";
								if(is_array($attr['validation']))
								{
									if(in_array("required", $attr['validation']))
										$customcss = "required";
								}
						?>	
					<div class="form-group <?php echo $customcss; ?> col-md-12" id = "varcu<?php echo $attr['veriable_name'];?>">
						<label for="email" class="col-md-2 control-label"><?php echo  $cnm;?></label>
						<div class="col-md-8">
							<?php  if($attr['unit'] != "" || $attr['input_type']  == "date"){?>	
							<div class="input-group date pull-right" id="<?php echo $attr['veriable_name'];?>" >
                            	<input type="text" id="<?php echo $attr['veriable_name'];?>" name="<?php echo $attr['veriable_name'];?>" placeholder="<?php echo  $cnm;?>" class="form-control input-sm" value="<?php echo isset($asset_details[$attr['veriable_name']]) ? $asset_details[$attr['veriable_name']] : ''; ?>">
                            	
                            	<span class="input-group-addon cursor">
	                            	<?php 
	                            	if($attr['input_type'] == "date")
	                            	{
	                            		echo '<i class="fa fa-calendar"></i>';
	                            		$vstring .= $attr['veriable_name'].'#/#';
	                            	}
	                            	else	
	                            		echo $attr['unit']; ?>
	                            </span>
	                          </div> 
	                        <?php  }else{ ?>
	                        	<input type="<?php echo $attr['input_type'];?>" id="<?php echo $attr['veriable_name'];?>" name="<?php echo $attr['veriable_name'];?>" placeholder="<?php echo  $cnm;?>" class="form-control input-sm" value="<?php echo isset($asset_details[$attr['veriable_name']]) ? $asset_details[$attr['veriable_name']] : ''; ?>">
	                        <?php } ?>
                           
						</div>
					</div>
					<?php } 
					}?>

					<div class="form-group required col-md-12 ">
						<label for="Title" class="col-md-2 control-label"><?php echo 'Sku Code';?></label>
						<div class="col-md-8">
							<input type="text" name="asset_sku" id="asset_sku" class="form-control input-sm" readonly value="<?php echo isset($editdata[0]['asset_sku']) ? $editdata[0]['asset_sku'] : ''; ?>">
                    	</div>
					</div>


					<?php //print_r($assets[0]);?>	
					<!-- Asset Tabs -->
					<?php 
						$assetvar = "";
						if(is_array($assets) && count($assets) > 0)
						{
							foreach($assets as $asset)
							{ 
								//print_r($asset); exit;
								$assetvar .= $asset['variable_name']."##";
								?>
								<input id="assets_ci_templ_id" name="assets_ci_templ_id[]" type="hidden" value="<?php echo $asset['ci_templ_id']; ?>">
								<input id="assets_ci_type_id" name="assets_ci_type_id[]" type="hidden" value="<?php echo $asset['ci_type_id']; ?>">
								<input id="assets_types" name="assets_types[]" type="hidden" value="<?php echo $asset['type']; ?>">
								<input id="assets_prefix" name="assets_prefix[]" type="hidden" value="<?php echo $asset['prefix']; ?>">
								<div class="col-md-12"> 
									<h5><?php echo  trans('citree.'.str_replace(" ","_", $asset['ci_name']));?></h5>
									<hr class="mt5 mb10" style="border-top: 1px solid #cccccc;">
								</div>
								<div class="col-md-12"> 
									<div class="emtblhscroll">
										<table width="100%" class="table table-bordered table-hover mb30 <?php echo $asset['ci_templ_id'];?>" cellspacing="0" cellpadding="0">
											<thead>
												<tr>
											<?php 
											if(isset($asset['attributes']) && is_array($asset['attributes']) && count($asset['attributes']) > 0)
											{
												foreach($asset['attributes'] as $attr)
												{ 
													$unit = "";
													if($attr['unit'] != '')
														$unit = '('.$attr['unit'].')';

													$customcss = "";
													if(is_array($attr['validation']))
													{
														if(in_array("required", $attr['validation']))
															$customcss = "field-required";
													}
													?>

													<th class=""><label class="control-label <?php echo $customcss; ?>"><?php echo $attr['attribute'] .''.$unit;?></label></th>
										<?php 	}
											}
										?>		
												<th><i id="<?php echo $asset['ci_templ_id'];?>" class="fa fa-plus mr10 fa-lg addmore" title="<?php echo trans('label.lbl_add_asset');?>"></i></th>
												</tr>
												</thead>
												<?php 
													$cn = 1;
											 $each_templ_id = isset($childdata[$asset['ci_templ_id']]) ? $childdata[$asset['ci_templ_id']] : '';
											 if($each_templ_id != "")
													$cn = count($each_templ_id);
												for($i = 1; $i <= $cn; $i++)
												{
													$j = $i - 1;	

												?>
												<tr id = "row-<?php echo $i; ?>">
											<?php 
											if(isset($asset['attributes']) && is_array($asset['attributes']) && count($asset['attributes']) > 0)
											{
												foreach($asset['attributes'] as $attr)
												{ 	
													?>
													<td>
														<?php  if($attr['input_type']  == "date"){?>	
														<div class="input-group date pull-right" id="<?php echo $attr['veriable_name'];?>" >
							                            	<input type="text" id="<?php echo $asset['ci_templ_id'].'#'.$attr['veriable_name'];?>" name="<?php echo $asset['ci_templ_id'].'#'.$attr['veriable_name'];?>[]" placeholder="<?php echo $attr['attribute'];?>" class="form-control input-sm" value="<?php echo isset($childdata[$asset['ci_templ_id']][$j]['asset_detailsarray'][$attr['veriable_name']]) ? $childdata[$asset['ci_templ_id']][$j]['asset_detailsarray'][$attr['veriable_name']] : ''; ?>">
							                            	
							                            	<span class="input-group-addon cursor">
								                            	<?php 
								                            	if($attr['input_type'] == "date")
								                            	{
								                            		echo '<i class="fa fa-calendar"></i>';
								                            		$vstring .= $attr['veriable_name'].'#/#';
								                            	}
								                            	 ?>
								                            </span>
								                          </div>
								                      <?php } else {?>
														<input type="text" placeholder="<?php echo  trans('citree.'.str_replace(" ","_", $attr['attribute']));?>" id="attribute" name="<?php echo $asset['ci_templ_id'].'#'.$attr['veriable_name'];?>[]" class="form-control input-sm" value="<?php echo isset($childdata[$asset['ci_templ_id']][$j]['asset_detailsarray'][$attr['veriable_name']]) ? $childdata[$asset['ci_templ_id']][$j]['asset_detailsarray'][$attr['veriable_name']] : ''; ?>">
													  <?php } ?>	
													</td>
										<?php 	}
											}
										?>		
													<td>
														<input id="assets_ids" name="<?php echo $asset['ci_templ_id'].'#multiassetid'?>[]" type="hidden" value="<?php echo isset($childdata[$asset['ci_templ_id']][$j]['asset_id']) ? $childdata[$asset['ci_templ_id']][$j]['asset_id'] : ''; ?>">
														<i class="fa fa-trash-o mr10 fa-lg remove" id="<?php echo isset($childdata[$asset['ci_templ_id']][$j]['asset_id']) ? $childdata[$asset['ci_templ_id']][$j]['asset_id'] : ''; ?>" title="Delete Asset"></i>
													</td>
												</tr>
											<?php } ?>	
											
										</table>
									</div>
								</div>	
					<?php 	}
						}
					?>	
					<div class="col-md-12"> 
						<h4><?php echo trans('label.lbl_asset_state');?></h4>
						<hr class="mt5 mb10" style="border-top: 2px solid #cccccc;">
					</div>
					<div class="form-group required col-md-6 ">
						<label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_businessvertical');?></label>
						<div class="col-md-8">
                            <select name="bv_id" id="bv_id"  class="chosen-select">
                            	<option value=""><?php echo trans('label.drop_select');?></option>
                            	<?php 
                            	if(is_array($bvdata) && count($bvdata) > 0)
                            	{
	                            	foreach($bvdata as $bv)
	                            	{?>
	                            		<option value="<?php echo $bv['bv_id']; ?>" <?php echo isset($editdata[0]['bv_id']) && $editdata[0]['bv_id'] == $bv['bv_id'] ? 'selected="selected"' : ''; ?>><?php echo $bv['bv_name']; ?></option>
	                           <?php 	}
	                            }	
                            	?>
                            </select>	
						</div>
					</div>
					<div class="form-group required col-md-6 ">
						<label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_location');?></label>
						<div class="col-md-8">
                            <select name="location_id" id="location_id"  class="chosen-select">
                            	<option value=""><?php echo trans('label.drop_select');?></option>
                            	<?php 
                            	if(is_array($locdata) && count($locdata) > 0)
                            	{
	                            	foreach($locdata as $loc)
	                            	{?>
	                            		<option value="<?php echo $loc['location_id']; ?>" <?php echo isset($editdata[0]['location_id']) && $editdata[0]['location_id'] == $loc['location_id'] ? 'selected="selected"' : ''; ?>><?php echo $loc['location_name']; ?></option>
	                           <?php 	}
	                            }	
                            	?>
                            </select>	
						</div>
					</div>

					<div class="col-md-12"> 
						<h4> <i class="fa fa-minus-square-o faclick" id="faminus"></i><i class="fa fa-plus-square-o faclick" id="faplus"></i> <?php echo trans('label.lbl_asset_details');?></h4>
						<hr class="mt5 mb10" style="border-top: 2px solid #cccccc;">
					</div>
					<div id="assetdtdiv">
						<div class="form-group col-md-6">
							<label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_vendor_name');?></label>
							<div class="col-md-8">
                            <select name="vendor_id" id="vendor_id"  class="chosen-select">
                            	<option value=""><?php echo trans('label.drop_select');?></option>
                            	<?php 
                            	if(is_array($vendordata) && count($vendordata) > 0)
                            	{
	                            	foreach($vendordata as $vendor)
	                            	{?>
	                            		<option value="<?php echo $vendor['vendor_id']; ?>" <?php echo isset($editdata[0]['vendor_id']) && $editdata[0]['vendor_id'] == $vendor['vendor_id'] ? 'selected="selected"' : ''; ?>><?php echo $vendor['vendor_name']; ?></option>
	                           <?php 	}
	                            }	
                            	?>
                            </select>	
						</div>
						</div>	

						<div class="form-group col-md-6 ">
							<label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_purchase_cost');?></label>
							<div class="col-md-8">
                            	<input type="text" id="purchasecost"  name="purchasecost" placeholder="<?php echo trans('label.lbl_purchase_cost');?>" class="form-control input-sm" value="<?php echo isset($editdata[0]['purchasecost']) ? $editdata[0]['purchasecost'] : ''; ?>">
							</div>
						</div>			

						<div class="form-group col-md-6 ">
							<label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_acquisition_date');?></label>
							<div class="col-md-8">
	                        	<div class="input-group date pull-right calendar" id="acquisitiondate">
                            		<input type="text" readonly="readonly" id="acquisitiondate" name="acquisitiondate" placeholder="<?php echo trans('label.lbl_acquisition_date');?>" class="form-control input-sm" value="<?php echo isset($editdata[0]['acquisitiondate'])  && strtotime($editdata[0]['acquisitiondate']) > 0 ? $editdata[0]['acquisitiondate'] : ''; ?>">
	                            	<span class="input-group-addon cursor">
		                            		<i class="fa fa-calendar"></i>
		                            </span>
	                          	</div>    
							</div>
						</div>		

						<div class="form-group col-md-6">
							<label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_expiry_date');?></label>
							<div class="col-md-8">
	                        	<div class="input-group date pull-right calendar" id="expirydate">
                            		<input type="text" readonly="readonly" id="expirydate" name="expirydate" placeholder="<?php echo trans('label.lbl_expiry_date');?>" class="form-control input-sm" value="<?php echo isset($editdata[0]['expirydate']) && strtotime($editdata[0]['expirydate']) > 0  ? $editdata[0]['expirydate'] : ''; ?>">
	                            	<span class="input-group-addon cursor">
		                            		<i class="fa fa-calendar"></i>
		                            </span>
	                          	</div>    
							</div>
						</div>	

						<div class="form-group  col-md-6 ">
							<label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_warranty_expiry_date');?></label>
							<div class="col-md-8">
	                        	<div class="input-group date pull-right calendar" id="warrantyexpirydate">
                            		<input type="text" readonly="readonly" id="warrantyexpirydate" name="warrantyexpirydate" placeholder="<?php echo trans('label.lbl_warranty_expiry_date');?>" class="form-control input-sm" value="<?php echo isset($editdata[0]['warrantyexpirydate']) && strtotime($editdata[0]['warrantyexpirydate']) > 0 ? $editdata[0]['warrantyexpirydate'] : ''; ?>">
	                            	<span class="input-group-addon cursor">
		                            		<i class="fa fa-calendar"></i>
		                            </span>
	                          	</div>    
							</div>
						</div>	
					</div>
					<div class="form-group col-md-12">
					<label class="col-md-4 control-label"></label>
						<div class="col-xs-2">
                            	<?php if($asset_id != ""){ ?>
							   <button id="assetupdate" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_update');?></button>
							<?php } else { ?>
                                <button id="assetsave" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_submit');?></button>
                               <?php } ?> 
                       
						</div>
						<div class="col-xs-2">
							<?
								if($asset_id != "")
									$resetid = "editreset";
								else
									$resetid = "resetasset";
							?>
							<button id="<?php echo $resetid; ?>"  type="button" class="btn btn-info btn-block"><?php echo trans('label.btn_reset');?></button>
						</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	var assetvar = "<?php echo $assetvar; ?>";
		//alert(assetvar);
	var res = assetvar.split("##");
	if(res.length > 0)
	{
		jQuery.each(res, function( i, val ) {
			if(val != "")
			{		
				setTimeout(function(){
					$("#varcu"+val).hide();
				},10);
		 		
			}
		});
	}

	var vstring = "<?php echo $vstring; ?>";
	//alert(vstring);
	var res = vstring.split("#/#");
	if(res.length > 0)
	{
		jQuery.each( res, function( i, val ) {
			if(val != "")
			{
		 		datetimecalendar(val);
			}
		});
		datecalendar("calendar","class");
	}


	function getasset_title()
    {
        title=$("#asset_title").find('option:selected').text();
		t=title.split(" ").splice(-1);
		sku1=t[0].split("(");
		sku2=sku1[1].split(")");
		$("#asset_sku").val(sku2[0]);
	}

</script>

