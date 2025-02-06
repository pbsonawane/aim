<div class="panel">
    <div class="panel-heading">
        <span class="panel-title"><?php echo  trans('label.lbl_assetdashboard') ?></span>
    </div>
    <div class="panel-body">
    	<?php //echo "<pre>"; print_r($citemplates);?>
    	<?php 
    		if(is_array($citemplates) && count($citemplates) > 0)
    		{
    			foreach($citemplates as $citemp)
    			{
    				//print_r($citemp); die();
    				?>
    					<div class="col-md-12">
    					<div style="padding-top:10px;"><b style="padding-left:5px;"><?php echo $citemp['title']; ?></b></div>
						<table class="table table-striped table-bordered table-hover">
							<thead>
							  <tr>
								<th style="width: 200px;word-break: break-word;"><?php echo trans('label.lbl_assets');?></th>
								<th><?php echo trans('label.lbl_instore');?></th>
					            <th><?php echo trans('label.lbl_inuse');?></th>
								<th><?php echo trans('label.lbl_inrepair');?></th>
					            <th><?php echo trans('label.lbl_expired');?></th>
								<th><?php echo trans('label.lbl_disposed');?></th>	
								<th><?php echo trans('label.lbl_total');?></th>				            
							  </tr>
							</thead>
							<tbody>
    				<?php
    				if(is_array($citemp['children']) && count($citemp['children']) > 0)
    				{
    					foreach($citemp['children'] as $ci)
    					{

    						if(is_array($ci['asset_staus']) && count($ci['asset_staus']) > 0)	

    								$total = array_sum($ci['asset_staus']);
    						else
    							$total = 0;
    						  						?>
    						<tr>
								<td style="width: 200px;word-break: break-word;"><a href="javascript:void(0)" onclick="assets('<?php echo $ci["title"]?>','<?php echo $ci["ci_type_id"]?>','<?php echo $ci["ci_templ_id"]?>','<?php echo $ci["prefix"]?>')"><?php echo $ci['title']?></a></td>
								<td><?php echo _isset($ci['asset_staus'],'in_store',0)?></td>
					           	<td><?php echo _isset($ci['asset_staus'],'in_use',0)?></td>
								<td><?php echo _isset($ci['asset_staus'],'in_repair',0)?></td>
					            <td><?php echo _isset($ci['asset_staus'],'expired',0)?></td>
								<td><?php echo _isset($ci['asset_staus'],'disposed',0)?></td>		
								<td><?php echo $total;?></td>			            
							  </tr>
    						<?php
    					}
    				}
    				?>
    					</tbody>
    				</table>
    			</div>			
    				<?php
    			} 
    		}
			else
			{
				echo trans('label.no_records');
			}
    	?>
    </div>
</div>
