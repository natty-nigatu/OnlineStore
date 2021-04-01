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

        if (isset($_POST['id'])) {

            $s = new Staff();
            $s->id = $_POST['id'];
            $s->read();
            $_SESSION['staff_edit'] = $_POST['id'];
        } else if (isset($_POST['username'])) {

            $s = new Staff();

            if (isset($_SESSION['staff_edit'])) {
                $s->id = $_SESSION['staff_edit'];
                unset($_SESSION['staff_edit']);
            } else
                header("Location: staff.php");


            $s->read();
            $s->name = $_POST["name"];
            $s->email = $_POST["email"];
            $s->phone = $_POST["phone"];

            if (isset($_POST["password"]) && !empty($_POST["password"]))
                $s->password = $_POST["password"];

            if (isset($_FILES["image"]) && !empty($_FILES['image']["tmp_name"])) {
                $img = Image::upload_image_file_admin($_FILES["image"]);
                if ($img != false) {
                    Image::delete_image_file_admin($s->picture);
                    $s->picture = $img;
                }
            }

            $s->update();
            header("Location: staff.php");
        } else
            header("Location: staff.php");
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
            Update a staff Member
        </h2>
        <?php include('./includes/header-nav.php'); ?>
        <main>
            <div class="products">
                <div class="card">

                    <div class="card-body">
                        <?php if (isset($_POST['cancel_satff'])) {
                            header("location:./staff.php");
                        } ?>
                        <form action="./edit_staff.php" method="post" class="add-customer" enctype="multipart/form-data">
                            <div class="title">
                                <h3>Update a staff Member</h3>
                            </div>
                            <div id="input-name" class="input-field">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" value="<?php echo "$s->name" ?>">
                            </div>
                            <div id="input-email" class="input-field">
                                <label for="email">E-mail</label>
                                <input type="email" name="email" id="email" value="<?php echo $s->email ?>">
                            </div>
                            <div id="input-phone" class="input-field">
                                <label for="phone">Phone-number</label>
                                <input type="text" name="phone" id="phone" value="<?php echo $s->phone ?>">
                            </div>
                            <div id="input-username" class="input-field">
                                <label for="">Username</label>
                                <input type="text" name="username" id="username" value="<?php echo $s->username ?>" readonly>
                            </div>
                            <div id="input-password" class="input-field">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password">
                            </div>
                            <div id="input-image" class="input-field">
                                <label for="image">Upload Image</label>
                                <div style="width: -webkit-fill-available; display:flex;">

                                    <?php if (isset($s->picture) && !empty($s->picture)) { ?>

                                        <a href="../assets/uploads/<?php echo $s->picture ?>" target="_blank" style="color:black; display:inline; margin:0 2%; text-align:center;">
                                            Current Image <br> <span class="las la-arrow-right"></span></a>

                                    <?php } ?>


                                    <input type="file" name="image" id="image" style="display:inline; width: 50%">

                                </div>
                            </div>
                            <div id="input-submit">
                                <input type="submit" name="edit_staff" id="edit_staff" value="Update">
                                <input type="submit" name="cancel_satff" id="cancel_cust" value="Cancel">
                            </div>






                        </form>
                    </div>
                </div>
            </div>
        </main>
</div>
</body>

</html>