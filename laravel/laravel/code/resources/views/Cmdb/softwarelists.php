<?php //echo 'DATA1';
//print_r($softwaredata);?>

<?php 

if(is_array($softwaredata) && count($softwaredata) > 0)
{
    foreach($softwaredata as $software)
    {

?>
<div class="col-md-12 pl5-md animated fadeIn">
                       
                       <div class="panel">
                           <div class="panel-body p1">

                                  <div class="softwarelist ccursor" data-id="<?php //echo $softwaredata['0']['software_id'];?>">
                                 <ul class="nav nav-messages p5 clear" role="menu">
                               <li class="">
                                           <strong><?php echo $softwaredata['0']['software_name'];?></strong>

                                       </li>

                                   </ul>
                               </div>
                               <div class="softwarelist" data-id="<?php echo $softwaredata['0']['software_id']; ?>">
                                   <hr class="mn br-light">
                                   <h4 class="ccursor ph10 mv15 floatleft contracttitle" >#<?php echo $softwaredata['0']['software_name'];
//echo $i + $offset + 1;
//echo " - ".$software['software_name']; ?> </h4>
                                   <span class=" mv15 floatright"> <?php echo 'Status';//echo $software['from_date'];  ?></span>
                                   <ul class="nav nav-messages p5 clear" role="menu">
                                       <li class="">
                                           <strong><?php echo 'Installations';//echo trans('label.lbl_contract_id');?> :</strong> <?php echo 'Test';//echo $software['contractid'];  ?>

                                       </li>
                                       <li class="">
                                            <strong><?php echo 'Purchased';//echo trans('label.lbl_contract_type');?> :</strong> <?php echo 'Test';//echo $software['contract_type'];?>

                                       </li>
                                       <li class="">
                                       <strong><?php echo 'Licensed ';//echo trans('label.lbl_vendor');?> :</strong> <?php echo 'Test';//echo $software['vendor_name'];?>

                                       </li>
                                       <?php echo '....';//if($software['contract_status']=='active'){
?><!--
                                               <span class="pull-right lh20 h-20 label label-success label-sm" style="background-color:green;"><?php echo 'Status';//echo ucwords($software['contract_status']); ?></span>
                                               <?php echo '..';//}else{?>
                                                <span class="pull-right lh20 h-20 label label-success label-sm" style="background-color:red;"><?php echo 'Status';//echo ucwords($software['contract_status']); ?></span>
-->
                                              <?php echo '...';//}?>

                                       </li>
                                   </ul>
                               </div>

<?php 

                                               }
                                              }?>
                    