<div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
</div>
<div class="col-md-12">
	<div class="panel">
		<div class="panel-body">
			<form class="form-horizontal"  name="addformmodule" id="addformmodule">
				<input id="module_id" name="module_id" type="hidden" value="<?php echo $module_id?>">
					<div class="form-group required ">
							<label for="inputStandard" class="col-md-3 control-label">Module Name</label>
							<div class="col-md-8">
								<input type="text" id="module_name" name="module_name" class="form-control input-sm" value="<?php if(isset($moduledata[0]['module_name'])) echo $moduledata[0]['module_name'];?>">
							</div>
					</div>
						<div class="form-group required">
								<label for="Description" class="col-md-3 control-label">Module Key</label>
								<div class="col-md-8">
									<?php 
									$readonly = "";
									if(isset($moduledata[0]['module_key']) && $module_id != '') { if($moduledata[0]['module_key'] == 'IAM') $readonly = 'readonly="readonly"';}?>
									<textarea id="module_key" name="module_key" class="form-control input-sm" <?php echo $readonly; ?>><?php  if(isset($moduledata[0]['module_key'])) echo $moduledata[0]['module_key'];?></textarea>
							</div>
					</div>
					
						
					<div class="form-group">
					<label class="col-md-3 control-label"></label>
						<div class="col-xs-2">
							<?php if($module_id != '') {?>
							<button id="moduleeditsubmit" type="button" class="btn btn-success btn-block">Submit</button>
							<?php }else{?>
							<button id="moduleaddsubmit" type="button" class="btn btn-success btn-block">Submit</button>
							<?php } ?>
						</div>
						<div class="col-xs-2">
							<button id="module_reset" type="button" class="btn btn-info btn-block">Reset</button>
						</div>
				</div>
			</form>
		</div>
	</div>
</div>