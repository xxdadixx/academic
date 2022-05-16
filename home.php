<?php

session_start();

if (!isset($_SESSION['username'])) {
    echo $_SESSION['error'] = "Please login!";
    header("Location: login.php");
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
    <link rel="stylesheet" href="css/home_style.css">
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
    <div class="container">
        <?php

        if (isset($_SESSION['user_login'])) {
            $user_id = $_SESSION['user_login'];
            $stmt = $conn->query("SELECT * FROM users WHERE id = $user_id");
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        ?>
        <h3 class="mt-4">Welcome, <?php echo $_SESSION['username'] ?></h3>
</body>

<script>
    $(document).ready(function() {

        var curPage = 1;
        var numOfPages = $(".skw-page").length;
        var animTime = 1000;
        var scrolling = false;
        var pgPrefix = ".skw-page-";

        function pagination() {
            scrolling = true;

            $(pgPrefix + curPage).removeClass("inactive").addClass("active");
            $(pgPrefix + (curPage - 1)).addClass("inactive");
            $(pgPrefix + (curPage + 1)).removeClass("active");

            setTimeout(function() {
                scrolling = false;
            }, animTime);
        };

        function navigateUp() {
            if (curPage === 1) return;
            curPage--;
            pagination();
        };

        function navigateDown() {
            if (curPage === numOfPages) return;
            curPage++;
            pagination();
        };

        $(document).on("mousewheel DOMMouseScroll", function(e) {
            if (scrolling) return;
            if (e.originalEvent.wheelDelta > 0 || e.originalEvent.detail < 0) {
                navigateUp();
            } else {
                navigateDown();
            }
        });

        $(document).on("keydown", function(e) {
            if (scrolling) return;
            if (e.which === 38) {
                navigateUp();
            } else if (e.which === 40) {
                navigateDown();
            }
        });

    });
</script>