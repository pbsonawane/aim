<?php
include_once 'eCos/eCos.php';

//eCos Variables define
define("CONTAINER_NAME", "nijisphp");
define("UPLOAD_PIC", "accidentImages");

$file_name = "esds_holiday_2020.png";
// code to download doc from eCos
$ecos = new eCos();
$ecos->getToken();

$container_name = CONTAINER_NAME;
$folder_name 	= UPLOAD_PIC;

$downloadObject_resp = $ecos->downloadObject($container_name, $folder_name, $file_name);
// eof code to download doc from eCos

//echo $downloadObject_resp; die();

// get file content type
$finfo = finfo_open();
$mime_type = finfo_buffer($finfo, $downloadObject_resp, FILEINFO_MIME_TYPE);

/*if(strlen($downloadObject_resp) > 0)
{
	$profileArr = array(
			"name" => $file_name,
			"type" => $mime_type,
			"data" => base64_encode($downloadObject_resp)
		);
}
print_r($profileArr);
echo "die"; die();*/


header("Content-Type: " . $mime_type);
header('Content-Disposition: filename="'.$file_name.'"');	// set file name
//header('Content-Disposition: attachment; filename="'.$file_name.'"');	// force file download

echo $downloadObject_resp; die();

?>