<?php

require_once('config/config.php');
session_start();

if (!isset($_SESSION['username'])) {
    echo $_SESSION['error'] = "Please login!";
    header("Location: login.php");
}

if (isset($_POST['update'])) {
    $email = $_POST['email'];

    $check_data = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $check_data->bindParam(":email", $email);
    $check_data->execute();
    $result = $check_data->rowCount();

    if ($result > 0) {
        $warning = "The email already exists in the database";
    }

    try {
        if (!isset($error) && !isset($warning)) {
            $query = "UPDATE users SET email=:email WHERE id=:id";
            $user_data = array(
                ':email'   => $_POST["email"],
                ':id'   => $_SESSION['id']
            );
            $statement = $pdo->prepare($query);
            $statement->execute($user_data);
            $getRow = $statement->fetch(PDO::FETCH_ASSOC);
            $_SESSION = $getRow;
            header("Location: login.php");
        }
    } catch (PDOException $e) {
        $errors[] = $e->getMessage();
    }
}

if (isset($_POST['change-pass'])) {

    if (empty($_POST['op'])) {
        $error = "Password is required";
    } else if (empty($_POST['np'])) {
        $error = "Password is required";
    } else if ($_POST['np'] !== $_POST['c_np']) {
        $error = "The confirmation password  does not match";
    } else if (strlen($_POST['op'])  > 20 || strlen($_POST['op']) < 8) {
        $error = 'Password must be between 8 and 20 characters long.';
    } else if (strlen($_POST['np'])  > 20 || strlen($_POST['np']) < 8) {
        $error = 'Password must be between 8 and 20 characters long.';
    } else if (strlen($_POST['c_np'])  > 20 || strlen($_POST['c_np']) < 8) {
        $error = 'Password must be between 8 and 20 characters long.';
    } else if ($_POST['np'] !== $_POST['c_np']) {
        $error = "The confirmation password  does not match";
    } else {
        // hashing the password
        $op = trim($_POST['op']);
        $np = trim($_POST['np']);
        $id = $_SESSION['id'];

        $check_data = $pdo->prepare("SELECT password FROM users WHERE id=:id AND password=:password");
        $check_data->bindParam(":id", $id);
        $check_data->bindParam(":password", $op);
        $check_data->execute();
        $result = $check_data->rowCount();

        if ($result === 1) {

            $query = "UPDATE users SET password=:password WHERE id=:id";
            $password_hash = password_hash($_POST["np"], PASSWORD_DEFAULT);
            $user_data = array(
                ':password'   => $password_hash,
                ':id'   => $_SESSION['id']
            );
            $statement = $pdo->prepare($query);
            $statement->execute($user_data);
            header("Location: login.php");
        } else {
            $error = "Incorrect password";
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="javascript/script.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/profile_style.css">
    <title>User Page</title>
</head>


<body>
    <nav>
        <ul class="menu">
            <li class="logo"><a href="home.php"><img src="https://i.pinimg.com/564x/fc/28/1f/fc281fe7423ce9776ed5fbb36a62cb57.jpg" width="100" height="100" alt="logo"></a></li>
            <li class="item fm"><a href="#">PERFORMANCE</a></li>
            <li class="item fm"><a href="#">SCHEDULE</a></li>
            <li class="item fm"><a href="#">TEAMS</a></li>
            <li class="item fm"><a href="#">ABOUT</a></li>
            <li class="item button fm"><a href="profile.php">PROFILE</a></li>
            <li class="item button secondary fm"><a href="logout.php">LOGOUT</a></li>
            <li class="toggle"><a href="#"><i class="fas fa-bars"></i></a></li>
        </ul>
    </nav>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="shadow w-350 p-3 text-center">
            <div class="user-details expanded">
                <div class="gravatar">
                    <img src="https://i.ibb.co/4MDzqfB/default-pp.png" width="150" height"150" />
                </div>
                <h2 class="user-name"><?= $_SESSION['username'] ?></h2>
                <p class="user-email"><?= $_SESSION['email'] ?></p>
                <p class="exclusions"><a href="#popup1" class="btn btn-success mt-3">Edit Profile</a></p>
                <div id="popup1" class="overlay">
                    <div class="popup">
                        <a class="close" href="profile.php">&times;</a>
                        <div class="content">
                            <form action="profile.php#popup1" class="form" method="post">
                                <br><br>
                                <h2>Edit Profile</h2>
                                <br>
                                <?php if (isset($warning)) { ?>
                                    <div class="alert alert-warning" role="alert">
                                        <?php {
                                            echo $warning;
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
                                <label>Username</label>
                                <div class="form-group">
                                    <input type="text" name="username" id="username" class="input disable" placeholder="Username" value="<?= $_SESSION['username'] ?>" disabled />
                                </div>
                                <label>Email</label>
                                <div class="form-group">
                                    <input type="text" name="email" id="email" class="input" placeholder="Email" value="<?= $_SESSION['email'] ?>" />
                                </div>
                                <label>Firstname</label>
                                <div class="form-group">
                                    <input type="text" name="firstname" id="firstname" class="input disable" placeholder="Firstname" value="<?= $_SESSION['firstname'] ?>" disabled />
                                </div>
                                <label>Lastname</label>
                                <div class="form-group">
                                    <input type="text" name="lastname" id="lastname" class="input disable" placeholder="Lastname" value="<?= $_SESSION['lastname'] ?>" disabled />
                                </div>
                                <label>Birthday</label>
                                <div class="form-group">
                                    <input type="date" name="birthday" id="birthday" class="input disable" value="<?= $_SESSION['birthday'] ?>" disabled />
                                </div>
                                <label>ID Card Number</label>
                                <div class="form-group">
                                    <input type="text" name="id_card_number" id="id_card_number" class="input disable" placeholder="ID Card number" value="<?= $_SESSION['id_card_number'] ?>" disabled />
                                </div>
                                <button type="submit" class="btn btn-success mt-3" name="update">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
                <p class="exclusions"><a href="#popup2" class="btn btn-success mt-1">Change Password</a></p>
                <div id="popup2" class="overlay">
                    <div class="popup">
                        <a class="close" href="profile.php">&times;</a>
                        <div class="content">
                            <form action="profile.php#popup2" class="form" method="post">
                                <br><br>
                                <h2>Change Password</h2>
                                <br>
                                <?php if (isset($warning)) { ?>
                                    <div class="alert alert-warning" role="alert">
                                        <?php {
                                            echo $warning;
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
                                <label>Current Password</label>
                                <div class="form-group">
                                    <input type="password" name="op" id="op" class="input" placeholder="current Password" />
                                </div>
                                <label>New Password</label>
                                <div class="form-group">
                                    <input type="password" name="np" id="np" class="input" placeholder="new Password" />
                                </div>
                                <label>Confirm Password</label>
                                <div class="form-group">
                                    <input type="password" name="c_np" id="c_np" class="input" placeholder="confirm Password" />
                                </div>
                                <button type="submit" class="btn btn-success mt-3" name="change-pass">Change Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>