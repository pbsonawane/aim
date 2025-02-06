<?php $url = parse_url($_SERVER['REQUEST_URI']);
        
        $display ="none";
        $customtime = "";
        $timerange = "";
        $dismissable = "alert-dismissable";  
        $reoveAdvFilter = "reoveAdvFilter";
        $criteria_selector = [];
        $criteria_ex_selector = [];
        $criteria_value = [];
        $report_name = "";
        $report_title = "";
        $fixedFilter = config('enconfig.alert_fixed_filter');
        if(_isset($url,'query') && !empty($url['query']))
        {
            parse_str($url['query'], $get_array);            
            //echo "<pre>"; print_r( $get_array); echo "</pre>"; die;
            $display ="block";
            $customtime = _isset($get_array, 'customtime')? $get_array['customtime'] : "";
            $timerange = _isset($get_array, 'timerange') ? $get_array['timerange'] : "";
            $criteria_selector = _isset($get_array, 'criteria_selector') ? $get_array['criteria_selector'] : [];
            $criteria_ex_selector = _isset($get_array, 'criteria_ex_selector') ? $get_array['criteria_ex_selector'] : [];
            $criteria_value = _isset($get_array, 'criteria_value') ? $get_array['criteria_value'] : [];
            
            $advArr = [];
            
            if(!empty($criteria_selector))
            {
                foreach($criteria_selector as $key => $selector)
                {
                    if($selector != "")
                    {
                        $advArr[] = $selector." ".@$criteria_ex_selector[$key]." ".@$criteria_value[$key];
                    }
                }
            }             
        }
        //$mapping = getlogsmapping($logtype);
        $mapping = [];
    ?>
