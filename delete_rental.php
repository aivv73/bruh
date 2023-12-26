<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<?php
// Include your database connection code
include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rental_id'])) {
    // Sanitize input to prevent SQL injection
    $rentalId = mysqli_real_escape_string($con, $_POST['rental_id']);

    // Perform the deletion
    $deleteQuery = "DELETE FROM rentals WHERE id = '$rentalId'";

    if (mysqli_query($con, $deleteQuery)) {
        echo "Аренда успешно удалена";
    } else {
        echo "Error deleting rental: " . mysqli_error($con);
    }
    
    // Close the database connection
    mysqli_close($con);
} else {
    // Handle the case where the request method is not POST or rental_id is not set
    echo "Invalid request.";
}
?>
<a href="index.php" class="btn btn-primary">Вернуться на стартовую страницу</a>
