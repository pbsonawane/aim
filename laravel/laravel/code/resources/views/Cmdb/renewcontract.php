
<link rel="stylesheet" type="text/css" href="<?php echo config('app.site_url'); ?>/bootstrap/assets/admin-tools/admin-forms/css/admin-forms.css">
<div class="row">
    <div class="col-md-10">
        <!--<div class="alert hidden alert-dismissable" id="msg_popup"></div>-->
        <div class="hidden alert-dismissable" id="msg_popup"></div>
    </div>
    <div class="col-md-12">
        <div class="panel">
            <div class="panel-body">
               
                <form class="form-horizontal"  name="addformcontract" id="addformcontract">
                    <input id="contract_id" name="contract_id" type="hidden" value="<?php echo $contract_id?>">
                    <input id="contract_details_id" name="contract_details_id" type="hidden" value="<?php if(isset($contractdata[0]['contract_details_id'])) echo $contractdata[0]['contract_details_id'];?>">
                    
                    <div class="row">
                    <div class="col-sm-6">
                    <div class="form-group required ">
                                <label for="inputStandard" class="col-md-3 control-label">Contract Name</label>
                                <div class="col-md-6">
                                    <input type="text" id="contract_name" name="contract_name" class="form-control input-sm" value="<?php if(isset($contractdata[0]['contract_name'])) echo $contractdata[0]['contract_name'];?>" >
                                </div>
                        </div>
                      
                        <div class="form-group">
					<label for="Description" class="col-md-3 control-label">Parent Contract2</label>
					<div class="col-md-6">
                  
                        <select class="form-control input-sm" name="parent_contract" id="parent_contract">
								<option value="">-Parent Contract-</option>
				
                                <?php 

				     			if(is_array($parentcontract) && count($parentcontract)>0)
									{
										foreach($parentcontract as $contract)
										{
                                            $cucontractdata_id = isset($contractdata[0]['contract_id']) ? $contractdata[0]['contract_id'] : '';
                                            if($contract['contract_status']=='active'){
								?>
										<option value="<?php echo $contract['contract_id'] ?>" <?php if($cucontractdata_id == $contract['contract_id']){echo "selected";} ?> > <?php echo $contract['contract_name']; ?> </option>
								<?php
                                        }
                                    }
                                    }
                                	
								?>

                        </select>	
         
					</div>
                    </div>
                    <div class="form-group required">
                                    <label for="Description" class="col-md-3 control-label"> ContractID</label>
                                    <div class="col-md-6">
                                    <input type="text" id="contractid" name="contractid" class="form-control input-sm" value="<?php if(isset($contractdata[0]['contractid'])) echo $contractdata[0]['contractid'];?>">
                                </div>
                        </div>
                        <div class="form-group required">
                                    <label for="Description" class="col-md-3 control-label">Description</label>
                                    <div class="col-md-6">
                                        <textarea id="description" name="description" class="form-control input-sm"  ><?php if(isset($contractdata[0]['description'])) echo $contractdata[0]['description'];?></textarea>
                                </div>
                        </div>
                        <div class="form-group required">
                                    <label for="attachment" class="col-md-3 control-label">Attachment</label>
                                    <div class="col-md-6">
                                        <input type="file" id="attachment" name="attachment">
                                </div>
                        </div>         
                    </div>
                    <div class="col-sm-6">
                    <div class="form-group required">
					<label for="Description" class="col-md-3 control-label">Contract Type</label>
					<div class="col-md-6">
						
                        <select class="form-control input-sm" name="contract_type_id" id="contract_type_id">
								<option value="">-Contract Type-</option>
				
								<?php 
									if(is_array($contracttypes) && count($contracttypes)>0)
									{
										foreach($contracttypes as $contracttype)
										{
											$cucontracttypedata_id = isset($contractdata[0]['contract_type_id']) ? $contractdata[0]['contract_type_id'] : '';
								?>
										<option value="<?php echo $contracttype['contract_type_id'] ?>" <?php if($cucontracttypedata_id == $contracttype['contract_type_id']){echo "selected";} ?> > <?php echo $contracttype['contract_type'] ?> </option>
								<?php
										}
									}	
								?>
							</select>		
					</div>
                </div>
                <div class="form-group required">
					<label for="Description" class="col-md-3 control-label">Vendor</label>
					<div class="col-md-6">
						
                        <select class="form-control input-sm" name="vendor_id" id="vendor_id">
								<option value="">-Vendor-</option>
						        <?php 
									if(is_array($vendors) && count($vendors)>0)
									{
										foreach($vendors as $vendor)
										{
											$cuvendordata_id = isset($contractdata[0]['vendor_id']) ? $contractdata[0]['vendor_id'] : '';
								?>
										<option value="<?php echo $vendor['vendor_id'] ?>" <?php if($cuvendordata_id == $vendor['vendor_id']){echo "selected";} ?> > <?php echo $vendor['vendor_name'] ?> </option>
								<?php
										}
									}	
								?>
							</select>	
					</div>
                </div>
                <div class="form-group">
                                    <label for="renewed" class="col-md-3 control-label">Renewed Contract</label>
                                    <div class="col-lg-6">
                                            <div class="checkbox-custom mb5">
                                                <input  type="checkbox"  class="renewed" id="renewed" name="renewed" value="y"
                                              <?php //echo  $contractdata[0]['renewed']=='y' ? 'checked' : '';
                                              if(isset($contractdata[0]['renewed']))
                                              if($contractdata[0]['renewed']=='y') 
                                              $contractdata[0]['renewed']=='y' ? 'checked' : '';
                                              else
                                              echo $contractdata[0]['renewed'];?>
                                              ?>
                                                <label for="renewed"><strong> </strong></label>
                                            </div>
                                        </div>
                                    
                        </div>
                        <div class="form-group required">
                                    <label for="Description" class="col-md-3 control-label">Support</label>
                                    <div class="col-md-6">
                                        <textarea id="support" name="support" class="form-control input-sm" ><?php if(isset($contractdata[0]['support'])) echo $contractdata[0]['support'];?></textarea>
                                </div>
                        </div>
                    </div>
                    </div> 
                       
            <hr>

            <div class="row">
            <div class="col-sm-6">
            <div class="form-group required">        
                        <label class="col-md-3 control-label" for="">Add Asset </label>
                       
                        <div class="col-sm-6"> 
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add Asset</button> 
                        <button type="button" id="removeassets" class="btn btn-warning" ><i class="fa fa-minus"></i> Remove Asset</button> 
                        <table>
                        <tr valign="top">
                        <td>
                        <br/><br/>
                        </td>
                        <td>&nbsp;&nbsp;&nbsp;</td>
                        <td>
                            <br>
                              <?php if(isset($assets_json[0]['asset_id']) && $assets_json[0]['asset_id'] != '')
                                    {
                                        $contractasset = json_decode($assets_json[0]['asset_id'],true);
                                    }
                                    else
                                        $contractasset = [];
                            ?>
                        <SELECT id="asset_id"  name="asset_id[]" size="10" multiple style="width:250px" >
                        <?php 
									if(is_array($assets) && count($assets)>0)
									{
										foreach($assets as $asset)
										{
											$cuvendordata_id = isset($contractdata[0]['asset_id']) ? $contractdata[0]['asset_id'] : '';
								?>
										<option value="<?php echo $asset['asset_id'] ?>" <?php if($cuvendordata_id == $asset['asset_id']){echo "selected";} ?> > <?php echo $asset['asset_id'] ?> </option>
								<?php
										}
									}	
								?>
                        </SELECT>
                        
                        </td>
                   
                        </tr>
                        </table>
                                </div>
                        <!-- Modal -->
                        <div id="myModal" class="modal fade" role="dialog">
                        <div class="modal-dialog modal-lg">

                            <!-- Modal content-->
                            <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Assets</h4>
                            </div>
                            <div class="modal-body">
                            <div>
                <form name=myForm method="post">
            <table class="table table-striped table-bordered table-hover table-responsive ">
	<thead>
	  	<tr>
	  		<th class="checkbox_column">
		  		<div class="checkbox-custom mb5 checkbox-info">
					<input type="checkbox" class="" id="assetCheckAll" value="6">
					<label for="assetCheckAll"></label>
	            </div>
                </th>
            <th>Asset Tag</th> 
            <th>Name</th>    
            <th>Asset Status</th>   
            <th>Status</th>  
		</tr>
	</thead>
	<tbody>
				        <tr>
		        
		            <!--<td class="srno">1</td>-->
		            <?php
                    $offset='0';
                    if (is_array($assets) && count($assets) > 0)
                    {         
                        foreach($assets as $i => $asset)
                        {	
                            $cucontracttypedata_id = isset($assets[0]['bv_id']) ? $assets[0]['bv_id'] : '';	
                  
                 
                   ?>
                    <tr data-val="value">
                
                    <td class="checkbox_column">
		        		<div class="checkbox-custom mb5">
                        <?php 
                        
                        $num = $i + $offset + 1;  ?>
		                    <input type="checkbox" name="asset_id" class="assetChk " id="<?php echo 'assetChk'.$num  ?>" data-asset-tag="<?php echo $asset['asset_tag']; ?>" value="<?php echo $asset['asset_id']?>" data-temp_name="<?php echo $asset['asset_id']?>">
		                    <label for="<?php echo 'assetChk'.$num ; ?>"></label>                       
		                </div>
		            </td>
                        <?php echo $i + $offset + 1?>
                        <td><?php echo $asset['asset_tag']; ?></td>
                        <td name="display_name" id="display_name"><?php echo $asset['display_name']; ?></td>
                        <!--<td>// echo $asset['bv_id'] </td>-->
                        <td><?php echo $asset['asset_status']; ?></td>
                        <td><?php echo $asset['status']; ?></td>
                    </tr>
                    <?php
                        }
                    }
				?>
                </tr>               
    </tbody> 
  
