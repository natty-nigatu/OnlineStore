<?php include('./includes/header.php') ?>

<?php

require '../data-classes.php';
session_start();


Database::connect();

$err = "";
$type = 0;
if (isset($_SESSION['account'])) {
    $type = $_SESSION['type'];
    if ($type != 1) {
        header("Location: ../logout.php");
    } else {
        $account = new Staff();
        $account->id = $_SESSION['account']->id;
        $account->read();


        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["add"])) {

            if (!Common::staff_username_available($_POST["username"]))
                $err = "Username is already taken.";  

            else {

                $s = new Staff();

                $s->name = $_POST["name"];
                $s->email = $_POST["email"];
                $s->phone = $_POST["phone"];
                $s->username = $_POST["username"];
                $s->password = $_POST["password"];
                $s->accounttype = $_POST["type"];

                if (isset($_FILES["image"]) && !empty($_FILES['image']["tmp_name"])) {
                    $img = Image::upload_image_file_admin($_FILES["image"]);
                    if ($img != false)
                        $s->picture = $img;
                }

                $s->create();

                header("Location: staff.php");
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
            Add a Staff Member
        </h2>
        <?php include('./includes/header-nav.php'); ?>
        <main>
            <div class="products">
                <div class="card">

                    <div class="card-body">
                        <?php if (isset($_POST['cancel_staff'])) {
                            header("location:./staff.php");
                        } ?>
                        <form action="./add_staff.php" method="post" class="add-customer" enctype="multipart/form-data">
                            <div class="title">
                                <h3>Add a new Staff Memeber</h3>
                            </div>
                            <div id="input-name" class="input-field">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" value="<?php if(isset($_POST['name'])) echo $_POST['name'] ?>">
                            </div>
                            <div id="input-email" class="input-field">
                                <label for="email">E-mail</label>
                                <input type="email" name="email" id="email" value="<?php if(isset($_POST['email'])) echo $_POST['email'] ?>">
                            </div>
                            <div id="input-phone" class="input-field">
                                <label for="phone">Phone</label>
                                <input type="text" name="phone" id="phone" value="<?php if(isset($_POST['phone'])) echo $_POST['phone'] ?>">
                            </div>

                            <div class="title">
                                <h4 style="color:brown; text-align:center;"><?php echo $err ?></h4>
                            </div>
                            <div id="input-username" class="input-field">
                                <label for="">Username</label>
                                <input type="text" name="username" id="username" required value="<?php if(isset($_POST['username'])) echo $_POST['username'] ?>">
                            </div>
                            <div id="input-password" class="input-field">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" required value="<?php if(isset($_POST['password'])) echo $_POST['password'] ?>">
                            </div>
                            <div id="input-type" class="input-field">
                                <label for="size">Account Type</label>
                            <select name="type" id="type" required>
                                    <option value="1" <?php if(isset($_POST['type']) && $_POST['type'] == 1) echo 'selected' ?> >Admin</option>
                                    <option value="2" <?php if(isset($_POST['type']) && $_POST['type'] == 2) echo 'selected' ?> >Delivery</option>
                            </select>
                            </div>
                            <div id="input-image" class="input-field">
                                <label for="image">Upload Image</label>
                                <input type="file" name="image" id="image">
                            </div>
                            <div id="input-submit">
                                <input type="submit" name="add" id="add" value="Add Staff">
                                <form action="staff.php" method="POST">
                                </form style="display:inline;">
                                <form style="display:inline;">
                                    <input type="submit" formaction="staff.php" value="Cancel">
                                </form>
                            </div>






                        </form>
                    </div>
                </div>
            </div>
        </main>
    </header>
</div>
</body>

</html>