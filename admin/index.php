<?php include('./includes/header.php') ?>

<?php

require '../data-classes.php';
session_start();


Database::connect();

$type = 0;
if (isset($_SESSION['account'])) {
    $type = $_SESSION['type'];
    if ($type != 1) {
        header("Location: ../index.php");
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
        <a href="index.php" class="active"><span class="las la-igloo"></span>
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
            Dashboard
        </h2>

        <?php include('./includes/header-nav.php'); ?>

        <main>

            <div class="cards">
                <div class="card-single">
                    <div>
                        <h1><?php echo Common::get_customer_count() ?></h1>
                        <span>Customers</span>
                    </div>
                    <div>
                        <span class="las la-users"></span>
                    </div>
                </div>

                <div class="card-single">
                    <div>
                        <h1><?php echo Common::get_product_count() ?></h1>
                        <span>Products</span>
                    </div>
                    <div>
                        <span class="las la-tags"></span>
                    </div>
                </div>

                <div class="card-single">
                    <div>
                        <h1><?php echo Common::get_order_count() ?></h1>
                        <span>Orders</span>
                    </div>
                    <div>
                        <span class="las la-shopping-bag"></span>
                    </div>
                </div>

                <div class="card-single">
                    <div>
                        <h1>ETB <?php echo round((Common::get_revenue() / 1000), 0); ?>K</h1>
                        <span>Income</span>
                    </div>
                    <div>
                        <span class="lab la-google-wallet"></span>
                    </div>
                </div>
            </div>
            <div class="recent-grid">
                <div class="products">
                    <div class="card">
                        <div class="card-header">
                            <h2>Recent Credit requests</h2>

                            <a href="#"><button>See all <span class="las la-arrow-right"></a>
                            </span></button>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table width="100%">
                                    <thead>
                                        <tr>
                                            <td>Name</td>
                                            <td>Amount</td>
                                            <td>Status</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $payments = Common::get_payments(1);

                                        foreach ($payments as $p) {

                                            $c = new Customer();
                                            $c->id = $p->customer;
                                            $c->read();

                                        ?>

                                            <tr>
                                                <td><?php echo $c->name ?></td>
                                                <td><?php echo $p->amount ?> ETB</td>
                                                <td>
                                                    <span class="status yellow"></span>
                                                    Pending
                                                </td>
                                            </tr>

                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="customers">
                    <div class="card">
                        <div class="card-header">
                            <h2>New Customers</h2>

                            <a href="customers.php"><button>See all <span class="las la-arrow-right"></a>
                            </span></button>
                        </div>

                        <div class="card-body">

                            <?php $customers = Common::get_new_customers();
                            $i = 0;

                            foreach ($customers as $c) {

                                if ($i < 5)
                                    $i++;
                                else
                                    break;

                                if (isset($c->picture) && !empty($c->picture))
                                    $img = $c->picture;
                                else
                                    $img = "noImage.png"

                            ?>
                                <div class="customer">
                                    <div class="info">
                                        <img src="../assets/uploads/<?php echo $img ?>" alt="user image" width="40px" height="40px">

                                        <div>
                                            <h4><?php echo $c->name ?></h4>
                                            <small>Customer</small>
                                        </div>
                                    </div>
                                    <div class="contact">
                                        <span class="las la-user-circle"></span>
                                        <a href="#"> <span class="las la-arrow-right"></span></a>
                                    </div>
                                </div>

                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>
</div>
</body>

</html>