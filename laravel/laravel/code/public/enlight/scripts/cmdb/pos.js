var postData = null;
//var first_po_id = "";
$(document).ready(function() {
    poList();
    $(document).on("click", ".polist", function() {
        $(".polist").removeClass("active");
        $(this).addClass("active");
        var id = $(this).data('id');
        poDetailsLoad(id);
    });
    $(document).on("click", "#pocreate", function() {
        purchaseordercreate();
    });
    $(document).on("click", "#add_more_item", function() {
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
    $(document).on("click", ".remove", function() {
        //alert(  $(this).closest("tr").index());
        //removeRow(); 
        if ($(this).closest("tr").attr('id') == "row-1") {
            alert(trans('messages.msg_cannot_delete_default_item'));
            return false;
        }
        if (confirm(trans('messages.msg_confirmation_remove_commonly', {
                "name": "Item"
            }))) {
            //alert($(this).closest("tr").attr('id'));
            $(this).closest("tr").remove();
            calculateTotalCost();
            var discount_amount = $("#discount_amount").val();
            var discount_per = $("#discount_per").val();
            if (discount_amount != 0 || discount_per != 0) {
                if (discount_amount > 0) calculateDiscountTotalCost("amount");
                else calculateDiscountTotalCost("per");
            }
        }
    });
    $(document).on("click", ".actionsPo", function() {
        var id = $(this).attr('id');
        var action = id.split('_')[0];
        if (action == "invoice") {
            var sub_action = id.split('_')[3];
            if (sub_action == "edit") {
                var post_fix = " " + trans('label.lbl_of');
            } else {
                var post_fix = " " + trans('label.lbl_in');
            }
        } else {
            var sub_action = "";
            var post_fix = "";
        }
        //alert(trans('messages.msg_actionconfirmation_po', {"name": action.replace("notify", "notify ")}));
        if (confirm(trans('messages.msg_actionconfirmation_po', {
                "name": sub_action + " " + action.replace("notify", "notify ") + post_fix
            }))) {
            if (action == "Print Preview") {
                printPreview(id);
            } else {
                actionsPo(id);
            }
        }
    });
    $(document).on("click", ".invoice_delete", function() {
        var id = $(this).attr('id');
        invoiceDelete(id);
    });
    $(document).on("click", ".invoice_edit", function() {
        var id = $(this).attr('id');
        actionsPo(id);
    });
    $(document).on("click", "#submitAction", function() {
        submitAction();
    });
    $(document).on("click", "#submitActionInvoice", function() {
        submitAction("Invoice");
    });
    $(document).on("click", "#submitActionreceived", function() {
        submitAction("received");
    });
    $(document).on("click", "#prSubmit", function() {
        //Direct Po
        prSubmit();
    });
    $(document).on("click", "#pr_comment_submit", function() {
        console.log('in onclick event of po comment');
        pr_Comment();
    });
    $(document).on("click", "#poSubmit", function() {
        // PR=> PO EDIT
        poSubmit();
    });
    $(document).on("click", "#submitComment", function() {
        approveRejectComment();
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
            approveReject('Hold', id);
        }
    }, ".hold button");
    $(document).on("click", "#enableMailNotificationCheck", function() {
        if ($("#enableMailNotificationCheck").is(":checked")) {
            $(".enableMailNotification").show();
        } else {
            $(".enableMailNotification").hide();
        }
    });
    $(document).on("click", "#poEdit", function() {
        var id = $(this).data('id');
        var po_id = id.split('_')[0];
        var pr_id = id.split('_')[1];
        purchaseorderedit(po_id, pr_id);
    });
    $(document).on("click", ".dropdown-notifications", function() {
        notificatioMessages();
    });
    $(document).on("keyup ", ".item_receive_qty", function() {
        if ($(this).val() == "") {
            $(this).val(0);
        }
        var maxval = parseInt($(this).attr('max'));
        var actualval = parseInt($(this).val());
        if (actualval > maxval) {
            alert(trans('messages.msg_recivingqty_cnt_morethan_orderqty'))
            $(this).val(0);
        }
    });
    $(document).on("change", "#po_filter_status", function() {
        var status = $(this).val();
        poList(status, "#po_filter_status");
    });
    $(document).on("click", ".deleteAttachment", function() {
        var attach_id = $(this).attr('id');
        if(confirm(trans('messages.msg_delattachmentconfirm')))
        {
            emLoader('show', trans('label.lbl_loading'));
            closeMsgAuto('msg_div');
            // closeMsgAuto('msg_modal');
            var pr_po_id = $('#pr_po_id[value]').val();
            var att_type = $("#attachment_type").val()
            var url      = SITE_URL + '/delete_attachment_po';
            
            if(url != ''){
            var postData = { 'attach_id' : attach_id, 'pr_po_id':pr_po_id, 'attachment_type':att_type};
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
    $(document).on("click", "#attachDoc", function() {
        $(".tab-block .nav-tabs > li").removeClass("active");
        $(".tab-block .nav-tabs > li.purchase_ordertab").addClass("active");
        $("#podetails_page .tab-content .tab-pane").removeClass("active");
        $("#purchase_order").addClass("active");
        $('html, body').animate({
            scrollTop: $("#attachment_details").offset().top
        }, 1000);
    });
    $(document).on("click", "#enableApprovalcheck", function() {
        if ($("#enableApprovalcheck").is(":checked")) {
            $("#enableApproval").show();
        } else {
            $("#enableApproval").hide();
        }
    });
    setTimeout(function() {
        $('#upload_err, #upload_suc').remove();
    }, 8000);
});

function poList(status = null, dropdown_selector = "") {
    closeMsgAuto('msg_div');
    emLoader('show', trans('label.lbl_loading'));
    var url = SITE_URL + '/purchaseorder/list';
    var postData = $("#frpurchase").serialize();
    if (status) {
        var postData = $('#frpurchase').serializeArray();
        postData.push({
            name: "searchkeyword",
            value: status
        });
    }
    var userajax = ajaxCall(userajax, url, postData, function(data) {
        var result = JSON.parse(data);
        var first_po_id = result.po_id;
        //console.log(result);
        showResponse(data, 'po_list', 'msg_div');
        poDetailsLoad(first_po_id);
        $(".polist:first").addClass("active");
        emLoader('hide');
        initsingleselect();
        //set dropdown value
        if (dropdown_selector != "") $(dropdown_selector).val(status).trigger('chosen:updated');
        //initmultiselect();
        applyVerticalScroll();
    });
}

function poDetailsLoad(first_po_id) {
    //alert(first_po_id);
    emLoader('show', trans('label.lbl_loading'));
    var url = SITE_URL + '/purchaseorder/details';
    postData = {
        'first_po_id': first_po_id
    };
    var userajax = ajaxCall(userajax, url, postData, function(data) {
        showResponse(data, 'po_detail', 'msg_div');
        emLoader('hide');
        window.scrollTo(0, 0);
        //poInvoiceLoad(first_po_id);
        $(".download_file").click(function() {
            var att_id = $(this).attr('download_id');
            var att_path = $(this).attr('download_path');
            var att_title = $(this).attr('title');
            // downloadAttachment(att_id, att_path);
            var postData    = { 'attach_id': att_id,'attach_path': att_path, 'attach_title': att_title};
            var url = SITE_URL + '/download_attachment_po';
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
    });
}
// function poInvoiceLoad(first_po_id)
// {
//     var url     = SITE_URL+'/purchaseorder/invoice';
//     postData    = {
//         po_id: first_po_id
//     }
//  var userajax = ajaxCall(userajax,url,postData,function(data){      
//         showResponse(data, 'invoice', 'msg_div');
//     });      
// }
function purchaseordercreate() {
    var url = SITE_URL + '/purchaseorder/add';
    //var postData ={'pr_id' : pr_id};
    emLoader('show', trans('label.lbl_loading'));
    var creTemplateajax = ajaxCall(creTemplateajax, url, postData, function(data) {
        lightbox('show', data, trans('label.lbl_po_add'), 'full');
        $("#build-form").hide();
        $(".render-btn-wrap").hide();
        if (jsonDataAsString != "") {
            setDataedit(jsonDataAsString);
        } else {
            alert(trans('messages.msg_invalidtemplate'));
        }
        setTimeout(function() {
            
             getPurchaseRenderFormData($('#approved_vendor_id').val());
            emLoader('hide');
        }, 500);
        //var row = $('select[name="allinputtogether"]').append(vendorsDetailsOPtions);        
        emLoader('hide');
        initsingleselect();
        initmultiselect();
    });
}

function setDataedit(jsonDataAsString) {
    //closeMsgAuto('msg_div')
    //console.log("Rendering...");
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
function pr_Comment() {

    var postData = $("#pr_formComment").serialize();
    var callfor ='pr';
    url = SITE_URL + '/approve_reject_pr';

    //console.log(postData);


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
                poList();
                window.scrollTo(0, 0);
            }
        });
    }
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

function getPurchaseRenderFormData(vendor_id='') {
    var url = SITE_URL + '/getPurchaseRenderFormData';
    var vendorajax = ajaxCall(vendorajax, url, {vendor_id:vendor_id}, function(data) {
    // var vendorajax = ajaxCall(vendorajax, url, postData, function(data) {
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
            $('select[name="bv_id"]').append(jsondata.businessVerticalDetailsOptions);
            $('select[name="location_id"]').append(jsondata.locationDetailsOptions);
            $('select[name="dc_id"]').append(jsondata.datacenterDetailsOptions);
            $('select[name="pr_vendor"]').append(jsondata.vendorsDetailsOptions);
            $('select[name="pr_cost_center"]').append(jsondata.costcenterDetailsOptions);
            $('select[name="pr_shipto"]').append(jsondata.shiptoDetailsOptions);
            $('select[name="pr_billto"]').append(jsondata.billtoDetailsOptions);
            $('select[name="pr_shipto_contact"]').append(jsondata.shiptoContactDetailsOptions);
            $('select[name="pr_billto_contact"]').append(jsondata.billtoContactDetailsOptions);
            $('select[name="pr_delivery"]').append(jsondata.deliveryDetailsOptions);
            $('select[name="pr_payment_terms"]').append(jsondata.paymenttermsDetailsOptions);
            $('textarea[name="pr_special_terms"]').append(jsondata.pr_special_termsDetails);
            if (jsonConfig != "") {
                var jsonConfigData = JSON.parse(jsonConfig);
                $('select[name="bv_id"]').val(jsonConfigData['bv_id']);
                $('select[name="location_id"]').val(jsonConfigData['location_id']);
                $('select[name="dc_id"]').val(jsonConfigData['dc_id']);
                $('select[name="pr_vendor"]').val(jsonConfigData['pr_vendor']);
                $('select[name="pr_cost_center"]').val(jsonConfigData['pr_cost_center']);
                $('select[name="pr_shipto"]').val(jsonConfigData['pr_shipto']);
                $('select[name="pr_billto"]').val(jsonConfigData['pr_billto']);
                $('select[name="pr_shipto_contact"]').val(jsonConfigData['pr_shipto_contact']);
                $('select[name="pr_billto_contact"]').val(jsonConfigData['pr_billto_contact']);
                $('select[name="pr_delivery"]').val(jsonConfigData['pr_delivery']);
                $('select[name="pr_payment_terms"]').val(jsonConfigData['pr_payment_terms']);
                $('textarea[name="pr_special_terms"]').val(jsonConfigData['pr_special_terms']);
            }
        }
    });
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
        $("#total_cost").val(sum.toFixed(2));
        $("#sub_total_cost").val(sum.toFixed(2));
    });
}

function onDiscountEnter(event, per_amount) {
    if (isDecimalNumber(event)) {
        calculateDiscountTotalCost(per_amount);
        return true;
    } else {
        console.log("false");
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

function prSubmit() {
    clearMsg('msg_popup');
    // var postDataItem =   $('#prItemApproval').serialize(); 
    // var postDataBuilder =   $('#prBuilderForm').serialize(); 
    //console.log(postDataItem);
    // console.log(postDataBuilder);
    var url = SITE_URL + '/purchaserequest/save';
    var postData = $('#poOtherDetails , #prBuilderForm, #prItemApproval').serialize();

    var form1 = new FormData('#prBuilderForm');
        var form2 = new FormData('#prItemApproval');
        console.log(form1);
        console.log(form2);
        alert('aadf');

    var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function(data) {
        var result = JSON.parse(data);
        if (result.is_error) {
            showResponse(data, '', 'msg_popup');
            emLoader('hide');
            window.scrollTo(0, 0);
        } else {
            emLoader('hide');
            lightbox('hide');
            poList();
            showResponse(data, 'grid_data', 'msg_div');
            window.scrollTo(0, 0);
        }
    });
}

function poSubmit() {
    clearMsg('msg_popup');
    var url = SITE_URL + '/purchaseorder/save';
    var postData = $('#poOtherDetails, #prBuilderForm, #prItemApproval').serialize();

      
    var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function(data) {
        var result = JSON.parse(data);
        if (result.is_error) {
            showResponse(data, '', 'msg_popup');
            emLoader('hide');
            window.scrollTo(0, 0);
        } else {
            emLoader('hide');
            lightbox('hide');
            poList();
            showResponse(data, 'grid_data', 'msg_div');
            window.scrollTo(0, 0);
        }
    });
}

function approveReject(action, id) {
    if (confirm("Are you sure you want to " + action + " this PO ?")) {
        var approval_status = id.split('_')[0];
        var user_id = id.split('_')[1];
        var po_id = id.split('_')[2];
        var confirmed_optional = id.split('_')[3];
        $('#myModal_approve_reject').modal('show');
        var title_approve_reject = approval_status.charAt(0).toUpperCase() + approval_status.slice(1);
        
        // var title_approve_reject = approval_status == "rejected" ? trans('label.lbl_reject') : trans('label.lbl_approve');
        $("#modal-title_approve_reject").html(title_approve_reject);
        $("#myModal_approve_reject #pr_po_id").val(po_id);
        $("#myModal_approve_reject #user_id").val(user_id);
        $("#myModal_approve_reject #approval_status").val(approval_status);
        $("#myModal_approve_reject #confirmed_optional").val(confirmed_optional);
        $("#myModal_approve_reject #pr_po_type").val("po");
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
                poList();
                window.scrollTo(0, 0);
            }
        });
    }
}

