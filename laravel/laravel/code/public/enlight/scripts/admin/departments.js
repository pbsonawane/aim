

var postData = null;
$(document).ready(function () {
    departmentList();

    $(document).on("click", ".departmentadd", function () { departmentadd(); });
    $(document).on("click", "#departmentaddsubmit", function () { departmentaddsubmit(); });
    $(document).on("click", ".department_edit", function () {
        var department_id = $(this).attr('id'); departmentedit(department_id);
    });
    $(document).on("click", "#departmenteditsubmit", function () { departmenteditsubmit(); });
    $(document).on("click", ".department_del", function () { var department_id = $(this).attr('id'); departmentdelete(department_id); });
    $(document).on("click", "#department_reset", function () { resetForm('addformdepartment'); });

});

function departmentList() {
    
    closeMsgAuto('msg_div');
    emLoader('show', 'Loading Departments');
    var url = SITE_URL + '/departments/list';
    var postData = $("#frmdevices").serialize();
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

function departmentadd() {
    closeMsgBox('msg_div');
    var url = SITE_URL + '/departmentadd';
    var notifyajax = ajaxCall(notifyajax, url, {}, function (data) {
        lightbox('show', data, 'Department Add', 'large');
        emLoader('hide');
    });
}
function departmentaddsubmit() {

    clearMsg('msg_popup');
    if (cltimer) {
        clas = 'error';
        showAlert("msg_popup", clas, "Session is already open.");
        return false;
    }
    emLoader('show', 'Department');
    var url = SITE_URL + '/departmentaddsubmit';
    var postData = $("#addformdepartment").serialize();
    console.log(postData);
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        var result = JSON.parse(data);
        console.log(result);
        //console.log(result.message.error);
        if (result.is_error) {
            showResponse(data, '', 'msg_popup');
            emLoader('hide');
        }
        else {
            emLoader('hide');
            lightbox('hide');
            // showResponse(data, 'msg_div');
            showResponse(data, 'grid_data', 'msg_div');
            departmentList();
        }
    });
}

function departmentedit(department_id) {
    //alert(department_id);
    var id = department_id.split('_')[1];
    var postData = { 'datatype': 'json', 'id': id };
    console.log(postData);
    var url = SITE_URL + '/departmentedit';

    var departmentediteditajax = ajaxCall(departmentediteditajax, url, postData, function (data) {
        lightbox('show', data, 'Department Edit', 'medium');
        emLoader('hide');

    });
}

function departmenteditsubmit() {
    clearMsg('msg_popup');
    clearMsg('msg_div');   
    emLoader('show', 'Updating Department');
    var url = SITE_URL + '/departmenteditsubmit';
    var postData = $("#addformdepartment").serialize();
    var departmentajax = ajaxCall(departmentajax, url, postData, function (data) {
        var result = JSON.parse(data);
        console.log(result);
        if (result.is_error) {
            showResponse(data, '', 'msg_popup');
            emLoader('hide');
        }
        else {
            emLoader('hide');
            lightbox('hide');
            showResponse(data,'grid_data','msg_div' );
            departmentList();
        }


    });
}
function departmentdelete(department_id) {
    if (confirm('Are you sure you want to delete department record')) {
        clearMsg('msg_popup');
        emLoader('show', 'Deleting Department');
        var id = department_id.split('_')[1];
        var postData = { 'datatype': 'json', 'department_id': id, 'status': 'd' };
        var url = SITE_URL + '/departmentdelete';
        var departmentdelajax = ajaxCall(departmentdelajax, url, postData, function (data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '','msg_div');
                emLoader('hide');
            }
            else {
                emLoader('hide');
                showResponse(data,'grid_data', 'msg_div' );
            }

        });
    }
}

