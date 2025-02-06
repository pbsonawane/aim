<div class="panel">
    <div class="panel-heading">
        <span class="panel-title"><?php echo $title ?> <?php echo trans('label.lbl_list') ?></span>
        <div class="topbar-right">
        <?php if(canuser('create','asset')) { ?>
        <div class="btn-group">
          <button id="timeline-toggle" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
          <span class="glyphicons glyphicons-show_lines fs16"></span>
          </button>
          <ul class="dropdown-menu pull-right" user="menu">
            <?php if(canuser('create','asset')) { ?>
            <li id="assetadd">
              <a><span title="User Add" class="assetadd"><?php echo trans('label.lbl_add') ?> <?php echo $title ;?></span></a>
            </li>
            <?php }?>
          </ul>
        </div>
        <?php }?>
      </div>
    </div>
    <div class="panel-body">
      <form method="post" name="assetfrm" id="assetfrm">
          <div class="row">
              <input type="hidden" name="ci_templ_id" id="ci_templ_id" value="<?php echo $ci_templ_id ?>" />
              <input type="hidden" name="ci_type_id" id="ci_type_id" value="<?php echo $ci_type_id ?>" />
              <input type="hidden" name="title" id="title" value="<?php echo $title ?>" />
              <?php echo csrf_field();?>
              <?php echo isset($emgridtop) ? $emgridtop : ''; ?>
              <div class=" panel-visible" id="assetdata"></div>
          </div>
      </form> 
    </div>
</div>

