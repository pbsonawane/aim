<!-- Start: Topbar -->
<header id="topbar" class="affix"  >
<div class="topbar-left">
   <?php breadcrum(trans('title.reports')); ?>
</div>
<div class="topbar-right">
  @if(canuser('create','reports'))
	<div class="btn-group">
		<button id="timeline-toggle" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		<span class="glyphicons glyphicons-show_lines fs16"></span>
		</button>
		 <ul class="dropdown-menu pull-right" role="menu">
			<li class="reportsadd" title="<?php echo trans('label.lbl_add_report');?>">
        <a id="reportsadd"><span title="<?php echo trans('label.lbl_add_report');?>" class="reportsadd"><?php echo trans('label.lbl_add_report');?></span></a>
			</li>
		</ul>
	</div>
  @endif
</div>
</header>
<!-- End: Topbar -->
<!--SK -->
<div id="content">
  <div class="row">
    <div class="col-md-12">
      <div class="alert hidden alert-dismissable" id="msg_div"></div>
    </div>
    <div class="col-md-12">
    	<form method="post" name="frmrepcat" id="frmrepcat">  	
      		<div class="panel">
    				<?php echo csrf_field(); ?>	
    				<?php echo isset($emgridtop) ? $emgridtop : ''; ?>
        		<div class="panel panel-visible" id="grid_data">
              <div class="col-md-3" id="tree_data">
                <div class="panel">
                  <div class="panel-heading">
                      <span class="panel-title">Report Category</span>
                      <div id="godashbord" class="input-group date pull-right">
                        <i class="fa fa-list-alt"></i>
                      </div>  
                  </div>
                  <div class="panel-body">       
                     <div class="col-md-12">
                      <div class="input-group date pull-right">
                          <input type="text" id="filter-reportcat" placeholder="Filter" name="filter-reportcat" class="form-control input-sm input-group date pull-right" value="" autocomplete="off">
                            <span class="input-group-addon cursor" id="btnResetSearch" disabled="disabled">
                              <i class="fa fa-times"></i>
                            </span>
                      </div>
                      <div class="demo rounded text-center text-white p-4 bg-dark mb-3"><span class="mt-0 mb-0"><span>You clicked </span><strong class="text-warning" id="supFool">nothing</strong>.</span></div>
                    </div>
                    <div id="treeshow" class="col-md-12 mt10">
                      <div class="filter__list-wrap mb-0">
                        <ul class="filter__tree" style="display: block;">
                          <li class="filter__tree-item active">
                            <ul class="filter__tree" style="display: block;">
                              <li class="filter__tree-item"><span class="filter__link"><i class="fa fa-folder mr-2"></i><span>  CMDB</span></span>
                              </li>
                              <li class="filter__tree-item"><span class="filter__link"><i class="fa fa-folder mr-2"></i><span>  Purchase</span></span>
                              </li>
                            </ul>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-9" id="report_data"></div>  
            </div>
      		</div>
      	</form>
    </div>
  </div>
</div>
</div>
<script language="javascript" type="text/javascript" src="enlight/scripts/common.js"></script>
<script language="javascript" type="text/javascript" src="enlight/scripts/reports/tags.js">
</script>
<script language="javascript" type="text/javascript" src="enlight/scripts/reports/reports.js"></script>
<script type="text/javascript">

 console.clear();

const listSearch = document.querySelector("#filter-reportcat");
listSearch.addEventListener("keyup", filter);

function filter() {
  var term = document.querySelector("#filter-reportcat").value.toLowerCase();
  var tag = document.querySelectorAll(".filter__link");
  for (i = 0; i < tag.length; i++) {
    if (tag[i].innerHTML.toLowerCase().indexOf(term) !== -1) {
      $(tag[i]).show();
      $(tag[i]).parent().parent().show();
    } else {
      $(tag[i]).hide();
    }
  }
}

$('.filter__link').click(function() {
  $(this).siblings('.filter__tree').slideToggle(250);
  $(this).siblings('.filter__tree').children('.filter__tree-item').children('.filter__tree').hide();
  $('.filter__link').removeClass('active');
  $(this).addClass('active');
});


