<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['username'])) {
    // Retrieve form inputs
    $pickupLocation = $_POST["pickupLocation"];
    $pickupDateTime = strtotime($_POST["pickupDateTime"]);
    $pickupDateTime = date('Y-m-d H:i:s',  $pickupDateTime);
    $dropoffDateTime = strtotime($_POST["dropoffDateTime"]);
    $dropoffDateTime = date('Y-m-d H:i:s',  $dropoffDateTime);
    $selectedCar = $_POST["selectedCar"];

    // Include your database connection code
    include('connection.php');

    // Insert the rental information into the rentals table
    $insertQuery = "INSERT INTO rentals (username, pickup_location, pickup_datetime, dropoff_datetime, selected_car)
                    VALUES ('{$_SESSION['username']}', '$pickupLocation', '$pickupDateTime', '$dropoffDateTime', '$selectedCar')";

    if (mysqli_query($con, $insertQuery)) {
        echo "Rental information successfully submitted!";
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
