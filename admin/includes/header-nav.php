
<?php

$type = 0;
if (isset($_SESSION['account'])) {
    $type = $_SESSION['type'];
    if ($type != 1) {
        header("Location: ../logout.php");
    } else {
        $account = new Staff();
        $account->id = $_SESSION['account']->id;
        $account->read();

        if(isset($account->picture) && !empty($account->picture))
            $img = $account->picture;
        else
            $img="noImage.png";
    }
} else
    header("Location: ../index.php");

?>

<div class="search-wrapper">
                <span class="las la-search"></span>
                <input type="search" name="" id="" placeholder="Search here">
            </div>

            <div class="user-wrapper">
                <img src="../assets/uploads/<?php echo $img ?>" alt="user img" width="40px" height="40px">
                <div>
                    <ul class="show">
                        <li><h4><?php echo $account->name ?></h4></li>
                        <li><small>Administrator&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</small></li>
                        <li class="hidden first"><a href="../account-staff.php">Account</a></li>
                        <li class="hidden second"><a href="../logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </header>