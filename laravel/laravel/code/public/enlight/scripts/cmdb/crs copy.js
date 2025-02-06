var postData = null;
//var first_po_id = "";
$(document).ready(function() {
    crList();
    $(document).on("click", ".crlist", function() {
        $(".crlist").removeClass("active");
        $(this).addClass("active");
        var id = $(this).data('id');
        crDetailsLoad(id);
    });
   
    $(document).on("click", "#pradd", function() {
        purchaserequestadd();
    });

    // repair_req_add
    $(document).on("click", "#repair_req_add", function() {
        var asset_display_name = $("#asset_display_name").val();
        var asset_sku = $("#asset_sku").val();
        var complaint_raised_no = $("#complaint_raised_no").val();
        purchaserepairrequestadd(asset_display_name,asset_sku,complaint_raised_no);
    });
    
    $(document).on("click", "#cr_itremark_submit", function () { itremarksubmitvalidate(); });

    $(document).on("click", "#cr_storeremark_submit", function () { storeremarksubmitvalidate(); });
   
    $(document).on("click", ".download_file", function () { 
        var att_id = $(this).attr('download_id');
        var att_path = $(this).attr('download_path');
        var att_title = $(this).attr('download_title');

        var postData    = { 'attach_id': att_id,'attach_path': att_path, 'attach_title': att_title};
        var url = SITE_URL + '/download_complaint_docs';
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


    $(document).on("click", "#crSubmit", function() {
        $("#complaintRaisedForm").validate({                
            rules: {
                pr_requester_name: {
                    required: true
                },
                priority: {
                    required: true
                },
                asset: {
                    required: true
                },
                browseFile: {
                    required: true,
                    extension: "jpeg|png|jpg|csv|txt|xlx|xls|pdf"
                },
                problemdetail: {
                    required: true,
                    maxlength: 500
                },
            },
            messages: {
                pr_requester_name: {
                    required: "Please Select Requester Name"
                },
                priority: {
                    required: "Please Select Priority"
                },
                asset: {
                    required: "Please Select Asset"
                },
                browseFile: {
                    required: "Please Browse File"
                },
                problemdetail: {
                    required: "Please Enter Problem In Detail",
                    maxlength: "Your Problem Detail maxlength should be 500 characters long."
                },
            }, 
            submitHandler: function() {  
                var url = SITE_URL + '/complaintRaisedAdd';
                emLoader('show', trans('label.lbl_loading'));   
                var postData = new FormData(document.getElementById('complaintRaisedForm'));        
                var userajax = ajaxCall_test(userajax, url, postData, function(data) {
                    alert("Request Added");                    
                });
                location.reload();
            }                  
        })
    });
   
    $(document).on("click", "#predit", function() {
        var pr_id = $(this).data("id");
        purchaserequestedit(pr_id);
    });
   
    $(document).on('change',"#pr_requester_name", function() {
        var userId = $(this).val();
        getAssignAsset(userId);
    });

    $(document).on("click", ".actionsPr", function() {
        var id = $(this).attr('id');
        var action = id.split('_')[0];
        if (confirm(trans('messages.msg_actionconfirmation_pr', {
                "name": action.replace("notify", "notify ")
            }))) {
            actionsPr(id);
        }
    });
    
    $(document).on("click", "#submitAction", function() {
        submitAction();
    });
    $(document).on("click", "#enableApprovalcheck", function() {
        if ($("#enableApprovalcheck").is(":checked")) {
            $("#enableApproval").show();
        } else {
            $("#enableApproval").hide();
        }
    });
    $(document).on("click", "#enableMailNotificationCheck", function() {
        if ($("#enableMailNotificationCheck").is(":checked")) {
            $(".enableMailNotification").show();
        } else {
            $(".enableMailNotification").hide();
        }
    });
    $(document).on("click", "#prSubmit", function() {
        prSubmit("pr");
    });
    $(document).on("click", "#prSubmitsample", function() {
        prSubmitsample("pr");
    });
    $(document).on("click", "#prConvertSubmit", function() {
        prConvertSubmit("pr");
    });
    $(document).on("click", "#poSubmit", function() { // PR to Po Creation
        prSubmit("po");
    });
    $(document).on("click", "#submitComment", function() {
        approveRejectComment();
    });
    $(document).on("click", "#pr_comment_submit", function() {
        console.log('in onclick event of pr comment');
        pr_Comment();
    });
    
    $(document).on("click", "#submitComment_qc", function() {
        quotation_final();
        approveRejectComment_qc();
    });
    $(document).on({
        mouseenter: function() {
            $(this).removeClass("btn btn-default");
            $(this).addClass("btn btn-success");
        },
        mouseleave: function() {
            $(this).removeClass("btn btn-success");
            $(this).addClass("btn btn-default");
        },
        click: function() {
            var id = $(this).attr('id');
            approveReject(trans('label.lbl_approve'), id);
        }
    }, ".approve button");
    $(document).on({
        mouseenter: function() {
            $(this).removeClass("btn btn-default");
            $(this).addClass("btn btn-danger");
        },
        mouseleave: function() {
            $(this).removeClass("btn btn-danger");
            $(this).addClass("btn btn-default");
        },
        click: function() {
            var id = $(this).attr('id');
            approveReject(trans('label.lbl_reject'), id);
        }
    }, ".reject button");
   
    $(document).on("change", "#pr_filter_status", function() {
        var status = $(this).val();
        $('input[type=hidden][name=searchkeyword]').val(status);
        crList(status, "#pr_filter_status");
    });
    $(document).on("click", ".deleteAttachment", function() {
        var id = $(this).attr('id');
        deleteAttachment(id);
    });
    $(document).on("click", "#poview", function() {
        var po_id = $(this).data('id');
        var type_pr = $(this).data('pr');
        viewPo(po_id, type_pr)
    });
    $(document).on("click", ".dropdown-notifications", function() {
        notificatioMessages();
    });
    $(document).on("click", "#attachDoc", function() {
        $(".tab-block .nav-tabs > li").removeClass("active");
        $(".tab-block .nav-tabs > li.purchase_requesttab").addClass("active");
        $("#podetails_page .tab-content .tab-pane").removeClass("active");
        $("#purchase_request").addClass("active");
        $('html, body').animate({
            scrollTop: $("#attachment_details").offset().top
        }, 1000);
    });
});

function storeremarksubmitvalidate()
{
    $("#cr_StoreForm").validate({
        rules: {
            store_commentboxs: {
                required: true,
                maxlength: 500
            },
            storeremarkfile: {
                required: true,
                extension: "jpeg|png|jpg|csv|txt|xlx|xls|pdf"
            },
        },
        messages: {
            store_commentboxs: {
                required: "Please enter Comment",
                maxlength: "Your Comment maxlength should be 500 characters long."
            },
            storeremarkfile: {
                required: "Please upload Attachment",
                maxlength: "File accept only jepg|png|jpg|csv|txt|xlx|xls|pdf."
            },
        },
        submitHandler: function() {  
            complaintStoresubmit();
        } 
    })
}

function itremarksubmitvalidate()
{
    $("#cr_ItForm").validate({
        rules: {
            ItsRepairable: {
                required: true,
                maxlength: 255
            },
            commentboxs: {
                required: true,
                maxlength: 500
            },
            itremarkfile: {
                required: true,
                extension: "jpeg|png|jpg|csv|txt|xlx|xls|pdf"
            },
        },
        messages: {
            ItsRepairable: {
                required: "Please select status",
                maxlength: "Your status maxlength should be 255 characters long."
            },
            commentboxs: {
                required: "Please enter Comment",
                maxlength: "Your Comment maxlength should be 500 characters long."
            },
            itremarkfile: {
                required: "Please upload Attachment",
                maxlength: "File accept only jepg|png|jpg|csv|txt|xlx|xls|pdf."
            },
        },
        submitHandler: function() {  
            complaintsubmit();
        } 
    })
}

function complaintStoresubmit()
{
    clearMsg('msg_popup');
    if (cltimer) {
        clas = 'error';
        showAlert("msg_popup", clas,  trans('messages.msg_session_open'));
        return false;
    }
    emLoader('show', 'Complaint');        
    var url = SITE_URL + '/complaintraised/storeremarksave';
    var postData = new FormData(document.getElementById('cr_StoreForm')); 
                     
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
            crList();
        }  
    });
}

