<?php 
    $data = $_POST;
    if(isset($_FILES['fileToUpload'])){
        // we have an image, get the image data
        $data['fileToUpload'] = base64_encode(file_get_contents($_FILES['fileToUpload']['tmp_name']));
    }
    $json_data = json_encode($data);
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,"http://10.10.99.2:8081/editprofilesubmit/d3b6b17a-1580-11e9-bb43-0242ac110004");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/json',
        'Accept: application/json',                                                                            
        'Content-Length: ' . strlen($json_data))                                                                       
    ); 

    $api_result = curl_exec ($ch);
    // $api_result 'success' if successful!

    curl_close ($ch);
 ?>