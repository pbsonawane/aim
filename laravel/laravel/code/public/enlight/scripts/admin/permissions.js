var postData = null;
var categories;
var countries = ["Afghanistan", "Albania", "Algeria", "Andorra", "Angola", "Antigua & Deps", "Argentina", "Armenia", "Australia", "Austria",
    "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bhutan", "Bolivia", "Bosnia Herzegovina",
    "Botswana", "Brazil", "Brunei", "Bulgaria", "Burkina", "Burma", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Central African Rep",
    "Chad", "Chile", "People's Republic of China", "Republic of China", "Colombia", "Comoros", "Democratic Republic of the Congo", "Republic of the Congo",
    "Costa Rica", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Danzig", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor",
    "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Fiji", "Finland", "France", "Gabon", "Gaza Strip",
    "The Gambia", "Georgia", "Germany", "Ghana", "Greece", "Grenada", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Honduras", "Hungary",
    "Iceland", "India", "Indonesia", "Iran", "Iraq", "Republic of Ireland", "Israel", "Italy", "Ivory Coast", "Jamaica", "Japan", "Jonathanland",
    "Jordan", "Kazakhstan", "Kenya", "Kiribati", "North Korea", "South Korea", "Kosovo", "Kuwait", "Kyrgyzstan", "Laos", "Latvia", "Lebanon", "Lesotho",
    "Liberia", "Libya", "Liechtenstein", "Lithuania", "Luxembourg", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta",
    "Marshall Islands", "Mauritania", "Namibia", "Netherlands", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Norway", "Oman", "Pakistan", "Palau",
    "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Poland", "Portugal", "Qatar", "Romania", "Russian Federation", "Rwanda", "Samoa",
    "San Marino", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia",
    "South Africa", "Spain", "Sri Lanka", "Sudan", "Swaziland", "Sweden", "Switzerland", "Syria,", "Tajikistan", "Tanzania", "Thailand", "Togo", "Tonga", "Trinidad & Tobago", "Tunisia", "Turkey", "Turkmenistan", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States of America", "Uruguay", "Uzbekistan", "Vanuatu", "Vatican City", "Venezuela", "Vietnam", "Yemen", "Zambia", "Zimbabwe"];
$(document).ready(function () {
    permissionList();
    $(document).on("click", ".permissionadd", function () {
        permissionAdd();
    });
    $(document).on("click", "#permissionsave", function () { permissionSave(); });
    
   // $(document).on("click", "#addCategory", function () { addCategory(); });

    $(document).on("click", "#perm_category_name_chosen", function () { 
        alert("old");
        $(".perm_category_name_div").removeClass("col-md-2");
        $(".perm_category_name_div").addClass("col-md-5");

        $(".perm_new_category_name_div").removeClass("col-md-5");
        $(".perm_new_category_name_div").addClass("col-md-2");
    });
    $(document).on("click", "#perm_new_category_name", function () { 
        alert("new");
        $(".perm_new_category_name_div").removeClass("col-md-2");
        $(".perm_new_category_name_div").addClass("col-md-5");

        $(".perm_category_name_div").removeClass("col-md-5");
        $(".perm_category_name_div").addClass("col-md-2");
    });

    $(document).on("click", "#permissionreset", function () { $("#addformpermission").trigger("reset") });
    $(document).on("click", ".permissionedit", function () {
        var rid = $(this).attr('id'); permissionEdit(rid);
    });
    $(document).on("click", "#permissionupdate", function () { permissionUpdate(); });
    $(document).on("click", ".permissiondelete", function () {
        var rid = $(this).attr('id'); permissionDelete(rid);
    });
    $(document).on("keyup", "#permissionupdate", function () { permissionUpdate(); });
});
function permissionList() {
    closeMsgAuto('msg_div');
    emLoader('show', 'Loading Permissions');
    var url = SITE_URL + '/permissions/list';
    var postData = $("#frmpermissions").serialize();
    var permissionajax = ajaxCall(permissionajax, url, postData, function (data) {
        showResponse(data, 'grid_data', 'msg_div');
        emLoader('hide');
    });
}
function permissionAdd() {
    var url = SITE_URL + '/permissionadd';
    var postData = {};
    var notifyajax = ajaxCall(notifyajax, url, postData, function (data) {
        lightbox('show', data, 'Permission Add', 'middle');
        emLoader('hide');
       // permissionCategories();
       initsingleselect();
    });
}
/*function addCategory()
{
    $(".perm_new_category_name").show();
}*/
function permissionSave() {
    clearMsg('msg_popup');
    var url = SITE_URL + '/permissionsave';
    var postData = $("#addformpermission").serialize();
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
            permissionList();
        }
    });
}
function permissionEdit(rid) {
    emLoader('show', 'Updating Permissions');
    var id = rid.split('_')[1];
    var postData = { 'datatype': 'json', 'permissionid': id };
    var url = SITE_URL + '/permissionedit';
    var permissionditajax = ajaxCall(permissionditajax, url, postData, function (data) {
        lightbox('show', data, 'Permission Edit', 'middle');
        emLoader('hide');
        //permissionCategories();
        initsingleselect();
    });
}
function permissionUpdate() {
    clearMsg('msg_popup');
    var url = SITE_URL + '/permissionupdate';
    var postData = $("#addformpermission").serialize();
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
            permissionList();
        }
    });
}
function permissionDelete(rid) {
    if (confirm("Are you sure you want to delete?")) {
        emLoader('show', 'Deleting Permission');
        var id = rid.split('_')[1];
        var postData = { 'datatype': 'json', 'permissionid': id };
        var url = SITE_URL + '/permissiondelete';
        var permissiondeleteajax = ajaxCall(permissiondeleteajax, url, postData, function (data) {
            showResponse(data, 'grid_data', 'msg_div');
            permissionList();
            emLoader('hide');
        });
    }
}
/*
function permissionCategories() {
    var postData = {};
    var url = SITE_URL + '/permissioncategories';
    var permissioncateajax = ajaxCall(permissioncateajax, url, postData, function (result) {
        if (result != null) {
            var categories = jQuery.parseJSON(result);
            $("#perm_category_name").shieldAutoComplete({
                dataSource: {
                    data: categories.content
                },
                minLength: 0
            });
        }
    });
}*/
