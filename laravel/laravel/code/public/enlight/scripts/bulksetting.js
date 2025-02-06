
jQuery(document).ready(function() {

	"use strict";

	// Init Theme Core    
	Core.init();

	// Init Demo JS     
	Demo.init();

	// Demo only javascript. no real use
	var contentType = $('#content-type');
	var Content = $('#dock-content');

	contentType.on('click', '.holder-style', function(e) {
		e.preventDefault();

		var This = $(this);
		var activeContent = This.attr('href');

		// Content button active
		contentType.find('.holder-style').removeClass('holder-active');
		This.addClass('holder-active');

		Content.children('div').removeClass('active-content');
		$(activeContent).addClass('active-content');
	});

	$('#dock-push').on('click', function() {

		var findPush = Content.children('.active-content').find('.dock-item');

		// Admin Dock Plugin
		findPush.dockmodal({
			minimizedWidth: 220,
			height: 430,
			title: function() {
				// note this is a panel specific callback
				// will return undefined if nonexistant
				return this.data('title');
			},
			initialState: "minimized"
		});

	});


});




$(document).ready(function() 
{
	$(document).on("click","#mon_submit_button", function() {deviceSettingSave('mon');});
	$(document).on("click","#noti_submit_button", function() {deviceSettingSave('noti');});
	$(document).on("click","#mgt_submit_button", function() {deviceSettingSave('mgt');});
	$(document).on("click","#cred_submit_button", function() {deviceSettingSave('cred');});
	$(document).on("click","#mon_reset_button", function() { resetForm("mon_addform"); });
	$(document).on("click","#noti_reset_button", function() { resetForm("noti_addform"); });
	$(document).on("click","#mgt_reset_button", function() { resetForm("mgt_addform"); });
	$(document).on("click","#cred_reset_button", function() { resetForm("cred_addform"); });
	
	
	$(document).on("click","#device_sett", function() {setting_option()});
	
	$(document).on("click","#device_sett_optn", function() {deviceEdit(); });
	$(document).on("click","#mon_sett_optn", function() {setting('monitoring'); });
	$(document).on("click","#cred_sett_optn", function() {setting('credential'); });
	$(document).on("click","#notif_sett_optn", function() {setting('notification'); });
	$("#device_heading").addClass("hidden");
	
});

function setting_option()
{	
	$("#rightdata").show();	
	//document.getElementById("rightdata").style.display = "";
}
function right_data()
{
	$("#rightdata").hide('');		
}

function setting(option)
{	
	$("#device_sett").hide();
	$("#notif_sett").show();
	$("#device_heading").addClass("hidden");
	$("#emgridadvsearch").addClass("hidden");
	
	closeMsgBox('msg_div');
	$("#setting_data").html('');	
	//emLoader('show', 'Device Settings');
	var url = SITE_URL+'/monitor/device/setting/';
	var postData = {'setting_option':option};
	var monsajax = ajaxCall(monsajax, url, postData, function(data)
	{
		//lightbox('show', data, 'Device Settings', 'full');
		$("#setting_data").html(data);	
		//$( "#monitor_method" ).trigger( "change" );
		//emLoader('hide');
	});
}

function deviceSettingSave(sel)
{
	closeMsgBox(sel+'_msg_div');
	emLoader('show', 'Saving Settings');
	var url = SITE_URL+'/monitor/device/setting_save_bulk/'+sel;
	var postData = $("#"+sel+"_addform").serialize();
	var sel_obj_ids = document.getElementById('devices_sel[]');
	var objlen = sel_obj_ids.length;
	var myArray = new Array();
	var j=0;
	for(i=0;i<objlen;i++)
	{
		myArray[j] = sel_obj_ids[i].value;
		j++;
	} 
	postData = postData + '&sel_devices='+myArray;
	var monsajax = ajaxCall(monsajax, url, postData, function(data)
	{	
		 // alert(data);
		  showResponse(data, sel+'_msg_div');
		  emLoader('hide');
	});	
}

function deviceEdit()
{	
	$("#notif_sett").hide();
	//$("#device_heading").show();
	$("#device_heading").removeClass("hidden");
	$("#emgridadvsearch").addClass("hidden");
	
	closeMsgBox('msg_div');
	var url = SITE_URL+'/monitor/device/edit/';
	var postData = {'objectid':''};
	var editajax = ajaxCall(editajax, url, postData, function(data)
	{
		$("#setting_data").html(data);	
		$(document).on("click","#btnSaveDevice", function() {deviceEditSave();});
	});
}
function deviceEditSave()
{
	closeMsgBox('msg_div');
	emLoader('show', 'Updating');
	var url = SITE_URL+'/monitor/device/editsave_bulk/';
	var postData = $("#frmEdit").serialize();
	var sel_obj_ids = document.getElementById('devices_sel[]');
	var objlen = sel_obj_ids.length;
	var myArray = new Array();
	var j=0;
	for(i=0;i<objlen;i++)
	{
		myArray[j] = sel_obj_ids[i].value;
		j++;
	} 
	postData = postData + '&sel_devices='+myArray;
	
	var editajax = ajaxCall(editajax, url, postData, function(data)
	{	
		emLoader('hide');	
		showResponse(data, 'msg_div');
	});
}
function getdevicedetails()
{	
	$("#emgridadvsearch").addClass("hidden");
	var url = SITE_URL+'/monitor/monitor/bulksettingdevices/';
	var postData = $("#frmlog").serialize();
	document.getElementById('deviceoption_data').innerHTML = '';
	var objajax = ajaxCall(objajax, url, postData, function(data)
	{	
		$("#deviceoption_data").html(data);	
		//document.getElementById('deviceoption_data').innerHTML = data;
		//emLoader('hide');	
		//showResponse(data, 'msg_div');
	});
}