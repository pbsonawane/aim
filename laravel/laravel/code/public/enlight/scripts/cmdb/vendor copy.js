var postData = null;
$(document).ready(function () {
vendorList();
$(document).on("click", "#vendoradd", function () { vendoradd(); });
$(document).on("click", "#vendoraddsubmit", function () { vendoraddsubmit(); });
$(document).on("click", ".vendor_edit", function () { var vendor_id = $(this).attr('id'); vendoredit(vendor_id); });
$(document).on("click", "#vendoreditsubmit", function () { vendoreditsubmit(); });
$(document).on("click", ".vendor_del", function () { var vendor_id = $(this).attr('id'); vendordelete(vendor_id); });
$(document).on("click", "#btn_reset", function () { resetForm('addformvendor'); });

});
function vendorList(){
    closeMsgAuto('msg_div');
    emLoader('show', trans('messages.msg_vendor_loading'));
    var url = SITE_URL + '/vendor/list';
    //alert(url);
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

    function vendoradd() {        
        closeMsgBox('msg_div');
		emLoader('show', trans('messages.msg_session_open'));
        var url = SITE_URL + '/vendor/add';
        var notifyajax = ajaxCall(notifyajax, url, {}, function (data) {
            lightbox('show', data, trans('messages.msg_vendor_add'), 'large');
            emLoader('hide');
        });    
    }

    function vendoraddsubmit() {

        clearMsg('msg_popup');
        if (cltimer) {
            clas = 'error';
            showAlert("msg_popup", clas,  trans('messages.msg_session_open'));
            return false;
        }
        emLoader('show', 'Vendor');
        var url = SITE_URL + '/vendor/addsubmit';
        var postData = $("#addformvendor").serialize();
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
                vendorList();
            }
    
        });
    }

    function vendoredit(vendor_id) {
		emLoader('show', trans('messages.msg_updating_vendor'));
        var id = vendor_id.split('_')[1];
        var postData = { 'datatype': 'json', 'id': id };
        console.log(postData);
        var url = SITE_URL + '/vendor/edit';
       // alert(id);
        var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
            lightbox('show', data, trans('messages.msg_vendor_edit'), 'large');
            emLoader('hide');
           // alert(id);
    
        });
    }
    
    function vendoreditsubmit() {
        clearMsg('msg_popup');
        clearMsg('msg_div');
        emLoader('show', trans('messages.msg_updating_vendor'));
        var url = SITE_URL + '/vendor/editsubmit';
        var postData = $("#addformvendor").serialize();
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
                vendorList();
    
            }
    
        });
    
    }
    
    function vendordelete(vendor_id) {
        if (confirm(trans('messages.msg_vendor_delete'))) {
            clearMsg('msg_popup');
            emLoader('show', trans('messages.msg_deleting_vendor'));
            var id = vendor_id.split('_')[1];
            var postData = { 'datatype': 'json', 'vendor_id': id, 'status': 'd' };
            var url = SITE_URL + '/vendor/delete';
            var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
                var result = JSON.parse(data);
                if (result.is_error) {
                    showResponse(data, '','msg_div');
                    emLoader('hide');
                }
                else {
                    emLoader('hide');
                    showResponse(data,'grid_data', 'msg_div' );
                    vendorList();
                }
            });
        }
    }


