<link rel="stylesheet" type="text/css" href="<?php echo config('app.site_url'); ?>/bootstrap/assets/admin-tools/admin-forms/css/admin-forms.css">

<div class="tab-block mb25">
   <ul class="nav nav-tabs tabs-border">
      <li class="active">
         <a href="#infotab" data-toggle="tab">User Info</a>
      </li>
      <li>
         <a href="#modulestab" data-toggle="tab">Modules</a>
      </li>
      <li>
         <a href="#regiontab" data-toggle="tab" class="userregiontab">Regions</a>
      </li>
	  <li>
         <a href="#bvtab" data-toggle="tab" class="userbvtab">Business Verticals</a>
      </li>
   </ul>
   <div class="tab-content">
     
	  <div id="infotab" class="tab-pane active">
         
		 <div class="panel-body pn">

			<table class="table mbn">
				
				<tbody>
					<tr>
						<td class="va-m fw600 text-muted" width="15%">Name</td>
						<td class="fs15 fw500" width="35%">
						<input type="hidden" id="user_id" value="<?php echo $userinfo['user_id'];?>"  />
						<?php echo  ucfirst($userinfo['firstname']).' '. ucfirst($userinfo['lastname']); ?></td>
						<td class="va-m fw600 text-muted" width="15%">Email</td>
						<td class="fs15 fw500" width="35%"><?php echo $userinfo['email'];?></td>
					</tr>
					<tr>
						<td class="va-m fw600 text-muted">User Type</td>
						<td class="fs15 fw500"><?php echo  ucfirst($userinfo['user_type']); ?></td>
						<td class="va-m fw600 text-muted">Contact No.</td>
						<td class="fs15 fw500"><?php echo $userinfo['contactno'];?></td>
					</tr>
					<tr>
						<td class="va-m fw600 text-muted">Role</td>
						<td class="fs15 fw500"><?php 
						$rolename = "";
						if(is_array($userinfo['role_name']) && count($userinfo['role_name']) > 0 )
						{
							foreach($userinfo['role_name'] as $rname)
							{
								$rolename .= ucfirst($rname).', ';
							}
							$rolename = trim($rolename,", ");	
						}
						 echo $rolename; ?></td>
						<td class="va-m fw600 text-muted">Manager</td>
						<td class="fs15 fw500"><?php echo ucfirst($userinfo['mgrfirstname']).' '. ucfirst($userinfo['mgrlastname']);?></td>
					</tr>
					<tr>
						<td class="va-m fw600 text-muted">Department</td>
						<td class="fs15 fw500"><?php echo $userinfo['department_name']; ?></td>
						<td class="va-m fw600 text-muted">Designation</td>
						<td class="fs15 fw500"><?php echo $userinfo['designation_name'];?></td>
					</tr>
				</tbody>
			</table>
        </div>
		 
		 
      </div>
	  
      <div id="modulestab" class="tab-pane">
	  <div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup_module"></div>
	  </div>
	  <div class="col-md-12">
      <form class="form-horizontal"  name="addusermoduleform" id="addusermoduleform">
            <?php 
            
            if(is_array($modules) && count($modules)>0)
            {
                foreach($modules as $key=>$module) 
                { 
                  if($module['module_name']!='IAM')
                  {
                      ?>
               <div class="col-md-2 ccursor crtype" id="<?php echo $module['module_name'];?>" >
                                <div class="panel panel-info panel-border top">
                                    <div class="panel-heading">
                                        <span class="panel-title"><?php echo $module['module_name'];?></span>
                                        <div class="widget-menu pull-right">
                                         
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                    <div class="admin-form">
                                        <label class="block mt15 switch switch-primary">
                                            <?php   
                                                     $checked = "";
                                                    if($module['checked'])
                                                        $checked = "checked = 'checked'";
                                                    
                                            ?>
                                                    <input type="checkbox" name="module_ids" class="user_modules" id="<?php echo $module['module_id'];?>" value="<?php echo $module['module_id'];?>" <?php echo  $checked;?>>
                                                    <label for="<?php echo $module['module_id'];?>" data-on="YES" data-off="NO"></label>
                                   </label>

                                                   

                </div>
                                    </div>
                                  
                                </div>
                                
                            </div> 
                            
        <?php
        
        $cnt = $key+1;
        if($cnt%6==0)
        {
            echo '<div class="clear"></div>';                     
        }
                }
                
            
                }
            }	
        ?>
        <div class="form-group clear">
			<label class="col-md-3 control-label"></label>
				<div class="col-xs-2">
				<button id="assignmodule" type="button" class="btn btn-success btn-block">Assign</button>
			</div>
        </div>
       </form>
	   </div>
      </div>
	  
      <div id="regiontab" class="tab-pane">
       
      </div>
	  
      <div id="bvtab" class="tab-pane">
       
      </div>
   </div>
</div>

