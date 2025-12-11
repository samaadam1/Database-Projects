
<?php
require '../config.php'; 
header('Content-Type: application/json');

//http://localhost:8080/car_rental_system/api/reports/reportCustomer.php?ID=2

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $ID = isset($_GET['ID']) ? $_GET['ID'] : null;


    if (!$ID) {
        echo json_encode(["message" => "ID is required"]);
        exit;
    }

    $sql = "
       Select  r.ReservationID, r.ReservationDate,r.StartDate, r.EndDate,r.OfficeID, p.TotalCost , 
               cu.CustomerID, cu.FirstName,cu.LastName , cu.Email, cu.Phone, cu.Address,
               ca.CarID,ca.Model,ca.PlateID
       From reservation r
       join payment p on r.ReservationID = p.ReservationID
       join customer cu on r.CustomerID = cu.CustomerID
       join car ca on ca.CarID = r.CarID
       Where r.CustomerID = '$ID';
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
