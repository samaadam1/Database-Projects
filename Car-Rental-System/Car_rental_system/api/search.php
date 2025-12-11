<?php
require '../config.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check for search parameters
    $model = isset($_GET['model']) ? $_GET['model'] : null;
    $status = isset($_GET['status']) ? $_GET['status'] : null;
    $reservation_date = isset($_GET['reservation_date']) ? $_GET['reservation_date'] : null;
    $customer_email = isset($_GET['customer_email']) ? $_GET['customer_email'] : null;
    $car_plate = isset($_GET['car_plate']) ? $_GET['car_plate'] : null;

    if ($model || $status) {
        // Perform basic search
        basicSearch($conn, $model, $status);
    } elseif ($reservation_date || $customer_email || $car_plate) {
        // Perform advanced search
        advancedSearch($conn, $reservation_date, $customer_email, $car_plate);
    } else {
        // If no valid parameters are provided
        echo json_encode(["error" => "No valid search parameters provided."]);
    }
}

$conn->close();

// Basic search function
function basicSearch($conn, $model, $status) {
    $query = "SELECT * FROM Car WHERE 1=1";
    $params = [];
    $types = "";

    if ($model) {
        $query .= " AND Model LIKE ?";
        $params[] = "%$model%"; // Add wildcards for partial matching
        $types .= "s";
    }
    if ($status) {
        $query .= " AND Status = ?";
        $params[] = $status;
        $types .= "s";
    }

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }

    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $cars = $result->fetch_all(MYSQLI_ASSOC);
    header('Content-Type: application/json');
    if (count($cars) > 0) {
        echo json_encode($cars);
    } else {
        echo json_encode(["message" => "No cars found matching the criteria."]);
    }
}


// Advanced search function
function advancedSearch($conn, $reservation_date, $customer_email, $car_plate) {
    $query = "
        SELECT Reservation.*, Customer.FirstName, Customer.LastName, Car.Model, Car.PlateID
        FROM Reservation
        JOIN Customer ON Reservation.CustomerID = Customer.CustomerID
        JOIN Car ON Reservation.CarID = Car.CarID
        WHERE 1=1
    ";
    $params = [];
    $types = "";

    // Check if the entered reservation date matches the ReservationDate in the table
    if ($reservation_date) {
        $query .= " AND Reservation.ReservationDate = ?";
        $params[] = $reservation_date;
        $types .= "s";
    }
    if ($customer_email) {
        $query .= " AND Customer.Email = ?";
        $params[] = $customer_email;
        $types .= "s";
    }
    if ($car_plate) {
        $query .= " AND Car.PlateID = ?";
        $params[] = $car_plate;
        $types .= "s";
    }

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }

    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $search_results = $result->fetch_all(MYSQLI_ASSOC);
    header('Content-Type: application/json');
    if (count($search_results) > 0) {
        echo json_encode($search_results);
    } else {
        echo json_encode(["message" => "No results found for the given criteria."]);
    }
}

?>
