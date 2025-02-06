
<div>
	<table class="table table-striped table-bordered table-hover table-responsive ">
		<thead>
		  <tr>
			<th class="srno">Sr.No.</th>
			<th>Name</th>
			<th>IP to be whitelisted</th>
			<th>Expiry Limit</th>
			<th>Approve</th>
		  </tr>
		</thead>
		<tbody>
			<?php
			$tokenips = $dbdata;
			if (is_array($tokenips) && count($tokenips) > 0)
			{
				foreach($tokenips as $i => $each_ip)
				{
					$id = $each_ip['id'];
					$ip = $each_ip['ip'];
		?>
			<tr>
				<td class="srno"><?php echo $i + $offset + 1?></td>
				<td><?php echo $each_ip['username']; ?></td>
				<td><?php echo $each_ip['ip']; ?></td>
				<td><?php echo $each_ip['expiry_date']; ?></td>
				<td>
					<span id="<?php echo $id?>" class="whitelist_approve" ip="<?php echo $ip?>">
                         <button type="button" class="btn btn-info btn-block">Approve</button>
                     </span>
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
