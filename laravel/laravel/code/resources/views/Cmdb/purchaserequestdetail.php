<?php
$disabled = '';
$item_qt_arr = json_decode($quotation_comparison_details['content'], true);
$item_count = count($item_qt_arr);

?>
<div class="panel-heading br-l br-r br-t"  style="background-color:aliceblue;">
   <span class="panel-title">
      <?php echo trans('label.lbl_purchaserequest'); ?> - <?php echo isset($pr_first_detail['pr_no']) ? $pr_first_detail['pr_no'] : "" ?></span>
      <div class="panel-header-menu pull-right mr10">
         <?php
if ($pr_first_detail['status'] == "rejected") {
    $status_color = "danger";
    echo '<button type="button" class="btn btn-danger"><strong>' . ucfirst($pr_first_detail['status']) . '</strong></button>';
} else if ($pr_first_detail['status'] == "approved") {
    $status_color = "success";
    echo '<button type="button"class="btn btn-success"><strong>' . ucfirst($pr_first_detail['status']) . '</strong></button>';
} else if ($pr_first_detail['status'] == "closed") {
    $status_color = "default";
    echo '<button type="button"class="btn btn-default"><strong>' . ucfirst($pr_first_detail['status']) . '</strong></button>';
} else if ($pr_first_detail['status'] == "cancelled") {
    $status_color = "danger";
    echo '<button type="button"class="btn btn-danger"><strong>' . ucfirst($pr_first_detail['status']) . '</strong></button>';
} else if ($pr_first_detail['status'] == "pending approval") {
    $status_color = "warning";
    echo '<button type="button"class="btn btn-warning"><strong>' . ucfirst($pr_first_detail['status']) . '</strong></button>';
}
$loggedinUser = showuserid();
?>
     </div>
  </div>
  <div class="panel-body bg-light">
   <?php //echo "<pre>"; //print_r($pr_first_detail);  //echo "</pre>"; ?>
   <div id="podetails_page" class="col-md-12 prn-md animated fadeIn">
      <div class="panel">
         <div class="bg-light pv8 pr10   br-light">
            <div class="row">
               <div class="hidden-xs hidden-sm col-md-12 va-m">
                  <?php //if ($pr_first_detail['status'] != "rejected" && $pr_first_detail['status'] != "cancelled" && $pr_first_detail['status'] != "closed" && $pr_first_detail['status'] != "approved") {

                  if ($pr_first_detail['status'] != "rejected" && $pr_first_detail['status'] != "cancelled" && $pr_first_detail['status'] != "closed") {
                    ?>

                     <?php if (canuser('update', 'purchaserequest')) {?>
                        <div class="btn-group">
                           <?php if (isset($isEditOpen)) {if ($isEditOpen == 'OpenEdit'){

            //convert json string to array using json_decode
            $already_po_cret = json_decode($pr_first_detail['already_po'], true);
            if (empty($already_po_cret)) {
                ?>
                           <button id="predit" data-id="<?php echo isset($pr_first_detail['pr_id']) ? $pr_first_detail['pr_id'] : ""; ?>"type="button" class="btn btn-default light"><i class="fa fa-pencil"></i> <?php echo trans('label.btn_editpr'); ?>
                           </button>
                        <?php }}}?>
                     </div>
                  <?php }?>
               <?php }
if (!empty($pr_first_detail['already_po'])) {
    //  echo "<pre>"; //print_r($pr_first_detail); 
    $already_po_cret_vendor = json_decode($pr_first_detail['already_po'], true);
    //print_r($already_po_cret_vendor);
    $existing_vendor = array_map(function ($ex_vendor) {

        return $ex_vendor['pr_vendor'];

    }, $already_po_cret_vendor);

    $approved_prs = array_filter($already_po_cret_vendor, function ($ex_vendor) {
        return ($ex_vendor['status'] == 'approved');
    });
    $po_status_value = array_column($already_po_cret_vendor, 'status');

    if ($pr_first_detail['status'] == "approved" && count($existing_vendor) > 0) {
        ?>
                 <?php
/*
        if(!empty($item_qt_arr[0]['approval']) && $item_qt_arr[0]['approval'] == 'approved'){
        if (canuser('create', 'purchaseorder')) {?>
        <!-- <div class="btn-group">
        <button id="pocreate" data-id="<?php echo isset($pr_first_detail['pr_id']) ? $pr_first_detail['pr_id'] : ""; ?>"  type="button" class="btn btn-default light"><i class="glyphicons glyphicons-folder_plus"></i> <?php echo trans('label.btn_createpo'); ?>
        </button>
        </div> -->
        <?php }}*/?>

               <!-- <div class="btn-group">
                  <button id="poview"  data-id="<?php echo $pr_first_detail['already_po']; ?>"type="button" class="btn btn-default light"><i class="glyphicons glyphicons-eye_open"></i> <?php echo trans('label.btn_viewpo'); ?>
                  </button>
               </div> -->
               <div class="btn-group">
                  <button id="poview"  data-id="<?php echo $pr_first_detail['pr_id']; ?>" data-pr="0" type="button" class="btn btn-default light"><i class="glyphicons glyphicons-eye_open"></i> View POs <?php //echo trans('label.btn_viewpo'); ?>
               </button>
            </div>
            <?php
}
}
$item_arr = [];
$approve_items = 0;
if (!empty($assetdetails)) {
    foreach ($assetdetails as $i => $asset) {
        $asset_details = json_decode($asset['asset_details'], true);
        $vendor_approve = json_decode($asset['vendor_approve'], true);
        //$vendor_approve = $asset['approval'];
        if (!empty($vendor_approve) && empty($vendor_approve['converted_as_po'])) {
            $approve_items += 1;
        }
        $item_arr[$vendor_approve['vendor_id']][] = $asset_details['item_product_name'];
    }
}
$item_qt_arr1 = array_filter($item_qt_arr, function ($arr) {
    return $arr['approval'] == 'approved';
});

/*echo "<pre>";
print_r($item_qt_arr1);
echo "</pre>";*/

if (!empty($item_qt_arr1)) {
    // if(!empty($item_qt_arr[0]['approval']) && $item_qt_arr[0]['approval'] == 'approved'){

    // if ($pr_first_detail['status'] == "approved"  && count($approved_prs) < count($item_arr)) {
    if ($pr_first_detail['status'] == "approved" && $approve_items > 0) {

        if (canuser('create', 'purchaseorder')) {?>
               <div class="btn-group">
                  <button id="pocreatevendorewise" data-id="<?php echo isset($pr_first_detail['pr_id']) ? $pr_first_detail['pr_id'] : ""; ?>"type="button" class="btn btn-default light"><i class="glyphicons glyphicons-folder_plus"></i> Create Vendor PO(Draft)</button>
               </div>
               <div class="btn-group">
                  <button id="pocreatevendorewise1" data-id="<?php echo isset($pr_first_detail['pr_id']) ? $pr_first_detail['pr_id'] : ""; ?>" type="button" class="btn btn-default light"><i class="glyphicons glyphicons-folder_plus"></i> Clubs PR in one PO(Draft)</button>
               </div>
            <?php }}?>
            <?php
}

if ($pr_first_detail['status'] == "pending approval") {
    ?>
           <div class="btn-group">
            <button type="button" id="notifyagain_<?php echo $loggedinUser . "_" . $pr_first_detail['pr_id']; ?>" class="actionsPr btn btn-success light"><i class="fa fa-check-square-o"></i> <?php echo trans('label.btn_submitapprovals'); ?>
         </button>
      </div>
   <?php }?>
   <?php if (canuser('advance', 'view_attachment_pr') || canuser('advance', 'cancel_pr') || canuser('delete', 'purchaserequest') || canuser('advance', 'close_pr') || canuser('advance', 'notify_owner_email') || canuser('advance', 'notify_vendor_email') || canuser('advance', 'convert_to_pr')) {
    ?>
      <div class="btn-group">
         <button type="button" class="btn btn-default light dropdown-toggle ph8" data-toggle="dropdown">
            <span class="fa fa-tags"></span>
            <span class="caret ml5"></span>
         </button>
         <ul class="dropdown-menu pull-right" role="menu">
                     <!--<li>
                        <a href="#"><i class="fa fa-print"></i> Print Preview</a>
                     </li>-->
                     <?php if (canuser('advance', 'view_attachment_pr')) {?>
                        <li>
                           <a href="#" id="attachDoc"><i class="fa fa-files-o"></i>  <?php echo trans('label.lbl_attachdocuments'); ?> </a>
                        </li>
                     <?php }?>
                     <?php
$approved_status = json_decode($pr_first_detail['approved_status'], true);
    //echo $pr_first_detail['approved_status'];
    if (!empty($pr_first_detail['approved_status']) && $pr_first_detail['status'] == 'approved') {

        if (is_array($approved_status) && !array_key_exists('convert_to_pr', $approved_status) && array_key_exists('confirmed', $approved_status)) {

            if (canuser('advance', 'convert_to_pr')) {?>

                          
                              
                              <li>
                                 <a class="convert_to_pr ccursor" class="actionsPr" id="convert_<?php echo $loggedinUser . "_" . $pr_first_detail['pr_id']; ?>"><i class="fa  fa-file-text-o"></i> <?php echo trans('label.lbl_convert_to_pr'); ?> </a>
                              </li>
                          
                           <?php }}}?>
                           <li>
                              <?php if ($pr_first_detail['status'] == "pending approval" || $pr_first_detail['status'] == "approved") {
        if (canuser('advance', 'cancel_pr')) {
            ?>
                                   <li>
                                    <a class="actionsPr ccursor" id="cancel_<?php echo $loggedinUser . "_" . $pr_first_detail['pr_id']; ?>"><i class="fa fa-thumbs-down "></i> <?php echo trans('label.lbl_cancelpr'); ?> </a>
                                 </li>
                              <?php }}
    if ($pr_first_detail['status'] == "pending approval" || $pr_first_detail['status'] == "approved") {
        if (canuser('delete', 'cancel_pr')) {
            ?>
                                  <li>
                                    <a class="actionsPr ccursor" class="actionsPr" id="delete_<?php echo $loggedinUser . "_" . $pr_first_detail['pr_id']; ?>"><i class="fa fa-trash"></i> <?php echo trans('label.lbl_deletepr'); ?> </a>
                                 </li>
                              <?php }}
    if ($pr_first_detail['status'] == "pending approval" || $pr_first_detail['status'] == "approved") {
        if (canuser('advance', 'close_pr')) {
            ?>
                                  <li>
                                    <a class="actionsPr ccursor" class="actionsPr" id="close_<?php echo $loggedinUser . "_" . $pr_first_detail['pr_id']; ?>"><i class="fa fa-close"></i> <?php echo trans('label.lbl_closepr'); ?> </a>
                                 </li>
                                 <?php
}}?>
                              <!--<li class="divider"></li>-->
                              <?php if (canuser('advance', 'notify_owner_email') || canuser('advance', 'notify_vendor_email')) {?>
                                 <li><strong><?php echo trans('label.lbl_notify'); ?></strong></li>
                                 <?php if (canuser('advance', 'notify_owner_email')) {?>
                                    <li>
                                       <a class="ccursor actionsPr" id="notifyowner_<?php echo $loggedinUser . "_" . $pr_first_detail['pr_id']; ?>"><i class="fa fa-share"></i> <?php echo trans('label.lbl_emailowner'); ?></a>
                                    </li>
                                 <?php }?>
                                 <?php if (canuser('advance', 'notify_vendor_email')) {?>
                                    <li>
                                       <a class="ccursor actionsPr" id="notifyvendor_<?php echo $loggedinUser . "_" . $pr_first_detail['pr_id']; ?>"><i class="fa fa-share"></i> <?php echo trans('label.lbl_emailvender'); ?></a>
                                    </li>
                                 <?php }}?>
                              </ul>

                              <div id="multiple_po" class="modal fade" role="dialog">
                                 <div class="modal-dialog">
                                    <form class="form-horizontal" id="create_multiple_po_form">
                                       <!-- Modal content-->
                                       <div class="modal-content">
                                          <div class="modal-header">
                                             <button type="button" class="close" data-dismiss="modal">&times;</button>
                                             <h4 class="modal-title"><span id="modal-title_actions"> Select vendor to create purchase order</span>  : <?php //echo isset($pr_first_detail['details']['pr_title']) ? $pr_first_detail['details']['pr_title'] : ""; ?></h4>
                                          </div>
                                          <div class="modal-body">

                                             <div class="vendors">
                                                <div class="row">
                                                   <div class="col-md-12">
                                                      <div class="hidden alert-dismissable" id="msg_modal"></div>
                                                   </div>
                                                </div>

                                                <input type="hidden" id="pr_po_id" value="<?php echo isset($pr_first_detail['pr_id']) ? $pr_first_detail['pr_id'] : ""; ?>" name="pr_po_id">
                                                <?php
if (!empty($vendors)) {
        $i = 0;
        foreach ($vendors as $key => $value) {
            $i++;

            if (!empty($item_arr[$value['vendor_id']]) && !in_array($value['vendor_id'], $existing_vendor)) {
                $items = implode(',', $item_arr[$value['vendor_id']]);
                ?>
                                            <!--  <div>
                                              <input  type="checkbox"  class="selectDeselectAll po_vendor_id" id="enableMailNotificationCheck-<?php echo $i; ?>" name="po_vendor_id" value="<?php echo $value['vendor_id'] ?>"> <label for="enableMailNotificationCheck-<?php echo $i; ?>"><?php echo $value['vendor_name'] ?></label>
                                           </div> -->
                                           <div class="checkbox-custom checkbox-info mb5 po_vendor_id_clk">
                                             <input  type="checkbox"  class="selectDeselectAll po_vendor_id" id="enableMailNotificationCheck-<?php echo $i; ?>" name="po_vendor_id" value="<?php echo $value['vendor_id'] ?>">
                                             <label for="enableMailNotificationCheck-<?php echo $i; ?>"><?php echo $value['vendor_name'] ?></label>
                                             <div>
                                                <?php echo $items; ?>
                                             </div>
                                          </div>

                                       <?php }}
    } else {
        echo "Vendor is not approved yet.";
    }
    ?>


                                    </div>
                                 </div>
                                 <div class="modal-footer">
                                    <button type="button" id="create_multiple_po" class="btn btn-success"><?php echo trans('label.btn_submit'); ?></button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo trans('label.btn_close'); ?></button>
                                 </div>
                              </div>
                           </form>
                        </div>
                     </div>
                     <div id="multiple_po_pr_clubs_modal" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                           <form class="form-horizontal" id="create_multiple_pr_po_form_submit">
                              <!-- Modal content-->
                              <div class="modal-content">
                                 <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title"><span id="modal-title_actions"> Select vendor to create purchase order</span>  : <?php //echo isset($pr_first_detail['details']['pr_title']) ? $pr_first_detail['details']['pr_title'] : ""; ?></h4>
                                 </div>
                                 <div class="modal-body">
                                    <div class="row">
                                       <div class="col-md-12">
                                          <div class="hidden alert-dismissable" id="msg_modal"></div>
                                       </div>
                                    </div>

                                    <?php

    if (!empty($vendors)) {
        $i = 1000;
        foreach ($vendors as $key => $value) {
            $i++;

            if (!empty($item_arr[$value['vendor_id']]) && !in_array($value['vendor_id'], $existing_vendor)) {
                $items = implode(',', $item_arr[$value['vendor_id']]);
                ?>

                                             <div class="checkbox-custom checkbox-info mb5 ">
                                                <input  type="checkbox"  class="selectDeselectAll po_vendor_id" id="enableMailNotificationCheck-<?php echo $i; ?>" name="multi_po_vendor_id" value="<?php echo $value['vendor_id'] ?>">
                                                <label for="enableMailNotificationCheck-<?php echo $i; ?>"><?php echo $value['vendor_name'] ?></label>
                                                <div>
                                                   <?php //echo $items;?>
                                                </div>
                                             </div>

                                          <?php }}
    } else {
        echo "Vendor is not approved yet.";
    }
    ?>
                                          <button type="button" id="create_multiple_pr_po" class="btn btn-success">Search</button>
                                          <hr>
                                          <div class="row">
                                             <div class="col-md-12" id="PR_numbers">

                                             </div>
                                          </div>

                                       </div>
                                       <div class="modal-footer">
                                          <button type="button" id="create_multiple_pr_po_submit" class="btn btn-success">Submit</button>
                                          <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo trans('label.btn_close'); ?></button>
                                       </div>
                                    </div>
                                 </form>
                              </div>
                           </div>

                           <!-- Modal -->
                        <div id="myModal_actions" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <form class="form-horizontal" id="prformActions" action="">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                       <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          <h4 class="modal-title"><span id="modal-title_actions"> <?php echo trans('label.lbl_canceldeleteclose'); ?> </span>  : <?php //echo isset($pr_first_detail['details']['pr_title']) ? $pr_first_detail['details']['pr_title'] : ""; ?></h4>
                                       </div>
                                       <div class="modal-body">
                                          <div class="row">
                                             <div class="col-md-12">
                                                <div class="hidden alert-dismissable" id="msg_modal"></div>
                                             </div>
                                          </div>
                                          <input type="hidden" id="pr_po_type" name="pr_po_type" value="pr">
                                          <input type="hidden" id="pr_po_id" name="pr_po_id">
                                          <input type="hidden" id="user_id" name="user_id">
                                          <input type="hidden" id="action" name="action">
                                          <input type="hidden" id="notify_to_id" name="notify_to_id">
                                          <div class="checkbox-custom checkbox-info mb5">
                                             <input  type="checkbox"  class="selectDeselectAll" id="enableMailNotificationCheck" name="mail_notification" value="y">
                                             <label for="enableMailNotificationCheck"><strong> <?php echo trans('label.lbl_sendmailnotification'); ?> </strong></label>
                                          </div>
                                 <!--<div class="form-group">
                                    <div class="col-md-12">
                                        <p>Are you Sure you want to <span id="modal-title_actions_2">Cancel / Delete / Close</span> this PR : <strong><?php //echo $pr_first_detail['details']['pr_title']; ?></strong> ? </p>
                                    </div>
                                 </div>-->
                                 <div class="form-group required enableMailNotification" style="display: none;">
                                    <label for="inputStandard" class="col-md-12 control-label textalignleft"><?php echo trans('label.lbl_to'); ?></label>
                                    <div class="col-md-12">
                                       <input class="col-md-12 form-control" name="mail_notification_to" id="mail_notification_to">
                                    </div>
                                 </div>
                                 <div class="form-group required enableMailNotification" style="display: none;">
                                    <label for="inputStandard" class="col-md-12 control-label textalignleft"><?php echo trans('label.lbl_subject'); ?></label>
                                    <div class="col-md-12">
                                       <input class="col-md-12 form-control" name="mail_notification_subject" id="mail_notification_subject">
                                    </div>
                                 </div>
                                 <div class="form-group required ">
                                    <label for="inputStandard" class="col-md-12 control-label textalignleft"><?php echo trans('label.lbl_description_comment'); ?></label>
                                    <div class="col-md-12">
                                       <textarea class="col-md-12" name="comment" maxlength="250"></textarea>
                                       <br><code style="float: inline-end;">(Max 250 Characters)</code>
                                    </div>
                                 </div>
                              </div>
                              <div class="modal-footer">
                                 <button type="button" id="submitAction" class="btn btn-success"><?php echo trans('label.btn_submit'); ?></button>
                                 <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo trans('label.btn_close'); ?></button>
                              </div>
                           </div>
                        </form>
                     </div>
                  </div>
               </div>
            <?php }?>
         </div>
      </div>
   </div>
   <!-- Begin: Content -->
   <?php if ($notupload = $errors->first('notupload')) {
    ?>
      <div class="alert alert-danger" role="alert">
         <?php echo $notupload; ?>
      </div>
      <?php
}
if ($upload = $errors->first('upload')) {
    ?>
     <div class="alert alert-success" role="alert">
      <?php echo $upload; ?>
   </div>
<?php }?>
<div class="panel-body pn br-n">
   <div class="tab-block mb25">
      <ul class="nav nav-tabs tabs-bg tabs-border">
       <!--  <p><b><?php //echo trans('label.lbl_purchaserequest'); ?> - </b></p> -->
       <li class="purchase_requesttab active">
         <a href="#purchase_request" data-toggle="tab" aria-expanded="false"><i class="fa fa-info-circle  text-purple"></i> <?php echo trans('label.lbl_purchaserequest'); ?></a>
      </li>
      <?php if (canuser('advance', 'approve_reject_pr')) {?>
         <li class="approve_reject_prtab">
            <a href="#approvals" data-toggle="tab" aria-expanded="true" style="z-index:10;"><i class="fa fa-check-square-o  text-purple"></i> <?php echo trans('label.lbl_approvals'); ?></a>
         </li>
      <?php }?>
      <?php
