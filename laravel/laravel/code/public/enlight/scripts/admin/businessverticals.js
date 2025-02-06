var postData = null;
$(document).ready(function () {
    businessverticalList();

    $(document).on("click", ".businessverticaladd", function () { businessverticaladd(); });
    $(document).on("click", "#businessverticaladdsubmit", function () { businessverticaladdsubmit(); });
    $(document).on("click", ".businessvertical_edit", function () {
        var bv_id = $(this).attr('id'); businessverticaledit(bv_id);
    });
    $(document).on("click", "#businessverticaleditsubmit", function () { businessverticaleditsubmit(); });
    $(document).on("click", ".businessvertical_del", function () { var bv_id = $(this).attr('id'); businessverticaldelete(bv_id); });
    $(document).on("click", "#businessvertical_reset", function () { resetForm('addformbusinessvertical'); });
});

function businessverticalList() {
    closeMsgAuto('msg_div');
    emLoader('show', 'Loading Business Verticals');
    var url = SITE_URL + '/businessverticals/list';
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

function businessverticaladd() {
    closeMsgBox('msg_div');
    var url = SITE_URL + '/businessverticaladd';
    var notifyajax = ajaxCall(notifyajax, url, {}, function (data) {
        lightbox('show', data, 'Business Vertical Add', 'large');
        emLoader('hide');
    });
}

function businessverticaladdsubmit() {

    clearMsg('msg_popup');
    if (cltimer) {
        clas = 'error';
        showAlert("msg_popup", clas, "Session is already open.");
        return false;
    }
    emLoader('show', 'Business Vertical');
    var url = SITE_URL + '/businessverticaladdsubmit';
    var postData = $("#addformbusinessvertical").serialize();
    console.log(postData);
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        var result = JSON.parse(data);
        console.log(result);
        if (result.is_error) {
            showResponse(data, '','msg_popup');
            emLoader('hide');
        }
        else {
            emLoader('hide');
            lightbox('hide');
            // showResponse(data, 'msg_div');
            window.scrollTo(0, 0);
            showResponse(data, 'grid_data', 'msg_div');
            businessverticalList();
        }

    });
}
function businessverticaledit(bv_id) {
    var id = bv_id.split('_')[1];
    var postData = { 'datatype': 'json', 'id': id };
    console.log(postData);
    var url = SITE_URL + '/businessverticaledit';

    var businessverticaleditajax = ajaxCall(businessverticaleditajax, url, postData, function (data) {
        lightbox('show', data, 'Business Vertical Edit', 'medium');
        emLoader('hide');

    });
}

function businessverticaleditsubmit() {
    clearMsg('msg_popup');
	clearMsg('msg_div');
    emLoader('show', 'Updating Business Vertical ');
    var url = SITE_URL + '/businessverticaleditsubmit';
    var postData = $("#addformbusinessvertical").serialize();
    var businessverticalajax = ajaxCall(businessverticalajax, url, postData, function (data) {
        var result = JSON.parse(data);
        console.log(result);
        if (result.is_error) {
            showResponse(data, '', 'msg_popup');
            emLoader('hide');
        }
        else {
            emLoader('hide');
            lightbox('hide');
            showResponse(data, 'grid_data', 'msg_div');
            businessverticalList();
        }
    });
}
function businessverticaldelete(bv_id) {
    if (confirm('Are you sure you want to delete Business Vertical record')) {
        closeMsgBox('msg_div');
        emLoader('show', 'Deleting Business Vertical');
        var id = bv_id.split('_')[1];
        var postData = { 'datatype': 'json', 'bv_id': id, 'status': 'd' };
        var url = SITE_URL + '/businessverticaldelete';
        var businessverticaldeletedelajax = ajaxCall(businessverticaldeletedelajax, url, postData, function (data) {
            var result = JSON.parse(data);

            if (result.is_error) {
                showResponse(data, '','msg_div');
                emLoader('hide');
            }
            else {
                emLoader('hide');
                showResponse(data,'grid_data', 'msg_div' );
                businessverticalList();
            }
        });
    }
}
