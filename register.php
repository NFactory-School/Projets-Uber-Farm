<?php include('inc/pdo.php'); ?>
<?php include('inc/functions.php'); ?>
<?php include('inc/requests.php'); ?>
<?php $title = 'Register';

$error = array();
$success = false;

//Submitted Form ?
if (!empty($_POST['submitted']) ) {
    // Protection faille XSS
    $email               = trim(strip_tags($_POST['email']));
    $firstlastname       = trim(strip_tags($_POST['firstlastname']));
    $password            = trim(strip_tags($_POST['password']));
    $password2           = trim(strip_tags($_POST['password2']));

    // verification email
    if (!empty($email)){
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo '';
        }
        else {
            $sql = "SELECT email FROM yjlv_users WHERE email = :email";
            $query = $pdo->prepare($sql);
            $query->bindValue(':email',$email, PDO::PARAM_STR);
            $query->execute();
            $loginEmail = $query -> fetch();
            if (!empty($loginEmail)) {
                $error['email'] = 'This e-mail address is already used.';
            }
        }
    } else {
        $error['email'] = '<span style="color:red">' . 'Please enter a valid e-mail address.' . '</span>';
    }


    //verification login
    if (!empty($firstlastname)){
        if(strlen($firstlastname) < 5 ) {
            $error['firstlastname'] = 'Your full name has to comport at least 5 characters.';
        } elseif(strlen($firstlastname) > 255) {
            $error['firstlastname'] = 'Your full name has to comport at most 255 characters.';
        }
        else {
            // C'est ici que l'on fait la requete
            $sql = "SELECT firstlastname FROM yjlv_users WHERE firstlastname = :firstlastname";
            $query = $pdo->prepare($sql);
            $query->bindValue(':firstlastname',$firstlastname, PDO::PARAM_STR);
            $query->execute();
            $loginlogin = $query -> fetch();
            if (!empty($loginfirstlastname)) {
                $error['firstlastname'] = 'It seems that your name is already used.';
            }
        }
    } else {
        $error['firstlastname'] = 'Please enter your full name.';
    }

    // verification password
    if (!empty($password)){
        if(strlen($password) < 6 ) {
            $error['password'] = 'Your password is too short. (Min 6 characters)';
        } elseif(strlen($password) > 255) {
            $error['password'] = 'Your password is too long. (Max 255 characters)';
        }
    } else {
        $error['password'] = 'Please enter a password.';
    }

    // verification password2
    if (!empty($password2)){
        if($password2 !== $password) {
            $error['password2'] = 'Passwords do not match.';
        }
    } else {
        $error['password2'] = 'Please rewrite the password above.';
    }


    // Si aucune error
    if (count($error) == 0){
        $success = true;
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $token = generateRandomString(120);
        $sql = "INSERT INTO yjlv_users (email,firstlastname,role,password,token, created_at)
              VALUES (:email, :firstlastname, 'user', :password, '$token', NOW())";
        // preparation de la requête
        $query = $pdo->prepare($sql);
        // Protection injections SQL
        $query->bindValue(':email',$email, PDO::PARAM_STR);
        $query->bindValue(':firstlastname',$firstlastname, PDO::PARAM_STR);
        $query->bindValue(':password',$hash, PDO::PARAM_STR);

        // execution de la requête preparé
        $query->execute();
        // redirection vers page connexion
        header('Location: connexion.php');
    }

}

?>
<?php include('inc/header.php'); ?>

    <div class="wrap">
        <div class="container">
            <h2>Inscription</h2>

            <form class="/" action="" method="post">

                <label for="firstlastname">Your Full Name* :</label><br>
                <input class="loginsignup" type="text" name="firstlastname" id="firstlastname" value="<?php if(!empty($_POST['firstlastname'])) {echo $_POST['firstlastname']; } ?>">
                <span class="error" style="color:red"><?php if(!empty($error['firstlastname'])) { echo $error['firstlastname']; } ?></span><br><br>

                <label for="email">Your e-mail address* :</label><br>
                <input class="loginsignup" type="email" name="email" id="email" value="<?php if(!empty($_POST['email'])) {echo $_POST['email'];} ?>">
                <span class="error" style="color:red"><?php if(!empty($error['email'])) { echo $error['email']; } ?></span><br><br>

                <label for="password">Your Password* :</label><br>
                <input class="loginsignup" type="password" name="password" id="password" value="<?php if(!empty($_POST['password'])) {echo $_POST['password'];} ?>">
                <span class="error" style="color:red"><?php if(!empty($error['password'])) { echo $error['password']; } ?></span><br><br>

                <label for="password2">Confirm your password* :</label><br>
                <input class="loginsignup" type="password" name="password2" id="password2" value="<?php if(!empty($_POST['password2'])) {echo $_POST['password2'];} ?>">
                <span class="error" style="color:red"><?php if(!empty($error['password2'])) { echo $error['password2']; } ?></span><br><br>

                <input class="loginsignup" type="submit" name="submitted" id="submit" value="Register">
                <p><span class="needed">* = Mandatory Fields</span></p>

            </form>

        </div>
    </div>



<?php include('inc/footer.php');
