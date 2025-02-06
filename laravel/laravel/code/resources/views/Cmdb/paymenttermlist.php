<div>
	<table class="table table-striped table-bordered table-hover">
		<thead>
		  <tr>
			<th class="text-center"><?php echo trans('label.lbl_srno'); ?></th>
			<th><?php echo trans('label.lbl_payment_term'); ?></th>
			<th><?php echo trans('label.lbl_action'); ?></th>
		  </tr>
		</thead>
		<tbody>
		<?php
		$paymentterms = $dbdata;
		if (is_array($paymentterms) && count($paymentterms) > 0) {
		    foreach ($paymentterms as $i => $paymentterm) {
		        $id     = $paymentterm['paymentterm_id'];
		        $delete = '';
		        $edit   = '';

        if (canuser('edit', 'paymentterm')) {
            $edit = '<span title = "Click To Edit Record" name="edit_b" id="edit_' . $id . '" type="button" data-paymenttermid="' . $id . '" class="paymentterm_edit" id="edit_b"><i class="fa fa-edit mr10 fa-lg"></i></span>';
        }
        if (canuser('delete', 'paymentterm')) {
            $delete = '<span title = "Click To Delete Record" type="button" id="delete_' . $id . '" data-paymenttermid="' . $id . '" class="paymentterm_del" id="delete_b"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';
        }
        ?>
			<tr>
				<td class="text-center"><?php echo $i + $offset + 1 ?></td>
				<td><?php echo $paymentterm['payment_term']; ?></td>
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