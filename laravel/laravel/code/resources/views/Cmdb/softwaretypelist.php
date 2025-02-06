<div>
	<table class="table table-striped table-bordered table-hover">
		<thead>
		  <tr>
			<th class="text-center"><?php echo trans('label.lbl_srno');?></th>
			<th><?php echo trans('label.lbl_software_type');?></th>
			<th><?php echo trans('label.lbl_description');?></th>
			<th><?php echo trans('label.lbl_action');?></th>
		  </tr>
		</thead>
		<tbody>
			<?php
			$softwaretypes = $dbdata;
			if (is_array($softwaretypes) && count($softwaretypes) > 0)
			{
				foreach($softwaretypes as $i => $softwaretype)
				{	
                    $id 	= $softwaretype['software_type_id'];
					$delete = '';
					$edit 	= '';

					if(canuser('update','softwaretype')){
					$edit = '<span title = "'.trans('label.click_to_edit_record').'" name="edit_b" id="edit_'.$id.'" type="button" data-softwaretypeid="'.$id.'" class="softwaretype_edit" id="edit_b"><i class="fa fa-edit mr10 fa-lg"></i></span>'; 
					}
					if(canuser('delete','softwaretype')){
					$delete = '<span title = "'.trans('label.click_to_delete_record').'" type="button" id="delete_'.$id.'" data-softwaretypeid="'.$id.'" class="softwaretype_del" id="delete_b"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';
					}	
			?>
			<tr>
				<td class="text-center"><?php echo $i + $offset + 1?></td>
				<td><?php echo $softwaretype['software_type']; ?></td>
				<td><?php echo $softwaretype['description']; ?></td>
                <td><?php 
					if(!empty(config('app.env')) && config('app.env') == 'production' && isset($softwaretype['is_default']) && $softwaretype['is_default'] == 'n'){
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