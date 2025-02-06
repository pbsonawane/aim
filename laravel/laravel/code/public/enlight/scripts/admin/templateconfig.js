/*$(document).ready(function() 
{
    $(document).on("click","#templateconfigsubmit", function() { formtemplatedefaultupdate();});
    
}); */
$(document).ready(function()
{    
    emLoader('show', 'Rendering');
    $(document).on("click","#templateconfigsubmit", function() { formtemplatedefaultupdate();});
   /* var jsonDataAsString = '<?php echo isset($form_templ_data['details']) ? $form_templ_data['details'] : ""; ?>';
    var jsonConfig = '<?php echo isset($form_templ_configdata['details']) ? $form_templ_configdata['details'] : ""; ?>';*/
    if(jsonDataAsString!="")
    {        
        setDataedit(jsonDataAsString, jsonConfig="");
        setTimeout(function()
        {
            var configpage = $("#urlpath").val();
            if( configpage == "adconfig" )
            {
                adconfig();
            }
        }, 100);
              
    }
    var configpage = $("#urlpath").val();

      
    $('.render-form').on('change','select, input', function(event) {
   
        var configpage = $("#urlpath").val();
        if( configpage == "mailserversetting" )
        {

           mailserversetting(event);
        } 
        /*alert('select');
        if(configpage == "rebranding" )
        {
            alert('select5256');
            rebranding(event);
        }*/
    });
    
});
function formtemplatedefaultupdate()
{
    closeMsgBox('msg_div');
	if(cltimer)
	{
		clas = 'error';
		showAlert("msg_div",clas,"Session is already open.");
		return false;    
    }
    emLoader('show', 'Template Config Update');
    var url = SITE_URL+'/formdataconfigupdate';
    //var postData = $("#configtemplateform").serialize();
    var postData =   jQuery('#configtemplateform').serializeArray(); 
    var object  = {};
    for (var i = 0; i < postData.length; i++) {
        console.log(postData[i].name);
        object[postData[i].name] = postData[i].value;
    }
    var postDataObj =  JSON.stringify(object);
   // console.log(postData);
    var postDataConfig = {
            details: postDataObj,
            form_templ_type: 'default',     
            form_templ_id : $("#form_templ_id").val(),
            urlpath : $("#urlpath").val(),
    };
    
	var rdpconnectsajax = ajaxCall(rdpconnectsajax,url,postDataConfig,function(data)
	{	
        var result = JSON.parse(data);
            showResponse(data, 'msg_div');
            emLoader('hide');
            window.scrollTo(0, 0);
    });  
}
function setDataedit(jsonDataAsString, jsonConfig)
{
    closeMsgAuto('msg_div');

    console.log("Rendering...");
   let container = document.querySelector('#build-form');
   var renderContainer = document.querySelector('.render-form');

 var  formeoOpts = {
   container: container,
   i18n: {
     preloaded: {
       'en-US': {'row.makeInputGroup': ' Repeatable Region'}
     }
   },
   allowEdit: true,
   controls: {
     sortable: false,
     groupOrder: [
       'common',
       'html',
     ],
     elements: [
     ],
     elementOrder: {
       common: [
       'button',
       'checkbox',
       'date-input',
       'hidden',
       'upload',
       'number',
       'radio',
       'select',
       'text-input',
       'textarea',
       ]
     }
   },
   events: {
     // onUpdate: console.log,
     // onSave: console.log
   },
   svgSprite: SITE_URL+"/enlight/scripts/formeo-master/img/formeo-sprite.svg",
   // debug: true,
   sessionStorage: false,
   editPanelOrder: ['attrs', 'options']
 };
 formeo = new window.Formeo(formeoOpts, jsonDataAsString);
 //templateDisplay();
 setTimeout(
 function()
 {
   templateDisplay(jsonConfig);
   emLoader('hide');  
 }, 100);

};
function  templateDisplay(jsonConfig) {
   //console.log("In render");
   var renderContainer = document.querySelector('.render-form');
       formeo.render(renderContainer);
       $(".render-form").show();
       
       if(jsonConfig)
       {
           var jsonConfigData =  JSON.parse(jsonConfig);
           var joForm = document.getElementsByTagName("form")[0];
           for (var i = 0; i < joForm.elements.length; i++) 
           {
               var elementname = joForm.elements[i].name;
               var elementTagName = joForm.elements[i].tagName.toLowerCase();
               
               if(elementname !=""){
                   if(elementTagName == "select"){
                       $("select[name="+elementname+"]").val(jsonConfigData[elementname]);
                   }
                   else //if(elementname == "input")
                   {
                       console.log(elementname);
                       $("input[name="+elementname+"]").val(jsonConfigData[elementname]);
                   } 
               }
           } 
       }
 }