function complaintsubmit() {
    clearMsg('msg_popup');
    if (cltimer) {
        clas = 'error';
        showAlert("msg_popup", clas,  trans('messages.msg_session_open'));
        return false;
    }
    emLoader('show', 'Complaint');        
    var url = SITE_URL + '/complaintraised/itremarksave';
    var postData = new FormData(document.getElementById('cr_ItForm')); 
                     
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
            crList();
        }  
    });
}
function approveReject(action, id) {
    if (confirm("Are You sure you want to "+ action +" this request", {
            "name": action.replace("notify", "notify ")
        })) {
        var approval_status = id.split('_')[0];
        var user_id = id.split('_')[1];
        var pr_id = id.split('_')[2];
        var confirmed_optional = id.split('_')[3];
        $('#myModal_approve_reject').modal('show');
        var title_approve_reject = approval_status == "rejected" ? trans('label.lbl_reject') : trans('label.lbl_approve');
        $("#modal-title_approve_reject").html(title_approve_reject);
        $("#myModal_approve_reject #pr_po_id").val(pr_id);
        $("#myModal_approve_reject #user_id").val(user_id);
        $("#myModal_approve_reject #approval_status").val(approval_status);
        $("#myModal_approve_reject #confirmed_optional").val(confirmed_optional);
        $("#myModal_approve_reject #pr_po_type").val("pr");
        emLoader('hide');
    }
}

function approveRejectComment() {
    closeMsgAuto('msg_div');
    closeMsgAuto('msg_modal_approve_reject');
    var url = '';
    var postData = $("#formComment").serialize();
    console.log(postData);
    url = SITE_URL + '/approve_reject_cr';
    if (url != '') {
        var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function(data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '', 'msg_modal_approve_reject');
                emLoader('hide');
                window.scrollTo(0, 0);
            } else {
                emLoader('hide');
                $('#myModal_approve_reject').modal('hide');
                showResponse(data, 'grid_data', 'msg_div');
                crList();
                window.scrollTo(0, 0);
            }
        });
    }
}

function pr_Comment() {
    var postData = $("#pr_formComment").serialize();
    var callfor = 'pr';
    url = SITE_URL + '/approve_reject_pr';
    console.log(postData);
    if (url != '') {
        var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function(data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '', 'msg_modal_approve_reject');
                emLoader('hide');
                window.scrollTo(0, 0);
            } else {
                emLoader('hide');
                $('#myModal_approve_reject').modal('hide');
                showResponse(data, 'grid_data', 'msg_div');
                crList();
                window.scrollTo(0, 0);
            }
        });
    }
}

