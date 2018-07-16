<html>
    <?php
      $styleBust = rand(1011,100000001);
    ?>
    <head>
        <title>CelebrityBrain - Register</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="image/x-icon" href="mediaFiles/brain-16x16.png">
        <?php echo '<link rel="stylesheet" type="text/css" href="css/login.css?random='.$styleBust.'">'; ?>
        <link href="https://fonts.googleapis.com/css?family=Assistant:300,400,600,700|Fjalla+One" rel="stylesheet">
        <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"> </script>
    </head>
    <body>
      <div id="backgroundTop"></div>
      <div id="registerCard" align="center" style="position:relative;">
        <div id="loginHeader" align="center" onclick="window.location.assign('login');">
          <p> <img id="logoImage" title="CelebrityBrain" src="mediaFiles/brain.svg" alt="Logo" height="35" width="35">
          <span style="padding-left:4px;">CelebrityBrain</span></p>
        </div>
        <div id="registerForm" align="center">
          <form action="register" method="POST">
            <input type="hidden" id="usernameGuard" name="usernameGuard" value="fail">
            <input type="text" style="width:70%;" id="username" name="username" placeholder="Username" maxlength="25" required="required" onkeyup="checkUsername();"> <span id="usernameHint" style="display:none; color:red; font-size:12px; float:left; margin-left:18%; padding-top:5px;"></span> <br>
            <input type="hidden" id="emailGuard" name="emailGuard" value="fail">
            <input type="email" style="margin-top:28px; width:70%;" id="email" name="email" placeholder="Email" required="required" maxlength="70" onkeyup="showEmailHint(this.value); showUsernameHint();"> <br> <span id="emailHint" style="color:red; font-size:12px; float:left; margin-left:18%; padding-top:5px;"></span> <br>
            <input type="password" style="margin-top:28px; width:70%;" id="password" name="password" placeholder="Password" required="required"> <br>
            <input type="password" style="margin-top:28px; width:70%;" id="password2" name="password2" placeholder="Confirm password" required="required" onkeyup="showPassHint(this.value)"> <br> <span id="passHint" style="color:red; font-size:12px; float:left; margin-left:18%; padding-top:5px;"></span> <br>
            
            <button type="submit" id="registerSubmit">Create account</button> <br>
          </form>
        </div>
      </div>
      
      
        
    </body>
    <script>
      $("#username").focus();
      function showPassHint(str) {
        if(str.length == 0) {  // if password field is empty
          document.getElementById("passHint").innerHTML = "";
          return;
        }
        else {
          if(str == document.getElementById("password").value) {
            document.getElementById("passHint").innerHTML = "passwords match!";
            document.getElementById("passHint").style.color = "#26b586";
          }
          else document.getElementById("passHint").innerHTML = "passwords must match";
        }
        checkEmail();
      }
      
      function showEmailHint(str) {  // remind user that email is required if they move on to password fields
        var pass = document.getElementById("password").value;
        if(pass.length == 0) {
          document.getElementById("emailHint").innerHTML = "";
          return;
        }
        checkEmail();
      }
      
      function checkEmail() {
        var accEmail = document.getElementById("email").value;
        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if(accEmail=="") {
          document.getElementById("emailHint").innerHTML = "Oops, an email is required";
          document.getElementById("emailGuard").value = "fail";
          return;
        }
        else if(!re.test(String(accEmail).toLowerCase())) {
          document.getElementById("emailHint").innerHTML = "This email appears invalid. Check for typos?";
          document.getElementById("emailGuard").value = "fail";
          return;
        }
        document.getElementById("emailHint").innerHTML = "";
        document.getElementById("emailGuard").value = "pass";
        return;
      }
      
      function showUsernameHint() {  
        var email = document.getElementById("email").value;
        if(email.length == 0) {
          document.getElementById("usernameHint").style.display = "none";
          document.getElementById("usernameHint").innerHTML = "";
          return;
        }
        checkUsername();
      }
      
      function checkUsername() {
        var username = document.getElementById("username").value;
        var re = new RegExp(/^[a-z0-9]+$/i);
        if(username.length == 0) {
          document.getElementById("usernameHint").innerHTML = "Oops, a username is required";
          document.getElementById("usernameHint").style.display = "block";
          document.getElementById("usernameGuard").value = "fail";
          return;
        }
        else if(!re.test(String(username))) {
          document.getElementById("usernameHint").innerHTML = "Letters, numbers, and underscores only";
          document.getElementById("usernameHint").style.display = "block";
          document.getElementById("usernameGuard").value = "fail";
          return;
        }
        document.getElementById("usernameHint").style.display = "none";
        document.getElementById("usernameHint").innerHTML = "";
        document.getElementById("usernameGuard").value = "pass";
        return;
      }
    </script>
</html>



<?php

$config = parse_ini_file('...ini');
$conn = mysqli_connect('localhost',$config['username'],$config['password'],$config['dbname']);
if ($conn === false) die("A connection to the playground database was unable to be established.  Please try again later, and/or notify the bridge troll.");

if($_SERVER["REQUEST_METHOD"] == "POST") {
  if ($_POST['password'] !== $_POST['password2']) {
    Print '<script>alert("Passwords do not match.  Please try again.");</script>'; 
    Print '<script>window.location.assign("register");</script>'; 
    return;
  }
  else if (strlen($_POST['username'])<4) {
    Print '<script>alert("Username must be at least 4 characters");</script>'; 
    Print '<script>window.location.assign("register");</script>'; 
    return;
  }
  else if ($_POST['emailGuard']!=="pass") {
    Print '<script>alert("Invalid email.  Please try again.");</script>'; 
    Print '<script>window.location.assign("register");</script>'; 
    return;
  }
  else if ($_POST['usernameGuard']!=="pass") {
    Print '<script>alert("Invalid username.  Please try again.");</script>'; 
    Print '<script>window.location.assign("register");</script>'; 
    return;
  }
  
  
  $username = strtolower($conn->real_escape_string($_POST['username']));
  $password = password_hash($conn->real_escape_string($_POST['password']), PASSWORD_DEFAULT);
  $email = strtolower($conn->real_escape_string($_POST['email']));
  $bool = true;
  
  $queryString = "Select * from users";
  $query = $conn->query($queryString);
  
  while($row = mysqli_fetch_array($query)) {
    $table_users = $row['username'];
    if($username == $table_users) {
      $bool = false;
      Print '<script>alert("Username is taken");</script>';
      Print '<script>window.location.assign("register");</script>';
    }
  }
  
  if($bool) {
    $conn->query("INSERT INTO users (username,password,email) VALUES ('$username','$password','$email')");
    Print '<script>alert("Registration successful!  You can now login to your new account.");</script>';
    Print '<script>window.location.assign("login");</script>';
  }
  
  $to      = $email;
  $subject = 'Welcome to CelebrityBrain!';
  $message = "Don't remember creating an account here?  That's a shame.";
  $headers = 'From: "CelebrityBrain" <chris@scavongelli.com>' . "\r\n" .
    'Reply-To: chris@scavongelli.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
  mail($to, $subject, $message, $headers);
}





?>





















