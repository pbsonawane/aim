<div class="panel-heading br-l br-r br-t" style="background-color:aliceblue;">
    <span class="panel-title">
        <?php echo "Asset Complaint"; ?> -
        <?php echo isset($crdetail['complaint_raised_no']) ? $crdetail['complaint_raised_no'] : "" ?></span>
    <div class="panel-header-menu pull-right mr10">
    </div>
</div>
<div class="panel-body pn br-n">
    <div class="tab-block mb25">
        <ul class="nav nav-tabs tabs-bg tabs-border">
            <li class="purchase_requesttab active">
                <a href="#purchase_request" data-toggle="tab" aria-expanded="false"><i
                        class="fa fa-info-circle  text-purple"></i>
                    Asset Complaint</a>
            </li>
            <li class="approve_reject_prtab">
                <a href="#approvals" data-toggle="tab" aria-expanded="true" style="z-index:10;"><i
                        class="fa fa-check-square-o  text-purple"></i>
                    Approval & Remarks</a>
            </li>
            <li class="view_commenttab">
                <a href="#pr_comment" data-toggle="tab" aria-expanded="true"><i class="fa fa-comment text-purple"></i>
                    IT Remark</a>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div id="purchase_request" class="tab-pane active">
                <div class="panel invoice-panel">
                    <div class="panel-body p20" id="invoice-item">
                        <div class="row mb30">
                            <div class="col-md-10">
                                <div class="pull-left">
                                    <h5 class="mn"> <?php echo "Complaint Date "; ?>:
                                        <?php echo isset($crdetail['created_at']) ? 
                                date("d F Y", strtotime($crdetail['created_at'])) : ""; ?></b>
                                        <br>
                                        <br>
                                </div>
                            </div>

                            <!--  -->
                            <div class="row" id="invoice-info">
                                <div class="col-md-6">
                                    <div class="panel panel-alt">
                                        <div class="panel-heading" style="background-color:aliceblue;">
                                            <span class="panel-title"> <i class="fa fa-info"></i> Requester Details:
                                            </span>
                                            <div class="panel-btns pull-right ml10"> </div>
                                        </div>
                                        <div class="panel-body">
                                            <ul class="list-unstyled">
                                                <li> <b><?php echo trans('label.lbl_requester_name'); ?> </b> :
                                                    <?php echo $requester_detail['fname'] . ' ' . $requester_detail['lname']?>
                                                </li>
                                                <li> <b><?php echo "Priority "; ?> </b> :
                                                    <?php echo $crdetail['priority']?></li>
                                                <li> <b><?php echo "Complaint Reason "; ?> </b> :
                                                    <?php echo $crdetail['problemdetail']?></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="panel panel-alt">
                                        <div class="panel-heading" style="background-color:aliceblue;">
                                            <span class="panel-title"> <i class="fa fa-info"></i> User Details: </span>
                                            <div class="panel-btns pull-right ml10"> </div>
                                        </div>
                                        <div class="panel-body">
                                            <ul class="list-unstyled">
                                                <li> <b><?php echo "User Name"; ?></b> :
                                                    <?php echo $user_detail['firstname'] . ' ' . $user_detail['lastname']?></b>
                                                </li>
                                                <li> <b><?php echo "Email"; ?></b> :
                                                    <?php echo $user_detail['email']?></b> </li>
                                                <li> <b><?php echo "Hod Name"; ?> </b> :
                                                    <?php echo $hod_detail['firstname'] . ' ' . $hod_detail['lastname']?>
                                                </li>
                                                <li> <b><?php echo "Hod Email"; ?> </b> :
                                                    <?php echo $hod_detail['email']?></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!--  -->
                            </div>
                            <!--  -->
                            <div class="row" id="invoice-table">
                                <div class="col-md-12">
                                    <table class="table table-striped table-condensed">
                                        <thead>
                                            <tr id="labelRow" style="height:30px;background-color:aliceblue;">
                                                <th width="10%" class="text-center">Sr</th>
                                                <th width="30%" class="text-center">Item Name</th>
                                                <th width="15%" class="text-center">Asset Tag</th>
                                                <th width="15%" class="text-center">Asset Sku</th>
                                                <th width="15%" class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="labelRow" style="height:30px;background-color:aliceblue;">
                                                <th width="10%" class="text-center">1</th>
                                                <th width="30%" class="text-center">
                                                    <?php echo $asset_detail['display_name']?></th>
                                                <th width="15%" class="text-center">
                                                    <?php echo $asset_detail['asset_tag']?></th>
                                                <th width="15%" class="text-center">
                                                    <?php echo $asset_detail['asset_sku']?></th>
                                                <th width="15%" class="text-center">
                                                    <?php echo $asset_detail['asset_status']?></th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!--  -->
                        </div>
                    </div>
                </div>
            </div>

            <div id="approvals" class="tab-pane">
                <div class="panel invoice-panel">
                    <div class="panel-body p20" id="invoice-item">
                        <div class="row" id="invoice-table">
                            <div class="col-md-12">
                                <table class="table mbn tc-med-1 tc-bold-last tc-fs13-last">
                                    <thead style="height:30px;background-color:aliceblue;">
                                        <th class="textaligncenter"><?php echo "Approval"; ?></th>
                                        <th class="textaligncenter"><?php echo trans('label.lbl_status'); ?></th>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if($crdetail['store_status'] == "PENDING" && $crdetail['status'] == "HOD" )
                                        {
                                            ?>
                                        <tr>
                                            <td><center><i class="fa fa-circle text-warning fs8 pr15"></i>
                                                <span
                                                    style="color: black"><?php echo $hod_detail['firstname'] . ' ' . $hod_detail['lastname']?></span><center>
                                            </td>
                                            <?php
                                            if($crdetail['hod_id'] == $currentUser['user_id'])
                                            {
                                                ?>
                                                <td><center>
                                                <div class="col-xs-6 pull-right">
                                                        <div class="btn-group reject">
                                                            <button id="rejected_<?php if (isset($currentUser['user_id'])
                                                             && isset($currentUser['user_id'])) {
                                                echo $currentUser['user_id']. "_confirmed";
                                             }
                                             ?>" type="button" class="btn btn-default"><i
                                                                    class="glyphicons glyphicons-remove"></i>
                                                                <?php echo "Reject"; ?>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6 pull-left">
                                                        <div class="btn-group approve">
                                                            <button id="approved_<?php if (isset($currentUser['user_id']) 
                                                            && isset($currentUser['user_id'])) {
                                             echo $currentUser['user_id'] . "_confirmed";
                                          }
                                          ?>" type="button" class="btn btn-default"><i
                                                                    class="glyphicons glyphicons-check"></i>
                                                                <?php echo "Approve"; ?>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    </center>
                                                    </td>
                                                <?php

                                            }else{
                                                ?>
                                                <td>
                                                    <center><strong> Pending </strong></center>
                                                </td>
                                                <?php
                                            }
                                            ?>
                                        </tr>

                                        <?php
                                        }else if($crdetail['status'] == "IT")
                                        {
                                            ?>
                                            <tr>
                                                <td><i class="fa fa-circle text-warning fs8 pr15"></i>
                                                    <span
                                                        style="color: black"><?php echo $hod_detail['firstname'] . ' ' . $hod_detail['lastname']?></span>
                                                </td>
                                                <td>
                                                <center><strong> Approved </strong></center>  
                                                </td>
                                            </tr>
                                            <?php

                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="pr_comment" class="tab-pane">

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
function cal_total() {
    for (var i = 1; i <= 3; i++) {
        var cm_tot = 0.00;
        for (var j = 1; j <= 3; j++) {
            var row_amount = $('#amount_' + j + '_v' + i).val();
            if (row_amount == "") {
                row_amount = 0.00;
            }
            cm_tot = parseFloat(cm_tot) + parseFloat(row_amount);
        }
        var total_amount = parseFloat(cm_tot).toFixed(2);
        $('#total_' + i).val(total_amount);
    }
}

function cal(id) {
    var index = id;
    var rate = $('#' + index).val();
    var exp_arr = index.split('_');
    var qty = $('#qty_' + exp_arr[1]).val();
    var amount = parseFloat(rate).toFixed(2) * parseFloat(qty).toFixed(2);
    var amountX = parseFloat(amount).toFixed(2);
    $('#amount_' + exp_arr[1] + '_' + exp_arr[2]).val(amountX);
    //cal_total();
}


jQuery(document).ready(function() {

    $('.vendor_select').on('change', function(event) {
        var prevValue = $(this).data('previous');
        $('.vendor_select').not(this).find('option[value="' + prevValue + '"]').show();
        var value = $(this).val();
        $(this).data('previous', value);
        $('.vendor_select').not(this).find('option[value="' + value + '"]').hide();
        vendorlistoption();

    });

    function vendorlistoption() {
        var vendorhtml = '';

        for (let i = 1; i < 6; i++) {
            var pr_vendor_id_text = $('#pr_vendor_id_' + i).find(":selected").text();
            var pr_vendor_id_value = $('#pr_vendor_id_' + i).find(":selected").val();

            if (pr_vendor_id_value) {
                vendorhtml += '<option value=' + pr_vendor_id_value + '>' + pr_vendor_id_text + '</option>';
            }
        }
        $('#pr_vendor_id').find('option').remove().end().append(vendorhtml);
    }
});
</script>
<style type="text/css">
.it_sz {
    width: 65px !important;
    text-align: right !important;
    font-variant-numeric: tabular-nums;
    height: 25px;
}

.text_size {
    width: 100% !important;
    height: 25px;
}

.gley {
    background-color: #eae5e5;
    color: black;
    cursor: not-allowed;
    font-variant-numeric: tabular-nums;
    height: 25px;
    border: 0px;
    /*font-weight: 600;*/
}


.container {
    display: block;
    position: relative;
    padding-left: 35px;
    margin-bottom: 12px;
    cursor: pointer;
    font-size: 22px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

/* Hide the browser's default checkbox */
.container input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

/* Create a custom checkbox */
.checkmark {
    /* position: absolute;
  top: 0;
  left: 0;*/
    height: 20px;
    width: 20px;
    background-color: #eee;
}

/* On mouse-over, add a grey background color */
.container:hover input~.checkmark {
    background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.container input:checked~.checkmark {
    background-color: #2196F3;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

/* Show the checkmark when checked */
.container input:checked~.checkmark:after {
    display: block;
}

/* Style the checkmark/indicator */
.container .checkmark:after {
    left: 9px;
    top: 5px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
}

.tab-block .tab-content {

    padding: 15px 0px !important;

}
</style>