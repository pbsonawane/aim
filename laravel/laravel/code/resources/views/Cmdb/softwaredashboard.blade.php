<!-- Start: Topbar -->
<header id="topbar" class="affix"  >
   <div class="topbar-left">
      <!-- Add bread crumb here -->
      <ol class="breadcrumb">
         <li class="crumb-active nounderline"><a class="nounderline"><?php echo trans('label.lbl_sw_dash');?></a></li>
         <li class="crumb-link"><a href="<?php echo config('enconfig.iamapp_url'); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
         <li class="crumb-link"><?php echo trans('title.itam');?></li>
         <li class="crumb-link"><?php echo trans('title.assetmanagement');?></li>
         <li class="crumb-link"><a href="/softwaredashboard"><?php echo trans('label.lbl_sw_dash');?></a></li>
      </ol>
   </div>
   <div class="topbar-right">
      <div class="btn-group">
         <button id="timeline-toggle" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
         <span class="glyphicons glyphicons-show_lines fs16"></span>
         </button>
         <ul class="dropdown-menu pull-right" role="menu" >
            <li class="" id="" title="<?php echo trans('label.lbl_sw_list');?>">
               <a href="<?php echo config('api.site_url'); ?>/software"><span title="<?php echo trans('label.lbl_sw_list');?>"><?php echo trans('label.lbl_sw_list');?></span></a>
            </li>
         </ul>
      </div>
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
               <div id="loadAllData">
                  <div class="col-md-12">
                     <div class="panel  panel-info panel-border top">
                        <!-- <div class="panel-heading">
                           <span class="panel-title"><i class="fa fa-2x" aria-hidden="true"></i>  <?php echo trans('label.lbl_sw_dash'); ?></span>
                           </div>-->

                        <div class="panel-body " id="div_swtype">
                           <div class="col-md-12">

                          
<?php
$sw_type_list_json  = "";
$lic_type_list_json = "";
$manufact_list_json = "";
$sum        = 0; //todo: not used anywhere

