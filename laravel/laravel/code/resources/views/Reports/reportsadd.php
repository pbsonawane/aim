<div class="row">
  <div class="col-md-10">
    <div class="hidden alert-dismissable" id="msg_popup"></div>
  </div>
  <div class="col-md-12">
    <div class="panel">
      <div class="panel-body">
        <?php if(isset($view) && $view=="primary")
        {
        ?>
        <form class="form-horizontal"  name="addformreportsprimary" id="addformreportsprimary">
          <div class="container">
            <?php
              if(is_array($reportmodules) && count($reportmodules)>0)
              {
                $i = 1;
                foreach($reportmodules as $module)
                {
                  $checked ="";
                  if ($i==1)
                  $checked = "checked";
            ?>
              <div class="radio">
                <input id="module-<?php echo $i;?>" name="module" type="radio" value="<?php echo $module['module_key'] ?>" <?php echo $checked;?>>
                <label for="module-<?php echo $i;?>" class="radio-label">
                  <?php echo $module['module_name'];?>
                </label>
                <?php
                if(is_array($cidata) && count($cidata) > 0 && (($module['module_key'] == "CMDB") || ($module['module_key'] == "ALLCOMP")))
                {
                ?>
                  <div class="radio cmdb-assets" style="margin-left: 5%;display:none;">
                    <select data-placeholder="Filter By" class="chosen-select" name="ci_templ_<?php echo $module['module_key']; ?>" id="ci_templ">
                    <option value="">-<?php echo trans('label.lbl_filter_by');?>-</option>
                    <?php
                    foreach($cidata as $citemp)
                    {
                      ?>
                      <optgroup label="<?php echo ucfirst($citemp['title']); ?>"> 
                      <?php
                      if(is_array($citemp['children']) && count($citemp['children']) > 0)
                      {
                        foreach($citemp['children'] as $ci)
                        {
                          $defaultci = config('app.defaultci');
                          if($module['module_key'] == "CMDB" && !in_array($ci['variable_name'],$defaultci))
                          {
                        ?> 
                          <option value="<?php echo $ci["ci_templ_id"].'|'.$ci["ci_type_id"]?>"><?php echo $ci['title']?>
                          </option>
                          <?php
                          }
                          elseif ($module['module_key'] == "ALLCOMP" && in_array($ci['variable_name'],$defaultci)) 
                          {
                            ?>
                            <option value="<?php echo $ci["ci_templ_id"].'|'.$ci["ci_type_id"]?>"><?php echo $ci['title']?>
                          </option>
                        <?php  
                          }
                        }
                      }
                      ?>
                    </optgroup>   
                      <?php
                    }
                    ?>
                    </select> 
                  </div>
                  <?php
                }?>
              </div>
            <?php
              $i++;
              }
            }
            ?>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label"></label>
                <div class="col-xs-2">
                    <button id="reportsaddsubmitprimary" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_submit');?></button>
                </div>
                <div class="col-xs-2">
                    <button id="reports_reset" type="reset" class="btn btn-info btn-block"><?php echo trans('label.btn_reset');?></button>
                </div>
            </div>
        </form>
        <?php 
        }
        elseif (isset($view) && $view=="secondary")
        {
        ?>
        <?php 
          if(is_array($reportmodules) && count($reportmodules)>0)
          {
            $curmodulekey   = $reportmodules['module_key'];
            $module_name    = $reportmodules['module_name'];
            $module_fields  = $reportmodules['module_fields'];
            $filter_fields  = json_decode($reportmodules['filter_fields'],true);
            $date_filter_fields  = json_decode($reportmodules['date_filter_fields'],true);

            if (isset($reportsdata[0]['filter_fields'])) 
            {
              $reports_fields_arr  = json_decode($reportsdata[0]['filter_fields'],true);
              $module_fields_arr   = json_decode($module_fields,true);
              $module_fields_common = $module_fields_diff = [];
              if (is_array($reports_fields_arr) && is_array($module_fields_arr)) 
              {
                //commented as mismatching indexes
                //$module_fields_common   = array_intersect_key($module_fields_arr, array_flip($reports_fields_arr));
                foreach($reports_fields_arr AS $key) 
                {
                  //$module_fields_common[$key] = $module_fields_arr[$key];
                  $module_fields_common[$key] = isset($module_fields_arr[$key]) ? $module_fields_arr[$key] : $key;
                }

                $module_fields_diff  = array_diff_assoc($module_fields_arr,$module_fields_common);

                $module_fields = json_encode($module_fields_diff);

              }
              if (is_array($module_fields_common) && count($module_fields_common)>0)
              {
                $module_fields_str ="";
                foreach ($module_fields_common as $module_fields_key => $module_fields_value) 
                {
                  $module_fields_str.='<li class="sortable-item allowPrimary sortable-item-contract_id ui-sortable-handle" data-fid="contract_id" style="position: relative; left: 0px; top: 0px;"><span class="icon-drag fas fa-grip-vertical mr-2"></span><input type="checkbox" name="filter_fields[]" value="'.$module_fields_key.'" class="sortable-item-input" checked=checked>'.$module_fields_value.'</li>';
                }
              }
            }
          } 
        ?>
        <form class="form-horizontal"  name="addformreports" id="addformreports">
          <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel invoice-panel">
              <div class="panel-body p20" id="invoice-item">
                <input id="report_id" name="report_id" type="hidden" value="<?php echo $report_id;?>">
                <div class="form-group required ">
                    <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_report_name');?></label>
                    <div class="col-md-8">
                    <input type="text" id="report_name" name="report_name" class="form-control input-sm" value="<?php if(isset($reportsdata[0]['report_name'])) echo $reportsdata[0]['report_name'];?>">
                    </div>
                </div>
                <div class="form-group required">
                  <label for="Description" class="col-md-3 control-label"><?php echo trans('label.lbl_report_category');?></label>
                  <div class="col-md-8">
                    <select class="form-control input-sm" name="report_cat_id" id="report_cat_id">
                      <option value=""><?php echo trans('label.opt_select_report_category');?></option>
                      <?php 
                        if(is_array($reportcategory) && count($reportcategory)>0)
                        {
                          $curreportcat = "";
                          foreach($reportcategory as $reportcat)
                          {
                            if (isset($reportsdata[0]['report_cat_id']))
                              {
                                $curreportcat = isset($reportsdata[0]['report_cat_id']) ? $reportsdata[0]['report_cat_id'] : '';
                              }
                      ?>
                          <option value="<?php echo $reportcat['report_cat_id'] ?>" <?php if($curreportcat == $reportcat['report_cat_id']){echo "selected";} ?> > <?php echo $reportcat['report_category'] ?> </option>
                      <?php
                          }
                        } 
                      ?>
                    </select>       
                  </div>
                </div>
                <div class="form-group required">
                  <label for="Description" class="col-md-3 control-label"><?php echo trans('label.module');?></label>
                  <div class="col-md-8">
                     <input type="text" id="" name="" class="form-control input-sm" value="<?php echo $module_name;?>" disabled>
                    <input type="hidden" name="module" value="<?php echo $curmodulekey;?>">
                    <input type="hidden" id="ci_templ_id" name="ci_templ_id" value="<?php if(isset($reportsdata[0]['ci_templ_id'])) echo $reportsdata[0]['ci_templ_id'];?>">
                    <input type="hidden" id="ci_type_id" name="ci_type_id" value="<?php if(isset($reportsdata[0]['ci_type_id'])) echo $reportsdata[0]['ci_type_id'];?>">
                  </div>
                </div>
      					<div class="form-group">
      					  <label for="inputStandard" class="col-md-3 control-label"><?php echo trans('label.lbl_share_report');?></label>
      						  <div class="col-md-8">
      							<div class="checkbox-custom mb5">
      							  <input type="checkbox" class="user_bvs" id="share_report" value="y" name="share_report" <?php if(isset($reportsdata[0]['share_report']) && $reportsdata[0]['share_report'] == 'y'){ echo "checked"; } ?>>
      							  <label for="share_report"></label>
      						   </div>
      						  </div>
      					 </div>
              </div>
            </div>
            <div class="panel">
              <div class="panel-heading" role="tab" id="headingOne">
                <h4 class="panel-title" style="padding:1%;">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                  <span class="panel-icon"><i class="fa fa-list"></i></span>
                  <span class="panel-title">
                    <?php echo trans('label.lbl_choose_fields');?>
                  </span>   
                </a>
                <div class="widget-menu pull-right">
                </div>
              </h4>
              </div>
              <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">
                  <div class="alert alert-info small mt-4">
                  <i class="fa fa-comment"></i>
                  <?php echo trans('messages.msg_rep_field_info');?>
                  </div>
                  <div class="col-md-12">
                  <input id="filter_fields" type="text" name="filter_fields[]" data-options='<?php echo $module_fields;?>' 
                      data-selected='[]' 
                      data-field-title="<i class='fa fa-folder-open'></i> Available Fields" 
                      data-selected-title="<i class='fa fa-star'></i> Selected Fields" 
                      class="dragableMultiselect">
                  <!--  END  -->
                </div>
                </div>
              </div>
            </div>
            <div class="panel">
              <div class="panel-heading" role="tab" id="headingTwo">
                <h4 class="panel-title" style="padding:1%;">
                <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                 <span class="panel-icon"><i class="fa fa-list"></i></span>
                  <span class="panel-title">
                    <?php echo trans('label.lbl_apply_filters');?>
                  </span>   
                </a>
                <div class="widget-menu pull-right">
                </div>
              </h4>
              </div>
              <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                <div class="panel-body">
                  <h4><?php echo trans('label.lbl_date_filters');?></h4>   
                  <table id="" class="table">
                    <thead>
                      <tr class="info">
                        <th>
                        <?php echo trans('label.lbl_column_name');?></th>
                        <th class="textalignright">
                        <?php echo trans('label.lbl_date_range');?>
                        </th>
                        <th>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      $cur_filter_date_value = "";
                      $customtime     = "";
                      $timerange      = "";
                      if (isset($reportsdata[0]['filter_date_value']) && $reportsdata[0]['filter_date_value'] !="")
                      {
                        $timerange  = $reportsdata[0]['filter_date_value'];
                      }
                      if (isset($reportsdata[0]['filter_date_range']) && $reportsdata[0]['filter_date_range'] !="")
                      {
                        $customtime      = $reportsdata[0]['filter_date_range'];
                      }
                      ?>
                      <tr id="row-1">
                        <td>
                          <div class="section">
                            <select  class="form-control input-sm" name="filter_date_field">
                                <option value=""><?php echo trans('label.opt_select_column');?></option>
                                <?php 
                                if(isset($date_filter_fields) && count($date_filter_fields)>0)
                                {
                                    if (isset($reportsdata[0]['filter_date_field']))
                                    {
                                      $cur_filter_date_value = isset($reportsdata[0]['filter_date_field']) ? $reportsdata[0]['filter_date_field'] : '';
                                    }
                                    foreach($date_filter_fields as $date_filter_fields_key => $date_filter_fields_val)
                                    {
                                      $selected = ""; 
                                      if($cur_filter_date_value == $date_filter_fields_key)
                                      {
                                        $selected = "selected";
                                      }
                                      echo "<option value='".$date_filter_fields_key."' ".$selected.">".$date_filter_fields_val."</option>";
                                    }
                                  }
                                ?>
                            </select>
                          </div>
                        </td>
                        <td>
                          <div class="col-md-12 f-field-group">
                            <select class="form-control input-sm" id="timerange"  name="filter_date_value" onclick="clearcustomtime();">
                               <option value=""><?php echo trans('label.opt_sel_cust_date');?></option>
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
                          </div>
                        </td>
                        <td>
                          <div class="section f-field-group">
                              <input type="text" id="customtime" class="input-sm form-control pull-right daterangepicker1" name="filter_date_range" placeholder="<?php echo trans('label.opt_sel_date_range');?>" readonly value="<?php echo $customtime; ?>" onfocus="cleardatetimerange();">
                            </div>
                        </td> 
                      </tr>
                    </tbody>
                  </table>
                  <h4><?php echo trans('label.lbl_advanced_filters');?></h4>   
                  <table id="advanced_filter" class="table addmore">
                    <thead>
                        <tr class="info">
                            <th>#</th>
                            <th>
                            <?php echo trans('label.lbl_column_name');?></th>
                            <th>
                            <?php echo trans('label.lbl_criteria');?>
                            </th>
                            <th>
                            <?php echo trans('label.lbl_value');?>
                            </th>
                            <th>
                            <?php echo trans('label.lbl_match');?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $key  = 0;
                    if (isset($reportsdata[0]['filters']))
                    {
                      $filters = json_decode($reportsdata[0]['filters'], true);
                    }
                    if(isset($filters) && count($filters)>0)
                    { 
                      $i = 0;
                      foreach($filters as $filter)
                      {
                        if (isset($filter['criteria_value']) && is_array($filter['criteria_value']))
                        {
                          $filter['criteria_value'] = implode(",", $filter['criteria_value']);
                        }
                    ?>
                    <tr id="row-<?php echo ($i+1); ?>">
                      <td><?php echo ($i+1); ?></td>
                      <td>
                        <div class="section">
                          <select  tabindex="5" data-placeholder="Select Item"  class="form-control input-sm filter_column" id="filter_column-<?php echo ($i+1); ?>" name="filter_column[]">
                            <option value=""><?php echo trans('label.opt_select_column');?></option>
                            <?php 
                            if(isset($filter_fields) && count($filter_fields)>0)
                            {
                              foreach($filter_fields as $filter_fields_key => $filter_fields_val)
                              {
                                $filter_fields_selected = "";
                                if (isset($filter['filter_column']) && $filter['filter_column'] == $filter_fields_key) 
                                {
                                  $filter_fields_selected = "selected";
										        ?>
                            <!--This jquery function is added By Snehal on 16 June 2020 for Selected the loc,bv, vendor and cost center dropdown values -->
                            <script>
        										$(function()
                            {	
        											getColumnId('<?php echo $filter_fields_key;?>' ,<?php echo $i+1; ?>, '<?php echo $filter['criteria_value'];?>');
        										});
        										</script>
										        <?php
                                }
                                echo "<option value='".$filter_fields_key."' ".$filter_fields_selected.">".$filter_fields_val."</option>";
                              }
                            } 
                            ?>
                          </select>
                        </div>
                      </td>
                      <td>
                        <select class="form-control  input-sm criteria_ex_selector select_criteria_array" name="criteria[]" id="criteria-<?php echo ($i+1); ?>">
                          <option value=""><?php echo trans('label.opt_select_criteria');?></option>
                          <?php 
                            $criteria_arr = trans('commonarr.selected_criteria');
                            if(is_array($criteria_arr) && count($criteria_arr) > 0 )
                            {
                              foreach($criteria_arr as $criteriakey => $criteriaval)
                              {
                                $criteriakey_selected = "";
                                if (isset($filter['criteria']) && $filter['criteria'] == $criteriakey) 
                                {
                                  $criteriakey_selected = "selected";
                                }
                              ?>
                              <option value="<?php echo $criteriakey; ?>" <?php echo $criteriakey_selected; ?>><?php echo $criteriaval; ?>
                              </option>
                              <?php 
                              }
                            }
                          ?>
                        </select>  
                      </td>
                      <td class="multi-tar-div">
                        <input placeholder="<?php echo (trans('label.lbl_enter_cr_val'));?>" type="text" value="<?php if(isset($filter['criteria_value'])) echo $filter['criteria_value'];?>" name="criteria_value-<?php echo ($i+1); ?>[]" id="criteria_value-<?php echo ($i+1); ?>" class="form-control input-sm criteria_value tagged">
                        <div class="section select_criteria_sec" id="select_criteria_sec<?php echo ($i+1); ?>" style="display:none;">
                          <select class="form-control chosen-select input-sm criteria_ex_selector select_criteria" name="criteria_value-<?php echo ($i+1); ?>[]" id="select_criteria-<?php echo ($i+1); ?>" data-placeholder="<?php echo (trans('label.lbl_select_option'));?>"  multiple tabindex="6">
                          </select>
                        </div>
                      </td>
                      <td>
                        <div class="section" >
                          <select class="form-control  input-sm criteria_ex_selector" name="criteria_match[]" id="criteria_match-<?php echo ($i+1); ?>">
                            <option value=""><?php echo trans('label.opt_select_match');?></option>
                          <?php 
                            $match_arr = trans('commonarr.match');
                            if(is_array($match_arr) && count($match_arr) > 0 )
                            {
                              foreach($match_arr as $matchkey => $matchval)
                              {
                                $match_selected = "";
                                if (isset($filter['criteria_match']) && $filter['criteria_match'] == $matchkey) 
                                {
                                  $match_selected = "selected";
                                }
                              ?>
                              <option value="<?php echo $matchkey; ?>" <?php echo $match_selected; ?>><?php echo $matchval; ?></option>
                              <?php 
                              }
                            }
                          ?>
                          </select>  
                        </div>
                      </td> 
                      <td>
                        <i class="fa fa-trash-o mr10 fa-lg remove"></i>
                      </td>                                      
                    </tr> 
                    <?php
                      $i++;
                      }
                    }
                    else 
                    {
                    ?>
                    <tr id="row-1">
                      <td>1</td>
                      <td>
                        <div class="section">
                          <select  tabindex="5" data-placeholder="Select Item"  class="form-control input-sm filter_column" id="filter_column-<?php echo ($key+1); ?>" name="filter_column[]">
                            <option value=""><?php echo trans('label.opt_select_column');?></option>
                            <?php 
                              if(isset($filter_fields) && count($filter_fields)>0)
                              {
                                foreach($filter_fields as $filter_fields_key => $filter_fields_val)
                                {
                                  echo "<option value='".$filter_fields_key."' >".$filter_fields_val."</option>";
                                }
                              } 
                            ?>
                          </select>
                        </div>
                      </td>
                      <td>
                        <select class="form-control  input-sm criteria_ex_selector" name="criteria[]" id="criteria-<?php echo ($key+1); ?>">
                          <option value=""><?php echo trans('label.opt_select_criteria');?></option>
                          <?php
                          $criteria_arr = trans('commonarr.selected_criteria');
                          if(is_array($criteria_arr) && count($criteria_arr) > 0 )
                          {
                            foreach($criteria_arr as $criteriakey => $criteriaval)
                            {
                          ?>
                          <option value="<?php echo $criteriakey; ?>"><?php echo $criteriaval; ?></option>
                          <?php 
                            }
                          }
                          ?>
                          </select>
                      </td>
                      <td class="multi-tar-div">
                        <input placeholder="<?php echo (trans('label.lbl_enter_cr_val'));?>" type="text" value="" name="criteria_value-<?php echo ($key+1); ?>[]" id="criteria_value-<?php echo ($key+1); ?>" class="form-control input-sm criteria_value tagged">
                        <div class="section select_criteria_sec" id="select_criteria_sec<?php echo ($key+1); ?>" style="display:none;">
          								<select class="form-control  input-sm criteria_ex_selector select_criteria chosen-select" name="criteria_value-<?php echo ($key+1); ?>[]"  id="select_criteria-<?php echo ($key+1); ?>" data-placeholder="<?php echo (trans('label.lbl_select_option'));?>" multiple tabindex="6">
          								</select> 			
          						  </div>
                      </td>
                      <td>
                        <div class="section">
                          <select class="form-control  input-sm criteria_ex_selector" name="criteria_match[]" id="criteria_match-<?php echo ($key+1); ?>">
                            <option value=""><?php echo trans('label.opt_select_match');?></option>
                            <?php 
                              $match_arr = trans('commonarr.match');
                              if(is_array($match_arr) && count($match_arr) > 0 )
                              {
                                foreach($match_arr as $matchkey => $matchval){
                                ?>
                                <option value="<?php echo $matchkey; ?>"><?php echo $matchval; ?></option>
                                <?php 
                                }
                              }
                            ?>
                          </select>  
                        </div>
                      </td>
                      <td>
                        <i class="fa fa-trash-o mr10 fa-lg remove"></i>
                      </td>   
                    </tr>
                    <?php 
                      }
                    ?>
                    </tbody>
                  </table>
                  <div class="panel-footer col-md-12">                             
                    <div class="col-md-12 widget-menu textalignright">
                      <a id="add_more_item" class="ccursor"><i class="fa fa-plus" aria-hidden="true"></i> <?php echo trans('label.lbl_addmoreitem');?></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-3 control-label"></label>
            <div class="col-xs-2">
            <?php if($report_id != '') {?>
            <button id="reportseditsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_update');?></button>
            <?php }else{?>
            <button id="reportsaddsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_submit');?></button>
            <?php } ?>
            </div>
            <div class="col-xs-2">
                <button id="reports_reset" type="reset" class="btn btn-info btn-block"><?php echo trans('label.btn_reset');?></button>
            </div>
          </div>
        </form> 
        <?php
        }
        ?>
      </div>
    </div>
  </div>
