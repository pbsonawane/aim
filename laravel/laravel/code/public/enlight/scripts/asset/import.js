var postData = null;
$(document).ready(function () {
  initsingleselect();
  fileimport();
  $(document).on("click", ".addmore", function () { var id = $(this).attr('id'); addMore(id); });
  $(document).on("click", ".remove", function () { var id = $(this).attr('id'); removeRow(id); });
  $(document).on("click", "#assetadd", function () { addAsset(); });
  $(document).on("click", "#resetasset", function () { addAsset(); });
  $(document).on("click", "#assetsave", function () {saveasset(); });
  $(document).on("click", "#importsave", function () {importsave(); });
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
  $(document).on("click", "#callrelationship", function () {assetrelationship(); });
  $(document).on("click", ".freeasset",  function () {  var id = $(this).attr('id'); assetfree(id); });  
  $(document).on("click", ".change_status",  function () {  var status = $(this).attr('id'); changeStatus(status);});
  $(document).on("click", "#stat_change", function () { statuschangesubmit(); });
//  $(document).on("click", "#import", function () { importasset(); });
  $(document).on("click", "#callassetcontract", function () { assetcontract(); });


$(document).on("click", "#callsoftware", function () {assetsoftwaredash(); });
  $(document).on("click", ".allocate_deallocate", function () {  var software_id = $(this).attr('id'); 
    swdeallocateuninstall(software_id); });
	
	
	$(window).on("scroll",function(e){
		$(".popover").hide();
	});
});





function onchangeci()
{

  var val = $('#ci_templ_id option:selected').attr('rel');
  var txt = $('#ci_templ_id option:selected').attr('txt');
  //alert(txt);
  $('#ci_type_id').val(val);
  $('#cititle').val(txt);
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

function fileimport()
{
  $("#frmimport").on('submit',function(e){
    if($('#file').length > 0 && typeof(($('#file')[0]).files[0]) != "undefined" && ($('#file')[0]).files[0].size > 2000000){
      var msg = trans('messages.msg_max_allowed_size',{'name':'2 MB'});
      showResponse('{"callfor":"asset_import","content":"","is_error":true,"msg":{"file":["'+msg+'"]}}', 'frmimport_alert','frmimport_alert');
      e.preventDefault();
      return false;
    }
    else{
      emLoader('show', trans('label.lbl_loading'));

      var options={
        url     : $(this).attr("action"),
        success : onsuccess,
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      }; 
      $(this).ajaxSubmit(options);
        return false;
    }
  });
}

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
         $("#importfrm #title").parent('div').parent('div.form-group').addClass('required');
      }
}



function importsave()
{
    clearMsg('msg_popup');
    clearMsg('msg_popup1');
    closeMsgAuto('msg_div');
    emLoader('show', 'Loding');
    var url = SITE_URL + '/importsave';
    var postData = $("#importfrm").serialize();
    var importsave = ajaxCall(importsave, url, postData, function (data) {
    var result = JSON.parse(data);
        if (result.is_error) {
            showResponse(data, '', 'msg_popup');
            emLoader('hide');
        }
        else {
            emLoader('hide');
            frmimport.reset();
           $(".chosen-select").val('').trigger("chosen:updated");
            //location.reload(true);
            //$('#importdata').html('');

            showResponse(data, 'importdata', 'msg_popup');

             closeMsgAuto('msg_popup');
            //lightbox('hide');
            //showResponse(data, 'assetdata', 'msg_div');
            //assetslist();
        }
    });
}



