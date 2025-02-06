var postData = null;
$(document).ready(function () {
    licensetypeList();
    $(document).on("click", "#licensetypeadd", function () { licensetypeadd(); });
    $(document).on("click", "#licensetypeaddsubmit", function () { licensetypeaddsubmit(); });
    $(document).on("click", ".licensetype_edit", function () { var license_type_id = $(this).attr('id'); licensetypeedit(license_type_id); });
    $(document).on("click", "#licensetypeeditsubmit", function () { licensetypeeditsubmit(); });
    $(document).on("click", ".licensetype_del", function () { var license_type_id = $(this).attr('id'); licensetypedelete(license_type_id); });
    $(document).on("click", "#btn_reset", function () { resetForm('addformlicensetype'); });
   
    $(document).on("change", "#is_free", function () {  if(this.checked) {
        $('[name=installation_allow] option').filter(function() { 
        return ($(this).text() == 'Unlimited'); 
    }).prop('selected', true); 
        } });

    $(document).on("change", "#installation_allow", function () {  
        var type = $('#installation_allow').val();
        if(type == 'unlimited'){
            $('#is_free').prop('checked',true);
        }
        });
    
    
         
});

function licensetypeList(){
    closeMsgAuto('msg_div');
    emLoader('show', trans('messages.msg_licensetype_loading'));
    var url = SITE_URL + '/licensetype/list';
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

function licensetypeadd() {
    closeMsgBox('msg_div');
    emLoader('show', trans('messages.msg_updating_licensetype'));
    var url = SITE_URL + '/licensetype/add';
    var notifyajax = ajaxCall(notifyajax, url, {}, function (data) {
        lightbox('show', data, trans('messages.msg_licensetype_add'), 'large');
        emLoader('hide');
    });

}

function licensetypeaddsubmit() {

    clearMsg('msg_popup');
    if (cltimer) {
        clas = 'error';
        showAlert("msg_popup", clas,  trans('messages.msg_session_open'));
        return false;
    }
    emLoader('show', trans('label.lbl_license_type'));
    var url = SITE_URL + '/licensetype/addsubmit';
    var postData = $("#addformlicensetype").serialize();
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
            licensetypeList();
        }

    });
}
function licensetypeedit(license_type_id) {
    
    emLoader('show', trans('messages.msg_updating_licensetype'));
    var id = license_type_id.split('_')[1];
    var postData = { 'datatype': 'json', 'id': id };
    console.log(postData);
    var url = SITE_URL + '/licensetype/edit';

    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        lightbox('show', data, trans('messages.msg_licensetype_edit'), 'large');

		var allow = $("#allow").val();
		if(allow != '' && allow != undefined) allow = allow.toLowerCase();
		document.getElementById('installation_allow').value=allow;
		emLoader('hide');
	
    });
}

function licensetypeeditsubmit() {
    clearMsg('msg_popup');
	clearMsg('msg_div');
    emLoader('show', trans('messages.msg_updating_licensetype'));
    var url = SITE_URL + '/licensetype/editsubmit';
    var postData = $("#addformlicensetype").serialize();
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
            licensetypeList();

        }

    });

}

function licensetypedelete(license_type_id) {
    if (confirm(trans('messages.msg_licensetype_delete'))) {
        clearMsg('msg_popup');
        emLoader('show', trans('messages.msg_licensetype_delete'));
        var id = license_type_id.split('_')[1];
        var postData = { 'datatype': 'json', 'license_type_id': id, 'status': 'd' };
        var url = SITE_URL + '/licensetype/delete';
        var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '','msg_div');
                emLoader('hide');
            }
            else {
                emLoader('hide');
                showResponse(data,'grid_data', 'msg_div' );
                licensetypeList();
            }
        });
    }
}