<?php
    $error_array = " ";
    if(isset($status)) {
        if($status == 1)
            $error_array = implode(", ", $error);
    }
?>

<!-- Start: Topbar -->
<header id="topbar" class="affix"  >
<div class="topbar-left">
   <?php breadcrum(trans('title.vendor')); ?> 
</div>
<div class="topbar-right">
</div>
<style>
  .error{
    color:red;
  }
</style>
</header>
<!-- End: Topbar -->
<div id="content">
    <div class="row">
        <div class="col-md-12">
            <?php 
            if(isset($status) && $status == 1) { 
            ?> 
            <div class="alert" style="background: #eb6b56;" id="msg_div">
              <p class="text-white" id="ERR_string">
                <?php echo $error_array; ?>
              </p>
            </div>
            <?php
            }
            ?>
        </div>
        <div class="col-md-12">
            <div class="col-md-8" style="display: contents;">
                <div class="panel">
                    <div class="panel-body">
                        <form class="form-horizontal" name="pbireports" id="pbireports" method="POST" action="/submit_pbireports" onsubmit="return validateForm()">
                            @csrf
                            <hr>
                            <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label">Report Type</label>
                                <div class="col-md-8">
                                    <select onchange="isopenpo(this.value);" name="report_type" id="report_type" class="form-control input-sm" style="width: 50%;">
                                        <option value="">Select</option>
                                        <option value="po_data">PO Data</option>
                                        <option value="open_po">Open PO</option>
                                        <option value="supporting">Supporting</option>
                                    </select>
                                </div>
                            </div>
                            <div id="from_date_div" class="form-group required">
                                <label for="inputStandard" class="col-md-3 control-label">From Date</label>
                                <div class="col-md-8">
                                    <input id="fromdate" type="date" name="fromdate" class="form-control input-sm" style="width: 50%;">
                                </div>
                            </div>
                            <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label">To Date</label>
                                <div class="col-md-8">
                                   <input id="todate" type="date" name="todate" class="form-control input-sm" style="width: 50%;">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label"></label>
                                <div class="col-xs-2">
                                    <button id="submit" type="submit"
                                        class="btn btn-success btn-block"><?php echo trans('label.btn_submit');?></button>
                                </div>
                                <div class="col-xs-2">
                                    <a id="reset" type="reset" href="{{ url('/pbireports') }}" class="btn btn-info btn-block">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>



<script>
    function isopenpo(report_type) {
        if(report_type == 'open_po') {
            $('#from_date_div').hide();
        } else {
            $('#from_date_div').show();
        }
    }

    function validateForm() {
        let to_date     = document.forms["pbireports"]["todate"].value;
        let from_date   = document.forms["pbireports"]["fromdate"].value;
        let report_type = document.forms["pbireports"]["report_type"].value;

        let error       = "";
        if(report_type != 'open_po') {
            if (to_date == "") {
                error += "To date must be filled out";
            } else {
                if(new Date(to_date) > Date.now()) 
                    error += error ? "\nTo Date must be less than today date" : "To Date must be less than today date";
            }
            if(from_date == "") {
                error += error ? "\nFrom Date must be filled out" : "From Date must be filled out";
            } else {
                if(new Date(from_date) > Date.now()) 
                    error += error ? "\nFrom Date must be less than today date" : "From Date must be less than today date";
            }
            if(report_type == "") {
                error += error ? "\nReport Type must be filled out" : "Report Type must be filled out";
            }
            if(from_date != '' && to_date != '' && new Date(to_date) < Date.now() && new Date(from_date) < Date.now()) {
                if(from_date > to_date) {
                    error += error ? "\nFrom Date is not gretter than To Date" : "From Date is not gretter than To Date";
                }
            }
        } else {
            if (to_date == "") {
                error += "To date must be filled out";
            } else {
                if(new Date(to_date) > Date.now()) 
                    error += error ? "\nTo Date must be less than today date" : "To Date must be less than today date";
            }
            if(report_type == "") {
                error += error ? "\nReport Type must be filled out" : "Report Type must be filled out";
            }
        }

        if(error == "") {
            return true;
        } else {
            alert(error);
            return false;
        }
    }
   
    if($('#msg_div').is(':visible')) {
        setTimeout(function() { 
            $('#msg_div').hide();
            }, 3000);
    }
   
</script>
