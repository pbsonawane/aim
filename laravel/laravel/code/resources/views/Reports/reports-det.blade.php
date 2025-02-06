<!-- Start: Topbar -->
<header id="topbar" class="affix"  >
<div class="topbar-left">
  <?php //breadcrum(trans('title.reports')); ?>
  <ol class="breadcrumb">
    <li class="crumb-active nounderline">
      <a class="nounderline"><?php echo trans('title.reports'); ?></a>
    </li>
    <li class="crumb-link">
      <a href="<?php echo config('enconfig.site_url'); ?>"><span class="glyphicon glyphicon-home"></span></a>
    </li>
    <li class="crumb-link"><?php echo trans('title.itam');?></li>
    <li class="crumb-link"><a href="<?php echo url('reports/') ?>"><?php echo trans('title.reports');?></a></li>
   
   <li class="crumb-link"><a href="<?php echo url('poreports/details', $report_id) ?>"><?php echo trans('title.reportdetails');?></a></li> 
  </ol>
</div>
<div class="topbar-right">
  @if(is_array($reportmodules) && count($reportmodules)>0)
  <div class="btn-group">
    <button id="timeline-toggle" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
      <i class="fa fa-download"></i> &nbsp;<?php echo trans('label.lbl_export');?>
    </button>
     <ul class="dropdown-menu pull-right" role="menu">
      <li class="" title="<?php echo trans('label.lbl_export_pdf');?>">
        <a id="pdf_<?php echo isset($report_id) ? $report_id : ''; ?>" class="export_report"> <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
         &nbsp;<?php echo trans('label.lbl_export_pdf');?></span></a>
      </li>
      <li class="" title="<?php echo trans('label.lbl_export_excel');?>">
        <a id="excel_<?php echo isset($report_id) ? $report_id : ''; ?>" class="export_report"> <i class="fa fa-file-excel-o" aria-hidden="true"></i>
         &nbsp;<?php echo trans('label.lbl_export_excel');?></a>
      </li>
      <li class="" title="<?php echo trans('label.lbl_export_csv');?>">
        <a id="csv_<?php echo isset($report_id) ? $report_id : ''; ?>" class="export_report"> <i class="fa fa-file" aria-hidden="true"></i>
         &nbsp;<?php echo trans('label.lbl_export_csv');?></a>
      </li>
    </ul>
  </div>
  @endif
