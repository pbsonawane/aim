
<div>
	<table class="table table-striped table-bordered table-hover">
		<thead>
		 	<tr>
                <th>Complaint No</th>
                <th>Complaint Date</th>
                <th>Requester</th>
                <th>Priority</th>
                <th>Problem Detail</th>
                <th>HOD Name</th>
                <th>HOD Remark</th>
                <th>IT Remark</th>
                <th>Store Remark</th>
                
            </tr>
		</thead>
		<tbody>
        <?php
			$cr_list = $dbdata;

			if (is_array($cr_list) && count($cr_list) > 0)
			{
				foreach($cr_list as $i => $val)
				{	
					$cr_id = isset($val['cr_id']) ? $val['cr_id'] : '';
					$complaint_raised_no = isset($val['complaint_raised_no']) ? $val['complaint_raised_no'] : '';
                    $complaint_raised_date = isset($val['complaint_raised_date']) ? $val['complaint_raised_date'] : '';
                    $priority = isset($val['priority']) ? $val['priority'] : '';
                    $problemdetail = isset($val['problemdetail']) ? $val['problemdetail'] : '';
                    $attachment = isset($val['attachment']) ? $val['attachment'] : '';
                    $hod_remark = isset($val['hod_remark']) ? $val['hod_remark'] : '';
                    $hod_status = isset($val['hod_status']) ? $val['hod_status'] : '';
                    $itfile = isset($val['itfile']) ? $val['itfile'] : '';
                    $itstatus = isset($val['itstatus']) ? $val['itstatus'] : '';
                    $it_remark = isset($val['it_remark']) ? $val['it_remark'] : '';
                    $it_status = isset($val['it_status']) ? $val['it_status'] : '';
                    $storefile = isset($val['storefile']) ? $val['storefile'] : '';
                    $store_remark = isset($val['store_remark']) ? $val['store_remark'] : '';
                    $store_status = isset($val['store_status']) ? $val['store_status'] : '';
                    $status = isset($val['status']) ? $val['status'] : '';
                    $created_at = isset($val['created_at']) ? $val['created_at'] : '';
                    $updated_at = isset($val['updated_at']) ? $val['updated_at'] : '';

                    $user_details = isset($val['user_details']) ? $val['user_details'] : '';
                    $user_fullName = isset($val['user_details']) ? $val['user_details']['firstname'] .' '. $val['user_details']['lastname']   : '';
                    
                    $requester_details = isset($val['requester_details']) ? $val['requester_details'] : '';
                    
                    $hod_details = isset($val['hod_details']) ? $val['hod_details'] : '';
                    $hod_fullName = isset($val['hod_details']) ?  $val['hod_details']['firstname'] .' '. $val['hod_details']['lastname'] : '';



          			
			?>
					<tr>
	                    <td><?php echo $complaint_raised_no; ?></td>
	                    <td><?php echo $complaint_raised_date; ?></td>
	                    <td><?php echo $user_fullName; ?></td>
                        <td><?php echo $priority; ?></td>
	                    <td><?php echo $problemdetail; ?></td>
	                    <td><?php echo $hod_fullName; ?></td>
	                    <td><?php echo $hod_remark; ?></td>
	                    <td><?php echo $it_remark; ?></td>
	                    <td><?php echo $store_remark; ?></td>	 

	                </tr>
			<?php
				}
			}
			else {
				echo '<tr><td colspan = "100" style="text-align:center">'.trans('messages.msg_norecordfound').'</td></tr>';
			}
			?>
		</tbody>
	</table>
</div>