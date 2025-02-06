
<div>
	<table class="table table-striped table-bordered table-hover">
		<thead>
		  <tr>
			<th class="text-center"><?php echo trans('label.lbl_srno');?></th>
			<th width="8%">Vendor Id</th> 
			<th><?php echo trans('label.lbl_vendor_name');?></th>
            <th><?php echo trans('label.lbl_contact_person');?></th>
			<th><?php echo trans('label.lbl_contact_no');?></th>
            <th><?php echo trans('label.lbl_address');?></th>
            <th>Status</th>
			<th><?php echo trans('label.lbl_action');?></th>
		  </tr>
		</thead>
		<tbody>
			<?php
			$vendors = $dbdata;	
			if (is_array($vendors) && count($vendors) > 0)
			{
				foreach($vendors as $i => $vendor)
				{	
                    $id = $vendor['vendor_id'];
					$delete = '';
					$edit = '';
					$view = '';

					if (canuser('view', 'vendor'))		
					{
					$view = '<a href="vendor/view/'.$id.'" target="_blank" type="button" id="vendor_view" class="vendor_view">
					<i class="fa fa-eye fa-md"></i></a>';
					}
					if(canuser('edit','vendor')){
					$edit = '<span title = "Click To Edit Record" name="edit_b" id="edit_'.$id.'" type="button" data-vendorid="'.$id.'" class="vendor_edit" id="edit_b"><i class="fa fa-edit fa-md"></i></span>'; 
					}
					if(canuser('delete','vendor')){
					$delete = '<span title = "Click To Delete Record" type="button" id="delete_'.$id.'" data-vendorid="'.$id.'" class="vendor_del" id="delete_b"><i class="fa fa-trash-o fa-md"></i></span>';			
					}

					$label_status     = '';
					if($vendor['approve_status'] != 'null') { 
			            $approve_data     = json_decode($vendor['approve_status'], true);
			            $status_data      = ucfirst($approve_data['approval_status']);

			            if(isset($status_data)) {
				            if($status_data == "Approve") {
				                $label_status .= '<label class="text-success">'.$status_data.'d</label>';
				            } else {
				                $label_status .= '<label class="text-danger">'.$status_data.'d</label>';
				            }
			            } 
			        }

			?>
			<tr>
				<td class="text-center"><?php echo $i + $offset + 1; ?></td>
				<td><?php echo ($vendor['vendor_unique_id']) ? $vendor['vendor_unique_id'] : ''; ?></td> 
				<td><?php echo $vendor['vendor_name']; ?></td>
				<td><?php echo $vendor['contact_person']; ?></td>
				<td><?php echo $vendor['contactno']; ?></td>
				<td><?php echo $vendor['address']; ?></td>
				<td><?php echo $label_status; ?></td>
                <td><?php echo $view.' '.$edit.' '.$delete; ?></td>
			</tr>
			<?php
				}
			}
			else
				echo '<tr><td colspan = "100" style="text-align:center">'.trans('messages.msg_norecordfound').'</td></tr>';
				?>	
		</tbody>
	</table>
</div>