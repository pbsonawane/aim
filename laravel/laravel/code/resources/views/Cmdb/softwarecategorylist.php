<div>
	<table class="table table-striped table-bordered table-hover">
		<thead>
		  <tr>
			<th class="text-center"><?php echo trans('label.lbl_srno');?></th>
			<th><?php echo trans('label.lbl_software_category');?></th>
			<th><?php echo trans('label.lbl_description');?></th>
			<th><?php echo trans('label.lbl_action');?></th>
		  </tr>
		</thead>
		<tbody>
			<?php
			$softwarecategorys = $dbdata;
			if (is_array($softwarecategorys) && count($softwarecategorys) > 0)
			{
				foreach($softwarecategorys as $i => $softwarecategory)
				{	
                    $id 	= $softwarecategory['software_category_id'];
					$delete = '';
					$edit 	= '';

					if(canuser('update','softwarecategory')){
					$edit = '<span title = "'.trans('label.click_to_edit_record').'" name="edit_b" id="edit_'.$id.'" type="button" data-softwarecategoryid="'.$id.'" class="softwarecategory_edit" id="edit_b"><i class="fa fa-edit mr10 fa-lg"></i></span>'; 
					}
					if(canuser('delete','softwarecategory')){
					$delete = '<span title = "'.trans('label.click_to_delete_record').'" type="button" id="delete_'.$id.'" data-softwarecategoryid="'.$id.'" class="softwarecategory_del" id="delete_b"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';
					}	
			?>
			<tr>
				<td class="text-center"><?php echo $i + $offset + 1?></td>
				<td><?php echo $softwarecategory['software_category']; ?></td>
				<td><?php echo $softwarecategory['description']; ?></td>
                <td><?php 
					if(!empty(config('app.env')) && config('app.env') == 'production' && isset($softwarecategory['is_default']) && $softwarecategory['is_default'] == 'n'){
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