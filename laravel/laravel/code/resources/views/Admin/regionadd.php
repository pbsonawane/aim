<div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
</div>
<div class="col-md-12">
	<div class="panel">
		<div class="panel-body">
			<form class="form-horizontal"  name="addformregion" id="addformregion">
				<input id="region_id" name="region_id" type="hidden" value="<?php echo $region_id?>">
					<div class="form-group required ">
							<label for="inputStandard" class="col-md-3 control-label">Region Name</label>
							<div class="col-md-8">
								<input type="text" id="region_name" name="region_name" class="form-control input-sm" value="<?php if(isset($regiondata[0]['region_name'])) echo $regiondata[0]['region_name'];?>">
							</div>
					</div>
						<div class="form-group required">
								<label for="Description" class="col-md-3 control-label">Description</label>
								<div class="col-md-8">
									<textarea id="region_description" name="region_description" class="form-control input-sm"><?php  if(isset($regiondata[0]['region_name'])) echo $regiondata[0]['region_description'];?></textarea>
							</div>
					</div>
					<div class="form-group">
					<label class="col-md-3 control-label"></label>
						<div class="col-xs-2">
							<?php if($region_id != '') {?>
							<button id="regioneditsubmit" type="button" class="btn btn-success btn-block">Submit</button>
							<?php }else{?>
							<button id="regionaddsubmit" type="button" class="btn btn-success btn-block">Submit</button>
							<?php } ?>
						</div>
						<div class="col-xs-2">
							<button id="region_reset" type="button" class="btn btn-info btn-block">Reset</button>
						</div>
				</div>
			</form>
		</div>
	</div>
</div>