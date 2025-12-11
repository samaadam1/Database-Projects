<?php
require 'config.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;

    if (!$start_date || !$end_date) {
        http_response_code(400);
        echo json_encode(["error" => "Start date and end date are required."]);
        exit;
    }

    $query = "
        SELECT Reservation.*, Customer.FirstName, Customer.LastName, Car.Model, Car.PlateID
        FROM Reservation
        JOIN Customer ON Reservation.CustomerID = Customer.CustomerID
        JOIN Car ON Reservation.CarID = Car.CarID
        WHERE StartDate >= ? AND EndDate <= ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();

    $reservations = $result->fetch_all(MYSQLI_ASSOC);

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($reservations);
}

$conn->close();
?>
