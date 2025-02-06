var postData = null;
$(document).ready(function () {
    if(typeof(sid) == "undefined") sid = "";
	if(typeof(type) == "undefined") type = "";
	if(typeof(id) == "undefined") id = "";
    softwaremainList(type,id);
    

    $(document).on("click", "#softwareadd", function () { softwareadd(); });
    $(document).on("click", "#softwareaddsubmit", function () { softwareaddsubmit(); });
    $(document).on("click", ".software_edit", function () { var software_id = $(this).attr('id'); softwareedit(software_id); });
    $(document).on("click", "#softwareeditsubmit", function () { softwareeditsubmit(); });
    $(document).on("click", ".software_del", function () { var software_id = $(this).attr('id'); softwaredelete(software_id); });
    $(document).on("click", "#btn_reset", function () { resetForm('addformsoftware'); });
});


function softwaremainList(type='', id=''){

   closeMsgAuto('msg_div');
    emLoader('show', trans('messages.msg_software_loading'));
    var url = SITE_URL + '/software/mainlist';

    if(type == '' || type == 'undefined')
    {
        var postData = $("#frmdevices").serialize();
      
    }
    
    else if(type == "software_manufacturer"){
        var postData = { 'datatype': 'json', 'advsoftware_manufacturer_id': id  };
        $("#div_emadvsearch").show();

    }
    else if(type == "software_type"){
        
        var postData = { 'datatype': 'json', 'advsoftware_type_id': id  };
        $("#div_emadvsearch").show();

    }
    
    //searchRecords('softwaremainList()');
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
                console.log($('#advsoftware_type_id'));
                $('#advsoftware_type_id').val(id);	
				$('#advsoftware_type_id').val(id).trigger('chosen:updated');
                //set software type id in advanced filter dropdown
                $('#advsoftware_manufacturer_id').val(id);
                $('#advsoftware_manufacturer_id').val(id).trigger('chosen:updated');//set software manufacturer id in advanced filter dropdown
                
                $('#advsoftware_category_id').val(id);
                $('#advsoftware_category_id').val(id).trigger('chosen:updated');
                
            emLoader('hide');
        });
    }
    //searchRecords('softwaremainList()');

}

function softwaremainListmanu(smid=''){
   //alert();
    closeMsgAuto('msg_div');
    emLoader('show', trans('messages.msg_software_loading'));
    var url = SITE_URL + '/software/mainlist';

    console.log("smid--"+smid);
    if(smid == '' || smid == 'undefined')
    {
        var postData = $("#frmdevices").serialize();
    }
    else
    {
    var postData = { 'datatype': 'json', 'advsoftware_manufacturer_id': smid  };

    }
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
            $('#advsoftware_manufacturer_id').val(smid);
            emLoader('hide');
        });
    }
}

function softwareadd() {
    closeMsgBox('msg_div');
    var url = SITE_URL + '/software/add';
    var notifyajax = ajaxCall(notifyajax, url, {}, function (data) {
        lightbox('show', data, trans('messages.msg_software_add'), 'large');
        emLoader('hide');
    });

}

function softwareaddsubmit() {

    clearMsg('msg_popup');
    if (cltimer) {
        clas = 'error';
        showAlert("msg_popup", clas,  trans('messages.msg_session_open'));
        return false;
    }
    emLoader('show', trans('label.lbl_software_type'));
    var url = SITE_URL + '/software/addsubmit';
    var postData = $("#addformsoftware").serialize();
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
            //softwareList();
            softwaremainList();
        }

    });
}
function softwareedit(software_id) {
    var id = software_id.split('_')[1];
    var postData = { 'datatype': 'json', 'id': id };
    console.log(postData);
    var url = SITE_URL + '/software/edit';

    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        lightbox('show', data, trans('messages.msg_software_edit'), 'large');
        emLoader('hide');

    });
}

function softwareeditsubmit() {
    clearMsg('msg_popup');
	clearMsg('msg_div');
    emLoader('show', trans('messages.msg_updating_software'));
    var url = SITE_URL + '/software/editsubmit';
    var postData = $("#addformsoftware").serialize();
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
            //softwareList();
            softwaremainList();

        }

    });

}

function softwaredelete(software_id) {
    if (confirm(trans('messages.msg_software_delete'))) {
        clearMsg('msg_popup');
        emLoader('show', trans('messages.msg_software_delete'));
        var id = software_id.split('_')[1];
        var postData = { 'datatype': 'json', 'software_id': id, 'status': 'd' };
        var url = SITE_URL + '/software/delete';
        var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '','msg_div');
                emLoader('hide');
            }
            else {
                emLoader('hide');
                showResponse(data,'grid_data', 'msg_div' );
                //softwareList();
                softwaremainList();
            }
        });
    }
}
