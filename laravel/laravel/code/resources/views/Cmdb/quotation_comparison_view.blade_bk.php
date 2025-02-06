<header id="topbar" class="affix"  >
	<div class="topbar-left">
		<?php breadcrum('Quotation Comparison Details');?>
	</div>
</header>
<div id="content">
	<div class="row">
		<div class="col-md-12">
			<div class="panel">
				<div class="panel-body">
			   <?php
				$item_qt_arr          = json_decode($data['content'], true);
				$item_count           = count($item_qt_arr);
				$item_arr_new         = array();
				$vendor_wise_arr      = array();
				$vendor_wise_arr_new  = array();
				$vendor_wise_arr_full = array();
				if ($item_count > 0) {
				    foreach ($item_qt_arr as $key => $value) {
				        $temp_item_arr                       = array();
				        $temp_item_arr['selected_item_id']   = $value['selected_item_id'];
				        $temp_item_arr['selected_item_name'] = $value['selected_item_name'];
				        $item_arr_new[]                      = $temp_item_arr;
				        $vendor_wise_arr                     = json_decode($item_qt_arr[$key]['quotation_comparison_data'], true);
				        $vendor_wise_arr_full[]              = $vendor_wise_arr;
				        if (count($vendor_wise_arr) > 0) {
				            foreach ($vendor_wise_arr as $vendor_id => $v_value) {
				                $vendor_wise_arr_new[] = $vendor_id;
				            }
				        }
				    }
				}
				$vendor_wise_arr_new = array_unique($vendor_wise_arr_new);
				$vendor_wise_arr_new = array_values($vendor_wise_arr_new);
				 ?>
				 <?php 
				 if($item_count > 0) { 
				 	?> 
					<table class="table table-striped table-bordered table-hover">
						  <tr>
						    <th class="h_cls" width="5%">Sr</th>
						    <th class="h_cls" width="15%">Item Details</th>
						    <th class="h_cls" width="5%"></th>
						    <?php for ($i = 1; $i <= count($vendor_wise_arr_new); $i++) { ?>	
								<th width="15%" class="h_cls" colspan="2"><span class="glyphicon glyphicon-user"></span>&nbsp;<?php getvendorbyid($vendor_wise_arr_new[($i-1)]) ?></th>
							<?php }	?>
						  </tr>
						  <tr>
						    <th>&nbsp;</th>
						    <th>&nbsp;</th>
						    <th>Qty</th>
						    <?php for ($i = 1; $i <= count($vendor_wise_arr_new); $i++) {
							    echo '<th>Rate</th>
							    <th>Amount</th>';
							}
							?>
						  </tr>
						   <?php 
						   $min_tot_arr =array();
						   for ($j = 0; $j < count($item_arr_new); $j++) 
						   { ?>
								<tr>
								    <th><?php echo $j + 1; ?></th>
								    <th style="color:#4349ac !important;"><span class="glyphicon glyphicon-shopping-cart"></span>&nbsp;<?php echo $item_arr_new[$j]['selected_item_name']; ?></th>
								    <td align="right">&nbsp;</td>
								    <?php 
								    for ($i = 1; $i <= count($vendor_wise_arr_new); $i++) {
	    								echo '<td align="right">&nbsp;</td>
	    								<td align="right">&nbsp;</td>';
								    }
								    ?>
								 </tr>
							   <?php 
							   $min_temp =array();
							   for($k=1; $k<=3;$k++)
							   { 
							  	?>
							  	<tr>
								    <td >&nbsp;</td>
								    <td>Quote <?php echo $k; ?> </td>
								    <td align="right">
								    	<?php 
									    	$display_qty = 0;
									    	if (isset($vendor_wise_arr_full[$j][$vendor_wise_arr_new[($j)]][($k-1)]['qty_' . ($k)])) {
									           $display_qty = $vendor_wise_arr_full[$j][$vendor_wise_arr_new[($j)]][($k-1)]['qty_' . ($k)];
									        }
									        echo $display_qty;
								        ?>
							        </td>
								     <?php 
									    for ($i = 0; $i < count($vendor_wise_arr_new); $i++) 
									 	{
									        $display_rate = $display_amt = 0;
									        if (isset($vendor_wise_arr_full[$j][$vendor_wise_arr_new[($i)]][($k-1)]['rate_' . ($k)])) {
									            $display_rate = $vendor_wise_arr_full[$j][$vendor_wise_arr_new[($i)]][($k-1)]['rate_' . ($k)];
									        }
									        if (isset($vendor_wise_arr_full[$j][$vendor_wise_arr_new[($i)]][($k-1)]['amount_' . ($k)])) {
									            $display_amt = $vendor_wise_arr_full[$j][$vendor_wise_arr_new[($i)]][($k-1)]['amount_' . ($k)];
									        }
									        if($display_rate > 0){
									        	echo '<td align="right" style="color:green;">' . $display_rate . '</td>
												<td align="right" style="color:green">' . $display_amt . '</td>';

												$min_tot_arr[$vendor_wise_arr_new[($i)]][$j][] = $display_amt;

									        } else {
									        	echo '<td align="right" style="color:red">' . $display_rate . '</td>
												<td align="right" style="color:red">' . $display_amt . '</td>';
									        }
									    }
								    ?>
								  </tr>
								 <?php }?>
						     <?php } ?>
						     <tr>
							  	<th colspan="3" align="right">Total Min Amount</th>
							    <?php 
							    	for ($i = 0; $i < count($vendor_wise_arr_new); $i++) 
							 		{	
						 				$display_amt = 0;
								        if (isset($min_tot_arr[$vendor_wise_arr_new[($i)]]))
								        {
								        	foreach($min_tot_arr[$vendor_wise_arr_new[($i)]] as $result)
								        	{
								        		$min_val = min($result);
								        		$display_amt = $display_amt + $min_val;
								        	}
								        }
								        echo '<td align="right">&nbsp;</td>
										<td align="right" style="font-weight:bold;">' . number_format($display_amt,2) . '</td>';
						    		}
						        ?>
							  </tr>
						</table>
					<?php } else { ?>
						<table class="table table-striped table-bordered table-hover">
						  	<tr>
							    <th class="h_cls" colspan="4"></th>
						    </tr>
						    <tr>
						    	<td colspan="4" class="text-center"><h3 style="color:red;">Quotation Comparison Data Not Found.</h3></td>
						    </tr>
						</table>  
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
<style type="text/css">
	th{
		text-align: center;
		letter-spacing: 1px;
	}
	.h_cls {
	background-color: coral !important;
	color: aliceblue;
}
</style>


