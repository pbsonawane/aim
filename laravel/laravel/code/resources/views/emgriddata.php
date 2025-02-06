<?php
	if ($data_cnt > 0)
	{
?>
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <?php if ($showserial == true) { ?>
                <th align="center" width="2%">Sr.No.</th>
                <?php } ?>
                <?php foreach($columns as $tblfield => $name) { ?>
                <th><?php echo $name; ?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
        <?php
			$sr_no = $page * $limit;
			foreach($dbdata as $row)
			{
				$sr_no = $sr_no + 1;
		?>
		<tr>
			<?php if ($showserial == true) { ?>
			<td align="center"><?php echo $sr_no."."; ?></td>
			<?php } ?>
			<?php foreach($columns as $tblfield => $name) { ?>
			<td><?php echo $row[$tblfield];?></td>
			<?php } ?>
		</tr>
		<?php 
			}	
		?>
        </tbody>
    </table>
<?php
	}
	else
	{
		echo 'No Data';
	}
?>