<div class="panel-heading br-l br-r br-t">
    <span class="panel-title"> <?php 
    echo $contractdata[0]['contract_name'];?> #<?php echo $contractdata[0]['contractid'];?>
 </span>
</div>
<div class="panel-body">
    <h4>
    <?php echo trans('label.lbl_status');?>:
<?php 


if($contractdata[0]['contract_status']=='active'){?>
<span style="color:green"> <?php echo ucfirst($contractdata[0]['contract_status']);
}else{?>
    <span style="color:red"> <?php echo ucfirst($contractdata[0]['contract_status']);
}
?></span>
 <?php echo trans('label.lbl_valid_till');?> : <?php echo $contractdata[0]['to_date'];
 $loggedinUser = showuserid();?>
</h4>

        <div id="" class="col-md-12 prn-md animated fadeIn">
            <div class="panel">
 
                <div class="bg-light pv8 pr10   br-light">

                    <div class="row">
                        <div class="hidden-xs hidden-sm col-md-12 va-m">
                            <div class="btn-group">
                                <!--<button id="contract_edit" data-id="" type="button" class="btn btn-default light"><i class="fa fa-pencil"></i>
                                 EDIT  
                                </button>-->
                                <?php $id=$contractdata[0]['contract_id'];
                                if($contractdata[0]['renewed_to']!='' && $contractdata[0]['contract_status']=='active'){
                                    if(canuser('update','contract')){
                                    ?>
                                <button name="edit_b" id="edit_<?php echo $id;?>"  type="button" class="contract_edit btn btn-default light"  data-contractid="'<?php echo $id;?>'" ><i class="fa fa-pencil"></i> <?php echo trans('label.btn_edit');?>
                                
                                </button>
                                    <?php }?>
                            </div>
                            <?php 
                            }?>
                            <?php   if($contractdata[0]['contract_status']=='active' ){
                                    if(canuser('advance','associate_child_contract')){
                            ?>
                            <div class="btn-group">
                                <button type="button" class="btn btn-success light" data-toggle="modal" data-target="#myModalassociatechild"><i class="fa fa-check-square-o"></i> <?php echo trans('label.btn_associate');?>
                                </button>
                       
                            </div>
                            <?}?>
                            <?}?>
                            <?php if($contractdata[0]['renewed_to']!=''){?>
                            <?php if(canuser('advance','renew_contract')){?>
                            <div class="btn-group">
                                <button name="renew_b" id="renew_<?php echo $id;?>"  type="button" class="contract_renew btn btn-success light"  data-contractid="'<?php echo $id;?>'" ><i class="fa fa-check-square-o"></i><?php echo trans('label.btn_renew_contract');?>
                                
                                </button>
                            </div>
                            <?}?>
                            <?}?>
                            <?php if(canuser('delete','contract') || canuser('advance','view_attachment_contract') || canuser('advance','notify_owner_email') || canuser('advance','notify_vendor_email')){?>
                            <div class="btn-group">
                                    <button type="button" class="btn btn-default light dropdown-toggle ph8" data-toggle="dropdown">
                                    <?php echo trans('label.btn_action');?>
                                        <span class="caret ml5"></span>
                                    </button>
                                    <ul class="dropdown-menu pull-right" role="menu">
                                        <?php if(canuser('delete','contract')){?>
                                        <li>
                                            <a name="delete_b" id="delete_<?php echo $id;?>" type="button" class="contract_delete"  data-contractid="'<?php echo $id;?>'" ><i class="fa fa-trash"></i><?php echo trans('label.btn_delete_contract');?></a>
                                        </li>     
                                        
                                        <?php }
                                            if(canuser('advance','view_attachment_contract')){?>
                                        <li>
                                            <a href="#attachtable" id="attachtdoc"><i class="fa fa-list"></i>  <?php echo trans('label.lbl_attachment');?></a>
                                        </li> 
                                        <?php }?> 
                                          <!--   
                                        <li>
                                            <a href="#"><i class="fa fa-print"></i> Print Preview</a>
                                        </li>
                                      -->
                                        <?php if(canuser('advance','notify_owner_email') || canuser('advance','notify_vendor_email')){ ?>
                                        <li><strong><?php echo trans('label.lbl_notify'); ?></strong></li>
                                        <?php if(canuser('advance','notify_owner_email')){?>
                                        <li>
                                            <a class="ccursor actionsPr" id="notifyowner_<?php echo $loggedinUser."_".$contractdata[0]['contract_id']; ?>" data-toggle="modal"><i class="fa fa-share"></i> <?php echo trans('label.lbl_emailowner'); ?></a>
                                        </li>
                                        <?php }?>
                                        <?php if(canuser('advance','notify_vendor_email')){?>
                                        <li>
                                            <a class="ccursor actionsPr" id="notifyvendor_<?php echo $loggedinUser."_".$contractdata[0]['contract_id']; ?>" data-toggle="modal"><i class="fa fa-share"></i> <?php echo trans('label.lbl_emailvender'); ?></a>
                                        </li>   
                                        <?php }}?>
                                                                           
                                    </ul>
                            </div>                                        
                            <?php }?>
                        </div>                                  
                    </div>
                </div>
                <div class="panel-body pn br-n">        
                    <div class="tab-block mb25">
                        <ul class="nav nav-tabs tabs-bg tabs-border">
                       
                           
                            <li class="active contract_detailstab" >
                                <a href="#contract_details"  data-toggle="tab" aria-expanded="true"><i class="fa fa-check-square-o  text-purple"></i> <?php echo trans('label.lbl_contract_details');?></a>
                            </li>   
                            <?php if(canuser('advance','renew_contract')){?>
                            <li class="">
                                <a href="#renewal" data-toggle="tab" class="renewcontractdeatils"  id="<?php echo $contractdata[0]['contract_id']."_".$contractdata[0]['primary_contract'];?>"  aria-expanded="false"><i class="fa fa-info-circle  text-purple"></i> <?php echo trans('label.lbl_renewal_details');?></a>
                            </li>                                                     
                            <?php }?>
                            <?php if(canuser('advance','associate_child_contract')){?>
                            <li class="">
                                <a href="#childcontract" class="childcontracts" id="<?php echo $contractdata[0]['contract_id']."_".$contractdata[0]['parent_contract'];?>" data-toggle="tab" aria-expanded="true"><i class="fa fa-history text-purple"></i><?php echo trans('label.lbl_child_details');?> </a>
                                </a>
                            </li>
                            <?php }?>

                            <?php if(canuser('advance','view_history')){?>
                            <li class="">
                                <a href="#history" class="history" id="<?php echo $contractdata[0]['contract_id']."_".$contractdata[0]['parent_contract'];?>" data-toggle="tab" aria-expanded="true"><i class="fa fa-history text-purple"></i><?php echo trans('label.lbl_history');?> </a>
                                </a>
                            </li>
                            <?php }?>
                        </ul>
                        <div class="tab-content" style="min-height:200px;">
                            <div id="contract_details" class="tab-pane active">
                                 <?php //echo "<pre>"; print_r(@$pr_first_detail);  echo "</pre>"; ?>  
                            <!-- Details START -->
                            <div class="panel invoice-panel">
                                <div class="panel-body p20" id="invoice-item">
                                    <div class="row" id="
                                    -info">         
                                    </div>
                                    <div class="row" id="invoice-table">
                                        <div class="col-md-12">
                                        <table class="table mbn">
				
				<tbody>
					<tr>
						<td class="va-m fw600 text-muted" width="15%"><?php echo trans('label.lbl_contract_name');?></td>
						<td class="fs15 fw500" width="25%"><?php echo $contractdata[0]['contract_name'];?>
                        
						</td>
						<td class="va-m fw600 text-muted" width="15%"><?php echo trans('label.lbl_contract_type');?></td>
						<td class="fs15 fw500" width="25%"><?php echo $contractdata[0]['contract_type'];?></td>
					</tr>
					<tr>
						<td class="va-m fw600 text-muted"><?php echo trans('label.lbl_contract_id');?></td>
						<td class="fs15 fw500"><?php echo $contractdata[0]['contractid'];?></td>
						<td class="va-m fw600 text-muted"><?php echo trans('label.lbl_parent_contract');?></td>
						<td class="fs15 fw500"><?php echo $contractdata[0]['parent_name'];?></td>
					</tr>
					<tr>
						<td class="va-m fw600 text-muted"><?php echo trans('label.lbl_active_period');?></td>
                        <td class="fs15 fw500"><?php echo $contractdata[0]['from_date'];?>
                        <?php echo $contractdata[0]['to_date'];?></td>
						<td class="va-m fw600 text-muted"><?php echo trans('label.lbl_renewed_contract');?></td>
						<td class="fs15 fw500"><?php if($contractdata[0]['renewed'] == 'y'){ echo trans('label.lbl_yes'); }else{ echo trans('label.lbl_no'); }?></td>
					</tr>
					<tr>
						<td class="va-m fw600 text-muted"><?php echo trans('label.lbl_cost');?></td>
						<td class="fs15 fw500"><?php echo $contractdata[0]['cost'];?></td>
						<td class="va-m fw600 text-muted"><?php echo trans('label.lbl_created_by');?></td>
						<td class="fs15 fw500"><?php echo showname();?></td>
					</tr>
                    <tr>
						<td class="va-m fw600 text-muted"><?php echo trans('label.lbl_description');?></td>
						<td class="fs15 fw500"><?php echo $contractdata[0]['description'];?></td>
						<td class="va-m fw600 text-muted"><?php echo trans('label.lbl_support_details');?></td>
						<td class="fs15 fw500"><?php echo $contractdata[0]['support'];?></td>
					</tr>
                    
				</tbody>
                </table>
                <hr >
                <table class="table mbn" id="attachtable">
                <tbody>
                <h4><?php echo trans('label.lbl_vendor_details');?></h4>
                
					<tr>
                      
						<td class="va-m fw600 text-muted" width="15%"><?php echo trans('label.lbl_vendor_name');?></td>
						<td class="fs15 fw500" width="25%">
						<?php echo $contractdata[0]['vendor_name'];?>
						</td>
						<td class="va-m fw600 text-muted" width="15%"><?php echo trans('label.lbl_contact_person');?></td>
						<td class="fs15 fw500" width="25%"><?php echo $contractdata[0]['contact_person'];?></td>
					</tr>
					<tr>
						<td class="va-m fw600 text-muted"><?php echo trans('label.lbl_email_address');?></td>
						<td class="fs15 fw500"><?php echo $contractdata[0]['address'];?></td>
						<td class="va-m fw600 text-muted"><?php echo trans('label.lbl_phone');?></td>
						<td class="fs15 fw500"><?php echo $contractdata[0]['contactno'];?></td>
					</tr>
				</tbody>
            </table>
            <hr >
            <?php if(canuser('advance','view_attachment_contract')){?>
            <table class="table mbn" >
                <tbody>
                <h4><?php echo trans('label.lbl_attachment');?></h4>
                <?php if(canuser('advance','add_attachment_contract')){?>
					<tr>
						<td class="va-m fw600 text-muted" width="15%">  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#attachfile"><i class="fa fa-plus"></i><?php echo trans('label.lbl_attach_file');?></button> 
                      <!--  <img src="<?php //echo config("app.site_url") ?>/showattachment"  class="responsive" />-->
                      </td>
						<td class="fs15 fw500" width="25%">
						</td>
					</tr>
                <?php }?>
				</tbody>
            </table>
             <!-- begin: .tray-center -->
             <div class="col-sm-12 pl30">
                                                <div class="tray tray-center pn">   
                                                        <table class="table table-striped table-condensed">
                                                            <thead>
                                                                <th>#</th>
                                                                <th><?php echo trans('label.lbl_file');?></th>
                                                                <th><?php echo trans('label.lbl_date');?></th>
                                                                
                                                            </thead>
                                                            <tbody>
                                                          <tr>
                                                          <tbody>
                                                                                                       <?php
                                                            if($contractattachment)
                                                            {
                                                                foreach($contractattachment as $key => $attachment)
                                                                {
                                                                    $delete = '';
                                                                    if(canuser('advance','delete_attachment_contract')){
                                                                  $delete = '<span title = "'. trans('messages.msg_clicktodelete') .'" type="button" id="'.$attachment['attach_id'].'" data-id="'.$attachment['attach_id'].'" class="deleteAttachment"><i class="fa fa-trash-o mr10 fa-lg"></i></span>'; 
                                                                    }   
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $key+1; ?></td>
                                                                    <!--
                                                                    <td><?php echo "<a target='_blank' href='".config('enconfig.itamservice_url').$attachment['attachment_name']."'>" . trans('label.lbl_attachment') . ' ' .($key+1)."</a>"; ?></td>
                                                                    -->

                                                                    <td>
                                                                        <span class = "download_file text-primary" download_id="<?php echo $attachment['attach_id'];?>" style="cursor:pointer;" title="<?php echo trans("label.lbl_viewdownload");?>" download_path = "<?php echo $attachment['attachment_name'];?>"><?php echo trans('label.lbl_attachment') . ' ' .($key+1); ?></span>
                                                                    </td>

                                                                    <td><?php echo date("d M Y h:i A", strtotime($attachment['created_at'])); ?></td>
                                                                    <td><?php echo $delete; ?></td>
                                                                </tr>
                                                                    
                                                            <?php                                
                                                                }
                                                            }
                                                            else{
                                                                echo "<tr><td colspan='4'>There are no files attached</td></tr>";
                                                            }
                                                            ?>
                                                            </tbody>
                                                        </table>
                                                </div>
                                            </div>
                                            <!-- end: .tray-center -->    
           
            <hr>
            <?php }?>
           <table class="table mbn">
                <tbody>
                <h4><?php echo trans('label.lbl_associated_assets');?></h4>
                
                                <thead>
                                <tr>
                                    <th class="text-center"><?php echo trans('label.lbl_srno');?></th>
                                    <th><?php echo trans('label.lbl_asset_name');?></th> 
                                    <th><?php echo trans('label.lbl_asset_tag');?></th>    
                                    <th><?php echo trans('label.lbl_asset_type');?></th>        
                                    <th><?php echo trans('label.lbl_aquisition_date');?></th>       
                                    <th><?php echo trans('label.lbl_site');?></th>           
                                    <th><?php echo trans('label.lbl_purchase_cost');?></th>   
                                    <th><?php echo trans('label.lbl_action');?></th>  
                                </tr>
                                </thead>
                                <tbody>
                                    
                                    <tr>
                                    <?php 
                              
                                    if(is_array($assets) && count($assets)>0)
									{
                                        $i = 1;
										foreach($assets  as $asset)
										{
                                            
                                    ?>
                                        <td class="text-center"><?php echo $i++;?></td>
                                        <td><a href="<?php echo config('app.site_url') .'/assets/'. $asset['asset_id'].'/'.$asset['ci_templ_id'];?>" target="_blank"><?php echo $asset['display_name']; ?></a>
                                        <td ><?php echo $asset['asset_tag'];?></td>
                                        <td><?php echo $asset['ci_templ_type'];?></td>
                                        <td><?php echo $asset['acquisitiondate'];?></td>
                                        <td><?php echo $asset['location_id'];?></td>
                                        <td><?php echo $asset['purchasecost'];?></td>
                                        <td>
                                        <?php $id=$contractdata[0]['contract_id'];?>

                                        <?php if(canuser('advance','remove_asset_contract')){ ?>
                                        <a name="delete_c" id="delete_<?php echo $id;?>_<?php echo $asset['asset_id'];?>" type="button" class="asset_delete"  data-contractid="'<?php echo $id;?>'" ><i class="fa fa-trash"></i></a>
                                        <?php }?>
                                        </td>
                                    </tr>		
                                    <?php 
                                    
                                    }
                                }
                                    ?>		
                              
                           
				</tbody>
			</table>
                                        </div>
                                    </div>
                                 

                                </div>
                            </div>                            
                            <!-- Details END -->

                            </div>
                            <div id="renewal" class="tab-pane " <?php if(!canuser('advance','renew_contract')){?> style="display:none;" <?php }?>>
                                  

                            </div>                                
                                   
                         

                         <!-- ==============Send Mail Form=============-->

