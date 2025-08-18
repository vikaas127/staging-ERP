<?php
    $apiUrl = "https://api.gst.gov.in/taxpayer/gstin/23DKLPD3493J1ZZ";

// Payload Data
$data = [
    "action" => "AUTHTOKEN",
    "username" => "AkashSingh01",
    "app_key" => "sCpuGRHVSPyY00iVjK7Wgh04LOXNRIuL8ymRHqDPnc70JubPPs4JQEX20Lc/gLeA/sIO2yKDaTW6qVv1XzGHm7Qx49H/TXvtATT88YsCTbiyniZZuHsgWhVQU7pExMv3/Cz8iC5IoRT/sRSCjuWERg9TPoxvyfO1zLzyDETpP+kGhZyGMa+1+XTNGa5Vk4BZiwS/oaezPK3jyYmr9FkH9uC9sF/WNhc4V85QOHCNtHM6YRF+WlYI4liqSw5as1Y+5cXQv9Sa3SpTuo6iX5gNnjC+4qk+WCLpOnhM/fqUoQsQtezoYtNkA47lpMUqxbUwd8d8yi8yI0XLEEe6NCHPRA==",
    "otp" => "BmgX0cZuroBbOHk7KT4Wzw=="
];

// Convert data to JSON
$jsonData = json_encode($data);

// Initialize cURL session
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Accept: application/json"
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code == 200) {
    echo $response;
} else {
    echo json_encode(["status" => "error", "message" => "Error fetching authentication token"]);
}
?>
