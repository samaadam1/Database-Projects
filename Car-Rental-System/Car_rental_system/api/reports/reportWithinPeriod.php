<?php
require '../config.php';

header('Content-Type: application/json');
//r.StartDate <= '$start_date'  and r.EndDate >= '$end_date'
//http://localhost:8080/car_rental_system/api/reports/reportWithinPeriod.php?start_date=2024-01-01&end_date=2024-12-31

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;

    if (!$start_date || !$end_date) {
        echo json_encode(["message" => "Start Date and End Date are required"]);
        exit;
    }

    $sql = "
        SELECT r.ReservationID, r.ReservationDate,r.StartDate, r.EndDate,r.OfficeID, p.TotalCost ,ca.*, 
                cu.CustomerID, cu.FirstName, cu.LastName , cu.Email, cu.Phone, cu.Address
        FROM Reservation r
        join payment p on r.ReservationID = p.ReservationID 
        JOIN Customer cu ON r.CustomerID = cu.CustomerID
        JOIN Car ca ON r.CarID = ca.CarID
        WHERE r.ReservationDate BETWEEN  '$start_date' and '$end_date'
        ORDER BY r.ReservationDate 
    ";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $reservations = [];
        while ($row = $result->fetch_assoc()) {
            $reservations[] = $row;
        }
    }
    
    echo json_encode(['data' => $reservations]);
    $conn->close();
}

?>