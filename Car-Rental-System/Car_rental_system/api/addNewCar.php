
<?php
require 'config.php'; 

 header('Content-Type: application/json');

//http://localhost:8080/Car_rental_system/api/addNewCar.php?model=hhh&year=2020&plateID=EG55&status=Active&officeID=2&costPerDay=200

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $model = isset($_GET['model']) ? $_GET['model'] : null;
    $year = isset($_GET['year']) ? $_GET['year'] : null;
    $plateID = isset($_GET['plateID']) ? $_GET['plateID'] : null;
    $status = isset($_GET['status']) ? $_GET['status'] : null;
    $costPerDay = isset($_GET['costPerDay']) ? $_GET['costPerDay'] : null;



    if (!$model || !$year || !$plateID || !$status  || !$costPerDay) {
        echo json_encode(["message" => "Error : All fields are required"]);
        exit;
    }

   $model = trim($conn->real_escape_string($model));
    $year = (int) $year;
    $plateID = trim($conn->real_escape_string($plateID));
    $status = trim($conn->real_escape_string($status));
    $costPerDay = (float) $costPerDay;

    $check = "Select count(*) as count 
              From Car 
              Where PlateID = '$plateID';  ";

    $result = $conn->query($check);
   // $result = $checkStmt->get_result();
    $row = $result->fetch_assoc();

    
    if ($row['count'] > 0) {

        echo json_encode(['message' => "Error: this plateID already exists."]);
        exit;
    }

    $sql = "
    INSERT INTO car ( Model, Year, PlateID, Status, CostPerDay)
    VALUES ('$model', '$year', '$plateID', '$status','$costPerDay');
    ";
    

    if ($conn->query($sql) === TRUE) {
        echo json_encode([ "message" => "Car added successfully!", "car_id" => $conn->insert_id]);
    } else {
        echo json_encode([ "message" => "Error adding car: " . $conn->error]);
    }

    $conn->close();
}

?>
