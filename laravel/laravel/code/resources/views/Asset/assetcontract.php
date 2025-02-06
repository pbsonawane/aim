<div class="emtblhscroll">
  <table class="table table-striped table-bordered table-hover">
      <thead>
        <tr>
          <th><?php echo trans('label.lbl_srno') ?></th>
          <th><?php echo trans('label.lbl_contract_name') ?></th>
          <th><?php echo trans('label.lbl_contract_type') ?></th>
          <th><?php echo trans('label.lbl_contract_status') ?></th>
          <th><?php echo trans('label.lbl_active_period_from') ?></th>
          <th><?php echo trans('label.lbl_to') ?></th>
         
        </tr>
      </thead>
      <tbody>
          <?php
          
          if (is_array($contractdata) && count($contractdata) > 0)
          {
              foreach($contractdata as $i => $contract)
              {   
          ?>
          <tr>

            <td><?php echo $i + 1 ; ?></td>
            <td><a href="<?php echo config('app.site_url') .'/contract/'. $contract['contract_id'];?>" target="_blank"><?php echo $contract['contract_name']; ?></a></td>
            <td><?php echo $contract['contract_type']; ?></td>
            <td><?php echo ucfirst($contract['contract_status']);?></td>
            <td><?php echo $contract['from_date']; ?></td>
            <td><?php echo $contract['to_date']; ?></td>
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