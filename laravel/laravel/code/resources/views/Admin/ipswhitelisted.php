
<div>
	<table class="table table-striped table-bordered table-hover table-responsive ">
		<thead>
		  <tr>
			<th></th>
			<th>Whitelisted IPs</th>
			<th>Action</th>
		  </tr>
		</thead>
		<tbody>
			<?php
			$whitelisted_ips = json_decode($whitelisted_ips_data['allowed_ip'],true);
			
			if (is_array($whitelisted_ips) && count($whitelisted_ips) > 0)
			{	
				$sr = 0;
				foreach($whitelisted_ips as $each_ip)
				{
					
		?>
			<tr>
				<td><input class="check-del-whitelsited_ip"  type="checkbox" value="<?php echo $sr;?>"></td>
				<td><?php echo $each_ip; ?></td>
				
				
				<td>
					 <?php echo '<span title = "Delete" type="button" id="delete_'.$sr.'" data-flag="ip" class="delete_wip"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';		
					 ?>
					 
				</td>
			</tr>
			<?php
				
				$sr++;
				} ?>
			<tr>
				<td colspan="3" align="left">
					<div class="col-xs-4">
						
						<button onclick="deleteWhiteListIp('-1','ip')" type="button" class="btn btn-success btn-block">Delete</button>
						
					</div>
				</td>
			</tr>	
				
		<?php }
			else
				echo '<tr><td colspan="100" align="center"> No Records</td></tr>';
				?>
		</tbody>
	</table>
</div>
<div>
	<table class="table table-striped table-bordered table-hover table-responsive ">
		<thead>
		  <tr>
			<th></th>
			<th>Whitelisted Subnets</th>
			<th>Action</th>
		  </tr>
		</thead>
		<tbody>
			<?php
			if($whitelisted_ips_data['allowed_subnets'] != '')
				$whitelisted_subnets = json_decode($whitelisted_ips_data['allowed_subnets'],true);
			else
				$whitelisted_subnets = array();	
			
			if (is_array($whitelisted_subnets) && count($whitelisted_subnets) > 0)
			{	
				$sr_sub = 0;
				foreach($whitelisted_subnets as $each_subnet)
				{
					
		?>
			<tr>
				<td><input class="check-del-whitelsited_subnet"  type="checkbox" value="<?php echo $sr_sub;?>"></td>
				<td><?php echo $each_subnet; ?></td>
				
				
				<td>
					 <?php echo '<span title = "Delete" type="button" id="delete_'.$sr_sub.'" data-flag="subnet" class="delete_wsubnet"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';		
					 ?>
					 
				</td>
			</tr>
			<?php
				
				$sr_sub++;
				} ?>
			<tr>
				<td colspan="3" align="left">
					<div class="col-xs-4">
						
						<button onclick="deleteWhiteListIp('-1','subnet')" type="button" class="btn btn-success btn-block">Delete</button>
						
					</div>
				</td>
			</tr>	
				
		<?php }
			else
				echo '<tr><td colspan="100" align="center"> No Records</td></tr>';
				?>
		</tbody>
	</table>
</div>