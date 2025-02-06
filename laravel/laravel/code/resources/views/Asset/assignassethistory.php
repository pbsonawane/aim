<div class="emtblhscroll">
  <table class="table table-striped table-bordered table-hover">
      <thead>
        <tr>
          <th><?php echo trans('label.lbl_srno') ?></th>
          <th><?php echo trans('label.lbl_user') ?></th>
          <th>Employee ID</th>
          <th>Department</th>
          <th>Staus</th>
          <th>Assigned Date</th>
          <th>Return Date</th>
          <th>Created Date</th>
         
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
            <td><?php if(isset($history['requestername_id'])) echo $history['requester_name'] ?></td>
            <td><?php if(isset($history['employee_id'])) echo $history['employee_id']; ?></td>
            <td><?php 
            $last_names = array_column($deptdata, 'department_name','department_id');
            if(isset($history['department_id'])) echo $last_names[$history['department_id']]; 
          ?></td>
          <?php 
            $cls ='';
            if($i == '0'){  $cls ='text-success';} ?>
            <td class="<?php echo $cls;?>"><b><?php
             if(isset($history['status'])) echo $history['status']; ?></b>
            </td>
            <td><?php if(trim($history['assign_date']) != "") {echo $history['assign_date'];} else echo "--"; ?></td>
            <td><?php if(trim($history['return_date']) != "") {echo $history['return_date'];} else echo "--"; ?></td>
            <td><?php if(isset($history['created_at'])) echo $history['created_at']; ?></td>
              
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