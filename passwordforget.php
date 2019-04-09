<?php include('inc/pdo.php'); ?>
<?php include('inc/functions.php'); ?>
<?php include('inc/requests.php'); ?>
<?php

$title = 'Password Forgotten';
$error = array();


if (!empty($_POST['submitted'])) {
  $email = trim(strip_tags($_POST['passwordforget']));

  if (!empty($email)){
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error['email'] = '<span style="color:red">' . 'Please enter a valid e-mail address.' . '</span>';
      }
      else {
        $sql = "SELECT email, token FROM yjlv_users WHERE email = :email";
        $query = $pdo->prepare($sql);
        $query->bindValue(':email',$email, PDO::PARAM_STR);
        $query->execute();
        $user = $query -> fetch();

        if (!empty($user)) {
          $body = '<p>Please check the link below :</p>';
          $body .= '<a href="passwordmodif.php?email='.urlencode($user['email']).'&token='.urlencode($user['token']).'">here !</a>';

          echo $body;
        }
      }
  } else {
    $error['email'] = '<span style="color:red">' . 'Please enter your e-mail address.' . '</span>';
  }
}

include('inc/header.php'); ?>


<div class="wrap">
  <form class="passwordforget" action="" method="post">

      <label for="">Your e-mail address :</label>
      <input type="email" name="passwordforget" value="">

      <input type="submit" name="submitted" id="submit" value="Send">

  </form>
</div>
















<?php include('inc/footer.php');
