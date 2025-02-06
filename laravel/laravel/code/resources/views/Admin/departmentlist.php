
<div>
	<table class="table table-striped table-bordered table-hover">
		<thead>
		  <tr>
			<th class="text-center">Sr.No.</th>
			<th>Department Name</th>
			<th>Status</th>
			<th>Action</th>
		  </tr>
		</thead>
		<tbody>
			<?php
			$departments = $dbdata;
			if (is_array($departments) && count($departments) > 0)
			{
				foreach($departments as $i => $department)
				{	
                    $id = $department['department_id'];
					$delete = '';
					$edit = '<span title = "Click To Edit Record" name="edit_b" id="edit_'.$id.'" type="button" data-departmentid="'.$id.'" class="department_edit" id="edit_b"><i class="fa fa-edit mr10 fa-lg"></i></span>'; 
					
                    $delete = '<span title = "Click To Delete Record" type="button" id="delete_'.$id.'" data-departmentid="'.$id.'" class="department_del" id="delete_b"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';		
			?>
			<tr>
				
				<td class="text-center"><?php echo $i + $offset + 1?></td>
				<td><?php echo $department['department_name']; ?></td>
				<td><?php echo $department['status']; ?></td>
                <td><?php echo $edit.' '.$delete; ?></td>
			</tr>
			<?php
				}
			}
			else
				echo '<tr><td colspan = "100"> No Records</td></tr>';
				?>	
		</tbody>
	</table>
</div>