
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<!-- Start: Topbar -->
<header id="topbar" class="affix"  >
	<div class="topbar-left">
		<?php breadcrum('User Dashboard'); ?>
	</div>


</header>
<!-- End: Topbar -->

<div id="content">
	<div class="row">   
		<div class="col-md-12">

			<div class="panel">
				<div class="panel-heading" style="padding-top:15px;">
					<span class="panel-title">PR User DashBoard</span>
				</div>
				<div class="panel-body">
					<table class="table table-striped table-bordered table-hover" id="example">
						<thead>
							<tr>
								<th >Users</th>
								<th>Total PR</th>
								<th>Total PO</th>
								<th>Open PO</th>
								<th>close PO</th>
								<th>Partally Open</th>
								<th>Cancelled</th>
								<th>Rejected</th>
								
								
							</tr>
						</thead>
						<tbody>
							<?php if(!empty($final_assing_pr)){
								foreach ($final_assing_pr as $value) {?>
									
								<tr>
									<td width="20%"><?php echo $value['full_name'];?></td>
									<td width="10%"><?php echo $value['total'];?></td>
									<td width="10%"><?php echo $value['totalpo']; ?></td>
									<td width="10%"><?php echo $value['openpo']; ?></td>
									<td width="10%"><?php echo $value['closedpo']; ?></td>
									<td width="10%"><?php echo $value['partallyopenpo']; ?></td>
									<td width="10%"><?php echo $value['cancelledpo']; ?></td>
									<td width="10%"><?php echo $value['rejectedpo']; ?></td>
									
									
								</tr>
							<?php } }?>
						</tbody>
					</table>					
				</div>
			</div>
		</div>
	</div>
</div>

<!--New Dashboard Section -->
			<div class="col-md-3">
			<div class="panel" id="p10">
			<div class="panel-heading">
 			<span class="panel-title">Total Purchase Report</span>
			<div class="panel-body pn">
			<table class="table mbn tc-med-1 tc-bold-last">
                        <thead>
                        <tr class="hidden">
                        <th>#</th>
                        <th>#</th>
                        </tr>
                        </thead>
                        <tbody style="background-color:white;color:darkgrey;">
			<?php $total = 0; foreach ($final_assing_pr as $value) {?>
			<tr>	
			<td width="20%"  style="color:#4587ca;"><?php echo $value['full_name'];?></td>
			
			<td width="10%"><?php echo $value['total'];?></td>
			</tr>	
			<?php $total += $value['total']; } ?>
		

			<tr>
				<td style="color:black;">Total</td>
				<td width="10%" style="color:black;"><?php echo $total;?></td>
			</tr>
			</tbody>
			</table>
			</div>
			</div>
			</div>
			</div>


		<!--Total PO section -->
		<div class="col-md-3">
			<div class="panel" id="p10">
			<div class="panel-heading">
 			<span class="panel-title">Total Purchase Order Report</span>
			<div class="panel-body pn">
			<table class="table mbn tc-med-1 tc-bold-last">
                        <thead>
                        <tr class="hidden">
                        <th>#</th>
                        <th>#</th>
                        </tr>
                        </thead>
                        <tbody style="background-color:white;color:darkgrey;">
			<?php $totalpo = 0; foreach ($final_assing_pr as $value) {?>
			<tr>	
			<td width="20%" style="color:#4587ca;"><?php echo $value['full_name'];?></td>
			
			<td width="10%"><?php echo $value['totalpo'];?></td>
			</tr>	
			<?php $totalpo += $value['totalpo']; } ?>
		

			<tr>
				<td style="color:black;">Total</td>
				<td width="10%" style="color:black;"><?php echo $totalpo;?></td>
			</tr>
			</tbody>
			</table>
			</div>
			</div>
			</div>
			</div>
		

	<!--Type of Purchase Order -->
<div class="col-md-6">
			<div class="panel" id="p10">
			<div class="panel-heading">
 			<span class="panel-title">Total Purchase Order Type</span>
			<div class="panel-body pn">
			<table class="table">
                        <thead>
                        <tr>
                        <th>Open</th>
                        <th>Closed</th>
			<th>Partially Open</th>
			<th>Cancel</th>
			<th>Reject</th>
                        </tr>
                        </thead>
                        <tbody style="background-color:white;color:darkgrey;">
			<?php $openpo = 0;$close = 0;$partially = 0;$cancel= 0;$reject = 0; foreach ($final_assing_pr as $value) {?>
			<?php $openpo += $value['openpo']; $close += $value['closedpo'];  $partially += $value['partallyopenpo'];  $cancel += $value['cancelledpo']; $reject += $value['rejectedpo'];} ?>
			<tr>
				<td width="20%" style="color:red;"><?php echo $openpo;?></td>
				<td width="20%" style="color:red;"><?php echo $close;?></td>
				<td width="20%" style="color:red;"><?php echo $partially;?></td>
				<td width="20%" style="color:red;"><?php echo $cancel;?></td>
				<td width="20%" style="color:red;"><?php echo $reject;?></td>
			</tr>
			</tbody>
			</table>
			</div>
			</div>
			</div>
			</div>



<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  


<script>
$(document).ready(function () {
    $('#example').DataTable({
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, 'All'],
        ],

    });
});
</script>




