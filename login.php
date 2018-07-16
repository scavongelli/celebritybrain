<html>
    <?php
      session_start(); 
      if($_SESSION['user']) { 
        header("location: /playgrounds/3playmedia");
      }
      $styleBust = rand(1011,100000001);
    ?>
    <head>
        <title>CelebrityBrain - Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="image/x-icon" href="mediaFiles/brain-16x16.png">
        <?php echo '<link rel="stylesheet" type="text/css" href="css/login.css?random='.$styleBust.'">'; ?>
        <link href="https://fonts.googleapis.com/css?family=Assistant:300,400,600,700|Fjalla+One" rel="stylesheet"> 
        <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"> </script>
    </head>
    <body>
      <div id="backgroundTop"></div>
      <div id="loginCard" align="center" style="position:relative;">
        <div id="loginHeader" align="center" onclick="window.location.assign('/playgrounds/3playmedia');">
          <p> <img id="logoImage" title="CelebrityBrain" src="mediaFiles/brain.svg" alt="Logo" height="35" width="35">
          <span style="padding-left:4px;">CelebrityBrain</span></p>
        </div>
        <div id="loginForm" align="center">
          <form action="checkLogin" method="POST">
            <input type="text" style="width:70%;" id="username" name="username" placeholder="Username" required="required"> <br>
            <input type="password" style="margin-top:28px; width:70%;" name="password" placeholder="Password" required="required"> <br>
            <button type="submit" id="loginSubmit">Login</button> <br>
            <?php if($_GET['auth']=="retry") echo '<span id="invalidCredsMessage" style="">Unrecognized credentials. <span style="padding-left:2px;">Try again.</span> </span>'; ?>
            <input id="loginSignUp" type="button" style="font-size:11px;" value="Create account" onclick="register()">
            <input id="loginForgotPassword" type="button" style="font-size:11px;" value="Forgot password" onclick="forgotPassword()">
          </form>
        </div>
      </div>
        
    </body>
    <script>
    $("#username").focus();
    function forgotPassword() {
      window.location.assign("forgotPassword");
    }
    function register() {
      window.location.assign("register");
    }
    </script>
</html>
