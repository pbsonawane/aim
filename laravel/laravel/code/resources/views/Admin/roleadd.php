<div class="col-md-10">
        <div class="hidden alert-dismissable" id="msg_popup"></div>
</div>
<div class="col-md-12">
	<div class="panel">
		<div class="panel-body">
			<form class="form-horizontal"  name="addformrole" id="addformrole">
				<input id="role_id" name="role_id" type="hidden" value="<?php echo isset($roleid) ? $roleid : ''; ?>">
					<div class="form-group required ">
						<label for="inputStandard" class="col-md-5 control-label">Role Name</label>
						<div class="col-md-7">
							<input type="text" id="role_name" name="role_name" class="form-control input-sm" value="<?php echo isset($roledata[0]['role_name']) ? $roledata[0]['role_name'] : ''; ?>">
						</div>
					</div>
                    <div class="form-group required ">
						<label for="inputStandard" class="col-md-5 control-label">Type</label>
						<div class="col-md-7">
                            <select name="role_type" id="role_type" class="form-control input-sm">
                                <option value="">-Select-</option>
                                <?php
									if (is_array($roletype) && count($roletype) > 0)
									{
										foreach($roletype as $key => $value)
										{
                                ?>
                                		<option value="<?php echo $key;?>" <?php echo isset($roledata[0]['role_type']) && $roledata[0]['role_type'] == $key ? 'selected="selected"' : ''; ?>><?php echo $value;?></option>
                                <?php
										}
									}
                                ?>
                            </select>
						</div>
					</div>
                    <div class="form-group required ">
						<label for="inputStandard" class="col-md-5 control-label">Key</label>
						<div class="col-md-7">
                            <input type="text" id="role_key" name="role_key" class="form-control input-sm" value="<?php echo isset($roledata[0]['role_key']) ? $roledata[0]['role_key'] : ''; ?>" <?php echo isset($roleid) ? 'readonly="true"' : '';?>>
						</div>
					</div>
					<div class="form-group required">
						<label for="Description" class="col-md-5 control-label">Description</label>
						<div class="col-md-7">
							<textarea id="role_description" name="role_description" class="form-control input-sm"><?php echo isset($roledata[0]['role_description']) ? $roledata[0]['role_description'] : ''; ?></textarea>
						</div>
					</div>
					<div class="form-group">
					<label for="inputStandard" class="col-md-5 control-label"></label>
						<div class="col-md-4">
                            <?php if (isset($roleid)) { ?>
							    <button id="roleupdate" type="button" class="btn btn-success btn-block">Update</button>
                            <?php } else { ?>
                                <button id="rolesave" type="button" class="btn btn-success btn-block">Submit</button>
                            <?php } ?>
						</div>
						<div class="col-md-3">
							<button id="rolereset" type="button" class="btn btn-info btn-block">Reset</button>
						</div>
				</div>
			</form>
		</div>
	</div>
</div>
