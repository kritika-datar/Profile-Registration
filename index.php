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
    <h1>Resume Registry</h1>
    <br>
    <?php
      try{
        if(isset($_POST['cancel'])){
          header("Location:index.php");
          return;
        }

        if ( isset($_SESSION['error']) ) {
          echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
          unset($_SESSION['error']);
        }
        if ( isset($_SESSION['success']) ) {
          echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
          unset($_SESSION['success']);
        }
        if(isset($_SESSION['name'])&&isset($_SESSION['user_id'])){
    ?>
          <a href="logout.php">Logout</a>
    <?php
        }
        else{
    ?>
          <a href="login.php">Please log in</a><br><br>
    <?php
        }
      }
      catch(PDOException $e){
        echo "Exception caught: ".$e->getMessage();
      }

        // $servername = "localhost";
        // $username = "root";
        // $password = "root";
        // $dbname = "resume";
        //
        // $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if(isset($_POST['submit3'])){
          $pstmt = $pdo->prepare('DELETE FROM profile where profile_id= :pi');
          $pstmt->execute(array(':pi' => $_POST['profile_id']));
        }

        $stmt = $pdo->prepare('SELECT profile_id,first_name FROM profile');

        $stmt->execute();

        if ( $stmt->rowCount() == true ) {
          echo "<table border='1'><tr><th>Profile ID</th><th>Name</th>";
          if(isset($_SESSION['name'])) {
            echo "<th>Action</th>";
          }
          echo "</tr>";
          while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
              echo "<tr><td><a href='view.php?profile_id=".$row["profile_id"]."'>".$row["profile_id"]."</a></td><td>".$row["first_name"]."</td>";
              if(isset($_SESSION['name'])&&isset($_SESSION['user_id'])){
                echo "<td><a href='edit.php?profile_id=".$row["profile_id"]."'>Edit</a>&nbsp;&nbsp;<a href='delete.php?profile_id=".$row["profile_id"]."'>Delete</a></td>";
              }
              echo "</tr>";
          }
          echo "</table>";
        }
        $pdo = null;
        if(isset($_SESSION['name'])&&isset($_SESSION['user_id'])){
      ?>
          <br><a href="add.php">Add New Entry</a>
      <?php
        }
      ?>
  </body>
  </div>
</html>