function actionsPr(id) {
    //alert(id);
    var action = id.split('_')[0];
    var user_id = id.split('_')[1];
    var pr_id = id.split('_')[2];
    var notify_to_id = id.split('_')[3];
    $('#myModal_actions').modal('show');
    $('#myModal_actions form')[0].reset();
    /// alert(action);
    $("#modal-title_actions").html(action.replace("notify", "notify ").toUpperCase());
    $("#modal-title_actions_2").html(action);
    $("#myModal_actions #pr_po_id").val(pr_id);
    $("#myModal_actions #pr_po_type").val("pr");
    $("#myModal_actions #user_id").val(user_id);
    $("#myModal_actions #action").val(action);
    $("#myModal_actions #notify_to_id").val(notify_to_id);
}

function convertToPR(id) {
    //alert(id);
    var action = id.split('_')[0];
    var user_id = id.split('_')[1];
    var pr_id = id.split('_')[2];
    let url = SITE_URL + '/convert_to_pr';
    if (url != '') {
        var postData = {
            action: action,
            user_id: user_id,
            pr_id: pr_id,
        }
        var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function(data) {
            var result = JSON.parse(data);
            // console.log(result.content['pr_id']);
            if (result.is_error) {
                emLoader('hide');
                showResponse(data, '', 'msg_div');
                window.scrollTo(0, 0);
            } else {
                emLoader('hide');
                showResponse(data, '', 'msg_div');
                crDetailsLoad(result.content['pr_id']);
                window.scrollTo(0, 0);
            }
            //after call completion, following code is to make page scrollable
            $('body').css('overflow', 'auto');
        });
    }
}

function submitAction() {
    closeMsgAuto('msg_div');
    closeMsgAuto('msg_modal');
    //    var url = SITE_URL + '/purchaserequest/prpoformActions';
    var url = '';
    var act = $('.modal:visible form [id=action]').val();
    if ($('#pr_po_type').val() == 'pr') {
        if (act == 'close') url = SITE_URL + '/close_pr';
        if (act == 'cancel') url = SITE_URL + '/cancel_pr';
        if (act == 'notifyowner') url = SITE_URL + '/notify_owner_email';
        if (act == 'notifyvendor') url = SITE_URL + '/notify_vendor_email';
        if (act == 'notifyagain') url = SITE_URL + '/notifyagain';
        if (act == 'delete') url = SITE_URL + '/purchaserequest/delete';
    }
    if ($('#pr_po_type').val() == 'po') {
        if (act == 'close') url = SITE_URL + '/close_po';
        if (act == 'cancel') url = SITE_URL + '/cancel_po';
        if (act == 'order') url = SITE_URL + '/order_po';
        if (act == 'received') url = SITE_URL + '/receive_items_po';
        if (act == 'invoice' && $('#invoice_id').val() == '') url = SITE_URL + '/invoice_po/addsubmit';
        if (act == 'invoice' && $('#invoice_id').val() != '') url = SITE_URL + '/invoice_po/editsubmit';
        if (act == 'notifyowner') url = SITE_URL + '/notify_owner_email';
        if (act == 'notifyvendor') url = SITE_URL + '/notify_vendor_email';
        if (act == 'notifyagain') url = SITE_URL + '/notifyagain';
        if (act == 'delete') url = SITE_URL + '/purchaseorder/delete';
    }
    if (url != '') {
        var postData = $("#prformActions").serialize();
        var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function(data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '', 'msg_modal');
                emLoader('hide');
                window.scrollTo(0, 0);
            } else {
                emLoader('hide');
                $('#myModal_actions').modal('hide');
                showResponse(data, 'grid_data', 'msg_div');
                crList();
                window.scrollTo(0, 0);
            }
            //after call completion, following code is to make page scrollable
            $('body').css('overflow', 'auto');
        });
    }
}

function crList(status = "", dropdown_selector = "") {

    closeMsgAuto('msg_div');
    emLoader('show', trans('label.lbl_loading'));
    var url = SITE_URL + '/complaintraised/list';

    var postData = $("#frmdevices").serialize();
    if (status) {
        var postData = $('#frpurchase').serializeArray();
        postData.push({
            name: "searchkeyword",
            value: status
        });
    }
    var userajax = ajaxCall(userajax, url, postData, function(data) {
        
        var result = JSON.parse(data);
        var first_pr_id = result.po_id;
        console.log(result);
        showResponse(data, 'cr_list', 'msg_div');
        crDetailsLoad(first_pr_id);
        $(".crlist:first").addClass("active");
        emLoader('hide');
        initsingleselect();
        if (dropdown_selector != "") $(dropdown_selector).val(status).trigger('chosen:updated');
        applyVerticalScroll();
    });
}

function crDetailsLoad(first_pr_id) {
    emLoader('show', trans('label.lbl_loading'));
    var url = SITE_URL + '/complaintraised/details';
    postData = {
        'first_pr_id': first_pr_id
    };
    var userajax = ajaxCall(userajax, url, postData, function(data) {
        console.log(data);
        showResponse(data, 'pr_detail', 'msg_div');
        showDropZoneFile("dropZone");
        emLoader('hide');
        // $(".download_file").click(function() {
        //     var att_id = $(this).attr('download_id');
        //     var att_path = $(this).attr('download_path');
        //     var att_title = $(this).attr('download_title');
        //     downloadAttachment(att_id, att_path, att_title);
        // });
    });
}

function purchaserepairrequestadd(asset_display_name,asset_sku,complaint_raised_no)
{
    closeMsgBox('msg_div');
    var url = SITE_URL + '/complaintraisedrepair/add';
    emLoader('show', trans('messages.msg_templaterendering'));
    var creTemplateajax = ajaxCall(creTemplateajax, url, postData, function(data) {        
        lightbox('show', data, "Add Complaint Repair Request for - Asset Name : "+asset_display_name+", Sku Code : "+asset_sku+", Asset Complaint No : " + complaint_raised_no, 'full');
        $("#build-form").hide();
        $(".render-btn-wrap").hide();
        if (jsonDataAsString != "") {
            setDataedit();
        } else {
            alert(trans('messages.msg_invalidtemplate'));
        }
        setTimeout(function() {
            getPurchaseRenderFormData();
            emLoader('hide');
        }, 500);
        emLoader('hide');
        initsingleselect();
        initmultiselect();
    }); 
}

