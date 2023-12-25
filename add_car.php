<?php
// Include your database connection code
include('connection.php');

session_start();
$role = $_SESSION['role'];
if (isset($_SESSION['username']) and $role == 1) {
    $isAdmin = true; // Change this based on your authentication logic
}

if (!$isAdmin) {
    // Redirect or show an error message if the user is not an admin
    header("Location: index.php");
    exit();
}

// Check if the form is submitted for adding a new car
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addCar'])) {
    // Retrieve form inputs
    $carName = mysqli_real_escape_string($con, $_POST['car_name']);
    $rentalRate = mysqli_real_escape_string($con, $_POST['rental_rate']);
    $availableCars = mysqli_real_escape_string($con, $_POST['available_cars']);

    // Insert the new car into the cars table
    $insertQuery = "INSERT INTO cars (car_name, rental_rate, available_count) VALUES ('$carName', '$rentalRate', $availableCars)";

    if (mysqli_query($con, $insertQuery)) {
        echo "Car '$carName' successfully added!";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}

// Check if the form is submitted for deleting an existing car
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteCar'])) {
    $carToDelete = mysqli_real_escape_string($con, $_POST['carToDelete']);

    // Perform deletion
    $deleteQuery = "DELETE FROM cars WHERE car_name = '$carToDelete'";
    
    if (mysqli_query($con, $deleteQuery)) {
        echo "Car '$carToDelete' deleted successfully!";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}

// Fetch data from the database for the delete form
$sql = "SELECT car_name FROM cars";
$result = mysqli_query($con, $sql);

// Close the database connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add or Delete Car</title>
    <!-- Add Bootstrap CSS link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
            <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <div class="col-md-3 mb-2 mb-md-0">
            </div>
    <div class="col-md-3 text-end">
        <a  href="index.php" class="btn btn-primary me-2">Back</a>
    </div>
    <div class="container mt-4">
        <h1>Add New Car</h1>

        <form action="" method="post">
            <label for="car_name">Car Name:</label>
            <input type="text" id="car_name" name="car_name" required>

            <label for="rental_rate">Rental Rate:</label>
            <input type="number" id="rental_rate" name="rental_rate" required>

            <label for="rental_rate">Available Cars:</label>
            <input type="number" id="available_cars" name="available_cars" required>

            <button type="submit" name="addCar" class="btn btn-success">Add Car</button>
        </form>
    </div>

    <div class="container mt-4">
        <h2>Delete Existing Car</h2>
        <form action="" method="post">
            <label for="carToDelete">Select Car to Delete:</label>
            <select id="carToDelete" name="carToDelete" required>
                <?php
                // Populate dropdown options for delete form
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<option value="' . $row['car_name'] . '">' . $row['car_name'] . '</option>';
                }
                ?>
            </select>

            <button type="submit" name="deleteCar" class="btn btn-danger">Delete Car</button>
        </form>
    </div>

    <!-- Add Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
