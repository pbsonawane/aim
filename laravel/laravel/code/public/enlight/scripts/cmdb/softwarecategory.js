var postData = null;
$(document).ready(function () {
    softwarecategoryList();
    $(document).on("click", "#softwarecategoryadd", function () { softwarecategoryadd(); });
    $(document).on("click", "#softwarecategoryaddsubmit", function () { softwarecategoryaddsubmit(); });
    $(document).on("click", ".softwarecategory_edit", function () { var software_category_id = $(this).attr('id'); softwarecategoryedit(software_category_id); });
    $(document).on("click", "#softwarecategoryeditsubmit", function () { softwarecategoryeditsubmit(); });
    $(document).on("click", ".softwarecategory_del", function () { var software_category_id = $(this).attr('id'); softwarecategorydelete(software_category_id); });
    $(document).on("click", "#btn_reset", function () { resetForm('addformsoftwarecategory'); });
});

function softwarecategoryList(){
    closeMsgAuto('msg_div');
    emLoader('show', trans('messages.msg_softwarecategory_loading'));
    var url = SITE_URL + '/softwarecategory/list';
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

function softwarecategoryadd() {
    closeMsgBox('msg_div');
    emLoader('show', trans('messages.msg_softwarecategory_loading'));
    var url = SITE_URL + '/softwarecategory/add';
    var notifyajax = ajaxCall(notifyajax, url, {}, function (data) {
        lightbox('show', data, trans('messages.msg_softwarecategory_add'), 'large');
        emLoader('hide');
    });

}

function softwarecategoryaddsubmit() {

    clearMsg('msg_popup');
    if (cltimer) {
        clas = 'error';
        showAlert("msg_popup", clas,  trans('messages.msg_session_open'));
        return false;
    }
    emLoader('show', trans('label.lbl_software_category'));
    var url = SITE_URL + '/softwarecategory/addsubmit';
    var postData = $("#addformsoftwarecategory").serialize();
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
            softwarecategoryList();
        }

    });
}
function softwarecategoryedit(software_category_id) {
    emLoader('show', trans('messages.msg_softwarecategory_loading'));
    var id = software_category_id.split('_')[1];
    var postData = { 'datatype': 'json', 'id': id };
    console.log(postData);
    var url = SITE_URL + '/softwarecategory/edit';

    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        lightbox('show', data, trans('messages.msg_softwarecategory_edit'), 'large');
        emLoader('hide');

    });
}

function softwarecategoryeditsubmit() {
    clearMsg('msg_popup');
	clearMsg('msg_div');
    emLoader('show', trans('messages.msg_updating_softwarecategory'));
    var url = SITE_URL + '/softwarecategory/editsubmit';
    var postData = $("#addformsoftwarecategory").serialize();
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
            softwarecategoryList();

        }

    });

}

function softwarecategorydelete(software_category_id) {
    if (confirm(trans('messages.msg_softwarecategory_delete'))) {
        clearMsg('msg_popup');
        emLoader('show', trans('messages.msg_softwarecategory_delete'));
        var id = software_category_id.split('_')[1];
        var postData = { 'datatype': 'json', 'software_category_id': id, 'status': 'd' };
        var url = SITE_URL + '/softwarecategory/delete';
        var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '','msg_div');
                emLoader('hide');
            }
            else {
                emLoader('hide');
                showResponse(data,'grid_data', 'msg_div' );
                softwarecategoryList();
            }
        });
    }
}