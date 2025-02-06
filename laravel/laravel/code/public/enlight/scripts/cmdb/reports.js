var postData = null;
$(document).ready(function () 
{
    reportsList();
    $(document).on("click", "#reportsadd", function () { reportsadd(); });
    $(document).on("click", "#reportsaddsubmit", function () { reportsaddsubmit(); });
    $(document).on("click", ".reports_edit", function () { var report_cat_id = $(this).attr('id'); reportsedit(report_cat_id); });
    $(document).on("click", "#reportseditsubmit", function () { reportseditsubmit(); });
    $(document).on("click", ".reports_del", function () { var report_cat_id = $(this).attr('id'); reportsdelete(report_cat_id); });
    
});

function reportsList()
{
    closeMsgAuto('msg_div');
    emLoader('show', trans('label.lbl_loading'));
    var url         = SITE_URL + '/reports/list';
    var postData    = $("#frmrepcat").serialize();
    var exporttype  = $("#frmrepcat input[name=exporttype]").val();
    if (exporttype == 'pdf' || exporttype == 'csv' || exporttype == 'print') 
    {
       var obj_form = document.frmrepcat;
       var mywindow = submitForm(url, obj_form, 1, 1);
       $("#frmrepcat input[name=exporttype]").val('');
       $("#frmrepcat input[name=page]").val('');
       emLoader('hide');
   }
   else 
   {
       var ajax_result = ajaxCall(ajax_result, url, postData, function (data) {
            showResponse(data, 'grid_data', 'msg_div');
            emLoader('hide');
        });    
    }
}

function reportsadd() 
{
    closeMsgBox('msg_div');
    var url = SITE_URL + '/reports/add';
    var notifyajax = ajaxCall(notifyajax, url, {}, function (data) 
    {
        lightbox('show', data, trans('label.lbl_add_report_category'), 'large');
        emLoader('hide');
    });

}

function reportsaddsubmit() 
{
    clearMsg('msg_popup');
    emLoader('show', trans('label.lbl_loading'));
    var url = SITE_URL + '/reports/addsubmit';
    var postData = $("#addformreports").serialize();
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) 
    {
        var result = JSON.parse(data);
        if (result.is_error) {
            showResponse(data, '',  'msg_popup');
            emLoader('hide');
        }
        else 
        {
            emLoader('hide');
            lightbox('hide');
            window.scrollTo(0, 0);
            showResponse(data, 'grid_data', 'msg_div');
            reportsList();
        }
    });
}
function reportsedit(report_cat_id) 
{
    var id       = report_cat_id.split('_')[1];
    var postData = { 'datatype': 'json', 'id': id };
    var url      = SITE_URL + '/reports/edit';
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) 
    {
        lightbox('show', data, trans('label.lbl_edit_report_category'), 'large');
        emLoader('hide');

    });
}

function reportseditsubmit() 
{
    clearMsg('msg_popup');
	clearMsg('msg_div');
    emLoader('show', trans('label.lbl_loading'));
    var url             = SITE_URL + '/reports/editsubmit';
    var postData        = $("#addformreports").serialize();
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) 
    {
        var result = JSON.parse(data);
        if (result.is_error) 
        {
            showResponse(data, '','msg_popup');
            emLoader('hide');
        }
        else 
        {
            emLoader('hide');
            lightbox('hide');
            showResponse(data,'grid_data','msg_div' );
            reportsList();
        }
    });
}
function reportsdelete(report_cat_id)
{
    if (confirm(trans('label.msg_confirm'))) 
    {
        clearMsg('msg_popup');
        emLoader('show', trans('label.lbl_loading'));
        var id              = report_cat_id.split('_')[1];
        var postData        = { 'datatype': 'json', 'report_cat_id': id, 'status': 'd' };
        var url             = SITE_URL + '/reports/delete';
        var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) 
        {
            var result = JSON.parse(data);
            if (result.is_error) 
            {
                showResponse(data, '','msg_div');
                emLoader('hide');
            }
            else 
            {
                emLoader('hide');
                showResponse(data,'grid_data', 'msg_div' );
                reportsList();
            }
        });
    }
}