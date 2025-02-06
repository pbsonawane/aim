
var postData = null;
$(document).ready(function () {
    businessunitList();

    $(document).on("click", ".businessunitadd", function () { businessunitadd(); });
    $(document).on("click", "#businessunitaddsubmit", function () { businessunitaddsubmit(); });
    $(document).on("click", ".businessunit_edit", function () { var bu_id = $(this).attr('id'); businessunitedit(bu_id); });

    $(document).on("click", ".businessunit_del", function () { var bu_id = $(this).attr('id'); businessunitdelete(bu_id); });
    $(document).on("click", "#businessuniteditsubmit", function () { businessuniteditsubmit(); });
    $(document).on("click", "#businessunit_reset", function () { resetForm('addformbusinessunit'); });
});

function businessunitList() {
    //clearMsg('msg_popup');
    closeMsgAuto('msg_div');
    emLoader('show', 'Loading Business Units');
    var url = SITE_URL + '/businessunits/list';
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
function businessunitadd() {
    closeMsgBox('msg_div');
    var url = SITE_URL + '/businessunitadd';
    var notifyajax = ajaxCall(notifyajax, url, {}, function (data) {
        lightbox('show', data, 'Business Unit Add', 'large');
        emLoader('hide');
    });

}

function businessunitaddsubmit() {

    clearMsg('msg_popup');
    if (cltimer) {
        clas = 'error';
        showAlert("msg_popup", clas, "Session is already open.");
        return false;
    }
    //var description =  $("#description").val();
    //var businessunit_name =  $("#businessunit_name").val();
    emLoader('show', 'Business Unit');
    var url = SITE_URL + '/businessunitaddsubmit';
    var postData = $("#addformbusinessunit").serialize();
    console.log(postData);
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        var result = JSON.parse(data);
        console.log(result);
        //console.log(result.message.error);
        if (result.is_error) {
            showResponse(data, '',  'msg_popup');
            emLoader('hide');
        }
        else {
            emLoader('hide');
            lightbox('hide');
            // showResponse(data, 'msg_div');
            window.scrollTo(0, 0);
            showResponse(data, 'grid_data', 'msg_div');
            businessunitList();
        }

    });
}

function businessunitedit(bu_id) {

    var id = bu_id.split('_')[1];
    var postData = { 'datatype': 'json', 'id': id };
    console.log(postData);
    var url = SITE_URL + '/businessunitedit';

    var businessuniteditajax = ajaxCall(businessuniteditajax, url, postData, function (data) {
        lightbox('show', data, 'Business Unit Edit', 'medium');
        emLoader('hide');

    });
}

function businessuniteditsubmit() {
    clearMsg('msg_popup');
	clearMsg('msg_div');
    emLoader('show', 'Updating Business Unit ');
    var url = SITE_URL + '/businessuniteditsubmit';
    var postData = $("#addformbusinessunit").serialize();
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
            showResponse(data,'grid_data','msg_div' );
            businessunitList();

        }


    });
}

function businessunitdelete(bu_id) {
    if (confirm('Are you sure you want to delete Business Unit record')) {
        clearMsg('msg_popup');
        emLoader('show', 'Deleting Business Unit');
        var id = bu_id.split('_')[1];
        var postData = { 'datatype': 'json', 'bu_id': id, 'status': 'd' };
        var url = SITE_URL + '/businessunitdelete';
        var businessunitdeletedelajax = ajaxCall(businessunitdeletedelajax, url, postData, function (data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '','msg_div');
                emLoader('hide');
            }
            else {
                emLoader('hide');
                showResponse(data,'grid_data', 'msg_div' );
                businessunitList();
            }


        });
    }
}

