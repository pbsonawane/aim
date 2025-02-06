
<div class="panel">
    <div class="panel-heading">
        <span class="panel-title"><?php echo  trans('label.add_component') ?> </span>
    </div>
    <div class="panel-body">
    	<div class="hidden alert-dismissable" id="msg_popup"></div>
    	<form class="form-horizontal" id="tempfrm" method="POST" name="tempfrm">
    		<input type="hidden" id="ci_id" name="ci_id" class=" form-control input-sm" value="">

    		<div class="form-group">
				<label for="cmp_name" class="col-md-3 control-label"><?php echo  trans('label.citype') ?></label>
				<div class="col-md-5">
					<!--<input type="text" id="citype" disabled="disabled" name="citype" class=" form-control input-sm" value="<?php echo $citype; ?>">-->


					<select id = "ci_type_id" name ="ci_type_id" class="form-control input-sm">
					<option value=""><?php echo  trans('label.citype') ?></option>
					<?php
                    $citypes = $citypes;

                    if (is_array($citypes) && count($citypes) > 0)
                    {
                        foreach ($citypes as $ci)
                        {
                            ?>
                            <option value="<?php echo $ci['ci_type_id'] ?>" <?php echo $ci_type_id == $ci['ci_type_id'] ? "selected" : "" ?>><?php echo  trans('label.'.str_replace(" ", "_", $ci['citype'])) ?></option>
                    <?php 
                        }
                    }
                    ?>
				</select>
				</div>
			</div>

    		<div class="form-group required">
				<label for="cmp_name" class="col-md-3 control-label"><?php echo  trans('label.ciname') ?></label>

				<div class="col-md-5">
					<input type="text" id="ci_name" name="ci_name" class=" form-control input-sm" value="">
				</div>
			</div>
			<div class="form-group required">
				<label for="cmp_name" class="col-md-3 control-label"><?php echo  trans('label.Variable_Name') ?></label>

				<div class="col-md-5">
					<input type="text" id="variable_name" name="variable_name" class=" form-control input-sm" value="">
				</div>
			</div>
			<div class="form-group required">
				<label for="cmp_name" class="col-md-3 control-label"><?php echo  trans('label.CI_Name_Prefix') ?></label>

				<div class="col-md-5">
					<input type="text" id="prefix" name="prefix" class=" form-control input-sm" value="">
				</div>
			</div>

			<div class="form-group required">
				<label for="cmp_name" class="col-md-3 control-label"><?php echo  trans('label.CI_SKU') ?></label>

				<div class="col-md-5">
				<select  id = "ci_sku_id" name ="ci_sku" class="col-md-12 input-sm select2">
					<option value=""><?php echo  trans('label.CI_SKU') ?></option>
					<?php
                    
					if (is_array($skucodes) && count($skucodes) > 0)
                    {
                        foreach ($skucodes as $key => $value)
                        {
                            ?>
                            <option value="<?= $key ?>"><?php echo $key . ' - ' . $value; ?></option>
                    <?php 
                        }
                    }
                    ?>
				</select>
				</div>
			</div>
			
			<!-- ATTRIBUTES-->

			<div class="form-group required">
				<div class="panel m10">
					<div class="panel-heading">
						<span class="panel-title"><?php echo  trans('label.Attributes') ?></span>
						<div class="pull-right">
							<div class="col-xs-12">
								<?php //if($comp_id == 0){?>
								<!-- <button id="addmore" class="btn btn-info btn-block" type="button">+</button>-->
								<i id="addmore" class="fa fa-plus mr10 fa-lg" title="Add Attribute"><?php echo  trans('label.Add') ?></i>
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
									<th><?php echo  trans('label.CI_SKU') ?></th>
									<th width="1%"></th>
								</tr>
								</thead>
								<tr id = "row-1">
									<td class="col-md-2">
										<input type="text" placeholder="<?php echo  trans('label.Attribute') ?>" id="attribute" name="attribute[]" class="form-control input-sm" value="">
									</td>
									<td class="col-md-2">
										<input type="text" placeholder="<?php echo  trans('label.Variable_Name') ?>" id="v_name" name="v_name[]" class="form-control input-sm" <?php //echo $read;?>  value="">
									</td>
									<td class="col-md-2">
										<select id="inpute_type" name="inpute_type[]" class="form-control input-sm">
											<option value = ""><?php echo  trans('label.Input_Type') ?></option>
											<option  value="text"><?php echo  trans('label.Text') ?></option>
											<option  value="date"><?php echo  trans('label.Date') ?></option>
										</select>
									</td>
									<td class="col-md-2">
										<select id="validations" size="2" multiple="multiple" name="validations[]" class="form-control input-sm selmulti">
											<option value = ""><?php echo  trans('label.Validation') ?></option>
											<option  value="required"><?php echo  trans('label.Required') ?></option>
											<option  value="numeric"><?php echo  trans('label.Numeric') ?></option>
											<option  value="alpha_num"><?php echo  trans('label.Alpha_Numeric') ?></option>
											<option  value="unique"><?php echo  trans('label.Unique') ?></option>
											<option  value="email"><?php echo  trans('label.Email') ?></option>
										</select>
									</td>
									<td class="col-md-2">
										<input type="text" placeholder="<?php echo  trans('label.Unit') ?>" id="unit" name="unit[]" class="form-control input-sm" value="">
									</td>
									<td class="col-md-2">
									<select id="skucode" name="skucode[]" class="form-control input-sm">
											<option value = ""><?php echo  trans('label.CI_SKU') ?></option>
											<?php
                    
											if (is_array($skucodes) && count($skucodes) > 0)
											{
												foreach ($skucodes as $key => $value)
												{
													?>
													 <option value="<?= $key ?>"><?php echo $key . ' - ' . $value; ?></option>
											<?php 
												}
											}
											?>
									</select>

									</td>
									<td>
										<i class="fa fa-trash-o mr10 fa-lg remove"></i>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group col-md-12">
				<label class="col-md-4 control-label"></label>
				<div class="col-xs-2">
                	<button id="addcomp" type="button" class="btn btn-success btn-block"><?php echo  trans('label.Submit') ?></button>

				</div>
				<div class="col-xs-2">
					<button id="resetbtn"  type="button" class="btn btn-info btn-block"><?php echo  trans('label.Reset') ?></button>
				</div>
			</div>
    	</form>
    </div>
</div>

<script type="text/javascript">
        $(document).ready(function() {
    // Your Select2 initialization code here
    $('.select2').select2();
});

    </script>