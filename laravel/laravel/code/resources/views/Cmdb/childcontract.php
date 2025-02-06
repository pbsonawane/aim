<table class="table table-striped table-bordered table-hover">
		<thead>
		  <tr>
                <th class="text-center"><?php echo trans('label.lbl_srno'); ?></th>
                <th><?php echo trans('label.lbl_contract_id'); ?></th> 
                <th><?php echo trans('label.lbl_contract_name'); ?></th> 
                <th><?php echo trans('label.lbl_contract_type'); ?></th> 
                <th><?php echo trans('label.desc'); ?></th>    
                <th><?php echo trans('label.lbl_from_date'); ?></th>        
                <th><?php echo trans('label.lbl_to_date'); ?></th>        
                 <th><?php echo trans('label.lbl_contract_status'); ?></th>                     
		  </tr>
		</thead>
		<tbody>
            <?php
           //print_r($childcontracts)  ;
            //$childcontracts =$dbdata;
            //print_r($childcontracts);
			if (is_array($childcontracts) && count($childcontracts) > 0)
			{
				foreach($childcontracts as $i => $childcontract)
				{	
                  ?>
			<tr>
				<td class="text-center"><?php echo $i +  1?></td>
				<td><?php echo $childcontract['contractid']; ?></td>
				<td><?php echo $childcontract['contract_name']; ?></td>
                <td><?php echo $childcontract['contract_type']; ?></td>
                <td><?php echo $childcontract['description']; ?></td>
                <td><?php echo $childcontract['from_date']; ?></td>
                <td><?php echo $childcontract['to_date']; ?></td>
                <td><?php echo $childcontract['contract_status']; ?></td>
			</tr>
			<?php
                }
            }  else
              echo '<tr><td colspan = "100" class ="text-center">'.trans('label.no_records').'</td></tr>';
             
		?>
		</tbody>
    </table>
       