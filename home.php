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
    <script src="javascript/home_script.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="home_style.css">
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
        <div class="row">
            <div class="col">
                <h1 class="title">TAKE IT TO DREAM</h1>
                <p class="mt-4">The Main Library at Indiana University sinks over an inch every year because when it was built, engineers failed to take into account the weight of all the books that would occupy the building.</p>
                <p>Mario, of Super Mario Bros. fame, appeared in the 1981 arcade game, Donkey Kong. His original name was Jumpman, but was changed to Mario to honor the Nintendo of America's landlord, Mario Segali.</p>
                <p>She sat deep in thought. The next word that came out o her mouth would likely be the most important word of her life. It had to be exact with no possibility of being misinterpreted. She was ready. She looked deeply into his eyes and said, "Octopus."</p>
            </div>
            <div class="col mt-4">
                <img src="https://pngimg.com/uploads/football/football_PNG52783.png" alt="">
            </div>
        </div>
        <header></header>
    </div>
</body>