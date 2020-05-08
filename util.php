<?php
  function validate(){
    if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 ||
        strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1) {

          return "All values are required";
    }
    if ( strpos($_POST['email'],'@') === false ) {
      return "Email address must contain @";
    }
    return false;
  }
?>
