
<?php
require '../config.php';
header('Content-Type: application/json');

//http://localhost:8080/car_rental_system/api/reports/reportPayments.php?start_date=2024-01-01&end_date=2024-12-31

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;

    if (!$start_date || !$end_date) {
        echo json_encode(["message" => "Start Date and End Date are required"]);
        exit;
    }

    $sql = "
        Select PaymentDate, TotalCost
        From payment
        where PaymentDate between '$start_date' and '$end_date'
        order by PaymentDate ;
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
