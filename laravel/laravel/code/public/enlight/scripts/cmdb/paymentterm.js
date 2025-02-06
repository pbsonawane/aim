var postData = null;
$(document).ready(function() {
    paymenttermList();
    $(document).on("click", "#paymenttermadd", function() {
        paymenttermadd();
    });
    $(document).on("click", "#paymenttermaddsubmit", function() {
        paymenttermaddsubmit();
    });
    $(document).on("click", ".paymentterm_edit", function() {
        var paymentterm_id = $(this).attr('id');
        paymenttermedit(paymentterm_id);
    });
    $(document).on("click", "#paymenttermeditsubmit", function() {
        paymenttermeditsubmit();
    });
    $(document).on("click", ".paymentterm_del", function() {
        var paymentterm_id = $(this).attr('id');
        paymenttermdelete(paymentterm_id);
    });
    $(document).on("click", "#btn_reset", function() {
        resetForm('addformpaymentterm');
    });
});

function paymenttermList() {
    closeMsgAuto('msg_div');
    emLoader('show', trans('messages.msg_paymentterm_loading'));
    var url = SITE_URL + '/paymentterm/list';
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
    } else {
        var mongraphsajax = ajaxCall(mongraphsajax, url, postData, function(data) {
            showResponse(data, 'grid_data', 'msg_div');
            emLoader('hide');
        });
    }
}

function paymenttermadd() {
    closeMsgBox('msg_div');
    emLoader('show', trans('messages.msg_session_open'));
    var url = SITE_URL + '/paymentterm/add';
    var notifyajax = ajaxCall(notifyajax, url, {}, function(data) {
        lightbox('show', data, trans('messages.msg_paymentterm_add'), 'large');
        emLoader('hide');
    });
}

function paymenttermaddsubmit() {
    clearMsg('msg_popup');
    if (cltimer) {
        clas = 'error';
        showAlert("msg_popup", clas, trans('messages.msg_session_open'));
        return false;
    }
    emLoader('show', 'paymentterm');
    var url = SITE_URL + '/paymentterm/addsubmit';
    var postData = $("#addformpaymentterm").serialize();
    console.log(postData);
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {
        var result = JSON.parse(data);
        console.log(result);
        if (result.is_error) {
            showResponse(data, '', 'msg_popup');
            emLoader('hide');
        } else {
            emLoader('hide');
            lightbox('hide');
            window.scrollTo(0, 0);
            showResponse(data, 'grid_data', 'msg_div');
            paymenttermList();
        }
    });
}

function paymenttermedit(paymentterm_id) {
    emLoader('show', trans('messages.msg_updating_paymentterm'));
    var id = paymentterm_id.split('_')[1];
    var postData = {
        'datatype': 'json',
        'id': id
    };
    console.log(postData);
    var url = SITE_URL + '/paymentterm/edit';
    // alert(id);
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {
        lightbox('show', data, trans('messages.msg_paymentterm_edit'), 'large');
        emLoader('hide');
        // alert(id);
    });
}

function paymenttermeditsubmit() {
    clearMsg('msg_popup');
    clearMsg('msg_div');
    emLoader('show', trans('messages.msg_updating_paymentterm'));
    var url = SITE_URL + '/paymentterm/editsubmit';
    var postData = $("#addformpaymentterm").serialize();
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {
        console.log(data);
        var result = JSON.parse(data);
        // console.log(result);
        if (result.is_error) {
            showResponse(data, '', 'msg_popup');
            emLoader('hide');
        } else {
            emLoader('hide');
            lightbox('hide');
            showResponse(data, 'grid_data', 'msg_div');
            paymenttermList();
        }
    });
}

function paymenttermdelete(paymentterm_id) {
    if (confirm(trans('messages.msg_paymentterm_delete'))) {
        clearMsg('msg_popup');
        emLoader('show', trans('messages.msg_deleting_paymentterm'));
        var id = paymentterm_id.split('_')[1];
        var postData = {
            'datatype': 'json',
            'paymentterm_id': id,
            'status': 'd'
        };
        var url = SITE_URL + '/paymentterm/delete';
        var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '', 'msg_div');
                emLoader('hide');
            } else {
                emLoader('hide');
                showResponse(data, 'grid_data', 'msg_div');
                paymenttermList();
            }
        });
    }
}