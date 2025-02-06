<div class="col-md-10">
        <div class="hidden alert-dismissable" id="msg_popup1"></div>
</div>
<?php // echo "<pre>";  print_r($childdata); 
	$vstring = ''; 
?>
<div class="col-md-12">
	   <div class="panel">
		<div class="panel-heading">
        <span class="panel-title"><?php echo trans('label.lbl_asset_maping')?> </span>
       
        </div>
		<div class="panel-body">
			<form class="form-horizontal"  name="importfrm" id="importfrm" autocomplete="off">
				<input id="asset_prefix" name="asset_prefix" type="hidden" value="<?php echo $assetdata['prefix'] ?>">
				<input id="cutype" name="cutype" type="hidden" value="<?php echo $assetdata['type'] ?>">
				<input id="ci_type_id" name="ci_type_id" type="hidden" value="<?php echo $assetdata['ci_type_id'] ?>">
				<input id="ci_templ_id" name="ci_templ_id" type="hidden" value="<?php echo $assetdata['ci_templ_id'] ?>">
				<input id="fname" name="fname" type="hidden" value="<?php echo $fname?>">
				<input type="hidden" name="type" id="type" value="">
				<input type="hidden" name="cititle" id="cititle" value="<?php echo $cititle ?>">
				
					

					<div class="form-group col-md-6 ">
						<label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_title');?></label>
						<div class="col-md-8">
                            <select name="title" id="title"  class="chosen-select">
                            	<option value=""><?php echo trans('label.lbl_csvoption');?></option>
                            	<?php 
                            	if(is_array($col_array) && count($col_array) > 0)
                            	{
	                            	foreach($col_array as $key => $val)
	                            	{?>
	                            		<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
	                           <?php 	}
	                            }	
                            	?>
                            </select>	
						</div>
					</div>
					<div class="form-group col-md-6 ">
						<label for="Title" class="col-md-4 control-label">SKU Code</label>
						<div class="col-md-8">
                            <select name="sku_title" id="sku_title"  class="chosen-select">
                            	<option value=""><?php echo trans('label.lbl_csvoption');?></option>
                            	<?php 
                            	if(is_array($col_array) && count($col_array) > 0)
                            	{
	                            	foreach($col_array as $key => $val)
	                            	{?>
	                            		<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
	                           <?php 	}
	                            }	
                            	?>
                            </select>	
						</div>
					</div>
					<?php 
					
					if(is_array($assetdata['attributes']) && count($assetdata['attributes']) > 0)
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
					<div class="form-group <?php echo $customcss; ?> col-md-6" id = "varcu<?php echo $attr['veriable_name'];?>">
						<label for="email" class="col-md-4 control-label"><?php echo  $cnm;?></label>
						<div class="col-md-8">
							<select name="<?php echo $attr['veriable_name'];?>" id="<?php echo $attr['veriable_name'];?>"  class="chosen-select">
                            	<option value=""><?php echo trans('label.lbl_csvoption');?></option>
                            	<?php 
                            	if(is_array($col_array) && count($col_array) > 0)
                            	{
	                            	foreach($col_array as $key => $val)
	                            	{?>
	                            		<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
	                           <?php 	}
	                            }	
                            	?>
                            </select>	
                           
						</div>
					</div>
					<?php } 
					} ?>

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
											if(is_array($asset['attributes']) && count($asset['attributes']) > 0)
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
											if(is_array($asset['attributes']) && count($asset['attributes']) > 0)
											{
												foreach($asset['attributes'] as $attr)
												{ 	
													?>
													<td>
													
													  <select name="<?php echo $asset['ci_templ_id'].'#'.$attr['veriable_name'];?>[]" id="<?php echo $asset['ci_templ_id'].'#'.$attr['veriable_name'];?>"  class="">
							                            	<option value=""><?php echo trans('label.lbl_csvoption');?></option>
							                            	<?php 
							                            	if(is_array($col_array) && count($col_array) > 0)
							                            	{
								                            	foreach($col_array as $key => $val)
								                            	{?>
								                            		<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
								                           <?php 	}
								                            }	
							                            	?>
							                            </select>

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
					<div class="form-group col-md-6 ">
						<label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_businessvertical');?></label>
				
						<div class="col-md-8">
                            <select name="bv_id" id="bv_id"  class="chosen-select">
                            	<option value=""><?php echo trans('label.lbl_csvoption');?></option>
                            	<?php 
                            	if(is_array($col_array) && count($col_array) > 0)
                            	{
	                            	foreach($col_array as $key => $val)
	                            	{?>
	                            		<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
	                           <?php 	}
	                            }	
                            	?>
                            </select>	
						</div>
					</div>
					<div class="form-group col-md-6 ">
						<label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_location');?></label>
						<div class="col-md-8">
                            <select name="location_id" id="location_id"  class="chosen-select">
                            	<option value=""><?php echo trans('label.lbl_csvoption');?></option>
                            	<?php 
                            	if(is_array($col_array) && count($col_array) > 0)
                            	{
	                            	foreach($col_array as $key => $val)
	                            	{?>
	                            		<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
	                           <?php 	}
	                            }	
                            	?>
                            </select>	
						</div>
					</div>
					<!-- Department-->
					<div class="form-group col-md-6 ">
						<label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_department');?></label>
						<div class="col-md-8">
                            <select name="department_id" id="department_id"  class="chosen-select">
                            	<option value=""><?php echo trans('label.lbl_csvoption');?></option>
                            	<?php 
                            	if(is_array($col_array) && count($col_array) > 0)
                            	{
	                            	foreach($col_array as $key => $val)
	                            	{?>
	                            		<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
	                           <?php 	}
	                            }	
                            	?>
                            </select>	
						</div>
					</div>
						<!-- Asset status -->
					<div class="form-group col-md-6 ">
						<label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_asset_status');?></label>
						<div class="col-md-8">
                            <select name="asset_status" id="asset_status"  class="chosen-select">
                            	<option value=""><?php echo trans('label.lbl_csvoption');?></option>
                            	<?php 
                            	if(is_array($col_array) && count($col_array) > 0)
                            	{
	                            	foreach($col_array as $key => $val)
	                            	{?>
	                            		<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
	                           <?php 	}
	                            }	
                            	?>
                            </select>
                            <small class="text-muted">ex. (in_store, in_use, in_repair, expired, disposed)</small>	
						</div>
					</div>

					<div class="col-md-12"> 
						<h4> <?php echo trans('label.lbl_asset_details');?></h4>
						<hr class="mt5 mb10" style="border-top: 2px solid #cccccc;">
					</div>
					<div id="assetdtdiv">
						<div class="form-group col-md-6">
							<label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_vendor_name');?></label>
							<div class="col-md-8">
                            <select name="vendor_id" id="vendor_id"  class="chosen-select">
                            	<option value=""><?php echo trans('label.lbl_csvoption');?></option>
                            	<?php 
                            	if(is_array($col_array) && count($col_array) > 0)
                            	{
	                            	foreach($col_array as $key => $val)
	                            	{?>
	                            		<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
	                           <?php 	}
	                            }	
                            	?>
                            </select>
						</div>
						</div>	

						<div class="form-group col-md-6 ">
							<label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_purchase_cost');?></label>
							<div class="col-md-8">
                            	
	                            <select name="purchasecost" id="purchasecost"  class="chosen-select">
	                            	<option value=""><?php echo trans('label.lbl_csvoption');?></option>
	                            	<?php 
	                            	if(is_array($col_array) && count($col_array) > 0)
	                            	{
		                            	foreach($col_array as $key => $val)
		                            	{?>
		                            		<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
		                           <?php 	}
		                            }	
	                            	?>
	                            </select>
							</div>
						</div>			

						<div class="form-group col-md-6 ">
							<label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_acquisition_date');?></label>
							<div class="col-md-8">
	                          	<select name="acquisitiondate" id="acquisitiondate"  class="chosen-select">
	                            	<option value=""><?php echo trans('label.lbl_csvoption');?></option>
	                            	<?php 
	                            	if(is_array($col_array) && count($col_array) > 0)
	                            	{
		                            	foreach($col_array as $key => $val)
		                            	{?>
		                            		<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
		                           <?php 	}
		                            }	
	                            	?>
	                            </select>  
							</div>
						</div>		

						<div class="form-group col-md-6">
							<label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_expiry_date');?></label>
							<div class="col-md-8">
	                        	<select name="expirydate" id="expirydate"  class="chosen-select">
	                            	<option value=""><?php echo trans('label.lbl_csvoption');?></option>
	                            	<?php 
	                            	if(is_array($col_array) && count($col_array) > 0)
	                            	{
		                            	foreach($col_array as $key => $val)
		                            	{?>
		                            		<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
		                           <?php 	}
		                            }	
	                            	?>
	                            </select>     
							</div>
						</div>	

						<div class="form-group  col-md-6 ">
							<label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_warranty_expiry_date');?></label>
							<div class="col-md-8">
	                        	<select name="warrantyexpirydate" id="warrantyexpirydate"  class="chosen-select">
	                            	<option value=""><?php echo trans('label.lbl_csvoption');?></option>
	                            	<?php 
	                            	if(is_array($col_array) && count($col_array) > 0)
	                            	{
		                            	foreach($col_array as $key => $val)
		                            	{?>
		                            		<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
		                           <?php 	}
		                            }	
	                            	?>
	                            </select>     
							</div>
						</div>	
					</div>
					<div class="form-group col-md-12">
					<label class="col-md-4 control-label"></label>
						<div class="col-xs-2">
                            
                            <button id="importsave" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_submit');?></button>
						</div>
						<div class="col-xs-2">
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

	/*var vstring = "<?php // echo $vstring; ?>";
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
	}*/
</script>

