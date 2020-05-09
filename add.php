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

    <script src="https://code.jquery.com/jquery-3.2.1.js"
    integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
    crossorigin="anonymous"></script>

  </head>
  <body>
    <div class="container">
      <?php
        if(!isset($_SESSION['name'])){
          die("ACCESS DENIED");
          return;
        }

        if(isset($_POST['cancel'])){
          header("Location:index.php");
          return;
        }

          if ( isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email'])
              && isset($_POST['headline']) && isset($_POST['summary'])) {
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

                $str = validateP();
                if(is_string($str)){
                    $_SESSION['error'] = $str;
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

                $profile_id = $pdo->lastInsertId();
                $rank = 1;
                for($i=1;$i<=9;$i++){
                    if(isset($_POST['year'.$i])){
                      $pstmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');

                      $pstmt->execute(array(
                        ':pid' => $profile_id,
                        ':rank' => $rank,
                        ':year' => $_POST['year'.$i],
                        ':desc' => $_POST['desc'.$i])
                      );
                      $rank++;
                    }
                }

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
        <label>Position: </label><input type="submit" id="position" value="+">
        <div id="positionFields"></div>
        <input type="submit" name="submit1" value="Add">
        <input type="submit" name="cancel" value="Cancel">
      </form>
      <script>
      count=0;
      $(document).ready(function(){
        window.console && console.log("Document ready called");
        $('#position').click(function(event){
          event.preventDefault();
          if(count>=9){
            alert("Maximum positions reached");
            return;
          }
          count++;
          window.console && console.log("Adding position "+count);
          $('#positionFields').append(
            '<div id="posit'+count+'"> \
            <p>Year: <input type="text" name="year'+count+'"> \
            <input type="button" value="-" onclick="$(\'#posit'+count+'\').remove(); return false;"><br> \
            <textarea name="desc'+count+'" rows="5" cols="80"></textarea></p></div>');
        });
      });
      </script>
    </div>
  </body>
</html>