function invoiceDelete(inv_id) {
    if (inv_id != '') {
        if (confirm(trans('label.msg_confirm'))) {
            clearMsg('msg_popup');
            emLoader('show', trans('label.lbl_loading'));
            var id_arr = inv_id.split('_');
            var id = id_arr[id_arr.length - 1];
            var postData = {
                'invoice_id': id,
                'status': 'd'
            };
            var url = SITE_URL + '/invoice_po/delete';
            var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {
                var result = JSON.parse(data);
                if (result.is_error) {
                    showResponse(data, '', 'msg_div');
                    emLoader('hide');
                } else {
                    emLoader('hide');
                    showResponse(data, 'grid_data', 'msg_div');
                    poList();
                }
            });
        }
    }
}

function actionsPo(id) {
    var action = id.split('_')[0];
    var user_id = id.split('_')[1];
    var pr_id = id.split('_')[2];
    if (action == 'invoice') {
        $('#myModal_actions_invoice').modal('show');
        $('#myModal_actions_invoice form')[0].reset();
        if (id.split('_')[3] != undefined && id.split('_')[4] != undefined) {
            var formaction = id.split('_')[3];
            var invoice_id = id.split('_')[4];
            var url = SITE_URL + '/invoice_po';
            var postData = {
                'invoice_id': invoice_id,
                'po_id': pr_id
            };
            // alert(postData);
            var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function(data) {
                var result = JSON.parse(data);
                if (result.is_error) {
                    alert(trans('messages.msg_norecordfound'));
                    $('#myModal_actions_invoice').modal('hide');
                } else {
                    var details = JSON.parse(result.details);
                    // console.log(details);
                    // console.log(details.comment);
                    $("#myModal_actions_invoice #id").val(details.id);
                    $("#myModal_actions_invoice #received_date").val(details.received_date);
                    $("#myModal_actions_invoice #payment_due_date").val(details.payment_due_date);
                    $("#myModal_actions_invoice #comment").val(details.comment);
                }
            });
            $("#myModal_actions_invoice #invoice_id").val(invoice_id);
            $("#myModal_actions_invoice #formaction").val(formaction);
        }
        $("#myModal_actions_invoice #modal-title_actions").html(action.replace("notify", "notify ").toUpperCase());
        $("#myModal_actions_invoice #modal-title_actions_2").html(action);
        $("#myModal_actions_invoice #pr_po_id").val(pr_id);
        $("#myModal_actions_invoice #pr_po_type").val("po");
        $("#myModal_actions_invoice #user_id").val(user_id);
        $("#myModal_actions_invoice #action").val(action);
        datecalendar('payment_due_date');
        datecalendar('received_date');
        $('body').css('overflow', 'auto');
    } else if (action == "received") {
        $('#myModal_actions_received').modal('show');
        $('#myModal_actions_received form')[0].reset();
        /// alert(action);
        //$("#myModal_actions_received #modal-title_actions").html(action.toUpperCase());
        $("#myModal_actions_received #modal-title_actions_2").html(action);
        $("#myModal_actions_received #pr_po_id").val(pr_id);
        $("#myModal_actions_received #pr_po_type").val("po");
        $("#myModal_actions_received #user_id").val(user_id);
        $("#myModal_actions_received #action").val(action);
    } else {
        var notify_to_id = id.split('_')[3];
        $('#myModal_actions').modal('show');
        $('#myModal_actions form')[0].reset();
        /// alert(action);
        $("#myModal_actions #modal-title_actions").html(action.replace("notify", "notify ").toUpperCase());
        $("#myModal_actions #modal-title_actions_2").html(action);
        $("#myModal_actions #pr_po_id").val(pr_id);
        $("#myModal_actions #pr_po_type").val("po");
        $("#myModal_actions #user_id").val(user_id);
        $("#myModal_actions #notify_to_id").val(notify_to_id);
        $("#myModal_actions #action").val(action);
        // alert(pr_id);
    }
}

