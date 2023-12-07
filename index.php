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
