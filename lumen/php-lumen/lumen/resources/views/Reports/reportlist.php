<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <title><?php echo isset($report_title) ? $report_title : ''; ?></title>
 <style type="text/css" media="all">

  @page{bleed:1cm;size:A4 portrait;size:auto;margin-left:0;margin-bottom:15px;margin-top:15px;}
  
  html{background-color:#fff;margin:0}

  body{margin:10mm 15mm 10mm 15mm}

  div.container{border-radius:15px;background:#fff;box-shadow:0 10px 10px rgba(0,0,0,.2)}

  div.invoice-letter{width:auto;position:relative;min-height:150px;background-color:#04617b;margin-right:-48px;margin-left:-48px;box-shadow:0 4px 3px rgba(0,0,0,.4)}

  /*div.letter-title{margin-top:10px;height:130px;border-right:2px solid #eee}*/

  div.letter-content{margin-top:10px}

  table.invoice thead th{background-color:rgba(4,97,123,.2);border-top:none;page-break-after: always;border: 0.5px solid rgba(4,97,123,.1);
    border-collapse: collapse;}

  /*table.invoice thead tr:first-child th:first-child{border-top-left-radius:25px}table.invoice thead tr:first-child th:last-child{border-top-right-radius:25px}*/

  tr.last-row{background-color:rgba(4,97,123,.2)}

  /*tr.last-row th{border-bottom-left-radius:25px;width:30px}tr.last-row td{border-bottom-right-radius:25px}*//*div.row div.to{height:260px;padding-right:25px;border-right:2px solid rgba(4,97,123,.2)}*/

  tr:nth-child(even){background-color:#eaeaed}

  body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";font-size:1rem;font-weight:400;line-height:1.5;color:#212529;text-align:left;background-color:#fff}
  
  .container-fluid{width:100%;padding-right:15px;padding-left:15px;margin-right:auto;margin-left:auto}
  
  /**,::after,::before{box-sizing:border-box}*/

  .row{display:-webkit-box;display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;margin-right:-15px;margin-left:-15px}

  .col-5{-webkit-box-flex:0;-ms-flex:0 0 41.666667%;flex:0 0 41.666667%;max-width:41.666667%}

  .h1,.h2,.h3,.h4,.h5,.h6,h1,h2,h3,h4,h5,h6{margin-bottom:.5rem;font-family:inherit;font-weight:500;line-height:1.2;color:inherit}

  .col-2{-webkit-box-flex:0;-ms-flex:0 0 16.666667%;flex:0 0 16.666667%;max-width:16.666667%}

  .h5,h5{font-size:1.25rem}
  .h6,h6{font-size:1rem}

  table{border-collapse:separate;border-spacing:0;width:100%}td,th{padding:6px 15px}

  .mainrow{border-top: 3px solid #3399CC !important;border-bottom: 3px solid #3399CC !important;margin: 2%;padding: 2%;}

  .logo{margin: 3% 0px 0px 10%;}

  .repname{font-size:22px;color:#3399CC !important;font-style:italic;}

  .font18{font-size:14px;}

  .bordtop{border-top: 3px solid #3399CC !important;}

  .mar-btm-5{margin-bottom:5%;}

  .bg-none{background: transparent !important;}

  .bordbtm{border-bottom: 3px solid #3399CC !important;}

  .text-right{text-align:right !important;}

  .text-left{text-align:left !important;}

  .mar-0{margin:0px !important;} 

  .mar-top-5{margin-top:5%;}

  .tdcenter{text-align: center;vertical-align: middle;}
  /*.bordered{border-top:none;page-break-after: always;border: 0.5px solid rgba(4,97,123,.1);
    border-collapse: collapse;}*/
 </style>
 <style>
 <?php
 $tableheaders = isset($tableheaders) ? $tableheaders : array();
 $reportsdata  = isset($reportsdata) ? $reportsdata : array();
 if (is_array($tableheaders) && count($tableheaders) > 9)
 {
 ?>
    @page { size: 40cm 100cm landscape !important; }
    .mar-top-5{margin-top:1% !important;}
    .mar-btm-5{margin-bottom:1% !important;}
    .logo{margin: 3% 0px 3% 10%;}
<?php
  }
?>
</style>
</head>
<body>
  <div class="container-fluid">
  <table class="invoice mar-btm-5">
    <tr class="bg-none">
      <td class="">
        <?php
          $url = config('enconfig.iamapp_url')."/showlogo";
        ?>
        <img width="125px" height="100px" class="logo" src="<?php echo $url;?>" />
      </td>
      <td class="align-right">
        <span class="repname"><?php echo isset($report_title) ? $report_title : ''; ?></span>
      </td>
    </tr>
    <tr class="bg-none">
      <td class="bordtop"></td>
      <td class="bordtop"></td>
    </tr>
    <tr class="bg-none">
      <td class="">
        <span class="font18"><?php echo trans('label.lbl_report_name'); ?>   :</span><span class="font18"> <?php echo isset($report_title) ? $report_title : ''; ?></span>
        <br>
        <span class="font18"><?php echo trans('label.lbl_genrated_at'); ?>   :</span><span class="font18"> <?php echo date("F j, Y, g:i a"); ?></span>
        <br>
        <span class="font18"><?php echo trans('label.lbl_totalrecords'); ?>  :</span><span class="font18"> <?php echo isset($total_rows) ? $total_rows : ''; ?></span>
        <br>      
      </td>
      <td class="">
        <?php 
        if (isset($from_time) && $from_time !="") 
        {
        ?>
        <span class="font18"><?php echo trans('label.lbl_from_time'); ?>  :</span><span class="font18"> <?php echo date("F j, Y, g:i", strtotime($from_time));?></span>
        <br>
        <?php
        }?>
        <?php 
        if (isset($to_time) && $to_time !="") 
        {
        ?>
        <span class="font18"><?php echo trans('label.lbl_to_time'); ?>  :</span><span class="font18"> <?php echo date("F j, Y, g:i", strtotime($to_time));?></span>
        <br>
        <?php
        }?>
      </td>
    </tr>
    <tr class="bg-none">
      <td class="bordbtm"></td>
      <td class="bordbtm"></td>
    </tr>
  </table>
  <div class="row table mt-5">
    <table class="invoice table table-hover mar-top-5">
      <thead class="thead">
          <tr>
          <?php
          $tableheadersdata = $tableheaders;
          if (is_array($tableheadersdata) && count($tableheadersdata) > 0)
          {
            echo"<th>".trans('label.lbl_srno')."</th>";
            foreach($tableheadersdata as $tableheader)
            {
            ?>
          <th class="text-left"><?php echo $tableheader;?></th>
          <?php
            }
          }
          ?>
        </tr>
      </thead>
      <tbody>
      <?php
      if (is_array($reportsdata) && count($reportsdata) > 0)
      {
        foreach($reportsdata as $i => $reports)
        {   
      ?>
      <tr>
        <td class="tdcenter bordered"><?php echo $i + 1;?></td>
         <?php
        if (is_array($reports) && count($reports) > 0)
        {
          foreach($reports as $i => $report)
          {   
        ?>
        <td class="text-left bordered"><?php echo wordwrap($report,50,"<br>\n",true);?></td>
        <?php }?>
      </tr>
      <?php
        }
        }
        }
      else
        echo '<tr><td colspan="100" align="center"> '.trans('messages.msg_norecordfound').'</td></tr>';
      ?>  
      </tbody>
      </table>
    </div>
  </div>
</body>
</html>