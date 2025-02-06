// JavaScript Document
// JavaScript Document



$(document).ready(function () {
    //showNotificationCount();
    $(document).on("click",".dropdown-notifications", function(){            
        notificatioMessages();
    });
    $(document).on("click",".dropdown-rep-notifications", function(){            
        showNotificatioMessages();
    });
    $(document).on("click",".download_report", function(){            
        var notification_id   = $(this).attr('notification_id');
        var report_name       = $(this).attr('report_name');
        downloadReport(notification_id,report_name);
    });
    
    
    $(document).on("click",".read_notification", function(){            
        var notification_id   = $(this).attr('notification_id');
        var notification_type = $(this).attr('notification_type');
        var action            = 'r';
        if(notification_type == 'import_asset') action = 'd';
        
        readNotification(notification_id,notification_type,action);
    });
    
    
    $(document).on("click","#notification_modal_markasread", function(){
        var notification_id   = $(this).attr('notification_id');
        var notification_type = $(this).attr('notification_type');
        var action            = 'r';
        
        readNotification(notification_id,notification_type,action);
    });
    
    $(document).on("click",".notification_modal_open", function(){
        $("#notification_modal_importname").html($(this).attr('report_name'));
        $("#notification_modal_importtitle").html($(this).attr('import_title'));
        $("#notification_modal_importsuccess").html($(this).attr('success'));
        $("#notification_modal_importfail").html($(this).attr('fail'));
        $("#notification_modal_importtotal").html($(this).attr('total'));
        $("#notification_modal_importdate").html($(this).attr('created_at'));
        $("#notification_modal_markasread").attr('notification_id',$(this).attr('notification_id'));
        $("#notification_modal_markasread").attr('notification_type',$(this).attr('notification_type'));
    });
	
	$(document).on("click","[id^= delete_]", function(){
		$('li.dropdown-menu').hide();
	});
});



