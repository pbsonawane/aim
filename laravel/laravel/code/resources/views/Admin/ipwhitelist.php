<!-- Start: Topbar -->
<header id="topbar" class="affix"  >
   <div class="topbar-left">
      <ol class="breadcrumb">
         <li class="crumb-active"><a class="nounderline" href="<?php echo url()->current();?>">Whitelist IP</a></li>
         <li class="crumb-link">Admin</li>
         <li class="crumb-trail">Whitelist IP Manage</li>
      </ol>
   </div>
</header>
<div id="content">
   <div class="row">
      <div class="col-md-12">
         <div class="col-lg-4">
            <div class="panel">
               <div class="panel-heading"> <span class="panel-title">Add IP / CIDR </span></div>
               <div class="panel-body">
                  <div class="row">
                     <div class="col-lg-12">
                         <div class="alert hidden alert-dismissable" id="msg_div_addip"></div>
                     </div>
                  </div>
				  
				  <div class="form-group">
					  <div class="input-group">
							<input type="text" id="add_allowed_ip" name="add_allowed_ip" class="form-control" placeholder="Allow IP / CIDR" >  
							<span class="input-group-addon" onClick="addWhiteListIp()" title="Save IP/Subnet to whitelisted"><i class="fa fa-plus-square"></i>
							</span>
						</div>
						 <span class="help-block">( For ex : 10.10.10.10 or 10.10.10.10/25)</span>
				  </div>
               </div>
            </div>
            <div class="panel">
               <div class="panel-heading"> <span class="panel-title">Whitelisted IPs / Subnets  </span></div>
               <div class="panel-body">
					<div class="row tableOverflow">
					   <div class="col-md-12">
						  <div class="alert hidden alert-dismissable" id="msg_div_whitelistedips"></div>
					   </div>
					   <div class="col-md-12">
						  <form method="post" name="frmwhitelistedips" id="frmwhitelistedips">
							 <div class="panel">
								<div class="panel">
									<div class="panel panel-visible" id="grid_data_whitelistedips"></div>
								</div>
							 </div>
						  </form>
					   </div>
					</div>
						
						
						
               </div>
            </div>
         </div>
         <div class="col-lg-8 nospace">
            <div class="panel">
               <div class="panel-heading"> <span class="panel-title">Pending requests of IP whitelisting </span></div>
               <div class="panel-body">
			   		<div class="row">
					   <div class="col-md-12">
						  <div class="alert hidden alert-dismissable" id="msg_div"></div>
					   </div>
					   <div class="col-md-12">
						  <form method="post" name="frmiplists" id="frmiplists">
							 <div class="panel">
								<div class="panel">
									<?php echo csrf_field(); ?>
									<?php echo isset($emgridtop_tokenip) ? $emgridtop_tokenip : ''; ?>
									<div class="panel panel-visible" id="grid_data"></div>
								</div>
							 </div>
						  </form>
					   </div>
					</div>
               </div>
            </div>
            <div class="panel">
               <div class="panel-heading"> <span class="panel-title">Login activity</span></div>
               <div class="panel-body">
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script language="javascript" type="text/javascript" src="enlight/scripts/common.js"></script> 
<script language="javascript" type="text/javascript" src="enlight/scripts/admin/ipwhitelist.js"></script>

