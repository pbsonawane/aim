

<table class="table mbn">
   <tbody>
      <tr>
         <td class="va-m fw600 text-muted" width="15%">
            <?php if(canuser('update','software')){ ?>
            <button type="button" class="btn btn-primary swlicense_attach" ><i class="fa fa-plus "></i> <?php echo trans('label.btn_sw_licence'); ?></button>
            
            <?php }?>
         </td>
      </tr>
   </tbody>
</table>
<div class="panel panel-visible" id="license_data">
   <table class="table table-striped table-bordered table-hover">
      <?php //print_r($swlicenses);?>
      <thead>
         <tr>
            <th class="text-center"><?php echo trans('label.lbl_srno') ?></th>
            <th><?php echo trans('label.lbl_software_manufacturer') ?></th>
            <th><?php echo trans('label.lbl_license_type') ?></th>
            <th><?php echo trans('label.lbl_license_key') ?></th>
            <th><?php echo trans('label.lbl_max_installation') ?></th>
            <th><?php echo trans('label.lbl_purchase_cost') ?></th>
            <th><?php echo trans('label.lbl_description') ?></th>
            <th><?php echo trans('label.lbl_acquisition_date') ?></th>
            <th><?php echo trans('label.lbl_expiry_date') ?></th>
            <th><?php echo trans('label.lbl_action') ?></th>
         </tr>
      </thead>
      <tbody>
         <?php

            // dd($swlicenses);
            $max_installation  = '';     

            if (is_array($swlicenses) && count($swlicenses) > 0)
            {
            $sum = 0;
            foreach ($swlicenses as $i => $swlicense)
            {
            
            $id = $swlicense['software_license_id'];
            $max_installation = $swlicense['max_installation'];
            
            $delete = '';
            $edit = '';
            
            if(canuser('update','software')){ 
            $edit = '<span title = "Click To Edit Record" name="edit_b" id="edit_'.$id.'" type="button" data-id="'.$id.'" class="softwarelicense_edit" id="edit_b"><i class="fa fa-edit mr10 fa-lg"></i></span>'; 
            $sum = $sum + (int)$max_installation;  
            
            }
            ?>
         <tr>
            <td class="text-center"><?php echo  $i + 1; ?></td>
            <td><?php echo $swlicense['software_manufacturer']; ?></td>
            <td><?php echo $swlicense['license_type']; ?></td>
            <td><?php echo $swlicense['license_key']; ?></td>
            <td><?php echo $swlicense['max_installation']; ?></td>
            <td><?php echo $swlicense['purchase_cost']; ?></td>
            <td><?php echo $swlicense['description']; ?></td>
            <td><?php echo $swlicense['acquisition_date']; ?></td>
            <td><?php echo $swlicense['expiry_date']; ?></td>
            <td><?php echo $edit; ?>
               <button type="button" id="<?php echo $id;?>" class="btn btn-primary allocation_btn" data-id="<?php echo $id;?>" data-max="<?php echo $max_installation;?>" data-toggle="modal"><i class="fa fa-plus "></i> <?php echo trans('label.btn_sw_licence_allocate'); ?></button>
            </td>
         </tr>
         <?php
            }
            //echo $sum;
            ?>
         <input id="sum" type="hidden" value="<?php echo $sum?>">
         
         <?php
            }
            
            else
            echo '<tr><td colspan = "100" class ="text-center">'.trans('label.no_records').'</td></tr>';
            ?>
      </tbody>
   </table>
   <hr>
   <div class="panel-heading br-l br-r br-t">
      <span class="panel-title"> <?php echo trans('label.lbl_alloc_license_list') ?>
      </span>
   </div>
   <?php //echo $max_installation;?>
   <table class="table table-striped table-bordered table-hover">
      <?php //print_r($swlicenses);?>
      <thead>
         <tr>

            <th class="text-center"><?php echo trans('label.lbl_srno') ?></th>
            <th><?php echo trans('label.lbl_device') ?></th>
            <th><?php echo trans('label.lbl_display_name') ?></th>
            <th><?php echo trans('label.lbl_action') ?></th>
         </tr>
      </thead>
      <tbody>
         <?php
            // dd($swallocations);
            
            if (is_array($swallocations) && count($swallocations) > 0)
            {
            //echo count($swinstalldata);
            foreach ($swallocations as $i => $swallocation)
            {
            $id = $swallocation['asset_id'];
            
            
            ?>
         <tr>
            <td class="text-center"><?php echo  $i + 1; ?></td>
            <!--<td><?php echo $swallocation['asset_tag']?></td>-->
            <td><?php echo '<a target="_blank" href="'.config('app.site_url').'/assets/'.$id.'/'.$swallocation['ci_templ_id'].'" id="'.$id.'" class="assetdash1">'.$swallocation['asset_tag'].'</a>'; ?></td>
            <td><?php echo $swallocation['display_name']?></td>
            <td class="va-m fw600 text-muted" width="15%">
               <?php if(canuser('update','software')){ ?>
               <?php $id=$software_id;?><button type="button" id="delete_<?php echo $id;?>_<?php echo $swallocation['asset_id'];?>" class="btn btn-danger allocate_asset_delete" data-assetid="'<?php echo $id;?>'"><?php echo trans('label.lbl_deallocate'); ?></button>
               <button type="button" id="delete_<?php echo $id;?>_<?php echo $swallocation['asset_id'];?>" class="btn btn-warning allocate_deallocate" data-assetid="'<?php echo $id;?>'"><?php echo trans('label.lbl_uninstall'); ?></button>
               <?php }?>
            </td>
         </tr>
         <?php
            }
            }
            
            else
            echo '<tr><td colspan = "100" class ="text-center">'.trans('label.no_records').'</td></tr>';
            ?>
      </tbody>
   </table>
