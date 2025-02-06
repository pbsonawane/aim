<div class="col-md-12 pl5-md animated fadeIn" style="min-height: 500px;">
    <!--<button type="button" class="btn btn-danger light btn-block compose-btn">Compose Message</button>-->
    <div class="panel">
        <div class="panel-body p1">
        <!--
            <div class="ph15 pv10 br-b br-light hidden">
                <div class="row table-layout">
                    <div class="col-md-12 va-m pn">
                        <button type="button" class="btn btn-danger light btn-block fw600">Compose Message
                        </button>
                    </div>
                    <div class="col-md-6 text-right hidden">
                        <div class="btn-group mr10">
                            <button type="button" class="btn btn-default light"><i class="fa fa-star"></i>
                            </button>
                            <button type="button" class="btn btn-default light"><i class="fa fa-calendar"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <h4 class="ph10 mt5 mb5"> Menu </h4>
            <ul class="nav nav-messages p5" role="menu">
                <li class="">
                    <a href="#" class="fw600 p8 animated animated-short fadeInDown">
                        <span class="fa fa-gear pr5"></span> Email Settings
                    </a>
                </li>
                <li class="active">
                    <a href="#" class="text-dark fw600 p8 animated animated-short fadeInUp">
                        <span class="fa fa-envelope pr5"></span> Messages
                        <span class="pull-right lh20 h-20 label label-warning label-sm">12</span>
                    </a>
                </li>
                <li class="">
                    <a href="#" class="fw600 p8 animated animated-short fadeInUp">
                        <span class="fa fa-user pr5"></span> Social
                        <span class="pull-right lh20 h-20 label label-warning label-sm">9</span>
                    </a>
                </li>
                <li class="">
                    <a href="#" class="fw600 p8 animated animated-short fadeInUp">
                        <span class="fa fa-trash-o pr5"></span> Spam
                        <span class="pull-right lh20 h-20 label label-muted label-sm">14</span>
                    </a>
                </li>
            </ul>
            <h4 class="ph10 mv15"> Tags </h4>
            <hr class="mn br-light">
            <ul class="nav nav-messages p5" role="menu">
                <li class="">
                    <a href="#" class="text-dark fw600 p8 animated animated-short fadeInUp">
                        Clients
                        <span class="fa fa-circle text-warning fs14 pull-right lh20"></span>
                    </a>
                </li>
                <li class="">
                    <a href="#" class="text-dark fw600 p8 animated animated-short fadeInUp">
                        Contractors
                        <span class="fa fa-circle text-system fs14 pull-right lh20"></span>
                    </a>
                </li>
                <li class="">
                    <a href="#" class="text-dark fw600 p8 animated animated-short fadeInUp">
                        Employees
                        <span class="fa fa-circle text-primary fs14 pull-right lh20"></span>
                    </a>
                </li>
                <li class="">
                    <a href="#" class="text-dark fw600 p8 animated animated-short fadeInUp">
                        Suppliers
                        <span class="fa fa-circle text-alert fs14 pull-right lh20"></span>
                    </a>
                </li>
            </ul>-->

          
            <div class="ph15 pv10 br-b br-light">
                <div class="row table-layout">
                    <div class="col-md-12 va-m pn">
                        <select id="pr_filter_status" class="chosen-select" tabindex="5"  class="form-control input-sm" data-placeholder="<?php echo trans('label.lbl_selectfilter');?>" >
                            <option value=""></option>
                            <optgroup label="">
                                <option value=""><?php echo trans('label.lbl_allpr');?></option>
                            <optgroup>
                            <optgroup label="<?php echo trans('label.lbl_filterby_sts');?>">
                                <option value="open"><?php echo trans('label.lbl_open');?></option>
                                <option value="pending approval"><?php echo trans('label.lbl_pendingapprovalprs');?></option>
                                <option value="partially approved"><?php echo trans('label.lbl_partiallyapproved');?></option>    
                                <option value="approved"><?php echo trans('label.lbl_approvedprs');?></option>
                                <option value="cancelled"><?php echo trans('label.lbl_cancelledprs');?></option>
                                <option value="closed"><?php echo trans('label.lbl_closedprs');?></option>
                                <option value="rejected"><?php echo trans('label.lbl_rejectedprs');?></option>
                                <!--<option value="deleted">Deleted</option>-->
                            </optgroup>
                        </select>
                    </div>
                </div>
            </div>     
            <div class="apply-custom-vertical-scroll" style="height: 500px">                                       
            <?php
            $prs = $dbdata;
            // echo '<pre>';
            // print_r($dbdata);
            // echo '</pre>';
            // exit;

            if (is_array($prs['data']) && count($prs['data']) > 0)
            {
                foreach($prs['data'] as $i => $pr)                                
                { 
                    $pr_details = $pr['details'];
                    // print_r($pr_details);
                    if($pr_details)
                    {
                        //$pr_title = isset($pr_details['pr_title']) ? $pr_details['pr_title']: "";
                        $pr_req_date = isset($pr_details['pr_req_date']) ? $pr_details['pr_req_date']: "";
                        $pr_due_date = isset($pr_details['pr_due_date']) ? $pr_details['pr_due_date']: "";
                        $pr_requirement_for = isset($pr_details['pr_requirement_for']) ? $pr_details['pr_requirement_for']: "";
                        $pr_category = isset($pr_details['pr_category']) ? $pr_details['pr_category']: "";
                        $pr_project_category = isset($pr_details['pr_project_category']) ? $pr_details['pr_project_category']: "";

                        /**New For Project Name**/
                        $pr_project_name = isset($pr_details['pr_project_name_dd']) ? $pr_details['pr_project_name_dd']: "";
                        /**New Project End**/

                        $pr_no = isset($pr['pr_no']) ? $pr['pr_no']: "";                    
                    }
                    ?>
                <div class="prlist" data-id="<?php echo $pr['pr_id']; ?>">
                    
                    <hr class="mn br-light"> <!--  ph10 mv15 -->
                    <h5 class=" floatleft potitle">#<?php  
                        echo $i + $offset + 1; echo ' | ' . $pr_no; //echo " - ".$pr_title; ?> </h5>

                   <!--  <span class=" mv15 floatright">  <strong><?php //echo trans('label.lbl_req_date');?> :</strong><?php //echo date("d F Y", strtotime($pr_req_date));  ?></span> -->
                    <ul class="nav nav-messages p5 clear" role="menu">
                        <li class="">                                         
                            <strong><?php echo trans('label.lbl_req_date');?> :</strong> <?php echo date("d F Y", strtotime($pr_req_date));  ?>   
                        </li>
                        <li class="">                                         
                            <strong><?php echo trans('label.lbl_duedate');?> :</strong> <?php echo date("d F Y", strtotime($pr_due_date));  ?>   
                        </li>

                        <!-- <li class="">                                         
                            <strong><?php echo trans('label.lbl_requirement_for');?> :</strong> 
                            <?php echo $pr_requirement_for;  ?>  
                            <strong><?php echo trans('label.lbl_category');?> :</strong> 
                            <?php echo $pr_category;  ?>    
                        </li> -->

                        <!-- <li class="">                                         
                            <strong><?php //echo trans('label.lbl_category');?> :</strong> 
                            <?php //echo $pr_category;  ?>   
                        </li> -->

                       <!--  <li class="">                                         
                            <strong><?php echo trans('label.lbl_project_category');?> :</strong> 
                            <?php echo $pr_project_category;  ?>   
                        </li> -->

                        <li class="">
                        <strong><?php echo trans('label.lbl_project_name');?> :</strong> 
                            <?php echo $pr_project_name;  ?>   
                        </li>
                        
                        <!--TODO: Check following hard coded values-->
                        <!-- <li class=""> 
                            <strong><?php //echo trans('label.lbl_businessvertical');?> :</strong>   
                            <?php 
                                /*if(isset($pr_details['bv_dc_loc_detail']['bv_name'])) print_r($pr_details['bv_dc_loc_detail']['bv_name']);
                                else echo "-";*/
                            ?>
                        </li> -->
                       <!--  <li class="">                                         
                            <strong><?php //echo trans('label.lbl_datacenter');?> :</strong>
                            <?php 
                               /* if(isset($pr_details['bv_dc_loc_detail']['dc_name'])) print_r($pr_details['bv_dc_loc_detail']['dc_name']);
                                else echo "-";*/
                            ?>
                        </li>   -->
                        <li>
                            <?php
                            $RequesterName = "";
                            if(isset($pr['requester_name_details']['fname']))
                            {
                                $RequesterName .= $pr['requester_name_details']['fname'] ." ";
                            }
                            if(isset($pr['requester_name_details']['lname']))
                            {
                                $RequesterName .= $pr['requester_name_details']['lname'] ." ";
                            }
                            ?>
                            <label for=""><b>Requester Name : </b>
                            <?php print_r($RequesterName) ?></label>
                        </li>
                        <?php
                            $assignpr_user_id = isset($pr['assignpr_user_id']) ? $pr['assignpr_user_id']: "";
                            $PRAssignName = "";
                            if($assignpr_user_id != "")
                            {
                                if(isset($prs['pr_assign_detail']) && count($prs['pr_assign_detail'])>0)
                                {
                                    $allasign = $prs['pr_assign_detail'];
                                    foreach($allasign as $asignval)
                                    {
                                        if($assignpr_user_id == $asignval['user_id'])
                                        {
                                            $PRAssignName .= $asignval['firstname'] ." " . $asignval['lastname'];
                                        }
                                    }
                                }
                            }
                            ?>
                            <?php
                            if($PRAssignName != "")
                            {
                                ?>
                                <li>
                                    <label for=""><b>Requester Asignee Name : </b>
                                    <?php print_r($PRAssignName) ?></label>
                                </li>
                                <?php
                            }
                        ?>
                        <li class="">                                         
                            <!-- <strong> <?php //echo trans('label.lbl_location');?> :</strong>  -->
                            <?php 
                               /* if(isset($pr_details['bv_dc_loc_detail']['lc_name'])) print_r($pr_details['bv_dc_loc_detail']['lc_name']);
                                else echo "-";*/
                            ?>

                            <?php 
                            if($pr['status']=="approved"){
                                $label = "success";
                            }
                            elseif($pr['status']=="rejected"){
                                $label = "danger";
                            }elseif($pr['status']=="pending approval"){
                                $label = "warning";
                            }elseif($pr['status']=="cancelled"){
                                $label = "danger";
                            }
                            else{
                                $label = "default";
                            }
                            ?>
                            <span class="pull-right lh20 h-20 label label-<?php echo $label; ?> label-sm"><?php echo ucwords($pr['status']); ?></span>  
                            <?php 
                            $convert_to_pr = '';
                            $approval_status = json_decode($pr['approved_status'],true);
                            if(is_array($approval_status)){
                                if(array_key_exists('convert_to_pr',$approval_status)){
                                    $convert_to_pr = "convert to pr";
                                } 
                            }                               
                            ?>

                            <!-- PO is created status -->
                            <?php 
                            if(isset($prs['pr_id_status'][$pr['pr_id']]) && $prs['pr_id_status'][$pr['pr_id']] == 0)
                            {
                                echo '<span class="lh20 h-20 label label-danger label-sm">PO is Created</span>'; 
                            } ?>
                            <!-- PO is created status -->
                            <span class=" lh20 h-20 label label-primary label-sm"><?php echo $convert_to_pr;?></span>
                            
                

                        </li>                                   
                    </ul>
                </div>
            <?php
                }
            } 
            else
            {
                echo "<div class='textaligncenter'><strong>". trans('messages.msg_norecordfound') ."</strong></div>";
            }
            ?>     
            </div><!-- Min height for Veritical Scroll -->                                            
        </div>
    </div>
</div>
<style>
    .mCSB_inside > .mCSB_container
    {
        margin-right: 0px;
    }

</style>           