</div>
</header>
<?php
if(is_array($reportmodules) && count($reportmodules)>0)
{

  $curmodulekey         = @$reportmodules['module_key'];
  $module_name          = @$reportmodules['module_name'];
  $module_fields        = @$reportmodules['module_fields'];
  $filter_fields        = json_decode(@$reportmodules['filter_fields'],true);
  $date_filter_fields   = json_decode(@$reportmodules['date_filter_fields'],true);
  $filter_field_data    = "";
  if (isset($reportsdata[0]['filter_fields']) && $reportsdata[0]['filter_fields']) 
  {
    $reports_fields_arr  = json_decode($reportsdata[0]['filter_fields'],true);
    if (is_array($reports_fields_arr)) 
    {
       foreach ($reports_fields_arr as $rep_field) 
      {
        $filter_field_data.='<input type="hidden" name="filter_fields[]" value="'.$rep_field.'">';
      }
    }
  }
}
?>
<!-- End: Topbar -->
<div id="content">
  <div class="row">
    <div class="col-md-12">
      <div class="alert hidden alert-dismissable" id="msg_div"></div>
      <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div>
    <div class="col-md-12" id="">
      @if(isset($reportsdata[0]) && is_array($reportsdata[0]) && count($reportsdata[0])>0)
      <form class="form-horizontal"  name="addformreports" id="addformreports">
      <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
        <div class="panel">
          <div class="panel-heading" role="tab" id="headingTwo">
            <h4 class="panel-title" style="padding:1%;">
            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
             <span class="panel-icon"><i class="fa fa-list"></i></span>
              <span class="panel-title">
                <?php echo trans('label.lbl_apply_filters');?>
              </span>   
            </a>
            <div class="widget-menu pull-right" style="margin-right: 50%">
            <?php if(isset($reportsdata[0]['report_name'])) echo $reportsdata[0]['report_name'];?>
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
                            if(isset($date_filter_fields) && is_array($date_filter_fields) && count($date_filter_fields)>0)
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
                      <div class="f-field-group">
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
                if(isset($filters) && is_array($filters) && count($filters)>0)
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
                    <?php
                    if($report_id == '2be3ed74-041e-11ec-bc69-a2bc2bf41391')
                    {
                      $filter_fields['vendor']='Vendor';
                    }
                    ?>
                    <div class="section">
                      <select  tabindex="5" data-placeholder="Select Item"  class="form-control input-sm filter_column" id="filter_column-<?php echo ($i+1); ?>" name="filter_column[]">
                        <option value=""><?php echo trans('label.opt_select_column');?></option>
                        <?php 
                        if(isset($filter_fields) && is_array($filter_fields) && count($filter_fields)>0)
                        {
                          foreach($filter_fields as $filter_fields_key => $filter_fields_val)
                          {
                            $filter_fields_selected = "";
                            if (isset($filter['filter_column']) && $filter['filter_column'] == $filter_fields_key) 
                            {
                              $filter_fields_selected = "selected";
                        ?>
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
                          if(isset($filter_fields) && is_array($filter_fields) && count($filter_fields)>0)
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
                      <select class="form-control  input-sm criteria_ex_selector select_criteria chosen-select" name="criteria_value-<?php echo ($key+1); ?>[]"  id="select_criteria-<?php echo ($key+1); ?>" multiple tabindex="6">
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
                <div class="col-md-12 widget-menu textalignright">
                  <div class="col-xs-2">
                    <button id="reportseditsubmit" type="button" class="btn btn-success btn-block"><?php echo trans('label.btn_update');?></button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <input id="report_id" name="report_id" type="hidden" value="<?php echo $report_id;?>">
      <input type="hidden" id="report_name" name="report_name" value="<?php if(isset($reportsdata[0]['report_name'])) echo $reportsdata[0]['report_name'];?>">
      <input type="hidden" id="report_cat_id" name="report_cat_id" value="<?php if(isset($reportsdata[0]['report_cat_id'])) echo $reportsdata[0]['report_cat_id'];?>">
      <input type="hidden" name="module" value="<?php echo @$curmodulekey;?>">
      <input type="hidden" name="share_report"  id="share_report" value="<?php if(isset($reportsdata[0]['share_report'])) echo $reportsdata[0]['share_report'];?>" name="share_report">
       <input type="hidden" id="ci_templ_id" name="ci_templ_id" value="<?php if(isset($reportsdata[0]['ci_templ_id'])) echo $reportsdata[0]['ci_templ_id'];?>">
       <input type="hidden" id="ci_type_id" name="ci_type_id" value="<?php if(isset($reportsdata[0]['ci_type_id'])) echo $reportsdata[0]['ci_type_id'];?>">
      <?php if(isset($filter_field_data)) echo $filter_field_data;?>
      </form> 
      @endif
      <form method="post" name="frmrepdet" id="frmrepdet">    
          <div class="panel">
            <?php echo csrf_field(); ?> 
            <?php echo isset($emgridtop) ? $emgridtop : ''; ?>
            <div class="panel panel-visible" id="grid_data"></div>
            <input type="hidden" name="report_id" value="<?php echo isset($report_id) ? $report_id : ''; ?>">
          </div>
        </form>
    </div>
  </div>
</div>
</div>
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/common.js"></script> 
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/reports/tags.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/reports/reportsdetails.js"></script>
<script>
  $(window).load(function(){
    initsingleselect();
    initmultiselect();
    //var tags = new Tags('.tagged');
});
  $(function() {

  // On ready
  $(document).ready(function() {
    //var tags = new Tags('.tagged');
    initsingleselect();
    initmultiselect();
    setevent();
  });

  // Checks to see if the fields section has fields selected. If not, shows a placeholder
  function checkFields( $this ) 
  {
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
  $(document).on("click",".chosen-drop .chosen-results li", function() {
        $(".chosen-choices").mCustomScrollbar("scrollTo","left");
    });
});
</script>
<style type="text/css">
.chosen-container.chosen-container-multi {
    width: 400px !important; /* or any value that fits your needs */
}
.tags-container {
  display: -webkit-box;
  display: flex;
  -webkit-box-orient: horizontal;
  -webkit-box-direction: normal;
  flex-flow: row wrap;
  /*margin-bottom: 15px;*/
  width: 400px;
  /*min-height: 34px;*/
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
.criteria_value{width:68% !important;}
</style>