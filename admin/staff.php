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
        <a href="orders.php"><span class="las la-shopping-bag"></span>
            <span>Orders</span></a>
    </li>
    <li>
        <a href="staff.php" class="active"><span class="las la-user-circle"></span>
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
            Staff
        </h2>
        <?php include('./includes/header-nav.php'); ?>
        <main>
            <a href="add_staff.php"><button>Add a new Staff member <span class="las la-arrow-right">
                    </span></button></a>
            <br>
            <br>

            <form action="edit_staff.php" method="POST">

                <div class="cards customer">

                    <?php

                    $staff = Common::get_staff();

                    foreach ($staff as $s) {

                        if (isset($s->picture))
                            $img = $s->picture;
                        else
                            $img = "noImage.png";

                    ?>


                        <div class="card-single customer">
                            <div>
                                <img src="../assets/uploads/<?php echo $img ?>" alt="" width="40px" height="40px" style="border-radius:30px;">
                                <h2><?php echo $s->name ?></h2>
                            </div>
                            <div>
                                <button type="submit" name="id" value="<?php echo $s->id ?>" class="staff-btns"> <span class="lar la-edit"></span> </button>
                                <span class="las la-trash"></span>
                                <a href="view_person.php?type=1&id=<?php echo $s->id ?>"><span class="las la-arrow-right"></span></a>
                            </div>
                        </div>

                    <?php } ?>


                </div>
            </form>
        </main>
    </header>
</div>
</body>

</html>