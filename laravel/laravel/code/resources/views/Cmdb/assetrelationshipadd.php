<div class="row">
    <div class="col-md-10">
        <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">
                <form class="form-horizontal"  name="addformassetrelationship" id="addformassetrelationship">
                    <input id="asset_id" name="asset_id" type="hidden" value="<?php echo $asset_id?>">
                    <div class="form-group required ">
                        <label for="relationship_type_id" class="col-md-3 control-label"><?php echo trans('label.lbl_selectrelation');?></label>
                        <div class="col-md-8">
                            <select data-placeholder="<?php echo trans('label.lbl_selectrelation');?>"  class="chosen-select form-control input-sm" name="relationship_type_id">
                                <option value="">[<?php echo trans('label.lbl_selectrelation');?>]</option>
                                <?php
                                if(isset($reltypelists) && is_array($reltypelists) && count($reltypelists) > 0)
                                {
                                    for($i=0;$i<count($reltypelists);$i++)
                                    {
                                        echo '<option value = "'. ($reltypelists[$i])['rel_type_id'] .'">'. ($reltypelists[$i])['rel_type'] .'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group required" >
                        <label for="email" class="col-md-3 control-label"><?php echo trans('label.lbl_selectcitypes');?></label>
                        <div class="col-md-8">
                            <select name="ci_templ_id" id="ci_templ_id" onchange="assetsofcitype(this);"  class="chosen-select">
                                <option value="">[<?php echo trans('label.lbl_selectcitypes');?>]</option>
                                <?php 
                                if(is_array($citemplates) && count($citemplates) > 0)
                                {
                                    foreach($citemplates as $citemp)
                                    {
                                        if(is_array($citemp['children']) && count($citemp['children']) > 0)
                                        {
                                            foreach($citemp['children'] as $ci)
                                            { ?>
                                            <option data-id = "<?php echo $ci['ci_templ_id'];?>" value="<?php echo $ci['variable_name']?>"><?php echo $ci['title']?></option>
                                           <?php }
                                        }
                                    }  
                                }
                            ?>
                            </select>
                        </div>
                    </div>
                    <span id="child_asset_id">
                        <div class="form-group required ">
                            <label for="child_asset_id" class="col-md-3 control-label"><?php echo trans('label.lbl_selectasset');?></label>
                            <div class="col-md-8">
                                <select data-placeholder="<?php echo trans('label.lbl_selectasset');?>"  class="chosen-select form-control input-sm" name="child_asset_id">
                                    <option value="">[<?php echo trans('label.lbl_selectasset');?>]</option>
                                </select>
                            </div>
                        </div>
                    </span>
                    <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                        <div class="col-xs-2">
                    
                            <?php if($asset_id != '') {?>
                            <button id="assetrelationshipaddsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_submit');?></button>
                            <?php } ?>
                        </div>
                        <div class="col-xs-2">
                            <button id="assetrelationship_reset" type="reset" class="btn btn-info btn-block"><?php echo trans('label.btn_reset');?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 