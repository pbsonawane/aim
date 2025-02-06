var postData = null;
$(document).ready(function () {


  var ass_id = $('#url_asset_id').val();
  var templ_id = $('#url_ci_templ_id').val();
  var type_id = $('#url_ci_type_id').val();
  var tit = $('#url_title').val();
  var po_id = $('#url_po_id').val();
  var prefix = "";

  ciTemplate();
  /*if((ass_id == "" || ass_id == "0") && po_id == "")
  {
    alert('a');
    dashboard();
  }
  else if(ass_id != "" && ass_id != "0")
  {
    alert('b');
    assetdashboardnew(ass_id,templ_id,type_id,tit);
  }
  else if(po_id != "")
  {
    alert('c');
    assets(tit,type_id,templ_id,prefix,po_id);
  }*/

  if((ass_id == "") && po_id == "")
  {

    dashboard();
  }else if(ass_id != "" && ass_id != "0")
  {
    assetdashboardnew(ass_id,templ_id,type_id,tit);
  }else{

    assets(tit,type_id,templ_id,prefix,po_id);
  }

    /*if((ass_id == "" || ass_id == "0") && po_id == "")
    dashboard();
  else if(ass_id != "" && ass_id != "0")
    assetdashboardnew(ass_id,templ_id,type_id,tit);
  else if(po_id != "")
    assets(tit,type_id,templ_id,prefix,po_id);*/

  $(document).on("click", ".addmore", function () { var id = $(this).attr('id'); addMore(id); });
  $(document).on("click", ".remove", function () { var id = $(this).attr('id'); removeRow(id); });
  $(document).on("click", "#assetadd", function () { addAsset(); });
  $(document).on("click", "#resetasset", function () { addAsset(); });
  $(document).on("click", "#assetsave", function () {saveasset(); });
//  $(document).on("click", "#importsave", function () {importsave(); });
$(document).on("click", "#assetupdate", function () {updateasset(); });
$(document).on("click", ".faclick",  function () {  var id = $(this).attr('id'); changetab(id); });
$(document).on("click", ".asset_ed",  function () {  var id = $(this).attr('id'); editAsset(id); });
$(document).on("click", "#editreset",  function () {  var id = $('#asset_id').val(); editAsset(id); });
$(document).on("click", ".asset_de",  function () {  var id = $(this).attr('id'); deleteAsset(id); });
$(document).on("click", ".assetdash",  function () {  var id = $(this).attr('id'); assetdashboard(id); });
$(document).on("click", "#goback",  function () {  assetdashboardlist(); });
$(document).on("click", "#godashbord",  function () {  dashboard(); });
$(document).on("click", ".assetrelationship_del",  function () {  var id = $(this).attr('id'); var childasset = $(this).attr('child_asset'); var rel_type = $(this).attr('rel_type');  deleteAssetrelationship(id,childasset,rel_type); });
$(document).on("click", ".addrelationship",  function () {  var id = $(this).attr('id'); addAssetrelationship(id); });
$(document).on("click", "#assetrelationshipaddsubmit",  function () {  var id = $(this).attr('id'); saveassetrelationship(); });
$(document).on("click", "#assetrelationship_reset",  function () { resetForm('addformassetrelationship'); });
$(document).on("click", ".asset_attach",  function () { attachAsset()});
$(document).on("click", "#hideoption",  function () { optionhide()});
$(document).on("click", "#showoption",  function () { optionshow()});
$(document).on("click", "#attchasset", function () {attachassetsave(); });
$(document).on("click", "#callhistory", function () {assethistory(); });
$(document).on("click", "#callassethistory", function () {assignassethistory(); });
$(document).on("click", "#callrelationship", function () {assetrelationship(); });
$(document).on("click", ".freeasset",  function () {  var id = $(this).attr('id'); assetfree(id); });  
$(document).on("click", ".change_status",  function () {  var status = $(this).attr('id'); changeStatus(status);});
$(document).on("click", "#stat_change", function () { statuschangesubmit(); });
//  $(document).on("click", "#import", function () { importasset(); });
$(document).on("click", "#callassetcontract", function () { assetcontract(); });


$(document).on("click", "#callsoftware", function () {assetsoftwaredash(); });
$(document).on("click", ".allocate_deallocate", function () {  var software_id = $(this).attr('id'); 
  swdeallocateuninstall(software_id); });
});

