var postData = null;
$(document).ready(function () {
    softwaremanufacturerList();
    $(document).on("click", "#softwaremanufactureradd", function () { softwaremanufactureradd(); });
    $(document).on("click", "#softwaremanufactureraddsubmit", function () { softwaremanufactureraddsubmit(); });
    $(document).on("click", ".softwaremanufacturer_edit", function () { var software_manufacturer_id = $(this).attr('id'); softwaremanufactureredit(software_manufacturer_id); });
    $(document).on("click", "#softwaremanufacturereditsubmit", function () { softwaremanufacturereditsubmit(); });
    $(document).on("click", ".softwaremanufacturer_del", function () { var software_manufacturer_id = $(this).attr('id'); softwaremanufacturerdelete(software_manufacturer_id); });
    $(document).on("click", "#btn_reset", function () { resetForm('addformsoftwaremanufacturer'); });
});

function softwaremanufacturerList(){
    closeMsgAuto('msg_div');
    emLoader('show', trans('messages.msg_softwaremanufacturer_loading'));
    var url = SITE_URL + '/softwaremanufacturer/list';
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

function softwaremanufactureradd() {
    closeMsgBox('msg_div');
    emLoader('show', trans('messages.msg_softwaremanufacturer_loading'));
    var url = SITE_URL + '/softwaremanufacturer/add';
    var notifyajax = ajaxCall(notifyajax, url, {}, function (data) {
        lightbox('show', data, trans('messages.msg_softwaremanufacturer_add'), 'large');
        emLoader('hide');
    });

}

function softwaremanufactureraddsubmit() {

    clearMsg('msg_popup');
    if (cltimer) {
        clas = 'error';
        showAlert("msg_popup", clas,  trans('messages.msg_session_open'));
        return false;
    }
    emLoader('show', trans('label.lbl_software_manufacturer'));
    var url = SITE_URL + '/softwaremanufacturer/addsubmit';
    var postData = $("#addformsoftwaremanufacturer").serialize();
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
            softwaremanufacturerList();
        }

    });
}
function softwaremanufactureredit(software_manufacturer_id) {
    emLoader('show', trans('messages.msg_softwaremanufacturer_loading'));
    var id = software_manufacturer_id.split('_')[1];
    var postData = { 'datatype': 'json', 'id': id };
    console.log(postData);
    var url = SITE_URL + '/softwaremanufacturer/edit';

    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        lightbox('show', data, trans('messages.msg_softwaremanufacturer_edit'), 'large');
        emLoader('hide');

    });
}

function softwaremanufacturereditsubmit() {
    clearMsg('msg_popup');
	clearMsg('msg_div');
    emLoader('show', trans('messages.msg_updating_softwaremanufacturer'));
    var url = SITE_URL + '/softwaremanufacturer/editsubmit';
    var postData = $("#addformsoftwaremanufacturer").serialize();
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
            softwaremanufacturerList();

        }

    });

}

function softwaremanufacturerdelete(software_manufacturer_id) {
    if (confirm(trans('messages.msg_softwaremanufacturer_delete'))) {
        clearMsg('msg_popup');
        emLoader('show', trans('messages.msg_softwaremanufacturer_delete'));
        var id = software_manufacturer_id.split('_')[1];
        var postData = { 'datatype': 'json', 'software_manufacturer_id': id, 'status': 'd' };
        var url = SITE_URL + '/softwaremanufacturer/delete';
        var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '','msg_div');
                emLoader('hide');
            }
            else {
                emLoader('hide');
                showResponse(data,'grid_data', 'msg_div' );
                softwaremanufacturerList();
            }
        });
    }
}