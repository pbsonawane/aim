<html>
<head>
  <style>
  @page { margin: 70px 0px 110px 0px; }
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
  #header img, #footer img{
    width: 790px;
    margin: 0;
    height: 100px;
  }
  #footer { 
    position: fixed; 
    left: 0px;
    bottom: 0px;
    right: 0px;
    height: 0px;
  }

  body{
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
    margin: 30px 30px;
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
    font-size: 10px;
    padding: 2px;
  }
  .bordertopbtm td {
    border-top: 0px;
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
  
</style>
</head>
<body>
  <div id="header">
    <img src="po_header/header.png">
    
  </div>
  
  <div class="content">
    <table class="table" border="1">
      <tbody>
        <tr>
          <td rowspan="5" width="60%" class="text-center"><h2>Purchase Order</h2></td>
          <td >PO No.</td><td ><?php echo $content['records'][0]['po_no'];?></td>
        </tr>
        
        <tr>
          <td >Date</td><td ><?php echo date('d-M-Y',strtotime($content['records'][0]['created_at']));?></td>
        </tr>
        <tr>
          <td >Requirement</td><td ></td>
        </tr>
        <tr>
          <td >Quotation Ref.No :</td><td ></td>
        </tr>
        <tr>
          <td >Category</td><td ></td>
          
        </tr>
      </tbody>
    </table>
    <table class="table ">
      <tbody>
        <tr>
          <td >To, <br><?php echo $content['records'][0]['vendor_details']['vendor_name'];?>
            <br><?php echo $content['records'][0]['vendor_details']['address'];?></td>
          <td >Bill To:
            <br><?php echo $content['records'][0]['billto_details']['company_name'];?>
            <br><?php echo $content['records'][0]['billto_details']['address'];?>

          </td>
          <td >Ship To:
            <br><?php echo $content['records'][0]['shipto_details']['company_name'];?>
            <br><?php echo $content['records'][0]['shipto_details']['address'];?>
          </td>
        </tr>

        <tr>
          <td>
            <table class="bordertopbtm">
              <tr>
                <td width="20%" class="borderleft">Contact</td>
                <td width="80%" class="borderright"><?php echo $content['records'][0]['vendor_details']['contact_person'];?></td>
              </tr>
            </table>
          </td>
          <td>
            <table class="bordertopbtm" >
              <tr>
                <td width="20%" class="borderleft">Contact</td>
                <td  width="80%"  class="borderright"><?php echo $content['records'][0]['billto_contact_details']['prefix'];?>.<?php echo $content['records'][0]['billto_contact_details']['fname'];?> <?php echo $content['records'][0]['billto_contact_details']['lname'];?></td>
              </tr>
            </table>
          </td>
          <td>
            <table class="bordertopbtm" >
              <tr>
                <td width="20%" class="borderleft">Contact</td>
                <td width="80%" class="borderright"><?php echo $content['records'][0]['shipto_contact_details']['prefix'];?>.<?php echo $content['records'][0]['shipto_contact_details']['fname'];?> <?php echo $content['records'][0]['shipto_contact_details']['lname'];?></td>
              </tr>
            </table>
          </td>
        </tr>

        <tr>
          <td>
            <table class="bordertopbtm">
              <tr>
                <td width="20%" class="borderleft">Email</td>
                <td width="80%" class="borderright"><?php echo $content['records'][0]['billto_contact_details']['email'];?></td>
              </tr>
            </table>
          </td>
          <td>
            <table class="bordertopbtm" >
              <tr>
                <td width="20%" class="borderleft">Email</td>
                <td  width="80%"  class="borderright"><?php echo $content['records'][0]['billto_contact_details']['email'];?></td>
              </tr>
            </table>
          </td>
          <td>
            <table class="bordertopbtm" >
              <tr>
                <td width="20%" class="borderleft">Email</td>
                <td width="80%" class="borderright"><?php echo $content['records'][0]['shipto_contact_details']['email'];?></td>
              </tr>
            </table>
          </td>
        </tr>

        <tr>
          <td>
            <table class="bordertopbtm">
              <tr>
                <td width="20%" class="borderleft">Cell</td>
                <td width="80%" class="borderright"><?php echo $content['records'][0]['billto_contact_details']['contact1'],' / ',$content['records'][0]['billto_contact_details']['contact2'];?></td>
              </tr>
            </table>
          </td>
          <td>
            <table class="bordertopbtm" >
              <tr>
                <td width="20%" class="borderleft">Cell</td>
                <td  width="80%"  class="borderright"><?php echo $content['records'][0]['billto_contact_details']['contact1'],' / ',$content['records'][0]['billto_contact_details']['contact2'];?></td>
              </tr>
            </table>
          </td>
          <td>
            <table class="bordertopbtm" >
              <tr>
                <td width="20%" class="borderleft">Cell</td>
                <td width="80%" class="borderright"><?php echo $content['records'][0]['shipto_contact_details']['contact1'],' / ',$content['records'][0]['shipto_contact_details']['contact2'];?></td>
              </tr>
            </table>
          </td>
        </tr>

        <tr>
          <td>
            <table class="bordertopbtm">
              <tr>
                <td width="20%" class="borderleft">GSTIN</td>
                <td width="80%" class="borderright"><?php echo $content['records'][0]['billto_details']['gstn'];?></td>
              </tr>
            </table>
          </td>
          <td>
            <table class="bordertopbtm" >
              <tr>
                <td width="20%" class="borderleft">GSTIN</td>
                <td  width="80%"  class="borderright"><?php echo $content['records'][0]['billto_details']['gstn'];?></td>
              </tr>
            </table>
          </td>
          <td>
            <table class="bordertopbtm" >
              <tr>
                <td width="20%" class="borderleft">GSTIN</td>
                <td width="80%" class="borderright"><?php echo $content['records'][0]['shipto_details']['gstn'];?></td>
              </tr>
            </table>
          </td>
        </tr>

        <tr>
          <td>
            <table class="bordertopbtm">
              <tr>
                <td width="20%" class="borderleft">PAN</td>
                <td width="80%" class="borderright"><?php echo $content['records'][0]['billto_details']['pan_no'];?></td>
              </tr>
            </table>
          </td>
          <td>
            <table class="bordertopbtm" >
              <tr>
                <td width="20%" class="borderleft">PAN</td>
                <td  width="80%"  class="borderright"><?php echo $content['records'][0]['billto_details']['pan_no'];?></td>
              </tr>
            </table>
          </td>
          <td>
            <table class="bordertopbtm" >
              <tr>
                <td width="20%" class="borderleft">PAN</td>
                <td width="80%" class="borderright"><?php echo $content['records'][0]['shipto_details']['pan_no'];?></td>
              </tr>
            </table>
          </td>
        </tr>

       

        <!-- <tr>
          <td >Contact: <?php echo $content['records'][0]['vendor_details']['contact_person'];?></td>
          <td >Contact:
            <?php echo $content['records'][0]['billto_contact_details']['prefix'];?>.<?php echo $content['records'][0]['billto_contact_details']['fname'];?> <?php echo $content['records'][0]['billto_contact_details']['lname'];?></td>
          <td >Contact: <?php echo $content['records'][0]['shipto_contact_details']['prefix'];?>.<?php echo $content['records'][0]['shipto_contact_details']['fname'];?> <?php echo $content['records'][0]['shipto_contact_details']['lname'];?></td>
          
        </tr>
        <tr>
          <td >Email:<?php echo $content['records'][0]['billto_contact_details']['email'];?></td>
          <td >Email:<?php echo $content['records'][0]['billto_contact_details']['email'];?></td>
          <td >Email:<?php echo $content['records'][0]['shipto_contact_details']['email'];?></td>
          
          
        </tr>
        <tr>
          <td >Cell:<?php echo $content['records'][0]['billto_contact_details']['contact1'],' / ',$content['records'][0]['billto_contact_details']['contact2'];?></td>
          <td >Cell:<?php echo $content['records'][0]['billto_contact_details']['contact1'],' / ',$content['records'][0]['billto_contact_details']['contact1'];?></td>
          <td >Cell:<?php echo $content['records'][0]['shipto_contact_details']['contact1'],' / ',$content['records'][0]['shipto_contact_details']['contact1'];?></td>
          
        </tr>
        <tr>
          <td >GSTIN:<?php echo $content['records'][0]['shipto_details']['gstn'];?></td>
          <td >GSTIN:<?php echo $content['records'][0]['billto_details']['gstn'];?></td>
          <td >GSTIN:<?php echo $content['records'][0]['shipto_details']['gstn'];?></td>
          
        </tr>
        <tr>
          <td >PAN:<?php echo $content['records'][0]['shipto_details']['pan_no'];?></td>
          <td >PAN:<?php echo $content['records'][0]['billto_details']['pan_no'];?></td>
          <td >PAN:<?php echo $content['records'][0]['shipto_details']['pan_no'];?></td>
          
        </tr> -->

        <tr>
          <td colspan="3">Dear Sir/Madam,<br> With reference to your offer, subsequent negotiations and referred quote, we are pleased to place the Purchase Order for the following;</td>
          
        </tr>
        
      </tbody>
    </table>
    <table class="table" border="1">
      <tbody>
        <tr>
          <th>Sr No.</th>
          <th>Description</th>
          <th>Qty</th>
          <th>Unit</th>
          <th>Rate</th>
          <th>ax Applicable If any</th>
          <th> Amount (Rs.)</th>
        </tr>
        <tr>
          <td>Sr No.</td>
          <td>Description</td>
          <td>Qty</td>
          <td>Unit</td>
          <td>Rate</td>
          <td>ax Applicable If any</td>
          <td> Amount (Rs.)</td>
        </tr>
        <tr>
          <td>Sr No.</td>
          <td>Description</td>
          <td>Qty</td>
          <td>Unit</td>
          <td>Rate</td>
          <td>ax Applicable If any</td>
          <td> Amount (Rs.)</td>
        </tr>
        <tr>
          <td>Inwords</td>
          <td colspan="3">Rupees:</td>
          <td colspan="2" class="text-right">Total:</td>
          <td> - </td>
        </tr>
      </tbody>
    </table>
    <table class="table table-bordered">
      <tbody>
        <tr><td>&nbsp;</td></tr>
      </tbody>
    </table>
    <table class="table table-bordered">
      <tbody>
        <tr><td colspan="2">Terms & Conditions</td></tr>
        <tr>
          <td>Delivery:</td>
          <td></td>
        </tr>
        <tr>
          <td>Delivery Terms:</td>
          <td><?php echo $content['records'][0]['details']['pr_delivery_terms'];?></td>
        </tr>
        <tr>
          <td>Payment Terms:</td>
          <td></td>
        </tr>
        <tr>
          <td>Taxes:</td>
          <td><?php echo $content['records'][0]['details']['pr_taxes'];?></td>
        </tr>
        <tr>
          <td>Support:</td>
          <td><?php echo $content['records'][0]['details']['pr_warranty_support'];?></td>
        </tr>
        <tr>
          <td>Special Terms:</td>
          <td><?php echo $content['records'][0]['details']['pr_special_terms'];?></td>
        </tr>
        <tr>
          <td >ESDS GST No:</td>
          <td >ESDS PAN No : </td>
        </tr>
        <tr>
          <td colspan="2">
            <br>
           1) No deviation without approval shall be accepted.<br>
             2) "All disputes arising from this purchase order shall be subject to the exclusive jurisdiction of courts in Nashik. Any prior understanding in this regard hereby 
stands revoked." <br>
             3) Refer Standard ESDS Terms: PTO <br></br>

             Thanks and Regards, <br>
Authorised Signatory,<br><br><br><br><br><br><br><br>

<span>&nbsp;&nbsp;For ESDS Software Solution Pvt. Ltd. </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="text-right">Acceptance by Supplier</span>

            
          </td>
          
        </tr>
      </tbody>
    </table>
    <div class="page-break"></div>

  </div>
  <div id="footer">
    <img src="po_header/footer.png">    
    
  </div>
</body>
</html>