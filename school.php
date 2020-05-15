<?php
  session_start();
  require_once("pdo.php");

  header("Content-type: application/json; charset=utf-8");

  $stmt = $pdo->prepare("SELECT name from institution where name LIKE :match");
  $stmt->execute(array(':match' => $_GET['term']."%"));

  $retval = array();
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $retval[] = $row['name'];
  }

  echo(json_encode($retval,JSON_PRETTY_PRINT));
?>
