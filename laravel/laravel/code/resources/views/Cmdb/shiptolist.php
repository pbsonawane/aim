<div>
	<table class="table table-striped table-bordered table-hover">
		<thead>
		  <tr>
			<th class="text-center"><?php echo trans('label.lbl_srno');?></th>
			<th><?php echo trans('label.lbl_location');?></th>
			<th><?php echo trans('label.lbl_company_name'); ?></th>
			<th><?php echo trans('label.lbl_address');?></th>
			<th><?php echo trans('label.lbl_pan_no');?></th>
            <th><?php echo trans('label.lbl_gstn');?></th>
			<th><?php echo trans('label.lbl_action');?></th>
		  </tr>
		</thead>
		<tbody>
			<?php
            $shiptos = $dbdata;
          /*  echo "<pre>";
            print_r($shiptos);*/
			if (is_array($shiptos) && count($shiptos) > 0)
			{
				foreach($shiptos as $i => $shipto)
				{	
	                if($shipto)
	                {
	                    $id = isset($shipto['shipto_id']) ? $shipto['shipto_id']: "";
						$delete = '';
						$edit= '';
						if(canuser('update','shipto')){
						$edit = '<span title = "Click To Edit Record" name="edit_b" id="edit_'.$id.'" type="button" data-shiptoid="'.$id.'" class="shipto_edit" id="edit_b"><i class="fa fa-edit mr10 fa-lg"></i></span>'; 
						}
						if(canuser('delete','shipto')){
						$delete = '<span title = "Click To Delete Record" type="button" id="delete_'.$id.'" data-shiptoid="'.$id.'" class="shipto_del" id="delete_b"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';			
						}
				?>
					<tr>
						<td class="text-center"><?php echo $i + $offset + 1?></td>
						<td><?php echo @$shipto['location_name']; ?></td>
						<td><?php echo @$shipto['company_name']; ?></td>
		                <td><?php echo $shipto['address']; ?></td>
						<td><?php echo $shipto['pan_no']; ?></td>
						<td><?php echo $shipto['gstn']; ?></td>
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