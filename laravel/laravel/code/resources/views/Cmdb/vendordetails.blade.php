<?php
  // echo '<pre>';print_r($vendordata);die;
?>


<!-- Start: Topbar -->
<header id="topbar" class="affix"  >
  <div class="topbar-left">
   <?php breadcrum(trans('title.vendor')); ?>
  </div>
  <div class="topbar-right">
    <a href="/vendor" class="btn btn-primary back_btn" style="color: #fff;" role="button" aria-pressed="true">Back</a>
  </div>
  
</header>
<!-- End: Topbar -->
<div id="content">
  <div class="row">
    <div class="col-md-12">
      <div class="alert hidden alert-dismissable" id="msg_div"></div>
    </div>
    <div class="col-md-12">
      <div class="panel">
        <div class="panel-body">

        <?php 
        if($vendordata[0]['approve_status'] != 'null') { 
          $app_data         = json_decode($vendordata[0]['approve_status'], true);
          $app_date         = $app_data['created_at'];
          $app_by_name      = $app_data['created_by_name'];
          $status_data      = ucfirst($app_data['approval_status']);
          $comment          = $app_data['comment'];

          $label_status     = '';
          if(isset($status_data)) {
            if($status_data == "Approve") {
              $label_status .= '<label class="text-success">'.$status_data.'d</label>';
            } else {
              $label_status .= '<label class="text-danger">'.$status_data.'d</label>';
            }
          } 
        ?>
          <div class="panel panel-alt">
            <div class="panel-heading" style="background-color:aliceblue;">
              <span class="fa panel-title">Approve Status</span>
            </div>
            <div class="panel-body">
              <div class="row">
                <?php 
                if(!empty($vendordata[0]['vendor_unique_id'])) { 
                ?>
                  <div class="form-group col-md-4">          
                    <label class="control-label">Vendor Id : </label>
                    <label class="ans"><?php if(isset($vendordata[0]['vendor_unique_id'])) echo $vendordata[0]['vendor_unique_id']; ?></label>
                  </div>
                <?php 
                } 
                ?>
                <div class="form-group col-md-4">          
                  <label class="control-label">Action Date : </label>
                  <label class="ans"><?php if(isset($app_date)) echo $app_date; ?></label>
                </div>
                <div class="form-group col-md-4">                
                  <label class="control-label">Action By : </label>
                  <label class="ans"><?php if(isset($app_by_name)) echo $app_by_name; ?></label>
                </div>
                <div class="form-group col-md-4">                
                  <label class="control-label">Status : </label>
                  <?php echo $label_status; ?>
                </div>
                <div class="form-group col-md-8">          
                  <label class="control-label">Comment : </label>
                  <label class="ans"><?php if(isset($comment)) echo $comment; ?></label>
                </div>
              </div>
            </div>
          </div>
        <?php 
        } 
        ?>
          <div class="panel panel-alt">
            <div class="panel-heading" style="background-color:aliceblue;">
              <span class="fa fa-user panel-title"> Vendor Details</span>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="form-group col-md-4">
                  <input type="hidden" name="vendor_id" class="vendor_id" id="vendor_id" value="<?php if(isset($vendordata[0]['vendor_id'])) echo $vendordata[0]['vendor_id']; ?>">
                  <label class="control-label"><?php echo trans('label.lbl_vendor_name');?> : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['vendor_name'])) echo $vendordata[0]['vendor_name']; ?></label>
                </div>
                <div class="form-group col-md-4">
                  <label class="control-label"><?php echo trans('label.lbl_vendor_reference');?> : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['vendor_ref_id'])) echo $vendordata[0]['vendor_ref_id']; ?></label>
                </div>
                <div class="form-group col-md-4">
                  <label class="control-label">Warehouse Location : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['warehouse_location'])) echo $vendordata[0]['warehouse_location']; ?></label>
                </div>
                <div class="form-group col-md-4">
                  <label class="control-label"><?php echo trans('label.lbl_contact_person');?> : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['contact_person'])) echo $vendordata[0]['contact_person']; ?></label>
                </div>
                <div class="form-group col-md-4">
                  <label class="control-label"><?php echo trans('label.lbl_contact_no');?> : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['contactno'])) echo $vendordata[0]['contactno']; ?></label>
                </div>
                <div class="form-group col-md-4">
                  <label class="control-label"><?php echo trans('label.lbl_email');?> : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['vendor_email'])) echo $vendordata[0]['vendor_email']; ?></label>
                </div>
                <div class="form-group col-md-4">
                  <label class="control-label">Registered Address : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['address'])) echo $vendordata[0]['address']; ?></label>
                </div>
                <div class="form-group col-md-4">
                  <label class="control-label">City : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['city'])) echo $vendordata[0]['city']; ?></label>
                </div>
                <div class="form-group col-md-4">
                  <label class="control-label">Pincode : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['pincode'])) echo $vendordata[0]['pincode']; ?></label>
                </div>
                <div class="form-group col-md-4">
                  <label class="control-label"><?php echo trans('label.lbl_gstno');?> : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['vendor_gst_no'])) echo $vendordata[0]['vendor_gst_no']; ?></label>
                </div>
                <div class="form-group col-md-4">
                  <label class="control-label">GST Prof : </label>
                  <?php if(!empty($vendordata[0]['vendor_gst_no_file'])) { ?>
                  <span class = "download_vendor_docs text-primary" 
                    download_id="<?php echo $vendordata[0]['vendor_id']; ?>" 
                    style="cursor:pointer;" 
                    title="Download GST File" 
                    download_path = "<?php echo $vendordata[0]['vendor_gst_no_file']; ?>"
                    download_title = "vendor_gst_file">
                    Download File
                  </span>
                  <?php } ?>
                  
                </div>
                <div class="form-group col-md-4">
                  <label class="control-label"><?php echo trans('label.lbl_pan');?> : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['vendor_pan'])) echo $vendordata[0]['vendor_pan']; ?></label>
                </div>
                <div class="form-group col-md-4">           
                  <label class="control-label">PAN Prof : </label>
                  <?php if(!empty($vendordata[0]['vendor_pan_file'])) { ?>
                  <span class = "download_vendor_docs text-primary" 
                    download_id="<?php echo $vendordata[0]['vendor_id']; ?>" 
                    style="cursor:pointer;" 
                    title="Download GST File" 
                    download_path = "<?php echo $vendordata[0]['vendor_pan_file']; ?>"
                    download_title = "vendor_pan_file">
                    Download File
                  </span>
                  <?php } ?>
                </div>
                <div class="form-group col-md-4">                    
                  <label class="control-label">If firm is MSME registered ? : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['is_msme_reg'])) echo $vendordata[0]['is_msme_reg']; ?></label>
                </div>
                <?php 
                  if(!empty($vendordata[0]['is_msme_reg'])) {
                    if($vendordata[0]['is_msme_reg'] == 'Yes') {
                ?>
                <div class="form-group col-md-4">                    
                  <label class="control-label">if Yes, MEME Registration Number : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['meme_reg_num'])) echo $vendordata[0]['meme_reg_num']; ?></label>
                </div>
                <?php
                    }
                  }
                ?>
                <div class="form-group col-md-4">                  
                  <label class="control-label" style="color:#ff2400;">Products/Services Offered : </label>
                  <label class="ans" style="color:#ff2400;"><?php if(isset($vendordata[0]['products_services_offered'])) echo $vendordata[0]['products_services_offered']; ?></label>
                </div>
                <div class="form-group col-md-4">                    
                  <label class="control-label">Association with OEM: : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['associate_oem'])) echo $vendordata[0]['associate_oem']; ?></label>
                </div>
                <div class="form-group col-md-4">                    
                  <label class="control-label">Delivery Time : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['delivery_time'])) echo $vendordata[0]['delivery_time']; ?></label>
                </div>
                <div class="form-group col-md-4">                
                  <label class="control-label">Payment Terms : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['payment_terms'])) echo $vendordata[0]['payment_terms']; ?></label>
                </div>
                <div class="form-group col-md-4">                  
                  <label class="control-label">Annual Turnover : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['annual_turnover'])) echo $vendordata[0]['annual_turnover']; ?></label>
                </div>
                <div class="form-group col-md-4">                  
                  <label class="control-label">Known Clients : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['known_client'])) echo $vendordata[0]['known_client']; ?></label>
                </div>
              </div>
            </div>
          </div>
          <div class="panel panel-alt">
            <div class="panel-heading" style="background-color:aliceblue;">
              <span class="fa fa-bank panel-title"> Bank Details</span>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="form-group col-md-4">                                
                  <label class="control-label"><?php echo trans('label.lbl_bank_name'); ?> : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['bank_name'])) echo $vendordata[0]['bank_name']; ?></label>
                </div>
                <div class="form-group col-md-4">                                  
                  <label class="control-label"><?php echo trans('label.lbl_bank_branch'); ?> : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['bank_branch'])) echo $vendordata[0]['bank_branch']; ?></label>
                </div>
                <div class="form-group col-md-4">                                  
                  <label class="control-label"><?php echo trans('label.lbl_bank_address');?> : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['bank_address'])) echo $vendordata[0]['bank_address']; ?></label>
                </div>
                <div class="form-group col-md-4">                                
                  <label class="control-label">Bank Passbook Prof : </label>
                  <?php if(!empty($vendordata[0]['bank_name_file'])) { ?>
                  <span class = "download_vendor_docs text-primary" 
                    download_id="<?php echo $vendordata[0]['vendor_id']; ?>" 
                    style="cursor:pointer;" 
                    title="Download GST File" 
                    download_path = "<?php echo $vendordata[0]['bank_name_file']; ?>"
                    download_title = "vendor_pan_file">
                    Download File
                  </span>
                  <?php } ?>
                </div>
                <div class="form-group col-md-4">                                  
                  <label class="control-label"><?php echo trans('label.lbl_account_type'); ?> : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['account_type'])) echo $vendordata[0]['account_type']; ?></label>
                </div>
                <div class="form-group col-md-4">                                  
                  <label class="control-label"><?php echo trans('label.lbl_bank_account_no'); ?> : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['bank_account_no'])) echo $vendordata[0]['bank_account_no']; ?></label>
                </div>
                <div class="form-group col-md-4">                                
                  <label class="control-label"><?php echo trans('label.lbl_ifsc_code'); ?> : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['ifsc_code'])) echo $vendordata[0]['ifsc_code']; ?></label>
                </div>
                <div class="form-group col-md-4">                                  
                  <label class="control-label"><?php echo trans('label.lbl_micr_code'); ?> : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['micr_code'])) echo $vendordata[0]['micr_code']; ?></label>
                </div>
              </div>
            </div>
          </div>
          <div class="panel panel-alt">
            <div class="panel-heading" style="background-color:aliceblue;">
              <span class="fa fa-info-circle panel-title"> Contact Details</span>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="form-group col-md-4"> 
                  <label class="control-label">Director/Proprietor Name : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['director_name'])) echo $vendordata[0]['director_name']; ?></label>
                </div>
                <div class="form-group col-md-4"> 
                  <label class="control-label">Director Contact Number : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['director_contact_no'])) echo $vendordata[0]['director_contact_no']; ?></label>
                </div>
                <div class="form-group col-md-4"> 
                  <label class="control-label">Director Email : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['director_email'])) echo $vendordata[0]['director_email']; ?></label>
                </div>
                <div class="form-group col-md-4"> 
                  <label class="control-label">Sales Officer Name : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['sales_officer_name'])) echo $vendordata[0]['sales_officer_name']; ?></label>
                </div>
                <div class="form-group col-md-4"> 
                  <label class="control-label">Sales Officer Contact Number :</label>
                  <label class="ans"><?php if(isset($vendordata[0]['sales_officer_contact_no'])) echo $vendordata[0]['sales_officer_contact_no']; ?></label>
                </div>
                <div class="form-group col-md-4"> 
                  <label class="control-label">Sales Officer Email : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['sales_officer_email'])) echo $vendordata[0]['sales_officer_email']; ?></label>
                </div>
                <div class="form-group col-md-4"> 
                  <label class="control-label">Account Officer Name : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['account_officer_name'])) echo $vendordata[0]['account_officer_name']; ?></label>
                </div>
                <div class="form-group col-md-4"> 
                  <label class="control-label">Account Officer Contact Number :</label>
                  <label class="ans"><?php if(isset($vendordata[0]['account_officer_contact_no'])) echo $vendordata[0]['account_officer_contact_no']; ?></label>
                </div>
                <div class="form-group col-md-4"> 
                  <label class="control-label">Account Officer Email : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['account_officer_email'])) echo $vendordata[0]['account_officer_email']; ?></label>
                </div>
              </div>
            </div>
          </div>
          <div class="panel panel-alt">
            <div class="panel-heading" style="background-color:aliceblue;">
              <span class="fa fa-clipboard panel-title"> Compliance Section</span>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="form-group col-md-6">  
                  <label class="control-label">Have any legal notices been served to the company in the last 1 or 2 years by any of the authorities?</label>
                  <br/>
                  <label class="ans"><?php if(isset($vendordata[0]['any_legal_notices'])) echo $vendordata[0]['any_legal_notices']; ?></label>
                </div>
          <?php 
            if(isset($vendordata[0]['any_legal_notices'])) {
              if(($vendordata[0]['any_legal_notices']) == 'Yes') {
          ?>
                <div class="form-group col-md-6">  
                  <label class="control-label">If Yes please elaborate</label>
                  <br/>
                  <label class="ans"><?php if(isset($vendordata[0]['legal_notice_elaborate'])) echo $vendordata[0]['legal_notice_elaborate'];?> </label>
                </div>
          <?php 
              }
            }
          ?>
              </div>
              <div class="row">
                <div class="form-group col-md-6">  
                  <label class="control-label">Is the company compliant with all the mandatory EHS statutory and legal requirements ?</label>
                  <br/>
                  <label class="ans"><?php if(isset($vendordata[0]['is_legal_requirements'])) echo $vendordata[0]['is_legal_requirements']; ?></label>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">  
                  <label class="control-label">What is the minimum age of your workers/employees?</label>
                  <br/>
                  <label class="ans"><?php if(isset($vendordata[0]['worker_minimum_age'])) echo $vendordata[0]['worker_minimum_age']; ?></label>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">  
                  <label class="control-label">Do the workers/employees have to submit any of their original governmental IDs to your company, while they join the company?</label>
                  <br/>
                  <label class="ans"><?php if(isset($vendordata[0]['submit_original_documents'])) echo $vendordata[0]['submit_original_documents']; ?></label>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">  
                  <label class="control-label">Have there been any serious incidents/accidents to any of their workers at any of the sites in the last 2 years?</label>
                  <br/>
                  <label class="ans"><?php if(isset($vendordata[0]['any_serious_incidents'])) echo $vendordata[0]['any_serious_incidents']; ?></label>
                </div>
          <?php 
            if(isset($vendordata[0]['any_serious_incidents'])) {
              if($vendordata[0]['any_serious_incidents'] == 'Yes') {
          ?>
                <div class="form-group col-md-6 pb-3">  
                  <label class="control-label">If Yes please elaborate</label>
                  <br/>
                  <label class="ans"><?php if(isset($vendordata[0]['elaborate_serious_incidents'])) echo $vendordata[0]['elaborate_serious_incidents']; ?></label>
                </div>
          <?php
              }
            }
          ?>
              </div>
              <div class="row">
                <div class="form-group col-md-6">  
                  <label class="control-label">Do the Company have an Anti-Corruption and Bribery Policy, Policy on POSH, Anti Child Labour and Forced and Bonded Labour Policy?</label>
                  <br/>
                  <label class="ans"><?php if(isset($vendordata[0]['is_anti_bribe_policy'])) echo $vendordata[0]['is_anti_bribe_policy']; ?></label>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">  
                  <label class="control-label">Do the Company have an environmental, health, safety and social (EHS&S) policy?</label>
                  <br/>
                  <label class="ans"><?php if(isset($vendordata[0]['is_health_safety_policy'])) echo $vendordata[0]['is_health_safety_policy']; ?></label>
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">  
                  <label class="control-label">Indicate whether your organization has been found to be out of compliance with any local labor, tax, or environmental regulations in 1-2 years?</label>
                  <br/>
                  <label class="ans"><?php if(isset($vendordata[0]['is_env_regulation'])) echo $vendordata[0]['is_env_regulation']; ?></label>
                </div>
          <?php 
            if(isset($vendordata[0]['is_env_regulation'])) {
              if($vendordata[0]['is_env_regulation'] == 'Yes') {
          ?>
                <div class="form-group col-md-6">  
                  <label class="control-label">If Yes please elaborate</label>
                  <br/>
                  <label class="ans"><?php if(isset($vendordata[0]['elaborate_env_regulation'])) echo $vendordata[0]['elaborate_env_regulation']; ?></label>
                </div>
          <?php
              }
            }
          ?>
              </div>
            </div>
          </div>
          <div class="panel panel-alt">
            <div class="panel-heading" style="background-color:aliceblue;">
              <span class="fa fa-pencil-square-o panel-title">Acknowledgment Section</span>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="form-group col-md-4">          
                  <label class="control-label">Name : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['name'])) echo $vendordata[0]['name']; ?></label>
                </div>
                <div class="form-group col-md-4">                
                  <label class="control-label">Date : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['date'])) echo $vendordata[0]['date']; ?></label>
                </div>
                <div class="form-group col-md-4">                
                  <label class="control-label">Designation : </label>
                  <label class="ans"><?php if(isset($vendordata[0]['designation'])) echo $vendordata[0]['designation']; ?></label>
                </div>
              </div>
            </div>
          </div>

          <?php if($vendordata[0]['approve_status'] == 'null') { 
            if (canuser('advance', 'VENDORDETAILSPAGE'))  {
          ?>
            <div class="form-group">
              <div class="btn-group approve_vendor">
                <button id="approve_<?php if (isset($vendordata[0]['vendor_id'])) { echo $vendordata[0]['vendor_id'];} ?>" type="button" class="btn btn-success btn-block"><i class="glyphicons glyphicons-check"></i>Approve
                </button>
              </div>
              <div class="btn-group reject_vendor">
                <button id="disapprove_<?php if (isset($vendordata[0]['vendor_id'])) { echo $vendordata[0]['vendor_id'];} ?>" type="button" class="btn btn-danger btn-block"><i class="glyphicons glyphicons-remove"></i>Disapprove
                </button>
              </div>
            </div>
          <?php 
            }
          } 
          ?>
            
        </div>
      </div>
    </div>
  </div>
