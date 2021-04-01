<?php

require 'data-classes.php';
session_start();

Database::connect();

$type = 0;
$err = false;
if (isset($_SESSION['account'])) {
    $type = $_SESSION['type'];
    if ($type == 10) {
        $account = new Customer();
        $account->id = $_SESSION['account']->id;
        $account->read();
    } else {
        if ($type == 2)
            die(header("Location: deliver.php"));
        else if ($type == 1)
            die(header("Location: admin/index.php"));
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (Common::count_cart($account->id) == 0)
            header("Location: store.php");

        $total = Common::total_cart($account->id);

        if ($total > $account->wallet)
            $err = true;
        else {
            $cart = Common::get_cart($account->id);

            foreach ($cart as $p) {
                $p->sell();
            }
            //reaload account data after sell
            $account->read();
        }
    } else
        die(header("Location: index.php"));
} else

    die(header("Location: index.php"));

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


    <link rel="stylesheet" href="assets/css/style2.css">
    <link rel="stylesheet" href="./assets/css/style.css" type="text/css">
</Head>

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

    <!-- ==== Checkout ++++ -->

    <?php if ($err) { ?>

        <h1 class="display-4"> Request Denied due to insufficient Wallet Balance </h1>

    <?php } else { ?>
        <h1 class="display-4"> Order is Completed! </h1>
        <div class="detail-form detail-content">

            <div>
                <h3>Shipping info</h3>
            </div>

            <div class="form-group">
                <strong>Name:&nbsp;&nbsp;</strong> <br />
                <span><?php echo $account->name ?></span>
            </div>

            <div class="form-group">
                <strong>Email:&nbsp;&nbsp;</strong><br />
                <span><?php echo $account->email ?></span>
            </div>

            <div class="form-group">
                <strong>Phone:&nbsp;&nbsp;</strong><br />
                <span>+251<?php echo $account->phone ?></span>
            </div>

            <div class="form-group">
                <strong>City:&nbsp;&nbsp;</strong><br />
                <span><?php echo $account->city ?></span>
            </div>

            <div class="form-group">
                <strong>Address:&nbsp;&nbsp;</strong><br />
                <span><?php echo $account->address ?></span>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <h3>Wallet Balance: <?php echo $account->wallet ?> ETB</h3>
            </div>



        </div>

    <?php } ?>
</body>

</html>