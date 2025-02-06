<div>
	<table class="table table-striped table-bordered table-hover">
		<thead>
		  <tr>
			<!--<th class="text-center">Sr.No.</th>-->
			<th><?php echo trans('label.lbl_tag') ?></th>
			<th><?php echo trans('label.lbl_name') ?></th>
			<th><?php echo trans('label.lbl_device') ?></th>
			<th><?php echo trans('label.lbl_status') ?></th>
			<th>PO Number</th>
			<th>Serial Number</th>
			<th><?php echo trans('label.lbl_date') ?></th>
			<!--<th><?php //echo trans('label.lbl_action')?></th>-->
		  </tr>
		</thead>
		<tbody>
			<?php
			$assetlits = $dbdata;
			if (is_array($assetlits) && count($assetlits) > 0)
			{
				foreach($assetlits as $i => $asset)
				{	
					$assets_details = json_decode($asset['asset_details'],true); 
                    $id = $asset['asset_id'];
					$delete = '';
					$edit = '<span title = "'.trans('label.click_to_edit_record').'" name="edit_b" id="'.$id.'" type="button" data-businessunitid="'.$id.'" class="asset_ed" id="edit_b"><i class="fa fa-edit mr10 fa-lg"></i></span>'; 
					
					$delete = '<span title = "'.trans('label.click_to_delete_record').'" type="button" id="'.$id.'" data-businessunitid="'.$id.'" class="asset_de" id="delete_b"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';
					if($asset['asset_status'] == "in_store")
						$deviceStatus = trans('label.lbl_instore');//"In Store";	
					elseif($asset['asset_status'] == "in_use")	
						$deviceStatus = trans('label.lbl_inuse');//"In Use";	
					elseif($asset['asset_status'] == "in_repair")	
						$deviceStatus = trans('label.lbl_inrepair');//"In Repair";	
					elseif($asset['asset_status'] == "expired")	
						$deviceStatus = trans('label.lbl_expired');//"Expired";	
					elseif($asset['asset_status'] == "disposed")	
						$deviceStatus = trans('label.lbl_disposed');//"Disposed";
					else
						$deviceStatus = '-';//"Procurement";	
			?>
			<tr>
				<!--<td class="text-center"><?php echo $i + $offset + 1?></td>-->
				<td><?php echo '<a target="_blank" href="'.config('app.site_url').'/assets/'.$id.'/'.$asset['ci_templ_id'].'" id="'.$id.'" class="assetdash1">'.$asset['asset_tag'].'</a>'; ?></td>
				<td><?php echo $asset['display_name']; ?></td>
				<td><?php echo $asset['object_id']; ?></td>
				<td><?php echo $deviceStatus; ?></td>
				<td><a href="<?php echo config('app.site_url') .'/purchaseorders/'. $asset['po_id'];?>" target="_blank"><?php $asset['po_name'] ?></a></td>
				<td><?php 
				if(isset($assets_details['serial_number'])){
					echo $assets_details['serial_number'];
				}else{
					echo "NA";
				}
				 ?></td>
				<td><?php echo $asset['created_at']; ?></td>
				<td>
               <!-- <td><?php //echo $edit.' '.$delete; ?></td>-->
			</tr>
			<?php
				}
			}
			else
				echo '<tr><td colspan = "100" class ="text-center">'.trans('label.no_records').'</td></tr>';
				?>	
		</tbody>
	</table>
</div>
