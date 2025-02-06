<!--<link rel="stylesheet" href="<?php //echo config('app.site_url'); ?>/enlight/scripts/multiselect/bootstrap-chosen.css" />
<script src="<?php //echo config('app.site_url'); ?>/enlight/scripts/multiselect/chosen.jquery.js"></script>
<link rel="stylesheet" type="text/css" href="<?php //echo config('app.site_url'); ?>/enlight/scripts/multiselect/jquery.mCustomScrollbar.min.css">
<script src="<?php //echo config('app.site_url'); ?>/enlight/scripts/multiselect/jquery.mCustomScrollbar.concat.min.js"></script>-->
    
    <script>
      $(function() {
        initsingleselect();
        initmultiselect();
        //$('.chosen-select').chosen();
       // $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
      });
    </script>
   <!-- <style>
        .chosen-container-multi .chosen-choices {
           /* overflow: auto !important;*/
            height: 34px !important;
            white-space: nowrap;
           /* width: auto !important;*/
        }
        .chosen-container-multi .chosen-choices li {
            float: none;
            display: inline;
        }
        .mCSB_horizontal.mCSB_inside > .mCSB_container {
	    width: 1000px !important;
        }
        </style>-->
<div class="panel-body bg-light form-group"> 
	
	<div class="col-md-12" id="datepickerdiv">
		<div class="row">
		<?php if (in_array("datesearch", $element_options)) {
			$timerange = _isset($advsearch_setting, 'timerange');
			$customtime = _isset($advsearch_setting, 'customtime');
			
		?>
			<div class="col-md-3 mt10">
				<div class="section">
						<select id="timerange" class="chosen-select" tabindex="5" data-placeholder="Select Date Range"  class="form-control input-sm" name="timerange" onchange="clearcustomtime();">
							<option value="">Select Date Range</option>
							<optgroup label="COMMON OPTIONS">		
								<!-- <option <?php echo $timerange == "last_15_min" ? "selected" : '' ?> value="last_15_min" >Last 15 minutes</option>
								<option <?php echo $timerange == "last_30_min" ? "selected" : '' ?>  value="last_30_min" >Last 30 minutes</option>
								<option <?php echo $timerange == "last_1_hour" ? "selected" : '' ?>  value="last_1_hour" >Last 1 hour</option>
								<option <?php echo $timerange == "last_6_hour" ? "selected" : '' ?>  value="last_6_hour" >Last 6 hours</option>
								<option <?php echo $timerange == "last_12_hour" ? "selected" : '' ?>  value="last_12_hour" >Last 12 hours</option>
								<option <?php echo $timerange == "last_24_hour" ? "selected" : '' ?>  value="last_24_hour" >Last 24 hours</option> -->
								<option <?php echo $timerange == "today" ? "selected" : '' ?>  value="today" >Today</option>
								<option <?php echo $timerange == "last_3_days" ? "selected" : '' ?>  value="last_3_days" >Last 3 Days</option>
								<option <?php echo $timerange == "last_7_days" ? "selected" : '' ?>  value="last_7_days" >Last 7 days</option>
								<option <?php echo $timerange == "last_15_days" ? "selected" : '' ?>  value="last_15_days" >Last 15 days</option>
								<option <?php echo $timerange == "last_30_days" ? "selected" : '' ?>  value="last_30_days" >Last 30 days</option>
								<option <?php echo $timerange == "last_60_days" ? "selected" : '' ?>  value="last_60_days" >Last 60 days</option>
								<option <?php echo $timerange == "last_90_days" ? "selected" : '' ?>  value="last_90_days" >Last 90 days</option>
								<option <?php echo $timerange == "last_6_month" ? "selected" : '' ?>  value="last_6_month" >Last 6 months</option>
								<option <?php echo $timerange == "last_1_year" ? "selected" : '' ?>  value="last_1_year" >Last 1 year</option>
								<option <?php echo $timerange == "last_2_year" ? "selected" : '' ?>  value="last_2_year" >Last 2 years</option>
							</optgroup>
							<!-- <optgroup label="COMMON OPTIONS">		
								<option <?php echo $timerange == "today" ? "selected" : '' ?>  value="today" >Today</option>
								<option <?php echo $timerange == "this_week" ? "selected" : '' ?>  value="this_week" >This week</option>
								<option <?php echo $timerange == "this_month" ? "selected" : '' ?>  value="this_month" >This month</option>
								<option <?php echo $timerange == "this_year" ? "selected" : '' ?>  value="this_year" >This year</option>
								<option <?php echo $timerange == "week_to_date" ? "selected" : '' ?>  value="week_to_date" >Week to date</option>
								<option <?php echo $timerange == "month_to_date" ? "selected" : '' ?>  value="month_to_date" >Month to date</option>
								<option <?php echo $timerange == "year_to_date" ? "selected" : '' ?>  value="year_to_date" >Year to date</option>
								<option <?php echo $timerange == "yesterday" ? "selected" : '' ?>  value="yesterday" >Yesterday</option>
								<option <?php echo $timerange == "day_b4_yest" ? "selected" : '' ?>  value="day_b4_yest" >Day before yesterday</option>
							</optgroup>	 -->
                        </select>
                     
                        
				</div>
            </div>
                   
			<div class="col-md-3 mt10">
				<div class="section">
					<div class="input-group date pull-right">
						<input type="text" class="form-control form-control input-sm" id="customtime" name="customtime"                                               value="<?php echo $customtime; ?>">
						
						<span class="input-group-addon cursor"><i class="fa fa-calendar"></i>
						</span>
						 <button type="reset" class="btn btn-primary" style="float:left;margin-left:3px;">Reset</button>
					</div>
					
				</div>
				
			</div>
			<?php } ?>

			<?php if (in_array("usertypes", $element_options)) { ?>
				<div class="col-md-3 mt10">
					<select data-placeholder="Select Roles" class="chosen-select" name="advusertype" class="advusertype">
						<option value="">-User Type-</option>

					<?php 	if(is_array($usertypes) && count($usertypes) > 0)
                        		{

                        			foreach ($usertypes as $key => $user_type) 
                        			{
                        		?>			
                        				<option value="<?php echo $key; ?>"><?php echo ucfirst($user_type); ?>
                        				</option>
                        	<?php	}
                        		}	?>
					</select>	
				</div>
			<?php } ?>	

			<?php if (in_array("roles", $element_options)) { ?>
				<div class="col-md-3 mt10">
					<select data-placeholder="Select Roles" class="chosen-select" multiple tabindex="6" name="advrole_id[]" id="advrole_id">
					<?php 	if(is_array($roles) && count($roles) > 0)
                        		{
                        			foreach ($roles as $key => $role) 
                        			{
                        		?>			
                        				<option value="<?php echo $role['role_id']; ?>"><?php echo ucfirst($role['role_name']); ?>
                        				</option>
                        	<?php	}
                        		}	?>
					</select>	
				</div>
			<?php } ?>	

			
			<?php if (in_array("departments", $element_options)) { ?>
				<div class="col-md-3 mt10">
					<select data-placeholder="Select Roles" class="chosen-select" name="advdepartment_id" id="advdepartment_id">
						<option value="">-Department-</option>

					<?php 	if(is_array($departments) && count($departments) > 0)
                        		{

                        			foreach ($departments as $key => $dept) 
                        			{
                        		?>			
                        				<option value="<?php echo $dept['department_id']; ?>"><?php echo ucfirst($dept['department_name']); ?>
                        				</option>
                        	<?php	}
                        		}	?>
					</select>	
				</div>
			<?php } ?>	

			<?php if (in_array("designations", $element_options)) { ?>
				<div class="col-md-3 mt10">
					<select data-placeholder="Select Roles" class="chosen-select" name="advdesignation_id" id="advdesignation_id">
						<option value="">-Designations-</option>

					<?php 	if(is_array($designations) && count($designations) > 0)
                        		{

                        			foreach ($designations as $key => $desig) 
                        			{
                        		?>			
                        				<option value="<?php echo $desig['designation_id']; ?>"><?php echo ucfirst($desig['designation_name']); ?>
                        				</option>
                        	<?php	}
                        		}	?>
					</select>	
				</div>
			<?php } ?>	

			<?php if (in_array("organizations", $element_options)) { ?>
				<div class="col-md-3 mt10">
					<select data-placeholder="Select Roles" class="chosen-select" name="advorg_id" id="advorg_id">
						<option value="">Organization-</option>

					<?php 	if(is_array($organizations) && count($organizations) > 0)
                        		{

                        			foreach ($organizations as $key => $org) 
                        			{
                        		?>			
                        				<option value="<?php echo $org['organization_id']; ?>"><?php echo ucfirst($org['organization_name']); ?>
                        				</option>
                        	<?php	}
                        		}	?>
					</select>	
				</div>
            <?php } ?>
            
            <?php if (in_array("contract_type", $element_options)) { ?>
				<div class="col-md-2 mt4">
					<select data-placeholder="Select Contract Type" class="chosen-select" name="advcontract_type_id" id="advcontract_type_id">
                        <option value="">-Contract Type-</option>
                        <?php 	if(is_array($contract_type) && count($contract_type) > 0)
                        		{

                        			foreach ($contract_type as $key => $contract) 
                        			{
                        		?>			
                        				<option value="<?php echo $contract['contract_type_id']; ?>"><?php echo ucfirst($contract['contract_type']); ?>
                        				</option>
                        	<?php	}
                        		}	?>
					</select>	
				</div>
            <?php } ?>	
            
            <?php if (in_array("contract_status", $element_options)) { ?>
				<div class="col-md-2 mt4">
					<select data-placeholder="Select Contract Status" class="chosen-select" name="advcontract_status" id="advcontract_status">
                        <option value="">-Contract Status-</option>
                        <option value="active">Active</option>
                        <option value="expired">Expired</option>	
					</select>	
				</div>
			<?php } ?>	

			<?php if (in_array("template_category", $element_options)) { ?>
				<div class="col-md-2 mt4">
					<select data-placeholder="Select Template Category" class="chosen-select" name="advtemplate_category" id="advtemplate_category">
                        <option value="">-Template Category-</option>
                        <?php
                        	if(is_array($template_category) && count($template_category) > 0){

                        		foreach ($template_category as $key => $tempcat){ ?>			
                        			<option value="<?php echo $tempcat['template_category']; ?>"><?php echo ucfirst($tempcat['template_category']); ?>
                        			</option>
                        	<?php	
                        		}
                        	}	?>
					</select>	
				</div>
			<?php } ?>

			<?php if (in_array("software_type", $element_options)) { ?>
				<div class="col-md-2 mt4">
					<select data-placeholder="Select Software Type" class="chosen-select" name="advsoftware_type_id" id="advsoftware_type_id">
                        <option value="">-Software Type-</option>
                        <?php 	if(is_array($software_type) && count($software_type) > 0)
                        		{

                        			foreach ($software_type as $key => $swtype) 
                        			{
                        		?>			
                        				<option value="<?php echo $swtype['software_type_id']; ?>"><?php echo ucfirst($swtype['software_type']); ?>
                        				</option>
                        	<?php	}
                        		}	?>
					</select>	
				</div>
            <?php } ?>	

            <?php if (in_array("software_category", $element_options)) { ?>
				<div class="col-md-2 mt4">
					<select data-placeholder="Select Software Category" class="chosen-select" name="advsoftware_category_id" id="advsoftware_category_id">
                        <option value="">-Software Category-</option>
                        <?php 	if(is_array($software_category) && count($software_category) > 0)
                        		{

                        			foreach ($software_category as $key => $swcat) 
                        			{
                        		?>			
                        				<option value="<?php echo $swcat['software_category_id']; ?>"><?php echo ucfirst($swcat['software_category']); ?>
                        				</option>
                        	<?php	}
                        		}	?>
					</select>	
				</div>
            <?php } ?>	



            <?php if (in_array("software_manufacturer", $element_options)) { ?>
				<div class="col-md-2 mt4">
					<select data-placeholder="Select Software Manufacturer" class="chosen-select" name="advsoftware_manufacturer_id" id="advsoftware_manufacturer_id">
                        <option value="">-Software Manufacturer-</option>
                        <?php 	if(is_array($software_manufacturer) && count($software_manufacturer) > 0)
                        		{

                        			foreach ($software_manufacturer as $key => $swmanuf) 
                        			{
                        		?>			
                        				<option value="<?php echo $swmanuf['software_manufacturer_id']; ?>"><?php echo ucfirst($swmanuf['software_manufacturer']); ?>
                        				</option>
                        	<?php	}
                        		}	?>
					</select>	
				</div>
            <?php } ?>

            <?php if (in_array("users", $element_options)) { ?>
				<div class="col-md-2 mt10">
					<select data-placeholder="Select user" class="chosen-select" name="user_id" id="user_id">
                        <option value="">-All-</option>
                        <?php 	if(is_array($users) && count($users) > 0)
                        		{

                        			foreach ($users as $key => $pr_users) 
                        			{
                        		?>			
                        				<option value="<?php echo $pr_users['user_id']; ?>"><?php echo ucfirst($pr_users['firstname']),' ',ucfirst($pr_users['lastname']); ?>
                        				</option>
                        	<?php	}
                        		}	?>
					</select>	
				</div>
            <?php } ?>
            <?php if (in_array("userlist", $element_options)) { ?>
				<div class="col-md-2 mt10">
					<input type="text" name="employee_id" id="employee_id" class="form-control" placeholder="Employee id ex: 1421">	
				</div>
            <?php } ?>
            <?php if (in_array("vendors", $element_options)) { ?>
				<div class="col-md-2 mt10">
					<select data-placeholder="Select Vendor" class="chosen-select" name="vendor_id" id="vendor_id">
                        <option value="">-Vendors-</option>
                        <?php 	if(is_array($vendors) && count($vendors) > 0)
                        		{

                        			foreach ($vendors as $key => $pr_vendor) 
                        			{
                        		?>			
                        				<option value="<?php echo $pr_vendor['vendor_id']; ?>"><?php echo ucfirst($pr_vendor['vendor_name']); ?>
                        				</option>
                        	<?php	}
                        		}	?>
					</select>	
				</div>
            <?php } ?>	
		</div>
	</div>
	
	
 
 
 
    <div class="col-md-1 mt10">
        <button onclick="<?php echo $jsfunction; ?>" id="adv_search" type="button" class="btn btn-success btn-block"><i class="fa fa-search"></i></button>
    </div>