function ciTemplate() {

  closeMsgAuto('msg_div');
  emLoader('show', 'Loading CI Template');
  var url = SITE_URL + '/assettree';
  var citemp = ajaxCall(citemp, url, postData, function (data) {
    showResponse(data, 'comtree', 'msg_div');
    inittree('treeshow',data,'treefun');
       //emLoader('hide');
     });

}

function assetdashboardlist()
{
  var title = $('#title').val(); 
  var ci_type_id = $('#ci_type_id').val(); 
  var ci_templ_id = $('#ci_templ_id').val(); 
  var  prefix = "";
  var po_id = "";
  assets(title,ci_type_id,ci_templ_id,prefix,po_id);
}

function treefun(data)
{
  if(data.node.data.type == 'item')
  {
    var ci_templ_id = data.node.data.ci_templ_id;
    var ci_type_id = data.node.data.ci_type_id;
    var prefix = data.node.data.prifix;
    var title = data.node.title;
    assets(title,ci_type_id,ci_templ_id,prefix);
  }
}

function dashboard()
{
  closeMsgAuto('msg_div');
  emLoader('show', 'Loading Dashboard');
  var url = SITE_URL + '/assetdashboardparent';
  var userajax = ajaxCall(userajax, url, postData, function (data) {
    showResponse(data, 'datatree', 'msg_div');
    emLoader('hide');
  });
}

function assets(title,ci_type_id,ci_templ_id,prefix,po_id)
{
  closeMsgAuto('msg_div');
  var url = SITE_URL + '/asset';
  var postData = {'ci_templ_id' : ci_templ_id,'title': title,'ci_type_id' : ci_type_id, 'po_id':po_id}
  var userajax = ajaxCall(userajax, url, postData, function (data) {
    showResponse(data, 'datatree', 'msg_div');
    assetslist();
        //emLoader('hide');
      });
}

function assetslist()
{
  closeMsgAuto('msg_div');
  emLoader('show', 'Loading');
  var asset_sku = "";
  asset_sku = $('#url_asset_sku').val();
  var po_id    = '';
  if($('#url_po_id').length > 0) po_id = $('#url_po_id').val();

  var url      = SITE_URL + '/asset/list';
  var postData = $("#assetfrm").serialize();
  postData     = postData + '&po_id='+po_id + '&asset_sku='+asset_sku;

  var userajax = ajaxCall(userajax, url, postData, function (data) {
    showResponse(data, 'assetdata', 'msg_div');
    emLoader('hide');
  });
  
}
function assetTracking()
{
  closeMsgAuto('msg_div');
  emLoader('show', 'Loading');

  var url      = SITE_URL + '/assets/assetTracking';
  var postData = $("#assettrackingfrm").serialize();
  
  var userajax = ajaxCall(userajax, url, postData, function (data) {
        //showResponse(data, 'assetdata', 'msg_div');
        //emLoader('hide');
        let jsonarr = JSON.parse(data);
        if(jsonarr.tracking ===1){
          window.location.href =SITE_URL+'/assets/'+jsonarr.content[0].asset_id+'/'+jsonarr.content[0].ci_templ_id;

        }else if(jsonarr.tracking === 2){
          emLoader('hide');
          showResponse(data, 'datatree', 'msg_div');

        }else{

          emLoader('hide');
          //var data = {content:[],http_code:200,is_error:true,msg:"record not found"};
          showResponse(data, 'assetdata', 'msg_div');

        }
      });  
}

function addAsset()
{
  emLoader('show', 'Add Asset');
  var ci_templ_id = $('#ci_templ_id').val();
  var ci_type_id = $('#ci_type_id').val();
  var title = $('#title').val();
  var postData = { 'ci_templ_id': ci_templ_id , 'ci_type_id': ci_type_id};
  var url = SITE_URL + '/asset/add';
  var passchangeajax = ajaxCall(passchangeajax, url, postData, function (data) {
    lightbox('show', data, 'Add New '+title, 'maxlarge');
    initsingleselect();
    $('#faminus').hide();
    $('#assetdtdiv').hide();
    emLoader('hide');

  });
}