if (is_array($approved_status) && array_key_exists('convert_to_pr', $approved_status) && array_key_exists('confirmed', $approved_status)) {
    if (canuser('advance', 'assignprtouser')) { //assignprtouser?>
         <li class="assignprtousertab">
            <a href="#assign_pr_to_user" data-toggle="tab" aria-expanded="true"><i class="fa fa-users text-purple"></i> <?php echo trans('label.lbl_assign_pr_to_user'); ?></a>
         </a>
      </li>
   <?php }}?>
   <?php if ($pr_first_detail['status'] != "pending approval") {
    if (canuser('advance', 'uploadquotation')) { //upload_quotation?>
         <li class="upload_quotationtab">
            <a href="#upload_quotation" data-toggle="tab" aria-expanded="true"><i class="fa fa-upload text-purple"></i> <?php echo trans('label.lbl_upload_quotation'); ?></a>
         </a>
      </li>
   <?php }}?>
<?php if (canuser('advance', 'estimatecost')) {?>
   <?php if ($pr_first_detail['status'] == "approved" && count($existing_vendor) > 0) {} else {?>
   <li class="view_commenttab">
   <a href="#pr_estimate_cost" data-toggle="tab" aria-expanded="true"><i class="fa fa-inr text-purple" aria-hidden="true"></i> Estimate Cost</a>
    </a>
  </li>

<?php }}?>
<li class="view_commenttab">
   <a href="#pr_comment" data-toggle="tab" aria-expanded="true"><i class="fa fa-comment text-purple"></i> <?php echo 'Comments'; ?>
</a>
</a>
</li>
   <?php if (canuser('advance', 'view_history')) {?>
      <li class="view_historytab">
         <a href="#history" data-toggle="tab" aria-expanded="true"><i class="fa fa-history text-purple"></i> <?php echo trans('label.lbl_history'); ?></a>
      </a>
   </li>
<?php }?>


</ul>
<div class="tab-content">
   <div id="purchase_request" class="tab-pane active">
                  <?php //echo "<pre>"; print_r(@$pr_first_detail);  echo "</pre>";
?>
                  <!-- Details START -->
                  <div class="panel invoice-panel">
                     <div class="panel-body p20" id="invoice-item">
                        <div class="row mb30">
                           <div class="col-md-10">
                              <div class="pull-left">
                                 <h5 class="mn"> <?php echo trans('label.lbl_req_date'); ?>: <?php echo isset($pr_first_detail['details']['pr_req_date']) ? date("d F Y", strtotime($pr_first_detail['details']['pr_req_date'])) : ""; ?> | <?php echo trans('label.lbl_due_date'); ?>: <?php echo isset($pr_first_detail['details']['pr_due_date']) ? date("d F Y", strtotime($pr_first_detail['details']['pr_due_date'])) : ""; ?> | <?php echo trans('label.lbl_status'); ?>: <b class="text-<?php echo $status_color; ?>"><?php echo isset($pr_first_detail['status']) ? ucwords($pr_first_detail['status']) : ""; ?></b>
                                  <br>
                                  <?php

// print_r($pr_first_detail);

if (isset($pr_first_detail['estimate_cost']) && $pr_first_detail['estimate_cost'] > 0) {
    if (isset($pr_first_detail['estimate_status']) && $pr_first_detail['estimate_status'] == 'rejected') {
        echo "<br><strong>Estimate Status: </strong><strong style='color:red;'>" . ucwords($pr_first_detail['estimate_status']) . "</strong>";
        echo " | <strong>Estimate Cost: </strong>" . $pr_first_detail['estimate_cost'];
        echo " | <strong>Comment: </strong>" . $pr_first_detail['estimate_cost_comment'];
    } else {
        echo "<br><strong>Estimate Status: </strong><strong style='color:green;'>" . ucwords($pr_first_detail['estimate_status']) . "</strong>";
        echo " | <strong>Estimate Cost: </strong>" . $pr_first_detail['estimate_cost'];
        echo " | <strong>Comment: </strong>" . $pr_first_detail['estimate_cost_comment'];
    }
}
?>

                                 </h5>
                              </div>
                           </div>
                           <!--<div class="col-md-4"> <img src="assets/img/logos/logo.png" class="img-responsive center-block mw200 hidden-xs" alt="AdminDesigns"> </div>
                              <div class="col-md-4">
                                  <div class="pull-right text-right">
                                      <h2 class="invoice-logo-text hidden lh10">AdminDesigns</h2>
                                      <h5> Sales Rep: <b class="text-info">Michael Ronny</b> </h5>
                                  </div>
                               </div>-->
                            </div>
                            <div class="row" id="invoice-info">
                              <div class="col-md-6">
                                 <div class="panel panel-alt">
                                    <div class="panel-heading"  style="background-color:aliceblue;">
                                       <span class="panel-title"> <i class="fa fa-info"></i> Requester & Department: </span>
                                       <div class="panel-btns pull-right ml10"> </div>
                                    </div>
                                    <div class="panel-body">
                                       <ul class="list-unstyled">
                                          <li> <b><?php echo trans('label.lbl_requester_name'); ?>:</b>
                                             <?php

$prefix = isset($pr_first_detail['requester_name_details']['prefix']) ? $pr_first_detail['requester_name_details']['prefix'] : "";
$fname = isset($pr_first_detail['requester_name_details']['fname']) ? $pr_first_detail['requester_name_details']['fname'] : "";
$lname = isset($pr_first_detail['requester_name_details']['lname']) ? $pr_first_detail['requester_name_details']['lname'] : "";
$employee_id = isset($pr_first_detail['requester_name_details']['employee_id']) ? $pr_first_detail['requester_name_details']['employee_id'] : "";

echo $prefix . '. ' . $fname . ' ' . $lname . ' | <b>Emp Id</b>: ' . $employee_id;
?>
                                          </li>
                                          <li> <b><?php echo trans('label.lbl_department'); ?>:</b> <?php echo isset($pr_first_detail['details']['pr_department']) ? $pr_first_detail['details']['pr_department'] : ""; ?>&nbsp;|&nbsp;<b><?php echo trans('label.lbl_requirement_for'); ?>:</b> <?php echo isset($pr_first_detail['details']['pr_requirement_for']) ? $pr_first_detail['details']['pr_requirement_for'] : ""; ?></li>
                                          <li> <b><?php echo trans('label.lbl_priority'); ?>:</b> <?php echo isset($pr_first_detail['details']['pr_priority']) ? ucwords($pr_first_detail['details']['pr_priority']) : ""; ?></li>
                                       </ul>
                                    </div>
                                 </div>
                              </div>

                              <div class="col-md-6">
                                 <div class="panel panel-alt">
                                    <div class="panel-heading"  style="background-color:aliceblue;">
                                       <span class="panel-title"> <i class="fa fa-info"></i> Category & Project Details: </span>
                                       <div class="panel-btns pull-right ml10"> </div>
                                    </div>
                                    <div class="panel-body">
                                       <ul class="list-unstyled">
                                          <li> <b><?php echo trans('label.lbl_category'); ?>:</b> <?php echo isset($pr_first_detail['details']['pr_category']) ? $pr_first_detail['details']['pr_category'] : ""; ?>&nbsp;|&nbsp;<b><?php echo trans('label.lbl_project_category'); ?>:</b> <?php echo isset($pr_first_detail['details']['pr_project_category']) ? $pr_first_detail['details']['pr_project_category'] : ""; ?></li>
                                          <?php
if (isset($pr_first_detail['details']['pr_project_category']) && $pr_first_detail['details']['pr_project_category'] == 'Internal') {
    ?>
                                            <li> <b><?php echo trans('label.lbl_project_name'); ?>:</b> <?php echo isset($pr_first_detail['details']['pr_project_name_dd']) ? $pr_first_detail['details']['pr_project_name_dd'] : ""; ?></li>
                                            <?php
} else {?>
                                          <li> <b><?php echo trans('label.lbl_project_name'); ?>:</b> <?php echo isset($pr_first_detail['details']['project_name']) ? $pr_first_detail['details']['project_name'] : ""; ?></li>
                                          <li> <b>Project WO Details:</b> <?php echo isset($pr_first_detail['details']['project_wo_details']) ? $pr_first_detail['details']['project_wo_details'] : ""; ?></li>
                                       <?php }
