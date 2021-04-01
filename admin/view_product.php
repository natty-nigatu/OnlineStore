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

            if (isset($_GET["id"])) {

                $product = Common::get_product($_GET["id"]);
            } else
                header("Location: products.php");
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
            <a href="customers.php"><span class="las la-users"></span>
                <span>Customers</span></a>
        </li>
        <li>
            <a href="Products.php" class="active"><span class="las la-tags"></span>
                <span>Products</span></a>
        </li>
        <li>
            <a href="orders.php"><span class="las la-shopping-bag"></span>
                <span>Orders</span></a>
        </li>
        <li>
            <a href="staff.php"><span class="las la-user-circle"></span>
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
                <?php echo $product->name ?>
            </h2>
            <?php include('./includes/header-nav.php'); ?>

            <main>

                <div id="imgslider" class="carousel slide" data-interval="false" data-ride="carousel">

                    <!-- Indicators -->
                    <ul class="carousel-indicators">
                        <?php
                        $i = 0;
                        foreach ($product->images as $q) {
                        ?>
                            <li data-target="#imgslider" data-slide-to="0" <?php if ($i == 0) echo 'class="active"' ?>></li>

                        <?php $i++;
                        } ?>
                    </ul>

                    <!-- The slideshow -->
                    <div class="carousel-inner" style="max-height: 500px;">

                        <?php
                        $i = 0;
                        if (count($product->images) > 0)
                            foreach ($product->images as $img) {
                        ?>

                            <div class="carousel-item <?php if ($i == 0) echo 'active' ?>">
                                <img style="max-height: 500px; !important" src="../assets/uploads/<?php echo $img ?>" alt="Main" class="product-img-view">
                            </div>

                        <?php
                                $i++;
                            }

                        if ($i == 0) {
                        ?>

                            <div class="carousel-item active">
                                <img src="../assets/uploads/noImage.png" alt="Main" class="product-img-view">
                            </div>

                        <?php } ?>

                    </div>

                    <!-- Left and right controls -->
                    <a class="carousel-control-prev" href="#imgslider" data-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </a>
                    <a class="carousel-control-next" href="#imgslider" data-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </a>
                </div>

                <div style="margin: 20px 10%; padding: 40px 10%;" class="float-shadow detail-list">
                    <h1><span class=""></span> Product Details</h1>
                    <h3><span class="las la-money-bill"></span> Price: <?php echo $product->price ?></h3>
                    <h3><span class="las la-eye-dropper"></span> Color: <?php echo $product->color ?></h3>
                    <h3><span class="las la-tshirt"></span> Size: <?php echo $product->size ?></h3>
                    <h3><span class="las la-transgender"></span> Gender: <?php echo $product->get_gender() ?></h3>

                </div>



            </main>
        </header>
    </div>




</body>

</html>