</div>
<script>
  $(window).load(function(){
    initsingleselect();
    initmultiselect();
    //var tags = new Tags('.tagged');
});
  $(function() {

  let mainWrapper = '.dragSortableItems',
      in_available_fields = '.in_available_fields',
      selectedDropzone = '.selectedDropzone',
      input_name = 'name';
  var module_fields_str = '<?php echo isset($module_fields_str) ?  $module_fields_str : "";?>';

  // On ready
  $(document).ready(function() {
    //var tags = new Tags('.tagged');
    initsingleselect();
    initmultiselect();
    setevent();
    const dragableMultiselect = $('.dragableMultiselect');
    dragableMultiselect.length && dragableMultiselect.each((index, value) => {
      const $this             = $(value);
      const available_fields  = $.extend({}, $this.data('options'));
      const selected_fields   = $.extend([], $this.data('selected'));
      const $input_name       = $this.attr(input_name);
      let fieldTitle          = $this.data('field-title');
      let selectedTitle       = $this.data('selected-title');
      
      let html = '<div class="row dragSortableItems dragSortableItem_' + index + '">\
                    <div class="col-sm-6">\
                      <div class="card">\
                        <div class="card-header">' + fieldTitle + '</div>\
                        <div class="card-body">\
                          <ul class="in_available_fields custom-scrollbar sortable-list fixed-panel ui-sortable"></ul>\
                        </div>\
                      </div>\
                    </div>\
                    <div class="col-sm-6">\
                      <div class="card primaryPanel">\
                        <div class="card-header">' + selectedTitle + '</div>\
                        <div class="card-body">\
                          <div class="alert alert-warning small text-center mb-0">No Fields Selected</div>\
                          <ul class="in_primary_fields sortable-list selectedDropzone fixed-panel">'+module_fields_str+'</ul>\
                        </div>\
                      </div>\
                    </div>\
                  </div>';
      $this.replaceWith(html);
      $dragSortableItem = $('.dragSortableItem_' + index);
      
      let $mainWrapper        = $dragSortableItem.closest(mainWrapper),
        $in_available_fields  = $mainWrapper.find(in_available_fields),
        $selectedDropzone     = $mainWrapper.find(selectedDropzone);

      //console.log(available_fields, selected_fields, $mainWrapper, $in_available_fields, $selectedDropzone, $input_name);

      Object.keys(available_fields).forEach(function(key) {
        var item = '<li class="sortable-item allowPrimary sortable-item-' + key + '" data-fid="' + key + '">'
                + '<span class="icon-drag fas fa-grip-vertical mr-2"></span>'
                + '<input type="checkbox" name="' + $input_name + '" value="' + key + '"  class="sortable-item-input"/>'
                + available_fields[key]
              + '</li>';
        $in_available_fields.append(item);
      });

      selected_fields.map(function(index) {
        var item = $in_available_fields.find('.sortable-item-' + index);
        item.find('.sortable-item-input').prop('checked', true);
        $selectedDropzone.append(item);
      });
      checkFields( $mainWrapper );
      
      // Set up our dropzone
      $in_available_fields.sortable({
        connectWith: '.sortable-list',
        placeholder: 'placeholder',
        start: function(event, ui) {
          if (!$(ui.item).hasClass("allowPrimary")) {
            $mainWrapper.find(".primaryPanel").removeClass('panel-primary').addClass("panel-danger");
          }
          checkFields( $mainWrapper )
        },
        receive: function(event, ui) {
          $(ui.item).find('.sortable-item-input').prop('checked', false);
        },
        stop: function(event, ui) {
          if (!$(ui.item).hasClass("allowPrimary")) {
            $mainWrapper.find(".primaryPanel").removeClass("panel-danger").addClass('panel-primary');
          }
        },
        change: function(event, ui) {
          checkFields( $mainWrapper );
        },
        update: function(event, ui) {
          checkFields( $mainWrapper );
        },
        out: function(event, ui) {
          checkFields( $mainWrapper );
        }
      }).disableSelection();

      // Enable dropzone for primary fields
      $selectedDropzone.sortable({
        connectWith: '.sortable-list',
        placeholder: 'placeholder',
        receive: function(event, ui) {
          // If we dont allow primary fields here, cancel
          if (!$(ui.item).hasClass("allowPrimary")) {
            $(ui.placeholder).css('display', 'none');
            $(ui.sender).sortable("cancel");
          } else {
            $(ui.item).find('.sortable-item-input').prop('checked', true);
          }
        },
        over: function(event, ui) {
          if (!$(ui.item).hasClass("allowPrimary")) {
            $(ui.placeholder).css('display', 'none');
          } else {
            $(ui.placeholder).css('display', '');
          }
        },
        start: function(event, ui) {
          checkFields( $mainWrapper )
        },
        change: function(event, ui) {
          checkFields( $mainWrapper );
        },
        update: function(event, ui) {
          checkFields( $mainWrapper );
        },
        out: function(event, ui) {
          checkFields( $mainWrapper );
        }
      }).disableSelection();
    });
  });

  // Checks to see if the fields section has fields selected. If not, shows a placeholder
  function checkFields( $this ) {
    if ( $this.find(selectedDropzone).find('li').length >= 1) {
      $this.find('.primaryPanel').find('.alert').hide();
    } else {
      $this.find('.primaryPanel').find('.alert').show();
    }
  }
  $('#add_more_item').unbind('click').click(function() 
  {
    validate = validateReport();
    if(validate)
    {
      addMore();    
      var row = $(".addmore tr").last();
      var id  = Number(row.attr('id').match(/\d+/));
      row.find('td').first().html(id);
      row.find('.select_criteria_sec').first().attr('id',"select_criteria_sec"+id);
      row.find('.select_criteria').first().attr('id', "select_criteria-"+id);
      row.find('.chosen-select').first().attr('id', "chosen-select-"+id);
      row.find('.chosen-select').first().val('').trigger('chosen:updated');
      
      var divstr ='<input placeholder="<?php echo (trans('label.lbl_enter_cr_val'));?>" type="text" value="" name="criteria_value-'+id+'[]" id="criteria_value-'+id+'" class="form-control input-sm criteria_value tagged">\
        <div class="section select_criteria_sec" id="select_criteria_sec'+id+'" style="display:none;">\
        <select class="form-control  input-sm criteria_ex_selector select_criteria chosen-select" name="criteria_value-'+id+'[]"  id="select_criteria-'+id+'" data-placeholder="<?php echo (trans('label.lbl_select_option'));?>" multiple tabindex="6">\
        </select>\
        </div>';
      row.find('.multi-tar-div').empty().append(divstr);
      //var tags = new Tags('.tagged');
      //tags.destroy();
      //$("#chosen-select-"+id).chosen();
      //row.find('[class^=location_criteria]').first().attr('class','location_criteria-'+id);
    } 
    else 
    {
      return false;
    }
  });
  $(document).on("click", ".remove", function () 
  {
    var trcount = $(this).parents('table').find('tr').length;
    if(trcount > 2)
    {
      $(this).closest("tr").remove();
      var trlength = $('.addmore tbody tr').length; 
      console.log(trlength); 
      $('.addmore tbody tr').each(function(trlength)
      {
        var id  = Number(trlength + 1);
        $(this).attr('id',id);
        $(this).find('td').first().html(id);
        $(this).find('.select_criteria_sec').first().attr('id',"select_criteria_sec"+id);
        $(this).find('.select_criteria').first().attr('id', "select_criteria-"+id);
        $(this).find('.chosen-select').first().attr('id', "chosen-select-"+id);
        $(this).find('.chosen-select').first().attr('name', "criteria_value-"+id+"[]");
        $(this).find('.criteria_value').first().attr('name',"criteria_value-"+id+"[]");
        $(this).find('.criteria_value').first().attr('id',"criteria_value-"+id);
        });    
    } 
    // removeRow(); 
  });
    

  function setevent()
  {
      $('.daterangepicker1').daterangepicker({
          timePicker24Hour:false,
          step:60,
          autoClose: true,
          separator: ' - ',
          startOfWeek: 'monday',
          singleDate : false,
          timePicker: true,
          //timePickerIncrement: 30,
          startDate: new Date(),
          format: 'YYYY-MM-DD HH:mm',
          showShortcuts: false,
          time: 
          {
          enabled: true
          } 
      });
  }
  function clearcustomtime() 
  {
    $("#customtime").val('');
  }
  $(document).on("click",".chosen-drop .chosen-results li", function() 
  {
    $(".chosen-choices").mCustomScrollbar("scrollTo","left");
  });
});
</script>
<style type="text/css">
.card {
  border: 0 solid #edf2f9;
  border-radius: .375rem;
  -webkit-box-shadow: 0 7px 14px 0 rgba(59, 65, 94, 0.1), 0 3px 6px 0 rgba(0, 0, 0, 0.07);
  box-shadow: 0 7px 14px 0 rgba(59, 65, 94, 0.1), 0 3px 6px 0 rgba(0, 0, 0, 0.07);
}

