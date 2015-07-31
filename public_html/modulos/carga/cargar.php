<?php
$ds     = DIRECTORY_SEPARATOR;  //1
 
$storeFolder = 'uploads';   //2

extract($_REQUEST);
 
if (!empty($_FILES)) {
     
    $tempFile = $_FILES['file']['tmp_name'];          //3             
      
    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4
    $ruta = dirname( __FILE__ ).'/' ;
     
    $targetFile =  $ruta. $_FILES['file']['name'];  //5
 
    move_uploaded_file($tempFile,$targetFile); //6
    
    echo $nombre;
     
}
?>