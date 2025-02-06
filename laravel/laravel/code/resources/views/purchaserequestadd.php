

<link rel="stylesheet" type="text/css" href="enlight/scripts/formeo-master/css/demo.css">
<div class="row" id="credentialTypeform">
<div class="col-md-10">
   <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
   <div class="hidden alert-dismissable" id="msg_popup"></div>
</div>
<?php
//print_r(@$assetdetails);
?>
<div class="col-md-12">
<div class="panel">
   <div class="panel-body purchaseRequestScroll">
      <div id="form-builder">
         <div class="form-group required clearfix">
            <section id="main_content" class="inner">
               <form id="build-form" class="build-form clearfix">
               </form>
               <form id="prBuilderForm" action="post">
                  <div class="render-form"></div>
               </form>
            </section>
            <div class="render-btn-wrap" >
               <button id="renderForm" class="btn btn-outline-primary" ><?php echo trans('label.btn_previewform'); ?></button>
               <button id="viewData" class="btn btn-outline-success"><?php echo trans('label.btn_generatejsondata'); ?></button>
               <button id="reloadBtn" class="btn btn-outline-danger"><?php echo trans('label.btn_reseteditor'); ?></button>
            </div>
         </div>
         <form id="prItemApproval" action="post">
            <?php 
            /*echo '<pre>';
            print_r($purchaserequestdetail);*/
            ?>
            <input id="action_name" name="formAction" type="hidden" value="<?php echo $formAction; ?>">
            
            <input id="pr_department" name="pr_department" type="hidden" value="<?php echo isset($pr_department) ? $pr_department : "";?>">
            <input id="pr_department_id" name="pr_department_id" type="hidden" value="<?php echo isset($pr_department_id) ? $pr_department_id : "";?>">
            
            <input id="pr_req_date" name="pr_req_date" type="hidden" value="
            <?php
            if(isset($purchaserequestdetail['details']['pr_req_date']) && $purchaserequestdetail['details']['pr_req_date'] != '')
            {
                echo $purchaserequestdetail['details']['pr_req_date']; 
            }
            ?>">
            
            <input type="hidden" id="form_templ_id" name="form_templ_id" value="<?php echo $form_templ_data['form_templ_id']; ?>">
            <input type="hidden" id="pr_id" name="pr_id" value="<?php echo $pr_id; ?>">
            <input type="hidden" id="pr_project_category_hidden" name="pr_project_category_hidden" value="<?php
echo isset($purchaserequestdetail['details']['pr_project_category']) ? $purchaserequestdetail['details']['pr_project_category'] : ""; ?>">
            <input type="hidden" id="pr_shipto_hidden" name="pr_shipto_hidden" value="<?php
echo isset($purchaserequestdetail['shipto_details']['company_name']) ? $purchaserequestdetail['shipto_details']['company_name'] : ""; ?>">
  <input type="hidden" id="pr_shiptocontact_hidden" name="pr_shiptocontact_hidden" value="<?php
echo isset($purchaserequestdetail['shipto_contact_details']['fname']) ? $purchaserequestdetail['shipto_contact_details']['fname'] : ""; ?>">

            <div class="render-form">
               <div id="project_internal_div" class="hide formeo-render formeo">
                  <div class="f-row">
                     <div class="f-render-column" style="width: 50%;">
                        <div class="f-field-group" >
                           <label for=""><?php echo trans('label.lbl_project_name'); ?><span class="text-error">*</span></label>
                           <select required="true" name="pr_project_name_dd" id="pr_project_name_dd">
                              <option label="[Select Project]">[Select Project]</option>
                              <option label="MDC" value="MDC" <?php
if (isset($purchaserequestdetail['details']['pr_project_name_dd']) && $purchaserequestdetail['details']['pr_project_name_dd'] == 'MDC') {echo 'selected="selected"';}?>>MDC</option>
                              <option label="BDC" value="BDC" <?php
if (isset($purchaserequestdetail['details']['pr_project_name_dd']) && $purchaserequestdetail['details']['pr_project_name_dd'] == 'BDC') {echo 'selected="selected"';}?>>BDC</option>
                              <option label="NDC" value="NDC" <?php
