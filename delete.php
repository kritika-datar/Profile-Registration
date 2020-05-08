<?php
  session_start();
  require_once("pdo.php");
  require_once("util.php");
?>
<html>
  <head>
    <title>Kritika Datar</title>
    <link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7"
    crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r"
    crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
      <h1>Deleting Profile</h1>
      <br>
      <?php
      // session_start();
      // $servername = "localhost";
      // $username = "root";
      // $password = "root";
      // $dbname = "resume";

      try{
          $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

          $pstmt = $conn->prepare('SELECT first_name, last_name from profile where profile_id= :pi');

          $var = $_GET['profile_id'];
          $pstmt ->execute(array(':pi' => $var));

          $row = $pstmt->fetch(PDO::FETCH_ASSOC);

      ?>
          First Name: <?php echo $row['first_name']; ?><br>
          Last Name: <?php echo $row['last_name']; ?><br>
          <form action="index.php" method="post">
            <input type="text" value="<?php echo $var; ?>" name="profile_id" hidden>
            <input type="submit" name="submit3" value="Delete">
            <input type="submit" name="cancel" value="Cancel">
            <!-- <button onclick="location.href='index.php';">Delete</button>
            <button onclick="location.href='index.php';">Cancel</button> -->
          </form>
      <?php

        }
      catch(PDOException $e){
        echo "Exception caught: ".$e->getMessage() ;
      }
      $conn = null;
    ?>
    </div>
  </body>
</html>
