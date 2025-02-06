
                        <table class="table table-striped table-bordered table-hover table-responsive ">
                            <thead>
                                <tr>
                                    <th class="checkbox_column">
                                        <div class="checkbox-custom mb5 checkbox-info">
                                            <input type="checkbox" class="region_dc" id="assetCheckAll" value="6">
                                            <label for="assetCheckAll"></label>
                                        </div>
                                        </th>
                                    <th><?php echo trans('label.lbl_asset_tag'); ?></th>
                                    <th><?php echo trans('label.lbl_name'); ?></th>
                                    <th><?php echo trans('label.lbl_asset_status'); ?></th>
                                    <th><?php echo trans('label.lbl_status'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php
									$assets = $dbdata;
                                    if (is_array($assets) && count($assets) > 0)
                                    {
                                        foreach ($assets as $i => $asset)
                                        { ?>
                                            <tr data-val="value">

                                                <td class="checkbox_column">
                                                    <div class="checkbox-custom mb5">
                                                        <?php $num = $i + 2;?>
                                                        <input type="checkbox" name="asset_id[]" class="assetChk " id="<?php echo 'credChk'.$num ?>" data-asset-tag="<?php echo $asset['asset_tag']; ?>" value="<?php echo $asset['asset_id'] ?>" data-temp_name="<?php echo $asset['asset_id'] ?>">
                                                        <label for="<?php echo 'credChk'.$num; ?>"></label>
                                                    </div>
                                                </td>
                                                <?php //echo $i +  1?>
                                                <td><?php echo $asset['asset_tag']; ?></td>
                                                <td name="display_name" id="display_name"><?php echo $asset['display_name']; ?></td>
                                                <!--<td>// echo $asset['bv_id'] </td>-->
                                                <td><?php $asset_status = str_replace('_',' ',$asset['asset_status']);
												echo ucwords($asset_status); ?></td>
                                                <td><?php if($asset['status'] == 'y'){ echo trans('label.lbl_yes'); }else{ echo trans('label.lbl_no'); } ?></td>
                                            </tr>
                                        <?php
                                        }
                                    }
                                    ?>
                                 </tr>
                            </tbody>
                        </table>

                   
