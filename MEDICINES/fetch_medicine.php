<?php
include '../connect.php';

header('Content-Type: application/json'); // Ensure the response is in JSON format

// Initialize response array
$response = [];

// Prepare SQL query to fetch all medicine records
$sql = "SELECT * FROM inv_meds";
$result = $conn->query($sql);

if ($result) {
    $medicines = [];
    while ($row = $result->fetch_assoc()) {
        // Format the expiration date for display
        $expirationDate = new DateTime($row["stock_exp"]);
        $formattedExpirationDate = $expirationDate->format('F j, Y'); // For display

        // Add formatted expiration date to the row
        $row["stock_exp"] = $formattedExpirationDate;
        $medicines[] = $row;
    }

    // Set success response with the medicine records
    $response['success'] = true;
    $response['data'] = $medicines; // Return all medicine records
} else {
    // Error fetching the records
    $response['success'] = false;
    $response['message'] = "Error fetching medicines list: " . $conn->error;
}

$conn->close();

// Return JSON response
echo json_encode($response);
?>