function saveasset()
{
  clearMsg('msg_popup');
  closeMsgAuto('msg_div');
  emLoader('show', 'Asset saving');
  var url = SITE_URL + '/asset/save';
  var postData = $("#saveassetfrm").serialize();
  var saveasset = ajaxCall(saveasset, url, postData, function (data) {
    var result = JSON.parse(data);
    if (result.is_error) {
      showResponse(data, '', 'msg_popup');
      emLoader('hide');
    }
    else {
      emLoader('hide');
      lightbox('hide');
      showResponse(data, 'assetdata', 'msg_div');
      assetslist();
    }
  });
}

function editAsset(asset_id)
{
  closeMsgAuto('msg_div');
  emLoader('show', 'Edit Asset');

  var ci_templ_id = $('#ci_templ_id').val();
  var ci_type_id = $('#ci_type_id').val();
  var title = $('#title').val();
  var postData = { 'ci_templ_id': ci_templ_id , 'ci_type_id': ci_type_id,'asset_id': asset_id};
  var url = SITE_URL + '/asset/edit';
  var passchangeajax = ajaxCall(passchangeajax, url, postData, function (data) {
    lightbox('show', data, 'Edit '+title, 'maxlarge');
    initsingleselect();
    $('#faminus').hide();
    $('#assetdtdiv').hide();
    emLoader('hide');

  });
}

function deleteAsset(asset_id)
{
  if(confirm(trans('messages.msg_asset_delete')))
  {
   closeMsgAuto('msg_div');
   emLoader('show', 'Asset saving');
   var url = SITE_URL + '/asset/delete';
   var postData = {'asset_id':asset_id}
   var saveasset = ajaxCall(saveasset, url, postData, function (data) {
    var result = JSON.parse(data);
    if (result.is_error) {
      showResponse(data, '', 'msg_popup');
      emLoader('hide');
    }
    else {
      emLoader('hide');
      lightbox('hide');
      assetdashboardlist();
      showResponse(data, 'assetdata', 'msg_div');
    }
  });
 } 
}

function updateasset()
{
  clearMsg('msg_popup');
  emLoader('show', 'Asset saving');
  var url = SITE_URL + '/asset/update';
  var postData = $("#saveassetfrm").serialize();
  var saveasset = ajaxCall(saveasset, url, postData, function (data) {
    var result = JSON.parse(data);
    if (result.is_error) {
      showResponse(data, '', 'msg_popup');
      emLoader('hide');
    }
    else {
      emLoader('hide');
      lightbox('hide');
      var asset_id = $('#asset_id').val();
      assetdashboard(asset_id);
      showResponse(data, 'assetdata', 'msg_div');
    }
  });
}

function changetab(id)
{
  $('#'+id).hide();
  if(id == 'faplus')
  {
    $('#assetdtdiv').show();
    $('#faminus').show();
  } 
  else
  {
    $('#assetdtdiv').hide();
    $('#faplus').show();
  }

}

function deletedasst(id)
{
  closeMsgAuto('msg_div');
  if(id != '')
  {
    var val = $('#deletedasset').val();
    val1 = val + '##' + id;
    $('#deletedasset').val(val1);
  }
}

function assetdashboard(id)
{
 var ci_type_id = $('#ci_type_id').val();
 var ci_templ_id = $('#ci_templ_id').val();
 var title = $('#title').val();   
 closeMsgAuto('msg_div');
 emLoader('show', 'Loading Asset');
 var url = SITE_URL + '/assetdashboard';
 var postData = { 'ci_templ_id': ci_templ_id , 'ci_type_id': ci_type_id,'asset_id': id, 'title':title, 'given':"info"};
 var asetdash = ajaxCall(asetdash, url, postData, function (data) {
  showResponse(data, 'datatree', 'msg_div');
  emLoader('hide');
});

}
function assetTrackingShow(data)
{

 closeMsgAuto('msg_div');
 emLoader('show', 'Loading Asset');
 var url = SITE_URL + '/assettrackingshow';
 var postData = { 'data': data };
 var asetdash = ajaxCall(asetdash, url, postData, function (data) {
  showResponse(data, 'datatree', 'msg_div');
  emLoader('hide');
});

}

function assetdashboardnew(id,ci_templ_id,ci_type_id,title)
{
  closeMsgAuto('msg_div');
  var url = SITE_URL + '/assetdashboard';
  var postData = { 'ci_templ_id': ci_templ_id , 'ci_type_id': ci_type_id,'asset_id': id, 'title':title, 'given':"info"};
  var asetdash = ajaxCall(asetdash, url, postData, function (data) {
    showResponse(data, 'datatree', 'msg_div');
    emLoader('hide');
  });
  
}

