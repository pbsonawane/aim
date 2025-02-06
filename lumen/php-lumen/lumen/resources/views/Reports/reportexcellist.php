<table>
  <thead>
    <tr>
      <?php
      $tableheadersdata = $tableheaders;
      if (is_array($tableheadersdata) && count($tableheadersdata) > 0)
      {
        foreach($tableheadersdata as $tableheader)
        {   
          $tableheader = strip_tags($tableheader);
          $tableheader = preg_replace("/&#?[a-z0-9]{2,8};/i","",$tableheader); 
         
      ?>
     <th><?php echo $tableheader;?></th>
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
         <?php
        if (is_array($reports) && count($reports) > 0)
        {
          foreach($reports as $i => $report)
          {  
            $report = strip_tags($report);
            $report = preg_replace("/&#?[a-z0-9]{2,8};/i","",$report ); 
        ?>
        <td><?php echo $report;?></td>
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