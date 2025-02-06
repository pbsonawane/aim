<!--<script language="javascript" type="text/javascript" src="<?php //echo config('app.site_url'); ?>/enlight/scripts/formeo-master/css/demo.css"></script>
 Start: Topbar -->
<header id="topbar" class="affix"  >
<div class="topbar-left">
    <ol class="breadcrumb">
        <li class="crumb-active"><a class="nounderline">Settings Template</a></li>
        <li class="crumb-link">Config</li>
        <li class="crumb-trail"><?php echo isset($templatetitle) ? $templatetitle : ''; ?></li>
    </ol>
</div>
</header>
<!-- End: Topbar -->
<div id="content">
    <div class="row">
        <div class="col-md-12">
            <div class="alert hidden alert-dismissable" id="msg_div"></div>
        </div>
        <div class="col-md-12">
            <!-- Keep all input Elements in this section only above the form tag -->
            <input id="form_templ_id" name="form_templ_id" type="hidden" value="<?php echo $form_templ_id; ?>">
            <input id="urlpath" name="urlpath" type="hidden" value="<?php echo $urlpath; ?>">
            <div class="panel">
                    <?php echo csrf_field(); ?>
                    <?php echo isset($emgridtop) ? $emgridtop : ''; ?>
                    <div class="panel panel-visible" id="grid_data"></div>



            <!--<form method="post" name="configtemplateform" id="configtemplateform" enctype="multipart/form-data">-->

                <div class="panel-body" >
                    <div class="form-group required clearfix">
                        <section id="main_content" class="inner">
                            <form style="display:none" id="build-form" class="build-form clearfix"></form>
                            <form method="post" name="configtemplateform" id="configtemplateform" enctype="multipart/form-data">
                                <div class="render-form"></div>
                                <div class="form-group">
                                    <div class="col-xs-2">
                                        <button id="templateconfigsubmit" type="button" class="btn btn-success btn-block">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </section>
                        <div class="render-btn-wrap" style="display:none">
                                <button id="renderForm" class="btn btn-outline-primary">Preview Form</button>
                                <button id="viewData" class="btn btn-outline-success">Generate JSON Data</button>
                                <button id="reloadBtn" class="btn btn-outline-danger">Reset Editor</button>
                                </div>
                                <!--<textarea disabled="" style="display:none" class="form-control input-sm details" placeholder="" name="details" id="details"><?php //echo isset($form_templ_data['details']) ? $form_templ_data['details'] : ""; ?></textarea>-->

                        </div>
                    </div>
                </div>
                <!--</form>-->
            </div><!-- End Panel -->
        </div>
    </div>
</div>
<?php
$jsonDataAsString = isset($form_templ_data['details']) ? $form_templ_data['details'] : "";
$jsonConfig = isset($form_templ_configdata['details']) ? $form_templ_configdata['details'] : "";
?>
<script type="text/javascript">var jsonDataAsString = '<?=$jsonDataAsString?>';</script>
<script type="text/javascript">var jsonConfig = '<?=$jsonConfig?>';</script>

<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/formeo-master/formeo.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/formeo-master/js/demo.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/common.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/admin/templateconfig.js"></script>

<style>
.panel {
    background-color: #FFF;
    margin-bottom: 0px;
}
    </style>

