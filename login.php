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
    <h1>Please Log In</h1>
    <br>
    <!-- <script type="text/javascript">
      $('form').each(function() { this.reset() });
    </script> -->
    <?php

    // $servername = "localhost";
    // $username = "root";
    // $password = "root";
    // $dbname = "resume";

    try{

      if(isset($_POST['cancel'])){
        header("Location:index.php");
        return;
      }

      if(isset($_POST['submit'])){
        $salt = 'XyZzy12*_';
        $check = hash('md5', $salt.$_POST['pass']);

        // $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE email = :em AND password = :pw');

        $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        session_start();

        if ( $row !== false ) {
          $_SESSION['name'] = $row['name'];
          $_SESSION['user_id'] = $row['user_id'];
          // Redirect the browser to index.php
          header("Location: index.php");
          return;
        }
        else if($row==false){
          echo "Incorrect email id or password";
        }
      }
    }
    catch(PDOException $e){
      echo "Exception caught: ".$e->getMessage() ;
    }
    $pdo = null;
    ?>
    <form action="login.php" method="post" id="idform">
      <label>Email</label> <input type="text" name="email" id="idemail"><br><br>
      <label>Password</label> <input type="password" name="pass" id="idpass"><br>
      <input type="submit" name="submit" value="Log In" onclick="return doValidate();">
      <input type="submit" value="Cancel" name="cancel">
    </form>
    <!-- <button onclick="location.href='index.php';">Cancel</button> -->
    <script type="text/javascript">
      function doValidate() {
        console.log('Validating...');
        try {
          email = document.getElementById('idemail').value;
          pass = document.getElementById('idpass').value;
          console.log("Validating email address = "+email+" password = "+pass);
          if (email == null || email == "" || pass == null || pass == "") {
            alert("Both fields must be filled out");
            return false;
          }
          if ( email.indexOf('@') == -1 ) {
            alert("Invalid email address");
            return false;
          }
          return true;
        } catch(e) {
            return false;
        }
        return false;
      }
    </script>
  </div>
  </body>
</html>
