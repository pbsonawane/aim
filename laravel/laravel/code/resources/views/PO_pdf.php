<html>
<head>
  <style>
    /*@page { margin: 80px 50px; }*/
/*  @page { margin: 70px 0px 110px 0px; }*/
@page {
  /*margin: 100px 0px;*/
  margin: 100px 0px 70px 0px;
}
.page-break {
  page-break-after: always;
}

#header { 
  position: fixed; 
  left: 0px; 
  top: -70px; 
  right: 0px; 
  height: 0px;

}
#header img{
  width: 770px;
  margin: 0;
  height: 100px;
}
#footer img{
  width: 630px;
  margin-left: 60px;
  /*height: 100px;*/
}
.tremconditions th{
  text-align: left;
}
.tremconditions tbody{
  font-size: 8px;
}
#footer { 
  position: fixed; 
  left: 0px;
  bottom: 0px;
  right: 0px;
  height: 0px;
}
#footer_right_img { 
  position: fixed;     
  bottom: 80px;
  left: 692px;
}
#footer_right_img img{ 
  width: 100px;
  opacity: 0.5;
}
body{
  background: url('po_header/center_img.png');
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-position: center;
  background-size: 400px; 
  margin-top: 2cm;
  margin-left: 1cm;
  margin-right: 0cm;
  margin-bottom: 2cm;
  font-family: 'Source Sans Pro', 'Helvetica Neue', Helvetica, Arial, sans-serif;
}

.font10{
  font-size: 10px;
}
.font12{
  font-size: 12px;
}
.font14 {
  font-size: 15px;
}
p{
  /*text-indent: 50px;*/
  font-size: 15px;
  text-align: justify;
}
.text-center{
  text-align: center;
}
.text-right{
  text-align: right;
}
.content{
  margin: -50px 30px 0px 30px;

  /*margin: 30px 30px;*/
}
.content1{
  margin: -50px 10px 0px 0px;

  /*margin: 30px 30px;*/
}
.content table{
  width: 100%;
}
table {
  border-collapse: collapse;
  border-spacing: 0;
}
.table-bordered , td ,th{
  border: 1px solid #000;
}
.table td,.table th{
  font-size: 11px;
  padding: 2px;
}
.tremconditions.table td,.tremconditions.table th{
  font-size: 10.5px;
  padding: 2px;
}
.bordertopbtm td {
  border-top: 0px;
  border-bottom: 0px;

}

.borderbtm td {
  border-bottom: 0px;

}
.borderleft  {

  border-left: 0px !important;
}
.borderright  {

  border-right: 0px;
}
.table {
  width: 100%;
  max-width: 100%;

}

