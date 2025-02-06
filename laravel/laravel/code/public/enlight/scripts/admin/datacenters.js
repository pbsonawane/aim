var postData = null; 
$(document).ready(function()
{
	dcList();
	$(document).on("click","#dcadd", function() { dcAdd();});
	$(document).on("click","#dcaddsubmit", function() { dcAddSubmit();});
	$(document).on("click","#reset_button", function() { $(this).closest('form').find("input[type=text], select,textArea").val("");});	
	$(document).on("click",".module_edit", function() { var dc_id = $(this).attr('id');dcedit(dc_id); });	 
	$(document).on("click",".module_del", function() { var dc_id = $(this).attr('id');dcdelete(dc_id); });	
	$(document).on("click","#dceditsubmit", function() {dceditsubmit();});
	$(document).on("click", "#addreset", function () { $("#frmdc").trigger("reset") });
    $(document).on("click", "#editreset", function () { var rid = $('#dc_id').val(); dcedit('dc_'+rid);});
});

function dcList()
{
	closeMsgAuto('msg_div');
    emLoader('show', 'Loading Datacenters');
    var url = SITE_URL + '/dclist/list';
    var postData = $("#frmlist").serialize();
    var dcajax = ajaxCall(dcajax, url, postData, function (data) {
        showResponse(data, 'grid_data', 'msg_div');
        emLoader('hide');
    });
}
function dcAdd()
{
   	//closeMsgBox('msg_div');
	var url = SITE_URL+'/dcadd';
	var notifyajax = ajaxCall(notifyajax, url, {}, function(data)
	{
		lightbox('show', data, 'Datacenter Add', 'medium');
		emLoader('hide');
    }); 
}
function dcAddSubmit()
{
   	clearMsg('msg_div');	
   	clearMsg('msg_popup');	
	emLoader('show', 'Adding Datacenter');
	var url = SITE_URL+'/dcaddsubmit';	
    var postData = $("#frmdc").serialize();
	var rdpconnectsajax = ajaxCall(rdpconnectsajax,url,postData,function(data)
	{	
 		var result = JSON.parse(data);
        console.log(result);
        if(result.is_error)
        {
            showResponse(data, '','msg_popup');
            emLoader('hide');
        }
        else
		{
           emLoader('hide');
           lightbox('hide');
           showResponse(data, 'grid_data', 'msg_div' );
           dcList();
        }
	});
}

function dcedit(dc_id)
{
	clearMsg('msg_div');	
   	clearMsg('msg_popup');	
   	emLoader('show', 'Update Datacenter');
	var id  = dc_id.split('_')[1];
	var postData ={'datatype' : 'json', 'id' : id};
	var url = SITE_URL+'/dcedit';
	
	var regioneditajax = ajaxCall(regioneditajax,url,postData,function(data)
	{	
		lightbox('show', data, 'Datacenter Edit', 'medium');
		emLoader('hide');
		
	});
}
function dceditsubmit()
{
	clearMsg('msg_div');	
   	clearMsg('msg_popup');	
	emLoader('show', 'Updating Datacenter ');
	var url = SITE_URL+'/dceditsubmit';	
    var postData = $("#frmdc").serialize();
	var rdpconnectsajax = ajaxCall(rdpconnectsajax,url,postData,function(data)
	{	
 		var result = JSON.parse(data);
        console.log(result);
        if(result.is_error)
        {
            showResponse(data, '', 'msg_popup');
            emLoader('hide');
        }
        else
		{
           emLoader('hide');
           lightbox('hide');
           showResponse(data, 'grid_data', 'msg_div' );
           dcList();
        }
	});
}

function dcdelete(dc_id)
{
	clearMsg('msg_div');	
   	clearMsg('msg_popup');	
	if (confirm(trans('label.msg_confirm'))) 
	{
		emLoader('show', 'Deleting Datacenter');
		var id  = dc_id.split('_')[1];
		var postData ={'datatype' : 'json', 'dc_id' : id,'action' : 'delete','status' : 'd'};
		var url = SITE_URL+'/dcdelete';
		var regiondelajax = ajaxCall(regiondelajax,url,postData,function(data)
		{	
			var result = JSON.parse(data);
			
	        if(result.is_error)
	        {	
	            showResponse(data,'', 'msg_div');
	            emLoader('hide');
	        }
	        else
			{
	           emLoader('hide');
	           showResponse(data, 'grid_data', 'msg_div' );
			   dcList();
	        }
		});
	}
}
