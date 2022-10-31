<?php
session_start();

// checking if it submits
if (
  isset($_POST['username']) &&
  isset($_POST['password'])
) {

  //data base
  include '../dbconn.php';

  // getting data post 
  $password = $_POST['password'];
  $username = $_POST['username'];

  //Validation
  if (empty($username)) {
    //error message
    $em = "Username is required";

    //error message 
    header("Location: ../../index.php?error=$em");
  } else if (empty($password)) {
    //error message 
    $em = "Password is required";

    //error message 
    header("Location: ../../index.php?error=$em");
  } else {
    $sql  = "SELECT * FROM 
               users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username]);

    //checkig if username exist
    if ($stmt->rowCount() === 1) {
      $user = $stmt->fetch();

      //is usersname equal
      if ($user['username'] === $username) {

        // verify password
        if (password_verify($password, $user['password'])) {

          //successfully logged in
          //SESSION 
          $_SESSION['username'] = $user['username'];
          $_SESSION['firstname'] = $user['firstname'];
          $_SESSION['user_id'] = $user['user_id'];


          header("Location: ../../home.php");
        } else {
          // error message
          $em = "Incorect Username or password";

          header("Location: ../../index.php?error=$em");
        }
      } else {
        //  message
        $em = "Incorect Username or password";

        header("Location: ../../index.php?error=$em");
      }
    }
  }
} else {
  header("Location: ../../index.php");
  exit;
}