function purchaserequestadd() {
    closeMsgBox('msg_div');
    //template_name = 'purchaserequest'; 
    // if(template_name)
    // {
    var url = SITE_URL + '/complaintraised/add';
    // var postData ={'template_name' : template_name};
    emLoader('show', trans('messages.msg_templaterendering'));
    var creTemplateajax = ajaxCall(creTemplateajax, url, postData, function(data) {
        // lightbox('show', data, trans('label.lbl_purchaserequestadd'), 'full');
        lightbox('show', data, "Add Complaint Request", 'full');
        $("#build-form").hide();
        $(".render-btn-wrap").hide();
        if (jsonDataAsString != "") {
            setDataedit();
        } else {
            alert(trans('messages.msg_invalidtemplate'));
        }
        setTimeout(function() {
            getPurchaseRenderFormData();
            emLoader('hide');
        }, 500);
        //var row = $('select[name="allinputtogether"]').append(vendorsDetailsOPtions);
        emLoader('hide');
        initsingleselect();
        initmultiselect();
    });
    //}
}

function purchaserequestaddsample() {
    closeMsgBox('msg_div');
    //template_name = 'purchaserequest'; 
    // if(template_name)
    // {
    var url = SITE_URL + '/purchaserequestsample/add';
    // var postData ={'template_name' : template_name};
    emLoader('show', trans('messages.msg_templaterendering'));
    var creTemplateajax = ajaxCall(creTemplateajax, url, postData, function(data) {
        lightbox('show', data, trans('label.lbl_purchaserequestadd'), 'full');
        $("#build-form").hide();
        $(".render-btn-wrap").hide();
        if (jsonDataAsString != "") {
            setDataedit();
        } else {
            alert(trans('messages.msg_invalidtemplate'));
        }
        setTimeout(function() {
            getPurchaseRenderFormData();
            emLoader('hide');
        }, 500);
        //var row = $('select[name="allinputtogether"]').append(vendorsDetailsOPtions);
        emLoader('hide');
        initsingleselect();
        initmultiselect();
    });
    //}
}
/*------------- Quotation Comparison Functions Start -----------*/
/* Showing Approve/Reject POPUP in Quotation Comparison page */
function approveReject_qc(action, id) {
    if (confirm(trans('messages.msg_actionconfirmation_qc', {
            "name": action.replace("notify", "notify ")
        }))) {
        var approval_status = id.split('_')[0];
        var pr_po_id = id.split('_')[1];
        $('#myModal_approve_reject_qc').modal('show');
        var title_approve_reject = approval_status == "rejected" ? trans('label.lbl_reject') : trans('label.lbl_approve');
        $("#modal-title_approve_reject").html(title_approve_reject);
        $("#myModal_approve_reject_qc #pr_po_id").val(pr_po_id);
        $("#myModal_approve_reject_qc #approval_status").val(approval_status);
        emLoader('hide');
    }
}
/* Show All items and vendor Quotation Details */
function quotation_details() {
    closeMsgBox('msg_div');
    var url = SITE_URL + '/prquotationcomparison/details';
    emLoader('show', 'Loading');
    postData = $("#PrQuotationComparison").serialize();
    var creTemplateajax = ajaxCall(creTemplateajax, url, postData, function(data) {
        emLoader('hide');
        var result = JSON.parse(data);
    });
}
/* Submit Quotation Comparison Reject Comment */
function approveRejectComment_qc() {
    closeMsgAuto('msg_div');
    closeMsgAuto('msg_modal_approve_reject_qc');
    var url = '';
    var postData = $("#formComment_qc").serialize();
    url = SITE_URL + '/approve_reject_qc';
    if (url != '') {
        var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function(data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '', 'msg_modal_approve_reject_qc');
                emLoader('hide');
                window.scrollTo(0, 0);
            } else {
                emLoader('hide');
                $('#myModal_approve_reject_qc').modal('hide');
                showResponse(data, 'grid_data', 'msg_div');
                //alert("Quotation Comparison Rrejected.");
                window.close();
                //crList();
                //window.scrollTo(0, 0);
            }
        });
    }
}
/* Submit Quotation Comparison Approve */
function qc_approve() {
    closeMsgBox('msg_div');
    var url = SITE_URL + '/prquotationcomparison_approve/update';
    emLoader('show', 'Loading');
    postData = $("#prquotationcomparison").serialize();
    var creTemplateajax = ajaxCall(creTemplateajax, url, postData, function(data) {
        emLoader('hide');
        var result = JSON.parse(data);
        alert("Quotation Details Approved Successfully.");
        window.close();
        //crDetailsLoad($("#prquotationcomparison #pr_po_id").val()); 
    });
}
/* Select items by vendors then submit Quotation Comparisons */
function quotation_final() {
    closeMsgBox('msg_div');
    var url = SITE_URL + '/prquotationcomparison/update';
    emLoader('show', 'Loading');
    postData = $("#prquotationcomparison").serialize();
    var creTemplateajax = ajaxCall(creTemplateajax, url, postData, function(data) {
        emLoader('hide');
        var result = JSON.parse(data);
        alert("Quotation Details Updated Successfully.");
        window.close();
        //crDetailsLoad($("#prquotationcomparison #pr_po_id").val()); 
    });
}
/* Each item Quotation Comparison add */
function quotation_vendor_cmp() {
    closeMsgBox('msg_div');
    var url = SITE_URL + '/quotationvendorcomparison/add';
    emLoader('show', 'Loading');
    postData = $("#quotationvendorcomparison").serialize();
    var pr_vendor_id_1 = $("#pr_vendor_id_1 option:selected").val();
    var pr_vendor_id_2 = $("#pr_vendor_id_2 option:selected").val();
    var pr_vendor_id_3 = $("#pr_vendor_id_3 option:selected").val();
    if (pr_vendor_id_1 == "" || pr_vendor_id_2 == "" || pr_vendor_id_3 == "") {
        alert('Please Select Vendor Name. 3 Quotations mandetory.');
        emLoader('hide');
        return false;
    }
    var creTemplateajax = ajaxCall(creTemplateajax, url, postData, function(data) {
        emLoader('hide');
        var result = JSON.parse(data);
        alert("Quotation Details Successfully saved.");
        crDetailsLoad($("#quotationvendorcomparison #pr_po_id").val());
    });
}

