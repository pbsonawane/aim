
<div>
	<table class="table table-striped table-bordered table-hover table-responsive ">
		<thead>
		  <tr>
            <th class="srno">Sr.No.</th>
            <!--<th> Title</th>-->
            <th> Name</th>                 
            <th> Type</th> 
            <!--<th> Description</th>-->
            <th> Content</th> 
            <th>Action</th>  
		  </tr>
		</thead>
		<tbody>
			<?php
            $templateData = $dbdata;        
          //  print_r($templateData)      ;
			if (is_array($templateData) && count($templateData) > 0)
			{
				foreach($templateData as $i => $credentialDetails)
				{

                    $id = $credentialDetails['config_id'];
                    $template_name = $credentialDetails['template_name'];
					$delete = '';
					$edit = '<span title = "Click To Edit Record" name="edit_b" id="edit_'.$id.'" type="button" data-id="'.$id.'" data-title="'.$template_name.'" class="credential_edit"><i class="fa fa-edit mr10 fa-lg"  data-id="'.$id.'"></i></span>';

                    $delete = '<span title = "Click To Delete Record" type="button" id="delete_'.$id.'" data-id="'.$id.'" class="credential_delete" id="delete_b"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';
                 // $credentialDetails =  json_decode($region['details']);                

			?>
	        <tr>
                <td class="srno"><?php echo $i + $offset + 1?></td>                
                <td><?php echo $credentialDetails['template_name']; ?></td>                	
                <td><?php echo $credentialDetails['template_title']; ?></td>
                <td><span  data-toggle="tooltip" data-placement="top" title = "<?php echo $credentialDetails['details']; ?>"><?php echo substr($credentialDetails['details'], 0,10); ?></span></td>
				<td><?php echo $edit.' '.$delete; ?></td>
			</tr> 
			<?php
				}
			}
			else
				echo '<tr><td colspan="100" align="center"> No Records</td></tr>';
				?>
		</tbody>
	</table>
</div>
