
<link rel="stylesheet" type="text/css" href="<?php echo config('app.site_url'); ?>/bootstrap/assets/admin-tools/admin-forms/css/admin-forms.css">
<div class="row">
    <div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div>
    <?php
//echo $formAction;
if ($formAction == "renew")
{
    $hidden = "hidden";
    $disabled = "disabled";

}
else
{
    $hidden = "";
    $disabled = "";

}
?>
<div class="col-md-12">
    <div class="panel">
        <div class="panel-body">
            <form class="form-horizontal"  name="addformcontract" id="addformcontract">
                <input id="contract_id" name="contract_id" type="hidden" value="<?php echo $contract_id ?>">
                <input id="user_id" name="user_id" type="hidden" value="<?php echo isset($user_id) ? $user_id : ''; ?>">

                <!-- <input id="primary_contract" name="primary_contract" type="hidden" value="<?php echo isset($primary_contract) ? $primary_contract : ''; ?>">-->
                <input id="action" name="formAction" type="hidden" value="<?php echo $formAction; ?>">
                <input id="contract_details_id" name="contract_details_id" type="hidden" value="<?php if (isset($contractdata[0]['contract_details_id']))
                    {
                     echo $contractdata[0]['contract_details_id'];
                     }
                     ?>">

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group required ">
                            <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_contract_name'); ?></label>
                            <div class="col-md-6">
                                <input type="text" id="contract_name" name="contract_name" class="form-control input-sm" value="<?php if (isset($contractdata[0]['contract_name']))
                                {
                                    echo $contractdata[0]['contract_name'];
                                }
                                ?>" >
                            </div>
                        </div>

                        <div class="form-group <?php echo $hidden; ?>">
                            <label for="Description" class="col-md-3 control-label"><?php echo trans('label.lbl_parent_contract'); ?></label>
                            <div class="col-md-6">

                                <select class="form-control input-sm" name="parent_contract" id="parent_contract">
                                    <option value="">-<?php echo trans('label.lbl_parent_contract'); ?>-</option>

                                    <?php

                                    if (is_array($parentcontract) && count($parentcontract) > 0)
                                    {
                                        foreach ($parentcontract as $contract)
                                        {
                                        $cucontractdata_id = isset($contractdata[0]['contract_id']) ? $contractdata[0]['contract_id'] : '';
                                            if ($contract['contract_status'] == 'active')
                                            {
                                            ?>
                                                <option value="<?php echo $contract['contract_id'] ?>" <?php if ($cucontractdata_id == $contract['contract_id']){
                                                echo "selected";}?> > <?php echo $contract['contract_name']; ?> </option>
                                                                <?php
                                            }
                                        }
                                    }

                                ?>

                                </select>

                            </div>
                        </div>
                        <div class="form-group required">
                            <label for="Description" class="col-md-3 control-label"><?php echo trans('label.lbl_contractid'); ?></label>
                            <div class="col-md-6">
                                <input type="text" id="contractid" name="contractid" class="form-control input-sm" value="<?php if (isset($contractdata[0]['contractid']))
                                    {
                                        echo $contractdata[0]['contractid'];
                                    }
                                    ?>">
                            </div>
                        </div>
                        <div class="form-group required ">
                            <label for="Description" class="col-md-3 control-label"><?php echo trans('label.lbl_description'); ?></label>
                            <div class="col-md-6">
                                <textarea id="description" name="description" class="form-control input-sm"  ><?php if (isset($contractdata[0]['description']))
                                {
                                    echo $contractdata[0]['description'];
                                }
                                ?>
                                </textarea>
                            </div>
                        </div>
                     <!--   <div class="form-group required">
                                    <label for="attachment" class="col-md-3 control-label">Attachment</label>
                                    <div class="col-md-6">
                                     <!--  // <input type="file" id="attachment" name="attachment">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#attachfile"><i class="fa fa-plus"></i> Attach File</button>

                                </div>
                        </div>  -->
                       <!-- 3-3-2020<div id="attachfile" class="modal fade" role="dialog">-->
                        <form name="attach" id="attach" method="post">
                            <div id="" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title"><?php echo trans('label.lbl_attach_file'); ?></h4>
                                        </div>
                                        <div class="modal-body">
                                            <input type="file" id="attachment" name="attachment">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo trans('label.lbl_attach_file'); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                        <div class="col-sm-6">
                            <div class="form-group required">
                                <label for="Description" class="col-md-3 control-label"><?php echo trans('label.lbl_contract_type'); ?></label>
                                <div class="col-md-6">

                                    <select class="form-control input-sm" name="contract_type_id" id="contract_type_id">
                                            <option value="">-<?php echo trans('label.lbl_contract_type'); ?>-</option>
                                            <?php
                                        if (is_array($contracttypes) && count($contracttypes) > 0)
                                        {
                                            foreach ($contracttypes as $contracttype)
                                            {
                                                $cucontracttypedata_id = isset($contractdata[0]['contract_type_id']) ? $contractdata[0]['contract_type_id'] : '';
                                                ?>
                                                                                <option value="<?php echo $contracttype['contract_type_id'] ?>" <?php if ($cucontracttypedata_id == $contracttype['contract_type_id'])
                                                {
                                                    echo "selected";}?> > <?php echo $contracttype['contract_type'] ?> </option>
                                                                        <?php
                                        }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group required <?php echo $hidden; ?>">
                                <label for="Description" class="col-md-3 control-label"><?php echo trans('label.lbl_vendor'); ?></label>
                                <div class="col-md-6">

                                    <select class="form-control input-sm" name="vendor_id" id="vendor_id">
                                        <option value="">-<?php echo trans('label.lbl_vendor'); ?>-</option>
                                                                            <?php
                                            if (is_array($vendors) && count($vendors) > 0)
                                            {
                                                foreach ($vendors as $vendor)
                                                {
                                                    $cuvendordata_id = isset($contractdata[0]['vendor_id']) ? $contractdata[0]['vendor_id'] : '';
                                                    ?>
                                        <option value="<?php echo $vendor['vendor_id'] ?>" <?php if ($cuvendordata_id == $vendor['vendor_id'])
                                                {
                                                    echo "selected";}?> > <?php echo $vendor['vendor_name'] ?> </option>
                                                                        <?php
                                             }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group  ">
                                <label for="renewed" class="col-md-3 control-label"><?php echo trans('label.lbl_renewed_contract'); ?></label>
                                <div class="col-lg-6">
                                    <div class="checkbox-custom mb5">
                                        <input <?php echo $disabled; ?> type="checkbox" class="renewed" id="renewed" name="renewed" value="y"
                                              <?php //echo  $contractdata[0]['renewed']=='y' ? 'checked' : '';
                                            if (isset($contractdata[0]['renewed']))
                                            {
                                                if ($contractdata[0]['renewed'] == 'y')
                                                {
                                                    $contractdata[0]['renewed'] == 'y' ? 'checked' : '';
                                                }
                                                else
                                                {
                                                    echo $contractdata[0]['renewed'];
                                                }
                                            }

                                            ?>? checked>
                                                <label for="renewed"><strong> </strong></label>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group required">
                                 <label for="Description" class="col-md-3 control-label"><?php echo trans('label.lbl_support'); ?></label>
                                <div class="col-md-6">
                                    <textarea id="support" name="support" class="form-control input-sm" ><?php if (isset($contractdata[0]['support']))
                                    {
                                        echo $contractdata[0]['support'];
                                    }
                                    ?></textarea>
                                </div>
                            </div>
                        </div>
                   
               
        <div class="col-sm-12">
            <hr>
        </div>

           
                <div class="col-sm-6" style="margin-right: 1%;">
                    <div class="form-group required">
                         <label class="col-md-3 control-label" for=""><?php echo trans('label.btn_add_asset'); ?> </label>
                   <?php /*?> <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> <?php echo trans('label.btn_add_asset'); ?></button><?php */?>
					  <button type="button" class="btn btn-primary" id="btn_add_asset"><i class="fa fa-plus"></i> <?php echo trans('label.btn_add_asset'); ?></button>
                    <button type="button" id="removeassets" class="btn btn-warning" ><i class="fa fa-minus"></i> <?php echo trans('label.btn_remove_asset'); ?></button>
                  
                    </div>
                </div>
            
       
                <div class="col-sm-6">
                    <label class="col-md-3 control-label" for=""></label>

                         <?php //echo $formAction;
                    //echo count($assets);
                    $cuassetsdata_idarr = isset($contractdata[0]['asset_id']) ? json_decode($contractdata[0]['asset_id'], true) : [];
                    // print_r($cuassetsdata_idarr);
                    ?>
                    <select id="asset_id" name="asset_id[]" size="10" multiple style="width:250px" >
                    <?php
                        if ($formAction == "edit"){
                            if (is_array($assets) && count($assets) > 0){
                                foreach ($assets as $asset){
                                    ?>
                                        <option value="<?php echo $asset['asset_id'] ?>"  > <?php echo $asset['asset_tag'] ?> </option>
                                <?php 
                                }
                            }
                        }
                    ?>
                    </select>
                </div>
                
<!-- Model for add asset -->

<!-- Modal -->
<div id="myModalAsset" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo trans('label.lbl_assets'); ?></h4>
            </div>
            <div class="modal-body">
			 <div style="overflow-y: scroll;height: 300px;">
                     <form name="contractassetform" id="contractassetform" method="post">
					 
						<div class="input-group">
							<input type="text" class="form-control input-sm" placeholder="Search..." name="searchkeyword" id="searchkeyword" onKeyUp="return searchRecords('contractassetlist()');" onkeydown="if (event.keyCode == 13) { return false; }" style="z-index: 0;"/>
						</div>
						
					  <div id="modal_data"></div>
					 </form>
				</div>
              <?php /*?>  <div style="overflow-y: scroll;height: 300px;">
                     <form name=myForm method="post">
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

                                    if (is_array($assets) && count($assets) > 0)
                                    {
                                        foreach ($assets as $i => $asset)
                                        { ?>
                                            <tr data-val="value">

                                                <td class="checkbox_column">
                                                    <div class="checkbox-custom mb5">
                                                        <?php $num = $i + 1;?>
                                                        <input type="checkbox" name="asset_id[]" class="assetChk " id="<?php echo 'credChk'.$num ?>" data-asset-tag="<?php echo $asset['asset_tag']; ?>" value="<?php echo $asset['asset_id'] ?>" data-temp_name="<?php echo $asset['asset_id'] ?>">
                                                        <label for="<?php echo 'credChk'.$num; ?>"></label>
                                                    </div>
                                                </td>
                                                <?php //echo $i +  1?>
                                                <td><?php echo $asset['asset_tag']; ?></td>
                                                <td name="display_name" id="display_name"><?php echo $asset['display_name']; ?></td>
                                                <!--<td>// echo $asset['bv_id'] </td>-->
                                                <td><?php echo $asset['asset_status']; ?></td>
                                                <td><?php echo $asset['status']; ?></td>
                                            </tr>
                                        <?php
                                        }
                                    }
                                    ?>
                                 </tr>
                            </tbody>
                        </table>

                    </form>
                </div><?php */?>
            </div>
            <div class="modal-footer" >
                <button type="button"  class="btn btn-success" id="checkbox_test"  data-dismiss="modal" ><?php echo trans('label.btn_add'); ?></button>
            </div>
        </div>

    </div>
</div>
                <div class="col-md-6">
                    <div class="form-group required">
                        <label class="col-md-3 control-label" for="datecontractfrom"><?php echo trans('label.lbl_active_period_from'); ?></label>
                            <div class="col-md-3">
                                <input type="text" readonly="readonly" class="form-control input-sm" name="from_date" id="from_date" value="<?php if (isset($contractdata[0]['from_date']))
                                    {
                                        echo $contractdata[0]['from_date'];
                                    }
                                    ?>">
                                </div>
                  
                    
                            <label class="col-md-1 control-label" for="datecontractto"><?php echo trans('label.lbl_to'); ?></label>
                            <div class="col-md-3">
                                <input type="text" readonly="readonly" class="form-control input-sm" name="to_date" id="to_date" value="<?php if (isset($contractdata[0]['to_date']))
                                {
                                    echo $contractdata[0]['to_date'];
                                }
                                ?>">
                            </div>
                            
                            
                    </div>
                   
                    <div class="form-group required">
                      
                       <!-- <div class="form-group required">-->
                                    <label for="maintenance_cost" class="col-md-3 control-label"><?php echo trans('label.lbl_maintenance_cost'); ?></label>
                                    <div class="col-md-7">
                                        <input type="text" id="cost" name="cost" onkeypress="return isDecimalNumber(event, this)" class="form-control input-sm" value="<?php if (isset($contractdata[0]['cost']))
                                        {
                                            echo $contractdata[0]['cost'];
                                        }
                                        ?>">
                                </div>

                    </div>

                </div>
            
  

             <div class="row">
                <div class="col-md-12" style="margin-top: 2%">           
                       <!-- </div>-->
                        <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                            <div class="col-xs-2">
                            <?php if ($formAction == "renew")
{
    ?>
                              <button id="contractrenew" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_renew'); ?></button>
                               <?php }
else
{
    ?>
                                <?php if ($contract_id != '')
{
    ?>
                                <button id="contracteditsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_update'); ?></button>
                                <?php }
else
{
    ?>
                                <button id="contractaddsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_add'); ?></button>
                                <?php }
}?>
                            </div>
                            <div class="col-xs-2">
                                <button id="contract_reset" type="reset" class="btn btn-info btn-block"><?php echo trans('label.btn_reset'); ?></button>
                            </div>
                    </div>
                                </div>
                                </div>
                </form>
            </div>
        </div>
    </div>
</div>