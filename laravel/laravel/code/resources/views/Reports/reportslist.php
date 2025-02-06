<div>
	<table class="table table-striped table-bordered table-hover">
		<thead>
		  <tr>
			<th class="text-center"><?php echo trans('label.lbl_srno');?></th>
			<th><?php echo trans('label.lbl_report_name');?></th>
			<th><?php echo trans('label.module');?></th>
			<th><?php echo trans('label.lbl_action');?></th>
		  </tr>
		</thead>
		<tbody>
			<?php
			$reports = $dbdata;
			if (is_array($reports) && count($reports) > 0)
			{
				foreach($reports as $i => $report)
				{	
                    $id 	= $report['report_id'];
					$edit = $delete = '';
					if(canuser('update','report'))
					$edit 	= '<span title = "'.trans('label.click_to_edit_record').'" name="edit_b" id="edit_'.$id.'" type="button" data-reportid="'.$id.'" class="reports_edit" id="edit_b"><i class="fa fa-edit mr10 fa-lg"></i></span>'; 
					if(canuser('delete','report'))
					$delete = '<span title = "'.trans('label.click_to_delete_record').'" type="button" id="delete_'.$id.'" data-reportid="'.$id.'" class="reports_del" id="delete_b"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';		
			?>
			<tr>
				<td class="text-center"><?php echo $i + $offset + 1?></td>
				<td><a href="<?php echo config('app.site_url'); ?>/reports/details/<?php echo $id;?>"><?php echo $report['report_name']; ?></a></td>
				<td><?php echo $report['module']; ?></td>
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