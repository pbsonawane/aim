<?php
echo phpinfo();
ini_set('allow_url_fopen', 'On');
$file_name = "esds_holiday_2020.png";

$encoded_file = file_get_contents($file_name);

header("Content-Type: image/png");
header('Content-Disposition: filename="'.$file_name.'"');	// set file name

echo $encoded_file; die();

?>