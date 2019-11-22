<?php
define('TITLE', 'Success');
require "includes/header.php";
// Initialize the session
session_start();

// check if the user is logged in, if not redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION["loggedin"] !== true) {
    echo "<script>location.href = 'Requesterlogin.php';</script>";
    exit;
}
// prepare a select statement
require "../dbConnection.php";
$sql = "SELECT * FROM  submit_request WHERE id = {$_SESSION['myid']}";
$result = $conn->query($sql);
if($result->num_rows == 1){
    $row = $result->fetch_assoc();
    echo "<div class='ml-5 mt-5'>
    <table class='table'>
    <tbody>
        <tr>
            <th>Request Id</th>
            <td>".$row['id']."</td>
        </tr>  
        <tr>
            <th>Request Id</th>
            <td>".$row['username']."</td>
        </tr>
        <tr>
            <th>Request Id</th>
            <td>".$row['email']."</td>
        </tr>
        <tr>
            <th>Request Id</th>
            <td>".$row['request']."</td>
        </tr>
        <tr>
            <th>Request Id</th>
            <td>".$row['request_desc']."</td>
        </tr> 
        <tr>
            <td><form class='d-print-none'><input type='submit' class='btn btn-danger' value='Print' onclick='window.print()'></form></td>
        </tr>           
    </tbody>
</table></div>";
} else {
    echo "Failed";
}
$conn->close();
?>
 


<?php
require "includes/footer.php";
?>