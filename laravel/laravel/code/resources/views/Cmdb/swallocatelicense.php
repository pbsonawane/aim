    <!-- Modal -->
                        <div id="swallocate_license" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-lg">

                            <!-- Modal content-->
                            <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title"><?php echo trans('label.lbl_allocate_license');?></h4>
                            </div>
                            <div class="modal-body">
                            <div>
                <form name="allocateform" id="allocateform" method="post" >
               
                
                <table class="table table-striped table-bordered table-hover table-responsive ">

   
     <thead>
        <tr>
           <th class="checkbox_column">
                <div class="checkbox-custom mb5 checkbox-info">
                    <input type="checkbox" class="" id="associateCheckAll" value="6">
                    <label for="associateCheckAll"></label>
                </div>
                </th>
            <th><?php echo trans('label.lbl_device') ?></th>
            <th><?php echo trans('label.lbl_display_name') ?></th>
           
        </tr>
    </thead>
    
    
</table>


                </form>
 
        </div>
               </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-success" id="allocate_license"   data-dismiss="modal"><?php echo trans('label.btn_allocate');?></button>
                            </div>
                            </div>

                        </div>
                        </div>
                        <!--Modal End-->