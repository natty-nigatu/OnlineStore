<?php

require 'data-classes.php';
session_start();


Database::connect();

$type = 0;
if (isset($_SESSION['account'])) {
    $type = $_SESSION['type'];
    if ($type == 10) {
        header("Location: index.php");
    } else {
        $account = new Staff();
        $account->id = $_SESSION['account']->id;
        $account->read();

        if (isset($_POST['edit'])) {

            if (isset($_FILES["image"]) && !empty($_FILES['image']["tmp_name"])) {
                $img = Image::upload_image_file($_FILES["image"]);

                if ($img != false) {
                    $account->picture = $img;
                }
            }

            $account->name = $_POST['name'];
            $account->email = $_POST['email'];
            $account->phone = $_POST['phone'];

            if (!$account->update())
                $account->read();
        }
    }
} else
    header("Location: index.php");


?>

<html>

<head>
    <title>Fua Clothing</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/style2.css">
    <link rel="stylesheet" href="assets/css/style3.css">
</head>

<body>

    <div class="nav-container">
        <nav class="navbar navbar-expand-sm bg-black drop-shadow navbar-dark">
            <a class="navbar-brand logo mr-auto" href="index.php">
                <img id="logo-img" src="assets/img/logo-white.png" alt="Fua Clothing">
            </a>
            <ul class="navbar-nav mx-auto main-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">HOME</a>
                </li>

                <?php if ($type != 0) { ?>

                    <li class="nav-item active">
                        <a class="nav-link" href="account.php">MY ACCOUNT</a>
                    </li>

                <?php } ?>

            </ul>
            <ul class="navbar-nav ml-auto nav-login">
                <?php if ($type == 0) { ?>

                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>

                <?php } else { ?>

                    <li class="nav-item">
                        <?php if (isset($account->picture) && !empty($account->picture))
                            $img = $account->picture;
                        else
                            $img = "noImage.png";

                        $img = "assets/uploads/" . $img;

                        ?>
                        <div>
                            <a href="account.php" style="text-decoration:none">
                                <img src="<?php echo $img ?>" alt="profile" style="width: 40px; height:40px; display:inline; border-radius:60px">
                            </a>
                            <a class="nav-link" href="logout.php" style="display:inline;">Log Out</a>
                        </div>

                    <?php } ?>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col">

            <div class="account-form">
                <?php if (isset($_POST['cancel_cust'])) {
                    header("location:./customers.php");
                } ?>
                <form action="account-staff.php" method="post" class="add-customer" enctype="multipart/form-data">
                    <div class="title">
                        <h3>Account Details</h3>
                    </div>
                    <div id="input-name" class="input-field">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" value="<?php echo $account->name ?>">
                    </div>
                    <div id="input-email" class="input-field">
                        <label for="email">E-mail</label>
                        <input type="email" name="email" id="email" value="<?php echo $account->email ?>">
                    </div>
                    <div id="input-phone" class="input-field">
                        <label for="phone">Phone</label>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;+251
                        <input type="text" name="phone" id="phone" value="<?php echo $account->phone ?>">
                    </div>
                    <div id="input-username" class="input-field">
                        <label for="">Username</label>
                        <input type="text" name="username" id="username" value="<?php echo $account->username ?>" readonly>
                    </div>
                    <div id="input-password" class="input-field">
                        <label for="password">Password</label>
                        <input type="button" onclick="location.href='change_password.php'" name="password" id="password" value="Change Password">
                    </div>
                    <div id="input-image" class="input-field">
                        <label for="image">Profile Image</label>
                        <input type="file" name="image" id="image">
                    </div>

                    <div id="input-submit">
                        <input class="btn btn-dark bg-black btn-half" type="submit" style="width: -webkit-fill-available;" name="edit" id="edit" value="Update">
                    </div>






                </form>
            </div>

        </div>
    </div>


    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

</body>

</html>