function submitAction(id = '') {
    clearMsg('msg_div');
    clearMsg(id + 'msg_modal');
    //   var url      = SITE_URL + '/purchaserequest/prpoformActions';
    var url = '';
    var act = $('.modal:visible form [id=action]').val();
    emLoader('show', trans('label.lbl_loading'));
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
        // var postData = new FormData($("#prformActionsInvoice")[0]); 
        if(act == 'invoice'){
            var postData = new FormData($("#prformActionsInvoice")[0]); 
            var prSubmitajax = ajaxCall_test(prSubmitajax, url, postData, function(data) {
                var result = JSON.parse(data);
                if (result.is_error) {
                    showResponse(data, '', id + 'msg_modal');
                    emLoader('hide');
                    window.scrollTo(0, 0);
                } else {
                    emLoader('hide');
                    //lightbox('hide');
                    if (act == "invoice") {
                        $('#myModal_actions_invoice').modal('hide');
                    } else if (act == "received") {
                        $('#myModal_actions_received').modal('hide');
                    } else {
                        $('#myModal_actions').modal('hide');
                    }
                    showResponse(data, 'grid_data', 'msg_div');
                    poList();
                    window.scrollTo(0, 0);
                }
                $('body').css('overflow', 'auto');
            });
        }else{
            var postData = $("#prformActions" + id).serialize();
            var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function(data) {
            // var prSubmitajax = ajaxCall_test(prSubmitajax, url, postData, function(data) {
                var result = JSON.parse(data);
                if (result.is_error) {
                    showResponse(data, '', id + 'msg_modal');
                    emLoader('hide');
                    window.scrollTo(0, 0);
                } else {
                    emLoader('hide');
                    //lightbox('hide');
                    if (act == "invoice") {
                        $('#myModal_actions_invoice').modal('hide');
                    } else if (act == "received") {
                        $('#myModal_actions_received').modal('hide');
                    } else {
                        $('#myModal_actions').modal('hide');
                    }
                    showResponse(data, 'grid_data', 'msg_div');
                    poList();
                    window.scrollTo(0, 0);
                }
                $('body').css('overflow', 'auto');
            });
        }
        
    }
}

