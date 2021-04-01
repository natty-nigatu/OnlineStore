<?php

require 'data-classes.php';
session_start();


Database::connect();

/////check who is logged in
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
            die(header("Location: admin/index.php"));
    }
}

////////parse url for query
$url = $_SERVER["REQUEST_URI"];
$values = parse_url($url);

if (!isset($values["query"]) || empty($values["query"]))
    die(header("Location: store.php"));
else {
    $id =  htmlspecialchars($values["query"]);

    if (!is_numeric($id))
        die(header("Location: store.php"));

    $product = Common::get_product($id);

    if ($product == false)
        die(header("Location: store.php"));
}

////////check if there is command
if (isset($_SESSION['account'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST")
        switch ($_POST["cmd"]) {

            case "ac":
                Common::add_to_cart($account->id, $product->id, 1);
                break;

            case "aw":
                Common::add_to_wishlist($account->id, $product->id);
                break;

            case "rw":
                Common::remove_from_wishlist($account->id, $product->id);
                break;

            case "rc":
                Common::remove_from_cart($account->id, $product->id);
                break;
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

    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

    <link rel="stylesheet" href="assets/css/style2.css">
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
                <li class="nav-item">
                    <a class="nav-link" href="index.php#products-list">POPULAR</a>
                </li>
                <li class="nav-item active">
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

                    <?php } ?>
            </ul>
        </nav>
    </div>


    <header id="products-list" class="sticky-top">
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
                    <input type="search" class="form-control" name="q" placeholder="Search for products...">
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

    <div id="navigation"></div>

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
        <div class="carousel-inner">

            <?php
            $i = 0;
            if (count($product->images) > 0)
                foreach ($product->images as $img) {
            ?>

                <div class="carousel-item <?php if ($i == 0) echo 'active' ?>">
                    <img src="assets/uploads/<?php echo $img ?>" alt="Main" class="product-img-view">
                </div>

            <?php
                    $i++;
                }

            if ($i == 0) {
            ?>

                <div class="carousel-item active">
                    <img src="assets/uploads/noImage.png" alt="Main" class="product-img-view">
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

    <div class="product-view-data">
        <h2><span class="las la-shopping-bag"></span><?php echo $product->name ?> </h2>
        <div>
            <h2><span class="las la-money-bill"></span> Price: <?php echo $product->price ?></h2>
            <h5><span class="las la-eye-dropper"></span> Color: <?php echo $product->color ?></h5>
            <h5><span class="las la-tshirt"></span> Size: <?php echo $product->size ?></h5>
            <h5><span class="las la-transgender"></span> Gender: <?php echo $product->get_gender() ?></h5>
        </div>

        <?php if ($type == 10) { ?>
            <form action="product.php?<?php echo $product->id ?>#navigation" method="POST">
                <div class="product-buttons">

                    <?php if (Common::in_cart($account->id, $product->id)) { ?>

                        <button type="submit" name="cmd" value="rc" class="btn btn-outline-danger">Remove from Cart</button>

                    <?php } else { ?>

                        <button type="submit" name="cmd" value="ac" class="btn btn-dark bg-black">Add to Cart</button>

                    <?php } ?>

                    <?php if (Common::in_wishlist($account->id, $product->id)) { ?>

                        <button type="submit" name="cmd" value="rw" class="btn btn-outline-success">Remove from Wishlist</button>

                    <?php } else { ?>

                        <button type="submit" name="cmd" value="aw" class="btn btn-light">Add to Wishlist</button>

                    <?php } ?>

                </div>
            </form>

        <?php } ?>

    </div>

    <hr style="margin: 20px 0;">
    <p class="display-4" style="text-align:center; margin: 80px 0 10px 0">Popular Products</p>

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

</body>

</html>