<div class="col-md-10">
        <div class="hidden alert-dismissable" id="msg_popup"></div>
</div>
<div class="col-md-12">
	<div class="panel">
		<div class="panel-body">
			<form class="form-horizontal"  name="addformdesignation" id="addformdesignation">
				<input id="designation_id" name="designation_id" type="hidden" value="<?php echo isset($designationid) ? $designationid : ''; ?>">
					<div class="form-group required ">
						<label for="inputStandard" class="col-md-5 control-label">Designation Name</label>
						<div class="col-md-7">
							<input type="text" id="designation_name" name="designation_name" class="form-control input-sm" value="<?php echo isset($designationdata[0]['designation_name']) ? $designationdata[0]['designation_name'] : ''; ?>">
						</div>
					</div>
					<div class="form-group">
					    <label for="inputStandard" class="col-md-5 control-label"></label>
                        <div class="col-md-4">
                            <?php if (isset($designationid)) { ?>
							    <button id="designationupdate" type="button" class="btn btn-success btn-block">Update</button>
                            <?php } else { ?>
                                <button id="designationsave" type="button" class="btn btn-success btn-block">Submit</button>
                            <?php } ?>
						</div>
						<div class="col-md-3">
							<button id="designationreset" type="button" class="btn btn-info btn-block">Reset</button>
						</div>
				</div>
			</form>
		</div>
	</div>
</div>
