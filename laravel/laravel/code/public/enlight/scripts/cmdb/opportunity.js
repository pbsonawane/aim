var postData = null;
$(document).ready(function() {
    opportunityList();

    $(document).on("click", "#opportunityList", function() {
        opportunityRunTime();
    });
});

function opportunityList() {
    closeMsgAuto('msg_div');
    emLoader('show', 'Loading');
    var url = SITE_URL + '/opportunity/list';
    var postData = $("#frmdevices").serialize();
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

function opportunityRunTime() 
{
    closeMsgBox('msg_div');
    emLoader('show', 'Loading');
    var url = SITE_URL + '/opportunity/runtime';
    var notifyajax = ajaxCall(notifyajax, url, {}, function (data) {
        console.log(notifyajax);
        emLoader('hide');
        opportunityList();
    });
}