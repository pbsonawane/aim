<div>
	<table class="table table-striped table-bordered table-hover">
		<thead>
		  <tr>
			<th class="text-center"><?php echo trans('label.lbl_srno');?></th>
			<th><?php echo trans('label.lbl_license_type');?></th>
			<th><?php echo trans('label.lbl_installation_allow');?></th>
            <th><?php echo trans('label.lbl_is_perpetual');?></th>
            <th><?php echo trans('label.lbl_is_free');?></th>
			<th><?php echo trans('label.lbl_action');?></th>
		  </tr>
		</thead>
		<tbody>
			<?php
			$licensetypes = $dbdata;
			if (is_array($licensetypes) && count($licensetypes) > 0)
			{
				foreach($licensetypes as $i => $licensetype)
				{	
                    $id = $licensetype['license_type_id'];
					$delete = '';
					$edit= '';
					
					if(canuser('update','licensetype')){
					$edit = '<span title = "'.trans('label.click_to_edit_record').'" name="edit_b" id="edit_'.$id.'" type="button" data-licensetypeid="'.$id.'" class="licensetype_edit" id="edit_b"><i class="fa fa-edit mr10 fa-lg"></i></span>'; 
					}
					if(canuser('delete','licensetype')){
					$delete = '<span title = "'.trans('label.click_to_delete_record').'" type="button" id="delete_'.$id.'" data-licensetypeid="'.$id.'" class="licensetype_del" id="delete_b"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';
					}

			?>
			<tr>
				<td class="text-center"><?php echo $i + $offset + 1?></td>
				<td><?php echo $licensetype['license_type']; ?></td>
				<td><?php echo $licensetype['installation_allow']; ?></td>
				<td><?php if($licensetype['is_perpetual'] == 'y'){ echo trans('label.lbl_yes'); }else{ echo trans('label.lbl_no'); }?></td>
				<td><?php if($licensetype['is_free'] == 'y'){ echo trans('label.lbl_yes'); }else{ echo trans('label.lbl_no'); }?></td>
                <td><?php 
					if(!empty(config('app.env')) && config('app.env') == 'production' && isset($licensetype['is_default']) && $licensetype['is_default'] == 'n'){
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