function bvListbox(buid, putAt, bvid) {
    var bvajax;
    if (parseInt(buid) > 0) {
        var postData = { 'datatype': 'json', 'buid': buid };
        var url = SITE_URL + '/main/getbvs/';
        ajaxCall(bvajax, url, postData, function (data) { createBvlistbox(data, putAt, bvid) });
    }
}
function createBvlistbox(data, putAt, bvid) {
    if (bvid > 0)
        var bv_id = bvid;
    else
        var bv_id = '';
    putAt = putAt == '' || putAt == undefined ? 'sr_bu_v_id' : putAt;
    var result = $.parseJSON(data);
    $("#" + putAt).html('<option>-Business Vertical-</option>');
    $.each(result, function (key, value) {
        if (bv_id == key)
            var selected = "selected";
        else
            var selected = "";
        $("#" + putAt).append('<option value="' + key + '" ' + selected + '>' + value.bvname + '</option>');
    });
}
function panelData(data, id) {
    var response = JSON.parse(data);
    if (response != null) {
        if (response.is_error == false) {
            $("#" + id).removeClass("panel hide").addClass("panel");
            $("#" + id).find("div.panel-body").html(response.html);
        }
    }
    return true;
}
var randval = Math.floor(Math.random() * 100000);
var sesskey;
var win_handle;
var cltimer;
var delete_cookie = function (name) {
    document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
};
function showResponse(data, id, msgdiv) {
    if (msgdiv == undefined)
        msgdiv = "msg_div";
    if (data != "" && data != null) {
        var response = JSON.parse(data);
        //console.log(response);
        if (response != null) {
            if (response.html == '' && response.msg == null && response.is_error == null) {
                //console.log(response);
                return false;
            }
            if (response.html != null && typeof response.is_error !== undefined && !response.is_error && response.html != '') {
                //$("#" + msgdiv).html('');
                $("#" + id).html('');
                showAlert(id, 'noclass', response.html);
                return true;
            }
            else {
                var clas = response.is_error ? 'danger' : 'success';
                //  var msg = '';
                message = response.msg;
                $("#" + id).html('');
                if (typeof message !== 'string') {
//                  console.log("In Array...");
                    $.each(message, function (key, value) {
                        showAlert(msgdiv, clas, value);
                    });
                }
                else {
//                  console.log("In String...");
                    message = response.msg;
                    showAlert(msgdiv, clas, message);
                }
                if (clas == 'error')
                    return false;
                else
                    return true;
            }
        }
    }
}
function showAlert(id, clas, msg) {
    if (msg != '') {
        //if(id != "") $("#" + id).css("background-color","unset");
        
        var close_button = '<div class="alert alert-dismissable alert-' + clas + '"><button type="button" class="close close_button" id="close_button" aria-hidden="true"><i class="fa fa-close"></i></button>';
        if (clas != 'noclass')
            msg = close_button + msg + '</div>';
        if (clas == 'error') {
            clas = "alert-danger";
            $("#" + id).addClass(clas).removeClass("hidden alert-success alert-info");
        }
        else if (clas == 'success') {

            clas = "alert-success";
            $("#" + id).addClass(clas).removeClass("hidden alert-danger alert-info");
        }
        else if (clas == 'info') {
            clas = "alert-info";
            $("#" + id).addClass(clas).removeClass("hidden alert-danger alert-success");
        }
        else if (clas == 'noclass') {
            $("#" + id).removeClass("hidden alert-danger alert-info alert-success");
        }
        $("#" + id).removeClass("alert hidden alert-dismissable");

        $("#" + id).append(msg);
        //hide repeated message divs
        if($(".alert.alert-dismissable:visible").length > 1){
            $(".alert.alert-dismissable:visible").each(function(){
                var txt = $(this).text();
                $(".alert.alert-dismissable:visible").filter(function() { return ($(this).text() === txt) }).not(":eq(0)").hide();
            });
        }
        if (clas != '' && clas != 'noclass')
            activateClose(id);
    }
}
function activateClose(id) {
    //$("#close_button").click(function () { closeMsgBox(id); });
    $(".close_button").click(function () { $(this).parent().addClass('hidden'); });
}
function closeMsgBox(id) {
    $("#" + id).addClass("hidden");
}
function closeMsgAuto(div_id) {
    setTimeout(function () { $("#" + div_id).fadeIn('slow').empty(); }, 3000);
}
function resetForm(form_id) {
    $("#" + form_id).find("input[type=text], input[type=password], input[type=number], input[type=checkbox], textarea, select").each(function () { $(this).val(''); });
    $("#" + form_id).find('.chosen-select').val('').trigger('chosen:updated');
}
function checkAll(e, t) {
    var a = document.getElementsByName(e);
    for (r = 0; r < a.length; r++)1 == t ? a[r].checked = !0 : a[r].checked = !1
}
function checkInt(evt) {
    evt = (evt) ? evt : window.event
    var charCode = (evt.which) ? evt.which : evt.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        status = "This field accepts numbers only."
        return false
    }
    status = ""
    return true
}
function lightbox(flag, contents, title, size) {
    var msg = 'Lightbox Errors\n';
    var is_error = false;
    var Body = $('body');
    var collapse = "sb-l-m";
    if (flag) {
        if (flag == 'show') {
            if (title) {
                $("#lightbox_title").text(title);
            }
            else {
                $("#lightbox_title").text('New Window');
            }
            if (contents) {
                $("#lightbox_data").html(contents);
                $("#sidebar_right").css("right", "0");
                if (size == '' || size == undefined || size == 'full') {
                    $("#sidebar_right").css("left", "70px");

                }
                else if (size == 'medium') {
                    $("#sidebar_right").css("left", "60%");
                }
                else if (size == 'middle') {
                    $("#sidebar_right").css("left", "50%");
                }
                else if (size == 'large') {
                    $("#sidebar_right").css("left", "40%");
                }
                 else if (size == 'maxlarge') {
                    $("#sidebar_right").css("left", "20%");
                }
                else if (size == 'small') {
                    $("#sidebar_right").css("left", "75%");
                }
            }
            $("#sidebar_right").show();
            $("#lightboxbackid").css("height", '250%');
            $("#sidebar_right").css("height", '250%');
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
            $("#sidebar_right").css("overflow", 'auto');
            $("#sidebar_right").addClass("custom-scrollbar");
            $("#lightboxbackid").addClass('lightboxback');
            Body.toggleClass('sb-r-o ').addClass(collapse);
        }
        else {
            $("#sidebar_right").hide();
            $("#lightboxbackid").css("height", 'auto');
            $("#sidebar_right").css("height", '100%');
            $("#lightboxbackid").removeClass('lightboxback');
        }

        $(window).trigger('resize');
    }
    else {
        is_error = true;
        msg += 'Please set Flag for Lightbox.\n';
    }
    if (is_error) {
        alert(msg);
        return false;
    }
}
function handleSearchBoxWidth(id) {
    var topclass = $("#" + id).attr('class');
    if (topclass == 'col-md-5') {
        $("#srchtext").parent().parent().removeClass('col-md-3').addClass('col-md-5');

    }
    else if (topclass == 'col-md-6') {
        $("#srchtext").parent().parent().removeClass('col-md-3').addClass('col-md-4');

    }
    else if (topclass == 'col-md-12') {
        $("#srchtext").parent().parent().removeClass('col-md-3').addClass('col-md-2');

    }
}
function afterChildClose(objid) {
    if (win_handle.closed) {
        clearInterval(cltimer);
        cltimer = false;
        closeconsole(sesskey, objid);
    }
}
function viewgraph(objectid, id, monid, entity_id, data_key) {
    var d = new Date();
    var newwnd = d.getTime()
    var url = SITE_URL + '/monitor/monitor/showgraph/';
    url = url + 'device/' + objectid + '/' + id + '/' + monid;
    var width = 1150;
    var height = 700;
    var left = parseInt((screen.availWidth / 2) - (width / 2));
    var top = parseInt((screen.availHeight / 2) - (height / 2));
    if (entity_id != undefined)
        url = url + "/" + entity_id;
    if (data_key != undefined)
        url = url + "/" + data_key;

    var winconf = 'width=' + width + ',height=' + height + ',resizable=1,left=' + left + ',top=' + top;
    window.open(url, newwnd, winconf);
}
function refreshgraph(exp) {
    closeMsgBox('msg_div');
    var url = SITE_URL + '/monitor/monitor/showgraph/device';
    if (exp == "yes") {
        $("#frmgraphfilter").attr("target", "_blank");
    }
    else {
        $("#exportto").val("");
    }
    $("#frmgraphfilter").submit();
    $("#frmgraphfilter").attr("target", "");
    $("#exportto").val("");
}
function clearcustomtime() {
    $("#customtime").val('');
}
function cleardatetimerange() 
{
    $("#timerange").val('');
}
function initialize_field() {
    $('#onlyonce').datetimepicker(
        {
            format: 'YYYY-MM-DD',
            changeMonth: true,
            changeYear: true,
            minDate: 0,
        }
    );
    if ($("#scheduletype").length) {
        var sch = $("#scheduletype").val();
        if (sch == '')
            sch = 'once';
        $("." + sch + ' > a').trigger("click");
    }
}
function listbox_moveacross(sourceID, destID) {
    var src = document.getElementById(sourceID);
    var dest = document.getElementById(destID);
    for (var count = 0; count < src.options.length; count++) {
        if (src.options[count].selected == true) {
            var option = src.options[count];

            var newOption = document.createElement("option");
            newOption.value = option.value;
            newOption.text = option.text;
            newOption.setAttribute('style', option.getAttribute('style'));
            newOption.selected = true;
            try {
                dest.add(newOption, null); //Standard
                src.remove(count, null);
            } catch (error) {
                dest.add(newOption); // IE only
                src.remove(count);
            }
            count--;
        }
    }
}
function checkBoxstr(className) {
    var inputElements = document.getElementsByClassName(className);
    var checkedValue = '';

    for (var i = 0; inputElements[i]; ++i) {
        if (inputElements[i].checked) {
            checkedValue = checkedValue + ',' + inputElements[i].value;
        }
    }
    return checkedValue;
}
function jsonDecode(data) {
    if (data != "" && data != null) {
        var response = JSON.parse(data);
        if (response != null) {
            return response;
        }
        else
            return false;
    }
}
function clearMsg(id) {
    $("#" + id).html('');
}
function initsingleselect()
{
    $('.chosen-select').chosen();
}
function initsingleselectid(id)
{   
    $('#chosen-select-'+id+'.chosen-select').chosen();
} 
function initmultiselect()
{
    $(document).on("mouseenter",".chosen-choices", function() 
    {
        var modPlaceholder;
        modPlaceholder = jQuery("select.chosen-select").attr("data-placeholder");
        jQuery(".chosen-search-input").val("");
        jQuery(".chosen-search-input").attr("placeholder", modPlaceholder);
        if(("li.search-choice").length)
        {
           jQuery(".chosen-search-input").removeAttr('placeholder');
        }
        $(".chosen-choices").mCustomScrollbar({
            axis:"x",
            theme:"dark-3",
            advanced:{ autoScrollOnFocus: "true" },
            autoHideScrollbar: true

        });
    });
}
function applyVerticalScroll(){
    $(document).on("mouseenter",".apply-custom-vertical-scroll", function() {
        console.log("vertical");
        $(".apply-custom-vertical-scroll").mCustomScrollbar({
            axis:"y",
            theme:"dark-3",
            advanced:{ autoScrollOnFocus: "true" },
            autoHideScrollbar: true

        });
    });
}
/*function initmultiselect(id)
{
    $("#" + id).multiselect({
                enableFiltering: true,
    });
}*/
function rebuildmultiselect(id)
{
    console.log(id);
    $('#'+id).trigger("chosen:updated");
  //  $("#" + id).multiselect("rebuild");
} 
var cudeleid = "";
function removeRow(id = "")
{
    cudeleid = id;    
    $(document).on('click', '.remove', function() {
        var trcount = $(this).parents('table').find('tr').length;
        //var trIndex = $(this).closest("tr").index();
        //alert(trIndex);
        if(trcount > 2)
        {
            $(this).closest("tr").remove();
            if(cudeleid != '')
            {
                //alert(cudeleid);
                deletedasst(cudeleid);
                cudeleid = "";
            }
        }    
            
       // if(trIndex > 0) {
         //$(this).closest("tr").remove();
       // }
    });
}
function addMore(classname='addmore')
{
    //var data = $(".addmore tr:eq(1)").clone(true).appendTo(".addmore");
   // data.find("input,select").val('');

    var row = $("."+classname+" tr").last().clone();
      ///  var oldId = Number(row.attr('id').slice(-1));
        var oldId = Number(row.attr('id').match(/\d+/)); // 123456
        var id = 1 + oldId; 
        row.attr('id', 'row-' + id );
        row.appendTo("."+classname);
        var completerow = row.find("input");
        //console.log(completerow);
        row.find("input").each(function (index, element){
            var originalName = element.name;
            var name = originalName.replace("[]", "")
            $("#row-"+id+" input[name='"+originalName+"']").attr('id', name+"-"+id);            
        });
        row.find("select").each(function (index, element){
            var originalName = element.name;
            //alert(originalName);
            var name = originalName.replace("[]", "")
            $("#row-"+id+" select[name='"+originalName+"']").attr('id', name+"-"+id); 

        });
        row.find(".item_product_cls").each(function (index, element){
            var originalName = element.name;
            //alert(originalName);
            var name = originalName.replace("[]", "")
            // $("#row-"+id+" select[name='"+originalName+"']").attr('id', name+"-"+id); 
            $("#row-"+id+" select[name='"+originalName+"']").html('<option value="">[Select Item]</option>'); 
                       
        });
        row.find("textarea").each(function (index, element){
            var originalName = element.name;
            var name = originalName.replace("[]", "")
            $("#row-"+id+" textarea[name='"+originalName+"']").attr('id', name+"-"+id);            
        });
     row.find("textarea, input, select").addClass('element-' + id );
     row.find("textarea, input, select").val('');
     initsingleselect();
}

