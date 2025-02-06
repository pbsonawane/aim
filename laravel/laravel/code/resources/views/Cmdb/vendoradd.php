<div class="row">
    <div class="col-md-10">
        <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div>
    <script>
    $(document).ready(function() {

        $("#meme_reg_num_div,.is_gstnumber_file,#legalNoticeYesDiv,#elaborate_serious_incidentsDIV,#elaborate_env_regulationDiv")
            .hide();
        // 
        $("#vendor_name,#name,#contact_person,#director_name,#sales_officer_name,#account_officer_name").keydown(function(event){
            var userGetData = event.which;  
            if((userGetData >= 97 && userGetData <= 105) || (userGetData >= 48 && userGetData <= 57) || 
            (userGetData >= 33 && userGetData <= 47) || 
            (userGetData >= 58 && userGetData <= 64) || 
            (userGetData >= 91 && userGetData <= 96) || 
            (userGetData >= 123 && userGetData <= 126)
            ) { 
                event.preventDefault(); 
            }
        });
        // 

        if ($("#is_msme_reg").val() == "Yes") {
            $("#meme_reg_num_div").show();
            $("#msme_certificate_div").show();
            
        } else {
            $("#meme_reg_num_div").hide();
            $("#msme_certificate_div").hide();
        }
        $("#is_msme_reg").on("change", function() {
            if ($(this).val() == "Yes") {
                $("#meme_reg_num_div").show();
                 $("#msme_certificate_div").show();
            } else {
                $("#meme_reg_num_div").hide();
                $("#msme_certificate_div").hide();
            }
        });

        $("#is_gstnumber_reg").on("change", function() {
            if ($(this).val() == "Yes") {
                $(".is_gstnumber_file").show();
            } else {
                $(".is_gstnumber_file").hide();
            }
        });

        // 
        if ($("#any_legal_notices").val() == "Yes") {
            $("#legalNoticeYesDiv").show();
        } else {
            $("#legalNoticeYesDiv").hide();
        }
        $("#any_legal_notices").on("change", function() {
            if ($(this).val() == "Yes") {
                $("#legalNoticeYesDiv").show();
            } else {
                $("#legalNoticeYesDiv").hide();
            }
        });

        // 
        if ($("#any_serious_incidents").val() == "Yes") {
            $("#elaborate_serious_incidentsDIV").show();
        } else {
            $("#elaborate_serious_incidentsDIV").hide();
        }
        $("#any_serious_incidents").on("change", function() {
            if ($(this).val() == "Yes") {
                $("#elaborate_serious_incidentsDIV").show();
            } else {
                $("#elaborate_serious_incidentsDIV").hide();
            }
        });

        // 
        if ($("#is_env_regulation").val() == "Yes") {
            $("#elaborate_env_regulationDiv").show();
        } else {
            $("#elaborate_env_regulationDiv").hide();
        }
        $("#is_env_regulation").on("change", function() {
            if ($(this).val() == "Yes") {
                $("#elaborate_env_regulationDiv").show();
            } else {
                $("#elaborate_env_regulationDiv").hide();
            }
        });
    });
    </script>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">
                <form class="form-horizontal" name="addformvendor" id="addformvendor" method="POST"
                    enctype="multipart/form-data">
                    <input id="vendor_id" name="vendor_id" type="hidden" value="<?php echo $vendor_id?>">
                    <label style="font-weight: bold;">1. Vendor Details</label>
                    <hr>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">1.1
                        Registered Business Name</label>
                        <div class="col-md-8">
                            <input type="text" id="vendor_name" name="vendor_name" class="form-control input-sm"
                                value="<?php if(isset($vendordata[0]['vendor_name'])) echo $vendordata[0]['vendor_name'];?>">
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="inputStandard" class="col-md-3 control-label">1.2
                            <?php echo trans('label.lbl_vendor_reference');?></label>
                        <div class="col-md-8">
                            <input type="text" id="vendor_ref_id" name="vendor_ref_id" class="form-control input-sm"
                                value="<?php if(isset($vendordata[0]['vendor_ref_id'])) echo $vendordata[0]['vendor_ref_id'];?>">
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="inputStandard" class="col-md-3 control-label">1.3 Warehouse Location</label>
                        <div class="col-md-8">
                            <textarea id="warehouse_location" name="warehouse_location" class="form-control input-sm"><?php if(isset($vendordata[0]['warehouse_location'])) echo $vendordata[0]['warehouse_location'];?></textarea>
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">1.4
                            <?php echo trans('label.lbl_contact_person');?></label>
                        <div class="col-md-8">
                            <input type="text" id="contact_person" name="contact_person" class="form-control input-sm"
                                value="<?php if(isset($vendordata[0]['contact_person'])) echo $vendordata[0]['contact_person'];?>">
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">1.5
                            <?php echo trans('label.lbl_contact_no');?></label>
                        <div class="col-md-8">
                            <input type="number" id="contactno" name="contactno" class="form-control input-sm"
                                value="<?php if(isset($vendordata[0]['contactno'])) echo $vendordata[0]['contactno'];?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">1.6
                            <?php echo trans('label.lbl_email');?></label>
                        <div class="col-md-8">
                            <input type="text" id="vendor_email" name="vendor_email" class="form-control input-sm"
                                value="<?php if(isset($vendordata[0]['vendor_email'])) echo $vendordata[0]['vendor_email'];?>">

                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">1.7 Registered Address</label>
                        <div class="col-md-8">
                            <textarea id="address" name="address"
                                class="form-control input-sm"><?php if(isset($vendordata[0]['address'])) echo $vendordata[0]['address'];?></textarea>
                        </div>
                    </div>

			<div class="form-group required ">
			<label for="inputStandard" class="col-md-3 control-label">State</label>
                        <div class="col-md-8">
                            <select name="vendor_state" id="vendor_state" class="form-control input-sm" required="required">
                                <option value="">Select</option>
				<option value="Andhra Pradesh">Andhra Pradesh</option>
				<option value="Arunachal Pradesh">Arunachal Pradesh</option>
				<option value="Assam">Assam</option>
				<option value="Bihar">Bihar</option>
				<option value="Chhattisgarh">Chhattisgarh</option>
				<option value="Goa">Goa</option>
				<option value="Gujarat">Gujarat</option>
				<option value="Haryana">Haryana</option>
				<option value="Himachal Pradesh">Himachal Pradesh</option>
				<option value="Jharkhand">Jharkhand</option>
				<option value="Karnataka">Karnataka</option>
				<option value="Kerala">Kerala</option>
				<option value="Madhya Pradesh">Madhya Pradesh</option>
				<option value="Maharashtra">Maharashtra</option>
				<option value="Manipur">Manipur</option>
				<option value="Meghalaya">Meghalaya</option>
				<option value="Mizoram">Mizoram</option>
				<option value="Nagaland">Nagaland</option>
				<option value="Odisha">Odisha</option>
				<option value="Punjab">Punjab</option>
				<option value="Rajasthan">Rajasthan</option>
				<option value="Sikkim">Sikkim</option>
				<option value="Tamil Nadu">Tamil Nadu</option>
				<option value="Telangana">Telangana</option>
				<option value="Tripura">Tripura</option>
				<option value="Uttar Pradesh">Uttar Pradesh</option>
				<option value="Uttarakhand">Uttarakhand</option>
				<option value="West Bengal">West Bengal</option>
				<option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
				<option value="Chandigarh">Chandigarh</option>
				<option value="Dadra and Nagar Haveli and Daman and Diu">Dadra and Nagar Haveli and Daman and Diu</option>
				<option value="Lakshadweep">Lakshadweep</option>
				<option value="Delhi">Delhi</option>
				<option value="Puducherry">Puducherry</option>

                            </select>
                        </div>
			</div>
			
                  
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">1.8 City</label>
                        <div class="col-md-8">
                            <input type="text" id="city" name="city" class="form-control input-sm"
                                value="<?php if(isset($vendordata[0]['city'])) echo $vendordata[0]['city'];?>">

                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">1.9 Pincode</label>
                        <div class="col-md-8">
                            <input type="number" id="pincode" name="pincode" class="form-control input-sm"
                                value="<?php if(isset($vendordata[0]['pincode'])) echo $vendordata[0]['pincode'];?>">
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">1.10
                            <?php echo trans('label.lbl_gstno');?></label>
                        <div class="col-md-8">
                            <input type="text"
                                value="<?php if(isset($vendordata[0]['vendor_gst_no'])) echo $vendordata[0]['vendor_gst_no'];?>"
                                id="vendor_gst_no" name="vendor_gst_no" class="form-control input-sm">

                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">1.11 GST Prof</label>
                        <div class="col-md-4">
                            <select name="is_gstnumber_reg" id="is_gstnumber_reg" class="form-control input-sm">
                                <option value="">Select</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                        </div>
                        <div class="col-md-4 is_gstnumber_file">
                            <input type="file" id="vendor_gst_no_file" name="vendor_gst_no_file"
                                class="form-control input-sm">
                            <label style="color:red">(only jepg,png,jpg,csv,txt,xlx,xls,pdf format accepted.)</label>
                        </div>
                        <?php if(isset($vendordata[0]['vendor_gst_no_file'])){
                            if($vendordata[0]['vendor_gst_no_file']!='')
                            {
                                echo '<label for="inputStandard" class="col-md-8">If You Upload a File, existing
                                File is override</label> ';
                            }
                        }
                        ?>
                        <input type="hidden" id="vendor_gst_no_file_url" name="vendor_gst_no_file_url"
                            class="form-control input-sm"
                            value="<?php if(isset($vendordata[0]['vendor_gst_no_file'])) echo $vendordata[0]['vendor_gst_no_file'];?>">
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">1.12
                            <?php echo trans('label.lbl_pan');?></label>
                        <div class="col-md-8">
                            <input type="text" id="vendor_pan"
                                value="<?php if(isset($vendordata[0]['vendor_pan'])) echo $vendordata[0]['vendor_pan'];?>"
                                name="vendor_pan" class="form-control input-sm">
                        </div>

                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">1.13 PAN Prof</label>
                        <div class="col-md-8">
                            <input type="file" id="vendor_pan_file" name="vendor_pan_file"
                                class="form-control input-sm">
                            <label style="color:red">(only jepg,png,jpg,csv,txt,xlx,xls,pdf format accepted.)</label>
                        </div>
                        <input type="hidden" id="vendor_pan_file_url" name="vendor_pan_file_url"
                            class="form-control input-sm"
                            value="<?php if(isset($vendordata[0]['vendor_pan_file'])) echo $vendordata[0]['vendor_pan_file'];?>">
                    </div>
                    <!--  -->
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">1.14 If firm is MSME registered
                            ?</label>
                        <div class="col-md-8">
                            <select name="is_msme_reg" id="is_msme_reg" class="form-control input-sm">
                                <option
                                    <?php if(isset($vendordata[0]['is_msme_reg'])){if($vendordata[0]['is_msme_reg'] == ''){echo "selected";}} ?>
                                    value="">Select</option>
                                <option
                                    <?php if(isset($vendordata[0]['is_msme_reg'])){if($vendordata[0]['is_msme_reg'] == 'Yes'){echo "selected";}} ?>
                                    value="Yes">Yes</option>
                                <option
                                    <?php if(isset($vendordata[0]['is_msme_reg'])){if($vendordata[0]['is_msme_reg'] == 'No'){echo "selected";}} ?>
                                    value="No">No</option>
                            </select>
                        </div>
                    </div>

                     <div class="form-group required" id="msme_certificate_div">
                       <label for="inputStandard" class="col-md-3 control-label">1.14.1 <?php echo trans('label.lbl_msme_certificate');?></label>
                        <div class="col-md-8">
                            <input type="file" id="msme_certificate" name="msme_certificate" class="form-control input-sm" value="<?php if(isset($vendordata[0]['msme_certificate'])) echo $vendordata[0]['msme_certificate'];?>">
                            <label style="color:red">(only jepg,png,jpg,csv,txt,xlx,xls,pdf format accepted.)</label>
                        </div>
                    </div>



                    <div class="form-group required" id="meme_reg_num_div">
                        <label for="inputStandard" class="col-md-3 control-label">1.14.2 if Yes, MSME Registration
                            Number</label>
                        <div class="col-md-8">
                            <input type="text" id="meme_reg_num" name="meme_reg_num" class="form-control input-sm"
                                value="<?php if(isset($vendordata[0]['meme_reg_num'])) echo $vendordata[0]['meme_reg_num'];?>">
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">1.15 Products/Services
                            Offered:</label>
                        <div class="col-md-8">
                            <input type="text" id="products_services_offered" name="products_services_offered"
                                class="form-control input-sm"
                                value="<?php if(isset($vendordata[0]['products_services_offered'])) echo $vendordata[0]['products_services_offered'];?>">
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">1.16 Association with OEM:</label>
                        <div class="col-md-8">
                            <input type="text" id="associate_oem" name="associate_oem" class="form-control input-sm"
                                value="<?php if(isset($vendordata[0]['associate_oem'])) echo $vendordata[0]['associate_oem'];?>">
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">1.17 Delivery Time</label>
                        <div class="col-md-8">
                            <input type="text" id="delivery_time" name="delivery_time" class="form-control input-sm"
                                value="<?php if(isset($vendordata[0]['delivery_time'])) echo $vendordata[0]['delivery_time'];?>">
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">1.18 Payment Terms</label>
                        <div class="col-md-8">
                            <textarea id="payment_terms" name="payment_terms"
                                class="form-control input-sm"><?php if(isset($vendordata[0]['payment_terms'])) echo $vendordata[0]['payment_terms'];?></textarea>
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">1.19 Annual Turnover</label>
                        <div class="col-md-8">
                            <input type="text" id="annual_turnover" name="annual_turnover" class="form-control input-sm"
                                value="<?php if(isset($vendordata[0]['annual_turnover'])) echo $vendordata[0]['annual_turnover'];?>">
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">1.20 Known Clients</label>
                        <div class="col-md-8">
                            <textarea id="known_client" name="known_client"
                                class="form-control input-sm"><?php if(isset($vendordata[0]['known_client'])) echo $vendordata[0]['known_client'];?></textarea>
                        </div>
                    </div>
                    <!--  -->
                    <label style="font-weight: bold;">2. Bank Details</label>
                    <hr>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">2.1
                            <?php echo trans('label.lbl_bank_name');?></label>
                        <div class="col-md-8">
                            <input type="text" id="bank_name"
                                value="<?php if(isset($vendordata[0]['bank_name'])) echo $vendordata[0]['bank_name'];?>"
                                name="bank_name" class="form-control input-sm">

                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">2.2
                            <?php echo trans('label.lbl_bank_branch');?></label>
                        <div class="col-md-8">
                            <input type="text"
                                value="<?php if(isset($vendordata[0]['bank_branch'])) echo $vendordata[0]['bank_branch'];?>"
                                id="bank_branch" name="bank_branch" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">2.3
                            <?php echo trans('label.lbl_bank_address');?></label>
                        <div class="col-md-8">
                            <textarea id="bank_address" name="bank_address"
                                class="form-control input-sm"><?php if(isset($vendordata[0]['bank_address'])) echo $vendordata[0]['bank_address'];?></textarea>
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">2.4 Bank Cancel Cheque</label>
                        <div class="col-md-8">
                            <input type="file" id="bank_name_file" name="bank_name_file" class="form-control input-sm">
                            <label style="color:red">(only jepg,png,jpg,csv,txt,xlx,xls,pdf format accepted.)</label>
                        </div>
                        <input type="hidden" id="bank_name_file_url" name="bank_name_file_url"
                            class="form-control input-sm"
                            value="<?php if(isset($vendordata[0]['bank_name_file'])) echo $vendordata[0]['bank_name_file'];?>">
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">2.5
                            <?php echo trans('label.lbl_account_type');?></label>
                        <div class="col-md-8">
                            <select id="account_type" name="account_type" class="form-control input-sm">
                                <option
                                    <?php if(isset($vendordata[0]['account_type'])){if($vendordata[0]['account_type'] == ''){echo "selected";}} ?>
                                    value="">Select</option>
                                <option
                                    <?php if(isset($vendordata[0]['account_type'])){if($vendordata[0]['account_type'] == 'CC Acount'){echo "selected";}} ?>
                                    value="CC Acount">CC Acount</option>
                                <option
                                    <?php if(isset($vendordata[0]['account_type'])){if($vendordata[0]['account_type'] == 'Saving Account'){echo "selected";}} ?>
                                    value="Saving Account">Saving Account</option>
                                <option
                                    <?php if(isset($vendordata[0]['account_type'])){if($vendordata[0]['account_type'] == 'Current Account'){echo "selected";}} ?>
                                    value="Current Account">Current Account</option>
                                <option
                                    <?php if(isset($vendordata[0]['account_type'])){if($vendordata[0]['account_type'] == 'OD Account'){echo "selected";}} ?>
                                    value="OD Account">OD Account</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">2.6
                            <?php echo trans('label.lbl_bank_account_no');?></label>
                        <div class="col-md-8">
                            <input type="text"
                                value="<?php if(isset($vendordata[0]['bank_account_no'])) echo $vendordata[0]['bank_account_no'];?>"
                                id="bank_account_no" name="bank_account_no" class="form-control input-sm">

                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">2.7
                            <?php echo trans('label.lbl_ifsc_code');?></label>
                        <div class="col-md-8">
                            <input type="text"
                                value="<?php if(isset($vendordata[0]['ifsc_code'])) echo $vendordata[0]['ifsc_code'];?>"
                                id="ifsc_code" name="ifsc_code" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="form-group required">
                        <label for="inputStandard" class="col-md-3 control-label">2.8
                            <?php echo trans('label.lbl_micr_code');?></label>
                        <div class="col-md-8">
                            <input id="micr_code"
                                value="<?php if(isset($vendordata[0]['micr_code'])) echo $vendordata[0]['micr_code'];?>"
                                type="text" name="micr_code" class="form-control input-sm">
                        </div>
                    </div>

                   

                    <!--  -->
                    <!--  -->
                    <label style="font-weight: bold;">3. Contact Details</label>
                    <hr>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">3.1.1 Director/Proprietor Name</label>
                        <div class="col-md-8">
                            <input type="text"
                                value="<?php if(isset($vendordata[0]['director_name'])) echo $vendordata[0]['director_name'];?>"
                                id="director_name" name="director_name" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">3.1.2 Director Contact Number</label>
                        <div class="col-md-8">
                            <input type="number"
                                value="<?php if(isset($vendordata[0]['director_contact_no'])) echo $vendordata[0]['director_contact_no'];?>"
                                id="director_contact_no" name="director_contact_no" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">3.1.3 Director Email</label>
                        <div class="col-md-8">
                            <input type="text"
                                value="<?php if(isset($vendordata[0]['director_email'])) echo $vendordata[0]['director_email'];?>"
                                id="director_email" name="director_email" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">3.2.1 Sales Officer Name</label>
                        <div class="col-md-8">
                            <input type="text"
                                value="<?php if(isset($vendordata[0]['sales_officer_name'])) echo $vendordata[0]['sales_officer_name'];?>"
                                id="sales_officer_name" name="sales_officer_name" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">3.2.2 Sales Officer Contact
                            Number</label>
                        <div class="col-md-8">
                            <input type="number"
                                value="<?php if(isset($vendordata[0]['sales_officer_contact_no'])) echo $vendordata[0]['sales_officer_contact_no'];?>"
                                id="sales_officer_contact_no" name="sales_officer_contact_no"
                                class="form-control input-sm">
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">3.2.3 Sales Officer Email</label>
                        <div class="col-md-8">
                            <input type="text"
                                value="<?php if(isset($vendordata[0]['sales_officer_email'])) echo $vendordata[0]['sales_officer_email'];?>"
                                id="sales_officer_email" name="sales_officer_email" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">3.3.1 Account Officer Name</label>
                        <div class="col-md-8">
                            <input type="text"
                                value="<?php if(isset($vendordata[0]['account_officer_name'])) echo $vendordata[0]['account_officer_name'];?>"
                                id="account_officer_name" name="account_officer_name" class="form-control input-sm">
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">3.3.2 Account Officer Contact
                            Number</label>
                        <div class="col-md-8">
                            <input type="number"
                                value="<?php if(isset($vendordata[0]['account_officer_contact_no'])) echo $vendordata[0]['account_officer_contact_no'];?>"
                                id="account_officer_contact_no" name="account_officer_contact_no"
                                class="form-control input-sm">
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">3.3.3 Account Officer Email</label>
                        <div class="col-md-8">
                            <input type="text"
                                value="<?php if(isset($vendordata[0]['account_officer_email'])) echo $vendordata[0]['account_officer_email'];?>"
                                id="account_officer_email" name="account_officer_email" class="form-control input-sm">
                        </div>
                    </div>
                    <!--  -->
                    <!--  -->
                    <label style="font-weight: bold;">4. Compliance Section</label>
                    <hr>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-5 control-label">4.1 Have any legal
                            notices been served to the company in the last 1 or 2 years by any of the
                            authorities?</label>
                        <div class="col-md-6">
                            <select name="any_legal_notices" id="any_legal_notices" class="form-control input-sm">
                                <option
                                    <?php if(isset($vendordata[0]['any_legal_notices'])){if($vendordata[0]['any_legal_notices'] == ''){echo "selected";}} ?>
                                    value="">Select</option>
                                <option
                                    <?php if(isset($vendordata[0]['any_legal_notices'])){if($vendordata[0]['any_legal_notices'] == 'Yes'){echo "selected";}} ?>
                                    value="Yes">Yes</option>
                                <option
                                    <?php if(isset($vendordata[0]['any_legal_notices'])){if($vendordata[0]['any_legal_notices'] == 'No'){echo "selected";}} ?>
                                    value="No">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group required" id="legalNoticeYesDiv">
                        <label for="inputStandard" class="col-md-5 control-label">4.1.1 If Yes please elaborate</label>
                        <div class="col-md-6">
                            <textarea id="legal_notice_elaborate" name="legal_notice_elaborate"
                                class="form-control input-sm"><?php if(isset($vendordata[0]['legal_notice_elaborate'])) echo $vendordata[0]['legal_notice_elaborate'];?></textarea>
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-5 control-label">4.2 Is the company
                            compliant with all the mandatory EHS statutory and legal
                            requirements?</label>
                        <div class="col-md-6">
                            <select name="is_legal_requirements" id="is_legal_requirements"
                                class="form-control input-sm">
                                <option
                                    <?php if(isset($vendordata[0]['is_legal_requirements'])){if($vendordata[0]['is_legal_requirements'] == ''){echo "selected";}} ?>
                                    value="">Select</option>
                                <option
                                    <?php if(isset($vendordata[0]['is_legal_requirements'])){if($vendordata[0]['is_legal_requirements'] == 'Yes'){echo "selected";}} ?>
                                    value="Yes">Yes</option>
                                <option
                                    <?php if(isset($vendordata[0]['is_legal_requirements'])){if($vendordata[0]['is_legal_requirements'] == 'No'){echo "selected";}} ?>
                                    value="No">No</option>
                                <option
                                    <?php if(isset($vendordata[0]['is_legal_requirements'])){if($vendordata[0]['is_legal_requirements'] == 'Not Applicable'){echo "selected";}} ?>
                                    value="Not Applicable">Not Applicable</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-5 control-label">4.3 What is the minimum
                            age of your workers/employees?</label>
                        <div class="col-md-6">
                            <input type="number" id="worker_minimum_age" name="worker_minimum_age"
                                class="form-control input-sm"
                                value="<?php if(isset($vendordata[0]['worker_minimum_age'])) echo $vendordata[0]['worker_minimum_age'];?>" />
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-5 control-label">4.4 Do the
                            workers/employees have to submit any of their original governmental IDs to
                            your company, while they join the company?</label>
                        <div class="col-md-6">
                            <select name="submit_original_documents" id="submit_original_documents"
                                class="form-control input-sm">
                                <option
                                    <?php if(isset($vendordata[0]['submit_original_documents'])){if($vendordata[0]['submit_original_documents'] == ''){echo "selected";}} ?>
                                    value="">Select</option>
                                <option
                                    <?php if(isset($vendordata[0]['submit_original_documents'])){if($vendordata[0]['submit_original_documents'] == 'Yes'){echo "selected";}} ?>
                                    value="Yes">Yes</option>
                                <option
                                    <?php if(isset($vendordata[0]['submit_original_documents'])){if($vendordata[0]['submit_original_documents'] == 'No'){echo "selected";}} ?>
                                    value="No">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-5 control-label">4.5 Have there been any
                            serious incidents/accidents to any of their workers at any of the sites in
                            the last 2 years?</label>
                        <div class="col-md-6">
                            <select name="any_serious_incidents" id="any_serious_incidents"
                                class="form-control input-sm">
                                <option
                                    <?php if(isset($vendordata[0]['any_serious_incidents'])){if($vendordata[0]['any_serious_incidents'] == ''){echo "selected";}} ?>
                                    value="">Select</option>
                                <option
                                    <?php if(isset($vendordata[0]['any_serious_incidents'])){if($vendordata[0]['any_serious_incidents'] == 'Yes'){echo "selected";}} ?>
                                    value="Yes">Yes</option>
                                <option
                                    <?php if(isset($vendordata[0]['any_serious_incidents'])){if($vendordata[0]['any_serious_incidents'] == 'No'){echo "selected";}} ?>
                                    value="No">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group required" id="elaborate_serious_incidentsDIV">
                        <label for="inputStandard" class="col-md-5 control-label">4.5.1 If Yes please
                            elaborate</label>
                        <div class="col-md-6">
                            <textarea id="elaborate_serious_incidents" name="elaborate_serious_incidents"
                                class="form-control input-sm"><?php if(isset($vendordata[0]['elaborate_serious_incidents'])) echo $vendordata[0]['elaborate_serious_incidents'];?></textarea>
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-5 control-label">4.6 Do the Company have
                            an Anti-Corruption and Bribery Policy, Policy on POSH, Anti Child Labour and
                            Forced and Bonded Labour Policy?</label>
                        <div class="col-md-6">
                            <select name="is_anti_bribe_policy" id="is_anti_bribe_policy" class="form-control input-sm">
                                <option
                                    <?php if(isset($vendordata[0]['is_anti_bribe_policy'])){if($vendordata[0]['is_anti_bribe_policy'] == ''){echo "selected";}} ?>
                                    value="">Select</option>
                                <option
                                    <?php if(isset($vendordata[0]['is_anti_bribe_policy'])){if($vendordata[0]['is_anti_bribe_policy'] == 'Yes'){echo "selected";}} ?>
                                    value="Yes">Yes</option>
                                <option
                                    <?php if(isset($vendordata[0]['is_anti_bribe_policy'])){if($vendordata[0]['is_anti_bribe_policy'] == 'No'){echo "selected";}} ?>
                                    value="No">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-5 control-label">4.7 Do the Company have
                            an environmental, health, safety and social (EHS&S) policy?</label>
                        <div class="col-md-6">
                            <select name="is_health_safety_policy" id="is_health_safety_policy"
                                class="form-control input-sm">
                                <option
                                    <?php if(isset($vendordata[0]['is_health_safety_policy'])){if($vendordata[0]['is_health_safety_policy'] == ''){echo "selected";}} ?>
                                    value="">Select</option>
                                <option
                                    <?php if(isset($vendordata[0]['is_health_safety_policy'])){if($vendordata[0]['is_health_safety_policy'] == 'Yes'){echo "selected";}} ?>
                                    value="Yes">Yes</option>
                                <option
                                    <?php if(isset($vendordata[0]['is_health_safety_policy'])){if($vendordata[0]['is_health_safety_policy'] == 'No'){echo "selected";}} ?>
                                    value="No">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-5 control-label">4.8 Indicate whether your
                            organization has been found to be out of compliance with any local labor,
                            tax, or environmental regulations in 1-2 years?</label>
                        <div class="col-md-6">
                            <select name="is_env_regulation" id="is_env_regulation" class="form-control input-sm">
                                <option
                                    <?php if(isset($vendordata[0]['is_env_regulation'])){if($vendordata[0]['is_env_regulation'] == ''){echo "selected";}} ?>
                                    value="">Select</option>
                                <option
                                    <?php if(isset($vendordata[0]['is_env_regulation'])){if($vendordata[0]['is_env_regulation'] == 'Yes'){echo "selected";}} ?>
                                    value="Yes">Yes</option>
                                <option
                                    <?php if(isset($vendordata[0]['is_env_regulation'])){if($vendordata[0]['is_env_regulation'] == 'No'){echo "selected";}} ?>
                                    value="No">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group required " id="elaborate_env_regulationDiv">
                        <label for="inputStandard" class="col-md-5 control-label">4.8.1 If Yes please
                            elaborate</label>
                        <div class="col-md-6">
                            <textarea id="elaborate_env_regulation" name="elaborate_env_regulation"
                                class="form-control input-sm"><?php if(isset($vendordata[0]['elaborate_env_regulation'])) echo $vendordata[0]['elaborate_env_regulation'];?></textarea>
                        </div>
                    </div>

                    <!--  -->
                    <label style="font-weight: bold;">5. Acknowledgment Section</label>
                    <hr>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">5.1 Name</label>
                        <div class="col-md-8">
                            <input type="text" id="name" name="name" class="form-control input-sm"
                                value="<?php if(isset($vendordata[0]['name'])) echo $vendordata[0]['name'];?>">
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">5.2 Date</label>
                        <div class="col-md-8">
                            <input id="date" type="date" name="date" class="form-control input-sm"
                                value="<?php if(isset($vendordata[0]['date'])) echo $vendordata[0]['date'];?>">
                        </div>
                    </div>
                    <div class="form-group required ">
                        <label for="inputStandard" class="col-md-3 control-label">5.3 Designation</label>
                        <div class="col-md-8">
                            <input type="text" id="designation" name="designation" class="form-control input-sm"
                                value="<?php if(isset($vendordata[0]['designation'])) echo $vendordata[0]['designation'];?>">
                        </div>
                    </div>
                    <div class="errorTxt"></div>
                    <!--  -->

                    <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                        <div class="col-xs-2">

                            <?php if($vendor_id != '') {?>
                            <button id="vendoreditsubmit" type="submit"
                                class="btn btn-success btn-block"><?php echo trans('label.btn_update');?></button>
                            <?php }else{?>
                            <button id="vendoraddsubmit" type="submit"
                                class="btn btn-success btn-block"><?php echo trans('label.btn_submit');?></button>
                            <?php } ?>
                        </div>
                        <div class="col-xs-2">
                            <button id="" type="reset"
                                class="btn btn-info btn-block"><?php echo trans('label.btn_reset');?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>