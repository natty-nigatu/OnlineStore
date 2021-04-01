<?php

class Database
{

    protected static $servername = "localhost";
    protected static $user = "root";
    protected static $pass = "MySQL";
    protected static $dbname = "onlinestore";

    protected static $conn;

    public static function connect()
    {

        // Create connection
        self::$conn = new mysqli(self::$servername, self::$user, self::$pass, self::$dbname);
    }

    public static function check()
    {

        // Check connection
        if (self::$conn->connect_error) {
            echo "Connection failed: " . self::$conn->connect_error;
            return false;
        }
        return true;
    }
}

class Staff extends Database
{

    public $id;
    public $name;
    public $email;
    public $phone;
    public $username;
    public $password;
    public $accounttype;
    public $picture;


    public function update()
    {

        if (!$this->exists())
            return false;

        $query = "UPDATE staff SET name = ?, email = ?, phone = ?," .
            " username = ?, password = ?, accounttype = ?, picture = ? " .
            "WHERE id = ?";

        $stmt = parent::$conn->prepare($query);

        $stmt->bind_param(
            "ssissisi",
            $this->name,
            $this->email,
            $this->phone,
            $this->username,
            $this->password,
            $this->accounttype,
            $this->picture,
            $this->id
        );

        return $stmt->execute();
    }


    public function create()
    {

        $query = "INSERT INTO staff (name, email, phone, username, password, accounttype, picture)
        VALUES ( '" . $this->name . "','"  . $this->email . "'," . $this->phone . ",'" . $this->username . "','"
            . $this->password . "'," . $this->accounttype . ",'" . $this->picture . "')";

        if (parent::$conn->query($query) == TRUE) {
            $this->id = parent::$conn->insert_id;
            return $this->id;;
        } else
            return 0;
    }

    public function delete()
    {

        if (!$this->exists())
            return false;

        $query = "DELETE FROM staff WHERE id = " . $this->id;;

        return parent::$conn->query($query);
    }


    public function read()
    {

        if (!$this->exists())
            return false;

        $query = "SELECT * FROM staff WHERE id = " . $this->id;

        $result = parent::$conn->query($query);

        if ($result->num_rows > 0) {

            $row = mysqli_fetch_assoc($result);
            $this->name = $row["name"];
            $this->email = $row["email"];
            $this->phone = $row["phone"];
            $this->username = $row["username"];
            $this->password = $row["password"];
            $this->accounttype = $row["accounttype"];
            $this->picture = $row["picture"];

            return true;
        }

        return false;
    }


    public function exists()
    {

        $query = "SELECT COUNT(1) AS count FROM staff WHERE id = '" . $this->id . "'";

        $result = parent::$conn->query($query);
        $row = mysqli_fetch_assoc($result);


        if ($row["count"] > 0)
            return true;

        return false;
    }
}

class Category extends Database
{
    public $id;
    public $name;
    public $products;

    public function exists()
    {

        $query = "SELECT COUNT(1) AS count FROM category WHERE id = '" . $this->id . "'";

        $result = parent::$conn->query($query);
        $row = mysqli_fetch_assoc($result);


        if ($row["count"] > 0)
            return true;

        return false;
    }

    public function update()
    {

        if (!$this->exists())
            return false;

        $query = "UPDATE category SET name = ? WHERE id = ?";

        $stmt = parent::$conn->prepare($query);

        $stmt->bind_param("si", $this->name, $this->id);

        return $stmt->execute();
    }

    public function delete()
    {

        if (!$this->exists())
            return false;

        $query = "DELETE FROM category WHERE id = " . $this->id;;

        return parent::$conn->query($query);
    }


    public function create()
    {

        $query = "INSERT INTO category (name) VALUES (?)";

        $stmt = parent::$conn->prepare($query);

        $stmt->bind_param("s", $this->name);

        if ($stmt->execute()) {
            $this->id = $stmt->insert_id;
            return $this->id;;
        } else
            return 0;
    }


