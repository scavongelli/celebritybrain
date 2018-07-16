<?php
  $config = parse_ini_file('...ini');
  $conn = mysqli_connect('localhost',$config['username'],$config['password'],$config['dbname']);
  if ($conn === false) die("A connection to the playground database was unable to be established.  Please try again later, and/or notify an admin (if you know one!)");
  
  session_start();
  $username = strtolower($_POST['username']);
  $password = $conn->real_escape_string($_POST['password']);
  $bool = true;
  
  $queryString = "Select * from users WHERE username='$username'";
  $query = $conn->query($queryString);

  $exists = mysqli_num_rows($query);  // Check if username exists
  $table_users = "";
  $table_password = "";
  $table_userID = "";
  if($exists>0) {    // if provided username is found...
     while($row = mysqli_fetch_assoc($query)) {  
        $table_users = $row['username']; 
        $table_password = $row['password']; 
        $table_userID = $row['id'];
     }
     if(($username == $table_users) && (password_verify($password, $table_password))) {
           $_SESSION['user'] = $username; 
           $_SESSION['user_id'] = $table_userID; 
           header("location: /playgrounds/3playmedia"); 
     }
     else Print '<script>window.location.assign("login?auth=retry");</script>'; 
  }
  else Print '<script>window.location.assign("login?auth=retry");</script>'; 
?>
