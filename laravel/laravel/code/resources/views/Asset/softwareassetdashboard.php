
   <table class="table table-striped table-bordered table-hover">
      <thead>
        <tr>
         <th class="text-center"><?php echo trans('label.lbl_srno');?></th>
         <th><?php echo trans('label.lbl_software_name');?></th>
        
            <th><?php echo trans('label.lbl_version');?></th>
            <th><?php echo trans('label.lbl_license_key');?></th>
            <th><?php echo trans('label.lbl_installed_date');?></th>
         
         <th><?php echo trans('label.lbl_action');?></th>
        </tr>
      </thead>
      <tbody>
        
         <?php
        //$softwares = $dbdata;

         //print_r($softwaredata);die;
         if (is_array($softwaredata) && count($softwaredata) > 0)
         {
            foreach($softwaredata as $i => $swdata)
            {  
               $id = $swdata['software_id'];
               $delete = '';
               $delete = '<span title = "Click To Delete Record" type="button" id="delete_'.$id.'_'.$asset_id.'" data-assetid="'.$id.'" class="allocate_deallocate"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';

               //echo $id=$swdata['software_id'];?>
         <tr>
            <td class="text-center"><?php echo $i + 1?></td>
                
                <td><?php echo $swdata['software_name']; ?></td>
                <td><?php echo $swdata['version']; ?></td>
                <td><?php echo $swdata['license_key']; ?></td>
                <td><?php echo $swdata['created_at']; ?></td> 
                 <td><?php echo $delete; ?></td>

                <!--<td><?php $id=$swdata['software_id'];?><button type="button" id="delete_<?php echo $id;?>_<?php echo $asset_id;?>" class="btn btn-warning allocate_deallocate" data-assetid="'<?php echo $id;?>'"><?php echo trans('label.lbl_uninstall'); ?></button></td>-->
         </tr>
         <?php
            }
         }
         else
            echo '<tr><td colspan = "100"> No Records</td></tr>';
            ?> 
      </tbody>
   </table>
</div>