<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
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
                echo "Аренда успешно удалена, а available_count обновлен!";
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
<a href="index.php" class="btn btn-primary">Вернуться на стартовую страницу</a>