<!--
                         <div id="emailowner" class="modal fade" role="dialog">
                                        <div class="modal-dialog">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title"><span id="modal-title_actions"> <?php echo trans('label.lbl_notification_vendor');?></span>  : <?php echo isset($contractdata[0]['contract_name']) ?  $contractdata[0]['contract_name'] : "";?></h4>
                                                </div>
                                                <div class="modal-body">   
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="hidden alert-dismissable" id="msg_modal"></div>
                                                    </div>
                                                </div>       
                         <form class="form-horizontal" name="formsendmail" id="formsendmail">
                         <input id="contract_id" name="contract_id" type="hidden" value="<?php echo isset($contractdata[0]['contract_id']) ?>">
                         <input type="text" id="mail_notification_to" name="mail_notification_to" class="form-control input-sm" value="" >
                         <input type="text" id="mail_notification_subject" name="mail_notification_subject" class="form-control input-sm" value="" >
                         <input type="text" id="comment" name="comment" class="form-control input-sm" value="" >
                         <button id="sendmailsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_add'); ?></button>
                         </form>
                         </div>
                         </div>-->
                    <!--====================Send mail End======================-->
                  <!--  <div id="emailowner" class="modal fade" role="dialog">
                                        <div class="modal-dialog">
                                            <form class="form-horizontal" name="formsendmail" id="formsendmail">
                                                <!-- Modal content
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title"><span id="modal-title_actions">
                                                    <?php echo trans('label.lbl_notification_owner');?></span>  : <?php echo isset($contractdata[0]['contract_name']) ?  $contractdata[0]['contract_name'] : "";?></h4>
                                                </div>
                                                <div class="modal-body">   
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="hidden alert-dismissable" id="msg_modal"></div>
                                                    </div>
                                                </div>                                                                                                  
                                                   
                                                     <input type="hidden" id="contract_id" name="contract_id">
                                                    <input type="hidden" id="user_id" name="user_id">
                                                    <input type="hidden" id="action" name="action">                             
                                                    <input type="hidden" id="notify_to_id" name="notify_to_id">                                 
                                                    <div class="checkbox-custom checkbox-info mb5">
                                                        <input  type="checkbox"  class="selectDeselectAll" id="enableMailNotificationCheck" name="mail_notification" value="y">
                                                        <label for="enableMailNotificationCheck"><strong> <?php echo trans('label.lbl_send_mail_notification');?></strong></label>
                                                    </div>  

                                                   <div class="form-group required enableMailNotification">
                                                  
                                                            <label for="inputStandard" class="col-md-12 control-label textalignleft"><?php echo trans('label.lbl_to');?></label>
                                                            <div class="col-md-12">
                                                                <input class="col-md-12 form-control" name="mail_notification_to"> 
                                                            </div>
                                                    </div>
                                                   <div class="form-group required enableMailNotification">
                                                    
                                                            <label for="inputStandard" class="col-md-12 control-label textalignleft"><?php echo trans('label.lbl_subject');?> </label>
                                                            <div class="col-md-12">
                                                            <input class="col-md-12 form-control" name="mail_notification_subject"> 
                                                            </div>
                                                    </div>                                                    
                                                    <div class="form-group required ">
                                                            <label for="inputStandard" class="col-md-12 control-label textalignleft"><?php echo trans('label.lbl_description');?> </label>
                                                            <div class="col-md-12">
                                                                <textarea class="col-md-12 form-control" name="comment"></textarea>
                                                            </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" id="submitAction" class="btn btn-success"><?php echo trans('label.btn_submit');?></button>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo trans('label.btn_close');?></button>
                                                </div>
                                            </div>
                                        </form>
										</div>
										</div> correct code-->
                            <div id="childcontract" class="tab-pane" <?php if(!canuser('advance','associate_child_contract')){?> style="display:none;" <?php }?>>
                           
                           

                            </div>
                            <?php if(canuser('advance','view_history')){?>
                            <div id="history" class="tab-pane">
                                <!--<p><b>History - </b></p>-->
                                <div class="mt30 timeline-single" id="timeline">
                            <?php 
                         
                            if($contracthistorylog)
                            {
                               // echo "<pre>"; print_r($prpohistorylog);echo "</pre>";
                                foreach($contracthistorylog as $history)
                                {
                                    if($history['history_date'])
                                    {
                            ?>
                                    <div class="timeline-divider mtn">
                                        <div class="divider-label"><?php echo $history['history_date']; ?></div>
                                        <!--<div class="pull-right">
                                            <button id="timeline-toggle" class="btn btn-default btn-sm">
                                                <span class="glyphicons glyphicons-show_lines fs16"></span>
                                            </button>
                                        </div>-->
                                    </div>
                                <?php } ?>

                                    <div class="row">
                                        <div class="col-sm-6 right-column">
                                            <div class="timeline-item">
                                                <div class="timeline-icon">
                                                    <?php 
                                                    $default = "glyphicons glyphicons-edit text-warning";
                                                    $reason = "<br>";
                                                    if($history['action'] == "renewed" || $history['action'] == "associatedchild")
                                                    {
                                                        $default = "glyphicons glyphicons-circle_info text-primary";
                                                        $reason .= "<strong>". trans('label.lbl_reason') .": </strong>".$history['comment'];
                                                    }
                                                    if($history['action'] == "created" || $history['action'] == "updated")
                                                    {
                                                        $default = "glyphicons glyphicons-check text-success";
                                                        $reason .= "<strong>". trans('label.lbl_reason') .": </strong>".$history['comment'];
                                                    }
                                                    if($history['action'] == "deleted")
                                                    {
                                                        $default = "glyphicons glyphicons-remove text-danger";
                                                        $reason .= "<strong>". trans('label.lbl_reason') .": </strong>".$history['comment'];
                                                    }
                                                    
                                                    ?>
                                                     
                                                    

                                                    <span class="<?php echo $default;?>"></span>
                                                </div>
                                                <div class="panel">
                                                    <div class="panel-body p10">
                                                        <strong><?php echo date("d M Y h:i A", strtotime($history['created_at'])); ?></strong> 
                                                        <blockquote class="mbn ml10">
                                                            <?php 
                                                                echo $history['details'];
                                                            //echo "[ ".ucwords($history['action'])." ]";
                                                                echo $reason;
                                                            ?>
                                                        <small>
															<?php 
																if(isset($history['created_by_name']['firstname'])) $fname = $history['created_by_name']['firstname'];
																else $fname = '';
																if(isset($history['created_by_name']['lastname'])) $lname = $history['created_by_name']['lastname'];
																else $lname = '';
																
																echo $fname." ".$lname;
															?>
														</small>    
                                                        <p></p>
                                                            </blockquote>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                            
                                <?php
                                    }   
                                }
                                else{
                                    echo trans('messages.msg_norecordfound');
                                }
                                ?>
                                </div>

                            </div>
                            <?php }?>
                        </div><!--tab-content -->
                    </div><!--tab-block mb25-->
                </div><!--panel-body pn br-n-->
            </div><!--panel-->
        </div><!--podetails_page-->
