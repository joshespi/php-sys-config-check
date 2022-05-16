<?php
$createAdmin = false;
$db_error=$fatal_error = false;
$php_error = $mysql_error = $session_error="";
//check PHP version
$php_version=phpversion();
if($php_version<8) {
  $fatal_error = true;
  $php_error = 'PHP version '.$php_version.' is too old!';
}

// check SQL version
function find_SQL_Version() {
  $output = shell_exec('mysql -V');
  preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version);
  return @$version[0]?$version[0]:-1;
}
$mysql_version=find_SQL_Version();
if($mysql_version<10){
  if($mysql_version==-1){
      $mysql_error="MySQL version will be checked at the next step.";
  } else {
      $fatal_error=true;
      $mysql_error="MySQL version is $mysql_version. Version 10 or newer is required.";
  } 
}

 //check for session cookies
 $_SESSION['myscriptname_sessions_work']=1;
 if(empty($_SESSION['myscriptname_sessions_work']))
 {
  $fatal_error=true;
   $session_error="Sessions must be enabled!";
 }

//output software/config errors
$fatal_errors = array($php_error,$mysql_error,$session_error);
if($fatal_error == true){
  foreach($fatal_errors as $error){
    echo "<span class='error'>".$error."</span>";
  }

  
}

//if is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
  // collect value of input field
  $dbhost = $_POST['dbhost'];
  $dbuser = $_POST['dbuser'];
  $dbpass = $_POST['dbpass'];
  $dbname = $_POST['dbname'];
  
  //Validate Host
  if (empty($dbhost)) {
    echo "Name is empty";
  } else {
  //  echo $dbhost;
  }

  //Validate User
  if (empty($dbuser)) {
    echo "Name is empty";
  } else {
   // echo $dbuser;
  }

  //Validate Pass
  if (empty($dbpass)) {
    echo "Name is empty";
  } else {
  //  echo $dbpass;
  }

  //Validate Name
  if (empty($dbname)) {
    echo "Name is empty";
  } else {
  //  echo $dbname;
  }
  //Test Connection
  $link = mysqli_connect($dbhost, $dbuser, $dbpass);
  if (!$link) {
    die('Could not connect: ' . mysql_error());
  } else {
    $confcreds = "<?php return array('dbhost' => '".$dbhost."','dbuser' => '".$dbuser."','dbpass' => '".$dbpass."', 'dbname' => '".$dbname."'); ?>";
     $filename = 'config/db.php';
     $makeconf = fopen($filename, "wb");
     if(!$makeconf) {
       die('Error creating the file ' . $filename);
     } else {
       fwrite($makeconf, $confcreds);
       fclose($makeconf);
     }
    $checkfordbquery = 'SHOW DATABASES LIKE "' . $dbname . '"';
    $checkfordbresults = $link->query($checkfordbquery);
    if($checkfordbresults->num_rows == 1) {
      $createAdmin = true;
    } else {
      $createdbsql = "CREATE DATABASE ".$dbname;
      if ($link->query($createdbsql) === TRUE) {
        echo "Database created successfully";
      } else {
        echo "Error creating database: " . $conn->error;
      }
    }
  }
  
 

}


//check if config file exists
$conf_file = 'config/db.php';
if(file_exists($conf_file)) {
  $conf = include($conf_file);
  //check for database
  $link = mysqli_connect($conf['dbhost'], $conf['dbuser'], $conf['dbpass'],$conf['dbname']);
  if($link) {
    $createAdmin = true;
  }else{
    echo 'link not established';
  } 
} else {
  ?>
    <!doctype html>
      <html lang="en">
        <head>
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <title>EspiDesk Installer</title>
          <link rel="stylesheet" href="assets/css/variable.css?v=1.0">
          <link rel="stylesheet" href="assets/css/general.css?v=1.0">
          <link rel="stylesheet" href="assets/css/installer.css?v=1.0">
        </head>

        <body>
          <h1>Espi Desk Installer</h1>
          <form action="install.php" method="post">
            <label for="dbhost">Hostname:</label><input type="text" name="dbhost" id="dbhost" value="localhost">
            <label for="dbuser">Database Username:</label><input type="text" name="dbuser" id="dbuser" value="user">
            <label for="dbpass">Database Password:</label><input type="text" name="dbpass" id="dbpass" value="pass">
            <label for="dbname">Database Name:</label><input type="text" name="dbname" id="dbname" value="name">
            <input type="submit" value="Run Setup">
          </form>
        </body>
      </html>
  <?php
}














/*


 
   



// try to connect to the DB, if not display error
if(!@mysqli_connect($_POST['dbhost'],$_POST['dbuser'],$_POST['dbpass']))
{
  $db_error=true;
  $error_msg="Sorry, these details are not correct.
  Here is the exact error: ".mysql_error();
}

if(!$db_error and !@mysql_select_db($_POST['dbname']))
{
  $db_error=true;
  $error_msg="The host, username and password are correct.
  But something is wrong with the given database.
  Here is the MySQL error: ".mysql_error();
}
// try to create the config file and let the user continue
$connect_code="<?php
define('DBSERVER','".$_POST['dbhost']."');
define('DBNAME','".$_POST['dbname']."');
define('DBUSER','".$_POST['dbuser']."');
define('DBPASS','".$_POST['dbpass']."');
?>";
if(!is_writable("inc/db_connect.php"))
{
  $error_msg="<p>Sorry, I can't write to <b>inc/db_connect.php</b>.
  You will have to edit the file yourself. Here is what you need to insert in that file:<br /><br />
  <textarea rows='5' cols='50' onclick='this.select();'>$connect_code</textarea></p>";
}
else
{
  $fp = fopen('inc/db_connect.php', 'wb');
  fwrite($fp,$connect_code);
  fclose($fp);
  chmod('inc/db_connect.php', 0666);
}
*/
?>

