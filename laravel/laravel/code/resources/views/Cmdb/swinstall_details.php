<?php //dd($softwaredata);?>
                        <table class="table mbn">
                           <tbody>
                            <tr>
                           <?php
                           //$cnt = count($swallocations);
                           //$available = $purchasecount - $cnt; 

if (is_array($swinstalldata) && count($swinstalldata) > 0)
{
    //echo count($swinstalldata);
    ?>
                              <!--<td class="va-m fw600 text-muted" width="15%"><?php echo 'Installation'; ?></td>
                              <td class="fs30 fw500" width="25%"><?php echo count($swinstalldata); ?>
                              </td>-->
<?php }

?>                             <?php if($softwaredata['0']['software_type'] == 'Managed'){?>
                             <!-- <td class="va-m fw600 text-muted" width="15%"><?php echo trans('label.lbl_purchased'); ?></td>
                              <td class="fs30 fw500" width="25%"><?php //echo $purchasecount; ?></td>-->
                            <?php } ?>
                            </tr>
                            <tr>
                             
                                          <!--<td class="va-m fw600 text-muted"><?php echo trans('label.lbl_allocated'); ?></td>
                                          <td class="fs30 fw500"><?php //echo count($swallocations); ?></td>-->
                                       
                                        <?php if($softwaredata['0']['software_type'] == 'Managed'){?>
                                          <!--<td class="va-m fw600 text-muted"><?php echo trans('label.lbl_availabled'); ?></td>-->
                                          
                                          <td class="fs30 fw500"><?php //echo $available; ?></td>
                                        <?php } ?>
                                       </tr>
                              <tr>

                                 <td class="va-m fw600 text-muted" width="15%"><button type="button" class="btn btn-primary swasset_attach" ><i class="fa fa-plus "></i> <?php echo trans('label.btn_sw_installation'); ?></button></td>
                              </tr>
                              </tr>
                           </tbody>
                        </table>
                        <table class="table table-striped table-bordered table-hover">
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

if (is_array($swinstalldata) && count($swinstalldata) > 0)
{
    //echo count($swinstalldata);
    foreach ($swinstalldata as $i => $swdata)
    {
        $id = $swdata['asset_id'];
        ?>

                                            <tr>

                                            <td class="text-center"><?php echo $i + 1; ?></td>
                                              <!--<td><?php //echo $swdata['asset_tag']; ?></td>-->
                                              <td><?php echo '<a target="_blank" href="'.config('app.site_url').'/assets/'.$id.'/'.$swdata['ci_templ_id'].'" id="'.$id.'" class="assetdash1">'.$swdata['asset_tag'].'</a>'; ?></td>
                                              <td><?php echo $swdata['display_name']; ?></td>
                                             
                                        <td><?php $id=$software_id;?>
                                        <a name="delete_c" id="delete_<?php echo $id;?>_<?php echo $swdata['asset_id'];?>" type="button" class="install_asset_delete"  data-assetid="'<?php echo $id;?>'" ><i class="fa fa-trash"></i></a></td>
                                             
                                            </tr>
                                            <?php
}
}
else
{
    echo '<tr><td colspan = "100" class ="text-center">'.trans('label.no_records').'</td></tr>';
}

?>
                                        </tbody>
                                    </table>