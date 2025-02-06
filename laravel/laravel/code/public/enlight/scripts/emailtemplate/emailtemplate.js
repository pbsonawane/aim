var postData = null;
$(document).ready(function () {
    emailtemplatelist();
    $(document).on("click", "#emailtemplateaddnew", function () {  emailtemplateadd(); });
    $(document).on("click", "#emailtemplateaddsubmit", function () { emailtemplateaddsubmit(); });
    $(document).on("click", ".template_edit", function () { var template_id = $(this).attr('id'); emailtemplateedit(template_id); });
    $(document).on("click", "#emailtemplateeditsubmit", function () { emailtemplateeditsubmit(); });
    $(document).on("click", ".template_del", function () { var template_id = $(this).attr('id'); emailtemplatedelete(template_id); });
	$(document).on("click", "#emailtemplate_reset", function () { 
	for ( instance in CKEDITOR.instances ){

        CKEDITOR.instances[instance].setData('');

    }
	resetForm('addformemailtemplate'); });
});

/* Function on select of template category  if category other then show template category textbox*/

function CheckCategory(val){
     var element=document.getElementById('template_category1');
     if(val==''||val=='others'){
       element.style.display='block';
       document.getElementById("template_category1").value = "";
    } else {
       element.style.display='none';
       document.getElementById("template_category1").value = val;
    }
}

/* Ajax call for template list*/
function emailtemplatelist(){
    closeMsgAuto('msg_div');
   emLoader('show', trans('messages.msg_emailtemplate_loading'));
    var url = SITE_URL + '/emailtemplate/list';
    var postData = $("#frmdevices").serialize();
   // alert(postData);
    var exporttype = $("#frmdevices input[name=exporttype]").val();
    console.log(exporttype);
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
/* Ajax call to add view of template */
function emailtemplateadd() {
    closeMsgBox('msg_div');
    var url = SITE_URL + '/emailtemplate/add';
    var notifyajax = ajaxCall(notifyajax, url, {}, function (data) {
        lightbox('show', data, trans('messages.msg_emailtemplate_add'), 'full');
        emLoader('hide');
    });

}
/* Ajax call to submi the newly added data into database */
function emailtemplateaddsubmit() {
    clearMsg('msg_popup');
    if (cltimer) {
        clas = 'error';
        showAlert("msg_popup", clas,  trans('messages.msg_session_open'));
        return false;
    }
    emLoader('show', trans('label.lbl_email_template'));
    var url = SITE_URL + '/emailtemplate/addsubmit';

    for(instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }
    var postData = $("#addformemailtemplate").serialize();
   // var email_body = CKEDITOR.instances.editor1.getData()
    console.log(postData);
    //console.log(émail_body)
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
            emailtemplatelist();
        }

    });
}

/* function to add the email quote*/

function emailquoteadd(){
    clearMsg('msg_popup');
    if (cltimer) {
        clas = 'error';
        showAlert("msg_popup", clas,  trans('messages.msg_session_open'));
        return false;
    }
    emLoader('show', trans('label.lbl_email_quote'));
    var url = SITE_URL + '/emailquoteaddsubmit';

    for(instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }
    var postData = $("#add_email_quotes").serialize();
   // var email_body = CKEDITOR.instances.editor1.getData()

    console.log(postData);
    //console.log(émail_body)
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
       // alert(data);
        emLoader('hide');
        $("#quotes").val('');
        $("#select_quote").html(data);

    });
}


/* function to display the email quote list */
function emailquotelist(){
    closeMsgAuto('msg_div');
   emLoader('show', trans('messages.msg_emailtemplate_loading'));
    var url = SITE_URL + '/emailquote/list';
    var postData = $("#frmdevices").serialize();
   // alert(postData);
    var exporttype = $("#frmdevices input[name=exporttype]").val();
    console.log(exporttype);
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
/* function to edit the email template */
function emailtemplateedit(template_id) {
    var id = template_id.split('_')[1];
    var postData = { 'datatype': 'json', 'id': id };
    console.log(postData);
    var url = SITE_URL + '/emailtemplate/edit';

    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        lightbox('show', data, trans('messages.msg_emailtemplate_edit'), 'full');
        emLoader('hide');

    });
}
$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    var $radio = $('input[type=checkbox]',this);
    $.each($radio,function(){
        if(!o.hasOwnProperty(this.name)){
            o[this.name] = 'n';
        }
    });
    return o;
};

/* function to update the email templat data */
function emailtemplateeditsubmit() {
    clearMsg('msg_popup');
	clearMsg('msg_div');
    emLoader('show', trans('messages.msg_updating_emailtemplate'));
    var url = SITE_URL + '/emailtemplate/editsubmit';
	for(instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }
    var postData = $("#addformemailtemplate").serializeObject();
	
 console.log(postData);
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
            window.scrollTo(0, 0);
            showResponse(data,'grid_data','msg_div' );
            emailtemplatelist();

        }

    });

}
/* function  to delete the email template */
function emailtemplatedelete(template_id) {
    if (confirm(trans('messages.msg_emailtemplate_delete'))) {
        clearMsg('msg_popup');
        emLoader('show', trans('messages.msg_emailtemplate_add'));
        var id = template_id.split('_')[1];
        var postData = { 'datatype': 'json', 'template_id': id, 'status': 'd' };
        var url = SITE_URL + '/emailtemplate/delete';
        var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '','msg_div');
                emLoader('hide');
            }
            else {
                emLoader('hide');
                window.scrollTo(0, 0);
                showResponse(data,'grid_data', 'msg_div' );
                emailtemplatelist();
            }
        });
    }
}

/* Function to change the template status */
function changestatus(template_id){
    emLoader('show', trans('messages.msg_emailtemplate_add'));
    var id = template_id.split('_')[1];
    if ($("#"+template_id).is(':checked')) {
       var status = 'e';

    }else{
        var status = 'd';
    }

    var postData = { 'datatype': 'json', 'id': id ,'status': status};
    console.log(postData);
    var url = SITE_URL + '/emailtemplatechangestatus';

    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
       // lightbox('show', data, trans('messages.msg_emailtemplate_edit'), 'full');
        emLoader('hide');
        window.scrollTo(0, 0);
        showResponse(data,'grid_data', 'msg_div' );
        emailtemplatelist();

    });
}