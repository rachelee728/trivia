<?php
include('database/credentials.php');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli($DBHOST, $DBUSER, $DBPWD, $DBNAME);

$error_msg = "";

if (isset($_POST["email"])) {
  $stmt = $mysqli->prepare("select * from users where email = ?;");
  $stmt->bind_param("s", $_POST["email"]);
  if (!$stmt->execute()) {
    $error_msg = "Error checking for user";
  } else { 
    $res = $stmt->get_result();
    $data = $res->fetch_all(MYSQLI_ASSOC);

    setcookie("name", $_POST["name"], time() + 86400, "/"); 
    setcookie("email", $_POST["email"], time() + 86400, "/");  
    setcookie("score", $data[0]["score"], time() + 86400, "/");    
    
    if (!empty($data)) {
      if (password_verify($_POST["password"], $data[0]["password"])) {
        header("Location: start_game.php");
        exit();
      } else {
        $error_msg = "Invalid Password";
      }
    } else {
      $hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
      $insert = $mysqli->prepare("insert into users (name, email, password) values (?, ?, ?);");
      $insert->bind_param("sss", $_POST["name"], $_POST["email"], $hash);
      
      if (!$insert->execute()) {
        $error_msg = "Error creating new user";
      } 
      
      header("Location: start_game.php");
      exit();
    }
  }
}
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Emma Choi & Rachel Lee">
    <meta name="description" content="CS4640 Homework 4">  
    <title>Trivia Game Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous"> 
  </head>
  <body>
    <div class="container" style="margin-top: 15px;">
      <div class="row col-xs-8">
        <h1>CS4640 Television Trivia Game - Get Started</h1>
        <p> Welcome to our trivia game!  To get started, login below or enter a new username and password to create an account</p>
      </div>
      <div class="row justify-content-center">
        <div class="col-4">
        <?php
          if (!empty($error_msg)) {
            echo "<div class='alert alert-danger'>$error_msg</div>";
          }
        ?>
        <form action="index.php" method="post">
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required/>
          </div>
          <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required/>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required/>
          </div>
          <div class="text-center">                
          <button type="submit" class="btn btn-primary">Log in / Create Account</button>
          <a href="logout.php" id="LogoutAction">Logout</a>
          </div>
        </form>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
  </body>
</html>