function datecalendar(datetimeid,idclass='id'){
    console.log("datetimepicker");
    //var dateToday = new Date();    
    if(idclass=='id'){
        $('#'+datetimeid).find('span').click(function(){$('.bootstrap-datetimepicker-widget:visible').hide();});
        $('#'+datetimeid).datetimepicker({
            format: 'YYYY/MM/DD',
            pickTime: false,
          //  minDate: dateToday
        });
    }else{    
        $('.'+datetimeid).find('span').click(function(){$('.bootstrap-datetimepicker-widget:visible').hide();});
        $('.'+datetimeid).datetimepicker({
            format: 'YYYY/MM/DD',
            pickTime: false,
           // minDate: dateToday
        });
    }
}
function datetimecalendar(datetimeid){
    $('#'+datetimeid).datetimepicker({
        format: 'YYYY/MM/DD  HH:mm',
        //minDate: 0
    });
    
}
function isNumberKey(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)){
        if(charCode >= 96 && charCode <= 105)
        {
            return true;
        }
        return false;
    }
    return true;
}
function isDecimalNumber(evt, element)
{
    var charCode = (evt.which) ? evt.which : event.keyCode
    console.log(charCode);
    if (charCode > 31 && (charCode != 46 || $(element).val().indexOf('.') != -1) &&      // “.” CHECK DOT, AND ONLY ONE.
        (charCode < 48 || charCode > 57)) {
        if(charCode >= 96 && charCode <= 105)
        {
            return true;
        }
        return false;
    }
        return true;
}

