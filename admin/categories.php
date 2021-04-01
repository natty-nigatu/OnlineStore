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
                    <div class="card-header">
                        <h2>Category List</h2>

                        <a href="add_category.php"><button>Add a new category <span class="las la-arrow-right"></a>
                        </span></button>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table width="100%">
                                <thead>
                                    <tr>
                                        <td>Category name</td>
                                        <td>Number of products</td>
                                        <td>Actions</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <form action="edit_category.php" method="POST">
                                        <?php $cs = Common::get_categories();
                                        foreach ($cs as $c) {

                                        ?>
                                            <tr>
                                                <td><?php echo $c->name ?></td>
                                                <td><?php echo $c->products ?></td>
                                                <td>
                                                    <button type="submit" name="edit" value="<?php echo $c->id ?>"><span class="lar la-edit"></span>Edit&nbsp;&nbsp;</button>
                                                </td>
                                            </tr>

                                        <?php } ?>
                                    </form>
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