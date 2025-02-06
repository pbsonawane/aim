var postData = null; 
$(document).ready(function()
{
	locationList();
	$(document).on("click","#locadd", function() { locationAdd();});
	$(document).on("click","#locaddsubmit", function() { locationAddSubmit();});
	$(document).on("click",".module_edit", function() { var loc_id = $(this).attr('id');locationedit(loc_id); });	 
	$(document).on("click",".module_del", function() { var loc_id = $(this).attr('id');locationdelete(loc_id); });	
	$(document).on("click","#loceditsubmit", function() {locationeditsubmit();});
	$(document).on("click", "#addreset", function () { $("#frmloc").trigger("reset") });
    $(document).on("click", "#editreset", function () { var rid = $('#location_id').val(); locationedit('locid_'+rid);});
	
});
function locationList()
{
	closeMsgAuto('msg_div');
    emLoader('show', 'Loading Locations');
    var url = SITE_URL + '/locations/list';
    var postData = $("#frmlist").serialize();
    var dcajax = ajaxCall(dcajax, url, postData, function (data) {
        showResponse(data, 'grid_data', 'msg_div');
        emLoader('hide');
    });
}

function locationAdd()
{
	var url = SITE_URL+'/locationadd';
	var locaddajax = ajaxCall(locaddajax, url, {}, function(data)
	{
		lightbox('show', data, 'Location Add', 'medium');
		emLoader('hide');
    }); 
}
function locationAddSubmit()
{
    clearMsg('msg_popup');	
	emLoader('show', 'Adding Locations');
	var url = SITE_URL+'/locationaddsubmit';	
    var postData = $("#frmloc").serialize();
	var locsaveajax = ajaxCall(locsaveajax,url,postData,function(data)
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
           showResponse(data, 'grid_data', 'msg_div');
           locationList();
        }
	});
}

function locationedit(loc_id)
{
	closeMsgBox('msg_div');
	emLoader('show', 'Editing Locations');
	var id  = loc_id.split('_')[1];
	var postData ={'datatype' : 'json', 'id' : id};
	var url = SITE_URL+'/locationedit';
	
	var loceditajax = ajaxCall(loceditajax,url,postData,function(data)
	{	
		lightbox('show', data, 'Location Edit', 'medium');
		emLoader('hide');
		
	});
}
function locationeditsubmit()
{
   	clearMsg('msg_popup');	
	emLoader('show', 'Updating Locations ');
	var url = SITE_URL+'/locationeditsubmit';	
    var postData = $("#frmloc").serialize();
	var locupdateajax = ajaxCall(locupdateajax,url,postData,function(data)
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
           locationList();
        }
	});
}

function locationdelete(loc_id)
{
	if (confirm("Are you sure you want to delete?")) 
	{
		emLoader('show', 'Deleting Location');
		var id  = loc_id.split('_')[1];
		var postData ={'datatype' : 'json', 'loc_id' : id,'action' : 'delete','status' : 'd'};
		var url = SITE_URL+'/locationdelete';
		var locdeleteajax = ajaxCall(locdeleteajax,url,postData,function(data)
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
			   locationList();
	        }
		});
	}	
}
