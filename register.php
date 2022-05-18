<?php

require_once('config/config.php');
if (isset($_POST["email"])) {
	$username = $_POST['username'];
	$email = $_POST['email'];
	$idcard = $_POST['id_card_number'];

	$check_data = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email OR id_card_number = :idcard");
	$check_data->bindParam(":username", $username);
	$check_data->bindParam(":email", $email);
	$check_data->bindParam(":idcard", $idcard);
	$check_data->execute();
	$result = $check_data->rowCount();

	if ($result > 0) {
		$warning = "The email or username or id card number already exists in the database.";
	}
	try {
		if (!isset($error) && !isset($warning)) {
			if (isset($_FILES['image']['name']) and !empty($_FILES['image']['name'])) {

				$img_name = $_FILES['image']['name'];
				$tmp_name = $_FILES['image']['tmp_name'];

				if (!isset($error)) {
					$img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
					$img_ex_to_lc = strtolower($img_ex);

					$allowed_exs = array('jpg', 'jpeg', 'png');
					if (in_array($img_ex_to_lc, $allowed_exs)) {
						$new_img_name = uniqid($username, true) . '.' . $img_ex_to_lc;
						$img_upload_path = 'upload/' . $new_img_name;
						move_uploaded_file($tmp_name, $img_upload_path);

						// Insert into Database
						$query = "INSERT INTO users (firstname, lastname, username ,email, password, phone_number, birthday, id_card_number, image) VALUES (:firstname, :lastname, :username, :email, :password, :phone_number, :birthday, :id_card_number, :image)";
						$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
						$user_data = array(
							':firstname'  => $_POST["firstname"],
							':lastname'  => $_POST["lastname"],
							':username'   => $_POST["username"],
							':email'   => $_POST["email"],
							':password'   => $password_hash,
							':birthday'  => $_POST["birthday"],
							':phone_number'  => $_POST["phone_number"],
							':id_card_number'  => $_POST["id_card_number"],
							':image'  => $new_img_name
						);
						$statement = $pdo->prepare($query);
						$statement->execute($user_data);
						$success = "Register finish! <a href='login.php' class='alert-link'>Login</a>";
					} else {
						$error = "You can't upload files of this type";
					}
				} else {
					$error = "unknown error occurred!";
				}
			} else {
				$query = "INSERT INTO users (firstname, lastname, username ,email, password, phone_number, birthday, id_card_number) VALUES (:firstname, :lastname, :username, :email, :password, :phone_number, :birthday, :id_card_number)";
				$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
				$user_data = array(
					':firstname'  => $_POST["firstname"],
					':lastname'  => $_POST["lastname"],
					':username'   => $_POST["username"],
					':email'   => $_POST["email"],
					':password'   => $password_hash,
					':birthday'  => $_POST["birthday"],
					':phone_number'  => $_POST["phone_number"],
					':id_card_number'  => $_POST["id_card_number"]
				);
				$statement = $pdo->prepare($query);
				$statement->execute($user_data);
				$success = "Register finish! <a href='login.php' class='alert-link'>Login</a>";
			}
		}
	} catch (PDOException $e) {
		$errors[] = $e->getMessage();
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
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<script src="sweetalert2.min.js"></script>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="css/regis_style.css">
	<style>
		.active_tab1 {
			background-color: #FFFFFF;
			color: #333;
			font-weight: 600;
		}

		.inactive_tab1 {
			background-color: #EFEFEF;
			color: #333;
			cursor: not-allowed;
		}

		.has-error {
			background-color: #ffff99;
		}
	</style>

	<title>Register Page</title>
</head>

<body>
	<div class="container">
		<!-- Sign In -->
		<div class="container__form container--signin">

			<form method="post" class="form" id="register_form" enctype="multipart/form-data">
				<img src="https://i.pinimg.com/564x/fc/28/1f/fc281fe7423ce9776ed5fbb36a62cb57.jpg" style="margin-top:-30px;" width="100" height="100" alt="logo">
				<?php if (isset($success)) { ?>
					<div class="alert alert-success" style="margin-bottom:-25px;" role="alert">
						<?php {
							echo $success;
						}
						?>
					</div>
				<?php } ?>
				<?php if (isset($warning)) { ?>
					<div class="alert alert-warning" style="margin-bottom:-25px;" role="alert">
						<?php {
							echo $warning;
						}
						?>
					</div>
				<?php } ?>
				<?php if (isset($error)) { ?>
					<div class="alert alert-danger" style="margin-bottom:-25px;" role="alert">
						<?php {
							echo $error;
						}
						?>
					</div>
				<?php } ?>
				<ul class="nav nav-tabs" style="margin-top:30px;">
					<li class="nav-item">
						<a class="active_tab1" style="border:1px solid #ccc" id="list_login_details">Login Details</a>
					</li>
					<li class="nav-item">
						<a class="inactive_tab1" id="list_personal_details" style="border:1px solid #ccc">Personal Details</a>
					</li>
					<li class="nav-item">
						<a class="inactive_tab1" id="list_contact_details" style="border:1px solid #ccc">Contact Details</a>
					</li>
				</ul>
				<div class="tab-content" style="margin-top:16px;">
					<div class="tab-pane active" id="login_details">
						<div>
							<div>
								<p class="fieldset" style="margin-top:10px;">
									<label class="image-replace">Username</label>
									<input class="full-width has-padding has-border" type="text" placeholder="Username" name="username" id="username">
									<span id="error_username" class="text-danger"></span>
								</p>
								<p class="fieldset">
									<label class="image-replace">Password</label>
									<input class="full-width has-padding has-border" type="password" placeholder="Password" name="password" id="password">
									<a href="#0" class="hide-password">Show</a>
									<span id="error_password" class="text-danger"></span>
								</p>
								<p class="fieldset">
									<label class="image-replace">Confirm Password</label>
									<input class="full-width has-padding has-border" type="password" placeholder="Confirm Password" name="c_Password" id="c_password">
									<a href="#0" class="hide-password">Show</a>
									<span id="error_c_password" class="text-danger"></span>
								</p>
								<br />
								<div align="center">
									<button type="button" name="btn_login_details" id="btn_login_details" class="btn">Next</button>
								</div>
								<div align="center" style="margin-top:10px;">
									<i class="bi bi-arrow-left"></i>
									<a href="login.php" class="link2" style="text-decoration: none;">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
											<path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"></path>
											<path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"></path>
										</svg>Back to login</a>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="personal_details">
						<div>
							<div>
								<p class="fieldset" style="margin-top:10px;">
									<label class="image-replace">Firstname</label>
									<input class="full-width has-padding has-border" type="text" placeholder="Firstname" name="firstname" id="firstname">
									<span id="error_firstname" class="text-danger"></span>
								</p>
								<p class="fieldset">
									<label class="image-replace">Lastname</label>
									<input class="full-width has-padding has-border" type="text" placeholder="Lastname" name="lastname" id="lastname">
									<span id="error_lastname" class="text-danger"></span>
								</p>
								<p class="fieldset">
									<label class="image-replace">Birthday</label>
									<input class="full-width has-padding has-border" type="date" name="birthday" id="birthday">
									<span id="error_birthday" class="text-danger"></span>
								</p>
								<p class="fieldset">
									<label class="image-replace">ID Card number</label>
									<input class="full-width has-padding has-border" type="text" placeholder="ID Card number" name="id_card_number" id="id_card_number">
									<span id="error_idcard" class="text-danger"></span>
								</p>
								<br />
								<div align="center">
									<button type="button" name="previous_btn_personal_details" id="previous_btn_personal_details" class="btn">Previous</button>
									<button type="button" name="btn_personal_details" id="btn_personal_details" class="btn">Next</button>
								</div>
								<div align="center" style="margin-top:20px;">
									<i class="bi bi-arrow-left"></i>
									<a href="login.php" class="link2" style="text-decoration: none;">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
											<path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"></path>
											<path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"></path>
										</svg>Back to login</a>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="contact_details">
						<div>
							<div>
							<p class="fieldset">
									<label class="image-replace">Phone number</label>
									<input class="full-width has-padding has-border" type="text" placeholder="Phone number" name="phone_number" id="phone_number">
									<span id="error_phone_number" class="text-danger"></span>
								</p>
								<p class="fieldset">
									<label class="image-replace">Email</label>
									<input class="full-width has-padding has-border" type="text" placeholder="Email" name="email" id="email">
									<span id="error_email" class="text-danger"></span>
								</p>
								<p class="fieldset">
									<label class="image-replace">Image</label>
									<input class="full-width has-padding has-border" type="file" name="image">
								</p>
								<br />
								<div align="center">
									<button type="button" name="previous_btn_contact_details" id="previous_btn_contact_details" class="btn">Previous</button>
									<button type="button" name="btn_contact_details" id="btn_contact_details" class="btn">Register</button>
								</div>
								<div align="center" style="margin-top:20px;">
									<i class="bi bi-arrow-left"></i>
									<a href="login.php" class="link2" style="text-decoration: none;">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
											<path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"></path>
											<path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"></path>
										</svg>Back to login</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>


		</div>

		<!-- Overlay -->
		<div class="container__overlay">
			<div class="overlay">
				<div class="overlay__panel overlay--right">
				</div>
			</div>
		</div>
	</div>
</body>

</html>

<?php

if (isset($error)) {
	unset($error);
} else if (isset($warning)) {
	unset($warning);
} else if (isset($success)) {
	unset($success);
}

?>

<script>
	$(document).ready(function() {
		$('#btn_login_details').click(function() {

			var error_username = '';
			var error_password = '';
			var error_c_password = '';


			if ($.trim($('#username').val()).length == 0) {
				error_username = 'Username is required';
				$('#error_username').text(error_username);
				$('#username').addClass('has-error');
			} else {
				error_username = '';
				$('#error_username').text(error_username);
				$('#username').removeClass('has-error');
			}

			if ($.trim($('#password').val()).length == 0) {
				error_password = 'Password is required';
				$('#error_password').text(error_password);
				$('#password').addClass('has-error');
			} else if ($.trim($('#password').val()).length < 8 || $.trim($('#password').val()).length > 20) {
				error_password = 'password must be between 8-20 characters';
				$('#error_password').text(error_password);
				$('#password').removeClass('has-error');
			} else {
				error_password = '';
				$('#error_password').text(error_password);
				$('#password').removeClass('has-error');
			}

			if ($.trim($('#c_password').val()).length == 0) {
				error_c_password = 'Confirm password is required';
				$('#error_c_password').text(error_c_password);
				$('#c_password').addClass('has-error');
			} else if ($.trim($('#c_password').val()).length < 8 || $.trim($('#c_password').val()).length > 20) {
				error_c_password = 'password must be between 8-20 characters';
				$('#error_c_password').text(error_c_password);
				$('#c_password').removeClass('has-error');
			} else if ($.trim($('#password').val()) !== $.trim($('#c_password').val())) {
				error_c_password = 'The password does not match';
				$('#error_c_password').text(error_c_password);
				$('#c_password').removeClass('has-error');
			} else {
				error_c_password = '';
				$('#error_c_password').text(error_c_password);
				$('#c_password').removeClass('has-error');
			}

			if (error_username != '' || error_password != '' || error_c_password != '') {
				return false;
			} else {
				$('#list_login_details').removeClass('active active_tab1');
				$('#list_login_details').removeAttr('href data-toggle');
				$('#login_details').removeClass('active');
				$('#list_login_details').addClass('inactive_tab1');
				$('#list_personal_details').removeClass('inactive_tab1');
				$('#list_personal_details').addClass('active_tab1 active');
				$('#list_personal_details').attr('href', '#personal_details');
				$('#list_personal_details').attr('data-toggle', 'tab');
				$('#personal_details').addClass('active in');
			}
		});

		$('#previous_btn_personal_details').click(function() {
			$('#list_personal_details').removeClass('active active_tab1');
			$('#list_personal_details').removeAttr('href data-toggle');
			$('#personal_details').removeClass('active in');
			$('#list_personal_details').addClass('inactive_tab1');
			$('#list_login_details').removeClass('inactive_tab1');
			$('#list_login_details').addClass('active_tab1 active');
			$('#list_login_details').attr('href', '#login_details');
			$('#list_login_details').attr('data-toggle', 'tab');
			$('#login_details').addClass('active in');
		});

		$('#btn_personal_details').click(function() {
			var error_firstname = '';
			var error_lastname = '';
			var error_birthday = '';
			var error_idcard = '';
			var idcard_validation = /^\d{13}$/;

			if ($.trim($('#firstname').val()).length == 0) {
				error_firstname = 'First Name is required';
				$('#error_firstname').text(error_firstname);
				$('#firstname').addClass('has-error');
			} else {
				error_firstname = '';
				$('#error_firstname').text(error_firstname);
				$('#firstname').removeClass('has-error');
			}

			if ($.trim($('#lastname').val()).length == 0) {
				error_lastname = 'Last Name is required';
				$('#error_lastname').text(error_lastname);
				$('#lastname').addClass('has-error');
			} else {
				error_lastname = '';
				$('#error_lastname').text(error_lastname);
				$('#lastname').removeClass('has-error');
			}

			if ($.trim($('#birthday').val()).length == 0) {
				error_birthday = 'Birthday is required';
				$('#error_birthday').text(error_birthday);
				$('#birthday').addClass('has-error');
			} else {
				error_birthday = '';
				$('#error_birthday').text(error_birthday);
				$('#birthday').removeClass('has-error');
			}


			if ($.trim($('#id_card_number').val()).length == 0) {
				error_idcard = 'ID Card number is required';
				$('#error_idcard').text(error_idcard);
				$('#id_card_number').addClass('has-error');
			} else {
				if (!idcard_validation.test($('#id_card_number').val())) {
					error_idcard = 'Invalid ID Card number';
					$('#error_idcard').text(error_idcard);
					$('#id_card_number').addClass('has-error');
				} else {
					error_idcard = '';
					$('#error_idcard').text(error_idcard);
					$('#id_card_number').removeClass('has-error');
				}
			}

			if (error_firstname != '' || error_lastname != '' || error_birthday != '' || error_idcard != '') {
				return false;
			} else {
				$('#list_personal_details').removeClass('active active_tab1');
				$('#list_personal_details').removeAttr('href data-toggle');
				$('#personal_details').removeClass('active');
				$('#list_personal_details').addClass('inactive_tab1');
				$('#list_contact_details').removeClass('inactive_tab1');
				$('#list_contact_details').addClass('active_tab1 active');
				$('#list_contact_details').attr('href', '#contact_details');
				$('#list_contact_details').attr('data-toggle', 'tab');
				$('#contact_details').addClass('active in');
			}
		});

		$('#previous_btn_contact_details').click(function() {
			$('#list_contact_details').removeClass('active active_tab1');
			$('#list_contact_details').removeAttr('href data-toggle');
			$('#contact_details').removeClass('active in');
			$('#list_contact_details').addClass('inactive_tab1');
			$('#list_personal_details').removeClass('inactive_tab1');
			$('#list_personal_details').addClass('active_tab1 active');
			$('#list_personal_details').attr('href', '#personal_details');
			$('#list_personal_details').attr('data-toggle', 'tab');
			$('#personal_details').addClass('active in');
		});

		$('#btn_contact_details').click(function() {
			var error_phone_number = '';
			var error_email = '';
			var phonenumber_validation = /^\d{10}$/;
			var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

			if ($.trim($('#phone_number').val()).length == 0) {
				error_phone_number = 'Phone number is required';
				$('#error_phone_number').text(error_phone_number);
				$('#phone_number').addClass('has-error');
			} else {
				if (!phonenumber_validation.test($('#phone_number').val())) {
					error_phone_number = 'Invalid Phone number';
					$('#error_phone_number').text(error_phone_number);
					$('#phone_number').addClass('has-error');
				} else {
					error_phone_number = '';
					$('#error_phone_number').text(error_phone_number);
					$('#phone_number').removeClass('has-error');
				}
			}


			if ($.trim($('#email').val()).length == 0) {
				error_email = 'Email is required';
				$('#error_email').text(error_email);
				$('#email').addClass('has-error');
			} else {
				if (!filter.test($('#email').val())) {
					error_email = 'Invalid Email';
					$('#error_email').text(error_email);
					$('#email').addClass('has-error');
				} else {
					error_email = '';
					$('#error_email').text(error_email);
					$('#email').removeClass('has-error');
				}
			}

			if (error_phone_number != '' || error_email != '') {
				return false;
			} else {
				$('#btn_contact_details').attr("disabled", "disabled");
				$(document).css('cursor', 'prgress');
				$("#register_form").submit();
			}

		});

	});

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