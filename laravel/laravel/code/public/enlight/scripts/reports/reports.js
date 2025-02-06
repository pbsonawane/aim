var postData = null;
$(document).ready(function () 
{
    //reportsList();
    $(document).on("click", "#reportsadd", function () { reportsadd(); });
    $(document).on("click", "#reportsaddsubmit", function () { reportsaddsubmit(); });
    $(document).on("click", ".reports_edit", function () { var report_id = $(this).attr('id'); reportsedit(report_id); });
    $(document).on("click", "#reportseditsubmit", function () { reportseditsubmit(); });
    $(document).on("click", ".reports_del", function () { var report_id = $(this).attr('id'); reportsdelete(report_id); });
    $(document).on("click", "#reportsaddsubmitprimary", function () { reportsaddsubmitprimary(); });
    $(document).on("click", "#reports_reset", function () {   $("#addformreportsprimary").find('.chosen-container').val('').trigger('chosen:updated');
	resetForm('addformreportsprimary'); });
    $(document).on('change', '.filter_column', function()
    { 
      var str = this.id;
      var res = str.split("-");
      getColumnId( this.value, res[1]);
    });

    $(document).on('change', '#addformreportsprimary input[name=module]', function()
    {
      if($(this).val() == "CMDB" || $(this).val() == "ALLCOMP")
      {
        $('div.cmdb-assets').hide();
        $(this).siblings('div.cmdb-assets').show();
      }
      else
      {
        $('div.cmdb-assets').hide();
      }
    });


});


function reportsList()
{
    closeMsgAuto('msg_div');
    emLoader('show', trans('label.lbl_loading'));
    var url         = SITE_URL + '/reports/list';
    var postData    = $("#frmrepcat").serialize();
    var exporttype  = $("#frmrepcat input[name=exporttype]").val();
    if (exporttype == 'pdf' || exporttype == 'csv' || exporttype == 'print') 
    {
       var obj_form = document.frmrepcat;
       var mywindow = submitForm(url, obj_form, 1, 1);
       $("#frmrepcat input[name=exporttype]").val('');
       $("#frmrepcat input[name=page]").val('');
       emLoader('hide');
   }
   else 
   {
     var ajax_result = ajaxCall(ajax_result, url, postData, function (data) {
          showResponse(data, 'report_data', 'msg_div');
          emLoader('hide');
      });    
    }
}

function reportsadd() 
{
  closeMsgBox('msg_div');
  var url = SITE_URL + '/reports/add';
  initsingleselect();
  initmultiselect();
  var notifyajax = ajaxCall(notifyajax, url, {}, function (data) 
  {
    lightbox('show', data, trans('label.lbl_add_report'), 'maxlarge');
    emLoader('hide');
  });

}



function reportsaddsubmitprimary() 
{
  closeMsgBox('msg_div');
  emLoader('show', trans('label.lbl_loading'));
  var validate    = true;
  var postData    = $("#addformreportsprimary").serialize();
  var radioval    = $("#addformreportsprimary input[type='radio']:checked").val();
  if(radioval == "CMDB" || radioval == "ALLCOMP")
  {
    if($('select[name="ci_templ_'+radioval+'"]').val() == "")
    {
      $('select[name="ci_templ_'+radioval+'"]').next().css('border','1px solid red');
      validate = false;
      emLoader('hide');
    }
    else
    {
      $(".chosen-single").css('border','1px solid #cccccc');
      validate = true;
    }
  }
  if (validate) 
  {
    var url = SITE_URL + '/reports/add';
    var notifyajax = ajaxCall(notifyajax, url,postData, function (data) 
    {
        lightbox('show', data, trans('label.lbl_add_report'), 'maxlarge');
        emLoader('hide');
    });
  }
}

function reportsaddsubmit() 
{
  var validate = true;
  clearMsg('msg_popup');
  emLoader('show', trans('label.lbl_loading'));
  pushTag();
  var url = SITE_URL + '/reports/addsubmit';
  var postData = $("#addformreports").serialize();
  var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) 
  {
      var result = JSON.parse(data);
      if (result.is_error) {
          showResponse(data, '',  'msg_popup');
          emLoader('hide');
      }
      else 
      {
          emLoader('hide');
          lightbox('hide');
          window.scrollTo(0, 0);
          showResponse(data, 'grid_data', 'msg_div');
          window.setTimeout(function(){location.reload()},1500);
          //reportsList();
      }
  });
}
function reportsedit(report_id) 
{
    var id       = report_id.split('_')[1];
    var postData = { 'datatype': 'json', 'id': id };
    var url      = SITE_URL + '/reports/edit';
    initsingleselect();
    initmultiselect();
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) 
    {
        lightbox('show', data, trans('label.lbl_edit_report'), 'maxlarge');
        emLoader('hide');

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
function reportsdelete(report_id)
{
    if (confirm(trans('label.msg_confirm'))) 
    {
        clearMsg('msg_popup');
        emLoader('show', trans('label.lbl_loading'));
        var id              = report_id.split('_')[1];
        var postData        = { 'datatype': 'json', 'report_id': id, 'status': 'd' };
        var url             = SITE_URL + '/reports/delete';
        var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) 
        {
            var result = JSON.parse(data);
            if (result.is_error) 
            {
                showResponse(data, '','msg_div');
                emLoader('hide');
            }
            else 
            {
                emLoader('hide');
                window.scrollTo(0, 0);
                showResponse(data,'grid_data', 'msg_div' );
                window.setTimeout(function(){location.reload()},1500);
                //reportsList();
            }
        });
    }
}

//This is used to call the data of locations, dc, bv, cost center and vendor.
function getBvLocData(elemkey, value, selected_value = '')
{  
  emLoader('show', trans('label.lbl_loading'));
  var url      = SITE_URL + '/getreportformdata';
  var field = value.split(".").pop();
  var postData = {'field': field ,'selected_value':selected_value};
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
//This function is used to change the criteria value to selectbox or texbox based on condition.
function getColumnId(value, elemkey, selected_value = '')
{
  var columnIds = new Array('location','loc','business_vertical','bv','datacenter','dc','vendor','contract_status','renewed','contracttype','sw_category','sw_type','sw_manufacturer','pr_priority','cost_center','po_status','status','asset_status','citype','ciname','po_name');
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
    $("#criteria_value-"+elemkey).val("");
    $("#criteria_value-"+elemkey).next("div.tags-container").find('div.tag').remove();
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
    var prevId       = Number(id);
    var taginp       =  $("#criteria_value-"+prevId).next("div.tags-container").find('input.tag-input:visible').val();
    var criteria_val = $("#criteria_value-"+prevId).val();

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