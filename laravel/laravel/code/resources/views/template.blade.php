<!DOCTYPE html>
<html><head>
<meta charset="utf-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>AIM - <?php echo $pageTitle != '' ? $pageTitle : 'AIM - Asset Inventory Manager';?></title><!-- eNlight 360 - -->
<!-- Dropzone CSS -->
<link rel="stylesheet" href="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/dropzone/downloads/css/dropzone.css">
<!-- Theme CSS -->
<link rel="stylesheet" type="text/css" href="<?php echo config('app.site_url'); ?>/bootstrap/assets/skin/default_skin/css/theme.css">
<link rel="stylesheet" type="text/css" href="<?php echo config('app.site_url'); ?>/bootstrap/assets/admin-tools/admin-plugins/admin-panels/adminpanels.css">
<!-- jQuery -->
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/vendor/jquery/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/js/jquery.blockui.min.js"></script>
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/js/ajax.js"></script>
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/js/jquery.form.js"></script>
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/vendor/jquery/jquery_ui/jquery-ui.min.js"></script>
<!-- js for localization -->
<script>
  <?php
    $langjs = "";
    $langjs = file_get_contents(URL::to('/js/lang'));
  ?>
  window.lang_trans_js = <?php echo $langjs;?>;
</script>
<script src="<?php echo config('app.site_url'); ?>/enlight/scripts/language.js"></script>
<!-- Dropzone JS -->
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/assets/js/utility/utility.js"></script>
<!-- data range css start -->
<link rel="stylesheet" type="text/css" href="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/ladda/ladda.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/datepicker/css/bootstrap-datetimepicker.css">
<link rel="stylesheet" type="text/css" href="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/daterange/daterangepicker.css">
<!-- data range js start -->
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/ladda/ladda.min.js"></script>
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/daterange/moment.min.js"></script> 
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/daterange/daterangepicker.js"></script>
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/datepicker/js/bootstrap-datetimepicker.js"></script>

<!-- date time calender js -->
<script   language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/daterange/daterangepicker.js"></script>
<script  language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/datepicker/js/bootstrap-datetimepicker.js"></script>
<script   language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- Css and Js For Multiselect & single select Dropdown -->
<link rel="stylesheet" href="<?php echo config('app.site_url'); ?>/enlight/scripts/multiselect/bootstrap-chosen.css" />
<script src="<?php echo config('app.site_url'); ?>/enlight/scripts/multiselect/chosen.jquery.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo config('app.site_url'); ?>/enlight/scripts/multiselect/jquery.mCustomScrollbar.min.css">
<script src="<?php echo config('app.site_url'); ?>/enlight/scripts/multiselect/jquery.mCustomScrollbar.concat.min.js"></script>

<!-- data range js End -->
<script language="javascript" type="text/javascript">
var SITE_URL = '<?php echo config('app.site_url'); ?>';
var HOST_IP = '<?php echo config('enconfig.host_ip'); ?>';
var IAMSERVICE_URL = '<?php echo config('enconfig.iamservice_url'); ?>';
var session_lang = "<?php echo config('app.lang_arr.'.app()->getLocale()) !="" ? config('app.lang_arr.'.app()->getLocale()) : config('app.default_lang'); ?>";
</script>

<link rel="icon" type="image/png" href="<?php echo config('app.site_url'); ?>/enlight/images/favicon.ico">

<!-- Mega menu start -->
<link id="effect" rel="stylesheet" type="text/css" media="all" href="<?php echo config('app.site_url'); ?>/megamenu/css/fade-down.css">
<link rel="stylesheet" type="text/css" media="all" href="<?php echo config('app.site_url'); ?>/megamenu/css/webslidemenu.css">
<link id="theme" rel="stylesheet" type="text/css" media="all" href="<?php echo config('app.site_url'); ?>/megamenu/css/black-gry.css">

<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/megamenu/js/webslidemenu.js"></script>
<link rel="stylesheet" href="<?php echo config('app.site_url'); ?>/megamenu/css/demo.css">
<!-- Custom CSS -->
<link rel="stylesheet" type="text/css" href="<?php echo config('app.site_url'); ?>/enlight/css/common.css">
<script language="javascript" type="text/javascript">
$(document).ready(function() {
      $("a[data-theme]").click(function() {
        $("head link#theme").attr("href", $(this).data("theme"));
        $(this).toggleClass('active').siblings().removeClass('active');
      });
      $("a[data-effect]").click(function() {
        $("head link#effect").attr("href", $(this).data("effect"));
        $(this).toggleClass('active').siblings().removeClass('active');
      });

      $.get("<?php echo config('enconfig.iamapp_url'); ?>" + "/showbrand", function( data ) {
        if(data){
          var res = JSON.parse(data);
          if(typeof(res.copyright_by) != "undefined"){
            $("#copyrightby").html(res.copyright_by);
          }
        }
      });
    });
</script>

