<?php
session_start(); // Start the session

// Database configuration
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "Carrentalsystem"; // Replace with your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the form data
$totalCost = $_POST['amount'] ?? null;
$cardID = $_POST['cardID'] ?? null;

// Validate input
if (!$totalCost || !$cardID) {
    die("All fields are required.");
}

// Insert reservation data into the database
$carID = $_SESSION['carID'] ?? null;
$customerID = $_SESSION['customerID'] ?? null;
$reservationDate = $_SESSION['reservationDate'] ?? null;
$startDate = $_SESSION['startDate'] ?? null;
$endDate = $_SESSION['endDate'] ?? null;
$officeID = $_SESSION['officeID'] ?? null;

if ($carID && $customerID && $reservationDate && $startDate && $endDate && $officeID) {
    $sql = "INSERT INTO Reservation (CarID, CustomerID, ReservationDate, StartDate, EndDate, OfficeID)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("iisssi", $carID, $customerID, $reservationDate, $startDate, $endDate, $officeID);
        if ($stmt->execute()) {
            // Get the reservation ID
            $reservationID = $stmt->insert_id;

            // Insert payment data into the database
            $paymentDate = date("Y-m-d"); // Current date
            $cardIDHash = hash("sha256", $cardID); // Hash the Card ID for security

            $sql = "INSERT INTO Payment (ReservationID, PaymentDate, CardID, TotalCost)
                    VALUES (?, ?, ?, ?)";
            $stmt2 = $conn->prepare($sql);

            if ($stmt2) {
                $stmt2->bind_param("issd", $reservationID, $paymentDate, $cardIDHash, $totalCost);
                if ($stmt2->execute()) {
                    $message = "Payment and reservation successfully recorded.";
                } else {
                    $message = "Payment recorded, but reservation failed: " . $stmt2->error;
                }
                $stmt2->close();
            } else {
                $message = "Payment recorded, but reservation failed: " . $conn->error;
            }
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Error: " . $conn->error;
    }
} else {
    $message = "Reservation details are missing.";
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url("../BS.jpg"); /* Background image */
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center top;
            position: relative;
            color: #333;
            margin: 0;
            padding: 0;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(255, 255, 255, 0.3);
            z-index: -1;
            filter: blur(80px);
        }

        h2 {
            text-align: center;
            background: linear-gradient(to right, #4caf50, #00aaff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 2.5em;
            margin-top: 30px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .message {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #000; /* Black background for details */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: #fff; /* White text */
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50; /* Green button */
            color: white;
            cursor: pointer;
            transition: background-color 0.3s; /* Smooth transition */
        }

        button:hover {
            background-color: #45a049; /* Darker green on hover */
        }
    </style>
</head>
<body>
    <h2>Payment Confirmation</h2>
    <div class="message">
        <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>

    <div class="button-container">
        <a href="../customerMenu.html"><button>Return to Menu</button></a> 
    </div>
</body>
</html>
