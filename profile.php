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
        if ($email) {
            try {
                if (!isset($error) && !isset($warning)) {
                    $query = "UPDATE users SET email=:email, height=:height, weight=:weight WHERE id=:id";
                    $user_data = array(
                        ':email'   => $_POST["email"],
                        ':height'   => $_POST["height"],
                        ':weight'   => $_POST["weight"],
                        ':id'   => $_SESSION['id']
                    );
                    $statement = $pdo->prepare($query);
                    $statement->execute($user_data);
                    $getRow = $statement->fetch(PDO::FETCH_ASSOC);
                    $_SESSION = $getRow;
                    $_SESSION['success'] = "Profile updated successfully!";
                    header("Location: login.php");
                }
            } catch (PDOException $e) {
                $errors[] = $e->getMessage();
            }
        }
    } else {
        $warning = "The email already exists in the database";
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
                <h2 class="user-name mt-3"><?= $_SESSION['username'] ?></h2>
                <p class="user-email"><?= $_SESSION['email'] ?></p>
                <div class="gravatar">
                    <img class="mt-3" src="upload/<?= $_SESSION['image'] ?>" width="150" height="150" />
                </div>
                <p class="exclusions"><a href="#popup1" class="btn btn-success mt-4">Edit Profile</a></p>
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
                                <div class="container">
                                    <div class="row">
                                        <div class="col">
                                            <label>Username</label>
                                            <div class="form-group">
                                                <input type="text" name="username" id="username" class="input disable" placeholder="Username" value="<?= $_SESSION['username'] ?>" disabled />
                                            </div>
                                            <label>Firstname</label>
                                            <div class="form-group">
                                                <input type="text" name="firstname" id="firstname" class="input disable" placeholder="Firstname" value="<?= $_SESSION['firstname'] ?>" disabled />
                                            </div>
                                            <label>Birthday</label>
                                            <div class="form-group">
                                                <input type="date" name="birthday" id="birthday" class="input disable" value="<?= $_SESSION['birthday'] ?>" disabled />
                                            </div>
                                        </div>
                                        <div class="col">
                                            <label>Email</label>
                                            <div class="form-group">
                                                <input type="text" name="email" id="email" class="input" placeholder="Email" value="<?= $_SESSION['email'] ?>" />
                                            </div>
                                            <label>Lastname</label>
                                            <div class="form-group">
                                                <input type="text" name="lastname" id="lastname" class="input disable" placeholder="Lastname" value="<?= $_SESSION['lastname'] ?>" disabled />
                                            </div>
                                            <label>ID Card Number</label>
                                            <div class="form-group">
                                                <input type="text" name="id_card_number" id="id_card_number" class="input disable" placeholder="ID Card number" value="<?= $_SESSION['id_card_number'] ?>" disabled />
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div>
                                                <label class="label-weight">Weight</label>
                                                <label class="label-height">Height</label>
                                                <br>
                                                <input type="text" name="weight" id="weight" class="input-weight" value="<?= $_SESSION['weight'] ?>" />
                                                <input type="text" name="height" id="height" class="input-height" value="<?= $_SESSION['height'] ?>" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success mt-3" name="update">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <h6>About</h6>
                    <p class="text-justify">There are many sports played around the world and football is one of them. It is a very popular and exciting game and also happens to be one of the oldest games. In earlier times, many such games were played. People love watching a football game.</p>
                    <p class="text-justify">Football is played by a team of 11 players, and there are two teams playing against each other. The football is round in shape and is kicked by the players. The players are not supposed to use hands to pass the ball. The aim is to hit the goal. To play football, a lot of energy is required.</p>
                </div>

                <div class="col-xs-6 col-md-3">
                    <h6>Categories</h6>
                    <ul class="footer-links">
                        <li><a href="#">Performance</a></li>
                        <li><a href="#">Schedule</a></li>
                        <li><a href="#">Teams</a></li>
                        <li><a href="#">About</a></li>
                    </ul>
                </div>

                <div class="col-xs-6 col-md-3">
                    <h6>Quick Links</h6>
                    <ul class="footer-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Contribute</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Sitemap</a></li>
                    </ul>
                </div>
            </div>
            <hr>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-6 col-xs-12">
                    <p class="copyright-text">Copyright &copy; 2022 All Rights Reserved by
                        <a href="#">Ittikorn Sirivejaband</a>.
                    </p>
                </div>

                <div class="col-md-4 col-sm-6 col-xs-12">
                    <ul class="social-icons">
                        <li><a class="facebook" href="https://www.facebook.com/sanshop2009"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                                    <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                                </svg></a></li>
                        <li><a class="instagram" href="https://www.instagram.com/san_itk80/"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                                    <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z" />
                                </svg></a></li>
                        <li><a class="github" href="https://github.com/xxdadixx"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-github" viewBox="0 0 16 16">
                                    <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z" />
                                </svg></a></li>
                        <li><a class="linkedin" href="https://www.linkedin.com/in/อิทธิกร-สิริเวชพันธุ์-930917250"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-linkedin" viewBox="0 0 16 16">
                                    <path d="M0 1.146C0 .513.526 0 1.175 0h13.65C15.474 0 16 .513 16 1.146v13.708c0 .633-.526 1.146-1.175 1.146H1.175C.526 16 0 15.487 0 14.854V1.146zm4.943 12.248V6.169H2.542v7.225h2.401zm-1.2-8.212c.837 0 1.358-.554 1.358-1.248-.015-.709-.52-1.248-1.342-1.248-.822 0-1.359.54-1.359 1.248 0 .694.521 1.248 1.327 1.248h.016zm4.908 8.212V9.359c0-.216.016-.432.08-.586.173-.431.568-.878 1.232-.878.869 0 1.216.662 1.216 1.634v3.865h2.401V9.25c0-2.22-1.184-3.252-2.764-3.252-1.274 0-1.845.7-2.165 1.193v.025h-.016a5.54 5.54 0 0 1 .016-.025V6.169h-2.4c.03.678 0 7.225 0 7.225h2.4z" />
                                </svg></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>