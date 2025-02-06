<div class="panel">
    <div class="panel-heading">
    	<?php 
    	if(!empty($content)){
    	$employee_id = array_column($content, 'full_name','employee_id');?>
        <span class="panel-title">Assets List- <?php echo $employee_id[key($employee_id)],' (',key($employee_id),')';?></span>
    <?php }?>
    </div>
    <div class="panel-body">
    
    					<div class="col-md-12">
    					<div style="padding-top:10px;"><b style="padding-left:5px;"></b></div>
						<table class="table table-striped table-bordered table-hover">
							<thead>
							  <tr>

								<th style="width: 200px;word-break: break-word;"><?php echo trans('label.lbl_assets');?></th>
					            <th>Assign Date</th>
								<th>Status</th>
					            <th>Created date</th>
								<th>Retured Date</th>	
							  </tr>
							</thead>
							<tbody>
								<?php 
								
								if(!empty($content)){
									foreach ($content as $value) {?>
										<tr>
										<td><a href="/assets/<?php echo $value['asset_id'],'/',$value['ci_templ_id'];?>" target="__blank"><?php echo $value['display_name'];?></a></td>								
										<td><?php echo $value['assign_date'];?></td>								
										<td><?php echo $value['status'];?></td>								
										<td><?php echo $value['created_at'];?></td>								
										<td><?php echo $value['return_date'];?></td>
										</tr>								
    							<?php } }?>
    					</tbody>
    				</table>
    			</div>			
    				
    </div>
</div>