if (isset($purchaserequestdetail['details']['pr_project_name_dd']) && $purchaserequestdetail['details']['pr_project_name_dd'] == 'NDC') {echo 'selected="selected"';}?>>NDC</option>
                           </select>
                        </div>
                     </div>
                     <?php                     
                        if (isset($purchaserequestdetail['details']['pr_project_name_dd']) && $purchaserequestdetail['details']['pr_project_name_dd'] != '') 
                        {
                           ?>
                           <div class="f-render-column" style="width: 50%;">
                              <div class="f-field-group" >                              
                              </div>
                           </div>
                           <?php
                           
                        }else{
                           ?>
                           <div class="f-render-column" style="width: 50%;">
                              <div class="f-field-group" >
                              <label for="">Attach File&nbsp;<span style="color: red; font-style: italic;">(Only Accept: jpeg,jpg,png,pdf,doc,docx,csv,xlsx,xls)</span></label>
                              <input type="file" name="internal_file" id="internal_file" accept=".jpeg,.png,.jpg,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document"/>
                              </div>
                           </div>
                           <?php
                        }
                     
                     ?>
                  </div>
               </div>
            </div>
            <div class="render-form">
               <div id="project_external_div" class="hide  formeo-render formeo">
                  <div class="f-row">
                     <div class="f-render-column" style="width: 50%;">
                        <div class="f-field-group" >
                           <label for="">Opportunity Code/Id<span class="text-error">*</span></label>
                            <input name="opportunity_code" type="text" required="true" id="opportunity_code" maxlength="250" value="<?php
echo isset($purchaserequestdetail['details']['opportunity_code']) ? $purchaserequestdetail['details']['opportunity_code'] : ""; ?>">
                        </div>
                     </div>
                     <div class="f-render-column" style="width: 50%;">
                        <div class="f-field-group" >
                           <label for=""><?php echo trans('label.lbl_project_name'); ?><span class="text-error">*</span></label>
                           <!-- <input readonly name="project_name" type="text" required="true" id="project_name" value="<?php echo isset($purchaserequestdetail['details']['project_name']) ? $purchaserequestdetail['details']['project_name'] : ""; ?>"> -->
                           <select name="project_name" id="project_name" required="true"></select>
                        </div>
                     </div>
                  </div>
                  <div class="f-row">
                     <div class="f-render-column" style="width: 50%;">
                        <div class="f-field-group" >
                           <label for=""><?php echo trans('label.lbl_project_wo_details'); ?><span class="text-error">*</span></label>
                           <input name="project_wo_details" type="text" required="true" id="project_wo_details" maxlength="250" value="<?php echo isset($purchaserequestdetail['details']['project_wo_details']) ? $purchaserequestdetail['details']['project_wo_details'] : ""; ?>">
                        </div>
                     </div>
                     <div class="f-render-column" style="width: 50%;">
                        <div class="f-field-group" >
                           <label for="">Customer PO</label>
                           <input name="customer_po_file" type="file" id="customer_po_file">
                        </div>
                     </div>
                  </div>
                  <div class="f-row">
                     <div class="f-render-column" style="width: 50%;">
                        <div class="f-field-group" >
                           <label for="">GC Approval</label>
                           <input name="gc_approval_file" type="file" required="true" id="gc_approval_file">
                        </div>
                     </div>
                     <div class="f-render-column" style="width: 50%;">
                        <div class="f-field-group" >
                           <label for="">Costing Details Against the Requirement</label>
                           <input name="costing_details_file" type="file" required="true" id="costing_details_file">
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <input type="hidden" id="editrequesterid" name="editrequesterid" value="<?php if(isset($purchaserequestdetail['requester_id'])){ echo $purchaserequestdetail['requester_id'];}?>">
            <div class="col-md-12">  
               <div class="panel">
                  <div class="panel-heading">
                     <span class="panel-icon"><i class="fa fa-list"></i></span>
                     <span class="panel-title">
                     <?php echo trans('label.lbl_itemdetails'); ?>
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
                              <th>Category</th>
                              <th><?php echo trans('label.lbl_itemname'); ?></th>
                              <th><?php echo trans('label.lbl_description'); ?></th>
                              <th><?php echo trans('label.lbl_quantity'); ?></th>
                              <th><?php echo trans('label.lbl_warranty_support_required'); ?></th>
                              <!-- <th><?php //echo trans('label.lbl_estimatedcost');?></th>
                                 <th><?php //echo trans('label.lbl_total');?></th> -->
                              <th width=""></th>
                           </tr>
                           <!--<tr>
                              <td colspan="4"><a id="add_more_item">+ Add More Item</a></td>
                              <td>Total Cost</td>
                              <td></td>
                              </tr>-->
                        </thead>
                        <tbody>
                           <?php
