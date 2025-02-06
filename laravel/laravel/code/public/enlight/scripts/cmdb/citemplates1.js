var postData = null;
$(document).ready(function () {
  
  ciTemplate();
  if(add_comp_permission == 1) addComponent('','','');
  $(document).on("click", "#addmore", function () {addMore(); });
  $(document).on("click", ".remove", function () {removeRow(); });
  $(document).on("click", "#addcomp", function () {savecomp(); });
  $(document).on("click", "#uptcomp", function () {updatecomp(); });
  $(document).on("click", "#componentdelete", function () {deletecomp(); });
  $(document).on("click", "#attradd", function () {addattributes(); });
  $(document).on("click", "#attrupdate", function () {updateattribute(); });
  $(document).on("click", "#delci", function () {deleteci(); });
  $(document).on("click", "#resetbtn", function () {addComponent('','',''); });
});

function ciTemplate() {
    
    closeMsgAuto('msg_div');
    emLoader('show', trans('label.lbl_loading'));
    var url = SITE_URL + '/citemplates/list';
   // var postData = $("#frmusers").serialize();
    var userajax = ajaxCall(userajax, url, postData, function (data) {
     
        showResponse(data, 'comtree', 'msg_div');
        inittree('treeshow',data,'treefun');
        emLoader('hide');
         $("#godashbord").hide();
    });
}

function addComponent(ci_type_id,citype,treetype)
{

  clearMsg('msg_popup');
   closeMsgAuto('msg_div');
    emLoader('show', trans('label.lbl_loading'));
    var url = SITE_URL + '/citemplates/add';
    var postData = {'ci_type_id': ci_type_id, 'citype': citype, 'treetype': treetype};
    var userajax = ajaxCall(userajax, url, postData, function (data) {
      
        showResponse(data, 'datatree', 'msg_div');
       // intitree(data);
        emLoader('hide');
    });
}

function editComponent(ci_templ_id,ci_name,ci_sku,ci_type_id,citype,treetype,status,type)
{
  clearMsg('msg_popup');
   closeMsgAuto('msg_div');
    emLoader('show', trans('label.lbl_loading'));
    var url = SITE_URL + '/citemplates/edit';
    var postData = {'ci_type_id': ci_type_id,'ci_sku':ci_sku ,'ci_name': ci_name, 'ci_templ_id': ci_templ_id, 'citype':citype, 'treetype': treetype, 'status':status, 'type': type};
    var userajax = ajaxCall(userajax, url, postData, function (data) {
        showResponse(data, 'datatree', 'msg_div');
       // ciTemplate();
        emLoader('hide');
    });
}

function savecomp()
{
    
    cuvalidations = [];
    
    clearMsg('msg_popup');
    closeMsgAuto('msg_div');
    emLoader('show', trans('label.lbl_loading'));
    var url = SITE_URL + '/citemplates/save';
    var i = 0;
    $(".selmulti").each(function() {
        var c = $(this).val();

       cuvalidations.push(c);
      
    });
  
  
    
    var xx = '';
    if(cuvalidations.length > 0) 
    { 
      for(vari=0;i<cuvalidations.length;i++)
      { 
        if(cuvalidations[i])
        {
          if(xx == '') 
            xx = cuvalidations[i].toString(); 
          else 
            xx = xx +'*'+ cuvalidations[i].toString(); 
        }
        
      }
    }

   var postData = $("#tempfrm").serialize() + "&valarray="+xx;
  
    var savecmp = ajaxCall(savecmp, url, postData, function (data) {
        showResponse(data, '', 'msg_popup');
        var response = JSON.parse(data);
        if(!response.is_error)
        {  
          ciTemplate();
          closeMsgAuto('msg_popup');
          $("#addmore").hide();
        }  
        emLoader('hide');
    });
}

