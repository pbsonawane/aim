<?php 
	$setpagefun  = isset($pagefunction['setpagefun']) &&  $pagefunction['setpagefun'] != '' ? $pagefunction['setpagefun'] : 'javascript: setPage';
?>
<ul class="pagination pull-right">
	<?php if ($page > 0) { ?>
	<li class="first "><a href="javascript:void(0);" pagedata="0" onclick="<?php echo $setpagefun ; ?>($(this),'<?php echo $jsfunction; ?>');"><<</a></li>
    <?php } ?>
    <?php if(($page - 1) >= 0){ ?>
    <li class="prev "><a href="javascript:void(0);" pagedata="<?php echo $page - 1 ; ?>" onclick="<?php echo $setpagefun ; ?>($(this),'<?php echo $jsfunction; ?>');"><</a></li>
    <?php } ?>
    <?php if( ( $page - 2 ) <= $noOfPages && ( $page - 2 ) >= 0 ){ ?>
    <li><a href="javascript:void(0);" pagedata="<?php echo $page - 2 ; ?>" onclick="<?php echo $setpagefun ; ?>( $(this),'<?php echo $jsfunction; ?>');"><?php echo $page - 1 ?></a></li>
    <?php } ?>
    <?php if( ( $page - 1 ) <= $noOfPages  && ( $page - 1 ) >= 0){ ?>
    <li><a href="javascript:void(0);" pagedata="<?php echo $page - 1 ; ?>" onclick="<?php echo $setpagefun ; ?>($(this),'<?php echo $jsfunction; ?>');"><?php echo $page  ?></a></li>
    <?php } ?>
    <li class="active"><a href="javascript:void(0);"><?php echo $page + 1; ?></a></li>
    <?php if( ( $page + 1 ) < $noOfPages ){ ?>
    <li><a href="javascript:void(0);" pagedata="<?php echo $page + 1; ?>" onclick="<?php echo $setpagefun ; ?>( $(this),'<?php echo $jsfunction; ?>');"><?php echo $page + 2 ?></a></li>
    <?php } ?>
    <?php if( ( $page + 2 ) < $noOfPages ){ ?>
    <li><a href="javascript:void(0);" pagedata="<?php echo $page + 2 ; ?>" onclick="<?php echo $setpagefun ; ?>( $(this),'<?php echo $jsfunction; ?>');"><?php echo $page + 3 ?></a></li>
    <?php } ?>
    <?php if( ( $page + 1 ) < $noOfPages ){ ?>
    <li class="next"><a href="javascript:void(0);" pagedata="<?php echo $page + 1 ; ?>" onclick="<?php echo $setpagefun ; ?>($(this),'<?php echo $jsfunction; ?>');">> </a></li>
    <?php } ?>
    <?php if ($page < $noOfPages-1) { ?>
    <li class="last "><a href="javascript:void(0);" pagedata="<?php echo $noOfPages-1;?>" onclick="<?php echo $setpagefun ; ?>($(this),'<?php echo $jsfunction; ?>');">>></a></li>
   	<?php } ?>
</ul>