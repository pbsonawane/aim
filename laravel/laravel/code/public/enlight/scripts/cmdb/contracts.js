var postData = null;
$(document).ready(function () {
    contractList();

   $(document).on("click", "#contractadd", function () { contractadd(); });
   $(document).on("click", ".contract_renew", function () {
      
    var contract_id = $(this).attr('id');  contractrenew(contract_id); });
    $(document).on("click", "#contractrenew", function () {contractrenewsubmit(contract_id);});
    $(document).on("click", "#contractaddsubmit", function () {contractaddsubmit(); });
    $(document).on("click", ".contract_edit", function () { var contract_id = $(this).attr('id'); 
   // alert(contract_id);
    contractedit(contract_id); });
   $(document).on("click", "#contracteditsubmit", function () { contracteditsubmit(); });
    $(document).on("click", ".contract_delete", function () {  var contract_id = $(this).attr('id'); 
    contractdelete(contract_id); });
    $(document).on("click", ".contractdetails", function () {  
       var contract_id = $(this).attr('id');
        contractdetails(contract_id); 
    });

    $(document).on("click",".contractlist", function() {  $(".contractlist").removeClass("active"); $(this).addClass("active");  var id = $(this).data('id'); 
    contractdetails(id); });
   
    $(document).on("click", "#checkbox_test", function () { var contract_id = $(this).attr('id'); 
    checkbox_asset(contract_id); });

    $(document).on("click", "#removeassets", function () { var contract_id = $(this).attr('id');
    removeassets(contract_id); });

    $(document).on("click", ".childcontracts", function () { var contract_id = $(this).attr('id'); 
   // alert(contract_id);
    childcontract(contract_id); });
    $(document).on("click", "#checkbox_associatechild", function () { var contract_id = $(this).attr('id'); 
   checkbox_associatechild(contract_id); });
   $(document).on("click", "#associatechild", function () { checkbox_associatechild(); });

   $(document).on("click", ".renewcontractdeatils", function () { var contract_id = $(this).attr('id'); 
   // alert(contract_id);
   renewcontractdeatils(contract_id); });
   $(document).on("click", "#upload", function () { contractattachfile(); });

   //function for contract asset list
    $(document).on("click", "#btn_add_asset", function () { contractassetlist(); });

   $(document).on("click","#attachtdoc", function() { 
        $(".tab-block .nav-tabs > li").removeClass("active");
        $(".tab-block .nav-tabs > li.contract_detailstab").addClass("active");
        $("#contract_details").addClass("active");
        $('html, body').animate({
            scrollTop: $("#attachtable").offset().top
            }, 1000);
});
$(document).on("click", ".asset_delete", function () {  var contract_id = $(this).attr('id'); 
contractassetdelete(contract_id); });
$(document).on("click", "#sendmailsubmit", function () {sendmailsubmit(); });
$(document).on("click", "#submitAction", function () {        
    submitAction();
});
$(document).on("click", ".actionsPr", function () {
    var id = $(this).attr('id');
    var action = id.split('_')[0];
	if(action == 'notifyowner'){
		var msg = 'notify owner';
	}else if(action == 'notifyvendor'){
		var msg = 'notify vendor';
	}
    if(confirm(trans('messages.msg_actionconfirmation_contract', {"name": msg})))
    {
        actionsPr(id);            
    }
});

    $(document).on("click",".deleteAttachment", function() { 
         
    var id = $(this).attr('id');
    deleteAttachment(id);
    });
	
	$(window).on("scroll",function(e){
	
		$("#advcontract_type_id").trigger('chosen:close');
		$("#advcontract_status").trigger('chosen:close');
	
	});
	
	$(window).on("scroll",function(e){
		$(".popover").hide();
	});
});


