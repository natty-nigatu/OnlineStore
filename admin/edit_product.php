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

            $p = new Product();
            $p->id = $_POST['id'];
            $p->read();
            $_SESSION['product_edit'] = $_POST['id'];

        } else if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST["add"])) {

            $p = new Product;

            if (isset($_SESSION['product_edit'])) {
                $p->id =  $_SESSION['product_edit'];
                unset($_SESSION['product_edit']);
            } else
                header("Location: product.php");

            $p->read();
            $p->name = $_POST["name"];
            $p->color = $_POST["color"];
            $p->size = $_POST["size"];
            $p->gender = $_POST["for"];
            $p->category = $_POST["cat"];
            $p->qty = $_POST["qty"];
            $p->price = $_POST["price"];
            $p->update();

            if (isset($_FILES["img4"]) && !empty($_FILES['img4']["tmp_name"]))
                if (isset($p->images[3])) {
                    Image::delete_image_file_admin($p->images[3]);
                    $p->delete_image($p->images[3]);
                }

            if (isset($_FILES["img3"]) && !empty($_FILES['img3']["tmp_name"]))
                if (isset($p->images[2])) {
                    Image::delete_image_file_admin($p->images[2]);
                    $p->delete_image($p->images[2]);
                }

            if (isset($_FILES["img2"]) && !empty($_FILES['img2']["tmp_name"]))
                if (isset($p->images[1])) {
                    Image::delete_image_file_admin($p->images[1]);
                    $p->delete_image($p->images[1]);
                }

            if (isset($_FILES["img1"]) && !empty($_FILES['img1']["tmp_name"]))
                if (isset($p->images[0])) {
                    Image::delete_image_file_admin($p->images[0]);
                    $p->delete_image($p->images[0]);
                }


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
                        <form action="./edit_product.php" method="post" class="add-customer" enctype="multipart/form-data">
                            <div class="title">
                                <h3>Edit product details</h3>
                            </div>
                            <div id="input-name" class="input-field">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" required value="<?php echo $p->name ?>">
                                <label for="color">Color</label>
                                <input type="text" name="color" id="color" required value="<?php echo $p->color ?>">
                            </div>
                            <div id="input-email" class="input-field">
                                <label for="size">Size</label>
                                <input type="text" name="size" id="size" required value="<?php echo $p->size ?>">
                                <label for="for">For: </label>
                                <select name="for" id="for" required>
                                    <option value="1" <?php if ($p->gender == 1) echo 'selected' ?>>Boys</option>
                                    <option value="2" <?php if ($p->gender == 2) echo 'selected' ?>>Girls</option>
                                    <option value="3" <?php if ($p->gender == 3) echo 'selected' ?>>Men</option>
                                    <option value="4" <?php if ($p->gender == 4) echo 'selected' ?>>Women</option>
                                    <option value="5" <?php if ($p->gender == 5) echo 'selected' ?>>Unisex</option>
                                </select>
                                <label for="cat">Category</label>
                                <select name="cat" id="cat" required>
                                    <?php $categories = Common::get_categories();

                                    foreach ($categories as $c) {
                                    ?>

                                        <option value="<?php echo $c->id ?>" <?php if ($p->category == $c->id) echo 'selected' ?>><?php echo $c->name ?></option>

                                    <?php } ?>

                                </select>
                            </div>
                            <div class="input-field">
                                <label for="qty">Quantity</label>
                                <input type="number" name="qty" id="qty" required value="<?php echo $p->qty ?>">
                                <label for="price">Price</label>
                                <input type="number" name="price" id="price" required value="<?php echo $p->price ?>">

                            </div>
                            <div class="cards prod">
                                <div class="card-single prod">
                                    <div>

                                        <?php if (isset($p->images[0])) { ?>
                                            <img src="../assets/uploads/<?php echo $p->images[0] ?>" style="width: 100px; height: 100px; object-fit: cover;" alt="product">
                                        <?php } else { ?>
                                            <h1>Add Image</h1>
                                        <?php } ?>

                                    </div>
                                    <div>
                                        <span id="img">
                                            <?php if (isset($p->images[0])) { ?>
                                                <h3> Change Image </h3>
                                            <?php } ?>
                                            <input type="file" name="img1" id="img1">
                                        </span>
                                    </div>
                                </div>

                                <div class="card-single prod">
                                    <div>
                                        <?php if (isset($p->images[1])) { ?>
                                            <img src="../assets/uploads/<?php echo $p->images[1] ?>" style="width: 100px; height: 100px; object-fit: cover;" alt="product">
                                        <?php } else { ?>
                                            <h1>Add Image</h1>
                                        <?php } ?>
                                    </div>
                                    <div>
                                        <span id="img">
                                            <?php if (isset($p->images[1])) { ?>
                                                <h3> Change Image </h3>
                                            <?php } ?>
                                            <input type="file" name="img2" id="img2">
                                        </span>
                                    </div>
                                </div>
                                <div class="card-single prod">
                                    <div>
                                        <?php if (isset($p->images[2])) { ?>
                                            <img src="../assets/uploads/<?php echo $p->images[2] ?>" style="width: 100px; height: 100px; object-fit: cover;" alt="product">
                                        <?php } else { ?>
                                            <h1>Add Image</h1>
                                        <?php } ?>
                                    </div>
                                    <div>
                                        <span id="img">
                                            <?php if (isset($p->images[2])) { ?>
                                                <h3> Change Image </h3>
                                            <?php } ?>
                                            <input type="file" name="img3" id="img3">
                                        </span>
                                    </div>
                                </div>
                                <div class="card-single prod">
                                    <div>
                                        <?php if (isset($p->images[3])) { ?>
                                            <img src="../assets/uploads/<?php echo $p->images[3] ?>" style="width: 100px; height: 100px; object-fit: cover;" alt="product">
                                        <?php } else { ?>
                                            <h1>Add Image</h1>
                                        <?php } ?>
                                    </div>
                                    <div>
                                        <span id="img">
                                            <?php if (isset($p->images[3])) { ?>
                                                <h3> Change Image </h3>
                                            <?php } ?>
                                            <input type="file" name="img4" id="img4">
                                        </span>
                                    </div>
                                </div>
                            </div>


                            <div id="input-submit">
                                <input type="submit" name="add" id="add" value="Update Product">
                                <form action="products.php" method="POST">
                                </form style="display:inline;">
                                <form style="display:inline;">
                                    <input type="submit" formaction="products.php" value="Cancel">
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