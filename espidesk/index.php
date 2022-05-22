<?php
    // Include config file
    if(file_exists('config/db.php')) {
        $conf = include('config/db.php');
    } else {
        header( 'Location: install.php' );
    }
    
    // Initialize the session
    session_start();

    // Check if the user is already logged in, if yes then redirect him to welcome page
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        header("location: dashboard.php");
        exit;
    }   
    
    // Define variables and initialize with empty values
    $username = $password = "";
    $username_err = $password_err = $login_err = "";

    //database information from the DB config file
    $dbhost = $conf['dbhost'];
    $dbuser = $conf['dbuser'];
    $dbpass = $conf['dbpass'];
    $dbname = $conf['dbname'];
    $link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    if(!$link){
        die("Failed to establish connection");
    } else {
    ?>
      <!doctype html>
        <html lang="en">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>EspiDesk Login</title>
            <link rel="stylesheet" href="assets/css/variable.css?v=1.0">
            <link rel="stylesheet" href="assets/css/general.css?v=1.0">
            <link rel="stylesheet" href="assets/css/installer.css?v=1.0">
        </head>
        <body>
            <h1>Espi Desk Login</h1>
            <form action="" method="post">
            <label for="user_name">Username:</label><input type="text" name="user_name" id="user_name" placeholder="Username">
            <label for="user_pass">Password:</label><input type="password" name="user_pass" id="user_pass" placeholder="Password">
            <input type="submit" value="Login">
            </form>
        </body>
        </html>
    <?php
    }

?>