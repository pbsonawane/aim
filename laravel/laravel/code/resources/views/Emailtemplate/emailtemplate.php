
<script src="ckeditor/ckeditor.js"></script>
<div class="row">
    <div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
      <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div>
    <div class="col-md-12">
      <div class="panel">
        <div class="panel-body">
          <div class="col-md-10">
          <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
            <div class="hidden alert-dismissable" id="msg_popup"></div>
          </div>
          <form class="form-horizontal"  name="addformemailtemplate" id="addformemailtemplate">
            <input id="template_id" name="template_id" type="hidden" value="<?php echo $template_id?>">
            <?php //print_r($contracttypedata);?>
                <div class="form-group required ">
                  <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_template_name');?></label>
                  <div class="col-md-5">
                      <input type="text" id="template_name" name="template_name" class="form-control input-sm" value="<?php if(isset($templatedata[0]['template_name'])) echo $templatedata[0]['template_name'];?>">
                  </div>
                </div>
                <div class="form-group required ">
                  <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_template_key');?></label>
                  <div class="col-md-5">
                      <input type="text" id="template_key" name="template_key" class="form-control input-sm" value="<?php if(isset($templatedata[0]['template_key'])) echo $templatedata[0]['template_key'];?>"  <?php if(isset($templatedata[0]['template_key'])){ echo "disabled"; }?>>
                  </div>
                </div>
                <div class="form-group required ">
                  <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_template_category');?></label>
                  <div class="col-md-5">
                    <select name="template_category_select" id="template_category" class="form-control" onchange="CheckCategory(this.value);">
                        <option value="">Select</option>
                         <?php
                          if(is_array($templatecategory) && count($templatecategory) > 0){

                            foreach ($templatecategory as $key => $tempcat){ ?>      
                              <option value="<?php echo $tempcat['template_category']; ?>"  <?php if(isset($templatedata[0]['template_category']) && $templatedata[0]['template_category'] == $tempcat['template_category']){ echo "selected"; }?>><?php echo ucfirst($tempcat['template_category']); ?>
                              </option>
                          <?php 
                            }
                          } ?>
                          <option value="others">Other</option>
                    </select>
                  </div>
                  <div class="col-md-4">
                      <input type="text" id="template_category1" name="template_category" class="form-control input-sm" value="<?php  if(isset($templatedata[0]['template_category'])){ echo $templatedata[0]['template_category']; } ?>" style="display:none;">
                  </div>
                </div>
               
                <div class="form-group">
                  <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_configure_email_ids');?></label>
                      <div class="col-md-6">
                      <?php /*  <label class="checkbox-container">                              
                          <input name="configure_email_id" type="checkbox" id="configure_email_id" class="form-check-input"  value="y" <?php if(isset($templatedata[0]['configure_email_id']) && $templatedata[0]['configure_email_id'] == 'y'){ echo "checked"; } ?>>
                          <span class="checkmark"></span>
                        </label><?php */?>
                        <div class="checkbox-custom mb5">
                          <input type="checkbox" class="user_bvs" id="configure_email_id" value="y" name="configure_email_id" <?php if(isset($templatedata[0]['configure_email_id']) && $templatedata[0]['configure_email_id'] == 'y'){ echo "checked"; } ?>>
                          <label for="configure_email_id"></label>
                       </div>
                      </div>
                  </div>
                  <div class="form-group " id="conf_email_ids" <?php if(isset($templatedata[0]['configure_email_id']) && $templatedata[0]['configure_email_id'] == 'y'){  echo 'style="display:block"'; }else{
                    echo 'style="display:none"'; }?>>
                    <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_email_ids');?></label>
                    <div class="col-md-5">
                        <input type="text" id="email_ids" name="email_ids" class="form-control input-sm" value="<?php if(isset($templatedata[0]['email_ids'])) echo $templatedata[0]['email_ids'];?>">
                    </div>
                    <div class="col-md-4">
                        <label class="checkbox-container"><?php echo trans('messages.msg_config_email_ids');?>
                        </label>
                      </div>
                  </div>
                  <div class="form-group required ">
                      <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_subject');?></label>
                      <div class="col-md-5">
                          <input type="text" id="subject" name="subject" class="form-control input-sm" value="<?php if(isset($templatedata[0]['subject'])) echo $templatedata[0]['subject'];?>">
                      </div>
                  </div>
                  <div class="form-group required ">
                    <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_email_body');?></label>
                    <div class="col-md-5">
                      <textarea class="form-control input-sm" id="email_body" name="email_body"><?php if(isset($templatedata[0]['email_body'])) echo $templatedata[0]['email_body'];?></textarea>
                      
                  </div>
                  <div class="col-md-1" style="margin-top: 15%"><a href="javascript:void(0);" id="add"><img src="enlight/images/arrow-left.png"></a></div>
                    <div class="col-md-2" id="select_quote">
                      <select  id="variables" size="20"  multiple='multiple' class="form-control medwidth">
                      <?php
                          if(is_array($emailquotes) && count($emailquotes) > 0){

                            foreach ($emailquotes as $key => $quotes){ ?>      
                              <option value="<?php echo $quotes['quote_id']; ?>"><?php echo ucfirst($quotes['quotes']); ?>
                              </option>
                          <?php 
                            }
                        } ?>
                       <!-- <option value="{ADDITIONAL_TITLE}">{ADDITIONAL_TITLE}</option>
                        <option value="{ASSET_ID}">{ASSET_ID}</option>
                        <option value="{BUSINESS_UNIT}">{BUSINESS_UNIT}</option>
                        <option value="{BUSINESS_VERTICLE}">{BUSINESS_VERTICLE}</option>
                        <option value="{CLIENT_EMAILID}">{CLIENT_EMAILID}</option>
                        <option value="{CLIENT_NAME}">{CLIENT_NAME}</option>
                        <option value="{CMS_REQUEST_ID}">{CMS_REQUEST_ID}</option>-->
                      </select>

                    </div>
                  </div>
                  <div class="form-group required ">
                    <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.status');?></label>
                      <div class="col-md-6">
                        <div class="radio-custom">
                        <input type="radio" id="status_e"name="status" value="e" <?php if(isset($templatedata[0]['status']) && $templatedata[0]['status'] == 'e'){ echo "checked"; }else if(empty($templatedata)){ echo "checked"; } ?>>
                        <label for="status_e"><?php echo trans('label.lbl_enable');?></label>
                        <input type="radio" id="status_d" name="status" value="d" <?php if(isset($templatedata[0]['status']) && $templatedata[0]['status'] == 'd'){ echo "checked"; } ?>>
                        <label for="status_d"><?php echo trans('label.lbl_disable');?></label>
                      </div>
                      </div>
                  </div>
                  <div class="form-group">
                    <label class="col-md-3 control-label"></label>
                    <div class="col-xs-2">
                      <?php if($template_id != '') {?>
                      <button id="emailtemplateeditsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_update');?></button>
                      <?php }else{?>
                      <button id="emailtemplateaddsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_submit');?></button>
                      <?php } ?>
                    </div>
                    <div class="col-xs-2">
                        <button <?php if ($template_id != '') { ?> id ="update_reset" <?php }else{ ?>id="emailtemplate_reset" <?php } ?> type="reset" class="btn btn-info btn-block"><?php echo trans('label.btn_reset');?></button>
                    </div>
                  </div>
            </form>

            <?php if(config("app.env")!= 'production'){ ?>
            <form name="add_email_quotes" id="add_email_quotes">
              <div class="row">
                <div class="col-xs-1" style="margin-left: 1010px;margin-top: -90px;">
                <a href="javascript:void(0);" name="add_quote" id="add_quote" title="<?php echo trans('label.lbl_add_email_quotes');?>"><img src="enlight/images/add.png"></a>
                </div>
                <span id="add_quote_sec" style="display: none;">
                   <div class="col-xs-2" style="margin-left: 930px;margin-top: -50px;">
                     <input type="text" id="quotes" name="quotes" class="form-control input-sm" value=""  >
                  </div>
                  <div class="col-xs-1" style="margin-left: 1120px;margin-top: -50px;">
                   <a href="javascript:void(0);" name="add_quote1" id="add_quote1" onclick="emailquoteadd();"><img src="enlight/images/enter.png"></a>
                  </div>
                </span>
              </div>
              </div>
            </form>

          <?php }?>
          </div>
        </div>
      </div>
    </div>
<script>
    CKEDITOR.replace( 'email_body' );

$("#configure_email_id").click(function () {
            if ($(this).is(":checked")) {
                $("#conf_email_ids").show();
            } else {
                $("#conf_email_ids").hide();
            }
    });

$("#add_quote").click(function(){
   $("#add_quote_sec").show();
});

$("#add").click(function(){
 // This event fires when you click the add button
    $("#variables option:selected").each(function(){ // Loop through each selected 
      CKEDITOR.instances['email_body'].insertText($(this).text());
      //  $("#email_body").val($("#email_body").val() + $(this).text() + "\n"); // Add its innerhtml to the textarea
    });
});
</script>