<div class="col-md-10">
    <div class="hidden alert-dismissable" id="msg_popup"></div>
</div>
<div class="col-md-12">
	<div class="panel">	
		<div class="panel-body">
    	<form id="attach" name ="attach"  method="POST" action="">	
    		<input type="hidden" name="asset_id" id="asset_id" value="<?php echo $asset_id; ?>">
    		<input type="hidden" name="bv_id" id="bv_id" value="<?php echo $bv_id; ?>">
    		<input type="hidden" name="location_id" id="location_id" value="<?php echo $location_id; ?>">
    		<input type="hidden" name="asset_ci_templ_id" id="asset_ci_templ_id" value="<?php echo $asset_ci_templ_id; ?>">
    		<input type="hidden" name="tag" id="tag" value="<?php echo $tag; ?>">
    	<div class="form-group col-md-6 required" >
			<label for="email" class="col-md-4 control-label">Select CI</label>
			<div class="col-md-8">
					<select name="ci_templ_id" id="ci_templ_id" onchange="assetSelect(this.value,'in_store')"  class="chosen-select">
						<option value="">Select</option>
                	<?php 
                		if(is_array($citemplates) && count($citemplates) > 0)
                		{
                			foreach($citemplates as $citemp)
                			{
                				if(is_array($citemp['children']) && count($citemp['children']) > 0)
                				{
                					foreach($citemp['children'] as $ci)
    								{ ?>

    										<option value="<?php echo $ci['ci_templ_id']?>"><?php echo $ci['title']?></option>
                    			   <?php }
                				}

                		    }  
                		}
                	?>
                </select>
			</div>
		</div>
		<div class="form-group col-md-12">
			<div class="form-group col-md-5">
				<label for="email" class="col-md-5 control-label">Assets</label>
				<select id="multiassetids" size="15" name="multiassetids[]"  multiple="multiple" class="form-control medwidth">
					<option value="">Select</option>
				</select>	
			</div>
			<div class="form-group col-md-1">
				<div class="col-md-1" style="margin-top: 150%">
					<a href="javascript:void(0);" id="hideoption"><img src="<?php echo config('app.site_url'); ?>/enlight/images/right_arr.png"></a>
					<br>
					<br>
					<br>
					<br>

					<a href="javascript:void(0);"  id="showoption"><img src="<?php echo config('app.site_url'); ?>/enlight/images/left_arr.png"></a>	
				</div>
			</div>	
			<div class="form-group col-md-5">
				<label for="email" class="col-md-5 control-label">Selected Assets</label>
				<select id="selectassetids" size="15" name="selectassetids[]" multiple="multiple" class="form-control medwidth">
					<option value="">Select</option>
				</select>
		</div>		

		</div>
		</form>	
		<div class="form-group col-md-12">    
		<label class="col-md-4 control-label"></label>
				<div class="col-xs-2">
		                <button id="attchasset" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_submit');?></button>
				</div>
				<div class="col-xs-2">
					<button id=""  type="button" onclick="attachAsset();" class="btn btn-info btn-block"><?php echo trans('label.btn_reset');?></button>
				</div>
		</div>

    </div>
</div>
</div>

