<?php
// Include your database connection code
include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rental_id'])) {
    // Sanitize input to prevent SQL injection
    $rentalId = mysqli_real_escape_string($con, $_POST['rental_id']);

    // Perform the deletion
    $deleteQuery = "DELETE FROM rentals WHERE id = '$rentalId'";

    if (mysqli_query($con, $deleteQuery)) {
        echo "Rental successfully deleted!";
    } else {
        echo "Error: " . mysqli_error($con);
    }

    // Close the database connection
    mysqli_close($con);
} else {
    // Handle the case where the request method is not POST or rental_id is not set
    echo "Invalid request.";
}
?>
