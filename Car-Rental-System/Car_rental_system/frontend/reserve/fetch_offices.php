<?php
// Fetch office locations from the database
$conn = new mysqli("localhost", "root", "", "CarRentalSystem");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Select OfficeID and Location instead of OfficeName
$sql = "SELECT OfficeID, Location FROM Office";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['OfficeID'] . "'>" . htmlspecialchars($row['Location'], ENT_QUOTES, 'UTF-8') . "</option>";
        // Debugging statement
        echo "<!-- OfficeID: " . $row['OfficeID'] . ", Location: " . htmlspecialchars($row['Location'], ENT_QUOTES, 'UTF-8') . " -->";
    }
} else {
    echo "<!-- No offices found -->";
}
$conn->close();
?>