$total_cost = $total = 0;
// print_r($ciDetails);
if (!empty($assetdetails)) {
    foreach ($assetdetails as $key => $asset) {
        $asset_details = json_decode($asset['asset_details'], true);
         if($asset_details['item'] != "")
         {
            $disabled = "disabled";
         }
        ?>
                           <tr id="row-<?php echo ($key + 1); ?>">
                              <!--<td><?php //echo ($key+1); ?></td>-->
                              <td>
                                 <div class="section">
                                    <!--<select class="chosen-select" tabindex="5" data-placeholder="Select Item"  class="form-control input-sm" name="item">-->
                                    <select <?php echo $disabled; ?> tabindex="5" data-placeholder="Select Item"  class="form-control input-sm item_id_cls category_name" id="item-<?php echo ($key + 1); ?>" name="item[]">
                                       <option value="">[<?php echo trans('label.lbl_selectitem'); ?>]
                                       </option>
                                       <?php
                                    if (isset($ciDetails) && !empty($ciDetails)) {

                                                foreach ($ciDetails as $ci) {
                                                    $selected = $asset_details['item'] == $ci['ci_templ_id'] ? $selected = "selected" : "";
                                                    
                                                    echo "<option value='" . $ci['ci_templ_id'] . "' " . $selected . ">" . $ci['ci_name'] . "</option>";
                                                }
                                            }
                                            ?>
                                    </select>
                                 </div>
                              </td>
                              <td>
                                 <div class="section">
                                    <!-- Edit Item Section -->
                                    <!--<select class="chosen-select" tabindex="5" data-placeholder="Select Item"  class="form-control input-sm" name="item">-->
                                   <select  <?php echo $disabled; ?>  tabindex="5" data-placeholder="Select Item"  class="form-control input-sm item_product_cls" id="item_product-<?php echo ($key + 1); ?>" name="item_product[]">
                                       <option value="">[<?php echo trans('label.lbl_selectitem'); ?>]</option> 
                                       <option selected value="<?php echo $asset_details['item_product'] .'~'. $asset_details['item_unit'];?>"><?php echo $asset_details['item_product_name'];?> </option>                                       
                                    </select>
                                 </div>
                              </td>
                              <td>
                                 <textarea maxlength="250" rows="<?php echo ($key + 1); ?>" id="item_desc-<?php echo ($key + 1); ?>" name="item_desc[]" class="form-control item_desc_cls" ><?php echo $asset_details['item_desc']; ?></textarea>
                              <code style="float: right;">(Max 250 Characters)</code></td>
                              <td>
                                 <input placeholder="<?php echo trans('label.lbl_enterquantity'); ?>" type="number" onkeypress="return isNumberKey(event, this)" onkeyup="return onQtyEnter(event, this)" id="item_qty-<?php echo ($key + 1); ?>" value="<?php echo $asset_details['item_qty']; ?>" min = 1 name="item_qty[]" class="form-control input-sm textalignright item_qty_cls" >
                              </td>
                              <td>
                                 <textarea maxlength="250" rows="<?php echo ($key + 1); ?>" id="warranty_support_required-<?php echo ($key + 1); ?>" name="warranty_support_required[]" class="form-control item_wsr_cls" ><?php echo $asset_details['warranty_support_required']; ?></textarea><code style="float: right;">(Max 250 Characters)</code>
                              </td>
                              <!-- <td>
                                 <input placeholder="<?php //echo trans('label.lbl_enterestimatedcost');?>" type="text" onkeypress="return isDecimalNumber(event, this)" onkeyup="return onCostEnter(event, this)" id="item_estimated_cost-<?php //echo ($key+1); ?>" name="item_estimated_cost[]" value="<?php //echo $asset_details['item_estimated_cost']; ?>" class="form-control input-sm textalignright">
                                 </td> -->
                              <!-- <td>
                                 <?php
//$total = $asset_details['item_estimated_cost'] * $asset_details['item_qty'];

        $total_cost = $total_cost + $total;
        ?>
                                     <input placeholder="<?php //echo trans('label.lbl_total');?>"class="form-control input-sm textalignright sum_item_estimated_cost" id="total-<?php //echo ($key+1); ?>" name="total[]" disabled type="text" value="<?php //echo $total; ?>" onkeypress="return isDecimalNumber(event, this)" >
                                 </td>   -->
                              <td>
                                 <?php if (($key + 1) > 1) {
                                echo '<i class="fa fa-trash-o mr10 fa-lg remove"></i>';
                                    }
                                    ?>
                                                          </td>
                                                       </tr>
                                                       <?php
                            }
                            } else {
                                ?>

                           <tr id="row-1">
                              <!--<td>1</td>-->
                              <td>
                                 <div class="section">
                                    <!--<select class="chosen-select" tabindex="5" data-placeholder="Select Item"  class="form-control input-sm" name="item">-->
                                    <select  tabindex="5" data-placeholder="Select Item"  class="form-control input-sm item_id_cls category_name" id="item-1" name="item[]">
                                       <option value="">[<?php echo trans('label.lbl_selectitem'); ?>]</option>
                                       <?php
                                        if (isset($ciDetails) && !empty($ciDetails)) {
                                                foreach ($ciDetails as $ci) {
                                                    echo "<option value='" . $ci['ci_templ_id'] . "'>" . $ci['ci_name'] . "</option>"; 
                                                }
                                            }
                                            ?>
                                    </select>
                                 </div>
                              </td>
                              <td>
                                 <div class="section">
                                    <!--<select class="chosen-select" tabindex="5" data-placeholder="Select Item"  class="form-control input-sm" name="item">-->
                                    <select  tabindex="5" data-placeholder="Select Item"  class="form-control input-sm item_product_cls" id="item_product-1" name="item_product[]">
                                       <option value="">[<?php echo trans('label.lbl_selectitem'); ?>]</option>                                       
                                    </select>
                                 </div>
                              </td>
                              <td><textarea rows="1" id="item_desc-1" name="item_desc[]" class="form-control item_desc_cls" maxlength="250"></textarea><code style="float: right;">(Max 250 Characters)</code></td>
                              <td><input placeholder="<?php echo trans('label.lbl_enterquantity'); ?>" type="number" min = 1 onkeypress="return isNumberKey(event, this)" onkeyup="return onQtyEnter(event, this)" id="item_qty-1" name="item_qty[]" class="form-control input-sm item_qty_cls" ></td>
                              <td><textarea rows="1" id="warranty_support_required-1" name="warranty_support_required[]" class="form-control item_wsr_cls" maxlength="250"></textarea><code style="float: right;">(Max 250 Characters)</code></td>
                              <!-- <td><input placeholder="<?php //echo trans('label.lbl_enterestimatedcost');?>" type="text" onkeypress="return isDecimalNumber(event, this)" onkeyup="return onCostEnter(event, this)" id="item_estimated_cost-1" name="item_estimated_cost[]" class="form-control input-sm textalignright"></td>
                                 <td>
                                     <input placeholder="<?php //echo trans('label.lbl_total');?>"class="form-control input-sm sum_item_estimated_cost textalignright" id="total-1" name="total[]" disabled type="text" onkeypress="return isDecimalNumber(event, this)" >
                                 </td>   -->
                              <td>
                                 <!--<i style="color:red;" class="fa fa-trash-o mr10 fa-lg remove"></i>-->
                              </td>
                           </tr>
                           <?php
}
?>
                        </tbody>
                     </table>
                  </div>
                  <div class="panel-footer col-md-12">
                     <div class="col-md-6 widget-menu pull-left">
                        <a id="add_more_item" class="ccursor btn btn-primary">+ <?php echo trans('label.lbl_addmoreitem'); ?></a>
                     </div>
                     <div class="col-md-6  widget-menu pull-right">
                        <!-- <div class="col-md-4 pull-left textalignright">
                           <strong><?php //echo trans('label.lbl_totalcost');?></strong>
                           </div>
                           <div class="col-md-5 pull-right textalignright">
                           <span id="total_cost"><strong><?php //echo $total_cost;?></strong> /-</span>
                           </div> -->
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-12">
               <div class="panel">
                  <div class="panel-heading">
                     <span class="panel-icon"><i class="fa fa-list"></i></span>
                     <span class="panel-title">
                     <?php echo trans('label.lbl_approvaldetails'); ?>
                     </span>
                  </div>
                  <div class="panel-body pn">
                     <table id="purchaserequestapprove" class="table">
                        <!--<thead>
                           <tr class="info">
                               <th>Item Name</th>
                           </tr>
                           </thead>-->
                        <?php
