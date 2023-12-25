<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <header>
        <link href="headers.css" rel="stylesheet">
    </header>
    
    <main>
    <div class="container">
            <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <div class="col-md-3 mb-2 mb-md-0">
            </div>
<?php
session_start();
$role = $_SESSION['role'];
if (isset($_SESSION['username']) and $role == 0)
    echo '            <div class="col-md-3 text-end">
        <a href="logout.php" class="btn btn-danger">Sign Out</a>
    </div>';
else if (isset($_SESSION['username']) and $role == 1)
    echo '            <div class="col-md-3 text-end">
        <a  href="add_car.php" class="btn btn-outline-primary">Add New Car</a>
        <a href="logout.php" class="btn btn-danger">Sign Out</a>
    </div>';
else
    echo ' <div class="col-md-3 text-end"> <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal"> Login </button>
         <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#signupModal"> Sign Up </button>
        </div>'
    ?>

            </header>
        </div>
    <?php
        // Check if the user is logged in
        session_start();
        $role = $_SESSION['role'];

        include ('connection.php');

        $checkExpiredQuery = "SELECT * FROM rentals WHERE username = '{$_SESSION['username']}' AND status = 'expired'";
        $expiredResult = mysqli_query($con, $checkExpiredQuery);

        if ($expiredResult && mysqli_num_rows($expiredResult) > 0) {
            echo '<div class="alert alert-warning" role="alert">You have expired rentals. Please check your rental history.</div>';
        }

        if (isset($_SESSION['username']) and ($role == 0)) {
            ?>
            <form action="car_selection.php" method="post">
                <!-- Your form fields here -->

                <div class="container mt-5">
    <form action="rental_process.php" method="post">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="pickupLocation" class="form-label">Pick-up Location:</label>
                <input type="text" class="form-control" id="pickupLocation" name="pickupLocation" required>
                <div class="invalid-feedback">
                    Valid Pick-up location is required.
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="differentDropoff" class="form-check-label">Specify Different Drop-off Location</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" id="differentDropoff" name="differentDropoff">
                </div>
            </div>

            <div class="col-md-6 mb-3" id="differentDropoffLocation" style="display: none;">
                <label for="dropoffLocation" class="form-label">Different Drop-off Location:</label>
                <input type="text" class="form-control" id="dropoffLocation" name="dropoffLocation">
            </div>
        </div>


        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="pickupDateTime" class="form-label">Pick-up Date and Time:</label>
                <input type="datetime-local" class="form-control" id="pickupDateTime" name="pickupDateTime" required>
            </div>
            
            <script>
                // Get the current date and time in the format required by datetime-local input
                var currentDate = new Date().toISOString().slice(0, 16);

                // Set the min attribute of the pickupDateTime input to the current date and time
                document.getElementById('pickupDateTime').min = currentDate;

                // Add an event listener to pickupDateTime to update dropoffDateTime min attribute
                document.getElementById('pickupDateTime').addEventListener('input', function() {
                    // Get the selected date and time from pickupDateTime
                    var selectedDateTime = document.getElementById('pickupDateTime').value;

                    // Set the min attribute of dropoffDateTime to the selected date and time from pickupDateTime
                    document.getElementById('dropoffDateTime').min = selectedDateTime;
                });
            </script>

            <div class="col-md-6 mb-3">
                <label for="dropoffDateTime" class="form-label">Drop-off Date and Time:</label>
                <input type="datetime-local" class="form-control" id="dropoffDateTime" name="dropoffDateTime" required>
            </div>
        </div>

        <button class="btn btn-primary btn-lg" type="submit">Next: Choose a Car</button>
    </form>
</div>

<script>
    // Function to toggle visibility of different drop-off location input
    function toggleDifferentDropoffLocation() {
        var differentDropoffLocation = document.getElementById('differentDropoffLocation');
        var differentDropoffCheckbox = document.getElementById('differentDropoff');

        // Check the initial state of the checkbox
        differentDropoffLocation.style.display = differentDropoffCheckbox.checked ? 'block' : 'none';

        // Add an event listener to the checkbox to update the input visibility
        differentDropoffCheckbox.addEventListener('change', function () {
            differentDropoffLocation.style.display = this.checked ? 'block' : 'none';
        });
    }

    // Call the function when the page loads
    window.onload = toggleDifferentDropoffLocation;
</script>

            <?php
        } else if (!isset($_SESSION['username'])) {
            echo '<p>Please log in to access the car rental form.</p>';
        }
                    ?>
                <?php
                    // Include your database connection code
                    include ('connection.php');

                    // Check if the role is equal to 1.
                    if (isset($_SESSION['username']) and $role == 1) {
                        // The user is an administrator.
                        // Get the user's rental history from the database
                        $getRentalsQuery = 'SELECT * FROM rentals ORDER BY dropoff_datetime DESC';
                        $rentalsResult = mysqli_query($con, $getRentalsQuery);

                        // Check if the query was successful
                        if ($rentalsResult) {
                            // Fetch the results as an associative array
                            $rentals = mysqli_fetch_all($rentalsResult, MYSQLI_ASSOC);

                            // Join the rentals table with the cars table
                            $joinQuery = "SELECT rentals.*, cars.car_name, cars.rental_rate FROM rentals JOIN cars ON rentals.car_id = cars.id";
                            $joinResult = mysqli_query($con, $joinQuery);

                            // Check if the join query was successful
                            if ($joinResult) {
                                // Fetch the results as an associative array
                                $rentals = mysqli_fetch_all($joinResult, MYSQLI_ASSOC);
                                // Separate rentals based on status
                                $expiredRentals = [];
                                $activeRentals = [];
                                $returnedRentals = [];

                                foreach ($rentals as $rental) {
                                    switch ($rental['status']) {
                                        case 'expired':
                                            $expiredRentals[] = $rental;
                                            break;
                                        case 'active':
                                            $activeRentals[] = $rental;
                                            break;
                                        case 'returned':
                                            $returnedRentals[] = $rental;
                                            break;
                                    }
                                }

                                // Merge the arrays in the desired order
                                $sortedRentals = array_merge($expiredRentals, $activeRentals, $returnedRentals);

                                echo '<div class="container mt-4">';

                                foreach ($sortedRentals as $rental) {

                                    $total_rent_cost = $rental['rental_days'] * $rental['rental_rate'];
                                    $isExpired = ($rental['status'] === 'expired');
                                    $cardClass = $isExpired ? 'bg-warning' : '';
                                    $liClass = $isExpired ? 'bg-warning' : '';
                                    echo '<div class="card mt-3 ' . $cardClass . '">';
                                    echo '<div class="card-body ">';
                                    
                                    echo '<h5 class="card-title">Rental Details</h5>';
                                    echo '<ul class="list-group list-group-flush ">';
                                    echo "<li class='list-group-item " . $liClass . "'>Status: {$rental['status']}</li>";
                                    echo "<li class='list-group-item " . $liClass . "'>Username: {$rental['username']}</li>";
                                    echo "<li class='list-group-item " . $liClass . "'>Pickup location: {$rental['pickup_location']}</li>";
                                    echo "<li class='list-group-item " . $liClass . "'>Dropoff location: {$rental['dropoff_location']}</li>";
                                    echo "<li class='list-group-item " . $liClass . "'>Pickup date and time: {$rental['pickup_datetime']}</li>";
                                    echo "<li class='list-group-item " . $liClass . "'>Dropoff date and time: {$rental['dropoff_datetime']}</li>";
                                    echo "<li class='list-group-item " . $liClass . "'>Car name: {$rental['car_name']}</li>";
                                    echo "<li class='list-group-item " . $liClass . "'>Rental days: {$rental['rental_days']}</li>";
                                    echo "<li class='list-group-item " . $liClass . "'>Total rent cost: \${$total_rent_cost}</li>";
                                    echo '</ul>';

                                    if ($rental['status'] != 'returned') {
                                        // Button to mark the rental as returned
                                        echo '<form action="update_status.php" method="post" class="mt-3">';
                                        echo '<input type="hidden" name="rental_id" value="' . $rental['id'] . '">';
                                        echo '<button type="submit" class="btn btn-primary">Mark as Returned</button>';
                                        echo '</form>';
                                    }
                                    // Button to delete the rental
                                    echo '<button type="button" class="btn btn-danger mt-3" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete Rental</button>';
                                    // Delete Modal
                                    echo '<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">';
                                    echo '<div class="modal-dialog">';
                                    echo '<div class="modal-content">';
                                    echo '<div class="modal-header">';
                                    echo '<h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>';
                                    echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                                    echo '</div>';
                                    echo '<div class="modal-body">';
                                    echo '<p>Are you sure you want to delete this rental?</p>';
                                    echo '</div>';
                                    echo '<div class="modal-footer">';
                                    echo '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>';
                                    echo '<form action="delete_rental.php" method="post">';
                                    echo '<input type="hidden" name="rental_id" value="' . $rental['id'] . '">';
                                    echo '<button type="submit" class="btn btn-danger">Delete Rental</button>';
                                    echo '</form>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';

                                    echo '</div>';
                                    echo '</div>';
                                }
                                echo '</div>';
                            } else {
                                // Display an error message if the join query failed
                                echo 'Error: ' . mysqli_error($con);
                            }
                        } else {
                            // Display an error message if the query failed
                            echo 'Error: ' . mysqli_error($con);
                        }
                    } else if (isset($_SESSION['username'])) {
                        // The user is not an administrator.
                        // Get the user's rental history from the database
                        $getRentalsQuery = "SELECT * FROM rentals WHERE username = '{$_SESSION['username']} ' ORDER BY dropoff_datetime DESC";
                        $rentalsResult = mysqli_query($con, $getRentalsQuery);

                        // Check if the query was successful
                        if ($rentalsResult) {
                            // Fetch the results as an associative array
                            $rentals = mysqli_fetch_all($rentalsResult, MYSQLI_ASSOC);

                            // Join the rentals table with the cars table
                            $joinQuery = "SELECT rentals.*, cars.car_name, cars.rental_rate FROM rentals JOIN cars ON rentals.car_id = cars.id WHERE rentals.username = '{$_SESSION['username']}'";
                            $joinResult = mysqli_query($con, $joinQuery);

                            // Check if the join query was successful
                            if ($joinResult) {
                                // Fetch the results as an associative array
                                $rentals = mysqli_fetch_all($joinResult, MYSQLI_ASSOC);

                                echo '<div class="container mt-4">';
                                echo '<h3>Rental History:</h3>';

                                // Separate rentals based on status
                                $expiredRentals = [];
                                $activeRentals = [];
                                $returnedRentals = [];

                                foreach ($rentals as $rental) {
                                    switch ($rental['status']) {
                                        case 'expired':
                                            $expiredRentals[] = $rental;
                                            break;
                                        case 'active':
                                            $activeRentals[] = $rental;
                                            break;
                                        case 'returned':
                                            $returnedRentals[] = $rental;
                                            break;
                                    }
                                }

                                // Merge the arrays in the desired order
                                $sortedRentals = array_merge($expiredRentals, $activeRentals, $returnedRentals);

                                foreach ($sortedRentals as $rental) {
                                    $total_rent_cost = $rental['rental_days'] * $rental['rental_rate'];
                                    $isExpired = ($rental['status'] === 'expired');

                                    $alertClass = $isExpired ? 'alert-danger' : 'alert-primary';
                                
                                    echo '<div class="card mt-3">';
                                    echo '<div class="card-body">';

                                    echo '<h5 class="card-title">' . $alertMessage . '</h5>';
    
                                    // Display an alert for expired rentals
                                    if ($isExpired) {
                                        echo '<div class="alert ' . $alertClass . '" role="alert">';
                                        echo 'This rental has expired. Please return the car.';
                                        echo '</div>';
                                    }
                                    
                                    echo '<h5 class="card-title">Rental Details</h5>';
                                    echo '<ul class="list-group list-group-flush">';
                                    echo "<li class='list-group-item'>Status: {$rental['status']}</li>";
                                    echo "<li class='list-group-item'>Pickup location: {$rental['pickup_location']}</li>";
                                    echo "<li class='list-group-item'>Dropoff location: {$rental['dropoff_location']}</li>";
                                    echo "<li class='list-group-item'>Pickup date and time: {$rental['pickup_datetime']}</li>";
                                    echo "<li class='list-group-item'>Dropoff date and time: {$rental['dropoff_datetime']}</li>";
                                    echo "<li class='list-group-item'>Car name: {$rental['car_name']}</li>";
                                    echo "<li class='list-group-item'>Rental days: {$rental['rental_days']}</li>";
                                    echo "<li class='list-group-item'>Total rent cost: \${$total_rent_cost}</li>";
                                    echo '</ul>';

                                    echo '</div>';
                                    echo '</div>';
                                }
                                echo '</div>';
                            } else {
                                // Display an error message if the join query failed
                                echo 'Error: ' . mysqli_error($con);
                            }
                        } else {
                            // Display an error message if the query failed
                            echo 'Error: ' . mysqli_error($con);
                        }

                        // Close the database connection
                        mysqli_close($con);
                    }

                ?>

    </main>
    <div class="container">
        <footer class="py-3 my-4">
            <ul class="nav justify-content-center border-bottom pb-3 mb-3">
            </ul>
            <p class="text-center text-body-secondary">© 2023 Company, Inc</p>
        </footer>
    </div>
    
    <div class="modal fade modal-sheet p-4 py-md-5" tabindex="-1" role="dialog" id="loginModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-4 shadow">
        <div class="modal-header p-5 pb-4 border-bottom-0">
            <h1 class="fw-bold mb-0 fs-2">Login</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body p-5 pt-0">
            <form action="login_process.php" method="post">
            <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-3" id="username" name="username" placeholder="Username" required>
                <label for="floatingInput">Username</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control rounded-3"  id="password" name="password" placeholder="Password" required>
                <label for="floatingPassword">Password</label>
            </div>
            <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Login</button>
            </form>
        </div>
        </div>
    </div>
    </div>

    <div class="modal fade modal-sheet p-4 py-md-5" tabindex="-1" role="dialog" id="signupModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content rounded-4 shadow">
        <div class="modal-header p-5 pb-4 border-bottom-0">
            <h1 class="fw-bold mb-0 fs-2">Sign Up</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body p-5 pt-0">
            <form action="register_process.php" method="post">
            <div class="form-floating mb-3">
                <input type="text" class="form-control rounded-3" id="username" name="username" placeholder="Username" required>
                <label for="floatingInput">Username</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control rounded-3"  id="password" name="password" placeholder="Password" required>
                <label for="floatingPassword">Password</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control rounded-3"  id="confirm_password" name="confirm_password" placeholder="Password" required>
                <label for="floatingPassword">Confirm Password</label>
            </div>
            <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Sign Up</button>
            </form>
        </div>
        </div>
    </div>
    </div>


    </body>
</html>
