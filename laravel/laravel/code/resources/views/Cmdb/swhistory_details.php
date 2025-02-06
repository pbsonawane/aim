

<div>
   <table class="table table-striped table-bordered table-hover ">
      <thead>
         <tr>
            <th class="text-center"><?php echo trans('label.lbl_srno') ?></th>
            <th><?php echo trans('label.lbl_message') ?></th>
            <th><?php echo trans('label.lbl_action') ?></th>
            <th><?php echo trans('label.lbl_date') ?></th>
         </tr>
      </thead>
      <tbody>
         <?php
         $history = $dbdata;
            if (is_array($history) && count($history) > 0)
            {
                    foreach ($history as $i => $historydata)
                {
                    ?>
         <tr>
            <td class="text-center"><?php echo $i + 1?></td>
            <td><?php echo $historydata['message']; ?></td>
            <td><?php echo $historydata['action']; ?></td>
            <td><?php echo $historydata['created_at']; ?></td>
         </tr>
         <?php
            }
            }else{
            
            echo '<tr><td colspan = "100" class ="text-center">'.trans('label.no_records').'</td></tr>';
            }?>
      </tbody>
   </table>
</div>
<!-- <script>
   $(document).ready(function() {
   $('.datatable_history').dataTable({
                  "aoColumnDefs": [{
                      'bSortable': false,
                      'aTargets': [-1]
                  }],
                  "oLanguage": {
                      "oPaginate": {
                          "sPrevious": "",
                          "sNext": ""
                      }
                  },
                  "iDisplayLength": 5,
                  "aLengthMenu": [
                      [5, 10, 25, 50, -1],
                      [5, 10, 25, 50, "All"]
                  ],
                  "sDom": '<"dt-panelmenu clearfix"lfr>t<"dt-panelfooter clearfix"ip>',
                  "oTableTools": {
                      "sSwfPath": "vendor/plugins/datatables/extensions/TableTools/swf/copy_csv_xls_pdf.swf"
                  }
             });
   });
</script>
 -->
