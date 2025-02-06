var postData = null;
$(document).ready(function()
{
	regionList();
	$(document).on("click",".regionadd", function() { regionadd();});
	$(document).on("click","#regionaddsubmit", function() { regionaddsubmit();});
	$(document).on("click","#region_reset", function() { resetForm('addformregion');});
	$(document).on("click",".region_edit", function() { var region_id = $(this).attr('id');regionedit(region_id); });
	$(document).on("click",".region_del", function() { var region_id = $(this).attr('id');regiondelete(region_id); });
	$(document).on("click","#regioneditsubmit", function() { regioneditsubmit();});
	
	$(document).on("click",".region_dc_assign", function() { var region_id = $(this).attr('id'); var region_name = $(this).attr('data-regionname') ;regionassign(region_id,region_name); });
	
	 $(document).on("click","#regionassigndc_submit", function() { saveRegionDc()});
	
});

function closeMsgAuto(div_id)
{
	setTimeout(function() { $("#"+div_id).fadeIn('slow').empty(); }, 10000);
}
function regionList()
{
	//closeMsgBox('msg_div');
	closeMsgAuto('msg_div');
	emLoader('show', 'Loading Regions');
	var url = SITE_URL+'/regions/list';
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

function regionadd()
{
   	closeMsgBox('msg_div');
	var url = SITE_URL+'/regionadd';
	var notifyajax = ajaxCall(notifyajax, url, {}, function(data)
	{
		lightbox('show', data, 'Region Add', 'medium');
		emLoader('hide');
    });
}
function regionaddsubmit()
{
   // closeMsgBox('msg_popup');
	clearMsg('msg_popup');
	emLoader('show', 'Adding Region ');
	var url = SITE_URL+'/regionsave';
    var postData = $("#addformregion").serialize();
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
           showResponse(data,'grid_data','msg_div' );
		   regionList();
        }


	});
}
function regionedit(region_id)
{
	//emLoader('show', 'Updating Business Units');
	var id  = region_id.split('_')[1];
	var postData ={'datatype' : 'json', 'id' : id};
	var url = SITE_URL+'/regionedit';

	var regioneditajax = ajaxCall(regioneditajax,url,postData,function(data)
	{
		lightbox('show', data, 'Region Edit', 'medium');
		emLoader('hide');

	});
}
function regioneditsubmit()
{
    clearMsg('msg_popup');
	clearMsg('msg_div');
	
	emLoader('show', 'Updating Region ');
	var url = SITE_URL+'/regionupdate';
    var postData = $("#addformregion").serialize();
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
           showResponse(data,'grid_data','msg_div' );
		   regionList();
        }


	});
}
function regiondelete(region_id)
{	
	if(!confirm("Are you sure you want to delete this region ?"))
		return false;
	clearMsg('msg_div');
	clearMsg('msg_popup');
	emLoader('show', 'Deleting Region');
	var id  = region_id.split('_')[1];
	var postData ={'datatype' : 'json', 'region_id' : id,'action' : 'delete','status' : 'd'};
	var url = SITE_URL+'/regiondelete';
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
           showResponse(data,'grid_data', 'msg_div' );
		   regionList();
        }
	});
}
function regionassign(region_id,region_name)
{
	var id  = region_id.split('_')[1];
	var postData ={'datatype' : 'json', 'region_id' : id,'region_name' : region_name};
	var url = SITE_URL+'/assigndc';

	var regionassignajax = ajaxCall(regionassignajax,url,postData,function(data)
	{
		lightbox('show', data, 'Assign Datacenter to Region', 'large');
		emLoader('hide');

	});
}
function saveRegionDc()
{	
	clearMsg('msg_popup');
	clearMsg('msg_div');
	var region_id = $("#region_id").val()
	var checkdcs = checkBoxstr('region_dc');

	var postData = {'dc_id' : checkdcs, 'region_id': region_id};
	//emLoader('show', 'Assigning DC to Region');
	var url = SITE_URL+'/assigndcregions';
	var saveajax = ajaxCall(saveajax,url,postData,function(data)
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
           showResponse(data,'grid_data','msg_div' );
		   regionList();
        }


	});
	
}
