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
                // if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 ||
                //     strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1) {
                //
                //   $_SESSION['error'] = "All values are required";
                //   header("Location: add.php");
                //   return;
                // }
                // if ( strpos($_POST['email'],'@') === false ) {
                //   $_SESSION['error'] = 'Email address must contain @';
                //   header("Location: add.php");
                //   return;
                // }

                $msg = validate();
                if(is_string($msg)){
                    $_SESSION['error'] = $msg;
                    header("Location: add.php");
                    return;
                }


                $stmt = $pdo->prepare('INSERT INTO profile(user_id, first_name, last_name, email, headline, summary)
                                          VALUES ( :uid, :fn, :ln, :em, :he, :su)');
                $stmt->execute(array(':uid' => $_SESSION['user_id'],
                                      ':fn' => $_POST['first_name'],
                                      ':ln' => $_POST['last_name'],
                                      ':em' => $_POST['email'],
                                      ':he' => $_POST['headline'],
                                      ':su' => $_POST['summary']));

                $_SESSION['success'] = 'Profile added';
                header( 'Location: index.php' ) ;
                return;
          }
        $pdo = null;
      ?>
      <h1>Adding Profile for <?php echo $_SESSION['name']; ?> </h1>
      <?php
      // Flash pattern
      if ( isset($_SESSION['error']) ) {
        echo "<p style='color:red'>".$_SESSION['error']."</p>";
        unset($_SESSION['error']);
      }
      ?>
      <form method="post">
        <label>First Name: </label><input type="text" name="first_name" size="50" id="idf_name"><br><br>
        <label>Last Name: </label><input type="text" name="last_name" size="50" id="idl_name"><br><br>
        <label>Email: </label><input type="text" name="email" size="50" id="idemail"><br><br>
        <label>Headline: </label><br><input type="text" name="headline" size="80" id="idheadline"><br><br>
        <label>Summary: </label><br><textarea rows="4" cols="50" id="idsummary" name="summary"></textarea><br><br>
        <input type="submit" name="submit1" value="Add">
        <input type="submit" name="cancel" value="Cancel">
      </form>
          <!-- <button onclick="location.href='index.php';">Cancel</button> -->
    </div>
  </body>
</html>
