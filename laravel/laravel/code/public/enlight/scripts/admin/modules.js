var postData = null; 
$(document).ready(function()
{
	moduleList();
	$(document).on("click",".moduleadd", function() { moduleadd();});
	$(document).on("click","#moduleaddsubmit", function() { moduleaddsubmit();});
	$(document).on("click","#module_reset", function() { resetForm('addformmodule');});	
	$(document).on("click",".module_edit", function() { var module_id = $(this).attr('id');moduleedit(module_id); });	 
	$(document).on("click",".module_del", function() { var module_id = $(this).attr('id');moduledelete(module_id); });	
	$(document).on("click","#moduleeditsubmit", function() { moduleeditsubmit();});
});

function closeMsgAuto(div_id)
{
	setTimeout(function() { $("#"+div_id).fadeIn('slow').empty(); }, 10000);	
}
function moduleList()
{
	//closeMsgBox('msg_div');
	closeMsgAuto('msg_div');
	emLoader('show', 'Loading Modules');
	var url = SITE_URL+'/modules/list';
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

function moduleadd()
{
   	clearMsg('msg_div');
	var url = SITE_URL+'/moduleadd';
	var notifyajax = ajaxCall(notifyajax, url, {}, function(data)
	{
		lightbox('show', data, 'Module Add', 'medium');
		emLoader('hide');
    }); 
}
function moduleaddsubmit()
{
  	clearMsg('msg_popup');
	
	emLoader('show', 'Adding module ');
	var url = SITE_URL+'/modulesave';	
    var postData = $("#addformmodule").serialize();
	var rdpconnectsajax = ajaxCall(rdpconnectsajax,url,postData,function(data)
	{	
 		var result = JSON.parse(data);
        console.log(result);
        if(result.is_error)
        {
             showResponse(data,'', 'msg_popup');
            emLoader('hide');
        }
        else
		{	
           emLoader('hide');
           lightbox('hide');
          showResponse(data, 'grid_data',  'msg_div' );
		   moduleList();
        }
		
		
	});
}
function moduleedit(module_id)
{
	//emLoader('show', 'Updating Business Units');
	var id  = module_id.split('_')[1];
	var postData ={'datatype' : 'json', 'id' : id};
	var url = SITE_URL+'/moduleedit';
	
	var moduleeditajax = ajaxCall(moduleeditajax,url,postData,function(data)
	{	
		lightbox('show', data, 'Module Edit', 'medium');
		emLoader('hide');
		
	});
}
function moduleeditsubmit()
{
    clearMsg('msg_popup');
	
	emLoader('show', 'Updating module ');
	var url = SITE_URL+'/moduleupdate';	
    var postData = $("#addformmodule").serialize();
	var rdpconnectsajax = ajaxCall(rdpconnectsajax,url,postData,function(data)
	{	
 		var result = JSON.parse(data);
        console.log(result);
        if(result.is_error)
        {
           showResponse(data,'', 'msg_popup');
           emLoader('hide');
        }
        else
		{
           emLoader('hide');
           lightbox('hide');
          showResponse(data, 'grid_data',  'msg_div' );
		   moduleList();
        }

		
	});
}
function moduledelete(module_id)
{
	if(!confirm("Are you sure you want to delete this module ?"))
		return false;
	emLoader('show', 'Deleting module');
	var id  = module_id.split('_')[1];
	var postData ={'datatype' : 'json', 'module_id' : id,'action' : 'delete','status' : 'd'};
	var url = SITE_URL+'/moduledelete';
	var moduledelajax = ajaxCall(moduledelajax,url,postData,function(data)
	{	
		var result = JSON.parse(data);
		
        if(result.is_error)
        {	
           	showResponse(data, '',  'msg_div' );
            emLoader('hide');
        }
        else
		{
           emLoader('hide');
          showResponse(data, 'grid_data',  'msg_div' );
		   moduleList();
        }
	});
}