<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Forgot Password Page</title>
</head>

<body>
    <div class="container right-panel-active">
        <!-- Sign Up -->
        <div class="container__form container--signup">
            <form action="forgot-password.php" class="form" id="form1" method="post">
                <h2 class="form__title">Reset Password</h2>
                <input type="password" placeholder="new password" class="input" name="pass1" aria-describedby="pass1">
                <input type="password" placeholder="confirm new password" class="input" name="pass2" aria-describedby="pass2">
                <button type="submit" class="btn" name="reset">Reset</button>
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