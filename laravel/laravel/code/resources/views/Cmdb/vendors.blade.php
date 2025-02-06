<!-- Start: Topbar -->
<header id="topbar" class="affix"  >
<div class="topbar-left">
   <?php breadcrum(trans('title.vendor')); ?>
</div>
<div class="topbar-right">
  @if(canuser('create','vendor'))
	<div class="btn-group">
		<button id="timeline-toggle" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		<span class="glyphicons glyphicons-show_lines fs16"></span>
		</button>
		 <ul class="dropdown-menu pull-right" role="menu" >
			<li class="vendoradd" id="vendoradd" title="Vendor Add">
            <a ><span title="Vendor Add" class="vendoradd"><?php echo trans('label.lbl_add_vendor');?></span></a>
			</li>
		</ul>
	</div>
  @endif
</div>
<style>
  .error{
    color:red;
  }
</style>
</header>
<!-- End: Topbar -->
<div id="content">
  <div class="row">
    <div class="col-md-12">
      <div class="alert hidden alert-dismissable" id="msg_div"></div>
    </div>
    <div class="col-md-12">
    	<form method="post" name="frmdevices" id="frmdevices">  	
      		<div class="panel">
				<?php echo csrf_field(); ?>	
                <?php echo isset($emgridtop) ? $emgridtop : ''; ?>						
				<div class="row" style="padding: 5px;">					
					<div class="col-md-8">												
						<select id="multiple" name="search_service[]" class="js-states chosen-select form-control input-sm" multiple>
						<option value="">Select Services</option>
							<?php
							if(isset($getvendorservices)){
								$VendorServices = array();
								$Vendor_Services = explode(',',$getvendorservices['content'][0]['VendorServices']);
								foreach($Vendor_Services as $Services)
								{
									array_push($VendorServices,trim($Services));
								}
								$VendorServices = array_unique($VendorServices);
								
								if(is_array($VendorServices) && count($VendorServices) > 0)
								{
									foreach($VendorServices as $Services)
		                        	{?>
		                        		<option value="<?php echo $Services; ?>" ><?php echo $Services; ?></option>
		                       <?php 	}
		                        }	
							}else{} ?>
						</select>
						
					</div>
					<div class="col-md-4">
						<button class="btn btn-primary px-4" type="button" id="SearchVendorServices">Search Services</button>
					</div>
				</div>		
                <div class="panel panel-visible" id="grid_data"></div>	
      		</div>
      	</form>
    </div>
  
  </div>
</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>
<script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>

<!-- Select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script>	
	$("#multiple").select2({
		placeholder: "Select Services",
		allowClear: true,
		maximumSelectionLength: 10
	});
</script>
<script language="javascript" type="text/javascript" src="enlight/scripts/common.js"></script> 
<script language="javascript" type="text/javascript" src="enlight/scripts/cmdb/vendor.js"></script>
