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
    <header>
        <h2>
            <label for="nav-toggle">
                <span class="las la-bars"></span>
            </label>
            Products
        </h2>

        <?php include('./includes/header-nav.php'); ?>
        <main>
            <a href="add_product.php"><button>Add a new product <span class="las la-arrow-right"></a>
            </span></button>
            <br>
            <br>
            <form action="edit_product.php" method="POST">
                <div class="cards product">

                    <?php $products = Common::get_all_products();

                    foreach ($products as $p) {

                        if (isset($p->images[0]))
                            $img = $p->images[0];
                        else
                            $img = "noImage.png";
                    ?>

                        <div class="card-single product">
                            <div>
                                <img src="../assets/uploads/<?php echo $img ?>" alt="Img" width="40px" height="40px">
                                <h2><?php echo $p->name ?></h2>
                            </div>
                            <div>
                                <button type="submit" name="id" value="<?php echo $p->id ?>" class="staff-btns"> <span class="lar la-edit"></span> </button>
                                
                                <a href="view_product.php?id=<?php echo $p->id ?>" ><span class="las la-arrow-right"></span></a>
                            </div>
                        </div>

                    <?php } ?>
                </div>
            </form>
        </main>
</div>
</body>

</html>