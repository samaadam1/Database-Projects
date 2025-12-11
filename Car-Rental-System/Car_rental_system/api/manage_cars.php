<?php
require 'config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $carID = isset($_GET['car_id']) ? $_GET['car_id'] : null;
    $status = isset($_GET['status']) ? $_GET['status'] : null;

    if (!$carID || !$status) {
        echo json_encode(['message' => "Both car_id and status are required."]);
        exit;
    }

    // Check carID exists
    $check = "SELECT COUNT(*) AS count FROM Car WHERE CarID = ? ;";
    $chStmt = $conn->prepare($check);
    $chStmt->bind_param("i", $carID);
    $chStmt->execute();
    $result = $chStmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] == 0) {
        echo json_encode(['message' => "CarID does not exist!"]);
    } else {
        // Update car status
        $query = "UPDATE Car SET Status = ? WHERE CarID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $status, $carID);

        if ($stmt->execute()) {
            echo json_encode(['message' => "Car status updated successfully!"]);
        } else {
            echo json_encode(['message' => "Error updating car status."]);
        }

        $stmt->close();
    }

    $chStmt->close();
    $conn->close();
}
?>
