<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT AdminID, Password FROM admin WHERE Email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($adminID, $storedPassword);

    if ($stmt->fetch()) {
        if ($storedPassword === $password || password_verify($password, $storedPassword)) {
            $_SESSION['adminID'] = $adminID;
            header("Location: ../frontend/adminMenu.html");
            exit();
        } else {
            header("Location: ../frontend/adminLogin.html?message=Invalid email or password&type=error");
            exit();
        }
    } else {
        header("Location: ../frontend/adminLogin.html?message=Invalid email or password&type=error");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