$(document).on("click","#upload", function(e) { 
//$(document).on("submit",".add_attachment_contract",function(){

      /*var file = $('input[type="file"]').val();
      var exts = ['doc','docx','rtf','odt','jpeg','png','svg'];
      // first check if file field has any value
      if ( file ) {
        // split file name at dot
        var get_ext = file.split('.');
        // reverse name to check extension
        get_ext = get_ext.reverse();
        // check file type is valid as given in 'exts' array
        if ( $.inArray ( get_ext[0].toLowerCase(), exts ) > -1 ){
			alert("valid")
        } else {
		  alert("Invalid File Extension");
          e.preventDefault();
        }
      }*/
	    var invalid_flag = 0;
		var invalid_size = 0;
		var exts = ['doc','docx','rtf','odt','jpeg','png','svg','gif','xlsx','pdf', 'csv','txt'];
		var fi  = document.getElementById('attachments');
	    if (fi.files.length > 0) { 
            for (var i = 0; i <= fi.files.length - 1; i++) { 
				
				console.log(fi.files.item(i));
				var get_ext = fi.files.item(i).name.split('.');
				get_ext = get_ext.reverse();
				
				if ( $.inArray ( get_ext[0].toLowerCase(), exts ) > -1 ){
					invalid_flag = 0;
					const fsize = fi.files.item(i).size; 
					const file = Math.round((fsize / 1024)); 
					// The size of the file. 
					if (file > 2048) { 
						invalid_size = 1;
					}
					
				}else{
					invalid_flag = 1;
				}
                
			}
		
			if(invalid_flag == 1){
				alert(trans('messages.msg_invalid_file_ext'));
				 e.preventDefault();
			}
			if(invalid_size == 1){
				alert(trans('messages.msg_max_allowed_size',{'name':'2 MB'}));
				e.preventDefault();
			}
			emLoader('hide'); 
		}
    
  });
function contractList(){
 
    closeMsgAuto('msg_div');
    emLoader('show', 'Loading Contract');
    var url = SITE_URL + '/contract/list';
    var postData = $("#frmdevices").serialize();
   
        var userajax = ajaxCall(userajax, url, postData, function (data) {
     
            var result = JSON.parse(data);
           
           
            if(typeof(result.contract_id) != "undefined" && result.contract_id !== null) {
                var first_contract_id = result.contract_id;
                showResponse(data, 'contract_list', 'msg_div');
                contractdetails(first_contract_id);
           }
           
            $(".contractlist:first").not( ".ccursor" ).addClass("active");
			$("div[data-id='" + first_contract_id + "']").not( ".ccursor" ).addClass("active");
            emLoader('hide');
            initsingleselect();
            initmultiselect();
        });
       
    }

    
function contractdetails(first_contract_id) {
   // alert(first_contract_id);
   if(first_contract_id != ""){
	   
	if(!$('#contract_detail').hasClass('rotated')){
		$("#contract_detail").addClass("br-l");
	}
    var url = SITE_URL + '/contract/details';
    postData = {
        'contract_id' : first_contract_id
    };
    emLoader('show', trans('messages.msg_contract_loading'), 'contract_detail');
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        emLoader('hide');
        showResponse( data, 'contract_detail', 'msg_div');
        //showDropZoneFile("dropZone");
         $(".download_file").click(function(){
            var att_id   = $(this).attr('download_id');
            var att_path = $(this).attr('download_path');
            downloadAttachment(att_id,att_path);
        });

    });
}else{
	$("#contract_detail").removeClass("br-l");
    $("#contract_detail").html("<div class='textaligncenter'><strong>"+trans('messages.msg_norecordfound') +"</strong></div>");

}
}

function contractadd() {
    closeMsgBox('msg_div');
	emLoader('hide');
    emLoader('show', trans('messages.msg_contract_loading'));
    var url = SITE_URL + '/contract/add';
    var notifyajax = ajaxCall(notifyajax, url, {}, function (data) {
        lightbox('show', data, trans('messages.msg_contract_add'), 'full');
        emLoader('hide');
        datecalendar('from_date');
        datecalendar('to_date');
        
    });
}

function contractaddsubmit() {

    clearMsg('msg_popup');
    if (cltimer) {
        clas = 'error';
        showAlert("msg_popup", clas, trans('messages.msg_session_open'));
        return false;
    }
    emLoader('show', trans('label.lbl_contract'));
    var url = SITE_URL + '/contract/addsubmit';
    var postData = $("#addformcontract").serialize();
    console.log(postData);
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        var result = JSON.parse(data);
        console.log(result);
        if (result.is_error) {
			
            showResponse(data, '',  'msg_popup');
            emLoader('hide');
        }
        else {
            emLoader('hide');
            lightbox('hide');
            
            window.scrollTo(0, 0);
            showResponse(data, 'grid_data', 'msg_div');
            contractList();
        }

    });
}

