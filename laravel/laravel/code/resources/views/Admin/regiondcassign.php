<div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
</div>
<div class="col-md-12">
	<div class="panel">
		<div class="panel-body">
			<form class="form-horizontal"  name="regionassigndcfrm" id="regionassigndcfrm">
				<input id="region_id" name="region_id" type="hidden" value="<?php echo $region_id?>">
					<div class="form-group col-md-12">
					<label class="control-label">Region - </label> <?php echo $region_name;?>
						
					</div>
					
					<div class="form-group">
						<table class="table">
							<tr>
								<td align="left" valign="top">
									<table width="100%" border="0" cellspacing="0" cellpadding="3">
										<?php
										$cnt = count($dc_data);
										if (is_array($dc_data) && $cnt > 0)
										{
											$j= 1;
											echo "<tr>";
											foreach($dc_data as $each_dc)
											{	
												$checked = "";
												if($each_dc['checked'])
													$checked = "checked = 'checked'";
											?>
																 
													<td width="33%">
													<div class="checkbox-custom mb5">
                                                        <input type="checkbox" class="region_dc"  <?php echo $checked;?> id="<?php echo $each_dc['dc_id']?>" value="<?php echo $each_dc['dc_id'] ?>">
                                                        <label for="<?php echo $each_dc['dc_id']?>"><?php echo $each_dc['dc_name']; ?></label>
                                                    </div>
															                  
													</td>
													<?php if ($j % 3 == 0)
														echo '</tr><tr>';
												$j++;
											}
											echo "</tr>";
										}
									?>
									</table>
								</td>
							</tr>
						</table>
					</div>
					
					
					<?php if (is_array($dc_data) && $cnt > 0) {?>
					<div class="form-group align-middle">
					<label class="col-md-3 control-label"></label>
						<div class="col-xs-2">
							<button id="regionassigndc_submit" type="button" class="btn btn-success btn-block">Assign</button>
						</div>
						<!--<div class="col-xs-2">
							<button id="regionassigndc_reset" type="button" class="btn btn-info btn-block">Reset</button>
						</div> -->
				</div> 
				<?php } 
					else
					{
				 ?>
				 	No Data
				 <?php } ?>
			</form>
		</div>
	</div>
</div>