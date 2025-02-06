<div>
	<table class="table table-striped table-bordered table-hover">
		<thead>
		  <tr>
			<th class="text-center"><?php echo trans('label.lbl_srno');?></th>
			<th><?php echo trans('label.lbl_software_name');?></th>
			<th><?php echo trans('label.lbl_software_type');?></th>
			<th><?php echo trans('label.lbl_software_category');?></th>
			<th><?php echo trans('label.lbl_software_manufacturer');?></th>
			<!--<th><?php echo trans('label.lbl_license_type');?></th>-->
			<th><?php echo trans('label.lbl_ci_type');?></th>
            <th><?php echo trans('label.lbl_version');?></th>
			<th><?php echo trans('label.lbl_description');?></th>
			<th><?php echo trans('label.lbl_action');?></th>
		  </tr>
		</thead>
		<tbody>
			<?php
			$softwares = $dbdata;
			if (is_array($softwares) && count($softwares) > 0)
			{
				foreach($softwares as $i => $software)
				{	
                    $id = $software['software_id'];
					$delete = '';
					$edit = '';
					if(canuser('update','software')){ 
					$edit = '<span title = "Click To Edit Record" name="edit_b" id="edit_'.$id.'" type="button" data-softwareid="'.$id.'" class="software_edit" id="edit_b"><i class="fa fa-edit mr10 fa-lg"></i></span>'; 
					}
					if(canuser('delete','software')){ 
					$delete = '<span title = "Click To Delete Record" type="button" id="delete_'.$id.'" data-softwareid="'.$id.'" class="software_del" id="delete_b"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';			
					}
			?>
			<tr>
				<td class="text-center"><?php echo $i + $offset + 1?></td>
                <td>
                <a href="<?php echo url('softwarelistdetails', $id) ?>" ><?php echo $software['software_name']; ?></a>
                </td>
                <td><?php echo $software['software_type']; ?></td>
                <td><?php echo $software['software_category']; ?></td> 
                <td><?php echo $software['software_manufacturer']; ?></td>
                <!--<td><?php echo $software['license_type']; ?></td> -->
                <td><?php echo $software['ci_type']; ?></td>
				<td><?php echo $software['version']; ?></td>
				<td><?php echo $software['description']; ?></td>
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