<?php
session_start();
require_once('config/config.php');

if (isset($_POST['login'])) {
	if (isset($_POST['username'], $_POST['password']) && !empty($_POST['username']) && !empty($_POST['password'])) {
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);

		$sql = "SELECT * FROM users WHERE username = :username ";
		$handle = $pdo->prepare($sql);
		$params = ['username' => $username];
		$handle->execute($params);
		$getRow = $handle->fetch(PDO::FETCH_ASSOC);

		if ($getRow) {
			$_SESSION['user_id'] = $getRow['id'];
			if (!empty($_POST['remember'])) {
				setcookie('user_login', $_POST['username'], time() + (10 * 365 * 24 * 60 * 60));
				setcookie('user_password', $_POST['password'], time() + (10 * 365 * 24 * 60 * 60));
			} else {
				if (isset($_COOKIE['user_login'])) {
					setcookie('user_login', '');

					if (isset($_COOKIE['user_password'])) {
						setcookie('user_password', '');
					}
				}
			}
		}

		if ($handle->rowCount() > 0) {
			if (password_verify($password, $getRow['password'])) {
				$_SESSION = $getRow;
				header('location:home.php');
				exit();
			} else {
				$errors[] = "Wrong username or Password";
			}
		} else {
			$errors[] = "Wrong username or Password";
		}
	} else {
		$errors[] = "Username and Password are required";
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
	<link rel="stylesheet" href="css/login_style.css">
	<title>Sign in Page</title>
</head>

<body>
	<div class="container right-panel-active">
		<!-- Sign Up -->
		<div class="container__form container--signup">
			<form action="login.php" class="form" method="post">
				<img src="https://i.pinimg.com/564x/fc/28/1f/fc281fe7423ce9776ed5fbb36a62cb57.jpg" width="100" height="100" alt="logo">
				<?php
				if (isset($errors) && count($errors) > 0) {
					foreach ($errors as $error_msg) {
						echo '<div class="alert alert-danger mt-3">' . $error_msg . '</div>';
					}
				}
				?>

				<?php if (isset($_SESSION['error'])) { ?>
					<div class="alert alert-danger mt-3" role="alert">
						<?php {
							echo $_SESSION['error'];
						}
						?>
					</div>
				<?php } ?>

				<?php if (isset($_SESSION['success'])) { ?>
					<div class="alert alert-success mt-3" role="alert">
						<?php {
							echo $_SESSION['success'];
						}
						?>
					</div>
				<?php } ?>
				<p class="fieldset mt-4">
					<label class="image-replace username">Username</label>
					<input class="full-width has-padding has-border" type="text" placeholder="Username" value="<?php if (isset($_COOKIE['user_login'])) {
																													echo $_COOKIE['user_login'];
																												} ?>" name="username">
				</p>
				<p class="fieldset">
					<label class="image-replace password">Password</label>
					<input class="full-width has-padding has-border" type="password" placeholder="Password" value="<?php if (isset($_COOKIE['user_password'])) {
																														echo $_COOKIE['user_password'];
																													} ?>" name="password">
					<a href="#0" class="hide-password">Show</a>
				</p>
				<div class="mt-1">
					<label class="checkbox">
						<input type="checkbox" id="remember" <?php if (isset($_COOKIE['user_login'])) { ?> checked <?php } ?> name="remember"> Remember me
					</label>
					<a href="check-idcard.php" class="link1">Forgot password?</a>
				</div>
				<button type="submit" class="btn" name="login">Login</button>
				<a href="register.php" class="btn">Register</a>
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

<?php

if (isset($_SESSION['error'])) {
	unset($_SESSION['error']);
} else if (isset($_SESSION['warning'])) {
	unset($_SESSION['warning']);
} else if (isset($_SESSION['success'])) {
	unset($_SESSION['success']);
}

?>

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