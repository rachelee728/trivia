<?php
// Connect to database
include('database/credentials.php');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli($DBHOST, $DBUSER, $DBPWD, $DBNAME);
$user = null;

// //Enforce login
// if (!isset($_COOKIE['type'])){
//   header("Location: index.php");
// }

if (isset($_POST["category"])) {
  setcookie("category", $_POST["category"], time() + (86400 * 30), "/");
  header("Location: question.php");
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Emma Choi & Rachel Lee">
    <meta name="description" content="CS4640 Homework 4">  
    <title>Trivia Game Instructions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous"> 
  </head>
  <body>
    <div class="container" style="margin: 3em;">
      <div class="card my-4">
        <div class="card-body">
          <h1>How to play Tricky Trivia!</h1>
          <p>Tricky Trivia is indeed tricky! Read the following instructions carefully and select a category to get started!</p>
        </div>
      </div>

      <div class="container" style="margin: 3em;">
      <div class="card my-4">
        <div class="card-body">
          <h1>Tricky Trivia Instructions!</h1>
          <p>The rules are simple:</p>
          <ul>1. Choose 1 category</ul>
          <ul>2. Answer to the best of your ability</ul>
          <ul>3. Answer correctly to gain points!</ul>
        </div>
      </div>

      <form action="start_game.php" method="post">
        <select class="dropdown-menu">
          <option value="Computers">Computers</option>
        </select>

        <div class="card my-4 mx-auto text-center" style="width: 25em;">
          <div class="btn-group-vertical btn-group-lg" role="group">
            <button class="btn btn-dark" style="pointer-events: none;">Categories</button>

            <button type="submit" name="category" value="Science: Computers" class="btn btn-outline-dark">Computers</button>
            <button type="submit" name="category" value="Entertainment: Video Games" class="btn btn-outline-dark">Video Games</button>
            <button type="submit" name="category" value="General Knowledge" class="btn btn-outline-dark">General Knowledge</button>
            <button type="submit" name="category" value="Entertainment: Music" class="btn btn-outline-dark">Music</button>
            <button type="submit" name="category" value="Entertainment: Film" class="btn btn-outline-dark">Films</button>
          </div>
        </div>
      </form>
    </div>
    <div>
    <a href="logout.php" id="LogoutAction">Logout</a>
  </div>  
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
  </body>
</html>