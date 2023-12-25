<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['username'])) {
    // Retrieve form inputs
    $pickupLocation = $_POST['pickupLocation'];
    $pickupDateTime = strtotime($_POST['pickupDateTime']);
    $differentDropoff = $_POST['differentDropoff'];
    $dropoffLocation = $_POST['dropoffLocation'];

    $dropoffDateTime = strtotime($_POST['dropoffDateTime']);
    $selectedCarArray = explode('|', $_POST['selectedCar']);
    $selectedCar = $selectedCarArray[0];
    $rentalRate = $selectedCarArray[1];

    // Include your database connection code
    include ('connection.php');

    // Query to get the car_id based on the selected car_name
    $getCarIdQuery = "SELECT id FROM cars WHERE car_name = '$selectedCar'";
    $carIDResult = mysqli_query($con, $getCarIdQuery);

    // Check if the query was successful
    if ($carIDResult) {
        // Fetch the result as an associative array
        $row = mysqli_fetch_assoc($carIDResult);

        // Access the 'id' key from the associative array
        $carID = $row['id'];

        // Calculate the number of days the car will be rented
        $rentalDays = ceil(($dropoffDateTime - $pickupDateTime) / (60 * 60 * 24));

        $totalRentCost = $rentalDays * $rentalRate;

        // Insert the rental information into the rentals table
        $insertQuery = "INSERT INTO rentals (username, pickup_location, dropoff_location, pickup_datetime, dropoff_datetime, car_id, different_dropoff, rental_days)
                        VALUES ('{$_SESSION['username']}', '$pickupLocation', '$dropoffLocation', FROM_UNIXTIME($pickupDateTime), FROM_UNIXTIME($dropoffDateTime), $carID, '$differentDropoff', $rentalDays)";

        if (mysqli_query($con, $insertQuery)) {
            // Update available_count in the cars table
            $updateCountQuery = "UPDATE cars SET available_count = available_count - 1 WHERE id = $carID";

            if (mysqli_query($con, $updateCountQuery)) {
                echo 'Бронирование произведенно успешно!';
                echo 'Общая стоимость аренды: $' . $totalRentCost . '<br>';
            } else {
                echo 'Error updating available_count: ' . mysqli_error($con);
            }
        } else {
            echo 'Error inserting rental information: ' . mysqli_error($con);
        }

        // Close the result set
        mysqli_free_result($carIDResult);
    } else {
        // Display an error message if the query failed
        echo 'Error getting car ID: ' . mysqli_error($con);
    }


    // Close the database connection
    mysqli_close($con);
} else {
    // If the form is not submitted or user is not logged in, redirect to an error page or homepage
    header('Location: index.php');
    exit ();
}
?>

<a href="index.php" class="btn btn-primary">На стартовую страницу</a>
