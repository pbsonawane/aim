<div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup_bv"></div>
</div>
<div class="col-md-12">
	<div class="panel">
		<div class="panel-body">
			<form class="form-horizontal"  name="userbvsform" id="userbvsform">
				<input id="userbv" name="type" type="hidden" value=" <?php echo $user_id;?>">
				
					<?php if(is_array($bvs['bv_data']) && count($bvs['bv_data']) > 0) { 
							foreach($bvs['bv_data'] as $bu_name=>$each_bu_data)
							{
					?>	
								<fieldset class="fieldsetCustom">
								<legend class="legendCustom"><strong><?php echo $bu_name;?></strong></legend>
									
									<div class="col-lg-12"> 
									
										<?php if (is_array($each_bu_data) && count($each_bu_data) > 0) {
											foreach($each_bu_data as $each_data) { 
												$checked = "";
												if($each_data['checked'])
													$checked = "checked = 'checked'";
											?>
												<div class="col-lg-3">
													<div class="checkbox-custom mb5">
													<input type="checkbox" class="user_bvs"  <?php echo $checked;?> id="<?php echo $each_data['bv_id']?>" value="<?php echo $each_data['bv_id'] ?>">
													<label for="<?php echo $each_data['bv_id']?>"><?php echo $each_data['bv_name']; ?></label>
												</div>
												</div>
								 		<?php } } ?>
									</div>
							  	</fieldset>
					
						<?php } } ?>
					
					<div class="form-group align-left">
					<label class="col-md-3 control-label"></label>
						<div class="col-xs-2">
							<button id="userbv_submit" type="button" class="btn btn-success btn-block">Assign</button>
						</div>
				</div>
			</form>
		</div>
	</div>
</div>