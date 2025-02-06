var postData = null;
$(document).ready(function () {
    contracttypeList();
    $(document).on("click", "#contracttypeadd", function () { contracttypeadd(); });
    $(document).on("click", "#contracttypeaddsubmit", function () { contracttypeaddsubmit(); });
    $(document).on("click", ".contracttype_edit", function () { var contract_type_id = $(this).attr('id'); contracttypeedit(contract_type_id); });
    $(document).on("click", "#contracttypeeditsubmit", function () { contracttypeeditsubmit(); });
    $(document).on("click", ".contracttype_del", function () { var contract_type_id = $(this).attr('id'); contracttypedelete(contract_type_id); });
    
});

function contracttypeList(){
    closeMsgAuto('msg_div');
    emLoader('show', trans('messages.msg_contracttype_loading'));
    var url = SITE_URL + '/contracttype/list';
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

function contracttypeadd() {
    closeMsgBox('msg_div');
    var url = SITE_URL + '/contracttype/add';
    var notifyajax = ajaxCall(notifyajax, url, {}, function (data) {
        lightbox('show', data, trans('messages.msg_contracttype_add'), 'large');
        emLoader('hide');
    });

}

function contracttypeaddsubmit() {

    clearMsg('msg_popup');
    if (cltimer) {
        clas = 'error';
        showAlert("msg_popup", clas,  trans('messages.msg_session_open'));
        return false;
    }
    emLoader('show', trans('label.lbl_contract_type'));
    var url = SITE_URL + '/contracttype/addsubmit';
    var postData = $("#addformcontracttype").serialize();
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
            contracttypeList();
        }

    });
}
function contracttypeedit(contract_type_id) {
    var id = contract_type_id.split('_')[1];
    var postData = { 'datatype': 'json', 'id': id };
    console.log(postData);
    var url = SITE_URL + '/contracttype/edit';

    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        lightbox('show', data, trans('messages.msg_contracttype_edit'), 'medium');
        emLoader('hide');

    });
}

function contracttypeeditsubmit() {
    clearMsg('msg_popup');
	clearMsg('msg_div');
    emLoader('show', trans('messages.msg_updating_contractype'));
    var url = SITE_URL + '/contracttype/editsubmit';
    var postData = $("#addformcontracttype").serialize();
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
            contracttypeList();

        }

    });

}

function contracttypedelete(contract_type_id) {
    if (confirm(trans('messages.msg_contract_delete'))) {
        clearMsg('msg_popup');
        emLoader('show', trans('messages.msg_contract_delete'));
        var id = contract_type_id.split('_')[1];
        var postData = { 'datatype': 'json', 'contract_type_id': id, 'status': 'd' };
        var url = SITE_URL + '/contracttype/delete';
        var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '','msg_div');
                emLoader('hide');
            }
            else {
                emLoader('hide');
                showResponse(data,'grid_data', 'msg_div' );
                contracttypeList();
            }
        });
    }
}