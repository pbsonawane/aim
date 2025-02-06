<div>
	<table class="table table-striped table-bordered table-hover">
		<thead>
		  <tr>
			<th class="text-center"><?php echo trans('label.lbl_srno');?></th>
			<th><?php echo trans('label.lbl_cc_code');?></th>
			<th><?php echo trans('label.lbl_cc_name');?></th>
            <th><?php echo trans('label.lbl_description');?></th>
		<?php /* ?>	<th><?php echo trans('label.lbl_location');?></th>
            <th><?php echo trans('label.lbl_department');?></th><?php */?>
			<th><?php echo trans('label.lbl_action');?></th>
            
            
		  </tr>
		</thead>
		<tbody>
			<?php
            $costcenters = $dbdata;
            //print_r($costcenters);
			if (is_array($costcenters) && count($costcenters) > 0)
			{
				foreach($costcenters as $i => $costcenter)
				{	
               // $costcenter = $costcenter['details'];
                if($costcenter)
                {
                    $id = isset($costcenter['cc_id']) ? $costcenter['cc_id']: "";
                   // $id = $costcenter['cc_id'];
					$delete = '';
					$edit= '';
					if(canuser('update','costcenter')){
					$edit = '<span title = "Click To Edit Record" name="edit_b" id="edit_'.$id.'" type="button" data-costcenterid="'.$id.'" class="costcenter_edit" id="edit_b"><i class="fa fa-edit mr10 fa-lg"></i></span>'; 
					}
					if(canuser('delete','costcenter')){
					$delete = '<span title = "Click To Delete Record" type="button" id="delete_'.$id.'" data-costcenterid="'.$id.'" class="costcenter_del" id="delete_b"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';			
					}
			?>
			<tr>
				<td class="text-center"><?php echo $i + $offset + 1?></td>
                <td><?php echo $costcenter['cc_code']; ?></td>
				<td><?php echo $costcenter['cc_name']; ?></td>
				<td><?php echo $costcenter['description']; ?></td>
				<?php /*?><td><?php echo @$costcenter['location_name']; ?></td>
				<td><?php //echo $costcenter['department_name']; ?></td><?php */?>
                <td><?php echo $edit.' '.$delete; ?></td>
			</tr>
			<?php
                }
				}
			}
			else
				echo '<tr><td colspan = "100"> No Records</td></tr>';
				?>	
		</tbody>
	</table>
</div>