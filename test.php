<?php

require_once('config.php');
$message = '';
if (isset($_POST["email"])) {
    sleep(5);
    $query = "INSERT INTO users (firstname, lastname, email, password, phone_number) VALUES (:firstname, :lastname, :email, :password, :phone_number)";
    $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);
    try {
        $user_data = array(
            ':firstname'  => $_POST["firstname"],
            ':lastname'  => $_POST["lastname"],
            ':email'   => $_POST["email"],
            ':password'   => $password_hash,
            ':phone_number'  => $_POST["phone_number"]
        );
        $statement = $pdo->prepare($query);
        if ($statement->execute($user_data)) {
            $message = '<div class="alert alert-success">Registration Completed Successfully</div>';
        } else {
            $message = '<div class="alert alert-success">There is an error in Registration</div>';
        }
    } catch (PDOException $e) {
        $errors[] = $e->getMessage();
    }
}
?>

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Multi Step Registration Form Using JQuery Bootstrap in PHP</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .box {
            width: 800px;
            margin: 0 auto;
        }

        .active_tab1 {
            background-color: #fff;
            color: #333;
            font-weight: 600;
        }

        .inactive_tab1 {
            background-color: #f5f5f5;
            color: #333;
            cursor: not-allowed;
        }

        .has-error {
            border-color: #cc0000;
            background-color: #ffff99;
        }
    </style>
</head>

<body>
    <div class="container box">
        <?php echo $message; ?>
        <form method="post" id="register_form">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active_tab1" style="border:1px solid #ccc" id="list_login_details">Login Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link inactive_tab1" id="list_personal_details" style="border:1px solid #ccc">Personal Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link inactive_tab1" id="list_contact_details" style="border:1px solid #ccc">Contact Details</a>
                </li>
            </ul>
            <div class="tab-content" style="margin-top:16px;">
                <div class="tab-pane active" id="login_details">
                    <div>
                        <div>Login Details</div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label>Enter Email Address</label>
                                <input type="text" name="email" id="email" class="form-control" />
                                <span id="error_email" class="text-danger"></span>
                            </div>
                            <div class="form-group">
                                <label>Enter Password</label>
                                <input type="password" name="password" id="password" class="form-control" />
                                <span id="error_password" class="text-danger"></span>
                            </div>
                            <br />
                            <div align="center">
                                <button type="button" name="btn_login_details" id="btn_login_details" class="btn">Next</button>
                            </div>
                            <br />
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="personal_details">
                    <div class="panel panel-default">
                        <div class="panel-heading">Fill Personal Details</div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label>Enter First Name</label>
                                <input type="text" name="firstname" id="firstname" class="form-control" />
                                <span id="error_firstname" class="text-danger"></span>
                            </div>
                            <div class="form-group">
                                <label>Enter Last Name</label>
                                <input type="text" name="lastname" id="lastname" class="form-control" />
                                <span id="error_lastname" class="text-danger"></span>
                            </div>
                            <br />
                            <div align="center">
                                <button type="button" name="previous_btn_personal_details" id="previous_btn_personal_details" class="btn">Previous</button>
                                <button type="button" name="btn_personal_details" id="btn_personal_details" class="btn">Next</button>
                            </div>
                            <br />
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="contact_details">
                    <div class="panel panel-default">
                        <div class="panel-heading">Fill Contact Details</div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label>Enter Mobile No.</label>
                                <input type="text" name="phone_number" id="phone_number" class="form-control" />
                                <span id="error_phone_number" class="text-danger"></span>
                            </div>
                            <br />
                            <div align="center">
                                <button type="button" name="previous_btn_contact_details" id="previous_btn_contact_details" class="btn">Previous</button>
                                <button type="button" name="btn_contact_details" id="btn_contact_details" class="btn btn-success btn-lg">Register</button>
                            </div>
                            <br />
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>

</html>

<script>
    $(document).ready(function() {

        $('#btn_login_details').click(function() {

            var error_email = '';
            var error_password = '';
            var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

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

            if ($.trim($('#password').val()).length == 0) {
                error_password = 'Password is required';
                $('#error_password').text(error_password);
                $('#password').addClass('has-error');
            } else {
                error_password = '';
                $('#error_password').text(error_password);
                $('#password').removeClass('has-error');
            }

            if (error_email != '' || error_password != '') {
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

            if (error_firstname != '' || error_lastname != '') {
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
            var mobile_validation = /^\d{10}$/;

            if ($.trim($('#phone_number').val()).length == 0) {
                error_phone_number = 'Mobile Number is required';
                $('#error_phone_number').text(error_phone_number);
                $('#phone_number').addClass('has-error');
            } else {
                if (!mobile_validation.test($('#phone_number').val())) {
                    error_phone_number = 'Invalid Mobile Number';
                    $('#error_phone_number').text(error_phone_number);
                    $('#phone_number').addClass('has-error');
                } else {
                    error_phone_number = '';
                    $('#error_phone_number').text(error_phone_number);
                    $('#phone_number').removeClass('has-error');
                }
            }
            if (error_phone_number != '') {
                return false;
            } else {
                $('#btn_contact_details').attr("disabled", "disabled");
                $(document).css('cursor', 'prgress');
                $("#register_form").submit();
            }

        });

    });
</script>