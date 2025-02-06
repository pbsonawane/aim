var postData = null;


//var first_po_id = "";
$(document).ready(function () {

    prList();
    $(document).on("blur", "#opportunity_code", function () {
        var intRegex = /^\d+$/;
        let opportunity_code = $("#opportunity_code").val();
        if (opportunity_code == "") {
            return false;
        }
        var opportunity_code_array = opportunity_code.split("-");
        if (opportunity_code_array[0].toUpperCase() == 'POT' && intRegex.test(opportunity_code_array[1])) {
            getProject(opportunity_code);
        } else {
            alert("Enter Valid POT Id");
            $("#opportunity_code").val('');
            $("#opportunity_code").focus();
        }
    });

    $(document).on("click", ".download_file", function () {
        // alert("download click");
        var att_id = $(this).attr('download_id');
        var att_path = $(this).attr('download_path');
        var att_title = $(this).attr('download_title');
        // downloadAttachment(att_id, att_path,att_title);
        var postData = { 'attach_id': att_id, 'attach_path': att_path, 'attach_title': att_title };
        var url = SITE_URL + '/download_attachment_pr';
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
                var download_path = SITE_URL + '/' + data_arr['html'];

                //download attachment
                var a = document.createElement('a');
                a.setAttribute('href', download_path);
                a.setAttribute('download', '');

                var aj = $(a);
                aj.appendTo('body');
                aj[0].click();
                aj.remove();

                window.scrollTo(0, 0);
            }
        });
    });

    $(document).on("click", ".prlist", function () {
        $(".prlist").removeClass("active");
        $(this).addClass("active");
        var id = $(this).data('id');
        prDetailsLoad(id);
    });

    $(document).on("click", ".conversion_prlist", function () {
        var id = $(this).data('id');
        prDetailsLoad(id);
    });

    $(document).on("click", "#pradd", function () {
        purchaserequestadd();
    });
    $(document).on("click", "#praddsample", function () {
        purchaserequestaddsample();
    });
    $(document).on("click", "#pocreatevendorewise1", function () {
        $('.po_vendor_id').prop('checked', false);
        $('#PR_numbers').html('');
        $('#multiple_po_pr_clubs_modal').modal('show');
    });
    $(document).on('change', '.po_vendor_id', function () {
        $('.po_vendor_id').not(this).prop('checked', false);
        $('#PR_numbers').html('');

    });
    /*$(document).on('click','.po_vendor_id',function(){     
       
      $('.po_vendor_id').attr('checked', false);
      $(this).attr('checked', true);  
       $('.po_vendor_id').not(this).attr('checked', false);         
   });*/
    $(document).on("change", ".category_name", function () {
        let item_id = $(this).attr('id');
        get_item_by_category($(this).val(), item_id);
    });

    $(document).on("click", "#conversionPr", function () {
        convertitemsinpr();
    });
    $(document).on("click", "#predit", function () {
        var pr_id = $(this).data("id");
        purchaserequestedit(pr_id);
    });
    $(document).on("click", "#pocreate", function () {
        var pr_id = $(this).data("id");
        purchaseordercreate(pr_id);
    });
    $(document).on("click", "#add_more_item", function () {
        addMore();
        var row = $(".addmore tr").last();
        var id = $('.addmore tr:last').attr('id');
        $('#' + id + ' td:last-child').html('<i class="fa fa-trash-o mr10 fa-lg remove"></i>');
        /*var id = Number(row.attr('id').match(/\d+/));  
        row.find('td')
        .first()
        .html(id);*/
        row.find('.chosen-select').first().attr('id', "chosen-select-" + id);
        $("#chosen-select-" + id).chosen();
    });

    $(document).on("click", ".remove", function () {
        //removeRow(); 
        if ($(this).closest("tr").attr('id') == "row-1") {
            alert(trans('messages.msg_cannot_delete_default_item'));
            return false;
        }
        if (confirm(trans('messages.msg_confirmation_remove_commonly', {
            "name": "Item"
        }))) {
            $(this).closest("tr").remove();
            calculateTotalCost();
        }
    });

    $(document).on("click", ".actionsPr", function () {
        var id = $(this).attr('id');
        var action = id.split('_')[0];
        if (confirm(trans('messages.msg_actionconfirmation_pr', {
            "name": action.replace("notify", "notify ")
        }))) {
            actionsPr(id);
        }
    });
    $(document).on("click", "#pocreatevendorewise", function () {
        $(".po_vendor_id").prop("checked", false);
        $('#multiple_po').modal('show');
    });
    $(document).on("click", "#create_multiple_po", function () {

        var pr_po_id = $("#pr_po_id").val();
        var po_vendor_id = $(".po_vendor_id:checked").val();
        console.log(pr_po_id + '/' + po_vendor_id);
        if (po_vendor_id == undefined) {
            //console.log(pr_po_id+'/EEE:'+po_vendor_id);
            alert('Please Select at least one vendor!');
        } else if (pr_po_id == '') {
            alert('Someting went wrong!');
        } else if (po_vendor_id !== '' && pr_po_id !== 'undefined') {
            //console.log(pr_po_id+'/sssss:'+po_vendor_id);
            purchaseordercreate(pr_po_id, po_vendor_id);
            $('#multiple_po').modal('hide');
        }

    });
    $(document).on("click", "#create_multiple_pr_po", function () {

        var pr_po_id = $("#pr_po_id").val();
        var po_vendor_id = $("#create_multiple_pr_po_form_submit .po_vendor_id:checked").val();
        // console.log(pr_po_id+'/'+po_vendor_id);
        if (po_vendor_id == undefined) {
            //console.log(pr_po_id+'/EEE:'+po_vendor_id);
            alert('Please Select at least one vendor!');
        } else if (pr_po_id == '') {
            alert('Someting went wrong!');
        } else if (po_vendor_id !== '' && pr_po_id !== 'undefined') {
            //console.log(pr_po_id+'/sssss:'+po_vendor_id);
            //purchaseordercreate(pr_po_id,po_vendor_id);
            //$('#multiple_po').modal('hide');
            get_pr_number_by_vendor_id(pr_po_id, po_vendor_id);
        }

    });
    $(document).on("click", "#create_multiple_pr_po_submit", function () {


        var po_vendor_id = $("#create_multiple_pr_po_form_submit .po_vendor_id:checked").val();
        let postData = [];
        let arr = [];
        $(".pr_ids").each(function (index) {
            if ($(this).is(':checked')) {
                arr.push($(this).val());
            }
        });

        let pr_po_id = arr.join(',');
        // console.log(postData);

        if (po_vendor_id == undefined) {
            //console.log(pr_po_id+'/EEE:'+po_vendor_id);
            alert('Please Select at least one vendor!');
        } else if (pr_po_id == '') {
            alert('Please Select At least one PR number.');
        } else if (po_vendor_id !== '' && pr_po_id !== '') {
            purchaseordercreate(pr_po_id, po_vendor_id);
            $('#multiple_po_pr_clubs_modal').modal('hide');
        }

    });

    // $(document).on("change", ".item_product_cls", function () {
    //     var item = $(this).val();
    //     console.log("selected item");
    //     console.log(item);
    //     var values = $("input[name='item_product[]']")
    //     .map(function () { return $(this).val(); }).get();
    //     console.log(values);       
       
    // });
    // function checkduplicate(item)
    // {
    //     var values = $("input[name='item_product[]']")
    //     .map(function () { return $(this).val(); }).get();
    //     console.log(values);        
    // }

    $(document).on("click", ".convert_to_pr", function () {

        var values = $("input[name='itemquantity[]']")
            .map(function () { return $(this).val(); }).get();
        var total = 0;
        for (var i = 0; i < values.length; i++) {
            total += values[i] << 0;
        }
        if (total == 0) {
            alert("Requester item quantity not be zero."); return false;
        }

        var id = $(this).attr('id');
        var action = id.split('_')[0];
        if (confirm(trans('messages.msg_actionconfirmation_pr', {
            "name": action.replace("Conver", "Conver ")
        }))) {
            convertToPR(id);
        }
    });
    $(document).on("click", "#submitAction", function () {
        submitAction();
    });
    $(document).on("click", "#enableApprovalcheck", function () {
        if ($("#enableApprovalcheck").is(":checked")) {
            $("#enableApproval").show();
        } else {
            $("#enableApproval").hide();
        }
    });
    $(document).on("click", "#enableMailNotificationCheck", function () {
        if ($("#enableMailNotificationCheck").is(":checked")) {
            $(".enableMailNotification").show();
        } else {
            $(".enableMailNotification").hide();
        }
    });
    $(document).on("click", "#prSubmit", function () {
        prSubmit("pr");
    });
    $(document).on("click", "#prSubmitsample", function () {
        prSubmitsample("pr");
    });
    $(document).on("click", "#prConvertSubmit", function () {
        prConvertSubmit("pr");
    });
    $(document).on("click", "#poSubmit", function () { // PR to Po Creation
        prSubmit("po");
    });
    $(document).on("click", "#submitComment", function () {
        approveRejectComment();
    });

    $(document).on("click", "#pr_comment_submit", function () {
        console.log('in onclick event of pr comment');
        pr_Comment();
    });

    $(document).on("click", "#pr_estimatecost_submit", function () {
        console.log('in onclick event of pr estimatecost');
        pr_estimatecost();
    });

    /* Each item Quotation Comparison add */
    $(document).on("click", "#pr_qt_Submit", function () {
        quotation_vendor_cmp();
    });

    /* Show All items and vendor Quotation Details */
    $(document).on("click", "#pr_qt_details", function () {
        quotation_details();
    });

    /* Select items by vendors then submit Quotation Comparisons */
    $(document).on("click", "#pr_qt_final", function () {
        quotation_final();
    });

    /* Submit Quotation Comparisons Approve */
    $(document).on("click", "#qc_approve", function () {
        qc_approve();
    });

    /* Submit Quotation Comparison Reject Comment */
    $(document).on("click", "#submitComment_qc", function () {
        quotation_final();
        approveRejectComment_qc();

    });

    $(document).on({
        mouseenter: function () {
            $(this).removeClass("btn btn-default");
            $(this).addClass("btn btn-success");
        },
        mouseleave: function () {
            $(this).removeClass("btn btn-success");
            $(this).addClass("btn btn-default");
        },
        click: function () {
            var id = $(this).attr('id');
            approveReject(trans('label.lbl_approve'), id);
        }
    }, ".approve button");
    $(document).on({
        mouseenter: function () {
            $(this).removeClass("btn btn-default");
            $(this).addClass("btn btn-danger");
        },
        mouseleave: function () {
            $(this).removeClass("btn btn-danger");
            $(this).addClass("btn btn-default");
        },
        click: function () {
            var id = $(this).attr('id');
            approveReject(trans('label.lbl_reject'), id);

        }
    }, ".reject button");

    /*------------- Quotation Comparison Calls -----------*/
    $(document).on({
        mouseenter: function () {
            $(this).removeClass("btn btn-default");
            $(this).addClass("btn btn-success");
        },
        mouseleave: function () {
            $(this).removeClass("btn btn-success");
            $(this).addClass("btn btn-default");
        },
        click: function () {
            var id = $(this).attr('id');
            approveReject_qc(trans('label.lbl_approve'), id);
        }
    }, ".approve_qc button");

    $(document).on({
        mouseenter: function () {
            $(this).removeClass("btn btn-default");
            $(this).addClass("btn btn-danger");
        },
        mouseleave: function () {
            $(this).removeClass("btn btn-danger");
            $(this).addClass("btn btn-default");
        },
        click: function () {
            var id = $(this).attr('id');
            approveReject_qc(trans('label.lbl_reject'), id);

        }
    }, ".reject_qc button");
    /*------------- Quotation Comparison Calls END -----------*/

    // $(document).on("click",".reject button", function() { var id = $(this).data('id'); alert(id); });
    // $(document).on("click",".approve button", function() { var id = $(this).data('id'); alert(id); });
    $(document).on("change", "#pr_filter_status", function () {
        var status = $(this).val();
        $('input[type=hidden][name=searchkeyword]').val(status);
        prList(status, "pr_filter_status");
    });
    $(document).on("click", ".deleteAttachment", function () {
        var attach_id = $(this).attr('id');
        if (confirm(trans('messages.msg_delattachmentconfirm'))) {
            emLoader('show', trans('label.lbl_loading'));
            closeMsgAuto('msg_div');
            // closeMsgAuto('msg_modal');
            var pr_po_id = $('#pr_po_id[value]').val();
            var att_type = $("#attachment_type").val()
            var url = SITE_URL + '/delete_attachment_pr';

            if (url != '') {
                var postData = { 'attach_id': attach_id, 'pr_po_id': pr_po_id, 'attachment_type': att_type };
                var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function (data) {
                    var result = JSON.parse(data);
                    if (result.is_error) {
                        showResponse(data, 'grid_data', 'msg_div');
                        emLoader('hide');
                        window.scrollTo(0, 0);
                    }
                    else {
                        emLoader('hide');
                        lightbox('hide');
                        prList();
                        showResponse(data, 'grid_data', 'msg_div');
                        window.scrollTo(0, 0);
                    }
                });
            }
        }
        // deleteAttachment(id);
    });
    $(document).on("click", "#poview", function () {
        var po_id = $(this).data('id');
        var type_pr = $(this).data('pr');
        viewPo(po_id, type_pr)
    });
    $(document).on("click", ".dropdown-notifications", function () {
        notificatioMessages();
    });
    $(document).on("click", "#attachDoc", function () {
        $(".tab-block .nav-tabs > li").removeClass("active");
        $(".tab-block .nav-tabs > li.purchase_requesttab").addClass("active");
        $("#podetails_page .tab-content .tab-pane").removeClass("active");
        $("#purchase_request").addClass("active");
        $('html, body').animate({
            scrollTop: $("#attachment_details").offset().top
        }, 1000);
    });
});

