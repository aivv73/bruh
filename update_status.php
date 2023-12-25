<?php
// Include your database connection code
include('connection.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form inputs
    $rentalID = mysqli_real_escape_string($con, $_POST['rental_id']);

    // Update the rental status to 'returned' in the rentals table
    $updateStatusQuery = "UPDATE rentals SET status = 'returned' WHERE id = $rentalID";

    if (mysqli_query($con, $updateStatusQuery)) {
        // Fetch the car_id for the returned rental
        $getCarIDQuery = "SELECT car_id FROM rentals WHERE id = $rentalID";
        $carIDResult = mysqli_query($con, $getCarIDQuery);

        if ($carIDResult) {
            $row = mysqli_fetch_assoc($carIDResult);
            $carID = $row['car_id'];

            // Update available_count in the cars table
            $updateCountQuery = "UPDATE cars SET available_count = available_count + 1 WHERE id = $carID";

            if (mysqli_query($con, $updateCountQuery)) {
                echo 'Rental marked as returned successfully!';
            } else {
                echo 'Error updating available_count: ' . mysqli_error($con);
            }

            // Close the result set
            mysqli_free_result($carIDResult);
        } else {
            echo 'Error getting car ID: ' . mysqli_error($con);
        }
    } else {
        echo 'Error updating rental status: ' . mysqli_error($con);
    }

    // Close the database connection
    mysqli_close($con);
}
?>
