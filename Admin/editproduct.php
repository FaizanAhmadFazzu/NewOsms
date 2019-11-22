<?php
define('TITLE', 'Add New Product');
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

$p_nameErr = $p_dopErr = $p_avaErr =  $p_totalErr = $p_originalcostErr = $p_sellingcostErr = "";
$p_name = $p_dop = $p_ava =  $p_total = $p_originalcost = $p_sellingcost = "";
if (isset($_POST['pupdate'])) {
    // Validate product name
    if (empty($_POST["p_name"])) {
        $p_nameErr = "Product Name is required";
    } else {

        $p_name = test_input($_POST["p_name"]);
    }
    // Validate dop
    if (empty($_POST["p_dop"])) {
        $p_dopErr = "This field is required";
    } else {
        $p_dop = test_input($_POST["p_dop"]);
    }

    // Validate Product available input
    if (empty($_POST["p_ava"])) {
        $p_avaErr = "This field is required";
    } else {
        $p_ava = test_input($_POST["p_ava"]);
    }



    // Validate Product total input
    if (empty($_POST["p_total"])) {
        $p_totalErr = "This field is required";
    } else {
        $p_total = test_input($_POST["p_total"]);
    }

    // Validate Product originalcost input
    if (empty($_POST["p_originalcost"])) {
        $p_originalcostErr = "This field is required";
    } else {
        $p_originalcost = test_input($_POST["p_originalcost"]);
    }

    // Validate Product sellingcost input
    if (empty($_POST["p_sellingcost"])) {
        $p_sellingcostErr = "This field is required";
    } else {
        $p_sellingcost = test_input($_POST["p_sellingcost"]);
    }

    // Check input errors before inserting in database
    if (empty($p_nameErr) && empty($p_avaErr) && empty($p_dopErr) && empty($p_totalErr) && empty($p_originalcostErr) && empty($p_sellingcostErr)) {
        $sql = "UPDATE  assets SET p_name = '$p_name', p_dop ='$p_dop', p_ava = '$p_ava', p_total = '$p_total', p_originalcost = '$p_originalcost', p_sellingcost = '$p_sellingcost' WHERE p_id = {$_POST['p_id']}";
        if ($conn->query($sql) == TRUE) {
            // below msg display on form submit success
            $msg = '<div class="alert alert-success col-sm-6 ml-5 mt-2" role="alert"> Updated Successfully </div>';
        } else {
            // below msg display on form submit failed
            $msg = '<div class="alert alert-danger col-sm-6 ml-5 mt-2" role="alert"> Unable to Update </div>';
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
    <h3 class="text-center">Update Product Details</h3>
    <?php
    if (isset($_POST['view'])) {
        $sql = "SELECT * FROM assets WHERE p_id = {$_POST['id']}";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
    }
    ?>
    <form action="" method="post">
        <div class="form-group">
            <label for="p_id">Product ID</label>
            <input type="text" name="p_id" id="p_name" class="form-control" value="<?php if (isset($row['p_id'])) {
                                                                                            echo $row['p_id'];
                                                                                        } ?>" readonly>
        </div>
        <div class="form-group">
            <label for="p_name">Product Name</label>
            <input type="text" name="p_name" id="p_name" class="form-control" value="<?php if (isset($row['p_name'])) {
                                                                                            echo $row['p_name'];
                                                                                        } ?>">
            <span class="help-block"> <?php echo $p_nameErr; ?></span>
        </div>
        <div class="form-group">
            <label for="p_dop">Date of Purchase</label>
            <input type="date" name="p_dop" value="<?php if (isset($row['p_dop'])) {
                                                        echo $row['p_dop'];
                                                    } ?>" id="p_dop" class="form-control">
            <span class="help-block"> <?php echo $p_dopErr; ?></span>
        </div>
        <div class="form-group">
            <label for="p_ava">Available</label>
            <input type="text" value="<?php if (isset($row['p_ava'])) {
                                                                                            echo $row['p_ava'];
                                                                                        } ?>" onkeypress="isInputNumber(event)" name="p_ava" id="p_ava" class="form-control">
            <span class="help-block"> <?php echo $p_avaErr; ?></span>
        </div>
        <div class="form-group">
            <label for="p_total">Total</label>
            <input type="text" value="<?php if (isset($row['p_total'])) {
                                                                                            echo $row['p_total'];
                                                                                        } ?>" name="p_total" id="p_total" onkeypress="isInputNumber(event)" class="form-control">
            <span class="help-block"> <?php echo $p_totalErr; ?></span>
        </div>
        <div class="form-group">
            <label for="p_originalcost">Original Cost Each</label>
            <input type="text" value="<?php if (isset($row['p_originalcost'])) {
                                                                                            echo $row['p_originalcost'];
                                                                                        } ?>" name="p_originalcost" id="p_originalcost" onkeypress="isInputNumber(event)" class="form-control">
            <span class="help-block"> <?php echo $p_originalcostErr; ?></span>
        </div>
        <div class="form-group">
            <label for="p_sellingcost">Selling Cost Each</label>
            <input type="text" value="<?php if (isset($row['p_sellingcost'])) {
                                                                                            echo $row['p_sellingcost'];
                                                                                        } ?>" name="p_sellingcost" id="p_sellingcost" onkeypress="isInputNumber(event)" class="form-control">
            <span class="help-block"> <?php echo $p_sellingcostErr; ?></span>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-danger" id="pupdate" name="pupdate">Update</button>
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