function contractedit(contract_id) {
    emLoader('show', trans('messages.msg_contract_loading'));
    var id = contract_id.split('_')[1];
    //var contract_details_id = $("#contract_details_id").val();
    var postData = { 'datatype': 'json', 'id': id};
    console.log(postData);
    var url = SITE_URL + '/contract/edit';

    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        lightbox('show', data, trans('messages.msg_contract_edit'), 'full');
        emLoader('hide');
        datecalendar('from_date');
        datecalendar('to_date');
        
    });
}

function contracteditsubmit() {
    clearMsg('msg_popup');
	clearMsg('msg_div');
    emLoader('show', trans('messages.msg_updating_contract'));
    
    var url = SITE_URL + '/contract/editsubmit';
    var postData = $("#addformcontract").serialize();
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
         console.log(data);
        var result = JSON.parse(data);
       // console.log(result);
        if (result.is_error) {
            showResponse(data, '','msg_popup');
            emLoader('hide');
        }
        else {
            emLoader('hide');
            lightbox('hide');
            showResponse(data,'contract_detail','msg_div' );
            contractList();

        }
    });

}

function contractdelete(contract_id) {
    if (confirm(trans('messages.msg_contract_delete'))) {
        clearMsg('msg_popup');
        emLoader('show', trans('messages.msg_deleting_contract'));
        var id = contract_id.split('_')[1];
        var postData = { 'datatype': 'json', 'contract_id': id, 'status': 'd' };
        var url = SITE_URL + '/contract/delete';
        var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '','msg_div');
                emLoader('hide');
            }
            else {
                emLoader('hide');
                showResponse(data,'contract_detail', 'msg_div' );
                contractList();
          
            }
        });
    }
}

function contractassetdelete(contract_id) {
    if (confirm(trans('messages.msg_contract_asset_delete'))) {
        clearMsg('msg_popup');
        emLoader('show', trans('messages.msg_deleting_contract'));
        var id = contract_id.split('_')[1];
        var assetid = contract_id.split('_')[2];
        //console.log("mycontract-- for contract delete --"+id);
        //console.log("mycontract-- for assetid delete --"+assetid);
        var postData = { 'datatype': 'json', 'contract_id': id, 'asset_id':assetid};
        var url = SITE_URL + '/remove_asset_contract';
        var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
            var result = JSON.parse(data);
            if (result.is_error) {
                showResponse(data, '','msg_div');
                emLoader('hide');
            }
            else {
                emLoader('hide');
                showResponse(data,'contract_detail', 'msg_div' );
                contractList();
          
            }
        });
    }
}



$(document).on("click","#assetCheckAll", function() { 
    if (this.checked){
        $(".assetChk").each(function(){
            this.checked=true;
        });
    }else{
        $(".assetChk").each(function(){
            this.checked=false;
        });
    }
});

/* Credentials Checkbox : Check One by One code*/
$(document).on("click",".assetChk", function(){ 
    if ($(this).is(":checked")){
        var isAllChecked = 0;
        $(".assetChk").each(function(){
            if (!this.checked)
                isAllChecked = 1;
        });
        if (isAllChecked == 0){
            $("#assetCheckAll").prop("checked", true);
        }     
    } else {
        $("#assetCheckAll").prop("checked", false);
    }
});
 

function checkbox_asset() {
    var counter = 0, // counter for checked checkboxes
        i = 0,       // loop variable
      
        // get a collection of objects with the specified 'input' TAGNAME
        input_obj = document.getElementsByClassName('assetChk');
    // loop through all collected objects
	var options = '';
    for (i = 0; i < input_obj.length; i++) {
        // if input object is checkbox and checkbox is checked then ...
        if (input_obj[i].type === 'checkbox' && input_obj[i].checked === true) {
            // ... increase counter and concatenate checkbox value to the url string
            counter++;
            options = options + '<option value="'+input_obj[i].value+'">'+input_obj[i].getAttribute("data-asset-tag")+'</option>';
        }
    }
	$("#asset_id").html(options);
	//alert(options);

    // display url string or message if there is no checked checkboxes
    
}
function removeassets(){
    if (confirm(trans('messages.msg_contract_asset_delete'))) {

       /*var x = $('#asset_id').val();
       alert('hi');
       alert(x);*/

       return !$('#asset_id option:selected').remove();
    
  /* var x = document.getElementById("asset_id");
    x.remove(x.selectedIndex);*/
}
}

