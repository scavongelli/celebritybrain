<html>
    <?php
      session_start(); 
      if($_SESSION['user']) { 
        $user_id = $_SESSION['user_id'];
        $user = $_SESSION['user'];
      }
      else {
        $user_id=-1; $user="-1";
        header("location: login"); 
      }
      $config = parse_ini_file('...ini');
      $conn = mysqli_connect('localhost',$config['username'],$config['password'],$config['dbname']);
      if ($conn === false) die("A connection to the playground database was unable to be established.  Please try again later, and/or notify the bridge troll.");
      $styleBust = rand(1011,100000001);
      $timeZone = new DateTimeZone('GMT-5');
      $today = new DateTime(); 
      $today->setTimezone($timeZone);
      $today->setTime(0,0,0);
      setlocale(LC_MONETARY, 'en_US');
      $landingPostersCount = 20;
    ?>
    
    <head>
        <title>CelebrityBrain - Leaderboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1">
        <?php echo '<link rel="stylesheet" type="text/css" href="css/general.css?random='.$styleBust.'">'; ?>
        <link rel="shortcut icon" type="image/x-icon" href="mediaFiles/brain-16x16.png">
        <link href="https://fonts.googleapis.com/css?family=Assistant:300,400,600,700|Fjalla+One" rel="stylesheet"> 
        <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.min.js"> </script> 
    </head>
    
    <body>
    
      <ul id="navMenuFrame">
        <span class="navMenuLogo" onclick="location.assign('/playgrounds/3playmedia')">CelebrityBrain</span> 
        <span id="navMenuLeftPadding" style=""></span>
        <li class="navMenu"> <a href="/playgrounds/3playmedia">Home</a> </li>
          <?php
          if($_SESSION['user']) {
            echo '<li class="navMenu"> <a href="" style="background-color:white; color:black;">Leaderboard</a> </li>';
            echo '<li class="navMenu"> <a href="/playgrounds/3playmedia/history">History</a> </li>';
            echo '<li class="navMenu navMenuDrop" style="float:right; margin-right:14px;" onclick="void(0)">';
            echo '<a id="navMenuUserName" style="padding-left:20px;">';
            if(strlen($userRecord["displayName"])>0) echo $user;
            else echo $user;
            echo '</a>';
            echo '<div class="navMenuDropFrame">';
            echo '<a href="">Preferences</a>';
            echo '<a href="">About</a>';
            echo '<a href="logout">Logout</a>';
            echo '</div>';
          }
          ?> 
          
        </li>
      </ul>
      
      <ul id="mobile-navMenuFrame">
        <li class="mobile-navMenu"> <span class="mobile-navMenuLogo" onclick="location.assign('/playgrounds/3playmedia')">CelebrityBrain</span> </li>
        <li class="mobile-navMenu mobile-navMenuIcon" style="float:right; margin-right:8px;" onclick="toggleMobileMenu();">
          <a onclick="toggleMobileMenu();">
          <img id="mobile-navMenuIcon" src="mediaFiles/icon-menu.svg" style="height:20;" onclick="toggleMobileMenu();">
          </a>
        </li>
      </ul>
      
      <div id="mobile-sideMenuFrame">
        <?php
        if($_SESSION['user']) {
          echo '<div class="mobile-sideMenuItem" style="" onclick="location.assign(\'/playgrounds/3playmedia\');"> Home </div>';
          echo '<div class="mobile-sideMenuItem" style="" onclick="location.assign(\'/playgrounds/3playmedia/history\');"> Score History </div>';
          echo '<div class="mobile-sideMenuItem" style="border-bottom: 1px solid rgba(0,0,0,0.4);" onclick="alert(\'This page is coming soon!\'); return false;"> Preferences </div>';
          echo '<div class="mobile-sideMenuItem" style="border:0px"> </div>';
          echo '<div class="mobile-sideMenuItem" style="border:0px"> </div>';
          echo '<div id="mobile-sideMenuLogout" style="" onclick="location.assign(\'logout\');"> Logout </div>';
        }
        ?>
      </div>
      
      
      <div id="leaderboard-spacerTop" style=""> </div>
      <div id="leaderboard-mainFrame" style="">
      
      <div id="leaderboard-dataTable" style="">
      <table style="width:100%">
        <tr style="font-weight:600;">
          <td class="leaderboard-tableColTitles">Player</td>
          <td class="leaderboard-tableColHighScore" style="line-height:20px;">High Score Average</td>
          <td class="leaderboard-tableColTotalAttempts" style="line-height:20px;">Quizzes Taken</td>
          <td class="leaderboard-tableColLastRecord" style="line-height:20px;">Last Personal Record</td>
        </tr>
        <?php
        $query = $conn->query("Select user, COUNT(score) records, AVG(score) avgHigh FROM scores GROUP BY user");
        while($row = mysqli_fetch_array($query)) {
          $playerID = $row['user'];
          // get player username
          $playerQuery = $conn->query("Select username FROM users WHERE id='$playerID'");
          $playerRecord = mysqli_fetch_assoc($playerQuery);
          $player = $playerRecord['username'];
          // get player's recent activity
          $playerScoreQuery = $conn->query("Select timestamp, movie, score FROM scores WHERE user='$playerID' ORDER BY timestamp DESC");
          $playerScoreRecord = mysqli_fetch_assoc($playerScoreQuery);
          $lastRecordDate = new DateTime($playerScoreRecord['timestamp']);
          $lastRecordScore = $playerScoreRecord['score'];
          $lastRecordMovie = $playerScoreRecord['movie'];
          Print "<tr>";
            Print '<td align="left" class="leaderboard-tableColPlayers"> '.$player.' </td>';
            Print '<td align="left" class="leaderboard-tableColScoreAvg"> '.round($row['avgHigh'],0).'% </td>';
            Print '<td class="leaderboard-tableColTotalQuizzes" align="left"> '.$row['records'].' </td>';
            Print '<td class="leaderboard-tableColLastRecord" align="left"> '.$lastRecordMovie.' - '.$lastRecordScore.'% <span style="padding-left:5px;">('.$lastRecordDate->format("F j, Y").')</span> </td>';
          Print "</tr>";
        }
        ?>
      
      
      </table>
      </div>  <!-- end history-dataTable div -->
      
      </div>  <!-- end history-mainFrame div -->
      
      
      
      
        
      <script>
        colorMe();
        // set listener on all body elements to close sideNav on mobile when user clicks outside it.
        $('body *').click(function(event) {
          if(!$(event.target).is('.mobile-sideMenuItem') && !$(event.target).is('#mobile-navMenuIcon') ) {
            $("#mobile-sideMenuFrame").hide();
          }     
        });
        
        function toggleMobileMenu() {
          document.getElementById("mobile-sideMenuFrame").style.display = "block";
        }
        
        function colorMe() {
          $("#leaderboard-dataTable tr:odd:not(:first-child) > td").css("background-color", "#f4f4f4");  // alternate table row coloring
          $('body').css("border","none");  // hide dark fading on home screen
        }
        
        
        
      </script>
    </body>
</html>






