function get_item_by_category(id, item_id) {
    closeMsgBox('msg_div');
    var url = SITE_URL + '/getitembycategory';
    emLoader('show', 'Loading');
    let postData = {
        ci_templ_id: id
    };
    let item_id1 = item_id.charAt(item_id.length - 1);
    var creTemplateajax = ajaxCall(creTemplateajax, url, postData, function(data) {
        emLoader('hide');
        var result = JSON.parse(data);
        $('#item_product-' + item_id1).html(result.html);
    });
}
//Convert items in one PR
function convertitemsinpr() {
    closeMsgBox('msg_div');
    var url = SITE_URL + '/convert/add';
    emLoader('show', trans('messages.msg_templaterendering'));
    var creTemplateajax = ajaxCall(creTemplateajax, url, postData, function(data) {
        lightbox('show', data, trans('label.lbl_purchaserequestadd'), 'full');
        $("#build-form").hide();
        $(".render-btn-wrap").hide();
        if (jsonDataAsString != "") {
            setDataedit();
        } else {
            alert(trans('messages.msg_invalidtemplate'));
        }
        setTimeout(function() {
            getPurchaseRenderFormData();
            emLoader('hide');
        }, 500);
        emLoader('hide');
        initsingleselect();
        initmultiselect();
    });
    //}
}

function purchaserequestedit(pr_id) {
    closeMsgBox('msg_div');
    var url = SITE_URL + '/purchaserequest/edit';
    var postData = {
        'pr_id': pr_id
    };
    emLoader('show', trans('messages.msg_templaterendering'));
    var creTemplateajax = ajaxCall(creTemplateajax, url, postData, function(data) {
        lightbox('show', data, trans('label.lbl_purchaserequestedit'), 'full');
        $("#build-form").hide();
        $(".render-btn-wrap").hide();
        if (jsonDataAsString != "") {
            setDataedit();
        } else {
            alert(trans('messages.msg_invalidtemplate'));
        }
        setTimeout(function() {
            getPurchaseRenderFormData();
            emLoader('hide');
        }, 500);
        emLoader('hide');
        initsingleselect();
    });
    //}
}

function getAssignAsset(userId)
{
    $('#asset').empty();
    var url = SITE_URL + '/getAssignAsset';
    var vendorajax = ajaxCall(vendorajax, url, {
        userId: userId
    }, function(data) {
        if (data) {            
            var jsondata = JSON.parse(data);
            console.log("Nikhil");
            console.log(jsondata);
            $('select[name="asset"]').append(jsondata.getAssignAsset);            
        }
    });
}

function getPurchaseRenderFormData(vendor_id = '') {
    var url = SITE_URL + '/getPurchaseRenderFormData';
    var vendorajax = ajaxCall(vendorajax, url, {
        vendor_id: vendor_id
    }, function(data) {
        if (data) {            
            var jsondata = JSON.parse(data);
            console.log("Nikhil");
            console.log(jsondata);
            // $('select[name="pr_requester_name"]').append(jsondata.requesternameDetailsOptions);
            if (jsonConfig != "") {
                var jsonConfigData = JSON.parse(jsonConfig);
                console.log("Nick");
                console.log(jsonConfigData);
            }
        }
    });
}
//function setDataedit(jsonDataAsString, jsonConfig="")
function setDataedit() {
    closeMsgAuto('msg_div');
    console.log("Rendering...");
    let container = document.querySelector('#build-form');
    var renderContainer = document.querySelector('.render-form');
    var formeoOpts = {
        container: container,
        i18n: {
            preloaded: {
                'en-US': {
                    'row.makeInputGroup': ' Repeatable Region'
                }
            }
        },
        allowEdit: true,
        controls: {
            sortable: false,
            groupOrder: ['common', 'html', ],
            elements: [],
            elementOrder: {
                common: ['button', 'checkbox', 'date-input', 'hidden', 'upload', 'number', 'radio', 'select', 'text-input', 'textarea', ]
            }
        },
        events: {
            // onUpdate: console.log,
            // onSave: console.log
        },
        svgSprite: SITE_URL + "/enlight/scripts/formeo-master/img/formeo-sprite.svg",
        // debug: true,
        sessionStorage: false,
        editPanelOrder: ['attrs', 'options']
    };
    formeo = new window.Formeo(formeoOpts, jsonDataAsString);
    //templateDisplay();
    setTimeout(function() {
        templateDisplay();
        emLoader('hide');
    }, 500);
}

