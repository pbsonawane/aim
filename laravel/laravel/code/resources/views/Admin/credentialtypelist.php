
<link rel="stylesheet" type="text/css" href="enlight/scripts/formeo-master/css/demo.css">

<div class="row" id="credentialTypeform">
    <div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div>
    
    <div class="col-md-12" id="cr_types">
        <div class="panel panel-visible">
            <div class="panel-body">          
                <?php 
                    if($cr_types)
                    {
                        foreach($cr_types as $key=>$cr)
                        {
                ?>      
                            <div class="col-md-3 ccursor crtype" id="<?php echo $cr['template_name'];?>">
                                <div class="panel panel-info panel-border top">
                                    <div class="panel-heading">
                                        <span class="panel-title"><?php echo $cr['template_name'];?></span>
                                        <div class="widget-menu pull-right">
                                            <!--<code class="mr10 p3 ph5">.panel.panel-border.top</code>-->
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <p class="p2"><?php echo $cr['description'];?></p>
                                    </div>
                                </div>
                            </div> 
                            <?php   
                                $cnt = $key+1;
                                if($cnt%4==0)
                                {
                                    echo '<div class="clearfix"></div>';                     
                                }
                               
                        }
                }
                else{
                    echo "No records.";
                } 
                ?>      
                <!--</form>-->
            </div><!--panel-body -->
        </div><!--panel panel-visible-->
  </div><!--col-md-12-->
</div> <!-- row-->
