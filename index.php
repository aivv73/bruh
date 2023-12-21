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

            <div class="col-md-3 text-end">
                <a  href="login.html" class="btn btn-outline-primary me-2">Login</a>
                <a  href="register.html" class="btn btn-primary">Sign-up</a>
            </div>
            </header>
        </div>
    <?php
        // Check if the user is logged in
        session_start();
        if (isset($_SESSION['username'])) {
            ?>
            <form action="car_selection.php" method="post">
                <!-- Your form fields here -->

                <div class="col-sm-6">
                <label for="pickupLocation">Pick-up Location:</label>
                <input type="text" class="form-control" id="pickupLocation" name="pickupLocation" required>
                <div class="invalid-feedback">
                    Valid Pick-up location is required.
                </div>
                </div>

                <!-- Additional input for different drop-off location -->
                <div class="col-sm-6" id="differentDropoffLocation">
                    <label for="dropoffLocation">Different Drop-off Location:</label>
                    <input type="text" class="form-control" id="dropoffLocation" name="dropoffLocation">
                </div>
                
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" id="differentDropoff" name="differentDropoff">
                    <label class="form-check-label" for="differentDropoff">
                        Specify Different Drop-off Location
                    </label>
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



                <label for="pickupDateTime">Pick-up Date and Time:</label>
                <input type="datetime-local" id="pickupDateTime" name="pickupDateTime" required>

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

                <label for="dropoffDateTime">Drop-off Date and Time:</label>
                <input type="datetime-local" id="dropoffDateTime" name="dropoffDateTime" required>

                <button class="w-100 btn btn-primary btn-lg" type="submit">Next: Choose a Car</button>
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