function templateDisplay() {
    //console.log("In render");
    var renderContainer = document.querySelector('.render-form');
    formeo.render(renderContainer);
    $(".render-form").show();
    //console.log(jsonConfig);
    if (jsonConfig) {
        var jsonConfigData = JSON.parse(jsonConfig);
        //  console.log(jsonConfigData)
        var joForm = document.querySelector('#prBuilderForm'); // document.getElementsByTagName("form")[0];
        //  console.log(joForm);
        for (var i = 0; i < joForm.elements.length; i++) {
            // console.log("-----------------");
            // console.log(joForm.elements[i]);
            var elementname = joForm.elements[i].name;
            var elementTagName = joForm.elements[i].tagName.toLowerCase();
            if (elementname != "") {
                if (elementTagName == "select") {
                    //  console.log(elementname);                  
                    $("select[name=" + elementname + "]").val(jsonConfigData[elementname]);
                } else if (elementTagName == "textarea") {
                    $("textarea[name=" + elementname + "]").val(jsonConfigData[elementname]);
                } else {
                    $("input[name=" + elementname + "]").val(jsonConfigData[elementname]);
                }
            }
        }
    }
}

function prSubmit_ORG(pr_po = "pr") {
    clearMsg('msg_popup');
    if (pr_po == "pr") {
        var url = SITE_URL + '/purchaserequest/save';
        var postData = $('#prBuilderForm, #prItemApproval').serialize();
    } else {
        var url = SITE_URL + '/purchaseorder/save';
        var postData = $('#poOtherDetails, #prItemApproval').serialize();
    }
    var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function(data) {
        var result = JSON.parse(data);
        if (result.is_error) {
            showResponse(data, '', 'msg_popup');
            emLoader('hide');
            window.scrollTo(0, 0);
        } else {
            emLoader('hide');
            lightbox('hide');
            crList();
            showResponse(data, 'grid_data', 'msg_div');
            window.scrollTo(0, 0);
        }
    });
}

function prConvertSubmit(pr_po = "pr") {
    clearMsg('msg_popup');
    // var postDataItem =   $('#prItemApproval').serialize(); 
    // var postDataBuilder =   $('#prBuilderForm').serialize(); 
    //console.log(postDataItem);
    // console.log(postDataBuilder);
    if (pr_po == "pr") {
        var url = SITE_URL + '/convert/save';
        var postData = $('#prBuilderForm, #prItemApproval').serialize();
    }
    var prSubmitajax = ajaxCall_po(prSubmitajax, url, postData, function(data) {
        var result = JSON.parse(data);
        if (result.is_error) {
            showResponse(data, '', 'msg_popup');
            emLoader('hide');
            window.scrollTo(0, 0);
        } else {
            emLoader('hide');
            lightbox('hide');
            crList();
            showResponse(data, 'grid_data', 'msg_div');
            window.scrollTo(0, 0);
        }
    });
}

function prSubmit(pr_po = "pr") {
    clearMsg('msg_popup');
    if (pr_po == "pr") {
        var url = SITE_URL + '/purchaserequest/save';
        //var postData = $('#prBuilderForm, #prItemApproval').serialize();
        var file_data1 = $('#customer_po_file').prop('files')[0];
        var file_data2 = $('#gc_approval_file').prop('files')[0];
        var file_data3 = $('#costing_details_file').prop('files')[0];
        var postData = new FormData($('#prBuilderForm')[0]);
        postData.append('customer_po_file_new', file_data1);
        postData.append('gc_approval_file_new', file_data2);
        postData.append('costing_details_file_new', file_data3);
        postData.append('formAction', $('#action_name').val());
        postData.append('form_templ_id', $('#form_templ_id').val());
        postData.append('pr_id', $('#pr_id').val());
        postData.append('pr_project_category_hidden', $('#pr_project_category_hidden').val());
        postData.append('pr_shipto_hidden', $('#pr_shipto_hidden').val());
        postData.append('pr_shiptocontact_hidden', $('#pr_shiptocontact_hidden').val());
        postData.append('pr_project_name_dd', $('#pr_project_name_dd').val());
        postData.append('project_name', $('#project_name').val());
        postData.append('project_wo_details', $('#project_wo_details').val());
        postData.append('opportunity_code', $('#opportunity_code').val());
        postData.append('pr_department', $('#pr_department').val());        
        postData.append('approval_req', 'y');
        if ($('#action_name').val() == 'add') {
            var d = new Date();
            var month = d.getMonth() + 1;
            var day = d.getDate();
            var output = d.getFullYear() + '/' + (month < 10 ? '0' : '') + month + '/' + (day < 10 ? '0' : '') + day;
            var view_date = (day < 10 ? '0' : '') + day + '/' + month + '/' + d.getFullYear();
            var save_date = d.getFullYear() + '-' + (month < 10 ? '0' : '') + month + '-' + (day < 10 ? '0' : '') + day;
            postData.append('pr_req_date', save_date);
        } else {
            postData.append('pr_req_date', $('#pr_req_date').val());
        }
        var item_id_arr = [];
        $('.item_id_cls').each(function() {
            var selected_item_id = $(this).val();
            item_id_arr.push(selected_item_id);
        })
        postData.append('item', item_id_arr);
        var item_product_cls = [];
        $('.item_product_cls').each(function() {
            var selected_item_id = $(this).val();
            item_product_cls.push(selected_item_id);
        })
        postData.append('item_product', item_product_cls);
        var item_desc_arr = [];
        $('.item_desc_cls').each(function() {
            var selected_item_desc = $(this).val();
            item_desc_arr.push(selected_item_desc);
        })
        postData.append('item_desc', item_desc_arr);
        var item_qty_arr = [];
        $('.item_qty_cls').each(function() {
            var selected_item_qty = $(this).val();
            item_qty_arr.push(selected_item_qty);
        })
        postData.append('item_qty', item_qty_arr);
        var item_wsr_arr = [];
        $('.item_wsr_cls').each(function() {
            var selected_item_wsr = $(this).val();
            item_wsr_arr.push(selected_item_wsr);
        })
        postData.append('warranty_support_required', item_wsr_arr);
        var approvers_arr = [];
        $('.approvers_cls').each(function() {
            var selected_approvers = $(this).val();
            approvers_arr.push(selected_approvers);
        })
        postData.append('approvers', approvers_arr);
        var approvers_optional_arr = [];
        $('.approvers_optional_cls').each(function() {
            var selected_approvers_optional = $(this).val();
            approvers_optional_arr.push(selected_approvers_optional);
        })
        postData.append('approvers_optional', approvers_optional_arr);
        //var form_data2 = new FormData($('#prItemApproval')[0]);                 
        //form_data.append('form_data2', $('#prItemApproval').serialize());
        var prSubmitajax = ajaxCall_test(prSubmitajax, url, postData, function(data) {
            //var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function(data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '', 'msg_popup');
                emLoader('hide');
                window.scrollTo(0, 0);
            } else {
                emLoader('hide');
                lightbox('hide');
                crList();
                showResponse(data, 'grid_data', 'msg_div');
                window.scrollTo(0, 0);
            }
        });
    } else {
        var url = SITE_URL + '/purchaseorder/save';
        var postData = $('#poOtherDetails, #prItemApproval, #prBuilderForm').serialize();
        // alert(postData);
        var prSubmitajax = ajaxCall_po(prSubmitajax, url, postData, function(data) {
            //var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function(data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '', 'msg_popup');
                emLoader('hide');
                window.scrollTo(0, 0);
            } else {
                emLoader('hide');
                lightbox('hide');
                crList();
                showResponse(data, 'grid_data', 'msg_div');
                window.scrollTo(0, 0);
            }
        });
    }
}

