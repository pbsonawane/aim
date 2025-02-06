var postData = null;
$(document).ready(function() {
    shiptoList();
    $(document).on("click", "#shiptoadd", function() {
        shiptoadd();
    });
    $(document).on("click", "#shiptoaddsubmit", function() {
        shiptoaddsubmit();
    });
    $(document).on("click", ".shipto_edit", function() {
        var shipto_id = $(this).attr('id');
        shiptoedit(shipto_id);
    });
    $(document).on("click", "#shiptoeditsubmit", function() {
        shiptoeditsubmit();
    });
    $(document).on("click", ".shipto_del", function() {
        var shipto_id = $(this).attr('id');
        shiptodelete(shipto_id);
    });
    $(document).on("click", "#reset", function() {
        resetForm('addformshipto');
    });
});

function shiptoList() {
    closeMsgAuto('msg_div');
    emLoader('show', trans('messages.msg_shipto_loading'));
    var url = SITE_URL + '/shipto/list';
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

function shiptoadd() {
    closeMsgBox('msg_div');
    var url = SITE_URL + '/shipto/add';
    var notifyajax = ajaxCall(notifyajax, url, {}, function(data) {
        lightbox('show', data, trans('label.lbl_shipto_add'), 'large');
        emLoader('hide');
        initsingleselect();
    });
}

function shiptoaddsubmit() {
    clearMsg('msg_popup');
    if (cltimer) {
        clas = 'error';
        showAlert("msg_popup", clas, trans('messages.msg_session_open'));
        return false;
    }
    emLoader('show', 'shipto');
    var url = SITE_URL + '/shipto/addsubmit';
    var postData = $("#addformshipto").serialize();
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
            shiptoList();
        }
    });
}

function shiptoedit(shipto_id) {
    var id = shipto_id.split('_')[1];
    var postData = {
        'datatype': 'json',
        'id': id
    };
    console.log(postData);
    var url = SITE_URL + '/shipto/edit';
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {
        lightbox('show', data, trans('label.lbl_shipto_edit'), 'large');
        initsingleselect();
        emLoader('hide');
    });
}

function shiptoeditsubmit() {
    clearMsg('msg_popup');
    clearMsg('msg_div');
    emLoader('show', trans('messages.msg_updating_shipto'));
    var url = SITE_URL + '/shipto/editsubmit';
    var postData = $("#addformshipto").serialize();
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
            shiptoList();
        }
    });
}

function shiptodelete(shipto_id) {
    if (confirm(trans('messages.msg_shipto_delete'))) {
        clearMsg('msg_popup');
        emLoader('show', trans('messages.msg_deleting_shipto'));
        var id = shipto_id.split('_')[1];
        var postData = {
            'datatype': 'json',
            'shipto_id': id,
            'status': 'd'
        };
        var url = SITE_URL + '/shipto/delete';
        var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '', 'msg_div');
                emLoader('hide');
            } else {
                emLoader('hide');
                showResponse(data, 'grid_data', 'msg_div');
                shiptoList();
            }
        });
    }
}