.card-body {
  padding: .5rem;
  background-color: #f9fafd !important;
}

.card-header {
  padding: 1rem 1.25rem;
  background-color: #fff;
  border-bottom: 0 solid #edf2f9;
}

.dragableMultiselect {
  display: none;
}

.dragSortableItems .sortable-list {
  list-style: none;
  margin: 0;
  min-height: 20px;
  padding: 0px;
}
.dragSortableItems .sortable-item {
  background-color: #fff;
  border: 1px solid #ddd;
  display: block;
  margin-bottom: -1px;
  padding: 10px;
  cursor: move;
  position: relative;
  padding-left: 30px;
}
.dragSortableItems .sortable-item .icon-drag {
  color: #ccc;
  position: absolute;
  left: 10px;
  top: 50%;
  transform: translateY(-50%);
}
.dragSortableItems .sortable-item .sortable-item-input {
  visibility: hidden;
  pointer-events: none;
  position: absolute;
}
.dragSortableItems .placeholder {
  border: 1px dashed #666;
  height: 45px;
  margin-bottom: 5px;
}
.dragSortableItems .fixed-panel {
  max-height: 500px;
  overflow-y: auto;
  padding-bottom: 1px;
}

.custom-scrollbar::-webkit-scrollbar {
  width: 7px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #888;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  border-radius: 5px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: #555;
}
.radio {
  margin: 0.5rem;
}
.radio input[type="radio"] {
  position: absolute;
  opacity: 0;
}
.radio input[type="radio"] + .radio-label:before {
  content: '';
  background: #f4f4f4;
  border-radius: 100%;
  border: 1px solid #b4b4b4;
  display: inline-block;
  width: 1.4em;
  height: 1.4em;
  position: relative;
  top: -0.2em;
  margin-right: 1em;
  vertical-align: top;
  cursor: pointer;
  text-align: center;
  -webkit-transition: all 250ms ease;
  transition: all 250ms ease;
}
.radio input[type="radio"]:checked + .radio-label:before {
  background-color: #3197EE;
  box-shadow: inset 0 0 0 4px #f4f4f4;
}
.radio input[type="radio"]:focus + .radio-label:before {
  outline: none;
  border-color: #3197EE;
}
.radio input[type="radio"]:disabled + .radio-label:before {
  box-shadow: inset 0 0 0 4px #f4f4f4;
  border-color: #b4b4b4;
  background: #b4b4b4;
}
.radio input[type="radio"] + .radio-label:empty:before {
  margin-right: 0;
}
.chosen-container.chosen-container-multi {
    width: 300px !important; /* or any value that fits your needs */
}
.chosen-container.chosen-container-single {
    width: 300px !important; /* or any value that fits your needs */
}
.tags-container {
  display: -webkit-box;
  display: flex;
  -webkit-box-orient: horizontal;
  -webkit-box-direction: normal;
  flex-flow: row wrap;
  /*margin-bottom: 15px;*/
  width: 300px;
  min-height: 31px;
  padding: 2px 5px;
  font-size: 14px;
  line-height: 1.4;
  background-color: transparent;
  border: 1px solid #ccc;
  border-radius: 1px;
  overflow: hidden;
  word-wrap: break-word;
  box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
}

