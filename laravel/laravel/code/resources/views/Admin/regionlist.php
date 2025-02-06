
<div class="table-wrapper-scroll-y">
	<table class="table table-striped table-bordered table-hover table-responsive ">
		<thead>
		  <tr>
			<th class="srno">Sr.No.</th>
			<th>Region Name</th>
			<th>Description</th>
			<th>Action</th>
		  </tr>
		</thead>
		<tbody>
			<?php
			$regions = $dbdata;
			if (is_array($regions) && count($regions) > 0)
			{
				foreach($regions as $i => $region)
				{
					$id = $region['region_id'];
					$delete = $assing_dc = '';
					$edit = '<span title = "Click To Edit Record" name="edit_b" id="edit_'.$id.'" type="button" data-regionid="'.$id.'" class="region_edit" ><i class="fa fa-edit mr10 fa-lg"></i></span>'; 
					
					$delete = '<span title = "Click To Delete Record" type="button" id="delete_'.$id.'" data-regionid="'.$id.'" class="region_del"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';
					
					$assing_dc = '<span title = "Assign DC to Region" type="button" id="assign_'.$id.'" data-regionname="'.$region['region_name'].'" class="region_dc_assign" id="assign_b"><i class="fa fa-check-square-o mr10 fa-lg"></i></span>';
						
		?>
			<tr>
				<td class="srno"><?php echo $i + $offset + 1?></td>
				<td><?php echo $region['region_name']; ?></td>
				<td><?php echo $region['region_description']; ?></td>
				<td><?php echo $edit.' '.$assing_dc.' '.$delete; ?></td>
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