    public function read()
    {

        if (!$this->exists())
            return false;

        $query = "SELECT * FROM category WHERE id = " . $this->id;

        $result = parent::$conn->query($query);

        if ($result->num_rows > 0) {

            $row = mysqli_fetch_assoc($result);
            $this->name = $row["name"];

            $query = "SELECT count(1) AS count FROM product WHERE category = $this->id";
            $result = parent::$conn->query($query);
            $row = $result->fetch_assoc();
            $this->products = $row['count'];

            return true;
        }

        return false;
    }
}

class Product extends Database
{
    public $id;
    public $name;
    public $color;
    public $size;
    public $gender;
    public $qty;
    public $price;
    public $category;
    public $images = array();
    public $cart;
    public $customer;

    public function get_gender()
    {
        switch ($this->gender) {

            case 1:
                return "Boys";


            case 2:
                return "Girls";


            case 3:
                return "Men";


            case 4:
                return "Women";


            case 5:
                return "Unisex";
        }
    }

    public function sell()
    {
        //check if they have enough money
        $customerobject = new Customer();
        $customerobject->id = $this->customer;
        $customerobject->read();

        if ($customerobject->wallet < ($this->cart * $this->price))
            return false;
        else {
            $customerobject->wallet -= ($this->cart * $this->price);
            $customerobject->update();
        }


        $this->qty -= $this->cart;
        $this->update();

        Common::remove_from_cart($this->customer, $this->id);

        $query = "INSERT INTO orderlist (status, product, qty, price, customer, buydate)
        VALUES (0, $this->id, $this->cart, $this->price , $this->customer , NOW())";

        return parent::$conn->query($query);
    }

    public function read_images()
    {

        if (!$this->exists())
            return;

        $query = "SELECT * FROM image WHERE product = $this->id";

        $result = parent::$conn->query($query);

        $this->images = array();

        while ($row = $result->fetch_assoc()) {
            array_push($this->images, $row["file"]);
        }
    }

    public function add_image($imagename)
    {

        $query = "INSERT INTO image (product, file) VALUES ($this->id, '$imagename')";

        if (parent::$conn->query($query)) {

            array_push($this->images, $imagename);
            return true;
        } else
            return false;
    }

    public function delete_image($imagename)
    {

        $query = "DELETE FROM image WHERE file = '$imagename'";


        if (parent::$conn->query($query)) {

            $size = count($this->images);

            for ($i = 0; $i < $size; $i++)

                if ($this->images[$i] == $imagename) {

                    array_splice($this->images, $i, 1);

                    break;
                }

            return true;
        } else
            return false;
    }

    public function exists()
    {

        $query = "SELECT COUNT(1) AS count FROM product WHERE id = '" . $this->id . "'";

        $result = parent::$conn->query($query);
        $row = mysqli_fetch_assoc($result);


        if ($row["count"] > 0)
            return true;

        return false;
    }

    public function update()
    {

        if (!$this->exists())
            return false;

        $query = "UPDATE product SET name = ?, color = ? , size = ?, " .
            "gender = ?, qty = ?, price = ?, category = ? WHERE id = ?";

        $stmt = parent::$conn->prepare($query);

        $stmt->bind_param(
            "sssiidii",
            $this->name,
            $this->color,
            $this->size,
            $this->gender,
            $this->qty,
            $this->price,
            $this->category,
            $this->id
        );

        return $stmt->execute();
    }

    public function delete()
    {

        if (!$this->exists())
            return false;

        $query = "DELETE FROM product WHERE id = " . $this->id;;

        return parent::$conn->query($query);
    }


    public function create()
    {

        $query = "INSERT INTO product (name, color, size, gender, qty, price, category) VALUES (?,?,?,?,?,?,?)";

        $stmt = parent::$conn->prepare($query);

        $stmt->bind_param(
            "sssiidi",
            $this->name,
            $this->color,
            $this->size,
            $this->gender,
            $this->qty,
            $this->price,
            $this->category
        );

        if ($stmt->execute()) {
            $this->id = $stmt->insert_id;
            return $this->id;;
        } else
            return 0;
    }