?>

                                       <li> <b>Opportunity Code:</b> <?php echo isset($pr_first_detail['details']['opportunity_code']) ? $pr_first_detail['details']['opportunity_code'] : ""; ?></li>
                                    </ul>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row" id="invoice-info">
                           <div class="col-md-6">
                              <div class="panel panel-alt">
                                 <div class="panel-heading" style="background-color:aliceblue;">
                                    <span class="panel-title" > <i class="fa fa-location-arrow"></i> <?php echo trans('label.lbl_shipto'); ?>:</span>
                                 </div>
                                 <div class="panel-body">
                                    <ul class="list-unstyled">
                                       <?php if (isset($pr_first_detail['shipto_details']['company_name']) && $pr_first_detail['shipto_details']['company_name'] == 'Other') {?>
                                          <li> <b>Details :</b> <?php echo isset($pr_first_detail['details']['ship_to_other']) ? $pr_first_detail['details']['ship_to_other'] : ""; ?></li>
                                       <?php } else {?>
                                          <li> <b><?php echo trans('label.lbl_company'); ?> :</b> <?php echo isset($pr_first_detail['shipto_details']['company_name']) ? $pr_first_detail['shipto_details']['company_name'] : ""; ?></li>
                                          <li>
                                             <b><?php echo trans('label.lbl_address'); ?> :</b>
                                             <?php echo isset($pr_first_detail['shipto_details']['address']) ? $pr_first_detail['shipto_details']['address'] : ""; ?>
                                          </li>
                                          <li>
                                             <b>PAN No :
                                             </b>
                                             <?php echo isset($pr_first_detail['shipto_details']['pan_no']) ? $pr_first_detail['shipto_details']['pan_no'] : ""; ?>
                                          </li>
                                          <li>
                                             <b><?php echo trans('label.lbl_gstn'); ?> :
                                             </b>
                                             <?php echo isset($pr_first_detail['shipto_details']['gstn']) ? $pr_first_detail['shipto_details']['gstn'] : ""; ?>
                                          </li>
                                       <?php }
?>
                                    </ul>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="panel panel-alt">
                                 <div class="panel-heading" style="background-color:aliceblue;">
                                    <span class="panel-title"> <i class="fa fa-user"></i> <?php echo trans('label.lbl_shipto_contact'); ?> :
                                    </span>
                                 </div>
                                 <div class="panel-body">
                                    <ul class="list-unstyled">
                                       <?php if (isset($pr_first_detail['shipto_contact_details']['fname']) && $pr_first_detail['shipto_contact_details']['fname'] == 'Other') {?>
                                          <li> <b>Details :</b> <?php echo isset($pr_first_detail['details']['ship_to_contact_other']) ? $pr_first_detail['details']['ship_to_contact_other'] : ""; ?></li>
                                       <?php } else {
    ?>
                                          <li>
                                             <b><?php echo trans('label.lbl_name'); ?> :</b>
                                             <?php
$prefix = isset($pr_first_detail['shipto_contact_details']['prefix']) ? $pr_first_detail['shipto_contact_details']['prefix'] : "";
    $fname = isset($pr_first_detail['shipto_contact_details']['fname']) ? $pr_first_detail['shipto_contact_details']['fname'] : "";
    $lname = isset($pr_first_detail['shipto_contact_details']['lname']) ? $pr_first_detail['shipto_contact_details']['lname'] : "";
    echo $prefix . '. ' . $fname . ' ' . $lname;
    ?>
                                          </li>
                                          <li>
                                             <b><?php echo trans('label.Email'); ?> :
                                             </b>
                                             <?php echo isset($pr_first_detail['shipto_contact_details']['email']) ? $pr_first_detail['shipto_contact_details']['email'] : ""; ?>
                                          </li>
                                          <li>
                                             <b><?php echo trans('label.lbl_contact'); ?> :
                                             </b>
                                             <?php echo isset($pr_first_detail['shipto_contact_details']['contact1']) ? $pr_first_detail['shipto_contact_details']['contact1'] : ""; ?> <strong>/</strong> <?php echo isset($pr_first_detail['shipto_contact_details']['contact2']) ? $pr_first_detail['shipto_contact_details']['contact2'] : ""; ?>
                                          </li>
                                       <?php }?>
                                    </ul>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row" id="invoice-table">
                           <div class="col-md-12">
                              <table class="table table-striped table-condensed">
                                 <thead>
                                    <!-- <tr>
                                       <th class="thmiddle" rowspan="2">#</th>
                                       <th class="thmiddle" rowspan="2">Item</th>
                                       <th class="thmiddle" rowspan="2">Description</th>
                                       <th class="thmiddle" rowspan="2" style="width: 135px;">Quanitity</th>
                                       <th class="thmiddle" rowspan="2">Rate</th>
                                       <th  class="text-center" colspan="2">Quantity<br></th>
                                       <th rowspan="2" class="text-right pr10  thmiddle">Price</th>
                                       </tr>
                                       <tr>
                                       <th>Ordered<br></th>
                                       <th>Received</th>
                                    </tr> -->
                                    <tr id="labelRow" style="height:30px;background-color:aliceblue;" >
                                       <th width="0%" class="text-center">Sr</th>
                                       <th width="20%" class="text-center"><?php echo trans('label.lbl_itemname'); ?></th>
                                       <th width="15%" class="text-center"><?php echo trans('label.lbl_description'); ?></th>
                                       <th width="5%" class="text-center">Qty</th>
                                       <th width="10%" class="text-center">In Stock</th>
                                       <th width="10%" class="text-center">Warranty/Support</th>
                                       <th width="20%" class="text-center">Address</th>
                                       <th width="10%" class="text-center">Conv. to PR</th>
                                      <!--  <th width="10%" class="text-center">PR Status</th> -->

                                       <!-- Extra Nikhil Code -->
                                       <?php
$qc_added_item = [];
$qc_item_array = [];
$qc_check_ary = json_decode($quotation_comparison_details['content'], true);
if ($qc_check_ary) {
    foreach ($qc_check_ary as $qckey => $qcvalue) {
        $qc_added_item[] = $qcvalue['selected_item_id'];
    }
    $qc_item_array = $qc_added_item;
}
if ($assetdetails) {
    $total_cost = 0;
    $total = 0;
    $item_product = '';
    foreach ($assetdetails as $i => $asset) {
        $asset_details = json_decode($asset['asset_details'], true);
        //   print_r($asset_details);
        if ($asset['convert_status'] == 'y' && canuser('advance', 'uploadquotation')) {
            $item_product = $asset_details['item_product'];
            if (in_array($item_product, $qc_item_array)) {
                ?>
                                                   <th width="10%" class="text-center">Add/Edit QT</th>
                                                   <?php
break;
            } else {
                ?>
                                                   <th width="10%" class="text-center">Add/Edit QT</th>
                                                <?php
break;
            }
        } else {
            echo isset($asset_details['is_delivered_instock']['delivered_status']) ? '<th width="10%" class="text-center">Status</th>' : '<th width="10%" class="text-center">Status</th>';
            break;
        }
    }
}

