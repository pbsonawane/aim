var postData = null;
$(document).ready(function () {
vendorList();
$(document).on("click", "#vendoradd", function () { vendoradd(); });
$(document).on("click", "#vendoraddsubmit", function () { 
    vendoraddsubmitvalidate(); 
});
$(document).on("click", ".vendor_edit", function () { var vendor_id = $(this).attr('id'); vendoredit(vendor_id); });
$(document).on("click", "#vendoreditsubmit", function () { vendoreditsubmitvalidate(); });
$(document).on("click", ".vendor_del", function () { var vendor_id = $(this).attr('id'); vendordelete(vendor_id); });
$(document).on("click", "#btn_reset", function () { resetForm('addformvendor'); });
});


    $(".download_vendor_docs").click(function() {
        var att_id = $(this).attr('download_id');
        var att_path = $(this).attr('download_path');
        var att_title = $(this).attr('download_title');

        var postData    = { 'attach_id': att_id,'attach_path': att_path, 'attach_title': att_title};
        var url = SITE_URL + '/download_vendor_docs';
        emLoader('show', trans('label.lbl_loading'));
        var result_ajax = ajaxCall(result_ajax, url, postData, function (data) {
            var result = JSON.parse(data);

            if (result.is_error) {
                showResponse(data, 'grid_data', 'msg_div');
                emLoader('hide');
                window.scrollTo(0, 0);
            } else {
                emLoader('hide');
                lightbox('hide');
                var data_arr = JSON.parse(data);
                var download_path = SITE_URL +'/'+ data_arr['html'];

                //download attachment
                var a = document.createElement('a');
                a.setAttribute('href', download_path);
                a.setAttribute('download','');

                var aj = $(a);
                aj.appendTo('body');
                aj[0].click();
                aj.remove();

                window.scrollTo(0, 0);
            }
        }); 
    });


    $(document).on({
        click: function() {
            var id = $(this).attr('id');
            approveReject_vendor('approve', id);
        }
    }, ".approve_vendor button");
    
    $(document).on({
        click: function() {
            var id = $(this).attr('id');
            approveReject_vendor('disapprove', id);
        }
    }, ".reject_vendor button");
    
    function approveReject_vendor(label, id){
        if (confirm('Are you sure you want to '+ label +' this Vendor ?')) {
            var approval_status = id.split('_')[0];
            var vendor_id = id.split('_')[1];

            $('#myModal_approve_reject_vendor').modal('show');
            // $("#modal-title_approve_reject").html();
            $("#myModal_approve_reject_vendor #vendor_id").val(vendor_id);
            $("#myModal_approve_reject_vendor #approval_status").val(approval_status); 
            emLoader('hide');
        }
    }

    /* Submit Quotation Comparison Reject Comment */
    $(document).on("click", "#submit_approve_reject_vendor", function() {
        var url = '';
        var postData = $("#formComment_vendor").serialize();
        url = SITE_URL + '/approve_reject_vendor';
        if (url != '') 
        {
            var vendorSubmitajax = ajaxCall(vendorSubmitajax, url, postData, function(data) {
                var response = JSON.parse(data);
                if (response.is_error) {
                    showResponse(data, '', 'msg_div');
                    emLoader('hide');
                    $('#myModal_approve_reject_vendor').modal('hide');
                    window.scrollTo(0, 0);
                } else {
                    emLoader('hide');
                    $('#myModal_approve_reject_vendor').modal('hide');
                    setTimeout(function () {
                        alert('Status Updated Successfully.');
                        setTimeout(function () {
                            location.reload();
                        }, 500);
                    }, 300);
                }
                closeMsgAuto('msg_div');
            });
        }
    });
    $('#multiple').change(function(){
        var search_service = $(this).val();
        if(search_service==null)
        {
            vendorList();
        }
      });
    $(document).on("click", "#SearchVendorServices", function() 
    {
        var search_service = $('#multiple').val();
        if(search_service=="")
        {
            return false;
        }
        closeMsgAuto('msg_div');
        emLoader('show', trans('messages.msg_vendor_loading'));
        
        var url = SITE_URL + '/vendor/servicesearch';
        var postData = $("#frmdevices").serialize();
        var mongraphsajax = ajaxCall(mongraphsajax, url, postData, function (data) {
            showResponse(data, 'grid_data', 'msg_div');
            emLoader('hide');
        });
    });

    function vendorList(){
        closeMsgAuto('msg_div');
        emLoader('show', trans('messages.msg_vendor_loading'));
        var url = SITE_URL + '/vendor/list';
        //alert(url);
        var postData = $("#frmdevices").serialize();
       // alert(postData);
        var exporttype = $("#frmdevices input[name=exporttype]").val();
        if (exporttype == 'pdf' || exporttype == 'csv' || exporttype == 'print') {
            var obj_form = document.frmdevices;
            var mywindow = submitForm(url, obj_form, 1, 1);
            $("#frmdevices input[name=exporttype]").val('');
            $("#frmdevices input[name=page]").val('');
            emLoader('hide');
        }
        else {
            var mongraphsajax = ajaxCall(mongraphsajax, url, postData, function (data) {
                showResponse(data, 'grid_data', 'msg_div');
                emLoader('hide');
            });
        }
    }

    function vendoradd() {        
        closeMsgBox('msg_div');
        emLoader('show', trans('messages.msg_session_open'));
        var url = SITE_URL + '/vendor/add';
        var notifyajax = ajaxCall(notifyajax, url, {}, function (data) {
            lightbox('show', data, trans('messages.msg_vendor_add'), 'large');
            emLoader('hide');
        });    
    }
    
    // Vendor Submit Event Start
    function vendoraddsubmitvalidate()
    {      
        $("#addformvendor").validate({                
            rules: {
                vendor_name: {
                    required: true,
                    maxlength: 255
                },
                acknowledgment: {
                    required: true
                },
                warehouse_location: {
                    required: true,
                    maxlength: 500
                },
                contact_person: {
                    required: true,
                    maxlength: 50
                },
                contactno: {
                    required: true,
                    maxlength: 10,
                    minlength: 10,
                    digits: true
                },
                vendor_email: {
                    required: true,
                    maxlength: 255,
                    email: true,
                },
                address: {
                    required: true,
                    maxlength: 255
                },
                city: {
                    required: true,
                    maxlength: 255
                },
                pincode: {
                    required: true,
                    maxlength: 6,
                    digits: true
                },
                vendor_gst_no: {
                    required: true,
                    maxlength: 15
                },
                vendor_pan: {
                    required: true,
                    maxlength: 10
                },
                is_msme_reg: {
                    required: true,
                    maxlength: 50
                },
                meme_reg_num: {
                    required: function () {
                        var is_check;
                        return is_check = ($('#is_msme_reg option:selected').val() == 'Yes') ? true : false;
                    },
                    digits: true
                },

                 msme_certificate: {
                    required: function () {
                        var is_check;
                        return is_check = ($('#is_msme_reg option:selected').val() == 'Yes') ? true : false;
                    },
                    digits: true
                },

                is_gstnumber_reg: {
                    required: true,
                    maxlength: 50
                },
                products_services_offered: {
                    required: true,
                },
                associate_oem: {
                    required: true,
                },
                delivery_time: {
                    required: true,
                    maxlength: 100
                },
                payment_terms: {
                    required: true,
                },
                annual_turnover: {
                    required: true,
                    maxlength: 100,
                },
                known_client: {
                    required: true,
                },
                bank_name: {
                    required: true,
                    maxlength: 255
                },
                bank_address: {
                    required: true,
                },
                bank_branch: {
                    required: true,
                    maxlength: 255
                },
                account_type: {
                    required: true,
                    maxlength: 255
                },
                bank_account_no: {
                    required: true,
                    digits:true,
                    minlength: 9,
                    maxlength: 20
                },
                ifsc_code: {
                    required: true,
                    maxlength: 11
                },
                director_name: {
                    required: true,
                },
                director_contact_no: {
                    required: true,
                    maxlength: 10,
                    minlength: 10,
                },
                director_email: {
                    required: true,
                    maxlength: 100,
                    email: true,
                },
                sales_officer_name: {
                    required: true,
                },
                sales_officer_contact_no: {
                    required: true,
                    maxlength: 10,
                    minlength: 10,
                },
                sales_officer_email: {
                    required: true,
                    maxlength: 100,
                    email: true,
                },
                account_officer_name: {
                    required: true,
                },
                account_officer_contact_no: {
                    required: true,
                    maxlength: 10,
                    minlength: 10,
                },
                account_officer_email: {
                    required: true,
                    maxlength: 100,
                    email: true,
                },
                any_legal_notices: {
                    required: true,
                    maxlength: 50
                },
                legal_notice_elaborate: {
                    required: true,
                    maxlength: 255
                },
                is_legal_requirements: {
                    required: true,
                    maxlength: 100
                },
                worker_minimum_age: {
                    required: true,
                    maxlength: 100
                },
                submit_original_documents: {
                    required: true,
                    maxlength: 100
                },
                any_serious_incidents: {
                    required: true,
                    maxlength: 100
                },
                elaborate_serious_incidents: {
                    required: true,
                    maxlength: 255
                },
                is_anti_bribe_policy: {
                    required: true,
                    maxlength: 100
                },
                is_health_safety_policy: {
                    required: true,
                    maxlength: 100
                },
                is_env_regulation: {
                    required: true,
                    maxlength: 100
                },
                elaborate_env_regulation: {
                    required: true,
                    maxlength: 255
                },
                name: {
                    required: true,
                },
                date: {
                    required: true,
                    maxlength: 100
                },
                designation: {
                    required: true,
                    maxlength: 100
                },
                vendor_gst_no_file: {
                    required: true,
                    extension: "jpeg|png|jpg|csv|txt|xlx|xls|pdf"
                },
                vendor_pan_file: {
                    required: true,
                    extension: "jpeg|png|jpg|csv|txt|xlx|xls|pdf"
                },
                bank_name_file: {
                    required: true,
                    extension: "jpeg|png|jpg|csv|txt|xlx|xls|pdf"
                },
                msme_certificate: {
                    required: true,
                    extension: "jpeg|png|jpg|csv|txt|xlx|xls|pdf"
                },
            },
            messages: {
                vendor_name: {
                    required: "Please enter Vendor Name",
                    maxlength: "Your Vendor Name maxlength should be 255 characters long."
                },
                warehouse_location: {
                    required: "Please enter Warehouse Location",
                    maxlength: "Your Warehouse Location maxlength should be 500 characters long."
                },
                contact_person: {
                    required: "Please enter Contact Person",
                    maxlength: "Your Contact Person maxlength should be 50 characters long."
                },
                contactno: {
                    required: "Please enter Contact Number",
                    maxlength: "Your Contact Number maxlength should be 10 characters long."
                },
                vendor_email: {
                    required: "Please enter Vendor Email",
                    maxlength: "Your Vendor Email maxlength should be 255 characters long."
                },
                address: {
                    required: "Please enter Address",
                    maxlength: "Your Address maxlength should be 255 characters long."
                },
                city: {
                    required: "Please enter City",
                    maxlength: "Your City maxlength should be 255 characters long."
                },
                pincode: {
                    required: "Please enter Pin Code",
                    maxlength: "Your Pin Code maxlength should be 6 characters long."
                },
                vendor_gst_no: {
                    required: "Please enter Vendor GST No",
                    maxlength: "Your Vendor GST No maxlength should be 15 characters long."
                },
                vendor_pan: {
                    required: "Please enter Vendor PAN No",
                    maxlength: "Your Vendor PAN No maxlength should be 10 characters long."
                },
                is_msme_reg: {
                    required: "Please Select Here",
                    maxlength: "Your MSME Register No maxlength should be 50 characters long."
                },
                meme_reg_num: {
                    required: "Please enter MEME Registration No",
                },

                is_gstnumber_reg: {
                    required: "Please Select Here",
                },
                products_services_offered: {
                    required: "Please enter Product/Services Offered.",
                },
                associate_oem: {
                    required: "Please enter Associate OEM",
                },
                delivery_time: {
                    required: "Please enter Delivery Time",
                    maxlength: "Your Delivery Time maxlength should be 100 characters long."
                },
                payment_terms: {
                    required: "Please enter Payment Terms",
                },
                annual_turnover: {
                    required: "Please enter Annual Turnover",
                    maxlength: "Your Annual Turnover maxlength should be 100 characters long."
                },
                known_client: {
                    required: "Please enter Known Clients",
                },
                bank_name: {
                    required: "Please enter Bank Name",
                    maxlength: "Your Bank Name maxlength should be 255 characters long."
                },
                bank_branch: {
                    required: "Please enter Branch Name",
                    maxlength: "Your Branch Name maxlength should be 255 characters long."
                },
                bank_address: {
                    required: "Please enter Bank Address",
                },
                account_type: {
                    required: "Please Select Here",
                    maxlength: "Your Account Type maxlength should be 255 characters long."
                },
                bank_account_no: {
                    required: "Please Enter Account Number",
                },
                ifsc_code: {
                    required: "Please Enter IFSC Code",
                    maxlength: "Your IFSC Code maxlength should be 40 characters long."
                },
                director_name: {
                    required: "Please Enter Director Name",
                },
                director_contact_no: {
                    required: "Please Enter Director Contact No",
                    maxlength: "Your Director Contact No maxlength should be 10 characters long."
                },
                director_email: {
                    required: "Please Enter Director Email",
                    maxlength: "Your Director Email maxlength should be 100 characters long."
                },
                sales_officer_name: {
                    required: "Please Enter Sales Officer Name",
                },
                sales_officer_contact_no: {
                    required: "Please Enter Sales Officer Contact No",
                    maxlength: "Your Sales Officer Contact No maxlength should be 10 characters long."
                },
                sales_officer_email: {
                    required: "Please Enter Sales Officer Email",
                    maxlength: "Your Sales Officer Email maxlength should be 100 characters long."
                },
                account_officer_name: {
                    required: "Please Enter Account Officer Name",
                },
                account_officer_contact_no: {
                    required: "Please Enter Account Officer Contact No",
                    maxlength: "Your Account Officer Contact No maxlength should be 10 characters long."
                },
                account_officer_email: {
                    required: "Please Enter Account Officer Email",
                    maxlength: "Your Account Officer Email maxlength should be 100 characters long."
                },
                any_legal_notices: {
                    required: "Please Select Here",
                    maxlength: "Your Any Legal Notices maxlength should be 100 characters long."
                },
                is_legal_requirements: {
                    required: "Please Select Here",
                    maxlength: "Your Is Legal Requirements maxlength should be 100 characters long."
                },
                submit_original_documents: {
                    required: "Please Select Here",
                    maxlength: "Your Is Submit Original Documents maxlength should be 100 characters long."
                },
                any_serious_incidents: {
                    required: "Please Select Here",
                    maxlength: "Your Any Serious Incidents maxlength should be 100 characters long."
                },
                elaborate_serious_incidents: {
                    required: "Please Enter Here",
                    maxlength: "Your maxlength should be 255 characters long."
                },
                legal_notice_elaborate: {
                    required: "Please Enter Here",
                    maxlength: "Your maxlength should be 255 characters long."
                },
                elaborate_env_regulation: {
                    required: "Please Enter Here",
                    maxlength: "Your maxlength should be 255 characters long."
                },
                is_anti_bribe_policy: {
                    required: "Please Select Here",
                    maxlength: "Your Anti Bribe Policy maxlength should be 100 characters long."
                },
                is_health_safety_policy: {
                    required: "Please Select Here",
                    maxlength: "Your Health Safety Policy maxlength should be 100 characters long."
                },
                is_env_regulation: {
                    required: "Please Select Here",
                    maxlength: "Your Environment Regulation maxlength should be 100 characters long."
                },
                name: {
                    required: "Please Enter Name",
                },
                date: {
                    required: "Please Select Date",
                    maxlength: "Your Date maxlength should be 100 characters long."
                },
                designation: {
                    required: "Please Enter Designation",
                    maxlength: "Your Designation maxlength should be 100 characters long."
                },
                vendor_gst_no_file: {
                    required: "Please upload Vendor GST No File",
                    maxlength: "File accept only jepg|png|jpg|csv|txt|xlx|xls|pdf."
                },
                vendor_pan_file: {
                    required: "Please upload Vendor PAN No File",
                    maxlength: "File accept only jepg|png|jpg|csv|txt|xlx|xls|pdf."
                },
                bank_name_file: {
                    required: "Please upload Bank Proof File",
                    maxlength: "File accept only jepg|png|jpg|csv|txt|xlx|xls|pdf."
                },
                 msme_certificate: {
                    required: "Please upload  MSME Certificate",
                    maxlength: "File accept only jepg|png|jpg|csv|txt|xlx|xls|pdf."
                },
            }, 
            submitHandler: function() {  
                vendoraddsubmit();
            }                  
        })        
    }

    function vendoraddsubmit() {
        clearMsg('msg_popup');
        if (cltimer) {
            clas = 'error';
            showAlert("msg_popup", clas,  trans('messages.msg_session_open'));
            return false;
        }
        emLoader('show', 'Vendor');        
        var url = SITE_URL + '/vendor/addsubmit';
        // var postData = $("#addformvendor").serialize();
        // console.log(postData);
        // var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        //     var result = JSON.parse(data);
        //     console.log(result);
        //     if (result.is_error) {
        //         showResponse(data, '',  'msg_popup');
        //         emLoader('hide');
        //     }
        //     else {
        //         emLoader('hide');
        //         lightbox('hide');
                
        //         window.scrollTo(0, 0);
        //         showResponse(data, 'grid_data', 'msg_div');
        //         vendorList();
        //     }    
        // });
        var postData = new FormData(document.getElementById('addformvendor')); 
                     
        var userajax = ajaxCall_test(userajax, url, postData, function(data) {
            var result = JSON.parse(data);

            console.log(result);
            if (result.is_error) {
                showResponse(data, '',  'msg_popup');
                emLoader('hide');
            }
            else {
                emLoader('hide');
                lightbox('hide');
                
                window.scrollTo(0, 0);
                showResponse(data, 'grid_data', 'msg_div');
                vendorList();
            }  
        });
    }

    // Vendor Submit Event End
    function vendoredit(vendor_id) {
        emLoader('show', trans('messages.msg_updating_vendor'));
        var id = vendor_id.split('_')[1];
        var postData = { 'datatype': 'json', 'id': id };
        console.log(postData);
        var url = SITE_URL + '/vendor/edit';
       // alert(id);
        var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
            lightbox('show', data, trans('messages.msg_vendor_edit'), 'large');
            emLoader('hide');
           // alert(id);
    
        });
    }

    // Vendor Edit Event Start
    function vendoreditsubmitvalidate()
    {      
        $("#addformvendor").validate({                
            rules: {
                vendor_name: {
                    required: true,
                    maxlength: 255
                },
                acknowledgment: {
                    required: true
                },
                warehouse_location: {
                    required: true,
                    maxlength: 500
                },
                contact_person: {
                    required: true,
                    maxlength: 50
                },
                contactno: {
                    required: true,
                    maxlength: 10,
                    minlength: 10,
                    digits: true
                },
                vendor_email: {
                    required: true,
                    maxlength: 255,
                    email: true,
                },
                address: {
                    required: true,
                    maxlength: 255
                },
                city: {
                    required: true,
                    maxlength: 255
                },
                pincode: {
                    required: true,
                    maxlength: 6,
                    digits: true
                },
                vendor_gst_no: {
                    required: true,
                    maxlength: 15
                },
                vendor_pan: {
                    required: true,
                    maxlength: 10
                },
                is_msme_reg: {
                    required: true,
                    maxlength: 50
                },
                is_gstnumber_reg: {
                    required: true,
                    maxlength: 50
                },
                products_services_offered: {
                    required: true,
                },
                associate_oem: {
                    required: true,
                },
                delivery_time: {
                    required: true,
                    maxlength: 100
                },
                payment_terms: {
                    required: true,
                },
                annual_turnover: {
                    required: true,
                    maxlength: 100,
                    
                },
                known_client: {
                    required: true,
                },
                bank_name: {
                    required: true,
                    maxlength: 255
                },
                bank_address: {
                    required: true,
                },
                bank_branch: {
                    required: true,
                    maxlength: 255
                },
                account_type: {
                    required: true,
                    maxlength: 255
                },
                bank_account_no: {
                    required: true,
                    digits:true,
                    minlength: 9,
                    maxlength: 20
                },
                ifsc_code: {
                    required: true,
                    maxlength: 11
                },
                micr_code: {
                    required: true,
                    digits:true,
                    maxlength: 9
                },
                director_name: {
                    required: true,
                },
                director_contact_no: {
                    required: true,
                    maxlength: 10,
                    minlength: 10,
                },
                director_email: {
                    required: true,
                    maxlength: 100,
                    email: true,
                },
                sales_officer_name: {
                    required: true,
                },
                sales_officer_contact_no: {
                    required: true,
                    maxlength: 10,
                    minlength: 10,
                },
                sales_officer_email: {
                    required: true,
                    maxlength: 100,
                    email: true,
                },
                account_officer_name: {
                    required: true,
                },
                account_officer_contact_no: {
                    required: true,
                    maxlength: 10,
                    minlength: 10,
                },
                account_officer_email: {
                    required: true,
                    maxlength: 100,
                    email: true,
                },
                any_legal_notices: {
                    required: true,
                    maxlength: 50
                },
                legal_notice_elaborate: {
                    required: true,
                    maxlength: 255
                },
                is_legal_requirements: {
                    required: true,
                    maxlength: 100
                },
                worker_minimum_age: {
                    required: true,
                    maxlength: 100
                },
                submit_original_documents: {
                    required: true,
                    maxlength: 100
                },
                any_serious_incidents: {
                    required: true,
                    maxlength: 100
                },
                elaborate_serious_incidents: {
                    required: true,
                    maxlength: 255
                },
                is_anti_bribe_policy: {
                    required: true,
                    maxlength: 100
                },
                is_health_safety_policy: {
                    required: true,
                    maxlength: 100
                },
                is_env_regulation: {
                    required: true,
                    maxlength: 100
                },
                elaborate_env_regulation: {
                    required: true,
                    maxlength: 255
                },
                name: {
                    required: true,
                },
                date: {
                    required: true,
                    maxlength: 100
                },
                designation: {
                    required: true,
                    maxlength: 100
                },
                vendor_gst_no_file: {
                    required: true,
                    extension: "jpeg|png|jpg|csv|txt|xlx|xls|pdf"
                },
                vendor_pan_file: {
                    required: true,
                    extension: "jpeg|png|jpg|csv|txt|xlx|xls|pdf"
                },
                bank_name_file: {
                    required: true,
                    extension: "jpeg|png|jpg|csv|txt|xlx|xls|pdf"
                },
            },
            messages: {
                vendor_name: {
                    required: "Please enter Vendor Name",
                    maxlength: "Your Vendor Name maxlength should be 255 characters long."
                },
                warehouse_location: {
                    required: "Please enter Warehouse Location",
                    maxlength: "Your Warehouse Location maxlength should be 500 characters long."
                },
                contact_person: {
                    required: "Please enter Contact Person",
                    maxlength: "Your Contact Person maxlength should be 50 characters long."
                },
                contactno: {
                    required: "Please enter Contact Number",
                    maxlength: "Your Contact Number maxlength should be 10 characters long."
                },
                vendor_email: {
                    required: "Please enter Vendor Email",
                    maxlength: "Your Vendor Email maxlength should be 255 characters long."
                },
                address: {
                    required: "Please enter Address",
                    maxlength: "Your Address maxlength should be 255 characters long."
                },
                city: {
                    required: "Please enter City",
                    maxlength: "Your City maxlength should be 255 characters long."
                },
                pincode: {
                    required: "Please enter Pin Code",
                    maxlength: "Your Pin Code maxlength should be 6 characters long."
                },
                vendor_gst_no: {
                    required: "Please enter Vendor GST No",
                    maxlength: "Your Vendor GST No maxlength should be 15 characters long."
                },
                vendor_pan: {
                    required: "Please enter Vendor PAN No",
                    maxlength: "Your Vendor PAN No maxlength should be 10 characters long."
                },
                is_msme_reg: {
                    required: "Please Select Here",
                    maxlength: "Your MSME Register No maxlength should be 50 characters long."
                },
                is_gstnumber_reg: {
                    required: "Please Select Here",
                },
                products_services_offered: {
                    required: "Please enter Product/Services Offered.",
                },
                associate_oem: {
                    required: "Please enter Associate OEM",
                },
                delivery_time: {
                    required: "Please enter Delivery Time",
                    maxlength: "Your Delivery Time maxlength should be 100 characters long."
                },
                payment_terms: {
                    required: "Please enter Payment Terms",
                },
                annual_turnover: {
                    required: "Please enter Annual Turnover",
                    maxlength: "Your Annual Turnover maxlength should be 100 characters long."
                },
                known_client: {
                    required: "Please enter Known Clients",
                },
                bank_name: {
                    required: "Please enter Bank Name",
                    maxlength: "Your Bank Name maxlength should be 255 characters long."
                },
                bank_branch: {
                    required: "Please enter Branch Name",
                    maxlength: "Your Branch Name maxlength should be 255 characters long."
                },
                bank_address: {
                    required: "Please enter Bank Address",
                },
                account_type: {
                    required: "Please Select Here",
                    maxlength: "Your Account Type maxlength should be 255 characters long."
                },
                bank_account_no: {
                    required: "Please Enter Account Number",
                },
                ifsc_code: {
                    required: "Please Enter IFSC Code",
                    maxlength: "Your IFSC Code maxlength should be 40 characters long."
                },
                micr_code: {
                    required: "Please Enter MICR Code",
                },
                director_name: {
                    required: "Please Enter Director Name",
                },
                director_contact_no: {
                    required: "Please Enter Director Contact No",
                    maxlength: "Your Director Contact No maxlength should be 10 characters long."
                },
                director_email: {
                    required: "Please Enter Director Email",
                    maxlength: "Your Director Email maxlength should be 100 characters long."
                },
                sales_officer_name: {
                    required: "Please Enter Sales Officer Name",
                },
                sales_officer_contact_no: {
                    required: "Please Enter Sales Officer Contact No",
                    maxlength: "Your Sales Officer Contact No maxlength should be 10 characters long."
                },
                sales_officer_email: {
                    required: "Please Enter Sales Officer Email",
                    maxlength: "Your Sales Officer Email maxlength should be 100 characters long."
                },
                account_officer_name: {
                    required: "Please Enter Account Officer Name",
                },
                account_officer_contact_no: {
                    required: "Please Enter Account Officer Contact No",
                    maxlength: "Your Account Officer Contact No maxlength should be 10 characters long."
                },
                account_officer_email: {
                    required: "Please Enter Account Officer Email",
                    maxlength: "Your Account Officer Email maxlength should be 100 characters long."
                },
                any_legal_notices: {
                    required: "Please Select Here",
                    maxlength: "Your Any Legal Notices maxlength should be 100 characters long."
                },
                is_legal_requirements: {
                    required: "Please Select Here",
                    maxlength: "Your Is Legal Requirements maxlength should be 100 characters long."
                },
                submit_original_documents: {
                    required: "Please Select Here",
                    maxlength: "Your Is Submit Original Documents maxlength should be 100 characters long."
                },
                any_serious_incidents: {
                    required: "Please Select Here",
                    maxlength: "Your Any Serious Incidents maxlength should be 100 characters long."
                },
                elaborate_serious_incidents: {
                    required: "Please Enter Here",
                    maxlength: "Your maxlength should be 255 characters long."
                },
                legal_notice_elaborate: {
                    required: "Please Enter Here",
                    maxlength: "Your maxlength should be 255 characters long."
                },
                elaborate_env_regulation: {
                    required: "Please Enter Here",
                    maxlength: "Your maxlength should be 255 characters long."
                },
                is_anti_bribe_policy: {
                    required: "Please Select Here",
                    maxlength: "Your Anti Bribe Policy maxlength should be 100 characters long."
                },
                is_health_safety_policy: {
                    required: "Please Select Here",
                    maxlength: "Your Health Safety Policy maxlength should be 100 characters long."
                },
                is_env_regulation: {
                    required: "Please Select Here",
                    maxlength: "Your Environment Regulation maxlength should be 100 characters long."
                },
                name: {
                    required: "Please Enter Name",
                },
                date: {
                    required: "Please Select Date",
                    maxlength: "Your Date maxlength should be 100 characters long."
                },
                designation: {
                    required: "Please Enter Designation",
                    maxlength: "Your Designation maxlength should be 100 characters long."
                },
                vendor_gst_no_file: {
                    required: "Please upload Vendor GST No File",
                    maxlength: "File accept only jepg|png|jpg|csv|txt|xlx|xls|pdf."
                },
                vendor_pan_file: {
                    required: "Please upload Vendor PAN No File",
                    maxlength: "File accept only jepg|png|jpg|csv|txt|xlx|xls|pdf."
                },
                bank_name_file: {
                    required: "Please upload Bank Proof File",
                    maxlength: "File accept only jepg|png|jpg|csv|txt|xlx|xls|pdf."
                },
            }, 
            submitHandler: function() {  
                vendoreditsubmit();
            }                  
        })        
    }

    // Vendor Edit Event End
    function vendoreditsubmit() {
        clearMsg('msg_popup');
        clearMsg('msg_div');
        emLoader('show', trans('messages.msg_updating_vendor'));
        var url = SITE_URL + '/vendor/editsubmit';
        var postData = $("#addformvendor").serialize();
        var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
             console.log(data);
            var result = JSON.parse(data);
           // console.log(result);
            if (result.is_error) {
                showResponse(data, '','msg_popup');
                emLoader('hide');
            }
            else {
                emLoader('hide');
                lightbox('hide');
                showResponse(data,'grid_data','msg_div' );
                vendorList();
    
            }
    
        });
    }
    
    function vendordelete(vendor_id) {
        if (confirm(trans('messages.msg_vendor_delete'))) {
            clearMsg('msg_popup');
            emLoader('show', trans('messages.msg_deleting_vendor'));
            var id = vendor_id.split('_')[1];
            var postData = { 'datatype': 'json', 'vendor_id': id, 'status': 'd' };
            var url = SITE_URL + '/vendor/delete';
            var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
                var result = JSON.parse(data);
                if (result.is_error) {
                    showResponse(data, '','msg_div');
                    emLoader('hide');
                }
                else {
                    emLoader('hide');
                    showResponse(data,'grid_data', 'msg_div' );
                    vendorList();
                }
            });
        }
    }

   
    


