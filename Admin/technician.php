<?php
define('TITLE', 'Technician');
define('PAGE', 'technician');
require "includes/header.php";
require "../dbConnection.php";
// Initialize the session
session_start();

// Chaeck if the admin is  logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>
<div class="col-sm-9 col-md-10 mt-5 text-center">
    <!-- Table -->
    <p class="bg-dark text-white p-2">List of Technicians</p>
    <?php
    $sql = "SELECT * FROM technician_tb";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo '<table class="table table-striped table-inverse">
        <thead class="thead-inverse">
            <tr>
                <th>Emp ID</th>
                <th>Name</th>
                <th>City</th>
                <th>Mobile</th>
                <th>Email</th>
            </tr>
            </thead>
            <tbody>';
        while ($row = $result->fetch_assoc()) {
            echo '<tr>
        <td scope="row">' . $row['emp_id'] . '</td>
        <td>' . $row['emp_name'] . '</td>
        <td >' . $row['emp_city'] . '</td>
        <td >' . $row['emp_mobile'] . '</td>
        <td >' . $row['emp_email'] . '</td>
        <td><form action="editemp.php" method="post" class="d-inline">
        <input type="hidden" name="id" value=' . $row['emp_id'] . '>
        <button type="submit" class="btn btn-info mr-3" name="view" value="View"><i class="fas fa-pen"></i></button>
    </form>
    <form action="" method="post" class="d-inline">
        <input type="hidden" name="id" value=' . $row['emp_id'] . '>
        <button type="submit"  class="btn btn-secondary mr-3" name="delete" value="Delete"><i class="far fa-trash-alt"></i></button>
    </form>
    </td>
    </tr>';
        }
        echo '</tbody>
    </table>';
    } else {
        "0 Result";
    }
    if(isset($_POST['delete'])){
    $sql = "DELETE FROM technician_tb WHERE emp_id = {$_POST['id']}";
    if($conn->query($sql) == TRUE){
        // below code will refresh the page after deleting the record
        echo ' <meta http-equiv="refresh" content = "0;URL=? deleted" />';
    } else {
        echo "Unable to Delete";
    }
    }
    ?>
</div>
</div>
<div><a href="insertemp.php" class="btn btn-danger box"><i class="fas fa-plus fa-2x"></i></a>
</div>
</div>
<?php
require "includes/footer.php";
?>