function deleteAssetrelationship(asset_relationship_id='',child_asset='',rel_type='') {
  if (confirm(trans('label.msg_confirm'))) {
    clearMsg('msg_popup');
    emLoader('show', trans('label.lbl_loading'));

    var id              = '';
    var parent_asset    = $('#tag').val();
    var asset_id        = $('#asset_id').val();

    if(asset_relationship_id != '' && asset_relationship_id.indexOf('_') != -1) id = asset_relationship_id.split('_')[1];

    var postData        = { 'datatype': 'json', 'asset_relationship_id': id, 'asset_id':asset_id, 'status': 'd', 'child_asset_name':child_asset, 'parent_asset_name':parent_asset, 'rel_type':rel_type};

    var url             = SITE_URL + '/assetrelationship/delete';
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
      var result = JSON.parse(data);
      if (result.is_error) {
        showResponse(data, '','msg_div');
        emLoader('hide');
      }
      else {
        emLoader('hide');
        assetdashboard(asset_id);
        showResponse(data,'assetdata', 'msg_div' );
      }
    });
  }
}

function addAssetrelationship() {
  closeMsgBox('msg_div');
  emLoader('show', trans('label.lbl_loading'));
  var id = $('#asset_id').val();
  if(id != '' && id != undefined){
    var url        = SITE_URL + '/assetrelationship/add';
    var postData   = { 'asset_id': id};
    var notifyajax = ajaxCall(notifyajax, url, postData, function (data) {
      lightbox('show', data, trans('label.lbl_addrelationship'), 'large');
      initsingleselect();
      emLoader('hide');
    });
  }
}

function saveassetrelationship(){
  clearMsg('msg_popup');
  closeMsgAuto('msg_div');
  emLoader('show', trans('label.lbl_loading'));
  var url           = SITE_URL + '/assetrelationship/save';
  var postData      = $("#addformassetrelationship").serialize();
  
  var relationship_type_name = $('select[name=relationship_type_id]').find(":selected").text();
  var child_asset_name       = $('select[name=child_asset_id]').find(":selected").text();
  var parent_asset_name      = $('#tag').val();

  postData                   = postData + '&relationship_type_name=' + relationship_type_name + '&child_asset_name=' + child_asset_name + '&parent_asset_name=' +parent_asset_name;

  var saveasset_rel = ajaxCall(saveasset_rel, url, postData, function (data) {
    var result    = JSON.parse(data);
    if (result.is_error) {
      showResponse(data, '', 'msg_popup');
      emLoader('hide');
    }
    else {
      emLoader('hide');
      lightbox('hide');
      var asset_id = $('#asset_id').val();
      assetdashboard(asset_id);
      showResponse(data,'assetdata', 'msg_div' );
    }
  });
}

function attachAsset()
{
 closeMsgAuto('msg_div');
 emLoader('show', 'Edit Asset');
 var bv_id = $('#bv_id').val();
 var location_id = $('#location_id').val();
 var ci_templ_id = $('#ci_templ_id').val();
 var asset_id = $('#asset_id').val();
 var tag = $('#tag').val();
 var postData = { 'asset_id': asset_id , 'location_id': location_id,'bv_id': bv_id,"tag":tag,"asset_ci_templ_id":ci_templ_id};
 var url = SITE_URL + '/assetattach';
 var passchangeajax = ajaxCall(passchangeajax, url, postData, function (data) {
  lightbox('show', data, 'Attach Asset with '+tag, 'maxlarge');
  initsingleselect();
  emLoader('hide');
});
}

