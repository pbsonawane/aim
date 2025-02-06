
<div>
	<table class="table table-striped table-bordered table-hover table-responsive ">
		<thead>
		  <tr>
			<th class="srno">Sr.No.</th>
			<th>POD Name</th>
			<th>POD Description</th>
			<th>Region</th>
			<th>Datacenter</th>
			<th>Action</th>
		  </tr>
		</thead>
		<tbody>
			<?php
			$pods = $dbdata;
			if (is_array($pods) && count($pods) > 0)
			{
				foreach($pods as $i => $pod)
				{
					$id = $pod['pod_id'];
					$delete = '';
					$edit = '<span title = "Click To Edit Record" name="edit_b" id="edit_'.$id.'" type="button" data-podid="'.$id.'" class="pod_edit" id="edit_b"><i class="fa fa-edit mr10 fa-lg"></i></span>'; 
					
					$delete = '<span title = "Click To Delete Record" type="button" id="delete_'.$id.'" data-podid="'.$id.'" class="pod_del" id="delete_b"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';
						
		?>
			<tr>
				<td class="srno"><?php echo $i + $offset + 1?></td>
				<td><?php echo $pod['pod_name']; ?></td>
				<td><?php echo $pod['pod_description']; ?></td>
				<td><?php echo $pod['region_name']; ?></td>
				<td><?php echo $pod['dc_name']; ?></td>
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
