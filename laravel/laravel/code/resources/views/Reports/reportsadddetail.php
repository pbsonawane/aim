<div class="row">
  <div class="col-md-10">
    <div class="hidden alert-dismissable" id="msg_popup"></div>
  </div>
  <div class="col-md-12">
    <div class="panel">
      <div class="panel-body">
        <form class="form-horizontal"  name="addformreports" id="addformreports">
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
              <div class="panel invoice-panel">
                  <div class="panel-body p20" id="invoice-item">
                    <input id="report_id" name="report_id" type="hidden" value="<?php echo $report_id?>">
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
                        <select class="form-control input-sm" name="module" id="module">
                          <option value=""><?php echo trans('label.opt_select_module');?></option>
                          <?php 
                            if(is_array($reportmodules) && count($reportmodules)>0)
                            {
                              $curmodulekey   = $reportmodules[0]['module_key'];
                              $module_fields  = $reportmodules[0]['module_fields'];
                              $filter_fields  = json_decode($reportmodules[0]['filter_fields'],true);
                              $date_filter_fields  = json_decode($reportmodules[0]['date_filter_fields'],true);

                              if (isset($reportsdata[0]['filter_fields'])) 
                              {
                                $reports_fields_arr  = json_decode($reportsdata[0]['filter_fields'],true);
                                $module_fields_arr   = json_decode($module_fields,true);

                                if (is_array($reports_fields_arr) && is_array($module_fields_arr)) 
                                {
                                  $module_fields_common   = array_intersect_key($module_fields_arr, array_flip($reports_fields_arr));

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
                              foreach($reportmodules as $module)
                              {
                                if (isset($reportsdata[0]['module']))
                                  {
                                    $curmodulekey = isset($reportsdata[0]['module']) ? $reportsdata[0]['module'] : '';
                                  }
                          ?>
                              <option value="<?php echo $module['module_key'] ?>" <?php if($curmodulekey == $module['module_key']){echo "selected";} ?> > <?php echo $module['module_name'] ?> </option>
                          <?php
                              }
                            } 
                          ?>
                        </select>       
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
                      Choose Fields
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
                    &nbsp;&nbsp;Drag &amp; Drop fields from the left (Available Fields) over to the right side in the desired location on your report.
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
                      Apply Filters  
                    </span>   
                  </a>
                  <div class="widget-menu pull-right">
                  </div>
                </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                  <div class="panel-body">
                  <h4>Date Filters</h4>   
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
                        $customtime     = $reportsdata[0]['filter_date_value'];
                      }
                      if (isset($reportsdata[0]['filter_date_range']) && $reportsdata[0]['filter_date_range'] !="")
                      {
                        $timerange      = $reportsdata[0]['filter_date_range'];
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
                            <select class="form-control input-sm" id="timerange"  name="filter_date_range" onclick="clearcustomtime();">
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
                          </div>
                        </td>
                        <td>
                          <div class="form-control input-sm section f-field-group">
                                <input type="text" id="customtime" class="input-sm form-control pull-right daterangepicker1" name="filter_date_range_value" placeholder="<?php echo trans('label.opt_sel_date_range');?>" readonly value="<?php echo $customtime; ?>" onfocus="cleardatetimerange();">
                            </div>
                        </td> 
                      </tr>
                    </tbody>
                  </table>
                  <h4>Advanced Filters</h4>   
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
                    ?>
                      <tr id="row-<?php echo ($i+1); ?>">
                        <td><?php echo ($i+1); ?></td>
                        <td>
                          <div class="section">
                              <select  tabindex="5" data-placeholder="Select Item"  class="form-control input-sm" id="filter_column-<?php echo ($i+1); ?>" name="filter_column[]">
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
                                      }
                                      echo "<option value='".$filter_fields_key."' ".$filter_fields_selected.">".$filter_fields_val."</option>";
                                    }
                                  } 
                                ?>
                                </select>
                            </div>
                        </td>
                        <td>
                          <div class="section">
                            <select class="form-control  input-sm criteria_ex_selector" name="criteria[]" id="criteria-<?php echo ($i+1); ?>">
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
                          </div>
                        </td>
                        <td>
                          <input placeholder="<?php echo (trans('label.lbl_enter_cr_val'));?>" type="text" value="<?php if(isset($filter['criteria_value'])) echo $filter['criteria_value'];?>" name="criteria_value[]" id="criteria_value-<?php echo ($i+1); ?>" class="form-control input-sm">
                        </td>
                        <td>
                          <div class="section">
                            <select class="form-control  input-sm criteria_ex_selector" name="criteria_match[]" id="criteria_match-<?php echo ($key+1); ?>">
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
                              <select  tabindex="5" data-placeholder="Select Item"  class="form-control input-sm" id="filter_column-<?php echo ($key+1); ?>" name="filter_column[]">
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
                          <div class="section">
                            <select class="form-control  input-sm criteria_ex_selector" name="criteria[]" id="criteria-<?php echo ($key+1); ?>">
                              <option value=""><?php echo trans('label.opt_select_criteria');?></option>
                            <?php 
                              $criteria_arr = trans('commonarr.selected_criteria');
                              if(is_array($criteria_arr) && count($criteria_arr) > 0 )
                              {
                                foreach($criteria_arr as $criteriakey => $criteriaval){
                                ?>
                                <option value="<?php echo $criteriakey; ?>"><?php echo $criteriaval; ?></option>
                                <?php 
                                }
                              }
                            ?>
                            </select>  
                          </div>
                        </td>
                        <td>
                          <input placeholder="<?php echo (trans('label.lbl_enter_cr_val'));?>" type="text" value="" name="criteria_value[]" id="criteria_value-<?php echo ($key+1); ?>" class="form-control input-sm">
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
              <div class="panel">
                <div class="panel-heading" role="tab" id="headingThree">
                  <h4 class="panel-title" style="padding:1%;">
                  <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                   <span class="panel-icon"><i class="fa fa-list"></i></span>
                    <span class="panel-title">
                      Shedule Report
                    </span>   
                  </a>
                  <div class="widget-menu pull-right">
                  </div>
                </h4>
                </div>
                <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                  <div class="panel-body">
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
      </div>
    </div>
  </div>