function approveReject(action, id) {
    if (confirm(trans('messages.msg_actionconfirmation_pr', {
        "name": action.replace("notify", "notify ")
    }))) {
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
    var callfor = $('#pr_po_type').val();
    if (callfor == 'pr') url = SITE_URL + '/approve_reject_pr';
    if (callfor == 'po') url = SITE_URL + '/approve_reject_po';
    if (url != '') {
        var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function (data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '', 'msg_modal_approve_reject');
                emLoader('hide');
                window.scrollTo(0, 0);
            } else {
                emLoader('hide');
                $('#myModal_approve_reject').modal('hide');
                showResponse(data, 'grid_data', 'msg_div');
                prList();
                window.scrollTo(0, 0);
            }

            closeMsgAuto('msg_div');
        });
    }
}



function pr_estimatecost() {

    var estimate_cost = $("#estimate_cost").val();
    var postData = $("#pr_formEstimatecost").serialize();
    var callfor = 'pr';
    url = SITE_URL + '/save_estimatecost';

    console.log(postData);
    if (estimate_cost == '') {
        alert("Please Enter Estimate Cost");
        return false;
    }
    if (url != '') {
        var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function (data) {

            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, 'grid_data', 'msg_div');
                emLoader('hide');
                window.scrollTo(0, 0);
            } else {
                emLoader('hide');
                showResponse(data, 'grid_data', 'msg_div');
                //prList();
                window.scrollTo(0, 0);
            }
            closeMsgAuto('msg_div');
            $("#estimate_cost").val('');
        });
    }
}

