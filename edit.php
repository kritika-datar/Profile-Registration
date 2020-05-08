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

        // $servername = "localhost";
        // $username = "root";
        // $password = "root";
        // $dbname = "resume";
        //
        // $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if(!isset($_SESSION['name'])){
          die("ACCESS DENIED");
          return;
        }

        if(isset($_POST['cancel'])){
          header("Location:index.php");
          return;
        }

        if ( isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {
          // Data validation
          $msg = validate();
          if(is_string($msg)){
              $_SESSION['error'] = $msg;
              header("Location: edit.php?profile_id=".$_POST['profile_id']);
              return;
          }

          $pstmt = $pdo->prepare('UPDATE Profile set
                                    first_name= :fn, last_name= :ln, email= :em, headline= :he, summary= :su where profile_id= :pid');
          $pstmt->execute(array(':fn' => $_POST['first_name'],
                                ':ln' => $_POST['last_name'],
                                ':em' => $_POST['email'],
                                ':he' => $_POST['headline'],
                                ':su' => $_POST['summary'],
                                ':pid' => $_POST['profile_id']));

          $_SESSION['success'] = 'Profile updated';
          header( 'Location: index.php' ) ;
          return;
        }

    ?>
    <h1>Editing Profile for <?php echo $_SESSION['name']; ?> </h1>
    <?php
    // Flash pattern
    if ( isset($_SESSION['error']) ) {
      echo "<p style='color:red'>".$_SESSION['error']."</p>";
      unset($_SESSION['error']);
    }

        $stmt = $pdo->prepare('SELECT * FROM profile where profile_id= :pi');

        $stmt->execute(array( ':pi' => $_GET['profile_id']));

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ( $stmt->rowCount() == true ) {
      ?>
          <form method='post'>
            <input type="text" value="<?php echo $_GET['profile_id'] ?>" name="profile_id" hidden>
            <label>First Name: </label><input type="text" name="first_name" size="50" value="<?php echo $row['first_name'] ?>" id="idf_name"><br><br>
            <label>Last Name: </label><input type="text" name="last_name" size="50" value="<?php echo $row['last_name'] ?>" id="idl_name"><br><br>
            <label>Email: </label><input type="text" name="email" size="50" value="<?php echo $row['email'] ?>" id="idemail"><br><br>
            <label>Headline: </label><br><input type="text" name="headline" size="80" value="<?php echo $row['headline'] ?>" id="idheadline"><br><br>
            <label>Summary: </label><br><textarea rows="4" cols="50" id="idsummary" name="summary"><?php echo $row['summary'] ?></textarea><br><br>
            <input type="submit" name="submit2" value="Save">
            <input type="submit" name="cancel" value="Cancel">
          </form>
        <!-- <button onclick="location.href='index.php';">Cancel</button> -->
        <?php
    }
      $pdo = null;
      ?>
    </div>
  </body>
</html>
