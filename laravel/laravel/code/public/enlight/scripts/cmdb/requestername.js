var postData = null;
$(document).ready(function() {
    requesternameList();
    $(document).on("click", "#requesternameadd", function() {
        requesternameadd();
    });
    $(document).on("click", "#requesternameaddsubmit", function() {
        requesternameaddsubmit();
    });
    $(document).on("click", ".requestername_edit", function() {
        var requestername_id = $(this).attr('id');
        requesternameedit(requestername_id);
    });
    $(document).on("click", "#requesternameeditsubmit", function() {
        requesternameeditsubmit();
    });
    $(document).on("click", ".requestername_del", function() {
        var requestername_id = $(this).attr('id');
        requesternamedelete(requestername_id);
    });
    $(document).on("click", "#btn_reset", function() {
        resetForm('addformrequestername');
    });
});

function requesternameList() {
    closeMsgAuto('msg_div');
    emLoader('show', trans('messages.msg_requestername_loading'));
    var url = SITE_URL + '/requestername/list';
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

function requesternameadd() {
    closeMsgBox('msg_div');
    emLoader('show', trans('messages.msg_session_open'));
    var url = SITE_URL + '/requestername/add';
    var notifyajax = ajaxCall(notifyajax, url, {}, function(data) {
        lightbox('show', data, trans('messages.msg_requestername_add'), 'large');
        emLoader('hide');
    });
}

function requesternameaddsubmit() {
    clearMsg('msg_popup');
    if (cltimer) {
        clas = 'error';
        showAlert("msg_popup", clas, trans('messages.msg_session_open'));
        return false;
    }
    emLoader('show', 'requestername');
    var url = SITE_URL + '/requestername/addsubmit';
    var postData = $("#addformrequestername").serialize();
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {
        var result = JSON.parse(data);
        if (result.is_error) {
            showResponse(data, '', 'msg_popup');
            emLoader('hide');
        } else {
            emLoader('hide');
            lightbox('hide');
            window.scrollTo(0, 0);
            showResponse(data, 'grid_data', 'msg_div');
            requesternameList();
        }
    });
}

function requesternameedit(requestername_id) {
    emLoader('show', trans('messages.msg_updating_requestername'));
    var id = requestername_id.split('_')[1];
    var postData = {
        'datatype': 'json',
        'id': id
    };
    console.log(postData);
    var url = SITE_URL + '/requestername/edit';
    // alert(id);
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {
        lightbox('show', data, trans('messages.msg_requestername_edit'), 'large');
        emLoader('hide');
        // alert(id);
    });
}

function requesternameeditsubmit() {
    clearMsg('msg_popup');
    clearMsg('msg_div');
    emLoader('show', trans('messages.msg_updating_requestername'));
    var url = SITE_URL + '/requestername/editsubmit';
    var postData = $("#addformrequestername").serialize();
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
            requesternameList();
        }
    });
}

function requesternamedelete(requestername_id) {
    if (confirm(trans('messages.msg_requestername_delete'))) {
        clearMsg('msg_popup');
        emLoader('show', trans('messages.msg_deleting_requestername'));
        var id = requestername_id.split('_')[1];
        var postData = {
            'datatype': 'json',
            'requestername_id': id,
            'status': 'd'
        };
        var url = SITE_URL + '/requestername/delete';
        var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '', 'msg_div');
                emLoader('hide');
            } else {
                emLoader('hide');
                showResponse(data, 'grid_data', 'msg_div');
                requesternameList();
            }
        });
    }
}