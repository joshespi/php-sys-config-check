<?php
    // Include config file
    if(file_exists('config/db.php')) {
        $conf = include('config/db.php');
        $link = mysqli_connect($conf['dbhost'], $conf['dbuser'], $conf['dbpass'],$conf['dbname']);
    } else {
        header( 'Location: install.php' );
    }
    
    // Initialize the session
    session_start();

     // Define variables and initialize with empty values
     $username = $password = "";
     $username_err = $password_err = $login_err = "";
    
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["user_name"]))){
        $username_err = "Please enter user name.";
    } else{
        $username = trim($_POST["user_name"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["user_pass"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["user_pass"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, user_name, user_pass FROM users WHERE user_name = ?";
        $stmt = mysqli_prepare($link, $sql);
        if($stmt){
            echo 'link working';
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = $username;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    //mysqli_close($link);
}

    // Check if the user is already logged in, if yes then redirect him to welcome page
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        header("location: dashboard.php");
        exit;
    }else{
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
                <?php 
                    echo $login_err;
                    echo $username_err;
                    echo $password_err;
                ?>
                <form action="" method="post">
                <label for="user_name">Username:</label><input type="text" name="user_name" id="user_name" placeholder="Username">
                <label for="user_pass">Password:</label><input type="password" name="user_pass" id="user_pass" placeholder="Password">
                <input type="submit" value="Login">
                </form>
            </body>
            </html>
        <?php
        }
    }

   
    echo '<h2>POST</h2>POST';
    print_r($_POST);
    echo '<h2>SESSION</h2>';
    print_r($_SESSION);
    echo '<h2>FILES</h2>';
    print_r($_FILES);
?>