<script>
	/**For PO Number @27/9/2023**/
$(document).ready(function() {

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

button#formButton {
    font-size: 16px;
    padding: 5px 16px 5px 8px;
    border: 1px solid #ddd;
    margin: 12px;
    background-color: aliceblue;
    font-weight: 200;
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

<!-- For status --->
	  <div class="col-md-3">
	<label for="po-number">PON:</label>
	 <input id="txt_status" type="text" class="" placeholder="Search PO.." class="search_">
	  </div>
<!-- End For Status -->	

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
                   <th>VNo.</th>
                   <th>PNo.</th>
               	   <th>QRefNo.</th>
		   <th>PO Date</th>
		  <th>Project</th>
		  <th>IT/NIT</th>
		  <th>HW/SW/Service</th>
		  <th>Vendor</th>
		  <th>Desc</th>
		  <th>Full Desc</th>
		  <th>Qty</th>
		 <th>Unit</th>
		  <th>Rate</th>
		  <th>GST Tax</th>
	          <th>TaxAmt</th>
		  <th>TAmt</th>
		 <th>Ledger name</th>
		 <th>VState</th>
		 <th>VAddr</th>
                 <th>Pan</th>
            	 <th>Contact</th>
		 <th>GST</th>
		 <th>Bank</th>
		 <th>Account No.</th>
		 <th>IFSC Code</th>

            </tr>
		</thead>
<?php
$newArr = $_SESSION['myArray'];
//print_r($newArr);exit();


?>

		<tbody id="myTable">
	<?php 
		$j = 0;
		foreach($newArr as $key => $item) { 

			$gst_amount = str_replace('"','', $newArr[$j]['amount']) * 0.18;

			$net_amount = str_replace('"','', $newArr[$j]['amount']) + $gst_amount;
			
			$vendor_state = $newArr[$j]['vendor_state'];

			

?>
	
<tr>
       <td><?php echo  ++$key?></td>
	<td><?php echo $item['po_no']?></td>
	<td><?php echo $item['quotation_cmp_id'] ?></td>
     	<td><?php echo $item['created_at'] ?></td>
	<!--<td><?php echo trim($item['Project_name'], '"') ?></td>-->
<td>
    <?php 
    if (trim($item['Project_name'], '"') == "NDC") {
        echo "Nashik Data Center";
    } elseif (trim($item['Project_name'], '"') == "MDC") {
        echo "Mumbai Data Center";
    } else {
        echo "Bangalore Data Center";
    }
    ?>
</td>
	<td><?php echo trim($item['project_req'], '"') ?></td>
	<td><?php echo trim($item['project_cat'], '"') ?></td>
	<td><?php echo $item['vendor_name'] ?></td>
	<td><?php echo $item['desp'] ?></td>
	<td><?php echo $item['desp'] ?></td>
	<td><?php echo trim($item['qty'], '"') ?></td>
	<td><?php echo trim($item['unit'], '"') ?></td>
	<td><?php echo trim($item['rate'], '"') ?></td>
	<td>18%</td>
	<td><?php echo $gst_amount; ?></td>
	<td><?php echo $net_amount; ?></td>
	<td>Fees and Sub./ AMC</td>
	<td><?php echo $vendor_state; ?></td>
	<td><?php echo $item['address'] ?></td>
	<td><?php echo $item['vendor_pan'] ?></td>
	<td><?php echo $item['contactno'] ?></td>
	<td><?php echo $item['vendor_gst_no'] ?></td>
	<td><?php echo $item['bank_name'] ?></td>
	<td><?php echo $item['bank_account_no'] ?></td>
    	<td><?php echo $item['ifsc_code'] ?></td>	
</tr>
<?php   
	$j++;
} ?>	

		</tbody>


	</table>
<script>
$(document).ready(function() {
  var table =   $('#example').DataTable( {
        dom: 'Bfrtip',
	 paging:  false,
  	
	
        
        buttons: [
            
            'excelHtml5',
            'csvHtml5'
            
        ]
});



/**End For PO Number**/

   // Event handler for the date inputs
    $('#start-date, #end-date').change(function() {
        table.draw();
    });

    // Event handler for the search input
    $("#txt_status").on("keyup", function() {
        table.draw();
    });


		 // Event handler for the reset button
    $('#reset-btn').on('click', function(event) {
	event.preventDefault();
        // Clear date inputs
        $('#start-date, #end-date').val('');
        // Clear search input
        $("#txt_status").val('');
        // Redraw the table to remove filters
        table.draw();
    });


    // Custom filtering function for date range and assigner name
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            var startDate = $('#start-date').val();
            var endDate = $('#end-date').val();
            var requestInitiatedDate = data[3]; // Assuming the "Request Initiated Date" column is at index 7
            var assignerName = data[1].toLowerCase(); // Assuming the "Assigner Name" column is at index 11
            var searchText = $("#txt_status").val().toLowerCase();

            // Filter by date range
            var dateInRange = (startDate === '' && endDate === '') ||
                              (startDate === '' && requestInitiatedDate <= endDate) ||
                              (startDate <= requestInitiatedDate && endDate === '') ||
                              (startDate <= requestInitiatedDate && requestInitiatedDate <= endDate);

            // Filter by assigner name and search text
            var assignerMatch = assignerName.includes(searchText);
            var searchTextMatch = data[1].toLowerCase().includes(searchText); // Assuming the "Requester Name" column is at index 2

            return dateInRange && (assignerMatch || searchTextMatch);
        }
    );

    // Apply the custom filtering function to the DataTable
    table.draw();
	});

</script>