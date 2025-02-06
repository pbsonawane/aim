var postData = null;
$(document).ready(function () {
    designationList();
    $(document).on("click", ".designationadd", function () { designationAdd(); });
    $(document).on("click", "#designationsave", function () { designationSave(); });
    $(document).on("click", "#designationreset", function () { $("#addformdesignation").trigger("reset") });
    $(document).on("click", ".designationedit", function () {
        var rid = $(this).attr('id'); designationEdit(rid);
    });
    $(document).on("click", "#designationupdate", function () { designationUpdate(); });
    $(document).on("click", ".designationdelete", function () {
        var rid = $(this).attr('id'); designationDelete(rid);
    });
});
function designationList() {
    closeMsgAuto('msg_div');
    emLoader('show', 'Loading Designations');
    var url = SITE_URL + '/designations/list';
    var postData = $("#frmdesignations").serialize();
    var designationajax = ajaxCall(designationajax, url, postData, function (data) {
        showResponse(data, 'grid_data', 'msg_div');
        emLoader('hide');
    });
}
function designationAdd() {
    var url = SITE_URL + '/designationadd';
    var postData = {};
    var notifyajax = ajaxCall(notifyajax, url, postData, function (data) {
        lightbox('show', data, 'Designation Add', 'medium');
        emLoader('hide');
    });
}
function designationSave() {
    clearMsg('msg_popup');
    var url = SITE_URL + '/designationsave';
    var postData = $("#addformdesignation").serialize();
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        var result = JSON.parse(data);
        if (result.is_error) {
            showResponse(data, '', 'msg_popup');
            emLoader('hide');
        }
        else {
            emLoader('hide');
            lightbox('hide');
            showResponse(data, 'grid_data', 'msg_div');
            designationList();
        }
    });
}
function designationEdit(rid) {
    emLoader('show', 'Updating Designations');
    var id = rid.split('_')[1];
    console.log(id);
    var postData = { 'datatype': 'json', 'designationid': id };
    var url = SITE_URL + '/designationedit';
    var designationditajax = ajaxCall(designationditajax, url, postData, function (data) {
        lightbox('show', data, 'Designation Edit', 'medium');
        emLoader('hide');
    });
}
function designationUpdate() {
    clearMsg('msg_popup');
    var url = SITE_URL + '/designationupdate';
    var postData = $("#addformdesignation").serialize();
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        console.log(data);
        var result = JSON.parse(data);
        if (result.is_error) {
            showResponse(data, '', 'msg_popup');
            emLoader('hide');
        }
        else {
            emLoader('hide');
            lightbox('hide');
            showResponse(data, 'grid_data', 'msg_div');
            designationList();
        }
    });
}
function designationDelete(rid) {
    if (confirm("Are you sure you want to delete?")) {
        emLoader('show', 'Deleting Designation');
        var id = rid.split('_')[1];
        var postData = { 'datatype': 'json', 'designationid': id };
        var url = SITE_URL + '/designationdelete';
        var designationdeleteajax = ajaxCall(designationdeleteajax, url, postData, function (data) {
            showResponse(data, 'grid_data', 'msg_div');
            designationList();
            emLoader('hide');
        });
    }
}
