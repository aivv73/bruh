<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Service</title>
</head>
<body>
    <header>
        <h1>Welcome to Car Rental Service</h1>
        <a href="login.html">Login</a>
        <a href="register.html">Register</a>
    </header>
      
    <main>
        <?php
        // Check if the user is logged in
        session_start();
        if (isset($_SESSION['username'])) {
            ?>
            <form action="rental_process.php" method="post">
                <!-- Your form fields here -->
                <label for="pickupLocation">Pick-up Location:</label>
                <input type="text" id="pickupLocation" name="pickupLocation" required>

                <label for="pickupDateTime">Pick-up Date and Time:</label>
                <input type="datetime-local" id="pickupDateTime" name="pickupDateTime" required>

                <label for="dropoffDateTime">Drop-off Date and Time:</label>
                <input type="datetime-local" id="dropoffDateTime" name="dropoffDateTime" required>
                <label for="cars">Choose a car:</label>
                <select id="cars" name="selectedCar">
                    <?php
                    // Include your database connection code
                    include('connection.php');

                    // Fetch data from the database
                    $sql = "SELECT car_name FROM cars";
                    $result = mysqli_query($con, $sql);

                    // Populate dropdown options
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $row['car_name'] . '">' . $row['car_name'] . '</option>';
                    }

                    // Close the database connection
                    mysqli_close($con);
                    ?>
                </select>
                <button type="submit">Submit</button>
            </form>
            <?php
        } else {
            echo "<p>Please log in to access the car rental form.</p>";
        }
        ?>
    </main>


    <footer>
        <p>&copy; 2023 Car Rental Service</p>
    </footer>
</body>
</html>
