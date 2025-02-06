<div>
	<table class="table table-striped table-bordered table-hover">
		<thead>
		  <tr>
			<th class="text-center"><?php echo trans('label.lbl_srno');?></th>
			<th><?php echo trans('label.lbl_report_category');?></th>
			<th><?php echo trans('label.lbl_description');?></th>
			<th><?php echo trans('label.lbl_action');?></th>
		  </tr>
		</thead>
		<tbody>
			<?php
			$reportcategories = $dbdata;
			if (is_array($reportcategories) && count($reportcategories) > 0)
			{
				foreach($reportcategories as $i => $reportcategory)
				{	
                    $id 	= $reportcategory['report_cat_id'];
					$edit = $delete = '';
					if(canuser('update','reportcategory'))
					$edit 	= '<span title = "'.trans('label.click_to_edit_record').'" name="edit_b" id="edit_'.$id.'" type="button" data-reportcategoryid="'.$id.'" class="reportcategory_edit" id="edit_b"><i class="fa fa-edit mr10 fa-lg"></i></span>'; 
					if(canuser('delete','reportcategory'))
					$delete = '<span title = "'.trans('label.click_to_delete_record').'" type="button" id="delete_'.$id.'" data-reportcategoryid="'.$id.'" class="reportcategory_del" id="delete_b"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';		
			?>
			<tr>
				<td class="text-center"><?php echo $i + $offset + 1?></td>
				<td><?php echo $reportcategory['report_category']; ?></td>
				<td><?php echo $reportcategory['description']; ?></td>
                <td><?php echo $edit.' '.$delete; ?></td>
			</tr>
			<?php
				}
			}
			else
				echo '<tr><td colspan="100" align="center"> '.trans('messages.msg_norecordfound').'</td></tr>';
			?>	
		</tbody>
	</table>
</div>