    public function read()
    {

        if (!$this->exists())
            return false;

        $this->read_images();

        $query = "SELECT * FROM product WHERE id = " . $this->id;

        $result = parent::$conn->query($query);

        if ($result->num_rows > 0) {

            $row = mysqli_fetch_assoc($result);
            $this->name = $row["name"];
            $this->color = $row["color"];
            $this->size = $row["size"];
            $this->gender = $row["gender"];
            $this->qty = $row["qty"];
            $this->price = $row["price"];
            $this->category = $row["category"];

            return true;
        }

        return false;
    }
}

class Customer extends Database
{
    public $id;
    public $name;
    public $email;
    public $phone;
    public $username;
    public $password;
    public $wallet;
    public $picture;
    public $city;
    public $address;

    public function exists()
    {

        $query = "SELECT COUNT(1) AS count FROM customer WHERE id = '" . $this->id . "'";

        $result = parent::$conn->query($query);
        $row = mysqli_fetch_assoc($result);


        if ($row["count"] > 0)
            return true;

        return false;
    }

    public function update()
    {

        if (!$this->exists())
            return false;

        $query = "UPDATE customer SET name = ?, email = ? , phone = ?, " .
            "username = ?, password = ?, wallet = ?, picture = ? , city = ?, address = ? WHERE id = ?";

        $stmt = parent::$conn->prepare($query);

        $stmt->bind_param(
            "ssissdsssi",
            $this->name,
            $this->email,
            $this->phone,
            $this->username,
            $this->password,
            $this->wallet,
            $this->picture,
            $this->city,
            $this->address,
            $this->id
        );

        return $stmt->execute();
    }

    public function delete()
    {

        if (!$this->exists())
            return false;

        $query = "DELETE FROM customer WHERE id = " . $this->id;;

        return parent::$conn->query($query);
    }


    public function create()
    {

        $query = "INSERT INTO customer (name, email, phone, username, password, wallet, picture, city, address) VALUES 
                    (?,?,?,?,?,?,?,?,?)";

        $stmt = parent::$conn->prepare($query);

        $stmt->bind_param(
            "ssissdsss",
            $this->name,
            $this->email,
            $this->phone,
            $this->username,
            $this->password,
            $this->wallet,
            $this->picture,
            $this->city,
            $this->address
        );

        if ($stmt->execute()) {
            $this->id = $stmt->insert_id;
            return $this->id;;
        } else
            return 0;
    }


    public function read()
    {

        if (!$this->exists())
            return false;

        $query = "SELECT * FROM customer WHERE id = " . $this->id;

        $result = parent::$conn->query($query);

        if ($result->num_rows > 0) {

            $row = mysqli_fetch_assoc($result);
            $this->name = $row["name"];
            $this->email = $row["email"];
            $this->phone = $row["phone"];
            $this->username = $row["username"];
            $this->password = $row["password"];
            $this->wallet = $row["wallet"];
            $this->picture = $row["picture"];
            $this->city = $row["city"];
            $this->address = $row["address"];

            return true;
        }

        return false;
    }
}

class Common extends Database
{
    
    public static function get_staff()
    {

        $query = "SELECT id FROM staff ORDER BY id DESC";

        $result = parent::$conn->query($query);

        $data = array();

        while ($row = $result->fetch_assoc()) {
            $p = new Staff();
            $p->id = $row["id"];
            $p->read();
            array_push($data, $p);
        }

        return $data;
    }

    public static function get_revenue()
    {
        $query = "SELECT SUM(qty*price) AS count FROM orderlist WHERE status = 2";

        $result = parent::$conn->query($query);
        $row = mysqli_fetch_assoc($result);

        return $row["count"];
    }

    public static function get_new_customers()
    {

        $query = "SELECT id FROM customer ORDER BY id DESC";

        $result = parent::$conn->query($query);

        $data = array();

        while ($row = $result->fetch_assoc()) {
            $p = new Customer();
            $p->id = $row["id"];
            $p->read();
            array_push($data, $p);
        }

        return $data;
    }

    public static function staff_username_available($username)
    {

        $query = "SELECT COUNT(1) AS count FROM staff WHERE username = '" . $username . "'";

        $result = parent::$conn->query($query);
        $row = mysqli_fetch_assoc($result);


        if ($row["count"] > 0)
            return false;

        return true;
    }

