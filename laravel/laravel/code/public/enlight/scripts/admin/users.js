var postData = null;
$(document).ready(function () {
    userList();
   
    $(document).on("click", "#useradd", function () { userAdd(); });
    $(document).on("click", "#usersave", function () { userSave(); });
    $(document).on("click", "#addreset", function () { $("#addformuser").trigger("reset") });
    $(document).on("click", "#editreset", function () { var rid = $('#user_id').val(); userEdit(rid);});
    $(document).on("click", ".useredit", function () {
        var rid = $(this).attr('id'); userEdit(rid);
    });
    $(document).on("click", "#userupdate", function () { userUpdate(); });
    $(document).on("click", ".userdelete", function () {
        var rid = $(this).attr('id'); userDelete(rid);
    });
    $(document).on("click", "#addorg", function () {
        var value = $('#organization_name').val(); orgSave(value);
    });
    $(document).on("click", "#genpass", function () {userPassword();});
	$(document).on("click", "#displaysetting", function () { displaySetting(); });
    $(document).on("change", "#user_type", function () {  var type = $('#user_type').val();usertypeChange(type,''); });
	$(document).on("click","#usercolsett_submit", function() { displaySettingSave()});
	$(document).on("click", ".eduser", function () { var user_id = $(this).attr('data-userinfo');userEdit(user_id); });
    $(document).on("click", ".sus_user", function () { var user_id = $(this).attr('data-userinfo');suspendUser(user_id,'s'); });
    $(document).on("click", ".react_user", function () { var user_id = $(this).attr('data-userinfo');suspendUser(user_id,'y'); });
     $(document).on("click", ".del_user", function () { var user_id = $(this).attr('data-userinfo');suspendUser(user_id,'d'); });
    $(document).on("click", ".assignentities", function () { var user_id = $(this).attr('data-userinfo');assignEntities(user_id); });
    
	$(document).on("click", "#assignmodule", function () {  assignmodule(); });
	$(document).on("click", ".userbvtab", function () { userbvload(); });
	$(document).on("click","#userbv_submit", function() { userbvssubmit()});
	
	$(document).on("click", ".userregiontab", function () { userregionload(); });
	
	$(document).on("click", ".user_regions", function () { regionEntities(); });
	$(document).on("click", ".user_dcs", function () { dcPods(); });
	$(document).on("click","#userregion_submit", function() { userregionsave()});
	$(document).on("keyup","#oldpassword", function() { 
        checkvalidpassword();
        });
    $(document).on("click","#updatepassword", function() { updatenewpassword()});
    //$(document).on("click","#upload", function() { upload()});
   // document.getElementById('upload').onclick = function() {
     //   document.getElementById('profile_photo').click();
   // };
});

function userList() {
    closeMsgAuto('msg_div');
    emLoader('show', 'Loading Users');
    var url = SITE_URL + '/users/list';
    var postData = $("#frmusers").serialize();
    var userajax = ajaxCall(userajax, url, postData, function (data) {
        showResponse(data, 'grid_data', 'msg_div');
        emLoader('hide');
    });
}
function userAdd() {
    var url = SITE_URL + '/useradd';
    var postData = {};
    var notifyajax = ajaxCall(notifyajax, url, postData, function (data) {
        lightbox('show', data, 'User Add', 'maxlarge');
       // $('#roleid').multiselect({
      ///          enableFiltering: true,
        //});
        initsingleselect();
        initmultiselect();
        /*initmultiselect('roleid');
        initmultiselect('manager_id');
        initmultiselect('department_id');
        initmultiselect('designation_id');
        initmultiselect('organization_id');*/
        usertypeChange('','');
        $('#password').val('');
        $('#username').val('');
        emLoader('hide');


    });
}

function usertypeChange(type,selected)
{
    //alert(selected);
    if(type == 'staff')
    {
        $('.clientshow').hide();
        $('.staffshow').show();
    }    
    else if(type == 'client')
    {
        $('.staffshow').hide();
        $('.clientshow').show();
    }
    else
    {
        $('.staffshow').hide();
        $('.clientshow').hide();
    }
    updateroleoption(type,selected);

    
}

