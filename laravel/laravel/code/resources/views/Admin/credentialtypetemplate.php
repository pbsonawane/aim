
<link rel="stylesheet" type="text/css" href="enlight/scripts/formeo-master/css/demo.css">

<div class="row" id="credentialTypeform">
    <div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div>
    <div class="col-md-12">
	    <div class="panel">
		    <div class="panel-body">   
                <div id="form-builder">
                        <div class="form-group required clearfix">                
                            <section id="main_content" class="inner">
                                <form id="build-form" class="build-form clearfix"></form>
                                <input id="action" name="action" type="hidden" value="<?php echo $action; ?>">
                                <input type="hidden" id="form_templ_id" name="form_templ_id" value="<?php echo $form_templ_data['form_templ_id']; ?>">
                                <input type="hidden" id="config_id" name="config_id" value="<?php echo isset($form_templ_creditdata['config_id']) ? $form_templ_creditdata['config_id'] : ""; ?>">
                                <form id="credentialform">
                                    <div class="render-form"></div>
                                </form>
                            </section>
                            <div class="render-btn-wrap" >
                                <button id="renderForm" class="btn btn-outline-primary" >Preview Form</button>
                                <button id="viewData" class="btn btn-outline-success">Generate JSON Data</button>
                                <button id="reloadBtn" class="btn btn-outline-danger">Reset Editor</button>
                            </div>
                        </div>
                        <div class="form-group">
                                <div class="col-xs-2">
                                    <button id="templatecredentialsubmit" type="button" class="btn btn-success btn-block"><?php echo $action=="edit" ? "Update" : "Save"; ?></button>
                                </div>
                        </div>
                    
                </div>
            </div>
        </div>
    </div>
</div> <!-- row-->

<?php
$jsonDataAsString = isset($form_templ_data['details']) ? $form_templ_data['details'] : "";
$jsonConfig = isset($form_templ_creditdata['details']) ? $form_templ_creditdata['details'] : "";
?>
<script type="text/javascript">var jsonDataAsString = '<?=$jsonDataAsString?>';</script>
<script type="text/javascript">var jsonConfig = '<?=$jsonConfig?>';</script>

<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/formeo-master/formeo.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/formeo-master/js/demo.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/common.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/admin/templateconfig.js"></script>
<!--
    <script language="javascript" type="text/javascript" src="<?php //echo config('app.site_url'); ?>/enlight/scripts/formeo-master/formeo.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php //echo config('app.site_url'); ?>/enlight/scripts/formeo-master/js/demo.js"></script>
<script>
    var jsonDataAsString = '<?php //echo isset($form_templ_data['details']) ? $form_templ_data['details'] : ""; ?>';
    var jsonConfig = '<?php //echo isset($form_templ_creditdata['details']) ? $form_templ_creditdata['details'] : ""; ?>';
    $(document).ready(function()
    {
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
        
    });
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

</script>-->