<?php
include_once 'eCos/eCos.php';

//eCos Variables define
define("CONTAINER_NAME", "nijisphp");
define("UPLOAD_PIC", "accidentImages");

ini_set('allow_url_fopen', 'On');

$file_name = "esds_holiday_2020.png";

$encoded_file = file_get_contents($file_name);
$encoded_file = base64_encode($encoded_file);

/*$myfile = fopen("encoded_file.txt", "w") or die("Unable to open file!");
$txt = $encoded_file;
fwrite($myfile, $txt);
fclose($myfile);*/

/*header("Content-Type: image/png");
header('Content-Disposition: filename="'.$file_name.'"');	// set file name*/
//echo $encoded_file; die();

// code to upload doc to eCos
$ecos = new eCos();
$ecos->getToken();

$container_name = CONTAINER_NAME;
$folder_name 	= UPLOAD_PIC;							
//$file_name		= $imgName;
$ImageType		= "image/png";


$encoded_file 	= 'data:'.$ImageType.';base64,'.$encoded_file;
$file_path 		= $encoded_file;

$imageResponse 	= $ecos->uploadObject($container_name, $folder_name, $file_path, $file_name);
// eof code to upload doc to eCos

if($imageResponse == false){
	echo "Picture not uploaded.";
}
else
{
	echo "Picture uploaded with name : " . $file_name;
}

?>