</div>


<!-- Modal -->
<div id="myModal1" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><?php echo trans('label.lbl_allocate_license');?></h4>
         </div>
         <div class="modal-body">
            <div>

               <form name="allocateform" id="allocateform" method="post" >
                <table class="table table-striped table-bordered table-hover table-responsive datatable_allocate" >
                  <input id="software_license_id" name="software_license_id" type="hidden" value="<?php echo $software_license_id?>">
                  <input id="max_installation" type="hidden" value="<?php echo $max_installation?>">

                  <input type="hidden" name="credentialnames" id="credentialnames">          
                  <?php


                     $arr1 = [];
                     $arr2 = [];   
                     
                     if (is_array($swallocations) && count($swallocations) > 0)
                     {
                     $cnt = count($swallocations); 
                     foreach ($swallocations as $i => $swallocation)
                     {
                     $id = $swallocation['asset_id'];
                     $arr1[] = $id; //explode(" ",$id);
                     
                     ?>
                  <input id="swassetsallocate_count" data-cnt="<?php echo $cnt;?>" type="hidden" value="<?php echo $cnt;?>">
                  <?php
                     }
                     }?>
                  
                     <thead>
                        <tr>
                           <th class="checkbox_column">
                              <div class="checkbox-custom mb5 checkbox-info">
                                 <input type="checkbox" class="" id="assetidsCheckAll" value="6">
                                 <label for="assetidsCheckAll" ></label>
                              </div>
                           </th>
                           <th><?php echo trans('label.lbl_device') ?></th>
                           <th><?php echo trans('label.lbl_display_name') ?></th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                           if (is_array($swinstalldata) && count($swinstalldata) > 0)
                            {         
                                foreach($swinstalldata as $i => $swdata)
                                {  
                                  
                                  $sid = $swdata['asset_id'];
                                  // $arr2[] = $sid; //explode(" ",$sid);
                                  $var = in_array($swdata['asset_id'], $arr1);
                                  if(!$var){
                                   
                                  
                           ?>
                        <tr>
                           <td class="checkbox_column">
                              <div class="checkbox-custom mb5">
                                 <?php $num = $i +  1;  ?>
                                 <input type="checkbox" name="selectassetids[]" class="selectassetidsChk check" id="<?php echo 'credChk'.$num; ?>" value="7" data-temp_name="<?php echo $swdata['asset_id']?>">
                                 <label for="<?php echo 'credChk'.$num; ?>"></label>                       
                              </div>
                           </td>
                           <td><?php echo $swdata['asset_tag']; ?></td>
                           <td><?php echo $swdata['display_name']; ?></td>
                        </tr>
                        <?php 
                           }
                           }
                           }else{
                           
                           echo '<tr><td colspan = "100" class ="text-center">'.trans('label.no_records').'</td></tr>';
                           }?>
                     </tbody>
                  </table>
               </form>
            </div>
         </div>
         <div class="modal-footer">
            <button type="submit" class="btn btn-success" id="swallocate_license"   data-dismiss="modal" ><?php echo trans('label.btn_allocate');?></button>
         </div>
      </div>
   </div>
</div>
<!--Modal End-->
<script>
   $(document).ready(function() {
    
   $('.datatable_allocate').dataTable({
   
             "aoColumnDefs": [{
              /*
               "dataSrc": null,
                 'bSortable': false,
                 //'aTargets': [-1]
              */
                 'bSortable': false,
                 'aTargets': [-1]
             }],
             "oLanguage": {
                 "oPaginate": {
                     "sPrevious": "",
                     "sNext": ""
                 }
             },
             "iDisplayLength": 5,
             "aLengthMenu": [
                 [5, 10, 25, 50, -1],
                 [5, 10, 25, 50, "All"]
             ],
             "sDom": '<"dt-panelmenu clearfix"lfr>t<"dt-panelfooter clearfix"ip>',
             "oTableTools": {
                 "sSwfPath": "vendor/plugins/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
             }
         });
   });
</script>

