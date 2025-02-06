var postData = null;
$(document).ready(function () {
    costcenterList();
    $(document).on("click", "#costcenteradd", function () { costcenteradd(); });
    $(document).on("click", "#costcenteraddsubmit", function () { costcenteraddsubmit(); });
    $(document).on("click", ".costcenter_edit", function () { var cc_id = $(this).attr('id'); costcenteredit(cc_id); });
    $(document).on("click", "#costcentereditsubmit", function () { costcentereditsubmit(); });
    $(document).on("click", ".costcenter_del", function () { var cc_id = $(this).attr('id'); costcenterdelete(cc_id); });
	$(document).on("click", "#reset", function () {  resetForm('addformcc'); });
});
function costcenterList(){
    closeMsgAuto('msg_div');
    emLoader('show', trans('messages.msg_costcenter_loading'));
    var url = SITE_URL + '/costcenter/list';
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
    function costcenteradd() {
        closeMsgBox('msg_div');
        var url = SITE_URL + '/costcenter/add';
        var notifyajax = ajaxCall(notifyajax, url, {}, function (data) {
            lightbox('show', data, trans('label.lbl_cc_add'), 'large');
            emLoader('hide');
            initsingleselect();
        });
    
    }
    function costcenteraddsubmit() {

        clearMsg('msg_popup');
        if (cltimer) {
            clas = 'error';
            showAlert("msg_popup", clas,  trans('messages.msg_session_open'));
            return false;
        }
        emLoader('show', 'Costcenter');
        var url = SITE_URL + '/costcenter/addsubmit';
        var postData = $("#addformcc").serialize();
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
                costcenterList();
            }
    
        });
    }
    function costcenteredit(cc_id) {
        var id = cc_id.split('_')[1];
        var postData = { 'datatype': 'json', 'id': id };
        console.log(postData);
        var url = SITE_URL + '/costcenter/edit';
        //alert(id);
        var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
            lightbox('show', data, trans('label.lbl_cc_edit'), 'large');
            initsingleselect();
            emLoader('hide');
           // alert(id);
    
        });
    }
    
    function costcentereditsubmit() {
        clearMsg('msg_popup');
        clearMsg('msg_div');
        emLoader('show', trans('messages.msg_updating_costcenter'));
        var url = SITE_URL + '/costcenter/editsubmit';
        var postData = $("#addformcc").serialize();
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
                costcenterList();
    
            }
    
        });
    
    }
    
    function costcenterdelete(cc_id) {
        if (confirm(trans('messages.msg_costcenter_delete'))) {
            clearMsg('msg_popup');
            emLoader('show', trans('messages.msg_deleting_costcenter'));
            var id = cc_id.split('_')[1];
            var postData = { 'datatype': 'json', 'cc_id': id, 'status': 'd' };
            var url = SITE_URL + '/costcenter/delete';
            var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
                var result = JSON.parse(data);
                if (result.is_error) {
                    showResponse(data, '','msg_div');
                    emLoader('hide');
                }
                else {
                    emLoader('hide');
                    showResponse(data,'grid_data', 'msg_div' );
                    costcenterList();
                }
            });
        }
    }