function orgSave(value)
{
    clearMsg('msg_popup');
    var url = SITE_URL + '/orgsave';
    var postData = {'organization_name':value};
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        var result = JSON.parse(data);
        if (result.is_error) {
            showResponse(data, '', 'msg_popup');
            emLoader('hide');
        }
        else {
            updateorgoption();
            emLoader('hide');
            showResponse(data, '', 'msg_popup');
        }
    });
}

function userPassword()
{
     clearMsg('msg_popup');
    var url = SITE_URL + '/userpassword';
    var postData = '';
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        var result = JSON.parse(data);
        if (result.is_error) {
            showResponse(data, '', 'msg_popup');
            emLoader('hide');
        }
        else {
            emLoader('hide');
            var genpass = result.content;
            alert('Genrated Password : ' + genpass);
              
            $('#password').val(genpass);
            $('#confirm_password').val(genpass);
           // lightbox('hide');
            //showResponse(data, '', 'msg_popup');
            //userList();
        }
    });
}

function userSave() {
    clearMsg('msg_popup');
    var url = SITE_URL + '/usersave';
    var postData = $("#addformuser").serialize();
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        var result = JSON.parse(data);
        if (result.is_error) {
            showResponse(data, '', 'msg_popup');
            emLoader('hide');
        }
        else {
            emLoader('hide');
            lightbox('hide');
            showResponse(data, 'grid_data', 'msg_div');
            userList();
        }
    });
}
function userEdit(rid) {
    emLoader('show', 'Updating Users');
    var id = rid;
    console.log(id);
    var postData = { 'datatype': 'json', 'userid': id };
    var url = SITE_URL + '/useredit';
    var userditajax = ajaxCall(userditajax, url, postData, function (data) {
        lightbox('show', data, 'User Edit', 'maxlarge');
       /* initmultiselect('roleid');
        initmultiselect('manager_id'); // initiat multi select option
        initmultiselect('department_id');
        initmultiselect('designation_id');
        initmultiselect('organization_id');*/
        initsingleselect();
        initmultiselect();
        

        var user_type = $('#user_type').val();
         var sel_roles = $('#sel_roles').val();
        usertypeChange(user_type,sel_roles);

        emLoader('hide');
    });
}
function updateroleoption(type, selected)
{
    var url = SITE_URL + '/getroleoptions';
    var postData = {'type': type, 'selected': selected}
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        console.log(data);
        //alert(data);
        if(data != '')
        {
            $('#roleid').html( data );
            initmultiselect();
            rebuildmultiselect('roleid');  // revuild multiselect     common js function   
        }
    });
}

function updateorgoption(selected)
{
    var url = SITE_URL + '/getorgoptions';
    var postData = {'selected': selected}
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        console.log(data);
        //alert(data);
        if(data != '')
        {
            $('#organization_id').html( data );
             //initmultiselect('organization_id'); 
            //rebuildmultiselect('organization_id'); 
            initsingleselect();
            rebuildmultiselect('organization_id'); 
        }
    });
}


function userUpdate() {
    clearMsg('msg_popup');
    var url = SITE_URL + '/userupdate';
    var postData = $("#addformuser").serialize();
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        console.log(data);
        var result = JSON.parse(data);
        if (result.is_error) {
            showResponse(data, '', 'msg_popup');
            emLoader('hide');
        }
        else {
            emLoader('hide');
            lightbox('hide');
            showResponse(data, 'grid_data', 'msg_div');
            userList();
        }
    });
}
function userDelete(rid) {
    if (confirm("Are you sure you want to delete?")) {
        emLoader('show', 'Deleting User');
        var id = rid.split('_')[1];
        var postData = { 'datatype': 'json', 'userid': id };
        var url = SITE_URL + '/userdelete';
        var userdeleteajax = ajaxCall(userdeleteajax, url, postData, function (data) {
            showResponse(data, 'grid_data', 'msg_div');
            userList();
            emLoader('hide');
        });
    }
}