//tree initiate function 

var treedata;
function inittree(treeid,jsondata,treefun)
{
    obj = jQuery.parseJSON( jsondata );
    treedata = jQuery.makeArray(obj.cidata);
    $("#"+treeid).fancytree({
      extensions: ["filter"],
      quicksearch: true,
      source: treedata,
      icon: false, 
      //source: {
      //  url: "http://10.10.99.2:6200/enlight/ajax-tree-local.json"
      //},
      filter: {
        //autoApply: true, 
        autoExpand: true,
        counter: false, // No counter badges
        nodata: true,
        mode: "hide"  
      },
      activate: function(event, data) {
         
          var treefunction = window[treefun];
        //Call the function
          treefunction(data);
      },
     // lazyLoad: function(event, data) {
       //   data.result = {
        //    url: "http://10.10.99.2:6200/enlight/ajax-sub2.json"
         // }
       // }
    });
    var tree = $("#"+treeid).fancytree("getTree");
    tree.options.filter.mode = "hide";
    tree.options.filter.icon = false;

      /*
     * Event handlers for our little demo interface
     */
    $("input[name=treesearch]").keyup(function(e) 
    {
      var n,
        leavesOnly = $("#leavesOnly").is(":checked"),
        match = $(this).val();

      if (e && e.which === $.ui.keyCode.ESCAPE || $.trim(match) === "") {
        removeTreeNoRecFound();
        tree.clearFilter();
      }
      if ($("#regex").is(":checked")) {
        // Pass function to perform match
        n = tree.filterNodes(function(node) {
          return new RegExp(match, "i").test(node.title);
        }, leavesOnly);
      } else {
        // Pass a string to perform case insensitive matching
        n = tree.filterNodes(match, leavesOnly);
        
        if(n==0){
            if($("#no_rec_found").length < 1) $('#treeshow').after('<div id="no_rec_found" class="col-md-12 mt10">'+trans("messages.msg_norecordfound")+'</div>');
            $('#treeshow').hide();
        }
        else{
            removeTreeNoRecFound();
        }
      }
      $("button#btnResetSearch").attr("disabled", false);
     // $("span#matches").text("(" + n + " matches)");
    });

    tree.options.filter.mode = "hide";
    tree.options.filter.autoExpand = true;
    tree.clearFilter();
    $("#btnResetSearch").click(function(e) 
    {
      removeTreeNoRecFound();
      $("input[name=treesearch]").val("");
      //$("span#matches").text("");
      tree.clearFilter();
    }).attr("disabled", true);

    $("input#hideMode").change(function(e) 
    {
      removeTreeNoRecFound();
      tree.options.filter.mode = $(this).is(":checked") ? "hide" : "dimm";
      tree.clearFilter();
      $("input[name=treesearch]").keyup();
    });

    $("input#leavesOnly").change(function(e) 
    {
      removeTreeNoRecFound();
      // tree.options.filter.leavesOnly = $(this).is(":checked");
      tree.clearFilter();
      $("input[name=treesearch]").keyup();
    });
    $("input#regex").change(function(e) {
      removeTreeNoRecFound();
      tree.clearFilter();
      $("input[name=treesearch]").keyup();
    });    

}
function removeTreeNoRecFound(){
    if($("#no_rec_found").length > 0)  $("#no_rec_found").remove();
    $('#treeshow').show();  
}
function applyHorizntalScrool(classname)
{
    $("."+classname).mCustomScrollbar({
        axis:"x",
        theme:"dark-3",
        advanced:{ autoScrollOnFocus: "true" },
        autoHideScrollbar: true

    });
}
function showDropZoneFile(dropzoneid)
{

    Dropzone.autoDiscover = false;
  //var serviceBlockUploadImage = $("#"+dropzoneid).dropzone({
    Dropzone.options.dropZone = {
        //autoProcessQueue: false,
        url: '/purchaserequest/upload',
        paramName: "file", // The name that will be used to transfer the file
        maxFilesize: 100, // MB
        dictDefaultMessage: '<i class="fa fa-cloud-upload"></i> \
        <span class="main-text"><b>Drop Files</b> to upload</span> <br /> \
        <span class="sub-text">(or click)</span> \
        ', 
        addRemoveLinks: true,
        init: function () {
    
            var myDropzone = this;
           //var myDropzone = serviceBlockUploadImage;
    
            // Update selector to match your button
            $("#attachmentbtn").click(function (e) {
                //alert();
                e.preventDefault();
                myDropzone.processQueue();
               
            });
    
            this.on('sending', function(file, xhr, formData) {
                // Append all form inputs to the formData Dropzone will POST
                var data = $('#dropZone').serializeArray();
                $.each(data, function(key, el) {
                    formData.append(el.name, el.value);
                   
                });
            });
        }
    }
   
   /* //var serviceBlockUploadImage = $("#"+dropzoneid).dropzone({
        Dropzone.options.dropZone = {
            url: "/uploads",
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 100, // MB

            addRemoveLinks: true,
            dictDefaultMessage: '<i class="fa fa-cloud-upload"></i> \
    <span class="main-text"><b>Drop Files</b> to upload</span> <br /> \
    <span class="sub-text">(or click)</span> \
    ', 
            dictResponseError: 'Server not Configured',
           // addedfile: function(file) { console.log(file); },
            sending: function(file, xhr, formData) {
                var self = serviceBlockUploadImage;
        
                // Get the id from the dropzone itself
                var idVal = self.data('id');
                var imageName = 'service_block_image'
        
                // Append this to the request
                formData.append('id', idVal);
                formData.append('_token', $('meta[name="_token"]').attr('content'));
                formData.append('name', imageName);
                formData.append('pagecontent_id', $('body').data('page-id'));
                console.log(formData);
                console.log(this.getAcceptedFiles());
        
            },
            init: function() {
                this.on("success", function(file, response) {
                    var self = serviceBlockUploadImage;
                    var imagePath = response.imagePath;
                    var parent = self.parent();
        
                    // If the image type is a background image, set the background to the nearest parent
                    // Else we update the image path
                    parent.find('img').attr('src', imagePath);
                    console.log(file);
        
                });
            },
            headers: {
                'x-csrf-token': document.querySelectorAll('meta[name=csrf-token]')[0].getAttributeNode('content').value,
              },
              
            accept: function(file, done)
            {
                file.postData = [];
                $.ajax({
                    url: '/uploads',
                    data: {name: file.name, type: file.type, size: file.size},
                    type: 'POST',
                    success: function(response)
                    {
                        file.postData = response.post;
                        file.guid = response.data.guid;
                        file.s3 = response.post.key;
                        done();
                    },
                    error: function(response)
                    {
                        if (response.responseText) {
                            response = JSON.parse(response.responseText);
                        }
                        if (response.message) {
                            done(response.message);
                        } else {
                            done('error preparing the file');
                        }
                    }
                });
            },
            uploadMultiple: true,
            autoProcessQueue:false,
            acceptedFiles: '.jpg, .jpeg, .png, .svg'            

        }*/
       // });

      /* Dropzone.options.dropZone = {
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 0, // MB

            addRemoveLinks: true,
            dictDefaultMessage: '<i class="fa fa-cloud-upload"></i> \
    <span class="main-text"><b>Drop Files</b> to upload</span> <br /> \
    <span class="sub-text">(or click)</span> \
    ',
            dictResponseError: 'Server not Configured'
            
       };*/
       // $('#'+dropzoneid).dropzone(); 
}

