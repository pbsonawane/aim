<!-- Start: Topbar -->
<header id="topbar" class="affix"  >
   <div class="topbar-left">
      <!-- Add bread crumb here -->
      <ol class="breadcrumb">
         <li class="crumb-active nounderline"><a class="nounderline"><?php echo trans('label.lbl_License_dash');?></a></li>
         <li class="crumb-link"><a href="<?php echo config('enconfig.iamapp_url'); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
         <li class="crumb-link"><?php echo trans('title.itam');?></li>
         <li class="crumb-link"><?php echo trans('title.assetmanagement');?></li>
         <li class="crumb-link"><a href="/licensedashboard/view"><?php echo trans('label.lbl_License_dash');?></a></li>
      </ol>
   </div>
  
</header>
<!-- End: Topbar -->
<div id="content">
<?php
$operating_dashboard_json  = ""; 
$database_dashboard_json = "";
$cpanel_dashboard_json = "";
?>
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
                           <span class="panel-title"><i class="fa fa-2x" aria-hidden="true"></i>  <?php echo trans('label.lbl_License_dash'); ?></span>
                           </div>-->

                        <div class="panel-body " id="div_swtype">
                           <div class="col-md-12">

                              <div class="col-md-6">
                                 <!-- Pie Chart -->
                                 <div class="panel" id="pchart10">
                                    <div class="panel-heading">
                                       <!-- <span class="panel-icon"><i class="fa fa-pencil"></i>-->
                                       </span>
                                       <span class="panel-title"> <?php echo trans('label.Operating_System');?></span>
                                    </div>
                                    <div class="panel-body ">
                                       <?php //echo $test;print_r($dashboard );?>
                                       <div class="panel-body pn">
                                          <div id="pie-chart2" style="width: 100%; height: 300px; margin: 0 auto"></div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                
                              <div class="col-md-6">
                                 <div class="panel" id="p10">
                                    <div class="panel-heading">
                                       <span class="panel-title"> <?php echo trans('label.lbl_Operating_System_Count');?></span>
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
                                 <?php $sum=0; $operating_dashboard_json = json_encode($dashboard); foreach($dashboard as $datas)  {  ?>
                           <tr>
                              <td style="color:black;"><?php echo trim($datas['Osversion'],'" \r');?>
                              </td>
                              <td style="color:darkgrey;"><?php echo $datas['count']?></td>
                           </tr>
                           <?php $sum += $datas['count']; } ?>
                           <tr>
                              <td style="color:black;">Total</td>
                              <td style="color:darkgrey;"><?php echo $sum;?></td>
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


                         <div class="panel-body " id="div_swtype">
                           <div class="col-md-12">

                              <div class="col-md-6">
                                 <!-- Pie Chart -->
                                 <div class="panel" id="pchart10">
                                    <div class="panel-heading">
                                       <!-- <span class="panel-icon"><i class="fa fa-pencil"></i>-->
                                       </span>
                                       <span class="panel-title"> <?php echo trans('label.Database_License');?></span>
                                    </div>
                                    <div class="panel-body ">
                                       <?php //echo $test;print_r($dashboard );?>
                                       <div class="panel-body pn">
                                          <div id="pie-chart3" style="width: 100%; height: 300px; margin: 0 auto"></div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                
                              <div class="col-md-6">
                                 <div class="panel" id="p10">
                                    <div class="panel-heading">
                                       <span class="panel-title"> <?php echo trans('label.lbl_Database_License_Count');?></span>
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
                                 <?php $sum=0; $database_dashboard_json  = json_encode($dashboardlicense); foreach($dashboardlicense as $datas)  {  ?>
                           <tr>
                              <td style="color:black;"><?php echo trim($datas['DBversion'],'" \r');?>
                              </td>
                              <td style="color:darkgrey;"><?php echo $datas['count']?></td>
                           </tr>
                           <?php $sum += $datas['count']; } ?>
                           <tr>
                              <td style="color:black;">Total</td>
                              <td style="color:darkgrey;"><?php echo $sum;?></td>
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



                         <div class="panel-body " id="div_swtype">
                           <div class="col-md-12">

                              <div class="col-md-6">
                                 <!-- Pie Chart -->
                                 <div class="panel" id="pchart10">
                                    <div class="panel-heading">
                                       <!-- <span class="panel-icon"><i class="fa fa-pencil"></i>-->
                                       </span>
                                       <span class="panel-title"> <?php echo trans('label.Cpanel_License');?></span>
                                    </div>
                                    <div class="panel-body ">
                                       <?php //echo $test;print_r($dashboard );?>
                                       <div class="panel-body pn">
                                          <div id="pie-chart" style="width: 100%; height: 300px; margin: 0 auto"></div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                
                              <div class="col-md-6">
                                 <div class="panel" id="p10">
                                    <div class="panel-heading">
                                       <span class="panel-title"> <?php echo trans('label.lbl_Cpanel_License_Count');?></span>
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
                                 <?php $sum=0; $cpanel_dashboard_json  = json_encode($cpaneldashboard); foreach($cpaneldashboard as $datas)  {  ?>
                           <tr>
                              <td style="color:black;"><?php echo trim($datas['CpanelVersion'],'" \r');?>
                              </td>
                              <td style="color:darkgrey;"><?php echo $datas['count']?></td>
                              
                           </tr>
                           <?php $sum += $datas['count']; } ?>
                           <tr>
                              <td style="color:black;">Total</td>
                               <td style="color:darkgrey;"><?php echo $sum;?></td>
                            
                             
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
    var operating_dashboard_json  = <?php echo $operating_dashboard_json;?>;
    var database_dashboard_json = <?php echo $database_dashboard_json;?>;
    var cpanel_dashboard_json =  <?php echo $cpanel_dashboard_json;?>;
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
      list_array = [];
    random_color_array = [];
    cnt = 0;
    if(operating_dashboard_json != ''){
      for(var i=0; i<operating_dashboard_json.length;i++){
        list_array[i]     = [(operating_dashboard_json[i]).Osversion,(operating_dashboard_json[i]).count];
        random_color_array[i] = get_random_color();
        if((operating_dashboard_json[i]).count > 0) cnt++;
      }
    }
    show_pie_chart('pie-chart2',list_array,random_color_array,cnt);
    

    /**************New Pie Chart ****************************/

     // Pie Chart software manufacturer
    list_array = [];
    random_color_array = [];
    cnt = 0;
    if(database_dashboard_json != ''){
      for(var i=0; i<database_dashboard_json.length;i++){
        list_array[i]     = [(database_dashboard_json[i]).DBversion,(database_dashboard_json[i]).count];
        random_color_array[i] = get_random_color();
        if((database_dashboard_json[i]).count > 0) cnt++;
      }
    }
    show_pie_chart('pie-chart3',list_array,random_color_array,cnt);
    /**********************END********************************/
        
    /***********PIE CHART FOR CPANEL **************/
     // Pie Chart software type
      list_array = [];
    random_color_array = [];
    cnt = 0;
    if(cpanel_dashboard_json != ''){
      for(var i=0; i<cpanel_dashboard_json.length;i++){
        list_array[i]     = [(cpanel_dashboard_json[i]).CpanelVersion,(cpanel_dashboard_json[i]).count];
        random_color_array[i] = get_random_color();
        if((cpanel_dashboard_json[i]).count > 0) cnt++;
      }
    }
    show_pie_chart('pie-chart',list_array,random_color_array,cnt);
    
    /**********************************************/



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
           
         }
       });
    }
</script> 
