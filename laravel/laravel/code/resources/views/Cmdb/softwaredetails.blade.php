 <?php $id = @$softwaredata['0']['software_id'];?>

 <?php config('app.site_url')?>
<!-- Start: Topbar -->
<header id="topbar" class="affix"  >
<div class="topbar-left">
   <!-- Add bread crumb here -->
  
 
  <ol class="breadcrumb">
  
         <li class="crumb-active nounderline"><a class="nounderline">Softwares</a></li>
         <li class="crumb-link"><a href="<?php echo config('enconfig.iamapp_url'); ?>"><span class="glyphicon glyphicon-home"></span></a></li>
       
         <li class="crumb-link">ITAM</li>
         <li class="crumb-link">Asset Management</li>
         <li class="crumb-link">Softwares</li>

         <li class="crumb-link"><a href="<?php echo url('softwarelistdetails', $id) ?>">Software Details</a></li>
        
      </ol>

</div>
</header>
<div class="panel-heading br-l br-r br-t">
   <span class="panel-title"> <?php echo @$softwaredata['0']['software_name']; ?>
   </span>
</div>
<div class="panel-body">
   <div id="" class="col-md-12 prn-md animated fadeIn">
      <div class="panel">
         <div class="bg-light pv8 pr10  br-light">
            <div class="row">
               <div class="hidden-xs hidden-sm col-md-12 va-m">
                  <div class="btn-group">
                     <a href="<?php config('app.site_url')?>/software" title="<?php echo trans("label.lbl_back");?>"><button name="" id="" type="button" class=" btn btn-default light" ><i class="fa fa-arrow-left"></i></a>
                     </button>
                  </div>
                  <?php $id = @$softwaredata['0']['software_id'];?>

                  <?php if(canuser('update','software')){ ?>
                  <div class="btn-group">
                     <button name="edit_b" id="edit_<?php echo $id; ?>"  type="button" class="software_edit btn btn-default light" data-softwareid="<?php echo $id; ?>" ><i class="fa fa-pencil"> <?php echo trans("label.btn_edit");?></i>
                     </button>
                  </div>
                  <?php }?>
                  <!--<div class="btn-group">
                     <button type="button" class="btn btn-default light dropdown-toggle ph8" data-toggle="dropdown">
                     Action
                     <span class="caret ml5"></span>
                     </button>
                     <ul class="dropdown-menu pull-right" role="menu">
                        <li>
                           <a href="#"><i class="fa fa-print"></i> Add Software license</a>
                        </li>
                        <li>
                           <a href="#"><i class="fa fa-print"></i> Add license ageement</a>
                        </li>
                        <li>
                           <a href="#"><i class="fa fa-print " ></i> Add software installation</a>
                        </li>
                        <li>
                           <a href="#"><i class="fa fa-print"></i> Email Users</a>
                        </li>
                     </ul>
                  </div>-->
               </div>
            </div>
         </div>
         <div class="panel-body pn br-n">
            <div class="tab-block mb25">
               <ul class="nav nav-tabs tabs-bg tabs-border">
                  <ul class="nav nav-tabs tabs-bg tabs-border">
                     <li class="active " >
                        <a href="#sw_details"   id="callswdetails" data-toggle="tab" aria-expanded="true"><i class="fa fa-check-square-o  text-purple"></i> <?php echo trans('label.lbl_software_details'); ?></a>
                     </li>
                     <li class="">
                        <a href="#install" class="" id="callinstall" data-toggle="tab" aria-expanded="true"><i class="fa fa-history text-purple"></i><?php echo trans('label.lbl_installations'); ?> </a>
                        </a>
                     </li>
                     <?php if(@$softwaredata['0']['software_type'] == 'Managed'){?>
                     <li class="">
                        <a href="#license" class="" data-toggle="tab" id="calllicense"  aria-expanded="false"><i class="fa fa-info-circle  text-purple"></i> <?php echo trans('label.lbl_license_details'); ?></a>
                     </li>
                     <?php } ?>
                     <?php if(canuser('advance','view_history')){?>
                     <li class="">
                        <a href="#history" class="" id="callhistory" data-toggle="tab" aria-expanded="true"><i class="fa fa-history text-purple"></i><?php echo trans('label.lbl_history'); ?> </a>
                        </a>
                     </li>
                     <?php }?>
                  </ul>
               </ul>
               <div class="tab-content">
                  <div id="sw_details" class="tab-pane active">
                     <?php //print_r($softwaredata);?>
                     <!-- Details START -->
                     <div class="panel invoice-panel">
                        <div class="panel-body p20">
                           <div class="row" id="
                              -info">
                           </div>
                           <div class="row" >
                              <div class="col-md-12">
                                 <table class="table mbn">
                                    <tbody>
                                  
                                      
                                       <tr>
                                          <td class="va-m fw600 text-muted" width="15%"><?php echo 'Installation'; ?></td>
                              <td class="fs30 fw500" width="25%"><?php echo count($swinstalldata); ?>
                              </td>
                            
                               <td class="va-m fw600 text-muted" width="15%"><?php echo trans('label.lbl_purchased'); ?></td>

 <?php 
