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
        <a href="customers.php" class="active"><span class="las la-users"></span>
            <span>Customers</span></a>
    </li>
    <li>
        <a href="Products.php"><span class="las la-tags"></span>
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
    <header>
        <h2>
            <label for="nav-toggle">
                <span class="las la-bars"></span>
            </label>
            Add a customer
        </h2>
        <?php include('./includes/header-nav.php'); ?>
        <main>
            <div class="products">
                <div class="card">

                    <div class="card-body">
                        <?php if (isset($_POST['cancel_cust'])) {
                            header("location:./customers.php");
                        } ?>
                        <form action="./add_customer.php" method="post" class="add-customer">
                            <div class="title">
                                <h3>Add a new Customer</h3>
                            </div>
                            <div id="input-name" class="input-field">
                                <label for="cust_name">Name</label>
                                <input type="text" name="cust_name" id="cust_name">
                            </div>
                            <div id="input-email" class="input-field">
                                <label for="cust_email">E-mail</label>
                                <input type="email" name="cust_email" id="cust_email">
                            </div>
                            <div id="input-phone" class="input-field">
                                <label for="cust_phone">Phone-number</label>
                                <input type="text" name="cust_phone" id="cust_phone">
                            </div>
                            <div id="input-username" class="input-field">
                                <label for="">Username</label>
                                <input type="text" name="cust_username" id="cust_username">
                            </div>
                            <div id="input-password" class="input-field">
                                <label for="cust_password">Password</label>
                                <input type="password" name="cust_password" id="cust_password">
                            </div>
                            <div id="input-image" class="input-field">
                                <label for="cust_image">Upload Image</label>
                                <input type="file" name="cust_image" id="cust_image">
                            </div>
                            <div id="input-city" class="input-field">
                                <label for="">City</label>
                                <input type="text" name="cust_city" id="cust_city">
                            </div>
                            <div id="input-address" class="input-field">
                                <label for="">Address</label>
                                <input type="text" name="cust_address" id="cust_address">
                            </div>
                            <div id="input-submit">
                                <input type="submit" name="add_customer" id="add_customer" value="Add Customer">
                                <input type="submit" name="cancel_cust" id="cancel_cust" value="Cancel">
                            </div>






                        </form>
                    </div>
                </div>
            </div>
        </main>
</div>
</body>

</html>