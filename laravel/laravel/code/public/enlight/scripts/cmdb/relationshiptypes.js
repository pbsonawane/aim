var postData = null;
$(document).ready(function () {
    relationshiptypeList();
    $(document).on("click", "#relationshiptypeadd", function () { relationshiptypeadd(); });
    $(document).on("click", "#relationshiptypeaddsubmit", function () { relationshiptypeaddsubmit(); });
    $(document).on("click", ".relationshiptype_edit", function () { var relationship_type_id = $(this).attr('id'); relationshiptypeedit(relationship_type_id); });
    $(document).on("click", "#relationshiptypeeditsubmit", function () { relationshiptypeeditsubmit(); });
    $(document).on("click", ".relationshiptype_del", function () { var relationship_type_id = $(this).attr('id'); relationshiptypedelete(relationship_type_id); });
    
});

function relationshiptypeList(){
    closeMsgAuto('msg_div');
    emLoader('show', trans('label.lbl_loading'));
    var url         = SITE_URL + '/relationshiptype/list';
    var postData    = $("#frmdevices").serialize();
    var exporttype  = $("#frmdevices input[name=exporttype]").val();

//    if (exporttype == 'pdf' || exporttype == 'csv' || exporttype == 'print') {
//        var obj_form = document.frmdevices;
//        var mywindow = submitForm(url, obj_form, 1, 1);
//        $("#frmdevices input[name=exporttype]").val('');
//        $("#frmdevices input[name=page]").val('');
//        emLoader('hide');
//    }
//    else {
        var ajax_result = ajaxCall(ajax_result, url, postData, function (data) {
            showResponse(data, 'grid_data', 'msg_div');
            emLoader('hide');
        });
//    }
}

function relationshiptypeadd() {
    closeMsgBox('msg_div');
    var url = SITE_URL + '/relationshiptype/add';
    var notifyajax = ajaxCall(notifyajax, url, {}, function (data) {
        lightbox('show', data, trans('label.lbl_addrelationshiptype'), 'large');
        emLoader('hide');
    });

}

function relationshiptypeaddsubmit() {

    clearMsg('msg_popup');
    if (cltimer) {
        clas = 'error';
        showAlert("msg_popup", clas,  trans('messages.msg_session_open'));
        return false;
    }
    emLoader('show', trans('label.lbl_loading'));
    var url = SITE_URL + '/relationshiptype/addsubmit';
    var postData = $("#addformrelationshiptype").serialize();
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
            relationshiptypeList();
        }

    });
}
function relationshiptypeedit(relationship_type_id) {
    var id       = relationship_type_id.split('_')[1];
    var postData = { 'datatype': 'json', 'id': id };
    var url      = SITE_URL + '/relationshiptype/edit';
    console.log(postData);

    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        lightbox('show', data, trans('label.lbl_editrelationshiptype'), 'large');
        emLoader('hide');

    });
}

function relationshiptypeeditsubmit() {
    clearMsg('msg_popup');
	clearMsg('msg_div');
    emLoader('show', trans('label.lbl_loading'));
    var url             = SITE_URL + '/relationshiptype/editsubmit';
    var postData        = $("#addformrelationshiptype").serialize();
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
            relationshiptypeList();
        }

    });

}

function relationshiptypedelete(relationship_type_id) {
    if (confirm(trans('label.msg_confirm'))) {
        clearMsg('msg_popup');
        emLoader('show', trans('label.lbl_loading'));
        var id              = relationship_type_id.split('_')[1];
        var postData        = { 'datatype': 'json', 'rel_type_id': id, 'status': 'd' };
        var url             = SITE_URL + '/relationshiptype/delete';
        var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '','msg_div');
                emLoader('hide');
            }
            else {
                emLoader('hide');
                showResponse(data,'grid_data', 'msg_div' );
                relationshiptypeList();
            }
        });
    }
}