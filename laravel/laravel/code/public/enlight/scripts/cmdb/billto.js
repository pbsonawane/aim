var postData = null;
$(document).ready(function() {
    billtoList();
    $(document).on("click", "#billtoadd", function() {
        billtoadd();
    });
    $(document).on("click", "#billtoaddsubmit", function() {
        billtoaddsubmit();
    });
    $(document).on("click", ".billto_edit", function() {
        var billto_id = $(this).attr('id');
        billtoedit(billto_id);
    });
    $(document).on("click", "#billtoeditsubmit", function() {
        billtoeditsubmit();
    });
    $(document).on("click", ".billto_del", function() {
        var billto_id = $(this).attr('id');
        billtodelete(billto_id);
    });
    $(document).on("click", "#reset", function() {
        resetForm('addformbillto');
    });
});

function billtoList() {
    closeMsgAuto('msg_div');
    emLoader('show', trans('messages.msg_billto_loading'));
    var url = SITE_URL + '/billto/list';
    var postData = $("#frmdevices").serialize();
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

function billtoadd() {
    closeMsgBox('msg_div');
    var url = SITE_URL + '/billto/add';
    var notifyajax = ajaxCall(notifyajax, url, {}, function(data) {
        lightbox('show', data, trans('label.lbl_billto_add'), 'large');
        emLoader('hide');
        initsingleselect();
    });
}

function billtoaddsubmit() {
    clearMsg('msg_popup');
    if (cltimer) {
        clas = 'error';
        showAlert("msg_popup", clas, trans('messages.msg_session_open'));
        return false;
    }
    emLoader('show', 'billto');
    var url = SITE_URL + '/billto/addsubmit';
    var postData = $("#addformbillto").serialize();
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
            billtoList();
        }
    });
}

function billtoedit(billto_id) {
    var id = billto_id.split('_')[1];
    var postData = {
        'datatype': 'json',
        'id': id
    };
    console.log(postData);
    var url = SITE_URL + '/billto/edit';
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {
        lightbox('show', data, trans('label.lbl_billto_edit'), 'large');
        initsingleselect();
        emLoader('hide');
    });
}

function billtoeditsubmit() {
    clearMsg('msg_popup');
    clearMsg('msg_div');
    emLoader('show', trans('messages.msg_updating_billto'));
    var url = SITE_URL + '/billto/editsubmit';
    var postData = $("#addformbillto").serialize();
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {
        console.log(data);
        var result = JSON.parse(data);
        if (result.is_error) {
            showResponse(data, '', 'msg_popup');
            emLoader('hide');
        } else {
            emLoader('hide');
            lightbox('hide');
            showResponse(data, 'grid_data', 'msg_div');
            billtoList();
        }
    });
}

function billtodelete(billto_id) {
    if (confirm(trans('messages.msg_billto_delete'))) {
        clearMsg('msg_popup');
        emLoader('show', trans('messages.msg_deleting_billto'));
        var id = billto_id.split('_')[1];
        var postData = {
            'datatype': 'json',
            'billto_id': id,
            'status': 'd'
        };
        var url = SITE_URL + '/billto/delete';
        var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '', 'msg_div');
                emLoader('hide');
            } else {
                emLoader('hide');
                showResponse(data, 'grid_data', 'msg_div');
                billtoList();
            }
        });
    }
}