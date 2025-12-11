<?php
$startDate = $_GET['startDate'];
$endDate = $_GET['endDate'];

// Fetch available cars from the database
$conn = new mysqli("localhost", "root", "", "CarRentalSystem");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT CarID, Model FROM Car WHERE Status != 'Out of Service' AND CarID NOT IN (
            SELECT CarID FROM Reservation WHERE (StartDate <= ? AND EndDate >= ?) OR (StartDate <= ? AND EndDate >= ?)
        )";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $endDate, $startDate, $startDate, $endDate);
$stmt->execute();
$result = $stmt->get_result();
$options = "";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $options .= "<option value='" . $row['CarID'] . "'>" . htmlspecialchars($row['Model'], ENT_QUOTES, 'UTF-8') . "</option>";
    }
} else {
    $options .= "<option value=''>No available cars for the selected dates</option>";
}
$stmt->close();
$conn->close();
echo $options;
?>
