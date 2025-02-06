<div class="emtblhscroll">
  <table class="table table-striped table-bordered table-hover">
      <thead>
        <tr>
          <th><?php echo trans('label.lbl_srno') ?></th>
          <th><?php echo trans('label.lbl_user') ?></th>
          <th><?php echo trans('label.lbl_action') ?></th>
          <th><?php echo trans('label.lbl_comment') ?></th>
          <th><?php echo trans('label.lbl_date') ?></th>
         
        </tr>
      </thead>
      <tbody>
          <?php
        //  dd($historydata);
          if (is_array($historydata) && count($historydata) > 0)
          {
              foreach($historydata as $i => $history)
              {   
          ?>
          <tr>

            <td><?php echo $i + 1 ; ?></td>
            <td><?php if(isset($users[$history['user_id']])) echo $users[$history['user_id']]['firstname'].' '.$users[$history['user_id']]['lastname']; ?></td>
            <td><?php if(isset($history['message'])) echo $history['message']; ?></td>
            <td><?php if(trim($history['comment']) != "") {echo $history['comment'];} else echo "--"; ?></td>
            <td><?php if(isset($history['updated_at'])) echo $history['updated_at']; ?></td>
              
          </tr>
          <?php
              }
          }
          else
              echo '<tr><td colspan = "100" class ="text-center">'.trans('label.no_records').'</td></tr>';
              ?>  
      </tbody>
  </table>
</div>