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

  function validateP(){
    for($i=1;$i<=9;$i++){
      if(!isset($_POST['year'.$i])) continue;
      if(!isset($_POST['desc'.$i])) continue;

      if(strlen($_POST['year'.$i]) < 1 || strlen($_POST['desc'.$i]) < 1){
        return "All values are required";
      }

      if(!is_numeric($_POST['year'.$i])){
        return "Year must be numeric";
      }
    }
    return true;
  }

  function validateEdu(){
    for($i=1;$i<=9;$i++){
      if(!isset($_POST['edu_year'.$i])) continue;
      if(!isset($_POST['edu_school'.$i])) continue;

      if(strlen($_POST['edu_year'.$i]) < 1 || strlen($_POST['edu_school'.$i]) < 1){
        return "All values are required";
      }

      if(!is_numeric($_POST['edu_year'.$i])){
        return "Year must be numeric";
      }
    }
    return true;
  }
?>