function suspendUser(userid,status)
{
    if(status == 's')
    {
        var cnf = "Are you sure you want to suspend this user?";
        var el = "Suspending User";
    } 
    else if(status == 'y')
    {
         var cnf = "Are you sure you want to activate this user?";
          var el = "Activating User";
    }
     else if(status == 'd')
    {
         var cnf = "Are you sure you want to delete this user?";
          var el = "Deleting User";
    }
    if (confirm(cnf)) {
        emLoader('show', el);
        var postData = { 'datatype': 'json', 'userid': userid, 'status':status };
        var url = SITE_URL + '/suspenduser';
        var userdeleteajax = ajaxCall(userdeleteajax, url, postData, function (data) {
            showResponse(data, 'grid_data', 'msg_div');
            userList();
            emLoader('hide');
        });
    }
}
function displaySetting()
{
	var postData ={'type' : 'user'};
	var url = SITE_URL+'/userdisplaysetting';

	var objajax = ajaxCall(objajax,url,postData,function(data)
	{
		lightbox('show', data, 'User List Columns', 'medium');
		emLoader('hide');
	});
}

function displaySettingSave()
{	
	if(!confirm("Are you sure you want to continue ?"))
		return false;
		
	var type = $("#type").val()
	var selected_fields = checkBoxstr('user_col');
	
	var postData = {'selected_fields' : selected_fields, 'type': type};
	emLoader('show', 'Updating Column Settings');
	var url = SITE_URL+'/userdisplaysettingsave';
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
		   userList();
        }
	});
	
}
function assignEntities(user_id)
{	
	var user_info = $("#info_"+user_id).val();
	
	var postData ={'userinfo' : user_info};
	var url = SITE_URL+'/userassignentities';

	var objajax = ajaxCall(objajax,url,postData,function(data)
	{
		lightbox('show', data, 'Assign Entities', 'maxlarge');
		//emLoader('hide');
	});
}
function assignmodule()
{
    clearMsg('msg_popup_module');
    var url = SITE_URL + '/usermoduleupdate'; 
    //var postData = $("#addusermoduleform").serialize();
	var user_id = $("#user_id").val();
	var user_modules = checkBoxstr('user_modules');
	
	var postData = {'module_ids' : user_modules, 'user_id': user_id};
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        var result = JSON.parse(data);
        if (result.is_error) {
            showResponse(data, '', 'msg_popup_module');
            emLoader('hide');
        }
        else {
            emLoader('hide');
         
            showResponse(data, '', 'msg_popup_module');
        }
		
		 closeMsgAuto('msg_popup_module');
    });
	
}

function userbvload()
{
	var user_id = $("#user_id").val();
	var postData = {'user_id' : user_id};
	//emLoader('show', 'User Business Vertical');
	
	var url = SITE_URL+'/userbvs';
	var saveajax = ajaxCall(saveajax,url,postData,function(data)
	{
 		//emLoader('hide');
		$("#bvtab").html(data);
	});
	
}

function userbvssubmit()
{
	clearMsg('msg_popup_bv');
	
	var user_id = $("#userbv").val();
	var bv_ids = checkBoxstr('user_bvs');
	
	var postData = {'bv_ids' : bv_ids, 'user_id': user_id};
	
	emLoader('show', 'Updating user businnes verticals');
	var url = SITE_URL+'/userbvupdate';
	var saveajax = ajaxCall(saveajax,url,postData,function(data)
	{
 		var result = JSON.parse(data);
        //console.log(result);
        if(result.is_error)
        {
            showResponse(data,'', 'msg_popup_bv');
            emLoader('hide');
        }
        else
		{
           emLoader('hide');
           showResponse(data,'','msg_popup_bv' );
        }
	});
}

function userregionload()
{
	var user_id = $("#user_id").val();
	var postData = {'user_id' : user_id};
	var url = SITE_URL+'/userregions';
	var saveajax = ajaxCall(saveajax,url,postData,function(data)
	{
 		emLoader('hide');
		$("#regiontab").html(data);
	});
}