function notificatioMessages(id)
{
    var url = SITE_URL + '/purchaserequest/getnotifications';
    var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function (data) {
         var result = JSON.parse(data);
         var notification  = '<li class="br-t of-h"> <a href="#" class="fw600 p12 animated animated-short fadeInDown"> <span class="fa fa-gear pr5"></span> Notifications</a> </li>';
         //$("#dropdown-notifications").html("");
         $("#dropdown-notifications").html(notification);
         if (result.is_error) {
            notification = notification + 
            $("#dropdown-notifications").append(result.message);
         }
         else {
            
            $("#dropdown-notifications").append(result.result);
         }
         
     }); 
}

function deleteAttachment(attach_id)
{
    if(confirm(trans('messages.msg_delattachmentconfirm')))
    {
        emLoader('show', trans('label.lbl_loading'));
        closeMsgAuto('msg_div');
        // closeMsgAuto('msg_modal');
        var pr_po_id = $('#pr_po_id[value]').val();
        var att_type = $("#attachment_type").val()
        //var url      = SITE_URL + '/purchaserequest/deleteattachment';
        var url      = '';
        console.log(pr_po_id);
        console.log(att_type);
        console.log(attach_id);
        console.log("delete attachment");
        
        var callfor   = window.location.pathname.split("/").pop();
        console.log(callfor);
        switch(callfor){
          case 'contract':
              url = SITE_URL + '/delete_attachment_contract';
          break;
          case 'purchaserequest':
              url = SITE_URL + '/delete_attachment_pr';
          break;
          case 'purchaseorders':
              url = SITE_URL + '/delete_attachment_po';
          break;
        }
        if(url != ''){
        var postData = { 'attach_id' : attach_id, 'pr_po_id':pr_po_id, 'attachment_type':att_type};
        var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function (data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, 'grid_data', 'msg_div');         
                emLoader('hide');
                window.scrollTo(0, 0);
            }
            else {
                emLoader('hide');
                lightbox('hide');
                if($("#attachment_type").val() == 'pr') prList();
                else poList();
                showResponse(data, 'grid_data', 'msg_div');           
                window.scrollTo(0, 0);
            }
        }); 
        }
    }
}
function downloadAttachment(att_id='',att_path='',att_title=''){
   
  //var url       = SITE_URL + '/purchaserequest/downloadattachment_pr';
  var url       = '';

  var callfor   = window.location.pathname.split("/").pop();
  switch(callfor){
    case 'contract':
        url = SITE_URL + '/download_attachment_contract';
    break;
    case 'purchaserequest':
        url = SITE_URL + '/download_attachment_pr';
    break;
    case 'purchaseorders':
        url = SITE_URL + '/download_attachment_po';
    break;
  }

  if(url != ''){
      var postData  = { 'attach_id': att_id,'attach_path': att_path,'attach_title':att_title};
   
      emLoader('show', trans('label.lbl_loading'));
      var result_ajax = ajaxCall(result_ajax, url, postData, function (data) {
        var result = JSON.parse(data);
        if (result.is_error) {
          showResponse(data, 'grid_data', 'msg_div');
          emLoader('hide');
          window.scrollTo(0, 0);
        }
        else {
          emLoader('hide');
          lightbox('hide');

          var data_arr = JSON.parse(data);
          var download_path = SITE_URL +'/'+ data_arr['html'];

          console.log(download_path);
          //download attachment
          var a = document.createElement('a');
          a.setAttribute('href', download_path);
          a.setAttribute('download','');

          var aj = $(a);
          aj.appendTo('body');
          aj[0].click();
          aj.remove();

          window.scrollTo(0, 0);
        }
      });
    }
}
//Notifications
function showNotificationCount()
{
    var postData     = {'count':'yes'};
    var url          = SITE_URL + '/reports/getreportnotifications';
    var notCountajax = ajaxCall(notCountajax, url, postData, function (data) {
    var response       = JSON.parse(data);
    if (response.html != null && typeof response.is_error !== undefined && !response.is_error && response.html != '') 
    {
       var el = document.querySelector('.notification');
        var count = Number(response.html) || 0;
        el.setAttribute('data-count', count);
        el.classList.remove('notify');
        el.offsetWidth = el.offsetWidth;
        el.classList.add('notify');
        if(count != 0)
        {
            el.classList.add('show-count');
        }
    } 
    }); 
}

