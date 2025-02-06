
<div>
	<table class="table table-striped table-bordered table-hover">
		<thead>
		  <tr>
			<th class="text-center">Sr.No.</th>
			<th>Datacenter Name</th>
			<th>Region Name</th>
			<th>Description</th>
			<th>Action</th>
		  </tr>
		</thead>
		<tbody>
			<?php
			$dcs = $dbdata;
			
			if (is_array($dcs) && count($dcs) > 0)
			{
				foreach($dcs as $i => $dc)
				{		
					$id = $dc['dc_id'];
					$delete = '';
					$edit = '<span title = "Click To Edit Record" name="edit_b" id="edit_'.$id.'" type="button" data-moduleid="'.$id.'" class="module_edit" id="edit_b"><i class="fa fa-edit mr10 fa-lg"></i></span>'; 
					
					$delete = '<span title = "Click To Delete Record" type="button" id="delete_'.$id.'" data-moduleid="'.$id.'" class="module_del" id="delete_b"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';
							
			?>
			<tr>
				
				<td class="text-center"><?php echo $i + $offset + 1?></td>
				<td><?php echo $dc['dc_name']; ?></td>
				<td><?php echo $dc['region_name']; ?></td>
				<td><?php echo $dc['dc_description']; ?></td>
				<td><?php echo $edit.' '.$delete; ?></td>
			</tr>
			<?php
				}
			}
			else
				echo '<tr><td colspan = "100" class="text-center"> No Records</td></tr>';
				?>	
		</tbody>
	</table>
</div>