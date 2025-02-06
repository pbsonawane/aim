<style>
  .dropdown button {
    padding: 3px 10px !important;
    background: #fff !important;
    border-radius: 0 !important;
    font-size: 16px !important;
    box-shadow: none !important;
    margin-top: 15px;
    border: none;
}
.dropdown button::before {
    display: block;
    content: "\f129";
    font-family: "FontAwesome";
    transform-origin: top center;
}
.dropdown button::before, .dropdown button::after {
    color: #f60 !important;
    text-shadow: none !important;
}
.dropdown button::after {
    font-family: Arial;
    font-size: 0.7em;
    font-weight: 700;
    position: absolute;
    top: -15px;
    right: -15px;
    padding: 5px 8px;
    line-height: 100%;
    border: 2px #fff solid;
    border-radius: 60px;
    background: #3498db;
    opacity: 0;
    content: attr(data-count);
    opacity: 0;
    transform: scale(0.5);
    transition: transform, opacity;
    transition-duration: 0.3s;
    transition-timing-function: ease-out;
}
    


.dropbtn:hover, .dropbtn:focus {
  background-color: #2980B9;
}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f1f1f1;
  min-width: 175px;
  overflow: auto;
  
  z-index: 1;
  right: 0;
  border-bottom: 2px solid #4349ac;
    background-color: #f9f9ff;
}

.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}


.show {display: block;}
              
</style>
<?php

 $notificationdata=user_notification();

 //dd($notificationdata);


?>

<header class="navbar bg-info navbar-fixed-top">
    <ul class="nav navbar-nav navbar-left">
        <li> <img src="<?php echo config('enconfig.iamapp_url'); ?>/showlogo" style="height:50px; margin-top:5px; margin-left:10px;"/> </li>
    </ul>
    
    <?php echo view('emmegamenu');	?>
    <!--<ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle fw600 p15" data-toggle="dropdown">
                <span> {{ trans('locale.'. App::getLocale()) }}</span>
                <span class="caret caret-tp"></span> 
            </a>
            <ul class="dropdown-menu dropdown-persist pn w250 bg-white" role="menu">
                @foreach (Config::get('app.languages') as $language)
                @if ($language != App::getLocale())
                <li  class="br-t of-h">
                    <a href="{{ route('langroute', $language) }}" class="fw600 p12 animated animated-short fadeInDown">
                    {{ trans('locale.'. $language) }}
                    </a>
                </li>
                @endif
                @endforeach
            </ul>
        </li>
    </ul>-->
    <ul class="nav navbar-nav navbar-right">
  
    <li class="dropdown">
  <button onclick="user_notification()" class="dropbtn"></button>
  <div id="myDropdown" class="dropdown-content">
  @if(canuser('advance','notification_pr') && $notificationdata['prrequestcount']>0)
    <a href="#home"><?php echo "PR Requested (".$notificationdata['prrequestcount'].")"; ?></a>
  @endif
  
  @if(canuser('advance','notification_cpr') && $notificationdata['coverttopr']>0)
    <a href="#home"><?php echo "Converted to PR (".$notificationdata['coverttopr'].")"; ?></a>
  @endif

  @if(canuser('advance','notification_apr') && $notificationdata['assigntopr']>0)
    <a href="#home"><?php echo "Assign to PR (".$notificationdata['assigntopr'].")"; ?></a>
  @endif

  @if(canuser('advance','notification_qg') && $notificationdata['quotationgenerated']>0)
    <a href="#home"><?php echo "Quotation Generated (".$notificationdata['quotationgenerated'].")"; ?></a>
  @endif  

  @if(canuser('advance','notification_qa') && $notificationdata['quotationapproved']>0)
    <a href="#home"><?php echo "Quotation Approved (".$notificationdata['quotationapproved'].")"; ?></a>
   @endif

   @if(canuser('advance','notification_qr') && $notificationdata['quotationrejected']>0) 
    <a href="#home"><?php echo "Quotation Rejected (".$notificationdata['quotationrejected'].")"; ?></a> 
    @endif

    @if(canuser('advance','notification_cpo') && $notificationdata['converttopo']>0) 
    <a href="#home"><?php echo "Converted to PO (".$notificationdata['converttopo'].")"; ?></a>  
    @endif
