<div>
	<table class="table table-striped table-bordered table-hover">
		<thead>
		  <tr>
			<th class="text-center"><?php echo trans('label.lbl_srno'); ?></th>
			<th><?php echo trans('label.lbl_department'); ?></th>
			<th><?php echo trans('label.lbl_requestername_full_name'); ?></th>
			<th><?php echo trans('label.lbl_requestername_employee_id'); ?></th>
			<th><?php echo trans('label.lbl_action'); ?></th>
		  </tr>
		</thead>
		<tbody>
		<?php
		$requesternames = $dbdata;
		/*echo '<pre>';
		print_r($requesternames);*/
		if (is_array($requesternames) && count($requesternames) > 0) {
		    foreach ($requesternames as $i => $requestername) {
		        $id     = $requestername['requestername_id'];
		        $delete = '';
		        $edit   = '';

        if (canuser('edit', 'requestername')) {
            $edit = '<span title = "Click To Edit Record" name="edit_b" id="edit_' . $id . '" type="button" data-requesternameid="' . $id . '" class="requestername_edit" id="edit_b"><i class="fa fa-edit mr10 fa-lg"></i></span>';
        }
        if (canuser('delete', 'requestername')) {
            $delete = '<span title = "Click To Delete Record" type="button" id="delete_' . $id . '" data-requesternameid="' . $id . '" class="requestername_del" id="delete_b"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';
        }
        ?>
			<tr>
				<td class="text-center"><?php echo $i + $offset + 1 ?></td>
				<td><?php echo @$requestername['department_name']; ?></td>
				<td><?php echo ucwords($requestername['prefix'] . '. ' . $requestername['fname'] . ' ' . $requestername['lname']); ?></td>
				<td><?php echo $requestername['employee_id']; ?></td>
                <td><?php echo $edit . ' ' . $delete; ?></td>
			</tr>
			<?php
			}
		} else {
		    echo '<tr><td colspan = "100" style="text-align:center">' . trans('messages.msg_norecordfound') . '</td></tr>';
		}
       ?>
		</tbody>
	</table>
</div>