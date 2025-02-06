
<div>
	<table class="table table-striped table-bordered table-hover table-responsive ">
		<thead>
		  <tr>
            <th class="srno">Sr.No.</th>
            <th>Template Title</th>
            <th>Template Name</th>
            <th>Description</th>
            <th>Type</th>
			<th>Default Type</th>
			<th>Action</th>
		  </tr>
		</thead>
		<tbody>
			<?php
			$regions = $dbdata;
			if (is_array($regions) && count($regions) > 0)
			{
				foreach($regions as $i => $region)
				{
					$id = $region['form_templ_id'];
					$delete = '';
					$edit = '<span title = "Click To Edit Record" name="edit_b" id="edit_'.$id.'" type="button" data-id="'.$id.'" class="settingtemplate_edit"><i class="fa fa-edit mr10 fa-lg"  data-id="'.$id.'"></i></span>';

					$delete = '<span title = "Click To Delete Record" type="button" id="delete_'.$id.'" data-id="'.$id.'" class="settingtemplate_delete" id="delete_b"><i class="fa fa-trash-o mr10 fa-lg"></i></span>';

			?>
			<tr>
                <td class="srno"><?php echo $i + $offset + 1?></td>
                <td><?php echo $region['template_title']; ?></td>
                <td><?php echo $region['template_name']; ?></td>
                <td><?php echo $region['description']; ?></td>				
                <td><?php echo $region['type']; ?></td>
                <td><?php echo $region['default_template']=='y'? 'Yes' : 'No'; ?></td>
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