</div>
        <!-- Modal -->
                        <div id="myModalassociatechild" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-lg">

                            <!-- Modal content-->
                            <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title"><?php echo trans('label.lbl_contract');?></h4>
                            </div>
                            <div class="modal-body">
                            <div>
                <form name="associateform" id="associateform" method="post" >
               
                <input id="parent_contract" name="parent_contract" type="hidden" value="<?php echo $contractdata[0]['contract_id'];?>">
                <table class="table table-striped table-bordered table-hover table-responsive ">
    <thead>
        <tr>
            <th class="checkbox_column">
                <div class="checkbox-custom mb5 checkbox-info">
                    <input type="checkbox" class="" id="associateCheckAll" value="6">
                    <label for="associateCheckAll"></label>
                </div>
                </th>
            <th><?php echo trans('label.lbl_contractid');?></th> 
            <th><?php echo trans('label.lbl_contract_name');?></th>    
            <th><?php echo trans('label.lbl_active_from');?></th>   
            <th><?php echo trans('label.lbl_expires_on');?></th>  
            <th><?php echo trans('label.lbl_contract_status');?></th>
            <th><?php echo trans('label.lbl_description');?></th>  
        </tr>
    </thead>
    <tbody>
                        <tr>
                
                    <!--<td class="srno">1</td>-->
                    <?php
                    //print_r($associatechildcontracts);
                    //echo "Hello";
                    if (is_array($associatechildcontracts) && count($associatechildcontracts) > 0)
                    {         
                        foreach($associatechildcontracts as $i => $associatechildcontract)
                        {       
                  
                   ?>
                    <tr data-val="value">
                
                    <td class="checkbox_column">
                        <div class="checkbox-custom mb5">
                        <?php 
                        
                        $num = $i +  1;  ?>
                            <input type="checkbox" name="associates_chk[]" class="associateChk " id="<?php echo 'credChk'.$num  ?>" data-contract-name="<?php echo $associatechildcontract['contractid']; ?>" value="<?php echo $associatechildcontract['contract_id']?>" data-temp_name="<?php echo $associatechildcontract['contract_id']?>">
                            <label for="<?php echo 'credChk'.$num ; ?>"></label>                       
                        </div>
                    </td>
                        <?php //echo $i +  1?>
                        <td><?php echo $associatechildcontract['contractid']; ?></td>
                        <td name="contract_name" id="contract_name"><?php echo $associatechildcontract['contract_name']; ?></td>
                        <td><?php echo $associatechildcontract['from_date']; ?></td>
                        <td><?php echo $associatechildcontract['to_date']; ?></td>
                        <td><?php echo $associatechildcontract['contract_status']; ?></td>
                        <td><?php echo $associatechildcontract['description']; ?></td>
                    </tr>
                    <?php
                        }
                    }
                ?>
                </tr>               
    </tbody> 
  
