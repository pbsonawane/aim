<script>


	/**For PO Number @27/9/2023**/
$(document).ready(function() {


 $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });

  // Search on status column only
                $('#txt_status').keyup(function(){
                    // Search Text
                    var search = $(this).val();

                    // Hide all table tbody rows
                    $('table tbody tr').hide();

                    // Count total search result
                    var len = $('table tbody tr:not(.notfound) td:nth-child(2):contains("'+search+'")').length;
                    
                    if(len > 0){
                      // Searching text in columns and show match row
                      $('table tbody tr:not(.notfound) td:contains("'+search+'")').each(function(){
                          $(this).closest('tr').show();
                      });
                    }else{
                      $('.notfound').show();
                    }
                    
                });
/**End For PO Number**/

  // Event handler for the date inputs
  $('#start-date, #end-date').change(function() {
    filterTable();
  });


  // Event handler for the search input

  $("#txt_status").on("change", function() {
    filterTable();
  });

  function filterTable() {
    var startDate = $('#start-date').val();
    var endDate = $('#end-date').val();
    var searchText = $("#txt_status").val().toLowerCase();

 	console.log("filtertablefuction call ");

    $('#myTable  tr').each(function() {
	
      var rowDateText = $(this).find('td:eq(3)').text();
      var rowText = $(this).text().toLowerCase();
	console.log("date text = "+ rowDateText );
		// Create a Date object from the date text
                var rowDate = new Date(rowDateText);

                // Extract the date part (year, month, day)
                var year = rowDate.getFullYear();
                var month = (rowDate.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-indexed
                var day = rowDate.getDate().toString().padStart(2, '0');

                // Create the formatted date string
                var formattedDate = year + '-' + month + '-' + day;
	
 	console.log("searchText = 	"+searchText +"  "+rowText  );
	
      // Check if the row matches the date range and search text
      if (   (formattedDate  >= startDate && formattedDate  <= endDate) &&  (rowText.indexOf(searchText) !== -1) ) {
        $(this).show();
      } else {
        $(this).hide();
      }
	
    });
  }
});
        $("#form1").hide();
        $("#formButton").click(function() {
    	$("#form1").toggle();
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
	<input id="myInput" type="text" placeholder="Search.." style="width:150px;height:30px;margin-left:10px;">
<center>

<div>
<button type="button" id="formButton" style="font-size:15px;color:black;">Advance Search..<span class="glyphicon glyphicon-search" style="color:lightgrey;"></span></button>
  <br>

<form id="form1">
	<div class="col-md-12">

<!-- For status --->
	  <div class="col-md-4">
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

	   </div>
	  <br><br>
	 </form>
	</div>
	</center>
	<table class="table table-striped table-bordered table-hover table-responsive">
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
		  <th>Qty</th>
		 <th>Unit</th>
		  <th>Rate</th>
		  <th>TAmt</th>
		  <th>VAddr</th>
                  <th>Pan</th>
            	  <th>Contact</th>
		  <th>GST</th>
		  <th>Bank</th>

            </tr>
		</thead>


		<tbody id="myTable">
	
<tr>
      
</tr>


		</tbody>


	</table>
