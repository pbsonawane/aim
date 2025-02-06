$(document).ready(function() 
{
    settingtemplateList();
    $(document).on("click","#addtemp", function() { settingstemplateadd();});	
    $(document).on("click","#settingstemplatesubmit", function() { settingstemplatesubmit();});	
    $(document).on("click",".settingtemplate_edit", function() { 
        var id = $(this).data('id')
        settingstemplateedit(id);
    });	
    $(document).on("click",".settingtemplate_copy", function() { 
        var id = $(this).data('id')
        settingstemplatcopy(id);
    });	  
    $(document).on("click",".settingtemplate_delete", function() { 
        var id = $(this).data('id')
        settingstemplatedelete(id);
    });  
    $(document).on("keyup", "#template_title", function(){
        var template_title = $("#template_title").val();
        keyPressTitle(template_title);
    });
    $(document).on("click","#settingtemplateaddsubmit_reset", function() { resettemplateForm();});
}); 
/* return separate Reset Function As Form Builder Can't use <Form>*/
function resettemplateForm()
{
    $("#settingstemplateform").find("input[type=text], input[type=password], input[type=number], input[type=checkbox], textarea, select").each(function () { $(this).val(''); });
}
function keyPressTitle(template_title) {
    var title = template_title.toLowerCase();  
    //this.templateName = title.replace(/ /g, '_');   
    /*this.templateName = title.replace(/ /g, ''); //Replace space  
    this.templateName = title.replace(/\//g, '');// Replace forword slash
    this.templateName = title.replace(/\\/g, '');// Replace Backslash */
    
    $("#template_name").val(title.replace(/ /g, '').replace(/\//g, '').replace(/\\/g, ''));
 }
function settingstemplateadd()
{
   	closeMsgBox('msg_div');
	emLoader('show', 'Setting Template Add');
	var url = SITE_URL+'/settingstemplate/add';
//	var postData ={'objectid' : objectid};
	var notifyajax = ajaxCall(notifyajax, url, {}, function(data)
	{
        //alert(data);
		lightbox('show', data, trans('title.add_setting_template'), 'full');
		emLoader('hide');
    });

}
function settingstemplatesubmit()
{
    clearMsg('msg_popup');
	if(cltimer)
	{
		clas = 'error';
		showAlert("msg_popup",clas,trans('messages.msg_session_open'));
		return false;    
    }
    var action = $("input[name='action']").val();
    var form_templ_id = $("input[name='form_templ_id']").val();
    var description =  $("#description").val();
    var type = $("select[name='type']").val();
    var template_name =  $("input[name='template_name']").val();
    var template_title =  $("input[name='template_title']").val();
    var default_template =  $("select[name='default_template']").val();
    var details =  $("#details").val();

    var postData = {form_templ_id : form_templ_id,
                    description : description, 
                    type : type,
                    template_name : template_name,
                    template_title : template_title,
                    default_template : default_template,
                    details:details};
    //console.log(postData);
    emLoader('show', 'Settings Template');
    if(action == "add")
    {
        var url = SITE_URL+'/settingstemplate/submit';	
    }
    else if(action == 'edit')
    {
        var url = SITE_URL+'/settingstemplate/update';	
    }
    //var postData = $("#settingstemplateaddform").serialize();
    //console.log(postData);
	var rdpconnectsajax = ajaxCall(rdpconnectsajax,url,postData,function(data)
	{	
        var result = JSON.parse(data);
       // console.log(result);
        //console.log(result.message.error);
        if(result.is_error)
        {
            showResponse(data,'', 'msg_popup');
            emLoader('hide');
            window.scrollTo(0, 0);
        }
        else{            
            emLoader('hide');
            lightbox('hide');
            settingtemplateList();
            showResponse(data, 'grid_data', 'msg_div' );
            window.scrollTo(0, 0);
           
        }
    });    
}
function settingtemplateList()
{
    
    closeMsgAuto('msg_div');
	emLoader('show', 'Loading Setting Templates');
	var url = SITE_URL+'/settingstemplate/list';
	var postData = $("#frmdevices").serialize();
	var exporttype = $("#frmdevices input[name=exporttype]").val();
	if (exporttype == 'pdf' || exporttype == 'csv' || exporttype == 'print')
	{
		var obj_form = document.frmdevices;
		var mywindow = submitForm(url,obj_form,1,1);	
		$("#frmdevices input[name=exporttype]").val('');
		$("#frmdevices input[name=page]").val('');
		emLoader('hide');
	}
	else
	{
		var mongraphsajax = ajaxCall(mongraphsajax,url,postData,function(data)
		{
			showResponse(data, 'grid_data', 'msg_div' );
			emLoader('hide');
		});	
	}
}
function settingstemplateedit(id)
{
    closeMsgBox('msg_div');
	emLoader('show', 'Setting Template Edit');
	var url = SITE_URL+'/settingstemplate/edit';
    var postData ={'id' : id};
	var notifyajax = ajaxCall(notifyajax, url, postData, function(data)
	{
        //alert(data);
		lightbox('show', data, trans('title.edit_setting_template'), 'full');
		emLoader('hide');
    });
}
function settingstemplatedelete(id)
{
    if(confirm(trans('messages.msg_confirmdelete')))
    {
        closeMsgBox('msg_div');
        emLoader('show', 'Deleting Setting Template');
        var url = SITE_URL+'/settingstemplate/delete';
        var postData ={'form_templ_id' : id, 'status': 'd'};
        var notifyajax = ajaxCall(notifyajax, url, postData, function(data)
        {
            var result = JSON.parse(data);
		
            if(result.is_error)
            {	
                showResponse(data, '',  'msg_div');
                emLoader('hide');
            }
            else
            {
               emLoader('hide');
               showResponse(data,  'grid_data', 'msg_div' );
               settingtemplateList();
            }
        });

    }
}
function settingstemplatcopy(id)
{
    if(confirm(trans('messages.msg_confirmclone')))
    {
        closeMsgBox('msg_div');
        emLoader('show', 'Cloning Template');
        var url = SITE_URL+'/clone';
        var postData ={'form_templ_id' : id};
        var notifyajax = ajaxCall(notifyajax, url, postData, function(data)
        {
            var result = JSON.parse(data);
		
            if(result.is_error)
            {	
                showResponse(data, '',  'msg_div');
                emLoader('hide');
            }
            else
            {
               emLoader('hide');
               showResponse(data,  'grid_data', 'msg_div' );
               settingtemplateList();
            }
        });

    }
}