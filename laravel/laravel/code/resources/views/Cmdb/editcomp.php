<div class="panel">

    <div class="panel-heading">
        <span class="panel-title"><?php echo  trans('label.lbl_edit_component') ?></span>
        <div class="topbar-right">
		<div class="btn-group">
			<button id="timeline-toggle" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
			<span class="glyphicons glyphicons-show_lines fs16"></span>
			</button>
			<ul class="dropdown-menu pull-right" user="menu">
				<li id="attradd">
					<a><span title="<?php echo trans('label.lbl_add_attribute'); ?>"><?php echo  trans('label.lbl_add_attribute'); ?></span></a>
				</li>

				<?php
					if($type == 'custom'){
				?>
					<li id="componentdelete">
						<a><span title="<?php echo trans('label.lbl_delete_component'); ?>"><?php echo trans('label.lbl_delete_component'); ?></span></a>
					</li>
				<?php
					}
				?>

			</ul>
		</div>
	</div>
    </div>
    <div class="panel-body">
    	<div class="hidden alert-dismissable" id="msg_popup1"></div>
    	<form class="form-horizontal" id="compfrm" method="POST" name="compfrm">
    		<input type="hidden" id="ci_type_id" name="ci_type_id" class=" form-control input-sm" value="<?php echo $ci_type_id;?>">
    		<input type="hidden" id="ci_id" name="ci_id" class=" form-control input-sm" value="<?php echo $ci_templ_id; ?>">
    		<input type="hidden" id="type" name="type" class=" form-control input-sm" value="<?php echo $type; ?>">
    		<div class="form-group">
				<label for="cmp_name" class="col-md-3 control-label"><?php echo  trans('label.citype') ?></label>
				<div class="col-md-5">
					<input type="text" id="citype" disabled="disabled" name="citype" class=" form-control input-sm" value="<?php echo $citype;?>">
				</div>
			</div>

    		<div class="form-group required">
				<label for="cmp_name" class="col-md-3 control-label"><?php echo  trans('label.ciname') ?></label>

				<div class="col-md-5">

					<input type="text" id="ci_name" <?php if($status == "false"){ echo "disabled"; } ?> name="ci_name" class=" form-control input-sm" value="<?php echo $ci_name; ?>">
				</div>
			</div>


			<div class="form-group required">
				<label for="cmp_name" class="col-md-3 control-label"><?php echo  trans('label.CI_SKU') ?></label>
				<div class="col-md-5">
						<select id = "ci_sku_id" name ="ci_sku" class="form-control input-sm">
					<option value=""><?php echo  trans('label.CI_SKU') ?></option>
					<?php
                    
					if (is_array($skucodes) && count($skucodes) > 0)
                    {
                        foreach ($skucodes as $sku)
                        {
							if($ci_sku==$sku)
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
			
					</div>
			</div>
			<!--<div class="form-group required">
				<label for="cmp_name" class="col-md-3 control-label">Variable Name</label>
				<div class="col-md-5">
					<input type="text" id="variable_name" name="variable_name" class=" form-control input-sm" value="">
				</div>
			</div>
			<div class="form-group required">
				<label for="cmp_name" class="col-md-3 control-label">CI Name Prefix</label>
				<div class="col-md-5">
					<input type="text" id="prefix" name="prefix" class=" form-control input-sm" value="">
				</div>
			</div>-->
			<?php if($status == "true"){ ?>
				<div class="form-group col-md-12">
					<label class="col-md-3 control-label"></label>
					<div class="col-xs-2">
	                	<button id="uptcomp" type="button" class="btn btn-success btn-block"><?php echo  trans('label.btn_update') ?></button>
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