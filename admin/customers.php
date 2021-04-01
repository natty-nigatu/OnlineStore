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
            Customers
        </h2>
        <?php include('./includes/header-nav.php'); ?>
        <main>
        
            <br>
            <br>
            <div class="cards customer">

            <?php $customers = Common::get_new_customers();

            foreach($customers as $c) {

                if(isset($c->picture) && !empty($c->picture))
                $img = $c->picture;
                else
                $img = "noImage.png";

                ?>

                <div class="card-single customer">
                    <div>
                        <img src="../assets/uploads/<?php echo $img ?>" alt="img" width="40px" height="40px" style="border-radius:30px;">
                        <h2><?php echo $c->name ?></h2>
                    </div>
                    <div>
                        
                    <a href="view_person.php?type=10&id=<?php echo $c->id ?>"><span class="las la-arrow-right"></span></a>
                    </div>
                </div>

                <?php } ?>
                
            </div>
        </main>
</div>
</body>

</html>