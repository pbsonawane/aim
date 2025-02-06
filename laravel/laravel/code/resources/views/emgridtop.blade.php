<?php

if ($gridsearch == true || $gridadvsearch == true || $gridpdf == true || $gridcsv == true || $gridexpand == true || $gridcollspase == true) { ?>
<input name="exporttype" type="hidden" value="" />
<!-- pdf or csv -->

<div class="pv5 pl5 bg-white pr5 " id="emgridadvsearch">
    <div class="table-layout">
        <?php 
        if ($gridsearch == true && $gridibsearch == true) { ?>
       
        <div class="col-md-3 pln">
            <div class="input-group">
                <input type="text" class="form-control input-sm" placeholder="Search..." name="searchkeyword" id="searchkeyword" onKeyUp="return searchRecords('<?php echo $jsfunction;?>');" onkeydown="if (event.keyCode == 13) { return false; }" style="z-index:0;"/>
                <?php if ($gridadvsearch == true) { ?>
                <span class="input-group-addon btn" id="spn_emadvsearch" style="color:#4349ac;font-weight:bold;"><i class="fa fa-search"></i>&nbsp;Advance Filters</span>
                <?php } ?>
            </div>
        </div>
        <?php }else if ($gridsearch == true && $gridibsearch == false && $gridadvsearch = true) {?>
        <div class="col-emd-0 pln">
            <div class="input-group">
				<span class="input-group-addon btn e-br-l e-bl-l sm-width" id="spn_emadvsearch" style="color:#4349ac;font-weight:bold;"><i class="fa fa-search">&nbsp;Advance Filters</i>
                </span>
			</div>
        </div>
		<div class="col-md-10 pln">
            <div class="input-group">
				&nbsp;<b>Date Range: </b><?php echo str_replace("_"," ",ucfirst(_isset($extradata,'timerange')))."&nbsp;&nbsp;<b>Duration: </b>"._isset($extradata,'selected_time'); ?>
            </div>

        </div>
        <?php	} ?>
        <div class="col-md-9 text-right prn">
        	<?php if ($gridpdf == true || $gridcsv == true || $gridprint == true || $gridexpand == true || $gridcollspase == true) {

			?>
			<?php if ($importdevices == true) {?>
				<button type="button" class="btn btn-success importdevices mr5 ml5 ">Import Host</button>
			<?php } ?>
            <div class="btn-group">

                <?php if ($gridpdf == true) {?>
                <button type="button" class="btn btn-default light" title="Click To Fetch PDF Report" onClick="javascript: exportFile(this,'<?php echo $jsfunction;?>', 'pdf');" id="icon_gridpdf"><i class="fa fa-file-pdf-o empdficon"></i> </button>
                <?php } ?>
                <?php
                 if ($gridcsv == true) {?>
                <button type="button" class="btn btn-default light" title="Click To Fetch Excel Report" onClick="javascript: exportFile(this, '<?php echo $jsfunction;?>', 'csv');" id="icon_gridcsv"><i class="fa fa-file-excel-o emcsvicon"></i> </button>
                <?php } ?>
                <?php if ($gridprint == true) {?>
                <button type="button" class="btn btn-default light" title="Click To Print Report" onClick="javascript: exportFile(this, '<?php echo $jsfunction;?>', 'print');" id="icon_gridprint"><i class="fa fa-print emprinticon"></i> </button>
                <?php } ?>

				<?php if ($gridexpand == true) {?>
                <button type="button" class="btn btn-default light" title="Click To Expand" onClick="javascript: expandAll();" id="icon_gridprint"><i class=" glyphicons glyphicons-left_arrow emcsvicon"></i><i class=" glyphicons glyphicons-right_arrow emcsvicon"></i> </button>
                <?php } ?>

				<?php if ($gridcollspase == true) {?>
                <button type="button" class="btn btn-default light" title="Click To Collapse" onClick="javascript: collapseAll();" id="icon_gridprint"><i class=" glyphicons glyphicons-right_arrow emcsvicon"></i><i class=" glyphicons glyphicons-left_arrow emcsvicon"></i> </button>
                <?php } ?>
            </div>
            <?php }?>
        </div>
    </div>

</div>
<?php if ($gridadvsearch == true) { ?>
    <div class="pv5 pl5 pr5" id="div_emadvsearch"><?php echo isset($advsearchform) ? $advsearchform : '';?></div>
    <?php } ?>
<?php } ?>



