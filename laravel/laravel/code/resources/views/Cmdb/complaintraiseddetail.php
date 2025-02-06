<div class="panel-heading br-l br-r br-t" style="background-color:aliceblue;">
    <span class="panel-title">
        <?php echo "Asset Complaint"; ?> -
        <?php echo isset($crdetail['complaint_raised_no']) ? $crdetail['complaint_raised_no'] : "" ?></span>
    <div class="panel-header-menu pull-right mr10">
    </div>
</div>
<input type="hidden" id="crdatabase_id" name="crdatabase_id" value="<?php echo $crdetail['cr_id'];?>">
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
            <?php
            if($crdetail['status'] == "IT")
            {
                if($crdetail['hod_status'] == "approved"){
            ?>
            <li class="view_commenttab">
                <a href="#pr_comment" data-toggle="tab" aria-expanded="true"><i class="fa fa-comment text-purple"></i>
                    IT Remark
                </a>
            </li>
            <?php
                }
            }
            ?>
            <!-- Store -->
            <?php
            if($crdetail['it_status'] == "APPROVE")
            {
            ?>
            <li class="upload_quotationtab">
                <a href="#upload_quotation" data-toggle="tab" aria-expanded="true"><i class="fa fa-comment text-purple"></i>
                    Store Remark
                </a>
            </li>
            <?php
            }
            ?>
            <!-- Store -->
        </ul>
        <div class="tab-content">
            <!--  -->
            <div id="upload_quotation" class="tab-pane">
                <?php
                if($currentUser["department_name"] == "Store")
                {
                ?>
                <div class="panel invoice-panel">
                    <div id="comment_details" class="col-md-12 pt10 pln prn">
                        <!--  -->
                        <input type="hidden" value="<?php echo $asset_detail['display_name']?>" id="asset_display_name">
                        <input type="hidden" value="<?php echo $asset_detail['asset_sku']?>" id="asset_sku">
                        <input type="hidden" value="<?php echo $crdetail['complaint_raised_no']?>" id="complaint_raised_no">
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
                        <br>
                        <!--  -->
                        <!--  -->
                        <div class="panel">                           
                            <div class="panel-heading" style="background-color:aliceblue;">
                                <span class="panel-icon">
                                    <i class="fa fa-upload"></i>
                                </span>
                                <span class="panel-title">IT Remark</span>
                                <div class="widget-menu pull-right">
                                        <!--  -->
                                        <?php
                                        if($crdetail['it_status'] == "APPROVE" && $crdetail['itstatus'] == "Repairable" )
                                        {?>
                                        <div class="btn-group">
                                            <button id="timeline-toggle" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                                <span class="glyphicons glyphicons-show_lines fs16"></span>
                                            </button>
                                            <ul class="dropdown-menu pull-right" role="menu">                                                
                                                <li id="repair_req_add" title="Add Complaint Raised">
                                                    <a><span title="Add Repair Request">
                                                    Add Repair Request</span></a>                
                                                </li> 
                                            
                                            </ul>
                                        </div>
                                        <?php
                                        }
                                        ?>
                                        <!--  -->
                                </div>
                            </div>
                            <!--  -->
                            <div class="panel-body pn">
                                <div class="row">    
                                    <div class="col-sm-6">
                                        <div class="form-group required " style="margin: 10px;">
                                        <label for="inputStandard" class="col-md-12 control-label textalignleft"> File </label>                                                    
                                        <div class="col-md-12">                                            
                                            <span class = "download_file text-primary" download_id="<?php echo $crdetail['cr_id']; ?>" style="cursor:pointer;" title="<?php echo trans("label.lbl_viewdownload"); ?>" download_path = "<?php echo $crdetail['itfile']; ?>"><?php echo 'Download Attached File'; ?>&nbsp;<i class="fa fa-cloud-download" style="font-size: large;color:green"></i></span>                                                
                                            <br>
                                        </div>
                                        </div>
                                    </div>                                                    
                                    <div class="col-sm-3">
                                        <div class="form-group required " style="margin: 10px;">
                                        <label for="inputStandard" class="col-md-12 control-label textalignleft"> Status Change </label>                                                    
                                        <div class="col-md-12">
                                            <input type="text" class="col-md-12 form-control" value="<?php echo $crdetail['itstatus']?>" readonly>
                                            <br>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group required " style="margin: 10px;">
                                            <label for="inputStandard" class="col-md-12 control-label textalignleft"> IT - <?php echo trans('label.lbl_comment'); ?> </label>                                                    
                                            <div class="col-md-12">
                                                <textarea class="col-md-12" maxlength="500" readonly><?php echo $crdetail['it_remark'] == "" ? "" : $crdetail['it_remark'];?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                
                            </div>
                            <!--  -->
                        </div>

                        <!-- Store -->
                        <div class="panel invoice-panel">
                            <div id="comment_details" class="col-md-12 pt10 pln prn">
                                <div class="panel">                           
                                    <div class="panel-heading" style="background-color:aliceblue;">
                                        <span class="panel-icon">
                                            <i class="fa fa-upload"></i>
                                        </span>
                                        <span class="panel-title">Store Remark</span>
                                        <div class="widget-menu pull-right">
                                            
                                        </div>
                                    </div>
                                    <div class="panel-body pn">
                                        <div class="col-sm-12 pt10 pl30">
                                            <div class="pl10 mb10 comment_msg">                                            
                                                <form class="form-horizontal" id="cr_StoreForm" method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" id="crStoreform_id" name="crStoreform_id" value="<?php echo $crdetail['cr_id'];?>">
                                                    <div class="row">    
                                                        <div class="col-sm-9">
                                                            <div class="form-group required " style="margin: 10px;">
                                                            <label for="inputStandard" class="col-md-12 control-label textalignleft"> File </label> &nbsp;<span style="color: red;">(Only Accept: jpeg,png,jpg,csv,txt,xlx,xls,pdf)</span>                                                    
                                                            <div class="col-md-12">
                                                                <?php
                                                                if($crdetail['store_status'] == "PENDING" )
                                                                {?>
                                                                <input type="file" class="col-md-12 form-control" id="storeremarkfile" name="storeremarkfile" accept="image/png, image/jpeg, application/pdf, application/vnd.ms-excel" required/>
                                                                <?php
                                                                }else{
                                                                    ?>
                                                                    <span class = "download_file text-primary" download_id="<?php echo $crdetail['cr_id']; ?>" style="cursor:pointer;" title="<?php echo trans("label.lbl_viewdownload"); ?>" download_path = "<?php echo $crdetail['storefile']; ?>"><?php echo 'Download Attached File'; ?>&nbsp;<i class="fa fa-cloud-download" style="font-size: large;color:green"></i></span>
                                                                    <?
                                                                }
                                                                ?>
                                                                <br>
                                                            </div>
                                                            </div>
                                                        </div>                                                                                                            
                                                    </div>
                                                    <div class="row">
                                                    <div class="col-sm-9">
                                                        <div class="form-group required " style="margin: 10px;">
                                                        <label for="inputStandard" class="col-md-12 control-label textalignleft"> <?php echo trans('label.lbl_comment'); ?> </label>
                                                        <input type="hidden" id="store_cr_id" name="store_cr_id" value="<?php echo $crdetail['cr_id'];?>">
                                                        <input type="hidden" id="store_user_id" name="store_user_id" value="<?php echo $user_detail['user_id'];?>">
                                                        <div class="col-md-12">
                                                            <textarea class="col-md-12" id="store_commentboxs" name="store_commentboxs" maxlength="500" required <?php if($crdetail['store_status'] != "PENDING" ){ echo "readonly";}?> ><?php echo $crdetail['store_remark'] == "" ? "" : $crdetail['store_remark'];?></textarea>
                                                            <br>
                                                            <code style="float: inline-end;">(Max 500 Characters)</code>
                                                        </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">                                                        
                                                        </div> <input type="hidden" name="_token" value="
                                                                            <?php echo csrf_token() ?>">
                                                        <?php
                                                        if($crdetail['store_status'] == "PENDING" )
                                                        {?>
                                                            
                                                            <button style="margin-top:30px;" type="submit" id="cr_storeremark_submit" class="btn btn-success submit_btn">Submit Remark</button>
                                                        
                                                        <?php
                                                        }
                                                        ?>
                                                        
                                                    </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Store -->
                    </div>
                </div>
                <?php
                }
                else{
                    ?>
                    <div class="panel invoice-panel">
                        <div id="comment_details" class="col-md-12 pt10 pln prn">
                            <div class="panel">                           
                                <div class="panel-heading" style="background-color:aliceblue;">                                    
                                    <center><span class="panel-title">Only Accessible for Store Department</span></center>
                                </div>                                
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>  
            </div>
            <!--  -->
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
            <!-- Model -->
            <div id="myModal_approve_reject" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <form class="form-horizontal" id="formComment">
                        <!-- Modal content-->
                        <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;
                            </button>
                            <h4 class="modal-title">
                                <span id="modal-title_approve_reject">
                                <?php echo trans('label.lbl_rejectapprove'); ?>
                                </span> <?php echo "this complaint"; ?> : 
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="hidden alert-dismissable" id="msg_modal_approve_reject"></div>
                                </div>
                            </div>
                            <input type="hidden" id="cr_id" name="cr_id" value="<?php echo $crdetail['cr_id'];?>">
                            <input type="hidden" id="user_id" name="user_id" value="<?php echo $user_detail['user_id'];?>">
                            <input type="hidden" id="requester_id" name="requester_id" value="<?php echo $crdetail['requester_id'];?>">
                            <input type="hidden" id="hod_id" name="hod_id" value="<?php echo $hod_detail['user_id'];?>">
                            <input type="hidden" id="approval_status" name="approval_status">
                            <div class="form-group required ">
                                    <label for="inputStandard" class="col-md-12 control-label textalignleft"><?php echo trans('label.lbl_comment'); ?>
                                    </label>
                                    <div class="col-md-12">
                                        <textarea required class="col-md-12" id="commentaprj" name="comment"></textarea>
                                    </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" id="submitComment" class="btn btn-success"><?php echo trans('label.btn_submit'); ?>
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                <?php echo trans('label.btn_close'); ?>
                            </button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
            <!-- Model -->
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
                                            <td style="align:left;"><i class="fa fa-circle text-warning fs8 pr15"></i>
                                                <span
                                                    style="color: black;"><?php echo $hod_detail['firstname'] . ' ' . $hod_detail['lastname']?></span>
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
                                                echo $currentUser['user_id'] . "_" . $crdetail['complaint_raised_no'] . "_confirmed";
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
                                             echo $currentUser['user_id'] . "_" . $crdetail['complaint_raised_no'] . "_confirmed";
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
                                                <center><strong> <?php if($crdetail['hod_status'] == "approved"){ echo "Approved";}else{ echo "Rejected"; } ?> </strong></center>  
                                                </td>
                                            </tr>
                                            <?php

                                        }
                                        ?>

                                        <tr>
                                        <td>
                                            <i class="fa fa-circle text-warning fs8 pr15"></i>
                                                <span
                                                    style="color: black">Internal-IT</span>
                                            </td>
                                            <td>
                                            <center><strong> <?php if($crdetail['it_status'] == "APPROVE"){ echo "Approved";}else{ echo "Pending"; } ?> </strong></center>  
                                            </td>
                                        </tr>
                                        <tr>  
                                        <td>                                          
                                            <i class="fa fa-circle text-warning fs8 pr15"></i>
                                                <span
                                                    style="color: black">Store Department</span>
                                            </td>
                                            <td>
                                            <center><strong> <?php if($crdetail['store_status'] == "APPROVE"){ echo "Approved";}else{ echo "Pending"; } ?> </strong></center>  
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="pr_comment" class="tab-pane">
                <?php
                if($currentUser["department_name"] == "Internal-IT")
                {
                    ?>
                    <div class="panel invoice-panel">
                        <div id="comment_details" class="col-md-12 pt10 pln prn">
                            <div class="panel">                           
                                <div class="panel-heading" style="background-color:aliceblue;">
                                    <span class="panel-icon">
                                        <i class="fa fa-upload"></i>
                                    </span>
                                    <span class="panel-title">IT Remark</span>
                                    <div class="widget-menu pull-right">
                                        
                                    </div>
                                </div>
                                <div class="panel-body pn">
                                    <div class="col-sm-12 pt10 pl30">
                                        <div class="pl10 mb10 comment_msg">                                            
                                            <form class="form-horizontal" id="cr_ItForm" method="POST" enctype="multipart/form-data">
                                                <input type="hidden" id="crform_id" name="crform_id" value="<?php echo $crdetail['cr_id'];?>">
                                                <div class="row">    
                                                    <div class="col-sm-6">
                                                        <div class="form-group required " style="margin: 10px;">
                                                        <label for="inputStandard" class="col-md-12 control-label textalignleft"> File </label>&nbsp;<span style="color: red;">(Only Accept: jpeg,png,jpg,csv,txt,xlx,xls,pdf)</span>                                                 
                                                        <div class="col-md-12">
                                                            <?php
                                                             if($crdetail['it_status'] == "PENDING" )
                                                             {?>
                                                            <input type="file" class="col-md-12 form-control" id="itremarkfile" name="itremarkfile" accept="image/png, image/jpeg, application/pdf, application/vnd.ms-excel" required/>
                                                            <?php
                                                             }else{
                                                                ?>
                                                                <span class = "download_file text-primary" download_id="<?php echo $crdetail['cr_id']; ?>" style="cursor:pointer;" title="<?php echo trans("label.lbl_viewdownload"); ?>" download_path = "<?php echo $crdetail['itfile']; ?>"><?php echo 'Download Attached File'; ?>&nbsp;<i class="fa fa-cloud-download" style="font-size: large;color:green"></i></span>
                                                                <?
                                                             }
                                                            ?>
                                                            <br>
                                                        </div>
                                                        </div>
                                                    </div>                                                    
                                                    <div class="col-sm-3">
                                                        <div class="form-group required " style="margin: 10px;">
                                                        <label for="inputStandard" class="col-md-12 control-label textalignleft"> Status Change </label>                                                    
                                                        <div class="col-md-12">
                                                            <select class="col-md-12 form-control" <?php echo $crdetail['itstatus'] != "" ? "disabled" : ""?> name="ItsRepairable" id="ItsRepairable">
                                                                <option value="">Select</option>
                                                                <option value="Repairable" <?php echo $crdetail['itstatus'] == "Repairable" ? "selected" : ""?>>Repairable</option>
                                                                <option value="Partial E-waste" <?php echo $crdetail['itstatus'] == "Partial E-waste" ? "selected" : ""?>>Partial E-waste</option>
                                                                <option value="E-waste" <?php echo $crdetail['itstatus'] == "E-waste" ? "selected" : ""?>>E-waste</option>
                                                            </select>
                                                            <br>
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                <div class="col-sm-9">
                                                    <div class="form-group required " style="margin: 10px;">
                                                    <label for="inputStandard" class="col-md-12 control-label textalignleft"> <?php echo trans('label.lbl_comment'); ?> </label>
                                                    <input type="hidden" id="cr_id" name="cr_id" value="<?php echo $crdetail['cr_id'];?>">
                                                    <input type="hidden" id="user_id" name="user_id" value="<?php echo $user_detail['user_id'];?>">
                                                    <div class="col-md-12">
                                                        <textarea class="col-md-12" id="commentboxs" name="commentboxs" maxlength="500" required <?php if($crdetail['it_status'] != "PENDING" ){ echo "readonly";}?> ><?php echo $crdetail['it_remark'] == "" ? "" : $crdetail['it_remark'];?></textarea>
                                                        <br>
                                                        <code style="float: inline-end;">(Max 500 Characters)</code>
                                                    </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="form-group">
                                                    <input type="hidden" id="cr_id" name="cr_id" value="">
                                                    </div> <input type="hidden" name="_token" value="
                                                                        <?php echo csrf_token() ?>">
                                                    <?php
                                                    if($crdetail['it_status'] == "PENDING" )
                                                    {?>
                                                        
                                                        <button style="margin-top:30px;" type="submit" id="cr_itremark_submit" class="btn btn-success submit_btn">Submit Remark</button>
                                                    
                                                    <?php
                                                    }
                                                    ?>
                                                    
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
                    <?php                    
                }else{
                    ?>
                    <div class="panel invoice-panel">
                        <div id="comment_details" class="col-md-12 pt10 pln prn">
                            <div class="panel">                           
                                <div class="panel-heading" style="background-color:aliceblue;">                                    
                                    <center><span class="panel-title">Only Accessible for Internal-IT Department</span></center>
                                </div>                                
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>                
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