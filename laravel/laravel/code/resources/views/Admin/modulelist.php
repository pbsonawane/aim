
<div>
	<table class="table table-striped table-bordered table-hover table-responsive ">
		<thead>
		  <tr>
			<th class="srno">Sr.No.</th>
			<th>Module Name</th>
			<th>Module Key</th>
			<th>Action</th>
		  </tr>
		</thead>
		<tbody>
			<?php
			$modules = $dbdata;
			if (is_array($modules) && count($modules) > 0)
			{
				foreach($modules as $i => $module)
				{
					$id = $module['module_id'];
					$delete = '';
					$edit = '<span title = "Click To Edit Record" name="edit_b" id="edit_'.$id.'" type="button" data-moduleid="'.$id.'" class="module_edit" id="edit_b"><i class="fa fa-edit mr10 fa-lg"></i></span>'; 
					
					if($module['module_key'] != 'IAM')
					$delete = '<span title = "Click To Delete Record" type="button" id="delete_'.$id.'" data-moduleid="'.$id.'" class="module_del" id="delete_b"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';
						
		?>
			<tr>
				<td class="srno"><?php echo $i + $offset + 1?></td>
				<td><?php echo $module['module_name']; ?></td>
				<td><?php echo $module['module_key']; ?></td>
				<td><?php echo $edit.' '.$delete; ?></td>
			</tr>
			<?php
				}
			}
			else
				echo '<tr><td colspan="100" align="center"> No Records</td></tr>';
				?>
		</tbody>
	</table>
</div>