?>
                                       <!-- Extra Nikhil Code End -->

                                       <!--<th width="5%"><?php echo trans('label.lbl_received'); ?></th>-->
                                       <!--  <th width="25%" class="textalignright"><?php echo trans('label.lbl_estimated'); ?> <br> <?php echo trans('label.lbl_cost'); ?>&nbsp;<span id="itemEstimatedCost"></span></th>
                                          <th width="15%" class="textalignright"><?php echo trans('label.lbl_total'); ?>&nbsp;<span id="itemTotalCost">(&#8377;)</span></th> -->
                                       </tr>
                                    </thead>
                                    <tbody>
                                    <?php
// print_r($assetdetails);

$qc_added_item = [];
$qc_item_array = [];
$qc_check_ary = json_decode($quotation_comparison_details['content'], true);
if ($qc_check_ary) {
    foreach ($qc_check_ary as $qckey => $qcvalue) {
        $qc_added_item[] = $qcvalue['selected_item_id'];
    }
    $qc_item_array = $qc_added_item;
}
/*echo '<pre>';
print_r($assetdetails);*/
if ($assetdetails) {
    $total_cost = 0;
    $total = 0;
    $item_product = '';
    $btnids = 0;
    foreach ($assetdetails as $i => $asset) {
        $btnids = $btnids + 1;
        $asset_details = json_decode($asset['asset_details'], true);
        /* $total         = $asset_details['item_estimated_cost'] * $asset_details['item_qty'];
        $total_cost    = $total_cost + $total;*/

        ?>
                                        <tr>
                                          <td><b><?php echo $i + 1; ?></b>
                                          </td>
                                          <td>
                                             <?php
echo $asset_details['item_product_name'], '&nbsp;(', $asset_details['asset_sku'], ')';
        if (!empty($asset_details['pr_id']) && is_array($asset_details['pr_id'])) {
            echo "<br>";
            foreach ($asset_details['pr_id'] as $key => $value) {
                echo '<a class="conversion_prlist" data-id="' . $key . '" href="#" title="' . $value . '">', substr($value, 0, 20), '...</a><br>';
            }
        }
        ?>
                                          </td>
                                          <td><?php //echo $asset_details['item_desc']; 
                                          echo $asset_details['item_desc'];  ?></td>
                                          <td class="text-center"><input type="hidden" name="itemquantity[]" value="<?php echo $asset_details['item_qty']; ?>"/><?php echo $asset_details['item_qty']; ?></td>
                                          <td class="text-center"><?php if (!empty($asset_in_stock_data[$asset_details['asset_sku']])) {
            $passingVariable = "'" . $asset_in_stock_data[$asset_details['asset_sku']]['ci_templ_id'] . "'";
            $item_product = "'" . $asset_details['item_product'] . "'";
            $asset_sku = "'" . $asset_details['asset_sku'] . "'";
            if ($asset['convert_status'] == 'y') {
                echo '<a>' . $asset_in_stock_data[$asset_details['asset_sku']]['total_assets'] . '</a>';
            } else {
                echo '<a  class="localstoragefunction" href="javascript:localstorageFunctions(' . $passingVariable . ',' . $item_product . ',' . $asset_sku . ')">' . $asset_in_stock_data[$asset_details['asset_sku']]['total_assets'] . '</a>';
            }

            // echo '<a  onclick="localstorageFunction();" href="/assets/0/' . $asset_in_stock_data[$asset_details['asset_sku']]['ci_templ_id'] . '">' . $asset_in_stock_data[$asset_details['asset_sku']]['total_assets'] . '</a>';
        } else {
            echo 0;
        }
        ?></td>

                                          <td class="text-center"><?php echo $asset_details['warranty_support_required']; ?></td>
                                          <td><?php
if (!empty($asset_details['addresses'])) {
            foreach ($asset_details['addresses'] as $value) {
                $keys = (array_keys($value));
                echo $value[$keys[1]], '--', $value[$keys[0]], '<br>';
            }
        } else {
            echo "<span class='text-center'>-</span>";
        }
        ?></td>
                                       <td class="text-center"><?php if ($asset['convert_status'] == 'y') {echo "<span style='color:green;font-weight:bold;'>Yes</span>";} else {echo "<span style='color:red;font-weight:bold;'>No</span>";}?></td>

                                         <!--  <td class="text-center"> 
                                                <?php
                                                 if ($pr_first_detail['status'] == "rejected") {
                                                
                                                echo '<div class="text-danger"><strong>' . ucfirst($pr_first_detail['status']) . '</strong></div>';
                                                  } 

                                            else if ($pr_first_detail['status'] == "approved") {
                                               
                                                echo '<div class="text-success"><strong>' . ucfirst($pr_first_detail['status']) . '</strong></div>';
                                            } else if ($pr_first_detail['status'] == "closed") {
                                                
                                                echo '<div class="text-secondary"><strong>' . ucfirst($pr_first_detail['status']) . '</strong></div>';
                                            } else if ($pr_first_detail['status'] == "cancelled") {
                                                
                                               echo '<div class="text-danger"><strong>' . ucfirst($pr_first_detail['status']) . '</strong></div>';
                                            } else if ($pr_first_detail['status'] == "pending approval") {
                                                
                                               echo '<div class="text-warning"><strong>' . ucfirst($pr_first_detail['status']) . '</strong></div>';
                                            } 
                                                  ?>

                                          </td> -->

                                       <td class="text-center">
                                          <?php
if ($asset['convert_status'] == 'y' && canuser('advance', 'uploadquotation')) {
            $item_product = $asset_details['item_product'];
            //echo "";print_r($asset_details);

            if (in_array($item_product, $qc_item_array)) {
                ?>
                                                     <button type="button" class="qc_added" title="Quotation Added" style="border-color:green; color:green !important;" id="qc_btn" onclick="send_item('<?php echo $item_product; ?>','<?php echo $asset_details['item_product_name']; ?>','<?php echo $asset_details['item_qty']; ?>','<?php echo $pr_first_detail['pr_id']; ?>','<?php echo $asset_details['asset_sku']; ?>')"><span id="<?php echo $item_product; ?>" class="glyphicon glyphicon-ok"></span></button>
                                                  <?php
} else {
                ?>
                                                     <button type="button" class="qc_not_added" title="Add Quotation" style="border-color:blue; color:blue !important;" id="qc_btn" onclick="send_item('<?php echo $item_product; ?>','<?php echo $asset_details['item_product_name']; ?>','<?php echo $asset_details['item_qty']; ?>','<?php echo $pr_first_detail['pr_id']; ?>','<?php echo $asset_details['asset_sku']; ?>')"><span id="<?php echo $item_product; ?>" class="glyphicon glyphicon-pencil"></span></button>
                                                  <?php
}
        } else {
            if (isset($asset_details['is_delivered_instock']['delivered_status'])) {
                if ($asset_details['is_delivered_instock']['delivered_item_qty'] != '') {
                    echo isset($asset_details['is_delivered_instock']['delivered_status']) ? $asset_details['is_delivered_instock']['delivered_item_qty'] . ' Delivered' : '-';
                }

            }
            // echo isset($asset_details['is_delivered_instock']['delivered_status']) ? $asset_details['is_delivered_instock']['delivered_item_qty'] . ' Delivered'  : '-';
        }?>
                                       </td>
                                       <!--
                                          <td class="textalignright">0</td>
                                       -->
                                       <!-- <td class="textalignright"><?php //echo number_format((float) $asset_details['item_estimated_cost'], 2, '.', ''); ?></td>
                                          <td class="text-right pr10 textalignright"><?php echo number_format((float) $total, 2, '.', ''); ?></td> -->
                                       </tr>
                                       <?php
}
}
?>
                              </tbody>
                           </table>
                        </div>
                     </div>
                     <div class="row" id="invoice-footer">
                           <!-- <div class="col-md-12">
                              <div class="pull-left mt20 fs15 text-info"> <?php echo trans('messages.msg_buss_thanks'); ?></div>
                              <div class="pull-right">
                                  <table class="table" id="invoice-summary">
                                      <thead>
                                          <tr>
                                              <th></th>
                                              <th></th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          <tr>
                                              <td><b><?php echo trans('label.lbl_subtotal'); ?>:</b>
                                              </td>
                                              <td><?php echo number_format((float) @$total_cost, 2, '.', ''); ?></td>
                                          </tr>
                                      </tbody>
                                  </table>
                              </div>


                           </div> -->
                        </div>
                     </div>
                     <?php if (canuser('advance', 'view_attachment_pr')) {
    ?>
                        <div id="attachment_details" class="col-md-12 pt10 pln prn">
                           <div class="panel">
                              <div class="panel-heading"  style="background-color:aliceblue;">
                                 <span class="panel-icon"><i class="fa fa-list"></i></span>
                                 <span class="panel-title">
                                    <?php echo trans('label.lbl_attachmentdetails'); ?>
                                 </span>
                                 <div class="widget-menu pull-right">
                                 </div>
                              </div>
                              <div class="panel-body pn">
                                 <?php if (canuser('advance', 'add_attachment_pr')) {?>
                                    <div class="col-sm-12 pt10 pl30">
                                       <div class="tray-bin pl10 mb10">
                                          <!--<h5 class="text-muted mt10 fw600 pl10"><i class="fa fa-exclamation-circle text-info fa-lg pr10"></i> Portlet Drag and Drop Uploader </h5>-->
                                          <!--<form action="/add_attachment_pr" method="post" class="dropzone dropzone-sm" id="dropZone" enctype="multipart/form-data">-->
                                             <form action="/add_attachment_pr" method="post" class="" id="dropZone" enctype="multipart/form-data">
                                                <div class="fallback">

                                                   <input name="file[]" id="uploadFile" type="file" multiple='multiple' />
                                                   <input type="hidden" id="pr_po_id" name="pr_po_id" value="<?php echo isset($pr_first_detail['pr_id']) ? $pr_first_detail['pr_id'] : ""; ?>">
                                                   <input type="hidden" id="type" name="type" value="document">
                                                   <input type="hidden" id="attachment_type" name="attachment_type" value="pr">
                                                </div>
                                                <?php //echo csrf_field(); ?>
                                                <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">
                                                <input type="submit" id="attachmentbtn" value="<?php echo trans('label.btn_upload'); ?>" name="submit">&nbsp;<span style="color: red; font-style: italic;">(Only Accept: jpeg,jpg,png,pdf,doc,docx,csv,xlsx,xls)</span>
                                             </form>
                                          </div>
                                       </div>
                                    <?php }?>
                                    <!-- begin: .tray-center -->
                                    <div class="col-sm-12 pl30">
                                       <div class="tray tray-center pn">
                                          <table class="table table-striped table-condensed">
                                             <thead>
                                              <tr style="height:30px;background-color:aliceblue;">
                                                <th>Sr.No.</th>
                                                <th><?php echo trans('label.lbl_file'); ?></th>
                                                <th><?php echo 'Uploaded ' . trans('label.lbl_date'); ?></th>
                                                <th><?php echo trans('label.lbl_delete'); ?>
                                                <tr>
                                             </th>
                                             </thead>
                                             <tbody>
                                                <?php
/*  echo '<pre>';
    print_r($prpoattachment);*/
    if ($prpoattachment) {
        foreach ($prpoattachment as $key => $attachment) {
            // print_r($attachment);
            if ($attachment['attachment_type'] == 'pr') {
                $delete = '<span title = "' . trans('messages.msg_clicktodelete') . '" type="button" id="' . $attachment['attach_id'] . '" data-id="' . $attachment['attach_id'] . '" class="deleteAttachment"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';
                ?>
                                                 <tr>
                                                   <td><?php echo $key + 1; ?></td>
                                             <!--
                                                <td><?php echo "<a target='_blank' href='" . config('enconfig.itamservice_url') . $attachment['attachment_name'] . "'>" . trans('label.lbl_attachment') . ' ' . ($key + 1) . "</a>"; ?></td>
                                             -->
                                             <td>
                                                <span class = "download_file text-primary" download_id="<?php echo $attachment['attach_id']; ?>" style="cursor:pointer;" title="<?php echo trans("label.lbl_viewdownload"); ?>" download_path = "<?php echo $attachment['attachment_name']; ?>" download_title="<?php echo $attachment['file_title'] != null ? $attachment['file_title'] : trans('label.lbl_attachment') . ' ' . ($key + 1); ?>"><?php echo $attachment['file_title'] != null ? $attachment['file_title'] : trans('label.lbl_attachment') . ' ' . ($key + 1); ?>&nbsp;<i class="fa fa-cloud-download" style="color:green;font-size: large;"></i></span>
                                             </td>
                                             <td><?php echo date("d M Y h:i A", strtotime($attachment['created_at'])); ?></td>
                                             <?php if (canuser('advance', 'delete_attachment_pr')) {?>
                                                   <?php if ($attachment['file_title'] != 'CRM uploaded file') {?>
                                                      <td style="color:red;"><?php echo $delete; ?></td>
                                                   <?php } else {?>
                                                      <td></td>
                                              <?php }} else {
                    echo '<td></td>';
                }?>
                                          </tr>
                                          <?php
}}
    } else {
        echo "<tr><td colspan='4'>" . trans('messages.msg_nofilesattached') . "</td></tr>";
    }
    ?>
                               </tbody>
                            </table>
                         </div>
                      </div>
                      <!-- end: .tray-center -->
                   </div>
                </div>
             </div>
          <?php }?>
       </div>
       <!-- Details END -->
    </div>
    <?php if (canuser('advance', 'approve_reject_pr')) {
    ?>
      <div id="approvals" class="tab-pane">
        <!--  <div class="pull-left">
            <p><b><?php echo trans('label.lbl_approvals'); ?> - </b></p>
         </div> -->
                  <!--   <?php
// if($pr_first_detail['status'] =="pending approval")
    // {
    ?>
                     <div class="pull-right">
                         Last Notified On : Mar 4, 2019 <a class="ccursor actionsPr" id="notifyagain_<?php //echo $loggedinUser."_".$pr_first_detail['pr_id']; ?>"><strong> [ Notify Again ] </strong></a>
                     </div>
                     <?php //} ?>-->
                     <!-- Modal -->
                     <div id="myModal_approve_reject" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                           <form class="form-horizontal" id="formComment">
                              <!-- Modal content-->
                              <div class="modal-content">
                                 <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title"><span id="modal-title_approve_reject"><?php echo trans('label.lbl_rejectapprove'); ?> </span> <?php echo trans('label.lbl_thispr'); ?>: <?php //echo $pr_first_detail['details']['pr_title']; ?></h4>
                                 </div>
                                 <div class="modal-body">
                                    <div class="row">
                                       <div class="col-md-12">
                                          <div class="hidden alert-dismissable" id="msg_modal_approve_reject"></div>
                                       </div>
                                    </div>
                                    <input type="hidden" id="pr_po_type" name="pr_po_type" value='pr'>
                                    <input type="hidden" id="pr_po_id" name="pr_po_id">
                                    <input type="hidden" id="user_id" name="user_id">
                                    <input type="hidden" id="approval_status" name="approval_status">
                                    <input type="hidden" id="confirmed_optional" name="confirmed_optional">
                                    <div class="form-group required ">
                                       <label for="inputStandard" class="col-md-12 control-label textalignleft"><?php echo trans('label.lbl_comment'); ?></label>
                                       <div class="col-md-12">
                                          <textarea class="col-md-12" name="comment" maxlength="250"></textarea>
                                          <br><code style="float: inline-end;">(Max 250 Characters)</code>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="modal-footer">
                                    <button type="button" id="submitComment" class="btn btn-success"><?php echo trans('label.btn_submit'); ?></button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo trans('label.btn_close'); ?></button>
                                 </div>
                              </div>
                           </form>
                        </div>
                     </div>


                     <div class="col-md-12">
                        <table class="table mbn tc-med-1 tc-bold-last tc-fs13-last">
                           <thead style="height:30px;background-color:aliceblue;">
                              <th class="textaligncenter"><?php echo trans('label.lbl_confirmed'); ?></th>
                              <th class="textaligncenter"><?php echo trans('label.lbl_status'); ?></th>
                           </thead>
                           <tbody>
                              <?php
$approved_status = json_decode($pr_first_detail['approved_status'], true);

    if (isset($pr_first_detail['approval_details_by_data']['confirmed']) && !empty($pr_first_detail['approval_details_by_data']['confirmed'])) {
        foreach ($pr_first_detail['approval_details_by_data']['confirmed'] as $user) {
            if (!empty($user)) {

                ?>
                                   <tr>
                                    <td><i class="fa fa-circle text-warning fs8 pr15"></i>
                                       <span style="color: black"><?php echo $user['firstname'] . " " . $user['lastname']; ?></span>
                                    </td>
                                    <td>
                                       <div class="row">
                                          <div class="col-xs-5 pull-right">
                                             <?php
if (isset($user['user_id']) && !isset($approved_status['confirmed'][$user['user_id']]) && $pr_first_detail['status'] != "rejected") {
                    ?>
                                              <div class="pull-right">
                                                <a class="ccursor actionsPr" id="notifyagain_<?php if (isset($user['user_id']) && isset($pr_first_detail['pr_id'])) {
                        echo $loggedinUser . "_" . $pr_first_detail['pr_id'] . "_" . $user['user_id'];
                    }
                    ?>"><strong> [ <?php echo trans('label.lbl_notifyagain'); ?> ] </strong></a>
                                          </div>
                                       <?php }?>
                                    </div>
                                    <?php
if (isset($user['user_id']) && !isset($approved_status['confirmed'][$user['user_id']]) && $pr_first_detail['status'] != "rejected" && $user['user_id'] == showuserid()) {

                    ?>
                                     <?php if (canuser('advance', 'approve_reject_pr')) {
                        ?>
                                       <div class="col-xs-3 pull-right">
                                          <div class="btn-group reject" >
                                             <button id="rejected_<?php if (isset($user['user_id']) && isset($pr_first_detail['pr_id'])) {
                            echo $user['user_id'] . "_" . $pr_first_detail['pr_id'] . "_confirmed";
                        }
                        ?>" type="button" class="btn btn-default"><i class="glyphicons glyphicons-remove"></i> <?php echo trans('label.lbl_reject'); ?>
                                          </button>
                                       </div>
                                    </div>
                                    <div class="col-xs-3 pull-right">
                                       <div class="btn-group approve">
                                          <button id="approved_<?php if (isset($user['user_id']) && isset($pr_first_detail['pr_id'])) {
                            echo $user['user_id'] . "_" . $pr_first_detail['pr_id'] . "_confirmed";
                        }
                        ?>" type="button" class="btn btn-default"><i class="glyphicons glyphicons-check"></i> <?php echo trans('label.lbl_approve'); ?>
                                       </button>
                                    </div>
                                 </div>
                                 <?php
}
                } elseif (isset($user['user_id']) && !isset($approved_status['confirmed'][$user['user_id']]) && $pr_first_detail['status'] != "rejected" && $user['user_id'] != showuserid()) {
                    if (showuserid() == "7117a498-41c3-11ea-9e9a-0242ac110003") {
                        // echo trans('label.lbl_pendingapproval') . "SuperAdmin";
                        // Start
                        ?>
                        <div class="row">
                           <div class="col-xs-3 pull-right">
                              <div class="btn-group reject">
                                    <button id="rejected_<?php if (isset($user['user_id']) && isset($pr_first_detail['pr_id'])) {
                            echo $user['user_id'] . "_" . $pr_first_detail['pr_id'] . "_confirmed";
                        }
                        ?>" type="button" class="btn btn-default"><i class="glyphicons glyphicons-remove"></i>
                                       <?php echo trans('label.lbl_reject'); ?>
                                    </button>
                              </div>
                           </div>
                           <div class="col-xs-3 pull-right">
                              <div class="btn-group approve">
                                    <button id="approved_<?php if (isset($user['user_id']) && isset($pr_first_detail['pr_id'])) {
                            echo $user['user_id'] . "_" . $pr_first_detail['pr_id'] . "_confirmed";
                        }
                        ?>" type="button" class="btn btn-default"><i class="glyphicons glyphicons-check"></i>
                                       <?php echo trans('label.lbl_approve'); ?>
                                    </button>
                              </div>
                           </div>
                        </div>
                        <?php
// End
                    } else {
                        echo trans('label.lbl_pendingapproval');
                    }
                }

                if (isset($user['user_id']) && isset($approved_status['confirmed'][$user['user_id']])) {
                    echo '<div class="pull-right">';
                    echo $approved_status['confirmed'][$user['user_id']] == "rejected" ? '<i class="glyphicons glyphicons-remove"></i> ' . ucfirst($approved_status['confirmed'][$user['user_id']]) : '<i class="glyphicons glyphicons-check"></i> ' . ucfirst($approved_status['confirmed'][$user['user_id']]);
                    $comment = "";
                    $date = "";
                    if (!empty($prpohistorylog)) {
                        foreach ($prpohistorylog as $history) {
                            if (isset($user['user_id']) && isset($history['created_by']) && $history['created_by'] == $user['user_id']) {
                                $comment = $history['comment'];
                                $date = date("d M Y h:i A", strtotime($history['created_at']));
                                break;
                            }
                        }
                    }

                    echo showmessage('msg_ondatecomment', ['{name}', '{comment}'], [$date, $comment]) . '</div>';
                }

                ?>
                   </td>
                </tr>
                <?php
}
        }
    } else {
        echo "<tr><td style='text-align: center;' colspan='3'>" . trans('messages.msg_norecordfound') . "</td></tr>";
    }
    ?>
    </tbody>
 </table>
</div>
<div class="col-md-12">
   <hr>
</div>
<div class="col-md-12">
   <table class="table mbn tc-med-1 tc-bold-last tc-fs13-last">
      <thead style="height:30px;background-color:aliceblue;">
         <th class="textaligncenter"><?php echo trans('label.lbl_optional'); ?></th>
         <th class="textaligncenter"><?php echo trans('label.lbl_status'); ?></th>
      </thead>
      <tbody>
         <?php