$('.filter__link').click(function(e) {
  var clickLabel = $(e.target).closest('span').text();
  $('#supFool').text(clickLabel)
  console.log(clickLabel);
});
</script>
<style type="text/css">
.chat {
  margin: 0;
  padding: 0;
  list-style-type: none;
  max-height: 380px;
  min-height: 380px;
  overflow-y: scroll;
}
.chat__item {
  display: -webkit-box;
  display: flex;
  width: 100%;
  -webkit-box-align: center;
          align-items: center;
  position: relative;
  top: 0;
  -webkit-animation: messageUp 300ms ease forwards;
          animation: messageUp 300ms ease forwards;
}
.chat__item:not(:last-of-type) {
  margin-bottom: 2rem;
}
.chat__item--agent {
  -webkit-box-orient: horizontal;
  -webkit-box-direction: reverse;
          flex-direction: row-reverse;
  -webkit-box-pack: start;
          justify-content: flex-start;
}
.chat__item--agent .chat__avatar {
  margin: 0 0 0 15px;
}
.chat__item--agent .chat__info {
  text-align: right;
}
.chat__item--agent .chat__message {
  background: rgba(56, 112, 196, 0.06);
  color: #3870C4;
  border-color: #3870C4;
  margin-right: 0;
  margin-left: auto;
}
.chat__avatar {
  border-radius: 999px;
  width: 35px;
  height: 35px;
  background: #CFD0D4;
  margin: 0 15px 0 0;
  background-size: cover;
  background-position: center;
  box-shadow: 0px 0px 0px 3px #FFFFFF;
}
.chat__content {
  width: 80%;
}
.chat__info {
  color: #57595F;
  display: block;
}
.chat__message {
  background: #FFFFFF;
  border: 1px solid #CFD0D4;
  padding: 8px;
  border-radius: .2rem;
  width: 100%;
  position: relative;
  margin-bottom: 8px;
  max-width: 500px;
}
.chat__message-input {
  height: 75px;
}

@-webkit-keyframes messageUp {
  0% {
    top: 0px;
  }
  25% {
    top: 15px;
  }
  50% {
    top: -5px;
  }
  75% {
    top: -5px;
  }
  100% {
    top: 0;
  }
}

@keyframes messageUp {
  0% {
    top: 0px;
  }
  25% {
    top: 15px;
  }
  50% {
    top: -5px;
  }
  75% {
    top: -5px;
  }
  100% {
    top: 0;
  }
}
.sidebar {
  padding: 0;
}

#merchantList {
  position: absolute;
  width: 100%;
  top: 3rem;
  left: 50%;
  -webkit-transform: translateX(-50%);
          transform: translateX(-50%);
}

#contactChangeContainer input[type=radio]:checked + label {
  font-weight: 700;
}

.filter__tree {
  margin: 0;
  padding: 0;
  list-style-type: none;
  position: relative;
}
.filter__tree .filter__tree {
  padding-left: 15px;
  display: none;
}
.filter__tree .filter__tree .filter__tree-item {
  position: relative;
}
.filter__tree .filter__tree .filter__tree-item:before {
  position: absolute;
  content: '';
  height: 100%;
  width: 2px;
  background: #CFD0D4;
  left: -10px;
}
.filter__tree .filter__tree .filter__tree-item .filter__link:hover:before, .filter__tree .filter__tree .filter__tree-item .filter__link:focus:before {
  position: absolute;
  content: '';
  height: 100%;
  width: 2px;
  left: -10px;
  background: currentColor;
}
.filter__link {
  text-align: left;
  padding-top: 3px;
  padding-bottom: 3px;
  display: block;
  color: #77787C;
  -webkit-animation: fade 350ms ease forwards;
          animation: fade 350ms ease forwards;
  cursor: pointer;
}
.filter__link:hover, .filter__link:focus {
  color: #3870C4;
  background: rgba(237, 241, 245, 0.6);
}
.filter__link.active {
  font-weight: 700;
  color: #3870C4;
}

</style>

