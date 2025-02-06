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
           <!--  <tr>
               <th colspan="7" class="text-center" style="background: #e6e8ff;">Items Details</th>
            </tr> -->
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
						
						if(!empty($opp_details))
						{
						
						foreach ($opp_details as $key => $val) {
						    ?>
			             <tr>
			               <td><?php echo $val['core_product_name'] ?></td>
			               <td><?php echo $val['sku_code'] ?></td>
			                <td><?php echo $val['item_location_name'] ?></td>
			                <td><?php echo $val['unit_name'] ?></td>
			               <td class="text-center" style="color:green; font-weight:bold;"><?php echo $val['item_quantity'] ?></td>
			               <td class="text-center"><?php
							if ($val['in_stock'] == 0) {
							        echo '<span style="color:red; font-weight:bold;">' . $val['in_stock'] . '</span>';
							    } else {
							        echo '<span style="color:green; font-weight:bold;">' . $val['in_stock'] . '</span>';
							    }
							    ?></td>
											            <td class="text-center">
											            	<?php
							$Order_Quantity = $val['item_quantity'] - $val['in_stock'];
							    echo '<span style="color:blue; font-weight:bold;">' . $Order_Quantity . '</span>';?>
											            </td>
														            </tr>
														            <?php
							}
}else
{
?>
 <tr>
			               <td colspan="6">No Data Found.</td>
						   </tr>
<?php

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
th{
	text-align: center !important;
	letter-spacing: 0.5px !important;
	border : 1px solid #bce0ff !important;
	background-color: aliceblue !important;
	color: black !important;
}
td{border : 1px solid #bce0ff !important;}
</style>
