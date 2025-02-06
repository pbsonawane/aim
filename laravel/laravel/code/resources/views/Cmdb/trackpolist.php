
<div>
	<table class="table table-striped table-bordered table-hover">
		<thead>
		 	<tr>
                 <th>Po.No</th>
                 <!-- <th>Project Name</th>
                 <th>Segment</th>
                 <th>Project</th> -->
                 <th>Componenet</th>
                 <th>Request Initiated Date</th>
                 <th>Day Lapsed</th>
                 <th>Delivery Timeline</th>
				 <th>Priority</th>
	             <th>Status</th>
            	 <th>Remark</th>
	             <!-- <th>Dependancy</th> -->
	             <th>Add Remark</th>


            </tr>
		</thead>
		<tbody>
			<?php
			$pr_list = $dbdata;

			if (is_array($pr_list) && count($pr_list) > 0)
			{
				foreach($pr_list as $i => $val)
				{	
					$fname = isset($val['pending_by']['firstname']) ? $val['pending_by']['firstname'] : '';
					$lname = isset($val['pending_by']['lastname']) ? $val['pending_by']['lastname'] : '';

          			$po_id                  = $val['po_id'];
					$po_no                  = $val['po_no'];
					$project_name           = '';
					$segment                = '';
					$project                = '';
					$component              = array();
					$request_initiated_date = $val['created_at'];

					$created_date           = new DateTime($val['created_at']);
					$interval               = $created_date->diff(new DateTime(Date('Y-m-d h:i:s')));
					$day_lapsed             = $interval->d;

					$delivery_timeline      = '';
					$priority               = '';
					$status                 = $val['status'];
					// $remark                 = $val['remark'] != 'null' ? implode("<br/>", json_decode($val['remark']) ) : '';       
					$dependancy             = $fname. " " . $lname;
					$component_desc = array();
					$component_qty = array();

					if(!empty($val['details'])) {
                        $json_data            = json_decode($val['details'], true);
                        $project_name         = (!empty($json_data['project_name'])) ? $json_data['project_name'] : (!empty($json_data['pr_project_name_dd'])?$json_data['pr_project_name_dd']:'');
                        $priority             = $json_data['pr_priority'];
                        $delivery_timeline    = $json_data['pr_due_date'];
                    }
                    if(!empty($val['asset_details'])) {
                        //get item_id from asset_deatails
                        $item_id_array        = array();  
                        $asset_details_json   = explode('#',$val['asset_details']);
                        foreach ($asset_details_json as $key => $value) {
                          	$item_id_list       = json_decode($value, true);
                         	$item_id_array[]    = $item_id_list['item_product'];
							$component_desc[]	  = $item_id_list['item_desc'];
							$component_qty[]	  = $item_id_list['item_qty'];
                        }

                        if(!empty($val['asset_name'])) {
                          	//get item_name and id from asset_name
							$item_name_array      = array();
							$asset_name_json      = explode(',',$val['asset_name']);
							foreach ($asset_name_json as $key => $value) {
	                            $tmp_item_name                  = json_decode($value, true);
	                            $key_data                       = array_keys($tmp_item_name);
	                            $val_data                       = array_values($tmp_item_name);
	                            $item_name_array[$key_data[0]]  = $val_data[0];
                          	}

                          	//prepare array of name 
                          	foreach ($item_id_array as $key => $id) {
                            	if(array_key_exists($id, $item_name_array)) {
                              		$component[]    = $item_name_array[$id];
                            	}
                          	}

                        }
                        
                                                
                    }
			?>
					<tr>
	                    <td><?php echo $po_no; ?></td>
	                    <!-- <td><?php echo $project_name; ?></td> 
	                    <td><?php echo $segment; ?> </td>
	                    <td></td> -->
	                    <td><?php echo implode("<br/>, ", $component); ?></td>
	                    <td><?php echo $request_initiated_date; ?></td>
	                    <td><?php echo $day_lapsed; ?></td>
	                    <td><?php echo $delivery_timeline; ?></td>
	                    <td><?php echo $priority; ?></td>
	                     <td><?php echo $status; ?></td>
	                      <td><span id="remark_content_<?php echo $po_id; ?>"><?php echo $val['remark'];  ?></span></td>
	                      <!-- <td><?php echo $dependancy; ?></td> -->
	                   
	                    <td>
	                      	<span title = "Add Remark" type="button" id="add_remark_'.$po_id.'" data-po_id="<?php echo $po_id; ?>" class="add_remark" >
	                      		<i class="fa fa-plus mr10 fa-lg"></i>
	                      	</span> 
	                    </td>
	                </tr>
			<?php
				}
			}
			else {
				echo '<tr><td colspan = "100" style="text-align:center">'.trans('messages.msg_norecordfound').'</td></tr>';
			}
			?>	
		</tbody>
	</table>
</div>