function contractrenew(contract_id){
    emLoader('show', 'Loading Contract');
    var id = contract_id.split('_')[1];
    //var contract_details_id = $("#contract_details_id").val();
    var postData = { 'datatype': 'json', 'id': id};
   // console.log(postData);
    var url = SITE_URL + '/contractrenew';
    $("#renewed").prop("disabled", true);
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        lightbox('show', data, trans('messages.msg_contract_renew'), 'full');
        emLoader('hide');
        datecalendar('from_date');
        datecalendar('to_date');
        $("input[name=contractid").val('');
        $("input[name=from_date").val('');
        $("input[name=to_date").val('');
        
    });
}

function contractrenewsubmit() {
    clearMsg('msg_popup');
	clearMsg('msg_div');
    emLoader('show', trans('messages.msg_contract_renew'));
    var url = SITE_URL + '/contractrenewsubmit';
    var postData = $("#addformcontract").serialize();
    //alert(postData);
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
         console.log(data);
        var result = JSON.parse(data);
       // console.log(result);
        if (result.is_error) {
            showResponse(data, '','msg_popup');
            emLoader('hide');
        }
        else {
            emLoader('hide');
            lightbox('hide');
            showResponse(data,'contract_detail','msg_div' );
            contractList();

        }
    });

}


function childcontract(contract_id) {
    emLoader('show', trans('label.lbl_contract'),'childcontract');
    var id = contract_id.split('_')[0];
    var postData = { 'datatype': 'json', 'contract_id': id};
    //console.log(postData);
    var url = SITE_URL + '/childcontract';

    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        console.log(data);
        showResponse( data, 'childcontract', 'msg_div');
        emLoader('hide');
        
    });
}

function renewcontractdeatils(id) {
    emLoader('show', trans('label.lbl_contract'),'renewal');
    var contract_id = id.split('_')[0];
    var primary_contract = id.split('_')[1];
    var postData = { 'datatype': 'json', 'contract_id': contract_id,'primary_contract': primary_contract};
    //console.log(postData);
    var url = SITE_URL + '/renewdetails';

    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        console.log(data);
        showResponse( data, 'renewal', 'msg_div');
        emLoader('hide');
        
    });
}

$(document).on("click","#associateCheckAll", function() { 
    if (this.checked){
        $(".associateChk").each(function(){
            this.checked=true;
        });
    }else{
        $(".associateChk").each(function(){
            this.checked=false;
        });
    }
});

/* Assets Checkbox : Check One by One code*/
$(document).on("click",".associateChk", function(){ 
    if ($(this).is(":checked")){
        var isAllChecked = 0;
        $(".associateChk").each(function(){
            if (!this.checked)
                isAllChecked = 1;
        });
        if (isAllChecked == 0){
            $("#associateCheckAll").prop("checked", true);
        }     
    } else {
        $("#associateCheckAll").prop("checked", false);
    }
});
function checkbox_associatechild() {
    clearMsg('msg_popup');
	clearMsg('msg_div');
    emLoader('show', trans('messages.msg_updating_contract'));
    
    var url = SITE_URL + '/contractupdateassociatechild';
    var postData = $("#associateform").serialize();
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
         console.log(data);
        var result = JSON.parse(data);
       // console.log(result);
        if (result.is_error) {
            showResponse(data, '','msg_popup');
            emLoader('hide');
        }
        else {
            emLoader('hide');
            lightbox('hide');
            showResponse(data,'contract_detail','msg_div' );
            contractList();

        }
    });
}  