function showNotificatioMessages(id)
{
   var postData     = {'count':'no'};
    var url          = SITE_URL + '/reports/getreportnotifications';
    var notCountajax = ajaxCall(notCountajax, url, postData, function (data) {
    var response       = JSON.parse(data);
         var notification  = '<li class="br-t of-h"> <a href="#" class="fw600 p12 animated animated-short fadeInDown"> <span class="fa fa-gear pr5"></span> Notifications</a> </li>';
         //$("#dropdown-notifications").html("");
         $("#dropdown-rep-notifications").html(notification);
         if (response.is_error) {
            notification = notification + 
            $("#dropdown-rep-notifications").append(response.message);
         }
         else {
            
            $("#dropdown-rep-notifications").append(response.html);
         }
         
     }); 
}
function downloadReport(notification_id='',report_name='')
{
    var url       = SITE_URL + '/reports/download'; 
    var postData  = { 'notification_id': notification_id,'report_name': report_name};
    emLoader('show', trans('label.lbl_loading'));
    var result_ajax = ajaxCall(result_ajax, url, postData, function (data) 
    {
        var result = JSON.parse(data);
        if (result.is_error) 
        {
          showResponse(data, 'grid_data', 'msg_div');
          emLoader('hide');
          window.scrollTo(0, 0);
        }
        else
        {
          emLoader('hide');
          lightbox('hide');

          var data_arr = JSON.parse(data);
          var download_path = SITE_URL +'/'+ data_arr['html'];
          //download attachment
          var a = document.createElement('a');
          a.setAttribute('href', download_path);
          a.setAttribute('download','');
          var aj = $(a);
          aj.appendTo('body');
          aj[0].click();
          aj.remove();
          window.scrollTo(0, 0);
        }
      });
}

function readNotification(notification_id='',notification_type='',action='r')
{
    if (confirm(trans('messages.msg_report_notification_remove'))) {
    var url       = SITE_URL + '/reports/readnotification'; 
    var postData  = { 'notification_id': notification_id,'notification_type': notification_type,'action': action};
    emLoader('show', trans('label.lbl_loading'));
    var result_ajax = ajaxCall(result_ajax, url, postData, function (data) 
    {
        var result = JSON.parse(data);
        if (result.is_error) 
        {
          showResponse(data, 'grid_data', 'msg_div');
          emLoader('hide');
          window.scrollTo(0, 0);
        }
        else
        {
          emLoader('hide');
          lightbox('hide');

          window.location.reload();
        }
      });
    }

}
