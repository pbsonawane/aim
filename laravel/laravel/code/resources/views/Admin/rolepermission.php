<link rel="stylesheet" type="text/css" href="<?php echo config('app.site_url'); ?>/bootstrap/assets/admin-tools/admin-forms/css/admin-forms.css">
<div class="col-md-10">
        <div class="hidden alert-dismissable" id="msg_popup"></div>
</div>
<div class="col-md-12">
	<div class="panel">
		<div class="panel-body">
        <input id="role_id" name="role_id" type="hidden" value="<?php echo isset($roleid) ? $roleid : ''; ?>">
			<form class="form-horizontal"  name="assignRolePermForm" id="assignRolePermForm">

                        <div class="tab-block mb25">
                            <ul class="nav nav-tabs tabs-border">
                                <?php foreach($all_modules as $m => $module ){ ?>
                                    <li class="<?php echo $m==0 ? 'active' : ''; ?>">
                                        <a href="#<?php echo $module['module_id'].'_'.$module['module_name']; ?>" data-toggle="tab"><?php echo $module['module_name']; ?></a>
                                    </li>
                                <?php } ?>
                            </ul>
                            
                            <div class="tab-content">
                                <?php foreach($all_modules as $m => $module ){ ?>
                                    <div id="<?php echo $module['module_id'].'_'.$module['module_name']; ?>" class="tab-pane <?php echo $m==0 ? 'active' : ''; ?>">

                                        <div class="col-lg-12">
                                            <div class="checkbox-custom fill checkbox-info mb5">
                                                <input  type="checkbox"  class="selectDeselectAll" id="selectDeselectAll_<?php echo $module['module_name']; ?>">
                                                <label for="selectDeselectAll_<?php echo $module['module_name']; ?>"><strong> Select / Deselect All</strong></label>
                                            </div>
                                        </div>

                                        <?php
                                        if(isset($all_permisions_by_module[$module['module_name']])   ){                                         
                                            foreach($all_permisions_by_module[$module['module_name']] as $category => $permissions){ 
                                                if((isset($permissions['advanced']) && !empty($permissions['advanced'] ) )   || (isset($permissions['crud']) && !empty($permissions['crud'] ) ) ){
                                                ?>
                                                    <fieldset class="fieldsetCustom fieldset_<?php echo $module['module_name']; ?>">
                                                        <legend class="legendCustom">
                                                            <strong> <?php  echo $category  ; ?></strong></legend>
                                                            <?php 
                                                            if(!empty($permissions['crud'])){
                                                            ?> 
                                                                <!--<div class="col-lg-12">   
                                                                    <div class="admin-form">
                                                                            <div class="section-divider mb40" id="spy1">
                                                                                <span>Access Permission</span>
                                                                        </div>
                                                                    </div>  
                                                                </div>-->
                                                                <div class="col-lg-12">  
                                                                    <strong>Access Permission :: </strong>
                                                                </div>
                                                                <div class="col-lg-12"><hr class="customhr"></div>
                                                                <?php foreach($permissions['crud'] as $crud => $crudsPermissions){ 
                                                                
                                                                $checked_c = $crudsPermissions['checked_c']==1 ? 'checked': '';
                                                                $checked_r = $crudsPermissions['checked_r']==1 ? 'checked': '';
                                                                $checked_u = $crudsPermissions['checked_u']==1 ? 'checked': '';
                                                                $checked_d = $crudsPermissions['checked_d']==1 ? 'checked': '';
                                                                $p_id = $crudsPermissions['permission_id'];
                                                                ?>
                                                                <div class="col-lg-12 allCrud_<?php echo $p_id; ?>"> 
                                                                    <!--<div class="col-lg-2"><?php //echo $crudsPermissions['permission_name'];?></div>
                                                                    <div class="col-lg-2"><?php //echo "<input type='checkbox'>All";?></div>
                                                                    <div class="col-lg-2"><?php //echo "<input type='checkbox' ".$checked_c."> View";?></div>
                                                                    <div class="col-lg-2"><?php //echo "<input type='checkbox' ".$checked_r."> Add";?></div>
                                                                    <div class="col-lg-2"><?php //echo "<input type='checkbox' ".$checked_u."> Edit";?></div>
                                                                    <div class="col-lg-2"><?php
                                                                    //echo "<input type='checkbox' ".$checked_d."> Delete"; ?></div>
                                                                    -->
                                                                    <?php 

                                                                    echo '<div class="col-lg-2">'.$crudsPermissions['permission_name'].'</div>';
                                                                    echo '<div class="col-lg-2">
                                                                    <div class="checkbox-custom  checkbox-info mb5">
                                                                        <input class="selectAllCrud" name="crud_'.$p_id.'_all" type="checkbox"  id="'.$p_id.'_all">
                                                                        <label for="'.$p_id.'_all">All</label></div>
                                                                    </div>'; 
                                                                    echo '<div class="col-lg-2">
                                                                    <div class="checkbox-custom  mb5">
                                                                        <input name="crud_'.$p_id.'_r" type="checkbox"  id="'.$p_id.'_r" '.$checked_r.'>
                                                                        <label for="'.$p_id.'_r">View</label></div>
                                                                    </div>'; 
                                                                    echo '<div class="col-lg-2">
                                                                    <div class="checkbox-custom  mb5">
                                                                        <input name="crud_'.$p_id.'_c" type="checkbox"  id="'.$p_id.'_c" '.$checked_c.'>
                                                                        <label for="'.$p_id.'_c">Add</label></div>
                                                                    </div>';                                                       echo '<div class="col-lg-2">
                                                                    <div class="checkbox-custom  mb5">
                                                                        <input name="crud_'.$p_id.'_u" type="checkbox"  id="'.$p_id.'_u" '.$checked_u.'>
                                                                        <label for="'.$p_id.'_u">Edit</label></div>
                                                                    </div>';                                                                     
                                                                    echo '<div class="col-lg-2">
                                                                    <div class="checkbox-custom  mb5">
                                                                        <input name="crud_'.$p_id.'_d" type="checkbox"  id="'.$p_id.'_d" '.$checked_d.'>
                                                                        <label for="'.$p_id.'_d">Delete</label></div>
                                                                    </div>'; 
                                                                    
                                                                    ?>
                                                                    
                                                                </div>                                                    
                                                                <div class="col-lg-12"><hr class="customhr"></div>
                                                            <?php  
                                                                }                
                                                                //echo "<pre>";
                                                               // print_r($permissions['crud']);   
                                                            }

                                                            if(!empty($permissions['advanced'])){
                                                            ?>
                                                            <!--<div class="col-lg-12">  
                                                                <div class=" admin-form">
                                                                    <div class="section-divider mb40" id="spy1">
                                                                        <span>Advanced Permission</span>
                                                                    </div>
                                                                </div>
                                                            </div>-->
                                                            <div class="col-lg-12">  
                                                               <strong>Advanced Permission ::</strong>
                                                            </div>
                                                            <div class="col-lg-12"><hr class="customhr"></div>
                                                            <?php foreach($permissions['advanced'] as $advanced => $advancedPermissions){ 
                                                                $checked = $advancedPermissions['checked']==1 ? 'checked': '';
                                                                $p_id = $advancedPermissions['permission_id'];
                                                                ?>
                                                                
                                                                  <!--  <div class="col-lg-2"><?php //echo "<input type='checkbox'  ".$checked.">".$advancedPermissions['permission_name'];?></div>-->
                                                                  <?php
                                                                  $permission_name = $advancedPermissions['permission_name'];
                                                                /*  echo '<div class="col-lg-2">
                                                                    <div class="checkbox-custom  mb5">
                                                                        <input name="advanced_'.$p_id.'" type="checkbox"  id="advanced_'.$p_id.'" '.$checked.'><label for="advanced_'.$p_id.'">'.$permission_name.'</label></div>
                                                                    </div>';   */
                                                                    echo '<div class="col-lg-2">
                                                                    <div class="checkbox-custom  mb5">
                                                                        <input name="advanced_'.$p_id.'_u" type="checkbox"  id="'.$p_id.'_advanced" '.$checked.'>
                                                                        <label for="'.$p_id.'_advanced">'.$permission_name.'</label></div>
                                                                    </div>';    
                                                               
                                                             
                                                                }
                                                               // print_r($permissions['advanced']);   
                                                            }
                                                            ?>
                                                    </fieldset>
                                        <?php 
                                                    }
                                                }
                                            }
                                         ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    




					<label class="col-md-3 control-label"></label>
						<div class="col-xs-2">                           
                            <button id="rolepermissionassign" type="button" class="btn btn-success btn-block">Assign</button>                            
                        </div>


				</div>
			</form>
		</div>
	</div>
</div>

<?php 
//print_r($permission_assign_to_role);
//print_r($permission_access_rights);
//print_r($all_modules);
//print_r($all_permisions_by_module);


 ?>