function regionEntities()
{
	//clearMsg('msg_popup_bv');
	var user_id = $("#userid").val();
	var user_regions = checkBoxstr('user_regions');
	var postData = {'region_ids' : user_regions, 'user_id': user_id};
	
	//emLoader('show', 'Updating user businnes verticals');
	var url = SITE_URL+'/regiondcspods';
	var saveajax = ajaxCall(saveajax,url,postData,function(data)
	{	
		var result = data.split("#|#");
		$("#loc_lists").html(result[0]);
		$("#dc_lists").html(result[1]);
		$("#pod_lists").html(result[2]);
	});
}
function dcPods()
{
	//clearMsg('msg_popup_bv');
	var user_id = $("#userid").val();
	var user_dcs = checkBoxstr('user_dcs');
	var postData = {'dc_ids' : user_dcs, 'user_id': user_id};
	
	//emLoader('show', 'Updating user businnes verticals');
	var url = SITE_URL+'/dcspods';
	var saveajax = ajaxCall(saveajax,url,postData,function(data)
	{	
		$("#pod_lists").html(data);
	});
}


function userregionsave()
{
	clearMsg('msg_popup_region');
	var user_id = $("#userid").val();
	var user_regions = checkBoxstr('user_regions');
	var user_locations = checkBoxstr('user_locations');
	var user_dcs = checkBoxstr('user_dcs');
	var user_pods = checkBoxstr('user_pods');
	var postData = {'user_id': user_id,'region_ids' : user_regions,'dc_ids' : user_dcs,'pod_ids' : user_pods,'location_ids' : user_locations};
	emLoader('show', 'Updating user regions');
	var url = SITE_URL+'/userregionupdate';
	var saveajax = ajaxCall(saveajax,url,postData,function(data)
	{
 		var result = JSON.parse(data);
        console.log(result);
        if(result.is_error)
        {
            showResponse(data,'', 'msg_popup_region');
            emLoader('hide');
        }
        else
		{
           emLoader('hide');
           showResponse(data,'','msg_popup_region' );
        }
	});
}
function checkvalidpassword(){
    clearMsg('msg_popup_password');
    var oldpassword = $("#oldpassword").val();
    var postData = {'oldpassword': oldpassword};
	var url = SITE_URL+'/checkvalidpassword';
	var saveajax = ajaxCall(saveajax,url,postData,function(data)
    {
        console.log(data);
        var result = JSON.parse(data);
        console.log(result);
        if(result.is_error)
        {    
            showResponse(data,'', 'msg_popup_password');
            emLoader('hide');
            $("#password").prop('disabled', true);
            $("#password_confirmation").prop('disabled', true);
        }
        else
		{
           emLoader('hide');
           showResponse(data,'grid_data','msg_popup_password' );

           $("#password").prop('disabled', false);
           $("#password_confirmation").prop('disabled', false);
        }
	});

}
function updatenewpassword(){
    var user_id = $("#user_id").val();
    var oldpassword = $("#oldpassword").val();
    var password = $("#password").val();
    var password_confirmation = $("#password_confirmation").val();
    var postData = {'user_id':user_id,'oldpassword': oldpassword,'password': password,'password_confirmation':password_confirmation};
	var url = SITE_URL+'/updatenewpassword';
	var saveajax = ajaxCall(saveajax,url,postData,function(data)
    {
        var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '','msg_div');
                emLoader('hide');
            }
            else {
                emLoader('hide');
                showResponse(data,'grid_data', 'msg_div' );
            }
	});
}

 function upload(){
    alert('photo');
   var profile_photos = $("#profile_photos").val();
    var postData = {'profile_photos': profile_photos};
    var url = SITE_URL+'/editprofilesubmit';
	var saveajax = ajaxCall(saveajax,url,postData,function(data)
    {
        var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '','msg_div');
                emLoader('hide');
            }
            else {
                emLoader('hide');
                showResponse(data,'grid_data', 'msg_div' );
            }
	});
	
}