</table>

                </form>
 
        </div>
               </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success"id="checkbox_test"  data-dismiss="modal">Send Data</button>
                            </div>
                            </div>

                        </div>
                        </div>
                                </div>
                              
                        <div class="form-group required">
                                        <label class="col-md-3 control-label" for="datecontractfrom">Active period From </label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control input-sm" name="from_date" id="from_date" value="<?php if(isset($contractdata[0]['from_date'])) echo $contractdata[0]['from_date'];?>">
                                        </div>
                                        <label class="col-md-1 control-label" for="datecontractto">To</label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control input-sm" name="to_date" id="to_date" value="<?php if(isset($contractdata[0]['to_date'])) echo $contractdata[0]['to_date'];?>">
                                        </div>
                                    </div>
                        <div class="form-group required">
                                    <label for="maintenance_cost" class="col-md-3 control-label">Maintenance Cost</label>
                                    <div class="col-md-6">
                                        <input type="text" id="cost" name="cost" class="form-control input-sm" value="<?php if(isset($contractdata[0]['cost'])) echo $contractdata[0]['cost'];?>">
                                </div>
                        </div>             
                        <div class="form-group">
                        <label class="col-md-3 control-label"></label>
                            <div class="col-xs-2">                        
                                <?php if($contract_id != '') {?>
                                <button id="contracteditsubmit" type="button" class="btn btn-success btn-block">Update</button>
                                <?php }else{?>
                                <button id="contractaddsubmit" type="button" class="btn btn-success btn-block">Submit</button>
                                <?php } ?>
                            </div>
                            <div class="col-xs-2">
                                <button id="contract_reset" type="button" class="btn btn-info btn-block">Reset</button>
                            </div>
                    </div>
                                </div>
                                </div>
                </form>
            </div>
        </div>
    </div>
</div> 