function contractattachfile() {

    clearMsg('msg_popup');
    if (cltimer) {
        clas = 'error';
        showAlert("msg_popup", clas, trans('messages.msg_session_open'));
        return false;
    }
   
    emLoader('show', trans('label.lbl_contract'));
    var url = SITE_URL + '/add_attachment_contract';
    
    var postData = $("#attachfilecontract").serialize();
   
    console.log(postData);
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        var result = JSON.parse(data);
        console.log(result);
        if (result.is_error) {
            showResponse(data, '',  'msg_popup');
            emLoader('hide');
        }
        else {
            emLoader('hide');
            lightbox('hide');
            
            window.scrollTo(0, 0);
            showResponse(data, 'grid_data', 'msg_div');
           // contractList();
        }

    });
}

function sendmailsubmit() {

    clearMsg('msg_popup');
    if (cltimer) {
        clas = 'error';
        showAlert("msg_popup", clas, trans('messages.msg_session_open'));
        return false;
    }
    emLoader('show', trans('label.lbl_contract'));
    var url = SITE_URL + '/sendmail';
    var postData = $("#formsendmail").serialize();
    console.log(postData);
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
        var result = JSON.parse(data);
        console.log(result);
        if (result.is_error) {
            showResponse(data, '',  'msg_popup');
            emLoader('hide');
            lightbox('hide');
        }
        else {
            emLoader('hide');
            lightbox('hide');
            
            window.scrollTo(0, 0);
            showResponse(data, 'grid_data', 'msg_div');
            contractList();
        }

    });
}
function submitAction()
{
    emLoader('show', 'Loading Contract');

    closeMsgAuto('msg_div');
    closeMsgAuto('msg_modal');
    var url = '';

    if($('#notifyvendor:visible').length > 0){
		url = SITE_URL + '/notify_vendor_contract';
		err_div_id = "msg_modal_notifyvendor";
		formid = "formsendmail_notifyvendor";
	} 
    if($('#notifyowner:visible').length > 0){
		url = SITE_URL + '/notify_owner_contract';
		err_div_id = "msg_modal_notifyowner";
		formid = "formsendmail_notifyowner";
	} 
	closeMsgAuto(err_div_id);
    if(url != ''){
    var postData = $("#"+formid).serialize();
   console.log(postData);
    var prSubmitajax = ajaxCall(prSubmitajax, url, postData, function (data) {
        var result = JSON.parse(data);
        if (result.is_error) {
            showResponse(data, '', err_div_id);
            emLoader('hide');
            window.scrollTo(0, 0);
        }
        else {
			
            emLoader('hide');
            lightbox('hide');
            $('.modal').modal('hide');
            contractList();
            showResponse(data, 'grid_data', 'msg_div');           
            window.scrollTo(0, 0);
        }
    }); 
    }
}
function actionsPr(id)
{
    //alert(id);
    var action = id.split('_')[0];
    var user_id = id.split('_')[1];
    var contract_id = id.split('_')[2];    
    var notify_to_id = id.split('_')[3];    
    console.log(action);
    console.log(user_id);
    console.log(contract_id);
    console.log(notify_to_id);

    $('#'+action).modal('show'); 
// alert(action);
    $("#myModal_actions #contract_id").val(contract_id);          
    $("#myModal_actions #user_id").val(user_id);          
    $("#myModal_actions .action").val(action); 
   // $("#emailowner .action").val(action);
    $("#myModal_actions #notify_to_id").val(notify_to_id); 
}

function deleteAttachment(attach_id)
{
    if(confirm(trans('messages.msg_delattachmentconfirm')))
    {
        emLoader('show', trans('label.lbl_loading'));
        closeMsgAuto('msg_div');
        // closeMsgAuto('msg_modal');
        var contract_id = $('#contract_id').val();
      
        var url      = SITE_URL + '/delete_attachment_contract';
        var postData = { 'attach_id' : attach_id, 'contract_id':contract_id};
       
    
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
                contractList();
                showResponse(data, 'grid_data', 'msg_div');           
                window.scrollTo(0, 0);
            }
        }); 
    }
}

function contractassetlist(){
   
	closeMsgAuto('msg_div');
    emLoader('show', 'Loading Contract');
    var url = SITE_URL + '/contract/assetlist';
    var postData = $("#contractassetform").serialize();
   
        var userajax = ajaxCall(userajax, url, postData, function (data) {
            var result = JSON.parse(data);
			$('#modal_data').html(result.html);
            $('#myModalAsset').modal('show');
            emLoader('hide');
            initsingleselect();
            initmultiselect();
        });
	
}

