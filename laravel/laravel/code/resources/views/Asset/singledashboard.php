<div class="panel">
    <div class="panel-heading">
        <span class="panel-title"><?php 
                    $disnm = "";
                        if(trim($editdata[0]['display_name']))
                        {
                            $disnm = " (".$editdata[0]['display_name'].")";
                        }
              echo $editdata[0]['asset_tag'].$disnm; ?></span>
        <div class="topbar-right">
        <div class="btn-group">
          <button id="timeline-toggle" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
          <span class="glyphicons glyphicons-show_lines fs16"></span>
          </button>
          <ul class="dropdown-menu pull-right" user="menu">
            <li id="goback">
              <a><span  class=""> <?php echo $title ;?> <?php  echo trans('label.lbl_list') ?></span></a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="panel-body">
      <form method="post" name="assetfrm" id="assetfrm">
          <div class="row">
              <input type="hidden" name="ci_templ_id" id="ci_templ_id" value="<?php echo $ci_templ_id ?>" />
              <input type="hidden" name="ci_type_id" id="ci_type_id" value="<?php echo $ci_type_id ?>" />
              <input type="hidden" name="title" id="title" value="<?php echo $title ?>" />
              <input type="hidden" name="asset_id" id="asset_id" value="<?php echo $asset_id ?>" />
              <input type="hidden" name="bv_id" id="bv_id" value="<?php echo $editdata[0]['bv_id'] ?>" />
              <input type="hidden" name="location_id" id="location_id" value="<?php echo $editdata[0]['location_id'] ?>" />
              <input type="hidden" name="tag" id="tag" value="<?php echo $editdata[0]['asset_tag'].$disnm ?>" />
              <input type="hidden" name="parent_asset_id" id="parent_asset_id" value="<?php echo $editdata[0]['parent_asset_id']?>" />
              <input type="hidden" name="department_id" id="department_id" value="<?php echo $editdata[0]['department_id']?>" />
              <?php 
              if(!empty($historydata)){?>
                <input type="hidden" name="requestername_id" id="requestername_id" value="<?php echo $historydata['requestername_id']?>" />
                <?php } ?>


              <?php
                if(isset($po_id) && $po_id != '' && $po_id != '0'){
              ?>
                    <input type="hidden" name="po_id" id="po_id" value="<?php echo $po_id ?>" />
              <?php
                }
              ?>

              <div class=" panel-visible">
                <div class="panel-body pn br-n">        
                  <div class="tab-block mb25">
                    <?php if(canuser('update','asset')) { ?>
                    <div class="btn-group">
                        <button id="<?php echo $asset_id;?>" type="button" class="btn btn-default light asset_ed"><i class="fa fa-pencil"></i> <?php echo trans('label.btn_edit');?> 
                        </button>                                
                    </div>
                    <?php }?>
                    <div class="topbar-right">
                      <div><strong><?php echo trans('label.lbl_asset_status');?> : <?php echo trans('label.'.$editdata[0]['asset_status'])?></strong></div>
                    </div>    


                      <?php if(canuser('add','assetattach') || canuser('add','assetrelationship') || canuser('update','asset') || canuser('delete','asset')) { ?>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default light dropdown-toggle ph8" data-toggle="dropdown">
                            <span class="fa fa-tags"></span>
                            <span class="caret ml5"></span>
                            </button>
                            <ul class="dropdown-menu pull-right" role="menu">
                                <?php if(canuser('add','assetattach')) { ?>
                                <li>
                                    <a class="actionsPo ccursor asset_attach"><i class="fa fa-paperclip"></i> <?php echo trans('label.lbl_attach_asset');?> </a>
                                </li>
                                <?php }?>
                                <?php if(canuser('add','assetrelationship')) { ?>
                                <li>
                                    <a class="actionsPo ccursor addrelationship"><i class="fa fa-random"></i> <?php echo trans('label.lbl_addrelationship');?> </a>
                                </li>
                                <?php }?>
                                <?php if(canuser('update','asset')) { ?>
                                <li>
                                    <a id="<?php echo $editdata[0]['asset_status'] ?>" class="actionsPo ccursor change_status"><i class="fa fa-stack-exchange"></i> <?php echo trans('label.lbl_change_status');?> </a>
                                </li>
                                <?php }?>
                                <?php if(canuser('delete','asset')) { ?>
                                <li>
                                    <a class="actionsPo ccursor asset_de" id="<?php echo $asset_id ?>"><i class="fa fa-trash"></i> <?php echo trans('label.lbl_delete');?> </a>
                                </li>
                                <?php }?>
                            </ul>
                        </div>   
                      <?php }?>

                      <ul class="nav nav-tabs tabs-bg tabs-border">

                        <li class="active">
                              <a href="#ci_info" data-toggle="tab" aria-expanded="false"><i class="fa fa-info-circle  text-purple"></i> 
                                  <?php echo trans('label.lbl_ci_info');?>
                              </a>
                        </li>
                        <?php if(canuser('view','assetattach')) { ?>    
                        <li class="">
                              <a href="#hardware"  data-toggle="tab"  aria-expanded="true"><i  class="fa fa-hdd-o text-purple"
                                  ></i> <?php echo trans('label.lbl_hardware');?>
                              </a>
                        </li> 
                        <?php }?>
                        <?php if(in_array($title,array('Server','Desktop','Laptop'))){?>   
                        <li class="">
                              <a href="#software" id="callsoftware"data-toggle="tab" aria-expanded="true"><i class="fa fa-desktop text-purple"></i> 
                                  <?php echo trans('label.lbl_software');?>
                              </a>
                        </li>  
                        <?php } ?> 

                            <?php if(canuser('view','assetrelationship')) { ?>    
                           <li class="">
                              <a href="#relationship" id="callrelationship" data-toggle="tab" aria-expanded="true"><i class="fa fa-users text-purple"></i> 
                                  <?php echo trans('label.lbl_relationship');?>
                              </a>
                          </li> 
                          <?php }?>
						              <li class="">
                              <a href="#contract" id="callassetcontract" data-toggle="tab" aria-expanded="true"><i class="fa fa-edit text-purple"></i> <?php echo trans('label.lbl_associated_contract');?>
                              </a>
                          </li>                                                 
                          <?php if(canuser('advance','view_history')){?>
                          <li class="">
                              <a href="#history" id="callhistory" data-toggle="tab" aria-expanded="true"><i class="fa fa-history text-purple"></i> <?php echo trans('label.lbl_history');?>
                              </a>
                          </li>
                          <?php }?>
                          <?php if(canuser('advance','view_history')){?>
                          <li class="">
                              <a href="#assignedhistory" id="callassethistory" data-toggle="tab" aria-expanded="true"><i class="fa fa-history text-purple"></i> Assets Tracking
                              </a>
                          </li>
                          <?php }?>
                      </ul>
                      <div class="tab-content">
                            <div id="ci_info" class="tab-pane active">
                               <div class="form-group col-md-6 ">
                                    <label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_title');?></label>
                                    <div class="col-md-8">
                                        <?php echo isset($editdata[0]['display_name']) ? $editdata[0]['display_name'] : ''; ?>
                                    </div>
                                </div>

                                <?php
                                if(_isset($assetdata,'attributes')){
                                    $type = $assetdata['type'];
                                if(is_array($assetdata['attributes']) && count($assetdata['attributes']) > 0)
                                {
                                    
                                    foreach($assetdata['attributes'] as $attr)
                                    {
                                            if($type == "default")
                                                $cnm = trans('citree.'.str_replace(" ","_", $attr['attribute']));
                                            else
                                                $cnm =  $attr['attribute'];

                                            $customcss = "";
                                            if(is_array($attr['validation']))
                                            {
                                                if(in_array("required", $attr['validation']))
                                                    $customcss = "required";
                                            }
                                    ?>  
                                <div class="form-group col-md-6" id = "var<?php echo $attr['veriable_name'];?>">
                                    <label  class="col-md-4 control-label"><?php echo  $cnm;?></label>
                                    <div class="col-md-8">
                                        <?php  
                                        if($attr['unit'] != "" || $attr['input_type']  == "date")
                                        {
                                            echo $cuval =  isset($asset_details[$attr['veriable_name']]) ? $asset_details[$attr['veriable_name']] : '--';
                                            if($attr['input_type'] == "date")
                                            {
                                                //echo '<i class="fa fa-calendar"></i>';
                                                //$vstring .= $attr['veriable_name'].'#/#';
                                            }
                                            else   
                                                if($cuval != "--" && $cuval != "") 
                                                echo $attr['unit']; 
                                       
                                        }
                                        else
                                        {
                                           echo isset($asset_details[$attr['veriable_name']]) ? $asset_details[$attr['veriable_name']] : '--'; 
                                        } ?>
                                       
                                    </div>
                                </div>
                                <?php } 
                                }
                                } ?>

                                <div class="form-group col-md-6 ">
                                    <label for="asset_sku" class="col-md-4 control-label"><?php echo 'Sku code';?></label>
                                    <div class="col-md-8">
                                        <?php echo isset($editdata[0]['asset_sku']) ? $editdata[0]['asset_sku'] : ''; ?>
                                    </div>
                                </div>

                                <?php if(isset($editdata[0]['po_id'])) {?>
                                <div class="form-group col-md-6 ">
                                    <label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_poname');?></label>
                                    <div class="col-md-8">

                                      <a href="<?php echo isset($editdata[0]['po_id']) ? config('app.site_url').'/purchaseorders/'.$editdata[0]['po_id'] : '#'; ?>" target="_blank" >
                                        <?php echo isset($editdata[0]['po_name']) ? $editdata[0]['po_name'] : ''; ?>
                                      </a>
                                    </div>
                                </div>
                                <?php }?>
                                
                                <?php 
                                $assetvar = "";
                                if(is_array($assets) && count($assets) > 0)
                                {
                                    foreach($assets as $asset)
                                    { 
                                        $assetvar .= $asset['variable_name']."##";
                                    }
                                }

                                ?>
                                <div class="col-md-12"> 
                                    <h4><?php echo trans('label.lbl_asset_state');?></h4>
                                    <hr class="mt5 mb10" style="border-top: 2px solid #cccccc;">
                                </div>
                                <div class="form-group  col-md-6 ">
                                    <label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_businessvertical');?></label>
                                    <div class="col-md-8">
                                        <?php 
                                        if(is_array($bvdata) && count($bvdata) > 0)
                                        {
                                            foreach($bvdata as $bv)
                                            {
                                                echo isset($editdata[0]['bv_id']) && $editdata[0]['bv_id'] == $bv['bv_id'] ? $bv['bv_name'] : ''; 
                                             }
                                        }   
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group col-md-6 ">
                                    <label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_location');?></label>
                                    <div class="col-md-8">
                                            <?php 
                                            if(is_array($locdata) && count($locdata) > 0)
                                            {
                                                foreach($locdata as $loc)
                                                {
                                                    echo isset($editdata[0]['location_id']) && $editdata[0]['location_id'] == $loc['location_id'] ? $loc['location_name'] : ''; 
                                                }
                                            }   
                                            ?>
                                        
                                    </div>
                                </div>
                                <div class="form-group col-md-6 ">
                                    <label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_department');?></label>
                                    <div class="col-md-8">
                                            <?php 
                                            if(is_array($dept) && count($dept) > 0)
                                            {
                                                foreach($dept as $de)
                                                {
                                                    echo isset($editdata[0]['department_id']) && $editdata[0]['department_id'] == $de['department_id'] ? $de['department_name'] : ''; 
                                                }
                                            }   
                                            ?>
                                        
                                    </div>
                                </div>
                                <div class="col-md-12"> 
                                    <h4> <?php echo trans('label.lbl_asset_details');?></h4>
                                    <hr class="mt5 mb10" style="border-top: 2px solid #cccccc;">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_vendor_name');?></label>
                                    <div class="col-md-8">
                                        <?php 
                                        if(is_array($vendordata) && count($vendordata) > 0)
                                        {
                                            foreach($vendordata as $vendor)
                                            {
                                                echo isset($editdata[0]['vendor_id']) && $editdata[0]['vendor_id'] == $vendor['vendor_id'] ? $vendor['vendor_name'] : ''; 
                                            }
                                        }   
                                        ?>
                                    </div>
                                </div> 
                                <div class="form-group col-md-6 ">
                                    <label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_purchase_cost');?></label>
                                    <div class="col-md-8">
                                            <?php echo isset($editdata[0]['purchasecost']) ? $editdata[0]['purchasecost'] : '--'; ?>
                                    </div>
                                </div> 
                                <div class="form-group col-md-6 ">
                                    <label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_acquisition_date');?></label>
                                    <div class="col-md-8">
                                        <?php echo isset($editdata[0]['acquisitiondate']) ? $editdata[0]['acquisitiondate'] : '--'; ?>
                                    </div>
                                </div> 

                                <div class="form-group col-md-6 ">
                                    <label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_expiry_date');?></label>
                                    <div class="col-md-8">
                                        <?php echo isset($editdata[0]['expirydate']) ? $editdata[0]['expirydate'] : '--'; ?>
                                    </div>
                                </div>  

                                <div class="form-group  col-md-6 ">
                                    <label for="Title" class="col-md-4 control-label"><?php echo trans('label.lbl_warranty_expiry_date');?></label>
                                    <div class="col-md-8">
                                        <?php echo isset($editdata[0]['warrantyexpirydate']) ? $editdata[0]['warrantyexpirydate'] : '--'; ?>
                                    </div>
                                </div>  
                            </div>  

                            <div id="hardware" class="tab-pane">
                              
                                <?php if(is_array($assets) && count($assets) > 0)
                                {
                                    
                                    foreach($assets as $asset)
                                    { 
                                        $ty = $asset['type'];
                                        $assetvar .= $asset['variable_name']."##";
                                            if($ty == "default")
                                             $cnm = trans('citree.'.str_replace(" ","_", $asset['ci_name']));
                                            else
                                               $cnm = $asset['ci_name']; 
                                        ?>
                                        <div class="col-md-12"> 
                                            <h5><?php echo $cnm; ?></h5>
                                            <hr class="mt5 mb10" style="border-top: 1px solid #cccccc;">
                                        </div>
                                        <div class="col-md-12"> 
                                            <div class="emtblhscroll">
                                                <table width="100%" class="table table-bordered table-hover mb30 <?php echo $asset['ci_templ_id'];?>" cellspacing="0" cellpadding="0">
                                                    <thead>
                                                        <tr>
                                                    <?php 

                                                    if(is_array($asset['attributes']) && count($asset['attributes']) > 0)
                                                    {
                                                        foreach($asset['attributes'] as $attr)
                                                        { 
                                                            $unit = "";
                                                            if($attr['unit'] != '')
                                                                $unit = '('.$attr['unit'].')';

                                                            $customcss = "";
                                                            if(is_array($attr['validation']))
                                                            {
                                                                if(in_array("required", $attr['validation']))
                                                                    $customcss = "field-required";
                                                            }
                                                            ?>

                                                            <th class=""><label class="control-label"><?php 
                                                            echo $attr['attribute'] .''.$unit;?></label></th>
                                                <?php   }
                                                    }
                                                ?>      
                                                       
                                                        </tr>
                                                        </thead>
                                                        <?php 
                                                            $cn = 1;
                                                     $each_templ_id = isset($childdata[$asset['ci_templ_id']]) ? $childdata[$asset['ci_templ_id']] : '';
                                                     if($each_templ_id != "")
                                                            $cn = count($each_templ_id);

                                                        for($i = 1; $i <= $cn; $i++)
                                                        {
                                                            $j = $i - 1;    
                                                           $disnm = "";
                                                           //dd($childdata[$asset['ci_templ_id']][$j]['display_name']);
                                                        if(isset($childdata[$asset['ci_templ_id']][$j]['display_name']) && trim($childdata[$asset['ci_templ_id']][$j]['display_name']) != "")
                                                        {
                                                            $disnm = " (".$childdata[$asset['ci_templ_id']][$j]['display_name'].")";
                                                        } 
                                                        ?>
                                                       
                                                      <tr>
                                                      <td colspan="50">

                                                      <a target="_blank" href="<?php echo config('app.site_url')?>/assets/<?php if(isset($childdata[$asset['ci_templ_id']][$j]['asset_id'])) echo $childdata[$asset['ci_templ_id']][$j]['asset_id']; ?>/<?php if(isset($asset['ci_templ_id'])) echo $asset['ci_templ_id'] ?>"><?php if(isset($childdata[$asset['ci_templ_id']][$j]['asset_tag'])) echo $childdata[$asset['ci_templ_id']][$j]['asset_tag'].$disnm;?>
                                                        
                                                      </a>
                                                      </td>
                                                      </tr>


                                                        <tr id = "row-<?php echo $i; ?>">
                                                    <?php 
                                                    if(isset($asset['attributes']) && is_array($asset['attributes']) && count($asset['attributes']) > 0)
                                                    {
                                                        foreach($asset['attributes'] as $attr)
                                                        {   
                                                            ?>
                                                            <td>
                                                                
                                                                <?php echo isset($childdata[$asset['ci_templ_id']][$j]['asset_detailsarray'][$attr['veriable_name']]) ? $childdata[$asset['ci_templ_id']][$j]['asset_detailsarray'][$attr['veriable_name']] : '--'; ?>
                                                             
                                                            </td>
                                                <?php   }
                                                    }
                                                ?>      
                                                            <!--<td>
                                                                <input id="assets_ids" name="<?php echo $asset['ci_templ_id'].'#multiassetid'?>[]" type="hidden" value="<?php echo isset($childdata[$asset['ci_templ_id']][$j]['asset_id']) ? $childdata[$asset['ci_templ_id']][$j]['asset_id'] : ''; ?>">
                                                                <i class="fa fa-trash-o mr10 fa-lg remove" id="<?php echo isset($childdata[$asset['ci_templ_id']][$j]['asset_id']) ? $childdata[$asset['ci_templ_id']][$j]['asset_id'] : ''; ?>" title="Delete Asset"></i>
                                                            </td>-->
                                                        </tr>
                                                    <?php } ?>  
                                                    
                                                </table>
                                            </div>
                                        </div>       
                            <?php   }
                                }
                            ?>  
                         
                           
                            <div class="col-md-12"> 
                                <h4> <?php echo trans("label.Attached_Asset")?></h4>
                                <hr class="mt5 mb10" style="border-top: 2px solid #cccccc;">
                            </div>
                            <div class="col-md-12"> 

                            <?php 
                            $cn = 0;
                            $html = "";
                               //print_r($childdata);
                            if(isset($childdata) && is_array($childdata) && count($childdata) > 0){
                                $html = '<table width="100%" class="table table-bordered table-hover mb30  cellspacing="0" cellpadding="0">';
                                foreach($childdata as $chaild)
                                {
                                    
                                    foreach($chaild as $ch)
                                    {
                                         if(trim($ch['display_name']))
                                        {
                                            $dis_nm = " (".$ch['display_name'].")";
                                        }
                                        
                                        if(in_array($citemps[$editdata[0]['ci_templ_id']],array('server','desktop','laptop')))
                                        {      
                                                if(!in_array($citemps[$ch['ci_templ_id']], array('ethernet','hdd','ram')))
                                                {
                                                 
                                                     $cn++;
                                                     $html .='<tr><td width="60%">'.$ch['asset_tag'].$dis_nm.'</td><td><a href="javascript:void(0)" id="'.$ch['asset_id'].'" class="freeasset" > <img  title="'.trans("label.detach_assat").'" src="'.config('app.site_url').'/enlight/images/close1.png"></a></td></tr>';
                                                }
                                        }
                                        else
                                        {
                                             $cn++;
                                             $html .='<tr><td width="60%">'.$ch['asset_tag'].$dis_nm.'</td>';

                                             if(canuser('delete','assetattach')) { 
                                             $html .='<td><a href="javascript:void(0)" id="'.$ch['asset_id'].'" class="freeasset" > <img  title="'.trans("label.detach_assat").'" src="'.config('app.site_url').'/enlight/images/close1.png"></a></td>';
                                              }
                                             $html .='</tr>';
                                        }                                       
                                    }
                                }
                                $html .="</table>";
                                if($cn == 0)
                                {
                                    echo  trans('label.no_records');
                                }
                                else
                                {
                                    echo $html;
                                }
                            }
                            else
                            {
                                echo  trans('label.no_records');
                            }
                          //  echo '*****'.$cn;
                            
                            

                            ?>
                                    
                                </div>
                            </div>    
                        
                            <div id="software" class="tab-pane">
                              
                            </div>
                            <div id="relationship" class="tab-pane">
                               
                            </div>
                            <?php if(canuser('advance','view_history')){?>
                            <div id="history" class="tab-pane">
                                    
                            </div>
                            <?php }?>
                            <?php if(canuser('advance','view_history')){?>
                            <div id="assignedhistory" class="tab-pane">
                                    
                            </div>
                            <?php }?>
                            <div id="contract" class="tab-pane">
                                    
                            </div>
                      </div>
                    </div>
                  </div>  <!-- End panel-body pn br-n  --> 
              </div>
          </div>
      </form> 
    </div>
</div>

<script type="text/javascript">
    /*var vstring = "<?php //echo $vstring; ?>";
    var res = vstring.split("#/#");
    if(res.length > 0)
    {
        jQuery.each( res, function( i, val ) {
            if(val != "")
            {
                datetimecalendar(val);
            }
        });
    }
    datecalendar("calendar","class");*/

    var assetvar = "<?php echo $assetvar; ?>";
    //alert(assetvar);
    var res = assetvar.split("##");
    if(res.length > 0)
    {
        jQuery.each( res, function( i, val ) {
            if(val != "")
            {
                $('#var'+val).hide();
            }
        });
    }
</script>