    public static function username_available($username)
    {

        $query = "SELECT COUNT(1) AS count FROM customer WHERE username = '" . $username . "'";

        $result = parent::$conn->query($query);
        $row = mysqli_fetch_assoc($result);


        if ($row["count"] > 0)
            return false;

        return true;
    }

    public static function register($username, $password)
    {
        $query = " INSERT INTO customer (username, password) VALUES ( '$username', '$password')";

        return parent::$conn->query($query);
    }

    public static function login($username, $password)
    {

        $query = 'SELECT id, username, password, accounttype FROM customer  
                WHERE username = "' . $username . '" AND password = "' . $password . '" 
                UNION SELECT id, username, password, accounttype FROM staff 
                WHERE username = "' . $username . '" AND password = "' . $password . '"';

        $result = parent::$conn->query($query);
        $data = array();

        if ($row = $result->fetch_assoc()) {

            $id = $row['id'];
            $type = $row['accounttype'];

            if ($type == 10) {
                $person = new Customer();
                $person->id = $id;
                $person->read();
            } else {

                $person = new Staff();
                $person->id = $id;
                $person->read();
            }


            $data['type'] = $type;
            $data['account'] = $person;

            return $data;
        }

        return false;
    }

    public static function get_next_image()
    {

        $query = "SELECT value FROM misc where name = 'image'";

        $result = parent::$conn->query($query);

        $row = $result->fetch_assoc();

        $image = $row["value"];

        $query = "UPDATE misc SET value = " . ($image + 1) . " WHERE name = 'image'";

        parent::$conn->query($query);

        return $image;
    }

    public static function get_customer_count()
    {

        $query = "SELECT COUNT(1) AS count FROM customer";

        $result = parent::$conn->query($query);
        $row = mysqli_fetch_assoc($result);

        return $row["count"];
    }

    public static function get_product_count()
    {

        $query = "SELECT COUNT(1) AS count FROM product";

        $result = parent::$conn->query($query);
        $row = mysqli_fetch_assoc($result);

        return $row["count"];
    }

    public static function get_order_count()
    {

        $query = "SELECT COUNT(1) AS count FROM orderlist";

        $result = parent::$conn->query($query);
        $row = mysqli_fetch_assoc($result);

        return $row["count"];
    }
    public static function get_customer_payments($customerid)
    {

        $query = "SELECT id FROM payment WHERE customer = $customerid ORDER BY  id DESC";


        $result = parent::$conn->query($query);

        $data = array();

        while ($row = $result->fetch_assoc()) {
            $p = new Payment();
            $p->id = $row["id"];
            $p->read();
            array_push($data, $p);
        }

        return $data;
    }

    public static function get_payments($status)
    {
        //0 declined //1 pending // 2 approved //3 all

        if ($status < 3)
            $query = "SELECT id FROM payment WHERE status = $status";
        else
            $query = "SELECT id FROM payment";


        $result = parent::$conn->query($query);

        $data = array();

        while ($row = $result->fetch_assoc()) {
            $p = new Payment();
            $p->id = $row["id"];
            $p->read();
            array_push($data, $p);
        }

        return $data;
    }

    public static function get_order_history($customerid)
    {

        $query = "SELECT id FROM orderlist WHERE customer = $customerid ORDER BY id DESC";

        $result = parent::$conn->query($query);

        $data = array();

        while ($row = $result->fetch_assoc()) {
            $o = new Order();
            $o->id = $row["id"];
            $o->read();
            array_push($data, $o);
        }

        return $data;
    }

    public static function get_orders_for_pickup()
    {

        $query = "SELECT id FROM orderlist WHERE status = 0 ORDER BY buydate";

        $result = parent::$conn->query($query);

        $data = array();

        while ($row = $result->fetch_assoc()) {
            $o = new Order();
            $o->id = $row["id"];
            $o->read();
            array_push($data, $o);
        }

        return $data;
    }

    public static function get_orders_for_delivery($staffid)
    {

        $query = "SELECT id FROM orderlist WHERE status = 1 AND deliveryperson = $staffid ORDER BY pickupdate";

        $result = parent::$conn->query($query);

        $data = array();

        while ($row = $result->fetch_assoc()) {
            $o = new Order();
            $o->id = $row["id"];
            $o->read();
            array_push($data, $o);
        }

        return $data;
    }

