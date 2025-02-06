<!-- Start: Topbar -->
<header id="topbar" class="affix"  >
<div class="topbar-left">
   <?php breadcrum('Email Templates'); ?>
</div>
<div class="topbar-right">
@if(canuser('create','emailtemplate'))
  <div class="btn-group">
    <button id="timeline-toggle" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
    <span class="glyphicons glyphicons-show_lines fs16"></span>
    </button>
     <ul class="dropdown-menu pull-right" role="menu">
      <li class="emailtemplateadd" title="Email Template Add">
            <a id="emailtemplateaddnew"><span title="Email Template Add" class="emailtemplateadd"><?php echo trans('label.lbl_add_email_template');?></span></a>
      </li>
    </ul>
  </div>
  @endif
</div>
</header>
<!-- End: Topbar -->
<div id="content">
  <div class="row">
    <div class="col-md-12">
      <div class="alert hidden alert-dismissable" id="msg_div"></div>
    </div>
    <div class="col-md-12">
      <form method="post" name="frmdevices" id="frmdevices">    
          <div class="panel">
        <?php echo csrf_field(); ?> 
        <?php echo isset($emgridtop) ? $emgridtop : ''; ?>
            <div class="panel panel-visible" id="grid_data"></div>
          </div>
        </form>
    </div>
  </div>
</div>
</div>
<script src="ckeditor/ckeditor.js"></script>
<script language="javascript" type="text/javascript" src="enlight/scripts/common.js"></script> 
<script language="javascript" type="text/javascript" src="enlight/scripts/emailtemplate/emailtemplate.js"></script>
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>