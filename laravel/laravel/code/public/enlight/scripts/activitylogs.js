	var logsajax;
	$(document).ready(function(){
		logs();
	});	
	function logs()
	{
		emLoader('show', 'Loading Logs');
		var url = SITE_URL+'/boots/logsdata/';
		var postData = $("#frmlog").serialize();
		var exporttype = $("#frmlog input[name=exporttype]").val();
		if (exporttype == 'pdf' || exporttype == 'csv' || exporttype == 'print')
		{
			var obj_form = document.frmlog;
			var mywindow = submitForm(url,obj_form);	
			$("#frmlog input[name=exporttype]").val('');
			$("#frmlog input[name=page]").val('');
			emLoader('hide');
		}
		else
		{
			emgridTopDisable('#frmlog');
			logsajax = ajaxCall(logsajax,url,postData,function(data){$("#logs_data").html(data);emgridTopEnable('#frmlog');emLoader('hide');});
		}
	}