</div> 
<script>
  $(function() {

  let mainWrapper = '.dragSortableItems',
      in_available_fields = '.in_available_fields',
      selectedDropzone = '.selectedDropzone',
      input_name = 'name';
  var module_fields_str = '<?php echo isset($module_fields_str) ?  $module_fields_str : "";?>';

  // On ready
  $(document).ready(function() {
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
    $(document).on("click","#add_more_item", function() 
    {  
        var validate = true;
        $('.addmore').find('tr input[type=text]').each(function(){
          if($(this).val() == ""){
              $(this).css('border-color','red');
              validate = false;
          }
          else
          {
            $(this).css('border-color','#dddddd');
          }
        });
        $('.addmore').find("select").each(function (index, element){
          if($(this).val() == "")
          {
            $(this).css('border-color','red');
            validate = false;
          }
          else
          {
            $(this).css('border-color','#dddddd');
          }
        });
        if(validate){
          addMore();    
          var row = $(".addmore tr").last();
          var id = Number(row.attr('id').match(/\d+/));  
          row.find('td')
          .first()
          .html(id);
          row.find('.chosen-select')
          .first() 
          .attr('id', "chosen-select-"+id);
          $("#chosen-select-"+id).chosen();
        } 
        else 
        {
          return false;
        }
    });
    $(document).on("click", ".remove", function () {
        removeRow(); 
    });
    function cleardatetimerange() 
    {
        $("#timerange").val('');
    }

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
function clearcustomtime() {
    $("#customtime").val('');
}
  
$(document).on("change","#module", function() { var module_key = $("#module").val(); dcListbox(module_key); });

// Get DCs for selected POD
function dcListbox(module_key)
{ 
  if (module_key != '')
  {
    var postData = {'datatype' : 'json', 'module_key' : module_key};
    var url =  SITE_URL+'/getreportmodules/';
    var dcajax = ajaxCall(dcajax, url, postData, function (data) {
        var result = JSON.parse(data);
        console.log(result.module_fields);
        $('#filter_fields').data('data-options', result.module_fields);
         $(".in_available_fields").html();
        $.each(JSON.parse(result.module_fields), function (key, value) {  
        $(".in_available_fields").append('<li class="sortable-item allowPrimary sortable-item-contract_id ui-sortable-handle" data-fid="contract_id" style="position: relative; left: 0px; top: 0px;"><span class="icon-drag fas fa-grip-vertical mr-2"></span><input type="checkbox" name="filter_fields[]" value="'+key+'" class="sortable-item-input" checked=checked>'+value+'</li>');
      }) ;

    });
  }
}

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

</style>