</div>

<div id="myModal_approve_reject_vendor" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <form class="form-horizontal" id="formComment_vendor">
      <input type="hidden" id="vendor_id" name="vendor_id" >
      <input type="hidden" id="approval_status" name="approval_status">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><span id="modal-title_approve_reject"></h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="hidden alert-dismissable" id="msg_modal_approve_reject_vendor"></div>
            </div>
          </div>
           
          <div class="form-group required ">
            <label for="inputStandard" class="col-md-12 control-label textalignleft">Reason/Comment</label>
            <div class="col-md-12">
              <textarea class="col-md-12" name="comment" maxlength="250"></textarea>
              <br><code style="float: right;">(Max 250 Characters)</code>
            </div>
          </div>
        </div> 
        <div class="modal-footer">
          <button type="button" id="submit_approve_reject_vendor" class="btn btn-success"><?php echo trans('label.btn_submit'); ?></button>&nbsp;|&nbsp;
          <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo trans('label.btn_close'); ?></button>
        </div>
      </div>
    </form>
  </div>
</div>

<style type="text/css">
  .ans{
    font-weight: 300;
  }
  .approve_vendor{
    padding-right: 10px;
  }
  .back_btn{
    padding: 5px 10px;
    font-size: 12px;
    line-height: 1.5;
    border-radius: 0px;
  }
</style>

<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/cmdb/vendor.js?<?php echo time(); ?>"></script> 
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/common.js?<?php echo time(); ?>"></script> 

