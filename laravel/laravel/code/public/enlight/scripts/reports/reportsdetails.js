var postData = null;
var countRow = 0;
$(document).ready(function () 
{
  reportdetailsList();
  reportdetailsList_po();
  reportdetailsList_pr();
  $(document).on("click", ".export_report", function () { var report_id = $(this).attr('id'); reportexport(report_id); });
  $(document).on("click", "#reportseditsubmit", function () { reportseditsubmit(); });
  $(document).on('change', '.filter_column', function(){ 
    var str = this.id;
    var res = str.split("-");
    getColumnId( this.value, res[1]);
  });

});

function reportdetailsList()
{
  //alert('Comman');
    closeMsgAuto('msg_div');
    emLoader('show', trans('label.lbl_loading'));
    var url         = SITE_URL + '/reports/details/list';
    var postData    = $("#frmrepdet").serialize();
    var exporttype  = $("#frmrepdet input[name=exporttype]").val();
    if (exporttype == 'pdf' || exporttype == 'csv' || exporttype == 'print') 
    {
       var obj_form = document.frmrepdet;
       var mywindow = submitForm(url, obj_form, 1, 1);
       $("#frmrepdet input[name=exporttype]").val('');
       $("#frmrepdet input[name=page]").val('');
       emLoader('hide');
   }
   else 
   {
       var ajax_result = ajaxCall(ajax_result, url, postData, function (data) {
            showResponse(data, 'grid_data', 'msg_div');
            emLoader('hide');
        });    
    }
}
function reportdetailsList_po()
{
  //alert('PO');
    closeMsgAuto('msg_div');
    emLoader('show', trans('label.lbl_loading'));
    var url         = SITE_URL + '/poreports/details/list';
    var postData    = $("#frmrepdet").serialize();
    var exporttype  = $("#frmrepdet input[name=exporttype]").val();
    if (exporttype == 'pdf' || exporttype == 'csv' || exporttype == 'print') 
    {
       var obj_form = document.frmrepdet;
       var mywindow = submitForm(url, obj_form, 1, 1);
       $("#frmrepdet input[name=exporttype]").val('');
       $("#frmrepdet input[name=page]").val('');
       emLoader('hide');
   }
   else 
   {
       var ajax_result = ajaxCall(ajax_result, url, postData, function (data) {
            showResponse(data, 'grid_data', 'msg_div');
            emLoader('hide');
        });    
    }
}
function reportdetailsList_pr()
{
  //alert('PR');
    closeMsgAuto('msg_div');
    emLoader('show', trans('label.lbl_loading'));
    var url         = SITE_URL + '/prreports/details/list';
    var postData    = $("#frmrepdet").serialize();
    var exporttype  = $("#frmrepdet input[name=exporttype]").val();
    if (exporttype == 'pdf' || exporttype == 'csv' || exporttype == 'print') 
    {
       var obj_form = document.frmrepdet;
       var mywindow = submitForm(url, obj_form, 1, 1);
       $("#frmrepdet input[name=exporttype]").val('');
       $("#frmrepdet input[name=page]").val('');
       emLoader('hide');
   }
   else 
   {
       var ajax_result = ajaxCall(ajax_result, url, postData, function (data) {
            var dataArray = JSON.parse(data);
            countRow = dataArray['total_records'];
            showResponse(data, 'grid_data', 'msg_div');
            emLoader('hide');
        });    
    }
}
function reportexport(report_id="") 
{
  if(countRow == 0)
  {
    alert("No Record Found.");
    return false;
  }
  clearMsg('msg_popup');
  emLoader('show', trans('label.lbl_loading'));
  var id            = report_id.split('_')[1];
  var report_type   = report_id.split('_')[0];
  var postData      = {'report_id': id ,'report_type':report_type};
  var url           = SITE_URL + '/reports/export';
  var reportexportsajax = ajaxCall(reportexportsajax, url, postData, function (data) 
  {
      emLoader('hide');
      window.scrollTo(0, 0);
      showResponse(data, 'grid_data', 'msg_div');
      window.setTimeout(function(){location.reload()},1500);
  });
}

function reportseditsubmit() 
{
    clearMsg('msg_popup');
    clearMsg('msg_div');
    emLoader('show', trans('label.lbl_loading'));
    pushTag();
    var url             = SITE_URL + '/reports/editsubmit';
    var postData        = $("#addformreports").serialize();
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) 
    {
        var result = JSON.parse(data);
        if (result.is_error) 
        {
            showResponse(data, '','msg_popup');
            emLoader('hide');
        }
        else 
        {
            emLoader('hide');
            lightbox('hide');
            window.scrollTo(0, 0);
            showResponse(data,'grid_data','msg_div' );
            window.setTimeout(function(){location.reload()},1500);
            //reportsList();
        }
    });
}

