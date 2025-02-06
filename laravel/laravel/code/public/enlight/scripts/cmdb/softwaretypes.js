var postData = null;
$(document).ready(function () {
    softwaretypeList();
    $(document).on("click", "#softwaretypeadd", function () { softwaretypeadd(); });
    $(document).on("click", "#softwaretypeaddsubmit", function () { softwaretypeaddsubmit(); });
    $(document).on("click", ".softwaretype_edit", function () { var software_type_id = $(this).attr('id'); softwaretypeedit(software_type_id); });
    $(document).on("click", "#softwaretypeeditsubmit", function () { softwaretypeeditsubmit(); });
    $(document).on("click", ".softwaretype_del", function () { var software_type_id = $(this).attr('id'); softwaretypedelete(software_type_id); });
    $(document).on("click", "#btn_reset", function () { resetForm('addformsoftwaretype'); });
});

function softwaretypeList(){
    closeMsgAuto('msg_div');
    emLoader('show', trans('messages.msg_softwaretype_loading'));
    var url = SITE_URL + '/softwaretype/list';
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

function softwaretypeadd() {
    closeMsgBox('msg_div');
    var url = SITE_URL + '/softwaretype/add';
    var notifyajax = ajaxCall(notifyajax, url, {}, function (data) {
        lightbox('show', data, trans('messages.msg_softwaretype_add'), 'large');
        emLoader('hide');
    });

}

function softwaretypeaddsubmit() {

    clearMsg('msg_popup');
    if (cltimer) {
        clas = 'error';
        showAlert("msg_popup", clas,  trans('messages.msg_session_open'));
        return false;
    }
    emLoader('show', trans('label.lbl_software_type'));
    var url = SITE_URL + '/softwaretype/addsubmit';
    var postData = $("#addformsoftwaretype").serialize();
    console.log(postData);
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
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
            showResponse(data, 'grid_data', 'msg_div');
            softwaretypeList();
        }

    });
}
function softwaretypeedit(software_type_id) {
    var id = software_type_id.split('_')[1];
    var postData = { 'datatype': 'json', 'id': id };
    console.log(postData);
    var url = SITE_URL + '/softwaretype/edit';

    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        lightbox('show', data, trans('messages.msg_softwaretype_edit'), 'medium');
        emLoader('hide');

    });
}

function softwaretypeeditsubmit() {
    clearMsg('msg_popup');
	clearMsg('msg_div');
    emLoader('show', trans('messages.msg_updating_softwaretype'));
    var url = SITE_URL + '/softwaretype/editsubmit';
    var postData = $("#addformsoftwaretype").serialize();
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
            softwaretypeList();

        }

    });

}

function softwaretypedelete(software_type_id) {
    if (confirm(trans('messages.msg_softwaretype_delete'))) {
        clearMsg('msg_popup');
        emLoader('show', trans('messages.msg_softwaretype_delete'));
        var id = software_type_id.split('_')[1];
        var postData = { 'datatype': 'json', 'software_type_id': id, 'status': 'd' };
        var url = SITE_URL + '/softwaretype/delete';
        var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '','msg_div');
                emLoader('hide');
            }
            else {
                emLoader('hide');
                showResponse(data,'grid_data', 'msg_div' );
                softwaretypeList();
            }
        });
    }
}