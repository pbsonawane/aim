
<div>
	<table class="table table-striped table-bordered table-hover table-responsive ">
		<thead>
		  <tr>
            <th class="srno"><?php echo trans('label.srno'); ?></th>
            <th><?php echo trans('label.lbl_template_title'); ?></th>
            <th><?php echo trans('label.lbl_template_name'); ?></th>
            <th><?php echo trans('label.lbl_description'); ?></th>
            <th><?php echo trans('label.lbl_type'); ?></th>
			<th><?php echo trans('label.lbl_default_template'); ?></th>
			<th><?php echo trans('label.action'); ?></th>
		  </tr>
		</thead>
		<tbody>
			<?php
			$regions = $dbdata;
			if (is_array($regions) && count($regions) > 0)
			{
				foreach($regions as $i => $region)
				{
					$id = $region['form_templ_id'];
					$copy = $edit = $delete = '';
					if(canuser('update','settingstemplate')){
					$edit = '<span title = "'.trans('label.click_to_edit_record').'" name="edit_b" id="edit_'.$id.'" type="button" data-id="'.$id.'" class="settingtemplate_edit"><i class="fa fa-edit mr10 fa-lg"  data-id="'.$id.'"></i></span>';
					}
					//if(canuser('advance','clone'))
                    $copy = '<span title = "'.trans('label.click_to_clone_record').'" type="button" id="copy_'.$id.'" data-id="'.$id.'" class="settingtemplate_copy" id="copy_c"><i class="fa fa-copy mr10 fa-lg"></i></span>';
                	if(canuser('delete','settingstemplate')){
					$delete = '<span title = "'.trans('label.click_to_delete_record').'" type="button" id="delete_'.$id.'" data-id="'.$id.'" class="settingtemplate_delete" id="delete_b"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';
					}
			?>
			<tr>
                <td class="srno"><?php echo $i + $offset + 1?></td>
                <td><?php echo $region['template_title']; ?></td>
                <td><?php echo $region['template_name']; ?></td>
                <td><?php echo $region['description']; ?></td>				
                <td><?php echo $region['type']; ?></td>
                <td><?php echo $region['default_template']=='y'? 'Yes' : 'No'; ?></td>
				<td><?php echo $edit.' '.$copy.' '.$delete; ?></td>
			</tr>
			<?php
				}
			}
			else
                echo '<tr><td colspan="100" align="center">'.showmessage('101', array('{name}'), array(trans('label.lbl_setting_templates')), true).'</td></tr>';
                 
				?>
		</tbody>
	</table>
</div>
