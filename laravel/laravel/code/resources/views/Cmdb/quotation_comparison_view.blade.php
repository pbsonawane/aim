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
				$item_qt_arr          = json_decode($data['quotation_data']['content'], true);
				$arra = [];

				foreach($item_qt_arr as $arr)
				{
					$quotationComparisonData = json_decode($arr['quotation_comparison_data'], true);


				foreach ($quotationComparisonData as $quotationData) {
       					 $gstExtra = $quotationData['gst_extra'];
      					  $firstGstExtraValue = $gstExtra[0];
						$arra[] = $gstExtra[0];
					

       
  				  }

				}
				//print_r($arra);
				//die();
				
				
				$item_count           = count($item_qt_arr);
				$item_arr_new         = array();
				$vendor_wise_arr      = array();
				$vendor_wise_arr_new  = array();
				$vendor_wise_arr_full = array();
				$vendor_approve_arr  = array(); 
				$vendor_approve_arr_all  = array(); 

				$pr_po_id = 0;
				if ($item_count > 0) {
				    foreach ($item_qt_arr as $key => $value) {
				        $temp_item_arr                       = array();
				        $temp_item_arr['selected_item_id']   = $value['selected_item_id'];
				        $temp_item_arr['selected_item_name'] = $value['selected_item_name'];
				        $pr_po_id = $value['pr_po_id'];
				        
				        $item_arr_new[]                      = $temp_item_arr;
				        $vendor_wise_arr                     = json_decode($item_qt_arr[$key]['quotation_comparison_data'], true);

				        $vendor_approve_arr                  = json_decode($item_qt_arr[$key]['vendor_approve'], true);

				        $vendor_approve_arr_all[]            = $vendor_approve_arr;
				        	
				        $vendor_wise_arr_full[]              = $vendor_wise_arr;

				        if (count($vendor_wise_arr) > 0) {
				            foreach ($vendor_wise_arr as $vendor_id => $v_value) {
								if(!empty($vendor_id)){
									$vendor_wise_arr_new[] = $vendor_id;
								}
				                
				            }
				        }
				    }
				}
				$vendor_wise_arr_new = array_unique($vendor_wise_arr_new);
				$vendor_wise_arr_new = array_values($vendor_wise_arr_new);
				//print_r($vendor_wise_arr_new);


				$prpoattachment=$data['prpoattachment1'];
							
				$attachmentcount=0;

				// if ($prpoattachment1) {
				// 	foreach ($prpoattachment1 as $key => $attachment) {
				// 	 if($attachment['attachment_type'] == 'qu'){
				// 		 $attachmentcount++;
				// 	 }
				// 	}
				// }

				if($prpoattachment)
				{
					foreach($vendor_wise_arr_new as $vk)
					{
						foreach($prpoattachment as $attachment)
						{
							if($attachment['attachment_type'] == 'qu' && $attachment['pr_vendor_id']==$vk)
							{
								$attachmentcount++;
								break;
							}
						}
					}
				}
				
				$vendor_count=count($vendor_wise_arr_new);
				/*echo '<pre>';
				echo '---------- ALL Aarray -----------';
				print_r($data);
				echo '---------- Vendor Aarray -----------';
				print_r($vendor_wise_arr_new);
				echo '<br>';
				echo '---------- ITEM Aarray -----------';
				print_r($item_arr_new);
				echo '---------- FULL Aarray -----------';
				print_r($vendor_wise_arr_full);
				print_r($vendor_approve_arr_all);
				echo '</pre>';*/
				 ?>
				 <?php if($item_count > 0) { ?> 
				 
				 		<table class="table table-striped table-bordered table-hover">
				 			<tr>
				 				<th>

								 <?php 
				 					echo '<span style="color: #f60; font-size: initial;">PR NO: '.$item_qt_arr[0]["pr_no"].'</span>';
				 				?>
									 
									 <!-- Qautation Status:&nbsp;
				 				// if($item_qt_arr[0]['approval'] == 'rejected') 
				 				// {
				 				// 	echo "<span style='color:red'>".strtoupper($item_qt_arr[0]['approval'])."</span>";
				 				// } else {
				 				// 	echo "<span style='color:green'>".strtoupper($item_qt_arr[0]['approval'])."</span>";
				 				// }
				 				
				 				?>
								 &nbsp;(Comment:&nbsp;// echo ucwords($item_qt_arr[0]['reject_comment']); ?>) -->
								 
								</th>
				 				
				 			</tr>
				 			
				 			
				 		
				 		</table>
				 		<br>
				 	
				 	<form action="<?php echo url('/prquotationcomparison/update/') ?>" id="prquotationcomparison" name="prquotationcomparison" method="post">
                     <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">
                     <input type="hidden" id="pr_po_id" name="pr_po_id" value="<?php echo $pr_po_id; ?>">
					<table class="table table-striped table-bordered table-hover">
						  <tr>
						    <th class="h_cls">Sr</th>
						    <th class="h_cls" >Item Details</th>
						    <th class="h_cls"></th>
						    <?php for ($i = 1; $i <= count($vendor_wise_arr_new); $i++) { ?>	
								<th class="h_cls" colspan="2"><?php getvendorbyid($vendor_wise_arr_new[($i-1)]) ?></th>
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
						    $min_rate_arr =array();
						   for ($j = 0; $j < count($item_arr_new); $j++) 
						   {
		$converted_po=isset($vendor_approve_arr_all[$j]['converted_as_po'])?$vendor_approve_arr_all[$j]['converted_as_po']:'no';

							   ?>
								<tr>
								    <td><?php echo $j + 1; ?></td>
								    <td style="color:#4349ac !important;font-weight: bold;"><?php echo $item_arr_new[$j]['selected_item_name'];
								if($item_qt_arr[$j]['approval'] == 'rejected') 
				 				 {
				 				 	echo "&nbsp;&nbsp;<span style='color:red'>(".strtoupper($item_qt_arr[$j]['approval']).")</span>";
									  echo "&nbsp;&nbsp;<i class='fa fa-info-circle' aria-hidden='true' data-toggle='tooltip' data-placement='top' title='".ucwords($item_qt_arr[$j]['reject_comment'])."'></i>";
				 				 } 
								if($item_qt_arr[$j]['approval']=='approved')
								{
								echo "&nbsp;&nbsp;<span style='color:green'>(".strtoupper($item_qt_arr[$j]['approval']).")</span>";
								if($converted_po=='yes')
								{
									echo "&nbsp;&nbsp;<span style='color:green'>(PO CREATED)</span>";
								}
								echo "&nbsp;&nbsp;<i class='fa fa-info-circle' aria-hidden='true' data-toggle='tooltip' data-placement='top' title='".ucwords($item_qt_arr[$j]['reject_comment'])."'></i>";
								}

								
								
				 				 
				 				
								  ?>
								</td>
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
									    	for ($i = 0; $i < count($vendor_wise_arr_new); $i++) 
										 	{
										    	if (isset($vendor_wise_arr_full[$j][$vendor_wise_arr_new[$i]][0]['qty_1'])) 
										    	{
										           $display_qty = $vendor_wise_arr_full[$j][$vendor_wise_arr_new[$i]][0]['qty_1'];break;
										        }
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
		$min_rate_arr[$vendor_wise_arr_new[($i)]][$j][] = $display_rate;

	  } else {
	echo '<td align="right" style="color:red">' . $display_rate . '</td>
	<td align="right" style="color:red">' . $display_amt . '</td>';
									        }
									    }
								    ?>
								  </tr>
								
							<?php } ?>
								 <tr>
									<td><?php //echo '<pre>'; print_r($min_tot_arr); echo '</pre>';?></td>	
							     	<td>&nbsp;</td>
							     	<td>&nbsp;</td>
							     	<?php 
							     	for ($i = 0; $i < count($vendor_wise_arr_new); $i++) 
									{  
										$display_amt = 0;
										if(isset($min_tot_arr[$vendor_wise_arr_new[$i]][$j]))
										{
											$display_amt = min($min_tot_arr[$vendor_wise_arr_new[$i]][$j]);
										}
										$display_rate = 0;
										if(isset($min_rate_arr[$vendor_wise_arr_new[$i]][$j]))
										{
											$display_rate = min($min_rate_arr[$vendor_wise_arr_new[$i]][$j]);
										}
										 
										$main_val = '';

								        if($display_amt > 0) { 
								        	$display_qty = 0;
								        	if (isset($vendor_wise_arr_full[$j][$vendor_wise_arr_new[$i]][0]['qty_1'])) 
									    	{
									           $display_qty = $vendor_wise_arr_full[$j][$vendor_wise_arr_new[$i]][0]['qty_1'];
									           //break;
									        }
									        
								        	$str_vendor_id 	= $vendor_wise_arr_new[$i];
								        	$str_item_id 	= $item_arr_new[$j]['selected_item_id'];
								        	$str_item_name 	= $item_arr_new[$j]['selected_item_name'];
								        	$str_min_amount = $display_amt;
								        	$str_qty 		= $display_qty;
								        	$str_rate		= $display_rate;

								        	$main_val = $str_vendor_id.'##'.$str_item_id.'##'.$str_min_amount.'##'.$str_qty.'##'.$str_item_name.'##'.$str_rate;

								        	$disabled  = '';
								        	// if($item_qt_arr[0]['approval'] == 'approved')
								        	// {
								        	// 	$disabled  = 'disabled  = "disabled"';
								        	// }

								        	$checked = '';

											
								        	foreach($vendor_approve_arr_all as $vkey => $vval)
								        	{
								        		$selected_item_id = $vval['item_id'];
								        		$selected_vendor_id = $vval['vendor_id'];
								        		$selected_amount = $vval['amount'];
													
												$converted_to_po=isset($vendor_approve_arr_all[$j]['converted_as_po'])?$vendor_approve_arr_all[$j]['converted_as_po']:'no';
								        		
								        		if($selected_item_id == $str_item_id 
								        			&& $selected_vendor_id == $str_vendor_id 
								        			&& $selected_amount == $str_min_amount)
								        		{
								        			$checked = 'checked = "checked"';
								        		}
								        		if($selected_item_id == $str_item_id 
								        			&& $selected_vendor_id == $str_vendor_id 
								        			&& $selected_amount == $str_min_amount)
								        		{
								        			$disabled  = 'disabled  = "disabled"';
								        		}

												if($converted_to_po == 'yes')
								        		{
								        			$disabled  = 'disabled  = "disabled"';
								        		}else{
													$disabled  = '';
												}
								        		
								        	}
											
											if (!canuser('advance', 'approve_reject_qc') && $item_qt_arr[$j]['vendor_approve']!='null') 
											{
												
												if($item_qt_arr[$j]['approval'] == 'approved')
												{
													$disabled  = 'disabled  = "disabled"';
												}
											}
											  								        	
								        	?>
								        	
								        	<td>&nbsp;</td>
												  
											<td><input type="radio" class="checkmark" id="pr_qt_Submit_<?php echo $j; ?>" name="approve_<?php echo $j; ?>" value="<?php echo $main_val; ?>" style="width:100%" <?php echo $checked;?><?php echo $disabled ; ?>></td>
								       
								       <?php  } else {
								        	echo '<td>&nbsp;</td>
								        	<td>&nbsp;</td>';
								        }
									 } // i for close
									 ?>
						     	</tr>
						     <?php } // j loop ?>
						     
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
								        echo '<th align="right">&nbsp;</th>
										<th align="right">' . number_format($display_amt,2) . '</th>';
						    		}
						        ?>
							  </tr>
						</table>
							<div class="form-group col-md-12" style="text-align:center;">
                        		<hr>
                        	        <div class="form-group">
                              <?php 
                              if (canuser('advance', 'approve_reject_qc')) 
                              {
                               ?>
                                    <div class="col-md-6" style="text-align: end;">
                                        <div class="btn-group approve_qc">
                                          <button id="approved_<?php if (isset($pr_po_id)) { echo $pr_po_id;} ?>" type="button" class="btn btn-success btn-block"><i class="glyphicons glyphicons-check"></i> <?php echo trans('label.lbl_approve'); ?>
                                          </button>
                                       </div>
                                       &nbsp;|&nbsp;
                                       <div class="btn-group reject_qc">
                                          <button id="rejected_<?php if (isset($pr_po_id)) { echo $pr_po_id;} ?>" type="button" class="btn btn-danger btn-block"><i class="glyphicons glyphicons-remove"></i> <?php echo trans('label.lbl_reject'); ?>
                                          </button>
                                       </div>
                                    </div>
                                    <?php
                                   }else{?>
                                   	<div class="col-xs-4"></div>
                                   		<div class="col-xs-4">
											
										   <?php 
										   
										   if($attachmentcount >= $vendor_count)
										   {
												$finaldisabled  = '';
												$validationnote="";
											}else{
												$finaldisabled  = 'disabled  = "disabled"';
												$validationnote="Quotation file not uploaded for selected vendors.";
											}

										   ?>
                                 			 <button id="pr_qt_final" <?php echo $finaldisabled; ?> type="button" class="btn btn-primary btn-block"><strong>Submit Quotation for Approval</strong></button> 
										</br><span style="color: red;font-size: initial;"><?php echo $validationnote; ?></span> 
                                 		</div>
                                 		<div class="col-xs-4"></div>
                                   <?php }
                                   ?>
                                  
                                </div>
                             </div>
                          </form>
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
	<div id="myModal_approve_reject_qc" class="modal fade" role="dialog">
                     <div class="modal-dialog">
                        <form class="form-horizontal" id="formComment_qc">
                        	<input type="hidden" id="pr_po_id" name="pr_po_id" value="<?php echo $pr_po_id; ?>">
                        	<input type="hidden" id="approval_status" name="approval_status">
                           <!-- Modal content-->
                           <div class="modal-content">
                              <div class="modal-header">
                                 <button type="button" class="close" data-dismiss="modal">&times;</button>
                                 <h4 class="modal-title"><span id="modal-title_approve_reject"></h4>
                              </div>
                              <div class="modal-body">
                                 <div class="row">
                                    <div class="col-md-12">
                                       <div class="hidden alert-dismissable" id="msg_modal_approve_reject_qc"></div>
                                    </div>
                                 </div>
                                 
                     				<input type="hidden" id="pr_po_id" name="pr_po_id" value="<?php echo $pr_po_id; ?>">
                                 <div class="form-group required ">
                                    <label for="inputStandard" class="col-md-12 control-label textalignleft">Reason/Comment</label>
                                    <div class="col-md-12">
                                       <textarea class="col-md-12" name="comment" maxlength="250"></textarea>
                                       <br><code style="float: right;">(Max 250 Characters)</code>
                                    </div>
                                 </div>
                              </div> 
                              <div class="modal-footer">
                                 <button type="button" id="submitComment_qc" class="btn btn-success"><?php echo trans('label.btn_submit'); ?></button>&nbsp;|&nbsp;<button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo trans('label.btn_close'); ?></button>
                              </div>
                            
                           </div>
                        </form>
                     </div>
                  </div>
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/common.js"></script> 
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/cmdb/prs.js?<?php echo time();?>"></script>
<style type="text/css">
	th{
	text-align: center !important;
	letter-spacing: 0.5px !important;
	border : 1px solid #bce0ff !important;
	background-color: aliceblue !important;
	color: black !important;
}
td{border : 1px solid #bce0ff !important;}
.container {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  font-size: 20px;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default checkbox */
.container input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
.checkmark {
 /* position: absolute;
  top: 0;
  left: 0;*/
  height: 20px;
  width: 20px;
  background-color: #eee;
}

/* On mouse-over, add a grey background color */
.container:hover input ~ .checkmark {
  background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.container input:checked ~ .checkmark {
  background-color: #2196F3;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark when checked */
.container input:checked ~ .checkmark:after {
  display: block;
}

/* Style the checkmark/indicator */
.container .checkmark:after {
  left: 9px;
  top: 5px;
  width: 5px;
  height: 10px;
  border: solid white;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}

.modal-footer {
	background: #fff !important;
	border-top: 1px solid #fff !important;
}
.modal-header {
	background-color: aliceblue !important;
}

</style>

<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>


