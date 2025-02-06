<?php
// print_r($dbdata);die;
?>

<!--Change by @Harshal Mahajan add search filter-->
<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>
<style type="text/css">
#myInput {
    font-size: 16px;
    padding: 5px 16px 5px 8px;
    border: 1px solid #ddd;
    margin: 12px;
},
</style>
<div>

	<input id="myInput" type="text" class="" placeholder="Search Here" class="search_"><br>
	<div class="col-md-4">
	<!-- <select class="form-control" id="depend">
		<option class="form-control" value="Purchase Head">Purchase Head</option>
		<option class="form-control" value="Vaishali Keshervani">Vaishali Keshervani</option>
		<option class="form-control" value="Ajinkya Bhanuwanshe">Ajinkya Bhanuwanshe</option>
	</select> -->



</div>


	<table class="table table-striped table-bordered table-hover">
		<thead>
		 	<tr>
                <th>Pr.No</th>
                <th>Status</th>
                <th>Requester Name</th>
                <th>Department</th>
                <th>Project Name</th>
				<th>Project Category</th>
                <th>Asset Details</th>
                <th>Request Initiated Date</th>
                <th>Day Lapsed</th>
                <th>Delivery Timeline</th>
                <th>Priority</th>
                <th>Remark</th>
                <th>Dependancy</th>
                <th>Add Remark</th>
            </tr>
		</thead>
		<tbody id="myTable">
			<?php
			$pr_list = $dbdata;
			
			if (is_array($pr_list) && count($pr_list) > 0)
			{
				foreach($pr_list as $i => $val)
				{	
					if(isset($val['pending_by']))
					{
						$fname = isset($val['pending_by']['firstname']) ? $val['pending_by']['firstname'] : '';
						$lname = isset($val['pending_by']['lastname']) ? $val['pending_by']['lastname'] : '';
						$dependancy             = $fname. " " . $lname;
					}else{
						$fname = "";
						$lname = "";
						$dependancy             = $fname. " " . $lname;
					}
					
					
					if(isset($val['requester_info']))
					{
						$requesterfname = isset($val['requester_info']['firstname']) ? $val['requester_info']['firstname'] : '';
						$requesterlname = isset($val['requester_info']['lastname']) ? $val['requester_info']['lastname'] : '';
						$requesterdepartment = isset($val['requester_info']['department_name']) ? $val['requester_info']['department_name'] : '';
						$requesterinfo             = $requesterfname. " " . $requesterlname;
					}else{
						$requesterfname = "";
						$requesterlname = "";
						$requesterdepartment = "";
						$requesterinfo             = $requesterfname. " " . $requesterlname;
					}
					// 

         	$pr_id                  = $val['pr_id'];
					$pr_no                  = $val['pr_no'];
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
					$remark                 = ($val['remark'] != 'null') ? implode("<br/>", json_decode($val['remark']) ) : '';    
					
					$component_desc = array();
					$component_qty = array();
					$component_warranty = array();

					if(!empty($val['details'])) {
                        $json_data            = json_decode($val['details'], true);
						if($json_data['project_name'] == "null")
						{
							$project_name = $json_data['pr_project_name_dd'];
						}else{
							$project_name = $json_data['project_name'];
						}
                        // $project_name         = (!empty($json_data['project_name'])) ? $json_data['project_name'] : (!empty($json_data['pr_project_name_dd'])?$json_data['pr_project_name_dd']:'');
						$pr_project_category = $json_data['pr_project_category'];

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
							$component_warranty[]	  = $item_id_list['warranty_support_required'];
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
					$itemdetails = array();
					
					for($i=0;$i<count($component_warranty);$i++)
					{
						$Name = isset($component[$i]) ? $component[$i] : '';
						$Desc = isset($component_desc[$i]) ? $component_desc[$i] : '';
						$Qty = isset($component_qty[$i]) ? $component_qty[$i] : '';
						$Warranty = isset($component_warranty[$i]) ? $component_warranty[$i] : '';
						$itemdetails[$i] =  "<b>Name - </b>". $Name ." , <b>Desc - </b>" . $Desc . " , <b>Qty - </b>" . $Qty . " , <b>Warranty - </b>" . $Warranty;

					}
					
					// print_r($component);
					// print_r($component_desc);
					// print_r($component_qty);
					// print_r($component_warranty);continue;

					// $itemdetails = array();
					// foreach($component_warranty as $key=>$val){
					// 	$val1 = $component[$key];
					// 	$val2 = $component_desc[$key];
					// 	$val3 = $component_qty[$key];
					// 	$itemdetails[$key] = "<b> Name - </b>". $val1 . ", <b>Desc - </b>". $val2  . ", <b>Qty - </b>". $val3 . ", <b>Warranty - </b>". $val; 
					// }
						
			?>
					<tr>
	                    <td><?php echo $pr_no; ?></td>
	                    <td><?php echo $status; ?></td>
	                    <td><?php echo $requesterinfo; ?></td>
	                    <td><?php echo $requesterdepartment; ?></td>
	                    <td><?php echo $project_name; ?></td>
						<td><?php echo $pr_project_category;?></td>
	                    <td><?php echo implode("<br/> , ", $itemdetails); ?></td>
	                    <td><?php echo $request_initiated_date; ?></td>
	                    <td><?php echo $day_lapsed; ?></td>
	                    <td><?php echo $delivery_timeline; ?></td>
	                    <td><?php echo $priority; ?></td>
	                    <td><span id="remark_content_<?php echo $pr_id; ?>"><?php echo $remark ?></span></td>
	                    <td><?php echo $dependancy; ?></td>
	                    <td>
	                      	<span title = "Add Remark" type="button" id="add_remark_'.$pr_id.'" data-pr_id="<?php echo $pr_id; ?>" class="add_remark" >
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