/*   $hidden = "";
$checked = "";
if(isset($purchaserequestdetail['approval_req']) && $purchaserequestdetail['approval_req'] =="y" && $formAction =="edit" )
{
$checked = "checked";
}
elseif($formAction =="add")
{
$hidden = "";  $checked = "checked";
}
if( $purchaserequestdetail['approval_req'] =="n" && $formAction =="edit")
{
$hidden = "hidden";  $checked = "";
}
else{
$hidden  ="";
}*/
$style   = "";
$checked = "checked";
if (isset($purchaserequestdetail['approval_req']) && $purchaserequestdetail['approval_req'] == "y") {
    $style   = "display:table-row";
    $checked = "checked";
} else {
    $checked = "";
}
if ($formAction == "add") {
    $checked = "checked";
}
?>
                        <tbody> 
                           <!-- <tr>
                              <td>
                                 <div class="checkbox-custom checkbox-info mb5">
                                    <input  type="checkbox"  class="selectDeselectAll" id="enableApprovalcheck" name="approval_req" value="y" <?php echo $checked; ?>>
                                    <label for="enableApprovalcheck"><strong> <?php echo trans('label.lbl_enableapprovalprocess'); ?> [ <?php echo trans('label.lbl_anyonefromconfirmedoptional'); ?> ]</strong></label>
                                 </div>
                              </td>
                           </tr> -->
                           <tr id="enableApproval" style="<?php echo $style; ?>">
                              <td>
                                 <div class="form-group">
                                    <label for="attachment" class="col-md-3 control-label"><?php echo trans('label.lbl_selectapprovers'); ?>[<?php echo trans('label.lbl_confirmed'); ?>]</label>
                                    <div class="col-md-9">
                                       <div class="section">
                                          <select  tabindex="5" data-placeholder="<?php echo trans('label.lbl_selectapprovers'); ?>"  class="chosen-select form-control input-sm approvers_cls" multiple name="approvers[]">
                                          <?php
                                          if ($formAction != "edit") {
                                          ?>
                                          <option value="">[<?php echo trans('label.lbl_selconfirmapprovers'); ?>]</option>
                                          <?php
                                          }
                                          ?>
                                             
                                             <?php
                                                if (isset($approversDetails) && !empty($approversDetails)) {
                                                    foreach ($approversDetails as $user) {
                                                        $selected = "";
                                                      
                                                      $role_id = json_decode($user['role_id']);

                                                      if($role_id[0] == 'de3451b2-0adc-11ec-abff-4e89be533080' || $role_id[0] == '1a815adc-1acf-11ec-8c45-4e89be533080' || $role_id[0] == '12ceb012-6f81-11ec-9c34-92ff989c7103' || $role_id[0] == '7443a154-6f81-11ec-9e6b-92ff989c7103')
                                                      {
                                                         if ($formAction == "edit") {
                                                            if (isset($purchaserequestdetail['approval_details']['confirmed'])) {
                                                               if(isset($user['user_id']))
                                                               {
                                                                  $selected = in_array($user['user_id'], $purchaserequestdetail['approval_details']['confirmed']) ? "selected" : "";
                                                               }                                                                
                                                            }
                                                        }

                                                        if(isset($user['user_id']))
                                                        {
                                                         echo "<option selected value='" . $user['user_id'] . "'  " . $selected . ">" . $user['firstname'] . " " . $user['lastname'] . "</option>";
                                                         break;
                                                        }                                                       
                                                      }
                                                      else
                                                      {
                                                        if ($formAction == "edit") {
                                                            if (isset($purchaserequestdetail['approval_details']['confirmed'])) {
                                                                $selected = in_array($user['parent_id'], $purchaserequestdetail['approval_details']['confirmed']) ? "selected" : "";
                                                            }
                                                        }
                                                        if(isset($user['user_id']))
                                                        {
                                                         if($user['user_id'] == $user['user_id'])
                                                         {
                                                          echo "<option selected value='" . $user['parent_id'] . "'  " . $selected . "  >" . $user['mgrfirstname'] . " " . $user['mgrlastname'] . "</option>";
                                                          break;
                                                         }                                                         
                                                        }
                                                      //   echo "<option selected value='" . $user['parent_id'] . "'  " . $selected . "  >" . $user['mgrfirstname'] . " " . $user['mgrlastname'] . "</option>";
                                                      }
                                                    }
                                                }
                                             ?>
                                          </select>
                                       </div>
                                    </div>
                                    <hr>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                     </div>
                     </div>
                  </div>
                  <div class="form-group col-md-12">
                     <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                        <div class="col-xs-2">
                           <button id="prSubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_submit'); ?></button>
                        </div>
                     </div>
                  </div>
         </form>
         </div>
         </div>
      </div>
   </div>