<!-- Mega menu end -->
</head>
<body class="sb-l-m">
<!--<div id="emmainloader_bg"></div>
<div id="emmainloader">
	Loading...
</div> -->
<!-- Start: Main -->
<div id="main"> 
    <!-- Start: Header -->
    <?php echo view('emheader');?>
    <!-- End: Header --> 
    
    <!-- Start: Sidebar -->
	
    <?php //echo view('emmenu');	?>
    <!-- End: Sidebar --> 
    
    <!-- Start: Content -->
    <section id="content_wrapper"> 
		<?php echo $includeView;?>
    </section>
	<div id="lightboxbackid" ></div>
	<!-- Start: Right Sidebar -->
        <aside id="sidebar_right" class="nano_sb white-bg">
            <div class="sidebar_right_content nano-content">
                <div class="tab-block sidebar-block br-n">                    
                    <div class="tab-content minheight br-n">
                    <div id="sidebar-right-tab1" class="tab-pane active">
                        <h5 class="title-divider text-muted mb0"> <strong id="lightbox_title">  </strong> <span class="pull-right"> <i class="fa fa-close fs22" style="" onclick="lightbox('hide');"></i> </span> </h5>
                    </div>
                    <div id="lightbox_data"></div>
                  </div>
                    <!-- end: .tab-content -->
                </div>
            </div>
        </aside>
        <!-- End: Right Sidebar -->
    <!-- End: Content --> 
    
  <!-- Notification Modal -->
  <div class="modal fade" id="notification_modal" tabindex="-1" role="dialog" aria-labelledby="notification_modal_title" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
      <h5 class="modal-title" id="notification_modal_longtitle"><?php echo trans('label.lbl_information');?></h5>
      </div>
      <div class="modal-body" id="notification_modal_body">
        <table class="table table-striped">
          <tbody>
            <tr>
              <td><?php echo trans('label.lbl_name');?></td>
              <td id="notification_modal_importname"></td>
            </tr>
            <tr>
              <td><?php echo trans('label.lbl_title');?></td>
              <td id="notification_modal_importtitle"></td>
            </tr>
            <tr>
              <td><?php echo trans('label.lbl_importsuccess');?></td>
              <td id="notification_modal_importsuccess"></td>
            </tr>
            <tr>
              <td><?php echo trans('label.lbl_importfail');?></td>
              <td id="notification_modal_importfail"></td>
            </tr>
            <tr>
              <td><?php echo trans('label.lbl_total');?></td>
              <td id="notification_modal_importtotal"></td>
            </tr>
            <tr>
              <td><?php echo trans('label.lbl_date');?></td>
              <td id="notification_modal_importdate"></td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-primary" id="notification_modal_markasread"><?php echo trans('label.lbl_markasread');?></button>
      <button type="button" class="btn btn-secondary" data-dismiss="modal" id="notification_modal_close"><?php echo trans('label.btn_close');?></button>
      </div>
    </div>
    </div>
  </div>
  <!------- Notification Modal End ------->
</div>

<!-------Start footer--------------------------------------->
<?php
$copyright_text = '';
$URL      = config('enconfig.iamapp_url') . '/showbrand';
$ch       = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $URL);
$data     = curl_exec($ch);
curl_close($ch);
if(json_last_error() == JSON_ERROR_NONE){
  $data = json_decode($data,true);
  if(isset($data['copyright_by'])) {
    $copyright_text = $data['copyright_by'];
  }
}
?>
<footer class="page-footer font-small blue" style="position: fixed;bottom: 0;padding: 10px;width: 100%;background-color: #fff;border-top: 1px solid #d2d6de; color: #444;">
  <?php if($copyright_text == ''){?>
  <!-- Copyright -->
  <div class="footer-copyright text-center py-3"><span id="copyrightby">Copyright by 
    <strong><span class="text-info">AIM - Asset Inventory Manager</span></strong></span>
  </div>
  <!-- Copyright -->
  <?php } else{?>
  <div class="footer-copyright text-center py-3"><span id="copyrightby"><?php echo isset($copyright_text) ? str_replace("AIM",'<strong class="text-info">'.trans('title.enlight360').'</strong>',$copyright_text) : ""; ?></span></div>
  <?php }?>
</footer>
<!-------End footer--------------------------------------->

<!-- End: Main --> 
<!-- Bootstrap --> 
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/assets/js/bootstrap/bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/assets/js/main.js"></script> 
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/assets/js/demo.js"></script> 
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/template.js"></script>

<script type="text/javascript">
	jQuery(document).ready(function() {
		/* Do not remove this code */
		"use strict";
		Core.init();
        Demo.init();
        
	});
</script>
<script type="text/javascript">
    jQuery(document).ready(function() {

      var csrf = $('meta[name="csrf-token"]').attr('content');
      if (!csrf)
      {
        location.reload();
      }
    });
    $('#browseFile').show();
</script>
</body>
</html>
