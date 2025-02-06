<div>
	<table class="table table-striped table-bordered table-hover">
		<thead>
		  <tr>
			<th class="text-center"><?php echo trans('label.lbl_srno'); ?></th>
			<th><?php echo trans('label.lbl_delivery'); ?></th>
			<th><?php echo trans('label.lbl_action'); ?></th>
		  </tr>
		</thead>
		<tbody>
		<?php
		$delivery = $dbdata;
		if (is_array($delivery) && count($delivery) > 0) {
		    foreach ($delivery as $i => $delivery) {
		        $id     = $delivery['delivery_id'];
		        $delete = '';
		        $edit   = '';

        if (canuser('edit', 'delivery')) {
            $edit = '<span title = "Click To Edit Record" name="edit_b" id="edit_' . $id . '" type="button" data-deliveryid="' . $id . '" class="delivery_edit" id="edit_b"><i class="fa fa-edit mr10 fa-lg"></i></span>';
        }
        if (canuser('delete', 'delivery')) {
            $delete = '<span title = "Click To Delete Record" type="button" id="delete_' . $id . '" data-deliveryid="' . $id . '" class="delivery_del" id="delete_b"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';
        }
        ?>
			<tr>
				<td class="text-center"><?php echo $i + $offset + 1 ?></td>
				<td><?php echo $delivery['delivery']; ?></td>
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