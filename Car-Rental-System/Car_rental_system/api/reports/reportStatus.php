
<?php
require '../config.php'; 

header('Content-Type: application/json');

//http://localhost:8080/car_rental_system/api/reports/reportStatus.php?day=2024-12-22

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $day = isset($_GET['day']) ? $_GET['day'] : null;


    if (!$day) {
        echo json_encode(["message" => "Date is required"]);
        exit;
    }

    $sql = "
        SELECT c.CarID , case WHEN 
                                    r.CarID is not null then 'Rented' 
                                    else c.Status 
                                    end as CarStatus
        FROM Car c
        Left JOIN reservation r on c.CarID = r.CarID
        and r.StartDate <= '$day'  and r.EndDate >= '$day'
        order by c.CarID ;
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
