<?php
session_start();

// Read the JSON input from the request
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (isset($data["gstin"]) && isset($data["username"]) && isset($data["password"])) {
    $_SESSION["gst_data"] = $data; // Store data in PHP session

    echo json_encode(["success" => true, "message" => "Data saved successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid data received"]);
}
?>
