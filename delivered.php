<?php

require 'data-classes.php';
session_start();


Database::connect();

$type = 0;
if (isset($_SESSION['account'])) {
    $type = $_SESSION['type'];
    if ($type != 2) {
        header("Location: logout.php");
    } else {
        $account = new Staff();
        $account->id = $_SESSION['account']->id;
        $account->read();
    }
} else
    header("Location: index.php");

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

    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

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
                    <a class="nav-link" href="deliver.php">DELIVER</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pickup.php">PICK UP</a>
                </li>


                <li class="nav-item">
                    <a class="nav-link active" href="delivered.php">DELIVERED</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="account.php">MY ACCOUNT</a>
                </li>

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


    <div class="cart-header bg-green float-shadow">
        <span class="color-white" style="font-size: 20px;">Orders Delivered</span>
    </div>

    <?php

    $orders = Common::get_orders_delivered($account->id);
    foreach ($orders as $o) {

        $customer = new Customer();
        $customer->id = $o->customer;
        $customer->read();
    ?>



        <div class="delivery-item-container float-shadow">
            <div>
                <span class="las la-user-check"></span>
                <div>
                    <span>Package ID : <?php echo $o->id ?> </span>
                    <span>City: <?php echo $customer->city ?></span>
                    <span>Address: <?php echo $customer->address ?> </span>
                    <span>Date Ordered: <?php echo $o->buydate ?> </span>
                    <span>Date Picked Up: <?php echo $o->pickupdate ?> </span>
                    <span>Date Delivered: <?php echo $o->deliverydate ?> </span>
                </div>
            </div>
            <span class="las la-check-circle" style="font-size: 100px; align-self:center;"></span>
        </div>

    <?php } ?>

</body>

</html>