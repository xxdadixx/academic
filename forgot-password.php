<?php

require_once('config/config.php');
session_start();

if (!isset($_SESSION['id_card_number'])) {
    echo $_SESSION['error'] = "Please enter ID Card Number!";
    header("Location: check-idcard.php");
}

if (isset($_POST['change-pass'])) {

    if (empty($_POST['np'])) {
        $error = "Password is required";
    } else if ($_POST['np'] !== $_POST['c_np']) {
        $error = "The confirmation password  does not match";
    } else if (strlen($_POST['np'])  > 20 || strlen($_POST['np']) < 8) {
        $error = 'Password must be between 8 and 20 characters long.';
    } else if (strlen($_POST['c_np'])  > 20 || strlen($_POST['c_np']) < 8) {
        $error = 'Password must be between 8 and 20 characters long.';
    } else if ($_POST['np'] !== $_POST['c_np']) {
        $error = "The confirmation password  does not match";
    } else {
        // hashing the password
        $query = "UPDATE users SET password=:password WHERE id=:id";
        $password_hash = password_hash($_POST["np"], PASSWORD_DEFAULT);
        $user_data = array(
            ':password'   => $password_hash,
            ':id'   => $_SESSION['id']
        );
        $statement = $pdo->prepare($query);
        $statement->execute($user_data);
        header("Location: login.php");
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="css/forgot_style.css">
    <title>Forgot Password Page</title>
</head>

<body>
    <div class="container right-panel-active">
        <!-- Sign Up -->
        <div class="container__form container--signup">
            <form action="forgot-password.php" class="form" method="post">
                <h3 class="form__title">Reset Password</h3>
                <p class="detail">Please enter your current password and enter a new password to change it </p>
                <?php if (isset($warring)) { ?>
                    <div class="alert alert-warning" role="alert">
                        <?php {
                            echo $warring;
                        }
                        ?>
                    </div>
                <?php } ?>
                <?php if (isset($error)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php {
                            echo $error;
                        }
                        ?>
                    </div>
                <?php } ?>
                <?php
                if (isset($errors) && count($errors) > 0) {
                    foreach ($errors as $error_msg) {
                        echo '<div class="alert alert-danger mt-3">' . $error_msg . '</div>';
                    }
                }
                ?>
                <p class="fieldset">
                    <label class="image-replace">ID Card Number</label>
                    <input class="full-width has-padding has-border disable" type="text" placeholder="ID Card Number" name="id_card_number" value="<?= $_SESSION['id_card_number'] ?>" disabled>
                </p>
                <p class="fieldset">
                    <label class="image-replace">New Password</label>
                    <input class="full-width has-padding has-border" type="password" placeholder="New Password" name="np">
                    <a href="#0" class="hide-password">Show</a>
                </p>
                <p class="fieldset">
                    <label class="image-replace">Confirm Password</label>
                    <input class="full-width has-padding has-border" type="password" placeholder="Confirm Password" name="c_np">
                    <a href="#0" class="hide-password">Show</a>
                </p>
                <button type="submit" class="btn" name="change-pass">Change Password</button>
                <div align="center" style="margin-top:10px;">
                    <i class="bi bi-arrow-left"></i>
                    <a href="login.php" class="link2" style="text-decoration: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"></path>
                            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"></path>
                        </svg>Back to login</a>
                </div>
            </form>
        </div>
        <!-- Overlay -->
        <div class="container__overlay">
            <div class="overlay">
                <div class="overlay__panel overlay--left">
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<script>
    //hide or show password
    $('.hide-password').on('click', function() {
        var $this = $(this),
            $password_field = $this.prev('input');

        ('password' == $password_field.attr('type')) ? $password_field.attr('type', 'text'): $password_field.attr('type', 'password');
        ('Show' == $this.text()) ? $this.text('Hide'): $this.text('Show');
        //focus and move cursor to the end of input field
        $password_field.putCursorAtEnd();
    });
</script>