function mailserversetting(event)
{
  console.log(event);
       /* For Mail server Page */
       console.log(event.target.name);
       if(event.target.name == "smtp_authentication")
       {
          // alert(event.target.value);
         if(event.target.value == "true")
         {   
               var secure_connectionlabelid =  $("select[name='secure_connection']").attr('id');
               var secure_connectionlabel = secure_connectionlabelid.split(/-(.+)/)[1];
               $("select[name='secure_connection']").show();
               $('label[for="'+secure_connectionlabel+'"]').show();
   
               var ssl_enablelabelid =  $("select[name='ssl_enable']").attr('id');
               var ssl_enablelabel = ssl_enablelabelid.split(/-(.+)/)[1];
               $("select[name='ssl_enable']").show();
               $('label[for="'+ssl_enablelabel+'"]').show();
   
               var certificate_filelabelid =  $("input[name='certificate_file']").attr('id');
               var certificate_filelabel = certificate_filelabelid.split(/-(.+)/)[1];
               $("input[name='certificate_file']").show();
               $('label[for="'+certificate_filelabel+'"]').show();
               
               var keypasswordlabelid =  $("input[name='keypassword']").attr('id');
               var keypasswordlabel = keypasswordlabelid.split(/-(.+)/)[1];
               $("input[name='keypassword']").show();
               $('label[for="'+keypasswordlabel+'"]').show();              
           }
           else{                
               var secure_connectionlabelid =  $("select[name='secure_connection']").attr('id');
               var secure_connectionlabel = secure_connectionlabelid.split(/-(.+)/)[1];
               $("select[name='secure_connection']").hide();
               $('label[for="'+secure_connectionlabel+'"]').hide();
   
               var ssl_enablelabelid =  $("select[name='ssl_enable']").attr('id');
               var ssl_enablelabel = ssl_enablelabelid.split(/-(.+)/)[1];
               $("select[name='ssl_enable']").hide();
               $('label[for="'+ssl_enablelabel+'"]').hide();
   
               var certificate_filelabelid =  $("input[name='certificate_file']").attr('id');
               var certificate_filelabel = certificate_filelabelid.split(/-(.+)/)[1];
               $("input[name='certificate_file']").hide();
               $('label[for="'+certificate_filelabel+'"]').hide();
               
               var keypasswordlabelid =  $("input[name='keypassword']").attr('id');
               var keypasswordlabel = keypasswordlabelid.split(/-(.+)/)[1];
               $("input[name='keypassword']").hide();
               $('label[for="'+keypasswordlabel+'"]').hide();
           }
       }
       if(event.target.name == "secure_connection")
       {
         if(event.target.value == "ssl_enables")
         {       
             var ssl_enablelabelid =  $("select[name='ssl_enable']").attr('id');
             var ssl_enablelabel = ssl_enablelabelid.split(/-(.+)/)[1];
             $("select[name='ssl_enable']").show();
             $('label[for="'+ssl_enablelabel+'"]').show();
   
             var keypasswordlabelid =  $("input[name='keypassword']").attr('id');
             var keypasswordlabel = keypasswordlabelid.split(/-(.+)/)[1];
             $("input[name='keypassword']").show();
             $('label[for="'+keypasswordlabel+'"]').show();
   
             var certificate_filelabelid =  $("input[name='certificate_file']").attr('id');
             var certificate_filelabel = certificate_filelabelid.split(/-(.+)/)[1];
             $("input[name='certificate_file']").show();
             $('label[for="'+certificate_filelabel+'"]').show();            
         }
         else{
           var ssl_enablelabelid =  $("select[name='ssl_enable']").attr('id');
           var ssl_enablelabel = ssl_enablelabelid.split(/-(.+)/)[1];
           $("select[name='ssl_enable']").hide();
           $('label[for="'+ssl_enablelabel+'"]').hide();
   
           var keypasswordlabelid =  $("input[name='keypassword']").attr('id');
           var keypasswordlabel = keypasswordlabelid.split(/-(.+)/)[1];
           $("input[name='keypassword']").hide();
           $('label[for="'+keypasswordlabel+'"]').hide();
   
           var certificate_filelabelid =  $("input[name='certificate_file']").attr('id');
           var certificate_filelabel = certificate_filelabelid.split(/-(.+)/)[1];
           $("input[name='certificate_file']").hide();
           $('label[for="'+certificate_filelabel+'"]').hide(); 
         }
   
        // $('label[for="'+servernamelabel+'"]').parent().next(".text-error").hide();
       }         
       if(event.target.name == "ssl_enable")
       {
           if(event.target.value == "ssl_pass")
           {     
                     
               var keypasswordlabelid =  $("input[name='keypassword']").attr('id');
               var keypasswordlabel = keypasswordlabelid.split(/-(.+)/)[1];
               $("input[name='keypassword']").show();
               $('label[for="'+keypasswordlabel+'"]').show();
       
               var certificate_filelabelid =  $("input[name='certificate_file']").attr('id');
               var certificate_filelabel = certificate_filelabelid.split(/-(.+)/)[1];
               $("input[name='certificate_file']").hide();
               $('label[for="'+certificate_filelabel+'"]').hide();      
           }
           else{
               var keypasswordlabelid =  $("input[name='keypassword']").attr('id');
               var keypasswordlabel = keypasswordlabelid.split(/-(.+)/)[1];
               $("input[name='keypassword']").hide();
               $('label[for="'+keypasswordlabel+'"]').hide();      
       
               var certificate_filelabelid =  $("input[name='certificate_file']").attr('id');
               var certificate_filelabel = certificate_filelabelid.split(/-(.+)/)[1];
               $("input[name='certificate_file']").show();
               $('label[for="'+certificate_filelabel+'"]').show();
           }
   
        // $('label[for="'+servernamelabel+'"]').parent().next(".text-error").hide();
       } 
             /* ---File Upload Code ----------*/   
       if(event.target.files != undefined){
          console.log('')
          // var selectedFile = event.target.files[0];
          // var selectedFile = $("input[name='certificate_file']").prop("files")[0];
           //console.log(selectedFile);
           //console.log(event.target.files);        
      /*     var formData = new FormData();
           var url = SITE_URL+'/ajaxUploadImage';	
           formData.append(event.target.name, $('input[type=file]')[0].files[0]);
           var postData = formData;*/

           var url = SITE_URL+'/ajaxUploadImage';
           var formData = new FormData();
           formData.append('image', $('input[type=file]')[0].files[0]);
alert($('meta[name="csrf-token"]').attr('content'));
console.log(formData);
          /* $.ajax({
                   type: 'POST',
                   url: url,
                   headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   },
                   data: formData,
                   processData: false,
                   contentType: false,
                   success: function (data) {
                       console.log(data);
                   },
                   error: function(data) {
                       console.error(data);
                   }
               });*/
               $.ajax({
                   url: url,
                   headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   },
                   data:{
                       image: new FormData($("#1fc0c0c6-b489-4b30-9153-02328ab3b8f8")[0]),
                       },
                   dataType:'json',
                   async:false,
                   type:'post',
                   processData: false,
                   contentType: false,
                   success:function(response){
                       console.log(response);
                   },
               });



          /* var rdpconnectsajax = ajaxCall(rdpconnectsajax,url,postData,function(data)
           {	
               var result = JSON.parse(data);
           // console.log(result);
               //console.log(result.message.error);
               if(result.is_error)
               {
                   showResponse(data, 'msg_popup');
                   emLoader('hide');
                   window.scrollTo(0, 0);
               }
               else{            
                   emLoader('hide');
                   lightbox('hide');
                   settingtemplateList();
                   showResponse(data,  'msg_div' );
                   window.scrollTo(0, 0);
               
               }
           });*/

           /*formData.append('certificate_file', this.selectedFile, this.selectedFile.name);
           this.iamService.uploadfile(formData).subscribe(data => {
             this.response = <string> data['status'];
             if(this.response=="success")
             { 
                 this.uploadedile = data['data'];         
             }
         })*/
       }
}
/*
function rebranding(event){
alert('rebranding');

   if(event.target.files != undefined){
          alert(event.target.files); 
           var formData = new FormData();
           var url = SITE_URL+'/ajaxUploadImage';	
           console.log(url);
           formData.append('product_logo', $('input[type=file]')[0].files[0]);  
           var postData = formData;
           alert(postData);
           $.ajax({
               url : url,
               type: "POST",
               data : postData,
               processData: false,
               dataType:'JSON',
               //contentType: false,
               cache: false,
               enctype: 'multipart/form-data',
               //contentType: false,
               success:function(data, textStatus, jqXHR){
                   alert('sucess');
                  alert(data);
               },
               error: function(jqXHR, textStatus, errorThrown){
                   //if fails     
               }
           });
           
   }     
}*/
function adconfig(){
           $("input[name='ad_server']").after("<div class='clearfix'></div><div class='small text-muted'>eg. 193.168.45.5</div>");

           $("input[name='admin_User']").after("<div class='clearfix'></div><div class='small text-muted'>User with higher rights. eg.Manager/Adminstrator</div>");                       
           $("input[name='password']").after("<div class='clearfix'></div><div class='small text-muted'>Administrator password</div>");

           $("input[name='root_dn']").after("<div class='clearfix'></div><div class='small text-muted'>eg.DC=example,DC=com (for LDAP) AND eg. @example.com (for AD)</div>");

           $("select[name='useSSL']").after("<div class='clearfix'></div><div class='small text-muted'>Set to Yes, if SSL is enabled on the server</div>");

           $("select[name='useTLS']").after("<div class='clearfix'></div><div class='small text-muted'>For this to enable set useSSL to NO</div>");

           $("input[name='base_dn']").after("<div class='clearfix'></div><div class='small text-muted'>eg. CN=Users, DC=example, DC=com</div>");

  
}

