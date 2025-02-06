<div>
	<table class="table table-striped table-bordered table-hover">
		<thead>
		  <tr>
			<th><?php echo trans('label.lbl_enable_disable');?></th>
			<th><?php echo trans('label.lbl_template_name');?></th>
            <th><?php echo trans('label.lbl_template_category');?></th>
			<th><?php echo trans('label.lbl_configure_email_id');?></th>
      <?php /*        <th><?php echo trans('label.lbl_email_body');?></th><?php */?>
            
			<th><?php echo trans('label.lbl_action');?></th>
            
		  </tr>
		</thead>
		<tbody>
			<?php
			$emailtemplates = $dbdata;
			if (is_array($emailtemplates) && count($emailtemplates) > 0)
			{
				foreach($emailtemplates as $i => $emailtemplates)
				{	
                    $id = $emailtemplates['template_id'];
					$delete = '';
					$edit = '';

					if(canuser('update','emailtemplate')){
					$edit = '<span title = "Click To Edit Record" name="edit_b" id="edit_'.$id.'" type="button" data-template_id="'.$id.'" class="template_edit" id="edit_b"><i class="fa fa-edit mr10 fa-lg"></i></span>'; 
					}
					if(canuser('delete','emailtemplate')){
					$delete = '<span title = "Click To Delete Record" type="button" id="delete_'.$id.'" data-templateid="'.$id.'" class="template_del" id="delete_b"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';		
					}	
			?>
			<tr>
				<td class="text-center">
					<?php if(canuser('update','emailtemplate')){?>
					<div class="switch">
  <input type="checkbox" onchange="changestatus(this.id);"class="custom-control-input" id="customSwitch_<?php echo $emailtemplates['template_id']; ?>"  <?php if($emailtemplates['status'] == 'e') { echo "checked"; } ?> >
  <label class="custom-control-label" for="customSwitch_<?php echo $emailtemplates['template_id']; ?>">Disbale</label>
</div><?php //echo $i + $offset + 1?>
	<?php }?>
</td>
				<td><?php echo $emailtemplates['template_name']; ?></td>
				<td><?php echo $emailtemplates['template_category']; ?></td>
				<td><?php if($emailtemplates['configure_email_id'] == 'y') { echo "Yes"; }else{ echo "No"; } ?></td>
				<?php /*?><td><?php echo $emailtemplates['email_ids']; ?></td>
				<td><?php echo $emailtemplates['email_body']; ?></td><?php */ ?>
                <td><?php echo $edit.' '.$delete; ?></td>
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