<div class="panel">
    <div class="panel-heading">
        <span class="panel-title"><?php echo  trans('label.Component') ?></span>
        <div id="godashbord" class="input-group date pull-right">
        	<i class="fa fa-desktop"></i>
        </div>	
    </div>
    <div class="panel-body">
		<div class="col-md-12">
		<!-- <input class="form-control input-sm" name="search" placeholder="">-->
			<div class="input-group date pull-right">
            	<input type="text" id="treesearch" placeholder="<?php echo  trans('label.Filter'); ?>" name="treesearch" class="form-control input-sm input-group date pull-right" value="" autocomplete="off">
                <span class="input-group-addon cursor" id="btnResetSearch">
                	<i class="fa fa-times"></i>
                </span>
            </div>
		</div>
			<!--<div class="col-xs-4">
			  <button class="btn btn-primary mr10 ph20" id="btnResetSearch"> Clear
			    <i class="fa fa-remove pl10"></i>
			  </button>
			  <span id="matches"></span>
			</div>-->
		<!--<p>
		<label for="hideMode" class="mr15">
		  <input type="checkbox" class="mr5 va-t" id="hideMode"> Hide unmatched nodes
		</label>
		<label for="leavesOnly" class="mr15">
		  <input type="checkbox" class="mr5 va-t" id="leavesOnly"> Leaves only
		</label>
		<label for="regex">
		  <input type="checkbox" class="mr5 va-t" id="regex"> Regular expression
		</label>
		</p>
		<hr class="short alt mv15">
		<p id="sampleButtons">
		</p> -->
		<!-- Add a <table> element where the tree should appear: -->
		<div id="treeshow" class="col-md-12 mt10"></div>
	</div>
</div>
