<div>
	<table class="table table-striped table-bordered table-hover">
		<thead>
		  <tr>
			<th class="text-center"><?php echo trans('label.lbl_srno'); ?></th>
			<th><?php echo trans('label.lbl_contact_full_name'); ?></th>
			<th><?php echo trans('label.lbl_contact1'); ?></th>
            <th><?php echo trans('label.lbl_contact2'); ?></th>
			<th><?php echo trans('label.Email'); ?></th>
            <th><?php echo trans('label.lbl_associated_with'); ?></th>
			<th><?php echo trans('label.lbl_action'); ?></th>
		  </tr>
		</thead>
		<tbody>
		<?php
		$contacts = $dbdata;
		/*echo '<pre>';
		print_r($contacts);*/
		if (is_array($contacts) && count($contacts) > 0) {
		    foreach ($contacts as $i => $contact) {
		        $id     = $contact['contact_id'];
		        $delete = '';
		        $edit   = '';

        if (canuser('edit', 'contact')) {
            $edit = '<span title = "Click To Edit Record" name="edit_b" id="edit_' . $id . '" type="button" data-contactid="' . $id . '" class="contact_edit" id="edit_b"><i class="fa fa-edit mr10 fa-lg"></i></span>';
        }
        if (canuser('delete', 'contact')) {
            $delete = '<span title = "Click To Delete Record" type="button" id="delete_' . $id . '" data-contactid="' . $id . '" class="contact_del" id="delete_b"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';
        }
        ?>
			<tr>
				<td class="text-center"><?php echo $i + $offset + 1 ?></td>
				<td><?php echo ucwords($contact['prefix'] . '. ' . $contact['fname'] . ' ' . $contact['lname']); ?></td>
				<td><?php echo $contact['contact1']; ?></td>
				<td><?php echo $contact['contact2']; ?></td>
				<td><?php echo $contact['email']; ?></td>
				<td><?php echo $contact['associated_with']; ?></td>
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