function prSubmitsample(pr_po = "pr") {
    clearMsg('msg_popup');
    if (pr_po == "pr") {
        var url = SITE_URL + '/purchaserequestsample/save';
        var file_data1 = $('#customer_po_file').prop('files')[0];
        var file_data2 = $('#gc_approval_file').prop('files')[0];
        var file_data3 = $('#costing_details_file').prop('files')[0];
        var postData = new FormData($('#prBuilderForm')[0]);
        postData.append('customer_po_file_new', file_data1);
        postData.append('gc_approval_file_new', file_data2);
        postData.append('costing_details_file_new', file_data3);
        postData.append('formAction', $('#action_name').val());
        postData.append('form_templ_id', $('#form_templ_id').val());
        postData.append('pr_id', $('#pr_id').val());
        postData.append('pr_project_category_hidden', $('#pr_project_category_hidden').val());
        postData.append('pr_shipto_hidden', $('#pr_shipto_hidden').val());
        postData.append('pr_shiptocontact_hidden', $('#pr_shiptocontact_hidden').val());
        postData.append('pr_project_name_dd', $('#pr_project_name_dd').val());
        postData.append('project_name', $('#project_name').val());
        postData.append('project_wo_details', $('#project_wo_details').val());
        postData.append('opportunity_code', $('#opportunity_code').val());
        postData.append('pr_department', $('#pr_department').val());
        
        postData.append('approval_req', 'y');
        if ($('#action_name').val() == 'add') {
            var d = new Date();
            var month = d.getMonth() + 1;
            var day = d.getDate();
            var output = d.getFullYear() + '/' + (month < 10 ? '0' : '') + month + '/' + (day < 10 ? '0' : '') + day;
            var view_date = (day < 10 ? '0' : '') + day + '/' + month + '/' + d.getFullYear();
            var save_date = d.getFullYear() + '-' + (month < 10 ? '0' : '') + month + '-' + (day < 10 ? '0' : '') + day;
            postData.append('pr_req_date', save_date);
        } else {
            postData.append('pr_req_date', $('#pr_req_date').val());
        }
        var item_product_cls = [];
        $('.item_product_cls').each(function() {
            var selected_item_id = $(this).val();
            item_product_cls.push(selected_item_id);
        })
        postData.append('item_product', item_product_cls);
        var item_desc_arr = [];
        $('.item_desc_cls').each(function() {
            var selected_item_desc = $(this).val();
            item_desc_arr.push(selected_item_desc);
        })
        postData.append('item_desc', item_desc_arr);
        var item_qty_arr = [];
        $('.item_qty_cls').each(function() {
            var selected_item_qty = $(this).val();
            item_qty_arr.push(selected_item_qty);
        })
        postData.append('item_qty', item_qty_arr);
        var item_wsr_arr = [];
        $('.item_wsr_cls').each(function() {
            var selected_item_wsr = $(this).val();
            item_wsr_arr.push(selected_item_wsr);
        })
        postData.append('warranty_support_required', item_wsr_arr);
        /* var approvers_arr = [];
         $('.approvers_cls').each(function() {
             var selected_approvers = $(this).val();
             approvers_arr.push(selected_approvers);
         })
         postData.append('approvers', approvers_arr);
         var approvers_optional_arr = [];
         $('.approvers_optional_cls').each(function() {
             var selected_approvers_optional = $(this).val();
             approvers_optional_arr.push(selected_approvers_optional);
         })
         postData.append('approvers_optional', approvers_optional_arr);*/
        //var form_data2 = new FormData($('#prItemApproval')[0]);                 
        //form_data.append('form_data2', $('#prItemApproval').serialize());
        var prSubmitajax = ajaxCall_test(prSubmitajax, url, postData, function(data) {
            //var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function(data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '', 'msg_popup');
                emLoader('hide');
                window.scrollTo(0, 0);
            } else {
                emLoader('hide');
                lightbox('hide');
                crList();
                showResponse(data, 'grid_data', 'msg_div');
                window.scrollTo(0, 0);
            }
        });
    } else {
        var url = SITE_URL + '/purchaseorder/save';
        var postData = $('#poOtherDetails, #prItemApproval, #prBuilderForm').serialize();
        // alert(postData);
        var prSubmitajax = ajaxCall_po(prSubmitajax, url, postData, function(data) {
            //var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function(data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '', 'msg_popup');
                emLoader('hide');
                window.scrollTo(0, 0);
            } else {
                emLoader('hide');
                lightbox('hide');
                crList();
                showResponse(data, 'grid_data', 'msg_div');
                window.scrollTo(0, 0);
            }
        });
    }
}