$unused = '0';                            
 if (is_array($purchasecountallsw) && count($purchasecountallsw) > 0)
{
  $unused =  $purchasecountallsw['0']['max_installation'] - $swallocationsallsw[0]['allocationcount'];
}
?>
  
                              <div class="col-md-4">
                                 <!-- Pie Chart -->
                                 <div class="panel" id="pchart10">
                                    <div class="panel-heading">
                                       <!-- <span class="panel-icon"><i class="fa fa-pencil"></i>-->
                                       </span>
                                       <span class="panel-title"> <?php echo trans('label.lbl_software_types');?></span>
                                    </div>
                                    <div class="panel-body ">
                                       <?php //echo $test;print_r($dashboard );?>
                                       <div class="panel-body pn">
                                          <div id="pie-chart" style="width: 100%; height: 210px; margin: 0 auto"></div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                
                              <div class="col-md-4">
                                 <div class="panel" id="p10">
                                    <div class="panel-heading">
                                       <span class="panel-title"> <?php echo trans('label.lbl_software_type_count');?></span>
                                    </div>
                                    <div class="panel-body pn">
                                       <div class="panel" id="p16" style="margin-bottom: 0px;max-height: 250px;overflow: auto;">
                                          <div class="panel-body pn">
                                             <table class="table mbn tc-med-1 tc-bold-last">
                                                <thead>
                                                   <tr class="hidden">
                                                      <th>#</th>
                                                      <th>#</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                          <?php
                            if (is_array($dashboard) && count($dashboard) > 0)
                            {
                              $sw_type_list_json = json_encode($dashboard);
                              foreach($dashboard as $i => $dash)
                              {
                                $tr = "";
                                $tr = "<tr>";
                                
                                $tr = $tr . "<td>" . 
                                (isset($dash["software_type_id"]) && !empty($dash["software_type_id"]) ? '<span><a href="'.url('/software/software_type', $dash['software_type_id']).'"> '.$dash["software_type"].' </a></span>' : '<span>'.$dash["software_type"].'</span>') . "</td>";
                                
                                
                                $tr = $tr . "<td>" . 
                                (isset($dash["software_type_id"]) && !empty($dash["software_type_id"]) ? '<span><a href="'.url('/software/software_type', $dash['software_type_id']).'"> '.$dash["count"].' </a></span>' : '<span>'.$dash["count"].'</span>') . "</td>";
                                
                                $tr = $tr . "</tr>";
                                echo $tr;
                              }
                              
                            }else{
                              echo trans('messages.msg_norecordfound');
                            }
                          ?>
                                                </tbody>
                                             </table>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                            
                        <div class="panel-body " id="div_swlicensetype">
                           <div class="col-md-12">
                              <div class="col-md-4">
                                 <!-- Pie Chart -->
                                 <div class="panel" id="pchart10">
                                    <div class="panel-heading">
                                       <!-- <span class="panel-icon"><i class="fa fa-pencil"></i>-->
                                       </span>
                                       <span class="panel-title"> <?php echo trans('label.lbl_license_type');?></span>
                                    </div>
                                    <div class="panel-body ">
                                       <?php //echo $test;print_r($dashboard );?>
                                       <div class="panel-body pn">
                                          <div id="pie-chart2" style="width: 100%; height: 210px; margin: 0 auto"></div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                
                              <div class="col-md-4">
                                 <div class="panel" id="p10">
                                    <div class="panel-heading">
                                       <span class="panel-title"> <?php echo trans('label.lbl_license_type_count');?></span>
                                    </div>
                                    <div class="panel-body pn">
                                       <div class="panel" id="p16" style="margin-bottom: 0px;max-height: 250px;overflow: auto;">
                                          <div class="panel-body pn">
                                             <table class="table mbn tc-med-1 tc-bold-last">
                                                <thead>
                                                   <tr class="hidden">
                                                      <th>#</th>
                                                      <th>#</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                          <?php
                            if (is_array($dashboardlicense) && count($dashboardlicense) > 0)
                            {
                              foreach($dashboardlicense as $i => $dash)
                              {
                                $lic_type_list_json = json_encode($dashboardlicense);
                                $sum        = $sum + $dash['count'];
                                
                                $tr = "";
                                $tr = "<tr>";
                                
                                $tr = $tr . "<td>" . 
                                (isset($dash["license_type"]) && !empty($dash["license_type"]) ? '<span></span>' . $dash["license_type"] : '') . "</td>";
                                
                                
                                $tr = $tr . "<td>" . 
                                (isset($dash["count"]) && !empty($dash["count"]) ? $dash["count"] : '0') . "</td>";
                              
                                $tr = $tr . "</tr>";
                                echo $tr;
                              }
                              
                            }else{
                              echo trans('messages.msg_norecordfound');
                            }
                          ?>
                                                </tbody>
                                             </table>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-md-4">
                                 <div class="panel" id="p10">
                                    <div class="panel-heading">
                                       <span class="panel-title"> <?php echo trans('label.lbl_total_count');?></span>
                                    </div>
                                    <div class="panel-body pn">
                                       <div class="panel" id="p16"  style="margin-bottom: 0px;max-height: 250px;overflow: auto;">
                                          <div class="panel-body pn">
                                             <table class="table mbn tc-med-1 tc-bold-last">
                                                <thead>
                                                   <tr class="hidden">
                                                      <th>#</th>
                                                      <th>#</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                          <tr>
                             <td>
                              <span class="fw700"></span><?php echo trans('label.lbl_use');?>
                             </td>
                             <?php if($swallocationsallsw[0]['allocationcount'] == ''){?>
                             <td><?php echo '0'; ?></td><?php 
                           }else{?>

                             <td><?php echo $swallocationsallsw[0]['allocationcount']; ?></td><?php 
                           }?>

                          </tr>
                                                   <tr>
                                                      <td>
                                                         <span class="fw700"></span><?php echo trans('label.lbl_unuse');?>
                                                      </td>
                                                      <?php if($unused == ''){?>
                              <td><?php echo '0'; ?></td><?php 
                                                        }else{?>
                              <td><?php echo $unused ; ?></td><?php 
                            }?>
                                                   </tr>
                                                </tbody>
                                             </table>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
            
            <div class="panel-body " id="div_swmanufacturer">
                           <div class="col-md-12">
                <div class="col-md-4">
                                 <!-- Pie Chart -->
                                 <div class="panel" id="pchart10">
                                    <div class="panel-heading">
                                       <!-- <span class="panel-icon"><i class="fa fa-pencil"></i>-->
                                       </span>
                                       <span class="panel-title"> <?php echo trans('label.lbl_software_manufacturer');?></span>
                                    </div>
                                    <div class="panel-body ">
                                       <?php //echo $test;print_r($dashboard );?>
                                       <div class="panel-body pn">
                                          <div id="pie-chart3" style="width: 100%; height: 210px; margin: 0 auto"></div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              
                              <div class="col-md-4" >
                                 <div class="panel" id="p10">
                                    <div class="panel-heading">
                                       <span class="panel-title"> <?php echo trans('label.lbl_software_manufacturer_count');?></span>
                                    </div>
                                    
                                    <div class="panel-body pn">
                                       <div class="panel" id="p16" style="margin-bottom: 0px;max-height: 250px;overflow: auto;">
                                          <div class="panel-body pn">
                                           <table class="table mbn tc-med-1 tc-bold-last">
                                                <thead>
                                                   <tr class="hidden">
                                                      <th>#</th>
                                                      <th>#</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                          <?php
                            if (is_array($dashboardmanufacturer) && count($dashboardmanufacturer) > 0)
                            {
                              foreach($dashboardmanufacturer as $i => $dash)
                              {
                                $manufact_list_json = json_encode($dashboardmanufacturer);
                                
                                $tr = "";
                                $tr = "<tr>";
                                
                                $tr = $tr . "<td>" . 
                                (isset($dash["software_manufacturer_id"]) && !empty($dash["software_manufacturer_id"]) ? '<span><a href="'.url('/software/software_manufacturer', $dash['software_manufacturer_id']).'"> '.$dash["software_manufacturer"].' </a></span>' : '<span>'.$dash["software_manufacturer"].'</span>') . "</td>";
                                
                                
                                $tr = $tr . "<td>" . 
                                (isset($dash["software_manufacturer_id"]) && !empty($dash["software_manufacturer_id"]) ? '<span><a href="'.url('/software/software_manufacturer', $dash['software_manufacturer_id']).'"> '.$dash["count"].' </a></span>' : '<span>'.$dash["count"].'</span>') . "</td>";
                                
                                $tr = $tr . "</tr>";
                                echo $tr;
                              }
                              
                            }else{
                              echo trans('messages.msg_norecordfound');
                            }
                          ?>
                                                </tbody>
                                             </table>
                                          </div>
                                       </div>
                                    </div>
                                  
                                 </div>
                              </div>
           
                          </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-8">
                     <div class="panel  panel-info panel-border">
                        <!--<div class="panel-heading">
                           <span class="panel-title"><i class="fa fa-2x" aria-hidden="true"></i>  <?php //echo trans('label.lbl_disk_partitions'); ?></span>
                           </div>-->
                        <div id="div_deviceserverdata"></div>
                     </div>
                  </div>
               </div>
            </div>
         </form>
      </div>
   </div>
