<?php include('inc/pdo.php'); ?>
<?php include('inc/functions.php'); ?>
<?php include('inc/requests.php'); ?>

<?php


$error = array();
$success = false;

//Submitted Form ?
if (!empty($_POST['submitted'])) {

    // Protection faille XSS
    $login      = trim(strip_tags($_POST['login']));
    $password   = trim(strip_tags($_POST['password']));

//Requete sur identité utilisateur (s'il existe)
    $sql   = "SELECT *
            FROM yjlv_users
            WHERE email = :login";
    $query = $pdo -> prepare($sql);
    $query -> bindValue(':login', $login, PDO::PARAM_STR);
    $query -> execute();
    $user = $query -> fetch();

    if (!empty($user)) {
        if (!password_verify($password, $user['password'])) {
            $error['password'] = 'Wrong password';
        }
    } else {
        $error['login'] = 'Please register before login.';
    }

    if (count($error) == 0) {

        $success = true;
        $_SESSION['yjlv_users'] = array(
            'id'      => $user['id'],
            'email'   => $user['email'],
            'role'    => $user['role'],
            'ip'      => $_SERVER['REMOTE_ADDR']
        );
        if (isAdmin()) {
            header('Location: dashboard.php');
        } else {
            header('Location: index.php');
        }
    }
}


$title = 'Login'; ?>
<?php include('inc/header.php'); ?>

    <div class="wrap">

        <h2>Login</h2>

        <form class="login" action="" method="post">

            <label for="login" >Your e-mail address* :</label><br>
            <input class="loginsignup" type="text" name="login" id="login" value="<?php if(!empty($_POST['login'])) {echo $_POST['login']; } ?>">
            <span class="error" style="color:red"><?php if(!empty($error['login'])) { echo $error['login']; } ?></span><br><br>

            <label for="password" >Your password* :</label><br>
            <input class="loginsignup" type="password" name="password" id="password" value="">
            <span class="error" style="color:red"><?php if(!empty($error['password'])) { echo $error['password']; } ?></span><br><br>

            <input class="loginsignup" type="submit" name="submitted" id="submit" value="Login">
            <p><span class="needed"><strong>* = Mandatory Fields</strong></span></p>
            <p id="forgottenpassword"><a href="passwordforget.php">Password Forgotten ?</a></p>
            <p id="notsignup"><span><em>Not registered ? Clic <a href="inscription.php">here </a>!</em></span></p>
        </form>


    </div>

<?php include('inc/footer.php');