function onQtyEnter(event, element) {
    if (isNumberKey(event)) {
        calculateTotal(element);
        calculateTotalCost();
        return true;
    } else {
        return false;
    }
}

function onCostEnter(event, element) {
    if (isDecimalNumber(event)) {
        calculateTotal(element);
        calculateTotalCost();
        return true;
    } else {
        return false;
    }
}

function calculateTotal(element) {
    var id = element.id.split('-')[1];
    var qty = $("#item_qty-" + id).val();
    var item_estimated_cost = $("#item_estimated_cost-" + id).val();
    //console.log("----------------");
    //console.log(qty);
    // console.log(item_estimated_cost);
    // console.log("------------------");
    if (qty != "" && item_estimated_cost != "") {
        $("#total-" + id).val(parseInt(qty) * parseInt(item_estimated_cost));
    } else {
        $("#total-" + id).val(0);
    }
}

function calculateTotalCost() {
    var sum = 0;
    $('.sum_item_estimated_cost').each(function() {
        var item_val = parseFloat($(this).val());
        console.log(item_val);
        if (isNaN(item_val)) {
            item_val = 0;
        }
        sum += item_val;
        // console.log(sum.toFixed(2));
        $("#total_cost strong").text(sum.toFixed(2));
    });
}

function purchaseordercreate(pr_id, po_vendor_id = '') {
    //alert(pr_id);
    var url = SITE_URL + '/purchaseorder/add';
    var postData = {
        'pr_id': pr_id,
        'po_vendor_id': po_vendor_id
    };
    emLoader('show', trans('messages.msg_templaterendering'));
    var creTemplateajax = ajaxCall(creTemplateajax, url, postData, function(data) {
        lightbox('show', data, trans('label.lbl_purchaseorderadd'), 'full');
        console.log(data);
        $("#build-form").hide();
        $(".render-btn-wrap").hide();
        if (jsonDataAsString != "") {
            setDataedit(); //jsonDataAsString
        } else {
            alert("Invalid Template.");
        }
        setTimeout(function() {
            getPurchaseRenderFormData($('#approved_vendor_id').val());
            $('#sidebar_right #lightbox_data #prBuilderForm .render-form input:visible,select:visible,textarea:visible').not('.form-control').attr('disabled', false);
            emLoader('hide');
        }, 500);
        //var row = $('select[name="allinputtogether"]').append(vendorsDetailsOPtions);
        emLoader('hide');
        initsingleselect();
    });
}

function get_pr_number_by_vendor_id(pr_id, po_vendor_id = '') {
    //alert(pr_id);
    var url = SITE_URL + '/getprnumberbyvendorid';
    var postData = {
        'pr_id': pr_id,
        'po_vendor_id': po_vendor_id
    };
    emLoader('show', trans('messages.msg_templaterendering'));
    var creTemplateajax = ajaxCall(creTemplateajax, url, postData, function(data) {
        var coche = JSON.parse(data);
        // console.log(coche);  
        $('#PR_numbers').html(coche.html);
        emLoader('hide');
    });
}

function viewPo(po_id, type_pr = '') {
    window.open("purchaseorders/" + type_pr + "/" + po_id);
    /*$.post("purchaseorders", { 'po_id': po_id }, function(data) {
         
    });*/
}

function notificatioMessages(id) {
    var url = SITE_URL + '/purchaserequest/getnotifications';
    var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function(data) {
        var result = JSON.parse(data);
        var notification = '<li class="br-t of-h"> <a href="#" class="fw600 p12 animated animated-short fadeInDown"> <span class="fa fa-gear pr5"></span> ' + trans('label.lbl_notifications') + '</a> </li>';
        //$("#dropdown-notifications").html("");
        $("#dropdown-notifications").html(notification);
        if (result.is_error) {
            notification = notification + $("#dropdown-notifications").append(result.message);
        } else {
            $("#dropdown-notifications").append(result.result);
        }
    });
}

function onDiscountEnter(event, per_amount) {
    if (isDecimalNumber(event)) {
        calculateDiscountTotalCost(per_amount);
        return true;
    } else {
        return false;
    }
}

function calculateDiscountTotalCost(per_amount) {
    var total_aount = 0;
    var discount = 0;
    var discount_amount = $("#discount_amount").val();
    var discount_per = $("#discount_per").val();
    var total_cost = $("#sub_total_cost").val();
    if (per_amount == "per") {
        $("#discount_amount").val(0);
        discount = (total_cost * discount_per / 100).toFixed(2);
    } else {
        $("#discount_per").val(0);
        discount = $("#discount_amount").val();
    }
    total_aount = total_cost - discount;
    if (total_aount > 0) {
        $("#total_cost").val(total_aount.toFixed(2));
    } else {
        $("#discount_amount").val(0);
        $("#discount_per").val(0);
        $("#total_cost").val(total_cost);
        alert(trans('messages.msg_discount_cnt_greaterthan_subtotal'));
    }
}