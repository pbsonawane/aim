
<body class="profile-page">
    <div id="main">
    <?php if ($notupload = $errors->first('notupload')){
    ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $notupload; ?>
    </div>
        <?php
    }
        if ($upload = $errors->first('notupload')){
    ?>
    <div class="alert alert-success" role="alert">
        <?php echo $upload; ?>
    </div>
    <?php } ?>
                         
					      
            <!-- Begin: Content -->
            <section id="content" class="pn">
            
                <!-- <div class="p40 bg-background bg-topbar bg-psuedo-tp"> -->
                <div class="pv30 ph40 bg-light dark br-b br-grey posr">
    
                    <div class="table-layout">
                        <div class="w200 text-center pr30">
                        <!--<?php //echo config("app.site_url"); ?>/editprofilesubmit-->
                        <form method="post" enctype="multipart/form-data"  action="/editprofilesubmit">
                        <input type = "hidden" name = "_token" value = "<?php echo csrf_token() ?>">
                        <?php
                        if(isset($uploadfilepath)){
                            ?>
                            <img src="<?php echo $uploadfilepath; ?>" class="responsive profile">
                            <?php
                        }
                        else{
                          ?>  <img src="<?php echo config('app.site_url'); ?>/uploads/profiles/avatar5.png" class="responsive profile">
                       <?php } ?>
                           <!--
                            <input type="hidden" id="user_id" name="user_id" class="form-control input-sm" value="<?php //echo isset($userdata['user_id']) ? $userdata['user_id'] : ''; ?>">
						-->
                            <input type="file" name="profile_photo" id="profile_photo" />
                          <input type="submit" name="upload" id="upload" value="Upload" class="btn btn-primary" >
                            </form>
                        </div>
                        <div class="va-t m30">

                            <h2 class=""> <?php 
                            
                            echo isset($userdata['firstname']) ? $userdata['firstname'] : ''; ?> <?php echo isset($userdata['lastname']) ? $userdata['lastname'] : ''; ?> <small> Profile </small></h2>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book
                            <?php echo 'userID'.showuserid();?>
                            </p>
                           
                          

                        </div>
                    </div>
                </div>

                <div class="p25 pt35">
                    <div class="row">
                        <div class="col-md-4">

                            <h4 class="page-header mtn br-light text-muted hidden">User Info</h4>

                            <div class="panel">
                                <div class="panel-heading">
                                    <span class="panel-icon"><i class="fa fa-star"></i>
                                    </span>
                                    <span class="panel-title"> User Info</span>
                                </div>
                                <div class="panel-body pn">
                                    <table class="table mbn tc-icon-1 tc-med-2 tc-bold-last">
                                        <thead>
                                            <tr class="hidden">
                                                <th class="mw30">#</th>
                                                <th>First Name</th>
                                                <th>Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <span class="fa fa-envelope text-warning"></span>
                                                </td>
                                                <td>Email</td>
                                                <td ><?php echo showname();?></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="fa fa-tasks text-primary"></span>
                                                </td>
                                                <td>Role</td>
                                                <td> <?php echo $userdata['role_name']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="fa fa-briefcase text-info"></span>
                                                </td>
                                                <td>Designation</td>
                                                <td>
                                                    <?php echo isset($userdata['designation_name']) ? $userdata['designation_name'] : ''; ?></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="fa fa-building text-info"></span>
                                                </td>
                                                <td>Department</td>
                                                <td ><?php echo isset($userdata['department_name']) ? $userdata['department_name'] : ''; ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="panel">
                                <!--<div class="panel-heading">
                                    <span class="panel-icon"><i class="fa fa-trophy"></i>
                                    </span>
                                    <span class="panel-title"> My Skills</span>
                                </div>
                                <div class="panel-body pb5">
                                    <span class="label label-warning mr5 mb10 ib lh15">Default</span>
                                    <span class="label label-primary mr5 mb10 ib lh15">Primary</span>
                                    <span class="label label-info mr5 mb10 ib lh15">Success</span>
                                    <span class="label label-success mr5 mb10 ib lh15">Info</span>
                                    <span class="label label-alert mr5 mb10 ib lh15">Warning</span>
                                    <span class="label label-system mr5 mb10 ib lh15">Danger</span>
                                    <span class="label label-info mr5 mb10 ib lh15">Success</span>
                                    <span class="label label-success mr5 mb10 ib lh15">Ui Design</span>
                                    <span class="label label-primary mr5 mb10 ib lh15">Primary</span>

                                </div>-->
                            </div>

                            <div class="panel">
                                <!--<div class="panel-heading">
                                    <span class="panel-icon"><i class="fa fa-pencil"></i>
                                    </span>
                                    <span class="panel-title">About Me</span>
                                </div>
                                <div class="panel-body pb5">
                                    <h6 class="text-muted fs13">Experience</h6>

                                    <h4>Facebook Internship</h4>
                                    <p class="text-muted"> University of Missouri, Columbia
                                        <br> Student Health Center, June 2010 - 2012
                                    </p>

                                    <hr class="short br-lighter">

                                    <h6 class="text-muted fs13">Education</h6>

                                    <h4>Bachelor of Science, PhD</h4>
                                    <p class="text-muted"> University of Missouri, Columbia
                                        <br> Student Health Center, June 2010 through Aug 2011
                                    </p>

                                    <hr class="short br-lighter">

                                    <h6 class="text-muted fs13">Accomplishments</h6>

                                    <h4>Successful Business</h4>
                                    <p class="text-muted pb10"> University of Missouri, Columbia
                                        <br> Student Health Center, June 2010 through Aug 2011
                                    </p>

                                </div>-->
                            </div>

                        </div>
                        <div class="col-md-8">

                            <h4 class="page-header text-muted mtn br-light hidden">User Activity</h4>

                            <div class="admin-form hidden">
                                <div class="panel mb30">
                                    <label class="field prepend-icon">
                                        <textarea class="gui-textarea br-light h-60" id="comment" name="comment" placeholder="Text area"></textarea>
                                        <label for="comment" class="field-icon"><i class="fa fa-comments"></i>
                                        </label>
                                        <span class="input-footer hidden">
                                            <strong>Hint:</strong>Don't be negative or off topic! just be awesome...</span>
                                    </label>
                                    <div class="panel-footer text-right br-t-n p8">
                                        <button type="button" class="btn btn-primary p4 ph10">Comment</button>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-block psor">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab1" data-toggle="tab">Profile</a>
                                    </li>
                                    <li>
                                        <a href="#tab2" data-toggle="tab">Change Password</a>
                                    </li>
                                   
                                 
                                </ul>
                                <div class="tab-content" style="height: 725px;">
                                    <div id="tab1" class="tab-pane active p15">
                                    <div class="form-group required col-md-8 ">
						<label for="firstname" class="col-md-4 control-label">First name</label>
						<div class="col-md-8">
                            <input type="text" id="firstname" placeholder="First Name" name="firstname" class="form-control input-sm" value="<?php echo isset($userdata['firstname']) ? $userdata['firstname'] : ''; ?>">
						</div>
                    </div>
                    <div class="form-group required col-md-8 ">
						<label for="lastname" class="col-md-4 control-label">Last name</label>
						<div class="col-md-8">
                            <input type="text" id="lastname" placeholder="Last Name" name="lastname" class="form-control input-sm" value="<?php echo isset($userdata['lastname']) ? $userdata['lastname'] : ''; ?>">
						</div>
                    </div>
                    <div class="form-group required col-md-8 ">
						<label for="contactno" class="col-md-4 control-label">Contact</label>
						<div class="col-md-8">
                            <input type="text" id="contactno" placeholder="contact" name="contactno" class="form-control input-sm" value="<?php echo isset($userdata['contactno']) ? $userdata['contactno'] : ''; ?>">
						</div>
					</div>
                                    </div>
                                  
                                   
                                    <div id="tab2" class="tab-pane"> 
                                    <div class="col-md-12">
            <div class="alert hidden alert-dismissable" id="msg_div"></div>
            </div>
                   <form enctype="multipart/form-data">
                                    <div class="form-group required col-md-8 ">
						<label for="lasttname" class="col-md-4 control-label">Old Password</label>
						<div class="col-md-8">
                            <input type="password" id="oldpassword" placeholder="Old Password" name="oldpassword" class="form-control input-sm" value="">
                            <input type="hidden" id="user_id" name="user_id" class="form-control input-sm" value="<?php echo isset($userdata['user_id']) ? $userdata['user_id'] : ''; ?>">
						</div>
                    </div>
                    <div class="col-md-12">
                        <div class="alert hidden alert-dismissable" id="msg_popup_password"></div>
                        </div>
                    <div class="form-group required col-md-8 ">
						<label for="lasttname" class="col-md-4 control-label">New Password</label>
						<div class="col-md-8">
                            <input type="password" id="password" disabled placeholder="New Password" name="password" class="form-control input-sm" value="">
						</div>
                    </div>
                    <div class="form-group required col-md-8 ">
						<label for="lasttname" class="col-md-4 control-label">Confirm Password</label>
						<div class="col-md-8">
                            <input type="password" id="password_confirmation"disabled  placeholder="Confirm Password" name="password_confirmation" class="form-control input-sm" value="">
						</div>
                    </div>
                    <div class="form-group required col-md-8 ">
						<label for="" class="col-md-4"></label>
						<div class="col-md-8">
                        <button id="updatepassword" type="button" class="btn btn-success btn-block">Update</button>
                           
						</div>
                    </div>
                    </form>
                                    </div>
                                    <div id="tab3" class="tab-pane"> Tab3</div>
                                    <div id="tab4" class="tab-pane"> Tab4</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </section>
            <!-- End: Content -->

        </section>
    </div>


<body>
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/common.js?v=<?php echo time();?>"></script> 
<script language="javascript" type="text/javascript" src="<?php echo config('app.site_url'); ?>/enlight/scripts/admin/users.js"></script>

