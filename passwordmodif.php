<?php include('inc/pdo.php'); ?>
<?php include('inc/functions.php'); ?>
<?php include('inc/requests.php'); ?>
<?php

if(isLogged()) {
        $id = $_SESSION['yjlv_users']['id'];
        //Requête BDD affichage données utilisateur
        $user = showConnectedUserInfo($id);

$error = array();


    if(!empty($user['email']) && !empty($user['token'])) {
      $email = $user['email'];
      $token = $user['token'];
      $sql = "SELECT id FROM yjlv_users WHERE email = :email AND token = :token";
      $query = $pdo -> prepare($sql);
      $query -> bindValue(':email', $email, PDO::PARAM_STR);
      $query -> bindValue(':token', $token, PDO::PARAM_STR);
      $query -> execute();
      $user = $query -> fetch();


        if (!empty($user)) {
          if (!empty($_POST['submitted']) ) {
              // Protection faille XSS
              $oldpassword    = trim(strip_tags($_POST['oldpassword']));
              $password       = trim(strip_tags($_POST['password']));
              $password2      = trim(strip_tags($_POST['password2']));

              //Vérification des champs du formulaire
              verifyOldPassword($error, $user);
              verificationfullField($error, $password, 'password', 6, 255);
              verifySamePassword($error);

              //Si aucune erreur
              if (count($error) == 0) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $token = generateRandomString(120);
                $sql = "UPDATE yjlv_users SET password = :password, token = :token WHERE id = :id";
                $query = $pdo -> prepare($sql);
                $query -> bindValue(':password', $hash, PDO::PARAM_STR);
                $query -> bindValue(':token', $token, PDO::PARAM_STR);
                $query -> bindValue(':id', $user['id'], PDO::PARAM_INT);
                $query -> execute();
                header('Location: disconnection.php');
              }
            }
          } else {
            die('404 1');
          }
    } else {
      die('404 2');
    }
} else {
  die('404 3');
}

$title = 'Modify your password'?>
<?php include('inc/header.php'); ?>

<div class="wrap">

  <form class="passwordmodif" action="" method="post">

      <label for="">Your old password : </label><br>
      <input type="password" name="oldpassword" value=""><br>

      <label for="">Your new password: </label><br>
      <input type="password" name="password" value=""><br>

      <label for="">Confirm your new password : </label><br>
      <input type="password" name="password2" value=""><br>

      <input type="submit" name="submitted" id="submit" value="Modify">

  </form>

</div>


<?php include('inc/footer.php'); ?>
