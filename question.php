<?php
// Connect to database
include('database/credentials.php');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli($DBHOST, $DBUSER, $DBPWD, $DBNAME);
$user = null;

// Check cookie to see if a category has been selected
if (!isset($_COOKIE["category"])) {
  header("Location: start_game.php");
  exit();
}

// Get a question from the database
$stmt = $mysqli->prepare("select id, question from question where category = ? order by rand() limit 1;");
$stmt->bind_param("s", $_COOKIE["category"]);
$stmt->execute();
$res = $stmt->get_result();

if ($res === false) {
  die("MySQL database failed");
}
$data = $res->fetch_all(MYSQLI_ASSOC);
if (!isset($data[0])) {
  die("No questions in the database");
}
$question = $data[0];

$message = "";

if (isset($_POST["questionid"])) {
  $qid = $_POST["questionid"];
  $answer = $_POST["answer"];
  
  $stmt = $mysqli->prepare("select * from question where id = ?;");
  $stmt->bind_param("i", $qid);
  if (!$stmt->execute()) {
    $message = "<div class='alert alert-info'>Error: could not find previous question</div>";
  } else {
    $res = $stmt->get_result();
    $data = $res->fetch_all(MYSQLI_ASSOC);
    
    if (!isset($data[0])) {
      $message = "<div class='alert alert-info'>Error: could not find previous question</div>";
    } else {
      if (strtolower($data[0]["answer"]) == strtolower($answer)) {
        $message = "<div class='alert alert-success'>Correct!</div>";

        // Update score in cookie
        $_COOKIE["score"] += $data[0]["points"];

        setcookie("score", $_COOKIE["score"], time() + 86400, "/");

        /*
        // Update score in database
        $stmt = $mysqli->prepare("update user set score  = ? where email = ?;");
        $stmt->bind_param("is", $_COOKIE["score"], $_COOKIE["email"]);
        $stmt->execute();
        */
      } else { 
        $message = "<div class='alert alert-danger'>Incorrect!</div>";
      }
    }
  }
}

// Get user information from cookie
$user = [
  "score" => $_COOKIE["score"]
];
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Emma Choi & Rachel Lee">
    <meta name="description" content="CS4640 Homework 4">
    <title>Trivia Game</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous"> 
  </head>

  <body>
    <div class="container" style="margin-top: 15px;">
      <div class="row col-xs-8">
        <h1>CS4640 Television Trivia Game</h1>
        <h3>Score: <?=$user["score"]?></h3>
      </div>
      <div class="row">
        <div class="col-xs-8 mx-auto">
        <form action="question.php" method="post">
          <div class="h-100 p-5 bg-light border rounded-3">
          <h2>Question</h2>
          <p><?=$question["question"]?></p>
          <input type="hidden" name="questionid" value="<?=$question["id"]?>"/>
          </div>
          <?=$message?>
          <div class="h-10 p-5 mb-3">
            <input type="text" class="form-control" id="answer" name="answer" placeholder="Type your answer here">
          </div>
          <div class="text-center">                
          <button type="submit" class="btn btn-primary">Submit</button>
          <a href="index.php" class="btn btn-danger">Log out</a>
          </div>
        </form>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
  </body>
</html>