var postData = null;
$(document).ready(function() {
    contactList();
    $(document).on("click", "#contactadd", function() {
        contactadd();
    });
    $(document).on("click", "#contactaddsubmit", function() {
        contactaddsubmit();
    });
    $(document).on("click", ".contact_edit", function() {
        var contact_id = $(this).attr('id');
        contactedit(contact_id);
    });
    $(document).on("click", "#contacteditsubmit", function() {
        contacteditsubmit();
    });
    $(document).on("click", ".contact_del", function() {
        var contact_id = $(this).attr('id');
        contactdelete(contact_id);
    });
    $(document).on("click", "#btn_reset", function() {
        resetForm('addformcontact');
    });
});

function contactList() {
    closeMsgAuto('msg_div');
    emLoader('show', trans('messages.msg_contact_loading'));
    var url = SITE_URL + '/contact/list';
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

function contactadd() {
    closeMsgBox('msg_div');
    emLoader('show', trans('messages.msg_session_open'));
    var url = SITE_URL + '/contact/add';
    var notifyajax = ajaxCall(notifyajax, url, {}, function(data) {
        lightbox('show', data, trans('messages.msg_contact_add'), 'large');
        emLoader('hide');
    });
}

function contactaddsubmit() {
    clearMsg('msg_popup');
    if (cltimer) {
        clas = 'error';
        showAlert("msg_popup", clas, trans('messages.msg_session_open'));
        return false;
    }
    emLoader('show', 'contact');
    var url = SITE_URL + '/contact/addsubmit';
    var postData = $("#addformcontact").serialize();
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
            contactList();
        }
    });
}

function contactedit(contact_id) {
    emLoader('show', trans('messages.msg_updating_contact'));
    var id = contact_id.split('_')[1];
    var postData = {
        'datatype': 'json',
        'id': id
    };
    console.log(postData);
    var url = SITE_URL + '/contact/edit';
    // alert(id);
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {
        lightbox('show', data, trans('messages.msg_contact_edit'), 'large');
        emLoader('hide');
        // alert(id);
    });
}

function contacteditsubmit() {
    clearMsg('msg_popup');
    clearMsg('msg_div');
    emLoader('show', trans('messages.msg_updating_contact'));
    var url = SITE_URL + '/contact/editsubmit';
    var postData = $("#addformcontact").serialize();
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
            contactList();
        }
    });
}

function contactdelete(contact_id) {
    if (confirm(trans('messages.msg_contact_delete'))) {
        clearMsg('msg_popup');
        emLoader('show', trans('messages.msg_deleting_contact'));
        var id = contact_id.split('_')[1];
        var postData = {
            'datatype': 'json',
            'contact_id': id,
            'status': 'd'
        };
        var url = SITE_URL + '/contact/delete';
        var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '', 'msg_div');
                emLoader('hide');
            } else {
                emLoader('hide');
                showResponse(data, 'grid_data', 'msg_div');
                contactList();
            }
        });
    }
}