if (isset($pr_first_detail['approval_details_by_data']['optional']) && !empty($pr_first_detail['approval_details_by_data']['optional'])) {
        foreach ($pr_first_detail['approval_details_by_data']['optional'] as $user) {
            if (!empty($user)) {
                ?>
              <tr>
               <td><i class="fa fa-circle text-warning fs8 pr15"></i>
                  <span style="color: black"><?php echo $user['firstname'] . " " . $user['lastname']; ?></span>
               </td>
               <td>
                  <div class="row">
                     <div class="col-xs-5 pull-right">
                        <?php
//if($pr_first_detail['status'] =="pending approval")

                if (isset($user['user_id']) && !isset($approved_status['optional'][$user['user_id']]) && $pr_first_detail['status'] != "rejected") {
                    ?>
                         <div class="pull-right">
                           <a class="ccursor actionsPr" id="notifyagain_<?php if (isset($user['user_id']) && isset($pr_first_detail['pr_id'])) {
                        echo $loggedinUser . "_" . $pr_first_detail['pr_id'] . "_" . $user['user_id'];
                    }
                    ?>"><strong> [ <?php echo trans('label.lbl_notifyagain'); ?> ] </strong></a>
                     </div>
                  <?php }?>
               </div>
               <?php
if (isset($user['user_id']) && !isset($approved_status['optional'][$user['user_id']]) && $pr_first_detail['status'] != "rejected" && $user['user_id'] == showuserid()) {
                    ?>
                <div class="col-xs-3 pull-right">
                  <div class="btn-group reject">
                     <button id="rejected_<?php if (isset($user['user_id']) && isset($pr_first_detail['pr_id'])) {
                        echo $user['user_id'] . "_" . $pr_first_detail['pr_id'] . "_optional";
                    }
                    ?>" type="button" class="btn btn-default"><i class="glyphicons glyphicons-remove"></i> <?php echo trans('label.lbl_reject'); ?>
                  </button>
               </div>
            </div>
            <div class="col-xs-3 pull-right">
               <div class="btn-group approve">
                  <button id="approved_<?php if (isset($user['user_id']) && isset($pr_first_detail['pr_id'])) {
                        echo $user['user_id'] . "_" . $pr_first_detail['pr_id'] . "_optional";
                    }
                    ?>" type="button" class="btn btn-default"><i class="glyphicons glyphicons-check"></i> <?php echo trans('label.lbl_approve'); ?>
               </button>
            </div>
         </div>
         <?php
} elseif (isset($user['user_id']) && !isset($approved_status['optional'][$user['user_id']]) && $pr_first_detail['status'] != "rejected" && $user['user_id'] != showuserid()) {
                    echo trans('label.lbl_pendingapproval');
                }
                if (isset($user['user_id']) && isset($approved_status['optional'][$user['user_id']])) {
                    echo $approved_status['optional'][$user['user_id']] == "rejected" ? '<i class="glyphicons glyphicons-remove"></i> ' . ucfirst($approved_status['optional'][$user['user_id']]) : '<i class="glyphicons glyphicons-check"></i> ' . ucfirst($approved_status['optional'][$user['user_id']]);
                    $comment = "";
                    $date = "";
                    if (!empty($prpohistorylog)) {
                        foreach ($prpohistorylog as $history) {
                            if (!empty($history)) {
                                if (isset($history['created_by']) && isset($user['user_id']) && $history['created_by'] == $user['user_id']) {
                                    $comment = $history['comment'];
                                    $date = date("d M Y h:i A", strtotime($history['created_at']));
                                    break;
                                }
                            }
                        }
                    }
                    echo showmessage('msg_ondatecomment', ['{name}', '{comment}'], [$date, $comment]) . '</div>';
                }

                ?>
</div>
</td>
</tr>
<?php
}
        }
    } else {
        echo "<tr><td style='text-align: center;'  colspan='2'>" . trans('messages.msg_norecordfound') . "</td></tr>";
    }
    ?>
