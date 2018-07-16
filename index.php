<html>
    <?php
      session_start(); 
      if($_SESSION['user']) { 
        $user_id = $_SESSION['user_id'];
        $user = $_SESSION['user'];
      }
      else {
        $user_id=-1; $user="-1";
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
        <title>CelebrityBrain</title>
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
        <li class="navMenu"> <a href="/playgrounds/3playmedia" style="background-color:white; color:black;">Home</a> </li>
          <?php
          if($_SESSION['user']) {
            echo '<li class="navMenu"> <a href="leaderboard">Leaderboard</a> </li>';
            echo '<li class="navMenu"> <a href="history">History</a> </li>';
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
          else {
            echo '<li class="navMenu"> <a href="" onclick="alert(\'You must be logged in to view the Leaderboard\'); return false;">Leaderboard</a> </li>';
            echo '<li class="navMenu"> <a href="" onclick="alert(\'You must be logged in to view your Score History\'); return false;">History</a> </li>';
            echo '<li class="navMenu navMenuDrop" style="float:right; margin-right:14px;" onclick="void(0)">';
            echo '<a id="navMenuUserName" href="login" style="padding-left:20px;">';
            echo 'Sign In';
            echo '</a>';
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
          echo '<div class="mobile-sideMenuItem" style="" onclick="location.assign(\'leaderboard\');"> Leaderboard </div>';
          echo '<div class="mobile-sideMenuItem" style="" onclick="location.assign(\'history\');"> Score History </div>';
          echo '<div class="mobile-sideMenuItem" style="border-bottom: 1px solid rgba(0,0,0,0.4);" onclick="alert(\'This page is coming soon!\'); return false;"> Preferences </div>';
          echo '<div class="mobile-sideMenuItem" style="border:0px"> </div>';
          echo '<div class="mobile-sideMenuItem" style="border:0px"> </div>';
          echo '<div id="mobile-sideMenuLogout" style="" onclick="location.assign(\'logout\');"> Logout </div>';
        }
        else {
          echo '<div class="mobile-sideMenuItem" style="" onclick="alert(\'You must be logged in to view the Leaderboard\'); return false;"> Leaderboard </div>';
          echo '<div class="mobile-sideMenuItem" style="border-bottom: 1px solid rgba(0,0,0,0.4);" onclick="alert(\'You must be logged in to view your Score History\'); return false;"> Score History </div>';
          echo '<div class="mobile-sideMenuItem" style="border:0px"> </div>';
          echo '<div class="mobile-sideMenuItem" style="border:0px"> </div>';
          echo '<div class="mobile-sideMenuItem" style="border:0px"> </div>';
          echo '<div id="mobile-sideMenuLogin" style="" onclick="location.assign(\'login\');"> Sign In </div>';
        }
        ?>
      </div>
      
      
      
      
      
      <div id="searchAreaFrame" style="">
      <div id="searchAreaTitle" style="margin-bottom:20px; font-size:19px; font-weight:700;">
      how pop culture are YOU?
      </div>
      <input type="text" id="movieChoiceInput" style="" placeholder="search a movie" onkeypress="loadMovie(this.value,event);" onchange="loadMovie(this.value);"> <br>
      
      </div>  <!-- end searchAreaFrame div -->
      
      <div id="spacerTop"></div>
      
      <div id="landingMovieCarousel" style="">
        <img class="landingMovieCarouselPosters" src="mediaFiles/sample-poster2.jpg" style="">
        <?php
        $i=0;
        while($i<($landingPostersCount-1)) {
          echo '<img class="landingMovieCarouselPosters" src="">';
          $i++;
        }
        ?>
      </div>
      
      
      
      <div id="resultsFrame" style="">
      
      
      <div id="lefthandInfoPanel" style="">
      <div id="resultsMovieSection" style="">
        <div id="mPosterFrame" style="float:left; display:inline;">
          <img id="mPoster" src="mediaFiles/sample-poster2.jpg" style="border: 1px solid black;">
          <div id="movieScoreHistory" style="">
            <div class="movieScoreHistoryLabels"> My High Score: <span id="movieMyHighScore" class="movieScoreHistoryData"> </span> </div>
            <div class="movieScoreHistoryLabels" style="font-weight:600;"> from <span id="movieMyHighScoreTimestamp" class="movieScoreHistoryData" style="padding-left:1px;"> </span> </div>
          </div>
        </div>
        <div id="resultsMovieSection-info" style="">
          <div id="mTitle" class="movieInfoDisplay" style="font-weight:600;">Blade Runner (2017)</div>
          <div id="mGenre" class="movieInfoDisplay">Action Drama</div>
          <div id="mDirector" class="movieInfoDisplay" style="margin-bottom:20px;">Directed by Han Solo</div>
          <div id="mTomatoRating" style="display:inline;"> <img src="mediaFiles/rotten-tomatoes.png" width="16px;"> <span id="mTomatoRatingNum" style="display:inline;">91%</span> </div>
        </div>
      </div>  <!-- end resultsMovieSection div -->
      <br>
      
      </div>  <!-- end lefthandInfoPanel div -->
      
      <div id="resultsActorSection" style="">
        <input type="hidden" id="record-actorCount" value="0">
        <input type="hidden" id="record-activeTitle" value="n/a">
        <?php
        if($_SESSION["user"]) $userStatus = "member";
        else $userStatus = "guest";
        echo '<input type="hidden" id="record-userStatus" value="'. $userStatus .'" >'; ?>
        
        <div id="wrapper0" class="actorRowWrapper" style="">
          <img src="mediaFiles/icon-correct.svg" class="iconMarkCorrect" style="">
          <img src="mediaFiles/icon-incorrect.svg" class="iconMarkIncorrect" style="">
          <div id="questionBank0" class="questionBank" style="">
            <form>
            <input type="radio" id="q1a1" class="radioInput" name="q1radio" value=""> <span id="q1a1D"> </span> <br>
            <input type="radio" id="q1a2" class="radioInput" name="q1radio" value=""> <span id="q1a2D"> </span> <br>
            <input type="radio" id="q1a3" class="radioInput" name="q1radio" value=""> <span id="q1a3D"> </span> <br>
            <input type="radio" id="q1a4" class="radioInput" name="q1radio" value=""> <span id="q1a4D"> </span> <br>
            <input type="radio" id="q1a5" class="radioInput" name="q1radio" value=""> <span id="q1a5D"> </span> <br>
            </form>
          </div>
          <div id="actorFrame0" class="actorFrame" style="float:right;">
            <img src="" class="actorFrameImage">
            <div class="actorFrameBottom" style=""> </div>
          </div>
        </div>
        <br>
        <div id="wrapper1" class="actorRowWrapper" style="">
          <img src="mediaFiles/icon-correct.svg" class="iconMarkCorrect" style="">
          <img src="mediaFiles/icon-incorrect.svg" class="iconMarkIncorrect" style="">
          <div id="questionBank1" class="questionBank" style="">
            <form>
            <input type="radio" id="q2a1" class="radioInput" name="q2radio" value=""> <span id="q2a1D"> </span> <br>
            <input type="radio" id="q2a2" class="radioInput" name="q2radio" value=""> <span id="q2a2D"> </span> <br>
            <input type="radio" id="q2a3" class="radioInput" name="q2radio" value=""> <span id="q2a3D"> </span> <br>
            <input type="radio" id="q2a4" class="radioInput" name="q2radio" value=""> <span id="q2a4D"> </span> <br>
            <input type="radio" id="q2a5" class="radioInput" name="q2radio" value=""> <span id="q2a5D"> </span> <br>
            </form>
          </div>
          <div id="actorFrame1" class="actorFrame" style="float:right;">
            <img src="" class="actorFrameImage"> 
            <div class="actorFrameBottom" style=""> </div>
          </div>
        </div>
        <br>
        <div id="wrapper2" class="actorRowWrapper" style="">
          <img src="mediaFiles/icon-correct.svg" class="iconMarkCorrect" style="">
          <img src="mediaFiles/icon-incorrect.svg" class="iconMarkIncorrect" style="">
          <div id="questionBank2" class="questionBank" style="">
            <form>
            <input type="radio" id="q3a1" class="radioInput" name="q3radio" value=""> <span id="q3a1D"> </span> <br>
            <input type="radio" id="q3a2" class="radioInput" name="q3radio" value=""> <span id="q3a2D"> </span> <br>
            <input type="radio" id="q3a3" class="radioInput" name="q3radio" value=""> <span id="q3a3D"> </span> <br>
            <input type="radio" id="q3a4" class="radioInput" name="q3radio" value=""> <span id="q3a4D"> </span> <br>
            <input type="radio" id="q3a5" class="radioInput" name="q3radio" value=""> <span id="q3a5D"> </span> <br>
            </form>
          </div>
          <div id="actorFrame2" class="actorFrame" style="float:right;">
            <img src="" class="actorFrameImage"> 
            <div class="actorFrameBottom" style=""> </div>
          </div>
        </div>
        <br>
        <div id="wrapper3" class="actorRowWrapper" style="">
          <img src="mediaFiles/icon-correct.svg" class="iconMarkCorrect" style="">
          <img src="mediaFiles/icon-incorrect.svg" class="iconMarkIncorrect" style="">
          <div id="questionBank3" class="questionBank" style="">
            <form>
            <input type="radio" id="q4a1" class="radioInput" name="q4radio" value=""> <span id="q4a1D"> </span> <br>
            <input type="radio" id="q4a2" class="radioInput" name="q4radio" value=""> <span id="q4a2D"> </span> <br>
            <input type="radio" id="q4a3" class="radioInput" name="q4radio" value=""> <span id="q4a3D"> </span> <br>
            <input type="radio" id="q4a4" class="radioInput" name="q4radio" value=""> <span id="q4a4D"> </span> <br>
            <input type="radio" id="q4a5" class="radioInput" name="q4radio" value=""> <span id="q4a5D"> </span> <br>
            </form>
          </div>
          <div id="actorFrame3" class="actorFrame" style="float:right;">
            <img src="" class="actorFrameImage"> 
            <div class="actorFrameBottom" style=""> </div>
          </div>
        </div>
        <br>
        <div id="wrapper4" class="actorRowWrapper" style="">
          <img src="mediaFiles/icon-correct.svg" class="iconMarkCorrect" style="">
          <img src="mediaFiles/icon-incorrect.svg" class="iconMarkIncorrect" style="">
          <div id="questionBank4" class="questionBank" style="">
            <form>
            <input type="radio" id="q5a1" class="radioInput" name="q5radio" value=""> <span id="q5a1D"> </span> <br>
            <input type="radio" id="q5a2" class="radioInput" name="q5radio" value=""> <span id="q5a2D"> </span> <br>
            <input type="radio" id="q5a3" class="radioInput" name="q5radio" value=""> <span id="q5a3D"> </span> <br>
            <input type="radio" id="q5a4" class="radioInput" name="q5radio" value=""> <span id="q5a4D"> </span> <br>
            <input type="radio" id="q5a5" class="radioInput" name="q5radio" value=""> <span id="q5a5D"> </span> <br>
            </form>
          </div>
          <div id="actorFrame4" class="actorFrame" style="float:right;">
            <img src="" class="actorFrameImage"> 
            <div class="actorFrameBottom" style=""> </div>
          </div>
        </div>
        
        <div id="wrapperSubmit" style="">
          <button class="actionButtons" id="buttonSubmit" style="" onclick="checkAnswers(); return false;">Submit Answers</button>
          <div id="quizResultsFeedback" style="">
            Score:  <span id="quizResultsFeedbackScore" style=""> </span> <br>
            <div id="quizResultsFeedbackMessage" style="margin-top:20px;"> </div>
            <div id="quizResultsFeedback72HourWarning" style="margin-top:35px;"> </div>
          </div>
        </div>
        
        
        
        
      </div>  <!-- end resultsActorSection -->
      
      
      </div>  <!-- end resultsFrame div -->
      
      
      <?php
      if($_GET["movie"]) echo '<input type="hidden" id="record-inboundMovieRequest" value="'.$conn->real_escape_string($_GET["movie"]).'">';
      else echo '<input type="hidden" id="record-inboundMovieRequest" value="-none-">';
      ?>
      
        
      <script>
        loadLanding(); // load poster carousel for 20 most popular movies
        
        // if user was directed here (clicked shortcut on score history), load the referenced movie
        if(document.getElementById("record-inboundMovieRequest").value!=="-none-") loadMovieFromLanding(document.getElementById("record-inboundMovieRequest").value);
       
        // set listener on all body elements to close sideNav on mobile when user clicks outside it.
        $('body *').click(function(event) {
          if(!$(event.target).is('.mobile-sideMenuItem') && !$(event.target).is('#mobile-navMenuIcon') ) {
            $("#mobile-sideMenuFrame").hide();
          }     
        });
          
        function toggleMobileMenu() {
          document.getElementById("mobile-sideMenuFrame").style.display = "block";
        }
        
        function resetPage() {
          document.getElementById("resultsFrame").style.display = "block";
          $('body').css("border","none");
          document.getElementById("lefthandInfoPanel").style.visibility = "hidden";   // toggle visibility for info panel
          document.getElementById("quizResultsFeedback").style.display = "none";
          $("#quizResultsFeedbackScore").html("");
          document.getElementById("record-actorCount").value = 0;
          $(".iconMarkCorrect").css("visibility","hidden");
          $(".iconMarkIncorrect").css("visibility","hidden");
          $(".actorFrameBottom").css("visibility","hidden");
          
          $('input[name="q1radio"]').prop('checked', false);
          $('input[name="q2radio"]').prop('checked', false);
          $('input[name="q3radio"]').prop('checked', false);
          $('input[name="q4radio"]').prop('checked', false);
          $('input[name="q5radio"]').prop('checked', false);
          window.scrollTo(0,0);
          $('#landingMovieCarousel').css("animation-play-state","paused");  // if we don't pause carousel it will keep scrolling for no reason in background
        }
        
        function loadLanding() {
          xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200) {
              //document.getElementById("resultsFrame").innerHTML = this.responseText;
              var response = JSON.parse(this.responseText).results;
              var posterCount = $('#landingMovieCarousel .landingMovieCarouselPosters').length; 
              var i=0;
              while(i<response.length && i<posterCount) {
                var posterPath = "http://image.tmdb.org/t/p/w185" + response[i]["poster_path"];
                var title = response[i]["title"];
                $('#landingMovieCarousel .landingMovieCarouselPosters').eq(i).attr("src",posterPath);
                $('#landingMovieCarousel .landingMovieCarouselPosters').eq(i).attr('onClick','loadMovieFromLanding("'+title+'")');
                i++;
              }
            }};
          xmlhttp.open("GET","https://api.themoviedb.org/3/discover/movie?api_key=1234&language=en-US&region=US&sort_by=popularity.desc&include_adult=false&include_video=false&page=1",false);
          xmlhttp.send();
        }
        
        function checkAnswers() {
          var movie = document.getElementById("record-activeTitle").value;
          var questionTally = document.getElementById("record-actorCount").value;
          if(movie=="n/a" || questionTally<1) return;
          movie = encodeURIComponent(movie);
          
          // check each set of radio buttons to see if the correct option is selected
          var correctTally = 0;
          if( $('input[name=q1radio]:checked').val() == "correct" ) {
            $("#wrapper0 > .iconMarkCorrect").css("visibility","visible");
            correctTally++;
          }
          else $("#wrapper0 > .iconMarkIncorrect").css("visibility","visible");
          
          if( $('input[name=q2radio]:checked').val() == "correct" ) {
            $("#wrapper1 > .iconMarkCorrect").css("visibility","visible");
            correctTally++;
          }
          else $("#wrapper1 > .iconMarkIncorrect").css("visibility","visible");
          
          if( $('input[name=q3radio]:checked').val() == "correct" ) {
            $("#wrapper2 > .iconMarkCorrect").css("visibility","visible");
            correctTally++;
          }
          else $("#wrapper2 > .iconMarkIncorrect").css("visibility","visible");
          
          if( $('input[name=q4radio]:checked').val() == "correct" ) {
            $("#wrapper3 > .iconMarkCorrect").css("visibility","visible");
            correctTally++;
          }
          else $("#wrapper3 > .iconMarkIncorrect").css("visibility","visible");
          
          if( $('input[name=q5radio]:checked').val() == "correct" ) {
            $("#wrapper4 > .iconMarkCorrect").css("visibility","visible");
            correctTally++;
          }
          else $("#wrapper4 > .iconMarkIncorrect").css("visibility","visible");
          
          var score = Math.round((correctTally/questionTally)*100);
          //var scoreDisplay = correctTally + " / " + questionTally;
          var scoreDisplay = score + "%";
          var scoreFeedback = "";
          
          if(score==100) scoreFeedback = "Perfect score!  Look at you go.";
          else if(score>85) scoreFeedback = "Well done.";
          else if(score>49) scoreFeedback = "Not too shabby.";
          else if(score>0) scoreFeedback = "a little studying goes a long way...";
          else scoreFeedback = "Hmm...";
          
          if(score>85) scoreDisplay = '<span style="color:green;">'+scoreDisplay+'</span>';
          else if(score<26) scoreDisplay = '<span style="color:#ea2c2c;">'+scoreDisplay+'</span>';
          
          $("#quizResultsFeedbackScore").html(scoreDisplay);  // show score percentage
          $("#quizResultsFeedbackMessage").html(scoreFeedback);  // show score feedback message
          document.getElementById("quizResultsFeedback").style.display = "block";
          $(".actorFrameBottom").css("visibility","visible");  // show correct names under photos
          document.getElementById("buttonSubmit").style.display = "none";  // hide submit button
          
          if(document.getElementById("record-userStatus").value!=="member") return;  // if user is not signed in, don't [attempt to] save this score
          xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200) {
              loadScoreData(movie);  // reload high score data for movie via AJAX (to reflect this submission)
              // if same quiz has been taken in last 72 hours, let user know about high score policy
              if((this.responseText).trim()=="72hours") document.getElementById("quizResultsFeedback72HourWarning").innerHTML = "<span style='text-decoration:underline;'>Note</span>: " +
              "<span style='padding-left:4px;;'></span>High scores are not saved for quizzes you've taken recently.";
            }};
          xmlhttp.open("GET","ajax/scoreUpdateRequest.php?request=addNew&movie="+movie+"&score="+score,false);
          xmlhttp.send();
        }
        
        // if user clicked on a movie poster on the landing page, load the movie and update search bar with its title.
        function loadMovieFromLanding(search) {
          document.getElementById("movieChoiceInput").value = search;
          loadMovie(search);
        }
        
        // load the specified movie using OMDB api
        function loadMovie(search,key) {
          if((key && key.keyCode !== 13) || search.length<2) return;  // only proceed if 'Enter' key (n/a on mobile)
          resetPage();
          document.getElementById("landingMovieCarousel").style.display = "none";
          var movieTitle = encodeURIComponent(search);
          xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200) {
              //document.getElementById("resultsFrame").innerHTML = this.responseText;
              var response = JSON.parse(this.responseText);
              var mTitle = response.Title;
              var mYear = response.Year;
              var mRating = response.Rated;
              var mRelease = response.Released;
              var mRuntime = response.Runtime;
              var mGenre = response.Genre;
              var mDirector = response.Director;
              var mActors = response.Actors;
              var mPoster = response.Poster;
              var mTomatoRating = "unknown";
              document.getElementById("lefthandInfoPanel").style.visibility = "visible";   // toggle visibility for info panel
              var i=0; var found="no";
              while(i<(response.Ratings).length && found=="no") {
                if(response.Ratings[i]["Source"]=="Rotten Tomatoes") {
                  mTomatoRating = response.Ratings[i]["Value"];
                  found="yes";
                }
                i++;
              } 
              document.getElementById("mTitle").innerHTML = mTitle + '<span style="font-weight:400; padding-left:2px;"> (' + mYear + ')</span>';
              document.getElementById("record-activeTitle").value = mTitle;
              document.getElementById("mGenre").innerHTML = mGenre;
              document.getElementById("mDirector").innerHTML = mDirector;
              if(found=="yes") { 
                document.getElementById("mTomatoRatingNum").innerHTML = mTomatoRating;
                document.getElementById("mTomatoRating").style.visibility = "visible";
              }
              else document.getElementById("mTomatoRating").style.visibility = "hidden";
              document.getElementById("mPoster").src = mPoster;
              fetchCast(mTitle);  // now that the movie info is loaded, we need the actor names & photos
              loadScoreData(mTitle);  // get user's high score history for this movie
            }};
          xmlhttp.open("GET","https://www.omdbapi.com/?t="+movieTitle+"&apikey=1234",false);
          xmlhttp.send();
        }
        
        // Get cast names & photos with IMDB api
        function fetchCast(search) {
          var movie = encodeURIComponent(search);        
          $(".actorRowWrapper").css('display','none');  // hide all actor cards, in case this search turns up fruitless or there are less actors than last time
          
          xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200) {
              var response = (JSON.parse(this.responseText)).results;
              var array=[];
              xmlhttp = new XMLHttpRequest();
              xmlhttp.onreadystatechange = function() {
                if(this.readyState == 4 && this.status == 200) {
                  var response2 = (JSON.parse(this.responseText)).cast;
                  // JSON comes back out of cast order (cast is not in order of importance)...
                  // so we need to create an array with each actor's cast order, name, [character,] and photo url...
                  for(a in response2) {
                    array.push([response2[a]['cast_id'],response2[a]['name'],response2[a]['character'],"http://image.tmdb.org/t/p/w185"+response2[a]['profile_path']]);
                  }
                  // ...then sort it.
                  array.sort(function(a,b){return a[0] - b[0]});
                  // ...keep only the first 5 actors, per the requirements.
                  var actors = array.slice(0,5);
                  // ...shuffle them up!
                  var actorList1 = shuffle(actors.slice(0));
                  var actorListShuf = actors.slice(0);
                  // update actor cards with new names & photos
                  var i=0;
                  while(i<actorList1.length && i<5) {
                    var thisActor = actorList1[i][1];
                    var imageURL = actorList1[i][3];
                    if(thisActor.length<3) continue;
                    document.getElementById("wrapper"+i).style.display = "block";
                    $("#actorFrame"+i+" > .actorFrameBottom").html(thisActor);
                    $("#actorFrame"+i+" > img").attr("src",imageURL);
                    i++;
                  }
                  // update multiple choice fields with new info
                  // ...I kept having issues with the shuffled arrays when in the same loop for some reason, so I split them into two loops.
                  i=0;
                  while(i<actorList1.length && i<5) {
                    var actorListShuf = shuffle(actorListShuf.slice(0));
                    var x=0;
                    while(x<actorListShuf.length && x<5) {
                      $("#q"+(i+1)+"a"+(x+1)+"D").html(actorListShuf[x][1]);
                      if(actorListShuf[x][1]==$("#actorFrame"+i+" > .actorFrameBottom").html()) $("#q"+(i+1)+"a"+(x+1)).attr("value","correct");
                      else $("#q"+(i+1)+"a"+(x+1)).attr("value","");
                      x++;
                    }
                    i++;
                  }
                  document.getElementById("buttonSubmit").style.display = "block";
                  document.getElementById("record-actorCount").value = x;  // record new actor count, which is referenced during grading
                }};
              xmlhttp.open("GET","https://api.themoviedb.org/3/movie/"+response[0]['id']+"/credits?api_key=1234",false);
              xmlhttp.send();
            }};
          xmlhttp.open("GET","https://api.themoviedb.org/3/search/movie?api_key=1234&language=en-US&query="+movie+"&page=1&include_adult=false&region=US",false);
          xmlhttp.send();
        }
        
        // Get high score data for user, if it exists
        function loadScoreData(movie) {
          if(document.getElementById("record-userStatus").value!=="member") {
            document.getElementById("movieScoreHistory").style.display = "none";
            return;
          }
          var movie = document.getElementById("record-activeTitle").value;
          movie = encodeURIComponent(movie);
          var myHigh=0;
          var publicHigh=50;
          
          xmlhttp = new XMLHttpRequest();
          xmlhttp.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200) {
              // Hide history info for guests.  This could be a good place to mention the benefit of signing up to track history...
              if(this.responseText=="not-logged-in") {
                document.getElementById("movieScoreHistory").style.display = "none";
                return;
              }
              var response = JSON.parse(this.responseText);
              if(response.myHigh!==null) {
                document.getElementById("movieMyHighScore").innerHTML = response.myHigh + "%";
                document.getElementById("movieMyHighScoreTimestamp").innerHTML = response.dateSet + " at " + response.timeSet;
                document.getElementById("movieScoreHistory").style.display = "block";
              }
              else document.getElementById("movieScoreHistory").style.display = "none";
            }};
          xmlhttp.open("GET","ajax/scoreUpdateRequest.php?request=getHistory&movie="+movie,false);
          xmlhttp.send();
        }
        
        function shuffle(array) {
          var currentIndex = array.length, temporaryValue, randomIndex;
          while (0 !== currentIndex) {
            // Pick remaining index...
            randomIndex = Math.floor(Math.random() * currentIndex);
            currentIndex -= 1;
            tempName = array[currentIndex][1];
            tempPhoto = array[currentIndex][3];
            
            // And swap it with the current index.
            array[currentIndex][1] = array[randomIndex][1];
            array[currentIndex][3] = array[randomIndex][3];
            array[randomIndex][1] = tempName;
            array[randomIndex][3] = tempPhoto;
          }
          return array;
        }
        
        
      </script>
    </body>
</html>






















