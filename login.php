<?php
require "data-classes.php";
session_start();

Database::connect();

$err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim(htmlspecialchars($_POST["username"]), " ");
    $password = trim(htmlspecialchars($_POST["password"]), " ");

    if ($result = Common::login($username, $password)) {

        $_SESSION['account'] = $result['account'];
        $_SESSION['type'] = $result['type'];
        $type = $result['type'];

        if ($type == 10)
            die(header("Location: index.php"));
        else if ($type == 2)
            die(header("Location: deliver.php"));
        else if ($type == 1)
            die(header("Location: admin/index.php"));
    } else
        $err = "Invalid username or password.";
}


?>

<html>

<Head>
    <title>Login-Fua Clothing</title>
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

    <div class="nav-container">
        <nav class="navbar navbar-expand-sm fixed-top bg-black drop-shadow navbar-dark">
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
                <li class="nav-item">
                    <a class="nav-link" href="store.php">STORE</a>
                </li>

            </ul>
            <ul class="navbar-nav ml-auto nav-login">
                <li class="nav-item">
                    <a class="nav-link active" href="login.php">Login</a>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Register</a>
                </li>
            </ul>
        </nav>
    </div>


    <!-- ==== Login From === -->

    <form action="login.php" method="POST" class="login-form float-shadow">

        <legend>Login To Fua Clothing</legend>
        <div class="inputs">
            <label for="name">Username</label>
            <input type="text" name="username" id="username" class="input" required autofocus placeholder="Your username" title="please enter a valid email address ">
        </div>
        <div class="inputs">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="input" required placeholder="Password" title="please enter your Password">
        </div>
        <h5 class="text-danger"><?php echo $err ?> </h5>
        <input class="float-shadow" type="submit" name="login" value="Login">
        <div class="create">
            <a href="Register.php">Create an account</a>
        </div>



    </form>

</body>

</html>