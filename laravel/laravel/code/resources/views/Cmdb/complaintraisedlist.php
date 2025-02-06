<div class="col-md-12 pl5-md animated fadeIn" style="min-height: 500px;">
    <div class="panel">
        <div class="panel-body p1">
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
// print_r($prs);

            if (is_array($prs) && count($prs) > 0)
            {
                foreach($prs as $i => $pr)                                
                { 
                    $pr_details = $pr;
                    if($pr_details)
                    {
                        
                        $complaint_raised_no = isset($pr_details['complaint_raised_no']) ? $pr_details['complaint_raised_no']: "";
                        $complaint_raised_date = isset($pr_details['complaint_raised_date']) ? $pr_details['complaint_raised_date']: "";
                        $complaint_raised_status = isset($pr_details['status']) ? $pr_details['status']: "";
                       
                        
                    }
                    ?>
                <div class="crlist" data-id="<?php echo $pr['cr_id']; ?>">
                    <hr class="mn br-light"> <!--  ph10 mv15 -->
                    <h5 class=" floatleft potitle">#<?php  
                        echo $i + $offset + 1; echo ' | ' . $complaint_raised_no; ?> </h3>
                    <ul class="nav nav-messages p5 clear" role="menu">
                        <li class="">                                         
                            <strong>Complaint Raised Date :</strong> <?php echo date("d F Y", strtotime($complaint_raised_date));  ?>   
                        </li>

                        <li class="">                                         
                            <strong>Complaint Raised Status :</strong> <?php echo $complaint_raised_status; ?>   
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