    public static function get_orders_delivered($staffid)
    {

        $query = "SELECT id FROM orderlist WHERE status = 2 AND deliveryperson = $staffid ORDER BY deliverydate DESC";

        $result = parent::$conn->query($query);

        $data = array();

        while ($row = $result->fetch_assoc()) {
            $o = new Order();
            $o->id = $row["id"];
            $o->read();
            array_push($data, $o);
        }

        return $data;
    }

    public static function get_all_orders()
    {

        $query = "SELECT id FROM orderlist ORDER BY buydate";

        $result = parent::$conn->query($query);

        $data = array();

        while ($row = $result->fetch_assoc()) {
            $o = new Order();
            $o->id = $row["id"];
            $o->read();
            array_push($data, $o);
        }

        return $data;
    }

    public static function get_all_orders_reversed()
    {

        $query = "SELECT id FROM orderlist ORDER BY id DESC";

        $result = parent::$conn->query($query);

        $data = array();

        while ($row = $result->fetch_assoc()) {
            $o = new Order();
            $o->id = $row["id"];
            $o->read();
            array_push($data, $o);
        }

        return $data;
    }

    public static function get_product($id)
    {
        $query = "SELECT id FROM product WHERE id = $id ORDER BY name";

        $result = parent::$conn->query($query);


        if ($row = $result->fetch_assoc()) {
            $p = new Product();
            $p->id = $row["id"];
            $p->read();
            return $p;
        }

        return false;
    }

    public static function get_products_by_category($category)
    {
        $query = "SELECT id FROM product WHERE category = $category ORDER BY name";

        $result = parent::$conn->query($query);

        $data = array();

        while ($row = $result->fetch_assoc()) {
            $p = new Product();
            $p->id = $row["id"];
            $p->read();
            array_push($data, $p);
        }

        return $data;
    }

    public static function get_categories()
    {

        $query = "SELECT id FROM category ORDER BY name";

        $result = parent::$conn->query($query);

        $data = array();

        while ($row = $result->fetch_assoc()) {
            $p = new Category();
            $p->id = $row["id"];
            $p->read();
            array_push($data, $p);
        }

        return $data;
    }

    public static function get_all_products()
    {

        $query = "SELECT id FROM product ORDER BY name";

        $result = parent::$conn->query($query);

        $data = array();

        while ($row = $result->fetch_assoc()) {
            $p = new Product();
            $p->id = $row["id"];
            $p->read();
            array_push($data, $p);
        }

        return $data;
    }

    public static function get_top_selling()
    {

        $query = "SELECT product, sum(qty) AS qty FROM orderlist GROUP BY product ORDER BY qty DESC";

        $result = parent::$conn->query($query);

        $data = array();

        while ($row = $result->fetch_assoc()) {
            $p = new Product();
            $p->id = $row["product"];
            $p->read();
            array_push($data, $p);
        }

        return $data;
    }

    public static function get_wishlist($customerid)
    {

        $query = "SELECT * FROM wishlist WHERE customer = $customerid";

        $result = parent::$conn->query($query);

        $data = array();

        while ($row = $result->fetch_assoc()) {
            $p = new Product();
            $p->id = $row["product"];
            $p->read();
            array_push($data, $p);
        }

        return $data;
    }

    public static function get_cart($customerid)
    {

        $query = "SELECT * FROM cart WHERE customer = $customerid";

        $result = parent::$conn->query($query);

        $data = array();

        while ($row = $result->fetch_assoc()) {
            $p = new Product();
            $p->id = $row["product"];
            $p->cart = $row["qty"];
            $p->customer = $customerid;
            $p->read();
            array_push($data, $p);
        }

        return $data;
    }

    public static function count_wishlist($customerid)
    {

        $query = "SELECT COUNT(1) as count FROM wishlist WHERE customer = $customerid";

        $result = parent::$conn->query($query);

        $row = $result->fetch_assoc();

        return $row["count"];
    }

