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

        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            if (isset($_POST["approve"])){

                $p = new Payment();
                $p->id = $_POST["approve"];
                $p->read();
                $p->approve();

            }else if (isset($_POST["decline"])){
                
                $p = new Payment();
                $p->id = $_POST["decline"];
                $p->read();
                $p->decline();

            }

        }


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
        <a href="staff.php"><span class="las la-user-circle"></span>
            <span>Staff</span></a>
    </li>
    <li>
        <a href="tasks.php" class="active"><span class="las la-clipboard-list"></span>
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
            Tasks
        </h2>
        <?php include('./includes/header-nav.php'); ?>
        <main>
            <br>
            <br>
            <div class="cards task">
                <form action="tasks.php" method="POST">
                    <?php $proof = 0;

                    $payments = Common::get_payments(1);

                    foreach ($payments as $p) {
                        $c = new Customer();
                        $c->id = $p->customer;
                        $c->read();

                        if (isset($c->picture) && !empty($c->picture))
                            $img = $c->picture;
                        else
                            $img = "noImage.png";
                    ?>

                        <div>
                            <div class="card-single task">
                                <div>
                                    <img src="../assets/uploads/<?php echo $img ?>" alt="img" width="40px" height="40px" style="border-radius:30px;">
                                    <h2><?php echo $c->name ?></h2>
                                    <span>Credit approval amount:</span><span> <?php echo $p->amount ?>ETB</span>
                                </div>

                                <div>
                                    <span class="las la-arrow-down" onclick="myFunction('proof<?php echo $proof; ?>')"></span>
                                </div>

                            </div>
                            <br>
                            <div id="proof<?php echo "$proof";
                                            $proof = $proof + 1; ?>" class="proof" style="display:none;">
                                <a href="../assets/uploads/<?php echo $p->picture ?>" target="_blank">
                                    <img src="../assets/uploads/<?php echo $p->picture ?>" alt="proof" width="300px" height="300px"></a>

                                <div style="display: block; margin-top: 5px;">
                                    <button style="float: none; width:15%;" type="submit" name="approve" value="<?php echo $p->id ?>">Approve</button>
                                    <button style="float: none; width:15%; background-color:brown; border-color:brown;" type="submit" name="decline" value="<?php echo $p->id ?>">Decline</button>
                                </div>
                            </div>
                        </div>

                    <?php } ?>

                </form>

            </div>
        </main>
</div>
<script src="./assets/js/script.js"></script>
</body>

</html>