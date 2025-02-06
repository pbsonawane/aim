
                    <div class="col-md-12 pl5-md animated fadeIn">
                        <!--<button type="button" class="btn btn-danger light btn-block compose-btn">Compose Message</button>-->
                        <div class="panel">
                            <div class="panel-body p1">
                          
                              <!--  <div class="ph15 pv10 br-b br-light">
                                    <div class="row table-layout">
                                        <div class="col-md-12 va-m pn">
                                            <select id="timerange" class="chosen-select" tabindex="5"  class="form-control input-sm" data-placeholder="Select Filter" >
                                                <option value=""></option>
                                                <optgroup label="">
                                                    <option>All PRs</option>
                                                <optgroup>
                                                <optgroup label="FILTER BY STATUS">
                                                    <option value="open">Open</option>
                                                    <option value="pending approval">Pending Approval PRs</option>
                                                    <option value="partially approved">Partially Approved</option>    
                                                    <option value="approved">Approved PRs</option>
                                                    <option value="cancelled">Cancelled PRs</option>
                                                    <option value="closed">Closed PRs</option>
                                                    <option value="deleted">Deleted</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                </div>   -->  
                                <?php
                                $contracts = $dbdata;
                                if (is_array($contracts) && count($contracts) > 0)
                                {
                                    
                                    foreach($contracts as $i => $contract)
                                    {	
                                        
                                       // if($contract['contract_status']=='active' ){?>
                                   <div class="contractlist ccursor" data-id="<?php echo $contract['contract_id']; ?>">
                                  <ul class="nav nav-messages p5 clear" role="menu">            
                                <li class="">             
                                            <strong><?php //echo $contract['contract_name']; ?></strong> 
                                            
                                        </li>
                                                
                                    </ul>
                                </div>
                                <div class="contractlist" data-id="<?php echo $contract['contract_id']; ?>">
                                    <hr class="mn br-light">
                                    <h4 class="ccursor ph10 mv15 floatleft contracttitle" >#<?php 
                                        echo $i + $offset + 1;
                                        echo " - ".$contract['contract_name']; ?> </h4>
                                    <span class=" mv15 floatright"> <?php echo $contract['from_date'];  ?></span>
                                    <ul class="nav nav-messages p5 clear" role="menu">
                                        <li class="">                                         
                                            <strong><?php echo trans('label.lbl_contract_id');?> :</strong> <?php echo $contract['contractid'];  ?>   
                                            
                                        </li>
                                        <li class="">                                         
                                             <strong><?php echo trans('label.lbl_contract_type');?> :</strong> <?php echo $contract['contract_type'];?> 
                                            
                                        </li>
                                        <li class="">                                         
                                        <strong><?php echo trans('label.lbl_vendor');?> :</strong> <?php echo $contract['vendor_name'];?>  
                                        
                                        </li>  
                                        <?php if($contract['contract_status']=='active'){
                                                ?>
                                                <span class="pull-right lh20 h-20 label label-success label-sm" style="background-color:green;"><?php echo ucwords($contract['contract_status']); ?></span> 
                                                <?php
                                                }
                                                else
                                                {?>
                                                 <span class="pull-right lh20 h-20 label label-success label-sm" style="background-color:red;"><?php echo ucwords($contract['contract_status']); ?></span> 

                                               <?php }?>
                                      
                                        </li>                                   
                                    </ul>
                                </div>


                                       <?php
                                   // }
                               }   
                            }else
                            {
                                echo "<div class='textaligncenter'><strong>". trans('messages.msg_norecordfound') ."</strong></div>";
                            }                 
                           ?>
             