function purchaseorderedit(po_id, pr_id) {
    var url = SITE_URL + '/purchaseorder/edit';
    var postData = {
        'po_id': po_id,
        'pr_id': pr_id
    };
    emLoader('show', 'Template Rendering');
    var creTemplateajax = ajaxCall(creTemplateajax, url, postData, function(data) {
        lightbox('show', data, trans('label.lbl_po_edit'), 'full');
        $("#build-form").hide();
        $(".render-btn-wrap").hide();
        // alert(jsonDataAsString);
        if (jsonDataAsString != "") {
            setDataedit(jsonDataAsString);
        } else {
            alert(trans('messages.msg_invalidtemplate'));
        }
        setTimeout(function() {
            getPurchaseRenderFormData($('#approved_vendor_id').val());
            emLoader('hide');
        }, 500);
        //var row = $('select[name="allinputtogether"]').append(vendorsDetailsOPtions);
        emLoader('hide');
        initsingleselect();
    });
}

function printPreview(id) {
    closeMsgAuto('msg_div');
    closeMsgAuto('msg_modal');
    var url = SITE_URL + '/purchaseorder/printpreview';
    var postData = $("#prformActions").serialize();
    //alert(id);
    var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function(data) {
        $('#myModal_approve_reject').modal('show');
    });
}