#footer .page:after { content: counter(page, upper-roman); }
.bold-text{
  font-weight: bold;
}
.padding{
 padding: 10px !important;
}
.pdf_table td:last-child {
  border: none !important;
}
</style>
</head>
<body>
  <div id="header">
    <img src="po_header/header.png">
    
  </div>
  <div id="footer_right_img">
    <img src="po_header/footer_right.png">
    
  </div>
  
  <div id="footer">
    <img src="po_header/footer.png">    

  </div>
  <div class="content">
    <table class="table borderbtm">
      <tbody>
        <tr>
          <td rowspan="5" width="60%" class="text-center"><h2>Purchase Order</h2>
            <?php 
            if($content['records'][0]['status'] =='pending approval'){?>
              <span style="color:#f00">(Approval Pending)</span>
              <?php }?></td>
              <td class="bold-text">PO No.</td><td ><?php echo $content['records'][0]['po_no'];?></td>
            </tr>

            <tr>
              <td class="bold-text">Date</td><td ><?php echo date('d-M-Y',strtotime($content['records'][0]['created_at']));?></td>
            </tr>
            <tr>
              <td class="bold-text">Requirement</td><td ><?php echo !empty($content['records'][0]['details']['pr_requirement'])?$content['records'][0]['details']['pr_requirement']:'';?></td>
            </tr>
            <tr>
              <td class="bold-text">Quotation Ref.No :</td><td >
                <?php 
                $items_array = $assetdetails_resp['content'];
                if(!empty($items_array)){
                  $qId ='';
                  foreach($items_array as $values){
                    $gst = json_decode($values['vendor_approval'],true);
                    if(!empty($gst['quotation_reference_no'])){
                      $qId = $gst['quotation_reference_no'];              
                    }
                  }
                  echo $qId;
                }
                ?>
              </td>
            </tr>
            <tr>
              <td class="bold-text">Category</td><td ><?php echo !empty($content['records'][0]['details']['pr_category'])?$content['records'][0]['details']['pr_category']:'';?></td>

            </tr>
          </tbody>
        </table>
        <table class="table borderbtm" >
          <tbody>
            <tr>
              <td ><span class="bold-text">To:</span> <br><?php echo $content['records'][0]['vendor_details']['vendor_name'];?>
              <br><?php echo $content['records'][0]['vendor_details']['address'];?></td>
              <td ><span class="bold-text">Bill To:</span>
                <br><?php echo $content['records'][0]['billto_details']['company_name'];?>
                <br><?php echo $content['records'][0]['billto_details']['address'];?>

              </td>
              <td ><span class="bold-text">Ship To:</span>
                <?php  if(!empty($content['records'][0]['details']['ship_to_other'])){
                  echo '<br>'.$content['records'][0]['details']['ship_to_other'];
                }else{?>
                  <br><?php echo $content['records'][0]['shipto_details']['company_name'];?>
                  <br><?php echo $content['records'][0]['shipto_details']['address'];?>
                <?php } ?>
              </td>
            </tr>

            <tr>
              <td>
                <table class="bordertopbtm">
                  <tr>
                    <td width="20%" class="borderleft bold-text">Contact</td>
                    <td width="80%" class="borderright"><?php echo $content['records'][0]['vendor_details']['contact_person'];?></td>
                  </tr>
                </table>
              </td>
              <td>
                <table class="bordertopbtm" >
                  <tr>
                    <td width="20%" class="borderleft bold-text">Contact</td>
                    <td  width="80%"  class="borderright"><?php echo $content['records'][0]['billto_contact_details']['prefix'];?>.<?php echo $content['records'][0]['billto_contact_details']['fname'];?> <?php echo $content['records'][0]['billto_contact_details']['lname'];?></td>
                  </tr>
                </table>
              </td>
              <td>
                <table class="bordertopbtm" >
                  <tr>
                    <td width="20%" class="borderleft bold-text">Contact</td>
                    <td width="80%" class="borderright">
                      <?php
                      if(!empty($content['records'][0]['details']['ship_to_contact_other'])){
                        echo $content['records'][0]['details']['ship_to_contact_other'];
                      }else{
                        echo $content['records'][0]['shipto_contact_details']['prefix'];?>.<?php echo $content['records'][0]['shipto_contact_details']['fname'];?> <?php echo $content['records'][0]['shipto_contact_details']['lname'];
                      }
                      ?>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>

            <tr>
              <td>
                <table class="bordertopbtm">
                  <tr>
                    <td width="20%" class="borderleft bold-text">Email</td>
                    <td width="80%" class="borderright"><?php echo $content['records'][0]['vendor_details']['vendor_email'];?></td>
                  </tr>
                </table>
              </td>
              <td>
                <table class="bordertopbtm" >
                  <tr>
                    <td width="20%" class="borderleft bold-text">Email</td>
                    <td  width="80%"  class="borderright"><?php echo $content['records'][0]['billto_contact_details']['email'];?></td>
                  </tr>
                </table>
              </td>
              <td>
                <table class="bordertopbtm" >
                  <tr>
                    <td width="20%" class="borderleft bold-text">Email</td>
                    <td width="80%" class="borderright"><?php echo $content['records'][0]['shipto_contact_details']['email'];?></td>
                  </tr>
                </table>
              </td>
            </tr>

            <tr>
              <td>
                <table class="bordertopbtm">
                  <tr>
                    <td width="20%" class="borderleft bold-text">Cell</td>
                    <td width="80%" class="borderright"><?php echo $content['records'][0]['vendor_details']['contactno'];?></td>
                  </tr>
                </table>
              </td>
              <td>
                <table class="bordertopbtm" >
                  <tr>
                    <td width="20%" class="borderleft bold-text">Cell</td>
                    <td  width="80%"  class="borderright"><?php echo $content['records'][0]['billto_contact_details']['contact1'],' / ',$content['records'][0]['billto_contact_details']['contact2'];?></td>
                  </tr>
                </table>
              </td>
              <td>
                <table class="bordertopbtm" >
                  <tr>
                    <td width="20%" class="borderleft bold-text">Cell</td>
                    <td width="80%" class="borderright"><?php echo $content['records'][0]['shipto_contact_details']['contact1'],' / ',$content['records'][0]['shipto_contact_details']['contact2'];?></td>
                  </tr>
                </table>
              </td>
            </tr>

            <tr>
              <td>
                <table class="bordertopbtm">
                  <tr>
                    <td width="20%" class="borderleft bold-text">GSTIN</td>
                    <td width="80%" class="borderright"><?php echo $content['records'][0]['vendor_details']['vendor_gst_no'];?></td>
                  </tr>
                </table>
              </td>
              <td>
                <table class="bordertopbtm" >
                  <tr>
                    <td width="20%" class="borderleft bold-text">GSTIN</td>
                    <td width="80%"  class="borderright"><?php echo $content['records'][0]['billto_details']['gstn'];?></td>
                  </tr>
                </table>
              </td>
              <td>
                <table class="bordertopbtm" >
                  <tr>
                    <td width="20%" class="borderleft bold-text">GSTIN</td>
                    <td width="80%" class="borderright"><?php echo $content['records'][0]['shipto_details']['gstn'];?></td>
                  </tr>
                </table>
              </td>
            </tr>

            <tr>
              <td>
                <table class="bordertopbtm">
                  <tr>
                    <td width="20%" class="borderleft bold-text">PAN</td>
                    <td width="80%" class="borderright"><?php echo $content['records'][0]['vendor_details']['vendor_pan'];?></td>
                  </tr>
                </table>
              </td>
              <td>
                <table class="bordertopbtm" >
                  <tr>
                    <td width="20%" class="borderleft bold-text">PAN</td>
                    <td width="80%"  class="borderright"><?php echo $content['records'][0]['billto_details']['pan_no'];?></td>
                  </tr>
                </table>
              </td>
              <td>
                <table class="bordertopbtm" >
                  <tr>
                    <td width="20%" class="borderleft bold-text">PAN</td>
                    <td width="80%" class="borderright"><?php echo $content['records'][0]['shipto_details']['pan_no'];?></td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td colspan="3" class="padding">Dear Sir/Madam,<br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;With reference to your offer, subsequent negotiations and referred quote, we are pleased to place the Purchase Order for the following;
              </td>
            </tr>
          </tbody>
        </table>

        <table class="table" border="1">
          <tbody>
            <tr>
              <th>Sr No</th>
              <th>Description</th>
              <th>Qty</th>
              <th>Unit</th>
              <th>Rate</th>
              <th>Tax As Applicable If any</th>
              <th>Amount (Rs.)</th>
            </tr>
            <?php
            $addresses =[];
            $items_array = $assetdetails_resp['content'];
            if(!empty($items_array)){

              $i = 1;
              $total = 0;
              foreach($items_array as $values){
                $items = json_decode($values['asset_details']);
                $gst = json_decode($values['vendor_approval'],true);
                $gst_extra = 'NA';
                if(!empty($gst['gst_extra']))
                {
                  if (strpos($gst['gst_extra'], 'GST') !== false) {
                    $tax = explode('_',$gst['gst_extra']);

                    $gst_extra = 'GST Extra';
                  }else{
                    $gst_extra = $gst['gst_extra'];

                  }
                }
                
                if(!empty($items->addresses)){
                  $addresses[$items->item_product_name] = $items->addresses; 
                }
            // $total += 22 * $items->item_qty; 
                $total += $items->item_estimated_cost * $items->item_qty;
                $gross_total = number_format(($items->item_estimated_cost * $items->item_qty),2);
                ?>
                <tr>
                  <td class="text-center"><?php echo $i++;?></td>
                  <td><?php echo $items->item_product_name;?><br><?php echo $items->item_desc;?></td>
                  <td class="text-center"><?php echo $items->item_qty;?></td>
                  
                  <td class="text-center"><?php  echo empty($ItemFinalUnitSku[$items->asset_sku]) 
                  ? 'NA' : $ItemFinalUnitSku[$items->asset_sku];?></td>

                  <td class="text-center"><?php echo number_format($items->item_estimated_cost,2,'.','');?></td>
                  <td class="text-center"><?php echo $gst_extra;?></td>
                  <td class="text-center"><?php echo $gross_total;?></td>
                </tr>
              <?php }}else{?>
                <tr><td colspan="7">Data not found..</td></tr>
              <?php }?>
             <tr>
                <td class="text-right bold-text">In Words</td>
                <td colspan="4" class="bold-text"><?php echo displaywords($total);?>&nbsp;Only.</td>
                <td colspan="1" class="text-right bold-text">Total</td>
                <td class="text-center bold-text"><?php echo number_format($total,2,'.','');?> </td>
              </tr>
              <tr>
                <td colspan="7">&nbsp;</td>
              </tr>
            </tbody>
          </table>

          <table class="table table-bordered">
            <tbody>
              <tr><th class="text-center" colspan="2">Terms & Conditions</th></tr>
              <tr>
                <td width="30%" class="bold-text">Delivery</td>
                <td width="70%"><?php echo $content['records'][0]['pr_delivery'];?></td>
              </tr>
              <tr>
                <td class="bold-text">Delivery Terms</td>
                <td><?php echo $content['records'][0]['details']['pr_delivery_terms'];?></td>
              </tr>
              <tr>
                <td class="bold-text">Payment Terms</td>
                <td><?php echo $content['records'][0]['pr_payment_terms'];?></td>
              </tr>
              <tr>
                <td class="bold-text">Taxes</td>
                <td><?php echo $content['records'][0]['details']['pr_taxes'];?></td>
              </tr>
              <tr>
                <td class="bold-text">Support</td>
                <td><?php echo $content['records'][0]['details']['pr_warranty_support'];?></td>
              </tr>
              <tr>
                <td class="bold-text">Special Terms</td>
                <td><?php echo $content['records'][0]['details']['pr_special_terms'];?></td>
              </tr>
              <tr>
                <td width="30%"><b>ESDS GST No :</b> 27AABCE4981A1ZV</td>
                <td width="70%"><b>ESDS PAN No :</b> AABCE4981A</td>
              </tr>
              <tr>
                <td colspan="2">
                  <br>1. No deviation without approval shall be accepted.<br>
                  2. "All disputes arising from this purchase order shall be subject to the exclusive jurisdiction of courts in Nashik. Any prior understanding in this regard hereby stands revoked."<br>
                  3. Refer Standard ESDS Terms: PTO<br>
                  4. PO Terms & Conditions to be convey on email.
                  </br><br></br><br><br>
                  <img src="po_header/RN_Sign_Stamp.jpg" style="width: 20%; height: auto;">
                  <br><br>
                  <br>Thanks and Regards,<br>
                  Authorised Signatory,<br><br><br>
                  <span class="bold-text">&nbsp;&nbsp;For ESDS Software Solution Limited </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="text-right bold-text">Acceptance by Supplier</span>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="page-break"></div>
          <div class="content1">
            <table class="table table-bordered tremconditions">
              <tbody>
                <tr>

                  <th colspan="2"><h3>Standard Terms & Conditions</h3></th>
                </tr>
               <!--  <tr>
                  <th colspan="2">PO ACCEPTANCE</th>
                </tr> -->
                <tr>
                  <td class="text-center">1]</td>
                  <td >The Supplier is bound to immediately return an order confirmation / acceptance (through email 
                    and Counter signing the PO copy) within three working days and to notify the exact delivery date 
                  to ESDS. </td>
                </tr>
                <tr>
                  <td class="text-center">2]</td>
                  <td>By accepting ESDS order or by supplying the goods ordered in pursuance of the same, the Supplier 
                  is deemed to have accepted the present conditions</td>
                </tr>
                <tr>
                  <td class="text-center">3]</td>
                  <td>Only orders in writing and traceable form (by Letter, Telefax, and E-Mail) are binding on ESDS. Verbal 
                    orders or orders through phone as well as changes/amendments/deviation , if any, to ESDS order/s 
                    shall be binding on ESDS only if the same are confirmed, if any, by ESDS in writing prior to effecting 
                    any such changes/amendments/deviation, if any. Terms at variance with ESDS General Purchase 
                    Conditions and additional terms, including reservations regarding price or exchange rates, as well 
                    as, in particular, deviating General Conditions of Sale and Delivery of the Supplier shall be valid 
                  only if accepted by ESDS in writing prior to effecting any such deviation, if any.</td>
                </tr>
                <tr>
                  <td class="text-center">4]</td>
                  <td>The Supplier shall be liable for all costs incurred by ESDS, if any, as a consequence of the failure of 
                  the Supplier to observe ESDS instructions or due to faulty, deficient or not validly agreed deliveries. </td>
                </tr>
                <tr>
                  <td class="text-center">5]</td>
                  <td>ESDS reserves the right to cancel a PO, in case of non-acceptance of any of the terms and conditions 
                  by the supplier without any liability on the part of ESDS.</td>
                </tr>
                <tr>
                  <th>GST</th>
                  <td></td>
                </tr>
                <tr>
                  <td></td>
                  <td>GST tax invoice and delivery challan / way bill / e-way bill documents are to be submitted 
                  immediately after dispatch of the goods to ESDS billing / shipping address respectively. </td>
                </tr>
                <tr>
                  <th colspan="2">Payment</th>

                </tr>
                <tr>
                  <td class="text-center">1]</td>
                  <td>Payment shall be made as per agreed terms, from the date of acceptance.</td>
                </tr>
                <tr>
                  <td class="text-center">2]</td>
                  <td>The supplier shall not receive payment of GST amount component till the Credit of the tax so paid 
                    is not reflected in ESDS Form GSTR-2. An amount to the particular extent till GST payable shall be 
                  retained till the said amount is not reflected in ESDS respective returns.</td>
                </tr>
                <tr>
                  <td class="text-center">3]</td>
                  <td>By making payments or conducting pre-shipment tests, ESDS are not waiving our legal remedies for 
                  faulty, deficient, and invalid deliveries which are not in accordance of agreed terms.</td>
                </tr>
                <tr>
                  <th colspan="2">Delivery</th>

                </tr>
                <tr>
                  <td class="text-center">1]</td>
                  <td>The time of delivery shall be of an essence of contract. The time of delivery is met, when the goods 
                    have arrived at our specified destination/s. In no event the delay the reason/s of which are 
                    unreasonable and/ invalid under the circumstances shall be entertained by ESDS. Foreseeable 
                    delays hindering the timely delivery in whole or in part shall be notified immediately through Notice 
                    in writing specifying the valid reasons and the estimated duration of the delay, if any. The 
                    acceptance or rejection of reasonableness and/validity of any such reasons mentioned in any such 
                    Notice shall be at the sole discretion of ESDS. Such Notice shall without prejudice to ESDS remedies 
                    at law (e.g. partial or total termination or rescission of the particular order without any liability on 
                  the part of ESDS). </td>
                </tr>
                <tr>
                  <td class="text-center">2]</td>
                  <td>The physical risk in the goods ordered shall pass on to ESDS upon physical inspection on arrival at 
                    the place of ship to address and upon due acceptance by ESDS, or, if an acceptance test is agreed 
                    for particular transaction, upon successful completion of said test/s and due acceptance by ESDS 
                    after such tests and not otherwise. ESDS stamp from the Security at ESDS on delivery challan / 
                    Invoice shall not be considered as deemed acceptance in any circumstances and same shall only be 
                  construed as material receipt and not otherwise.</td>
                </tr>
                <tr>
                  <td class="text-center">3]</td>
                  <td>Part shipments and advance deliveries require our prior consent.</td>
                </tr>
                <tr>
                  <th colspan="2">Packing</th>

                </tr>
                <tr>
                  <td></td>
                  <td>The Supplier has to arrange for appropriate, suitable and acceptable packing to the material as may 
                    be required for the particular material to be procured by ESDS as per industry standards and as per 
                    specific directions, if any, given by ESDS in this regard at his own costs and shall be liable if the 
                  goods are damaged on transport due to faulty packing.</td>
                </tr>
                <tr>
                  <th>Rejection</th>
                  <td>If any deviation is observed/foreseen or if there are any issues in the material technically / services 
                    quality wise, the material / services shall be subject to immediate rejection without any liability on 
                  the part of ESDS.</td>
                </tr>
                <tr>
                  <th colspan="2">Warranty</th>

                </tr>
                <tr>
                  <td></td>
                  <td >The warranty for replacement and repairs shall be the same as agreed for the original delivery; the 
                    warranty period starts from the date of successful installation and written acceptance by ESDS to 
                    that effect and not otherwise. The warranty period for replaced parts shall start running anew. This 
                  shall equally apply to replaced parts and components.</td>

                </tr>
                <tr>
                  <th colspan="2">Disputes</th>
                </tr>
                <tr>
                  <td></td>
                  <td >All disputes arising out of any of the PO’s shall be subject to an exclusive jurisdiction of Courts at Nashik.
                  </td>
                </tr>
                <tr>
                  <th colspan="2">Confidentiality</th>

                </tr>
                <tr>
                  <td></td>
                  <td>The Supplier shall treat the order and all related deliveries as confidential. </td>

                </tr>          
              </tbody>

            </table>
            <?php if (!empty($addresses)) {?>
              <table class="table" border="1" id="pdf_table"> 
                <tbody>
                  <tr>

                    <th colspan="3"><h3>Annexure</h3></th>
                  </tr>
                  <tr>
                   <th>Sr.No.</th>
                   <th>Product</th>
                   <th>
                    <table>
                      <tr>
                        <th width="15%" style="border: 0px;border-right: 1px">Quantity</th>
                        <th width="85%" style="border: 0px;">Delivery Location</th>
                      </tr>
                    </table>
                  </th>
                </tr>
                <?php 

                $i=1;
                foreach($addresses as $k =>$v)
                {
                  ?>
                  <tr>
                    <th><?php echo $i++;?></th>
                    <td><?php echo $k;?></td>


                    <td style="border-bottom: 0px;">
                      <table class="" style="border-bottom: 0px;">
                        <?php foreach ($v as $value) {  ?>

                         <tbody>
                          <tr class="pdf_table">
                            <td width="15%" class="text-center borderleft" style="border-top: none; border-spacing: 0px;border-collapse: collapse;border-bottom: : 1px dotted #444; "><?php echo $value->qty; ?></td>
                            <td width="85%" class="borderright" style="border-top: none; border-bottom: : 1px dotted #444;"><?php echo $value->location; ?></td>
                          </tr>
                        </tbody>
                        <?php 
                      }?>

                    </table>
                  </td>


                </tr>
              <?php }?>  

            </tbody>
          </table>
        <?php }?>


      </div>


    </body>
    </html>
