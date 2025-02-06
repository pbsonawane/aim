var postData = null;
$(document).ready(function() {
    deliveryList();
    $(document).on("click", "#deliveryadd", function() {
        deliveryadd();
    });
    $(document).on("click", "#deliveryaddsubmit", function() {
        deliveryaddsubmit();
    });
    $(document).on("click", ".delivery_edit", function() {
        var delivery_id = $(this).attr('id');
        deliveryedit(delivery_id);
    });
    $(document).on("click", "#deliveryeditsubmit", function() {
        deliveryeditsubmit();
    });
    $(document).on("click", ".delivery_del", function() {
        var delivery_id = $(this).attr('id');
        deliverydelete(delivery_id);
    });
    $(document).on("click", "#btn_reset", function() {
        resetForm('addformdelivery');
    });
});

function deliveryList() {
    closeMsgAuto('msg_div');
    emLoader('show', trans('messages.msg_delivery_loading'));
    var url = SITE_URL + '/delivery/list';
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

function deliveryadd() {
    closeMsgBox('msg_div');
    emLoader('show', trans('messages.msg_session_open'));
    var url = SITE_URL + '/delivery/add';
    var notifyajax = ajaxCall(notifyajax, url, {}, function(data) {
        lightbox('show', data, trans('messages.msg_delivery_add'), 'large');
        emLoader('hide');
    });
}

function deliveryaddsubmit() {
    clearMsg('msg_popup');
    if (cltimer) {
        clas = 'error';
        showAlert("msg_popup", clas, trans('messages.msg_session_open'));
        return false;
    }
    emLoader('show', 'delivery');
    var url = SITE_URL + '/delivery/addsubmit';
    var postData = $("#addformdelivery").serialize();
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
            deliveryList();
        }
    });
}

function deliveryedit(delivery_id) {
    emLoader('show', trans('messages.msg_updating_delivery'));
    var id = delivery_id.split('_')[1];
    var postData = {
        'datatype': 'json',
        'id': id
    };
    console.log(postData);
    var url = SITE_URL + '/delivery/edit';
    // alert(id);
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {
        lightbox('show', data, trans('messages.msg_delivery_edit'), 'large');
        emLoader('hide');
        // alert(id);
    });
}

function deliveryeditsubmit() {
    clearMsg('msg_popup');
    clearMsg('msg_div');
    emLoader('show', trans('messages.msg_updating_delivery'));
    var url = SITE_URL + '/delivery/editsubmit';
    var postData = $("#addformdelivery").serialize();
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
            deliveryList();
        }
    });
}

function deliverydelete(delivery_id) {
    if (confirm(trans('messages.msg_delivery_delete'))) {
        clearMsg('msg_popup');
        emLoader('show', trans('messages.msg_deleting_delivery'));
        var id = delivery_id.split('_')[1];
        var postData = {
            'datatype': 'json',
            'delivery_id': id,
            'status': 'd'
        };
        var url = SITE_URL + '/delivery/delete';
        var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function(data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '', 'msg_div');
                emLoader('hide');
            } else {
                emLoader('hide');
                showResponse(data, 'grid_data', 'msg_div');
                deliveryList();
            }
        });
    }
}