</tbody>
</table>
</div>
</div>
<?php }?>
<?php if (canuser('advance', 'view_history')) {
    ?>
   <div id="history" class="tab-pane">
      <!--<p><b>History - </b></p>-->
      <div class="mt30 timeline-single" id="timeline">
         <?php
if ($prpohistorylog) {
        // echo "<pre>"; print_r($prpohistorylog);echo "</pre>";
        foreach ($prpohistorylog as $history) {
            if (!empty($history['details'])) {
                if ($history['history_date']) {
                    ?>
                 <div class="timeline-divider mtn">
                  <div class="divider-label"><?php echo $history['history_date']; ?></div>
                           <!--<div class="pull-right">
                              <button id="timeline-toggle" class="btn btn-default btn-sm">
                                  <span class="glyphicons glyphicons-show_lines fs16"></span>
                              </button>
                           </div>-->
                        </div>
                     <?php }?>
                     <div class="row">
                        <div class="col-sm-6 right-column">
                           <div class="timeline-item">
                              <div class="timeline-icon">
                                 <?php
$default = "glyphicons glyphicons-edit text-warning";
                $reason = "<br>";
                if ($history['action'] == "pending approval") {
                    $default = "glyphicons glyphicons-circle_info text-primary";
                    $reason .= "<strong>" . trans('label.lbl_reason') . ": </strong>" . $history['comment'];
                }
                if ($history['action'] == "approved") {
                    $default = "glyphicons glyphicons-check text-success";
                    $reason .= "<strong>" . trans('label.lbl_reason') . ": </strong>" . $history['comment'];
                }
                if ($history['action'] == "rejected") {
                    $default = "glyphicons glyphicons-remove text-danger";
                    $reason .= "<strong>" . trans('label.lbl_reason') . ": </strong>" . $history['comment'];
                }
                if ($history['action'] == "cancelled") {
                    $default = "glyphicons glyphicons-remove text-danger";
                    $reason .= "<strong>" . trans('label.lbl_reason') . ": </strong>" . $history['comment'];
                }
                if ($history['action'] == "notifyowner") {
                    $default = "fa fa-warning text-warning";
                    $reason .= "<strong>" . trans('label.lbl_reason') . ": </strong>" . $history['comment'];
                }
                if ($history['action'] == "notifyagain") {
                    $default = "fa fa-warning text-warning";
                    $reason .= "<strong>" . trans('label.lbl_reason') . ": </strong>" . $history['comment'];
                }
                ?>
                                <span class="<?php echo $default; ?>"></span>
                             </div>
                             <div class="panel">
                              <div class="panel-body p10">
                                 <strong><?php echo date("d M Y h:i A", strtotime($history['created_at'])); ?></strong>
                                 <blockquote class="mbn ml10">
                                    <?php
echo $history['details'];
                //echo "[ ".ucwords($history['action'])." ]";
                echo $reason;
                ?>
                                    <small>
                                       <?php
if (isset($history['created_by_name']['firstname'])) {
                    $fname = $history['created_by_name']['firstname'];
                } else {
                    $fname = '';
                }

                if (isset($history['created_by_name']['lastname'])) {
                    $lname = $history['created_by_name']['lastname'];
                } else {
                    $lname = '';
                }

                echo $fname . " " . $lname;
                ?>
                                   </small>
                                   <p></p>
                                </blockquote>
                             </div>
                          </div>
                       </div>
                    </div>
                 </div>
                 <?php
}
        }
    } else {
        echo trans('messages.msg_norecordfound');
    }
    ?>
    </div>
 </div>
<?php }?>
<?php if (canuser('advance', 'uploadquotation')) {
    ?>
   <div id="upload_quotation" class="tab-pane">
      <div class="panel invoice-panel">
         <?php if (canuser('advance', 'uploadquotation')) {
        $pr_id = isset($pr_first_detail['pr_id']) ? $pr_first_detail['pr_id'] : "";
        if ($item_count > 0) {
            ?>
            <form action="<?php echo url('/PrQuotationComparison/details/' . $pr_id) ?>" id="PrQuotationComparison" name="PrQuotationComparison" method="post">
               <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">
               <input type="hidden" id="pr_po_id" name="pr_po_id" value="<?php echo isset($pr_first_detail['pr_id']) ? $pr_first_detail['pr_id'] : ""; ?>">
               <div class="form-group col-md-12">
                  <div class="form-group">
                     <label class="col-md-3 control-label"></label>
                     <div class="col-xs-4">
                        <!-- <button style="width: 100%;" id="pr_qt_details" type="button" class="btn btn-warning btn-block">Get all Quotation Details</button> -->
                        <a href="<?php echo url('/prquotationcomparison/details/' . $pr_id) ?>" target="_blank" class="btn btn-success btn-block"><strong><span class="glyphicon glyphicon-list-alt"></span>&nbsp;Show all Quotation Comparisons</strong></a>
                     </div>
                  </div>
               </div>
            </form>
         <?php }?>
         <?php

        $disabled = '';
        if ($item_count > 0) {
            if (isset($item_qt_arr[0]['approval']) && !empty($item_qt_arr[0]['approval'])) {
                if ($item_qt_arr[0]['approval'] == 'approved') {
                    //$disabled  = 'disabled  = "disabled"';
                }
                ?>
              <?php

                /*foreach ($item_qt_arr as $key => $value)
                {
                $vendor_approve = $value['vendor_approve'];
                $vendor_approve_arr          = json_decode($vendor_approve, true);
                //echo '<pre>'; print_r($vendor_approve_arr);

                if(isset($vendor_approve_arr['converted_as_po']))
                {
                $disabled  = 'disabled  = "disabled"';
                }
                else
                {
                $disabled  = '';
                }
                }*/
                ?>
               <!-- <table class="table table-striped table-bordered table-hover">
                  <tr>
                     <th>Qautation Status&nbsp;:&nbsp;<?php
if ($item_qt_arr[0]['approval'] == 'rejected') {
                    echo "<span style='color:red'>" . strtoupper($item_qt_arr[0]['approval']) . "</span>";
                } else {
                    echo "<span style='color:green'>" . strtoupper($item_qt_arr[0]['approval']) . "</span>";
                }
                ?></th>
                  <th>Comment/Reason&nbsp;:&nbsp;<?php echo $item_qt_arr[0]['reject_comment']; ?></th>
               </tr>
            </table> -->
         <?php }}?>
         <div id="attachment_details" class="col-md-12 pt10 pln prn qcadd" style="display:none;">
            <div class="panel">
               <div class="panel-heading"  style="background-color:aliceblue;">
                  <span class="panel-icon"><i class="fa fa-upload"></i></span>
                  <span class="panel-title">
                     Quotation Comparison
                  </span>
                  <div class="widget-menu pull-right">
                  </div>
               </div>
               <div class="panel-body pn">
                  <?php //if (canuser('advance', 'uploadquotation')) {?>
                     <div class="col-md-12 pt10 pln prn">
                        <div class="col-md-12 pt10 pln prn">
                           <div class="table-responsive">
                              <form action="<?php echo url('/quotationvendorcomparison/add') ?>" id="quotationvendorcomparison" name="quotationvendorcomparison" method="post">
                                 <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">
                                 <input type="hidden" id="pr_po_id" name="pr_po_id" value="<?php echo isset($pr_first_detail['pr_id']) ? $pr_first_detail['pr_id'] : ""; ?>">
                                 <input type="hidden" id="selected_item_id" value="" name="selected_item_id">
                                 <input type="hidden" id="selected_item_name" value="" name="selected_item_name">
                                 <input type="hidden" id="selected_asset_sku" value="" name="selected_asset_sku">

                                 <table border=1 class="table table-striped table-condensed">
                                  <tbody>

                                    <tr>
                                     <!--  <td></td> -->
                                     <!--   <td></td> -->
                                     <td colspan="2"></td>
                                     <td colspan="2" class="text-center"><strong>Vendor 1&nbsp;<span style="color:red;">*</span></strong></strong></td>
                                     <td colspan="2" class="text-center"><strong>Vendor 2&nbsp;<span style="color:red;">*</span></strong></td>
                                     <td colspan="2" class="text-center"><strong>Vendor 3&nbsp;<span style="color:red;">*</span></strong></td>
                                     <td colspan="2" class="text-center"><strong>Vendor 4</strong></td>
                                     <td colspan="2" class="text-center"><strong>Vendor 5</strong></td>
                                  </tr>

                                  <tr>
                                    <!-- <td>#</td> -->
                                    <td colspan="2" id="ItemGet" style="text-align: center; color:blue;font-weight: bold;"> <i id="asset_sku"></i></td>
                                    <td colspan="2">
                                       <select data-placeholder="<?php echo trans('label.lbl_vendor'); ?>" tabindex="5"  class="form-control chosen-select vendor_select"  name="pr_vendor_id[]" <?php echo $disabled; ?> id="pr_vendor_id_1">
                                          <option value="">-Select</option>
                                          <?php
if (!empty($vendors)) {
            foreach ($vendors as $key => $value) {?>
                                                <option value="<?php echo $value['vendor_id'] ?>"><?php echo $value['vendor_name'] ?></option>
                                             <?php }}?>
                                          </select>
                                       </td>
                                       <td colspan="2">
                                          <select data-placeholder="<?php echo trans('label.lbl_vendor'); ?>" tabindex="5"  class="form-control chosen-select vendor_select"  name="pr_vendor_id[]" id="pr_vendor_id_2" <?php echo $disabled; ?>>
                                             <option value="">-Select-</option>
                                             <?php
if (!empty($vendors)) {
            foreach ($vendors as $key => $value) {?>
                                                   <option value="<?php echo $value['vendor_id'] ?>"><?php echo $value['vendor_name'] ?></option>
                                                <?php }}?>
                                             </select>
                                          </td>
                                          <td colspan="2">
                                             <select data-placeholder="<?php echo trans('label.lbl_vendor'); ?>" tabindex="5"  class="form-control chosen-select vendor_select"  name="pr_vendor_id[]" id="pr_vendor_id_3" <?php echo $disabled; ?>>
                                                <option value="">-Select-</option>
                                                <?php
if (!empty($vendors)) {
            foreach ($vendors as $key => $value) {?>
                                                      <option value="<?php echo $value['vendor_id'] ?>"><?php echo $value['vendor_name'] ?></option>
                                                   <?php }}?>
                                                </select>
                                             </td>

                                             <td colspan="2">
                                                <select data-placeholder="<?php echo trans('label.lbl_vendor'); ?>" tabindex="5"  class="form-control chosen-select vendor_select"  name="pr_vendor_id[]" id="pr_vendor_id_4" <?php echo $disabled; ?>>
                                                   <option value="">-Select-</option>
                                                   <?php
if (!empty($vendors)) {
            foreach ($vendors as $key => $value) {?>
                                                         <option value="<?php echo $value['vendor_id'] ?>"><?php echo $value['vendor_name'] ?></option>
                                                      <?php }}?>
                                                   </select>
                                                </td>

                                                <td colspan="2">
                                                   <select data-placeholder="<?php echo trans('label.lbl_vendor'); ?>" tabindex="5"  class="form-control chosen-select vendor_select"  name="pr_vendor_id[]" id="pr_vendor_id_5" <?php echo $disabled; ?>>
                                                      <option value="">-Select-</option>
                                                      <?php
if (!empty($vendors)) {
            foreach ($vendors as $key => $value) {?>
                                                            <option value="<?php echo $value['vendor_id'] ?>"><?php echo $value['vendor_name'] ?></option>
                                                         <?php }}?>
                                                      </select>
                                                   </td>

                                                </tr>

                                                <tr>
                                                   <td></td>
                                                   <td class="text-center"><strong>Qty</strong></td>
                                                   <!--  <td> </td> -->
                                                   <td class="text-center"><strong>Rate</strong></td>
                                                   <td class="text-center"><strong>Amount</strong></td>
                                                   <td class="text-center"><strong>Rate</strong></td>
                                                   <td class="text-center"><strong>Amount</strong></td>
                                                   <td class="text-center"><strong>Rate</strong></td>
                                                   <td class="text-center"><strong>Amount</strong></td>
                                                   <td class="text-center"><strong>Rate</strong></td>
                                                   <td class="text-center"><strong>Amount</strong></td>
                                                   <td class="text-center"><strong>Rate</strong></td>
                                                   <td class="text-center"><strong>Amount</strong></td>
                                                </tr>

                                                <tr class="text-center">
                                                   <!-- <td class="text-center"><strong>1</strong></td> -->
                                                   <td class="text-left" style="width:60px;">1 Quote</td>
                                                   <td><input type="number" min = 1   id="qty_1" class="it_sz gley" name="qty_1[]" readonly="readonly"></td>
                                                   <td><input type="number" min = 1   class="it_sz" id="rate_1_v1" name="rate_1[]" onkeyup="cal(this.id);" onchange="cal(this.id);" onblur="cal(this.id);"></td>
                                                   <td><input type="number" min = 1   class="it_sz gley" id="amount_1_v1" name="amount_1[]" readonly="readonly"></td>
                                                   <td><input type="number" min = 1   class="it_sz" id="rate_1_v2" name="rate_1[]" onkeyup="cal(this.id);" onchange="cal(this.id);" onblur="cal(this.id);"></td>
                                                   <td><input type="number" min = 1   class="it_sz gley" id="amount_1_v2" name="amount_1[]" readonly="readonly"></td>
                                                   <td><input type="number" min = 1   class="it_sz" id="rate_1_v3" name="rate_1[]" onkeyup="cal(this.id);" onchange="cal(this.id);" onblur="cal(this.id);"></td>
                                                   <td><input type="number" min = 1   class="it_sz gley" id="amount_1_v3" name="amount_1[]" readonly="readonly"></td>
                                                   <td><input type="number" min = 1   class="it_sz" id="rate_1_v4" name="rate_1[]" onkeyup="cal(this.id);" onchange="cal(this.id);" onblur="cal(this.id);"></td>
                                                   <td><input type="number" min = 1   class="it_sz gley" id="amount_1_v4" name="amount_1[]" readonly="readonly"></td>
                                                   <td><input type="number" min = 1   class="it_sz" id="rate_1_v5" name="rate_1[]" onkeyup="cal(this.id);" onchange="cal(this.id);" onblur="cal(this.id);"></td>
                                                   <td><input type="number" min = 1  class="it_sz gley" id="amount_1_v5" name="amount_1[]" readonly="readonly"></td>
                                                </tr>

                                                <tr class="text-center">
                                                 <!--  <td> </td> -->
                                                 <td class="text-left" style="width:60px;">2 Quote</td>
                                                 <td><input type="number" min = 1   id="qty_2" class="it_sz gley" name="qty_2[]"></td>
                                                 <td><input type="number" min = 1  class="it_sz" id="rate_2_v1" name="rate_2[]" onkeyup="cal(this.id);" onchange="cal(this.id);" onblur="cal(this.id);"></td>
                                                 <td><input type="number" min = 1  class="it_sz gley" id="amount_2_v1" name="amount_2[]" readonly="readonly"></td>
                                                 <td><input type="number" min = 1  class="it_sz" id="rate_2_v2" name="rate_2[]" onkeyup="cal(this.id);" onchange="cal(this.id);" onblur="cal(this.id);"></td>
                                                 <td><input type="number" min = 1  class="it_sz gley" id="amount_2_v2" name="amount_2[]" readonly="readonly"></td>
                                                 <td><input type="number" min = 1  class="it_sz" id="rate_2_v3" name="rate_2[]" onkeyup="cal(this.id);" onchange="cal(this.id);" onblur="cal(this.id);"></td>
                                                 <td><input type="number" min = 1  class="it_sz gley" id="amount_2_v3" name="amount_2[]" readonly="readonly"></td>
                                                 <td><input type="number" min = 1  class="it_sz" id="rate_2_v4" name="rate_2[]" onkeyup="cal(this.id);" onchange="cal(this.id);" onblur="cal(this.id);"></td>
                                                 <td><input type="number" min = 1  class="it_sz gley" id="amount_2_v4" name="amount_2[]" readonly="readonly"></td>
                                                 <td><input type="number" min = 1  class="it_sz" id="rate_2_v5" name="rate_2[]" onkeyup="cal(this.id);" onchange="cal(this.id);" onblur="cal(this.id);"></td>
                                                 <td><input type="number" min = 1  class="it_sz gley" id="amount_2_v5" name="amount_2[]" readonly="readonly"></td>
                                              </tr>

                                              <tr class="text-center">
                                               <!--   <td> </td> -->
                                               <td class="text-left" style="width:60px;">3 Quote</td>
                                               <td><input type="number" min = 1   id="qty_3" class="it_sz gley" name="qty_3[]"></td>
                                               <td><input type="number" min = 1  class="it_sz" id="rate_3_v1" name="rate_3[]" onkeyup="cal(this.id);" onchange="cal(this.id);" onblur="cal(this.id);"></td>
                                               <td><input type="number" min = 1  class="it_sz gley" id="amount_3_v1" name="amount_3[]" readonly="readonly" ></td>
                                               <td><input type="number" min = 1  class="it_sz" id="rate_3_v2" name="rate_3[]" onkeyup="cal(this.id);" onchange="cal(this.id);" onblur="cal(this.id);"></td>
                                               <td><input type="number" min = 1  class="it_sz gley" id="amount_3_v2" name="amount_3[]" readonly="readonly"></td>
                                               <td><input type="number" min = 1  class="it_sz" id="rate_3_v3" name="rate_3[]" onkeyup="cal(this.id);" onchange="cal(this.id);" onblur="cal(this.id);"></td>
                                               <td><input type="number" min = 1  class="it_sz gley" id="amount_3_v3" name="amount_3[]" readonly="readonly"></td>
                                               <td><input type="number" min = 1  class="it_sz" id="rate_3_v4" name="rate_3[]" onkeyup="cal(this.id);" onchange="cal(this.id);" onblur="cal(this.id);"></td>
                                               <td><input type="number" min = 1  class="it_sz gley" id="amount_3_v4" name="amount_3[]" readonly="readonly"></td>
                                               <td><input type="number" min = 1  class="it_sz" id="rate_3_v5" name="rate_3[]" onkeyup="cal(this.id);" onchange="cal(this.id);" onblur="cal(this.id);"></td>
                                               <td><input type="number" min = 1  class="it_sz gley" id="amount_3_v5" name="amount_3[]" readonly="readonly"></td>
                                            </tr>

                                            <!-- quotation reference no start -->
                                            <tr class="text-center">
                                               <td colspan="2" class="text-left">Quotation Ref No</td>
                                               <td colspan="2"><input type="text" class="text_size" id="quotation_reference_no_1" name="quotation_reference_no_1[]" ></td>
                                               <td colspan="2"><input type="text" class="text_size" id="quotation_reference_no_2" name="quotation_reference_no_2[]" > </td>
                                               <td colspan="2"> <input type="text" class="text_size" id="quotation_reference_no_3" name="quotation_reference_no_3[]" ></td>
                                               <td colspan="2"> <input type="text" class="text_size" id="quotation_reference_no_4" name="quotation_reference_no_4[]" ></td>
                                               <td colspan="2"> <input type="text" class="text_size" id="quotation_reference_no_5" name="quotation_reference_no_5[]" ></td>
                                            </tr>
                                            <!-- quotation reference no end -->

                                            <tr class="text-center">
                                             <!-- <td class="text-center"><strong>2</strong></td> -->
                                             <td colspan="2" class="text-left">Warranty & Support</td>
                                             <!-- <td> </td> -->
                                             <td colspan="2"><input type="text" class="text_size" id="warranty_support_1" name="warranty_support_1[]" ></td>
                                             <td colspan="2"><input type="text" class="text_size" id="warranty_support_2" name="warranty_support_2[]" > </td>
                                             <td colspan="2"> <input type="text" class="text_size" id="warranty_support_3" name="warranty_support_3[]" ></td>
                                             <td colspan="2"> <input type="text" class="text_size" id="warranty_support_4" name="warranty_support_4[]" ></td>
                                             <td colspan="2"> <input type="text" class="text_size" id="warranty_support_5" name="warranty_support_5[]" ></td>

                                          </tr>
                                          <tr class="text-center">
                                           <!--  <td class="text-center"><strong>3</strong></td> -->
                                           <td colspan="2" class="text-left">GST Extra</td>
                                           <!-- <td > </td> -->
                                           <!-- <td colspan="2"><input type="text" class="text_size" id="gst_extra_1" name="gst_extra_1[]" > </td>
                                           <td colspan="2"> <input type="text" class="text_size" id="gst_extra_2" name="gst_extra_2[]" ></td>
                                           <td colspan="2"><input type="text" class="text_size" id="gst_extra_3" name="gst_extra_3[]" > </td>
                                           <td colspan="2"><input type="text" class="text_size" id="gst_extra_4" name="gst_extra_4[]" > </td>
                                           <td colspan="2"><input type="text" class="text_size" id="gst_extra_5" name="gst_extra_5[]" > </td> -->
                                           <td colspan="2">
                                            <select id="gst_extra_1" name="gst_extra_1[]" class="text_size">
                                                <option value="">Select</option>
                                              <option value="NA">NA</option>
                                              <option value="18%">18%</option>
                                              <option value="28%">28%</option>
                                            </select>
                                          </td>
                                          <td colspan="2">
                                            <select id="gst_extra_2" name="gst_extra_2[]" class="text_size">
                                                <option value="">Select</option>
                                              <option value="NA">NA</option>
                                              <option value="18%">18%</option>
                                              <option value="28%">28%</option>
                                            </select>
                                          </td>
                                          <td colspan="2">
                                            <select id="gst_extra_3" name="gst_extra_3[]" class="text_size">
                                                <option value="">Select</option>
                                              <option value="NA">NA</option>
                                              <option value="18%">18%</option>
                                              <option value="28%">28%</option>
                                            </select>
                                          </td>
                                          <td colspan="2">
                                            <select id="gst_extra_4" name="gst_extra_4[]" class="text_size">
                                                <option value="">Select</option>
                                              <option value="NA">NA</option>
                                              <option value="18%">18%</option>
                                              <option value="28%">28%</option>
                                            </select>
                                          </td>
                                          <td colspan="2">
                                            <select id="gst_extra_5" name="gst_extra_5[]" class="text_size">
                                              <option value="">Select</option>
                                              <option value="NA">NA</option>
                                              <option value="18%">18%</option>
                                              <option value="28%">28%</option>
                                            </select>
                                          </td>
                                        </tr>
                                        <tr class="text-center">
                                          <!-- <td  class="text-center"><strong>4</strong></td> -->
                                          <td colspan="2" class="text-left">Payment terms</td>
                                          <!--  <td > </td> -->
                                          <td colspan="2"> <input type="text" class="text_size" id="payment_terms_1" name="payment_terms_1[]" ></td>
                                          <td colspan="2"> <input type="text" class="text_size" id="payment_terms_2" name="payment_terms_2[]" ></td>
                                          <td colspan="2"> <input type="text" class="text_size" id="payment_terms_3" name="payment_terms_3[]" ></td>
                                          <td colspan="2"> <input type="text" class="text_size" id="payment_terms_4" name="payment_terms_4[]" ></td>
                                          <td colspan="2"> <input type="text" class="text_size" id="payment_terms_5" name="payment_terms_5[]" ></td>
                                       </tr>
                                       <tr class="text-center">
                                        <!--  <td  class="text-center"><strong>5</strong></td> -->
                                        <td colspan="2" class="text-left">Freight &amp; Transport</td>
                                        <!-- <td > </td> -->
                                        <td colspan="2"> <input type="text" class="text_size" id="transport_1" name="transport_1[]" ></td>
                                        <td colspan="2"> <input type="text" class="text_size" id="transport_2" name="transport_2[]" ></td>
                                        <td colspan="2"><input type="text" class="text_size" id="transport_3" name="transport_3[]" > </td>
                                        <td colspan="2"><input type="text" class="text_size" id="transport_4" name="transport_4[]" > </td>
                                        <td colspan="2"><input type="text" class="text_size" id="transport_5" name="transport_5[]" > </td>
                                     </tr>
                                     <tr class="text-center">
                                       <!-- <td  class="text-center"><strong>6</strong></td> -->
                                       <td colspan="2" class="text-left">Delivery terms</td>
                                       <!-- <td> </td> -->
                                       <td colspan="2"> <input type="text" class="text_size" id="delivery_terms_1" name="delivery_terms_1" ></td>
                                       <td colspan="2"> <input type="text" class="text_size" id="delivery_terms_2" name="delivery_terms_2" ></td>
                                       <td colspan="2"> <input type="text" class="text_size" id="delivery_terms_3" name="delivery_terms_3" ></td>
                                       <td colspan="2"> <input type="text" class="text_size" id="delivery_terms_4" name="delivery_terms_4" ></td>
                                       <td colspan="2"> <input type="text" class="text_size" id="delivery_terms_5" name="delivery_terms_5" ></td>
                                    </tr>
                                    <tr class="text-center">
                                     <!--  <td  class="text-center"><strong>7</strong></td> -->
                                     <td colspan="2" class="text-left">Material Details</td>
                                     <!--  <td> </td> -->
                                     <td colspan="2"> <input type="text" class="text_size" id="material_description_1" name="material_description_1[]" ></td>
                                     <td colspan="2"> <input type="text" class="text_size" id="material_description_2" name="material_description_2[]" ></td>
                                     <td colspan="2"> <input type="text" class="text_size" id="material_description_3" name="material_description_3[]" ></td>
                                     <td colspan="2"> <input type="text" class="text_size" id="material_description_4" name="material_description_4[]" ></td>
                                     <td colspan="2"> <input type="text" class="text_size" id="material_description_5" name="material_description_5[]" ></td>
                                  </tr>
                                  <tr class="text-center" style="display: none;">
                                    <td colspan="3"  class="text-right"><strong>Minimum Amount</strong></td>
                                    <td colspan="2"><input type="number" class="text_size gley text-right" id="total_1" name="total_1" readonly="readonly"> </td>
                                    <td colspan="2">  <input type="number" class="text_size gley text-right" id="total_2" name="total_2" readonly="readonly"></td>
                                    <td colspan="2"> <input type="number" class="text_size gley text-right"  id="total_3" name="total_3" readonly="readonly"></td>
                                    <td colspan="2"> <input type="number" class="text_size gley text-right"  id="total_4" name="total_4" readonly="readonly"></td>
                                    <td colspan="2"> <input type="number" class="text_size gley text-right"  id="total_5" name="total_5" readonly="readonly"></td>
                                 </tr>
                                                      <!--<tr class="text-center">
                                                      <td colspan="3" class="text-right"><strong>Approve Vendor</strong></td>
                                                      <td colspan="2"><input type="radio" class="checkmark" id="pr_qt_Submit" name="approve" value="0">&nbsp;<strong style="display:block;">Approve</strong></td>
                                                      <td colspan="2"><input type="radio"  class="checkmark" id="pr_qt_Submit" name="approve" value="1">&nbsp;<strong style="display:block;">Approve</strong></td>
                                                      <td colspan="2"><input type="radio" class="checkmark" id="pr_qt_Submit" name="approve" value="2">&nbsp;<strong style="display:block;">Approve</strong></td>
                                                   </tr> -->
                                                </tbody>
                                             </table>
                                             <br>
                                             <div class="form-group col-md-12">
                                                <div class="form-group">
                                                   <label class="col-md-3 control-label"></label>
                                                   <div class="col-xs-4">
                                                    <?php
if (in_array("rejected", $po_status_value)) {
            echo '<button style="width: 100%;" id="pr_qt_Submit" type="button" class="btn btn-success btn-block">Submit Quotation</button>';
        } else {
            echo '<button style="width: 100%;" id="pr_qt_Submit" type="button" class="disabled-btnx btn btn-success btn-block">Submit Quotation</button>';
        }
        ?>
                                                   </div>
                                                </div>
                                             </div>
                                          </form>

                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <?php //}?>
                        </div>
                     <?php }?>
                     <?php if (canuser('advance', 'uploadquotation')) {?>
                        <div id="attachment_details" class="col-md-12 pt10 pln prn">
                           <div class="panel">
                              <div class="panel-heading"  style="background-color:aliceblue;">
                                 <span class="panel-icon"><i class="fa fa-upload"></i></span>
                                 <span class="panel-title">
                                    <?php echo trans('label.lbl_upload_quotation'); ?>
                                 </span>
                                 <div class="widget-menu pull-right">
                                 </div>
                              </div>
                              <div class="panel-body pn">
                                 <?php if (canuser('advance', 'uploadquotation')) {?>
                                    <div class="col-sm-12 pt10 pl30">
                                       <div class="tray-bin pl10 mb10">
                                          <!--<h5 class="text-muted mt10 fw600 pl10"><i class="fa fa-exclamation-circle text-info fa-lg pr10"></i> Portlet Drag and Drop Uploader </h5>-->
                                          <!--<form action="/add_attachment_pr" method="post" class="dropzone dropzone-sm" id="dropZone" enctype="multipart/form-data">-->
                                             <!-- <form action="/add_attachment_pr" method="post" class="" id="dropZone" enctype="multipart/form-data"> -->
                                             <form method="post" class="" id="uploadVendorQuotation" enctype="multipart/form-data">
                                                <div class="form-group">
                                                   <label><?php echo trans('label.lbl_vendor'); ?>:</label>
                                                   <select style="width: 25%;"  data-placeholder="<?php echo trans('label.lbl_vendor'); ?>" tabindex="5"  class="form-control chosen-select"  name="pr_vendor_id" id="pr_vendor_id">
                                                      <option value="">-Select-</option>
                                                      <?php
if (!empty($vendors)) {
        foreach ($vendors as $key => $value) {
            if (isset($vendorInPrQuotations)) {
                if (in_array($value['vendor_id'], $vendorInPrQuotations)) {
                    ?>
                                                            <option value="<?php echo $value['vendor_id'] ?>"><?php echo $value['vendor_name'] ?></option>
                                                         <?php
}
            }
        }}?>
                                                      </select>
                                                   </div>
                                                   <div class="form-group fallback">
                                                      <input name="file[]" id="quotationuploadFile" type="file" multiple='multiple' />

                                                      <input type="hidden" id="pr_po_id" name="pr_po_id" value="<?php echo isset($pr_first_detail['pr_id']) ? $pr_first_detail['pr_id'] : ""; ?>">
                                                      <input type="hidden" id="pr_po_id111" name="pr_po_id111" value="<?php echo isset($pr_first_detail['pr_id']) ? $pr_first_detail['pr_id'] : ""; ?>">
                                                      <input type="hidden" id="type" name="type" value="document">
                                                      <input type="hidden" id="attachment_type" name="attachment_type" value="qu">

                                                   </div>
                                                   <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">
                                                   <input type="button" id="quotationattachmentbtn" value="Upload Quotation" name="submit">&nbsp;<span style="color: red; font-style: italic;">(Only Accept: jpeg,jpg,png,pdf,doc,docx,csv,xlsx,xls)</span>
                                                </form>
                                             </div>
                                          </div>
                                       <?php }?>
                                       <!-- begin: .tray-center -->
                                       <div class="col-sm-12 pl30">
                                          <div class="tray tray-center pn">
                                             <table class="table table-striped table-condensed">
                                                <thead>
                                                  <tr style="height:30px;background-color:aliceblue;">
                                                   <th>Sr.No.</th>
                                                   <th><?php echo 'Quotations'; ?></th>
                                                   <th><?php echo trans('label.lbl_vendor') . ' Name'; ?></th>
                                                   <th><?php echo 'Uploaded ' . trans('label.lbl_date'); ?></th>
                                                   <th><?php echo trans('label.lbl_delete'); ?></th>
                                                   <tr>
                                                </thead>
                                                <tbody>
                                                   <?php
/* echo '<pre>'; print_r($prpoattachment1); echo '</pre>';*/
        if ($prpoattachment1) {
            foreach ($prpoattachment1 as $key => $attachment) {

                if ($attachment['attachment_type'] == 'qu') {
                    $delete = '<span title = "' . trans('messages.msg_clicktodelete') . '" type="button" id="' . $attachment['attach_id'] . '" data-id="' . $attachment['attach_id'] . '" class="deleteAttachment"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';
                    ?>
                                                       <tr>
                                                         <td><?php echo $key + 1; ?></td>
                                                <!--
                                                   <td><?php echo "<a target='_blank' href='" . config('enconfig.itamservice_url') . $attachment['attachment_name'] . "'>" . trans('label.lbl_attachment') . ' ' . ($key + 1) . "</a>"; ?></td>
                                                -->
                                                <td>
                                                   <span class = "download_file text-primary" download_id="<?php echo $attachment['attach_id']; ?>" style="cursor:pointer;" title="<?php echo trans("label.lbl_viewdownload"); ?>" download_path = "<?php echo $attachment['attachment_name']; ?>"><?php echo 'Quotation' . ' ' . ($key + 1); ?>&nbsp;<i class="fa fa-cloud-download" style="font-size: large;color:green"></i></span>
                                                </td>
                                                <td><?php echo getvendorbyid($attachment['pr_vendor_id']) ?></td>
                                                 <td><?php echo date("d M Y h:i A", strtotime($attachment['created_at'])); ?></td>
                                                <?php if (canuser('advance', 'delete_attachment_pr')) {?>
                                                   <td style="color: red;"  id="<?php echo $attachment['attach_id']; ?>" data-id="<?php echo $attachment['attach_id']; ?>" class="deleteAttachment"><?php echo $delete; ?></td>
                                                <?php }?>
                                             </tr>
                                             <?php
}}
        } else {
            echo "<tr><td colspan='5'>" . trans('messages.msg_nofilesattached') . "</td></tr>";
        }
        ?>
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                           <!-- end: .tray-center -->
                        </div>
                     </div>
                  </div>
               <?php }?>
            </div>
            <!-- Details END -->
         </div>
      <?php }?>
      <?php if (canuser('advance', 'assignprtouser')) {
    ?>
         <div id="assign_pr_to_user" class="tab-pane">
            <?php //echo "<pre>"; print_r(@$pr_first_detail);  echo "</pre>"; ?>
            <!-- Details START -->
            <div class="panel invoice-panel">
               <?php if (canuser('advance', 'assignprtouser')) {?>
                  <div id="attachment_details" class="col-md-12 pt10 pln prn">
                     <div class="panel">
                        <div class="panel-heading"  style="background-color:aliceblue;">
                           <span class="panel-icon"><i class="fa fa-upload"></i></span>
                           <span class="panel-title">
                              <?php echo trans('label.lbl_assign_pr_to_user'); ?>
                           </span>
                           <div class="widget-menu pull-right">
                           </div>
                        </div>
                        <div class="panel-body pn">
                           <?php if (canuser('advance', 'assignprtouser')) {?>
                              <div class="col-sm-8 pt10 pl30">
                                 <div class="tray-bin pl10 mb10">
                                    <!--<h5 class="text-muted mt10 fw600 pl10"><i class="fa fa-exclamation-circle text-info fa-lg pr10"></i> Portlet Drag and Drop Uploader </h5>-->
                                    <!--<form action="/add_attachment_pr" method="post" class="dropzone dropzone-sm" id="dropZone" enctype="multipart/form-data">-->
                                       <form action="/assignprtouser" method="post" class="" id="dropZone" enctype="multipart/form-data">
                                          <div class="form-group">
                                             <label>&nbsp;</label>
                                             <select class="form-control" name="pr_assign_user_id" id="pr_assign_user_id">
                                                <option value="">-Select-</option>
                                                <?php
if (!empty($allUsers)) {
        //print_r($allUsers);
        foreach ($allUsers as $value) {

            if ($pr_first_detail['assignpr_user_id'] == $value['user_id']) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            ?>
                                                  <option <?php echo $selected; ?> value="<?php echo $value['user_id'] ?>~<?php echo $value['firstname'] . ' ' . $value['lastname'] ?>"><?php echo $value['firstname'] . ' ' . $value['lastname'] ?></option>
                                               <?php }
    }?>
                                            </select>
                                         </div>
                                         <div class="form-group">
                                          <input type="hidden" id="pr_po_id" name="pr_po_id" value="<?php echo isset($pr_first_detail['pr_id']) ? $pr_first_detail['pr_id'] : ""; ?>">
                                       </div>
                                       <?php //echo csrf_field(); ?>
                                       <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">
                                       <input type="submit" class="btn btn-primary" id="assign_pr_to_user_btn" value="Assigned" name="submit">
                                    </form>
                                 </div>
                              </div>
                           <?php }?>
                           <!-- begin: .tray-center -->
                           <!-- end: .tray-center -->
                        </div>
                     </div>
                  </div>
               <?php }?>
            </div>
            <!-- Details END -->
         </div>
      <?php }?>

      <!-- Start comment tab -->