function updatecomp()
{
    clearMsg('msg_popup');
    clearMsg('msg_popup1');
    closeMsgAuto('msg_div');
    emLoader('show', trans('label.lbl_loading'));

    var url       = SITE_URL + '/citemplates/update';
    var postData  = $("#compfrm").serialize();
    var is_delete = false;
    if($("#act").length > 0 && $("#act").val() == "del") is_delete = true;
   
   
    var savecmp = ajaxCall(savecmp, url, postData, function (data) {
        if(is_delete == true){
          var result = JSON.parse(data);
          if(!result.is_error){
            result.msg = trans('messages.118',{'name':'CI'});
            data = JSON.stringify(result);
          }
        }

        showResponse(data, '', 'msg_popup1');
        var response = JSON.parse(data);
        if(!response.is_error)
        {  

          ciTemplate();
          if(is_delete == true) addComponent('','','');
          closeMsgAuto('msg_popup1');
        }
       
        emLoader('hide');
    });
}

function addattributes()
{
  var url = SITE_URL + '/addattributes';
  var ci_type_id = $('#ci_type_id').val();
  var ci_id = $('#ci_id').val();
  var postData = {'ci_type_id': ci_type_id, 'ci_id': ci_id};
  var addatt = ajaxCall(addatt, url, postData, function (data) {
      showResponse(data, 'datatree', 'msg_div');
      emLoader('hide');
  });
}

function editAttribute(ci_templ_id,attr_name,ci_sku,ci_type_id,variable,treetype,status,type)
{
   emLoader('show', trans('label.lbl_loading'));
    var url = SITE_URL + '/editAttribute';
    var postData = {
                      'attr_name': attr_name, 
                      'ci_templ_id': ci_templ_id,
                      'ci_type_id': ci_type_id,
                      'skucodes':ci_sku,
                      'variable': variable,
                      'treetype': treetype,
                      'status' : status,
                      'type': type
                    };
    var addatt = ajaxCall(addatt, url, postData, function (data) {
       showResponse(data, 'datatree', 'msg_div');
       //ciTemplate();
      emLoader('hide');
    });
}


function updateattribute()
{
    clearMsg('msg_popup');
    closeMsgAuto('msg_div');
    emLoader('show', trans('label.lbl_loading'));
    var url = SITE_URL + '/updateattribute';
    var postData = $("#edatrr").serialize();
    var savecmp = ajaxCall(savecmp, url, postData, function (data) {
      showResponse(data, '', 'msg_popup');
      var response = JSON.parse(data);
      if(!response.is_error)
      {
        ciTemplate();
        closeMsgAuto('msg_popup');
      }
      else{
        $("#msg_popup").removeClass("alert-success");
      }
      emLoader('hide');
    });
}

function deleteci()
{
   if (confirm(trans('label.msg_confirm'))) 
    {
        clearMsg('msg_popup');
        closeMsgAuto('msg_div');
        var type = $('#type').val();
        var ci_id = $('#ci_id').val();
        var variable_name = $('#v_name').val();
        var url = SITE_URL + '/citemplates/delete';
        var postData = {'type': type, 'ci_id': ci_id , 'variable_name': variable_name};
        var savecmp = ajaxCall(savecmp, url, postData, function (data) {
          showResponse(data, '', 'msg_popup');
          $('#attrdata').hide();
          $('#actionbtn').hide();
          var response = JSON.parse(data);
          if(!response.is_error)
            ciTemplate();
          //ciTemplate();
          emLoader('hide');
        });
    }
    
}

function treefun(data)
{
 
   if(data.node.data.treetype == 'citype')
    {
        if(add_comp_permission == 1) addComponent(data.node.key,data.node.title,data.node.data.ci_sku,data.node.data.treetype);
    }
    else if(data.node.data.treetype == 'component')
    {
       if(edit_comp_permission == 1) editComponent(data.node.key,data.node.title,data.node.data.ci_sku,data.node.data.ci_type_id,data.node.data.citype,data.node.data.treetype,data.node.data.status,data.node.data.type);
    }
    else if(data.node.data.treetype == 'attribute')
    {
       if(edit_comp_permission == 1) editAttribute(data.node.key,data.node.title,data.node.data.skucode,data.node.data.ci_type_id,data.node.data.variable,data.node.data.treetype,data.node.data.status,data.node.data.type);
    }
}

function deletecomp()
{
    clearMsg('msg_popup');
    clearMsg('msg_popup1');
    closeMsgAuto('msg_div');

    if(confirm(trans("messages.msg_delrecordconfirm")))
    {
      $("#compfrm").append('<input type="hidden" value="del" id="act" name="act">');
      updatecomp();
      $("#act").remove();
    }
}