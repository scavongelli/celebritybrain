<?php
if($_SERVER["REQUEST_METHOD"] == "GET") {
  session_start();
  if($_SESSION['user_id']) $user_id = $_SESSION['user_id'];
  else {
    echo "not-logged-in";
    return;
  }
  date_default_timezone_set('America/New_York');
  $config = parse_ini_file('...ini');
  $conn = mysqli_connect('localhost',$config['username'],$config['password'],$config['dbname']);
  if ($conn === false) die("A connection to the database was unable to be established.  Please try again later, and/or notify an admin (if you know one!)");
  $timestampRaw = new DateTime(); 
  $timestamp = $timestampRaw->format('Y-m-d H:i:s');
  
  if($_GET['request'] == "addNew") scoresAddNew();
  else if($_GET['request'] == "getHistory") scoresGetHistory();
}

function scoresAddNew() {
  global $user_id, $conn, $timeZone, $timestampRaw, $timestamp;
  $movie = $conn->real_escape_string($_GET["movie"]);
  $score = $_GET["score"];
  
  $quizQuery = $conn->query("Select * FROM scores WHERE user='$user_id' AND movie='$movie' ORDER BY timestamp DESC LIMIT 1");
  $quizRecord = mysqli_fetch_assoc($quizQuery);
  // if the user has taken this quiz before, update the existing record
  if(count($quizRecord)>0) {
    $lastQuiz = new DateTime($quizRecord["lastAttempt"]);
    $quizID = $quizRecord["id"];
    $lastScore = $quizRecord["score"];
    $totalAttempts = $quizRecord["totalAttempts"];
    $totalAttempts++;
    $diff = $lastQuiz->diff($timestampRaw);
    $hours = $diff->h;
    $hours += ($diff->days*24);
    // if the last attempt was > 72 hours ago
    if($hours>72) {
      // ...and this score is better than the last...
      if($lastScore<$score) $conn->query("Update scores SET score='$score', timestamp='$timestamp', lastAttempt='$timestamp', totalAttempts='$totalAttempts' WHERE user='$user_id' AND id='$quizID'");
      else $conn->query("Update scores SET lastAttempt='$timestamp', totalAttempts='$totalAttempts' WHERE user='$user_id' AND id='$quizID'");
      echo 'done';
    }
    // if the last attempt was more recent, just update the lastAttempt time and total attempts
    else {
      $conn->query("Update scores SET lastAttempt='$timestamp', totalAttempts='$totalAttempts' WHERE user='$user_id' AND id='$quizID'");
      echo '72hours';
    }
    return;
  }
  // if the user has not taken this quiz before, create a new record
  else $conn->query("INSERT INTO scores (user,movie,score,timestamp,lastAttempt) VALUES ('$user_id','$movie','$score','$timestamp','$timestamp')");
  echo 'done';
}

function scoresGetHistory() {
  global $user_id, $conn;
  $movie = $conn->real_escape_string($_GET["movie"]);
  
  // collect user's scores
  $quizQuery = $conn->query("Select * FROM scores WHERE user='$user_id' AND movie='$movie' ORDER BY score, timestamp DESC LIMIT 1");
  $quizRecord = mysqli_fetch_assoc($quizQuery);
  if(count($quizRecord)>0) {
    $userHigh = $quizRecord['score'];
    $timeSet = new DateTime($quizRecord['timestamp']);
  }
  else $timeSet = new DateTime();
  
  $response = array(
    'myHigh' => $userHigh,
    'timeSet' => $timeSet->format('g:ia'),
    'dateSet' => $timeSet->format('F j')
  );
  echo json_encode($response);
}





?>






