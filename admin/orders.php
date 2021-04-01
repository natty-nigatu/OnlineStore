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
        <a href="Products.php"><span class="las la-tags"></span>
            <span>Products</span></a>
    </li>
    <li>
        <a href="orders.php" class="active"><span class="las la-shopping-bag"></span>
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
            Orders
        </h2>
        <?php include('./includes/header-nav.php'); ?>
        <main>
            <div class="products">
                <div class="card">
                    <div class="card-header">
                        <h2>Orders</h2>
                    </div>



                    <div class="card-body">
                        <div class="table-responsive">
                            <table width="100%">
                                <thead>
                                    <tr>
                                        <td>Order Id</td>
                                        <td>Username</td>
                                        <td>Product name</td>
                                        <td>Qty</td>
                                        <td>Price</td>
                                        <td>Status</td>
                                        <td>Purchase Date</td>
                                        <td>Pickup Date</td>
                                        <td>Delivery Date</td>
                                        <td>Delivery Person</td>
                                    </tr>
                                </thead>
                                <tbody>

                                <?php $orders = Common::get_all_orders_reversed();
                                
                                foreach($orders as $o) {

                                    $c = new Customer();
                                    $c->id = $o->customer;
                                    $c->read();
                                    
                                    $p = new Product();
                                    $p->id = $o->product;
                                    $p->read();

                                    $s = new Staff();
                                    $s->id = $o->deliveryperson;
                                    $s->read();

                                ?>

                                    <tr>
                                        <td><?php echo $o->id ?></td>
                                        <td><a href="#"><?php echo $c->name ?></a></td>
                                        <td><a href="#"><?php echo $p->name ?></a></td>
                                        <td><?php echo $o->qty ?></td>
                                        <td><?php echo $o->price ?> ETB</td>
                                        <td><?php echo $o->get_status() ?></td>
                                        <td><?php echo $o->buydate ?></td>
                                        <td><?php echo $o->pickupdate ?></td>
                                        <td><?php echo $o->deliverydate ?></td>
                                        <td><a href="#"><?php echo $s->name ?></a></td>
                                    </tr>

                                    <?php } ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
</div>
</body>

</html>