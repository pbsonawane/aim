
<div>
	<table class="table table-striped table-bordered table-hover table-responsive ">
		<thead>
		  <tr>
			<th class="srno">Sr.No.</th>
			<th>Business Vertical Name</th>
            <th>Business Unit Name</th>
			<th>Description</th>
			<th>Action</th>
		  </tr>
		</thead>
		<tbody>
			<?php
			$businessverticals = $dbdata;
			if (is_array($businessverticals) && count($businessverticals) > 0)
			{
				foreach($businessverticals as $i => $businessvertical)
				{	
					$id = $businessvertical['bv_id'];
					$delete = '';
					$edit = '<span title = "Click To Edit Record" name="edit_b" id="edit_'.$id.'" type="button" data-businessverticalid="'.$id.'" class="businessvertical_edit" id="edit_b"><i class="fa fa-edit mr10 fa-lg"></i></span>'; 
					
					$delete = '<span title = "Click To Delete Record" type="button" id="delete_'.$id.'" data-businessverticalid="'.$id.'" class="businessvertical_del" id="delete_b"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';	
						
			?>
			<tr>
				<td class="srno"><?php echo $i + $offset + 1?></td>
				<td><?php echo $businessvertical['bv_name']; ?></td>
                <td><?php echo $businessvertical['bu_name']; ?></td>
				<td><?php echo $businessvertical['bv_description']; ?></td>
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