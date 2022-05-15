<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Forgot Password Page</title>
</head>

<body>
    <div class="container right-panel-active">
        <!-- Sign Up -->
        <div class="container__form container--signup">
            <form action="check-email.php" class="form" id="form1" method="post">
                <h2 class="form__title">Reset Password</h2>
                <input type="text" placeholder="please input email for password reset" class="input" name="email" aria-describedby="email">
                <button type="submit" class="btn" name="send_link">Send Reset Link</button>
            </form>
        </div>
        <!-- Overlay -->
        <div class="container__overlay">
            <div class="overlay">
                <div class="overlay__panel overlay--left">
                    <button onclick="location.href='login.php'" class="btn">Login</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>