</div>

<script>
$(document).ready(function() 
{	
	<?php if (in_array("def_open", $element_options)) { ?>
		$("#div_emadvsearch").show();
	<?php }else{ ?>
		$("#div_emadvsearch").hide();
	<?php } ?>
	$("#spn_emadvsearch").click(function(){
			$("#div_emadvsearch").slideToggle('slow');
	});
	<?php if (in_array("datesearch", $element_options)) { ?>
	$('#customtime').daterangepicker(
		{
			format: 'YYYY-MM-DD HH:mm',
		}
	);
	<?php } ?>
	<?php if (in_array("datarange", $element_options)) { ?>
		 $('#advfromdate').datetimepicker({
			 format: 'YYYY-MM-DD HH:mm',
		});
		$('#advtodate').datetimepicker({
			 format: 'YYYY-MM-DD HH:mm',
		});
	<?php } ?>
	<?php if (in_array("datesearch", $element_options)) { ?>
		 $('#advfromdate').datetimepicker({
			 format: 'YYYY-MM-DD HH:mm',
		});
		$('#advtodate').datetimepicker({
			 format: 'YYYY-MM-DD HH:mm',
		});
	<?php } ?>
	
    $(document).on("change","#sr_bu_id", function() { var cuid = $(this).val();bvListbox(cuid,'sr_bu_v_id');});
    

 


 


});


</script>

