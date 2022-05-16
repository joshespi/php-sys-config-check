<?php
    // Include config file
    if(file_exists('config/db.php')) {
        $conf = include('config/db.php');
    } else {
        header( 'Location: install.php' );
    }
//ob_start();
    
    $dbhost = $conf['dbhost'];
    $dbuser = $conf['dbuser'];
    $dbpass = $conf['dbpass'];
    $dbname = 'espidesk';
    $link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    
    if(!$link){
        
        die("Failed to establish connection");
    } else {
        echo "Connection established successfully"; 
    }
//ob_end_flush();
?>