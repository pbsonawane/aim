var postData = null; 
$(document).ready(function()
{
	$(document).on("click","#dwopen", function() { dwLoad(); });
	
});

function dwLoad()
{
	var dwload;
	emLoader('show', 'Reports','dwload_report');
	var url =  SITE_URL+'/admin/manage/dwLoad/';
	ajaxCall(dwload,url,postData,function(data)
	{
		showResponse(data, 'dwload_report');
		emLoader('hide', '');
	});
}

function repDelete(id)
{
	
	if(id > 0)
	{
		if (confirm("Are you sure you want to delete?"))
		{
			var rptdelete; 
			var url =  SITE_URL+'/admin/manage/repdelete/';
			postData = {'id':id}
			ajaxCall(rptdelete,url,postData,function(data)
			{
				$( "#rt_"+id ).addClass( "hidden" );
			});
		}
	}
	
}