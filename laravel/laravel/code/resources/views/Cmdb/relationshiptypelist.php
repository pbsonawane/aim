<div>
	<table class="table table-striped table-bordered table-hover">
		<thead>
		  <tr>
			<th class="text-center"><?php echo trans('label.lbl_srno');?></th>
			<th><?php echo trans('label.lbl_relationshiptype');?></th>
			<th><?php echo trans('label.lbl_inverserelationtype');?></th>
			<th><?php echo trans('label.lbl_relationshipdesc');?></th>
			<th><?php echo trans('label.lbl_action');?></th>
		  </tr>
		</thead>
		<tbody>
			<?php
			$relationshiptypes = $dbdata;
			if (is_array($relationshiptypes) && count($relationshiptypes) > 0)
			{
				foreach($relationshiptypes as $i => $relationshiptype)
				{	
                    $id 	= $relationshiptype['rel_type_id'];
					$delete = '';
					$edit 	= '';

					if(canuser('update','relationshiptype')){ 
					$edit 	= '<span title = "'.trans('label.click_to_edit_record').'" name="edit_b" id="edit_'.$id.'" type="button" data-relationshiptypeid="'.$id.'" class="relationshiptype_edit" id="edit_b"><i class="fa fa-edit mr10 fa-lg"></i></span>'; 
					}
					if(canuser('delete','relationshiptype')){ 
					$delete = '<span title = "'.trans('label.click_to_delete_record').'" type="button" id="delete_'.$id.'" data-relationshiptypeid="'.$id.'" class="relationshiptype_del" id="delete_b"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';	
					}	
			?>
			<tr>
				<td class="text-center"><?php echo $i + $offset + 1?></td>
				<td><?php echo $relationshiptype['rel_type']; ?></td>
				<td><?php echo $relationshiptype['inverse_rel_type']; ?></td>
				<td><?php echo $relationshiptype['description']; ?></td>
                <td><?php 
					if(!empty(config('app.env')) && config('app.env') == 'production' && isset($relationshiptype['is_default']) && $relationshiptype['is_default'] == 'n'){
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