<?php

$pr_first_detail = (isset($pr_first_detail) ? $pr_first_detail : array());
if (is_array($pr_first_detail) && count($pr_first_detail) > 0 && isset($pr_first_detail['po_id']) && $pr_first_detail['po_id'] != "") {

    $pr_first_detail['details'] = (isset($pr_first_detail['details']) ? $pr_first_detail['details'] : array());
    $pr_first_detail['po_id'] = (isset($pr_first_detail['po_id']) ? $pr_first_detail['po_id'] : "");
    $pr_first_detail['approved_status'] = (isset($pr_first_detail['approved_status']) ? $pr_first_detail['approved_status'] : "");
    $status = ['pending approval', 'rejected', 'hold']; // PDF and add invoice block

    ?>
<div class="panel-heading br-l br-r br-t">
    <span class="panel-title"> Purchase Order No - <?php echo isset($pr_first_detail['po_no']) ? $pr_first_detail['po_no'] : "";
    $pr_first_detail['status'] = (isset($pr_first_detail['status']) ? $pr_first_detail['status'] : "");
    //echo $pr_first_detail['details']['pr_title'];       ?></span>
    <div class="panel-header-menu pull-right mr10">
        <?php
if ($pr_first_detail['status'] == "rejected") {
        $status_color = "danger";
        echo ' <button type="button" class="btn btn-danger"><strong>' . ucfirst($pr_first_detail['status']) . '</strong></button> ';
    } else if ($pr_first_detail['status'] == "approved") {
        $status_color = "success";
        echo ' <button type="button"class="btn btn-success"><strong>' . ucfirst($pr_first_detail['status']) . '</strong></button>';
    } else if ($pr_first_detail['status'] == "closed") {
        $status_color = "default";
        echo ' <button type="button"class="btn btn-default"><strong>' . ucfirst($pr_first_detail['status']) . '</strong></button> ';
    } else if ($pr_first_detail['status'] == "cancelled") {
        $status_color = "danger";
        echo ' <button type="button"class="btn btn-danger"><strong>' . ucfirst($pr_first_detail['status']) . '</strong></button> ';
    } else if ($pr_first_detail['status'] == "ordered") {
        $status_color = "info";
        echo ' <button type="button"class="btn btn-info"><strong>' . ucfirst($pr_first_detail['status']) . '</strong></button> ';
    } else if ($pr_first_detail['status'] == "pending approval") {
        $status_color = "warning";
        echo '<button type="button"class="btn btn-warning"><strong>' . ucfirst($pr_first_detail['status']) . '</strong></button>';
    } else if ($pr_first_detail['status'] == "item received") {
        $status_color = "info";
        echo '<button type="button"class="btn btn-info"><strong>' . ucfirst($pr_first_detail['status']) . '</strong></button>';
    } else {
        $status_color = "default";
        echo ' <button type="button"class="btn btn-default"><strong>' . ucfirst($pr_first_detail['status']) . '</strong></button> ';
    }
    $loggedinUser = showuserid();
    ?>
    </div>
    <span class="panel-controls pr5"> <?php //echo trans('label.lbl_pr_title'); ?> -
        <?php //echo (isset($pr_first_detail['details']['pr_title']) ? $pr_first_detail['details']['pr_title'] : ""); ?>
    </span>
</div>
<div class="panel-body bg-light">
    <?php //echo "<pre>";print_r($pr_first_detail);  echo "</pre>"; ?>
    <div id="podetails_page" class="col-md-12 prn-md animated fadeIn">
        <div class="panel">
            <div class="bg-light pv8 pr10   br-light">
                <div class="row">
                    <div class="hidden-xs hidden-sm col-md-12 va-m">
                        <?php if ($pr_first_detail['status'] != "rejected" && $pr_first_detail['status'] != "item received" && $pr_first_detail['status'] != "closed" && $pr_first_detail['status'] != "cancelled" && $pr_first_detail['status'] != "deleted" && $pr_first_detail['status'] != "ordered" && $pr_first_detail['status'] != "approved" && $pr_first_detail['status'] != "partially received") {
        ?>
                        <?php if (canuser('update', 'purchaseorder')) {?>
                        <div class="btn-group">
                            <button id="poEdit"
                                data-id="<?php echo isset($pr_first_detail['po_id']) ? $pr_first_detail['po_id'] : ""; ?>_<?php echo isset($pr_first_detail['pr_id']) ? $pr_first_detail['pr_id'] : ""; ?>"
                                type="button" class="btn btn-default light"><i class="fa fa-pencil"></i>
                                <?php echo trans('label.btn_edit_po'); ?>
                            </button>
                        </div>
                        <?php
}
    }
    if ($pr_first_detail['status'] == "pending approval") {

        ?>
                        <div class="btn-group">
                            <button type="button"
                                id="notifyagain_<?php echo $loggedinUser . "_" . $pr_first_detail['po_id']; ?>"
                                class="actionsPo btn btn-success light"><i class="fa fa-check-square-o"></i>
                                <?php echo trans('label.btn_submitapprovals'); ?>
                            </button>
                        </div>
                        <?php }?>

                        <?php if (canuser('advance', 'view_attachment_po') || canuser('advance', 'cancel_po') || canuser('advance', 'order_po') || canuser('advance', 'receive_items_po') || canuser('delete', 'purchaseorder') || canuser('advance', 'close_po') || canuser('create', 'invoice_po') || canuser('advance', 'notify_owner_email') || canuser('advance', 'notify_vendor_email')) {
        ?>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default light dropdown-toggle ph8"
                                data-toggle="dropdown">
                                <span class="fa fa-tags"></span>
                                <span class="caret ml5"></span>
                            </button>
                            <ul class="dropdown-menu pull-right" role="menu">
                                <li>
                                    <!--<a target="_blank" href="/purchaseorder/printpreview" class="actionsPo ccursor" id="Print Preview_<?php //echo $loggedinUser."_".$pr_first_detail['po_id']; ?>"><i class="fa fa-print"></i> Print Preview</a>-->
                                </li>
                                <!--<li>
                                    <a class="actionsPo ccursor"  id="Print Preview_<?php //echo $pr_first_detail['po_id']; ?>"><i class="fa fa-files-o"></i> Print Preview </a>
                                </li>-->

                                <?php if (canuser('advance', 'view_attachment_po')) {?>
                                <li>
                                    <a href="#" id="attachDoc"><i class="fa fa-files-o"></i>
                                        <?php echo trans('label.lbl_attachdocuments'); ?></a>
                                </li>
                                <?php }?>
                                <li>
                                    <?php if ($pr_first_detail['status'] == "pending approval" || $pr_first_detail['status'] == "approved" || $pr_first_detail['status'] == "ordered") {
            if (canuser('advance', 'cancel_po')) {
                ?>
                                <li>
                                    <a class="actionsPo ccursor"
                                        id="cancel_<?php echo $loggedinUser . "_" . $pr_first_detail['po_id']; ?>"><i
                                            class="fa fa-thumbs-down "></i>
                                        <?php echo trans('label.btn_cancel_po'); ?></a>
                                </li>
                                <?php }}
        if ($pr_first_detail['status'] == "approved" || $pr_first_detail['status'] == "ordered" || $pr_first_detail['status'] == "partially received") {
            ?>

                                <?php if (canuser('advance', 'order_po')) {?>
                                <li>
                                    <a class="actionsPo ccursor"
                                        id="order_<?php echo $loggedinUser . "_" . $pr_first_detail['po_id']; ?>"><i
                                            class="fa fa-check-square-o "></i>
                                        <?php echo trans('label.btn_order_po'); ?></a>
                                </li>
                                <?php }?>
                                <?php if (canuser('advance', 'receive_items_po')) {?>
                                <li>
                                    <a class="actionsPo ccursor"
                                        id="received_<?php echo $loggedinUser . "_" . $pr_first_detail['po_id']; ?>"><i
                                            class="glyphicons glyphicons-list"></i>
                                        <?php echo trans('label.btn_recive_items'); ?></a>
                                </li>
                                <?php }}
        if ($pr_first_detail['status'] == "pending approval" || $pr_first_detail['status'] == "approved") {
            if (canuser('delete', 'purchaseorder')) {
                ?>
                                <li>
                                    <a class="actionsPo ccursor" class="actionsPo"
                                        id="delete_<?php echo $loggedinUser . "_" . $pr_first_detail['po_id']; ?>"><i
                                            class="fa fa-trash"></i> <?php echo trans('label.btn_delete_po'); ?></a>
                                </li>
                                <?php }}
        if ($pr_first_detail['status'] == "pending approval" || $pr_first_detail['status'] == "approved") {
            if (canuser('advance', 'close_po')) {
                ?>
                                <li>
                                    <a class="actionsPo ccursor" class="actionsPo"
                                        id="close_<?php echo $loggedinUser . "_" . $pr_first_detail['po_id']; ?>"><i
                                            class="fa fa-close"></i> <?php echo trans('label.btn_close_po'); ?></a>
                                </li>
                                <?php
}}
        if (canuser('create', 'invoice_po')) {
            if (!in_array($pr_first_detail['status'], $status)) {
                ?>
                                <li>
                                    <a class="actionsPo ccursor" class="actionsPo"
                                        id="invoice_<?php echo $loggedinUser . "_" . $pr_first_detail['po_id']; ?>_add"><i
                                            class="fa fa-file"></i> <?php echo trans('label.btn_add_invoice'); ?></a>
                                </li>
                                <?php }}?>
                                <!--<li class="divider"></li>-->
                                <?php if (canuser('advance', 'notify_owner_email') || canuser('advance', 'notify_vendor_email')) {?>
                                <li><strong><?php echo trans('label.lbl_notify'); ?></strong></li>
                                <?php if (canuser('advance', 'notify_owner_email')) {?>
                                <li>
                                    <a class="ccursor actionsPo"
                                        id="notifyowner_<?php echo $loggedinUser . "_" . $pr_first_detail['po_id']; ?>"><i
                                            class="fa fa-share"></i> <?php echo trans('label.lbl_emailowner'); ?></a>
                                </li>
                                <?php }?>
                                <?php if (canuser('advance', 'notify_vendor_email')) {?>
                                <li>
                                    <a class="ccursor actionsPo"
                                        id="notifyvendor_<?php echo $loggedinUser . "_" . $pr_first_detail['po_id']; ?>"><i
                                            class="fa fa-share"></i> <?php echo trans('label.lbl_emailvender'); ?></a>
                                </li>
                                <?php }}?>
                            </ul>
                            <!-- Modal -->
                            <div id="myModal_actions" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <form class="form-horizontal" id="prformActions">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close"
                                                    data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">
                                                    <span id="modal-title_actions">
                                                        <?php echo trans('label.lbl_canceldeleteclose'); ?></span> :
                                                    <?php echo isset($pr_first_detail['po_name']) ? $pr_first_detail['po_name'] : ""; ?>
                                                </h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="hidden alert-dismissable" id="msg_modal"></div>
                                                    </div>
                                                </div>
                                                <input type="hidden" id="pr_po_id" name="pr_po_id">
                                                <input type="hidden" id="pr_po_type" name="pr_po_type" value="po">
                                                <input type="hidden" id="user_id" name="user_id">
                                                <input type="hidden" id="action" name="action">
                                                <input type="hidden" id="notify_to_id" name="notify_to_id">
                                                <div class="checkbox-custom checkbox-info mb5">
                                                    <input type="checkbox" class="selectDeselectAll"
                                                        id="enableMailNotificationCheck" name="mail_notification"
                                                        value="y">
                                                    <label for="enableMailNotificationCheck"><strong>
                                                            <?php echo trans('label.lbl_sendmailnotification'); ?></strong></label>
                                                </div>
                                                <!--<div class="form-group">
                                            <div class="col-md-12">
                                                <p>Are you Sure you want to <span id="modal-title_actions_2">Cancel / Delete / Close</span> this PO : <strong><?php //echo $pr_first_detail['details']['pr_title']; ?></strong> ? </p>
                                            </div>
                                        </div>-->
                                                <div class="form-group required enableMailNotification"
                                                    style="display:none;">
                                                    <label for="inputStandard"
                                                        class="col-md-12 control-label textalignleft"><?php echo trans('label.lbl_to'); ?>
                                                    </label>
                                                    <div class="col-md-12">
                                                        <input class="col-md-12 form-control"
                                                            name="mail_notification_to">
                                                    </div>
                                                </div>
                                                <div class="form-group required enableMailNotification"
                                                    style="display:none;">
                                                    <label for="inputStandard"
                                                        class="col-md-12 control-label textalignleft"><?php echo trans('label.lbl_subject'); ?>
                                                    </label>
                                                    <div class="col-md-12">
                                                        <input class="col-md-12 form-control"
                                                            name="mail_notification_subject">
                                                    </div>
                                                </div>
                                                <div class="form-group required ">
                                                    <label for="inputStandard"
                                                        class="col-md-12 control-label textalignleft"><?php echo trans('label.lbl_description_comment'); ?>
                                                    </label>
                                                    <div class="col-md-12">
                                                        <textarea class="col-md-12 form-control"
                                                            name="comment"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" id="submitAction"
                                                    class="btn btn-success"><?php echo trans('label.btn_submit'); ?></button>
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal"><?php echo trans('label.btn_close'); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div id="myModal_actions_invoice" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <form class="form-horizontal" id="prformActionsInvoice">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close"
                                                    data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">
                                                    <span
                                                        id="modal-title_actions"><?php echo trans('label.btn_add_invoice'); ?>
                                                    </span> :
                                                    <?php echo isset($pr_first_detail['po_name']) ? $pr_first_detail['po_name'] : ""; ?>
                                                </h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="hidden alert-dismissable" id="Invoicemsg_modal">
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" id="pr_po_id" name="pr_po_id">
                                                <input type="hidden" id="invoice_id" name="invoice_id">
                                                <input type="hidden" id="pr_po_type" name="pr_po_type" value="po">
                                                <input type="hidden" id="user_id" name="user_id">
                                                <input type="hidden" id="action" name="action">
                                                <input type="hidden" id="formaction" name="formaction">
                                                <div class="form-group required">
                                                    <label for="inputStandard"
                                                        class="col-md-12 control-label textalignleft">
                                                        <?php echo trans('label.lbl_invoice_id'); ?>
                                                    </label>
                                                    <div class="col-md-12">
                                                        <input class="col-md-12 form-control input-sm" id="id"
                                                            name="id">
                                                    </div>
                                                </div>
                                                <div class="col-md-6 form-group required">
                                                    <label for="inputStandard"
                                                        class="col-md-12 control-label textalignleft"><?php echo trans('label.lbl_received_date'); ?>
                                                    </label>
                                                    <input class="col-md-12 form-control input-sm" id="received_date"
                                                        name="received_date" readonly='true'>
                                                </div>
                                                <div class="col-md-6 form-group required pull-right">
                                                    <label for="inputStandard"
                                                        class="col-md-12 control-label textalignleft"><?php echo trans('label.lbl_pay_due_date'); ?>
                                                    </label>
                                                    <input class="col-md-12 form-control input-sm" id="payment_due_date"
                                                        name="payment_due_date" readonly='true'>
                                                </div>
                                                <div class="col-md-6 form-group required ">
                                                    <label for="inputStandard"
                                                        class="col-md-12 control-label textalignleft">Upload Invoice
                                                    </label>
                                                    <input type="file" class="col-md-12 form-control input-sm"
                                                        id="invoice_file" name="invoice_file">
                                                </div>
                                                <div class="form-group required ">
                                                    <label for="inputStandard"
                                                        class="col-md-12 control-label textalignleft"><?php echo trans('label.lbl_comment'); ?>
                                                    </label>
                                                    <div class="col-md-12">
                                                        <textarea class="col-md-12 form-control input-sm" id="comment"
                                                            name="comment"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" id="submitActionInvoice"
                                                    class="btn btn-success"><?php echo trans('label.btn_submit'); ?>
                                                </button>
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal"><?php echo trans('label.btn_close'); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div id="myModal_actions_received" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <form class="form-horizontal" id="prformActionsreceived">
                                        <!-- Modal content-->
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close"
                                                    data-dismiss="modal">&times;</button>
                                                <h4 class="modal-title">
                                                    <span
                                                        id="modal-title_actions"><?php echo trans('label.btn_recive_items'); ?>
                                                    </span> : <?php echo isset($pr_first_detail['po_name']) ? $pr_first_detail['po_name'] : "";
        echo isset($pr_first_detail['po_no']) ? ": # " . $pr_first_detail['po_no'] : ""; ?>
                                                </h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="hidden alert-dismissable" id="receivedmsg_modal">
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" id="pr_po_id" name="pr_po_id">
                                                <input type="hidden" id="pr_po_type" name="pr_po_type" value="po">
                                                <input type="hidden" id="user_id" name="user_id">
                                                <input type="hidden" id="location_id" name="location_id"
                                                    value="<?php echo @$pr_first_detail['details']['location_id']; ?>">
                                                <input type="hidden" id="vendor_id" name="vendor_id"
                                                    value="<?php echo @$pr_first_detail['details']['pr_vendor']; ?>">
                                                <input type="hidden" id="bv_id" name="bv_id"
                                                    value="<?php echo @$pr_first_detail['details']['bv_id']; ?>">
                                                <input type="hidden" id="dc_id" name="dc_id"
                                                    value="<?php echo @$pr_first_detail['details']['dc_id']; ?>">
                                                <input type="hidden" id="action" name="action">
                                                <div class="row" id="invoice-table">
                                                    <div class="col-md-12">
                                                        <table class="table table-striped table-condensed">
                                                            <thead>
                                                                <tr id="labelRow" style="height:30px;">
                                                                    <th width="5%">#</th>
                                                                    <th><?php echo trans('label.lbl_items'); ?><span
                                                                            class="text-danger">*</span>
                                                                    </th>
                                                                    <th><?php echo trans('label.lbl_quantity'); ?><span
                                                                            class="text-danger">*</span>
                                                                        <?php echo trans('messages.msg_maxt_limit_to_receive_item_qty'); ?>
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
if ($assetdetails) {

            //foreach($assetdetails as $i => $asset)
            $i = 0; //Newly Added 6 Oct 2020
            foreach ($assetdetails as $asset) {
                $asset_details = json_decode($asset['asset_details'], true);

                //$received_qty       = isset($pr_first_detail['ci_received_qty'][$asset_details['item']]) ?  $pr_first_detail['ci_received_qty'][$asset_details['item']] : 0;

                $received_qty = isset($pr_first_detail['ci_asset_received_count'][$asset_details['item']]) ? $pr_first_detail['ci_asset_received_count'][$asset_details['item']] : 0;

                $actual_count = $asset_details['item_qty'];
                $item_remain_count = $asset_details['item_qty'] - $received_qty;

                if ($item_remain_count != 0) {
                    /*echo "<pre>";
                    print_r($asset_details);
                    echo "</pre>";*/
                    ?>
                                                                <tr>
                                                                    <td>
                                                                        <?php echo $i + 1; ?>
                                                                    </td>
                                                                    <td>
                                                                        <div class="checkbox-custom checkbox-info mb5">
                                                                            <input type="checkbox" class=""
                                                                                id="check_<?php echo $i; ?>"
                                                                                name="receiveitems[]"
                                                                                value="<?php echo $asset_details['item'] . "_" . $i; ?>">

                                                                            <label
                                                                                for="check_<?php echo $i; ?>"><?php echo @$asset_details['item_product_name']; ?></label>

                                                                        </div>
                                                                        <input type="hidden" class=""
                                                                            id="check_<?php echo $i; ?>"
                                                                            name="skucode[]"
                                                                            value="<?php echo @$asset_details['asset_sku'] ?>">
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-group">
                                                                            <div class="col-md-4">
                                                                                <input type="hidden"
                                                                                    class="form-control"
                                                                                    name="ci_type_id[]"
                                                                                    value="<?php echo @$pr_first_detail['ci_type_id_details'][$asset_details['item']]; ?>">

                                                                                <input type="hidden"
                                                                                    class="form-control" name="cutype[]"
                                                                                    value="<?php echo @$pr_first_detail['ci_cutype_details'][$asset_details['item']]; ?>">

                                                                                <input type="hidden"
                                                                                    class="form-control" name="title[]"
                                                                                    value="<?php echo @$pr_first_detail['ci_asset_details'][$asset_details['item']]; ?>">
                                                                                <input type="hidden"
                                                                                    class="form-control"
                                                                                    name="item_title[]"
                                                                                    value="<?php echo @$asset_details['item_product_name']; ?>">

                                                                                <input type="number"
                                                                                    class="item_receive_qty form-control"
                                                                                    name="itemqty[]" min="1"
                                                                                    value="<?php echo (int) $item_remain_count > 500 ? 500 : $item_remain_count; ?>"
                                                                                    max="<?php echo (int) $item_remain_count > 500 ? 500 : $item_remain_count; ?>">

                                                                                <input type="hidden" id="purchasecost"
                                                                                    name="purchasecost[]"
                                                                                    value="<?php echo !empty($asset_details['item_estimated_cost']) ? $asset_details['item_estimated_cost'] : ''; ?>">

                                                                                <input type="hidden" id="actualqty"
                                                                                    name="actualqty[]"
                                                                                    value="<?php echo $asset_details['item_qty']; ?>">
                                                                            </div>
                                                                            <!--<div class="col-md-8 pln">
                                                            <label for="inputStandard" class="col-md-12 control-label textalignleft"><?php //echo " / ".$asset_details['item_qty']; ?></label>
                                                        </div>-->
                                                                            <div class="col-md-8 pln">
                                                                                <label for="inputStandard"
                                                                                    class="col-md-12 control-label textalignleft"><?php echo " / " . $item_remain_count; ?></label>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <?php
$i++; //Newly Added 6 Oct 2020
                }
            }
        }
        ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" id="submitActionreceived"
                                                        class="btn btn-success">
                                                        <?php echo trans('label.btn_submit'); ?>
                                                    </button>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                                        <?php echo trans('label.btn_close'); ?>
                                                    </button>
                                                </div>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php }

    if (isset($pr_first_detail['status']) && !in_array($pr_first_detail['status'], $status)) {
        ?>
                        <div class="btn-group">
                            <button type="button" id="downloadPDF" class="downloadPDF  btn btn-default light"><i
                                    class="fa fa-download"></i> <?php echo trans('label.btn_download_po_pdf'); ?>
                            </button>
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
                        <!--   <p><b><?php echo trans('label.lbl_purchase_order'); ?> - </b></p> -->
                        <li class="purchase_ordertab active">
                            <a href="#purchase_order" data-toggle="tab" aria-expanded="false"><i
                                    class="fa fa-info-circle  text-purple"></i>
                                <?php echo trans('label.lbl_purchase_order'); ?>
                            </a>
                        </li>
                        <li class="">
                            <a href="#approvals" data-toggle="tab" aria-expanded="true" style="z-index:10;"><i
                                    class="fa fa-check-square-o  text-purple"></i>
                                <?php echo trans('label.lbl_approvals'); ?>
                            </a>
                        </li>
                        <?php if (canuser('view', 'invoice_po')) {if (!in_array($pr_first_detail['status'], $status)) {?>
                        <li class="">
                            <a href="#invoices" data-toggle="tab" aria-expanded="true"><i
                                    class="fa fa-file text-purple"></i>
                                <?php echo trans('label.lbl_invoice'); ?>
                            </a>
                        </li>
                        <?php }}?>
                        <?php if (canuser('advance', 'view_history')) {?>
                        <li class="">
                            <a href="#history" data-toggle="tab" aria-expanded="true"><i
                                    class="fa fa-history text-purple"></i> <?php echo trans('label.lbl_history'); ?>
                            </a>
                        </li>
                        <?php }?>
                        <li class="view_commenttab">
                            <a href="#pr_comment" data-toggle="tab" aria-expanded="true"><i
                                    class="fa fa-comment text-purple"></i> <?php echo 'Comments'; ?></a>

                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="purchase_order" class="tab-pane active">
                            <?php
//echo "<pre>"; print_r(@$pr_first_detail);  echo "</pre>"; ?>
                            <!-- Details START -->
                            <div class="panel invoice-panel">
                                <div class="panel-body p20" id="invoice-item">
                                    <!--  <div class="row mb30">
                                    <div class="col-md-12">
                                        <div class="pull-left">
                                         <h1 class="lh10 mt10"> INVOICE </h1>
                                            <h5 class="mn">
                                               PR Numbers: <?php if (!empty($pr_first_detail['pr_no'])) {
        foreach ($pr_first_detail['pr_no'] as $pr_numbers) {?>

                                                        <a style="text-decoration: none; cursor: pointer;color:darkgreen;font-weight: bold;" title="View Pr Details" id="pr_id_link" onClick="store_opp_prid(<?php echo "'" . $pr_numbers['pr_id'] . "'"; ?>)" ><?php if (isset($pr_numbers['pr_no'])) {echo $pr_numbers['pr_no'];} else {echo '-';}?></a>

                                                        //echo "<span data-pr_id='".$pr_numbers['pr_id']."'>".$pr_numbers['pr_no']."</span>&nbsp;&nbsp;&nbsp;";
                                                        // echo "<a href='".$pr_numbers['pr_id']."'>".$pr_numbers['pr_no']."</a>&nbsp;&nbsp;&nbsp;";
                                                        <?php
}
    }
    ?>
                                            </h5>
                                            <h5 class="mn">
                                                <?php echo trans('label.lbl_created'); ?> : <?php echo isset($pr_first_detail['details']['pr_req_date']) ? date("d F Y", strtotime($pr_first_detail['details']['pr_req_date'])) : ""; ?>
                                            </h5>
                                            <h5 class="mn"> <?php echo trans('label.lbl_status'); ?> : <b class="text-<?php echo $status_color; ?>"><?php echo isset($pr_first_detail['status']) ? ucwords($pr_first_detail['status']) : ""; ?></b> </h5>
                                        </div>
                                    </div>

                                </div> -->
                                    <div class="row" id="invoice-info">
                                        <div class="col-md-6">
                                            <div class="panel panel-alt">
                                                <div class="panel-heading">
                                                    <span class="panel-title">
                                                        <i class="fa fa-info"></i> PR & PO Details :
                                                    </span>
                                                    <div class="panel-btns pull-right ml10"></div>
                                                </div>
                                                <div class="panel-body">
                                                    <ul class="list-unstyled">
                                                        <li> <b>PR Numbers :</b><br>
                                                            <?php
if (!empty($pr_first_detail['pr_no'])) {
        $i = 1;
        foreach ($pr_first_detail['pr_no'] as $pr_numbers) {
            ?>
                                                            <a style="text-decoration: none; cursor: pointer;color:blue;font-weight: bold;"
                                                                title="View Pr Details" id="pr_id_link"
                                                                onClick="store_opp_prid(<?php echo "'" . $pr_numbers['pr_id'] . "'"; ?>)">
                                                                <?php
if (isset($pr_numbers['pr_no'])) {echo $i . '.&nbsp;' . $pr_numbers['pr_no'] . '<br>';}
            ?>
                                                            </a>
                                                            <?php $i++;}}
    ?>
                                                        </li>
                                                        <li>
                                                            <b><?php echo trans('label.lbl_created'); ?> :</b>
                                                            <?php echo isset($pr_first_detail['details']['pr_req_date']) ? date("d F Y", strtotime($pr_first_detail['details']['pr_req_date'])) : ""; ?>
                                                            | <b><?php echo trans('label.lbl_status'); ?> :</b>
                                                            <b
                                                                class="text-<?php echo $status_color; ?>"><?php echo isset($pr_first_detail['status']) ? ucwords($pr_first_detail['status']) : ""; ?></b>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="panel panel-alt">
                                                <div class="panel-heading">
                                                    <span class="panel-title">
                                                        <i class="fa fa-info"></i>
                                                        <?php echo trans('label.lbl_vendordetails'); ?> :
                                                    </span>
                                                    <div class="panel-btns pull-right ml10"></div>
                                                </div>
                                                <div class="panel-body">
                                                    <ul class="list-unstyled">
                                                        <li> <b><?php echo trans('label.lbl_vendor'); ?> :</b>
                                                            <?php echo isset($pr_first_detail['vendor_details']['vendor_name']) ? $pr_first_detail['vendor_details']['vendor_name'] : ""; ?>
                                                        </li>
                                                        <li>
                                                            <b><?php echo trans('label.lbl_contact_person'); ?> :</b>
                                                            <?php echo isset($pr_first_detail['vendor_details']['contact_person']) ? $pr_first_detail['vendor_details']['contact_person'] : ""; ?>
                                                        </li>
                                                        <li>
                                                            <b><?php echo trans('label.lbl_address'); ?> :
                                                            </b>
                                                            <?php echo isset($pr_first_detail['vendor_details']['address']) ? $pr_first_detail['vendor_details']['address'] : ""; ?>
                                                        </li>
                                                        <li>
                                                            <b><?php echo trans('label.lbl_phone'); ?> :
                                                            </b>
                                                            <?php echo isset($pr_first_detail['vendor_details']['contactno']) ? $pr_first_detail['vendor_details']['contactno'] : ""; ?>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="invoice-info">
                                        <div class="col-md-6">
                                            <div class="panel panel-alt">
                                                <div class="panel-heading">
                                                    <span class="panel-title"> <i class="fa fa-user"></i>
                                                        <?php echo trans('label.lbl_billto'); ?> :
                                                    </span>
                                                </div>
                                                <div class="panel-body">
                                                    <ul class="list-unstyled">
                                                        <li> <b><?php echo trans('label.lbl_company'); ?> :</b>
                                                            <?php echo isset($pr_first_detail['billto_details']['company_name']) ? $pr_first_detail['billto_details']['company_name'] : ""; ?>
                                                        </li>
                                                        <li style="text-align: justify;">
                                                            <b><?php echo trans('label.lbl_address'); ?> :</b>
                                                            <?php echo isset($pr_first_detail['billto_details']['address']) ? $pr_first_detail['billto_details']['address'] : ""; ?>
                                                        </li>
                                                        <li>
                                                            <b><?php echo trans('label.lbl_pan_no'); ?> :
                                                            </b>
                                                            <?php echo isset($pr_first_detail['billto_details']['pan_no']) ? $pr_first_detail['billto_details']['pan_no'] : ""; ?>
                                                        </li>
                                                        <li>
                                                            <b><?php echo trans('label.lbl_gstn'); ?> :
                                                            </b>
                                                            <?php echo isset($pr_first_detail['billto_details']['gstn']) ? $pr_first_detail['billto_details']['gstn'] : ""; ?>
                                                        </li>
                                                    </ul>


                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="panel panel-alt">
                                                <div class="panel-heading">
                                                    <span class="panel-title"> <i class="fa fa-location-arrow"></i>
                                                        <?php echo trans('label.lbl_shipto'); ?> :</span>
                                                    <!--<div class="panel-btns pull-right ml10">
                                                    <span class="panel-title-sm"> Edit</span>
                                                </div>-->
                                                </div>
                                                <div class="panel-body">
                                                    <!-- <address>
                                                    <b><?php echo trans('label.lbl_address'); ?> :</b>  <?php echo @$pr_first_detail['details']['shipping_address']; ?>
                                                </address> -->
                                                    <ul class="list-unstyled">
                                                        <?php
if (!empty($pr_first_detail['details']['ship_to_other'])) {
        echo '<li><strong>Other:</strong></li>
                                                                <li>' . $pr_first_detail['details']['ship_to_other'] . ' </li>';
    } else {
        ?>
                                                        <li> <b><?php echo trans('label.lbl_company'); ?> :</b>
                                                            <?php echo isset($pr_first_detail['shipto_details']['company_name']) ? $pr_first_detail['shipto_details']['company_name'] : ""; ?>
                                                        </li>
                                                        <li style="text-align: justify;">
                                                            <b><?php echo trans('label.lbl_address'); ?> :</b>
                                                            <?php echo isset($pr_first_detail['shipto_details']['address']) ? $pr_first_detail['shipto_details']['address'] : ""; ?>
                                                        </li>
                                                        <li>
                                                            <b><?php echo trans('label.lbl_pan_no'); ?> :
                                                            </b>
                                                            <?php echo isset($pr_first_detail['shipto_details']['pan_no']) ? $pr_first_detail['shipto_details']['pan_no'] : ""; ?>
                                                        </li>
                                                        <li>
                                                            <b><?php echo trans('label.lbl_gstn'); ?> :
                                                            </b>
                                                            <?php echo isset($pr_first_detail['shipto_details']['gstn']) ? $pr_first_detail['shipto_details']['gstn'] : ""; ?>
                                                        </li>
                                                        <?php
}
    ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row" id="invoice-info">

                                        <div class="col-md-6">
                                            <div class="panel panel-alt">
                                                <div class="panel-heading">
                                                    <span class="panel-title"> <i class="fa fa-user"></i>
                                                        <?php echo trans('label.lbl_billto_contact'); ?> :
                                                    </span>
                                                </div>
                                                <div class="panel-body">
                                                    <ul class="list-unstyled">
                                                        <li>
                                                            <b><?php echo trans('label.lbl_name'); ?> :</b>
                                                            <?php
$prefix = isset($pr_first_detail['billto_contact_details']['prefix']) ? $pr_first_detail['billto_contact_details']['prefix'] : "";
    $fname = isset($pr_first_detail['billto_contact_details']['fname']) ? $pr_first_detail['billto_contact_details']['fname'] : "";
    $lname = isset($pr_first_detail['billto_contact_details']['lname']) ? $pr_first_detail['billto_contact_details']['lname'] : "";
    echo $prefix . '. ' . $fname . ' ' . $lname;
    ?>
                                                        </li>
                                                        <?php if (!empty($pr_first_detail['billto_contact_details']['email'])) {?>
                                                        <li>
                                                            <b><?php echo trans('label.Email'); ?> :
                                                            </b>
                                                            <?php echo isset($pr_first_detail['billto_contact_details']['email']) ? $pr_first_detail['billto_contact_details']['email'] : ""; ?>
                                                        </li>
                                                        <?php }?>

                                                        <?php if (!empty($pr_first_detail['billto_contact_details']['contact1'])) {?>
                                                        <li>
                                                            <b><?php echo trans('label.lbl_contact'); ?> :
                                                            </b>
                                                            <?php echo isset($pr_first_detail['billto_contact_details']['contact1']) ? $pr_first_detail['billto_contact_details']['contact1'] : ""; ?>
                                                            <strong>/</strong>
                                                            <?php echo isset($pr_first_detail['billto_contact_details']['contact2']) ? $pr_first_detail['billto_contact_details']['contact2'] : ""; ?>
                                                        </li>
                                                        <?php }?>
                                                    </ul>


                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="panel panel-alt">
                                                <div class="panel-heading">
                                                    <span class="panel-title"> <i class="fa fa-location-arrow"></i>
                                                        <?php echo trans('label.lbl_shipto_contact'); ?> :</span>
                                                </div>
                                                <div class="panel-body">
                                                    <ul class="list-unstyled">
                                                        <li><?php
if (!empty($pr_first_detail['details']['ship_to_other'])) {
        echo '<b>Other Contact: </b>' . $pr_first_detail['details']['ship_to_contact_other'];
    } else {
        $prefix = isset($pr_first_detail['shipto_contact_details']['prefix']) ? $pr_first_detail['shipto_contact_details']['prefix'] : "";
        $fname = isset($pr_first_detail['shipto_contact_details']['fname']) ? $pr_first_detail['shipto_contact_details']['fname'] : "";
        $lname = isset($pr_first_detail['shipto_contact_details']['lname']) ? $pr_first_detail['shipto_contact_details']['lname'] : "";
        echo '<b>Name :</b>' . $prefix . '. ' . $fname . ' ' . $lname;
    }
    ?>
                                                        </li>
                                                        <?php if (!empty($pr_first_detail['shipto_contact_details']['email'])) {?>
                                                        <li>
                                                            <b><?php echo trans('label.Email'); ?> :
                                                            </b>
                                                            <?php echo isset($pr_first_detail['shipto_contact_details']['email']) ? $pr_first_detail['shipto_contact_details']['email'] : ""; ?>
                                                        </li>
                                                        <?php }?>
                                                        <?php if (!empty($pr_first_detail['shipto_contact_details']['contact1'])) {?>
                                                        <li>
                                                            <b><?php echo trans('label.lbl_contact'); ?> :
                                                            </b>
                                                            <?php echo isset($pr_first_detail['shipto_contact_details']['contact1']) ? $pr_first_detail['shipto_contact_details']['contact1'] : ""; ?>
                                                            <strong>/</strong>
                                                            <?php echo isset($pr_first_detail['shipto_contact_details']['contact2']) ? $pr_first_detail['shipto_contact_details']['contact2'] : ""; ?>
                                                        </li>
                                                        <?php }?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row" id="invoice-table">
                                        <div class="col-md-12">
                                            <table class="table table-striped table-condensed"
                                                style="height:30px;background-color:aliceblue;">
                                                <thead>
                                                    <tr id="labelRow" style="height:30px;">
                                                        <th class="text-center" width="5%">Sr</th>
                                                        <th class="text-center" width="20%">
                                                            <?php echo trans('label.lbl_itemname'); ?>
                                                        </th>
                                                        <th class="text-center" width="25%">
                                                            <?php echo trans('label.lbl_description'); ?>
                                                        </th>
                                                        <th class="text-center" width="5%">Qty</th>
                                                        <th class="text-center" width="5%">Unit</th>
                                                        <th class="text-center" width="5%">
                                                            <?php echo trans('label.lbl_received'); ?></th>
                                                        <th class="text-center" width="25%">Address</th>
                                                        <th class="text-center" width="10%">
                                                            <?php echo trans('label.lbl_estimated'); ?>
                                                            <br> <?php echo trans('label.lbl_cost'); ?>&nbsp;<span
                                                                id="itemEstimatedCost"></span>
                                                        </th>
                                                        <th class="text-center" width="10%" class="textalignright">
                                                            <?php echo trans('label.lbl_total'); ?>&nbsp;<span
                                                                id="itemTotalCost">(&#8377;)
                                                            </span>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
$total_cost = 0;
    $sub_total_cost = 0;
    $discount_per = 0;
    $discount_amount = 0;
    if ($assetdetails) {
        
        $other_details = json_decode($pr_first_detail['other_details'], true);

        $discount_amount = isset($other_details['discount_amount']) ? $other_details['discount_amount'] : 0;

        $discount_per = isset($other_details['discount_per']) ? $other_details['discount_per'] : 0;
        foreach ($assetdetails as $i => $asset) {
            $asset_details = json_decode($asset['asset_details'], true);
            
            //$total = 2 * $asset_details['item_qty'];
            $total = 0;
            if (!empty($asset_details['item_estimated_cost'])) {
                $total = $asset_details['item_estimated_cost'] * $asset_details['item_qty'];

            }

            $sub_total_cost = $sub_total_cost + $total;

            ?>
                                                    <tr>
                                                        <td>
                                                            <b><?php echo $i + 1; ?></b>
                                                        </td>
                                                        <td>
                                                            <?php /*echo @$pr_first_detail['ci_asset_details'][$asset_details['item']]; ?>(<?php echo @$pr_first_detail['ci_asset_details'][$pr_first_detail['ci_asset_details'][$asset_details['item']]]; )*/?>
                                                            <?php echo $asset_details['item_product_name']; ?>(<?php echo $asset_details['asset_sku']; ?>)

                                                        </td>
                                                        <td>
                                                            <?php echo $asset_details['item_desc']; ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php echo $asset_details['item_qty']; ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php echo empty($itemunit_sku[$asset_details['asset_sku']]) ? 'NA' : $itemunit_sku[$asset_details['asset_sku']]; ?>
                                                        </td>
                                                        <td class="textalignright">
                                                            <?php //echo isset($pr_first_detail['ci_received_qty'][$asset_details['item']]) ? $pr_first_detail['ci_received_qty'][$asset_details['item']] : 0; ?>

                                                            <span class="text-primary textalignright"
                                                                style="cursor:pointer;"
                                                                title="<?php echo trans('label.lbl_showassets'); ?>"
                                                                onclick='show_received_assetlist("<?php echo @$pr_first_detail['ci_asset_details'][$asset_details['item']]; ?>","<?php echo @$pr_first_detail['ci_type_id_details'][$asset_details['item']]; ?>","<?php echo $asset_details['item']; ?>","<?php echo $pr_first_detail['po_id']; ?>");'>

                                                                <?php echo isset($pr_first_detail['ci_asset_received_count'][$asset_details['item_product']]) ? $pr_first_detail['ci_asset_received_count'][$asset_details['item_product']] : 0; ?>

                                                            </span>
                                                        </td>
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
                                                        <td class="textalignright">
                                                            <?php
// echo number_format((float) 12345, 2, '.', '');
            echo !empty($asset_details['item_estimated_cost']) ?
            number_format((float) $asset_details['item_estimated_cost'], 2, '.', '') : '';
            ?>
                                                        </td>
                                                        <td class="text-right pr10 textalignright">
                                                            <?php //echo number_format((float) $total, 2, '.', ''); ?>
								<?php echo number_format((float) @$total, 2, '.', ','); ?>

                                                        </td>
                                                    </tr>
                                                    <?php
}
    }
    if ($discount_per != 0) {
        $total_cost = $sub_total_cost - ($sub_total_cost * $discount_per / 100);
    } elseif ($discount_amount) {
        $total_cost = $sub_total_cost - $discount_amount;
    } else {
        $total_cost = $sub_total_cost;
    }
    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row" id="invoice-footer">
                                        <div class="col-md-12">
                                            <div class="pull-left mt20 fs15 text-info">
                                                <?php echo trans('messages.msg_buss_thanks'); ?>
                                            </div>
                                            <div class="pull-right">
                                                <table class="table" id="invoice-summary">
                                                    <!--  <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                    </tr>
                                                </thead> -->
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <b><?php echo trans('label.lbl_subtotal'); ?> :</b>
                                                            </td>
                                                            <td align="right">
                                                                <?php //echo number_format((float) @$sub_total_cost, 2, '.', ''); ?>
								<?php echo number_format((float) @$sub_total_cost, 2, '.', ','); ?>

                                                            </td>
                                                        </tr>
                                                        <?php if ($discount_amount) {?>
                                                        <tr>
                                                            <td><b><?php echo trans('label.lbl_discount'); ?> :</b>
                                                            </td>
                                                            <td align="right">
                                                                <?php echo number_format((float) @$discount_amount, 2, '.', ''); ?>
                                                            </td>
                                                        </tr>
                                                        <?php } elseif ($discount_per) {?>
                                                        <tr>
                                                            <td>
                                                                <b><?php echo trans('label.lbl_discount'); ?> (%) :
                                                                </b>
                                                            </td>
                                                            <td align="right">
                                                                <?php echo number_format((float) @$discount_per, 2, '.', ''); ?>
                                                            </td>
                                                        </tr>
                                                        <?php }?>
                                                        <tr>
                                                            <td><b><?php echo trans('label.lbl_total'); ?> :</b>
                                                            </td>
                                                            <td align="right">
                                                               
								<?php echo number_format((float) @$total_cost, 2, '.', ','); ?>

                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if (canuser('advance', 'view_attachment_po')) {
        ?>
                                <div id="attachment_details" class="col-md-12 pt10 pln prn">
                                    <div class="panel">
                                        <div class="panel-heading">
                                            <span class="panel-icon"><i class="fa fa-list"></i></span>
                                            <span class="panel-title">
                                                <?php echo trans('label.lbl_attachmentdetails'); ?>
                                            </span>
                                            <div class="widget-menu pull-right">

                                            </div>
                                        </div>
                                        <div class="panel-body pn">
                                            <?php if (canuser('advance', 'add_attachment_po')) {?>
                                            <div class="col-sm-8 pt10 pl30">
                                                <div class="tray-bin pl10 mb10">
                                                    <!--<form action="/add_attachment_po" method="post" class="dropzone dropzone-sm" id="dropZone" enctype="multipart/form-data">-->
                                                    <form action="/add_attachment_po" method="post" class=""
                                                        id="dropZone" enctype="multipart/form-data">
                                                        <div class="fallback">
                                                            <input name="file[]" id="uploadFile" type="file"
                                                                multiple='multiple' />
                                                            <input type="hidden" id="pr_po_id" name="pr_po_id"
                                                                value="<?php echo isset($pr_first_detail['po_id']) ? $pr_first_detail['po_id'] : ""; ?>">
                                                            <input type="hidden" id="type" name="type" value="document">
                                                            <input type="hidden" id="attachment_type"
                                                                name="attachment_type" value="po">
                                                        </div>
                                                        <?php //echo csrf_field(); ?>
                                                        <input type="hidden" name="_token"
                                                            value="<?php echo csrf_token() ?>">
                                                        <input type="submit" id="attachmentbtn" value="Upload"
                                                            name="submit">
                                                    </form>
                                                </div>
                                            </div>
                                            <?php }?>
                                            <!-- begin: .tray-center -->
                                            <div class="col-sm-12 pl30">
                                                <div class="tray tray-center pn">
                                                    <table class="table table-striped table-condensed">
                                                        <thead>
                                                            <th>#</th>
                                                            <th>
                                                                <?php echo trans('label.lbl_file'); ?>
                                                            </th>
                                                            <th>
                                                                <?php echo trans('label.lbl_date'); ?>
                                                            </th>
                                                            <?php if (canuser('advance', 'delete_attachment_po')) {?>
                                                            <th>
                                                                <?php echo trans('label.lbl_delete'); ?>
                                                            </th>
                                                            <?php }?>
                                                        </thead>
                                                        <tbody>
                                                            <?php
if ($prpoattachment) {
            foreach ($prpoattachment as $key => $attachment) {
                $delete = '<span title = "' . trans('messages.msg_clicktodelete') . '" type="button" id="' . $attachment['attach_id'] . '" data-id="' . $attachment['attach_id'] . '" class="deleteAttachment"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';
                ?>
                                                            <tr>
                                                                <td>
                                                                    <?php echo $key + 1; ?>
                                                                </td>

                                                                <!--
                                                                <td><?php echo "<a target='_blank' href='" . config('enconfig.itamservice_url') . $attachment['attachment_name'] . "'>Attachment " . ($key + 1) . "</a>"; ?>
                                                                </td>
                                                                -->

                                                                <td>
                                                                    <span class="download_file text-primary"
                                                                        download_id="<?php echo $attachment['attach_id']; ?>"
                                                                        style="cursor:pointer;"
                                                                        title="<?php echo trans("label.lbl_viewdownload"); ?>"
                                                                        download_path="<?php echo $attachment['attachment_name']; ?>"><?php echo trans('label.lbl_attachment') . ' ' . ($key + 1); ?></span>
                                                                </td>

                                                                <td><?php echo date("d M Y h:i A", strtotime($attachment['created_at'])); ?>
                                                                </td>
                                                                <?php if (canuser('advance', 'delete_attachment_po')) {?>
                                                                <td><?php echo $delete; ?>
                                                                </td>
                                                                <?php }?>
                                                            </tr>
                                                            <?php
}
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
                        <div id="approvals" class="tab-pane">
                            <!--  <div class="pull-left">
                            <p><b><?php echo trans('label.lbl_approvals'); ?> - </b></p>
                        </div> -->
                            <!--<?php
//if($pr_first_detail['status'] =="pending approval")
    // {
    ?>
                        <div class="pull-right">
                            Last Notified On : Mar 4, 2019 <a class="ccursor actionsPo" id="notifyagain_<?php //echo $loggedinUser."_".$pr_first_detail['po_id']; ?>"><strong> [ Notify Again ] </strong></a>
                        </div>
                        <?php //} ?>-->
                            <!-- Modal -->
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
                                                    </span> <?php echo trans('label.lbl_this_po'); ?> :
                                                    <?php echo isset($pr_first_detail['po_name']) ? $pr_first_detail['po_name'] : ""; ?>
                                                </h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="hidden alert-dismissable"
                                                            id="msg_modal_approve_reject"></div>
                                                    </div>
                                                </div>
                                                <input type="hidden" id="pr_po_type" name="pr_po_type" value='po'>
                                                <input type="hidden" id="pr_po_id" name="pr_po_id">
                                                <input type="hidden" id="user_id" name="user_id">
                                                <input type="hidden" id="approval_status" name="approval_status">
                                                <input type="hidden" id="confirmed_optional" name="confirmed_optional">
                                                <div class="form-group required ">
                                                    <label for="inputStandard"
                                                        class="col-md-12 control-label textalignleft"><?php echo trans('label.lbl_comment'); ?>
                                                    </label>
                                                    <div class="col-md-12">
                                                        <textarea class="col-md-12" name="comment"></textarea>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="modal-footer">

                                                <button type="button" id="submitComment"
                                                    class="btn btn-success"><?php echo trans('label.btn_submit'); ?>
                                                </button>
                                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                                    <?php echo trans('label.btn_close'); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <br>
                            <div class="col-md-12">
                                <div style="color: red; font-style: italic;">Note: Once click on the <strong>Not Approve
                                        button</strong>, no further actions can be performed on the PO.</div>
                                <table class="table mbn tc-med-1 tc-bold-last tc-fs13-last">
                                    <thead style="height:30px;background-color:aliceblue;">
                                        <th class="textaligncenter">
                                            <?php echo trans('label.lbl_confirmed'); ?>
                                        </th>
                                        <th class="textaligncenter">
                                            <?php echo trans('label.lbl_status'); ?>
                                        </th>
                                        <th class="textaligncenter">
                                            Action
                                        </th>
                                    </thead>
                                    <tbody>
                                        <?php
$approved_status = json_decode($pr_first_detail['approved_status'], true);
    if (isset($pr_first_detail['approval_details_by_data']['confirmed']) && !empty($pr_first_detail['approval_details_by_data']['confirmed'])) {
        foreach ($pr_first_detail['approval_details_by_data']['confirmed'] as $user) {
            ?>
                                        <tr>
                                            <td><i class="fa fa-circle text-warning fs8 pr15"></i>
                                                <span style="color: black">
                                                    <?php
if (isset($user['firstname'])) {
                $fname = $user['firstname'];
            } else {
                $fname = '';
            }

            if (isset($user['lastname'])) {
                $lname = $user['lastname'];
            } else {
                $lname = '';
            }

            echo $fname . " " . $lname;
            ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-xs-4 pull-right">
                                                        <?php
//if($pr_first_detail['status'] =="pending approval")
            if (isset($user['user_id']) && !isset($approved_status['confirmed'][$user['user_id']]) && ($pr_first_detail['status'] != "rejected" && $pr_first_detail['status'] != "hold")) {
                ?>
                                                        <div class="pull-right">
                                                            <a class="ccursor actionsPo" id="notifyagain_<?php if (isset($user['user_id']) && isset($pr_first_detail['po_id'])) {
                    echo $loggedinUser . "_" . $pr_first_detail['po_id'] . "_" . $user['user_id'];
                }
                ?>"><strong> [ <?php echo trans('label.lbl_notifyagain'); ?> ] </strong>
                                                            </a>
                                                        </div>
                                                        <?php }?>
                                                    </div>
                                                    <?php
if (isset($user['user_id']) && !isset($approved_status['confirmed'][$user['user_id']]) && ($pr_first_detail['status'] != "rejected" && $pr_first_detail['status'] != "hold") && $user['user_id'] == showuserid()) {
                list($role_id) = json_decode(session()->get('role_id'), true);
                $approved_cnt = 2;
                if ($role_id === 'ef09b146-e63a-11ec-81c8-86bd6599c53f' && !empty($approved_status['confirmed'])) {
                    $approved_cnt = count(array_filter(array_values($approved_status['confirmed']), function ($arr) {
                        return $arr == 'approved';
                    }));
                }

                if (canuser('advance', 'approve_reject_po')) {
                    if ($approved_cnt >= 2) {
                        ?>

                                                    <div class="col-xs-2 pull-right1">
                                                        <div class="btn-group approve">

                                                            <button style="margin: 10px;" id="approved_<?php if (isset($user['user_id']) && isset($pr_first_detail['po_id'])) {
                            echo $user['user_id'] . "_" . $pr_first_detail['po_id'] . "_confirmed";
                        }
                        ?>" type="button" class="btn btn-default"><i class="glyphicons glyphicons-check"></i>
                                                                <?php echo trans('label.lbl_approve'); ?>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-2 pull-right1">
                                                        <div class="btn-group hold">
                                                            <button style="margin: 10px;" id="hold_<?php if (isset($user['user_id']) && isset($pr_first_detail['po_id'])) {
                            echo $user['user_id'] . "_" . $pr_first_detail['po_id'] . "_confirmed";
                        }
                        ?>" type="button" class="btn btn-default"><i class="glyphicon glyphicon-pause"></i> Hold
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-2 pull-right1">
                                                        <div class="btn-group reject">
                                                            <button style="margin: 10px;" id="rejected_<?php if (isset($user['user_id']) && isset($pr_first_detail['po_id'])) {
                            echo $user['user_id'] . "_" . $pr_first_detail['po_id'] . "_confirmed";
                        }

                        ?>" type="button" class="btn btn-default"><i class="glyphicons glyphicons-remove"></i> Not
                                                                Approve <?php //echo trans('label.lbl_reject'); ?>

                                                            </button>
                                                        </div>
                                                    </div>


                                                </div>
                            </div>

                            <?php
}}} elseif (isset($user['user_id']) && !isset($approved_status['confirmed'][$user['user_id']]) && ($pr_first_detail['status'] != "rejected" && $pr_first_detail['status'] != "hold") && $user['user_id'] != showuserid()) {
    if(showuserid() == "7117a498-41c3-11ea-9e9a-0242ac110003")
    {
        // echo trans('label.lbl_pendingapproval') . "1";
        ?>
<div class="col-xs-2 pull-right1">
    <div class="btn-group approve">
    <button style="margin: 10px;" id="approved_<?php if (isset($user['user_id']) && isset($pr_first_detail['po_id'])) {
    echo $user['user_id'] . "_" . $pr_first_detail['po_id'] . "_confirmed";
    }
    ?>" type="button" class="btn btn-default"><i class="glyphicons glyphicons-check"></i>
    <?php echo trans('label.lbl_approve'); ?>
    </button>
    </div>
</div>
<div class="col-xs-2 pull-right1">
    <div class="btn-group hold">
    <button style="margin: 10px;" id="hold_<?php if (isset($user['user_id']) && isset($pr_first_detail['po_id'])) {
    echo $user['user_id'] . "_" . $pr_first_detail['po_id'] . "_confirmed";
    }
    ?>" type="button" class="btn btn-default"><i class="glyphicon glyphicon-pause"></i> Hold
    </button>
    </div>
</div>
        <?
    }else{
    echo trans('label.lbl_pendingapproval');
    }
    
        
            }
            if (isset($user['user_id']) && isset($approved_status['confirmed'][$user['user_id']])) {
                echo '<div class="pull-right">';
                echo $approved_status['confirmed'][$user['user_id']] == "rejected" && $pr_first_detail['status'] != "hold" ? '<i class="glyphicons glyphicons-remove"></i> ' . ucfirst($approved_status['confirmed'][$user['user_id']]) : '<i class="glyphicons glyphicons-check"></i> ' . ucfirst($approved_status['confirmed'][$user['user_id']]);
                $comment = "";
                $date = "";
                if (!empty($prpohistorylog)) {
                    foreach ($prpohistorylog as $history) {
                        //if($history['created_by'] == $user['user_id'])
                        if (isset($user['user_id']) && isset($history['created_by']) && $history['created_by'] == $user['user_id']) {
                            $comment = $history['comment'];
                            $date = date("d M Y h:i A", strtotime($history['created_at']));
                            break;
                        }
                    }
                }
                echo " On " . $date . "<div class='media'><p>Comment: " . $comment . "</p></div>";
            }
            /* if(isset( $approved_status['confirmed'][$user['user_id']])){
            echo $approved_status['confirmed'][$user['user_id']] == "rejected" ?'<i class="glyphicons glyphicons-remove"></i> '.ucfirst($approved_status['confirmed'][$user['user_id']]) : '<i class="glyphicons glyphicons-check"></i> '.ucfirst($approved_status['confirmed'][$user['user_id']]);
            $comment ="";
            $date="";
            if(!empty($prpohistorylog))
            {
            foreach($prpohistorylog as $history)
            {
            //if($history['created_by'] == $user['user_id'])
            if($history['created_by'] == $user['user_id'])
            {
            $comment = $history['comment'];
            $date = date("d M Y h:i A", strtotime($history['created_at']));
            break;
            }
            }
            }
            echo " On ".$date."<div class='media'><p>Comment: ".$comment."</p></div>";

            }*/
            ?>

                        </div>
                        </td>
                        <td>

                            <?php
if (canuser('advance', 'resendtoapproval')) {
                if (isset($user['user_id']) && isset($approved_status['confirmed'][$user['user_id']])) {

                    echo '<div class="text-center">';
                    echo $approved_status['confirmed'][$user['user_id']] == "hold" ? '<a href="javascript:void(0)" class="btn btn-info text-center resendtoapproval" data-userid="' . $user['user_id'] . '" data-poid="' . $pr_first_detail["po_id"] . '">Send to approval</a>' : '-';

                }}?>

                        </td>
                        </tr>
                        <?php
}
    } else {
        echo "<tr><td style='text-align: center;' colspan='2'>" . trans('messages.msg_norecordfound') . "</td></tr>";
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
                                <th class="textaligncenter"><?php echo trans('label.lbl_optional'); ?>
                                </th>
                                <th class="textaligncenter">
                                    <?php echo trans('label.lbl_status'); ?>
                                </th>
                            </thead>
                            <tbody>
                                <?php
if (isset($pr_first_detail['approval_details_by_data']['optional']) && !empty($pr_first_detail['approval_details_by_data']['optional'])) {
        foreach ($pr_first_detail['approval_details_by_data']['optional'] as $user) {
            ?>
                                <tr>
                                    <td><i class="fa fa-circle text-warning fs8 pr15"></i>
                                        <span style="color: black">
                                            <?php
if (isset($user['firstname'])) {
                $fname = $user['firstname'];
            } else {
                $fname = '';
            }

            if (isset($user['lastname'])) {
                $lname = $user['lastname'];
            } else {
                $lname = '';
            }

            echo $fname . " " . $lname;
            ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="row1">
                                            <div class="col-xs-5 pull-right">
                                                <?php
//if($pr_first_detail['status'] =="pending approval")
            if (isset($user['user_id']) && !isset($approved_status['optional'][$user['user_id']]) && $pr_first_detail['status'] != "rejected") {
                ?>
                                                <div class="pull-right">
                                                    <a class="ccursor actionsPo" id="notifyagain_<?php if (isset($user['user_id']) && isset($pr_first_detail['po_id'])) {
                    echo $loggedinUser . "_" . $pr_first_detail['po_id'] . "_" . $user['user_id'];
                }
                ?>">
                                                        <strong> [ <?php echo trans('label.lbl_notifyagain'); ?> ]
                                                        </strong>
                                                    </a>
                                                </div>
                                                <?php }?>
                                            </div>
                                            <?php
if (isset($user['user_id']) && !isset($approved_status['optional'][$user['user_id']]) && $pr_first_detail['status'] != "rejected" && $user['user_id'] == showuserid()) {
                ?>
                                            <?php if (canuser('advance', 'approve_reject_po')) {
                    ?>
                                            <div class="col-xs-3 pull-right">
                                                <div class="btn-group reject">
                                                    <button id="rejected_<?php if (isset($user['user_id']) && isset($pr_first_detail['po_id'])) {
                        echo $user['user_id'] . "_" . $pr_first_detail['po_id'] . "_optional";
                    }
                    ?>" type="button" class="btn btn-default"><i class="glyphicons glyphicons-remove"></i>
                                                        Not Approved<?php //echo trans('label.lbl_reject'); ?>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-xs-3 pull-right">
                                                <div class="btn-group approve">
                                                    <button id="approved_<?php if (isset($user['user_id']) && isset($pr_first_detail['po_id'])) {
                        echo $user['user_id'] . "_" . $pr_first_detail['po_id'] . "_optional";
                    }
                    ?>" type="button" class="btn btn-default">
                                                        <i class="glyphicons glyphicons-check"></i>
                                                        <?php echo trans('label.lbl_approve'); ?>
                                                    </button>
                                                </div>
                                            </div>
                                            <?php
}} elseif (isset($user['user_id']) && !isset($approved_status['optional'][$user['user_id']]) && $pr_first_detail['status'] != "rejected" && $user['user_id'] != showuserid()) {
                echo "Pending Approval";
            }
            if (isset($user['user_id']) && isset($approved_status['optional'][$user['user_id']])) {
                echo '<div class="pull-right">';
                echo $approved_status['optional'][$user['user_id']] == "rejected" ? '<i class="glyphicons glyphicons-remove"></i> ' . ucfirst($approved_status['optional'][$user['user_id']]) : '<i class="glyphicons glyphicons-check"></i> ' . ucfirst($approved_status['optional'][$user['user_id']]);

                $comment = "";
                $date = "";
                if (!empty($prpohistorylog)) {
                    foreach ($prpohistorylog as $history) {
                        //if($history['created_by'] == $user['user_id'])
                        if (isset($history['created_by']) && isset($user['user_id']) && $history['created_by'] == $user['user_id']) {
                            $comment = $history['comment'];
                            $date = date("d M Y h:i A", strtotime($history['created_at']));
                            break;
                        }
                    }
                }
                //echo " On ".$date."<div class='media'><p>Comment: ".$comment."</p></div>";
                //echo trans('messages.msg_ondatecomment', ['name' => $date,'comment' => $comment]);
                echo showmessage('msg_ondatecomment', array('{name}', '{comment}'), array($date, $comment)) . '</div>';
            }
            /*if(isset( $approved_status['optional'][$user['user_id']]))
            {
            echo $approved_status['optional'][$user['user_id']] == "rejected" ?'<i class="glyphicons glyphicons-remove"></i> '.ucfirst($approved_status['optional'][$user['user_id']]) : '<i class="glyphicons glyphicons-check"></i> '.ucfirst($approved_status['optional'][$user['user_id']]);
            $comment ="";
            $date="";
            if(!empty($prpohistorylog))
            {
            foreach($prpohistorylog as $history)
            {
            if($history['created_by'] == $user['user_id'])
            {
            $comment = $history['comment'];
            $date = date("d M Y h:i A", strtotime($history['created_at']));
            break;
            }
            }
            }
            echo " On ".$date."<div class='media'><p>Comment: ".$comment."</p></div>";
            }*/
            ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php
}
    } else {
        echo "<tr><td style='text-align: center;' colspan='2'>" . trans('messages.msg_norecordfound') . "</td></tr>";
    }
    ?>

                            </tbody>
                        </table>

                    </div>
                </div>
                <?php if (canuser('view', 'invoice_po')) {
        ?>
                <div id="invoices" class="tab-pane">
                    <div class="row" id="invoice-table">
                        <div class="col-md-12">
                            <table class="table table-striped table-condensed">
                                <thead style="height:30px;background-color:aliceblue;">
                                    <th>#</th>
                                    <th>
                                        <?php echo trans('label.lbl_invoice_id'); ?>
                                    </th>
                                    <th>
                                        <?php echo trans('label.lbl_received_date'); ?>
                                    </th>
                                    <th>
                                        <?php echo trans('label.lbl_pay_due_date'); ?>
                                    </th>
                                    <th> Invoice </th>
                                    <th>
                                        <?php echo trans('label.lbl_action'); ?>
                                    </th>
                                    <!--<th>Created By</th>-->
                                </thead>
                                <tbody>
                                    <?php

        if ($purchaseinvoices) {
            foreach ($purchaseinvoices as $key => $invoice) {
                $details = json_decode($invoice['details'], true);
                $id = $invoice['invoice_id'];
                $edit = $delete = '';

                if (canuser('update', 'invoice_po')) {
                    $edit = '<span title = "' . trans('messages.msg_clicktoedit') . '" id="invoice_' . $loggedinUser . '_' . $pr_first_detail['po_id'] . '_edit_' . $id . '" type="button"  class="actionsPo"  ><i class="fa fa-edit mr10 fa-lg"></i></span>';
                }
                if (canuser('delete', 'invoice_po')) {
                    $delete = '<span title = "' . trans('messages.msg_clicktodelete') . '" type="button" id="invoice_' . $loggedinUser . '_' . $pr_first_detail['po_id'] . '_delete_' . $id . '" data-invoiceid="' . $id . '" class="invoice_delete" ><i class="fa fa-trash-o mr10 fa-lg"></i></span>';
                }
                ?>
                                    <tr>
                                        <td><?php echo $key + 1; ?></td>
                                        <td><?php echo $details['id']; ?></td>
                                        <td><?php echo date("d F Y", strtotime($details['received_date'])); ?></td>
                                        <td><?php echo date("d F Y", strtotime($details['payment_due_date'])); ?></td>
                                        <td><?php
if (!empty($details['invoice_file_name'])) {
                    echo '<a href="' . $details['invoice_file_name'] . '">Download</a>';
                }?></td>
                                        <td><?php echo $edit . ' ' . $delete; ?></td>
                                        <!--<td><<?php //echo $invoice['created_by']; ?></td>-->
                                    </tr>
                                    <?php
}
        }
        ?>
                                </tbody>
                            </table>
                        </div>
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
            //echo "<pre>"; print_r($prpohistorylog);echo "</pre>";
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
$default = "glyphicons glyphicons-edit text-info";
                    $reason = "<br>";
                    if ($history['action'] == "pending approval") {
                        $default = "glyphicons glyphicons-check text-primary";
                        $reason .= "<strong>" . trans('label.lbl_reason') . " : </strong>" . $history['comment'];
                    }
                    if ($history['action'] == "approved") {
                        $default = "glyphicons glyphicons-check text-success";
                        $reason .= "<strong>" . trans('label.lbl_reason') . " : </strong>" . $history['comment'];
                    }
                    if ($history['action'] == "rejected") {
                        $default = "glyphicons glyphicons-remove text-danger";
                        $reason .= "<strong>" . trans('label.lbl_reason') . " : </strong>" . $history['comment'];
                    }
                    if ($history['action'] == "cancelled") {
                        $default = "glyphicons glyphicons-remove text-danger";
                        $reason .= "<strong>" . trans('label.lbl_reason') . " : </strong>" . $history['comment'];
                    }
                    if ($history['action'] == "notifyowner") {
                        $default = "fa fa-warning text-warning";
                        $reason .= "<strong>" . trans('label.lbl_reason') . " : </strong>" . $history['comment'];
                    }
                    if ($history['action'] == "notifyagain") {
                        $default = "fa fa-warning text-warning";
                        $reason .= "<strong>" . trans('label.lbl_reason') . " : </strong>" . $history['comment'];
                    }
                    if ($history['action'] == "ordered") {
                        $default = "fa fa-warning text-info";
                        $reason .= "<strong>" . trans('label.lbl_reason') . " : </strong>" . $history['comment'];
                    }
                    ?>
                                        <span class="<?php echo $default; ?>">
                                        </span>
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
                                                            <label for="inputStandard"
                                                                class="col-md-12 control-label textalignleft">
                                                                <?php echo trans('label.lbl_comment'); ?> </label>
                                                            <input type="hidden" id="pr_po_type_comment"
                                                                name="pr_po_type" value="pr">
                                                            <input type="hidden" id="pr_po_id_comment" name="pr_po_id">
                                                            <input type="hidden" id="user_id_comment" name="user_id">
                                                            <input type="hidden" id="action_comment" name="action">
                                                            <input type="hidden" id="notify_to_id_comment"
                                                                name="notify_to_id">
                                                            <input type="hidden" id="approval_status_comment"
                                                                name="approval_status" value="comment">
                                                            <input type="hidden" id="confirmed_optional_comment"
                                                                name="confirmed_optional" value="optional">
                                                            <input type="hidden" id="is_comment" name="is_comment"
                                                                value="yes">

                                                            <div class="col-md-12">
                                                                <textarea class="col-md-12" name="comment"
                                                                    maxlength="250" required></textarea>
                                                                <br>
                                                                <code
                                                                    style="float: inline-end;">(Max 250 Characters)</code>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="form-group">
                                                            <input type="hidden" id="pr_po_id" name="pr_po_id"
                                                                value="
                                        <?php echo isset($pr_first_detail['po_id']) ? $pr_first_detail['po_id'] : ""; ?>">
                                                        </div> <?php //echo csrf_field(); ?> <input type="hidden"
                                                            name="_token" value="
                                        <?php echo csrf_token() ?>">
                                                        <button style="margin-top:30px;" type="button"
                                                            id="pr_comment_submit"
                                                            class="btn btn-success comment_btn">Comment</button>
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
                                                            <strong>
                                                                <?php echo date("d M Y h:i A", strtotime($history['created_at'])); ?>
                                                            </strong>
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
jQuery(document).ready(function() {
    $(document).on('click', '.downloadPDF', function() {
        var win = window.open('/downloadPDF?po_id=<?php echo $pr_first_detail['po_id']; ?>', '_self');

    })
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
    var cid = "<?php echo "comments_" . $loggedinUser . "_" . $pr_first_detail['po_id']; ?>";

    var action = cid.split('_')[0];
    var user_id = cid.split('_')[1];
    var pr_id = c = cid.split('_')[2];
    var notify_to_id = cid.split('_')[3];

    $("#pr_po_id_comment").val(pr_id);
    $("#pr_po_type_comment").val("po");
    $("#user_id_comment").val(user_id);
    $("#action_comment").val(action);
    $("#notify_to_id_comment").val(notify_to_id);
});
</script>
<?php
} else {
    echo "<div class='row'><div class='panel-body'> <div class='col-md-12 textaligncenter'><strong>" . trans('messages.msg_norecordfound') . "</strong></div></div></div>";
}
?>
<script type="text/javascript">
function store_opp_prid(id) {
    localStorage.setItem("opp_pr_id", id);
    window.open('/purchaserequest', '_blank');

}
</script>