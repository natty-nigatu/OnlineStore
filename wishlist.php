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

<Head>
    <title>Cart-Fua Clothing</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="assets/css/style2.css">
    <link rel="stylesheet" href="./assets/css/style.css" type="text/css">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
</Head>

<body>

    <?php
    Database::connect();

    $url = $_SERVER["REQUEST_URI"];
    $values = parse_url($url);

    $err = "";

    if (!isset($values["query"]) || empty($values["query"]))
        $products = Common::get_all_products();
    else {
        $id =  htmlspecialchars($values["query"]);

        if (is_numeric($id)) {
            $products = Common::get_products_by_category($id);

            if (count($products) == 0) {
                $products = Common::get_all_products();
                $err = "No Product in Selected Category.";
            }
        } else {
            if (isset($_GET["q"]) && !empty($_GET['q'])) {

                $q = htmlspecialchars($_GET["q"]);
                $s = new Search();
                $products = $s->search($q);

                if (count($products) == 0) {
                    $products = Common::get_all_products();
                    $err = "No Product Found.";
                }
            } else
                $products = Common::get_all_products();
        }
    }
    ?>

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
                <li class="nav-item">
                    <a class="nav-link" href="store.php">STORE</a>
                </li>

                <?php if ($type != 0) { ?>

                    <li class="nav-item">
                        <a class="nav-link" href="account">MY ACCOUNT</a>
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
                    <li><a href="wishlist.php" style="border-bottom: solid black 2px;">Wishlist:&nbsp;<span class="number"><?php echo Common::count_wishlist($account->id) ?></span></a></li>
                    <li><a href="cart.php">Cart:&nbsp;<span class="number"><?php echo Common::count_cart($account->id) ?><span></a></li>
                    <li><a href="account.php" style="display: inline-block;">Wallet:&nbsp;<span class="wallet-balance"><?php echo $account->wallet ?> ETB</span></a></li>
                </ul>

            <?php } ?>

        </nav>
    </header>



    <!-- ==== Cart item details === -->
    <div class="cart-header bg-green float-shadow">
        <span class="color-white" style="font-size: 18px;">Products in Wishlist</span>
    </div>

    <div class="cart-form">

        <?php
        $wishlist = Common::get_wishlist($account->id);
        foreach ($wishlist as $p) {

            if (isset($p->images[0]))
                $img = $p->images[0];
            else
                $img = 'noimage.png';

        ?>

            <div class="card mb-3 text-center float-shadow " id="cart-card">
                <div class="row no-gutters">
                    <div class="col-md-4">
                        <img src="assets/uploads/<?php echo $img ?>" class="card-img" alt="Product Image">
                    </div>
                    <div class="col-md-8 cart-text-container">
                        <div class="card-body cart-card-text">
                            <h5 class="card-title" style="font-size: 30px;"><?php echo $p->name ?></h5>
                            <h5><span class="las la-eye-dropper"></span> Color: <?php echo $p->color ?></h5>
                            <h5><span class="las la-tshirt"></span> Size: <?php echo $p->size ?></h5>
                            <h5><span class="las la-transgender"></span> Gender: <?php echo $p->get_gender() ?></h5>
                            <p class="card-text" style="margin: 2% 0;"><span class="money-span">Current Price: <?php echo $p->price ?> Birr</span></p>
                            <a type="button" style="width:50%;" href="product.php?<?php echo $p->id ?>" class="btn btn-outline-success">View Product</a>
                        </div>
                    </div>
                </div>
            </div>

        <?php } ?>

    </div>
</body>

</html>