function assetSelect(id,asset_status)
{
  if(id != "")  
  {

    clearMsg('msg_popup');
    emLoader('show', 'Loding');
    var bv_id = $("#bv_id").val();
    var cuasset_id = $("#asset_id").val();
    var location_id = $("#location_id").val();
    var url = SITE_URL + '/assetwithstatus';
    var postData = {"ci_templ_id":id,"asset_status":asset_status, "bv_id":bv_id,"location_id":location_id}
    var asserselect = ajaxCall(asserselect, url, postData, function (data) {
     var str = "";
     if(data.length > 0)
     {
       var cl = 0;
       for(var i = 0; i<data.length; i++)
       {
        var dt = data[i];
        if(cuasset_id != dt.asset_id)
        {
          cl++;
          if(dt.display_name == '')
            str = str + '<option value="'+dt.asset_id+'">'+dt.asset_tag+'</option>';
          else
            str = str + '<option value="'+dt.asset_id+'">'+dt.asset_tag+'('+dt.display_name+')</option>';
        }

      }
      if(cl == 0)
      {
       str = '<option value="">'+trans('label.no_records')+'</option>';
     }
   }
   else
   {
     str = '<option value="">'+trans('label.no_records')+'</option>';
   }
   if(str != '')
   {
    $('#multiassetids').html(str);
    $('#selectassetids').html("");
          //$("#selectassetids").children('option').hide();
        }

        
      });
  }
  else
  {
   var str = '<option value="">'+trans('label.no_records')+'</option>';
   $('#multiassetids').html(str);
   //  $('#selectassetids').html(str);
 }

 emLoader('hide');
}

function optionhide()
{
  $('#multiassetids :selected').each(function(){
    $("#multiassetids option[value=" + $(this).val() + "]").remove();
    $("#selectassetids").append("<option value='" + $(this).val() + "'>"+ $(this).text()+"</option>");
  });
}
function optionshow()
{
  $('#selectassetids :selected').each(function(){
    $("#selectassetids option[value=" + $(this).val() + "]").remove();
    $("#multiassetids").append("<option value='" + $(this).val() + "'>"+ $(this).text()+"</option>");
  });
}

function attachassetsave()
{
  clearMsg('msg_popup');
  emLoader('show', 'Asset saving');

  $('#selectassetids option').each(function () {
    if ($(this).css('display') == 'block') {
            //$(this).prop("selected", true);
            $(this).attr('selected', true);   
          }

        });

  var url = SITE_URL + '/assetattach/save';
  var postData = $("#attach").serialize();
  var attachsaveasset = ajaxCall(attachsaveasset, url, postData, function (data) {
    var result = JSON.parse(data);
    if (result.is_error) {
      showResponse(data, '', 'msg_popup');
      emLoader('hide');
    }
    else {
      emLoader('hide');
      lightbox('hide');
      var asset_id = $('#asset_id').val();
      assetdashboard(asset_id);
      showResponse(data, 'assetdata', 'msg_div');
    }
  });
}

function assetsofcitype(event){
  //if(id != ""){
    id = event.selectedOptions[0].getAttribute('data-id');
   // alert(event.selectedOptions[0].getAttribute('data-id'));

   clearMsg('msg_popup');
   emLoader('show', 'Asset saving');
   var url = SITE_URL + '/assetsofcitype';
   var postData = {"ci_templ_id":id}
   var asserselect = ajaxCall(asserselect, url, postData, function (data) {
    emLoader('hide');
    $('#child_asset_id').html(data);
    $('.chosen-select').chosen();

  });


 }

 function assetfree(id)
 {
  if(confirm(trans('messages.msg_free_asset')))
  {
    var asset_id = $('#asset_id').val();  
    var url = SITE_URL + '/assetattach/delete';
    var postData = {"parent_asset_id":asset_id, "asset_id":id}
    var assetfree = ajaxCall(assetfree, url, postData, function (data) {
      var result = JSON.parse(data);
      if (result.is_error) {
        showResponse(data, '', 'msg_popup');
        emLoader('hide');
      }
      else {
        emLoader('hide');
        lightbox('hide');
        assetdashboard(asset_id);
        showResponse(data, 'assetdata', 'msg_div');
      }
    });
  }
}

function assethistory()
{
  emLoader('show', 'Loding');
  var asset_id = $('#asset_id').val();  
  var url = SITE_URL + '/assethistory';
  var postData = {"asset_id":asset_id}
  var assetfree = ajaxCall(assetfree, url, postData, function (data) {
   $('#history').html(data);
   emLoader('hide');
 });
}
function assignassethistory()
{
  emLoader('show', 'Loding');
  var asset_id = $('#asset_id').val();  
  var url = SITE_URL + '/assignassethistory';
  var postData = {"asset_id":asset_id}
  var assetfree = ajaxCall(assetfree, url, postData, function (data) {
   $('#assignedhistory').html(data);
   emLoader('hide');
 });
}

