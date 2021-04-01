<html>

<head>
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="../assets/js/jquery-3.6.0.min.js"></script>
    <!-- Popper JS -->
    <script src="../assets/js/popper.min.js"></script>
    <!-- Bootstrap JS-->
    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>


    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/style2.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/style3.css">
</head>

<body class="p-body">


    <?php include('./includes/header.php') ?>


    <?php

    require '../data-classes.php';
    session_start();


    Database::connect();

    $type = 0;
    if (isset($_SESSION['account'])) {
        $type = $_SESSION['type'];
        if ($type != 1) {
            header("Location: ../logout.php");
        } else {
            $account = new Staff();
            $account->id = $_SESSION['account']->id;
            $account->read();

            if (isset($_GET["id"]) && isset($_GET["type"])) {

                if ($_GET["type"] == 10) {
                    $p = new Customer();
                    $p->id = $_GET["id"];
                    $p->read();
                } else {
                    $p = new Staff();
                    $p->id = $_GET["id"];
                    $p->read();
                }
            } else
                header("Location: index.php");
        }
    } else
        header("Location: ../index.php");

    ?>



    <ul>
        <li>
            <a href="index.php"><span class="las la-igloo"></span>
                <span>Dashboard</span></a>
        </li>
        <li>
            <a href="categories.php"><span class="las la-list-ul"></span>
                <span>Categories</span></a>
        </li>
        <li>
            <a href="customers.php" <?php if($_GET['type'] == 10) echo'class="active"' ?>><span class="las la-users"></span>
                <span>Customers</span></a>
        </li>
        <li>
            <a href="Products.php" ><span class="las la-tags"></span>
                <span>Products</span></a>
        </li>
        <li>
            <a href="orders.php"><span class="las la-shopping-bag"></span>
                <span>Orders</span></a>
        </li>
        <li>
            <a href="staff.php" <?php if($_GET['type'] != 10) echo'class="active"' ?>><span class="las la-user-circle"></span>
                <span>Staff</span></a>
        </li>
        <li>
            <a href="tasks.php"><span class="las la-clipboard-list"></span>
                <span>Tasks</span></a>
        </li>
    </ul>
    </div>
    </div>


    <div class="main-content">
        <header style="height:80px">
            <h2>
                <label for="nav-toggle">
                    <span class="las la-bars"></span>
                </label>
                Account Details
            </h2>
            <?php include('./includes/header-nav.php'); ?>

            <main>

                <?php
                $img = "noImage.png";

                if(isset($p->picture) && !empty($p->picture))
                    $img = $p->picture
                ?>
                <img style="max-height: 300px; !important; border-radius:1000px" src="../assets/uploads/<?php echo $img ?>" alt="Main" class="product-img-view">




                <div style="margin: 20px 10%; padding: 40px 10%;" class="float-shadow detail-list">
                    <h1><span class=""></span> Account Details</h1>
                    <h3>Name: <?php echo $p->name?></h3>
                    <h3>Email: <?php echo $p->email?></h3>
                    <h3>phone: <?php echo $p->phone?></h3>
                    <h3>username: <?php echo $p->username?></h3>

                </div>



            </main>
        </header>
    </div>




</body>

</html>