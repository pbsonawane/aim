
<div>
	<table class="table table-striped table-bordered table-hover">
		<thead>
		  <tr>
			<th class="text-center">SrNo</th>
			<th>Location Name</th>
			<th>Regions</th>
			<th>Description</th>
			<th>Action</th>
		  </tr>
		</thead>
		<tbody>
			<?php
			$locs = $dbdata;
			if (is_array($locs) && count($locs) > 0)
			{
				foreach($locs as $i => $loc)
				{			
					$id = $loc['location_id'];
					$delete = '';
					$edit = '<span title = "Click To Edit Record" name="edit_b" id="edit_'.$id.'" type="button" data-moduleid="'.$id.'" class="module_edit" id="edit_b"><i class="fa fa-edit mr10 fa-lg"></i></span>'; 
					
					$delete = '<span title = "Click To Delete Record" type="button" id="delete_'.$id.'" data-moduleid="'.$id.'" class="module_del" id="delete_b"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';
			?>
			<tr>
				
				<td class="text-center"><?php echo $i + $offset + 1?></td>
				<td><?php echo $loc['location_name']; ?></td>
				<td><?php echo $loc['region_name'] ?></td>
				<td><?php echo $loc['location_description'] ?></td>
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