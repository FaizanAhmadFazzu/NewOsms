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
$sql = "SELECT * FROM customer WHERE c_id={$_SESSION['c_id']}";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        echo '<div class="ml-5 mt-5">
        <table class="table table-striped table-inverse">
        <h3>Customer Bill</h3>
        <tbody>
            <tr>
                <th>Customer ID</th>
                <td>'. $row['c_id'].'</td>
            </tr>
            <tr>
                <th>Customer Name</th>
                <td>'. $row['c_name'].'</td>
            </tr>
            <tr>
                <th>Address</th>
                <td>'. $row['c_add'].'</td>
            </tr>
            <tr>
                <th>Product</th>
                <td>'. $row['p_name'].'</td>
            </tr>
            <tr>
                <th>Price Each</th>
                <td>'. $row['p_sellingcost'].'</td>
            </tr>
            <tr>
                <th>Total Cost</th>
                <td>'. $row['total_cost'].'</td>
            </tr>
            <tr>
                <th>Date</th>
                <td>'. $row['sell_date'].'</td>
            </tr>
            <tr>
                <td><form class="d-print-none">
  <input type="submit" name="" id="" class="btn btn-danger" placeholder="" value="Print" onClick="window.print()"></form></td>
  <td><a href="assets.php" class="btn btn-secondary d-print-none">Close</a></td>
</td>
            </tr>
            </tbody>  
    </table></div>';
    } else {
        "Failed";
    }
?>
<?php
require "includes/footer.php";
$conn->close();
?>
