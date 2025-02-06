var postData = null;
$(document).ready(function () {
    roleList();
    $(document).on("click", ".roleadd", function () { roleAdd(); });
    $(document).on("click", "#rolesave", function () { roleSave(); });
    $(document).on("click", "#rolereset", function () { $("#addformrole").trigger("reset") });
    $(document).on("click", ".roleedit", function () {
        var rid = $(this).attr('id'); roleEdit(rid);
    });
    $(document).on("click", "#roleupdate", function () { roleUpdate(); });
    $(document).on("click", ".roledelete", function () {
        var rid = $(this).attr('id'); roleDelete(rid);
    });
    $(document).on("click", ".rolepermissions", function () {
        var rid = $(this).attr('id'); rolePermissions(rid);
    });
    $(document).on("click", "#rolepermissionassign", function () {
        var rid = $("#role_id").val(); roleAssign(rid);
    });
    $(document).on('click', ".selectDeselectAll", function () {
        var id = $(this).attr('id');
        var module_name = id.split('_')[1];
        $('.fieldset_' + module_name + ' input:checkbox').not(this).prop('checked', this.checked);
    });
    $(document).on('click', ".selectAllCrud", function () {
        var id = $(this).attr('id');
        var permission_id = id.split('_')[0];
        $('.allCrud_' + permission_id + ' input:checkbox').not(this).prop('checked', this.checked);
    });
});
function roleList() {
    closeMsgAuto('msg_div');
    emLoader('show', 'Loading Roles');
    var url = SITE_URL + '/roles/list';
    var postData = $("#frmroles").serialize();
    var roleajax = ajaxCall(roleajax, url, postData, function (data) {
        showResponse(data, 'grid_data', 'msg_div');
        emLoader('hide');
    });
}
function roleAdd() {
    var url = SITE_URL + '/roleadd';
    var postData = {};
    var notifyajax = ajaxCall(notifyajax, url, postData, function (data) {
        lightbox('show', data, 'Role Add', 'medium');
        emLoader('hide');
    });
}
function roleSave() {
    clearMsg('msg_popup');
    var url = SITE_URL + '/rolesave';
    var postData = $("#addformrole").serialize();
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
            roleList();
        }
    });
}
function roleEdit(rid) {
    emLoader('show', 'Updating Roles');
    var id = rid.split('_')[1];
    console.log(id);
    var postData = { 'datatype': 'json', 'roleid': id };
    var url = SITE_URL + '/roleedit';
    var roleditajax = ajaxCall(roleditajax, url, postData, function (data) {
        lightbox('show', data, 'Role Edit', 'medium');
        emLoader('hide');
    });
}
function roleUpdate() {
    clearMsg('msg_popup');
    var url = SITE_URL + '/roleupdate';
    var postData = $("#addformrole").serialize();
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
            roleList();
        }
    });
}
function roleDelete(rid) {
    if (confirm("Are you sure you want to delete?")) {
        emLoader('show', 'Deleting Role');
        var id = rid.split('_')[1];
        var postData = { 'datatype': 'json', 'roleid': id };
        var url = SITE_URL + '/roledelete';
        var roledeleteajax = ajaxCall(roledeleteajax, url, postData, function (data) {
            showResponse(data, 'grid_data', 'msg_div');
            roleList();
            emLoader('hide');
        });
    }
}
function rolePermissions(rid) {
    emLoader('show', 'Role Permissions Listing');
    var id = rid.split('_')[1];
    var postData = { 'datatype': 'json', 'roleid': id };
    var url = SITE_URL + '/rolepermissions';
    var rolepermissionsajax = ajaxCall(rolepermissionsajax, url, postData, function (data) {
        lightbox('show', data, 'Role Permissions Listing', 'full');
        emLoader('hide');
    });
}
function roleAssign(rid) {
    emLoader('show', 'Assigning Role');
    // var postData = { 'datatype': 'json', 'roleid': rid };
    //alert(rid);
    var postDataArr = $("#assignRolePermForm").serializeArray();
    var permission_idsArr = [];
    var accessrightsArr = [];
    postDataArr.forEach(function (element) {
        //console.log(element);
        //console.log(element.name);
        var name = element.name;
        var crud_adv = name.split('_')[0];
        var permission_id = name.split('_')[1];
        var postition = permission_idsArr.indexOf(permission_id);
        var postitionAccess = accessrightsArr.findIndex(p => p.permission_id == permission_id);
        if (postition === -1) {
            permission_idsArr.push(permission_id);
            if (crud_adv == "crud") {
                var rights = name.split('_')[2];
                //console.log(rights);

                var elementObj = {};
                elementObj.permission_id = permission_id;
                if (rights == 'all') {
                    elementObj.access_rights = [];
                }
                else {
                    elementObj.access_rights = [rights];
                }
                accessrightsArr.push(elementObj);
                /*console.log("-------------------");
                console.log(accessrightsArr);
                console.log("-------------------");*/
            }
        }
        else {
            if (crud_adv == "crud") {
                var rights = name.split('_')[2];
                if (postitionAccess !== -1) {
                    //console.log(accessrightsArr[postitionAccess]);
                    accessrightsArr[postitionAccess].access_rights.push(rights);
                }
            }
        }
    });
    var postData = {
        permission_id: permission_idsArr.toString(), //comma separated
        accessrightsArr: accessrightsArr ? accessrightsArr : [],
        role_id: rid
    };
    var url = SITE_URL + '/roleassign';
    var roleassignajax = ajaxCall(roleassignajax, url, postData, function (data) {
        var result = JSON.parse(data);
        if (result.is_error) {
            showResponse(data, '', 'msg_popup');
            emLoader('hide');
        }
        else {
            emLoader('hide');
            lightbox('hide');
            roleList();
            showResponse(data, 'grid_data', 'msg_div');
        }
        window.scrollTo(0, 0);
    });
}
