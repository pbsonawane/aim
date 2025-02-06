<link rel="stylesheet" type="text/css" href="enlight/scripts/formeo-master/css/demo.css">
<div class="row" id="credentialTypeform">
    <div class="col-md-10"> 
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div><?php     
    if( $formAction == "add")
    {
        $disabled = "readonly";
    }
    else{
        $disabled = "";
    }

    ?>
   <?php /* <div class="col-md-4 pull-right mr10 mv10 pv10" style="background:#FFFFCC;border:1px solid #e7e7e7">
    <form id="poOtherDetails" class="form-horizontal" action="post">
        <div class="form-group required ">
            <label for="inputStandard" class="col-md-4 control-label">
                <?php echo trans('label.lbl_po_no');?>
            </label>
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-addon">
                        <?php echo trans('label.lbl_po_no');?>
                    </span>
                    <?php
                    //as User can not Edit PO NO.
                    if(isset($purchaserequestdetail['po_no']))
                    {
                        echo '<span class="form-control input-sm" >'.$purchaserequestdetail['po_no'].'</span>';
                        echo '<input type="hidden" id="po_no" name="po_no" class="form-control input-sm" value="'.$purchaserequestdetail['po_no'].'">';
                    }
                    else
                    {
                        echo '<input type="text" id="po_no" name="po_no" class="form-control input-sm" value="">';
                    }
                    ?>                    
                </div>                                     
            </div>
        </div> 
        <div class="form-group required ">
            <label for="inputStandard" class="col-md-4 control-label">
                <?php echo trans('label.lbl_po_name');?>        
            </label>
            <div class="col-md-8">
                <?php 
                //as User can not Edit PO Name
                if(isset($purchaserequestdetail['po_name']))
                {
                    echo '<span class="form-control input-sm" >'.$purchaserequestdetail['po_name'].'</span>'; 
                    echo '<input type="hidden" id="po_name" name="po_name" class="form-control input-sm" value="'.$purchaserequestdetail['po_name'].'">';               
                }
                else
                {
                    echo '<input type="text" id="po_name" name="po_name" class="form-control input-sm" value="">';
                }
                ?>
            </div>
        </div>
        <div class="form-group ">
            <label for="inputStandard" class="col-md-4 control-label">
                <?php echo trans('label.lbl_po_status');?>        
            </label>
            <div class="col-md-8">
                 <span class="form-control input-sm" ><?php echo isset($purchaserequestdetail['status']) ? ucwords($purchaserequestdetail['status']) : '';  ?> </span>
            </div>
        </div>
    </form>
    </div> */?>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body purchaseRequestScroll">
            <div id="form-builder">
            <?php //if($pr_id == "" || $formAction == "edit"){ ?>
            <div class="form-group required clearfix">
                <section id="main_content" class="inner">
                    <form id="build-form" class="build-form clearfix">
                    </form>
                   
                    <form id="prBuilderForm" action="post">
                        
                        <div class="render-form"></div>
                    </form>
                </section>
                <div class="render-btn-wrap" >
                    <button id="renderForm" class="btn btn-outline-primary" ><?php echo trans('label.btn_previewform');?>
                    </button>
                    <button id="viewData" class="btn btn-outline-success"><?php echo trans('label.btn_generatejsondata');?>
                    </button>
                    <button id="reloadBtn" class="btn btn-outline-danger"><?php echo trans('label.btn_reseteditor');?>
                    </button>
                </div>
            </div>
            <?php //} ?>
            <input type="hidden" name="approved_vendor_id" id="approved_vendor_id" value="<?php echo !empty($vendors[0]);?>">
            <form id="prItemApproval" action="post">

                <input id="action" name="formAction" type="hidden" value="<?php echo $formAction    ; ?>">
                <input id="pr_po_type" name="pr_po_type" type="hidden" value="po">
                <input type="hidden" id="form_templ_id" name="form_templ_id" value="<?php echo $form_templ_data['form_templ_id']; ?>">
                <input type="hidden" id="pr_id" name="pr_id" value="<?php echo $pr_id; ?>">
                <input type="hidden" id="po_id" name="po_id" value="<?php echo $po_id; ?>">
                <div class="col-md-12">
                    <div class="panel"> 
                        <div class="panel-heading">
                            <span class="panel-icon"><i class="fa fa-list"></i>
                            </span>
                            <span class="panel-title">
                                <?php echo trans('label.lbl_itemdetails');?>
                            </span>
                            <span class="text-danger">*</span>
                            <div class="widget-menu pull-right">
                            </div>
                        </div>                        
                        <div class="panel-body pn">
                            <table id="purchaserequestadd" class="table addmore">
                                <thead>
                                    <tr class="info">
                                        <!--<th>#</th>-->
                                        <th>
                                        <?php echo trans('label.lbl_itemname');?>
                                        </th>
                                        <th>
                                        <?php echo trans('label.lbl_description');?></th>
                                        <th>
                                        <?php echo trans('label.lbl_quantity');?>
                                        </th>
                                        <th>
                                        <?php echo trans('label.lbl_estimatedcost');?>
                                        </th>
                                        <th>
                                        <?php echo trans('label.lbl_total');?>
                                        </th>
                                        <?php 
                                        if($formAction != "add" || $pr_id == "")
                                        {
                                        ?>
                                        <th width="1%"></th>
                                        <?php 
                                        } ?>
                                    </tr>
                                    <!--<tr>
                                        <td colspan="4"><a id="add_more_item">+ Add More Item</a></td>
                                        <td>Total Cost</td>
                                        <td></td>
                                    </tr>-->
                                </thead>
                                <tbody>
                                <?php 
                                    $sub_total_cost  = 0;    
                                    $total_cost      = 0;   
                                    $discount_per    = 0; 
                                    $discount_amount = 0;
                                    if(!empty($assetdetails))
                                    { 
                                        foreach($assetdetails as $key => $asset )
                                        {
                                            $asset_details = json_decode($asset['asset_details'], true);
                                            $vendor_approve = json_decode($asset['vendor_approve'], true);

                                        ?>
                                    <tr id="row-<?php echo ($key+1); ?>">
                                    <!--<td><?php //echo ($key+1); ?></td>-->
                                    <td>
                                    <div class="section">
                                        <!--<select class="chosen-select" tabindex="5" data-placeholder="Select Item"  class="form-control input-sm" name="item">-->
                                        <select <?php echo $disabled; ?> tabindex="5" data-placeholder="Select Item"  class="form-control input-sm" id="item-<?php echo ($key+1); ?>" name="item[]">
                                            <option value="">[Select Item]</option>
                                            <?php 
                                                if(isset($ciDetails) && !empty($ciDetails))
                                                {
                                                    foreach($ciDetails as $ci)
                                                    {
                                                        $selected = $asset_details['item'] == $ci['ci_templ_id'] ? $selected = "selected" : "";


                                                        echo "<option value='".$ci['ci_templ_id']."' ".$selected.">".$ci['ci_name']."</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    </td>
                                    <td>
                                        <textarea <?php echo $disabled; ?> rows="<?php echo ($key+1); ?>" id="item_desc-<?php echo ($key+1); ?>" name="item_desc[]" class="form-control" ><?php echo $asset_details['item_desc']; ?>        
                                        </textarea>
                                    </td>
                                    <td>
                                        <input <?php echo $disabled; ?> placeholder="Enter Quantity" type="text" onkeypress="return isNumberKey(event, this)" onkeyup="return onQtyEnter(event, this)" id="item_qty-<?php echo ($key+1); ?>" value="<?php echo $asset_details['item_qty']; ?>" name="item_qty[]" class="form-control input-sm textalignright" >
                                    </td>
                                    <td>
                                        <input <?php echo $disabled; ?> placeholder="Enter Estimated Cost" type="text" onkeypress="return isDecimalNumber(event, this)" onkeyup="return onCostEnter(event, this)" id="item_estimated_cost-<?php echo ($key+1); ?>" name="item_estimated_cost[]" value="<?php echo !empty($vendor_approve['amount'])?$vendor_approve['amount']:$asset_details['item_estimated_cost'];
                                         ?>" class="form-control input-sm textalignright">
                                    </td>
                                    <td> 
                                    <?php 
                                    if(isset($purchaserequestdetail['other_details']))
                                    {
                                        $other_details = json_decode($purchaserequestdetail['other_details'],true);
                                    }
                                    $discount_amount = isset($other_details['discount_amount']) ? $other_details['discount_amount'] : 0;
                                    $discount_per = isset($other_details['discount_per']) ? $other_details['discount_per'] : 0;
                                    if(!empty($vendor_approve['amount'])){
                                        $total = @$vendor_approve['amount'] * $asset_details['item_qty'];

                                    }else{

                                    $total = @$asset_details['item_estimated_cost'] * $asset_details['item_qty'];
                                    }

                                    $sub_total_cost = $sub_total_cost + $total;
                                     
                                    $total_cost = $total_cost + $total;
                                    ?>
                                    <input <?php echo $disabled; ?> placeholder="Total"class="form-control input-sm textalignright sum_item_estimated_cost" id="total-<?php echo ($key+1); ?>" name="total[]" disabled type="text" value="<?php echo $total; ?>" onkeypress="return isDecimalNumber(event, this)" >
                                    </td>  
                                    <?php 
                                    if($formAction != "add" || $pr_id == ""){
                                        ?>                                        
                                    <td>
                                        <?php
                                        if(($key+1) > 1){
                                        ?>
                                        <i class="fa fa-trash-o mr10 fa-lg remove"></i>
                                    <?php } ?>
                                    </td>  
                                    <?php 
                                        }
                                        ?>                                       
                                    </tr> 
                                    <?php
                                            }
                                        }
                                        else 
                                        {
                                    ?>
                                    <tr id="row-1">
                                        <!--<td>1</td>-->
                                        <td>
                                        <div class="section">
                                            <!--<select class="chosen-select" tabindex="5" data-placeholder="Select Item"  class="form-control input-sm" name="item">-->
                                            <select  tabindex="5" data-placeholder="Select Item"  class="form-control input-sm" id="item-1" name="item[]">
                                            <option value="">[<?php echo trans('label.lbl_selectitem');?>]</option>
                                            <?php 
                                                if(isset($ciDetails) && !empty($ciDetails))
                                                {
                                                    foreach($ciDetails as $ci)
                                                    {
                                                        echo "<option value='".$ci['ci_templ_id']."'>".$ci['ci_name']."</option>";
                                                    }
                                                }
                                            ?>
                                            </select>
                                        </div>
                                        </td>
                                        <td>
                                        <textarea rows="1" id="item_desc-1" name="item_desc[]" class="form-control" ></textarea>
                                        </td>
                                        <td>
                                            <input placeholder="<?php echo trans('label.lbl_enterquantity');?>" type="text" onkeypress="return isNumberKey(event, this)" onkeyup="return onQtyEnter(event, this)" id="item_qty-1" name="item_qty[]" class="form-control input-sm" >
                                        </td>
                                        <td>
                                            <input placeholder="<?php echo trans('label.lbl_enterestimatedcost');?>" type="text" onkeypress="return isDecimalNumber(event, this)" onkeyup="return onCostEnter(event, this)" id="item_estimated_cost-1" name="item_estimated_cost[]" class="form-control input-sm textalignright">
                                        </td>
                                        <td> 
                                            <input placeholder="<?php echo trans('label.lbl_total');?>"class="form-control input-sm sum_item_estimated_cost textalignright" id="total-1" name="total[]" disabled type="text" onkeypress="return isDecimalNumber(event, this)" >
                                        </td>  
                                        <?php if($formAction != "add" || $pr_id == "") { ?>
                                        <td>
                                            <!--<i class="fa fa-trash-o mr10 fa-lg remove"></i>-->
                                        </td>    
                                        <?php  } ?>         
                                    </tr>
                                    <?php 
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="panel-footer col-md-12">
                        <?php
                            if($pr_id == "" || $formAction =="edit")
                            {
                             ?>
                            <div class="col-md-6 widget-menu pull-left">
                                <a id="add_more_item" class="ccursor">+ <?php echo trans('label.lbl_addmoreitem');?></a>
                            </div>
                            <?php 
                            }  
                            if($discount_per)
                            {
                                $total_cost = $total_cost - ($total_cost * $discount_per / 100);
                            }
                            elseif($discount_amount)
                            {
                                $total_cost = $total_cost - $discount_amount;
                            }
                            ?>
                            <div class="col-md-6  widget-menu pull-right">
                                <div class="clear col-md-4 pull-left textalignright">
                                    <strong>
                                    <?php echo trans('label.lbl_subtotal_cost');?>
                                    </strong>
                                </div>
                                <div class="col-md-8 pull-right textalignright pb5">
                                    <input id="sub_total_cost" class="form-control input-sm textalignright"  name="sub_total_cost" disabled  value="<?php echo $sub_total_cost;?>" >
                                </div>  
                                <div class="clear col-md-4 pull-left textalignright">
                                    <strong><?php echo trans('label.lbl_discount');?>(%)</strong>
                                </div>
                                <div class="col-md-8 pull-right textalignright">
                                    <div class="col-md-4 pln pb5">
                                        <input onkeypress="return isDecimalNumber(event, this)"  onkeyup="return onDiscountEnter(event, 'per')"  class="form-control input-sm textalignright" name="discount_per" id="discount_per" value="<?php echo $discount_per;?>"> 
                                    </div>
                                    <div class="col-md-3 pull-left textalignright">
                                        <strong><?php echo trans('label.lbl_discount');?></strong>
                                    </div>
                                    <div class="col-md-5 prn pb5">
                                        <input onkeypress="return isNumberKey(event, this)"  onkeyup="return onDiscountEnter(event, 'amount')" class="form-control input-sm textalignright"  name="discount_amount" id="discount_amount" value="<?php echo $discount_amount;?>" > 
                                    </div>
                                </div>     
                                <div class="clear col-md-4 pull-left textalignright">
                                    <strong><?php echo trans('label.lbl_totalcost');?></strong>
                                </div>
                                <div class="col-md-8 pull-right textalignright">
                                   <input id="total_cost" class="form-control input-sm textalignright"  name="total_cost" disabled   value="<?php echo $total_cost;?>" >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 pt10">
                    <div class="panel"> 
                    <div class="panel-heading">
                        <span class="panel-icon"><i class="fa fa-list"></i></span>
                        <span class="panel-title">
                           <?php echo trans('label.lbl_po_approval_det');?>
                        </span>                                 
                    </div>                        
                    <div class="panel-body pn">
                        <table id="purchaserequestapprove" class="table">
                        <?php 
                            $style = "";
                            $checked ="checked";
                            if(isset($purchaserequestdetail['approval_req'])  && $purchaserequestdetail['approval_req'] =="y")
                            {
                                $style = "display:table-row";  $checked = "checked";
                            }
                            else
                            {
                                $checked = "";
                            }
                            if($formAction == "add")
                            {
                                $checked = "checked";
                            }
                            ?>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="checkbox-custom checkbox-info mb5">
                                        <input  type="checkbox"  class="selectDeselectAll" id="enableApprovalcheck" name="approval_req" value="y" <?php echo $checked; ?> >
                                        <label for="enableApprovalcheck"><strong> <?php echo trans('label.lbl_enableapprovalprocess');?> [ <?php echo trans('label.lbl_anyonefromconfirmedoptional');?> ]</strong></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="enableApproval" style="<?php echo $style;?>">
                                <td>
                                <div class="form-group">
                                    <label for="attachment" class="col-md-3 control-label"><?php echo trans('label.lbl_selectapprovers');?>[<?php echo trans('label.lbl_confirmed');?>]</label>
                                    <div class="col-md-9">
                                        <div class="section">
                                            <select  tabindex="5" data-placeholder="Select Approvers"  class="chosen-select form-control input-sm" multiple name="approvers[]">
                                            <option value="">[<?php echo trans('label.lbl_selconfirmapprovers');?>]</option>
                                            <?php 
                                            if(isset($approversDetails) && !empty($approversDetails))
                                            {
                                                foreach($approversDetails as $user)
                                                {
                                                    $selected = "";
                                                   
                                                    if($formAction =="edit")
                                                    {
                                                        if(isset($purchaserequestdetail['approval_details']['confirmed']))
                                                        {
                                                            $selected   = in_array($user['user_id'],$purchaserequestdetail['approval_details']['confirmed']) ? "selected" : "";
                                                        }
                                                    }
                                                    echo "<option value='".$user['user_id']."'  ".$selected.">".$user['firstname']." ".$user['lastname']."</option>";
                                                }
                                            }
                                        ?>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group">
                                    <label for="attachment" class="col-md-3 control-label"><?php echo trans('label.lbl_selectapprovers');?>[<?php echo trans('label.lbl_optional');?>]</label>
                                    <div class="col-md-9">
                                        <div class="section">
                                            <select  tabindex="5" data-placeholder="Select Approvers"  class="chosen-select form-control input-sm" multiple name="approvers_optional[]">
                                            <option value="">[<?php echo trans('label.lbl_seloptionalapprovers');?>]</option>
                                            <?php 
                                            if(isset($approversDetails) && !empty($approversDetails))
                                            {
                                                foreach($approversDetails as $user)
                                                {
                                                    $selected = "";
                                                   
                                                    if($formAction =="edit")
                                                    {
                                                        if(isset($purchaserequestdetail['approval_details']['optional']))
                                                        {
                                                            $selected   = in_array($user['user_id'],$purchaserequestdetail['approval_details']['optional']) ? "selected" : "";
                                                        }
                                                    }
                                                    echo "<option value='".$user['user_id']."'  ".$selected.">".$user['firstname']." ".$user['lastname']."</option>";
                                                }
                                            }
                                        ?>
                                        </select>
                                    </div>
                                </div>
                                </td>                                            
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>  
            </form>
            <div class="form-group col-md-12">                
                <div class="form-group">
                    <label class="col-md-3 control-label"></label>
                    <div class="col-xs-2">
                        <?php  if($formAction == "edit") { ?> <!--PO Without PR Edit -->
                        <button id="poSubmit" type="button" class="btn btn-success btn-block">
                            <?php echo trans('label.btn_update');?>
                        </button>
                        <?php }
                        if($formAction == "add" && $pr_id != "") { ?> <!-- Create PO -->
                        <button id="poSubmit" type="button" class="btn btn-success btn-block">
                            <?php echo trans('label.btn_submit');?>
                        </button>
                        <?php }
                        if($formAction == "add" && $pr_id == ""){ ?> <!-- PO Without PR ADD-->
                            <button id="prSubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_submit');?>
                            </button>
                        <?php } ?> 
                       
                    </div>
                </div>
            </div>                                          
            </div>
            </div>
        </div>
    </div>
</div>
<!-- row-->
<?php
$jsonDataAsString = isset($form_templ_data['details']) ? $form_templ_data['details'] : "";
$jsonConfig = isset($purchaserequestdetail['details']) ? json_encode($purchaserequestdetail['details'], true) : "";

?>
<script type="text/javascript">var jsonDataAsString = '<?=$jsonDataAsString?>';</script> 
<script type="text/javascript">var jsonConfig = '<?=$jsonConfig?>';</script> 
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/formeo-master/formeo.min.js"></script> 
<!--<script language="javascript" type="text/javascript" src="<?php //echo config('app.site_url'); ?>/enlight/scripts/formeo-master/js/demo.js"></script> -->
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/common.js"></script> 
<!--<script language="javascript" type="text/javascript" src="<?php //echo config('app.site_url'); <!--?>/enlight/scripts/admin/templateconfig.js"></script> -->
<!-- Dropzone CSS -->
<link rel="stylesheet" href="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/dropzone/downloads/css/dropzone.css">
<!-- Dropzone JS -->
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/dropzone/downloads/dropzone.min.js"></script>
<script>
$(document).ready(function () {

        //     "use strict";

        // Init Theme Core    
      //  Core.init();

        // Init Theme Core    
      //  Demo.init();
        

 Dropzone.autoDiscover = false;
            $("#uploadme").dropzone({
                paramName: 'photos',
                url: 'upload.php',
                dictDefaultMessage: "Drag your images",
                clickable: true,
                enqueueForUpload: true,
                maxFilesize: 1,
                uploadMultiple: false,
                addRemoveLinks: true
            });
        // Dropzone autoattaches to "dropzone" class.
        // Configure Dropzone options
       /* Dropzone.options.dropZone = { 
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 2, // MB

            addRemoveLinks: true,
            dictDefaultMessage: '<i class="fa fa-cloud-upload"></i><span class="main-text"><b>Drop Files</b> to upload</span> <br /> <span class="sub-text">(or click)</span>',
            dictResponseError: 'Server not Configured'
        };

        Dropzone.options.dropZone2 = {
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 0, // MB

            addRemoveLinks: true,
            dictDefaultMessage: '<i class="fa fa-cloud-upload"></i><span class="main-text"><b>Drop Files</b> to upload</span> <br /><span class="sub-text">(or click)</span>',
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
        });*/


 
 
});
</script>



