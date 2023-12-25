<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['username'])) {
    // Retrieve form input
    $rentalId = $_POST["rental_id"];

    // Include your database connection code
    include('connection.php');

    // Update the rental status to "returned"
    $updateStatusQuery = "UPDATE rentals SET status = 'returned' WHERE id = '$rentalId'";
    
    if (mysqli_query($con, $updateStatusQuery)) {
        echo "Rental status successfully updated!";
    } else {
        echo "Error: " . mysqli_error($con);
    }

    // Close the database connection
    mysqli_close($con);
} else {
    // If the form is not submitted or user is not logged in, redirect to an error page or homepage
    header("Location: index.php");
    exit();
}
?>
