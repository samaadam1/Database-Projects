<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'] ?? null;
    $lastName = $_POST['lastName'] ?? null;
    $email = $_POST['email'] ?? null;
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
    $phone = $_POST['phone'] ?? null;
    $address = $_POST['address'] ?? null;

    // Check if any required fields are missing
    if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($phone) || empty($address)) {
        echo json_encode(["message" => "All fields are required to register."]);
        exit();
    }

    // Check for duplicate email or phone number
    $checkQuery = "SELECT Email, Phone FROM Customer WHERE Email = ? OR Phone = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ss", $email, $phone);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // If a duplicate is found, send a JSON response
        echo json_encode(["message" => "The email or phone number is already registered."]);
        $stmt->close();
        $conn->close();
        exit();
    }

    $stmt->close();

    // If no duplicates, insert the new user
    $insertQuery = "INSERT INTO Customer (FirstName, LastName, Email, Password, Phone, Address) 
                    VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ssssss", $firstName, $lastName, $email, $password, $phone, $address);

    if ($stmt->execute()) {
        // Success response
        //echo json_encode(["message" => "Registration successful."]);
        //header('Location: ../frontend/success.html');
        echo json_encode(["message" => "Registration successful.", "redirect" => "../frontend/success.html"]);
    } else {
        // Error response
        echo json_encode(["message" => "Error: " . $conn->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
