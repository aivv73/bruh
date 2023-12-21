<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['username'])) {
    // Retrieve form inputs
    $pickupLocation = $_POST["pickupLocation"];
    $pickupDateTime = strtotime($_POST["pickupDateTime"]);
    $differentDropoff = $_POST["differentDropoff"];
    $dropoffLocation = $_POST["dropoffLocation"];

    $dropoffDateTime = strtotime($_POST["dropoffDateTime"]);
    $selectedCar = $_POST["selectedCar"];

    // Calculate the number of days the car will be rented
    $rentalDays = floor(($dropoffDateTime - $pickupDateTime) / (60 * 60 * 24));

    // Include your database connection code
    include('connection.php');

    // Insert the rental information into the rentals table
    $insertQuery = "INSERT INTO rentals (username, pickup_location, dropoff_location, pickup_datetime, dropoff_datetime, selected_car, different_dropoff, rental_days)
                    VALUES ('{$_SESSION['username']}', '$pickupLocation', '$dropoffLocation', FROM_UNIXTIME($pickupDateTime), FROM_UNIXTIME($dropoffDateTime), '$selectedCar', '$differentDropoff','$rentalDays')";

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