</div>
</li>
           
       
        <li class=" dropdown ccursor">
            <a  class="dropdown-rep-notifications dropdown-toggle fw600 p15" data-toggle="dropdown">
                <div class="notification"></div>
                <style>
                    #dropdown-rep-notifications{
                        max-height: 225px;
                        overflow: auto;
                    }
                    #dropdown-rep-notifications li:first-child{
                        position: sticky;
                        top: 0;
                        background-color:#fff;
                        z-index: 15;
                    }
                  /* Notifications */

                .notification {
                    display: inline-block;
                    position: relative;
                    padding: 0.6em;
                    background: #3498db;
                    border-radius: 0.2em;
                    font-size: 1.3em;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
                }

                .notification::before, 
                .notification::after {
                    color: #fff;
                    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
                }

                .notification::before {
                    display: block;
                    content: "\f0f3";
                    font-family: "FontAwesome";
                    transform-origin: top center;
                }

                .notification::after {
                    font-family: Arial;
                    font-size: 0.7em;
                    font-weight: 700;
                    position: absolute;
                    top: -15px;
                    right: -15px;
                    padding: 5px 8px;
                    line-height: 100%;
                    border: 2px #fff solid;
                    border-radius: 60px;
                    background: #3498db;
                    opacity: 0;
                    content: attr(data-count);
                    opacity: 0;
                    transform: scale(0.5);
                    transition: transform, opacity;
                    transition-duration: 0.3s;
                    transition-timing-function: ease-out;
                }

                .notification.notify::before {
                    animation: ring 1.5s ease;
                }

                .notification.show-count::after {
                    transform: scale(1);
                    opacity: 1;
                }

                @keyframes ring {
                    0% {
                        transform: rotate(35deg);
                    }
                    12.5% {
                        transform: rotate(-30deg);
                    }
                    25% {
                        transform: rotate(25deg);
                    }
                    37.5% {
                        transform: rotate(-20deg);
                    }
                    50% {
                        transform: rotate(15deg);
                    }
                    62.5% {
                        transform: rotate(-10deg);
                    }
                    75% {
                        transform: rotate(5deg);
                    }
                    100% {
                        transform: rotate(0deg);
                    }
                }
                </style>
            </a>
            <ul id="dropdown-rep-notifications" class="dropdown-menu pn w350 bg-white" role="menu">
                <li class="br-t of-h"> <a href="#" class="fw600 p12 animated animated-short fadeInDown"> <span class="fa fa-gear pr5"></span> <?php echo trans('label.lbl_notifications');?></a> </li>     
            </ul>
        </li>
        <!-- <li class=" dropdown ccursor">
            <a  class="dropdown-notifications dropdown-toggle fw600 p15" data-toggle="dropdown">
                <i class="glyphicons glyphicons-bell" title="Notifications"></i>
            </a>
            <ul id="dropdown-notifications" class="dropdown-menu pn w350 bg-white" role="menu">
                <li class="br-t of-h"> <a href="#" class="fw600 p12 animated animated-short fadeInDown"> <span class="fa fa-gear pr5"></span> Notifications</a> </li>	
            </ul>
        </li> -->
        <li class="dropdown"> 
            <a href="#" class="dropdown-toggle fw600 p15" data-toggle="dropdown"> <img src="<?php echo config("enconfig.iamapp_url") ?>/viewprofile"  width="30px" height="30px" alt="avatar" class="mw30 br64 mr15">
            <span> <?php echo showuserfullname(); ?> </span> <span class="caret caret-tp"></span> 
            </a>
            <ul class="dropdown-menu dropdown-persist pn w250 bg-white" role="menu">
                <!-- <li class="br-t of-h"> <a href="<?php //echo config('enconfig.iamapp_url');?>userprofile" class="fw600 p12 animated animated-short fadeInDown"> <span class="fa fa-gear pr5"></span> Account Settings</a> </li> -->
                <!--<li class="br-t of-h"> <a onclick="updatelicense();" class="fw600 p12 animated animated-short fadeInDown"> <span class="fa fa-certificate pr5"></span>Update License</a></li>
                <li class="br-t of-h"> <a onclick="productinfo();" class="fw600 p12 animated animated-short fadeInDown"> <span class="fa fa-info-circle pr5"></span>Product Info</a> 
                </li>-->
                <li class="br-t of-h"> <a href="https://www.esds.co.in/contactus" class="fw600 p12 animated animated-short fadeInDown" target="_blank"> <span class="fa fa-headphones pr5"></span>Contact CRM</a> 
                </li>
                <li class="br-t of-h"> <a href="https://docs.enlightcloud.com/" class="fw600 p12 animated animated-short fadeInDown" target="_blank"> <span class="fa fa-book pr5"></span>User Guide</a>
                </li>
				<li class="br-t of-h"> <a href="<?php echo config('enconfig.iamapp_url');?>" class="fw600 p12 animated animated-short fadeInDown"> <span class="fa fa-gear pr5"></span> Goto IAM</a> </li>
                <li class="br-t of-h"> <a href="/logout" class="fw600 p12 animated animated-short fadeInDown"> <span class="fa fa-power-off pr5"></span> Logout </a> </li>

            <!--    <li class="br-t of-h"> <a href="/userprofile" class="fw600 p12 animated animated-short fadeInDown"> <span class="fa fa-gear pr5"></span>Account Settings</a>
                </li> -->
                              
              
                </li>
            </ul>
        </li>
    </ul>
</header>

<script>
/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
function user_notification() {
  document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
</script>
 