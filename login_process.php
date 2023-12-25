<?php
include ('connection.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve user inputs from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

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

    $sql = "SELECT role FROM login WHERE username = '$username'";
    $result = mysqli_query($con, $sql);

    $row = mysqli_fetch_assoc($result);

    $role = $row['role'];

    // Check if the provided credentials are valid
    if ($count == 1) {
        // Authentication successful, set session variable and redirect
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        header('Location: index.php');
        exit ();
    } else {
        // Handle unsuccessful login
        header('Location: index.html?error=1');
        exit ();
    }
} else {
    // If the form is not submitted, redirect to the login page
    header('Location: index.html');
    exit ();
}
?>