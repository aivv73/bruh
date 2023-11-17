<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user inputs from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Replace this with your actual authentication logic
    // In a real-world scenario, you would check the user credentials against a database
    $validUsername = "example_user";
    $validPassword = "example_password";

    // Check if the provided credentials are valid
    if ($username === $validUsername && $password === $validPassword) {
        // Authentication successful, redirect to a dashboard or main page
        header("Location: dashboard.php");
        exit();
    } else {
        // Authentication failed, redirect back to the login page with an error message
        header("Location: login.html?error=1");
        exit();
    }
} else {
    // If the form is not submitted, redirect to the login page
    header("Location: login.html");
    exit();
}
?>
