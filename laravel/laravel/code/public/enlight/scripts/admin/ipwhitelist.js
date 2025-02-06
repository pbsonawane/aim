var postData = null;
$(document).ready(function () {
   ipList();
   ipListWhitelisted();
   $(document).on("click",".whitelist_approve", function() { var id = $(this).attr('id');var ip = $(this).attr('ip');approveip(id,ip); });
   
   $(document).on("click",".delete_wip", function() { var ip_id = $(this).attr('id');var flag = $(this).attr('data-flag');deleteWhiteListIp(ip_id,flag);});
   
    $(document).on("click","#delete_whitelisted_ips", function() { var ip_id = $(this).attr('id');var flag = $(this).attr('data-flag');deleteWhiteListIp(ip_id,flag);});
	 
	 $(document).on("click",".delete_wsubnet", function() { var subnet_id = $(this).attr('id');var flag = $(this).attr('data-flag');deleteWhiteListIp(subnet_id,flag);});
	
	
   
   
});

function ipList()
{
	closeMsgAuto('msg_div');
	emLoader('show', 'Loading Pending requests of IP whitelisting');
	var url = SITE_URL+'/gettokenwhitelist';
    var postData = $("#frmiplists").serialize();
    var ipajax = ajaxCall(ipajax, url, postData, function (data) {
        showResponse(data, 'grid_data', 'msg_div');
        emLoader('hide');
    });
}

function addWhiteListIp()
{
	if (!confirm("Are you sure you want to add IP / CIDR ?"))
		return false;
	clearMsg('msg_div_addip');	
	emLoader('show', 'Adding IP/CIDR');
	
	var url = SITE_URL + '/adduserwhitelistedips';
	var add_ip = $("#add_allowed_ip").val()
    var postData = { 'datatype': 'json', 'add_ip': add_ip };
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) 
	{
        var result = JSON.parse(data);
		
        if (result.is_error) {
            showResponse(data, '', 'msg_div_addip');
            emLoader('hide');
        }
        else 
		{
            emLoader('hide');
            showResponse(data, '', 'msg_div_addip');
			ipListWhitelisted()
        }
    });
	
}
function approveip(id,ip)
{
	if (!confirm("Are you sure you want to approve IP ?"))
		return false;
	clearMsg('msg_div');	
	emLoader('show', 'Approving IP');
	var url = SITE_URL + '/approveuserwhitelistedips';
    var postData = { 'add_ip': ip, 'id': id };
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) 
	{
        var result = JSON.parse(data);
		
        if (result.is_error) {
            showResponse(data, '', 'msg_div');
            emLoader('hide');
        }
        else 
		{
            emLoader('hide');
            showResponse(data, 'grid_data', 'msg_div');
        }
    });
}

function ipListWhitelisted()
{
	closeMsgAuto('msg_div_whitelistedips');
	var url = SITE_URL+'/userwhilistedips';
    var postData = {};
    var ipajax = ajaxCall(ipajax, url, postData, function (data) {
        showResponse(data, 'grid_data_whitelistedips', 'msg_div_whitelistedips');
        emLoader('hide');
    });
}
function deleteWhiteListIp(ip_id,flag)
{		
	if(ip_id == '-1')
	{	
		if(flag == 'subnet')
			var checkips = checkBoxstr('check-del-whitelsited_subnet');
		else	
			var checkips = checkBoxstr('check-del-whitelsited_ip');
		var id = checkips;
		
		if(checkips == '')
		{
			alert("Please select IPs/Subnets to delete.");
			return false;
		}
		
	}
	else
	{
		var id  = ip_id.split('_')[1];
	}
	
	if (!confirm("Are you sure you want to delete IP / IP's?"))
		return false;
	clearMsg('msg_div_whitelistedips');	
	emLoader('show', 'Deleting IP/Subnet');
	
	var url = SITE_URL + '/deletewhitelistip';
	if(flag == 'subnet')
   	 	var postData = { 'delete_subnet': id, 'flag': flag };
	else
		var postData = { 'delete_ip': id, 'flag': flag };
		
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) 
	{
        var result = JSON.parse(data);
		
        if (result.is_error) {
            showResponse(data, '', 'msg_div_whitelistedips');
            emLoader('hide');
        }
        else 
		{
            emLoader('hide');
            showResponse(data, '', 'msg_div_whitelistedips');
			ipListWhitelisted();
        }
    });
	
}
