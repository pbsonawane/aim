

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
           /* echo '<pre>';
           print_r($purchaserequestdetail);*/
           ?>
           <input id="action_name" name="formAction" type="hidden" value="<?php echo $formAction; ?>">
           <input type="hidden" id="form_templ_id" name="form_templ_id" value="<?php echo $form_templ_data['form_templ_id']; ?>">
           <input type="hidden" id="pr_id" name="pr_id" value="<?php echo $pr_id; ?>">
           <!--  <input type="hidden" id="pr_project_category_hidden" name="pr_project_category_hidden" value="<?php
echo isset($purchaserequestdetail['details']['pr_project_category']) ? $purchaserequestdetail['details']['pr_project_category'] : ""; ?>">
            <input type="hidden" id="pr_shipto_hidden" name="pr_shipto_hidden" value="<?php
echo isset($purchaserequestdetail['shipto_details']['company_name']) ? $purchaserequestdetail['shipto_details']['company_name'] : ""; ?>">
  <input type="hidden" id="pr_shiptocontact_hidden" name="pr_shiptocontact_hidden" value="<?php
  echo isset($purchaserequestdetail['shipto_contact_details']['fname']) ? $purchaserequestdetail['shipto_contact_details']['fname'] : ""; ?>"> -->

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
      <th>#</th>
      <th><?php echo trans('label.lbl_itemname'); ?></th>
      <th><?php echo trans('label.lbl_description'); ?></th>
      <th><?php echo trans('label.lbl_quantity'); ?></th>
      <th><?php echo trans('label.lbl_warranty_support_required'); ?></th>
      <th>Address</th>

      <th width="1%"></th>
    </tr>

  </thead>
  <tbody>


   <?php 


   $total_cost = $total = 0;
   if (!empty($items_arr)) {
    $i = 0;

    foreach ($items_arr as $key=>$asset) {
      $arr =[];
     foreach($asset['pr_shipto'] as $key => $val){
      if(!empty($arr[$val['address_id']][$val['location']] )){
       $arr[$val['address_id']][$val['location']] = $arr[$val['address_id']][$val['location']] + $val['quantity'];
     }else{
      $arr[$val['address_id']][$val['location']] = $val['quantity'];
    }
   // $address_arr[$key] = $arr;
  }
 
  $pr_id = key($asset['pr_no']);

  ?> 
  <tr id="row">
   <td><div class="checkbox-custom checkbox-info mb5">
    <input  type="checkbox"  class="selectDeselectAll" id="enableApprovalcheck_<?php echo $i;?>" name="selected_items[]" data-value="<?php echo $i;?>" value="<?php echo $i;?>" >
    <label for="enableApprovalcheck_<?php echo $i;?>"></label>

  </div>

</td>
<td>
 <div class="section">
  <!--<select class="chosen-select" tabindex="5" data-placeholder="Select Item"  class="form-control input-sm" name="item">-->
   <?php 
   if (isset($ciDetails) && !empty($ciDetails)) {
    foreach ($ciDetails as $ci) {
     if($asset['item_id'] == $ci['ci_templ_id'] ) {

       echo '<input  type="hidden" name="item[]" data-value="'.$asset['item_id'].'" value="'.$asset['item_id'].'" >';

     }
   }
 }

 if (isset($products) && !empty($products)) {
   foreach ($products as $ci) {
    if($asset['item_product'] == $ci['pa_id'] ) {
     echo $ci['display_name'];
     echo '<input  type="hidden" name="item_product[]" data-value="'.$asset['item_product'].'" value="'.$asset['item_product'].'" >';
                                                        // $ci['ci_templ_id']
     if(!empty($asset['pr_no'])){
       foreach($asset['pr_no'] as $k => $v){ ?>
        <input  type="hidden" name="pr_id[<?php echo  $i;?>][<?php echo $asset['item_id']?>][<?php echo $k;?>]" value="<?php echo $v?>" >
        <input  type="hidden" name="pr_no[]" value="<?php echo $asset['pr_no'][$pr_id]?>" >
        <br>
        <small><a class="pr_ids_a" href="/purchaserequest/<?php echo $k;?>"><?php echo $v;?></a></small>
      <?php } } 
    }
  }
}
?>
                                             <?php /*if(!empty($asset['pr_no'])){
                                                foreach($asset['pr_no'] as $k => $v){ ?>
                                                   <input  type="hidden" name="pr_id[<?php echo  $i;?>][<?php echo $k;?>]" value="<?php echo $v?>" >
                                 <input  type="hidden" name="pr_no[]" value="<?php echo $asset['pr_no'][$pr_id]?>" >
                                            <br>
                                            <small><a class="pr_ids_a" href="/purchaserequest/<?php echo $k;?>"><?php echo $v;?></a></small>
                                          <?php } }*/?>
                                        </div>
                                      </td>
                                      <td>
                                        <textarea readonly rows="" id="item_desc-" name="item_desc[]" class="form-control item_desc_cls" ><?php echo $asset['item_desc']; ?></textarea>
                                      </td>
                                      <td>
                                        <input readonly placeholder="<?php echo trans('label.lbl_enterquantity'); ?>" type="text" onkeypress="return isNumberKey(event, this)" onkeyup="return onQtyEnter(event, this)" id="item_qty-" value="<?php echo $asset['item_qty']; ?>" name="item_qty[]" class="form-control input-sm textalignright item_qty_cls" >
                                      </td>
                                      <td>
                                        <textarea readonly rows="" id="warranty_support_required-" name="warranty_support_required[]" class="form-control item_wsr_cls" ><?php echo $asset['warranty_support_required']; ?></textarea>
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
                                    <select readonly multiple="multiple" name="addresses[<?php echo $i?>][]" id="addresses" class="form-control">

                                     <?php 
                                     if(!empty($arr)){
                                      foreach($arr as $key => $val){
                                        foreach($val as $add => $qty){
                                       ?>
                                       <option selected value="<?php echo $key,'~',$add,'~',$qty;?>"><?php echo $add,'-',$qty;?></option>
                                     <?php }}}?>
                                   </select>
                                 <?php /*if (($key + 1) > 1) {
                                echo '<i class="fa fa-trash-o mr10 fa-lg remove"></i>';
                              }*/
                              ?>
                            </td>
                          </tr>
                          <?php
                          $i++;
                        }
                      } else{?>
                        <tr><td colspan="6">Items not found.</td></tr>
                      <?php }
                      ?>
                    </tbody>
                  </table>
                </div>

              </div>
            </div>

            <div class="form-group col-md-12">
             <div class="form-group">
              <label class="col-md-3 control-label"></label>
              <div class="col-xs-2">
               <button id="prConvertSubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_submit'); ?></button>
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
 if(text == '. Other'){
   $('.ship_to_contact_other_class').removeClass('hide');
 }
 else{
  $('.ship_to_contact_other_class').addClass('hide');
}
}



var d = new Date();
var month = d.getMonth()+1;
var day = d.getDate();
var output = d.getFullYear() + '/' + (month<10 ? '0' : '') + month + '/' + (day<10 ? '0' : '') + day;
var view_date = (day<10 ? '0' : '') + day + '/' + month  + '/' +  d.getFullYear();
var save_date = d.getFullYear() + '-' + (month<10 ? '0' : '') + month + '-' + (day<10 ? '0' : '') + day;



</script>
<style type="text/css">#main_content {
 padding-bottom: 5px !important;
 clear: both;
}
.pr_ids_a:hover{ text-decoration: underline !important;  }
</style>

