<script>
	/**For PO Number @27/9/2023**/
$(document).ready(function() {

	  // Event handler for the date inputs
  
        $("#form1").hide();
        $("#formButton").click(function() {
    	$("#form1").toggle();
  	});
});
</script>
<style type="text/css">
#myInput {
    font-size: 16px;
    padding: 5px 16px 5px 8px;
    border: 1px solid #ddd;
    margin: 8px;
}
#form1 {
  padding: 15px;
 background: #fff;
display: none;
  
}

#formButton {
  
  margin-right: auto;
  margin-left: auto;
 
}

button#formButton {
    font-size: 16px;
    padding: 5px 16px 5px 8px;
    border: 1px solid #ddd;
    margin: 12px;
    background-color: aliceblue;
    font-weight: 200;
}

#reset-btn
{
margin-right: auto;
margin-left: auto;
}

button#reset-btn {
    font-size: 10px;
    padding: 5px 16px 5px 8px;
     width:100px;
    border: 1px solid #ddd;
    margin: 23px;
    //background-color: blue;
    font-weight: 100;
}


input#txt_name {
    font-size: 16px;
    padding: 5px 16px 5px 8px;
    border: 1px solid #ddd;
    margin: 12px;
}

input#txt_status {
    font-size: 16px;
    padding: 5px 16px 5px 8px;
    border: 1px solid #ddd;
    margin: 12px;
}

input#txt_assign {
    font-size: 16px;
    padding: 5px 16px 5px 8px;
    border: 1px solid #ddd;
    margin: 12px;
}


input#txt_project {
    font-size: 16px;
    padding: 5px 16px 5px 8px;
    border: 1px solid #ddd;
    margin: 12px;
}
.start_date {
    font-size: 16px;
    padding: 5px 16px 5px 8px;
    border: 1px solid #ddd;
    margin: 12px;
}
.end_date {
    font-size: 16px;
    padding: 5px 16px 5px 8px;
    border: 1px solid #ddd;
    margin: 12px;
}



</style>
	
<center>

<div>
<button type="button" id="formButton" style="font-size:15px;color:black;">Advance Search..<span class="glyphicon glyphicon-search" style="color:lightgrey;"></span></button>
  <br>

<form id="form1">
	<div class="col-md-12">

<!-- For Assigner--->
	  <div class="col-md-3">
	<label for="po-number">Assigner</label>
	 <input id="txt_assign" type="text" class="" placeholder="Search Name.." class="search_">
	  </div>
<!-- End For Assigner-->	



<div class="col-md-6">
<label for="start-date">Start Date:</label>
<input type="date" id="start-date" class="start_date">
<label for="end-date">End Date:</label>
<input type="date" id="end-date" class="end_date">
</div>
<div class="col-md-3">
<button id="reset-btn" class="btn btn-primary">Reset Filters</button>
</div>

	   </div>

	  <br><br>
	 </form>
	</div>
	</center>
	<table id="example" class="cell-border" style="width:100%">
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
			$requesterinfo   = $requesterfname. " " . $requesterlname;
		}
					// 

         	$pr_id                  = $val['pr_id'];
					$pr_no                  = $val['pr_no'];
					$project_name           = '';
					$segment                = '';
					$project                = '';
					$component              = [];
					$request_initiated_date = $val['created_at'];

					$created_date           = new DateTime($val['created_at']);
					$interval               = $created_date->diff(new DateTime(Date('Y-m-d h:i:s')));
					$day_lapsed             = $interval->d;

					$delivery_timeline      = '';
					$priority               = '';
					$status                 = $val['status'];
					$remark                 = ($val['remark'] != 'null') ? implode("<br/>", json_decode($val['remark']) ) : '';    
					
					$component_desc = [];
					$component_qty = [];
					$component_warranty = [];

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
                        $item_id_array        = [];  
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
							$item_name_array      = [];
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
					$itemdetails = [];
					
					for($i=0;$i<count($component_warranty);$i++)
					{
						$Name = isset($component[$i]) ? $component[$i] : '';
						$Desc = isset($component_desc[$i]) ? $component_desc[$i] : '';
						$Qty = isset($component_qty[$i]) ? $component_qty[$i] : '';
						$Warranty = isset($component_warranty[$i]) ? $component_warranty[$i] : '';
						$itemdetails[$i] =  "<b>Name - </b>". $Name ." , <b>Desc - </b>" . $Desc . " , <b>Qty - </b>" . $Qty . " , <b>Warranty - </b>" . $Warranty;

					}
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
	                 
	                  <td><span id="remark_content_<?php echo $pr_id; ?>"><?php echo $remark ?></span></td>
	                  <td><?php echo $dependancy; ?></td>
	                  <td>
	                  <span title = "Add Remark" type="button" id="add_remark_'.$pr_id.'" data-pr_id="<?php echo $pr_id; ?>" class="add_remark" >
	                  <i class="fa fa-plus mr10 fa-lg"></i>
	                  </span> 
	                  
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
<script>
$(document).ready(function() {
    var table = $('#example').DataTable({
	
        dom: 'Bfrtip',
        paging: false,
        buttons: [
            'excelHtml5',
            'csvHtml5'
        ]
    });

    // Event handler for the date inputs
    $('#start-date, #end-date').change(function() {
        table.draw();
    });

    // Event handler for the search input
    $("#txt_assign").on("keyup", function() {
        table.draw();
    });

	 // Event handler for the reset button
    $('#reset-btn').on('click', function(event) {
	event.preventDefault();
        // Clear date inputs
        $('#start-date, #end-date').val('');
        // Clear search input
        $("#txt_assign").val('');
        // Redraw the table to remove filters
        table.draw();
    });


    // Custom filtering function for date range and assigner name
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var startDate = $('#start-date').val();
            var endDate = $('#end-date').val();
            var requestInitiatedDate = data[7]; // Assuming the "Request Initiated Date" column is at index 7
            var assignerName = data[2].toLowerCase(); // Assuming the "Assigner Name" column is at index 11
            var searchText = $("#txt_assign").val().toLowerCase();

            // Filter by date range
            var dateInRange = (startDate === '' && endDate === '') ||
                              (startDate === '' && requestInitiatedDate <= endDate) ||
                              (startDate <= requestInitiatedDate && endDate === '') ||
                              (startDate <= requestInitiatedDate && requestInitiatedDate <= endDate);

            // Filter by assigner name and search text
            var assignerMatch = assignerName.includes(searchText);
            var searchTextMatch = data[2].toLowerCase().includes(searchText); // Assuming the "Requester Name" column is at index 2

            return dateInRange && (assignerMatch || searchTextMatch);
        }
    );

    // Apply the custom filtering function to the DataTable
    table.draw();
});

</script>