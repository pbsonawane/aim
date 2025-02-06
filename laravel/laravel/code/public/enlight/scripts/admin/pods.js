var postData = null; 
$(document).ready(function()
{
	podList();
	$(document).on("click",".podadd", function() { podadd();});
	$(document).on("click","#podaddsubmit", function() { podaddsubmit();});
	$(document).on("click","#pod_reset", function() { resetForm('addformpod');});	
	$(document).on("click",".pod_edit", function() { var pod_id = $(this).attr('id');podedit(pod_id); });	 
	$(document).on("click",".pod_del", function() { var pod_id = $(this).attr('id');poddelete(pod_id); });	
	$(document).on("click","#podeditsubmit", function() { podeditsubmit();});
	$(document).on("change","#region_id", function() { var regionId = $("#region_id").val(); dcListbox(regionId, 'dc_id',''); });
});


function podList()
{
	//closeMsgBox('msg_div');
	closeMsgAuto('msg_div');
	emLoader('show', 'Loading PODs');
	var url = SITE_URL+'/pods/list';
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


function podadd()
{
   	closeMsgBox('msg_div');
	var url = SITE_URL+'/podadd';
	var notifyajax = ajaxCall(notifyajax, url, {}, function(data)
	{
		lightbox('show', data, 'POD Add', 'large');
		emLoader('hide');
    }); 
}
function podaddsubmit()
{
    //closeMsgBox('msg_popup');
	clearMsg('msg_popup');
	emLoader('show', 'Adding POD ');
	var url = SITE_URL+'/podsave';	
    var postData = $("#addformpod").serialize();
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
          // showResponse(data,  'msg_div' );
		   showResponse(data, 'grid_data', 'msg_div');
		   podList();
        }
		
		
	});
}
function podedit(pod_id)
{
	emLoader('show', 'Updating POD');
	var id  = pod_id.split('_')[1];
	var postData ={'datatype' : 'json', 'id' : id};
	var url = SITE_URL+'/podedit';
	
	var podeditajax = ajaxCall(podeditajax,url,postData,function(data)
	{	
		lightbox('show', data, 'POD Edit', 'large');
		emLoader('hide');
		
	});
}
function podeditsubmit()
{
    clearMsg('msg_div');
	clearMsg('msg_popup');
	emLoader('show', 'Updating pod ');
	var url = SITE_URL+'/podupdate';	
    var postData = $("#addformpod").serialize();
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
           showResponse(data, 'grid_data',  'msg_div' );
		   podList();
        }

		
	});
}
function poddelete(pod_id)
{	
	if(!confirm("Are you sure you want to delete this POD ?"))
		return false;
	clearMsg('msg_popup');
	clearMsg('msg_div');
	
	emLoader('show', 'Deleting POD');
	var id  = pod_id.split('_')[1];
	var postData ={'datatype' : 'json', 'pod_id' : id,'action' : 'delete','status' : 'd'};
	var url = SITE_URL+'/poddelete';
	var poddelajax = ajaxCall(poddelajax,url,postData,function(data)
	{	
		var result = JSON.parse(data);
        if(result.is_error)
        {	
            showResponse(data, '', 'msg_div');
            emLoader('hide');
        }
        else
		{
           emLoader('hide');
           showResponse(data, 'grid_data',  'msg_div' );
		   podList();
        }
	});
}

// Get DCs for selected POD
function dcListbox(regionid, putAt, dcid)
{	
	var dcajax;
	if (regionid != '')
	{
		var postData = {'datatype' : 'json', 'region_id' : regionid};
		var url =  SITE_URL+'/getregiondcs/';
		ajaxCall(dcajax,url,postData,function(data){createDclistbox(data, putAt,dcid)});
	}
}
function createDclistbox(data, putAt, dcid)
{
	if(dcid != '' )
		var dc_id = dcid;
	else
		var dc_id = '';
	putAt = putAt == '' || putAt == undefined ? 'sr_dc_id' : putAt;
	var result = $.parseJSON(data);
	$("#"+putAt).html('<option>-Datacenters-</option>');
	$.each(result, function (key, value) {  
		if(dc_id == key)
			var selected = "selected";
		else
			var selected = "";
		$("#"+putAt).append('<option value="' + value.dc_id + '" '+selected+'>' + value.dc_name +'</option>');
	}) ;
}