<?php
session_start(); // Start the session to get customerID

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $conn = new mysqli("localhost", "root", "", "CarRentalSystem");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve form data
    $carID = $_POST['carID'];
    $officeID = $_POST['officeID'];
    $customerID = $_SESSION['customerID']; // Get the customerID from the session
    $reservationDate = date('Y-m-d'); // Current date
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    // Calculate the number of reserved days
    $startDateTime = new DateTime($startDate);
    $endDateTime = new DateTime($endDate);
    $interval = $startDateTime->diff($endDateTime);
    $numberOfDays = $interval->days + 1; // Include the start date

    // Fetch the cost per day for the selected car
    $sql = "SELECT CostPerDay, Model FROM Car WHERE CarID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $carID);
    $stmt->execute();
    $stmt->bind_result($costPerDay, $carModel);
    $stmt->fetch();
    $stmt->close();

    // Calculate the total cost
    $totalCost = $costPerDay * $numberOfDays;

    // Store reservation details in session
    $_SESSION['carID'] = $carID;
    $_SESSION['officeID'] = $officeID;
    $_SESSION['reservationDate'] = $reservationDate;
    $_SESSION['startDate'] = $startDate;
    $_SESSION['endDate'] = $endDate;
    $_SESSION['totalCost'] = $totalCost;
    $_SESSION['carModel'] = $carModel;

    // Redirect to the confirmation page with reservation details
    header("Location: confirm_reservation.php?carModel=$carModel&totalCost=$totalCost&startDate=$startDate&endDate=$endDate");
    exit();
}
?>
