<!-- Start: Topbar -->
<header id="topbar" class="affix"  >
   <div class="topbar-left">
      <?php breadcrum('Opportunity Quotation Details');?>
   </div>
</header>
<!-- End: Topbar -->
<div id="content">
   <div class="row">
      <div class="table-responsive ">
         <table border="1" class="table table-striped table-bordered table-hover">
            <tr>
               <th colspan="7" class="text-center" style="background: #e6e8ff;">Items Details</th>
            </tr>
            <?php 
echo '<pre>';
print_r($opp_details);exit;
            foreach ($opp_details as $phase_key => $phase_val) { ?>
            		<tr>
               			<th>Phase name</th>
		               	<th colspan="6"><?php echo $phase_val['phase_name'] ?></th>
		            </tr>
		             <?php foreach ($phase_val['group'] as $group_key => $group_val) { ?>
			             <tr>
			               <th style="">Group name</th>
			               <th colspan="6"><?php echo $group_val['group_name'] ?></th>
			            </tr>
			            <tr>
			               <th class="text-center">Product Name</th>
			               <th class="text-center">SKU Code</th>
			               <th class="text-center">Location For</th>
			               <th class="text-center">Unit</th>
			               <th class="text-center">Required Quantity</th>
			               <th class="text-center">Available Quantity</th>
			                <th class="text-center">Order Quantity</th>
			            </tr>
	            		<?php 
$Order_Quantity = 0;
	            		foreach ($group_val['items'] as $items_key => $items_val) { ?>
			             <tr>
			               <td><?php echo $items_val['core_product_name'] ?></td>
			               <td><?php echo $items_val['sku_code'] ?></td>
			                <td><?php echo $items_val['item_location_name'] ?></td>
			                <td><?php echo $items_val['unit_name'] ?></td>
			               <td class="text-center" style="color:green; font-weight:bold;"><?php echo $items_val['item_quantity'] ?></td>
			               <td class="text-center"><?php
								if ($items_val['in_stock'] == 0) {
				                echo '<span style="color:red; font-weight:bold;">' . $items_val['in_stock'] . '</span>';
				            } else {
				                echo '<span style="color:green; font-weight:bold;">' . $items_val['in_stock'] . '</span>';
				            }
				            ?></td>
				            <td class="text-center">
				            	<?php 
				            	$Order_Quantity = $items_val['item_quantity'] - $items_val['in_stock'];
				            	echo '<span style="color:blue; font-weight:bold;">' .$Order_Quantity  . '</span>'; ?>
				            </td>
							            </tr>
							            <?php
										}
								   }
								}
								?>
            <tr style="background: #e6e8ff;">
	   			<th colspan="7" style="background: #e6e8ff;">&nbsp;</th>
	        </tr>
         </table>
      </div>
   </div>
</div>
</div>
<script language="javascript" type="text/javascript" src="enlight/scripts/common.js"></script>
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/dropzone/downloads/dropzone.min.js"></script>

<style>
.table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {
	border: 1px solid #c4c4c4;
	color: black;
}
.cls { background: #e6e8ff !important; };
body{color: #2f2f2f;}
</style>