//This is used to call the data of locations, dc, bv, cost center and vendor.
function getBvLocData(elemkey, value, selected_value = '')
{  
  emLoader('show', trans('label.lbl_loading'));
  var url      = SITE_URL + '/getreportformdata';
  var field = value.split(".").pop();
  var reportids = $("#report_id").val();
  var postData = {'field': field ,'selected_value':selected_value, 'reportids':reportids};
  console.log(postData);
  var vendorajax = ajaxCall(vendorajax, url, postData, function(data)
  {
    if(data)
    {
      emLoader('hide');
      var jsondata = JSON.parse(data);
      $('#select_criteria-'+elemkey).empty().append(jsondata);
      initmultiselect();
      $('#select_criteria-'+elemkey).trigger("chosen:updated");
      $('#select_criteria-'+elemkey).chosen({ width: "100%" });
    }
  });
}
//THis function is used to change the criteria value to selectbox or texbox based on condition.
function getColumnId(value, elemkey, selected_value = '')
{
  var columnIds = new Array('','location','loc','business_vertical','bv','datacenter','dc','vendor','contract_status','renewed','contracttype','sw_category','sw_type','sw_manufacturer','pr_priority','cost_center','po_status','status','asset_status','citype','ciname','po_name');

  if(jQuery.inArray(value, columnIds) !== -1)
  {
    if (value == 'loc') {value ='location';}
    if (value == 'bv')  {value ='business_vertical';}
    if (value == 'dc')  {value ='datacenter';} 

    getBvLocData(elemkey,value,selected_value);
    
    $("#select_criteria_sec-"+elemkey).removeAttr('disabled');
    $("#select_criteria_sec"+elemkey).show();
    $("#criteria_value-"+elemkey).attr('disabled','disabled');
    $("#criteria_value-"+elemkey).hide();
    $("#criteria_value-"+elemkey).next('div.tags-container').hide();
    
    var title = new Array("contains","notcontains","start_with","end_with");
    var i;

    for (i = 0; i < title.length; i++) {
      $("#criteria-"+elemkey+" option[value=" + title[i] + "]").hide();
    }
  }
  else
  {
    $("#select_criteria_sec-"+elemkey).attr('disabled','disabled');
    $("#select_criteria_sec"+elemkey).hide();
    $("#criteria_value-"+elemkey).removeAttr('disabled');
    $("#criteria_value-"+elemkey).show();
    $("#criteria_value-"+elemkey).next('div.tags-container').show();
    if(selected_value != "")
    {
     $("#criteria_value-"+elemkey).val(selected_value);
    }
    var title = new Array("contains","notcontains","start_with","end_with");
    var i;
    for (i = 0; i < title.length; i++)
    {
     $("#criteria-"+elemkey+" option[value=" + title[i] + "]").show();
    }
    var tags = new Tags("#criteria_value-"+elemkey);
    $('input.tag-input').attr('placeholder',trans('label.lbl_enter_mul_val'));
    $('input.tag-input').attr('title',trans('label.lbl_enter_mul_val'));

  }
}

function validateReport()
{
  var validate = true;
  $('.addmore:visible').find('input.criteria_value').each(function(index, element)
  {
    if($(element).is(":visible"))
    {
      if($(this).val() == "")
      {
        $(this).css('border-color','red');
        validate = false;
      }
      else
      {
        $(this).css('border-color','#dddddd');
      }
    }
  });

  $('.addmore:visible').find("select").each(function (index, element)
  {
    if($(element).is(":visible"))
    {
      if($(this).val() == "")
      {
        $(this).css('border-color','red');
        validate = false;
      }
      else
      {
        $(this).css('border-color','#dddddd');
      }
    }
  });
  
  var row = $(".addmore tr").last();
  var id  = Number(row.attr('id').match(/\d+/));
  if (id)
  {
    var prevId        = Number(id);
    var taginp        = $("#criteria_value-"+prevId).next("div.tags-container").find('input.tag-input:visible').val();
    var criteria_val  = $("#criteria_value-"+prevId).val();

    if (typeof(taginp) != "undefined" && taginp!="" && criteria_val=="")
    {
      var tagStr = '<div class="tag"><span class="tag__name">'+taginp+'</span><button class="tag__remove">×</button></div>';
      $("#criteria_value-"+prevId).next("div.tags-container").find('input.tag-input').val("");
      $("#criteria_value-"+prevId).val(taginp);
      $("#criteria_value-"+prevId).next("div").prepend(tagStr);
      $("#criteria_value-"+prevId).next("div").css('border-color','#ccc');
    }
    else
    {
      if (criteria_val) 
      {
        $("#criteria_value-"+prevId).next("div").css('border-color','#ccc');
      }
      else
      {
        if (typeof(taginp) != "undefined" && taginp=="")
        {
          $("#criteria_value-"+prevId).next("div").css('border-color','red');
          validate = false;
        }
        else
        {
          $("#criteria_value-"+prevId).next("div").css('border-color','#ccc');
        }

        var chosenVal =  $("#select_criteria-"+prevId).chosen().val();
        if (typeof(chosenVal) != "undefined" && !chosenVal)
        {
          $("#select_criteria_"+prevId+"_chosen").css('border','1px solid red');
          validate = false;
        }
        else
        {
          $("#select_criteria_"+prevId+"_chosen").css('border','none');
        }
      }
    } 
  }
  return validate;
}

function pushTag()
{
  var validate        =  false;
  var criteria        =  $("select[name='criteria[]']").val();
  var filter_column   =  $("select[name='filter_column[]']").val();
  var criteria_value  =  $("input.criteria_value").val();
  var criteria_match  =  $("select[name='criteria_match[]']").val();
  if ((typeof(criteria) != "undefined" && criteria!="") || (typeof(filter_column) != "undefined" && filter_column!="") || (typeof(criteria_value) != "undefined" && criteria_value!="") || (typeof(criteria_match) != "undefined" && criteria_match!=""))
  {
    var validate = validateReport();
    return validate;
  }
  else
  {
    return true;
  }
}