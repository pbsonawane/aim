<div class="panel">
    <div class="panel-heading">
        <span class="panel-title"><?php echo  trans('label.lbl_edit_attribute') ?></span>
    </div>
    <div class="panel-body">
    	<div class="hidden alert-dismissable" id="msg_popup"></div>
    	<form class="form-horizontal" id="edatrr" method="POST" name="edatrr">
    		
    		<input type="hidden" id="ci_type_id" name="ci_type_id" class=" form-control input-sm" value="<?php echo $ci_type_id;?>">
    		<input type="hidden" id="ci_id" name="ci_id" class=" form-control input-sm" value="<?php echo $ci_templ_id?>">
    		<input type="hidden" id="prefix" name="prefix" class=" form-control input-sm" value="<?php echo $cidata[0]['prefix']?>">
    		<input type="hidden" id="variable_name" name="variable_name" class=" form-control input-sm" value="<?php echo $cidata[0]['variable_name']?>">

    		<input type="hidden" id="ci_name" name="ci_name" class=" form-control input-sm" value="<?php echo $cidata[0]['ci_name']?>">

			<input type="hidden" id="ci_sku" name="ci_sku" class=" form-control input-sm" value="<?php echo $cidata[0]['ci_sku']?>">

    		<input type="hidden" id="type" name="type" class=" form-control input-sm" value="<?php echo $cidata['type']?>">
    		
    		<div class="form-group required">
				<label for="cmp_name" class="col-md-3 control-label"><?php echo  trans('label.citype') ?></label>
				<div class="col-md-5">
					<input type="text" id="citype" name="citype" disabled="disabled" class=" form-control input-sm" value="<?php echo $cidata[0]['citype']?>">
				</div>
			</div>
			<div class="form-group required">
				<label for="cmp_name" class="col-md-3 control-label"><?php echo  trans('label.CI_Name_Prefix') ?></label>
				<div class="col-md-5">
					<input type="text" id="ciname" name="ciname" disabled="disabled" class=" form-control input-sm" value="<?php echo $cidata[0]['ci_name']?>">
				</div>
			</div>

			<div class="form-group required">
				<label for="cmp_name" class="col-md-3 control-label"><?php echo  trans('label.CI_SKU') ?></label>
				<div class="col-md-5">
					<input type="text" id="ci_sku" name="ci_sku" disabled="disabled" class=" form-control input-sm" value="<?php echo $cidata[0]['ci_sku']?>">
				</div>
			</div>

			<!-- ATTRIBUTES-->

			<div id="attrdata" class="form-group required">
				<div class="panel m10">
					<div class="panel-heading">
						<span class="panel-title"><?php echo  trans('label.Attributes') ?></span>
						<div class="pull-right">
							<div class="col-xs-12">
								<?php //if($comp_id == 0){?>
								<!--<button id="addmore" class="btn btn-info btn-block" type="button">Add Row</button>-->
								<?php //} ?>
							</div>
						</div>
					</div>
					<div class="panel-body pn">
						<div class="emtblhscroll">
							<table class="table table-striped table-bordered table-hover addmore">
								<thead>
								<tr>
									<th class="field-required"><?php echo  trans('label.Attribute_name') ?></th>
									<th class="field-required"><?php echo  trans('label.Variable_Name') ?></th>
									<th class="field-required"><?php echo  trans('label.Input_Type') ?></th>
									<th><?php echo  trans('label.Validation') ?></th>
									<th><?php echo  trans('label.Unit') ?></th>
									<?php if($status == 'true'){ ?>
									<th width="1%"></th>
									<?php } ?>
								</tr>
								</thead>
								<tr>
									<td class="col-md-2">
										<input type="text" placeholder="Attribute" id="attribute" name="attribute[]" class="form-control input-sm" value="<?php echo $item['attribute'] ?>" maxlength="25">
									</td>
									<td class="col-md-2">
										<input type="text" disabled="disabled" placeholder="Variable Name" class="form-control input-sm" value="<?php echo $item['veriable_name'] ?>">
										<input type="hidden" placeholder="Variable Name" id="v_name" 
										name = "v_name[]" class="form-control input-sm" value="<?php echo $item['veriable_name'] ?>">
									</td>
									<td class="col-md-2">
										<select id="inpute_type" name="inpute_type[]" class="form-control input-sm">
											<option value = ""><?php echo  trans('label.Select_Input_Type') ?></option> 
											<option  value="text" <?php echo $item['input_type'] == "text" ? "selected" : ""?>><?php echo  trans('label.Text') ?></option>	
											<option  value="date" <?php echo $item['input_type'] == "date" ? "selected" : ""?>><?php echo  trans('label.Date') ?></option>	
										</select>
									</td>
									<td class="col-md-2">
										<?php 
											if(is_array($item['validation']) && count($item['validation']) > 0)
												$valds = $item['validation'];
											else
												$valds = [];	

										?>
										<select id="validations" size="2" multiple="multiple" name="validations[]" class="form-control input-sm">
											<option value = ""><?php echo  trans('label.Select_Validation') ?></option>
											<option  value="required" <?php echo in_array('required',$valds) ? "selected" : ""; ?>><?php echo  trans('label.Required') ?></option>
											<option  value="numeric" <?php echo in_array('numeric',$valds) ? "selected" : ""; ?>><?php echo  trans('label.Numeric') ?></option>
											<option  value="alpha_num" <?php echo in_array('alpha_num',$valds) ? "selected" : ""; ?>><?php echo  trans('label.Alpha_Numeric') ?></option>
											<option  value="unique" <?php echo in_array('unique',$valds) ? "selected" : ""; ?>><?php echo  trans('label.Unique') ?></option>
											<option  value="email" <?php echo in_array('email',$valds) ? "selected" : ""; ?>><?php echo  trans('label.Email') ?></option>
										</select>
									</td>
									<td class="col-md-2">
										<input type="text" placeholder="Unit" id="unit" name="unit[]" class="form-control input-sm" maxlength="25" value="<?php echo $item['unit'] ?>">
									</td>

									<td class="col-md-2">
									<select id="skucode" name="skucode[]" class="form-control input-sm">
											<option value = ""><?php echo  trans('label.CI_SKU') ?></option>
											<?php
													
													if (is_array($skucodes) && count($skucodes) > 0)
													{
														foreach ($skucodes as $sku)
														{
															if($item['skucode']==$sku)
															{
															?>
															<option value="<?php echo $sku ?>" selected><?php echo $sku; ?></option>
															<?php 
															}else{
															?>
																<option value="<?php echo $sku ?>"><?php echo $sku; ?></option>
															<?php 
															}
														}
													}
											?>
									</select>

									</td>

									<?php if($status == 'true'){ ?>
									<td id="delci">
										<i class="fa fa-trash-o mr10 fa-lg remove"></i>
									</td>
									<?php } ?>
								</tr>	
							</table>
						</div>
					</div>
				</div>
			</div>
			<?php if($status == 'true'){ ?>
			<div id="actionbtn" class="form-group col-md-12">
				<label class="col-md-4 control-label"></label>
				<div class="col-xs-2">
                	<button id="attrupdate" type="button" class="btn btn-success btn-block"><?php echo  trans('label.btn_update') ?></button>
                    
				</div>
				<!--<div class="col-xs-2">
						<?php 
							if($ci_type_id != '')
								$reid = "editreset";
							else
								$reid = "addreset";
						 ?>
					<button id="<?php echo $reid ?>"  type="button" class="btn btn-info btn-block">Reset</button>
				</div>-->
			</div>
			<?php } ?>
    	</form>	
    </div>	
</div> 
