<?php
// Include your database connection code
include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rental_id'])) {
    // Sanitize input to prevent SQL injection
    $rentalId = mysqli_real_escape_string($con, $_POST['rental_id']);

    // Fetch the car_id for the rental being deleted
    $getCarIdQuery = "SELECT car_id FROM rentals WHERE id = '$rentalId'";
    $carIdResult = mysqli_query($con, $getCarIdQuery);

    if ($carIdResult) {
        $row = mysqli_fetch_assoc($carIdResult);
        $carId = $row['car_id'];

        // Perform the deletion
        $deleteQuery = "DELETE FROM rentals WHERE id = '$rentalId'";

        if (mysqli_query($con, $deleteQuery)) {
            // Update available_count in the cars table
            $updateCountQuery = "UPDATE cars SET available_count = available_count + 1 WHERE id = $carId";

            if (mysqli_query($con, $updateCountQuery)) {
                echo "Rental successfully deleted, and available_count updated!";
            } else {
                echo "Error updating available_count: " . mysqli_error($con);
            }
        } else {
            echo "Error deleting rental: " . mysqli_error($con);
        }

        // Close the result set
        mysqli_free_result($carIdResult);
    } else {
        echo "Error getting car ID: " . mysqli_error($con);
    }

    // Close the database connection
    mysqli_close($con);
} else {
    // Handle the case where the request method is not POST or rental_id is not set
    echo "Invalid request.";
}
?>
