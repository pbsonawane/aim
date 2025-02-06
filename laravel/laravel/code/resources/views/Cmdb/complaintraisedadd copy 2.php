<link rel="stylesheet" type="text/css" href="enlight/scripts/formeo-master/css/demo.css">
<div class="row" id="credentialTypeform">
   <div class="col-md-10">
      <div class="hidden alert-dismissable" id="msg_popup">
      </div>
   </div>
   <div class="col-md-12">
      <div class="panel">
         <div class="panel-body purchaseRequestScroll">
            <div class="container">
               <form id="complaintRaisedForm" method="post" enctype="multipart/form-data">
               <div class="row">
                  <div class=" col-md-2">

                  </div>
                  <div class="form-group col-md-4">
                        <label for="inputStandard" class="col-md-12 control-label">Requester Name<span class="text-danger">*</span></label>
                        <div class="col-md-12">
                           <select class="form-control input-sm" name="pr_requester_name" id="pr_requester_name">
                           </select>
                        </div>
                  </div>
                  <div class="form-group col-md-4">
                        <label for="inputStandard" class="col-md-12 control-label">Priority<span class="text-danger">*</span></label>
                        <div class="col-md-12">
                           <select class="form-control input-sm" name="priority" id="priority">
                              <option value="">Select Priority</option>
                              <option value="High">High</option>
                              <option value="Medium">Medium</option>
                              <option value="Low">Low</option>
                           </select>
                        </div>
                  </div>
                  <div class=" col-md-2">
                  </div>
               </div>
               <div class="row">
                  <div class=" col-md-2">

                  </div>
                  <div class="form-group col-md-4">
                        <label for="inputStandard" class="col-md-12 control-label">Asset<span class="text-danger">*</span></label>
                        <div class="col-md-12">
                           <select class="form-control input-sm" name="asset" id="asset">
                              <option value="">Select Asset</option>
                           </select>
                        </div>
                  </div>
                  <div class="form-group col-md-4">
                        <label for="inputStandard" class="col-md-12 control-label">Browse File<span class="text-danger">*</span></label>
                        <div class="col-md-12">
                           <input type="file" id="browseFile" name="browseFile"
                              class="form-control input-sm">
                        </div>
                  </div>
                  <div class=" col-md-2">

                  </div>
               </div>
               <div class="row">
                  <div class=" col-md-2">

                  </div>
                  <div class="form-group col-md-8">
                        <label for="inputStandard" class="col-md-12 control-label">Problem<span class="text-danger">*</span></label>
                        <div class="col-md-12">
                           <textarea id="problemdetail" name="problemdetail"
                              class="form-control input-sm"></textarea>
                        </div>
                  </div>
                  <div class=" col-md-2">

                  </div>
               </div>
               <div class="form-group col-md-12">
                  <div class="form-group">
                     <label class="col-md-5 control-label"></label>
                     <div class="col-xs-2">
                        <button id="crSubmit" type="submit" class="btn btn-success btn-block">Submit</button>
                     </div>
                  </div>
               </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- row-->

<?php

$jsonDataAsString = isset($form_templ_data['details']) ? $form_templ_data['details'] : "";
$jsonConfig = isset($purchaserequestdetail['details']) ? json_encode($purchaserequestdetail['details'], true) : "";



?>
<script type="text/javascript">var jsonDataAsString = '<?=$jsonDataAsString?>';</script>
<script type="text/javascript">var jsonConfig = '<?=$jsonConfig?>';</script>

<script type="text/javascript">
   console.log("jsonDataAsString");
   console.log(jsonDataAsString);
   console.log("jsonConfig");
   console.log(jsonConfig);
</script>
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/formeo-master/formeo.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/common.js"></script> -->
<script type="text/javascript">
   $( "form#prBuilderForm" )
       .attr( "enctype", "multipart/form-data" )
       .attr( "encoding", "multipart/form-data" )
   ;
   $( "form#prItemApproval" )
       .attr( "enctype", "multipart/form-data" )
       .attr( "encoding", "multipart/form-data" )
   ;



</script>
<style type="text/css">#main_content {
   padding-bottom: 5px !important;
   clear: both;
   }
   .remove{
      color: red;
   }
</style>