//fucntion to get the asset contract 
function assetcontract()
{
  emLoader('show', 'Loding');
  var asset_id = $('#asset_id').val();  
  var url = SITE_URL + '/assetcontract';
  var postData = {"asset_id":asset_id}
  var assetfree = ajaxCall(assetfree, url, postData, function (data) {
   $('#contract').html(data);
   emLoader('hide');
 });
}


function assetrelationship()
{
 emLoader('show', 'Loding');
 var asset_id = $('#asset_id').val();  
 var url = SITE_URL + '/assetrelationship';
 var postData = {"asset_id":asset_id}
 var assetfree = ajaxCall(assetfree, url, postData, function (data) {
   $('#relationship').html(data);
   emLoader('hide');
 });
}

function changeStatus(status)
{
  
  var asset_id = $('#asset_id').val();
  var location_id = $('#location_id').val();
  var bv_id = $('#bv_id').val();
  var parent_asset_id = $('#parent_asset_id').val();
  var department_id = $('#department_id').val();
  var requestername_id = $('#requestername_id').val();
  var instock_asset_prid = "";
  var instock_asset_pr_department_id = "";
  var instock_asset_pr_requester_id = "";
  if (localStorage.getItem("instock_asset_prid") != null) {
    instock_asset_prid = localStorage.getItem("instock_asset_prid");
  }
  if (localStorage.getItem("instock_asset_pr_department_id") != null) {
    instock_asset_pr_department_id = localStorage.getItem("instock_asset_pr_department_id");
  }
  if (localStorage.getItem("instock_asset_pr_requester_id") != null) {
    instock_asset_pr_requester_id = localStorage.getItem("instock_asset_pr_requester_id");
  }

  if(status != "" && asset_id != "")
  {
    closeMsgAuto('msg_div');
    emLoader('show', 'Edit Asset');
    var postData = { 'status': status , 'asset_id': asset_id, 'location_id':location_id, 'bv_id': bv_id,'parent_asset_id':parent_asset_id,'department_id':department_id,'requestername_id':requestername_id
    ,'instock_asset_prid':instock_asset_prid
    ,'instock_asset_pr_department_id':instock_asset_pr_department_id
    ,'instock_asset_pr_requester_id':instock_asset_pr_requester_id};
    var url = SITE_URL + '/statuschange';
    var passchangeajax = ajaxCall(passchangeajax, url, postData, function (data) {
      lightbox('show', data, trans('label.lbl_change_status'), 'large');
      initsingleselect();

      emLoader('hide');
      var pre_requestername_id = $('#requestername_id').val();
      if(instock_asset_pr_department_id!='' && instock_asset_pr_requester_id!='')
      {
        getrequesters(instock_asset_pr_department_id,instock_asset_pr_requester_id);
      }else{
        getrequesters($('#department_id').val(),pre_requestername_id);
      }     
    });
  }
  else
  {
    alert("Asset ID not Available.");
  }
}

function statchange(status)
{
 // alert(status);
 if(status == 'in_store')
 {
  $('#bvlocinfo').show();
  $('#assetinfo').hide();
  $('#requesters').hide();
}
else
{
  if(status =='in_use')
  {
      // alert(status);
      $('#requesters').show();
      $('#assetinfo').show();
    }
    else
      $('#assetinfo').hide();
    $('#requesters').hide();
    $('#bvlocinfo').hide();
  }

}


function statuschangesubmit()
{  
  clearMsg('msg_popup');
  emLoader('show', 'Loding');
  var url = SITE_URL + '/statuschangesubmit';
  var postData = $("#changestatus").serialize();
  
  var statuschangesubmit = ajaxCall(statuschangesubmit, url, postData, function (data) {
      //alert(data);
      
      var result = JSON.parse(data);  
      
      if (result.is_error) {
        showResponse(data, '', 'msg_popup');
        emLoader('hide');
      }
      else 
      {        
      // Clear Local Storage
      localStorage.removeItem("instock_asset_prid");
      localStorage.removeItem("instock_asset_pr_department_id");
      localStorage.removeItem("instock_asset_pr_requester_id");
      localStorage.removeItem("instock_asset_pr_item_product");
      // Clear Local Storage
        emLoader('hide');
        lightbox('hide');
        var asset_id = $('#asset_id').val();
        assetdashboard(asset_id);
        showResponse(data, 'assetdata', 'msg_div');
      }
    });

}
function getrequesters(dept_id,pre_requestername_id='')
{
  clearMsg('msg_popup');
  emLoader('show', 'Loding');
  var url = SITE_URL + '/getrequesters';
  var postData = {dept_id:dept_id,pre_requestername_id:pre_requestername_id};
  var getdept = ajaxCall(getdept, url, postData, function (data) {
      //alert(data);
      var result = JSON.parse(data);

      if (result.is_error) {
        showResponse(data, '', 'msg_popup');
        emLoader('hide');
      }
      else 
      {
        $('#requesters_id').html(result.html);
        emLoader('hide');
        
      }
    });

}

