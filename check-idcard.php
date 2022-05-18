<?php

require_once('config/config.php');
session_start();

if (isset($_POST['next'])) {

    if (empty($_POST['id_card_number'])) {
        $error = "ID Card Number is required";
    } else {
        $id_card_number = $_POST['id_card_number'];

        $check_data = $pdo->prepare("SELECT * FROM users WHERE id_card_number = :id_card_number");
        $check_data->bindParam(":id_card_number", $id_card_number);
        $check_data->execute();
        $getRow = $check_data->fetch(PDO::FETCH_ASSOC);
        $result = $check_data->rowCount();

        if ($result > 0) {
            $_SESSION = $getRow;
            header("Location: forgot-password.php");
        } else {
            $error = "This id card could not be found in the database";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="css/forgot_style.css">
    <title>Forgot Password Page</title>
</head>

<body>
    <div class="container right-panel-active">
        <!-- Sign Up -->
        <div class="container__form container--signup">
            <form action="check-idcard.php" class="form" method="post">
                <h3 class="form__title">Reset Password</h3>
                <p class="detail">Please enter your id card number</p>
				<?php if (isset($error)) { ?>
					<div class="alert alert-danger" role="alert">
						<?php {
							echo $error;
						}
						?>
					</div>
				<?php } ?>
                <?php if (isset($_SESSION['error'])) { ?>
					<div class="alert alert-danger" role="alert">
						<?php {
							echo $_SESSION['error'];
						}
						?>
					</div>
				<?php } ?>
                <p class="fieldset">
                    <label class="image-replace">ID Card Number</label>
                    <input class="full-width has-padding has-border" type="text" placeholder="ID Card Number" name="id_card_number">
                </p>
                <button type="submit" class="btn" name="next">Next</button>
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
