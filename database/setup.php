<?php
  include("credentials.php");
  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
  $db = new mysqli($DBHOST, $DBUSER, $DBPWD, $DBNAME);
  
  $db->query("drop table if exists question;");
  $db->query("create table question (
    id int not null auto_increment,
    category text not null,
    question text not null,
    answer text not null,
    points int not null,
    primary key (id));");
  
  $db->query("drop table if exists user;");
  $db->query("create table user (
    id int not null auto_increment,
    email text not null,
    name text not null,
    password text not null,
    primary key (id));");

  $db->query("drop table if exists user_question;");
  $db->query("create table user_question (
    user_id int not null,
    question_id int not null,
    points int not null);");
    
  $data = json_decode(file_get_contents("https://opentdb.com/api.php?amount=20&category=11&type=boolean"), true);
  
  print_r($data);
  
  $points = 10;
  $stmt = $db->prepare("insert into question (category, question, answer, points) values (?,?,?,?);");
  foreach($data["results"] as $qn) {
      $stmt->bind_param("sssi", $qn["category"], $qn["question"], $qn["correct_answer"], $points);
      if (!$stmt->execute()) {
          echo "Could not add question: {$qn["question"]}\n";
      } 
  }
?>