    public static function edit_cart($customerid, $productid, $qty)
    {

        $query = "UPDATE cart SET qty = $qty WHERE customer = $customerid AND product= $productid";

        return parent::$conn->query($query);
    }

    public static function total_cart($customerid)
    {

        $query = "SELECT sum(cart.qty * product.price) as count FROM cart JOIN product ON cart.product = product.id
                                WHERE cart.customer = $customerid";

        $result = parent::$conn->query($query);

        $row = $result->fetch_assoc();

        return $row["count"];
    }

    public static function count_cart($customerid)
    {

        $query = "SELECT COUNT(1) as count FROM cart WHERE customer = $customerid";

        $result = parent::$conn->query($query);

        $row = $result->fetch_assoc();

        return $row["count"];
    }

    public static function remove_from_wishlist($customerid, $productid)
    {

        $query = "DELETE FROM wishlist WHERE customer = $customerid AND product = $productid";

        return parent::$conn->query($query);
    }

    public static function remove_from_cart($customerid, $productid)
    {

        $query = "DELETE FROM cart WHERE customer = $customerid AND product = $productid";

        return parent::$conn->query($query);
    }

    public static function in_wishlist($customerid, $productid)
    {

        $query = "SELECT COUNT(1) AS count FROM wishlist WHERE customer = $customerid AND product = $productid";

        $result = parent::$conn->query($query);
        $row = mysqli_fetch_assoc($result);


        if ($row["count"] > 0)
            return true;

        return false;
    }

    public static function in_cart($customerid, $productid)
    {

        $query = "SELECT COUNT(1) AS count FROM cart WHERE customer = $customerid AND product = $productid";

        $result = parent::$conn->query($query);
        $row = mysqli_fetch_assoc($result);


        if ($row["count"] > 0)
            return true;

        return false;
    }

    public static function add_to_wishlist($customerid, $productid)
    {

        $query = "INSERT INTO wishlist (customer, product) VALUES ($customerid, $productid)";

        return parent::$conn->query($query);
    }

    public static function add_to_cart($customerid, $productid, $qty)
    {

        $query = "INSERT INTO cart (customer, product, qty) VALUES ($customerid, $productid, $qty)";

        return parent::$conn->query($query);
    }
}

class Image extends Database
{

    public static $upload_image_err;

    public static function upload_image_file_admin($file)
    {

        $target_dir = "../assets/uploads/";
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo(basename($file["name"]), PATHINFO_EXTENSION));


