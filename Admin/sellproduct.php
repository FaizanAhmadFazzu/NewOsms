<?php
define('TITLE', 'Sell Product');
define('PAGE', 'assets');
require "includes/header.php";
require "../dbConnection.php";
// Initialize the session
session_start();

// Chaeck if the admin is  logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Define variables and initialize with empty values

$c_nameErr = $c_addErr   = $p_quantityErr  =  $total_priceErr  = $sell_dateErr =  "";
$c_name = $c_add = $p_name  = $p_quantity =  $total_price  = $sell_date =  "";
if (isset($_POST['billsubmit'])) {
    // Validate customer name
    if (empty($_POST["c_name"])) {
        $c_nameErr = "Customer Name is required";
    } else {

        $c_name = test_input($_POST["c_name"]);
    }
    // Validate Customer Address
    if (empty($_POST["c_add"])) {
        $c_addErr = "Product Name is required";
    } else {

        $c_add = test_input($_POST["c_add"]);
    }
    // Validate product quantity
    if (empty($_POST["p_quantity"])) {
        $p_quantityErr = "Product Quantity is required";
    } else {

        $p_quantity = $_POST["p_quantity"];
    }
    // Validate  total price
    if (empty($_POST["total_cost"])) {
        $total_costErr = "This field is required";
    } else {
        $total_cost = $_POST["total_cost"];
    }
    // Validate  sell date
    if (empty($_POST["sell_date"])) {
        $sell_dateErr = "This field is required";
    } else {
        $sell_date = $_POST["sell_date"];
    }

    $p_name = $_POST['p_name']; 
    $p_sellingcost = $_POST['p_sellingcost']; 
    

    // Check input errors before inserting in database
    if (empty($c_nameErr) && empty($c_addErr) &&  empty($p_quantityErr) && empty($total_PriceErr) && empty($sell_dateErr)) {
        $sql = "INSERT INTO customer(c_name, c_add, p_name, p_quantity, p_sellingcost, total_cost, sell_date) VALUES('$c_name', '$c_add', '$p_name', '$p_quantity', '$p_sellingcost', '$total_cost', '$sell_date')";
        if ($conn->query($sql) == TRUE) {
            $p_ava = ($_POST['p_ava'] - $_POST['p_quantity']);
            // below function captures inserted id
            $genid = mysqli_insert_id($conn);
            session_start();
            $_SESSION['c_id'] = $genid;
            echo "<script>location.href='productsellsuccess.php';
            </script>";
            // Updating Asset data for available product agter sell
        $sql = "UPDATE assets SET p_ava = '$p_ava' WHERE p_id = {$_POST['p_id']}"; 
        $conn->query($sql);
        } else {
            // below msg display on form submit failed
            $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert"> Fail </div>';
        }
    }
}
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>
<div class="jumbotron col-sm-6 mt-5 mx-3">
    <h3 class="text-center">Customer Bill</h3>
    <?php
    if (isset($_POST['issue'])) {
        $sql = "SELECT * FROM assets WHERE p_id = {$_POST['id']}";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
    }
    ?>
    <form action="" method="post">
        <div class="form-group">
            <label for="p_id">Product ID</label>
            <input type="text" name="p_id" id="p_id" class="form-control" value="<?php if (isset($row['p_id'])) {
                                                                                            echo $row['p_id'];
                                                                                        } ?>" readonly>
        </div>
        <div class="form-group">
            <label for="c_name">Customer Name</label>
            <input type="text" name="c_name" id="c_name" class="form-control">
        </div>
        <div class="form-group">
            <label for="c_add">Customer Address</label>
            <input type="text" name="c_add" id="c_add" class="form-control">
        </div>
        <div class="form-group">
            <label for="p_name">Product Name</label>
            <input type="text" name="p_name" id="p_name" class="form-control" value="<?php if (isset($row['p_name'])) {
                                                                                            echo $row['p_name'];
                                                                                        } ?>" r>
        </div>
        
        <div class="form-group">
            <label for="p_ava">Available</label>
            <input type="text" value="<?php if (isset($row['p_ava'])) {
                                                                                            echo $row['p_ava'];
                                                                                        } ?>" onkeypress="isInputNumber(event)" name="p_ava" id="p_ava" class="form-control" readonly>
        </div>
        <div class="form-group">
            <label for="p_quantity">Quantity</label>
            <input type="text" name="p_quantity" id="p_quantity" onkeypress="isInputNumber(event)" class="form-control">
        </div>
        <div class="form-group">
            <label for="p_sellingcost">Price Each</label>
            <input type="text" onkeypress="isInputNumber(event)" value="<?php if (isset($row['p_sellingcost'])) {
                                                                                            echo $row['p_sellingcost'];
                                                                                        } ?>" name="p_sellingcost" id="p_sellingcost" onkeypress="isInputNumber(event)" class="form-control" readonly>
        </div>
        <div class="form-group">
            <label for="total_cost">Total Price</label>
            <input type="text" name="total_cost" id="total_cost" onkeypress="isInputNumber(event)" class="form-control">
        </div>
        <div class="form-group">
            <label for="sell_date">Date</label>
            <input type="date" name="sell_date" id="sell_date" class="form-control">
        </div>       
        <div class="text-center">
            <button type="submit" class="btn btn-danger" id="billsubmit" name="billsubmit">Submit</button>
            <a href="assets.php" class="btn btn-secondary">Close</a>
        </div>
        <?php if (isset($msg)) {
            echo $msg;
        } ?>
    </form>
</div>
<!-- Only Number for input fields -->
<script>
    function isInputNumber(evt) {
        var ch = String.fromCharCode(evt.which);
        if (!(/[0-9]/.test(ch))) {
            evt.preventDefault();
        }
    }
</script>
<?php
require "includes/footer.php";
?>