$(document).on('click','.resendtoapproval',function(){
    var result = confirm('Do you want to perform this action?');
    if(!result){
        return false;
    }else{
       closeMsgAuto('msg_div');
        var url = SITE_URL + '/purchaseorder/resendtoapproval';
        var postData = {
            po_id: $(this).data('poid'),
            user_id: $(this).data('userid')
        }
        //alert(id);
        var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function(data) {
            showResponse(data, 'grid_data', 'msg_div');
            poList();
        }); 
    }
    
});

function notificatioMessages(id) {
    var url = SITE_URL + '/purchaserequest/getnotifications';
    var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function(data) {
        var result = JSON.parse(data);
        var notification = '<li class="br-t of-h"> <a href="#" class="fw600 p12 animated animated-short fadeInDown"> <span class="fa fa-gear pr5"></span> ' + trans('label.lbl_notifications') + '</a> </li>';
        //$("#dropdown-notifications").html("");
        $("#dropdown-notifications").html(notification);
        if (result.is_error) {
            //notification = notification + 
            $("#dropdown-notifications").append(result.message);
        } else {
            $("#dropdown-notifications").append(result.result);
        }
    });
}

function show_received_assetlist(title, ci_type_id, ci_templ_id, po_id) {
    console.log('title : ' + title);
    console.log('ci_type_id : ' + ci_type_id);
    console.log('ci_templ_id : ' + ci_templ_id);
    console.log('po_id : ' + po_id);
    if (view_asset_permission == true) {
        var url = SITE_URL + '/assets/0/' + ci_templ_id + '/' + po_id;
        var win = window.open(url, '_blank');
        win.focus();
    }
}