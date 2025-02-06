$(document).ready(function() 
{



    credentialList();
    $(document).on("click",".crelist", function() { credentialtemplatetypes();});
    $(document).on("click",".crtype	", function() { getCredentialTemplateByType(this.id);});
    $(document).on("click","#templatecredentialsubmit", function() { templatecredentialAddUpdate();});
    $(document).on("click",".credential_edit", function() { 
        var id = $(this).data('id');
        var title = $(this).data('title')
        credentialedit(id, title);
    });	  
    $(document).on("click",".credential_delete", function() { 
        var id = $(this).data('id')
        credentialdelete(id);
    });  
 
    //$(document).on("click","#credentialaddsubmit_reset", function() { resettemplateForm();});
}); 
/* return separate Reset Function As Form Builder Can't use <Form>*/
function resettemplateForm()
{
    $("#credentialform").find("input[type=text], input[type=password], input[type=number], input[type=checkbox], textarea, select").each(function () { $(this).val(''); });
}
 
function credentialtemplatetypes()
{
   	closeMsgBox('msg_div');
	emLoader('show', 'Credential Type List');
	var url = SITE_URL+'/credentialtemplatetypes';
//	var postData ={'objectid' : objectid};
	var notifyajax = ajaxCall(notifyajax, url, {}, function(data)
	{
		lightbox('show', data, 'Credential Type List', 'large');
		emLoader('hide');
    });

}

function templatecredentialAddUpdate()
{
    var action = $("input[name='action']").val();
    //alert(action);
    closeMsgBox('msg_div');
	if(cltimer)
	{
		clas = 'error';
		showAlert("msg_div",clas,"Session is already open.");
		return false;    
    }

   
   
    //var postData = $("#configtemplateform").serialize();
    var postData =   jQuery('#credentialform').serializeArray(); 
    var object  = {};
    for (var i = 0; i < postData.length; i++) {
        object[postData[i].name] = postData[i].value;
    }
    var postDataObj =  JSON.stringify(object);
   // console.log(postData);

    if(action == "add")
    {
        emLoader('show', 'Template Credential Save');
        var url = SITE_URL+'/templatecredentialadd';
        var postDataConfig = {
            details: postDataObj,
            form_templ_type: 'default',     
            form_templ_id : $("#form_templ_id").val(),
            urlpath : 'cr',
    };
    }
    else if(action == 'edit')
    {
        emLoader('show', 'Template Credential Update');
        var url = SITE_URL+'/templatecredentialupdate';
        var postDataConfig = {
            details: postDataObj,
            form_templ_type: 'default',     
            form_templ_id : $("#form_templ_id").val(),
            config_id : $("#config_id").val(),
            urlpath : 'cr',
    };
    }
	var rdpconnectsajax = ajaxCall(rdpconnectsajax,url,postDataConfig,function(data)
	{	
            var result = JSON.parse(data);
            if(result.is_error)
            {
                showResponse(data, '','msg_popup');
                emLoader('hide');
            }
            else
            {
               emLoader('hide');
               lightbox('hide');               
               credentialList();
               showResponse(data, 'grid_data', 'msg_div' );
            }
            window.scrollTo(0, 0);
    });  
}

function credentialList()
{
    
    closeMsgAuto('msg_div');
	emLoader('show', 'Loading Credentials');
	var url = SITE_URL+'/credentials/list';
	var postData = $("#frmdevices").serialize();
	var exporttype = $("#frmdevices input[name=exporttype]").val();
	if (exporttype == 'pdf' || exporttype == 'csv' || exporttype == 'print')
	{
		var obj_form = document.frmdevices;
		var mywindow = submitForm(url,obj_form,1,1);	
		$("#frmdevices input[name=exporttype]").val('');
		$("#frmdevices input[name=page]").val('');
		emLoader('hide');
	}
	else
	{
		var mongraphsajax = ajaxCall(mongraphsajax,url,postData,function(data)
		{
			showResponse(data, 'grid_data', 'msg_div' );
			emLoader('hide');
		});	
	}
}
function getCredentialTemplateByType(template_name)
{ 
    closeMsgBox('msg_div');  
    if(template_name)
    {
        var url = SITE_URL+'/getCredentialtemplatebytype';
        var postData ={'template_name' : template_name};
        emLoader('show', 'Template Rendering');
        var creTemplateajax = ajaxCall(creTemplateajax, url, postData, function(data)
		{
            lightbox('show', data, 'Credential Template: '+template_name, 'large');
            $("#build-form").hide();
            $(".render-btn-wrap").hide();
          
            if(jsonDataAsString!="")
            {
                setDataedit();
            }
            else
            {
                alert("Invalid Template.");
            }

			emLoader('hide');
		});	
    }
}
function credentialedit(id, template_name)
{
    closeMsgBox('msg_div');
	emLoader('show', 'Credential Edit Template Rendering');
	var url = SITE_URL+'/credentialedit';
    var postData ={'config_id' : id, 'template_name' : template_name};
	var notifyajax = ajaxCall(notifyajax, url, postData, function(data)
	{
        //alert(data);
        lightbox('show', data, 'Credential Template Edit', 'large');
        $("#build-form").hide();
        $(".render-btn-wrap").hide();
      
        if(jsonDataAsString!="")
        {
            setDataedit();
        }
        else
        {
            alert("Invalid Template.");
        }
		emLoader('hide');
    });
}
function credentialdelete(id)
{
    if(confirm("Are you sure you want to delete Setting Template ?"))
    {
        closeMsgBox('msg_div');
        emLoader('show', 'Deleting Setting Template');
        var url = SITE_URL+'/credentialdelete';
        var postData ={'config_id' : id, 'status': 'd'};
        var notifyajax = ajaxCall(notifyajax, url, postData, function(data)
        {
            var result = JSON.parse(data);
		
            if(result.is_error)
            {	
                showResponse(data, 'msg_div');
                emLoader('hide');
            }
            else
            {
               emLoader('hide'); 
               showResponse(data,  'msg_div' );
               credentialList();
            }
        });

    }
}
function setDataedit(){
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
        svgSprite: "<?php echo config('app.site_url'); ?>/enlight/scripts/formeo-master/img/formeo-sprite.svg",
        // debug: true,
        sessionStorage: false,
        editPanelOrder: ['attrs', 'options']
    };
    formeo = new window.Formeo(formeoOpts, jsonDataAsString);
    //templateDisplay();
     setTimeout(
    function()
    {
        templateDisplay();
    }, 1000);

};
function  templateDisplay() {
    console.log("In render");
    var renderContainer = document.querySelector('.render-form');
        formeo.render(renderContainer);
        $(".render-form").show();
       
        if(jsonConfig)
        {
            var jsonConfigData =  JSON.parse(jsonConfig);
            // var joForm = document.getElementsByTagName("form")[0];
            var joForm =  document.querySelector("#credentialform");
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