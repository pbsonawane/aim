<?php 
	$display_cols = array('designation_name' => 'Designation','organization_name' => 'Organization','department_name' => 'Department','user_type' => 'User Type','contactno' => 'Contact No.','role_name' => 'Role','manager' => 'Manager');

	$show_cols = $columns;

?>
<div>
	<table class="table table-striped table-bordered table-hover table-responsive">
		<thead>
		  <tr>
			<th class="srno">Sr.No.</th>
			<th>Full Name</th>
			<th>Email</th>
			<?php 
			if(is_array($show_cols) && count($show_cols) > 0) 
			{ 
				for($i=0;$i<count($show_cols);$i++)
				{ ?>
					<th><?php echo $display_cols[$show_cols[$i]];?></th>	
			  <? }
			}
			?>
			
			<!--
			<th>Role</th>
			<th>Manager</th> -->
			
			
            <th>Status</th>
            <th>Action</th>
		  </tr>
		</thead>
		<tbody>
			<?php
                $users = $dbdata;
        
                if (is_array($users) && count($users) > 0)
                {
                    foreach ($users as $i => $user)
                    {
                        $rolename = '';
                        $id = $user['user_id'];
                        $delete = '';
                        $edit = '<span title = "Click To Edit Record" name="edit_b" id="edit_'.$id.'" type="button" data-moduleid="'.$id.'" class="useredit"><i class="fa fa-edit mr10 fa-lg"></i></span>';
                        $delete = '<span title = "Click To Delete Record" type="button" id="delete_'.$id.'" data-moduleid="'.$id.'" class="userdelete"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';
						
						$user_info = json_encode($user);
                        $custatus = '';
                        if($user['status'] == 'y')
                            $custatus ='<span class="badge badge-success">Active</span>';
                        else if($user['status'] == 's')
                            $custatus ='<span class="badge badge-danger">Suspend</span>';
						
						if(is_array($user['role_name']) && count($user['role_name']) > 0 )
						{
							foreach($user['role_name'] as $rname)
								$rolename .= $rname.', ';
						}
                        ?>	
						
                            <tr>
                                <td class="srno"><?php echo $i + $offset + 1 ?></td>
                                <td><?php echo  ucfirst($user['firstname']).' '. ucfirst($user['lastname']); ?></td>
                                <td><?php echo $user['email']; ?></td>
								
								<?php 
								if(is_array($show_cols) && count($show_cols) > 0) 
								{ 
									for($i=0;$i<count($show_cols);$i++)
									{ 		
										$show_data = "";
										if($show_cols[$i] == 'role_name')
											$show_data = trim($rolename,', ');
										elseif($show_cols[$i] == 'manager')	
											$show_data = ucfirst($user['mgrfirstname']).' '. ucfirst($user['mgrlastname']);
                                        elseif($show_cols[$i] == 'user_type') 
                                            $show_data = ucfirst($user[$show_cols[$i]]);
										else
											$show_data = $user[$show_cols[$i]];	
									?>
										<td><?php echo $show_data;?></td>	
								  <? }
								}
								?>
								
								<!--
                                 <td><?php echo trim($rolename,', ');?></td>
                                 <td><?php echo  ucfirst($user['mgrfirstname']).' '. ucfirst($user['mgrlastname']); ?></td>
								 -->
								 
								 
                                <td><?php echo $custatus; ?></td>
                                <td>
									<textarea id="info_<?php echo $id;?>" style="display:none"><?php echo $user_info;?></textarea>
									<div class="btn-group">
									
									<span id="timeline-toggle" class=" dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="fa fa-gear mr10 fa-lg"></i></span>
					
									<ul class="dropdown-menu pull-right" user="menu">
										<li id="assignentities" class="assignentities" data-userinfo="<?php echo $id;?>">
											<a class="dropdown-item ccursor">Assign Entities</a>
										</li>
                                        <li id="eduser" class="eduser" data-userinfo="<?php echo $id;?>">
                                            <a class="dropdown-item ccursor">Edit User</a>
                                        </li>
                                        <li id="cng_pass" class="cng_pass" data-userinfo="<?php echo $id;?>">
                                            <a class="dropdown-item ccursor">Change Password</a>
                                        </li>
                                        <?php if( $user['status'] == 'y') {?>
                                            <li id="sus_user" class="sus_user"  data-userinfo="<?php echo $id;?>">
                                                <a class="dropdown-item ccursor">Suspend User</a>
                                            </li>
                                        <?php } ?>
                                        <?php if( $user['status'] == 's') {?>
                                            <li id="react_user" class="react_user"  data-userinfo="<?php echo $id;?>">
                                                <a class="dropdown-item ccursor">Activate User</a>
                                            </li>
                                            <li id="delete_user" class="del_user"  data-userinfo="<?php echo $id;?>">
                                                <a class="dropdown-item ccursor">Delete User</a>
                                            </li>
                                        <?php } ?>
									</ul>
								</div>
								</td>
                            </tr>
                        <?php
                    }
                }
                else
                {
                    echo '<tr><td colspan="5" align="center"> No Records</td></tr>';
                }
            ?>
		</tbody>
	</table>
</div>