<div id="pr_comment" class="tab-pane">
   <div class="panel invoice-panel">
     <div id="comment_details" class="col-md-12 pt10 pln prn">
       <div class="panel">
         <div class="panel-heading" style="background-color:aliceblue;">
           <span class="panel-icon">
             <i class="fa fa-upload"></i>
           </span>
           <span class="panel-title">Comments</span>
           <div class="widget-menu pull-right"></div>
         </div>
         <div class="panel-body pn">
            <div class="col-sm-12 pt10 pl30">
             <div class="pl10 mb10 comment_msg">
               <div class="row">
                 <form class="form-horizontal" id="pr_formComment">
                   <div class="col-sm-9">
                     <div class="form-group required " style="margin: 10px;">
                       <label for="inputStandard" class="col-md-12 control-label textalignleft"> <?php echo trans('label.lbl_comment'); ?> </label>
                       <input type="hidden" id="pr_po_type_comment" name="pr_po_type" value="pr">
                       <input type="hidden" id="pr_po_id_comment" name="pr_po_id">
                       <input type="hidden" id="user_id_comment" name="user_id">
                       <input type="hidden" id="action_comment" name="action">
                       <input type="hidden" id="notify_to_id_comment" name="notify_to_id">
                       <input type="hidden" id="approval_status_comment" name="approval_status" value="comment">
                       <input type="hidden" id="confirmed_optional_comment" name="confirmed_optional" value="optional">
                       <div class="col-md-12">
                         <textarea class="col-md-12" id="commentboxs" name="comment" maxlength="250" required></textarea>
                         <br>
                         <code style="float: inline-end;">(Max 250 Characters)</code>
                       </div>
                     </div>
                   </div>
                   <div class="col-sm-3">
                     <div class="form-group">
                       <input type="hidden" id="pr_po_id" name="pr_po_id" value="
                                        <?php echo isset($pr_first_detail['pr_id']) ? $pr_first_detail['pr_id'] : ""; ?>">
                     </div> <?php //echo csrf_field(); ?> <input type="hidden" name="_token" value="
                                        <?php echo csrf_token() ?>">
                     <button style="margin-top:30px;" type="button" id="pr_comment_submit" class="btn btn-success comment_btn">Comment</button>
                   </div>
                 </form>
               </div>
             </div>
           </div>
         </div>

         <div id="pr_comment_history" class="tab-pane">
           <div class="mt30 timeline-single" id="timeline"> <?php