        // Check if image file is a actual image or fake image
        //if (isset($_POST["submit"])) 
        {
            $check = getimagesize($file["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                self::$upload_image_err =  "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Check file size
        if ($file["size"] > 500000) {
            self::$upload_image_err =   "File is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            self::$upload_image_err =   "Only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            return false;
            // if everything is ok, try to upload file
        } else {

            $target_file = Common::get_next_image() . ".$imageFileType";

            if (move_uploaded_file($file["tmp_name"],  $target_dir . $target_file)) {
                return $target_file;
            } else {
                self::$upload_image_err =  "Uploading error.";
                return false;
            }
        }
    }

    public static function upload_image_file($file)
    {

        $target_dir = "assets/uploads/";
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo(basename($file["name"]), PATHINFO_EXTENSION));


        // Check if image file is a actual image or fake image
        //if (isset($_POST["submit"])) 
        {
            $check = getimagesize($file["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                self::$upload_image_err =  "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Check file size
        if ($file["size"] > 500000) {
            self::$upload_image_err =   "File is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            self::$upload_image_err =   "Only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            return false;
            // if everything is ok, try to upload file
        } else {

            $target_file = Common::get_next_image() . ".$imageFileType";

            if (move_uploaded_file($file["tmp_name"],  $target_dir . $target_file)) {
                return $target_file;
            } else {
                self::$upload_image_err =  "Uploading error.";
                return false;
            }
        }
    }

    public static function delete_image_file_admin($file)
    {
        $dir = "../assets/uploads/";

        return unlink($dir . $file);
    }

    public static function delete_image_file($file)
    {
        $dir = "assets/uploads/";

        return unlink($dir . $file);
    }
}

class Search extends Database
{

    private $allproducts;

    public function __construct()
    {
        $this->refresh();
    }

    public function refresh()
    {
        $this->allproducts = Common::get_all_products();
    }

    private function strip($str)
    {
        return str_replace(" ", "", $str);
    }

    public function search($searchKey)
    {

        $filteredlist = array();

        if ($searchKey == null || strlen($searchKey) == 0) {
            return $filteredlist;
        }

        if (strlen($searchKey) <= 2)
            foreach ($this->allproducts as $product) {
                if ((stripos($this->strip($product->name), $this->strip($searchKey))) === 0)
                    array_push($filteredlist, $product);
            }
        else
            foreach ($this->allproducts as $product) {
                if (stripos($this->strip($product->name), $this->strip($searchKey)) !== FALSE)
                    array_push($filteredlist, $product);
            }

        return $filteredlist;
    }
}

class Order extends Database
{
    public $id;
    public $status;
    public $product;
    public $qty;
    public $price;
    public $customer;
    public $buydate;
    public $pickupdate;
    public $deliverydate;
    public $deliveryperson;


    public function get_status()
    {

        switch ($this->status) {

            case 0:
                return "Processing";

            case 1:
                return "Picked up";

            case 2:
                return "Delivered";

            default:
                return "Unknown";
        }
    }

    public function read()
    {

        $query = "SELECT * FROM orderlist WHERE id = " . $this->id;

        $result = parent::$conn->query($query);

        if ($result->num_rows > 0) {

            $row = mysqli_fetch_assoc($result);
            $this->status = $row["status"];
            $this->product = $row["product"];
            $this->qty = $row["qty"];
            $this->price = $row["price"];
            $this->customer = $row["customer"];
            $this->buydate = $row["buydate"];
            $this->pickupdate = $row["pickupdate"];
            $this->deliverydate = $row["deliverydate"];
            $this->deliveryperson = $row["deliveryperson"];

            return true;
        }

        return false;
    }

    public function pickup($staffid)
    {

        if ($this->status != 0)
            return false;

        $query = "UPDATE orderlist SET status = 1, deliveryperson = $staffid, 
            pickupdate = NOW() WHERE id = $this->id";


        if (parent::$conn->query($query)) {
            $this->read();
            return true;
        }

        return false;
    }


    public function deliver()
    {

        if ($this->status != 1)
            return false;

        $query = "UPDATE orderlist SET status = 2, deliverydate = NOW() WHERE id = $this->id";


        if (parent::$conn->query($query)) {
            $this->read();
            return true;
        }

        return false;
    }
}

class Payment extends Database
{
    public $id;
    public $customer;
    public $amount;
    public $picture;
    public $status;

    public function get_status()
    {

        switch ($this->status) {

            case 0:
                return "Declined";

            case 1:
                return "Processing";

            case 2:
                return "Accepted";

            default:
                return "Unknown";
        }
    }


    public function approve()
    {

        if ($this->status == 2)
            return false;

        $query = "UPDATE customer SET wallet = (wallet + $this->amount) WHERE id = $this->customer";

        if (parent::$conn->query($query)) {

            $this->status = 2;

            $query = "UPDATE payment SET status = 2 WHERE id = $this->id";

            return (parent::$conn->query($query));
        }

        return false;
    }

    public function decline()
    {

        if ($this->status == 2 || $this->status == 0)
            return false;

        $this->status = 0;

        $query = "UPDATE payment SET status = 0 WHERE id = $this->id";

        return (parent::$conn->query($query));
    }

    public function read()
    {

        $query = "SELECT * FROM payment WHERE id = " . $this->id;

        $result = parent::$conn->query($query);

        if ($result->num_rows > 0) {

            $row = $result->fetch_assoc();
            $this->customer = $row["customer"];
            $this->amount = $row["amount"];
            $this->picture = $row["picture"];
            $this->status = $row["status"];

            return true;
        }

        return false;
    }

    public function create()
    {

        $query = "INSERT INTO payment (customer, amount, picture) VALUES ($this->customer, $this->amount, '$this->picture')";

        return parent::$conn->query($query);
    }
}
