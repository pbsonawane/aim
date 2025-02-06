<div class="wsmenucontainer"> 
    <!-- Mobile Header -->
    <div class="wsmobileheader clearfix"> 
        <a id="wsnavtoggle" class="wsanimated-arrow"><span></span></a>
        <span class="smllogo">
        <img src="<?php echo config("app.site_url") ?>/showlogo"  style="height:40px; margin-top:3px;" alt="AIM - Asset Inventory Manager"/>
    </span> 
    </div>
    <!-- Mobile Header -->
    <div class="wsmenu clearfix">
        <ul class="wsmenu-list">
            <?php
            $menu = array();
            $menu = trans('enmenu.menu');
            $usersids = Session::get('user_id');
            // Replacing config array with multilingual messages array config('enmenu.menu');
            if (is_array($menu) && count($menu) > 0 )//&& session()->has('accessrights'))
            {
                //$permissions = Session::get('accessrights');
                // foreach($menu as $mnu_module => $mnu_details)
                // {
                    
                    $mnu_links = isset($menu['itam']['links']) ? $menu['itam']['links'] : array();
                if (is_array($mnu_links) && count($mnu_links) > 0)
                {
                    foreach($mnu_links as $sub_links)
                    {
                    $sublinks = isset($sub_links['sublinks']) ? $sub_links['sublinks'] : array();
            ?>
            <li aria-haspopup="true"><span class="wsmenu-click"><i class="wsmenu-arrow fa fa-angle-down"></i></span><a href="#" class="navtext"><span><font>&nbsp;</font></span> 
            <span><?php echo $sub_links['title'];?></span></a>
                <div class="wsmegamenu clearfix">
                    <div class="container-fluid">
                        <div class="row">                        
                                    <?php
                                        if (is_array($sublinks) && count($sublinks) > 0)
                                        {
                                            $cur=0;
                                            foreach($sublinks as $link)
                                            {
                                                $pbi_usr_id = array('d4f1ef18-0ae3-11ec-beda-4e89be533080','4fdd3e2e-fdf7-11ec-8955-5ea741a655e9','9ab9c0a8-3f19-11ed-96bd-5ea741a655e9','04ca1fe0-3f17-11ed-96e2-5ea741a655e9','a58c0822-3f16-11ed-84d8-5ea741a655e9');
                                                // if (check_accessrights($link['key'])) 
                                                // { 
                                                    if($cur == 0)
                                                    {
                                                        echo '<div class="col-md-3 col-md-12">
                                                                <ul class="wstliststy02 clearfix">';
                                                    }  
                                                    if($link['title'] == "Export PBI Reports")      
                                                    {
                                                        if(in_array(trim($usersids), $pbi_usr_id))
                                                        {
                                                        ?>

                                                    <li><i class="<?php echo isset($link['icon']) ? $link['icon'] : '#';?>"></i> 
                                                        <a href="<?php echo isset($link['link']) ? $link['link'] : '#';?>"><?php echo $link['title'];?>
                                                        </a>
                                                    </li>
                                                    <?php
                                                       
                                                       }
                                                       continue; 
                                                    }
                                        ?> 
                                                    <li><i class="<?php echo isset($link['icon']) ? $link['icon'] : '#';?>"></i> 
                                                        <a href="<?php echo isset($link['link']) ? $link['link'] : '#';?>"><?php echo $link['title'];?>
                                                        </a>
                                                    </li>
                                        <?php
                                                    if($cur == 4)
                                                    {
                                                        echo '</ul></div>';
                                                        $cur = 0;
                                                    }
                                                    else
                                                    {
                                                        $cur++;
                                                    }
                                           // }
                                            } //foreach($sublinks as $link)
                                        } //if (is_array($sublinks) && count($sublinks) > 0)
                                    ?>                                      
                        </div>
                    </div>
                </div>
            </li>
            <?php
                                } //foreach($mnu_links as $sub_links)
                            } //if (is_array($mnu_links) && count($mnu_links) > 0)
                    //} // foreach($menu as $mnu_module => $mnu_details)
                } // if (is_array($menu) && count($menu) > 0)
            ?>
        </ul>
    </div>
</div>
<script>
$(window).load(function() {
   $(".wsmenu-list li").each(function()
    {
        if(!$(this).has(".wstliststy02").length)
        {
            $(this).css('display','none');
        }
    });
    $('.wstliststy02 li').css('display','block');
});
</script>