</div>
</div>
<script language="javascript" type="text/javascript" src="enlight/scripts/common.js"></script> 
<script language="javascript" type="text/javascript" src="enlight/scripts/cmdb/softwaredashboard.js"></script>
<script language="javascript" type="text/javascript" src="enlight/scripts/cmdb/softwarelist.js"></script>
<script language="javascript" type="text/javascript" src="enlight/scripts/cmdb/software.js"></script>
<script>
   // Donut Chart Values
   
       
</script>
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/c3charts/d3.min.js"></script>
<script type="text/javascript" src="<?php echo config('app.site_url'); ?>/bootstrap/vendor/plugins/c3charts/c3.min.js"></script>
<!--<script type="text/javascript" src="bootstrap/assets/js/pages/charts/d3.js"></script>
   -->
<script type="text/javascript">
    var sw_type_list_json  = <?php echo $sw_type_list_json;?>;
    var lic_type_list_json = <?php echo $lic_type_list_json;?>;
    var manufact_list_json = <?php echo $manufact_list_json;?>;
    var list_array       = [];
    var random_color_array = [];
    
    function get_random_color(){
      return 'hsl(' + 360 * Math.random() + ',' +
           (25 + 70 * Math.random()) + '%,' + 
           (60 + 10 * Math.random()) + '%)' //increase this value for making color lighter (0=darker,100=lighter)
    }

    jQuery(document).ready(function() {
      "use strict";

      // Init Theme Core    
      Core.init();

      // Init Theme Core    
      Demo.init();

      // This page contains more Initilization Javascript than normal.
      // As a result it has its own js page. See charts.js for more info
      //D3Charts.init();

      // Init tray navigation smooth scroll
      $('.tray-nav a').smoothScroll({
        offset: -145
      });
    });
    
    // Pie Chart software type
    var cnt = 0;
    if(sw_type_list_json != ''){
      for(var i=0; i<sw_type_list_json.length;i++){
        list_array[i]     = [(sw_type_list_json[i]).software_type,(sw_type_list_json[i]).count];
        random_color_array[i] = get_random_color();
        if((sw_type_list_json[i]).count > 0) cnt++;
      }
    }
    show_pie_chart('pie-chart',list_array,random_color_array,cnt);
    
    // Pie Chart license type
    list_array = [];
    random_color_array = [];
    cnt = 0;
    if(lic_type_list_json != ''){
      for(var i=0; i<lic_type_list_json.length;i++){
        list_array[i]     = [(lic_type_list_json[i]).license_type,(lic_type_list_json[i]).count];
        random_color_array[i] = get_random_color();
        if((lic_type_list_json[i]).count > 0) cnt++;
      }
    }
    show_pie_chart('pie-chart2',list_array,random_color_array,cnt);
   
       // Pie Chart software manufacturer
    list_array = [];
    random_color_array = [];
    cnt = 0;
    if(manufact_list_json != ''){
      for(var i=0; i<manufact_list_json.length;i++){
        list_array[i]     = [(manufact_list_json[i]).software_manufacturer,(manufact_list_json[i]).count];
        random_color_array[i] = get_random_color();
        if((manufact_list_json[i]).count > 0) cnt++;
      }
    }
    show_pie_chart('pie-chart3',list_array,random_color_array,cnt);
    
    //darshan[8-oct-2020]
    function show_pie_chart(id,list_array,random_color_array,counts=0){
      if(counts > 6){
        if($("#"+id).length > 0 && $("#"+id).height() < 300){
          $("#"+id).css("height","400px");
          $("#"+id).css("max-height","500px");
        }
      }
      var chart12 = c3.generate({
         bindto: '#'+id,
         color: {
         pattern: random_color_array,
         },
         data: {
           columns: list_array,
           type : 'pie',
           //onclick: function (d, i) { console.log("onclick", d, i); },
           //onmouseover: function (d, i) { console.log("onmouseover", d, i); },
           //onmouseout: function (d, i) { console.log("onmouseout", d, i); }
         }
       });
    }
</script>