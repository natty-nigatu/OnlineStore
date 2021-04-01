<?php

require 'data-classes.php';
session_start();


Database::connect();

$type = 0;
if (isset($_SESSION['account'])) {
    $type = $_SESSION['type'];
    if ($type == 10) {
        $account = new Customer();
        $account->id = $_SESSION['account']->id;
        $account->read();
    } else {
        if ($type == 2)
            header("Location: deliver.php");
        else if ($type == 1)
            header("Location: admin/index.php");
    }
}

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


    <link rel="stylesheet" href="assets/css/style2.css">
</head>

<body class="body-margin">

    <?php
    Database::connect();
    ?>

    <div class="nav-container">
        <nav class="navbar navbar-expand-sm fixed-top bg-black drop-shadow navbar-dark">
            <a class="navbar-brand logo mr-auto" href="index.php">
                <img id="logo-img" src="assets/img/logo-white.png" alt="Fua Clothing">
            </a>
            <ul class="navbar-nav mx-auto main-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">HOME</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#products-list">POPULAR</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="store.php">STORE</a>
                </li>

                <?php if ($type != 0) { ?>

                    <li class="nav-item">
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


                        <!--
                        <a class="nav-link" href="logout.php"><?php if (isset($account) && !empty($account->name)) echo $account->name . ": " ?>Log Out</a>

                -->
                    <?php } ?>

            </ul>
        </nav>
    </div>

    <div id="imgslider" class="carousel slide" data-ride="carousel">

        <!-- Indicators -->
        <ul class="carousel-indicators">
            <li data-target="#imgslider" data-slide-to="0" class="active"></li>
            <li data-target="#imgslider" data-slide-to="1"></li>
            <li data-target="#imgslider" data-slide-to="2"></li>
        </ul>

        <!-- The slideshow -->
        <div class="carousel-inner carousel-inner-home">
            <div class="carousel-item active">
                <img src="assets/img/ban1.jpg" alt="Main">
            </div>
            <div class="carousel-item">
                <img src="assets/img/ban2.jpg" alt="Img 2">
            </div>
            <div class="carousel-item">
                <img src="assets/img/ban3.jpg" alt="Img 3">
            </div>
        </div>

        <!-- Left and right controls -->
        <a class="carousel-control-prev" href="#imgslider" data-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </a>
        <a class="carousel-control-next" href="#imgslider" data-slide="next">
            <span class="carousel-control-next-icon"></span>
        </a>
    </div>

    <header id="products-list" class="sticky-top">
        <p class="display-4" style="text-align:center; margin:20px 0 10px 0">Popular Products</p>
        <nav class="secondary-nav container-fluid drop-shadow">
            <div class="drop-cat">
                <button class="cat-button">Categories</button>
                <div class="cat-menu">
                    <a href="store.php">All</a>
                    <?php $category = Common::get_categories();

                    foreach ($category as $cat) {
                    ?>
                        <a href="store.php?<?php echo $cat->id ?>"><?php echo $cat->name ?></a>

                    <?php } ?>

                </div>
            </div>
            <form action="store.php" method="GET" class="search-products">
                <div class="input-group">
                    <input type="search" class="form-control" name="q" <?php if (isset($_GET["q"]) && !empty($_GET['q'])) {
                                                                            echo 'value=' . $_GET["q"];
                                                                        } ?> placeholder="Search for products...">
                    <div class="input-group-append">
                        <button class="btn btn-success" type="submit" value="search">Search</button>
                    </div>
                </div>
            </form>

            <?php if ($type == 10) { ?>

                <ul class="imp-nav">
                    <li><a href="wishlist.php">Wishlist:&nbsp;<span class="number"><?php echo Common::count_wishlist($account->id) ?></span></a></li>
                    <li><a href="cart.php">Cart:&nbsp;<span class="number"><?php echo Common::count_cart($account->id) ?><span></a></li>
                    <li><a href="account.php" style="display: inline-block;">Wallet:&nbsp;<span class="wallet-balance"><?php echo $account->wallet ?> ETB</span></a></li>
                </ul>

            <?php } ?>

        </nav>
    </header>


    <div class="row">
        <?php
        $i = 0;
        $products = Common::get_top_selling();
        foreach ($products as $product) {
            if ($i < 6)
                $i++;
            else
                break;

            if (isset($product->images[0]))
                $img = $product->images[0];
            else
                $img = 'noimage.png';

        ?>

            <div class="col product-card">
                <div class="card">
                    <img class="card-img-top  float-shadow" src="assets/uploads/<?php echo $img ?>" alt="Product Image">

                    <div class="card-img-overlay d-flex justify-content-end h-100 flex-column">
                        <h4 class="card-title" style="text-align: center; padding: 2px 0; border-radius:5px; background: rgba(255, 255, 255, 0.5)"><?php echo $product->name ?></h4>
                        <div class="align-self-stretch align-content-center" style="display: inline;">
                            <a href="product.php?<?php echo $product->id ?>" class="btn btn-dark bg-black stretched-link btn-product" style="width: -webkit-fill-available;">View</a>
                        </div>

                    </div>
                </div>

            </div>

        <?php } ?>
    </div>

    <div>
        <p class="view-store bg-black">
            <a class="color-white" href="store.php">View Store</a>
        </p>
    </div>


</body>

</html>