<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
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
                echo 'Аренда отмечена как успешно возвращенная!';
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
<a href="index.php" class="btn btn-primary">Вернуться на стартовую страницу</a>