function pr_Comment() {

    var postData = $("#pr_formComment").serialize();
    var callfor = 'pr';
    url = SITE_URL + '/approve_reject_pr';
    emLoader('show', trans('label.lbl_loading'));
    //console.log(postData);


    if (url != '') {

        var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function (data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '', 'msg_modal_approve_reject');
                emLoader('hide');
                window.scrollTo(0, 0);
            } else {
                emLoader('hide');
                $('#myModal_approve_reject').modal('hide');
                showResponse(data, 'grid_data', 'msg_div');
                prList();
                window.scrollTo(0, 0);
            }
            $("#commentboxs").val('');

            closeMsgAuto('msg_div');
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
        var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function (data) {
            var result = JSON.parse(data);
            // console.log(result.content['pr_id']);
            if (result.is_error) {
                emLoader('hide');
                showResponse(data, '', 'msg_div');
                window.scrollTo(0, 0);
            } else {
                emLoader('hide');
                showResponse(data, '', 'msg_div');
                prDetailsLoad(result.content['pr_id']);
                window.scrollTo(0, 0);
            }

            closeMsgAuto('msg_div');
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

        var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function (data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '', 'msg_modal');
                emLoader('hide');
                window.scrollTo(0, 0);
            } else {
                
                emLoader('hide');
                $('#myModal_actions').modal('hide');
                showResponse(data, 'grid_data', 'msg_div');
                prList(status, "pr_filter_status");
                window.scrollTo(0, 0);
            }
            //after call completion, following code is to make page scrollable
            $('body').css('overflow', 'auto');
        });
    }
}

