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
      <?php
        // session_start();
        // $servername = "localhost";
        // $username = "root";
        // $password = "root";
        // $dbname = "resume";

        try{
          // $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
          // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

          $stmt = $pdo->prepare('SELECT * FROM profile where profile_id= :pi');

          $stmt->execute(array( ':pi' => $_GET['profile_id']));

          $row = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
          <h1>Profile Information</h1><br>
          <label>First Name: </label><?php echo $row['first_name'] ?><br><br>
          <label>Last Name: </label><?php echo $row['last_name'] ?><br><br>
          <label>Email: </label><?php echo $row['email'] ?><br><br>
          <label>Headline: </label><?php echo $row['headline'] ?><br><br>
          <label>Summary: </label><?php echo $row['summary'] ?><br><br>
          <?php
            $pstmt = $pdo->prepare('SELECT * FROM education inner join institution on education.institution_id = institution.institution_id where profile_id= :pi');
            $pstmt->execute(array( ':pi' => $_GET['profile_id']));

            if($pstmt->rowCount() == true){
              echo "<label>Education: </label><br>";
              echo "<ul>";
              while($row1 = $pstmt->fetch(PDO::FETCH_ASSOC)){
                echo "<li>".$row1['year'].": ".$row1['name']."</li>";
              }
              echo "</ul>";
            }
            $pstmt = $pdo->prepare('SELECT * FROM position where profile_id= :pi');
            $pstmt->execute(array( ':pi' => $_GET['profile_id']));

            if($pstmt->rowCount() == true){
              echo "<label>Positions: </label><br>";
              echo "<ul>";
              while($row1 = $pstmt->fetch(PDO::FETCH_ASSOC)){
                echo "<li>".$row1['year'].": ".$row1['description']."</li>";
              }
              echo "</ul>";
            }

          }
          catch(PDOException $e){
            echo "Exception caught: ".$e->getMessage();
          }

          ?>
          <a href="index.php">Done</a>
    </div>
  </body>
</html>
