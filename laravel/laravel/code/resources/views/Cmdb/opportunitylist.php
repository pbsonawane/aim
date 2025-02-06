<div>
	<table class="table table-striped table-bordered table-hover">
		<thead>
		  <tr>
			<th class="text-center"><?php echo trans('label.lbl_srno'); ?></th>
			<th>Opportunity Code</th>
			<th>Opportunity Status</th>
			<th>Opportunity Stage</th>
			<th>Opportunity Created By</th>
			<th>Created Date</th>
			<th>Purchase Request No.</th>
			<!-- <th>View Details</th> -->
		  </tr>
		</thead>
		<tbody>
			<?php
				$opportunities = $dbdata;
				if (is_array($opportunities) && count($opportunities) > 0) {
				    foreach ($opportunities as $i => $opportunity) {
				        if ($opportunity) { ?>
					<tr>
						<td class="text-center"><?php echo $i + $offset + 1 ?></td>
						<td><a style="text-decoration: none; cursor: pointer;font-weight: bold;"  title="View Item Details" target="_blank" href=<?php echo "/opportunity/" . @$opportunity['opportunity_id']; ?>><?php echo @$opportunity['opportunity_code']; ?></a></td>
						<td><?php echo @$opportunity['opportunity_status']; ?></td>
		       			<td><?php echo @$opportunity['opportunity_stage']; ?></td>
		       			<td><?php echo $opportunity['created_by_name']; ?></td>
						<td><?php echo $opportunity['created_date']; ?></td>
						<td><a style="text-decoration: none; cursor: pointer;color:darkgreen;font-weight: bold;" title="View Pr Details" id="pr_id_link" onClick="store_opp_prid(<?php echo "'" . $opportunity['pr_id'] . "'"; ?>)" ><?php if(isset($opportunity['pr_no'])){ echo $opportunity['pr_no']; } else { echo '-'; } ?></a></td>
		        		<!-- <td class="text-center"><a style="text-decoration: none; cursor: pointer;font-weight: bold;"  title="View Item Details" target="_blank" href=<?php //echo "/opportunity/" . @$opportunity['opportunity_id']; ?>>View Items</a></td> -->
					</tr>
				<?php 
				        }
				    }
				} else {
				    echo '<tr><td colspan = "100"> No Records</td></tr>';
				}

				?>
		</tbody>
	</table>
</div>
<script language="javascript" type="text/javascript" src="enlight/scripts/common.js"></script>
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/dropzone/downloads/dropzone.min.js"></script>
<script type="text/javascript">
function store_opp_prid(id)
{
    localStorage.setItem("opp_pr_id",id);
    window.open('/purchaserequest', '_blank');

}
</script>



