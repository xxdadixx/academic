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
    <script src="script.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="css/profile_style.css">
    <title>User Page</title>
</head>


<body>
    <nav>
        <ul class="menu">
            <li class="logo"><a href="welcome.php"><img src="https://i.pinimg.com/564x/fc/28/1f/fc281fe7423ce9776ed5fbb36a62cb57.jpg" width="100" height="100" alt="logo"></a></li>
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
            <div class="student-details expanded">
                <div class="gravatar">
                    <img src="https://i.ibb.co/4MDzqfB/default-pp.png" width="150" height"150" />
                </div>
                <h2 class="student-name"><?= $_SESSION['username'] ?></h2>
                <p class="student-email"><?= $_SESSION['email'] ?></p>
            </div>

            <div class="forms">
                <div class="tabs edit-profile">
                    <h3>Edit Profile</h3>
                </div>
                <div class="tab-content edit-profile-form-wrap">
                    <!--  -->
                    <form class="edit-profile-form">
                        <div class="form-control first-name">
                            <label for="first-name">First Name&#58;</label>
                            <input type="text" class="textbox" id="first-name" value="<?= $_SESSION['firstname'] ?>">
                        </div>
                        <div class="form-control first-name">
                            <label for="last-name">Last Name&#58;</label>
                            <input type="text" class="textbox" id="last-name" value="<?= $_SESSION['lastname'] ?>">
                        </div>
                        <div class="form-control email">
                            <label for="email">E-mail&#58;</label>
                            <input type="text" class="textbox" id="email" value="<?= $_SESSION['email'] ?>">
                        </div>
                        <div class="form-control email">
                            <label for="phone_number">Phone number&#58;</label>
                            <input type="text" class="textbox" id="phone_number" value="<?= $_SESSION['phone_number'] ?>">
                        </div>
                        <div class="form-buttons">
                            <button type="submit" class="primary-btn" value="Update Profile">Update Profile</button>
                            <!-- <button type="button" class="secondary-btn cancel" value="Cancel">Cancel</button> -->
                        </div>
                    </form>
                </div>
                <div class="tabs change-password">
                    <h3>Change Password</h3>
                </div>
                <div class="tab-content change-password-form-wrap">
                    <!--  -->
                    <form class="edit-profile-form">
                        <div class="form-control first-name">
                            <label for="current-password">Current Password&#58;</label>
                            <input type="text" class="textbox" id="current-password">
                        </div>
                        <div class="form-control first-name">
                            <label for="new-password">New Password&#58;</label>
                            <input type="text" class="textbox" id="new-password">
                        </div>
                        <div class="form-control email">
                            <label for="confirm-password">Confirm New Password&#58;</label>
                            <input type="text" class="textbox" id="confirm-password">
                        </div>
                        <div class="form-buttons">
                            <button type="submit" class="primary-btn" value="Change Password">Change Password</button>
                            <!-- <button type="button" class="secondary-btn cancel" value="Cancel">Cancel</button> -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
</body>

<script>
    var profileStates = {
        editProfile: false,
        changePassword: false,
        showDetails: true
    }

    $('.tabs').on('click', function(e) {
        e.stopPropagation();
        tabHandler(e.currentTarget);
    })

    $('.cancel').on('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        pageReset();
    });

    function tabHandler(tab) {
        var editProfileTab = $(tab).hasClass('edit-profile');
        var changePassTab = $(tab).hasClass('change-password');

        switch (true) {

            // if Tab is Edit Profile and Edit Profile is not showing
            case (editProfileTab && !isEditProfileShowing()):
                // Check to see if Profile Details are showing. Hide if they are.
                if (isDetailsShowing()) {
                    profileStates.showDetails = false;
                    $('.student-details').removeClass('expanded');
                }

                // Remove .expanded from all content wrappers
                $('.tab-content').removeClass('expanded');

                // Add .expanded to edit profile content wrapper
                $('.edit-profile-form-wrap').addClass('expanded');

                profileStates.editProfile = true;
                profileStates.changePassword = false;
                break;

                // if Tab is Change Password and Detail is Showing
            case (changePassTab && !isChangePasswordShowing()):
                // Check to see if Profile Details are showing. Hide if they are.
                if (isDetailsShowing()) {
                    profileStates.showDetails = false;
                    $('.student-details').removeClass('expanded');
                }

                // Remove .expanded from all content wrappers
                $('.tab-content').removeClass('expanded');

                // Add .expanded to edit profile content wrapper
                $('.change-password-form-wrap').addClass('expanded');
                profileStates.editProfile = false;
                profileStates.changePassword = true;
                break;

                // if Tab is Edit Profile and Edit Profile is Showing
            case (editProfileTab && isEditProfileShowing()):
                pageReset();
                break;
                // if Tab is Change Password and Change Password is Shwoing
            case (changePassTab && isChangePasswordShowing()):
                pageReset();
                break;
        }
    }

    // Reset page to default state
    function pageReset() {
        $('.tab-content').removeClass('expanded');
        $('.student-details').addClass('expanded');
        profileStates.showDetails = true;
        profileStates.editProfile = false;
        profileStates.changePassword = false;
    }

    // Check to see if Student Details are being shown
    function isDetailsShowing() {
        return profileStates.showDetails;
    }

    // Check to see if Change Password form is showing
    function isChangePasswordShowing() {
        return profileStates.changePassword;
    }

    // Check to see if Edit Profil form is showing
    function isEditProfileShowing() {
        return profileStates.editProfile;
    }
</script>