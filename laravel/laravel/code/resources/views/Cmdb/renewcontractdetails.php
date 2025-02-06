<table class="table table-striped table-bordered table-hover table-responsive ">
   <thead>
         <tr>
             
                <th class="text-center"><?php echo trans('label.lbl_srno'); ?></th>
				<th><?php echo trans('label.lbl_contract_id'); ?></th> 
				<th><?php echo trans('label.desc'); ?></th>    
				<th><?php echo trans('label.lbl_from_date'); ?></th>        
				<th><?php echo trans('label.lbl_to_date'); ?></th>    
				<th><?php echo trans('label.lbl_cost'); ?></th>    
				<th><?php echo trans('label.lbl_renewed_date'); ?></th>           
				<th><?php echo trans('label.lbl_renewed_by'); ?></th>    
                                   
       </tr>
   </thead>
   <tbody>
                       <tr>
               
                   <!--<td class="srno">1</td>-->
                   <?php
                 
                   if (is_array($renewdetails) && count($renewdetails) > 0)
                   {         
                       foreach($renewdetails as $i => $renewdetail)
                       {		
                 
                  ?>
                   <tr data-val="value">
                   <td class="text-center"><?php echo $i +  1?></td>
                       <td><?php echo $renewdetail['contractid']; ?></td>
                       <td><?php echo $renewdetail['description']; ?></td>
                       <td><?php echo $renewdetail['from_date']; ?></td>
                       <td><?php echo $renewdetail['to_date']; ?></td>
                       <td><?php echo $renewdetail['cost']; ?></td>
                       <td><?php echo $renewdetail['created_at']; ?></td>
                       <td><?php echo showname();?></td>
                     
                       </tr>
			<?php
                }
            }else
            { 
                
        echo '<tr><td colspan = "100" style="text-align:center">'.trans('messages.msg_norecordfound').'</td></tr>';
        
                
            }
            
		?>     
   </tbody> 
 
</table>


               </form>