<?php
header('Content-Type: application/json');

if (isset($_GET['payment_id'])) {
    $access_token = 'APP_USR-2809921708630207-060514-4a21a82278a2d11d7db0c1013297795f-460406015';
    $payment_id = $_GET['payment_id'];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.mercadopago.com/v1/payments/" . $payment_id);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Bearer " . $access_token]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $payment_info = json_decode($response, true);
    
    echo json_encode(['status' => $payment_info['status']]);
} else {
    echo json_encode(['error' => 'Payment ID not provided']);
}
?>