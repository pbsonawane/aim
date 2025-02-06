<header id="topbar" class="affix">
  <div class="topbar-left">
    <?php breadcrum(trans('title.asset_import'));?>
    <!--<div class="topbar-right">
    <div class="btn-group">
      <button id="timeline-toggle" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
      <span class="glyphicons glyphicons-show_lines fs16"></span>
      </button>
      <ul class="dropdown-menu pull-right" user="menu">
        <li id="useradd">
          <a><span title="User Add" class="useradd">Add User</span></a>
        </li>
        <li id="displaysetting">
          <a><span title="Configure user list columns" class="displaysetting">Column Setting</span></a>
        </li>
      </ul>
    </div>
  </div>-->
  </div>
  <style>
  .popover-content{height:auto!important;}
  </style>
</header>
<div id="content">
  <div class="panel">
   <div class="panel-heading">
        <span class="panel-title"><?php echo trans('label.lbl_import')?> </span>
    </div>   
  <div class="col-md-12" style="z-index: 1;">
      <div class="hidden alert-dismissable" id="msg_popup"></div>
  </div>
  <div class="panel">
        <div class="panel-body">
          <div id='frmimport_alert'></div>
          <form id="frmimport" name="frmimport" method="post" action="/importfile" enctype="multipart/form-data">
            <input type="hidden" name="filenm" id="filenm" value="<?php echo time();?>">
          <input type="hidden" name="ci_type_id" id="ci_type_id" value="">
          <input type="hidden" name="cititle" id="cititle" value="">   
        <div class="form-group col-md-12 required" >
        <label for="email" class="col-md-3 control-label"><?php echo trans('label.lbl_ci');?></label>
        <div class="col-md-4">
            <select name="ci_templ_id" id="ci_templ_id" onchange="onchangeci()"  class="chosen-select">
              <option rel="" value=""><?php echo trans('label.lbl_selectcitypes')?></option>
                    <?php 
                      if(is_array($citemplates) && count($citemplates) > 0)
                      {
                        foreach($citemplates as $citemp)
                        {
                          if(is_array($citemp['children']) && count($citemp['children']) > 0)
                          {
                            foreach($citemp['children'] as $ci)
                      { ?>

                          <option  txt = "<?php echo $ci['title']?>" rel="<?php echo $ci['ci_type_id']?>" value="<?php echo $ci['ci_templ_id']; ?>"><?php echo $ci['title']; ?></option>
                               <?php }
                          }

                          }  
                      }
                    ?>
                  </select>
            </div>
          </div> 

          <div class="form-group required col-md-12">
              <label for="Title" class="col-md-3 control-label">
                <i class="fa fa-question-circle" aria-hidden="true"  data-container="body" data-toggle="popover" data-placement="right" data-html="true" data-content="
                <ul>
                <li><?php echo showmessage('msg_only_csv_allowed');?></li>
                <li><?php echo showmessage('msg_max_allowed_size', array('{name}'), array('2 MB'), true);?></li>
                <li><?php echo showmessage('msg_std_value_bvlocven');?></li>
                </ul>
                "></i>
                <?php echo trans('label.lbl_csvfile');?>
              </label>
              <div class="col-md-8">
                  <input name="file" id="file" type="file" accept=".csv"/>   
              </div>
          </div>
      
          <div class="form-group col-md-12">
            <label class="col-md-3 control-label"></label>
              <div class="col-xs-2">
                <button id="file_upload" name ="file_upload" type="submit" class="btn btn-success btn-block"><?php echo trans('label.btn_submit');?></button>
              </div>
              <div class="col-xs-2">
                <button onclick="location.reload(true)" type="button" class="btn btn-info btn-block"><?php echo trans('label.btn_reset');?></button>
              </div>
          </div>
          </form>
          <div id="importdata"></div>
       </div>        
  </div>
</div>
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/common.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/asset/import.js"></script>
