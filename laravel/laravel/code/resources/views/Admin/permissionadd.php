<style>
	.typeahead { border: 2px solid #FFF;border-radius: 4px;padding: 8px 12px;max-width: 300px;min-width: 290px;background: rgba(66, 52, 52, 0.5);color: #FFF;}
	.tt-menu { width:300px; }
	ul.typeahead{margin:0px;padding:10px 0px;}
	ul.typeahead.dropdown-menu li a {padding: 10px !important;	border-bottom:#CCC 1px solid;color:#FFF;}
	ul.typeahead.dropdown-menu li:last-child a { border-bottom:0px !important; }
	.bgcolor {max-width: 550px;min-width: 290px;max-height:340px;background:url("world-contries.jpg") no-repeat center center;padding: 100px 10px 130px;border-radius:4px;text-align:center;margin:10px;}
	.demo-label {font-size:1.5em;color: #686868;font-weight: 500;color:#FFF;}
	.dropdown-menu>.active>a, .dropdown-menu>.active>a:focus, .dropdown-menu>.active>a:hover {
		text-decoration: none;
		background-color: #1f3f41;
		outline: 0;
	}
	</style>
<div class="col-md-10">
        <div class="hidden alert-dismissable" id="msg_popup"></div>
</div>
<div class="col-md-12">
	<div class="panel">
		<div class="panel-body">
			<form class="form-horizontal"  name="addformpermission" id="addformpermission">
				<input id="permission_id" name="permission_id" type="hidden" value="<?php echo isset($permissionid) ? $permissionid : ''; ?>">
					<div class="form-group required ">
						<label for="inputStandard" class="col-md-5 control-label">Permission Name</label>
						<div class="col-md-7">
							<input type="text" id="permission_name" name="permission_name" class="form-control input-sm" value="<?php echo isset($permissiondata[0]['permission_name']) ? $permissiondata[0]['permission_name'] : ''; ?>">
						</div>
                    </div>
                    <div class="form-group required ">
						<label for="inputStandard" class="col-md-5 control-label">Key</label>
						<div class="col-md-7">
							<input type="text" id="permission_key" name="permission_key" class="form-control input-sm" value="<?php echo isset($permissiondata[0]['permission_key']) ? $permissiondata[0]['permission_key'] : ''; ?>" <?php echo isset($permissionid) ? 'readonly="true"' : ''; ?>>
						</div>
                    </div>
                    <div class="form-group required ">
						<label for="inputStandard" class="col-md-5 control-label">Type</label>
						<div class="col-md-7">
                            <label class="radio-inline mr5"><input type="radio" id="permission_type" name="permission_type" value="crud" <?php echo isset($permissiondata[0]['permission_type']) && $permissiondata[0]['permission_type'] == 'crud' ? 'checked="checked"' : ''; ?>>Crud</label>
                            <label class="radio-inline mr5"><input type="radio" id="permission_type" name="permission_type" value="advanced" <?php echo isset($permissiondata[0]['permission_type']) && $permissiondata[0]['permission_type'] == 'advanced' ? 'checked="checked"' : ''; ?>>Advanced</label>
						</div>
                    </div>
                    <div class="form-group required ">
						<label for="inputStandard" class="col-md-5 control-label">Category</label>
						<div class="col-md-2 nopaddingright perm_new_category_name_div">
                            <input class="form-control input-sm" id="perm_new_category_name" name="perm_new_category_name">
                            
   
                            <!--<div class="input-group">
                                <input type="text" value="" class="form-control" name="text">
                                <div class="input-group-btn bs-dropdown-to-select-group">
                                    <button type="button" class="btn btn-default dropdown-toggle as-is bs-dropdown-to-select" data-toggle="dropdown">
                                        <span data-bind="bs-drp-sel-label">Select...</span>
                                        <input type="hidden" name="selected_value" data-bind="bs-drp-sel-value" value="">
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu" style="">
                                        <li data-value="1"><a href="#">One</a></li>
                                        <li data-value="2"><a href="#">Two</a></li>
                                        <li data-value="3"><a href="#">Three</a></li>
                                    </ul>
                                </div>
                            </div>-->
                            
                        </div>
                        <div class="col-md-5 nopaddingleft perm_category_name_div">
                            <!--<i id="addCategory" class="fa fa-plus-circle mr10 fa-lg" ></i>-->
                            <select id="perm_category_name" name="perm_category_name"  class="chosen-select" tabindex="5" data-placeholder="Select Category">
                                
                                <?php
                                    echo "<option value=''> Select Category </option>";
                                if(isset($categories)){
                                    foreach($categories as $cat)
                                    {                                        
                                        $selected = isset($permissiondata[0]['perm_category_name']) && $permissiondata[0]['perm_category_name']==$cat ? 'selected="selected"' : '';
                                            echo "<option value=".$cat." ".$selected.">".$cat."</option>";
                                    }
                                } ?>
                                        
                            </select>
                        </div>
                    </div>
                    <div class="form-group perm_new_category_name" style="display:none">
                    <label for="inputStandard" class="col-md-5 control-label">New Category</label>
                        <div class="col-md-7">
                                <input id="perm_new_category_name" name="perm_new_category_name"  class="form-control input-sm" >
                        </div>
                    </div>
                    <div class="form-group required ">
						<label for="inputStandard" class="col-md-5 control-label">Module</label>
						<div class="col-md-7">
                            <select id="module_id" class="chosen-select" tabindex="5" name="module_id" class="form-control input-sm">
                                <option value="">-Select-</option>
                                <?php
                                    if (is_array($modules) && count($modules) > 0)
                                    {
                                        foreach($modules as $module)
                                        {
                                ?>
                                            <option value="<?php echo $module['module_id'];?>" <?php echo isset($permissiondata[0]['module_id']) && $permissiondata[0]['module_id'] == $module['module_id'] ? 'selected="selected"' : '';?>><?php echo $module['module_name'];?></option>
                                <?php
                                        }
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group required">
						<label for="inputStandard" class="col-md-5 control-label">Description</label>
						<div class="col-md-7">
                            <textarea id="permission_description" name="permission_description" class="form-control input-sm"><?php echo isset($permissiondata[0]['permission_description']) ? $permissiondata[0]['permission_description'] : ''; ?></textarea>
						</div>
                    </div>
					<div class="form-group">
					    <label for="inputStandard" class="col-md-5 control-label"></label>
						<div class="col-md-4">
                            <?php if (isset($permissionid)) {
                            ?>
							    <button id="permissionupdate" type="button" class="btn btn-success btn-block">Update</button>
                            <?php } else { ?>
                                <button id="permissionsave" type="button" class="btn btn-success btn-block">Submit</button>
                            <?php }?>
						</div>
						<div class="col-md-3">
							<button id="permissionreset" type="button" class="btn btn-info btn-block">Reset</button>
						</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
$(document).ready(function(e){
    $( document ).on( 'click', '.bs-dropdown-to-select-group .dropdown-menu li', function( event ) {
    	var $target = $( event.currentTarget );
		$target.closest('.bs-dropdown-to-select-group')
			.find('[data-bind="bs-drp-sel-value"]').val($target.attr('data-value'))
			.end()
			.children('.dropdown-toggle').dropdown('toggle');
		$target.closest('.bs-dropdown-to-select-group')
    		.find('[data-bind="bs-drp-sel-label"]').text($target.context.textContent);
		return false;
	});
});
</script>