function prList(status = "", dropdown_selector = "") {

    var valstatus = $("#pr_filter_status").val();
    console.log("filter status");
    console.log(status);
    console.log(valstatus);
    console.log(dropdown_selector);
    closeMsgAuto('msg_div');
    emLoader('show', trans('label.lbl_loading'));
    var url = SITE_URL + '/purchaserequest/list';
    //    var postData = {
    //        'searchkeyword':status
    //    }
    var postData = $("#frmdevices").serialize();
    if (valstatus) {
        // var postData = $('#frpurchase').serializeArray();
        var postData = $('#frmdevices').serializeArray();
        postData.push({
            name: "searchkeyword",
            value: valstatus
        });
    }
    var userajax = ajaxCall(userajax, url, postData, function (data) {
        var result = JSON.parse(data);
        var first_pr_id = result.po_id; //result.pr_id;
        //console.log(result);
        showResponse(data, 'pr_list', 'msg_div');

        var opp_pr_id = localStorage.getItem("opp_pr_id");
        if (opp_pr_id) {
            first_pr_id = opp_pr_id;
            localStorage.removeItem("opp_pr_id");
        }

        prDetailsLoad(first_pr_id);
        $(".prlist:first").addClass("active");
        emLoader('hide');
        initsingleselect();
        //      initmultiselect();
        //set dropdown value
        // if (dropdown_selector != "") $("#pr_filter_status").val(status).trigger('chosen:updated');
        if (dropdown_selector != "") $("#pr_filter_status").val(status);
        //Example for chosen-select dropdown:
        //Set selected values in dropdown(for single select)- $('#select-id').val("22").trigger('chosen:updated');
        //Set selected values in dropdown(for multi select)- $('#documents').val(["22", "25", "27"]).trigger('chosen:updated');
        applyVerticalScroll();
    });
}
$(document).on('click', '#quotationattachmentbtn', function () {
    if (document.getElementById("pr_vendor_id").value == '') {
        alert(trans('messages.msg_vendor_select'));
        return false;
    }

    if (document.getElementById("quotationuploadFile").files.length == 0) {
        alert(trans('messages.msg_nofilesattached'));
        return false;
    }

    var url = SITE_URL + '/add_attachment_pr';
    var pr_po_id = $("#pr_po_id111").val();
    emLoader('show', trans('label.lbl_loading'));
    var postData = new FormData(document.getElementById('uploadVendorQuotation'));
    var userajax = ajaxCall_test(userajax, url, postData, function (data) {
        prDetailsLoad(pr_po_id);
        $(".prlist:first").addClass("active");
    });
    return false;
});
function prDetailsLoad(first_pr_id) {
    emLoader('show', trans('label.lbl_loading'));
    var url = SITE_URL + '/purchaserequest/details';
    postData = {
        'first_pr_id': first_pr_id
    };
    var userajax = ajaxCall(userajax, url, postData, function (data) {
        showResponse(data, 'pr_detail', 'msg_div');
        showDropZoneFile("dropZone");
        emLoader('hide');
        $(".download_file").click(function () {
            // var att_id = $(this).attr('download_id');
            // var att_path = $(this).attr('download_path');
            // var att_title= $(this).attr('download_title');
            // downloadAttachment(att_id, att_path,att_title);
        });
    });
}