if ($prpohistorylog) {
    foreach ($prpohistorylog as $history) {
        if (empty($history['details'])) {
            if ($history['history_date']) {
                ?> <div class="timeline-divider mtn">
               <div class="divider-label"> <?php echo $history['history_date']; ?> </div>
             </div> <?php }?> <div class="row">
               <div class="col-sm-6 right-column">
                 <div class="timeline-item">
                   <div class="timeline-icon"> <?php
$default = "glyphicons glyphicons-edit text-warning";
            $reason = "
                <br>";
            if ($history['action'] == "pending approval") {
                $default = "glyphicons glyphicons-circle_info text-primary";
                $reason .= "
                  <strong>" . trans('label.lbl_reason') . ": </strong>" . $history['comment'];
            }
            if ($history['action'] == "approved") {
                $default = "glyphicons glyphicons-check text-success";
                $reason .= "
                  <strong>" . trans('label.lbl_reason') . ": </strong>" . $history['comment'];
            }
            if ($history['action'] == "rejected") {
                $default = "glyphicons glyphicons-remove text-danger";
                $reason .= "
                  <strong>" . trans('label.lbl_reason') . ": </strong>" . $history['comment'];
            }
            if ($history['action'] == "cancelled") {
                $default = "glyphicons glyphicons-remove text-danger";
                $reason .= "
                  <strong>" . trans('label.lbl_reason') . ": </strong>" . $history['comment'];
            }
            if ($history['action'] == "notifyowner") {
                $default = "fa fa-warning text-warning";
                $reason .= "
                  <strong>" . trans('label.lbl_reason') . ": </strong>" . $history['comment'];
            }
            if ($history['action'] == "notifyagain") {
                $default = "fa fa-warning text-warning";
                $reason .= "
                  <strong>" . trans('label.lbl_reason') . ": </strong>" . $history['comment'];
            }
            ?> <span class="
                    <?php echo $default; ?>">
                     </span>
                   </div>
                   <div class="panel">
                     <div class="panel-body p10">
                       <strong> <?php echo date("d M Y h:i A", strtotime($history['created_at'])); ?> </strong>
                       <blockquote class="mbn ml10"> <?php
echo $history['comment'];
            //echo "[ ".ucwords($history['action'])." ]";
            echo $reason;
            ?> <small> <?php
if (isset($history['created_by_name']['firstname'])) {
                $fname = $history['created_by_name']['firstname'];
            } else {
                $fname = '';
            }

            if (isset($history['created_by_name']['lastname'])) {
                $lname = $history['created_by_name']['lastname'];
            } else {
                $lname = '';
            }

            echo $fname . " " . $lname;
            ?> </small>
                         <p></p>
                       </blockquote>
                     </div>
                   </div>
                 </div>
               </div>
             </div> <?php
}
    }
} else {
    echo trans('messages.msg_norecordfound');
}
?> </div>
         </div>
       </div>
     </div>
   </div>
 </div>

            <!-- End comment tab -->





 <?php if (canuser('advance', 'estimatecost')) {?>
<div id="pr_estimate_cost" class="tab-pane">
   <div class="panel invoice-panel">
     <div id="comment_details" class="col-md-12 pt10 pln prn">
       <div class="panel">
         <div class="panel-heading" style="background-color:aliceblue;">
           <span class="panel-icon">
             <i class="fa fa-inr" aria-hidden="true"></i>
           </span>
           <span class="panel-title">Estimate Cost</span>
           <div class="widget-menu pull-right"></div>
         </div>
         <div class="panel-body pn">
           <div class="col-sm-12">
             <div class="comment_msg">
               <div class="row">
                 <form class="form-horizontal" id="pr_formEstimatecost">
                   <div class="col-sm-12">
                     <div class="form-group required " style="margin: 10px;">
                       <input type="hidden" name="_token" value="<?php echo csrf_token() ?>">
                       <input type="hidden" id="pr_po_id" name="pr_po_id" value="<?php echo isset($pr_first_detail['pr_id']) ? $pr_first_detail['pr_id'] : ""; ?>">
                       <input type="hidden" id="pr_department_id" name="pr_department_id" value=" <?php echo isset($pr_first_detail['details']['pr_department_id']) ? $pr_first_detail['details']['pr_department_id'] : ""; ?>">
                         <label for="inputStandard" class="col-md-3" style="text-align: end;">Estimate Cost</label>&nbsp;
                         <input type="number" min = 1 class="col-md-3" id="estimate_cost" name="estimate_cost" required style="height: 30px;" />&nbsp;&nbsp;
                         <button type="button" id="pr_estimatecost_submit" class="col-md-2 btn btn-success comment_btn" style="margin-left: 50px;">Verify Estimate</button>
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
 </div>

<?php }?>

         </div>
         <!--tab-content -->
      </div>
      <!--tab-block mb25-->
   </div>
   <!--panel-body pn br-n-->
</div>
<!--panel-->
</div>
<!--podetails_page-->
</div>
<script type="text/javascript">

   function cal_total()
   {
      for(var i = 1; i<=3; i++) {
         var cm_tot = 0.00;
         for(var j = 1; j<=3; j++) {
          var row_amount =  $('#amount_'+j+'_v'+i).val();
          if(row_amount == ""){
           row_amount = 0.00;
        }
        cm_tot = parseFloat(cm_tot) +  parseFloat(row_amount);
     }
     var total_amount = parseFloat(cm_tot).toFixed(2);
     $('#total_'+i).val(total_amount);
  }
}
function cal(id)
{
  var index = id;
  var rate = $('#'+index).val();
  var exp_arr = index.split('_');
  var qty = $('#qty_'+exp_arr[1]).val();
  var amount = parseFloat(rate).toFixed(2) * parseFloat(qty).toFixed(2);
  var amountX = parseFloat(amount).toFixed(2);
  $('#amount_'+exp_arr[1]+'_'+exp_arr[2]).val(amountX);
 //cal_total();
}
function send_item(item_id,item_name,item_qty,pr_po_id,asset_sku)
{
// item_product add in localstorage
   localStorage.setItem("btn_item_product", item_id);
//
   $('.qcadd').show();
   $('#ItemGet').html(item_name);
   $('#selected_item_id').val(item_id);
   $('#selected_asset_sku').val(asset_sku);
   $('#selected_item_name').val(item_name);
   $('#qty_1').val(item_qty);
   $('#qty_2').val(item_qty);
   $('#qty_3').val(item_qty);

   closeMsgBox('msg_div');
   var url = SITE_URL + '/quotationvendorcomparison/edit';
   emLoader('show','Loading....');
   var postData = {selected_item_id:item_id,item_name:item_name,pr_po_id:pr_po_id};
   var creTemplateajax = ajaxCall(creTemplateajax, url, postData, function(data) {
      emLoader('hide');

      $('#purchase_request').removeClass('active');
      $('#history').removeClass('active');
      $('#approvals').removeClass('active');
      $('#assign_pr_to_user').removeClass('active');
      $('#upload_quotation').addClass('active');
      $('.purchase_requesttab').removeClass('active');
      $('.view_historytab').removeClass('active');
      $('.view_commenttab').removeClass('active');
      $('.approve_reject_prtab').removeClass('active');
      $('.assignprtousertab').removeClass('active');
      $('.upload_quotationtab').addClass('active');

      var result = JSON.parse(data);
      var obj = result.content;
   //console.log(obj);
   if(obj.length > 2)
   {
      var res2 = JSON.parse(obj);
     //console.log(res2);
      var res3 = JSON.parse(res2[0]['quotation_comparison_data']);
      ///$("input[name=approve][value=" + value + "]").prop('checked', true);
      var k=0;
      var j=1;
      var vendor_approve = JSON.parse(res2[0]['vendor_approve']);
      var approval = res2[0]['approval'];
      //console.log(approval);


      if(approval == "approved") {
        //$('#pr_qt_Submit').prop('disabled', true);
        $('.disabled-btnx').prop('disabled', true);

      } else {
        //$('#pr_qt_Submit').prop('disabled', false);
        $('.disabled-btnx').prop('disabled', false);
      }



      /*if(vendor_approve) {
        if(vendor_approve['converted_as_po'] == 'yes') {
          $('#pr_qt_Submit').prop('disabled', true);
        }
      } else {
        $('#pr_qt_Submit').prop('disabled', false);
      }*/

      //console.log(res3);
      $.each( res3, function( key, value )
      {

         //console.log(j+key);
         $('#quotation_reference_no_'+j).val(res3[key]['quotation_reference_no']);
         $('#warranty_support_'+j).val(res3[key]['warranty_support']);
         $('#gst_extra_'+j).val(res3[key]['gst_extra']);
         $('#payment_terms_'+j).val(res3[key]['payment_terms']);
         $('#transport_'+j).val(res3[key]['transport']);
         $('#delivery_terms_'+j).val(res3[key]['delivery_terms']);
         $('#material_description_'+j).val(res3[key]['material_description']);
         $('#total_'+j).val(res3[key]['total']);
         $('#pr_vendor_id_'+j+ ' option[value="'+key+'"]').attr("selected", "selected");


         var prevValue = $('#pr_vendor_id_'+j).data('previous');
         $('.vendor_select').not('#pr_vendor_id_'+j).find('option[value="'+prevValue+'"]').show();
         var value2 = $('#pr_vendor_id_'+j).val();
         $('#pr_vendor_id_'+j).data('previous',value2);
         $('.vendor_select').not('#pr_vendor_id_'+j).find('option[value="'+value2+'"]').hide();

         // $('.vendor_select').not('#pr_vendor_id_'+j).find('option[value="'+key+'"]').hide();

         $("input[name=approve][value=" + res2[0]['approve_option'] + "]").prop('checked', true);

         if(j==1)
         {
            for(x=1;x<=3;x++)
            {
               $('#qty_'+x).val(res3[key][parseInt(x)-parseInt(1)]['qty_'+x]);
            }
         }



         for(x=0;x<3;x++)
         {

            $('#rate_'+(parseInt(x) + parseInt(1))+'_v'+j).val(res3[key][x]['rate_'+(parseInt(x) + parseInt(1))]);
            $('#amount_'+(parseInt(x) + parseInt(1))+'_v'+j).val(res3[key][x]['amount_'+(parseInt(x) + parseInt(1))]);
         }



         k++;
         j++;
      });


      var edit_vendorhtml='';
            for (let i = 1; i < 6; i++) {
               var pr_vendor_id_text = $('#pr_vendor_id_'+i).find(":selected").text();
               var pr_vendor_id_value = $('#pr_vendor_id_'+i).find(":selected").val();

               if(pr_vendor_id_value)
               {
                  edit_vendorhtml+='<option value='+pr_vendor_id_value+'>'+pr_vendor_id_text+'</option>';
               }
            }
               $('#pr_vendor_id').find('option').remove().end().append(edit_vendorhtml);

   } else {
      $("#quotationvendorcomparison").find('input:text, input:number, input:password, input:file, select, textarea').val('');
      $("#quotationvendorcomparison").find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
      $('#qty_1').val(item_qty);
      $('#qty_2').val(item_qty);
      $('#qty_3').val(item_qty);
   }
});
}



jQuery(document).ready(function() {

$('.vendor_select').on('change', function(event ) {
   var prevValue = $(this).data('previous');
   $('.vendor_select').not(this).find('option[value="'+prevValue+'"]').show();
   var value = $(this).val();
  $(this).data('previous',value); $('.vendor_select').not(this).find('option[value="'+value+'"]').hide();
  vendorlistoption();

});

function vendorlistoption()
{
   var vendorhtml='';

 for (let i = 1; i < 6; i++) {
     var pr_vendor_id_text = $('#pr_vendor_id_'+i).find(":selected").text();
     var pr_vendor_id_value = $('#pr_vendor_id_'+i).find(":selected").val();

     if(pr_vendor_id_value)
     {
           vendorhtml+='<option value='+pr_vendor_id_value+'>'+pr_vendor_id_text+'</option>';
     }
  }
    $('#pr_vendor_id').find('option').remove().end().append(vendorhtml);
}




       // Dropzone autoattaches to "dropzone" class.
       // Configure Dropzone options


      /*
       // Dropzone autoattaches to "dropzone" class.
       // Configure Dropzone options
       Dropzone.options.dropZone = {
           paramName: "file", // The name that will be used to transfer the file
           maxFilesize: 0, // MB

           addRemoveLinks: true,
           dictDefaultMessage: '<i class="fa fa-cloud-upload"></i> \
    <span class="main-text"><b>Drop Files</b> to upload</span> <br /> \
    <span class="sub-text">(or click)</span> \
   ',
           dictResponseError: 'Server not Configured'

       };

       Dropzone.options.dropZone2 = {
           paramName: "file", // The name that will be used to transfer the file
           maxFilesize: 0, // MB

           addRemoveLinks: true,
           dictDefaultMessage: '<i class="fa fa-cloud-upload"></i> \
    <span class="main-text"><b>Drop Files</b> to upload</span> <br /> \
    <span class="sub-text">(or click)</span> \
   ',
           dictResponseError: 'Server not Configured'
       };

       // demo code
       setTimeout(function() {
           var Drop = $('#dropZone2');
           Drop.addClass('dz-started dz-demo');

           setTimeout(function() {
               $('.example-preview').each(function(i, e) {
                   var This = $(e);

                   var thumbOut = setTimeout(function() {
                       Drop.append(This);
                       This.addClass('animated fadeInRight').removeClass('hidden');
                   }, i * 135);

               });
           }, 750);

       }, 800);

       // Demo code
       $('.example-preview').on('click', 'a.dz-remove', function() {
           $(this).parent('.example-preview').remove();
       });

       */

       var cid = "<?php echo "comments_" . $loggedinUser . "_" . $pr_first_detail['pr_id']; ?>";

       var action = cid.split('_')[0];
       var user_id = cid.split('_')[1];
       var pr_id =c= cid.split('_')[2];
       var notify_to_id = cid.split('_')[3];

       $("#pr_po_id_comment").val(pr_id);
       $("#pr_po_type_comment").val("pr");
       $("#user_id_comment").val(user_id);
       $("#action_comment").val(action);
       $("#notify_to_id_comment").val(notify_to_id);

    });
 </script>
 <style type="text/css">
   .it_sz{
      width: 65px !important;
      text-align: right !important;
      font-variant-numeric: tabular-nums;
      height: 25px;
   }
   .text_size{
      width: 100% !important;
      height: 25px;
   }
   .gley{
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
.container:hover input ~ .checkmark {
 background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.container input:checked ~ .checkmark {
 background-color: #2196F3;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
 content: "";
 position: absolute;
 display: none;
}

/* Show the checkmark when checked */
.container input:checked ~ .checkmark:after {
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

<script type="text/javascript">

function localstorageFunctions(url,item_product,asset_sku)
{
   prId =  "<?php echo isset($pr_first_detail['pr_id']) ? $pr_first_detail['pr_id'] : ''; ?>";
   pr_department_id = "<?php echo isset($pr_first_detail['details']['pr_department_id']) ? $pr_first_detail['details']['pr_department_id'] : ''; ?>";
   pr_requester_name = "<?php echo isset($pr_first_detail['details']['pr_requester_name']) ? $pr_first_detail['details']['pr_requester_name'] : ''; ?>";
   var urls = "/assetsSku/0/" + url + "/0/" + asset_sku;
   localStorage.setItem("instock_asset_prid", prId);
   localStorage.setItem("instock_asset_pr_department_id", pr_department_id);
   localStorage.setItem("instock_asset_pr_requester_id", pr_requester_name);
   localStorage.setItem("instock_asset_pr_item_product", item_product);
   window.open(urls, "_blank");
}

</script>
<script type="text/javascript">
    $("#submitAction").click(function(){
       
       var to = $('#mail_notification_to').val();
       var subject = $('#mail_notification_subject').val();

       if(to ==""){
        alert("Please Enter Notification To mail Address");
        return false;
       }
      if(subject =="")
       {
        alert("Please Enter Subject");
        return false;
       }
})
</script>
