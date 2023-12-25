<?php
include ('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve user inputs from the registration form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate password match
    if ($password !== $confirm_password) {
        header('Location: index.php?error=1');
        exit ();
    }

    // To prevent SQL injection
    $username = stripcslashes($username);
    $password = stripcslashes($password);
    $confirm_password = stripcslashes($confirm_password);
    $username = mysqli_real_escape_string($con, $username);
    $password = mysqli_real_escape_string($con, $password);
    $confirm_password = mysqli_real_escape_string($con, $confirm_password);
    $password_hash = md5($password);

    // Insert the user into the database (replace 'users' with your actual table name)
    $sql = "INSERT INTO login (username, password_hash, role) VALUES ('$username', '$password_hash', 0)";
    $result = mysqli_query($con, $sql);

    if ($result) {
        // Registration successful, redirect to the login page
        header('Location: index.php');
        exit ();
    } else {
        // Registration failed, redirect back to the registration page with an error message
        header('Location: index.php?error=2');
        exit ();
    }
} else {
    // If the form is not submitted, redirect to the registration page
    header('Location: index.php');
    exit ();
}
?>
