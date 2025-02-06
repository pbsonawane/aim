<?php
	if ($showpagination == true)
	{
?>
<div class="dt-panelmenu clearfix pn">
    <div class="col-md-6 pt5">
		<input type="hidden" value="<?php echo $_scroll_id?>" name="_scroll_id" id="_scroll_id" />
        <?php if ($total_rows > 0) { ?>
        Show <?php echo limitbox($limit,$jsfunction,$show_all);?> entries
        <?php } ?>
        <input name="page" type="hidden" value="" />
    </div>
    <div class="col-md-6 pn">
        <?php if ($total_rows > 0) echo $paginglink; ?>
    </div>
</div>
<?php	
	}
?>
<div class="emtblhscroll"> <?php echo $tabledata; ?> </div>
<!-- data grid -->
<?php
	if ($showpagination == true)
	{
		$from = $offset + 1;
		if($limit == 'all')
			$to = $total_rows;
		else
			$to = $offset + $limit;
		if ($limit == '' || $limit == 'all' || $to >= $total_rows)
			$to = $total_rows;
?>
<div class="dt-panelmenu clearfix pn">
    <div class="col-md-6 pt10"><?php echo $total_rows > 0 ? $from.' to '.$to.' of '.$total_rows.' entries' : '';?></div>
    <div class="col-md-6 pn">
        <?php  if ($total_rows > 0) echo $paginglink;?>
    </div>
</div>
<?php
	}
?>