input.tag-input {
  -webkit-box-flex: 3;
  flex: 3;
  border: 0;
  outline: 0;
}

.tag {
  position: relative;
  margin: 2px 6px 2px 0;
  padding: 1px 20px 1px 8px;
  font-size: inherit;
  font-weight: 400;
  text-align: center;
  color: #fff;
  background-color: #317CAF;
  border-radius: 3px;
  -webkit-transition: background-color .3s ease;
  transition: background-color .3s ease;
  cursor: default;
}
.tag:first-child {
  margin-left: 0;
}
.tag--marked {
  background-color: #6fadd7;
}
.tag--exists {
  background-color: #EDB5A1;
  -webkit-animation: shake 1s linear;
          animation: shake 1s linear;
}
.tag__name {
  margin-right: 3px;
}

.tag__remove {
  position: absolute;
  right: 0;
  bottom: 0;
  width: 20px;
  height: 100%;
  padding: 0 5px;
  font-size: 16px;
  font-weight: 400;
  -webkit-transition: opacity .3s ease;
  transition: opacity .3s ease;
  opacity: .5;
  cursor: pointer;
  border: 0;
  background-color: transparent;
  color: #fff;
  line-height: 1;
}
.tag__remove:hover {
  opacity: 1;
}
.tag__remove:focus {
  outline: 5px auto #fff;
}

@-webkit-keyframes shake {
  0%, 100% {
    -webkit-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
  }
  10%, 30%, 50%, 70%, 90% {
    -webkit-transform: translate3d(-5px, 0, 0);
            transform: translate3d(-5px, 0, 0);
  }
  20%, 40%, 60%, 80% {
    -webkit-transform: translate3d(5px, 0, 0);
            transform: translate3d(5px, 0, 0);
  }
}

@keyframes shake {
  0%, 100% {
    -webkit-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
  }
  10%, 30%, 50%, 70%, 90% {
    -webkit-transform: translate3d(-5px, 0, 0);
            transform: translate3d(-5px, 0, 0);
  }
  20%, 40%, 60%, 80% {
    -webkit-transform: translate3d(5px, 0, 0);
            transform: translate3d(5px, 0, 0);
  }
}
.criteria_value{width:80% !important;}
</style>