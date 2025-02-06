<div>
  <table class="table table-striped table-bordered table-hover">
    <thead>
      
      <?php
      $tableheadersdata = $columns;
      if (is_array($tableheadersdata) && count($tableheadersdata) > 0)
      {
        echo '<tr><th class="text-center">Sr.No</th>';
        foreach($tableheadersdata as $tableheader)
        {   
      ?>
     <th><?php echo $tableheader;?></th>
      <?php
        }
      }
      echo "</tr>";
      ?>
    </thead>
    <tbody>
      <?php
      $reportsdata = $dbdata;
      if (is_array($reportsdata) && count($reportsdata) > 0)
      {
        foreach($reportsdata as $i => $reports)
        {   
      ?>
      <tr>
        <td class="text-center"><?php echo $i + $offset + 1?></td>
         <?php
        if (is_array($reports) && count($reports) > 0)
        {
          foreach($reports as $i => $report)
          {   
        ?>
        <td><?php echo wordwrap($report,50,"<br>\n",true);?></td>
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