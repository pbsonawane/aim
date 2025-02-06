<div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
</div>
<div class="col-md-12">
	<div class="panel">
		<div class="panel-body">
			<form class="form-horizontal"  name="usercolsettingform" id="usercolsettingform">
				<input id="type" name="type" type="hidden" value="user">
					<?php $checked = 'checked = "checked"'?>
					<div class="form-group">
						<table class="table">
							<tr>
								<td align="left" valign="top">
									<table width="100%" border="0" cellspacing="0" cellpadding="3">
										<tr>
											<td>
											
											<div class="checkbox-custom mb5">
												<input type="checkbox" class="user_col" <?php if(in_array('designation_name',$display_fields)) echo $checked;?> id="designation_name" value="designation_name">
													<label for="designation_name">Designation</label>
											</div>
											</td>
											<td>
											<div class="checkbox-custom mb5">
												<input type="checkbox" class="user_col"  <?php if(in_array('organization_name',$display_fields)) echo $checked;?> id="organization_name" value="organization_name">
													<label for="organization_name">Organization</label>
											</div>
											</td>
										</tr>
										<tr>
											<td>
											<div class="checkbox-custom mb5">
												<input type="checkbox" class="user_col"  <?php if(in_array('department_name',$display_fields)) echo $checked;?> id="department_name" value="department_name">
													<label for="department_name">Department</label>
											</div>
											
											</td>
											<td>
											<div class="checkbox-custom mb5">
												<input type="checkbox" class="user_col" <?php if(in_array('user_type',$display_fields)) echo $checked;?> id="user_type" value="user_type">
													<label for="user_type">User Type</label>
											</div>
											</td>
										</tr>
										<tr>
											<td>
											<div class="checkbox-custom mb5">
												<input type="checkbox" class="user_col" <?php if(in_array('contactno',$display_fields)) echo $checked;?> id="contactno" value="contactno">
													<label for="contactno">Contact Number</label>
											</div>
											
											
											</td>
											<td>
											<div class="checkbox-custom mb5">
												<input type="checkbox" class="user_col" <?php if(in_array('role_name',$display_fields)) echo $checked;?> id="role_name" value="role_name">
													<label for="role_name">Role</label>
											</div>
											
											</td>
										</tr>
										<tr>
											<td>
											<div class="checkbox-custom mb5">
												<input type="checkbox" class="user_col" <?php if(in_array('manager',$display_fields)) echo $checked;?> id="manager" value="manager">
													<label for="manager">Manager Name</label>
											</div>
											
											</td>
											<td>&nbsp
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
					
					
					
					<div class="form-group align-left">
					<label class="col-md-3 control-label"></label>
						<div class="col-xs-2">
							<button id="usercolsett_submit" type="button" class="btn btn-success btn-block">Assign</button>
						</div>
						<!--<div class="col-xs-2">
							<button id="regionassigndc_reset" type="button" class="btn btn-info btn-block">Reset</button>
						</div> -->
				</div>
			</form>
		</div>
	</div>
</div>