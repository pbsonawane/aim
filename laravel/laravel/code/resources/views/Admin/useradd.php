<div class="col-md-10">
        <div class="hidden alert-dismissable" id="msg_popup"></div>
</div>
<div class="col-md-12">
	<div class="panel">
		<div class="panel-body">
			<form class="form-horizontal"  name="addformuser" id="addformuser" autocomplete="off">
				<input id="user_id" name="user_id" type="hidden" value="<?php echo isset($userid) ? $userid : ''; ?>">
				<?PHP if($userid != ''){ ?>
					<input id="user_type" name="user_type" type="hidden" value="<?php echo isset($userdata[0]['user_type']) ? $userdata[0]['user_type'] : ''; ?>">
					<input id="sel_roles" name="sel_roles" type="hidden" value='<?php echo isset($userdata[0]["role_id"]) ? $userdata[0]["role_id"] : ""; ?>'>
				<?php } ?>
					<?PHP if($userid == ''){ ?>
					<div class="form-group required col-md-6">
						<label for="inputStandard" class="col-md-4 control-label">User Type</label>
						<div class="col-md-8">
							<select name="user_type" id="user_type" data-placeholder="Select User Type" class="chosen-select" tabindex="1">
                                <option value="">-User Type-</option>
                                <option value="staff" <?php echo isset($userdata[0]['user_type']) && $userdata[0]['user_type'] == 'staff' ? 'selected="selected"' : ''; ?>>Staff</option>
                                <option value="client" <?php echo isset($userdata[0]['role_type']) && $userdata[0]['user_type'] == 'client' ? 'selected="selected"' : ''; ?>>Client</option>
                            </select>
						</div>
					</div>
				<?php } ?>
                    <div class="form-group required col-md-6 ">
                    	<label for="inputStandard" class="col-md-4 control-label">Role</label>
						<div class="col-md-8">
							<?php if(isset($userdata[0]['role_id']) && $userdata[0]['role_id'] != '')
									{
										$userRole = json_decode($userdata[0]['role_id'],true);
									}
									else
										$userRole = [];
							?>
                			<!--<select id="roleid" name="role_id[]" multiple="multiple"  style="display: none;">-->
                            <select id="roleid" name="role_id[]" data-placeholder="Select Role" class="chosen-select" multiple tabindex="6">
                                
                               <?php 
                        		if(is_array($alldata['roles']) && count($alldata['roles']) > 0)
                        		{
                        			foreach ($alldata['roles'] as $key => $role) 
                        			{
                        		?>			
                        				<option value="<?php echo $role['role_id']; ?>" <?php echo is_array($userRole) && in_array($role['role_id'], $userRole) ? 'selected="selected"' : ''; ?>><?php echo ucfirst($role['role_name']); ?>
                        				</option>
                        	<?php	}
                        		}	?>
                            </select>
                       </div>                         
					</div>
					<div class="form-group required col-md-6 staffshow">
						<label for="Manager" class="col-md-4 control-label">Manager</label>
						<div class="col-md-8">
                            <select id="manager_id" name="manager_id" data-placeholder="Your Favorite Football Teams" class="chosen-select"  tabindex="5">
                            	<option value="">-Manager-</option>
                            	<?php 
                            		if(is_array($alldata['users']) && count($alldata['users']) > 0)
                            		{
                            			foreach ($alldata['users'] as $key => $user) 
                            			{
                            	?>			
                            				<option value="<?php echo $user['user_id']; ?>" <?php echo isset($userdata[0]['parent_id']) && $userdata[0]['parent_id'] == $user['user_id'] ? 'selected="selected"' : ''; ?>><?php echo ucfirst($user['firstname']).' '.ucfirst($user['lastname']) ?></option>
                            	<?php	}
                            		}  	?>
                                
                                
	                        </select>
						</div>
					</div>
					<div class="form-group required col-md-6 staffshow">
						<label for="Department" class="col-md-4 control-label">Department</label>
						<div class="col-md-8">
                            <select id="department_id" name="department_id" data-placeholder="Your Favorite Football Teams" class="chosen-select" tabindex="5">
                            	<option value="">-Department-</option>
                               <?php 
                        		if(is_array($alldata['departments']) && count($alldata['departments']) > 0)
                        		{
                        			foreach ($alldata['departments'] as $key => $department) 
                        			{
                        		?>			
                        				<option value="<?php echo $department['department_id']; ?>" <?php echo isset($userdata[0]['department_id']) && $userdata[0]['department_id'] == $department['department_id'] ? 'selected="selected"' : ''; ?>><?php echo $department['department_name']; ?></option>
                        	<?php	}
                        		}	?>
                            </select>
						</div>
					</div>
					<div class="form-group required col-md-6 ">
						<label for="username" class="col-md-4 control-label">Username</label>
						<div class="col-md-8">
                            <input type="text" id="username" <?php echo  $userid != '' ? 'disabled="disabled"' : '' ?> placeholder="Username" name="username" class="form-control input-sm" value="<?php echo isset($userdata[0]['username']) ? $userdata[0]['username'] : ''; ?>">
						</div>
					</div>
					<div class="form-group required col-md-6 ">
						<label for="email" class="col-md-4 control-label">Email</label>
						<div class="col-md-8">
                            <input type="text" id="email" name="email" placeholder="Email ID" class="form-control input-sm" value="<?php echo isset($userdata[0]['email']) ? $userdata[0]['email'] : ''; ?>">
						</div>
					</div>
					<div class="form-group required col-md-6 ">
						<label for="firstname" class="col-md-4 control-label">First name</label>
						<div class="col-md-8">
                            <input type="text" id="firstname" placeholder="First Name" name="firstname" class="form-control input-sm" value="<?php echo isset($userdata[0]['firstname']) ? $userdata[0]['firstname'] : ''; ?>">
						</div>
					</div>
					<div class="form-group required col-md-6 ">
						<label for="lasttname" class="col-md-4 control-label">Last name</label>
						<div class="col-md-8">
                            <input type="text" id="lastname" placeholder="Last Name" name="lastname" class="form-control input-sm" value="<?php echo isset($userdata[0]['lastname']) ? $userdata[0]['lastname'] : ''; ?>" autocomplete="off">
						</div>
					</div>
					<?PHP if($userid == ''){ ?>
					<div class="form-group required col-md-6 ">
						<label for="password" class="col-md-4 control-label password">Password</label>
						<div class="col-md-8">
							<div class="input-group date pull-right" >
                            <input type="password" id="password" placeholder="Password" name="password" class="form-control input-sm input-group date pull-right" value="" autocomplete="off">
	                            <span class="input-group-addon cursor" id="genpass">
	                            	<i class="fa fa-key"></i>
	                            </span>
                            </div>
                            
						</div>
					</div>

					<div class="form-group required col-md-6 ">
						<label for="confirm_password" class="col-md-4 control-label">Confirm Password</label>
						<div class="col-md-8">
                            <input type="password" id="confirm_password" placeholder="Confirm Password" name="password_confirmation" class="form-control input-sm" value="">
						</div>
					</div>
				<?php } ?>
					<div class="form-group required col-md-6 clientshow">
						<label for="Organization" class="col-md-4 control-label">Organization</label>
						<div class="col-md-4">
                            <select id="organization_id" name="organization_id" data-placeholder="Your Favorite Football Teams" class="chosen-select"   tabindex="5">
                            	<option value="">-Organization-</option>
                               <?php 
                        		if(is_array($alldata['orgs']) && count($alldata['orgs']) > 0)
                        		{
                        			foreach ($alldata['orgs'] as $key => $org) 
                        			{
                        		?>			
                        				<option value="<?php echo $org['organization_id']; ?>" <?php echo isset($userdata[0]['organization_id']) && $userdata[0]['organization_id'] == $org['organization_id'] ? 'selected="selected"' : ''; ?>><?php echo $org['organization_name']; ?></option>
                        	<?php	}
                        		}	?>
                            </select>
                        </div>
                            <div class="input-group date pull-right col-md-4" >
                            <input type="text" id="organization_name" placeholder="New Organization" name="organization_name" class="form-control input-sm input-group date pull-right" value="">
	                            <span class="input-group-addon cursor" id="addorg">
	                            	<i class="fa fa-plus"></i>
	                            </span>
                            </div>
						
					</div>

					<div class="form-group required col-md-6 ">
						<label for="Designation" class="col-md-4 control-label">Designation</label>
						<div class="col-md-8">
                           <!-- <select id="designation_id" name="designation_id" style="display: none;">-->
                           <select id="designation_id" name="designation_id" class="chosen-select" tabindex="5" data-placeholder="Select Date Range">
                            	<option value="">-Designation-</option>
                               <?php 
                        		if(is_array($alldata['designations']) && count($alldata['designations']) > 0)
                        		{
                        			foreach ($alldata['designations'] as $key => $designation) 
                        			{
                        		?>			
                        				<option value="<?php echo $designation['designation_id']; ?>" <?php echo isset($userdata[0]['designation_id']) && $userdata[0]['designation_id'] == $designation['designation_id'] ? 'selected="selected"' : ''; ?>><?php echo $designation['designation_name']; ?></option>
                        	<?php	}
                        		}	?>
                            </select>

						</div>
					</div>

					<div class="form-group col-md-12">
					<label class="col-md-4 control-label"></label>
						<div class="col-xs-2">
                            <?php if ($userid != '')
							{
							    ?>
							    <button id="userupdate" type="button" class="btn btn-success btn-block">Update</button>
                            <?php }
							else
							{
							    ?>
                                <button id="usersave" type="button" class="btn btn-success btn-block">Submit</button>
                            <?php }?>
						</div>
						<div class="col-xs-2">
								<?php 
									if($userid != '')
										$reid = "editreset";
									else
										$reid = "addreset";
								 ?>
							<button id="<?php echo $reid ?>"  type="button" class="btn btn-info btn-block">Reset</button>
						</div>
				</div>
			</form>
		</div>
	</div>
</div>
