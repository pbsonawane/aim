<?php
    $show_single = isset($dbdata['show_single']) ? $dbdata['show_single'] : "false";;

?>
<div class="col-md-12 pl5-md animated fadeIn" <?php echo $show_single == "true" ? 'style="height: 220px;"' : 'style="min-height: 500px; height:auto;"'; ?>>
    <!--<button type="button" class="btn btn-danger light btn-block compose-btn">Compose Message</button>-->

    <div class="panel">
        <div class="<?php echo $show_single == "true" ? 'panel-body p1 br-n mt20' : 'panel-body p1 '; ?>">
        <!--<div class="ph15 pv10 br-b br-light hidden">
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
            <?php 
            if($show_single == "false"){
            ?>
            <div class="ph15 pv10 br-b br-light">
                <div class="row table-layout">
                    <div class="col-md-12 va-m pn">
                        <select id="po_filter_status" class="chosen-select" tabindex="5"  class="form-control input-sm" data-placeholder="<?php echo trans('label.lbl_selectfilter');?>" >
                            <option value=""></option>
                            <optgroup label="">
                                <option value="">
                                    <?php echo trans('label.lbl_all_po');?>
                                </option>
                            <optgroup>
                            <optgroup label="<?php echo trans('label.lbl_filterby_sts');?>">
                                <option value="open">
                                <?php echo trans('label.lbl_open');?>        
                                </option>
                                <option value="pending approval">
                                <?php echo trans('label.lbl_pendingapprovalpos');?>
                                </option>
                                <option value="partially approved">
                                <?php echo trans('label.lbl_partiallyapproved');?>
                                </option>
                                <option value="partially received">
                                <?php echo trans('label.lbl_partially_recived');?>
                                </option>      
                                <option value="approved">
                                <?php echo trans('label.lbl_approved_po');?>
                                </option>
                                <option value="ordered">
                                <?php echo trans('label.lbl_ordered');?>
                                </option>
                                <option value="cancelled">
                                <?php echo trans('label.lbl_cancelled_po');?>        
                                </option>
                                <option value="closed">
                                <?php echo trans('label.lbl_closed_po');?>
                                </option>
                                <!--<option value="deleted">
                                <?php //echo trans('label.lbl_deleted');?>
                                </option>-->
                                <option value="rejected"><?php echo trans('label.lbl_rejectedpos');?></option>
                            </optgroup>
                        </select>
                    </div>
                </div>
            </div>     
            <?php 
            }
            ?>  

            <div class="apply-custom-vertical-scroll" style="height: 500px">                                     
            <?php
            $pos = isset($dbdata['pos']) ? $dbdata['pos'] : array();
            
            if (is_array($pos) && count($pos) > 0)
            {
                foreach($pos as $i => $po)                                
                { 
                    $po_details = isset($po['details']) ? $po['details']: "";
                    if($po_details)
                    {
                        $po_name        = isset($po['po_name']) ? $po['po_name']: "";
                        
                        $pr_title       = isset($po_details['pr_title']) ? $po_details['pr_title']: "";
                        
                        $po_no          = isset($po['po_no']) ? $po['po_no']: "";
                        
                        $po_req_date    = isset($po_details['pr_req_date']) ? $po_details['pr_req_date']: "";
                        
                        $po_due_date    = isset($po_details['pr_due_date']) ? $po_details['pr_due_date']: "";
                        
                        $po_bv_name     = isset($po_details['bv_dc_loc_detail']['bv_name']) ? $po_details['bv_dc_loc_detail']['bv_name']: "";
                        
                        $po_dc_name     = isset($po_details['bv_dc_loc_detail']['dc_name']) ? $po_details['bv_dc_loc_detail']['dc_name']: "";
                        
                        $po_location    = isset($po_details['bv_dc_loc_detail']['lc_name']) ? $po_details['bv_dc_loc_detail']['lc_name']: "";
                        
                    }
                    ?>
                    <div class="polist" data-id="<?php echo $po['po_id']; ?>">
                        <hr class="mn br-light">
                        <h5 class="ph10 mv15 floatleft potitle">#<?php 
                            echo $i + $offset + 1;
                            echo '- '.$po_no;  ?> 
                            <span>
                                <?php if(isset($po['pr_id'])){echo $po['pr_id']==NULL ? "[Without PR]" : "";} ?>        
                            </span>  
                        </h5> 
                       <!--  <span class=" mv15 floatright">
                         <?php //echo date("d F Y", strtotime($po_req_date));  ?>
                         </span> -->
                        <ul class="nav nav-messages p5 clear" role="menu">
                       <!--  <?php if(isset($po['pr_id']) && $po['pr_id']!=NULL){ ?>
                        <li class="">                                         
                            <strong> <?php //echo trans('label.lbl_pr_title');?> :</strong> <?php //echo $pr_title;  ?> 
                        </li>
                        <?php } ?> -->
                                               
                        <li class="">                                         
                            <strong> Request Date :</strong> <?php echo date("d F Y", strtotime($po_req_date));  ?>  
                        </li>
                         <li class="">                                         
                            <strong> <?php echo trans('label.lbl_duedate');?> :</strong> <?php echo date("d F Y", strtotime($po_due_date));  ?>    
                        </li>  
                         <li class="">                                         
                            <strong> PO Requester :</strong> <?php 
                            if(isset($po['requester_info']))
                            {
                                echo $po['requester_info']['firstname']  ." ". $po['requester_info']['lastname'];
                            }
                            ?>    
                        </li>  
                       <!--  <li class="">                                         
                            <strong> <?php //echo trans('label.lbl_businessvertical');?> :</strong> <?php //echo $po_bv_name;  ?>
                        </li>
                        <li class="">                                         
                            <strong> <?php //echo trans('label.lbl_datacenter');?> :</strong>  <?php echo $po_dc_name;  ?>  
                        
                        </li>  <strong> <?php //echo trans('label.lbl_location');?> :</strong> <?php //echo $po_location;  ?>  -->
                        <li class="">                                         
                             
                            <?php 
                            if($po['status']=="approved")
                            {
                                $label = "success";
                            }
                            elseif($po['status']=="rejected")
                            {
                                $label = "danger";
                            }elseif($po['status']=="ordered")
                            {
                                $label = "info";
                            }elseif($po['status']=="pending approval")
                            {
                                $label = "warning";
                            }
                            elseif($po['status']=="cancelled")
                            {
                                $label = "danger";
                            }
                            elseif($po['status']=="item received")
                            {
                                $label = "info";
                            }
                            else
                            {
                                $label = "default";
                            }
                            ?>
                            <span class="pull-right lh20 h-20 label label-<?php echo $label; ?> label-sm"><?php echo ucwords($po['status']); ?></span> 
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
            </div>                                                
        </div>
    </div>
</div>
<style>
    .mCSB_inside > .mCSB_container
    {
        margin-right: 0px;
    }

</style>                  