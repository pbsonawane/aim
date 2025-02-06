<div>
	<table class="table table-striped table-bordered table-hover">
		<thead>
		  <tr>
			<th class="text-center"><?php echo trans('label.lbl_srno');?></th>
			<th><?php echo trans('label.lbl_software_manufacturer');?></th>
			<th><?php echo trans('label.lbl_description');?></th>
			<th><?php echo trans('label.lbl_action');?></th>
		  </tr>
		</thead>
		<tbody>
			<?php
			$softwaremanufacturers = $dbdata;
			if (is_array($softwaremanufacturers) && count($softwaremanufacturers) > 0)
			{
				foreach($softwaremanufacturers as $i => $softwaremanufacturer)
				{	
                    $id 	= $softwaremanufacturer['software_manufacturer_id'];
					$delete = '';
					$edit 	= '';
					
					if(canuser('update','softwaremanufacturer')){
					$edit = '<span title = "'.trans('label.click_to_edit_record').'" name="edit_b" id="edit_'.$id.'" type="button" data-softwaremanufacturerid="'.$id.'" class="softwaremanufacturer_edit" id="edit_b"><i class="fa fa-edit mr10 fa-lg"></i></span>'; 
					}
					if(canuser('delete','softwaremanufacturer')){
					$delete = '<span title = "'.trans('label.click_to_delete_record').'" type="button" id="delete_'.$id.'" data-softwaremanufacturerid="'.$id.'" class="softwaremanufacturer_del" id="delete_b"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';	
					}	
			?>
			<tr>
				<td class="text-center"><?php echo $i + $offset + 1?></td>
				<td><?php echo $softwaremanufacturer['software_manufacturer']; ?></td>
				<td><?php echo $softwaremanufacturer['description']; ?></td>
                <td><?php 
					if(!empty(config('app.env')) && config('app.env') == 'production' && isset($softwaremanufacturer['is_default']) && $softwaremanufacturer['is_default'] == 'n'){
							echo $edit.' '.$delete; 
					}
					elseif(!empty(config('app.env')) && config('app.env') != 'production'){
							echo $edit.' '.$delete;
					}
				?>
				</td>
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