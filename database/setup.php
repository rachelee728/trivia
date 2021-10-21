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
  
  $db->query("drop table if exists users;");
  $db->query("create table users (
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
    
  $computers = "https://opentdb.com/api.php?amount=20&category=18&type=boolean";
  $video_games = "https://opentdb.com/api.php?amount=20&category=15&type=boolean";
  $general_knowledge = "https://opentdb.com/api.php?amount=20&category=9&type=boolean";
  $music = "https://opentdb.com/api.php?amount=20&category=12&type=boolean";
  $films = "https://opentdb.com/api.php?amount=20&category=11&type=boolean";

  $triviaData = array(
    json_decode(file_get_contents($computers), true),
    json_decode(file_get_contents($video_games), true),
    json_decode(file_get_contents($general_knowledge), true),
    json_decode(file_get_contents($music), true),
    json_decode(file_get_contents($films), true)
  );
  
  print_r($triviaData);
  
  $points = 10;
  $stmt = $db->prepare("insert into question (category, question, answer, points) values (?,?,?,?);");

  foreach($triviaData as $data) {
    foreach($data["results"] as $qn) {
      $stmt->bind_param("sssi", $qn["category"], $qn["question"], $qn["correct_answer"], $points);
      if (!$stmt->execute()) {
        echo "Could not add question: {$qn["question"]}\n";
      } 
    }
  }
?>