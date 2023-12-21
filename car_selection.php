<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html"); // Redirect to login page if not logged in
    exit();
}

// Include your database connection code
include('connection.php');

// Fetch data from the database to display car choices
$sql = "SELECT car_name FROM cars";
$result = mysqli_query($con, $sql);

// Check for errors in the database query
if (!$result) {
    die("Database query failed: " . mysqli_error($con));
}

// Close the database connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Selection</title>
</head>
<body>
    
    <h1>Choose Your Car</h1>

    <form action="rental_process.php" method="post">
        <?php
        // Include hidden fields from the previous form
        if (isset($_POST['pickupLocation']) && isset($_POST['pickupDateTime']) && isset($_POST['dropoffDateTime'])) {
            echo '<input type="hidden" name="pickupLocation" value="' . $_POST['pickupLocation'] . '">';
            echo '<input type="hidden" name="pickupDateTime" value="' . $_POST['pickupDateTime'] . '">';
            echo '<input type="hidden" name="dropoffDateTime" value="' . $_POST['dropoffDateTime'] . '">';
            echo '<input type="hidden" name="differentDropoff" value="0">';
            echo '<input type="hidden" name="dropoffLocation" value="' . $_POST['pickupLocation'] . '">';
        }

        if (isset($_POST['differentDropoff']) && isset($_POST['dropoffLocation'])) {
            echo '<input type="hidden" name="differentDropoff" value="' . $_POST['differentDropoff'] . '">';
            echo '<input type="hidden" name="dropoffLocation" value="' . $_POST['dropoffLocation'] . '">';
        }
        ?>

        <label for="selectedCar">Select a Car:</label>
        <select id="selectedCar" name="selectedCar" required>
            <?php
            // Populate dropdown options
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<option value="' . $row['car_name'] . '">' . $row['car_name'] . '</option>';
            }
            ?>
        </select>

        <button type="submit">Next: Complete the Form</button>
    </form>
</body>
</html>