$available ='0';
 if (is_array($purchasecount) && count($purchasecount) > 0)
{
    //print_r($purchasecount);
   $available =  $purchasecount[0]['max_installation'] - count($swallocations);
    ?>
                              <td class="fs30 fw500" width="25%"><?php echo $purchasecount[0]['max_installation'];?></td>
                           <?php } else{?>
                              <td class="fs30 fw500" width="25%"><?php echo '0';?></td>
                          <?php }?>
                                       </tr>
                                       <tr>
                                          <td class="va-m fw600 text-muted"><?php echo trans('label.lbl_allocated'); ?></td>
                                          <td class="fs30 fw500"><?php echo count($swallocations); ?></td>
                                        
                                       <td class="va-m fw600 text-muted"><?php echo trans('label.lbl_availabled'); ?></td>
                                          
                                          <td class="fs30 fw500"><?php echo $available; ?></td>
                                        
                                       </tr>

                                            <tr>
                                          <?php
                                             //print_r($softwaredata);?>
                                          <td class="va-m fw600 text-muted" width="15%"><?php echo trans('label.lbl_software_name'); ?>
                                          </td>
                                          <td class="fs15 fw500" width="25%"><?php echo @$softwaredata['0']['software_name']; ?>
                                          </td>
                                          <td class="va-m fw600 text-muted" width="15%"><?php echo trans('label.lbl_software_type'); ?></td>
                                          <td class="fs15 fw500" width="25%"><?php echo @$softwaredata['0']['software_type']; ?></td>
                                       </tr>
                                       <tr>
                                          <td class="va-m fw600 text-muted"><?php echo trans('label.lbl_software_category'); ?></td>
                                          <td class="fs15 fw500"><?php echo @$softwaredata['0']['software_category']; ?></td>
                                          <td class="va-m fw600 text-muted"><?php echo trans('label.lbl_software_manufacturer'); ?></td>
                                          <td class="fs15 fw500"><?php echo @$softwaredata['0']['software_manufacturer']; ?></td>
                                       </tr>
                                       <tr>
                                          
                                          <td class="va-m fw600 text-muted"><?php echo trans('label.lbl_ci_type'); ?></td>
                                          <td class="fs15 fw500"><?php echo @$softwaredata['0']['ci_type']; ?></td>
                                          <td class="va-m fw600 text-muted"><?php echo trans('label.lbl_version'); ?></td>
                                          <td class="fs15 fw500"><?php echo @$softwaredata['0']['version']; ?></td>
                                       </tr>
                                       
                                    </tbody>
                                 </table>
                              </div>
                           </div>
                        </div>
                     </div>
                     <!-- Details END -->
                  </div>
                  <div id="install" class="tab-pane">
                  
                     <div id="div_install_details">
                     </div>
                     
                  </div>
                  <div id="license" class="tab-pane">
                     
                     <div id="div_license_details">
                     </div>
                     
                  </div>
                  <?php if(canuser('advance','view_history')){?>
                  <div id="history" class="tab-pane">
                     <div id="div_history_details">
                     </div>
                  </div>
                  <?php }?>
               </div>
               <!--tab-content -->
            </div>
            <!--tab-block mb25-->
         </div>
         <!--panel-body pn br-n-->
      </div>
      <!--panel-->
   </div>
   <!--podetails_page-->
</div>