<div class="admin-form tab-pane" id="advancedsearch_r" style="display:<?php echo $display; ?>" role="tabpanel">
    <div class="panel panel-Default">
        <div class="panel-body">            
            <div class="row mt5">
                    <!-- Three panes -->
                <form class="form-horizontal" method="get" action="" id="form-advanced-search1">     
                    <div class="col-md-12" id="datepickerdiv">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-12 f-field-group">
                                        <label class="field select">
                                            <select class="" id="timerange"  name="timerange" onclick="clearcustomtime();">
                                                <option value=""><?php echo trans('label.opt_sel_date_range');?></option>
                                                <optgroup label="LAST TIME">        
                                                    <option <?php echo $timerange == "last_15_min" ? "selected" : '' ?> value="last_15_min" >
                                                    <?php echo trans('label.opt_lst_minutes', ['number' => '15']);?>
                                                    </option>
                                                    <option <?php echo $timerange == "last_30_min" ? "selected" : '' ?>  value="last_30_min" >
                                                    <?php echo trans('label.opt_lst_minutes', ['number' => '30']);?>
                                                    </option>
                                                    <option <?php echo $timerange == "last_1_hour" ? "selected" : '' ?>  value="last_1_hour" >
                                                    <?php echo trans('label.opt_lst_hours', ['number' => '1']);?>
                                                    </option>
                                                    <option <?php echo $timerange == "last_6_hour" ? "selected" : '' ?>  value="last_6_hour" >
                                                    <?php echo trans('label.opt_lst_hours', ['number' => '6']);?>
                                                    </option>
                                                    <option <?php echo $timerange == "last_12_hour" ? "selected" : '' ?>  value="last_12_hour" >
                                                    <?php echo trans('label.opt_lst_hours', ['number' => '12']);?>
                                                    </option>
                                                    <option <?php echo $timerange == "last_24_hour" ? "selected" : '' ?>  value="last_24_hour" >
                                                    <?php echo trans('label.opt_lst_hours', ['number' => '24']);?>
                                                    </option>
                                                    <option <?php echo $timerange == "last_3_days" ? "selected" : '' ?>  value="last_3_days" >
                                                    <?php echo trans('label.opt_lst_days', ['number' => '3']);?>
                                                    </option>
                                                    <option <?php echo $timerange == "last_7_days" ? "selected" : '' ?>  value="last_7_days" >
                                                    <?php echo trans('label.opt_lst_days', ['number' => '7']);?>
                                                    </option>
                                                    <option <?php echo $timerange == "last_15_days" ? "selected" : '' ?>  value="last_15_days" >
                                                    <?php echo trans('label.opt_lst_days', ['number' => '15']);?>
                                                    </option>
                                                    <option <?php echo $timerange == "last_30_days" ? "selected" : '' ?>  value="last_30_days" >
                                                    <?php echo trans('label.opt_lst_days', ['number' => '30']);?>
                                                    </option>
                                                    <option <?php echo $timerange == "last_60_days" ? "selected" : '' ?>  value="last_60_days" >
                                                    <?php echo trans('label.opt_lst_days', ['number' => '60']);?>
                                                    </option>
                                                    <option <?php echo $timerange == "last_90_days" ? "selected" : '' ?>  value="last_90_days" >
                                                    <?php echo trans('label.opt_lst_days', ['number' => '90']);?>
                                                    </option>
                                                    <option <?php echo $timerange == "last_6_month" ? "selected" : '' ?>  value="last_6_month" >
                                                    <?php echo trans('label.opt_lst_months', ['number' => '6']);?>
                                                    </option>
                                                    <option <?php echo $timerange == "last_1_year" ? "selected" : '' ?>  value="last_1_year" >
                                                    <?php echo trans('label.opt_lst_year', ['number' => '1']);?>
                                                    </option>
                                                    <option <?php echo $timerange == "last_2_year" ? "selected" : '' ?>  value="last_2_year" >
                                                    <?php echo trans('label.opt_lst_year', ['number' => '2']);?>
                                                    </option>
                                                </optgroup>
                                                <optgroup label="COMMON OPTIONS">
                                                    <option <?php echo $timerange == "today" ? "selected" : '' ?>  value="today" >
                                                    <?php echo trans('label.opt_today');?>
                                                    </option>
                                                    <option <?php echo $timerange == "this_week" ? "selected" : '' ?>  value="this_week" >
                                                    <?php echo trans('label.opt_this_week');?>
                                                    </option>
                                                    <option <?php echo $timerange == "this_month" ? "selected" : '' ?>  value="this_month" >
                                                    <?php echo trans('label.opt_this_month');?>
                                                    </option>
                                                    <option <?php echo $timerange == "this_year" ? "selected" : '' ?>  value="this_year" >
                                                    <?php echo trans('label.opt_this_year');?>
                                                    </option>
                                                    <option <?php echo $timerange == "week_to_date" ? "selected" : '' ?>  value="week_to_date" >
                                                    <?php echo trans('label.opt_week_to_date');?>
                                                    </option>
                                                    <option <?php echo $timerange == "month_to_date" ? "selected" : '' ?>  value="month_to_date" >
                                                    <?php echo trans('label.opt_month_to_date');?>
                                                    </option>
                                                    <option <?php echo $timerange == "year_to_date" ? "selected" : '' ?>  value="year_to_date" >
                                                    <?php echo trans('label.opt_year_to_date');?>
                                                    </option>
                                                    <option <?php echo $timerange == "yesterday" ? "selected" : '' ?>  value="yesterday" >
                                                    <?php echo trans('label.opt_yesterday');?>
                                                    </option>
                                                    <option <?php echo $timerange == "day_b4_yest" ? "selected" : '' ?>  value="day_b4_yest" >
                                                    <?php echo trans('label.opt_day_b4_yest');?>
                                                    </option>
                                                </optgroup> 
                                            </select>
                                            <i class="arrow double"></i>
                                        </label>  
                                    </div>
                                </div>                                                 
                            </div>                               
                            <div class="col-md-4">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <div class="section f-field-group">
                                            <input type="text" id="customtime" class="input-sm form-control pull-right daterangepicker1" name="customtime" placeholder="<?php echo trans('label.opt_sel_date_range');?>" readonly value="<?php echo $customtime; ?>" onfocus="cleardatetimerange();">
                                        </div>
                                    </div>
                                </div>
                            </div>                                                         
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        <label for="max_alerts_per_page" class="field prepend-icon custom-modal pull-left animation-switcher">
                                            <span class="pl10 text-info cursorpointer" data-href="#modal-form-advanced-search" data-effect="mfp-flipInX"><?php echo trans('label.lbl_advanced_filters'); ?></span>
                                            <div id="searchCriteria" class="p5">
                                        <?php
                                            if(!empty($advArr))
                                            {
                                                foreach($advArr as $key =>  $str)
                                                {     
                                                               
                                        ?>
                                            <div class="<?php echo $reoveAdvFilter; ?> col-md-2 alert alert-micro alert-info light <?php echo $dismissable; ?> mb5" id="<?php echo $key; ?>">
                                            <?php
                                                if($dismissable !="" ) { ?>
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                <?php } ?>
                                                <i class="fa fa-cog pr10 hidden"></i><strong></strong><?php echo $str; ?>
                                            </div>
                                        <?php 
                                                }
                                            } ?>
                                            </div>
                                            <label for="max_alerts_per_page" class="field-icon">
                                            </label>
                                        </label>     
                                    </div>                    
                                </div> 
                            </div>
                        </div>
                    </div> 
                </form>                                     
            </div>

        </div>
        <!-- end .form-body section -->

        <div class="panel-footer pull-center">
            <button type="button" class="btn btn-success ad-btn" onclick="submitAdvanceSearch();"><?php echo trans('label.btn_submit')?></button>
            <button type="reset" class="btn btn-danger ad-btn reset_btn"><?php echo trans('label.btn_reset')?></button>
        </div>
        <!-- end .form-footer section -->
    </div>