//function importasset()
//{
//    emLoader('show', 'Add Asset');
//    var ci_templ_id = $('#ci_templ_id').val();
//    var ci_type_id = $('#ci_type_id').val();
//    var title = $('#title').val();
//    var postData = { 'ci_templ_id': ci_templ_id , 'ci_type_id': ci_type_id};
//    var url = SITE_URL + '/asset_import';
//    var passchangeajax = ajaxCall(passchangeajax, url, postData, function (data) {
//        lightbox('show', data, trans('label.lbl_import')+' '+ title, 'maxlarge');
//        emLoader('hide');
//        //alert();
//        fileimport();
//    });   
//
//}



//function fileimport()
//{ 
//   // $(".progress").hide();
//   // $("#logdata").hide();
//  $("#frmimport").on('submit',function(){
//    emLoader('show', 'Loding');
//      // $("#respmsg").html("");
//      // $(".progress").show();
//      // $("#logdata").show(); 
//      // $("#sub_btn").hide();
//     //$("#importmsg").show();
//
//  var options={
//   url     : $(this).attr("action"),
//   success : onsuccess,
//    headers: {
//    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//  }
//  }; 
//  $(this).ajaxSubmit(options);
//  return false;
// });
//}
function onsuccess(response,status)
{
  var result = JSON.parse(response);
  $('#frmimport_alert').html('');
  if (result.is_error) {
    if(result.hasOwnProperty('callfor') && result.callfor == 'asset_import'){
      showResponse(response, 'frmimport_alert', 'frmimport_alert');
      $('#frmimport_alert .alert-danger').show();
    }
    else showResponse(response, '', 'msg_popup');
    emLoader('hide');
  }
  else 
  {
   emLoader('hide');
   showResponse(response, 'importdata', 'msg_div');
   initsingleselect();
 }
}

function assetsoftwaredash()
{
  emLoader('show', 'Loding');
  var asset_id = $('#asset_id').val();  
  var url = SITE_URL + '/swonassetdashboard';
  var postData = {"asset_id":asset_id}
  var assetfree = ajaxCall(assetfree, url, postData, function (data) {
   $('#software').html(data);
   emLoader('hide');
 });
}

function swdeallocateuninstall(software_id) {
  if (confirm(trans('messages.msg_sw_uninstall'))) {
    clearMsg('msg_popup');
    emLoader('show', trans('messages.msg_deleting_swasset'));
    var id = software_id.split('_')[1];
    var assetid = software_id.split('_')[2];
    var postData = { 'datatype': 'json', 'software_id': id, 'asset_id':assetid};
    var url = SITE_URL + '/swdeallocateuninstall';
    var rdpconnectsajax = ajaxCall(rdpconnectsajax, url, postData, function (data) {
      var result = JSON.parse(data);
      if (result.is_error) {
        showResponse(data, '','msg_div');
        emLoader('hide');
      }
      else {
        emLoader('hide');
        showResponse(data,'software', 'msg_div' );


      }
    });
  }
}

//function importsave()
//{
//    clearMsg('msg_popup');
//    clearMsg('msg_popup1');
//    closeMsgAuto('msg_div');
//    emLoader('show', 'Loding');
//    var url = SITE_URL + '/importsave';
//    var postData = $("#importfrm").serialize();
//    var importsave = ajaxCall(importsave, url, postData, function (data) {
//    var result = JSON.parse(data);
//        if (result.is_error) {
//            showResponse(data, '', 'msg_popup');
//            emLoader('hide');
//        }
//        else {
//            emLoader('hide');
//            showResponse(data, 'importdata', 'msg_popup');
//            //lightbox('hide');
//            //showResponse(data, 'assetdata', 'msg_div');
//            //assetslist();
//        }
//    });
//}



