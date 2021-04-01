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


        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (isset($_POST["payment"])) {

                if (isset($_FILES["proof"]) && !empty($_FILES['proof']["tmp_name"])) {
                    $img = Image::upload_image_file($_FILES["proof"]);
                    $amount = intval($_POST["amount"]);

                    if ($img != false) {

                        $p = new Payment();
                        $p->customer = $account->id;
                        $p->amount = $amount;
                        $p->picture = $img;
                        echo ($p->create());
                    }
                }
            } else if (isset($_POST['edit'])) {

                if (isset($_FILES["image"]) && !empty($_FILES['image']["tmp_name"])) {
                    $img = Image::upload_image_file($_FILES["image"]);

                    if ($img != false) {
                        $account->picture = $img;
                    }
                }

                $account->name = $_POST['name'];
                $account->email = $_POST['email'];
                $account->phone = $_POST['phone'];
                $account->city = $_POST['city'];
                $account->address = $_POST['address'];

                if (!$account->update())
                    $account->read();
            }
        }
    } else {
        header("Location: account-staff.php");
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

<body class="body-margin">

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


    <header id="products-list">
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
        <div class="col">

            <div class="account-form">

                <form action="account.php" method="post" class="add-customer" enctype="multipart/form-data">
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
                        <input type="file" name="image" id="image" accept="image/png ,image/jpeg">
                    </div>
                    <div id="input-city" class="input-field">
                        <label for="">City</label>
                        <input type="text" name="city" id="city" value="<?php echo $account->city ?>">
                    </div>
                    <div id="input-address" class="input-field">
                        <label for="">Address</label>
                        <input type="text" name="address" id="address" value="<?php echo $account->address ?>">
                    </div>
                    <div id="input-submit">
                        <input class="btn btn-dark bg-black btn-half" type="submit" style="width: -webkit-fill-available;" name="edit" id="edit" value="Update">
                    </div>






                </form>
            </div>

        </div>

        <div class="col">

            <div class="account-form">
                <form action="account.php" method="post" class="add-customer" enctype="multipart/form-data">
                    <div class="title">
                        <h3>Wallet Details</h3>
                    </div>
                    <hr>

                    <div class="title input-field" style="margin: 40px 0;">
                        <h4 class="h" style="display: inline;">Current Balance </h4>
                        <h4 style="display: inline;"> <?php echo $account->wallet ?> ETB</h4>
                    </div>
                    <hr>

                    <div class="title">
                        <h3>Send Recharge Request</h3>
                    </div>

                    <div id="input-phone" class="input-field">
                        <label for="phone">Amount</label>
                        <input type="number" name="amount" id="amount" required min="0">
                    </div>

                    <div id="input-image" class="input-field">
                        <label for="image">Deposit Proof</label>
                        <input type="file" name="proof" id="proof" required accept="image/png ,image/jpeg">
                    </div>
                    <div id="input-submit">
                        <input class="btn btn-dark bg-black btn-half" type="submit" style="width: -webkit-fill-available;" name="payment" id="payment" value="Send Request">
                    </div>


                </form>
            </div>
        </div>
    </div>

    <div class="products">
        <div class="card" style="height: fit-content;">
            <div class="card-header">
                <h2>Orders</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table width="100%">
                        <thead>
                            <tr>
                                <td>Order Id</td>
                                <td>Product name</td>
                                <td>Qty</td>
                                <td>Price</td>
                                <td>Status</td>
                                <td>Purchase Date</td>
                                <td>Pickup Date</td>
                                <td>Delivery Date</td>
                            </tr>
                        </thead>
                        <tbody>


                            <?php $orders = Common::get_order_history($account->id);

                            foreach ($orders as $o) {

                                $p = new Product();
                                $p->id = $o->product;
                                $p->read();
                            ?>
                                <tr>
                                    <td><?php echo $o->id ?></td>
                                    <td><a href="product.php?<?php echo $o->product ?>"><?php echo $p->name ?></a></td>
                                    <td><?php echo $o->qty ?></td>
                                    <td><?php echo $o->price ?> ETB</td>
                                    <td><?php echo $o->get_status() ?></td>
                                    <td><?php echo $o->buydate ?></td>
                                    <td><?php echo $o->pickupdate ?></td>
                                    <td><?php echo $o->deliverydate ?></td>
                                </tr>

                            <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



    <div class="products">
        <div class="card" style="height: fit-content;">
            <div class="card-header">
                <h2>Wallet</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table width="100%">
                        <thead>
                            <tr>
                                <td>Id</td>
                                <td>Amount</td>
                                <td>Deposit Proof</td>
                                <td>Status</td>
                            </tr>
                        </thead>
                        <tbody>

                            <?php

                            $payments = Common::get_customer_payments($account->id);

                            foreach ($payments as $p) {

                            ?>
                                <tr>
                                    <td><?php echo $p->id ?></td>
                                    <td><?php echo $p->amount ?> ETB</td>
                                    <td><a target="_blank" href="assets/uploads/<?php echo $p->picture ?>">Image</a></td>
                                    <td><?php echo $p->get_status() ?></td>
                                </tr>

                            <?php } ?>

                        </tbody>
                    </table>
                </div>
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