</div>
<!-- row-->
<?php
$jsonDataAsString = isset($form_templ_data['details']) ? $form_templ_data['details'] : "";
$jsonConfig       = isset($purchaserequestdetail['details']) ? json_encode($purchaserequestdetail['details'], true) : "";
?>
<script type="text/javascript">var jsonDataAsString = '<?=$jsonDataAsString?>';</script>
<script type="text/javascript">var jsonConfig = '<?=$jsonConfig?>';</script>
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/formeo-master/formeo.min.js"></script>
<!--<script language="javascript" type="text/javascript" src="<?php //echo config('app.site_url'); ?>/enlight/scripts/formeo-master/js/demo.js"></script> -->
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/common.js"></script>
<!--<script language="javascript" type="text/javascript" src="<?php //echo config('app.site_url'); <!--?>/enlight/scripts/admin/templateconfig.js"></script> -->
<script type="text/javascript">   
   $( "form#prBuilderForm" )
       .attr( "enctype", "multipart/form-data" )
       .attr( "encoding", "multipart/form-data" )
   ;
   $( "form#prItemApproval" )
       .attr( "enctype", "multipart/form-data" )
       .attr( "encoding", "multipart/form-data" )
   ;

   function get_pr_project_category(selected_val)
   {
       if(selected_val == 'Internal'){
           $('#project_internal_div').removeClass('hide');
           $('#project_external_div').addClass('hide');
       }
       else if(selected_val == 'External'){
           $('#project_internal_div').addClass('hide');
           $('#project_external_div').removeClass('hide');
       }
       else{
           $('#project_internal_div').addClass('hide');
           $('#project_external_div').addClass('hide');
       }
   }
   var pr_project_category = $('#pr_project_category_hidden').val();
   var pr_id = $('#pr_id').val();

   if(pr_id != '')
   {
       if(pr_project_category == 'Internal'){
           $('#project_internal_div').removeClass('hide');
           $('#project_external_div').addClass('hide');
       }
       else if(pr_project_category == 'External'){
           $('#project_internal_div').addClass('hide');
           $('#project_external_div').removeClass('hide');
       }
       else{
           $('#project_internal_div').addClass('hide');
           $('#project_external_div').addClass('hide');
       }
   }