</div>

<!-- Admin Form Popup Test Rule-->

<div id="modal-form-advanced-search" class=" popup-lg popup-lg_custom admin-form mfp-with-anim mfp-hide">
    <div class="panel">
        <div class="panel-heading p10">
            <span class="panel-title"><i class="glyphicons glyphicons-notes"></i><?php echo trans('label.lbl_advanced_search')?>
            </span>
            <div class="pull-right pr30"><?php echo trans('label.lbl_adv_search_note')?></div>
        </div>
        <!-- end .panel-heading section -->

        <form class="form-horizontal"  method="post" action="" id="form-advanced-search2">
            <div class="panel-body p15 pr40">
                <div class="section">
                    <div class="addMore fixedbutton-toright cursorpointer">
                        <i class="fa fa-plus-square text-success"></i>
                    </div> 
                    <div class="col-md-12">
                        <div class="col-md-12  form-group complete_row original" data-nextid="<?php echo !empty($criteria_selector) ? count($criteria_selector)-1: 0?>" data-id="0" id="row-id-0">                       
                            <div class="col-md-4 searchfiltermultiselect4"> 
                           
                                
                                    <select class="form-control  input-sm criteria_selector" name="criteria_selector[]">
                                    <option value=""> <?php echo trans('label.drop_select'); ?> </option>  
                                        <?php 
                                        if(is_array($mapping))
                                        {
                                            foreach( $mapping as $alert )
                                            {
                                                $selected_criteria_selector = _isset($criteria_selector, 0) && $criteria_selector[0] == $alert ? "selected" : "";
                                                echo "<option value='".$alert."' ".$selected_criteria_selector." >".$alert."</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                            </div>  
                            <div class="col-md-3 searchfiltermultiselect4">
                                <select class="form-control  input-sm criteria_ex_selector" name="criteria_ex_selector[]">
                                <?php 
                                    $selected_criteria_ex_selector = _isset($criteria_ex_selector, 0) ? $criteria_ex_selector[0] : "";
                                    $criteria_arr = trans('commonarr.selected_criteria');
                                    if(is_array($criteria_arr) && count($criteria_arr) > 0 )
                                    {
                                        foreach($criteria_arr as $key => $val){
                                        ?>
                                        <option value="<?php echo $key; ?>" <?php echo $selected_criteria_ex_selector == $key ? "selected" : ""; ?>><?php echo $val; ?></option>
                                        <?php 
                                        }
                                    }
                                ?>
                                </select>                              
                            </div>  
                            <div class="col-md-4 criteria_value_div">
                                <?php
                                 $selected_criteria_value = _isset($criteria_value, 0) ? $criteria_value[0] : ""; 
                                 ?>
                                <input type="text" class="form-control  input-sm criteria_value" name="criteria_value[]" placeholder="<?php echo trans('label.lbl_criteria_value'); ?>" value="<?php echo $selected_criteria_value; ?>">   
                                                                                             
                            </div>  
                            <div class="col-md-1 extradiv">
                                <i id="0" class="glyphicon glyphicon-remove-circle text-danger removerow cursorpointer" style="display:none"></i>
                            </div>
                        </div>    
                    </div>                 
                    <div class="col-md-12 advsearch_all_rows">
                      
                            <?php 
                                if(isset($criteria_selector) && !empty($criteria_selector) && count($criteria_selector) > 1)
                                {
                                    unset($criteria_selector[0]);
                                    unset($criteria_ex_selector[0]);
                                    unset($criteria_value[0]);
                                     
                                    foreach($criteria_selector  as $key => $selector)
                                    {                                        
                                        $rowcnt =  $key;
                            ?>  
                                <div class='col-md-12  form-group complete_row' data-nextid="<?php echo $rowcnt;  ?>" data-id='<?php echo $rowcnt;  ?>' id='row-id-<?php echo $rowcnt;  ?>'>                              
                                    <div class="col-md-4 searchfiltermultiselect4">
                                    
                                        
                                            <select class="form-control  input-sm criteria_selector" name="criteria_selector[]">
                                            <option value=""> <?php echo trans('label.drop_select'); ?> </option>  
                                                <?php 
                                                if(is_array($mapping))
                                                {
                                                    foreach( $mapping as $alert )
                                                    {
                                                        $selected_criteria_selector= _isset($criteria_selector, $key) && $criteria_selector[$key] == $alert ? "selected" : "";

                                                        echo "<option value='".$alert."' ".$selected_criteria_selector.">".$alert."</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                    </div>  
                                    <div class="col-md-3 searchfiltermultiselect4">
                                        <select class="form-control  input-sm criteria_ex_selector" name="criteria_ex_selector[]">
                                        <?php 
                                            $selected_criteria_ex_selector = _isset($criteria_ex_selector, $key) ? $criteria_ex_selector[$key] : ""; 
                                            $criteria_arr = trans('commonarr.selected_criteria');
                                            if(is_array($criteria_arr) && count($criteria_arr) > 0 )
                                            {
                                                foreach($criteria_arr as $key => $val){
                                                ?>
                                                <option value="<?php echo $key; ?>" <?php echo $selected_criteria_ex_selector == $key ? "selected" : ""; ?>><?php echo $val; ?></option>
                                                <?php 
                                                }
                                            }
                                        ?>
                                            
                                        </select>                              
                                    </div>  
                                    <div class="col-md-4 criteria_value_div">
                                    <?php $selected_criteria_value = _isset($criteria_value, $key) ? $criteria_value[$key] : ""; ?>
                                        <input type="text" class="form-control  input-sm criteria_value" name="criteria_value[]" placeholder="Enter Criteria Value" value="<?php echo $selected_criteria_value; ?>">   
                                                                                                     
                                    </div>  
                                    <div class="col-md-1 extradiv">
                                        <i id="<?php echo $rowcnt;  ?>" class="glyphicon glyphicon-remove-circle text-danger removerow"></i>
                                    </div>
                                </div>                                     
                            <?php                         
                                    }
                                }
                            ?>                        
                    </div>   
                </div>
                <!-- end section -->

            </div>
            <!-- end .form-body section -->

            <div class="panel-footer pull-center">
                <button type="button" class="submit-advanced-search btn btn-success ad-btn" onclick="addAdvanceSeachCustomeOptions(this)"><?php echo trans('label.btn_submit')?></button>
                <!-- <button type="reset" id="clear-test-rule" class="btn  btn-danger ad-btn"><?php //echo trans('label.btn_reset')?></button> -->
            </div>    
            <!-- end .form-footer section -->
        </form>
    </div>
    <!-- end: .panel -->
</div>