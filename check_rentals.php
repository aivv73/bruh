<?php
// Include your database connection code
include('connection.php');

// Get the current date and time
$currentDateTime = date('Y-m-d H:i:s');

// Query to retrieve active rentals
$sql = "SELECT * FROM rentals WHERE status = 'active'";
$result = mysqli_query($con, $sql);

if ($result) {
    echo "Rentals checked successfully";

    // Loop through the results
    while ($row = mysqli_fetch_assoc($result)) {
        // Check if the drop-off date and time have passed
        if ($row['dropoff_datetime'] <= $currentDateTime) {
            echo   "Rental expired: " . $row['id'];

            // Update rental status to 'completed' or 'expired'
            $rentalId = $row['id'];
            $updateSql = "UPDATE rentals SET status = 'expired' WHERE id = $rentalId";
            mysqli_query($con, $updateSql);
        }
    }
} else {
    echo "Error: " . mysqli_error($con);
}

// Close the database connection
mysqli_close($con);
?>