function get_pr_ship_to(selected_val)
{
   var text = selected_val.options[selected_val.selectedIndex].text;
   if(text == 'Other'){
       $('.ship_to_other_class').removeClass('hide');
   }
   else{
     $('.ship_to_other_class').addClass('hide');
   }
}

function get_pr_ship_to_contact(selected_val)
{
   var text = selected_val.options[selected_val.selectedIndex].text;
   if(text == 'Other'){
       $('.ship_to_contact_other_class').removeClass('hide');
   }
   else{
     $('.ship_to_contact_other_class').addClass('hide');
   }
}

/*var department_name = $('#pr_department').val();
alert(department_name);
$('#5c3f80f2-505f-4f37-994f-eb6ec9ce3f68').text(department_name);
alert($('#5c3f80f2-505f-4f37-994f-eb6ec9ce3f68').text(department_name));*/
/*
var d = new Date();
var month = d.getMonth()+1;
var day = d.getDate();
var output = d.getFullYear() + '/' + (month<10 ? '0' : '') + month + '/' + (day<10 ? '0' : '') + day;
var view_date = (day<10 ? '0' : '') + day + '/' + month  + '/' +  d.getFullYear();
var save_date = d.getFullYear() + '-' + (month<10 ? '0' : '') + month + '-' + (day<10 ? '0' : '') + day;

$('#pr_req_date').val(save_date);*/



</script>
<style type="text/css">#main_content {
   padding-bottom: 5px !important;
   clear: both;
   }
   .remove{
      color: red;
   }
</style>

<script type="text/javascript">
   $(function() {
  var $dp1 = $("#pr_req_date");
  $dp1.datepicker({
    changeYear: true,
    changeMonth: true,
      minDate:0,
    dateFormat: "yy-m-dd",
    yearRange: "-100:+20",
  });

</script>