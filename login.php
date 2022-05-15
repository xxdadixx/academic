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
				unset($getRow['password']);
				$_SESSION = $getRow;
				header('location:welcome.php');
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
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<link rel="stylesheet" href="css/style.css">
	<title>Sign Up Page</title>
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
				<input type="text" placeholder="username" class="input mt-4" value="<?php if (isset($_COOKIE['user_login'])) {
																						echo $_COOKIE['user_login'];
																					} ?>" name="username" aria-describedby="username">
				<input type="password" placeholder="password" class="input" value="<?php if (isset($_COOKIE['user_password'])) {
																						echo $_COOKIE['user_password'];
																					} ?>" name="password" aria-describedby="password">
				<div class="mt-1">
					<label class="checkbox">
						<input type="checkbox" id="remember" <?php if (isset($_COOKIE['user_login'])) { ?> checked <?php } ?> name="remember"> Remember me
					</label>
					<a href="check-username.php" class="link1">Forgot password?</a>
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