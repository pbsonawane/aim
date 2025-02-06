
<div class="row">
    <div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div>
    <div class="col-md-12">
<div class="panel">
	<div class="panel-body">
		<form class="form-horizontal"  name="addformbusinessvertical" id="addformbusinessvertical">
			<input id="bv_id" name="bv_id" type="hidden" value="<?php echo $bv_id?>">
				
				<div class="form-group required">
					<label for="Description" class="col-md-3 control-label">Business Unit Name</label>
					<div class="col-md-8">
						
                        <select class="form-control input-sm" name="bu_id" id="bu_id">
								<option value="">-Business Unit-</option>
								<?php 
									if(is_array($businessunits) && count($businessunits)>0)
									{
										foreach($businessunits as $businessunit)
										{
											$cubusinessunit_id = isset($businessverticaldata[0]['bu_id']) ? $businessverticaldata[0]['bu_id'] : '';
								?>
										<option value="<?php echo $businessunit['bu_id'] ?>" <?php if($cubusinessunit_id == $businessunit['bu_id']){echo "selected";} ?> > <?php echo $businessunit['bu_name'] ?> </option>
								<?php
										}
									}	
								?>
							</select>		
					</div>
                </div>
                <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">Business Vertical Name</label>
                        <div class="col-md-8">
                            <input type="text" id="bv_name" name="bv_name" class="form-control input-sm" value="<?php if(isset($businessverticaldata[0]['bv_name'])) echo $businessverticaldata[0]['bv_name'];?>">
                        </div>
				</div>
				<div class="form-group required">
					<label for="Description" class="col-md-3 control-label">Description</label>
					<div class="col-md-8">
						<textarea id="bv_description" name="bv_description" class="form-control input-sm"> <?php if(isset($businessverticaldata[0]['bv_description'])) echo $businessverticaldata[0]['bv_description'];?></textarea>
					</div>
				</div>	
				<div class="form-group">
				<label class="col-md-3 control-label"></label>
					<div class="col-xs-2">
						
                        <?php if($bv_id != '') {?>
							<button id="businessverticaleditsubmit" type="button" class="btn btn-success btn-block">Update</button>
							<?php }else{?>
							<button id="businessverticaladdsubmit" type="button" class="btn btn-success btn-block">Submit</button>
							<?php } ?>
                    </div>
					<div class="col-xs-2">
						<button id="businessvertical_reset" type="button" class="btn btn-info btn-block">Reset</button>
					</div>
			</div>
		</form>
	</div>
    </div>
  </div>
</div>
                         
