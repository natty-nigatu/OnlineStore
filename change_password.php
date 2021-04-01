<?php

use function PHPSTORM_META\type;

require "data-classes.php";
session_start();

Database::connect();

$err = "";

if (isset($_SESSION['account'])) {
    $type = $_SESSION['type'];
    if ($type == 10) {
        $account = new Customer();
        $account->id = $_SESSION['account']->id;
        $account->read();
    } else {
        $account = new Staff();
        $account->id = $_SESSION['account']->id;
        $account->read();
    }
} else
    header("Location: index.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $current = trim(htmlspecialchars($_POST["current"]), " ");
    $password = trim(htmlspecialchars($_POST["password"]), " ");
    $cpassword = trim(htmlspecialchars($_POST["cpassword"]), " ");


    if ($current != $account->password) {
        $err = "Incorrect current password.";
    } else if ($password != $cpassword) {
        $err = "Passwords do not match.";
    } else {
        $account->password = $password;
        $account->update();

        header("Location: account.php");
    }
}

?>

<html>

<Head>
    <title>Fua Clothing</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <link rel="stylesheet" href="./assets/css/style.css" type="text/css">
    <link rel="stylesheet" href="assets/css/style2.css">
</head>

<body class="body-margin">

    <?php if ($type == 10) { ?>

        <div class="nav-container">
            <nav class="navbar navbar-expand-sm bg-black drop-shadow navbar-dark fixed-top">
                <a class="navbar-brand logo mr-auto" href="index.php">
                    <img id="logo-img" src="assets/img/logo-white.png" alt="Fua Clothing">
                </a>
                <ul class="navbar-nav mx-auto main-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#products-list">POPULAR</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="store.php">STORE</a>
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

    <?php } else { ?>

        <div class="nav-container">
            <nav class="navbar navbar-expand-sm bg-black drop-shadow navbar-dark fixed-top">
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

    <?php } ?>


    <!-- ======== change password form ===== -->
    <form action="change_password.php" method="POST" class="register-form float-shadow">

        <legend>Change Password</legend>
        <div class="inputs">
            <label for="name">Current Password</label>
            <input type="password" name="current" id="current" class="input" required autofocus placeholder="Your current passoword" title="please enter your current password" <?php if ($_SERVER["REQUEST_METHOD"] == "POST") echo "value= $current" ?>>
        </div>
        <div class="inputs">
            <label for="password">New Password</label>
            <input type="password" name="password" id="password" class="input" required placeholder="Password" title="please enter your Password" <?php if ($_SERVER["REQUEST_METHOD"] == "POST") echo "value= $password" ?>>
        </div>
        <div class="inputs">
            <label for="cpassword">Confirm Password</label>
            <input type="password" name="cpassword" id="cpassword" class="input" required placeholder="Confirm Password" title="please enter your correct password" <?php if ($_SERVER["REQUEST_METHOD"] == "POST") echo "value= $cpassword" ?>>
        </div>
        <h5 class="text-danger"><?php echo $err ?> </h5>
        <input class="float-shadow" type="submit" name="Register" value="Change Password" style="width:fit-content">

    </form>

</body>

</html>