</table>


                </form>
 
        </div>
               </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success"id="checkbox_associatechild"  data-dismiss="modal"><?php echo trans('label.btn_add');?></button>
                            </div>
                            </div>

                        </div>
                        </div>
                        <!--Modal End-->  
  <!--Modal start-->
<div id="notifyowner" class="modal fade" role="dialog">
<div class="modal-dialog">
    <form class="form-horizontal" id="formsendmail_notifyowner">
        <!-- Modal content-->
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title"><span id="modal-title_actions">
            <?php echo trans('label.lbl_notification_owner');?></span>  : <?php echo isset($contractdata[0]['contract_name']) ?  $contractdata[0]['contract_name'] : "";?></h4>
        </div>
        <div class="modal-body">   
        <div class="row">
            <div class="col-md-12">
                <div class="hidden alert-dismissable" id="msg_modal_notifyowner"></div>
            </div>
        </div>                                                                                                  
            <input type="hidden" id="contract_id" name="contract_id" value="<?php echo $contractdata[0]['contract_id']?>">
          
            <input type="hidden" id="user_id" name="user_id" value="<?php echo $loggedinUser;?>">
             <input type="hidden" id="action" class="action" name="action" value="notifyowner">                                    
            <div class="checkbox-custom checkbox-info mb5">
                <input  type="checkbox"  class="selectDeselectAll" id="enableMailNotificationCheck" name="mail_notification" value="y">
                <label for="enableMailNotificationCheck"><strong> <?php echo trans('label.lbl_send_mail_notification');?></strong></label>
            </div>       

            <div class="form-group required enableMailNotification">
                    <label for="inputStandard" class="col-md-12 control-label textalignleft"><?php echo trans('label.lbl_to');?></label>
                    <div class="col-md-12">
                        <input class="col-md-12 form-control" name="mail_notification_to"> 
                    </div>
            </div>
            <div class="form-group required enableMailNotification">
                    <label for="inputStandard" class="col-md-12 control-label textalignleft"><?php echo trans('label.lbl_subject');?> </label>
                    <div class="col-md-12">
                    <input class="col-md-12 form-control" name="mail_notification_subject"> 
                    </div>
            </div>                                                    
            <div class="form-group required ">
                    <label for="inputStandard" class="col-md-12 control-label textalignleft"><?php echo trans('label.lbl_description');?> </label>
                    <div class="col-md-12">
                        <textarea class="col-md-12 form-control" name="comment"></textarea>
                    </div>
            </div>
        </div>
        <div class="modal-footer">

            <button type="button" id="submitAction" class="btn btn-success"><?php echo trans('label.btn_submit');?></button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo trans('label.btn_close');?></button>
        </div>
    </div>
