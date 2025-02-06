<header id="topbar" class="affix">
<div class="topbar-left">
    <?php breadcrum("Assets");?>
   
</div>
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
</header>
<div id="content">
      <input type="hidden" name="url_asset_id" id="url_asset_id" value="<?php echo $asset_id?>">
      <input type="hidden" name="url_ci_templ_id" id="url_ci_templ_id" value="<?php echo $ci_templ_id?>">
      <input type="hidden" name="url_title" id="url_title" value="<?php echo $title?>">
      <input type="hidden" name="url_ci_type_id" id="url_ci_type_id" value="<?php echo $ci_type_id?>">
      <input type="hidden" name="url_po_id" id="url_po_id" value="<?php echo $po_id?>">
      <input type="hidden" name="url_asset_sku" id="url_asset_sku" value="<?php echo isset($asset_sku)? $asset_sku : ""?>">
  <div class="row">
    <div class="col-md-12">
      <div class="alert hidden alert-dismissable" id="msg_div"></div>
    </div>
    <div class="col-md-12">
      <form method="prst" name="assettrackingfrm" id="assettrackingfrm">    
          <div class="panel">
                <?php echo csrf_field(); ?> 
                <?php echo isset($emgridtop) ? $emgridtop : ''; ?>                
          </div>
        </form>
    </div>
    <div class="col-md-12">

      <!--<form method="post" name="frmusers" id="frmusers">-->
          <div class="">
                <?php //echo csrf_field(); ?>
        <?php //echo isset($emgridtop) ? $emgridtop : ''; ?>
            <div class="row" id="grid_data">
              <div class="col-md-3" id="comtree"></div>
              <div class="col-md-9" id="datatree"></div>
            </div>
          </div>
       <!-- </form> -->
    </div>
  </div>
</div>
</div>
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/common.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/asset/assets.js"></script>
<!-- Latest compiled and minified CSS -->

 <!-- Fancytree CSS -->
<link rel="stylesheet" type="text/css" href="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/fancytree/skin-win8/ui.fancytree.min.css">

<!-- Fancytree Plugin -->
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/fancytree/jquery.fancytree-all.min.js"></script>

<!-- Fancytree Addons - Childcounter -->
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/fancytree/extensions/jquery.fancytree.childcounter.js"></script>

<!-- Fancytree Addons - Childcounter -->
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/fancytree/extensions/jquery.fancytree.columnview.js"></script>

<!-- Fancytree Addons - Drag and Drop -->
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/fancytree/extensions/jquery.fancytree.dnd.js"></script>

<!-- Fancytree Addons - Inline Edit -->
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/fancytree/extensions/jquery.fancytree.edit.js"></script>

<!-- Fancytree Addons - Inline Edit -->
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/fancytree/extensions/jquery.fancytree.filter.js"></script>
