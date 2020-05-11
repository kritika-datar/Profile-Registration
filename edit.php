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

        if ( isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {
          // Data validation
          $msg = validate();
          if(is_string($msg)){
              $_SESSION['error'] = $msg;
              header("Location: edit.php?profile_id=".$_POST['profile_id']);
              return;
          }

          $str = validateP();
          if(is_string($str)){
            $_SESSION['error'] = $str;
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


          $var = $pdo->prepare('DELETE from position where profile_id= :pid');
          $var->execute(array(':pid' => $_GET['profile_id']));

          $rank = 1;
          for($i=1;$i<=9;$i++){
              if(isset($_POST['year'.$i])){
                $pstmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');

                $pstmt->execute(array(
                  ':pid' => $_GET['profile_id'],
                  ':rank' => $rank,
                  ':year' => $_POST['year'.$i],
                  ':desc' => $_POST['desc'.$i])
                );
                $rank++;
              }
          }

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
        //if ( $stmt->rowCount() == true ) {
      ?>
          <form method='post'>
            <input type="text" value="<?php echo $_GET['profile_id'] ?>" name="profile_id" hidden>
            <label>First Name: </label><input type="text" name="first_name" size="50" value="<?php echo $row['first_name'] ?>" id="idf_name"><br><br>
            <label>Last Name: </label><input type="text" name="last_name" size="50" value="<?php echo $row['last_name'] ?>" id="idl_name"><br><br>
            <label>Email: </label><input type="text" name="email" size="50" value="<?php echo $row['email'] ?>" id="idemail"><br><br>
            <label>Headline: </label><br><input type="text" name="headline" size="80" value="<?php echo $row['headline'] ?>" id="idheadline"><br><br>
            <label>Summary: </label><br><textarea rows="4" cols="50" id="idsummary" name="summary"><?php echo $row['summary'] ?></textarea><br><br>
            <label>Positions: </label><input type="submit" id="position" value="+">
            <div id="positionFields">
            <?php
              $pos = 0;
              $ps = $pdo->prepare('SELECT * FROM position where profile_id= :pi');
              $ps->execute(array( ':pi' => $_GET['profile_id']));

              if($ps->rowCount() == true){
                while($row1 = $ps->fetch(PDO::FETCH_ASSOC)){
                  $pos++;
                  echo "<div id='posit".$pos."'><p><input type='text' name='year".$pos."' value='".$row1['year']."'>
                        <input type='button' value='-' onclick='$(\"#posit".$pos."\").remove(); return false;'><br>";
                  echo "<textarea name='desc".$pos."' rows='5' cols='80'>".$row1['description']."</textarea></p></div>";
                }
              }
            ?>
            </div>
            <input type="submit" name="submit2" value="Save">
            <input type="submit" name="cancel" value="Cancel">
          </form>
        <?php
    //}
      $pdo = null;
      ?>

      <script>
      count= <?=$pos ?> ;
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
