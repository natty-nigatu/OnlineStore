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


        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["add"])) {

            $p = new Product;

            $p->name = $_POST["name"];
            $p->color = $_POST["color"];
            $p->size = $_POST["size"];
            $p->gender = $_POST["for"];
            $p->category = $_POST["cat"];
            $p->qty = $_POST["qty"];
            $p->price = $_POST["price"];
            $p->create();

            if (isset($_FILES["img1"]) && !empty($_FILES['img1']["tmp_name"])) {
                $img = Image::upload_image_file_admin($_FILES["img1"]);
                if ($img != false)
                    $p->add_image($img);
            }
            if (isset($_FILES["img2"]) && !empty($_FILES['img2']["tmp_name"])) {
                $img = Image::upload_image_file_admin($_FILES["img2"]);
                if ($img != false)
                    $p->add_image($img);
            }
            if (isset($_FILES["img3"]) && !empty($_FILES['img3']["tmp_name"])) {
                $img = Image::upload_image_file_admin($_FILES["img3"]);
                if ($img != false)
                    $p->add_image($img);
            }
            if (isset($_FILES["img4"]) && !empty($_FILES['img4']["tmp_name"])) {
                $img = Image::upload_image_file_admin($_FILES["img4"]);
                if ($img != false)
                    $p->add_image($img);
            }

            header("location:./products.php");
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
            Add a new product
        </h2>
        <?php include('./includes/header-nav.php'); ?>
        <main>
            <div class="products">
                <div class="card">

                    <div class="card-body">
                        <?php if (isset($_POST['cancel_prod'])) {
                            header("location:./products.php");
                        } ?>
                        <form action="./add_product.php" method="post" class="add-customer" enctype="multipart/form-data">
                            <div class="title">
                                <h3>Add a new product</h3>
                            </div>
                            <div id="input-name" class="input-field">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" required>
                                <label for="color">Color</label>
                                <input type="text" name="color" id="color" required>
                            </div>
                            <div id="input-email" class="input-field">
                                <label for="size">Size</label>
                                <input type="text" name="size" id="size" required>
                                <label for="for">For: </label>
                                <select name="for" id="for" required>
                                    <option value="1">Boys</option>
                                    <option value="2">Girls</option>
                                    <option value="3">Men</option>
                                    <option value="4">Women</option>
                                    <option value="5">Unisex</option>
                                </select>
                                <label for="cat">Category</label>
                                <select name="cat" id="cat" required>
                                    <?php $categories = Common::get_categories();

                                    foreach ($categories as $c) {
                                    ?>

                                        <option value="<?php echo $c->id ?>"><?php echo $c->name ?></option>

                                    <?php } ?>
                                </select>
                            </div>
                            <div class="input-field">
                                <label for="qty">Quantity</label>
                                <input type="number" name="qty" id="qty" required>
                                <label for="price">Price</label>
                                <input type="number" name="price" id="price" required>

                            </div>
                            <div class="cards prod">
                                <div class="card-single prod">
                                    <div>
                                        <h1>Add Image</h1>
                                    </div>
                                    <div>
                                        <span id="img">
                                            <input type="file" name="img1" id="img1">
                                        </span>
                                    </div>
                                </div>
                                <div class="card-single prod">
                                    <div>
                                        <h1>Add Image</h1>
                                    </div>
                                    <div>
                                        <span id="img">
                                            <input type="file" name="img2" id="img2">
                                        </span>
                                    </div>
                                </div>
                                <div class="card-single prod">
                                    <div>
                                        <h1>Add Image</h1>
                                    </div>
                                    <div>
                                        <span id="img">
                                            <input type="file" name="img3" id="img3">
                                        </span>
                                    </div>
                                </div>
                                <div class="card-single prod">
                                    <div>
                                        <h1>Add Image</h1>
                                    </div>
                                    <div>
                                        <span id="img">
                                            <input type="file" name="img4" id="img4">
                                        </span>
                                    </div>
                                </div>
                            </div>


                            <div id="input-submit">
                                <input type="submit" name="add" id="add" value="Add Product">
                                <form action="products.php" method="POST">
                                </form style="display:inline;">
                                <form style="display:inline;">
                                    <input type="submit" formaction="products.php" value = "Cancel" >
                                </form>
                            </div>






                        </form>
                    </div>
                </div>
            </div>
        </main>
</div>
</body>

</html>