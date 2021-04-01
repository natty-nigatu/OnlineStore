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

        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            if (isset($_POST["add_cat"])) {
                $category = new Category();
                $category->name = $_POST["cat_name"];
                $category->create();
                header("Location: categories.php");
            } else
                header("Location: index.php");
        }

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
        <a href="categories.php" class="active"><span class="las la-list-ul"></span>
            <span>Categories</span></a>
    </li>
    <li>
        <a href="customers.php"><span class="las la-users"></span>
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
            Categories
        </h2>
        <?php include('./includes/header-nav.php'); ?>
        <main>
            <div class="products">
                <div class="card">

                    <div class="card-body">
                        <?php if (isset($_POST['cancel_cat'])) {
                            header("location:./categories.php");
                        } ?>
                        <form action="./add_category.php" method="post" class="add-category">
                            <label for="cat_name">Category name</label>
                            <input type="text" name="cat_name" id="cat_name">
                            <button style="float: none;" type="submit" name="add_cat" id="add_cat" value="Add Category">Add Category</button>
                            <input type="submit" name="cancel_cat" id="cancel_cat" value="Cancel">
                        </form>
                    </div>
                </div>
            </div>
        </main>
</div>
</body>

</html>