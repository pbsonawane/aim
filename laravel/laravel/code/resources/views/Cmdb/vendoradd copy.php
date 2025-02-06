<div class="row">
    <div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">               
                <form class="form-horizontal"  name="addformvendor" id="addformvendor">
                    <input id="vendor_id" name="vendor_id" type="hidden" value="<?php echo $vendor_id?>">
                        <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_vendor_name');?></label>
                                <div class="col-md-8">
                                    <input type="text" id="vendor_name" name="vendor_name" class="form-control input-sm" value="<?php if(isset($vendordata[0]['vendor_name'])) echo $vendordata[0]['vendor_name'];?>">
                                </div>
                        </div>
                        <div class="form-group ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_vendor_reference');?></label>
                                <div class="col-md-8">
                                    <input type="text" id="vendor_ref_id" name="vendor_ref_id" class="form-control input-sm" value="<?php if(isset($vendordata[0]['vendor_ref_id'])) echo $vendordata[0]['vendor_ref_id'];?>">
                                </div>
                        </div>
                        <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_email');?></label>
                                <div class="col-md-8">
                                    <input type="text" id="vendor_email"  name="vendor_email" class="form-control input-sm" value="<?php if(isset($vendordata[0]['vendor_email'])) echo $vendordata[0]['vendor_email'];?>">
                                   
                                </div>
                        </div>
                        <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_contact_person');?></label>
                                <div class="col-md-8">
                                    <input type="text" id="contact_person" name="contact_person" class="form-control input-sm" value="<?php if(isset($vendordata[0]['contact_person'])) echo $vendordata[0]['contact_person'];?>">
                                </div>
                        </div>
                        <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_contact_no');?></label>
                                <div class="col-md-8">
                                    <input type="text" id="contactno" name="contactno" class="form-control input-sm" value="<?php if(isset($vendordata[0]['contactno'])) echo $vendordata[0]['contactno'];?>">
                                </div>
                        </div>
                        <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_address');?></label>
                                <div class="col-md-8">
                                     <textarea  id="address" name="address" class="form-control input-sm" ><?php if(isset($vendordata[0]['address'])) echo $vendordata[0]['address'];?></textarea>
                                </div>
                        </div>
                        <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_gstno');?></label>
                                <div class="col-md-8">
                                <input type="text" value="<?php if(isset($vendordata[0]['vendor_gst_no'])) echo $vendordata[0]['vendor_gst_no'];?>" id="vendor_gst_no" name="vendor_gst_no" class="form-control input-sm">
                                     
                                </div>
                        </div>
                         <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_pan');?></label>
                                <div class="col-md-8">
                                     <input type="text" id="vendor_pan" value="<?php if(isset($vendordata[0]['vendor_pan'])) echo $vendordata[0]['vendor_pan'];?>" name="vendor_pan" class="form-control input-sm">
                                     
                                </div>
                        </div>
                        
                        <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_bank_name');?></label>
                                <div class="col-md-8">
                                     <input type="text" id="bank_name" value="<?php if(isset($vendordata[0]['bank_name'])) echo $vendordata[0]['bank_name'];?>" name="bank_name" class="form-control input-sm">
                                     
                                </div>
                        </div>
                        <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_bank_address');?></label>
                                <div class="col-md-8">
                                     <textarea id="bank_address"   name="bank_address" class="form-control input-sm"><?php if(isset($vendordata[0]['bank_address'])) echo $vendordata[0]['bank_address'];?></textarea>
                                     
                                </div>
                        </div>
                        <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_bank_branch');?></label>
                                <div class="col-md-8">
                                     <input type="text" value="<?php if(isset($vendordata[0]['bank_branch'])) echo $vendordata[0]['bank_branch'];?>" id="bank_branch" name="bank_branch" class="form-control input-sm">
                                     
                                </div>
                        </div>
                        <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_bank_account_no');?></label>
                                <div class="col-md-8">
                                     <input type="text" value="<?php if(isset($vendordata[0]['bank_account_no'])) echo $vendordata[0]['bank_account_no'];?>" id="bank_account_no" name="bank_account_no" class="form-control input-sm">
                                
                                </div>
                        </div>
                        <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_ifsc_code');?></label>
                                <div class="col-md-8">
                                     <input type="text" value="<?php if(isset($vendordata[0]['ifsc_code'])) echo $vendordata[0]['ifsc_code'];?>" id="ifsc_code" name="ifsc_code" class="form-control input-sm">
                                     
                                </div>
                        </div>
                        <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_micr_code');?></label>
                                <div class="col-md-8">
                                     <input id="micr_code" value="<?php if(isset($vendordata[0]['micr_code'])) echo $vendordata[0]['micr_code'];?>"  type="text" name="micr_code" class="form-control input-sm">
                                     
                                </div>
                        </div>
                        <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_account_type');?></label>
                                <div class="col-md-8">
                                     <input type="text" value="<?php if(isset($vendordata[0]['account_type'])) echo $vendordata[0]['account_type'];?>" id="account_type" name="account_type" class="form-control input-sm">
                                     
                                </div>
                        </div>
                        
                        <?php 
                        if(isset($vendordata[0]['vendors_assets'])){
                            $vendors_assets = json_decode($vendordata[0]['vendors_assets'],true);
                            $vendors_assets = $vendors_assets['vendors_assets'];
                        }
                        
                        // print_r($vendors_assets);
                        ?>
                        <div class="form-group ">
                               <label for="inputStandard" class="col-md-3 control-label"><strong>Choose Assets:<span class="text-danger">*</span></strong></label>
                                
                                <div class="col-md-12">
                                    <?php $assets = $citemplates['content']['records'];

                                    foreach($assets as $key => $val){

                                        echo '<fieldset class="fieldsetCustom fieldset_IAM">
                                                <legend class="legendCustom">
                                                    <strong>'.$val['title'].'</strong>
                                                </legend>';
                                                 
                                        foreach($val['children'] as $items){
                                            $select = '';
                                            if(!empty($vendors_assets[$val['key']])){
                                                foreach($vendors_assets[$val['key']] as $item_ky){
                                                    if($item_ky == $items['key']){
                                                        $select = 'checked';
                                                    }
                                                }
                                            }
                                            
                                            echo '<div class="col-lg-2">
                                                    <div class="checkbox-custom  mb5">
                                                        <input '.$select.'  name="vendors_assets['.$val["key"].'][]" type="checkbox" id="'.$items['key'].'_advanced"  value="'.$items['key'].'">
                                                        <label for="'.$items['key'].'_advanced">'.$items['title'].'</label>
                                                    </div>
                                                </div>';
                                        }
                                        echo "<div class='clear'></div></fieldset>";
                                    }

                                    /*foreach($assets as $key => $val){
                                        echo '<div class="col-md-12"> <label for="inputStandard" class="col-md-3 control-label"><strong>'.$val['title'].':</strong></label>';
                                        foreach($val['children'] as $items){
                                            echo " <div class='checkbox-custom  mb5'><input type='checkbox' id='' 
                                            name='vendors_assets[".$val["key"]."][]' value='".$items['key']."'><label>".$items['title']." </label></div>";

                                        }
                                        echo "</div>";
                                    }*/
                                    ?>
                                </div>
                        </div>
                           
                        
                            
                        <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                            <div class="col-xs-2">
                        
                                <?php if($vendor_id != '') {?>
                                <button id="vendoreditsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_update');?></button>
                                <?php }else{?>
                                <button id="vendoraddsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_submit');?></button>
                                <?php } ?>
                            </div>
                            <div class="col-xs-2">
                                <button id="" type="reset" class="btn btn-info btn-block"><?php echo trans('label.btn_reset');?></button>
                            </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 