
<div class="table-wrapper-scroll-y">
 
</div>
	<table class="table table-striped table-bordered table-hover table-responsive ">
		<thead>
		  <tr>
			<th class="srno">Sr.No.</th>
			<th>IP</th>
			<th>User</th>
            <th>Method</th>
            <th>Action</th>
            <th>Time</th>
            <th>URL</th>
            <th>Details</th>
		  </tr>
		</thead>
		<tbody>
			<?php
			$userlogs = $dbdata;
			if (is_array($userlogs) && count($userlogs) > 0)
			{
				foreach($userlogs as $i => $userlog)
				{
				/*	$id = $userlog['region_id'];
					$delete = $assing_dc = '';
					$edit = '<span title = "Click To Edit Record" name="edit_b" id="edit_'.$id.'" type="button" data-regionid="'.$id.'" class="region_edit" ><i class="fa fa-edit mr10 fa-lg"></i></span>'; 
					
					$delete = '<span title = "Click To Delete Record" type="button" id="delete_'.$id.'" data-regionid="'.$id.'" class="region_del"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';
					
					$assing_dc = '<span title = "Assign DC to Region" type="button" id="assign_'.$id.'" data-regionname="'.$userlog['region_name'].'" class="region_dc_assign" id="assign_b"><i class="fa fa-check-square-o mr10 fa-lg"></i></span>';*/
						
		 ?>
			<tr>
				<td class="srno"><?php echo $i + $offset + 1?></td>
				<td><?php echo $userlog['ip']; ?></td>
                <td><?php echo $userlog['fullname']; ?></td>
                <td><?php echo $userlog['method']; ?></td>
                <td><?php echo ucfirst($userlog['action']); ?></td>
                <td><?php echo $userlog['logtime']; ?></td>
                <td><?php echo $userlog['url']; ?></td>
                <td><?php //echo $userlog['json_string']; ?>
                    
                    <button    data-toggle="popover" id="<?php echo $i + $offset + 1?>" title="Detail" data-placement="left" data-html="true" type="button" class="btn btn-info btn-block">View</button>
                    <div style="display:none" id="popover-content_<?php echo $i + $offset + 1?>">
                    <?php 
                       echo $userlog['json_string'];
                        ?> 
                    </div>

                </td>
			</tr>
			<?php
				}
			}
			else
				echo '<tr><td colspan="100" align="center"> No Records</td></tr>';
				?>
		</tbody>
	</table>
</div>