</form>
</div>
</div> 
 <!--Modal End-->

  <!--Modal start-->
                        <div id="attachfile" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title"><?php echo trans('label.lbl_attach_file');?></h4>
                               
                            </div>
                            <div class="modal-body">
                            <!--<form method="post" id="attachfile" enctype="multipart/form-data"  action="/attachfile">-->
                            <!--<form method="post" id="attachfile" enctype="multipart/form-data">-->
                            <!--3-3-2020<form method="post" enctype="multipart/form-data"  action="/attachfile">-->
                            
                            <form method="post" class="add_attachment_contract" enctype="multipart/form-data" action="/add_attachment_contract">
                        <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">
                        <input type="hidden" id="contract_id" name="contract_id" class="form-control input-sm" value="<?php echo isset($contractdata[0]['contract_id']) ? $contractdata[0]['contract_id'] : ''; ?>">
                        <input type="hidden" id="contract_details_id" name="contract_details_id" class="form-control input-sm" value="<?php echo isset($contractdata[0]['contract_details_id']) ? $contractdata[0]['contract_details_id'] : ''; ?>">
                       
                          <input type="file" name="attachments[]" id="attachments" multiple="multiple"></i> 
						  <span><?php echo trans('messages.msg_allowed_ext');?></span><br>
                          <input type="submit" name="upload" id="upload" value="Upload" class="btn btn-primary">
						  
                           <div class="modal-footer">
                           

                            </form>
                            </div>
                         
                        
                            </div>
                            </div>

                        </div>
                        </div>
                          <!--Modal End-->
                          
                              <!--Modal start-->
                       
                        <div id="notifyvendor" class="modal fade" role="dialog"> 
                                        <div class="modal-dialog">
                                            <form class="form-horizontal" id="formsendmail_notifyvendor">
                                                <!-- Modal content -->
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title"><span id="modal-title_actions"> <?php echo trans('label.lbl_notification_vendor');?></span>  : <?php echo isset($contractdata[0]['contract_name']) ?  $contractdata[0]['contract_name'] : "";?></h4>
                                                </div>
                                                <div class="modal-body">   
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="hidden alert-dismissable" id="msg_modal_notifyvendor" ></div>
                                                    </div>
                                                </div>                                                                                                  
                                                <input type="hidden" id="contract_id" name="contract_id" value="<?php echo $contractdata[0]['contract_id']?>">
                                                    <input type="hidden" id="user_id" name="user_id" value="<?php echo $loggedinUser;?>">
                                                    <input type="hidden" id="action" class="action" name="action" value="notifyvendor">                             
                                                    <input type="hidden" id="notify_to_id" name="notify_to_id" value="">                                
                                                    <div class="checkbox-custom checkbox-info mb5">
                                                        <input  type="checkbox"  class="selectDeselectAll" id="enableMailNotificationCheck" name="mail_notification" value="y">
                                                        <label for="enableMailNotificationCheck"><strong> <?php echo trans('label.lbl_send_mail_notification');?></strong></label>
                                                    </div>       

                                                    <div class="form-group required enableMailNotification">
                                                            <label for="inputStandard" class="col-md-12 control-label textalignleft"><?php echo trans('label.lbl_to');?> </label>
                                                            <div class="col-md-12">
                                                                <input class="col-md-12 form-control" name="mail_notification_to"> 
                                                            </div>
                                                    </div>
                                                    <div class="form-group required enableMailNotification">
                                                            <label for="inputStandard" class="col-md-12 control-label textalignleft"><?php echo trans('label.lbl_subject');?> </label>
                                                            <div class="col-md-12">
                                                            <input class="col-md-12 form-control" name="mail_notification_subject"> 
                                                            </div>
                                                    </div>                                                    
                                                    <div class="form-group required ">
                                                            <label for="inputStandard" class="col-md-12 control-label textalignleft"><?php echo trans('label.lbl_description');?> </label>
                                                            <div class="col-md-12">
                                                                <textarea class="col-md-12 form-control" name="comment"></textarea>
                                                            </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">

                                                    <button type="button" id="submitAction" class="btn btn-success"><?php echo trans('label.btn_submit');?></button>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo trans('label.btn_close');?></button>
                                                </div>
                                            </div>
                                        </form>
                                        </div>
                                        </div>
                          <!--Modal End-->