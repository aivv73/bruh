<?php
include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user inputs from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // To prevent SQL injection
    $username = stripcslashes($username);
    $password = stripcslashes($password);
    $username = mysqli_real_escape_string($con, $username);
    $password = mysqli_real_escape_string($con, $password);
    $password_hash = md5($password);

    $sql = "SELECT * FROM login WHERE username = '$username' AND password_hash = '$password_hash'";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $count = mysqli_num_rows($result);

    // Check if the provided credentials are valid
    if ($count == 1) {
        // Authentication successful, redirect to a dashboard or main page
        header("Location: test.php");
        exit();
    } else {
        // Authentication failed, redirect back to the login page with an error message
        echo "<h1> Login failed. Invalid username or password.</h1>";  
        exit();
    }
} else {
    // If the form is not submitted, redirect to the login page
    header("Location: login.html");
    exit();
}
?>
