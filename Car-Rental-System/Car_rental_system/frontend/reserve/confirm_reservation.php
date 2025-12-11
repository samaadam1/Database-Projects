<?php
session_start(); // Start the session

require '../../config.php';

// Get the query parameters
$carModel = $_GET['carModel'] ?? null;
$totalCost = $_GET['totalCost'] ?? null; // This can be calculated based on days
$startDate = $_GET['startDate'] ?? null;
$endDate = $_GET['endDate'] ?? null;

// Validate input
if (!$carModel || !$startDate || !$endDate) {
    die("Invalid reservation details.");
}

// Get the customerID from the session
$customerID = $_SESSION['customerID'] ?? null;

// Validate customerID
if (!$customerID) {
    die("CustomerID is not set in the session.");
}

// Retrieve customer details from the database
$query = "SELECT FirstName, LastName FROM Customer WHERE CustomerID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $customerID);
$stmt->execute();
$stmt->bind_result($firstName, $lastName);
$stmt->fetch();
$stmt->close();

// Retrieve the cost per day from the Car table
$query = "SELECT CostPerDay FROM Car WHERE Model = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $carModel);
$stmt->execute();
$stmt->bind_result($costPerDay);
$stmt->fetch();
$stmt->close();

// Calculate the total cost based on the number of days
$startDateObj = new DateTime($startDate);
$endDateObj = new DateTime($endDate);
$interval = $startDateObj->diff($endDateObj);
$days = $interval->days ;
$totalCost = $costPerDay * $days; // Calculate total cost

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Reservation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url("d2.jpeg"); 
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center top;
            position: relative;
            color: #333;
            margin: 0;
            padding: 0;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(255, 255, 255, 0.3);
            z-index: -1;
            filter: blur(80px);
        }

        h2 {
            text-align: center;
            background: linear-gradient(to right, #4caf50, #00aaff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 2.5em;
            margin-top: 30px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .reservation-details {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #000; /* Black background for details */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        p {
            background: #fff; /* Opaque white background */
            color: #333; /* Dark text for contrast */
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #fff; /* White text for labels */
        }

        input, button {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc; /* Light border for input fields */
            border-radius: 4px; /* Rounded corners */
        }

        button {
            background-color: #4CAF50; /* Green button */
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s; /* Smooth transition */
        }

        button:hover {
            background-color: #45a049; /* Darker green on hover */
        }
    </style>
</head>
<body>
    <h2>Confirm Reservation</h2>
    <div class="reservation-details">
        <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($firstName . ' ' . $lastName, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>Customer ID:</strong> <?php echo htmlspecialchars($customerID, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>Car Model:</strong> <?php echo htmlspecialchars($carModel, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>Start Date:</strong> <?php echo htmlspecialchars($startDate, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>End Date:</strong> <?php echo htmlspecialchars($endDate, ENT_QUOTES, 'UTF-8'); ?></p>
        <p><strong>Total Cost:</strong> $<?php echo htmlspecialchars($totalCost, ENT_QUOTES, 'UTF-8'); ?> (<?php echo htmlspecialchars($costPerDay, ENT_QUOTES, 'UTF-8'); ?> x <?php echo $days; ?> days = $<?php echo htmlspecialchars($totalCost, ENT_QUOTES, 'UTF-8'); ?>)</p>

        <form action="payment.php" method="POST">
            <input type="hidden" name="amount" value="<?php echo htmlspecialchars($totalCost, ENT_QUOTES, 'UTF-8'); ?>">

            <label for="cardID">Card ID:</label>
            <input type="text" id="cardID" name="cardID" required>

            <button type="submit">Submit Payment</button>
        </form>
    </div>
</body>
</html>
