 <?php  
$report_type_name = isset($report_type_name) ? $report_type_name : 'report';
 //excel.php  
 header('Content-Type: application/vnd.ms-excel');  
 header('Content-disposition: attachment; filename='.$report_type_name.'_'.rand().'.xls');  
$counter = false;
  foreach($datas as $row) {
    if(!$counter) {
      echo implode("\t", array_keys($row)) . "\n";
      $counter = true;
    }
    echo implode("\t", array_values($row)) . "\n";
  }
 
 ?> 