function purchaserequestadd() {
    closeMsgBox('msg_div');
    //template_name = 'purchaserequest'; 
    // if(template_name)
    // {
    var url = SITE_URL + '/purchaserequest/add';
    // var postData ={'template_name' : template_name};
    emLoader('show', trans('messages.msg_templaterendering'));
    var creTemplateajax = ajaxCall(creTemplateajax, url, postData, function (data) {
        lightbox('show', data, trans('label.lbl_purchaserequestadd'), 'full');
        $("#build-form").hide();
        $(".render-btn-wrap").hide();
        if (jsonDataAsString != "") {
            setDataedit();
        } else {
            alert(trans('messages.msg_invalidtemplate'));
        }
        setTimeout(function () {

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
    var creTemplateajax = ajaxCall(creTemplateajax, url, postData, function (data) {
        lightbox('show', data, trans('label.lbl_purchaserequestadd'), 'full');
        $("#build-form").hide();
        $(".render-btn-wrap").hide();
        if (jsonDataAsString != "") {
            setDataedit();
        } else {
            alert(trans('messages.msg_invalidtemplate'));
        }
        setTimeout(function () {

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

    var creTemplateajax = ajaxCall(creTemplateajax, url, postData, function (data) {
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
        var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function (data) {
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
                //prList();
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

    var creTemplateajax = ajaxCall(creTemplateajax, url, postData, function (data) {
        emLoader('hide');
        var result = JSON.parse(data);
        alert("Quotation Details Approved Successfully.");
        window.close();
        //prDetailsLoad($("#prquotationcomparison #pr_po_id").val()); 
    });
}

/* Select items by vendors then submit Quotation Comparisons */
function quotation_final() {
    closeMsgBox('msg_div');
    var url = SITE_URL + '/prquotationcomparison/update';
    emLoader('show', 'Loading');
    postData = $("#prquotationcomparison").serialize();
    var creTemplateajax = ajaxCall(creTemplateajax, url, postData, function (data) {
        emLoader('hide');
        var result = JSON.parse(data);
        alert("Quotation Details Updated Successfully.");
        window.close();
        //prDetailsLoad($("#prquotationcomparison #pr_po_id").val()); 
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
        alert('Please Select Vendor Names. Minimum 3 Quotations are mandetory.');
        emLoader('hide');
        return false;
    }
    // Rate Mandatory
    var rate_1_v1 = $("#rate_1_v1").val();
    var rate_1_v2 = $("#rate_1_v2").val();
    var rate_1_v3 = $("#rate_1_v3").val();
    if (rate_1_v1 == "" || rate_1_v2 == "" || rate_1_v3 == "") {
        alert('Please Enter Minimum One Quotation for all 3 Vendors');
        emLoader('hide');
        return false;
    }
    // 

    var creTemplateajax = ajaxCall(creTemplateajax, url, postData, function (data) {
        emLoader('hide');
        var result = JSON.parse(data);
        alert("Quotation Details Successfully saved.");
        // prDetailsLoad($("#quotationvendorcomparison #pr_po_id").val());         
        // Hide Quotation and display home start code
        var btn_item_product = localStorage.getItem("btn_item_product");
        $('.qcadd').hide();
        $("#quotationvendorcomparison")[0].reset();
        $('#history,#approvals,#assign_pr_to_user,.purchase_requesttab,.view_historytab,.approve_reject_prtab,.assignprtousertab,.upload_quotationtab,#upload_quotation').removeClass('active');
        $('#purchase_request,.purchase_request').addClass('active');
        $('#' + btn_item_product).removeClass('glyphicon glyphicon-pencil');
        $('#' + btn_item_product).addClass('glyphicon glyphicon-ok');
        // end code
    });
}

/*------------- Quotation Comparison Functions END -----------*/

/* Submit Quotation Comparison Approve */
function get_item_by_category(id, item_id) {
    closeMsgBox('msg_div');
    var url = SITE_URL + '/getitembycategory';
    emLoader('show', 'Loading');
    let postData = {
        ci_templ_id: id
    };
    let item_id1 = item_id.charAt(item_id.length - 1);
    var creTemplateajax = ajaxCall(creTemplateajax, url, postData, function (data) {
        emLoader('hide');
        var result = JSON.parse(data);
        if (result.html == '<option value="">-Select-</option>') {
            var dropdown = '<option value="">No Items</option>';
            $('#item_product-' + item_id1).html(dropdown);
        } else {
            $('#item_product-' + item_id1).html(result.html);
        }
        //prDetailsLoad($("#prquotationcomparison #pr_po_id").val()); 
    });
}


//Convert items in one PR
function convertitemsinpr() {
    closeMsgBox('msg_div');
    //template_name = 'purchaserequest'; 
    // if(template_name)
    // {
    var url = SITE_URL + '/convert/add';
    // var postData ={'template_name' : template_name};
    emLoader('show', trans('messages.msg_templaterendering'));
    var creTemplateajax = ajaxCall(creTemplateajax, url, postData, function (data) {
        lightbox('show', data, trans('label.lbl_purchaserequestadd'), 'full');
        $("#build-form").hide();
        $(".render-btn-wrap").hide();
        if (jsonDataAsString != "") {
            setDataedit();
        } else {
            alert(trans('messages.msg_invalidtemplate'));
        }
        setTimeout(function () {
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

function purchaserequestedit(pr_id) {
    closeMsgBox('msg_div');
    //template_name = 'purchaserequest'; 
    // if(template_name)
    // {
    var url = SITE_URL + '/purchaserequest/edit';
    var postData = {
        'pr_id': pr_id
    };
    emLoader('show', trans('messages.msg_templaterendering'));
    var creTemplateajax = ajaxCall(creTemplateajax, url, postData, function (data) {
        lightbox('show', data, trans('label.lbl_purchaserequestedit'), 'full');
        $("#build-form").hide();
        $(".render-btn-wrap").hide();
        if (jsonDataAsString != "") {
            setDataedit();
        } else {
            alert(trans('messages.msg_invalidtemplate'));
        }
        setTimeout(function () {
            getPurchaseRenderFormData();
            emLoader('hide');
            //applyHorizntalScrool("purchaseRequestScroll");
        }, 500);
        //var row = $('select[name="allinputtogether"]').append(vendorsDetailsOPtions);
        emLoader('hide');
        initsingleselect();
    });
    //}
}

function getPurchaseRenderFormData(vendor_id = '') {

    var url = SITE_URL + '/getPurchaseRenderFormData';
    var vendorajax = ajaxCall(vendorajax, url, { vendor_id: vendor_id }, function (data) {
        if (data) {
            var pr_shipto_hidden = $('#pr_shipto_hidden').val();
            var pr_shiptocontact_hidden = $('#pr_shiptocontact_hidden').val();
            var pr_id = $('#pr_id').val();
            if (pr_id != '') {

                if (pr_shipto_hidden == 'Other') {
                    $('.ship_to_other_class').removeClass('hide');
                } else {
                    $('.ship_to_other_class').addClass('hide');
                }
                if (pr_shiptocontact_hidden == 'Other') {
                    $('.ship_to_contact_other_class').removeClass('hide');
                } else {
                    $('.ship_to_contact_other_class').addClass('hide');
                }
            }
            var jsondata = JSON.parse(data);
            console.log("Nikhil");
            console.log(jsondata);
            //$('select[name="bv_id"]').append(jsondata.businessVerticalDetailsOptions);
            //$('select[name="location_id"]').append(jsondata.locationDetailsOptions);
            //$('select[name="dc_id"]').append(jsondata.datacenterDetailsOptions);
            $('select[name="pr_vendor"]').append(jsondata.vendorsDetailsOptions);
            //$('select[name="pr_cost_center"]').append(jsondata.costcenterDetailsOptions);
            $('select[name="pr_shipto"]').append(jsondata.shiptoDetailsOptions);
            $('select[name="pr_billto"]').append(jsondata.billtoDetailsOptions);
            $('select[name="pr_shipto_contact"]').append(jsondata.shiptoContactDetailsOptions);
            $('select[name="pr_billto_contact"]').append(jsondata.billtoContactDetailsOptions);
            $('select[name="pr_delivery"]').append(jsondata.deliveryDetailsOptions);
            $('select[name="pr_payment_terms"]').append(jsondata.paymenttermsDetailsOptions);
            $('textarea[name="pr_special_terms"]').val(jsondata.pr_special_termsDetails);
            $('select[name="pr_requester_name"]').append(jsondata.requesternameDetailsOptions);

            // $('select[name="pr_vendor"]')[0].selectedIndex = 1;
            if (jsonConfig != "") {
                var jsonConfigData = JSON.parse(jsonConfig);
                console.log("all data");
                console.log(jsonConfigData);
                // console.log("Vendor");
                // console.log(jsonvendorIds[0]);
                // $('select[name="bv_id"]').val(jsonConfigData['bv_id']);
                // $('select[name="location_id"]').val(jsonConfigData['location_id']);
                //$('select[name="dc_id"]').val(jsonConfigData['dc_id']);
                $('select[name="pr_category"]').val(jsonConfigData['pr_category']);
                $('textarea[name="pr_remark"]').val(jsonConfigData['pr_remark']);
                $('select[name="pr_vendor"]').val(jsonConfigData['pr_vendor']);
                //$('select[name="pr_cost_center"]').val(jsonConfigData['pr_cost_center']);
                $('select[name="pr_shipto"]').val(jsonConfigData['pr_shipto']);
                $('select[name="pr_billto"]').val(jsonConfigData['pr_billto']);
                $('select[name="pr_shipto_contact"]').val(jsonConfigData['pr_shipto_contact']);
                $('select[name="pr_billto_contact"]').val(jsonConfigData['pr_billto_contact']);
                $('select[name="pr_delivery"]').val(jsonConfigData['pr_delivery']);
                $('select[name="pr_payment_terms"]').val(jsonConfigData['pr_payment_terms']);
                 $('select[name="pr_requester_name"]').val(jsonConfigData['pr_requester_name']);
             //   $('input[name="pr_requester_name"]').val(jsonConfigData['pr_requester_name']);
                
                $('select[name="pr_requirement"]').val(jsonConfigData['pr_project_category']);
                var jsonvendorIds = JSON.parse(vendorIds);
                $('select[name="pr_category"]').val(jsonConfigData['pr_requirement_for']);
                $('select[name="pr_vendor"]').val(jsonvendorIds[0]);
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
            groupOrder: ['common', 'html',],
            elements: [],
            elementOrder: {
                common: ['button', 'checkbox', 'date-input', 'hidden', 'upload', 'number', 'radio', 'select', 'text-input', 'textarea',]
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
    setTimeout(function () {
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
    // var postDataItem =   $('#prItemApproval').serialize(); 
    // var postDataBuilder =   $('#prBuilderForm').serialize(); 
    //console.log(postDataItem);
    // console.log(postDataBuilder);

    emLoader('show', trans('messages.msg_templaterendering'));
    if (pr_po == "pr") {
        var url = SITE_URL + '/purchaserequest/save';
        var postData = $('#prBuilderForm, #prItemApproval').serialize();
    } else {
        var url = SITE_URL + '/purchaseorder/save';
        var postData = $('#poOtherDetails, #prItemApproval').serialize();
    }
    var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function (data) {
        var result = JSON.parse(data);
        if (result.is_error) {
            showResponse(data, '', 'msg_popup');
            emLoader('hide');
            window.scrollTo(0, 0);
        } else {
            emLoader('hide');
            lightbox('hide');
            prList();
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
    var prSubmitajax = ajaxCall_po(prSubmitajax, url, postData, function (data) {
        var result = JSON.parse(data);
        if (result.is_error) {
            showResponse(data, '', 'msg_popup');
            emLoader('hide');
            window.scrollTo(0, 0);
        } else {
            emLoader('hide');
            lightbox('hide');
            prList();
            showResponse(data, 'grid_data', 'msg_div');
            window.scrollTo(0, 0);
        }
    });
}

function getProject(opportunity_code) {
    var url = SITE_URL + '/purchaserequest/getProject';
    var postData = new FormData();
    postData.append('opp_id', opportunity_code);
    var prSubmitajax = ajaxCall_test(prSubmitajax, url, postData, function (data) {
        var result = JSON.parse(data);
        if (result.is_error) {

        } else {
            var result = JSON.parse(data);
            console.log(result);
            if (result == "") {
                $('#project_name option').remove();
                $("#project_name").append("<option>Please Enter Correct POT Id</option>");
            }
            $('#project_name option').remove();
            $("#project_name").append("<option>Select Project</option>");
            result.forEach(function (result1) {
                console.log(result1['project_name']);
                $("#project_name").append("<option value='" + result1['project_name'] + "'>" + result1['project_name'] + "</option>");
            });
        }
    });
}

function prSubmit(pr_po = "pr") {
    clearMsg('msg_popup');
    if (pr_po == "pr") {
        // item[] ;
        // alert($('select[name="item[]"]').val());
        // alert($('select[name="item_product[]"]').val());
        // alert($('input[name="item_qty[]"]').val());
        // if($('select[name="item[]"]').val()=="" || $('select[name="item_product[]"]').val() == "" 
        // || $('input[name="item_qty[]"]').val() =="")
        // {
        //     alert("Please Fill all details of Item.");
        //     return false;
        // }

        var url = SITE_URL + '/purchaserequest/save';
        //var postData = $('#prBuilderForm, #prItemApproval').serialize();
        if($('#action_name').val() == 'add')
        {
            var file_data4 = $('#internal_file').prop('files')[0];
        }
        
        var file_data1 = $('#customer_po_file').prop('files')[0];
        var file_data2 = $('#gc_approval_file').prop('files')[0];
        var file_data3 = $('#costing_details_file').prop('files')[0];
        var postData = new FormData($('#prBuilderForm')[0]);

        if($('#action_name').val() == 'add')
        {
            postData.append('internal_file', file_data4);
        }
        
        postData.append('customer_po_file_new', file_data1);
        postData.append('gc_approval_file_new', file_data2);
        postData.append('costing_details_file_new', file_data3);
        postData.append('formAction', $('#action_name').val());
        postData.append('form_templ_id', $('#form_templ_id').val());
        postData.append('editrequesterid', $('#editrequesterid').val());
        postData.append('pr_id', $('#pr_id').val());
        postData.append('pr_project_category_hidden', $('#pr_project_category_hidden').val());
        postData.append('pr_shipto_hidden', $('#pr_shipto_hidden').val());
        postData.append('pr_shiptocontact_hidden', $('#pr_shiptocontact_hidden').val());
        postData.append('pr_project_name_dd', $('#pr_project_name_dd').val());
        postData.append('project_name', $('#project_name').val());
        postData.append('project_wo_details', $('#project_wo_details').val());
        postData.append('opportunity_code', $('#opportunity_code').val());
        postData.append('pr_department', $('#pr_department').val());
        postData.append('pr_department_id', $('#pr_department_id').val());
        //postData.append('approval_req_by_input', $("input[type='checkbox']").val());
        //postData.append('approval_req', $("#enableApprovalcheck").val('y'));
        postData.append('approval_req', 'y');
        if ($('#action_name').val() == 'add') {
            var d = new Date();
            var month = d.getMonth() + 1;
            var day = d.getDate();
            var output = d.getFullYear() + '/' + (month < 10 ? '0' : '') + month + '/' + (day < 10 ? '0' : '') + day;
            var view_date = (day < 10 ? '0' : '') + day + '/' + month + '/' + d.getFullYear();
            var save_date = d.getFullYear() + '-' + (month < 10 ? '0' : '') + month + '-' + (day < 10 ? '0' : '') + day;
            postData.append('pr_req_date', save_date);
        }
        else {
            postData.append('pr_req_date', $('#pr_req_date').val());
        }

        var item_id_arr = [];
        $('.item_id_cls').each(function () {
            var selected_item_id = $(this).val();
            item_id_arr.push(selected_item_id);
        })
        postData.append('item', item_id_arr);

        var item_product_cls = [];
        $('.item_product_cls').each(function () {
            var selected_item_id = $(this).val();
            item_product_cls.push(selected_item_id);
        })
        postData.append('item_product', item_product_cls);
        var item_desc_arr = [];
        $('.item_desc_cls').each(function () {
            var selected_item_desc = $(this).val();
            item_desc_arr.push(selected_item_desc);
        })
        postData.append('item_desc', item_desc_arr);
        var item_qty_arr = [];
        $('.item_qty_cls').each(function () {
            var selected_item_qty = $(this).val();
            item_qty_arr.push(selected_item_qty);
        })
        postData.append('item_qty', item_qty_arr);
        var item_wsr_arr = [];
        $('.item_wsr_cls').each(function () {
            var selected_item_wsr = $(this).val();
            item_wsr_arr.push(selected_item_wsr);
        })
        postData.append('warranty_support_required', item_wsr_arr);
        var approvers_arr = [];
        $('.approvers_cls').each(function () {
            var selected_approvers = $(this).val();
            approvers_arr.push(selected_approvers);
        })
        postData.append('approvers', approvers_arr);
        var approvers_optional_arr = [];
        $('.approvers_optional_cls').each(function () {
            var selected_approvers_optional = $(this).val();
            approvers_optional_arr.push(selected_approvers_optional);
        })
        postData.append('approvers_optional', approvers_optional_arr);

        //var form_data2 = new FormData($('#prItemApproval')[0]);                 
        //form_data.append('form_data2', $('#prItemApproval').serialize());
        var prSubmitajax = ajaxCall_test(prSubmitajax, url, postData, function (data) {
            //var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function(data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '', 'msg_popup');
                emLoader('hide');
                window.scrollTo(0, 0);
            } else {
                emLoader('hide');
                lightbox('hide');
                prList();
                showResponse(data, 'grid_data', 'msg_div');
                window.scrollTo(0, 0);
            }
        });
    } else {
        var url = SITE_URL + '/purchaseorder/save';
        var postData = $('#poOtherDetails, #prItemApproval, #prBuilderForm').serialize();
        // alert(postData);

        var prSubmitajax = ajaxCall_po(prSubmitajax, url, postData, function (data) {
            //var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function(data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '', 'msg_popup');
                emLoader('hide');
                window.scrollTo(0, 0);
            } else {
                emLoader('hide');
                lightbox('hide');
                prList();
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
        //postData.append('approval_req_by_input', $("input[type='checkbox']").val());
        //postData.append('approval_req', $("#enableApprovalcheck").val('y'));
        postData.append('approval_req', 'y');
        if ($('#action_name').val() == 'add') {
            var d = new Date();
            var month = d.getMonth() + 1;
            var day = d.getDate();
            var output = d.getFullYear() + '/' + (month < 10 ? '0' : '') + month + '/' + (day < 10 ? '0' : '') + day;
            var view_date = (day < 10 ? '0' : '') + day + '/' + month + '/' + d.getFullYear();
            var save_date = d.getFullYear() + '-' + (month < 10 ? '0' : '') + month + '-' + (day < 10 ? '0' : '') + day;
            postData.append('pr_req_date', save_date);
        }
        else {
            postData.append('pr_req_date', $('#pr_req_date').val());
        }

        /*var item_id_arr = [];
        $('.item_id_cls').each(function() {
            var selected_item_id = $(this).val();
            item_id_arr.push(selected_item_id);
        })
        postData.append('item', item_id_arr);*/

        var item_product_cls = [];
        $('.item_product_cls').each(function () {
            var selected_item_id = $(this).val();
            item_product_cls.push(selected_item_id);
        })
        postData.append('item_product', item_product_cls);
        var item_desc_arr = [];
        $('.item_desc_cls').each(function () {
            var selected_item_desc = $(this).val();
            item_desc_arr.push(selected_item_desc);
        })
        postData.append('item_desc', item_desc_arr);
        var item_qty_arr = [];
        $('.item_qty_cls').each(function () {
            var selected_item_qty = $(this).val();
            item_qty_arr.push(selected_item_qty);
        })
        postData.append('item_qty', item_qty_arr);
        var item_wsr_arr = [];
        $('.item_wsr_cls').each(function () {
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
        var prSubmitajax = ajaxCall_test(prSubmitajax, url, postData, function (data) {
            //var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function(data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '', 'msg_popup');
                emLoader('hide');
                window.scrollTo(0, 0);
            } else {
                emLoader('hide');
                lightbox('hide');
                prList();
                showResponse(data, 'grid_data', 'msg_div');
                window.scrollTo(0, 0);
            }
        });
    } else {
        var url = SITE_URL + '/purchaseorder/save';
        var postData = $('#poOtherDetails, #prItemApproval, #prBuilderForm').serialize();
        // alert(postData);

        var prSubmitajax = ajaxCall_po(prSubmitajax, url, postData, function (data) {
            //var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function(data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '', 'msg_popup');
                emLoader('hide');
                window.scrollTo(0, 0);
            } else {
                emLoader('hide');
                lightbox('hide');
                prList();
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
    $('.sum_item_estimated_cost').each(function () {
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
    var creTemplateajax = ajaxCall(creTemplateajax, url, postData, function (data) {
        lightbox('show', data, trans('label.lbl_purchaseorderadd'), 'full');
        console.log(data);
        $("#build-form").hide();
        $(".render-btn-wrap").hide();
        if (jsonDataAsString != "") {
            setDataedit(); //jsonDataAsString
        } else {
            alert("Invalid Template.");
        }
        setTimeout(function () {

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
    var creTemplateajax = ajaxCall(creTemplateajax, url, postData, function (data) {
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
    var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function (data) {
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