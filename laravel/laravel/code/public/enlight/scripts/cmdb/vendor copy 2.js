var postData = null;
$(document).ready(function () {

// 

// 
vendorList();
$(document).on("click", "#vendoradd", function () { vendoradd(); });
$(document).on("click", "#vendoraddsubmit", function () { vendoraddsubmitvalidate(); });
$(document).on("click", ".vendor_edit", function () { var vendor_id = $(this).attr('id'); vendoredit(vendor_id); });
$(document).on("click", "#vendoreditsubmit", function () { vendoreditsubmit(); });
$(document).on("click", ".vendor_del", function () { var vendor_id = $(this).attr('id'); vendordelete(vendor_id); });
$(document).on("click", "#btn_reset", function () { resetForm('addformvendor'); });

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
    function vendoraddsubmitvalidate()
    {      
        alert("Validation function call")  ;
        $("#addformvendor").validate({
                
            rules: {
                vendor_name: {
                    required: true,
                    maxlength: 255
                },
                vendor_ref_id: {
                    required: true,
                    maxlength: 50
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
                    maxlength: 50
                },
                vendor_gst_no: {
                    required: true,
                    maxlength: 255
                },
                vendor_pan: {
                    required: true,
                    maxlength: 255
                },
                is_msme_reg: {
                    required: true,
                    maxlength: 50
                },
                meme_reg_num: {
                    required: true,
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
                    maxlength: 100
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
                },
                ifsc_code: {
                    required: true,
                    maxlength: 40
                },
                micr_code: {
                    required: true,
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
                vendor_ref_id: {
                    required: "Please enter Vendor Reference Id",
                    maxlength: "Your Vendor Reference Id maxlength should be 50 characters long."
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
                    maxlength: "Your Pin Code maxlength should be 50 characters long."
                },
                vendor_gst_no: {
                    required: "Please enter Vendor GST No",
                    maxlength: "Your Vendor GST No maxlength should be 255 characters long."
                },
                vendor_pan: {
                    required: "Please enter Vendor PAN No",
                    maxlength: "Your Vendor PAN No maxlength should be 255 characters long."
                },
                is_msme_reg: {
                    required: "Please Select Is MSME Register",
                    maxlength: "Your MSME Register No maxlength should be 50 characters long."
                },
                meme_reg_num: {
                    required: "Please enter MEME Number",
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
                    required: "Please Select Account Type",
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
                    required: "Please Select Any Legal Notices",
                    maxlength: "Your Any Legal Notices maxlength should be 100 characters long."
                },
                is_legal_requirements: {
                    required: "Please Select Is Legal Requirements",
                    maxlength: "Your Is Legal Requirements maxlength should be 100 characters long."
                },
                submit_original_documents: {
                    required: "Please Select Is Submit Original Documents",
                    maxlength: "Your Is Submit Original Documents maxlength should be 100 characters long."
                },
                any_serious_incidents: {
                    required: "Please Select Any Serious Incidents",
                    maxlength: "Your Any Serious Incidents maxlength should be 100 characters long."
                },
                is_anti_bribe_policy: {
                    required: "Please Select Is Anti Bribe Policy",
                    maxlength: "Your Anti Bribe Policy maxlength should be 100 characters long."
                },
                is_health_safety_policy: {
                    required: "Please Select Is Health Safety Policy",
                    maxlength: "Your Health Safety Policy maxlength should be 100 characters long."
                },
                is_env_regulation: {
                    required: "Please Select Is Environment Regulation",
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
            errorPlacement: function(error, element) {
                var placement = $(element).data('error');
                if (placement) {
                  $(placement).append(error)
                } else {
                  error.insertAfter(element);
                }
              },  
            submitHandler: function() {  
                alert("Add Submit Call") ;
                vendoraddsubmit();
            }                  
        })
        
    }
    function vendoraddsubmit() {
        // Validation Start
        alert("Validate Add Submit Call");
        // Validation End

        // clearMsg('msg_popup');
        // if (cltimer) {
        //     clas = 'error';
        //     showAlert("msg_popup", clas,  trans('messages.msg_session_open'));
        //     return false;
        // }
        // emLoader('show', 'Vendor');        
        // var url = SITE_URL + '/vendor/addsubmit';
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
    }

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


